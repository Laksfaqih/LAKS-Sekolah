@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="nama" value="Nama Kelas" />
        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $kelas->nama)" required />
        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tingkat" value="Tingkat" />
        <x-text-input id="tingkat" name="tingkat" type="text" class="mt-1 block w-full" :value="old('tingkat', $kelas->tingkat)" />
        <x-input-error :messages="$errors->get('tingkat')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jurusan" value="Jurusan" />
        <x-text-input id="jurusan" name="jurusan" type="text" class="mt-1 block w-full" :value="old('jurusan', $kelas->jurusan)" />
        <x-input-error :messages="$errors->get('jurusan')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="keterangan" value="Keterangan" />
        <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('keterangan', $kelas->keterangan) }}</textarea>
        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('admin.kelas.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</a>
</div>
