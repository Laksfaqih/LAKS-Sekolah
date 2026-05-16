<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Tambah Guru</h2>
    </x-slot>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.gurus.store') }}">
            @include('admin.gurus._form')
        </form>
    </div>
</x-app-layout>
