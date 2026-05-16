<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());

        $gurus = Guru::query()
            ->with(['jadwalPelajarans.mataPelajaran'])
            ->withCount('jadwalPelajarans')
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

        return view('kepsek.gurus.index', compact('gurus', 'search'));
    }
}
