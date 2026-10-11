@props([
    'variant' => 'edit',
    'type' => 'button',
])

@php
    $classes = $variant === 'delete'
        ? '
            inline-flex
            min-h-8
            items-center
            justify-center
            gap-1.5
            rounded-lg
            border
            border-danger-100
            bg-danger-50
            px-2.5
            py-1.5
            text-xs
            font-semibold
            text-danger-700
            transition-all
            duration-200

            hover:border-danger-200
            hover:bg-danger-100
            hover:text-danger-800

            focus:outline-none
            focus:ring-2
            focus:ring-danger-500
            focus:ring-offset-1

            active:bg-danger-200

            disabled:pointer-events-none
            disabled:opacity-50
        '
        : '
            inline-flex
            min-h-8
            items-center
            justify-center
            gap-1.5
            rounded-lg
            border
            border-industrial-200
            bg-industrial-50
            px-2.5
            py-1.5
            text-xs
            font-semibold
            text-industrial-700
            transition-all
            duration-200

            hover:border-industrial-300
            hover:bg-industrial-100
            hover:text-industrial-800

            focus:outline-none
            focus:ring-2
            focus:ring-industrial-500
            focus:ring-offset-1

            active:bg-industrial-200

            disabled:pointer-events-none
            disabled:opacity-50
        ';
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($variant === 'edit')
        <svg
            class="h-3.5 w-3.5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
            />
        </svg>
    @endif

    {{ $slot }}
</button>