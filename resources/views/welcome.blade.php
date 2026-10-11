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

    {{-- Ambient shelf sensor pulse (same as the login page). Respects reduced motion. --}}
    <style>
        @keyframes shelf-sensor {
            0%   { transform: scale(1);   opacity: .55; }
            70%  { transform: scale(3.2); opacity: 0; }
            100% { transform: scale(3.2); opacity: 0; }
        }

        .shelf-sensor-ring {
            transform-box: fill-box;
            transform-origin: center;
            animation: shelf-sensor 2.8s ease-out infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .shelf-sensor-ring { animation: none; }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">

    <main
        x-data="{ ready: false }"
        x-init="setTimeout(() => ready = true, 50)"
        class="relative min-h-screen overflow-hidden"
    >

        {{-- ===================================================== --}}
        {{-- BACKGROUND (matches login) --}}
        {{-- ===================================================== --}}

        <div class="absolute inset-0 bg-gradient-to-br from-white via-slate-50 to-sky-50"></div>

        <div class="pointer-events-none absolute -right-24 -top-32 h-[560px] w-[560px] rounded-full bg-sky-200/50 blur-[110px]"></div>

        <div class="pointer-events-none absolute -bottom-48 -left-32 h-[520px] w-[520px] rounded-full bg-safety-300/30 blur-[120px]"></div>

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
        {{-- PAGE WRAPPER --}}
        {{-- ===================================================== --}}

        <div class="relative z-10 flex min-h-screen items-center px-6 py-12 sm:px-10 sm:py-14 lg:px-12 lg:py-16">

            <div class="mx-auto grid w-full max-w-[1200px] items-center gap-12 lg:grid-cols-[minmax(0,1fr)_460px] lg:gap-16">


                {{-- ================================================= --}}
                {{-- LEFT: INTRODUCTION (desktop only) --}}
                {{-- ================================================= --}}

                <section class="relative hidden lg:block">

                    {{-- Shelf rack illustration, sits behind the text on wide screens --}}
                    <svg
                        class="pointer-events-none absolute right-0 top-1/2 hidden h-[min(26rem,65vh)] w-auto -translate-y-1/2 text-slate-900 xl:block"
                        viewBox="0 0 320 520"
                        fill="none"
                        aria-hidden="true"
                    >
                        <rect x="12" y="20" width="6" height="500" rx="3" fill="currentColor" fill-opacity=".08" />
                        <rect x="302" y="20" width="6" height="500" rx="3" fill="currentColor" fill-opacity=".08" />

                        <rect x="12" y="150" width="296" height="6" rx="3" fill="currentColor" fill-opacity=".12" />
                        <rect x="12" y="270" width="296" height="6" rx="3" fill="currentColor" fill-opacity=".12" />
                        <rect x="12" y="390" width="296" height="6" rx="3" fill="currentColor" fill-opacity=".12" />
                        <rect x="12" y="510" width="296" height="6" rx="3" fill="currentColor" fill-opacity=".12" />

                        <rect x="34"  y="88"  width="70" height="62" rx="6" stroke="currentColor" stroke-opacity=".18" />
                        <rect x="114" y="106" width="52" height="44" rx="6" stroke="currentColor" stroke-opacity=".14" />
                        <rect x="176" y="72"  width="82" height="78" rx="6" stroke="currentColor" stroke-opacity=".18" />

                        <rect x="34"  y="204" width="58" height="66" rx="6" stroke="currentColor" stroke-opacity=".14" />
                        <rect x="102" y="192" width="90" height="78" rx="6" stroke="currentColor" stroke-opacity=".18" />
                        <rect x="202" y="222" width="60" height="48" rx="6" stroke="currentColor" stroke-opacity=".14" />

                        <rect x="34"  y="316" width="84" height="74" rx="6" stroke="currentColor" stroke-opacity=".18" />
                        <rect x="128" y="332" width="56" height="58" rx="6" stroke="currentColor" stroke-opacity=".14" />
                        <rect x="194" y="310" width="76" height="80" rx="6" class="stroke-success-500" stroke-opacity=".8" fill="currentColor" fill-opacity=".03" />

                        <circle cx="232" cy="350" r="4" class="fill-success-500 shelf-sensor-ring" />
                        <circle cx="232" cy="350" r="4" class="fill-success-500" />

                        <rect x="34"  y="444" width="66" height="66" rx="6" stroke="currentColor" stroke-opacity=".14" />
                        <rect x="110" y="430" width="96" height="80" rx="6" stroke="currentColor" stroke-opacity=".18" />
                        <rect x="216" y="458" width="50" height="52" rx="6" stroke="currentColor" stroke-opacity=".14" />
                    </svg>


                    <div
                        class="relative max-w-[26rem] transition duration-700 ease-out motion-reduce:transition-none"
                        :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    >

                        {{-- Brand --}}
                        <div class="flex items-center gap-3">

                            <div class="relative flex h-12 w-12 items-center justify-center rounded-xl bg-industrial-800 text-white shadow-md shadow-slate-900/15">

                                {{-- Heroicons: Cube --}}
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25M21 7.5v9l-9 5.25m0-9L3 7.5m9 5.25v9M3 7.5v9l9 5.25" />
                                </svg>

                                <span class="absolute -right-1 -top-1 h-3 w-3 rounded-full border-2 border-slate-50 bg-safety-400"></span>
                            </div>

                            <div>
                                <p class="text-base font-bold leading-tight tracking-tight text-slate-900">Smart Shelf</p>
                                <p class="text-xs font-medium text-slate-500">Inventory Management System</p>
                            </div>

                        </div>


                        {{-- Heading --}}
                        <h1 class="mt-10 text-4xl font-bold leading-[1.1] tracking-tight text-slate-900 xl:text-[44px]">
                            Smarter stock control for your storage shelves.
                        </h1>

                        <p class="mt-5 text-[15px] leading-7 text-slate-600">
                            Monitor inventory levels, manage shelf locations and sensors,
                            and identify low-stock items through one centralized inventory system.
                        </p>


                        {{-- Capabilities --}}
                        <ul class="mt-9 space-y-4">

                            <li class="flex items-center gap-3.5">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-industrial-700 shadow-sm">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Real-time</p>
                                    <p class="text-xs text-slate-500">Inventory monitoring</p>
                                </div>
                            </li>

                            <li class="flex items-center gap-3.5">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-industrial-700 shadow-sm">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.981-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">IoT Sensors</p>
                                    <p class="text-xs text-slate-500">Automated readings</p>
                                </div>
                            </li>

                            <li class="flex items-center gap-3.5">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-safety-200 bg-safety-50 text-safety-700 shadow-sm">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Alerts</p>
                                    <p class="text-xs text-slate-500">Low-stock awareness</p>
                                </div>
                            </li>

                        </ul>

                    </div>

                </section>


                {{-- ================================================= --}}
                {{-- RIGHT: ACCESS CARD --}}
                {{-- ================================================= --}}

                <section
                    class="
                        mx-auto w-full max-w-[460px] overflow-hidden rounded-2xl
                        border border-slate-200 bg-white
                        shadow-[0_20px_60px_rgba(15,23,42,0.10)]
                        transition duration-700 ease-out motion-reduce:transition-none
                        lg:mx-0
                    "
                    :class="ready ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    style="transition-delay: 100ms"
                >

                    {{-- Industrial to safety accent bar --}}
                    <div class="h-1.5 bg-gradient-to-r from-industrial-800 via-industrial-600 to-safety-500"></div>

                    <div class="px-6 py-7 sm:px-8 sm:py-8">


                        {{-- Mobile brand --}}
                        <div class="mb-7 flex items-center gap-3 lg:hidden">

                            <div class="relative flex h-11 w-11 items-center justify-center rounded-xl bg-industrial-800 text-white shadow-md shadow-slate-900/15">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25M21 7.5v9l-9 5.25m0-9L3 7.5m9 5.25v9M3 7.5v9l9 5.25" />
                                </svg>
                                <span class="absolute -right-1 -top-1 h-3 w-3 rounded-full border-2 border-white bg-safety-400"></span>
                            </div>

                            <div>
                                <p class="text-sm font-bold text-slate-900">Smart Shelf</p>
                                <p class="text-xs text-slate-500">Inventory Management System</p>
                            </div>

                        </div>


                        {{-- Heading --}}
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-[28px]">
                            Welcome back
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Select your account type to continue to the Smart Shelf Inventory System.
                        </p>


                        {{-- ============================================= --}}
                        {{-- ALREADY AUTHENTICATED --}}
                        {{-- ============================================= --}}

                        @auth

                            <div class="mt-6 rounded-xl border border-success-200 bg-success-50 p-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-success-100 text-success-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-success-800">You are already signed in</p>
                                        <p class="mt-0.5 text-xs text-success-700">Continue to your inventory dashboard.</p>
                                    </div>

                                    <a
                                        href="{{ route('dashboard') }}"
                                        class="
                                            inline-flex shrink-0 items-center gap-1 rounded-md text-xs font-bold text-success-700
                                            transition-colors hover:text-success-900
                                            focus:outline-none focus-visible:ring-2 focus-visible:ring-success-500 focus-visible:ring-offset-2
                                        "
                                    >
                                        Dashboard
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </a>

                                </div>

                            </div>

                        @endauth


                        {{-- ============================================= --}}
                        {{-- ACCESS OPTIONS --}}
                        {{-- ============================================= --}}

                        <div class="mt-6 space-y-3">


                            {{-- Administrator --}}
                            <a
                                href="{{ route('admin.login') }}"
                                class="
                                    group flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm
                                    transition duration-200
                                    hover:-translate-y-0.5 hover:border-industrial-300 hover:shadow-app-card-hover
                                    focus:outline-none focus-visible:ring-2 focus-visible:ring-industrial-500 focus-visible:ring-offset-2
                                "
                            >

                                <span
                                    class="
                                        flex h-12 w-12 shrink-0 items-center justify-center rounded-xl
                                        bg-industrial-50 text-industrial-700 ring-1 ring-industrial-100
                                        transition duration-200
                                        group-hover:bg-industrial-700 group-hover:text-white group-hover:ring-industrial-700
                                    "
                                >
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                    </svg>
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span class="flex flex-wrap items-center gap-2">
                                        <span class="text-[15px] font-bold text-slate-900">Administrator</span>
                                        <span class="rounded-full bg-industrial-50 px-2 py-0.5 text-[11px] font-semibold text-industrial-700">Admin</span>
                                    </span>
                                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                                        Manage staff accounts, roles, permissions and administration.
                                    </span>
                                </span>

                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition duration-200 group-hover:translate-x-0.5 group-hover:bg-industrial-50 group-hover:text-industrial-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </span>

                            </a>


                            {{-- Staff --}}
                            <a
                                href="{{ route('staff.login') }}"
                                class="
                                    group flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4 shadow-sm
                                    transition duration-200
                                    hover:-translate-y-0.5 hover:border-safety-300 hover:shadow-app-card-hover
                                    focus:outline-none focus-visible:ring-2 focus-visible:ring-safety-500 focus-visible:ring-offset-2
                                "
                            >

                                <span
                                    class="
                                        flex h-12 w-12 shrink-0 items-center justify-center rounded-xl
                                        bg-safety-50 text-safety-700 ring-1 ring-safety-100
                                        transition duration-200
                                        group-hover:bg-safety-500 group-hover:text-white group-hover:ring-safety-500
                                    "
                                >
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </span>

                                <span class="min-w-0 flex-1">
                                    <span class="flex flex-wrap items-center gap-2">
                                        <span class="text-[15px] font-bold text-slate-900">Staff</span>
                                        <span class="rounded-full bg-safety-50 px-2 py-0.5 text-[11px] font-semibold text-safety-700">Manager / Keeper</span>
                                    </span>
                                    <span class="mt-1 block text-xs leading-5 text-slate-500">
                                        Access inventory, shelf, stock and sensor functions assigned to your role.
                                    </span>
                                </span>

                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400 transition duration-200 group-hover:translate-x-0.5 group-hover:bg-safety-50 group-hover:text-safety-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </span>

                            </a>

                        </div>


                        {{-- Information --}}
                        <div class="mt-5 flex items-start gap-2.5 rounded-xl bg-slate-50 px-4 py-3">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                            <p class="text-xs leading-5 text-slate-500">
                                Staff accounts are created and managed by the system Administrator.
                            </p>
                        </div>

                    </div>

                </section>

            </div>

        </div>

    </main>

</body>

</html>