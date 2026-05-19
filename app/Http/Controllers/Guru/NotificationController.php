<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(15);

        return view('guru.notifications.index', compact('notifications'));
    }

    public function poll(Request $request): JsonResponse
    {
        $user = auth()->user();
        $lastChecked = $request->query('last_checked');

        $query = $user->unreadNotifications();

        if ($lastChecked) {
            $query->where('created_at', '>', $lastChecked);
        }

        $notifications = $query
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($notification) => $this->formatNotification($notification));

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
            'last_checked' => now()->toIso8601String(),
        ]);
    }

    public function recent(): JsonResponse
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn ($notification) => $this->formatNotification($notification));

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(string $id): JsonResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    public function markAllAsRead(): JsonResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    protected function formatNotification($notification): array
    {
        $data = $notification->data;

        return [
            'id' => $notification->id,
            'message' => $data['message'] ?? '',
            'mata_pelajaran' => $data['mata_pelajaran'] ?? '',
            'kelas' => $data['kelas'] ?? '',
            'jam_mulai' => $data['jam_mulai'] ?? '',
            'jam_selesai' => $data['jam_selesai'] ?? '',
            'hari' => $data['hari'] ?? '',
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at->toIso8601String(),
            'created_at_human' => $notification->created_at->diffForHumans(),
        ];
    }
}
