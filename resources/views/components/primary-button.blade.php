<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => '
            inline-flex
            min-h-10
            items-center
            justify-center
            gap-2
            rounded-lg
            border
            border-transparent
            bg-industrial-700
            px-4
            py-2
            text-xs
            font-semibold
            uppercase
            tracking-wider
            text-white
            shadow-sm
            transition-all
            duration-200
            ease-out

            hover:bg-industrial-800
            hover:shadow-app-primary

            focus:outline-none
            focus:ring-2
            focus:ring-industrial-500
            focus:ring-offset-2

            active:bg-industrial-900
            active:shadow-sm

            disabled:pointer-events-none
            disabled:opacity-50
        '
    ]) }}
>
    {{ $slot }}
</button>