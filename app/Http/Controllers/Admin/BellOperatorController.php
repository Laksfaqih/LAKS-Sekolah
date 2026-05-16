<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BellTrigger;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class BellOperatorController extends Controller
{
    private const CACHE_KEY = 'bells:operator:active';

    private const LOCK_KEY = 'bells:operator:lock';

    private const OPERATOR_TTL_SECONDS = 30;

    public function status(Request $request): JsonResponse
    {
        $browserToken = $this->validatedBrowserToken($request);
        $lease = $this->currentLease();

        return response()->json($this->formatStatusPayload($browserToken, $lease));
    }

    public function activate(Request $request): JsonResponse
    {
        $browserToken = $this->validatedBrowserToken($request);
        $lease = $this->withOperatorLock(function () use ($request, $browserToken) {
            $lease = $this->currentLease();

            if ($lease && $lease['browser_token'] !== $browserToken) {
                return $lease;
            }

            $lease = $this->makeLeasePayload($request, $browserToken);
            $this->storeLease($lease);

            return $lease;
        });

        return response()->json($this->formatStatusPayload($browserToken, $lease));
    }

    public function heartbeat(Request $request): JsonResponse
    {
        $browserToken = $this->validatedBrowserToken($request);
        $lease = $this->withOperatorLock(function () use ($request, $browserToken) {
            $lease = $this->currentLease();

            if (! $lease || $lease['browser_token'] !== $browserToken) {
                return $lease;
            }

            $lease = $this->makeLeasePayload($request, $browserToken);
            $this->storeLease($lease);

            return $lease;
        });

        return response()->json($this->formatStatusPayload($browserToken, $lease));
    }

    public function deactivate(Request $request): JsonResponse
    {
        $browserToken = $this->validatedBrowserToken($request);

        $lease = $this->withOperatorLock(function () use ($browserToken) {
            $lease = $this->currentLease();

            if ($lease && $lease['browser_token'] === $browserToken) {
                Cache::forget(self::CACHE_KEY);

                return null;
            }

            return $lease;
        });

        return response()->json($this->formatStatusPayload($browserToken, $lease));
    }

    public function pending(Request $request): JsonResponse
    {
        $browserToken = $this->validatedBrowserToken($request);
        $lease = $this->currentLease();
        $status = $this->formatStatusPayload($browserToken, $lease);

        if (! $status['is_current_operator']) {
            return response()->json([
                ...$status,
                'trigger' => null,
            ]);
        }

        $trigger = BellTrigger::query()
            ->where('status', BellTrigger::STATUS_PENDING)
            ->whereNotNull('audio_path')
            ->orderBy('triggered_at')
            ->first();

        return response()->json([
            ...$status,
            'trigger' => $trigger ? [
                'id' => $trigger->id,
                'nama' => $trigger->nama,
                'tipe_bel' => $trigger->tipe_bel,
                'triggered_at' => $trigger->triggered_at?->toIso8601String(),
                'audio_url' => $trigger->audio_url,
            ] : null,
        ]);
    }

    public function acknowledge(Request $request, BellTrigger $bellTrigger): JsonResponse
    {
        $browserToken = $this->validatedBrowserToken($request);
        $lease = $this->currentLease();
        $status = $this->formatStatusPayload($browserToken, $lease);

        if (! $status['is_current_operator']) {
            return response()->json([
                ...$status,
                'updated' => false,
            ], 409);
        }

        $payload = $request->validate([
            'result' => ['required', 'in:played,failed'],
            'failure_reason' => ['nullable', 'string', 'max:255'],
        ]);

        if ($bellTrigger->status !== BellTrigger::STATUS_PENDING) {
            return response()->json([
                ...$status,
                'updated' => false,
            ]);
        }

        $bellTrigger->update([
            'status' => $payload['result'] === 'played' ? BellTrigger::STATUS_PLAYED : BellTrigger::STATUS_FAILED,
            'played_at' => now(),
            'played_by_browser' => $browserToken,
            'failure_reason' => $payload['result'] === 'failed'
                ? ($payload['failure_reason'] ?? 'Pemutaran audio gagal.')
                : null,
        ]);

        return response()->json([
            ...$status,
            'updated' => true,
        ]);
    }

    private function validatedBrowserToken(Request $request): string
    {
        return $request->validate([
            'browser_token' => ['required', 'string', 'max:100'],
        ])['browser_token'];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function currentLease(): ?array
    {
        $lease = Cache::get(self::CACHE_KEY);

        if (! is_array($lease)) {
            return null;
        }

        $expiresAt = Carbon::parse($lease['expires_at'] ?? null);

        if ($expiresAt->isPast()) {
            Cache::forget(self::CACHE_KEY);

            return null;
        }

        return $lease;
    }

    /**
     * @return array<string, mixed>
     */
    private function makeLeasePayload(Request $request, string $browserToken): array
    {
        return [
            'browser_token' => $browserToken,
            'user_id' => $request->user()->id,
            'expires_at' => now()->addSeconds(self::OPERATOR_TTL_SECONDS)->toIso8601String(),
        ];
    }

    /**
     * @param  array<string, mixed>  $lease
     */
    private function storeLease(array $lease): void
    {
        Cache::put(self::CACHE_KEY, $lease, now()->addSeconds(self::OPERATOR_TTL_SECONDS));
    }

    /**
     * @param  callable(): (array<string, mixed>|null)  $callback
     * @return array<string, mixed>|null
     */
    private function withOperatorLock(callable $callback): ?array
    {
        try {
            return Cache::lock(self::LOCK_KEY, 5)->block(2, $callback);
        } catch (LockTimeoutException) {
            return $this->currentLease();
        }
    }

    /**
     * @param  array<string, mixed>|null  $lease
     * @return array<string, mixed>
     */
    private function formatStatusPayload(string $browserToken, ?array $lease): array
    {
        if (! $lease) {
            return [
                'status' => 'inactive',
                'is_current_operator' => false,
            ];
        }

        if (($lease['browser_token'] ?? null) === $browserToken) {
            return [
                'status' => 'active',
                'is_current_operator' => true,
            ];
        }

        return [
            'status' => 'active_elsewhere',
            'is_current_operator' => false,
        ];
    }
}
