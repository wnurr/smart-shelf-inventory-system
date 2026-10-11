@extends('layouts.app')

@section('content')
@php
    $roleLabel = ucwords(str_replace('_', ' ', $user->role?->position ?? 'Staff'));
    $initials = collect(preg_split('/\s+/', trim($user->name)))
        ->filter()->take(2)->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-7 flex items-start justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 mb-2 text-[11px] font-bold uppercase tracking-[.14em] text-amber-600"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Account Settings</div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">My Profile</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your personal information, profile picture and account password.</p>
        </div>
    </div>

    @if ($errors->any() && !$errors->updatePassword->any())
        <div class="profile-error mb-5">
            <p class="text-sm font-semibold text-red-800">Please check your profile information.</p>
            <ul class="text-sm text-red-700 list-disc pl-5 mt-1 space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <aside class="profile-card lg:col-span-1 h-fit">
            <div class="p-6 text-center">
                <div class="profile-avatar-wrap mx-auto">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="profile-avatar-image">
                    @else
                        <div class="profile-avatar-fallback">{{ $initials ?: 'U' }}</div>
                    @endif
                </div>
                <h2 class="text-lg font-bold text-gray-900 mt-4">{{ $user->name }}</h2>
                <span class="profile-role-badge mt-2">{{ $roleLabel }}</span>
                <div class="profile-summary mt-5">
                    <div><span>Email</span><strong>{{ $user->email }}</strong></div>
                    <div><span>Phone</span><strong>{{ $user->phone_no ?: 'Not provided' }}</strong></div>
                </div>
            </div>
        </aside>

        <div class="lg:col-span-2 space-y-5">
            <section class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-section-icon">
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
</svg>
                    </div>
                    <div><h2 class="text-sm font-semibold text-gray-900">Personal Information</h2><p class="text-xs text-gray-500 mt-0.5">Keep your own contact information up to date.</p></div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-5 sm:p-6">
                    @csrf
                    @method('PATCH')

                    <div class="mb-5">
                        <label class="profile-label" for="profile_photo">Profile Picture</label>
                        <div class="profile-upload-row">
                            <div class="profile-mini-avatar" id="photoPreviewWrap">
                                @if($user->profile_photo)
                                    <img id="photoPreview" src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile preview">
                                @else
                                    <span id="photoInitials">{{ $initials ?: 'U' }}</span>
                                    <img id="photoPreview" class="hidden" alt="Profile preview">
                                @endif
                            </div>
                            <div class="flex-1">
                                <input class="profile-file-input" type="file" id="profile_photo" name="profile_photo" accept="image/png,image/jpeg,image/webp">
                                <p class="profile-help">JPG, PNG or WEBP. Maximum file size 2 MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2"><label class="profile-label" for="name">Full Name</label><input class="profile-input" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required data-titlecase autocomplete="name"></div>
                        <div><label class="profile-label" for="email">Email Address</label><input class="profile-input" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="email" autocapitalize="none"></div>
                        <div><label class="profile-label" for="phone_no">Phone Number</label><input class="profile-input" id="phone_no" name="phone_no" type="text" value="{{ old('phone_no', $user->phone_no) }}" placeholder="e.g. 0123456789" inputmode="numeric" autocomplete="tel"></div>
                        <div class="md:col-span-2"><label class="profile-label">Role</label><div class="profile-readonly">{{ $roleLabel }}<span>Role assignment is managed by the administrator.</span></div></div>
                    </div>

                    <div class="flex justify-end mt-6"><button class="profile-primary-btn" type="submit">Save Profile</button></div>
                </form>
            </section>

            <section class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-section-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 0v8m-4 0h8"/></svg>
                    </div>
                    <div><h2 class="text-sm font-semibold text-gray-900">Account Security</h2><p class="text-xs text-gray-500 mt-0.5">Change your password using your current password.</p></div>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="p-5 sm:p-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2"><label class="profile-label" for="current_password">Current Password</label><input class="profile-input" id="current_password" name="current_password" type="password" autocomplete="current-password">@foreach($errors->updatePassword->get('current_password') as $message)<p class="profile-field-error">{{ $message }}</p>@endforeach</div>
                        <div><label class="profile-label" for="password">New Password</label><input class="profile-input" id="password" name="password" type="password" autocomplete="new-password">@foreach($errors->updatePassword->get('password') as $message)<p class="profile-field-error">{{ $message }}</p>@endforeach</div>
                        <div><label class="profile-label" for="password_confirmation">Confirm New Password</label><input class="profile-input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"></div>
                    </div>
                    <p class="profile-help mt-3">Use at least 8 characters and avoid reusing your temporary password.</p>
                    <div class="flex justify-end mt-6"><button class="profile-primary-btn" type="submit">Change Password</button></div>
                </form>
            </section>
        </div>
    </div>
</div>

<style>
:root{--steel:#243B53;--navy:#334E68;--amber:#F59E0B;--line:#E2E8F0;--muted:#64748B;--soft:#F8FAFC}
.profile-card{overflow:hidden;background:#fff;border:1px solid var(--line);border-radius:1rem;box-shadow:0 8px 24px rgba(15,23,42,.045);transition:box-shadow .2s ease,border-color .2s ease}.profile-card:hover{border-color:#CBD5E1;box-shadow:0 12px 30px rgba(15,23,42,.065)}
.profile-card-header{display:flex;align-items:center;gap:.85rem;padding:1.1rem 1.35rem;background:linear-gradient(180deg,#fff,#F8FAFC);border-bottom:1px solid #E9EEF4}.profile-section-icon{width:2.5rem;height:2.5rem;display:flex;align-items:center;justify-content:center;color:var(--steel);background:#EDF3F7;border:1px solid #DCE6EC;border-radius:.7rem;flex-shrink:0}
.profile-avatar-wrap{position:relative;width:7.5rem;height:7.5rem;border-radius:9999px;overflow:hidden;border:5px solid #fff;box-shadow:0 0 0 1px #DCE5EA,0 8px 24px rgba(36,59,83,.14)}.profile-avatar-image,.profile-mini-avatar img{width:100%;height:100%;object-fit:cover}.profile-avatar-fallback{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(145deg,var(--steel),var(--navy));color:#fff;font-size:1.8rem;font-weight:800;letter-spacing:.03em}
.profile-role-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .7rem;color:#8A5700;background:#FFF7E6;border:1px solid #FDE2A7;border-radius:9999px;font-size:.7rem;font-weight:800;letter-spacing:.02em}.profile-role-badge:before{content:'';width:.38rem;height:.38rem;border-radius:50%;background:var(--amber)}
.profile-summary{margin-top:1.4rem;border-top:1px solid #EEF2F6;text-align:left}.profile-summary div{padding:.9rem 0;border-bottom:1px solid #EEF2F6}.profile-summary span{display:block;color:#94A3B8;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.055em}.profile-summary strong{display:block;color:#334155;font-size:.8rem;font-weight:650;margin-top:.22rem;overflow-wrap:anywhere}
.profile-label{display:block;margin-bottom:.45rem;color:#475569;font-size:.73rem;font-weight:750;letter-spacing:.015em}.profile-input{width:100%;height:2.8rem;padding:0 .85rem;border:1px solid #CBD5E1;border-radius:.65rem;background:#fff;font-size:.875rem;color:#0F172A;transition:border-color .15s,box-shadow .15s}.profile-input:hover{border-color:#94A3B8}.profile-input:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 3px rgba(51,78,104,.10);--tw-ring-color:transparent}.profile-help{color:#94A3B8;font-size:.69rem;line-height:1.45}
.profile-upload-row{display:flex;align-items:center;gap:1rem;padding:1rem;background:var(--soft);border:1px dashed #CBD5E1;border-radius:.8rem}.profile-mini-avatar{width:3.75rem;height:3.75rem;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;border-radius:9999px;color:var(--steel);background:#EAF1F5;border:1px solid #D7E2E9;font-size:.95rem;font-weight:800}.profile-file-input{display:block;width:100%;font-size:.75rem;color:#64748B}.profile-file-input::file-selector-button{margin-right:.75rem;padding:.55rem .85rem;border:1px solid #CBD5E1;border-radius:.55rem;background:#fff;color:#334155;font-size:.72rem;font-weight:700;cursor:pointer;transition:.15s}.profile-file-input::file-selector-button:hover{border-color:#94A3B8;background:#F8FAFC}
.profile-readonly{min-height:2.8rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.7rem .85rem;border:1px solid #E2E8F0;border-radius:.65rem;background:#F8FAFC;color:#334155;font-size:.85rem;font-weight:700}.profile-readonly span{color:#94A3B8;font-size:.66rem;font-weight:500;text-align:right}
.profile-primary-btn{min-height:2.7rem;display:inline-flex;align-items:center;justify-content:center;padding:0 1.1rem;border:1px solid var(--steel);border-radius:.6rem;background:var(--steel);color:#fff;font-size:.8rem;font-weight:750;box-shadow:0 3px 8px rgba(36,59,83,.15);transition:.15s}.profile-primary-btn:hover{background:#1B3044;border-color:#1B3044;transform:translateY(-1px);box-shadow:0 5px 12px rgba(36,59,83,.2)}.profile-primary-btn:active{transform:translateY(0)}
.profile-error{padding:1rem 1.1rem;background:#FFF7F7;border:1px solid #FECACA;border-left:4px solid #DC2626;border-radius:.75rem}.profile-field-error{margin-top:.4rem;color:#B42318;font-size:.7rem;font-weight:600}
@media(max-width:640px){.profile-upload-row{align-items:flex-start}.profile-readonly{align-items:flex-start;flex-direction:column;gap:.3rem}.profile-readonly span{text-align:left}.profile-primary-btn{width:100%}}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('profile_photo');
    const preview = document.getElementById('photoPreview');
    const initials = document.getElementById('photoInitials');
    if (!input || !preview) return;
    input.addEventListener('change', event => {
        const file = event.target.files?.[0];
        if (!file) return;
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        if (initials) initials.classList.add('hidden');
    });
});
</script>
@endsection
