@props([
    'label',
    'value',
])

<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $value }}</p>
</div>
