@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="nama" value="Nama Guru" />
        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $guru->nama)" required />
        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="nip" value="NIP" />
        <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full" :value="old('nip', $guru->nip)" />
        <x-input-error :messages="$errors->get('nip')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $guru->email)" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="no_hp" value="No. HP" />
        <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full" :value="old('no_hp', $guru->no_hp)" />
        <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="alamat" value="Alamat" />
        <textarea id="alamat" name="alamat" rows="4" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('alamat', $guru->alamat) }}</textarea>
        <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-3">
            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-500" @checked(old('is_active', $guru->is_active ?? true))>
            <span class="text-sm text-slate-700">Guru aktif</span>
        </label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('admin.gurus.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
        Batal
    </a>
</div>
