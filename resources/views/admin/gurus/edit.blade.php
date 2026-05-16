<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Edit Guru</h2>
    </x-slot>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.gurus.update', $guru) }}">
            @method('PUT')
            @include('admin.gurus._form')
        </form>
    </div>
</x-app-layout>
