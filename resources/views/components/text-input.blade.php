@props([
    'disabled' => false,
])

<input
    @disabled($disabled)

    {{ $attributes->merge([
        'class' => '
            block
            min-h-11
            w-full
            rounded-lg
            border-slate-300
            bg-white
            px-3
            py-2
            text-sm
            text-slate-800
            shadow-sm
            transition-all
            duration-200

            placeholder:text-slate-400

            hover:border-slate-400

            focus:border-industrial-500
            focus:ring-industrial-500

            disabled:cursor-not-allowed
            disabled:border-slate-200
            disabled:bg-slate-100
            disabled:text-slate-500
            disabled:shadow-none
        '
    ]) }}
>