<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());

        $users = User::query()
            ->with('guru')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.users.create', [
            'user' => new User(),
            'guruOptions' => Guru::query()->whereNull('user_id')->orderBy('nama')->get(),
            'selectedGuruId' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'role' => $request->string('role')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        $this->syncGuruAssociation($user, $request->input('guru_id'));

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun user berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'guruOptions' => Guru::query()
                ->whereNull('user_id')
                ->orWhere('user_id', $user->id)
                ->orderBy('nama')
                ->get(),
            'selectedGuruId' => $user->guru?->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $payload = [
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'role' => $request->string('role')->toString(),
        ];

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($request->string('password')->toString());
        }

        $user->update($payload);
        $this->syncGuruAssociation($user, $request->input('guru_id'));

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun user berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->guru) {
            $user->guru->update(['user_id' => null]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun user berhasil dihapus.');
    }

    private function syncGuruAssociation(User $user, mixed $guruId): void
    {
        Guru::query()->where('user_id', $user->id)->update(['user_id' => null]);

        if ($user->role !== User::ROLE_GURU || empty($guruId)) {
            return;
        }

        Guru::query()->whereKey($guruId)->update(['user_id' => $user->id]);
    }
}
