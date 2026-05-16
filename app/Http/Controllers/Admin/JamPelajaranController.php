<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JamPelajaranRequest;
use App\Models\JamPelajaran;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JamPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());

        $jamPelajarans = JamPelajaran::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('urutan')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jam-pelajaran.index', compact('jamPelajarans', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.jam-pelajaran.create', [
            'jamPelajaran' => new JamPelajaran,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JamPelajaranRequest $request): RedirectResponse
    {
        JamPelajaran::query()->create($request->validated());

        return redirect()->route('admin.jam-pelajaran.index')
            ->with('success', 'Jam pelajaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JamPelajaran $jamPelajaran): View
    {
        return view('admin.jam-pelajaran.edit', compact('jamPelajaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JamPelajaranRequest $request, JamPelajaran $jamPelajaran): RedirectResponse
    {
        $jamPelajaran->update($request->validated());

        return redirect()->route('admin.jam-pelajaran.index')
            ->with('success', 'Jam pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JamPelajaran $jamPelajaran): RedirectResponse
    {
        try {
            $jamPelajaran->delete();
        } catch (QueryException $exception) {
            if (! $this->isForeignKeyConstraintViolation($exception)) {
                throw $exception;
            }

            return redirect()->route('admin.jam-pelajaran.index')
                ->with('error', 'Jam pelajaran tidak dapat dihapus karena masih digunakan pada jadwal pelajaran.');
        }

        return redirect()->route('admin.jam-pelajaran.index')
            ->with('success', 'Jam pelajaran berhasil dihapus.');
    }
}
