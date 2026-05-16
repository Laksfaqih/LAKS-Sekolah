@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="nama" value="Nama Bel" />
        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $pengaturanBel->nama)" required />
        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tipe_bel" value="Tipe Bel" />
        <select id="tipe_bel" name="tipe_bel" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
            <option value="">Pilih tipe bel</option>
            @foreach ($tipeOptions as $tipe)
                <option value="{{ $tipe }}" @selected(old('tipe_bel', $pengaturanBel->tipe_bel) === $tipe)>{{ str($tipe)->replace('_', ' ')->title() }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('tipe_bel')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jam_bunyi" value="Jam Bunyi" />
        <x-text-input id="jam_bunyi" name="jam_bunyi" type="time" class="mt-1 block w-full" :value="old('jam_bunyi', $pengaturanBel->jam_bunyi ? substr((string) $pengaturanBel->jam_bunyi, 0, 5) : '')" required />
        <x-input-error :messages="$errors->get('jam_bunyi')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="audio_file" value="File Audio" />
        <input id="audio_file" name="audio_file" type="file" accept="audio/*" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200">
        <x-input-error :messages="$errors->get('audio_file')" class="mt-2" />
        @if ($pengaturanBel->audio_path)
            <p class="mt-2 text-xs text-slate-500">File saat ini: {{ basename($pengaturanBel->audio_path) }}</p>
        @endif
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-500" @checked(old('is_active', $pengaturanBel->is_active))>
            Aktifkan jadwal bel ini
        </label>
        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('admin.pengaturan-bel.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</a>
</div>
