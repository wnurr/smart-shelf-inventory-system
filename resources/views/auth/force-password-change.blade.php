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

            {{-- Heroicons: Key --}}
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
                    d="M15.75 5.25a3 3 0 1 1 3 3m-3-3a3 3 0 0 0-4.243 4.243L3 18v3h3v-3h3v-3h3l2.507-2.507A3 3 0 0 0 15.75 5.25Z"
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
            Security Required
        </div>


        <h1
            class="
                text-2xl
                font-bold
                tracking-tight
                text-slate-900
            "
        >
            Create your password
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
            Before accessing Smart Shelf, replace the temporary
            password with a password of your own.
        </p>

    </div>


    {{-- ===================================================== --}}
    {{-- SECURITY NOTICE --}}
    {{-- ===================================================== --}}

    <div
        class="
            mb-6
            flex
            items-start
            gap-3
            rounded-xl
            border
            border-safety-200
            bg-safety-50
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

            {{-- Heroicons: Shield Check --}}
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
                    d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
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
                First-time security setup
            </p>

            <p
                class="
                    mt-0.5
                    text-[11px]
                    leading-5
                    text-safety-700
                "
            >
                Your temporary password must be changed before
                you can continue to the system.
            </p>
        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- PASSWORD FORM --}}
    {{-- ===================================================== --}}

    <form
        method="POST"
        action="{{ route('password.force.update') }}"
    >

        @csrf
        @method('PUT')


        {{-- Temporary password --}}
        <div>

            <x-password-input
                name="current_password"
                label="Temporary Password"
                autocomplete="current-password"
                required
                autofocus
            />

        </div>


        {{-- Direction indicator --}}
        <div
            class="
                my-4
                flex
                items-center
                gap-3
            "
        >

            <div class="h-px flex-1 bg-slate-100"></div>


            <div
                class="
                    flex
                    h-7
                    w-7
                    items-center
                    justify-center
                    rounded-full
                    bg-industrial-50
                    text-industrial-500
                "
            >

                {{-- Heroicons: Arrow Down --}}
                <svg
                    class="h-3.5 w-3.5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m19.5 8.25-7.5 7.5-7.5-7.5"
                    />
                </svg>

            </div>


            <div class="h-px flex-1 bg-slate-100"></div>

        </div>


        {{-- New password --}}
        <div>

            <x-password-input
                name="password"
                label="New Password"
                autocomplete="new-password"
                required
            />


            <div
                class="
                    mt-2
                    flex
                    items-start
                    gap-1.5
                    text-[10px]
                    leading-4
                    text-slate-400
                "
            >

                {{-- Heroicons: Information Circle --}}
                <svg
                    class="
                        mt-px
                        h-3.5
                        w-3.5
                        shrink-0
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
                        d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063 1.062L12 12.646m0-6.036h.008v.008H12V6.61ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                    />
                </svg>

                <span>
                    Your new password must be different from the
                    temporary password provided by the Administrator.
                </span>

            </div>

        </div>


        {{-- Confirm --}}
        <div class="mt-5">

            <x-password-input
                name="password_confirmation"
                label="Confirm New Password"
                autocomplete="new-password"
                required
            />

        </div>


        {{-- Submit --}}
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

            {{-- Heroicons: Check --}}
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
                    d="m4.5 12.75 6 6 9-13.5"
                />
            </svg>

            Change Password & Continue

        </button>

    </form>


    {{-- ===================================================== --}}
    {{-- LOGOUT --}}
    {{-- ===================================================== --}}

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
            Or
        </span>

        <div class="h-px flex-1 bg-slate-100"></div>
    </div>


    <form
        method="POST"
        action="{{ route('logout') }}"
        class="text-center"
    >

        @csrf


        <button
            type="submit"
            class="
                group
                inline-flex
                items-center
                gap-1.5
                text-xs
                font-semibold
                text-slate-500
                transition-colors

                hover:text-danger-600
            "
        >

            {{-- Heroicons: Arrow Left On Rectangle --}}
            <svg
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"
                />
            </svg>

            Log out instead

        </button>

    </form>

</x-guest-layout>