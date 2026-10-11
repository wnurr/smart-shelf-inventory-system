<x-guest-layout>

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}

    <div class="mb-7 text-center">

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
                bg-safety-50
                text-safety-700
                ring-1
                ring-safety-100
            "
        >

            {{-- Heroicons: Users --}}
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
                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197A5.971 5.971 0 0 0 6 18.719M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"
                />
            </svg>

        </div>


        <div
            class="
                mx-auto
                mb-3
                w-fit
                rounded-full
                bg-safety-50
                px-3
                py-1
                text-[9px]
                font-bold
                uppercase
                tracking-[0.14em]
                text-safety-700
            "
        >
            Staff Access
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
            Sign in to access the inventory functions assigned
            to your staff role.
        </p>


        <div
            class="
                mt-3
                flex
                items-center
                justify-center
                gap-2
            "
        >

            <span
                class="
                    rounded-full
                    bg-slate-100
                    px-2.5
                    py-1
                    text-[9px]
                    font-bold
                    uppercase
                    tracking-wider
                    text-slate-500
                "
            >
                Store Manager
            </span>

            <span
                class="
                    rounded-full
                    bg-slate-100
                    px-2.5
                    py-1
                    text-[9px]
                    font-bold
                    uppercase
                    tracking-wider
                    text-slate-500
                "
            >
                Store Keeper
            </span>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- FORM --}}
    {{-- ===================================================== --}}

    <form
        method="POST"
        action="{{ route('staff.login.store') }}"
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


        {{-- Login --}}
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

            Sign In as Staff

        </button>


        {{-- Account information --}}
        <div
            class="
                mt-5
                flex
                items-start
                gap-3
                rounded-xl
                border
                border-safety-100
                bg-safety-50/70
                p-3.5
            "
        >

            <div
                class="
                    flex
                    h-8
                    w-8
                    shrink-0
                    items-center
                    justify-center
                    rounded-lg
                    bg-safety-100
                    text-safety-700
                "
            >

                {{-- Heroicons: Information Circle --}}
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
                        d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063 1.062L12 12.646m0-6.036h.008v.008H12V6.61ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                    />
                </svg>

            </div>


            <div>
                <p
                    class="
                        text-xs
                        font-semibold
                        text-safety-800
                    "
                >
                    Need a staff account?
                </p>

                <p
                    class="
                        mt-0.5
                        text-[11px]
                        leading-5
                        text-safety-700
                    "
                >
                    Please contact your Administrator to request an account.
                </p>
            </div>

        </div>


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