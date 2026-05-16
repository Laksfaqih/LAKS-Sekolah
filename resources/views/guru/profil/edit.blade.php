<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Profil Guru</h2>
    </x-slot>

    <div class="space-y-6">
        <x-flash-message />

        @if (! $guru)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm text-amber-800">
                Akun ini belum terhubung ke data guru.
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('guru.profil.update') }}" class="grid gap-5 md:grid-cols-2">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="nama" value="Nama" />
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
                        <x-primary-button>Simpan Profil</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Ganti Password</h3>
                <div class="mt-6 max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
