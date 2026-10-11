@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-6">
        <a href="{{ route('staff.index') }}" class="staff-back-link">← Back to Manage Staff</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-3">Manage Staff Access</h1>
        <p class="text-sm text-gray-500 mt-1">Review staff information, assign a role or reset account access.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4">
            <p class="text-sm font-semibold text-red-800 mb-1">Please check the information below.</p>
            <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('staff.update', $staff) }}" autocomplete="off" class="space-y-5">
        @csrf
        @method('PUT')

        <section class="staff-form-card">
            <div class="staff-form-header">
                <div class="staff-section-icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19a6 6 0 00-12 0m6-8a4 4 0 100-8 4 4 0 000 8z"/></svg></div>
                <div><h2 class="text-sm font-semibold text-gray-900">Staff Account</h2><p class="text-xs text-gray-500 mt-0.5">Personal information is maintained by the staff member from My Profile.</p></div>
            </div>
            <div class="p-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="staff-readonly md:col-span-2"><span>Full Name</span><strong>{{ $staff->name }}</strong></div>
                    <div class="staff-readonly"><span>Email Address</span><strong>{{ $staff->email }}</strong></div>
                    <div class="staff-readonly"><span>Phone Number</span><strong>{{ $staff->phone_no ?: 'Not provided' }}</strong></div>
                </div>
                <label class="staff-label" for="role">Role</label>
                <select class="staff-input" id="role" name="role" required>
                    <option value="">Select role</option>
                    @foreach($roles as $role)<option value="{{ $role->position }}" {{ old('role', $staff->role?->position) === $role->position ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$role->position)) }}</option>@endforeach
                </select>
                <p class="staff-help">Permissions are controlled by the selected role in Manage Roles.</p>
            </div>
        </section>

        <section class="staff-form-card">
            <div class="staff-form-header">
                <div class="staff-section-icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 0v8m-4 0h8"/></svg></div>
                <div><h2 class="text-sm font-semibold text-gray-900">Reset Staff Password</h2><p class="text-xs text-gray-500 mt-0.5">Optional administrative account recovery.</p></div>
            </div>
            <div class="p-5 sm:p-6">
                <div class="staff-info-box mb-5"><strong>Only reset when needed</strong><span>Leave both fields blank to keep the current password. A reset password becomes temporary and must be changed by the staff member after login.</span></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div><label class="staff-label" for="password">New Temporary Password</label><input class="staff-input" type="password" id="password" name="password" autocomplete="new-password"><p class="staff-help">Minimum 8 characters.</p></div>
                    <div><label class="staff-label" for="password_confirmation">Confirm Temporary Password</label><input class="staff-input" type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"></div>
                </div>
            </div>
        </section>

        <div class="flex justify-end items-center gap-3"><a href="{{ route('staff.index') }}" class="staff-cancel-btn">Cancel</a><button type="submit" class="staff-save-btn">Save Changes</button></div>
    </form>
</div>
<style>
.staff-back-link{color:#6B7280;font-size:.75rem;font-weight:600;text-decoration:none}.staff-back-link:hover{color:#284B63}.staff-form-card{overflow:hidden;background:#fff;border:1px solid #E5E7EB;border-radius:.75rem}.staff-form-header{display:flex;align-items:center;gap:.75rem;padding:1rem 1.25rem;background:#F9FAFB;border-bottom:1px solid #F3F4F6}.staff-section-icon{width:2.25rem;height:2.25rem;display:flex;align-items:center;justify-content:center;color:#284B63;background:#EDF3F7;border-radius:.55rem;flex-shrink:0}.staff-label{display:block;margin-bottom:.4rem;color:#374151;font-size:.78rem;font-weight:600}.staff-input{width:100%;height:2.65rem;border:1px solid #D1D5DB;border-radius:.5rem;font-size:.875rem;color:#111827}.staff-input:focus{border-color:#284B63;--tw-ring-color:#284B63}.staff-help{margin-top:.35rem;color:#9CA3AF;font-size:.68rem}.staff-readonly{padding:.75rem .85rem;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:.55rem}.staff-readonly span{display:block;color:#9CA3AF;font-size:.65rem}.staff-readonly strong{display:block;margin-top:.2rem;color:#374151;font-size:.8rem;font-weight:600;overflow-wrap:anywhere}.staff-info-box{display:flex;flex-direction:column;gap:.15rem;padding:.8rem 1rem;color:#284B63;background:#F5F8FA;border:1px solid #DCE8EF;border-radius:.6rem;font-size:.75rem}.staff-info-box span{color:#64748B;font-size:.7rem}.staff-cancel-btn,.staff-save-btn{min-height:2.625rem;display:inline-flex;align-items:center;justify-content:center;padding:0 1rem;border-radius:.5rem;font-size:.8125rem;font-weight:600}.staff-cancel-btn{color:#4B5563;background:#fff;border:1px solid #D1D5DB}.staff-cancel-btn:hover{background:#F9FAFB}.staff-save-btn{color:#fff;background:#284B63;border:1px solid #284B63}.staff-save-btn:hover{background:#1F3A4C;border-color:#1F3A4C}
</style>
@endsection
