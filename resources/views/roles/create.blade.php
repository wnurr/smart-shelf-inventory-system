@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Role</h1>
        <p class="text-sm text-gray-500 mt-1">Create a role and assign its system permissions.</p>
    </div>

    <form method="POST" action="{{ route('roles.store') }}" id="roleForm" class="space-y-5">
        @csrf

        <div class="role-form-card">
            <div class="role-section-header">
                <div><h2 class="text-sm font-semibold text-gray-900">Role Information</h2><p class="text-xs text-gray-500 mt-1">Use a clear name that describes the staff responsibility.</p></div>
            </div>
            <div class="p-5">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Role Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required data-titlecase placeholder="e.g. Assistant Manager" class="w-full rounded-lg border-gray-300 text-sm focus:border-[#284B63] focus:ring-[#284B63]">
                @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="role-form-card">
            <div class="role-section-header flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div><h2 class="text-sm font-semibold text-gray-900">Permissions</h2><p class="text-xs text-gray-500 mt-1"><span id="permissionCount">0</span> of <span id="permissionTotal">0</span> selected</p></div>
                <button type="button" id="toggleAllPermissions" class="permission-master-btn">Select All Permissions</button>
            </div>

            <div class="p-5 space-y-5">
                @foreach($permissions as $group => $groupPermissions)
                    <section class="permission-group" data-permission-group>
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <div><p class="text-xs font-bold uppercase tracking-wide text-gray-500">{{ $group }}</p><p class="text-[11px] text-gray-400 mt-0.5"><span data-group-count>0</span> of {{ $groupPermissions->count() }} selected</p></div>
                            <button type="button" class="permission-group-btn" data-group-toggle>Select All</button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($groupPermissions as $permission)
                                <label class="permission-option">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" @checked(in_array($permission->id, old('permissions', []))) class="permission-checkbox rounded border-gray-300 text-[#284B63] focus:ring-[#284B63]">
                                    <span><span class="block text-sm font-medium text-gray-700">{{ $permission->label }}</span><span class="block text-[11px] text-gray-400 mt-0.5">{{ str_replace('_', ' ', $permission->name) }}</span></span>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-1">
            <a href="{{ route('roles.index') }}" class="role-cancel-btn">Cancel</a>
            <button type="submit" class="role-save-btn">Create Role</button>
        </div>
    </form>
</div>

<style>
.role-form-card{overflow:hidden;background:#fff;border:1px solid #E5E7EB;border-radius:.75rem}.role-section-header{display:flex;padding:1rem 1.25rem;background:#F9FAFB;border-bottom:1px solid #F3F4F6}.permission-master-btn{min-height:2.25rem;padding:0 .85rem;color:#284B63;background:#fff;border:1px solid #CBD5E1;border-radius:.5rem;font-size:.75rem;font-weight:700;white-space:nowrap}.permission-master-btn:hover{background:#EDF3F7;border-color:#284B63}.permission-group{padding:1rem;border:1px solid #E5E7EB;border-radius:.65rem;background:#fff}.permission-group-btn{color:#284B63;font-size:.72rem;font-weight:700}.permission-group-btn:hover{text-decoration:underline}.permission-option{display:flex;align-items:flex-start;gap:.7rem;padding:.75rem;border:1px solid #E5E7EB;border-radius:.55rem;cursor:pointer;transition:background-color .15s,border-color .15s}.permission-option:hover{background:#F9FAFB;border-color:#CBD5E1}.permission-option:has(input:checked){background:#F4F8FA;border-color:#9FB5C3}.permission-checkbox{margin-top:.15rem;flex-shrink:0}.role-cancel-btn{display:inline-flex;align-items:center;justify-content:center;min-height:2.5rem;padding:0 1rem;color:#6B7280;font-size:.8125rem;font-weight:600}.role-cancel-btn:hover{color:#374151}.role-save-btn{min-height:2.5rem;padding:0 1rem;color:#fff;background:#284B63;border:1px solid #284B63;border-radius:.5rem;font-size:.8125rem;font-weight:600}.role-save-btn:hover{background:#1F3A4C;border-color:#1F3A4C}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('roleForm');
    if (!form) return;
    const allCheckboxes = Array.from(form.querySelectorAll('.permission-checkbox'));
    const countEl = document.getElementById('permissionCount');
    const totalEl = document.getElementById('permissionTotal');
    const masterButton = document.getElementById('toggleAllPermissions');

    function refresh() {
        const selected = allCheckboxes.filter(cb => cb.checked).length;
        countEl.textContent = selected;
        totalEl.textContent = allCheckboxes.length;
        masterButton.textContent = allCheckboxes.length > 0 && selected === allCheckboxes.length ? 'Deselect All Permissions' : 'Select All Permissions';

        form.querySelectorAll('[data-permission-group]').forEach(group => {
            const boxes = Array.from(group.querySelectorAll('.permission-checkbox'));
            const checked = boxes.filter(cb => cb.checked).length;
            group.querySelector('[data-group-count]').textContent = checked;
            group.querySelector('[data-group-toggle]').textContent = boxes.length > 0 && checked === boxes.length ? 'Deselect All' : 'Select All';
        });
    }

    masterButton.addEventListener('click', function () {
        const shouldSelect = !allCheckboxes.length || !allCheckboxes.every(cb => cb.checked);
        allCheckboxes.forEach(cb => cb.checked = shouldSelect);
        refresh();
    });

    form.querySelectorAll('[data-group-toggle]').forEach(button => {
        button.addEventListener('click', function () {
            const group = button.closest('[data-permission-group]');
            const boxes = Array.from(group.querySelectorAll('.permission-checkbox'));
            const shouldSelect = !boxes.every(cb => cb.checked);
            boxes.forEach(cb => cb.checked = shouldSelect);
            refresh();
        });
    });

    allCheckboxes.forEach(cb => cb.addEventListener('change', refresh));
    refresh();
});
</script>
@endsection
