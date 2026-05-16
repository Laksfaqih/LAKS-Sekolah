@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="hari" value="Hari" />
        <select id="hari" name="hari" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
            <option value="">Pilih hari</option>
            @foreach ($hariOptions as $hari)
                <option value="{{ $hari }}" @selected(old('hari', $jadwalPelajaran->hari) === $hari)>{{ $hari }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('hari')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jam_pelajaran_id" value="Jam Pelajaran" />
        <select id="jam_pelajaran_id" name="jam_pelajaran_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
            <option value="">Pilih jam</option>
            @foreach ($jamPelajaranOptions as $jam)
                <option value="{{ $jam->id }}" @selected((string) old('jam_pelajaran_id', $jadwalPelajaran->jam_pelajaran_id) === (string) $jam->id)>
                    {{ $jam->urutan }} - {{ $jam->jam_mulai }} s/d {{ $jam->jam_selesai }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('jam_pelajaran_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="guru_id" value="Guru Pengajar" />
        <select id="guru_id" name="guru_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
            <option value="">Pilih guru</option>
            @foreach ($guruOptions as $guru)
                <option value="{{ $guru->id }}" @selected((string) old('guru_id', $jadwalPelajaran->guru_id) === (string) $guru->id)>{{ $guru->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('guru_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="kelas_id" value="Kelas" />
        <select id="kelas_id" name="kelas_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
            <option value="">Pilih kelas</option>
            @foreach ($kelasOptions as $kelas)
                <option value="{{ $kelas->id }}" @selected((string) old('kelas_id', $jadwalPelajaran->kelas_id) === (string) $kelas->id)>{{ $kelas->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="mata_pelajaran_id" value="Mata Pelajaran" />
        <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
            <option value="">Pilih mata pelajaran</option>
            @foreach ($mataPelajaranOptions as $mataPelajaran)
                <option value="{{ $mataPelajaran->id }}" @selected((string) old('mata_pelajaran_id', $jadwalPelajaran->mata_pelajaran_id) === (string) $mataPelajaran->id)>{{ $mataPelajaran->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('mata_pelajaran_id')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('admin.jadwal-pelajaran.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</a>
</div>
