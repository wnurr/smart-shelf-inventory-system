<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Support\TextFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * Display staff accounts grouped by role.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $roleFilter = trim((string) $request->input('role', ''));

        // All manageable roles are dynamic. Admin stays protected and hidden.
        $roles = Role::where('position', '!=', 'admin')
            ->orderBy('position')
            ->get();

        // Validate the filter softly. An unknown role simply falls back to All Roles.
        if ($roleFilter !== '' && ! $roles->contains('position', $roleFilter)) {
            $roleFilter = '';
        }

        $staff = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('position', '!=', 'admin');
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($staffQuery) use ($search) {
                    $staffQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone_no', 'like', '%' . $search . '%');
                });
            })
            ->when($roleFilter !== '', function ($query) use ($roleFilter) {
                $query->whereHas('role', function ($roleQuery) use ($roleFilter) {
                    $roleQuery->where('position', $roleFilter);
                });
            })
            ->orderBy('name')
            ->get();

        // Group the filtered result by role_id so each role owns one table.
        $staffByRole = $staff->groupBy('role_id');

        // Summary cards always describe the complete staff population,
        // independent of the current search/filter.
        $totalStaffCount = User::whereHas('role', function ($query) {
            $query->where('position', '!=', 'admin');
        })->count();

        $roleCounts = User::selectRaw('role_id, COUNT(*) as total')
            ->whereHas('role', function ($query) {
                $query->where('position', '!=', 'admin');
            })
            ->groupBy('role_id')
            ->pluck('total', 'role_id');

        return view('staff.index', compact(
            'roles',
            'staff',
            'staffByRole',
            'search',
            'roleFilter',
            'totalStaffCount',
            'roleCounts'
        ));
    }

    /**
     * Show create staff form.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Dynamic Staff Roles
        |--------------------------------------------------------------------------
        |
        | Every role created in Manage Roles will automatically appear here,
        | except the protected Admin role.
        |
        */

        $roles = Role::where(
            'position',
            '!=',
            'admin'
        )
            ->orderBy('position')
            ->get();

        return view(
            'staff.create',
            compact('roles')
        );
    }


    /**
     * Create new staff account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone_no' => [
                'nullable',
                'string',
                'max:20',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            /*
            |--------------------------------------------------------------------------
            | Dynamic Role Validation
            |--------------------------------------------------------------------------
            |
            | Role must exist in roles table and cannot be Admin.
            |
            */

            'role' => [
                'required',

                Rule::exists(
                    'roles',
                    'position'
                )->where(
                    fn ($query) =>
                        $query->where(
                            'position',
                            '!=',
                            'admin'
                        )
                ),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Format Staff Name
        |--------------------------------------------------------------------------
        */

        $validated['name'] =
            TextFormatter::titleCase(
                $validated['name']
            );


        /*
        |--------------------------------------------------------------------------
        | Find Selected Role
        |--------------------------------------------------------------------------
        */

        $role = Role::where(
            'position',
            $validated['role']
        )
            ->where(
                'position',
                '!=',
                'admin'
            )
            ->firstOrFail();

        /*Create Staff*/

        User::create([

            'name' =>
                $validated['name'],

            'phone_no' =>
                $validated['phone_no'] ?? null,

            'email' =>
                $validated['email'],

            'role_id' =>
                $role->id,

            'password' =>
                Hash::make(
                    $validated['password']
                ),

            /*Temporary Password*/

            'must_change_password' =>
                true,
        ]);


        return redirect()
            ->route('staff.index')
            ->with(
                'success',
                'Staff account created successfully. The staff member must change the temporary password after login.'
            );
    }


    /**
     * Show Edit Staff form.
     */
    public function edit(User $staff)
    {
        /*
        |--------------------------------------------------------------------------
        | Admin account cannot be managed here
        |--------------------------------------------------------------------------
        */

        if (
            $staff->role?->position
            === 'admin'
        ) {
            abort(
                403,
                'Admin accounts cannot be managed from the Staff module.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Dynamic roles
        |--------------------------------------------------------------------------
        */

        $roles = Role::where(
            'position',
            '!=',
            'admin'
        )
            ->orderBy('position')
            ->get();


        return view(
            'staff.edit',
            compact(
                'staff',
                'roles'
            )
        );
    }


    /**
     * Update staff access.
     *
     * Personal details belong to the staff member and are edited from
     * My Profile. Admin manages only role assignment and password reset.
     */
    public function update(Request $request, User $staff)
    {
        if ($staff->role?->position === 'admin') {
            abort(403, 'Admin accounts cannot be managed from the Staff module.');
        }

        $validated = $request->validate([
            'role' => [
                'required',
                Rule::exists('roles', 'position')->where(
                    fn ($query) => $query->where('position', '!=', 'admin')
                ),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $role = Role::where('position', $validated['role'])
            ->where('position', '!=', 'admin')
            ->firstOrFail();

        $staff->role_id = $role->id;

        if (! empty($validated['password'])) {
            $staff->password = Hash::make($validated['password']);
            $staff->must_change_password = true;
        }

        $staff->save();

        return redirect()
            ->route('staff.index')
            ->with('success', 'Staff access updated successfully.');
    }


    /**
     * Delete Staff.
     */
    public function destroy(User $staff)
    {
        /*
        |--------------------------------------------------------------------------
        | Protect Admin
        |--------------------------------------------------------------------------
        */

        if (
            $staff->role?->position
            === 'admin'
        ) {
            return redirect()
                ->route('staff.index')
                ->with(
                    'error',
                    'Admin accounts cannot be deleted from the Staff module.'
                );
        }


        try {

            $staffName =
                $staff->name;

            $staff->delete();


            return redirect()
                ->route('staff.index')
                ->with(
                    'success',
                    $staffName .
                    ' has been deleted successfully.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->route('staff.index')
                ->with(
                    'error',
                    'Unable to delete the staff account. Please try again.'
                );
        }
    }
}