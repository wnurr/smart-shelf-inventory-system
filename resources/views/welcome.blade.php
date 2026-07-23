<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center justify-center min-h-screen flex-col">

        <!-- Top-right nav (kept, but only shows Dashboard link when already authed) 
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @endauth
                </nav>
            @endif
        </header>-->

        <!-- MAIN -->
        <main class="w-full lg:max-w-4xl max-w-[335px] flex-1 flex flex-col lg:flex-row items-center justify-center gap-10">

            <!-- Left: brand / pitch -->
            <div class="flex-1 flex flex-col gap-4 lg:pr-6">
                <span class="inline-flex items-center gap-2 w-fit px-3 py-1 rounded-full text-xs font-medium border border-[#19140035] dark:border-[#3E3E3A] text-[#706f6c] dark:text-[#A1A09A]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#284B63]"></span>
                    {{ config('app.name', 'Laravel') }}
                </span>

                <h1 class="text-3xl lg:text-4xl font-medium leading-tight text-[#1b1b18] dark:text-[#EDEDEC]">
                    Welcome back to<br>Smart Shelf Inventory System.
                </h1>

                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm leading-[20px] max-w-[420px]">
                    Sign in to access your dashboard, manage your account, and keep everything running smoothly.
                </p>
            </div>

            <!-- Right: login card -->
            <div class="w-full max-w-[380px] shrink-0">
                <div class="rounded-2xl border border-[#19140035] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] shadow-[0px_4px_24px_-4px_rgba(0,0,0,0.08)] p-8">

                    <h2 class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-1">Log in</h2>
                    <p class="text-[13px] text-[#706f6c] dark:text-[#A1A09A] mb-6">Enter your details to continue.</p>

                    @if (Route::has('login'))
                    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                        @csrf

                        <div class="flex flex-col gap-1.5">
                            <label for="email" class="text-[13px] font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Email</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                required
                                autofocus
                                placeholder="you@example.com"
                                class="w-full rounded-lg border border-[#19140035] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-[#a8a7a3] focus:outline-none focus:ring-2 focus:ring-[#284B63]/40 focus:border-[#284B63]"
                            >
                            @error('email')
                                <span class="text-[12px] text-[#284B63]">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between">
                                <label for="password" class="text-[13px] font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[12px] text-[#706f6c] dark:text-[#A1A09A] underline underline-offset-4 hover:text-[#284B63]">
                                        Forgot?
                                    </a>
                                @endif
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                placeholder="••••••••"
                                class="w-full rounded-lg border border-[#19140035] dark:border-[#3E3E3A] bg-[#FDFDFC] dark:bg-[#0a0a0a] px-3 py-2 text-sm text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-[#a8a7a3] focus:outline-none focus:ring-2 focus:ring-[#284B63]/40 focus:border-[#284B63]"
                            >
                            @error('password')
                                <span class="text-[12px] text-[#284B63]">{{ $message }}</span>
                            @enderror
                        </div>

                        <label class="flex items-center gap-2 text-[13px] text-[#706f6c] dark:text-[#A1A09A]">
                            <input type="checkbox" name="remember" class="rounded border-[#19140035] dark:border-[#3E3E3A] text-[#284B63] focus:ring-[#284B63]/40">
                            Remember me
                        </label>

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] text-sm font-medium py-2.5 mt-1 transition-colors hover:bg-[#284B63] dark:hover:bg-[#284B63] dark:hover:text-white"
                        >
                            Log in
                        </button>
                    </form>

                    @if (Route::has('register'))
                        <p class="text-[13px] text-[#706f6c] dark:text-[#A1A09A] text-center mt-6">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium underline underline-offset-4 hover:text-[#284B63]">
                                Register
                            </a>
                        </p>
                    @endif
                    @endif

                </div>
            </div>

        </main>

    </body>
</html>