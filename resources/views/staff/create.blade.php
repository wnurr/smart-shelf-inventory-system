@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-6">
        <a href="{{ route('staff.index') }}" class="staff-back-link">← Back to Manage Staff</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-3">Add Staff</h1>
        <p class="text-sm text-gray-500 mt-1">Create a staff account and assign the appropriate system role.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4">
            <p class="text-sm font-semibold text-red-800 mb-1">Please check the information below.</p>
            <ul class="text-sm text-red-700 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('staff.store') }}" autocomplete="off" class="space-y-5">
        @csrf
        <div style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden" aria-hidden="true">
            <input type="text" name="fake_username" autocomplete="username" tabindex="-1">
            <input type="password" name="fake_password" autocomplete="current-password" tabindex="-1">
        </div>

        <section class="staff-form-card">
            <div class="staff-form-header">
                <div class="staff-section-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19a6 6 0 00-12 0m6-8a4 4 0 100-8 4 4 0 000 8zm6 1h6m-3-3v6"/></svg>
                </div>
                <div><h2 class="text-sm font-semibold text-gray-900">Staff Information</h2><p class="text-xs text-gray-500 mt-0.5">Basic account details and role assignment.</p></div>
            </div>
            <div class="p-5 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2"><label class="staff-label" for="name">Full Name</label><input class="staff-input" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter staff name" required data-titlecase autocomplete="off"></div>
                <div><label class="staff-label" for="email">Email Address</label><input class="staff-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="staff@example.com" required autocomplete="off" autocapitalize="none" spellcheck="false"></div>
                <div><label class="staff-label" for="phone_no">Phone Number</label><input class="staff-input" type="text" id="phone_no" name="phone_no" value="{{ old('phone_no') }}" placeholder="e.g. 0123456789" autocomplete="off" inputmode="numeric"></div>
                <div class="md:col-span-2"><label class="staff-label" for="role">Role</label><select class="staff-input" id="role" name="role" required><option value="">Select role</option>@foreach($roles as $role)<option value="{{ $role->position }}" {{ old('role') === $role->position ? 'selected' : '' }}>{{ ucwords(str_replace('_',' ',$role->position)) }}</option>@endforeach</select><p class="staff-help">Permissions are controlled by the selected role in Manage Roles.</p></div>
            </div>
        </section>

        <section class="staff-form-card">
            <div class="staff-form-header">
                <div class="staff-section-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 0v8m-4 0h8"/></svg>
                </div>
                <div><h2 class="text-sm font-semibold text-gray-900">Account Access</h2><p class="text-xs text-gray-500 mt-0.5">Set the temporary password used for the first login.</p></div>
            </div>
            <div class="p-5 sm:p-6">
                <div class="staff-info-box mb-5"><strong>Temporary password</strong><span>The staff member will be required to change this password after their first login.</span></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div><label class="staff-label" for="password">Temporary Password</label><input class="staff-input" type="password" id="password" name="password" required autocomplete="new-password"><p class="staff-help">Minimum 8 characters.</p></div>
                    <div><label class="staff-label" for="password_confirmation">Confirm Temporary Password</label><input class="staff-input" type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"></div>
                </div>
            </div>
        </section>

        <div class="flex justify-end items-center gap-3"><a href="{{ route('staff.index') }}" class="staff-cancel-btn">Cancel</a><button type="submit" class="staff-save-btn">Create Staff</button></div>
    </form>
</div>
<style>
.staff-back-link{color:#6B7280;font-size:.75rem;font-weight:600;text-decoration:none}.staff-back-link:hover{color:#284B63}.staff-form-card{overflow:hidden;background:#fff;border:1px solid #E5E7EB;border-radius:.75rem}.staff-form-header{display:flex;align-items:center;gap:.75rem;padding:1rem 1.25rem;background:#F9FAFB;border-bottom:1px solid #F3F4F6}.staff-section-icon{width:2.25rem;height:2.25rem;display:flex;align-items:center;justify-content:center;color:#284B63;background:#EDF3F7;border-radius:.55rem;flex-shrink:0}.staff-label{display:block;margin-bottom:.4rem;color:#374151;font-size:.78rem;font-weight:600}.staff-input{width:100%;height:2.65rem;border:1px solid #D1D5DB;border-radius:.5rem;font-size:.875rem;color:#111827}.staff-input:focus{border-color:#284B63;--tw-ring-color:#284B63}.staff-help{margin-top:.35rem;color:#9CA3AF;font-size:.68rem}.staff-info-box{display:flex;flex-direction:column;gap:.15rem;padding:.8rem 1rem;color:#284B63;background:#F5F8FA;border:1px solid #DCE8EF;border-radius:.6rem;font-size:.75rem}.staff-info-box span{color:#64748B;font-size:.7rem}.staff-cancel-btn,.staff-save-btn{min-height:2.625rem;display:inline-flex;align-items:center;justify-content:center;padding:0 1rem;border-radius:.5rem;font-size:.8125rem;font-weight:600}.staff-cancel-btn{color:#4B5563;background:#fff;border:1px solid #D1D5DB}.staff-cancel-btn:hover{background:#F9FAFB}.staff-save-btn{color:#fff;background:#284B63;border:1px solid #284B63}.staff-save-btn:hover{background:#1F3A4C;border-color:#1F3A4C}
</style>
@endsection
