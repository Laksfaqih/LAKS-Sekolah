@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="nama" value="Nama Jam" />
        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $jamPelajaran->nama)" />
        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="urutan" value="Urutan" />
        <x-text-input id="urutan" name="urutan" type="number" min="1" class="mt-1 block w-full" :value="old('urutan', $jamPelajaran->urutan)" required />
        <x-input-error :messages="$errors->get('urutan')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jam_mulai" value="Jam Mulai" />
        <x-text-input id="jam_mulai" name="jam_mulai" type="time" class="mt-1 block w-full" :value="old('jam_mulai', $jamPelajaran->jam_mulai)" required />
        <x-input-error :messages="$errors->get('jam_mulai')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jam_selesai" value="Jam Selesai" />
        <x-text-input id="jam_selesai" name="jam_selesai" type="time" class="mt-1 block w-full" :value="old('jam_selesai', $jamPelajaran->jam_selesai)" required />
        <x-input-error :messages="$errors->get('jam_selesai')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('admin.jam-pelajaran.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</a>
</div>
