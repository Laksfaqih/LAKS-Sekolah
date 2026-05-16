@props([
    'title',
    'description' => null,
    'actionLabel' => null,
    'actionHref' => null,
])

<div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 text-sm text-slate-600">{{ $description }}</p>
        @endif
    </div>

    @if ($actionLabel && $actionHref)
        <a href="{{ $actionHref }}" class="inline-flex rounded-xl bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
            {{ $actionLabel }}
        </a>
    @endif
</div>
