@props([
    'action' => url()->current(),
    'search' => request('search', ''),
    'searchName' => 'search',
    'placeholder' => 'Search...',
    'buttonLabel' => 'Search',
    'clearUrl' => url()->current(),
    'showClear' => false,
])

<div
    {{ $attributes->merge([
        'class' => '
            rounded-xl
            border
            border-slate-200
            bg-white
            p-4
            shadow-sm
        '
    ]) }}
>
    <form
        method="GET"
        action="{{ $action }}"
        class="flex flex-col gap-3 md:flex-row md:items-center"
    >

        {{-- =====================================================
             SEARCH INPUT
             ===================================================== --}}

        <div class="relative flex-1">

            <div
                class="
                    pointer-events-none
                    absolute
                    inset-y-0
                    left-0
                    flex
                    items-center
                    pl-3
                "
            >
                <svg
                    class="h-4 w-4 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                    />
                </svg>
            </div>

            <input
                type="text"
                name="{{ $searchName }}"
                value="{{ $search }}"
                placeholder="{{ $placeholder }}"
                class="
                    h-10
                    w-full
                    rounded-lg
                    border-slate-300
                    bg-white
                    pl-10
                    pr-3
                    text-sm
                    text-slate-700
                    placeholder:text-slate-400

                    focus:border-industrial-500
                    focus:ring-industrial-500
                "
            >

        </div>


        {{-- =====================================================
             PAGE-SPECIFIC FILTERS
             ===================================================== --}}

        {{ $slot }}


        {{-- =====================================================
             SEARCH BUTTON
             ===================================================== --}}

        <button
            type="submit"
            class="
                inline-flex
                h-10
                items-center
                justify-center
                gap-2
                whitespace-nowrap
                rounded-lg
                border
                border-transparent
                bg-industrial-700
                px-4
                text-sm
                font-semibold
                text-white
                shadow-sm
                transition-all
                duration-200

                hover:bg-industrial-800
                hover:shadow-app-primary

                focus:outline-none
                focus:ring-2
                focus:ring-industrial-500
                focus:ring-offset-2

                active:bg-industrial-900
            "
        >
            <svg
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                />
            </svg>

            {{ $buttonLabel }}
        </button>


        {{-- =====================================================
             CLEAR BUTTON
             ===================================================== --}}

        @if($showClear)

            <a
                href="{{ $clearUrl }}"
                class="
                    inline-flex
                    h-10
                    items-center
                    justify-center
                    whitespace-nowrap
                    rounded-lg
                    border
                    border-slate-300
                    bg-white
                    px-4
                    text-sm
                    font-medium
                    text-slate-600
                    transition-all
                    duration-200

                    hover:border-industrial-300
                    hover:bg-industrial-50
                    hover:text-industrial-800

                    focus:outline-none
                    focus:ring-2
                    focus:ring-industrial-500
                    focus:ring-offset-2

                    active:bg-industrial-100
                "
            >
                Clear
            </a>

        @endif

    </form>
</div>