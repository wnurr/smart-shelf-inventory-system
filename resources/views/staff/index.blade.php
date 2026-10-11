@extends('layouts.app')

@section('content')

<div
    class="max-w-7xl mx-auto px-4 sm:px-6 py-8"
    x-data="{
        showAddStaffModal: {{ $errors->any() && !old('_edit_staff_id') ? 'true' : 'false' }},
        showEditStaffModal: {{ old('_edit_staff_id') ? 'true' : 'false' }},
        editStaff: {
            id: @js(old('_edit_staff_id')),
            name: @js(old('_edit_staff_name', '')),
            email: @js(old('_edit_staff_email', '')),
            phone: @js(old('_edit_staff_phone', '')),
            role: @js(old('role', '')),
            updateUrl: @js(old('_edit_staff_url', ''))
        },
        openEditStaffFromButton(button) {
            this.editStaff = {
                id: button.dataset.staffId,
                name: button.dataset.staffName,
                email: button.dataset.staffEmail,
                phone: button.dataset.staffPhone || 'Not provided',
                role: button.dataset.staffRole,
                updateUrl: button.dataset.updateUrl
            };
            this.showEditStaffModal = true;
        },
        closeEditStaff() {
            this.showEditStaffModal = false;
        }
    }"
    x-init="
        const syncBodyLock = () => document.body.classList.toggle('overflow-hidden', showAddStaffModal || showEditStaffModal);
        $watch('showAddStaffModal', syncBodyLock);
        $watch('showEditStaffModal', syncBodyLock);
        syncBodyLock();
    "
>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">
        <div>
            <div class="ui-eyebrow"><span></span>ACCESS MANAGEMENT</div>
            <h1 class="text-2xl font-bold text-gray-900 ui-page-title mt-2">Manage Staff</h1>
            <p class="text-sm text-gray-500 mt-1 ui-page-subtitle">
                Manage staff accounts and assign access through system roles.
            </p>
        </div>

        <button type="button" @click="showAddStaffModal = true" class="staff-primary-btn">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Staff
        </button>
    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="staff-summary-card ui-summary">
            <div>
                <p class="staff-summary-label">Total Staff</p>
                <p class="staff-summary-number">{{ $totalStaffCount }}</p>
                <p class="staff-summary-help">Non-admin staff accounts</p>
            </div>
            <div class="staff-summary-icon staff-summary-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
</svg>

            </div>
        </div>

        <div class="staff-summary-card ui-summary">
            <div>
                <p class="staff-summary-label">Active Roles</p>
                <p class="staff-summary-number">{{ $roles->count() }}</p>
                <p class="staff-summary-help">Available staff role groups</p>
            </div>
            <div class="staff-summary-icon staff-summary-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
</svg>

            </div>
        </div>

        <div class="staff-summary-card sm:col-span-2 lg:col-span-1">
            <div>
                <p class="staff-summary-label">Showing</p>
                <p class="staff-summary-number">{{ $staff->count() }}</p>
                <p class="staff-summary-help">Staff matching current filters</p>
            </div>
            <div class="staff-summary-icon staff-summary-green">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- SEARCH + ROLE FILTER --}}
    {{-- ========================================================= --}}

    <x-search-filter
        :action="route('staff.index')"
        :search="$search"
        placeholder="Search name, email or phone number..."
        :clear-url="route('staff.index')"
        :show-clear="$search !== '' || $roleFilter !== ''"
        class="mb-6"
    >
        <select
            name="role"
            class="h-10 min-w-[190px] rounded-lg border-gray-300 text-sm text-gray-700 focus:border-[#F59E0B] focus:ring-[#F59E0B]"
        >
            <option value="">All Roles</option>
            @foreach($roles as $role)
                <option value="{{ $role->position }}" {{ $roleFilter === $role->position ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $role->position)) }}
                </option>
            @endforeach
        </select>
    </x-search-filter>


    {{-- ========================================================= --}}
    {{-- ROLE GROUPS --}}
    {{-- ========================================================= --}}

    <div class="space-y-6">
        @php $visibleGroupCount = 0; @endphp

        @foreach($roles as $role)
            @php
                $roleStaff = $staffByRole->get($role->id, collect());
                $showRole = $roleFilter === '' || $roleFilter === $role->position;
            @endphp

            @if($showRole && $roleStaff->isNotEmpty())
                @php $visibleGroupCount++; @endphp

                <section class="staff-role-card ui-surface">
                    <div class="staff-role-header">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-sm font-bold text-gray-900">
                                    {{ ucwords(str_replace('_', ' ', $role->position)) }}
                                </h2>
                                <span class="staff-role-count">
                                    {{ $roleStaff->count() }} {{ \Illuminate\Support\Str::plural('staff', $roleStaff->count()) }}
                                </span>
                            </div>
                            
                        </div>

                        <div class="text-xs text-gray-400">
                            Total in role: {{ (int) ($roleCounts[$role->id] ?? 0) }}
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="staff-table-head ui-table-head">
                                    <th class="px-5 py-3 text-left w-16">No.</th>
                            <th class="px-5 py-3 text-left">Name</th>
                                    <th class="px-5 py-3 text-left">Email</th>
                                    <th class="px-5 py-3 text-left">Phone Number</th>
                                    <th class="px-5 py-3 text-right">Action</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @foreach($roleStaff as $user)
                                    <tr class="staff-row ui-row">
                                        <td class="px-5 py-4 align-top"><x-table-number :loop="$loop" /></td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="staff-avatar">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-400 mt-0.5">Staff account</p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 text-gray-600">
                                            {{ $user->email }}
                                        </td>

                                        <td class="px-5 py-4 text-gray-600">
                                            {{ $user->phone_no ?: '—' }}
                                        </td>

                                        <td class="px-5 py-4 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <x-table-action-button
                                                    variant="edit"
                                                    data-staff-id="{{ $user->id }}"
                                                    data-staff-name="{{ $user->name }}"
                                                    data-staff-email="{{ $user->email }}"
                                                    data-staff-phone="{{ $user->phone_no ?: 'Not provided' }}"
                                                    data-staff-role="{{ $user->role?->position }}"
                                                    data-update-url="{{ route('staff.update', $user) }}"
                                                    @click="openEditStaffFromButton($el)"
                                                >
                                                    Edit
                                                </x-table-action-button>

                                                <form
                                                    method="POST"
                                                    action="{{ route('staff.destroy', $user) }}"
                                                    class="js-confirm-form inline-block"
                                                    data-confirm-title="Delete Staff"
                                                    data-confirm-message="Are you sure you want to delete {{ $user->name }}? This action cannot be undone."
                                                    data-confirm-button="Delete"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-table-action-button type="submit" variant="delete">
                                                        Delete
                                                    </x-table-action-button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        @endforeach

        @if($visibleGroupCount === 0)
            <div class="staff-empty-state">
                <div class="staff-empty-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-4a4 4 0 10-8 0 4 4 0 008 0z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-700 mt-3">
                    {{ $search !== '' || $roleFilter !== '' ? 'No matching staff' : 'No staff accounts yet' }}
                </h3>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $search !== '' || $roleFilter !== '' ? 'Try changing the search or role filter.' : 'Add a staff account to get started.' }}
                </p>
            </div>
        @endif
    </div>

    {{-- ========================================================= --}}
    {{-- ADD STAFF MODAL --}}
    {{-- ========================================================= --}}

    <div
        x-show="showAddStaffModal"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="showAddStaffModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div
            x-show="showAddStaffModal"
            x-transition
            @click.outside="showAddStaffModal = false"
            class="w-full max-w-2xl max-h-[92vh] overflow-hidden rounded-2xl bg-white shadow-2xl"
        >
            <div class="flex items-center justify-between gap-4 bg-[#284B63] px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3M13.5 6A3.5 3.5 0 1 1 6.5 6a3.5 3.5 0 0 1 7 0ZM3 20a7 7 0 0 1 14 0"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Add Staff</h2>
                        <p class="mt-0.5 text-xs text-white/70">Create a staff account and assign its system role.</p>
                    </div>
                </div>

                <button type="button" @click="showAddStaffModal = false" class="rounded-lg p-2 text-white/70 hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('staff.store') }}">
                @csrf

                <div class="max-h-[65vh] overflow-y-auto px-6 py-6">
                    

                    <div class="modal-section-title">STAFF INFORMATION</div>
                    <p class="modal-section-help">Enter the staff member's basic account information.</p>
                    <div class="modal-divider"></div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="modal-label" for="staff_name">Full Name <span class="text-red-500">*</span></label>
                            <input id="staff_name" name="name" type="text" value="{{ old('name') }}" required class="modal-input" placeholder="e.g. Nur Aisyah">
                            @error('name') <p class="modal-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="modal-label" for="staff_email">Email Address <span class="text-red-500">*</span></label>
                            <input id="staff_email" name="email" type="email" value="{{ old('email') }}" required class="modal-input" placeholder="staff@example.com">
                            @error('email') <p class="modal-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="modal-label" for="staff_phone">Phone Number</label>
                            <input id="staff_phone" name="phone_no" type="text" value="{{ old('phone_no') }}" class="modal-input" placeholder="e.g. 0123456789">
                            @error('phone_no') <p class="modal-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-7 modal-section-title">ACCESS & TEMPORARY PASSWORD</div>
                    <p class="modal-section-help">Choose a role and temporary password. The staff member will be required to change it after login.</p>
                    <div class="modal-divider"></div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="modal-label" for="staff_role">Role <span class="text-red-500">*</span></label>
                            <select id="staff_role" name="role" required class="modal-input">
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->position }}" @selected(old('role') === $role->position)>
                                        {{ ucwords(str_replace('_', ' ', $role->position)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <p class="modal-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="modal-label" for="staff_password">Temporary Password <span class="text-red-500">*</span></label>
                            <input id="staff_password" name="password" type="password" required minlength="8" class="modal-input" placeholder="Minimum 8 characters">
                            @error('password') <p class="modal-error">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="modal-label" for="staff_password_confirmation">Confirm Password <span class="text-red-500">*</span></label>
                            <input id="staff_password_confirmation" name="password_confirmation" type="password" required minlength="8" class="modal-input" placeholder="Repeat password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
                    <button type="button" @click="showAddStaffModal = false" class="modal-cancel-btn">Cancel</button>
                    <button type="submit" class="modal-submit-btn">Add Staff</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- EDIT STAFF MODAL --}}
    {{-- ========================================================= --}}

    <div
        x-show="showEditStaffModal"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="closeEditStaff()"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div
            x-show="showEditStaffModal"
            x-transition
            @click.outside="closeEditStaff()"
            class="w-full max-w-2xl max-h-[92vh] overflow-hidden rounded-2xl bg-white shadow-2xl"
        >
            <div class="flex items-center justify-between gap-4 bg-[#284B63] px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 19a6 6 0 00-12 0m6-8a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Manage Staff Access</h2>
                        <p class="mt-0.5 text-xs text-white/70">Assign a role or reset this staff account's temporary password.</p>
                    </div>
                </div>
                <button type="button" @click="closeEditStaff()" class="rounded-lg p-2 text-white/70 hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" :action="editStaff.updateUrl" autocomplete="off">
                @csrf
                @method('PUT')
                <input type="hidden" name="_edit_staff_id" :value="editStaff.id">
                <input type="hidden" name="_edit_staff_name" :value="editStaff.name">
                <input type="hidden" name="_edit_staff_email" :value="editStaff.email">
                <input type="hidden" name="_edit_staff_phone" :value="editStaff.phone">
                <input type="hidden" name="_edit_staff_url" :value="editStaff.updateUrl">

                <div class="max-h-[65vh] overflow-y-auto px-6 py-6">
                    

                    <div class="modal-section-title">STAFF ACCOUNT</div>
                    <p class="modal-section-help">Personal information is maintained by the staff member from My Profile.</p>
                    <div class="modal-divider"></div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="staff-readonly sm:col-span-2"><span>Full Name</span><strong x-text="editStaff.name"></strong></div>
                        <div class="staff-readonly"><span>Email Address</span><strong x-text="editStaff.email"></strong></div>
                        <div class="staff-readonly"><span>Phone Number</span><strong x-text="editStaff.phone || 'Not provided'"></strong></div>
                    </div>

                    <div class="mt-5">
                        <label class="modal-label" for="edit_staff_role">Role <span class="text-red-500">*</span></label>
                        <select id="edit_staff_role" name="role" x-model="editStaff.role" required class="modal-input">
                            <option value="">Select role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->position }}">{{ ucwords(str_replace('_', ' ', $role->position)) }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1.5 text-xs text-gray-400">Permissions are controlled by the selected role in Manage Roles.</p>
                        @error('role') @if(old('_edit_staff_id'))<p class="modal-error">{{ $message }}</p>@endif @enderror
                    </div>

                    <div class="mt-7 modal-section-title">RESET STAFF PASSWORD</div>
                    <p class="modal-section-help">Optional account recovery. Leave both fields blank to keep the current password.</p>
                    <div class="modal-divider"></div>

                    <div class="staff-info-box mb-5">
                        <strong>Only reset when needed</strong>
                        <span>A reset password becomes temporary and must be changed by the staff member after login.</span>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="modal-label" for="edit_staff_password">New Temporary Password</label>
                            <input class="modal-input" type="password" id="edit_staff_password" name="password" autocomplete="new-password" placeholder="Minimum 8 characters">
                            @error('password') @if(old('_edit_staff_id'))<p class="modal-error">{{ $message }}</p>@endif @enderror
                        </div>
                        <div>
                            <label class="modal-label" for="edit_staff_password_confirmation">Confirm Temporary Password</label>
                            <input class="modal-input" type="password" id="edit_staff_password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Repeat password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
                    <button type="button" @click="closeEditStaff()" class="modal-cancel-btn">Cancel</button>
                    <button type="submit" class="modal-submit-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display:none !important; }
    .modal-section-title { color:#374151; font-size:.7rem; font-weight:800; letter-spacing:.08em; }
    .modal-section-help { margin-top:.25rem; color:#9CA3AF; font-size:.72rem; }
    .modal-divider { margin:.75rem 0 1.1rem; border-top:1px solid #E5E7EB; }
    .modal-label { display:block; margin-bottom:.4rem; color:#374151; font-size:.75rem; font-weight:600; }
    .modal-input { width:100%; min-height:2.65rem; border:1px solid #D1D5DB; border-radius:.55rem; padding:.6rem .75rem; color:#111827; background:#fff; font-size:.8125rem; }
    .modal-input:focus { border-color:#284B63; outline:none; box-shadow:0 0 0 1px #284B63; }
    .modal-error { margin-top:.3rem; color:#DC2626; font-size:.7rem; }
    .modal-cancel-btn { min-height:2.5rem; padding:0 1rem; border:1px solid #D1D5DB; border-radius:.5rem; background:#fff; color:#4B5563; font-size:.8rem; font-weight:600; }
    .modal-cancel-btn:hover { background:#F9FAFB; }
    .modal-submit-btn { min-height:2.5rem; padding:0 1rem; border:1px solid #284B63; border-radius:.5rem; background:#284B63; color:#fff; font-size:.8rem; font-weight:600; }
    .modal-submit-btn:hover { background:#1F3A4C; border-color:#1F3A4C; }

    .staff-primary-btn { min-height:2.625rem; display:inline-flex; align-items:center; justify-content:center; gap:.45rem; padding:0 1rem; border-radius:.5rem; color:#fff; background:#284B63; border:1px solid #284B63; font-size:.8125rem; font-weight:600; text-decoration:none; white-space:nowrap; }
    .staff-primary-btn:hover { background:#1F3A4C; border-color:#1F3A4C; }
    .staff-summary-card { min-height:7rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1.25rem; background:#fff; border:1px solid #E5E7EB; border-radius:.75rem; }
    .staff-summary-label { color:#6B7280; font-size:.75rem; font-weight:500; }
    .staff-summary-number { margin-top:.45rem; color:#111827; font-size:1.75rem; line-height:1; font-weight:700; }
    .staff-summary-help { margin-top:.5rem; color:#9CA3AF; font-size:.68rem; }
    .staff-summary-icon { width:2.5rem; height:2.5rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; border-radius:.6rem; }
    .staff-summary-primary { color:#284B63; background:#EDF3F7; }
    .staff-summary-blue { color:#1D4ED8; background:#DBEAFE; }
    .staff-summary-green { color:#15803D; background:#DCFCE7; }
    .staff-role-card { overflow:hidden; background:#fff; border:1px solid #E5E7EB; border-radius:.75rem; }
    .staff-role-header { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.25rem; border-bottom:1px solid #F3F4F6; }
    .staff-role-count { padding:.25rem .55rem; color:#284B63; background:#EDF3F7; border-radius:9999px; font-size:.68rem; font-weight:700; }
    .staff-table-head { color:#6B7280; background:#F9FAFB; border-bottom:1px solid #F3F4F6; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.035em; }
    .staff-row { transition:background-color .15s ease; }
    .staff-row:hover { background:#FAFAFA; }
    .staff-avatar { width:2.1rem; height:2.1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; color:#284B63; background:#EDF3F7; border-radius:9999px; font-size:.75rem; font-weight:700; }
    .staff-edit-btn:hover { background:#DCE8EF; }
    .staff-delete-btn:hover { background:#FEE2E2; }
    .staff-empty-state { padding:4rem 1.5rem; text-align:center; background:#fff; border:1px solid #E5E7EB; border-radius:.75rem; }
    .staff-empty-icon { width:3rem; height:3rem; margin:0 auto; display:flex; align-items:center; justify-content:center; color:#9CA3AF; background:#F3F4F6; border-radius:9999px; }
.staff-readonly{padding:.75rem .85rem;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:.55rem}.staff-readonly span{display:block;color:#9CA3AF;font-size:.65rem}.staff-readonly strong{display:block;margin-top:.2rem;color:#374151;font-size:.8rem;font-weight:600;overflow-wrap:anywhere}.staff-info-box{display:flex;flex-direction:column;gap:.15rem;padding:.8rem 1rem;color:#284B63;background:#F5F8FA;border:1px solid #DCE8EF;border-radius:.6rem;font-size:.75rem}.staff-info-box span{color:#64748B;font-size:.7rem}

    /* =========================================================
       INDUSTRIAL UI REFRESH — presentation only
       Deep Steel #243B53 · Steel Navy #334E68 · Safety Amber #F59E0B
       ========================================================= */
    .ui-eyebrow{display:flex;align-items:center;gap:.5rem;color:#B96B00;font-size:.68rem;font-weight:800;letter-spacing:.12em}
    .ui-eyebrow span{width:.42rem;height:.42rem;border-radius:9999px;background:#F59E0B;box-shadow:0 0 0 4px rgba(245,158,11,.10)}
    .ui-page-title{color:#0F172A!important;letter-spacing:-.025em}
    .ui-page-subtitle{color:#64748B!important;max-width:44rem}
    .ui-surface{border-color:#E2E8F0!important;border-radius:1rem!important;box-shadow:0 3px 12px rgba(15,23,42,.04)!important}
    .ui-primary{background:#243B53!important;border-color:#243B53!important;border-radius:.65rem!important;box-shadow:0 4px 12px rgba(36,59,83,.12)!important}
    .ui-primary:hover{background:#334E68!important;border-color:#334E68!important;transform:translateY(-1px);box-shadow:0 7px 18px rgba(36,59,83,.16)!important}
    .ui-summary{position:relative;overflow:hidden;min-height:7.35rem;padding:1.3rem!important;background:linear-gradient(180deg,#fff 0%,#FCFDFE 100%)!important;border:1px solid #E2E8F0!important;border-radius:1rem!important;box-shadow:0 2px 8px rgba(15,23,42,.035)!important;transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}
    .ui-summary:before{content:"";position:absolute;left:0;top:1.1rem;bottom:1.1rem;width:3px;border-radius:0 999px 999px 0;background:#CBD5E1}
    .ui-summary:nth-child(1):before{background:#F59E0B}.ui-summary:nth-child(2):before{background:#2563EB}.ui-summary:nth-child(3):before{background:#059669}.ui-summary:nth-child(4):before{background:#DC2626}
    .ui-summary:hover{transform:translateY(-2px);border-color:#CBD5E1!important;box-shadow:0 10px 24px rgba(15,23,42,.07)!important}
    .ui-table-head{background:#F8FAFC!important;color:#64748B!important;border-color:#E2E8F0!important;font-size:.66rem!important;letter-spacing:.065em!important}
    .ui-row{transition:background-color .15s ease}.ui-row:hover{background:#F8FAFC!important}
    .ui-control{border-color:#CBD5E1!important;border-radius:.65rem!important;background:#fff!important}
    .ui-control:hover{border-color:#94A3B8!important}.ui-control:focus{border-color:#F59E0B!important;box-shadow:0 0 0 3px rgba(245,158,11,.12)!important;outline:none!important}
    .ui-modal-shell{border:1px solid rgba(255,255,255,.15)!important;border-radius:1rem!important;box-shadow:0 24px 70px rgba(15,23,42,.24)!important}
    @media(max-width:640px){.ui-summary{min-height:6.6rem}}

    .staff-primary-btn,.modal-submit-btn{background:#243B53!important;border-color:#243B53!important}.staff-primary-btn:hover,.modal-submit-btn:hover{background:#334E68!important}
    .staff-summary-label{color:#64748B!important;font-size:.7rem!important;letter-spacing:.055em!important}.staff-summary-number{color:#0F172A!important;font-size:2rem!important;letter-spacing:-.035em}.staff-summary-help{color:#94A3B8!important}
    .staff-summary-icon{width:2.8rem!important;height:2.8rem!important;border-radius:.8rem!important}.staff-summary-primary{background:#FFF7E6!important;color:#B96B00!important;border:1px solid #FDE3AE}.staff-summary-blue{background:#EFF6FF!important;border:1px solid #DBEAFE}.staff-summary-green{background:#ECFDF5!important;border:1px solid #D1FAE5}
    .staff-role-card{border-color:#E2E8F0!important;border-radius:1rem!important;box-shadow:0 3px 12px rgba(15,23,42,.04)!important}.staff-role-header{background:linear-gradient(180deg,#fff,#FCFDFE)!important;border-color:#E2E8F0!important}.staff-avatar{background:#F1F5F9!important;color:#334E68!important;border:1px solid #E2E8F0!important}
    .modal-input{border-color:#CBD5E1!important;border-radius:.65rem!important}.modal-input:focus{border-color:#F59E0B!important;box-shadow:0 0 0 3px rgba(245,158,11,.12)!important}

</style>

@endsection
