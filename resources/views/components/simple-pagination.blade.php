@if ($paginator->hasPages())

    <div
        class="
            flex
            flex-col
            gap-4
            border-t
            border-slate-100
            px-5
            py-4
            sm:flex-row
            sm:items-center
            sm:justify-between
        "
    >

        {{-- =====================================================
             RESULT INFORMATION
             ===================================================== --}}

        <div class="text-sm text-slate-500">

            Showing

            <span class="font-semibold text-slate-700">
                {{ $paginator->firstItem() }}
            </span>

            <span class="text-slate-400">
                –
            </span>

            <span class="font-semibold text-slate-700">
                {{ $paginator->lastItem() }}
            </span>

            of

            <span class="font-semibold text-slate-700">
                {{ $paginator->total() }}
            </span>

        </div>


        {{-- =====================================================
             PAGE CONTROLS
             ===================================================== --}}

        <div class="flex items-center gap-2">

            {{-- Previous --}}

            @if ($paginator->onFirstPage())

                <span
                    class="
                        inline-flex
                        h-9
                        w-9
                        cursor-not-allowed
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-slate-200
                        bg-slate-50
                        text-slate-300
                    "
                    aria-disabled="true"
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
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </span>

            @else

                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="
                        inline-flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-slate-200
                        bg-white
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
                    "
                    aria-label="Previous page"
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
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </a>

            @endif


            {{-- Current Page --}}

            <div
                class="
                    inline-flex
                    h-9
                    items-center
                    justify-center
                    rounded-lg
                    border
                    border-industrial-200
                    bg-industrial-50
                    px-3
                    text-sm
                    font-semibold
                    text-industrial-700
                "
            >
                <span>{{ $paginator->currentPage() }}</span>

                <span class="mx-1.5 text-industrial-300">
                    /
                </span>

                <span>{{ $paginator->lastPage() }}</span>
            </div>


            {{-- Next --}}

            @if ($paginator->hasMorePages())

                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="
                        inline-flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-slate-200
                        bg-white
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
                    "
                    aria-label="Next page"
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
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </a>

            @else

                <span
                    class="
                        inline-flex
                        h-9
                        w-9
                        cursor-not-allowed
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-slate-200
                        bg-slate-50
                        text-slate-300
                    "
                    aria-disabled="true"
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
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </span>

            @endif

        </div>

    </div>

@endif