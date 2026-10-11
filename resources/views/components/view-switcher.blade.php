@props([
    'options' => [
        ['value' => 'list', 'label' => 'List'],
        ['value' => 'cards', 'label' => 'Cards'],
    ],
])

<div
    x-data="{ value: 'list', open: false }"
    x-modelable="value"
    {{ $attributes->merge(['class' => 'relative']) }}
    @click.outside="open = false"
>
    <button
        type="button"
        @click="open = !open"
        class="inline-flex h-9 items-center gap-2 rounded-lg border border-gray-200 bg-white px-3
               text-xs font-medium text-gray-600 shadow-sm transition
               hover:bg-gray-50 hover:text-gray-900"
        :aria-expanded="open"
    >
        <svg x-show="value === 'list'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
        </svg>

        <svg x-show="value === 'cards'" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z" />
        </svg>

        <span>View</span>

        <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m19 9-7 7-7-7" />
        </svg>
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition.origin.top.right
        class="absolute right-0 z-30 mt-2 w-40 overflow-hidden rounded-xl
               border border-gray-200 bg-white p-1.5 shadow-lg"
    >
        @foreach($options as $option)
            <button
                type="button"
                @click="value = @js($option['value']); open = false"
                class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2
                       text-left text-sm transition"
                :class="value === @js($option['value'])
                    ? 'bg-gray-100 font-medium text-gray-900'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
            >
                @if($option['value'] === 'list')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                    </svg>
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z" />
                    </svg>
                @endif

                <span>{{ $option['label'] }}</span>

                <svg
                    x-show="value === @js($option['value'])"
                    class="ml-auto w-4 h-4 text-[#284B63]"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="m5 12 4 4L19 6" />
                </svg>
            </button>
        @endforeach
    </div>
</div>
