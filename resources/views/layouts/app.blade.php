<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Smart Shelf Inventory System</title>

    <link
        rel="icon"
        href="{{ asset('images/package.png') }}"
        type="image/png"
    >

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body
    class="
        overflow-hidden
        bg-industrial-50
        text-slate-800
        antialiased
    "
>

<x-toast />


@php

    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    */

    $sidebarUser = auth()->user();

    $sidebarInitials = collect(
        preg_split('/\s+/', trim($sidebarUser->name))
    )
        ->filter()
        ->take(2)
        ->map(
            fn ($part) =>
                mb_strtoupper(
                    mb_substr($part, 0, 1)
                )
        )
        ->implode('');


    /*
    |--------------------------------------------------------------------------
    | Navigation Classes
    |--------------------------------------------------------------------------
    */

    $navBase = '
        group
        relative
        flex
        min-h-11
        items-center
        gap-3
        rounded-xl
        px-3
        text-sm
        font-medium
        transition-all
        duration-200
    ';

    $navIdle = '
        text-slate-500

        hover:bg-industrial-50
        hover:text-industrial-800
    ';

    $navActive = '
        bg-industrial-100
        text-industrial-800
        font-semibold
        shadow-sm
    ';

@endphp


<div
    class="flex h-screen overflow-hidden"

    x-data="{
        sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',

        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;

            localStorage.setItem(
                'sidebarOpen',
                this.sidebarOpen
            );
        }
    }"
>

    {{-- ========================================================= --}}
    {{-- SIDEBAR --}}
    {{-- ========================================================= --}}

    <aside
        class="
            relative
            z-30
            flex
            h-screen
            shrink-0
            flex-col
            border-r
            border-slate-200
            bg-white
            shadow-sm
            transition-all
            duration-300
            ease-in-out
        "

        :class="
            sidebarOpen
                ? 'w-72'
                : 'w-[88px]'
        "
    >

        {{-- ===================================================== --}}
        {{-- BRAND --}}
        {{-- ===================================================== --}}

        <div
            class="
                relative
                overflow-hidden
                border-b
                border-industrial-700
                bg-gradient-to-br
                from-industrial-900
                via-industrial-800
                to-industrial-700
            "
        >

            {{-- Decorative industrial accent --}}

            <div
                class="
                    pointer-events-none
                    absolute
                    -right-10
                    -top-12
                    h-32
                    w-32
                    rounded-full
                    bg-white/5
                "
            ></div>

            <div
                class="
                    pointer-events-none
                    absolute
                    -bottom-10
                    left-12
                    h-24
                    w-24
                    rounded-full
                    bg-safety-500/10
                "
            ></div>


            <div
                class="
                    relative
                    flex
                    h-[88px]
                    items-center
                    px-5
                "

                :class="
                    sidebarOpen
                        ? 'justify-between'
                        : 'justify-center'
                "
            >

                {{-- Brand identity --}}

                <div
                    class="flex min-w-0 items-center gap-3"
                >

                    <div
                        class="
                            relative
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            border
                            border-white/15
                            bg-white/10
                            shadow-lg
                            backdrop-blur-sm
                        "
                    >

                        <svg
                            class="h-6 w-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="1.7"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            />
                        </svg>


                        {{-- Safety accent --}}

                        <span
                            class="
                                absolute
                                -right-1
                                -top-1
                                h-3
                                w-3
                                rounded-full
                                border-2
                                border-industrial-800
                                bg-safety-400
                            "
                        ></span>

                    </div>


                    <div
                        x-show="sidebarOpen"
                        x-cloak
                        x-transition.opacity
                        class="min-w-0"
                    >

                        <p
                            class="
                                truncate
                                text-[15px]
                                font-bold
                                tracking-tight
                                text-white
                            "
                        >
                            Smart Shelf
                        </p>

                        <p
                            class="
                                mt-0.5
                                truncate
                                text-[11px]
                                font-medium
                                tracking-wide
                                text-industrial-200
                            "
                        >
                            Inventory System
                        </p>

                    </div>

                </div>


                {{-- Collapse toggle --}}

                <button
                    type="button"

                    @click="toggleSidebar()"

                    x-show="sidebarOpen"
                    x-cloak

                    class="
                        flex
                        h-9
                        w-9
                        shrink-0
                        items-center
                        justify-center
                        rounded-lg
                        border
                        border-white/10
                        bg-white/5
                        text-industrial-100
                        transition-all
                        duration-200

                        hover:border-white/20
                        hover:bg-white/10
                        hover:text-white

                        focus:outline-none
                        focus:ring-2
                        focus:ring-safety-400
                        focus:ring-offset-2
                        focus:ring-offset-industrial-800
                    "

                    title="Collapse sidebar"
                    aria-label="Collapse sidebar"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M11 19l-7-7 7-7m8 14-7-7 7-7"
                        />
                    </svg>

                </button>


                {{-- Expand toggle when collapsed --}}

                <button
                    type="button"

                    @click="toggleSidebar()"

                    x-show="!sidebarOpen"
                    x-cloak

                    class="
                        absolute
                        bottom-2
                        right-1
                        flex
                        h-6
                        w-6
                        items-center
                        justify-center
                        rounded-full
                        border
                        border-white/20
                        bg-industrial-700
                        text-white
                        shadow-md
                        transition-all

                        hover:bg-safety-500
                        hover:text-industrial-900

                        focus:outline-none
                        focus:ring-2
                        focus:ring-safety-400
                    "

                    title="Expand sidebar"
                    aria-label="Expand sidebar"
                >

                    <svg
                        class="h-3.5 w-3.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2.5"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m9 18 6-6-6-6"
                        />
                    </svg>

                </button>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- USER PROFILE --}}
        {{-- ===================================================== --}}

        <div
            class="
                border-b
                border-slate-100
                px-3
                py-4
            "
        >

            @if($sidebarUser->role?->position !== 'admin')

                <a
                    href="{{ route('profile.edit') }}"

                    class="
                        group
                        flex
                        items-center
                        rounded-xl
                        transition-all
                        duration-200

                        {{ request()->routeIs('profile.*')
                            ? 'bg-industrial-50 ring-1 ring-industrial-100'
                            : 'hover:bg-slate-50'
                        }}
                    "

                    :class="
                        sidebarOpen
                            ? 'gap-3 p-2.5'
                            : 'justify-center p-2'
                    "

                    title="View My Profile"
                >

                    {{-- Photo / Initials --}}

                    <div class="relative shrink-0">

                        @if($sidebarUser->profile_photo)

                            <img
                                src="{{ asset('storage/' . $sidebarUser->profile_photo) }}"
                                alt="{{ $sidebarUser->name }}"
                                class="
                                    h-10
                                    w-10
                                    rounded-xl
                                    border
                                    border-slate-200
                                    object-cover
                                    shadow-sm
                                "
                            >

                        @else

                            <div
                                class="
                                    flex
                                    h-10
                                    w-10
                                    items-center
                                    justify-center
                                    rounded-xl
                                    bg-industrial-100
                                    text-xs
                                    font-bold
                                    text-industrial-800
                                    ring-1
                                    ring-industrial-200
                                "
                            >
                                {{ $sidebarInitials ?: 'U' }}
                            </div>

                        @endif


                        {{-- Online indicator --}}

                        <span
                            class="
                                absolute
                                -bottom-0.5
                                -right-0.5
                                h-3
                                w-3
                                rounded-full
                                border-2
                                border-white
                                bg-success-500
                            "
                            title="Signed in"
                        ></span>

                    </div>


                    {{-- User information --}}

                    <div
                        x-show="sidebarOpen"
                        x-cloak
                        x-transition.opacity
                        class="min-w-0 flex-1"
                    >

                        <p
                            class="
                                truncate
                                text-sm
                                font-semibold
                                text-slate-800
                                transition-colors

                                group-hover:text-industrial-800
                            "
                        >
                            {{ $sidebarUser->name }}
                        </p>

                        <div
                            class="
                                mt-1
                                flex
                                items-center
                                gap-1.5
                            "
                        >

                            <span
                                class="
                                    inline-flex
                                    rounded-full
                                    bg-industrial-50
                                    px-2
                                    py-0.5
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-wide
                                    text-industrial-600
                                "
                            >
                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $sidebarUser->role?->position ?? 'No Role'
                                    )
                                ) }}
                            </span>

                        </div>

                    </div>


                    <svg
                        x-show="sidebarOpen"
                        x-cloak
                        class="
                            h-4
                            w-4
                            shrink-0
                            text-slate-300
                            transition-all

                            group-hover:translate-x-0.5
                            group-hover:text-industrial-600
                        "
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

                <div
                    class="flex items-center rounded-xl"

                    :class="
                        sidebarOpen
                            ? 'gap-3 p-2.5'
                            : 'justify-center p-2'
                    "
                >

                    <div class="relative shrink-0">

                        <div
                            class="
                                flex
                                h-10
                                w-10
                                items-center
                                justify-center
                                rounded-xl
                                bg-industrial-100
                                text-xs
                                font-bold
                                text-industrial-800
                                ring-1
                                ring-industrial-200
                            "
                        >
                            {{ $sidebarInitials ?: 'U' }}
                        </div>

                        <span
                            class="
                                absolute
                                -bottom-0.5
                                -right-0.5
                                h-3
                                w-3
                                rounded-full
                                border-2
                                border-white
                                bg-success-500
                            "
                        ></span>

                    </div>


                    <div
                        x-show="sidebarOpen"
                        x-cloak
                        x-transition.opacity
                        class="min-w-0"
                    >

                        <p
                            class="
                                truncate
                                text-sm
                                font-semibold
                                text-slate-800
                            "
                        >
                            {{ $sidebarUser->name }}
                        </p>

                        <span
                            class="
                                mt-1
                                inline-flex
                                rounded-full
                                bg-industrial-50
                                px-2
                                py-0.5
                                text-[10px]
                                font-semibold
                                uppercase
                                tracking-wide
                                text-industrial-600
                            "
                        >
                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $sidebarUser->role?->position ?? 'No Role'
                                )
                            ) }}
                        </span>

                    </div>

                </div>

            @endif

        </div>


        {{-- ===================================================== --}}
        {{-- NAVIGATION --}}
        {{-- ===================================================== --}}

        <nav
            class="
                flex-1
                overflow-y-auto
                overflow-x-hidden
                px-3
                py-4
            "
        >

            <ul class="space-y-1">


                {{-- ================================================= --}}
                {{-- DASHBOARD --}}
                {{-- ================================================= --}}

                <li>

                    <a
                        href="{{ route('dashboard') }}"

                        class="
                            {{ $navBase }}
                            {{ request()->routeIs('dashboard')
                                ? $navActive
                                : $navIdle
                            }}
                        "

                        :class="
                            sidebarOpen
                                ? ''
                                : 'justify-center px-0'
                        "

                        title="Dashboard"
                    >

                        @if(request()->routeIs('dashboard'))

                            <span
                                class="
                                    absolute
                                    -left-3
                                    top-1/2
                                    h-6
                                    w-1
                                    -translate-y-1/2
                                    rounded-r-full
                                    bg-safety-500
                                "
                            ></span>

                        @endif


                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.7"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                            />
                        </svg>


                        <span
                            x-show="sidebarOpen"
                            x-cloak
                            x-transition.opacity
                            class="truncate"
                        >
                            Dashboard
                        </span>

                    </a>

                </li>


                {{-- ================================================= --}}
                {{-- SETUP --}}
                {{-- ================================================= --}}

                @if(
                    auth()->user()->hasPermission('view_locations')
                    ||
                    auth()->user()->hasPermission('view_sensors')
                )

                    <li class="pt-5">

                        <div
                            x-show="sidebarOpen"
                            x-cloak
                            class="
                                flex
                                items-center
                                gap-2
                                px-3
                                pb-2
                            "
                        >

                            <span
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-slate-400
                                "
                            >
                                Setup
                            </span>

                            <span
                                class="
                                    h-px
                                    flex-1
                                    bg-slate-100
                                "
                            ></span>

                        </div>

                    </li>

                @endif


                {{-- LOCATIONS --}}

                @if(auth()->user()->hasPermission('view_locations'))

                    <li>

                        <a
                            href="{{ route('locations.index') }}"

                            class="
                                {{ $navBase }}
                                {{ request()->routeIs('locations.*')
                                    ? $navActive
                                    : $navIdle
                                }}
                            "

                            :class="
                                sidebarOpen
                                    ? ''
                                    : 'justify-center px-0'
                            "

                            title="Locations"
                        >

                            @if(request()->routeIs('locations.*'))

                                <span
                                    class="
                                        absolute
                                        -left-3
                                        top-1/2
                                        h-6
                                        w-1
                                        -translate-y-1/2
                                        rounded-r-full
                                        bg-safety-500
                                    "
                                ></span>

                            @endif


                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"
                                />
                            </svg>


                            <span
                                x-show="sidebarOpen"
                                x-cloak
                                x-transition.opacity
                                class="truncate"
                            >
                                Locations
                            </span>

                        </a>

                    </li>

                @endif


                {{-- SENSORS --}}

                @if(auth()->user()->hasPermission('view_sensors'))

                    <li>

                        <a
                            href="{{ route('sensors.index') }}"

                            class="
                                {{ $navBase }}
                                {{ request()->routeIs('sensors.*')
                                    ? $navActive
                                    : $navIdle
                                }}
                            "

                            :class="
                                sidebarOpen
                                    ? ''
                                    : 'justify-center px-0'
                            "

                            title="Sensors"
                        >

                            @if(request()->routeIs('sensors.*'))

                                <span
                                    class="
                                        absolute
                                        -left-3
                                        top-1/2
                                        h-6
                                        w-1
                                        -translate-y-1/2
                                        rounded-r-full
                                        bg-safety-500
                                    "
                                ></span>

                            @endif


                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z"
                                />
                            </svg>


                            <span
                                x-show="sidebarOpen"
                                x-cloak
                                x-transition.opacity
                                class="truncate"
                            >
                                Sensors
                            </span>

                        </a>

                    </li>

                @endif


                {{-- ================================================= --}}
                {{-- INVENTORY --}}
                {{-- ================================================= --}}

                @if(
                    auth()->user()->hasPermission('view_items')
                    ||
                    auth()->user()->hasPermission('calibrate_sensor')
                    ||
                    auth()->user()->hasPermission('view_stock')
                )

                    <li class="pt-5">

                        <div
                            x-show="sidebarOpen"
                            x-cloak
                            class="
                                flex
                                items-center
                                gap-2
                                px-3
                                pb-2
                            "
                        >

                            <span
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-slate-400
                                "
                            >
                                Inventory
                            </span>

                            <span
                                class="
                                    h-px
                                    flex-1
                                    bg-slate-100
                                "
                            ></span>

                        </div>

                    </li>

                @endif


                {{-- ITEMS --}}

                @if(auth()->user()->hasPermission('view_items'))

                    <li>

                        <a
                            href="{{ route('items.index') }}"

                            class="
                                {{ $navBase }}
                                {{ request()->routeIs('items.*')
                                    ? $navActive
                                    : $navIdle
                                }}
                            "

                            :class="
                                sidebarOpen
                                    ? ''
                                    : 'justify-center px-0'
                            "

                            title="Items"
                        >

                            @if(request()->routeIs('items.*'))

                                <span
                                    class="
                                        absolute
                                        -left-3
                                        top-1/2
                                        h-6
                                        w-1
                                        -translate-y-1/2
                                        rounded-r-full
                                        bg-safety-500
                                    "
                                ></span>

                            @endif


                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.7"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                />
                            </svg>


                            <span
                                x-show="sidebarOpen"
                                x-cloak
                                x-transition.opacity
                                class="truncate"
                            >
                                Items
                            </span>

                        </a>

                    </li>

                @endif


                {{-- CALIBRATION --}}

                @if(auth()->user()->hasPermission('calibrate_sensor'))

                    <li>

                        <a
                            href="{{ route('calibration.index') }}"

                            class="
                                {{ $navBase }}
                                {{ request()->routeIs('calibration.*')
                                    ? $navActive
                                    : $navIdle
                                }}
                            "

                            :class="
                                sidebarOpen
                                    ? ''
                                    : 'justify-center px-0'
                            "

                            title="Calibration"
                        >

                            @if(request()->routeIs('calibration.*'))

                                <span
                                    class="
                                        absolute
                                        -left-3
                                        top-1/2
                                        h-6
                                        w-1
                                        -translate-y-1/2
                                        rounded-r-full
                                        bg-safety-500
                                    "
                                ></span>

                            @endif


                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 13.5V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 9.75V10.5"
                                />
                            </svg>


                            <span
                                x-show="sidebarOpen"
                                x-cloak
                                x-transition.opacity
                                class="truncate"
                            >
                                Calibration
                            </span>

                        </a>

                    </li>

                @endif


                {{-- STOCK --}}

                @if(auth()->user()->hasPermission('view_stock'))

                    <li>

                        <a
                            href="{{ route('stock.index') }}"

                            class="
                                {{ $navBase }}
                                {{ request()->routeIs('stock.*')
                                    ? $navActive
                                    : $navIdle
                                }}
                            "

                            :class="
                                sidebarOpen
                                    ? ''
                                    : 'justify-center px-0'
                            "

                            title="Stock"
                        >

                            @if(request()->routeIs('stock.*'))

                                <span
                                    class="
                                        absolute
                                        -left-3
                                        top-1/2
                                        h-6
                                        w-1
                                        -translate-y-1/2
                                        rounded-r-full
                                        bg-safety-500
                                    "
                                ></span>

                            @endif


                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z"
                                />
                            </svg>


                            <span
                                x-show="sidebarOpen"
                                x-cloak
                                x-transition.opacity
                                class="truncate"
                            >
                                Stock
                            </span>

                        </a>

                    </li>

                @endif


                {{-- ================================================= --}}
                {{-- ACTIVITY --}}
                {{-- ================================================= --}}

                @if(auth()->user()->hasPermission('view_history'))

                    <li class="pt-5">

                        <div
                            x-show="sidebarOpen"
                            x-cloak
                            class="
                                flex
                                items-center
                                gap-2
                                px-3
                                pb-2
                            "
                        >

                            <span
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-slate-400
                                "
                            >
                                Activity
                            </span>

                            <span
                                class="
                                    h-px
                                    flex-1
                                    bg-slate-100
                                "
                            ></span>

                        </div>

                    </li>


                    <li>

                        <a
                            href="{{ route('history.index') }}"

                            class="
                                {{ $navBase }}
                                {{ request()->routeIs('history.*')
                                    ? $navActive
                                    : $navIdle
                                }}
                            "

                            :class="
                                sidebarOpen
                                    ? ''
                                    : 'justify-center px-0'
                            "

                            title="History"
                        >

                            @if(request()->routeIs('history.*'))

                                <span
                                    class="
                                        absolute
                                        -left-3
                                        top-1/2
                                        h-6
                                        w-1
                                        -translate-y-1/2
                                        rounded-r-full
                                        bg-safety-500
                                    "
                                ></span>

                            @endif


                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                />
                            </svg>


                            <span
                                x-show="sidebarOpen"
                                x-cloak
                                x-transition.opacity
                                class="truncate"
                            >
                                History
                            </span>

                        </a>

                    </li>

                @endif


                {{-- ================================================= --}}
                {{-- ADMINISTRATION --}}
                {{-- ================================================= --}}

                @if(
                    auth()->user()->hasPermission('manage_staff')
                    ||
                    auth()->user()->hasPermission('manage_roles')
                )

                    <li class="pt-5">

                        <div
                            x-show="sidebarOpen"
                            x-cloak
                            class="
                                flex
                                items-center
                                gap-2
                                px-3
                                pb-2
                            "
                        >

                            <span
                                class="
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-slate-400
                                "
                            >
                                Administration
                            </span>

                            <span
                                class="
                                    h-px
                                    flex-1
                                    bg-slate-100
                                "
                            ></span>

                        </div>

                    </li>

                @endif


                {{-- MANAGE STAFF --}}

                @if(auth()->user()->hasPermission('manage_staff'))

                    <li>

                        <a
                            href="{{ route('staff.index') }}"

                            class="
                                {{ $navBase }}
                                {{ request()->routeIs('staff.*')
                                    ? $navActive
                                    : $navIdle
                                }}
                            "

                            :class="
                                sidebarOpen
                                    ? ''
                                    : 'justify-center px-0'
                            "

                            title="Manage Staff"
                        >

                            @if(request()->routeIs('staff.*'))

                                <span
                                    class="
                                        absolute
                                        -left-3
                                        top-1/2
                                        h-6
                                        w-1
                                        -translate-y-1/2
                                        rounded-r-full
                                        bg-safety-500
                                    "
                                ></span>

                            @endif


                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"
                                />
                            </svg>


                            <span
                                x-show="sidebarOpen"
                                x-cloak
                                x-transition.opacity
                                class="truncate"
                            >
                                Manage Staff
                            </span>

                        </a>

                    </li>

                @endif


                {{-- MANAGE ROLES --}}

                @if(auth()->user()->hasPermission('manage_roles'))

                    <li>

                        <a
                            href="{{ route('roles.index') }}"

                            class="
                                {{ $navBase }}
                                {{ request()->routeIs('roles.*')
                                    ? $navActive
                                    : $navIdle
                                }}
                            "

                            :class="
                                sidebarOpen
                                    ? ''
                                    : 'justify-center px-0'
                            "

                            title="Manage Roles"
                        >

                            @if(request()->routeIs('roles.*'))

                                <span
                                    class="
                                        absolute
                                        -left-3
                                        top-1/2
                                        h-6
                                        w-1
                                        -translate-y-1/2
                                        rounded-r-full
                                        bg-safety-500
                                    "
                                ></span>

                            @endif


                            <svg
                                class="h-5 w-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.7"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
                                />
                            </svg>


                            <span
                                x-show="sidebarOpen"
                                x-cloak
                                x-transition.opacity
                                class="truncate"
                            >
                                Manage Roles
                            </span>

                        </a>

                    </li>

                @endif

            </ul>

        </nav>


        {{-- ===================================================== --}}
        {{-- SIDEBAR FOOTER / LOGOUT --}}
        {{-- ===================================================== --}}

        <div
            class="
                border-t
                border-slate-100
                bg-slate-50/70
                p-3
            "
        >

            <form
                method="POST"
                action="{{ route('logout') }}"

                class="js-confirm-form"

                data-confirm-title="Confirm Logout"
                data-confirm-message="Are you sure you want to logout?"
                data-confirm-button="Logout"
            >

                @csrf


                <button
                    type="submit"

                    class="
                        group
                        flex
                        min-h-11
                        w-full
                        items-center
                        gap-3
                        rounded-xl
                        px-3
                        text-sm
                        font-medium
                        text-slate-500
                        transition-all
                        duration-200

                        hover:bg-danger-50
                        hover:text-danger-700

                        focus:outline-none
                        focus:ring-2
                        focus:ring-danger-500
                        focus:ring-offset-2
                    "

                    :class="
                        sidebarOpen
                            ? ''
                            : 'justify-center px-0'
                    "

                    title="Logout"
                >

                    <svg
                        class="
                            h-5
                            w-5
                            shrink-0
                            transition-transform
                            duration-200

                            group-hover:translate-x-0.5
                        "
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        />
                    </svg>


                    <span
                        x-show="sidebarOpen"
                        x-cloak
                        x-transition.opacity
                    >
                        Logout
                    </span>

                </button>

            </form>


            {{-- Small system label --}}

            <div
                x-show="sidebarOpen"
                x-cloak
                class="
                    mt-3
                    flex
                    items-center
                    justify-center
                    gap-1.5
                    border-t
                    border-slate-100
                    pt-3
                    text-[10px]
                    font-medium
                    text-slate-400
                "
            >

                <span
                    class="
                        h-1.5
                        w-1.5
                        rounded-full
                        bg-success-500
                    "
                ></span>

                Smart Shelf System

            </div>

        </div>

    </aside>


    {{-- ========================================================= --}}
    {{-- MAIN APPLICATION AREA --}}
    {{-- ========================================================= --}}

    <main
        class="
            relative
            min-w-0
            flex-1
            overflow-y-auto
            overflow-x-hidden
            bg-industrial-50
        "
    >

        {{-- Subtle industrial background decoration --}}

        <div
            class="
                pointer-events-none
                fixed
                right-0
                top-0
                h-72
                w-72
                rounded-full
                bg-industrial-100/40
                blur-3xl
            "
        ></div>


        <div
            class="
                relative
                min-h-full
                px-4
                py-5
                sm:px-6
                sm:py-6
                lg:px-8
                lg:py-8
            "
        >

            @yield('content')

        </div>

    </main>

</div>


{{-- ========================================================= --}}
{{-- GLOBAL REUSABLE CONFIRMATION MODAL --}}
{{-- ========================================================= --}}

<x-confirm-action-modal />


</body>

</html>