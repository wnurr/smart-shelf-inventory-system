<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => '
            inline-flex
            min-h-10
            items-center
            justify-center
            gap-2
            rounded-lg
            border
            border-slate-300
            bg-white
            px-4
            py-2
            text-xs
            font-semibold
            uppercase
            tracking-wider
            text-slate-600
            shadow-sm
            transition-all
            duration-200
            ease-out

            hover:border-industrial-300
            hover:bg-industrial-50
            hover:text-industrial-800

            focus:outline-none
            focus:ring-2
            focus:ring-industrial-500
            focus:ring-offset-2

            active:bg-industrial-100

            disabled:pointer-events-none
            disabled:opacity-50
        '
    ]) }}
>
    {{ $slot }}
</button>