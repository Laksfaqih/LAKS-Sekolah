<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuruRequest;
use App\Models\Guru;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());

        $gurus = Guru::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.gurus.index', compact('gurus', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.gurus.create', [
            'guru' => new Guru,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuruRequest $request): RedirectResponse
    {
        Guru::query()->create([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.gurus.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru): View
    {
        return view('admin.gurus.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GuruRequest $request, Guru $guru): RedirectResponse
    {
        $guru->update([
            ...$request->validated(),
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.gurus.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guru $guru): RedirectResponse
    {
        try {
            $guru->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyConstraintViolation($exception)) {
                throw $exception;
            }

            return redirect()->route('admin.gurus.index')
                ->with('error', 'Data guru tidak dapat dihapus karena masih digunakan pada jadwal atau presensi guru.');
        }

        return redirect()->route('admin.gurus.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
