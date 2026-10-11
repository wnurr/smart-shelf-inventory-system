@props([
    'loop',
    'paginator' => null,
])

@php
    $number = $loop->iteration;

    if (
        $paginator instanceof \Illuminate\Contracts\Pagination\Paginator
        && $paginator->firstItem()
    ) {
        $number = $paginator->firstItem() + $loop->index;
    }
@endphp

<span
    {{ $attributes->merge([
        'class' => '
            font-medium
            tabular-nums
            text-slate-500
        '
    ]) }}
>
    {{ $number }}.
</span>