@props([
    'name' => 'password',
    'label' => 'Password',
    'autocomplete' => 'current-password',
])

<div>

    {{-- ===================================================== --}}
    {{-- LABEL --}}
    {{-- ===================================================== --}}

    <label
        for="{{ $name }}"
        class="
            mb-2
            block
            text-xs
            font-semibold
            text-slate-700
        "
    >
        {{ $label }}
    </label>


    {{-- ===================================================== --}}
    {{-- PASSWORD FIELD --}}
    {{-- ===================================================== --}}

    <div
        x-data="{ showPassword: false }"
        class="relative"
    >

        {{-- Lock icon --}}
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

            {{-- Heroicons: Lock Closed --}}
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
                    d="M16.5 10.5V6.75a4.5 4.5 0 0 0-9 0v3.75m-.75 10.5h10.5A2.25 2.25 0 0 0 19.5 18.75v-6A2.25 2.25 0 0 0 17.25 10.5H6.75A2.25 2.25 0 0 0 4.5 12.75v6A2.25 2.25 0 0 0 6.75 21Z"
                />
            </svg>

        </div>


        {{-- Password input --}}
        <input
            :type="showPassword ? 'text' : 'password'"
            id="{{ $name }}"
            name="{{ $name }}"
            autocomplete="{{ $autocomplete }}"
            placeholder="Enter your password"

            {{ $attributes->merge([
                'class' => '
                    block
                    w-full
                    rounded-xl
                    border-slate-300
                    bg-white
                    py-2.5
                    pl-10
                    pr-11
                    text-sm
                    text-slate-800
                    shadow-sm
                    transition

                    placeholder:text-slate-400

                    hover:border-slate-400

                    focus:border-industrial-500
                    focus:ring-industrial-500

                    disabled:cursor-not-allowed
                    disabled:bg-slate-50
                    disabled:text-slate-500
                    disabled:opacity-70
                '
            ]) }}
        >


        {{-- ================================================= --}}
        {{-- SHOW / HIDE PASSWORD --}}
        {{-- ================================================= --}}

        <button
            type="button"
            @click="showPassword = !showPassword"

            class="
                absolute
                inset-y-0
                right-0
                flex
                w-11
                items-center
                justify-center
                rounded-r-xl
                text-slate-400
                transition-colors
                duration-200

                hover:text-industrial-700

                focus:outline-none
                focus:text-industrial-700

                focus-visible:ring-2
                focus-visible:ring-inset
                focus-visible:ring-industrial-500
            "

            :aria-label="
                showPassword
                    ? 'Hide password'
                    : 'Show password'
            "

            :title="
                showPassword
                    ? 'Hide password'
                    : 'Show password'
            "
        >

            {{-- ============================================= --}}
            {{-- Heroicons: Eye --}}
            {{-- ============================================= --}}

            <svg
                x-show="!showPassword"
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
                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                />
            </svg>


            {{-- ============================================= --}}
            {{-- Heroicons: Eye Slash --}}
            {{-- ============================================= --}}

            <svg
                x-show="showPassword"
                x-cloak
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
                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                />
            </svg>

        </button>

    </div>


    {{-- ===================================================== --}}
    {{-- VALIDATION ERROR --}}
    {{-- ===================================================== --}}

    <x-input-error
        :messages="$errors->get($name)"
        class="mt-2"
    />

</div>