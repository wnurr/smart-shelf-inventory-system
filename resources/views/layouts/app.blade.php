<!DOCTYPE html>
<html>

<head>
    <title>Smart Shelf System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-grey">

    <div class="flex h-screen" x-data="{ sidebarOpen: false }">

        <!-- SIDEBAR -->
        <div
            class="bg-white text-yale-blue p-5 transition-all duration-300 flex flex-col"
            :class="sidebarOpen ? 'w-64' : 'w-20'">
            <!-- Header row: title + toggle button -->
            <div class="flex items-center mb-6" :class="sidebarOpen ? 'justify-between' : 'flex-col gap-3'">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Smart Shelf logo"
                    class="shrink-0 object-contain transition-all duration-300"
                    :class="sidebarOpen ? 'h-8 w-8' : 'h-9 w-9'">

                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="p-1.5 rounded hover:bg-grey transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300"
                        :class="{ 'rotate-180': !sidebarOpen }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <ul class="space-y-2">
                <li>
                    <a href="/dashboard" class="flex items-center gap-3 px-2 py-2 rounded hover:bg-grey hover:text-yale-blue transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span x-show="sidebarOpen" x-cloak x-transition.opacity>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/products" class="flex items-center gap-3 px-2 py-2 rounded hover:bg-grey hover:text-yale-blue transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span x-show="sidebarOpen" x-cloak x-transition.opacity>Products</span>
                    </a>
                </li>
                <li>
                    <a href="/stock" class="flex items-center gap-3 px-2 py-2 rounded hover:bg-grey hover:text-yale-blue transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                        </svg>
                        <span x-show="sidebarOpen" x-cloak x-transition.opacity>Stock</span>
                    </a>
                </li>
                <li>
                    <a href="/calibration" class="flex items-center gap-3 px-2 py-2 rounded hover:bg-grey hover:text-yale-blue transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 9.75V10.5" />
                        </svg>
                        <span x-show="sidebarOpen" x-cloak x-transition.opacity>Calibration</span>
                    </a>
                </li>
                <li>
                    <a href="/history" class="flex items-center gap-3 px-2 py-2 rounded hover:bg-grey hover:text-yale-blue transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-show="sidebarOpen" x-cloak x-transition.opacity>History</span>
                    </a>
                </li>
            </ul>

            <!-- Logout pinned to bottom -->
            <div class="mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-2 py-2 rounded hover:bg-grey hover:text-red transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="sidebarOpen" x-cloak x-transition.opacity>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="flex-1 p-6 overflow-y-auto">
            @yield('content')
        </div>

    </div>

</body>

</html>