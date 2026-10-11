<x-guest-layout>

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}

    <div class="mb-6 text-center">

        <div
            class="
                mx-auto
                mb-4
                flex
                h-12
                w-12
                items-center
                justify-center
                rounded-xl
                bg-industrial-50
                text-industrial-700
                ring-1
                ring-industrial-100
            "
        >

            {{-- Heroicons: Shield Check --}}
            <svg
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
                />
            </svg>

        </div>


        <div
            class="
                mx-auto
                mb-3
                w-fit
                rounded-full
                bg-industrial-50
                px-3
                py-1
                text-[9px]
                font-bold
                uppercase
                tracking-[0.14em]
                text-industrial-600
            "
        >
            Administrator Access
        </div>


        <h1
            class="
                text-2xl
                font-bold
                tracking-tight
                text-slate-900
            "
        >
            Welcome back
        </h1>


        <p
            class="
                mx-auto
                mt-2
                max-w-sm
                text-sm
                leading-6
                text-slate-500
            "
        >
            Sign in using your Administrator account to manage
            the Smart Shelf system.
        </p>

    </div>


    {{-- ===================================================== --}}
    {{-- FORM --}}
    {{-- ===================================================== --}}

    <form
        method="POST"
        action="{{ route('admin.login.store') }}"
    >

        @csrf


        {{-- Email --}}
        <div>

            <label
                for="email"
                class="
                    mb-2
                    block
                    text-xs
                    font-semibold
                    text-slate-700
                "
            >
                Email Address
            </label>


            <div class="relative">

                {{-- Heroicons: Envelope --}}
                <div
                    class="
                        pointer-events-none
                        absolute
                        inset-y-0
                        left-0
                        flex
                        items-center
                        pl-3.5
                        text-slate-400
                    "
                >
                    <svg
                        class="h-[18px] w-[18px]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"
                        />
                    </svg>
                </div>


                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email address"
                    class="
                        block
                        w-full
                        rounded-xl
                        border-slate-300
                        bg-white
                        py-2.5
                        pl-10
                        pr-4
                        text-sm
                        text-slate-800
                        shadow-sm
                        transition

                        placeholder:text-slate-400

                        hover:border-slate-400

                        focus:border-industrial-500
                        focus:ring-industrial-500
                    "
                >

            </div>


            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>


        {{-- Password --}}
        <div class="mt-5">

            <x-password-input
                name="password"
                label="Password"
                autocomplete="current-password"
                required
            />

        </div>


        {{-- Login button --}}
        <button
            type="submit"
            class="
                mt-6
                inline-flex
                w-full
                items-center
                justify-center
                gap-2
                rounded-xl
                bg-industrial-700
                px-4
                py-3
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
                active:scale-[0.99]
            "
        >

            {{-- Heroicons: Arrow Right On Rectangle --}}
            <svg
                class="h-[18px] w-[18px]"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"
                />
            </svg>

            Sign In as Administrator

        </button>


        {{-- Divider --}}
        <div
            class="
                my-6
                flex
                items-center
                gap-3
            "
        >
            <div class="h-px flex-1 bg-slate-100"></div>

            <span
                class="
                    text-[9px]
                    font-semibold
                    uppercase
                    tracking-[0.12em]
                    text-slate-300
                "
            >
                Smart Shelf
            </span>

            <div class="h-px flex-1 bg-slate-100"></div>
        </div>


        {{-- Back --}}
        <div class="text-center">

            <a
                href="{{ route('landing') }}"
                class="
                    group
                    inline-flex
                    items-center
                    gap-1.5
                    text-xs
                    font-semibold
                    text-slate-500
                    transition-colors

                    hover:text-industrial-700
                "
            >

                {{-- Heroicons: Arrow Left --}}
                <svg
                    class="
                        h-4
                        w-4
                        transition-transform
                        group-hover:-translate-x-0.5
                    "
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
                    />
                </svg>

                Back to role selection

            </a>

        </div>

    </form>

</x-guest-layout>