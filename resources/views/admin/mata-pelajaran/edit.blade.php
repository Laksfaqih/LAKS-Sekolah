<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">Edit Mata Pelajaran</h2>
    </x-slot>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.mata-pelajaran.update', $mataPelajaran) }}">
            @method('PUT')
            @include('admin.mata-pelajaran._form')
        </form>
    </div>
</x-app-layout>
