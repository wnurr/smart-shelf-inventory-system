<?php

namespace App\Http\Controllers;

use App\Support\TextFormatter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private function ensureStaff(Request $request): void
    {
        if ($request->user()->role?->position === 'admin') {
            abort(403, 'The default administrator account does not use the staff profile module.');
        }
    }

    public function edit(Request $request): View
    {
        $this->ensureStaff($request);

        return view('profile.edit', [
            'user' => $request->user()->load('role'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->ensureStaff($request);

        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone_no' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->name = TextFormatter::titleCase($validated['name']);
        $user->email = $validated['email'];
        $user->phone_no = $validated['phone_no'] ?? null;

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->profile_photo = $request->file('profile_photo')
                ->store('profile-photos', 'public');
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}
