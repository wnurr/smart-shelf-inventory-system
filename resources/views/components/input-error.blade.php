@props([
    'messages',
])

@if ($messages)

    <ul
        {{ $attributes->merge([
            'class' => '
                mt-1.5
                space-y-1
                text-sm
                font-medium
                text-danger-600
            '
        ]) }}
    >
        @foreach ((array) $messages as $message)

            <li class="flex items-start gap-1.5">

                <svg
                    class="mt-0.5 h-4 w-4 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

                <span>
                    {{ $message }}
                </span>

            </li>

        @endforeach
    </ul>

@endif