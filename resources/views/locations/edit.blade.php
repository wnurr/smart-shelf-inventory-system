@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('locations.index') }}" class="location-back-link">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Locations
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Location</h1>
        <p class="text-sm text-gray-500 mt-1">Update the storage position while keeping the standard location format.</p>
    </div>

    <form method="POST" action="{{ route('locations.update', $location) }}" class="location-form-card">
        @csrf
        @method('PUT')

        <div class="flex items-start gap-3 mb-5">
            <div class="location-form-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/></svg>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Location Details</h2>
                <p class="text-xs text-gray-500 mt-0.5">Format: Aisle-Bay-Shelf-Bin, for example A-A-1-1.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="aisle" class="block text-sm font-medium text-gray-700 mb-1.5">Aisle</label>
                <input id="aisle" name="aisle" type="text" maxlength="1" pattern="[A-Za-z]" value="{{ old('aisle', $location->aisle) }}" placeholder="A" required data-uppercase class="location-input">
                <p class="text-xs text-gray-400 mt-1.5">One letter only (A-Z).</p>
                @error('aisle')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="bay" class="block text-sm font-medium text-gray-700 mb-1.5">Bay</label>
                <input id="bay" name="bay" type="text" maxlength="1" pattern="[A-Za-z]" value="{{ old('bay', $location->bay) }}" placeholder="A" required data-uppercase class="location-input">
                <p class="text-xs text-gray-400 mt-1.5">One letter only (A-Z).</p>
                @error('bay')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="shelf" class="block text-sm font-medium text-gray-700 mb-1.5">Shelf</label>
                <input id="shelf" name="shelf" type="text" inputmode="numeric" maxlength="1" pattern="[1-9]" value="{{ old('shelf', $location->shelf) }}" placeholder="1" required class="location-input">
                <p class="text-xs text-gray-400 mt-1.5">One number only (1-9).</p>
                @error('shelf')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="bin" class="block text-sm font-medium text-gray-700 mb-1.5">Bin</label>
                <input id="bin" name="bin" type="text" inputmode="numeric" maxlength="1" pattern="[1-9]" value="{{ old('bin', $location->bin) }}" placeholder="1" required class="location-input">
                <p class="text-xs text-gray-400 mt-1.5">One number only (1-9).</p>
                @error('bin')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="location-note">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p>Aisle and Bay accept one letter each. Shelf and Bin accept one number from 1 to 9. Duplicate physical locations are rejected automatically.</p>
        </div>

        <div class="location-form-actions">
            <a href="{{ route('locations.index') }}" class="location-secondary-btn">Cancel</a>
            <button type="submit" class="location-primary-btn">Save Changes</button>
        </div>
    </form>
</div>

<style>
.location-back-link{display:inline-flex;align-items:center;gap:.375rem;margin-bottom:1rem;color:#6B7280;font-size:.8125rem;font-weight:600;text-decoration:none}.location-back-link:hover{color:#284B63}.location-form-card{padding:1.5rem;background:#fff;border:1px solid #E5E7EB;border-radius:.875rem;box-shadow:0 1px 2px rgba(15,23,42,.04)}.location-form-icon{width:2rem;height:2rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#284B63;background:#EDF3F7;border-radius:.5rem}.location-input{width:100%;height:2.75rem;border:1px solid #D1D5DB;border-radius:.5rem;padding:0 .75rem;font-size:.875rem;text-transform:uppercase}.location-input:focus{border-color:#284B63;box-shadow:0 0 0 1px #284B63;outline:none}.location-preview{display:inline-flex;align-items:center;padding:.35rem .55rem;color:#284B63;background:#EDF3F7;border-radius:.4rem;font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;font-size:.75rem;font-weight:700}.location-note{display:flex;gap:.55rem;margin-top:1.25rem;padding:.8rem .9rem;color:#52616B;background:#F8FAFC;border:1px solid #E5E7EB;border-radius:.6rem;font-size:.72rem;line-height:1.5}.location-form-actions{display:flex;align-items:center;justify-content:flex-end;gap:.75rem;margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid #EEF0F2}.location-primary-btn,.location-secondary-btn{min-height:2.625rem;display:inline-flex;align-items:center;justify-content:center;padding:0 1rem;border-radius:.5rem;font-size:.875rem;font-weight:600;text-decoration:none}.location-primary-btn{color:#fff;background:#284B63;border:1px solid #284B63}.location-primary-btn:hover{background:#1F3A4C}.location-secondary-btn{color:#4B5563;background:#fff;border:1px solid #D1D5DB}.location-secondary-btn:hover{background:#F9FAFB}
</style>

@endsection
