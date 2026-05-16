<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Pengaturan Sistem</h2>
    </x-slot>

    <div class="space-y-6">
        <x-flash-message />

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Identitas Sekolah</h3>
            <p class="mt-1 text-sm text-slate-600">Data ini dipakai sebagai identitas dasar aplikasi dan laporan.</p>

            <form method="POST" action="{{ route('admin.system-settings.update') }}" class="mt-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="nama_sekolah" value="Nama Sekolah" />
                    <x-text-input id="nama_sekolah" name="nama_sekolah" type="text" class="mt-1 block w-full" :value="old('nama_sekolah', $setting->nama_sekolah)" required />
                    <x-input-error :messages="$errors->get('nama_sekolah')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="npsn" value="NPSN" />
                    <x-text-input id="npsn" name="npsn" type="text" class="mt-1 block w-full" :value="old('npsn', $setting->npsn)" />
                    <x-input-error :messages="$errors->get('npsn')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="alamat" value="Alamat" />
                    <textarea id="alamat" name="alamat" rows="4" class="mt-1 w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('alamat', $setting->alamat) }}</textarea>
                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <x-input-label for="telepon" value="Telepon" />
                        <x-text-input id="telepon" name="telepon" type="text" class="mt-1 block w-full" :value="old('telepon', $setting->telepon)" />
                        <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $setting->email)" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="website" value="Website" />
                        <x-text-input id="website" name="website" type="url" class="mt-1 block w-full" :value="old('website', $setting->website)" />
                        <x-input-error :messages="$errors->get('website')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="nama_kepala_sekolah" value="Nama Kepala Sekolah" />
                        <x-text-input id="nama_kepala_sekolah" name="nama_kepala_sekolah" type="text" class="mt-1 block w-full" :value="old('nama_kepala_sekolah', $setting->nama_kepala_sekolah)" />
                        <x-input-error :messages="$errors->get('nama_kepala_sekolah')" class="mt-2" />
                    </div>
                </div>

                <x-primary-button>Simpan Pengaturan</x-primary-button>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900">Ganti Password Admin</h3>
            <p class="mt-1 text-sm text-slate-600">Gunakan form bawaan profil untuk mengganti password akun yang sedang login.</p>

            <div class="mt-6 max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-app-layout>
