@csrf

<div x-data="{ role: '{{ old('role', $user->role ?: \App\Models\User::ROLE_GURU) }}' }" class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="name" value="Nama User" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="role" value="Role" />
        <select id="role" name="role" x-model="role" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
            <option value="{{ \App\Models\User::ROLE_ADMIN }}">Admin</option>
            <option value="{{ \App\Models\User::ROLE_GURU }}">Guru</option>
            <option value="{{ \App\Models\User::ROLE_KEPSEK }}">Kepsek</option>
        </select>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    <div x-show="role === '{{ \App\Models\User::ROLE_GURU }}'">
        <x-input-label for="guru_id" value="Data Guru" />
        <select id="guru_id" name="guru_id" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
            <option value="">Pilih guru</option>
            @foreach ($guruOptions as $guru)
                <option value="{{ $guru->id }}" @selected((string) old('guru_id', $selectedGuruId) === (string) $guru->id)>{{ $guru->nama }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('guru_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password" value="Password {{ $user->exists ? '(opsional)' : '' }}" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Batal</a>
</div>
