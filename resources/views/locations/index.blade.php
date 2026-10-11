@extends('layouts.app')

@section('content')

<div
    class="max-w-7xl mx-auto px-4 sm:px-6 py-8"
   x-data="{
    showAddLocationModal: {{ $errors->any() ? 'true' : 'false' }},
    showEditLocationModal: false,

    editLocation: {
        id: null,
        label: '',
        aisle: '',
        bay: '',
        shelf: '',
        bin: '',
        updateUrl: ''
    },

    openEditLocationFromButton(button) {
        this.editLocation = {
            id: button.dataset.locationId,
            label: button.dataset.locationLabel,
            aisle: button.dataset.locationAisle,
            bay: button.dataset.locationBay,
            shelf: button.dataset.locationShelf,
            bin: button.dataset.locationBin,
            updateUrl: button.dataset.updateUrl
        };

        this.showEditLocationModal = true;
    },

    closeEditLocation() {
        this.showEditLocationModal = false;
    }
}"
x-effect="
    document.body.classList.toggle(
        'overflow-hidden',
        showAddLocationModal || showEditLocationModal
    )
"
>

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">

        <div>
            <div class="location-eyebrow"><span></span> STORAGE SETUP</div>
            <h1 class="text-2xl font-bold text-slate-900 mt-2">Manage Locations</h1>
            <p class="text-sm text-slate-500 mt-1">Organize physical shelf positions and monitor which locations are currently in use.</p>
        </div>


        @if(auth()->user()->hasPermission('manage_locations'))

            <button
                type="button"
                @click="showAddLocationModal = true"
                class="location-primary-btn"
            >

                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Add Location

            </button>

        @endif

    </div>



    {{-- ========================================================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6 location-summary-grid">

        {{-- TOTAL LOCATIONS --}}
        <div class="location-summary-card">

            <div>

                <p class="location-summary-label">
                    Total Locations
                </p>

                <p class="location-summary-number">
                    {{ $totalLocations }}
                </p>

                <p class="location-summary-help">
                    Registered storage positions
                </p>

            </div>


            <div class="location-summary-icon location-summary-primary">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>

            </div>

        </div>


        {{-- OCCUPIED --}}
        <div class="location-summary-card">

            <div>

                <p class="location-summary-label">
                    Occupied
                </p>

                <p class="location-summary-number">
                    {{ $occupiedLocations }}
                </p>

                <p class="location-summary-help">
                    Currently assigned to items
                </p>

            </div>


            <div class="location-summary-icon location-summary-blue">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                    />
                </svg>

            </div>

        </div>


        {{-- AVAILABLE --}}
        <div class="location-summary-card">

            <div>

                <p class="location-summary-label">
                    Available
                </p>

                <p class="location-summary-number">
                    {{ $availableLocations }}
                </p>

                <p class="location-summary-help">
                    Ready for item assignment
                </p>

            </div>


            <div class="location-summary-icon location-summary-green">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />
                </svg>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- SEARCH + FILTER --}}
    {{-- ========================================================= --}}

    <x-search-filter
        :action="route('locations.index')"
        :search="$search"
        placeholder="Search location, e.g. A-A-1-1..."
        :clear-url="route('locations.index')"
        :show-clear="$search !== '' || $status !== 'all'"
        class="mb-5"
    >

        <select
            name="status"
            class="h-10 rounded-lg border-gray-300 text-sm focus:border-[#284B63] focus:ring-[#284B63] md:w-44"
        >

            <option
                value="all"
                @selected($status === 'all')
            >
                All Status
            </option>

            <option
                value="occupied"
                @selected($status === 'occupied')
            >
                Occupied
            </option>

            <option
                value="available"
                @selected($status === 'available')
            >
                Available
            </option>

        </select>

    </x-search-filter>



    {{-- ========================================================= --}}
    {{-- LOCATION TABLE --}}
    {{-- ========================================================= --}}

    <div class="location-table-card location-surface">

        {{-- TABLE HEADER --}}
        <div class="location-table-header">

            <div>

                <h2 class="text-sm font-semibold text-gray-900">
                    Location Registry
                </h2>

                <p class="text-xs text-gray-500 mt-0.5">
                    Storage positions and their current assignments.
                </p>

            </div>


            <span class="location-count-badge">

                {{ $locations->total() }}
                {{ \Illuminate\Support\Str::plural(
                    'location',
                    $locations->total()
                ) }}

            </span>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="location-table-head">

                        <th class="px-5 py-3 text-left w-16">No.</th>
                            <th class="px-5 py-3 text-left">
                            Location
                        </th>

                        <th class="px-5 py-3 text-left">
                            Aisle
                        </th>

                        <th class="px-5 py-3 text-left">
                            Bay
                        </th>

                        <th class="px-5 py-3 text-left">
                            Shelf
                        </th>

                        <th class="px-5 py-3 text-left">
                            Bin
                        </th>

                        <th class="px-5 py-3 text-left">
                            Assigned Item
                        </th>

                        <th class="px-5 py-3 text-left">
                            Status
                        </th>

                        <th class="px-5 py-3 text-right">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($locations as $location)

                        <tr class="location-row">
                                        <td class="px-5 py-4 align-top"><x-table-number :loop="$loop" :paginator="$locations" /></td>

                            {{-- LOCATION --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="location-code-icon">

                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            />

                                        </svg>

                                    </div>


                                    <span class="font-mono font-semibold text-gray-900">
                                        {{ $location->label() }}
                                    </span>

                                </div>

                            </td>


                            {{-- AISLE --}}
                            <td class="px-5 py-4 text-gray-700">
                                {{ $location->aisle }}
                            </td>


                            {{-- BAY --}}
                            <td class="px-5 py-4 text-gray-700">
                                {{ $location->bay }}
                            </td>


                            {{-- SHELF --}}
                            <td class="px-5 py-4 text-gray-700">
                                {{ $location->shelf }}
                            </td>


                            {{-- BIN --}}
                            <td class="px-5 py-4 text-gray-700">
                                {{ $location->bin }}
                            </td>


                            {{-- ASSIGNED ITEM --}}
                            <td class="px-5 py-4">

                                @if($location->item)

                                    <p class="font-medium text-gray-800">
                                        {{ $location->item->item_name }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $location->item->item_code }}
                                    </p>

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td class="px-5 py-4">

                                @if($location->item)

                                    <span class="location-status location-status-occupied">

                                        <span class="location-status-dot"></span>

                                        Occupied

                                    </span>

                                @else

                                    <span class="location-status location-status-available">

                                        <span class="location-status-dot"></span>

                                        Available

                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}
                            <td class="px-5 py-4 text-right">

                                @if(auth()->user()->hasPermission('manage_locations'))

                                    <div class="inline-flex items-center gap-3">

                                        {{-- EDIT --}}
                                        <x-table-action-button
                                            variant="edit"
                                            data-location-id="{{ $location->id }}"
                                            data-location-label="{{ $location->label() }}"
                                            data-location-aisle="{{ $location->aisle }}"
                                            data-location-bay="{{ $location->bay }}"
                                            data-location-shelf="{{ $location->shelf }}"
                                            data-location-bin="{{ $location->bin }}"
                                            data-update-url="{{ route('locations.update', $location) }}"
                                            @click="openEditLocationFromButton($el)"
                                        >
                                            Edit
                                        </x-table-action-button>


                                        {{-- DELETE --}}
                                        @if($location->item)

                                            <span
                                                class="location-delete-disabled"
                                                title="Remove the item assignment before deleting this location."
                                            >
                                                In Use
                                            </span>

                                        @else

                                            <form
                                                method="POST"
                                                action="{{ route('locations.destroy', $location) }}"
                                                class="js-confirm-form inline-block"
                                                data-confirm-title="Delete Location"
                                                data-confirm-message="Are you sure you want to delete {{ $location->label() }}? This action cannot be undone."
                                                data-confirm-button="Delete"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <x-table-action-button type="submit" variant="delete">
                                                    Delete
                                                </x-table-action-button>

                                            </form>

                                        @endif

                                    </div>

                                @else

                                    <span class="text-gray-300">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="px-6 py-16 text-center"
                            >

                                <div class="location-empty-icon">

                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.7"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                                        />
                                    </svg>

                                </div>


                                <h3 class="text-sm font-semibold text-gray-700 mt-3">

                                    {{
                                        ($search !== '' || $status !== 'all')
                                            ? 'No matching locations'
                                            : 'No locations registered'
                                    }}

                                </h3>


                                <p class="text-xs text-gray-400 mt-1">

                                    {{
                                        ($search !== '' || $status !== 'all')
                                            ? 'Try changing your search or status filter.'
                                            : 'Add a storage location to get started.'
                                    }}

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        <x-simple-pagination :paginator="$locations" />

    </div>



    {{-- ========================================================= --}}
    {{-- ADD LOCATION MODAL --}}
    {{-- ========================================================= --}}

    @if(auth()->user()->hasPermission('manage_locations'))

        <div
            x-show="showAddLocationModal"
            x-cloak
            @keydown.escape.window="showAddLocationModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4"
        >

            {{-- BACKDROP --}}
            <div
                class="absolute inset-0 bg-slate-950/55 backdrop-blur-[2px]"
                @click="showAddLocationModal = false"
            ></div>


            {{-- MODAL CONTAINER --}}
            <div
                x-show="showAddLocationModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.stop
                class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col location-modal-shell"
                style="max-height: 92vh;"
            >

                {{-- ================================================= --}}
                {{-- MODAL HEADER --}}
                {{-- ================================================= --}}

                <div
                    class="flex items-center justify-between gap-4 px-6 py-5 text-white flex-shrink-0"
                    style="background:linear-gradient(135deg,#243B53 0%,#334E68 100%);"
                >

                    <div class="flex items-center gap-3">

                        {{-- ICON --}}
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg flex-shrink-0"
                            style="background:rgba(255,255,255,.10);"
                        >

                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />

                            </svg>

                        </div>


                        {{-- TITLE --}}
                        <div>

                            <h2 class="text-lg font-semibold leading-tight">
                                Add Location
                            </h2>

                            <p class="text-sm mt-1 text-white/70">
                                Register a new storage location.
                            </p>

                        </div>

                    </div>


                    {{-- CLOSE --}}
                    <button
                        type="button"
                        @click="showAddLocationModal = false"
                        class="flex items-center justify-center w-9 h-9 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition"
                        aria-label="Close modal"
                    >

                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>

                    </button>

                </div>



                {{-- ================================================= --}}
                {{-- FORM --}}
                {{-- ================================================= --}}

                <form
                    method="POST"
                    action="{{ route('locations.store') }}"
                    class="flex flex-col min-h-0"
                >

                    @csrf


                    {{-- ================================================= --}}
                    {{-- SCROLLABLE BODY --}}
                    {{-- ================================================= --}}

                    <div class="overflow-y-auto px-6 py-6">




                        {{-- ================================================= --}}
                        {{-- LOCATION INFORMATION --}}
                        {{-- ================================================= --}}

                        <section>

                            <p class="location-modal-section-title">
                                Location Information
                            </p>

                            <p class="location-modal-section-help">
                                Define the physical storage position.
                            </p>

                            <div class="location-modal-divider"></div>


                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                                {{-- AISLE --}}
                                <div>

                                    <label
                                        for="aisle"
                                        class="location-modal-label"
                                    >
                                        Aisle
                                    </label>

                                    <input
                                        id="aisle"
                                        name="aisle"
                                        type="text"
                                        maxlength="1"
                                        pattern="[A-Za-z]"
                                        value="{{ old('aisle') }}"
                                        placeholder="e.g. A"
                                        required
                                        data-uppercase
                                        class="location-modal-input"
                                    >

                                    <p class="location-modal-help">
                                        One letter only (A-Z).
                                    </p>

                                    @error('aisle')

                                        <p class="location-modal-error">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- BAY --}}
                                <div>

                                    <label
                                        for="bay"
                                        class="location-modal-label"
                                    >
                                        Bay
                                    </label>

                                    <input
                                        id="bay"
                                        name="bay"
                                        type="text"
                                        maxlength="1"
                                        pattern="[A-Za-z]"
                                        value="{{ old('bay') }}"
                                        placeholder="e.g. A"
                                        required
                                        data-uppercase
                                        class="location-modal-input"
                                    >

                                    <p class="location-modal-help">
                                        One letter only (A-Z).
                                    </p>

                                    @error('bay')

                                        <p class="location-modal-error">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- SHELF --}}
                                <div>

                                    <label
                                        for="shelf"
                                        class="location-modal-label"
                                    >
                                        Shelf
                                    </label>

                                    <input
                                        id="shelf"
                                        name="shelf"
                                        type="text"
                                        inputmode="numeric"
                                        maxlength="1"
                                        pattern="[1-9]"
                                        value="{{ old('shelf') }}"
                                        placeholder="e.g. 1"
                                        required
                                        class="location-modal-input"
                                    >

                                    <p class="location-modal-help">
                                        One number only (1-9).
                                    </p>

                                    @error('shelf')

                                        <p class="location-modal-error">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- BIN --}}
                                <div>

                                    <label
                                        for="bin"
                                        class="location-modal-label"
                                    >
                                        Bin
                                    </label>

                                    <input
                                        id="bin"
                                        name="bin"
                                        type="text"
                                        inputmode="numeric"
                                        maxlength="1"
                                        pattern="[1-9]"
                                        value="{{ old('bin') }}"
                                        placeholder="e.g. 1"
                                        required
                                        class="location-modal-input"
                                    >

                                    <p class="location-modal-help">
                                        One number only (1-9).
                                    </p>

                                    @error('bin')

                                        <p class="location-modal-error">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>

                            </div>

                        </section>



                        {{-- ================================================= --}}
                        {{-- LOCATION FORMAT --}}
                        {{-- ================================================= --}}

                        <section class="mt-8">

                            <p class="location-modal-section-title">
                                Location Format
                            </p>

                            <p class="location-modal-section-help">
                                Storage positions follow the Aisle-Bay-Shelf-Bin format.
                            </p>

                            <div class="location-modal-divider"></div>


                            <div class="location-modal-info">

                                <div class="flex-shrink-0 mt-0.5">

                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>

                                </div>


                                <div>

                                    <p class="font-medium text-gray-700">
                                        Example: A-A-1-1
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500 leading-relaxed">
                                        Aisle and Bay use letters while Shelf and
                                        Bin use numbers. Duplicate physical locations
                                        are rejected automatically.
                                    </p>

                                </div>

                            </div>

                        </section>

                    </div>



                    {{-- ================================================= --}}
                    {{-- MODAL FOOTER --}}
                    {{-- ================================================= --}}

                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">

                        <button
                            type="button"
                            @click="showAddLocationModal = false"
                            class="location-modal-cancel"
                        >
                            Cancel
                        </button>


                        <button
                            type="submit"
                            class="location-modal-submit"
                        >

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>

                            Add Location

                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endif

    {{-- ========================================================= --}}
{{-- EDIT LOCATION MODAL --}}
{{-- ========================================================= --}}

@if(auth()->user()->hasPermission('manage_locations'))

    <div
        x-show="showEditLocationModal"
        x-cloak
        @keydown.escape.window="closeEditLocation()"
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4"
    >

        {{-- BACKDROP --}}
        <div
            class="absolute inset-0 bg-slate-950/55 backdrop-blur-[2px]"
            @click="closeEditLocation()"
        ></div>


        {{-- MODAL CONTAINER --}}
        <div
            x-show="showEditLocationModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col location-modal-shell"
            style="max-height: 92vh;"
        >

            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <div
                class="flex items-center justify-between gap-4 px-6 py-5 text-white flex-shrink-0"
                style="background:linear-gradient(135deg,#243B53 0%,#334E68 100%);"
            >

                <div class="flex items-center gap-3">

                    {{-- ICON --}}
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-lg flex-shrink-0"
                        style="background:rgba(255,255,255,.10);"
                    >

                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            />
                        </svg>

                    </div>


                    {{-- TITLE --}}
                    <div>

                        <h2 class="text-lg font-semibold leading-tight">
                            Edit Location
                        </h2>

                        <p class="text-sm mt-1 text-white/70">
                            Update storage location information.
                        </p>

                    </div>

                </div>


                {{-- CLOSE --}}
                <button
                    type="button"
                    @click="closeEditLocation()"
                    class="flex items-center justify-center w-9 h-9 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition"
                    aria-label="Close modal"
                >

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </button>

            </div>



            {{-- ================================================= --}}
            {{-- FORM --}}
            {{-- ================================================= --}}

            <form
                method="POST"
                :action="editLocation.updateUrl"
                class="flex flex-col min-h-0"
            >

                @csrf
                @method('PUT')


                {{-- SCROLLABLE BODY --}}
                <div class="overflow-y-auto px-6 py-6">


                    {{-- ================================================= --}}
                    {{-- CURRENT LOCATION --}}
                    {{-- ================================================= --}}

                    <div class="edit-location-current">

                        <div class="edit-location-current-icon">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>

                        </div>


                        <div>

                            <p class="text-xs text-gray-500">
                                Current Location
                            </p>

                            <p
                                class="font-mono text-sm font-semibold text-gray-800 mt-0.5"
                                x-text="editLocation.label"
                            ></p>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- LOCATION INFORMATION --}}
                    {{-- ================================================= --}}

                    <section class="mt-7">

                        <p class="location-modal-section-title">
                            Location Information
                        </p>

                        <p class="location-modal-section-help">
                            Update the physical storage position.
                        </p>

                        <div class="location-modal-divider"></div>


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">


                            {{-- AISLE --}}
                            <div>

                                <label
                                    for="edit_aisle"
                                    class="location-modal-label"
                                >
                                    Aisle
                                </label>

                                <input
                                    id="edit_aisle"
                                    name="aisle"
                                    type="text"
                                    maxlength="1"
                                    pattern="[A-Za-z]"
                                    x-model="editLocation.aisle"
                                    placeholder="e.g. A"
                                    required
                                    data-uppercase
                                    class="location-modal-input"
                                >

                                <p class="location-modal-help">
                                    One letter only (A-Z).
                                </p>

                            </div>


                            {{-- BAY --}}
                            <div>

                                <label
                                    for="edit_bay"
                                    class="location-modal-label"
                                >
                                    Bay
                                </label>

                                <input
                                    id="edit_bay"
                                    name="bay"
                                    type="text"
                                    maxlength="1"
                                    pattern="[A-Za-z]"
                                    x-model="editLocation.bay"
                                    placeholder="e.g. A"
                                    required
                                    data-uppercase
                                    class="location-modal-input"
                                >

                                <p class="location-modal-help">
                                    One letter only (A-Z).
                                </p>

                            </div>


                            {{-- SHELF --}}
                            <div>

                                <label
                                    for="edit_shelf"
                                    class="location-modal-label"
                                >
                                    Shelf
                                </label>

                                <input
                                    id="edit_shelf"
                                    name="shelf"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="1"
                                    pattern="[1-9]"
                                    x-model="editLocation.shelf"
                                    placeholder="e.g. 1"
                                    required
                                    class="location-modal-input"
                                >

                                <p class="location-modal-help">
                                    One number only (1-9).
                                </p>

                            </div>


                            {{-- BIN --}}
                            <div>

                                <label
                                    for="edit_bin"
                                    class="location-modal-label"
                                >
                                    Bin
                                </label>

                                <input
                                    id="edit_bin"
                                    name="bin"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="1"
                                    pattern="[1-9]"
                                    x-model="editLocation.bin"
                                    placeholder="e.g. 1"
                                    required
                                    class="location-modal-input"
                                >

                                <p class="location-modal-help">
                                    One number only (1-9).
                                </p>

                            </div>

                        </div>

                    </section>



                    {{-- ================================================= --}}
                    {{-- LOCATION FORMAT --}}
                    {{-- ================================================= --}}

                    <section class="mt-8">

                        <p class="location-modal-section-title">
                            Location Format
                        </p>

                        <p class="location-modal-section-help">
                            Preview the updated storage location.
                        </p>

                        <div class="location-modal-divider"></div>


                        <div class="location-modal-info">

                            <div class="flex-shrink-0 mt-0.5">

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>

                            </div>


                            <div>

                                <p class="text-xs text-gray-500">
                                    Updated Location
                                </p>

                                <p
                                    class="mt-1 font-mono text-sm font-semibold"
                                    style="color:#284B63;"
                                >
                                    <span x-text="editLocation.aisle || '-'"></span>-<span x-text="editLocation.bay || '-'"></span>-<span x-text="editLocation.shelf || '-'"></span>-<span x-text="editLocation.bin || '-'"></span>
                                </p>


                                <p class="mt-2 text-xs text-gray-500 leading-relaxed">
                                    Duplicate physical locations are not allowed.
                                </p>

                            </div>

                        </div>

                    </section>

                </div>



                {{-- ================================================= --}}
                {{-- FOOTER --}}
                {{-- ================================================= --}}

                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">

                    <button
                        type="button"
                        @click="closeEditLocation()"
                        class="location-modal-cancel"
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        class="location-modal-submit"
                    >

                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

@endif

</div>



{{-- ========================================================= --}}
{{-- STYLES --}}
{{-- ========================================================= --}}

<style>

    [x-cloak] {
        display: none !important;
    }


    /* =========================================================
       GENERAL BUTTON
       ========================================================= */

    .location-primary-btn {
        min-height: 2.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        padding: 0 1rem;

        color: #fff;
        background: #284B63;

        border: 1px solid #284B63;
        border-radius: .5rem;

        font-size: .8125rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;

        transition: .15s ease;
    }

    .location-primary-btn:hover {
        background: #1F3A4C;
        border-color: #1F3A4C;
    }



    /* =========================================================
       SUMMARY CARDS
       ========================================================= */

    .location-summary-card {
        min-height: 7rem;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 1rem;
        padding: 1.25rem;

        background: #fff;

        border: 1px solid #E5E7EB;
        border-radius: .75rem;
    }


    .location-summary-label {
        color: #6B7280;
        font-size: .75rem;
        font-weight: 500;
    }


    .location-summary-number {
        margin-top: .45rem;

        color: #111827;

        font-size: 1.75rem;
        line-height: 1;
        font-weight: 700;
    }


    .location-summary-help {
        margin-top: .5rem;

        color: #9CA3AF;

        font-size: .68rem;
    }


    .location-summary-icon,
    .location-code-icon {
        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        border-radius: .6rem;
    }


    .location-summary-icon {
        width: 2.5rem;
        height: 2.5rem;
    }


    .location-summary-primary,
    .location-code-icon {
        color: #284B63;
        background: #EDF3F7;
    }


    .location-summary-blue {
        color: #1D4ED8;
        background: #DBEAFE;
    }


    .location-summary-green {
        color: #15803D;
        background: #DCFCE7;
    }



    /* =========================================================
       TABLE
       ========================================================= */

    .location-table-card {
        overflow: hidden;

        background: #fff;

        border: 1px solid #E5E7EB;
        border-radius: .75rem;
    }


    .location-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 1rem;

        padding: 1rem 1.25rem;

        border-bottom: 1px solid #F3F4F6;
    }


    .location-count-badge {
        padding: .3rem .6rem;

        color: #284B63;
        background: #EDF3F7;

        border-radius: 9999px;

        font-size: .7rem;
        font-weight: 700;
    }


    .location-table-head {
        color: #6B7280;
        background: #F9FAFB;

        border-bottom: 1px solid #F3F4F6;

        font-size: .7rem;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .035em;
    }


    .location-row {
        transition: background-color .15s ease;
    }


    .location-row:hover {
        background: #FAFAFA;
    }


    .location-code-icon {
        width: 2.1rem;
        height: 2.1rem;
    }



    /* =========================================================
       STATUS
       ========================================================= */

    .location-status {
        display: inline-flex;
        align-items: center;

        gap: .4rem;

        padding: .3rem .6rem;

        border-radius: 9999px;

        font-size: .7rem;
        font-weight: 600;

        white-space: nowrap;
    }


    .location-status-dot {
        width: .38rem;
        height: .38rem;

        background: currentColor;

        border-radius: 9999px;
    }


    .location-status-occupied {
        color: #1D4ED8;
        background: #DBEAFE;
    }


    .location-status-available {
        color: #15803D;
        background: #DCFCE7;
    }



    /* =========================================================
       TABLE ACTIONS
       ========================================================= */


    .location-edit-btn:hover {
        text-decoration: underline;
    }


    .location-delete-btn:hover {
        text-decoration: underline;
    }


    .location-delete-disabled {
        color: #9CA3AF;

        font-size: .75rem;
        font-weight: 600;

        cursor: not-allowed;
    }



    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .location-empty-icon {
        width: 3rem;
        height: 3rem;

        margin: 0 auto;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #9CA3AF;
        background: #F3F4F6;

        border-radius: 9999px;
    }



    /* =========================================================
       ADD LOCATION MODAL
       ========================================================= */

    .location-modal-section-title {
        color: #374151;

        font-size: .72rem;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .06em;
    }


    .location-modal-section-help {
        margin-top: .3rem;

        color: #9CA3AF;

        font-size: .7rem;
    }


    .location-modal-divider {
        height: 1px;

        margin: .65rem 0 1.2rem;

        background: #EEF0F2;
    }

    /* =========================================================
   EDIT LOCATION MODAL
   ========================================================= */

.edit-location-current {
    display: flex;
    align-items: center;
    gap: .75rem;

    padding: .85rem 1rem;

    background: #F8FAFC;

    border: 1px solid #E5E7EB;
    border-radius: .6rem;
}

.edit-location-current-icon {
    width: 2.25rem;
    height: 2.25rem;

    display: flex;
    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    color: #284B63;
    background: #EDF3F7;

    border-radius: .5rem;
}



    /* =========================================================
       MODAL INPUTS
       ========================================================= */

    .location-modal-label {
        display: block;

        margin-bottom: .45rem;

        color: #4B5563;

        font-size: .8rem;
        font-weight: 600;
    }


    .location-modal-input {
        width: 100%;
        height: 2.7rem;

        padding: 0 .8rem;

        color: #111827;
        background: #fff;

        border: 1px solid #D1D5DB;
        border-radius: .55rem;

        font-size: .875rem;

        text-transform: uppercase;

        transition:
            border-color .15s ease,
            box-shadow .15s ease;
    }


    .location-modal-input::placeholder {
        color: #9CA3AF;
    }


    .location-modal-input:focus {
        border-color: #284B63;

        box-shadow: 0 0 0 1px #284B63;

        outline: none;
    }


    .location-modal-help {
        margin-top: .4rem;

        color: #9CA3AF;

        font-size: .68rem;
    }


    .location-modal-error {
        margin-top: .35rem;

        color: #DC2626;

        font-size: .7rem;
    }



    /* =========================================================
       LOCATION FORMAT INFO
       ========================================================= */

    .location-modal-info {
        display: flex;

        gap: .65rem;

        padding: .9rem 1rem;

        color: #52616B;
        background: #F8FAFC;

        border: 1px solid #E5E7EB;
        border-radius: .6rem;
    }



    /* =========================================================
       MODAL BUTTONS
       ========================================================= */

    .location-modal-cancel,
    .location-modal-submit {
        min-height: 2.55rem;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: .4rem;

        padding: 0 1rem;

        border-radius: .5rem;

        font-size: .8125rem;
        font-weight: 600;

        transition: .15s ease;
    }


    .location-modal-cancel {
        color: #4B5563;
        background: #fff;

        border: 1px solid #D1D5DB;
    }


    .location-modal-cancel:hover {
        background: #F9FAFB;
    }


    .location-modal-submit {
        color: #fff;
        background: #284B63;

        border: 1px solid #284B63;
    }


    .location-modal-submit:hover {
        background: #1F3A4C;
        border-color: #1F3A4C;
    }



    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 640px) {

        .location-modal-cancel,
        .location-modal-submit {
            min-height: 2.7rem;
        }

    }


    /* =========================================================
       UI REFRESH — presentation only
       ========================================================= */
    .location-eyebrow{display:flex;align-items:center;gap:.5rem;color:#B96B00;font-size:.68rem;font-weight:800;letter-spacing:.12em}
    .location-eyebrow span{width:.42rem;height:.42rem;border-radius:9999px;background:#F59E0B;box-shadow:0 0 0 4px rgba(245,158,11,.10)}
    .location-primary-btn{min-height:2.75rem;padding:0 1.05rem;background:#243B53;border-color:#243B53;border-radius:.65rem;box-shadow:0 4px 12px rgba(36,59,83,.12)}
    .location-primary-btn:hover{background:#334E68;border-color:#334E68;transform:translateY(-1px);box-shadow:0 7px 18px rgba(36,59,83,.16)}
    .location-summary-card{position:relative;overflow:hidden;min-height:7.6rem;padding:1.35rem;background:linear-gradient(180deg,#fff 0%,#FCFDFE 100%);border-color:#E2E8F0;border-radius:1rem;box-shadow:0 2px 8px rgba(15,23,42,.035);transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}
    .location-summary-card:before{content:"";position:absolute;left:0;top:1.15rem;bottom:1.15rem;width:3px;border-radius:0 999px 999px 0;background:#CBD5E1}
    .location-summary-card:nth-child(1):before{background:#F59E0B}.location-summary-card:nth-child(2):before{background:#2563EB}.location-summary-card:nth-child(3):before{background:#059669}
    .location-summary-card:hover{transform:translateY(-2px);border-color:#CBD5E1;box-shadow:0 10px 24px rgba(15,23,42,.07)}
    .location-summary-label{color:#64748B;font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.055em}
    .location-summary-number{margin-top:.5rem;color:#0F172A;font-size:2rem;font-weight:800;letter-spacing:-.035em}
    .location-summary-help{color:#94A3B8;font-size:.7rem}
    .location-summary-icon{width:2.8rem;height:2.8rem;border-radius:.8rem}
    .location-summary-primary{color:#B96B00;background:#FFF7E6;border:1px solid #FDE3AE}
    .location-summary-blue{color:#2563EB;background:#EFF6FF;border:1px solid #DBEAFE}
    .location-summary-green{color:#059669;background:#ECFDF5;border:1px solid #D1FAE5}
    .location-surface{border-color:#E2E8F0;border-radius:1rem;box-shadow:0 3px 12px rgba(15,23,42,.04)}
    .location-table-header{padding:1.15rem 1.3rem;background:linear-gradient(180deg,#fff,#FCFDFE);border-bottom-color:#E2E8F0}
    .location-count-badge{padding:.36rem .7rem;color:#334E68;background:#F1F5F9;border:1px solid #E2E8F0}
    .location-table-head{color:#64748B;background:#F8FAFC;border-bottom-color:#E2E8F0;font-size:.66rem;letter-spacing:.065em}
    .location-row{transition:background-color .15s ease,box-shadow .15s ease}.location-row:hover{background:#F8FAFC}
    .location-code-icon{width:2.25rem;height:2.25rem;color:#334E68;background:#F1F5F9;border:1px solid #E2E8F0;border-radius:.65rem}
    .location-status{padding:.34rem .65rem;border:1px solid transparent;font-weight:700}
    .location-status-occupied{color:#1D4ED8;background:#EFF6FF;border-color:#DBEAFE}
    .location-status-available{color:#047857;background:#ECFDF5;border-color:#D1FAE5}
    .location-delete-disabled{display:inline-flex;align-items:center;padding:.3rem .55rem;color:#94A3B8;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:.45rem;font-size:.7rem}
    .location-empty-icon{width:3.4rem;height:3.4rem;color:#64748B;background:#F8FAFC;border:1px solid #E2E8F0}
    .location-modal-shell{border:1px solid rgba(255,255,255,.15)}
    .location-modal-section-title{color:#334E68;font-size:.68rem;letter-spacing:.09em}
    .location-modal-divider{background:#E2E8F0}
    .location-modal-input{height:2.85rem;border-color:#CBD5E1;border-radius:.65rem;background:#fff}
    .location-modal-input:hover{border-color:#94A3B8}
    .location-modal-input:focus{border-color:#F59E0B;box-shadow:0 0 0 3px rgba(245,158,11,.12)}
    .location-modal-info,.edit-location-current{background:#F8FAFC;border-color:#E2E8F0;border-radius:.75rem}
    .edit-location-current-icon{color:#B96B00;background:#FFF7E6;border:1px solid #FDE3AE}
    .location-modal-cancel,.location-modal-submit{min-height:2.65rem;border-radius:.6rem}
    .location-modal-cancel:hover{background:#F8FAFC;border-color:#94A3B8}
    .location-modal-submit{background:#243B53;border-color:#243B53;box-shadow:0 3px 8px rgba(36,59,83,.12)}
    .location-modal-submit:hover{background:#334E68;border-color:#334E68}
    @media(max-width:640px){.location-summary-card{min-height:6.8rem}.location-table-header{align-items:flex-start}.location-count-badge{flex-shrink:0}}

</style>

@endsection