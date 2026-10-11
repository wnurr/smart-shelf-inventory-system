<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smart Shelf Inventory System</title>

    <link rel="icon" href="{{ asset('images/package.png') }}" type="image/png">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- One entrance (the card) and one ambient signal (the shelf sensor). Both respect reduced motion. --}}
    <style>
        @keyframes auth-card-in {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes shelf-sensor {
            0%   { transform: scale(1);   opacity: .55; }
            70%  { transform: scale(3.2); opacity: 0; }
            100% { transform: scale(3.2); opacity: 0; }
        }

        .auth-card-in { animation: auth-card-in .5s cubic-bezier(.2, .7, .2, 1) both; }
        .shelf-sensor-ring {
            transform-box: fill-box;
            transform-origin: center;
            animation: shelf-sensor 2.8s ease-out infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-card-in,
            .shelf-sensor-ring { animation: none; }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">

    <x-toast />

    <main class="relative min-h-screen overflow-hidden">

        {{-- ===================================================== --}}
        {{-- BACKGROUND (light) --}}
        {{-- ===================================================== --}}

        {{-- Light base with a faint cool tint --}}
        <div class="absolute inset-0 bg-gradient-to-br from-white via-slate-50 to-sky-50"></div>

        {{-- Soft sky light, top right --}}
        <div class="pointer-events-none absolute -right-24 -top-32 h-[560px] w-[560px] rounded-full bg-sky-200/50 blur-[110px]"></div>

        {{-- Safety-yellow warmth, bottom left --}}
        <div class="pointer-events-none absolute -bottom-48 -left-32 h-[520px] w-[520px] rounded-full bg-safety-300/30 blur-[120px]"></div>

        {{-- Technical grid, fades out toward the bottom --}}
        <div
            class="pointer-events-none absolute inset-0"
            style="
                background-image:
                    linear-gradient(rgba(15,23,42,.06) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(15,23,42,.06) 1px, transparent 1px);
                background-size: 56px 56px;
                mask-image: linear-gradient(to bottom, rgba(0,0,0,.9), transparent 90%);
                -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,.9), transparent 90%);
            "
        ></div>



        {{-- ===================================================== --}}
        {{-- PAGE CONTENT --}}
        {{-- ===================================================== --}}

        <div class="relative z-10 flex min-h-screen items-center justify-center px-6 py-12 sm:px-10 sm:py-14 lg:px-12 lg:py-16">

            <div class="flex w-full items-center justify-center lg:gap-16 xl:gap-24">

            <div class="w-full max-w-[440px]">

                {{-- ================================================= --}}
                {{-- BRAND --}}
                {{-- ================================================= --}}

                <a
                    href="{{ route('landing') }}"
                    class="
                        group mx-auto mb-5 flex w-fit items-center gap-3 rounded-xl
                        focus:outline-none
                        focus-visible:ring-2 focus-visible:ring-safety-500 focus-visible:ring-offset-2 focus-visible:ring-offset-slate-50
                    "
                >

                    {{-- Logo icon --}}
                    <div
                        class="
                            relative flex h-11 w-11 items-center justify-center rounded-xl
                            bg-industrial-800 text-white shadow-md shadow-slate-900/15
                            transition duration-200
                            group-hover:-translate-y-0.5 group-hover:bg-industrial-700
                        "
                    >

                        {{-- Heroicons: Cube --}}
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25M21 7.5v9l-9 5.25m0-9L3 7.5m9 5.25v9M3 7.5v9l9 5.25"
                            />
                        </svg>

                        {{-- Safety accent --}}
                        <span class="absolute -right-1 -top-1 h-3 w-3 rounded-full border-2 border-slate-50 bg-safety-400"></span>
                    </div>

                    {{-- Brand text --}}
                    <p class="text-sm font-semibold leading-tight tracking-tight text-slate-900">
                        Smart Shelf Inventory System
                    </p>

                </a>


                {{-- ================================================= --}}
                {{-- AUTH CARD --}}
                {{-- ================================================= --}}

                <div
                    class="
                        auth-card-in
                        overflow-hidden rounded-2xl
                        border border-slate-200 bg-white
                        shadow-[0_20px_60px_rgba(15,23,42,0.10)]
                    "
                >

                    {{-- Industrial to safety accent bar --}}
                    <div class="h-1.5 bg-gradient-to-r from-industrial-800 via-industrial-600 to-safety-500"></div>

                    <div class="px-6 py-7 sm:px-8 sm:py-8">
                        {{ $slot }}
                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- FOOTER --}}
                {{-- ================================================= --}}

                <p class="mt-4 text-center text-xs font-medium text-slate-500">
                    Smart Shelf Inventory Management System
                </p>

            </div>

            {{-- Shelf rack illustration: the one memorable element. One bin carries a live sensor. --}}
            <svg
                class="pointer-events-none hidden h-[min(32rem,72vh)] w-auto shrink-0 text-slate-900 lg:block"
                viewBox="0 0 320 520"
                fill="none"
                aria-hidden="true"
            >
                {{-- Uprights --}}
                <rect x="12" y="20" width="6" height="500" rx="3" fill="currentColor" fill-opacity=".08" />
                <rect x="302" y="20" width="6" height="500" rx="3" fill="currentColor" fill-opacity=".08" />

                {{-- Shelf rails --}}
                <rect x="12" y="150" width="296" height="6" rx="3" fill="currentColor" fill-opacity=".12" />
                <rect x="12" y="270" width="296" height="6" rx="3" fill="currentColor" fill-opacity=".12" />
                <rect x="12" y="390" width="296" height="6" rx="3" fill="currentColor" fill-opacity=".12" />
                <rect x="12" y="510" width="296" height="6" rx="3" fill="currentColor" fill-opacity=".12" />

                {{-- Top shelf bins --}}
                <rect x="34"  y="88" width="70" height="62" rx="6" stroke="currentColor" stroke-opacity=".18" />
                <rect x="114" y="106" width="52" height="44" rx="6" stroke="currentColor" stroke-opacity=".14" />
                <rect x="176" y="72" width="82" height="78" rx="6" stroke="currentColor" stroke-opacity=".18" />

                {{-- Second shelf bins --}}
                <rect x="34"  y="204" width="58" height="66" rx="6" stroke="currentColor" stroke-opacity=".14" />
                <rect x="102" y="192" width="90" height="78" rx="6" stroke="currentColor" stroke-opacity=".18" />
                <rect x="202" y="222" width="60" height="48" rx="6" stroke="currentColor" stroke-opacity=".14" />

                {{-- Third shelf bins, including the sensor bin --}}
                <rect x="34"  y="316" width="84" height="74" rx="6" stroke="currentColor" stroke-opacity=".18" />
                <rect x="128" y="332" width="56" height="58" rx="6" stroke="currentColor" stroke-opacity=".14" />
                <rect x="194" y="310" width="76" height="80" rx="6" class="stroke-success-500" stroke-opacity=".8" fill="currentColor" fill-opacity=".03" />

                {{-- Sensor --}}
                <circle cx="232" cy="350" r="4" class="fill-success-500 shelf-sensor-ring" />
                <circle cx="232" cy="350" r="4" class="fill-success-500" />

                {{-- Bottom shelf bins --}}
                <rect x="34"  y="444" width="66" height="66" rx="6" stroke="currentColor" stroke-opacity=".14" />
                <rect x="110" y="430" width="96" height="80" rx="6" stroke="currentColor" stroke-opacity=".18" />
                <rect x="216" y="458" width="50" height="52" rx="6" stroke="currentColor" stroke-opacity=".14" />
            </svg>

            </div>

        </div>

    </main>

</body>

</html>