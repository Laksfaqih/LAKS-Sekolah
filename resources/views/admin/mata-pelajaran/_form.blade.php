@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="kode" value="Kode" />
        <x-text-input id="kode" name="kode" type="text" class="mt-1 block w-full" :value="old('kode', $mataPelajaran->kode)" />
        <x-input-error :messages="$errors->get('kode')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="nama" value="Nama Mata Pelajaran" />
        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $mataPelajaran->nama)" required />
        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="deskripsi" value="Deskripsi" />
        <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('deskripsi', $mataPelajaran->deskripsi) }}</textarea>
        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('admin.mata-pelajaran.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</a>
</div>
