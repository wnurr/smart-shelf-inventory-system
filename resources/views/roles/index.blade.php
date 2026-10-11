@extends('layouts.app')

@section('content')

<div
    class="max-w-7xl mx-auto px-4 sm:px-6 py-8"
    x-data="{
        showAddRoleModal: {{ $errors->any() && old('_form_mode') !== 'edit_role' ? 'true' : 'false' }},
        showEditRoleModal: {{ old('_form_mode') === 'edit_role' ? 'true' : 'false' }},
        editRole: {
            id: @js(old('_edit_role_id')),
            name: @js(old('name', '')),
            updateUrl: @js(old('_edit_role_url', '')),
            permissions: @js(array_map('strval', old('permissions', [])))
        },
        openEditRoleFromButton(button) {
            this.editRole = {
                id: button.dataset.roleId,
                name: button.dataset.roleName,
                updateUrl: button.dataset.updateUrl,
                permissions: (button.dataset.permissions || '')
                    .split(',')
                    .filter(Boolean)
            };
            this.showEditRoleModal = true;
            this.$nextTick(() => this.refreshEditPermissionLabels());
        },
        closeEditRole() {
            this.showEditRoleModal = false;
        },
        isEditPermissionChecked(id) {
            return this.editRole.permissions.map(String).includes(String(id));
        },
        toggleEditPermission(id) {
            id = String(id);
            const current = this.editRole.permissions.map(String);
            this.editRole.permissions = current.includes(id)
                ? current.filter(value => value !== id)
                : [...current, id];
            this.$nextTick(() => this.refreshEditPermissionLabels());
        },
        toggleAllEditPermissions() {
            const all = Array.from(this.$refs.editRoleForm.querySelectorAll('[data-edit-permission]'))
                .map(el => String(el.value));
            const selected = this.editRole.permissions.map(String);
            this.editRole.permissions = all.length > 0 && all.every(id => selected.includes(id)) ? [] : all;
            this.$nextTick(() => this.refreshEditPermissionLabels());
        },
        toggleEditGroup(groupName) {
            const boxes = Array.from(this.$refs.editRoleForm.querySelectorAll(`[data-edit-group='${groupName}']`));
            const ids = boxes.map(el => String(el.value));
            const selected = this.editRole.permissions.map(String);
            const allSelected = ids.length > 0 && ids.every(id => selected.includes(id));

            this.editRole.permissions = allSelected
                ? selected.filter(id => !ids.includes(id))
                : Array.from(new Set([...selected, ...ids]));

            this.$nextTick(() => this.refreshEditPermissionLabels());
        },
        refreshEditPermissionLabels() {
            if (!this.$refs.editRoleForm) return;

            const all = Array.from(this.$refs.editRoleForm.querySelectorAll('[data-edit-permission]'));
            const selected = this.editRole.permissions.map(String);
            const count = this.$refs.editRoleForm.querySelector('[data-edit-count]');
            const total = this.$refs.editRoleForm.querySelector('[data-edit-total]');
            const master = this.$refs.editRoleForm.querySelector('[data-edit-master]');

            if (count) count.textContent = selected.length;
            if (total) total.textContent = all.length;
            if (master) {
                master.textContent = all.length > 0 && all.every(el => selected.includes(String(el.value)))
                    ? 'Deselect All Permissions'
                    : 'Select All Permissions';
            }

            this.$refs.editRoleForm.querySelectorAll('[data-edit-permission-group]').forEach(group => {
                const boxes = Array.from(group.querySelectorAll('[data-edit-permission]'));
                const checked = boxes.filter(el => selected.includes(String(el.value))).length;
                const countEl = group.querySelector('[data-edit-group-count]');
                const button = group.querySelector('[data-edit-group-toggle]');

                if (countEl) countEl.textContent = checked;
                if (button) button.textContent = boxes.length > 0 && checked === boxes.length ? 'Deselect All' : 'Select All';
            });
        }
    }"
    x-init="
        const syncBodyLock = () => document.body.classList.toggle('overflow-hidden', showAddRoleModal || showEditRoleModal);
        $watch('showAddRoleModal', syncBodyLock);
        $watch('showEditRoleModal', syncBodyLock);
        syncBodyLock();
        $nextTick(() => { if (showEditRoleModal) refreshEditPermissionLabels(); });
    "
>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">
        <div>
            <div class="ui-eyebrow"><span></span>ACCESS CONTROL</div>
            <h1 class="text-2xl font-bold text-gray-900 ui-page-title mt-2">Manage Roles</h1>
            <p class="text-sm text-gray-500 mt-1 ui-page-subtitle">Create roles and control access to system modules.</p>
        </div>

        <button type="button" @click="showAddRoleModal = true" class="role-primary-btn">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Role
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="role-summary-card ui-summary">
            <div>
                <p class="role-summary-label">Total Roles</p>
                <p class="role-summary-number">{{ $totalRoles }}</p>
                <p class="role-summary-help">Non-admin roles configured</p>
            </div>
            <div class="role-summary-icon role-summary-primary">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
            </div>
        </div>

        <div class="role-summary-card ui-summary">
            <div>
                <p class="role-summary-label">Assigned Staff</p>
                <p class="role-summary-number">{{ $assignedStaffCount }}</p>
                <p class="role-summary-help">Staff using managed roles</p>
            </div>
            <div class="role-summary-icon role-summary-blue">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <div class="role-summary-card ui-summary">
            <div>
                <p class="role-summary-label">Available Permissions</p>
                <p class="role-summary-number">{{ $availablePermissionCount }}</p>
                <p class="role-summary-help">Assignable system permissions</p>
            </div>
            <div class="role-summary-icon role-summary-green">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                </svg>
            </div>
        </div>
    </div>

    <x-search-filter
        :action="route('roles.index')"
        :search="$search"
        placeholder="Search role name..."
        :clear-url="route('roles.index')"
        :show-clear="$search !== '' || $sort !== 'asc'"
        class="mb-5"
    >
        <select name="sort" class="h-10 rounded-lg border-gray-300 text-sm text-gray-700 focus:border-[#F59E0B] focus:ring-[#F59E0B]">
            <option value="asc" @selected($sort === 'asc')>Role Name: A → Z</option>
            <option value="desc" @selected($sort === 'desc')>Role Name: Z → A</option>
        </select>
    </x-search-filter>

    <div class="role-table-card ui-surface">
        <div class="role-table-header">
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Role Directory</h2>
                <p class="text-xs text-gray-500 mt-0.5">Roles, assigned staff and permission coverage.</p>
            </div>
            <span class="role-count-badge">{{ $roles->total() }} {{ \Illuminate\Support\Str::plural('role', $roles->total()) }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="role-table-head ui-table-head">
                        <th class="px-5 py-3 text-left w-16">No.</th>
                            <th class="px-5 py-3 text-left">Role</th>
                        <th class="px-5 py-3 text-left">Staff</th>
                        <th class="px-5 py-3 text-left">Permissions</th>
                        <th class="px-5 py-3 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($roles as $role)
                        @php
                            $isDefault = in_array($role->position, ['store_manager', 'store_keeper'], true);
                        @endphp

                        <tr class="role-row ui-row">
                                        <td class="px-5 py-4 align-top"><x-table-number :loop="$loop" :paginator="$roles" /></td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $role->position)) }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $isDefault ? 'Default system role' : 'Custom role' }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <span class="role-staff-badge">{{ $role->users_count }} {{ \Illuminate\Support\Str::plural('staff', $role->users_count) }}</span>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 min-w-[180px]">
                                    <div class="role-progress">
                                        <span style="width: {{ $availablePermissionCount > 0 ? min(100, ($role->permissions_count / $availablePermissionCount) * 100) : 0 }}%"></span>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600 whitespace-nowrap">{{ $role->permissions_count }} / {{ $availablePermissionCount }}</span>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <x-table-action-button
                                        variant="edit"
                                        data-role-id="{{ $role->id }}"
                                        data-role-name="{{ ucwords(str_replace('_', ' ', $role->position)) }}"
                                        data-update-url="{{ route('roles.update', $role) }}"
                                        data-permissions="{{ $role->permissions->pluck('id')->implode(',') }}"
                                        @click="openEditRoleFromButton($el)"
                                    >
                                        Edit
                                    </x-table-action-button>

                                    @if(!$isDefault && $role->users_count === 0)
                                        <form
                                            method="POST"
                                            action="{{ route('roles.destroy', $role) }}"
                                            class="js-confirm-form inline-block"
                                            data-confirm-title="Delete Role"
                                            data-confirm-message="Are you sure you want to delete {{ ucwords(str_replace('_', ' ', $role->position)) }}? This action cannot be undone."
                                            data-confirm-button="Delete"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <x-table-action-button type="submit" variant="delete">
                                                Delete
                                            </x-table-action-button>
                                        </form>
                                    @elseif(!$isDefault)
                                        <span class="role-delete-disabled" title="Reassign all staff before deleting this role.">In Use</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="role-empty-icon">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 12l2 2 4-4m5-4a11 11 0 01-8-3 11 11 0 01-8 3c0 5.25 3.4 10.74 8 12 4.6-1.26 8-6.75 8-12z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-700 mt-3">{{ $search !== '' ? 'No matching roles' : 'No roles found' }}</h3>
                                <p class="text-xs text-gray-400 mt-1">{{ $search !== '' ? 'Try a different role name.' : 'Create a role to get started.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-simple-pagination :paginator="$roles" />
    </div>


    {{-- ========================================================= --}}
    {{-- ADD ROLE MODAL --}}
    {{-- ========================================================= --}}

    <div
        x-show="showAddRoleModal"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="showAddRoleModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div
            x-show="showAddRoleModal"
            x-transition
            @click.outside="showAddRoleModal = false"
            class="w-full max-w-3xl max-h-[92vh] overflow-hidden rounded-2xl bg-white shadow-2xl"
        >
            <div class="flex items-center justify-between gap-4 bg-[#284B63] px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5-4a11 11 0 01-8-3 11 11 0 01-8 3c0 5.25 3.4 10.74 8 12 4.6-1.26 8-6.75 8-12z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Add Role</h2>
                        <p class="mt-0.5 text-xs text-white/70">Create a role and choose which system modules it can access.</p>
                    </div>
                </div>

                <button type="button" @click="showAddRoleModal = false" class="rounded-lg p-2 text-white/70 hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <input type="hidden" name="_form_mode" value="add_role">

                <div class="max-h-[65vh] overflow-y-auto px-6 py-6">

                    <div class="modal-section-title">ROLE INFORMATION</div>
                    <p class="modal-section-help">Give the role a clear name. It will be stored in system format automatically.</p>
                    <div class="modal-divider"></div>

                    <div>
                        <label for="role_name" class="modal-label">Role Name <span class="text-red-500">*</span></label>
                        <input
                            id="role_name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            maxlength="100"
                            class="modal-input"
                            placeholder="e.g. Assistant Manager"
                        >
                        @error('name') <p class="modal-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-7 modal-section-title">MODULE PERMISSIONS</div>
                    <div class="modal-divider"></div>

                    <div class="space-y-4">
                        @forelse($permissions as $group => $groupPermissions)
                            <div class="rounded-xl border border-gray-200 p-4">
                                <div class="mb-3">
                                    <h3 class="text-sm font-bold text-gray-800">{{ ucwords(str_replace('_', ' ', $group)) }}</h3>
                                    <p class="mt-0.5 text-xs text-gray-400">Choose access available under this module group.</p>
                                </div>

                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    @foreach($groupPermissions as $permission)
                                        <label class="permission-option">
                                            <input
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->id }}"
                                                @checked(in_array((string) $permission->id, array_map('strval', old('permissions', [])), true))
                                                class="rounded border-gray-300 text-[#284B63] focus:ring-[#F59E0B]"
                                            >
                                            <span>
                                                <span class="block text-xs font-semibold text-gray-700">{{ $permission->label }}</span>
                                                
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-gray-300 p-6 text-center text-sm text-gray-400">
                                No assignable permissions are available.
                            </div>
                        @endforelse
                    </div>

                    @error('permissions') <p class="modal-error mt-2">{{ $message }}</p> @enderror
                    @error('permissions.*') <p class="modal-error mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
                    <button type="button" @click="showAddRoleModal = false" class="modal-cancel-btn">Cancel</button>
                    <button type="submit" class="modal-submit-btn">Add Role</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- EDIT ROLE MODAL --}}
    {{-- ========================================================= --}}

    <div
        x-show="showEditRoleModal"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="closeEditRole()"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    >
        <div
            x-show="showEditRoleModal"
            x-transition
            @click.outside="closeEditRole()"
            class="w-full max-w-3xl max-h-[92vh] overflow-hidden rounded-2xl bg-white shadow-2xl"
        >
            <div class="flex items-center justify-between gap-4 bg-[#284B63] px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 4H4v7m16-7h-5m5 0v5M4 20h5m-5 0v-5m16 5h-7m7 0v-7M8 12h8"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Edit Role</h2>
                        <p class="mt-0.5 text-xs text-white/70">Update the role name and its system permissions.</p>
                    </div>
                </div>
                <button type="button" @click="closeEditRole()" class="rounded-lg p-2 text-white/70 hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" :action="editRole.updateUrl" x-ref="editRoleForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form_mode" value="edit_role">
                <input type="hidden" name="_edit_role_id" :value="editRole.id">
                <input type="hidden" name="_edit_role_url" :value="editRole.updateUrl">

                <div class="max-h-[65vh] overflow-y-auto px-6 py-6">

                    <div class="modal-section-title">ROLE INFORMATION</div>
                    <p class="modal-section-help">Use a clear name that describes the staff responsibility.</p>
                    <div class="modal-divider"></div>

                    <div>
                        <label class="modal-label" for="edit_role_name">Role Name <span class="text-red-500">*</span></label>
                        <input id="edit_role_name" name="name" type="text" x-model="editRole.name" required maxlength="100" class="modal-input" placeholder="e.g. Assistant Manager">
                        @error('name') @if(old('_form_mode') === 'edit_role')<p class="modal-error">{{ $message }}</p>@endif @enderror
                    </div>

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div class="modal-section-title">MODULE PERMISSIONS</div>
                        </div>
                        <button type="button" data-edit-master @click="toggleAllEditPermissions()" class="permission-master-btn">Select All Permissions</button>
                    </div>
                    <div class="modal-divider"></div>

                    <div class="space-y-4">
                        @foreach($permissions as $group => $groupPermissions)
                            <section class="permission-group" data-edit-permission-group>
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wide text-gray-500">{{ $group }}</p>
                                        <p class="mt-0.5 text-[11px] text-gray-400"><span data-edit-group-count>0</span> of {{ $groupPermissions->count() }} selected</p>
                                    </div>
                                    <button type="button" data-edit-group-toggle data-group-name="{{ $group }}" @click="toggleEditGroup($el.dataset.groupName)" class="permission-group-btn">Select All</button>
                                </div>

                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    @foreach($groupPermissions as $permission)
                                        <label class="permission-option">
                                            <input
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->id }}"
                                                data-edit-permission
                                                data-edit-group="{{ $group }}"
                                                :checked="isEditPermissionChecked(@js((string) $permission->id))"
                                                @change="toggleEditPermission(@js((string) $permission->id))"
                                                class="permission-checkbox rounded border-gray-300 text-[#284B63] focus:ring-[#F59E0B]"
                                            >
                                            <span>
                                                <span class="block text-sm font-medium text-gray-700">{{ $permission->label }}</span>
                                            
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 bg-gray-50 px-6 py-4">
                    <button type="button" @click="closeEditRole()" class="modal-cancel-btn">Cancel</button>
                    <button type="submit" class="modal-submit-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
[x-cloak]{display:none!important}
.role-primary-btn{min-height:2.625rem;display:inline-flex;align-items:center;justify-content:center;gap:.45rem;padding:0 1rem;border-radius:.5rem;font-size:.8125rem;font-weight:600;color:#fff;background:#284B63;border:1px solid #284B63;text-decoration:none}.role-primary-btn:hover{background:#1F3A4C;border-color:#1F3A4C}
.role-summary-card{min-height:7rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1.25rem;background:#fff;border:1px solid #E5E7EB;border-radius:.75rem}.role-summary-label{color:#6B7280;font-size:.75rem;font-weight:500}.role-summary-number{margin-top:.45rem;color:#111827;font-size:1.75rem;line-height:1;font-weight:700}.role-summary-help{margin-top:.5rem;color:#9CA3AF;font-size:.68rem}.role-summary-icon{width:2.5rem;height:2.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;border-radius:.6rem}.role-summary-primary{color:#284B63;background:#EDF3F7}.role-summary-blue{color:#1D4ED8;background:#DBEAFE}.role-summary-green{color:#15803D;background:#DCFCE7}
.role-table-card{overflow:hidden;background:#fff;border:1px solid #E5E7EB;border-radius:.75rem}.role-table-header{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid #F3F4F6}.role-count-badge{padding:.3rem .6rem;color:#284B63;background:#EDF3F7;border-radius:9999px;font-size:.7rem;font-weight:700}.role-table-head{color:#6B7280;background:#F9FAFB;border-bottom:1px solid #F3F4F6;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.035em}.role-row{transition:background-color .15s ease}.role-row:hover{background:#FAFAFA}.role-staff-badge{display:inline-flex;padding:.3rem .55rem;color:#4B5563;background:#F3F4F6;border-radius:9999px;font-size:.7rem;font-weight:600}.role-progress{width:7rem;height:.4rem;overflow:hidden;background:#E5E7EB;border-radius:9999px}.role-progress span{display:block;height:100%;background:#284B63;border-radius:9999px}.role-edit-btn:hover{text-decoration:underline}.role-delete-btn:hover{text-decoration:underline}.role-delete-disabled{color:#9CA3AF;font-size:.75rem;font-weight:600;cursor:not-allowed}.role-empty-icon{width:3rem;height:3rem;margin:0 auto;display:flex;align-items:center;justify-content:center;color:#9CA3AF;background:#F3F4F6;border-radius:9999px}
.modal-section-title{color:#374151;font-size:.7rem;font-weight:800;letter-spacing:.08em}.modal-section-help{margin-top:.25rem;color:#9CA3AF;font-size:.72rem}.modal-divider{margin:.75rem 0 1.1rem;border-top:1px solid #E5E7EB}.modal-label{display:block;margin-bottom:.4rem;color:#374151;font-size:.75rem;font-weight:600}.modal-input{width:100%;min-height:2.65rem;border:1px solid #D1D5DB;border-radius:.55rem;padding:.6rem .75rem;color:#111827;background:#fff;font-size:.8125rem}.modal-input:focus{border-color:#284B63;outline:none;box-shadow:0 0 0 1px #284B63}.modal-error{margin-top:.3rem;color:#DC2626;font-size:.7rem}.modal-cancel-btn{min-height:2.5rem;padding:0 1rem;border:1px solid #D1D5DB;border-radius:.5rem;background:#fff;color:#4B5563;font-size:.8rem;font-weight:600}.modal-cancel-btn:hover{background:#F9FAFB}.modal-submit-btn{min-height:2.5rem;padding:0 1rem;border:1px solid #284B63;border-radius:.5rem;background:#284B63;color:#fff;font-size:.8rem;font-weight:600}.modal-submit-btn:hover{background:#1F3A4C;border-color:#1F3A4C}.permission-option{display:flex;align-items:flex-start;gap:.65rem;padding:.7rem;border:1px solid #E5E7EB;border-radius:.55rem;cursor:pointer;transition:.15s}.permission-option:hover{background:#F9FAFB;border-color:#CBD5E1}
.permission-master-btn{min-height:2.25rem;padding:0 .85rem;color:#284B63;background:#fff;border:1px solid #CBD5E1;border-radius:.5rem;font-size:.75rem;font-weight:700;white-space:nowrap}.permission-master-btn:hover{background:#EDF3F7;border-color:#284B63}.permission-group{padding:1rem;border:1px solid #E5E7EB;border-radius:.65rem;background:#fff}.permission-group-btn{color:#284B63;font-size:.72rem;font-weight:700}.permission-group-btn:hover{text-decoration:underline}.permission-option:has(input:checked){background:#F4F8FA;border-color:#9FB5C3}.permission-checkbox{margin-top:.15rem;flex-shrink:0}

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

    .role-primary-btn,.modal-submit-btn{background:#243B53!important;border-color:#243B53!important}.role-primary-btn:hover,.modal-submit-btn:hover{background:#334E68!important}
    .role-summary-label{color:#64748B!important;font-size:.7rem!important;letter-spacing:.055em!important}.role-summary-number{color:#0F172A!important;font-size:2rem!important;letter-spacing:-.035em}.role-summary-help{color:#94A3B8!important}
    .role-summary-icon{width:2.8rem!important;height:2.8rem!important;border-radius:.8rem!important}.role-summary-primary{background:#FFF7E6!important;color:#B96B00!important;border:1px solid #FDE3AE}.role-summary-blue{background:#EFF6FF!important;border:1px solid #DBEAFE}.role-summary-green{background:#ECFDF5!important;border:1px solid #D1FAE5}
    .role-table-card{border-color:#E2E8F0!important;border-radius:1rem!important;box-shadow:0 3px 12px rgba(15,23,42,.04)!important}.role-table-header{background:linear-gradient(180deg,#fff,#FCFDFE)!important;border-color:#E2E8F0!important}.role-count-badge{background:#F1F5F9!important;border:1px solid #E2E8F0!important;color:#334E68!important}
    .permission-group{border-color:#E2E8F0!important;border-radius:.8rem!important}.permission-option:hover{background:#F8FAFC!important}.modal-input{border-color:#CBD5E1!important;border-radius:.65rem!important}.modal-input:focus{border-color:#F59E0B!important;box-shadow:0 0 0 3px rgba(245,158,11,.12)!important}

</style>

@endsection
