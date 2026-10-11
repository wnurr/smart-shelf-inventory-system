<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display roles.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $sort = $request->input('sort', 'asc');

        if (!in_array($sort, ['asc', 'desc'], true)) {
            $sort = 'asc';
        }

        $roles = Role::with('permissions:id')
            ->withCount([
                'users',
                'permissions',
            ])
            ->where('position', '!=', 'admin')
            ->when(
                $search !== '',
                fn ($query) => $query->where(
                    'position',
                    'like',
                    '%' . Str::snake($search) . '%'
                )
            )
            ->orderBy('position', $sort)
            ->paginate(10)
            ->withQueryString();

        $totalRoles = Role::where('position', '!=', 'admin')->count();

        $assignedStaffCount = Role::where('position', '!=', 'admin')
            ->withCount('users')
            ->get()
            ->sum('users_count');

        $permissions = Permission::whereNotIn(
            'name',
            ['manage_staff', 'manage_roles']
        )
            ->orderBy('group')
            ->orderBy('label')
            ->get()
            ->groupBy('group');

        $availablePermissionCount = $permissions
            ->flatten(1)
            ->count();

        return view(
            'roles.index',
            compact(
                'roles',
                'permissions',
                'search',
                'sort',
                'totalRoles',
                'assignedStaffCount',
                'availablePermissionCount'
            )
        );
    }


    /**
     * Show Add Role form.
     */
    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Exclude Admin-Only Permissions
        |--------------------------------------------------------------------------
        |
        | Custom roles are not allowed to receive:
        |
        | - manage_staff
        | - manage_roles
        |
        */

        $permissions = Permission::whereNotIn(
            'name',
            [
                'manage_staff',
                'manage_roles',
            ]
        )
            ->orderBy('group')
            ->orderBy('label')
            ->get()
            ->groupBy('group');


        return view(
            'roles.create',
            compact('permissions')
        );
    }


    /**
     * Store role.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'exists:permissions,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Convert Role Name
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Assistant Manager
        |
        | becomes:
        |
        | assistant_manager
        |
        */

        $position = Str::snake(
            trim(
                $validated['name']
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Prevent Admin Role Creation
        |--------------------------------------------------------------------------
        */

        if ($position === 'admin') {

            return back()
                ->withErrors([
                    'name' =>
                        'The Admin role is reserved and cannot be created.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Role
        |--------------------------------------------------------------------------
        */

        if (
            Role::where(
                'position',
                $position
            )->exists()
        ) {

            return back()
                ->withErrors([
                    'name' =>
                        'This role already exists.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Create Role
        |--------------------------------------------------------------------------
        */

        $role = Role::create([
            'position' =>
                $position,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Remove Admin-Only Permissions
        |--------------------------------------------------------------------------
        |
        | Even if somebody manually sends the permission IDs through
        | Postman or browser DevTools, the backend will remove them.
        |
        */

        $selectedPermissions =
            $this->filterAdminOnlyPermissions(
                $validated['permissions'] ?? []
            );


        /*
        |--------------------------------------------------------------------------
        | Assign Permissions
        |--------------------------------------------------------------------------
        */

        $role->permissions()->sync(
            $selectedPermissions
        );


        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Role created successfully.'
            );
    }


    /**
     * Show Edit Role form.
     */
    public function edit(
        Role $role
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Admin Role Cannot Be Edited
        |--------------------------------------------------------------------------
        */

        if ($role->position === 'admin') {
            abort(
                403,
                'The Admin role cannot be modified.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Exclude Admin-Only Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = Permission::whereNotIn(
            'name',
            [
                'manage_staff',
                'manage_roles',
            ]
        )
            ->orderBy('group')
            ->orderBy('label')
            ->get()
            ->groupBy('group');


        /*
        |--------------------------------------------------------------------------
        | Current Permissions
        |--------------------------------------------------------------------------
        */

        $selectedPermissions =
            $role->permissions()
                ->whereNotIn(
                    'name',
                    [
                        'manage_staff',
                        'manage_roles',
                    ]
                )
                ->pluck('permissions.id')
                ->toArray();


        return view(
            'roles.edit',
            compact(
                'role',
                'permissions',
                'selectedPermissions'
            )
        );
    }


    /**
     * Update role.
     */
    public function update(
        Request $request,
        Role $role
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Admin Role Cannot Be Modified
        |--------------------------------------------------------------------------
        */

        if ($role->position === 'admin') {
            abort(
                403,
                'The Admin role cannot be modified.'
            );
        }


        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'exists:permissions,id',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Convert Role Name
        |--------------------------------------------------------------------------
        */

        $position = Str::snake(
            trim(
                $validated['name']
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Prevent Rename To Admin
        |--------------------------------------------------------------------------
        */

        if ($position === 'admin') {

            return back()
                ->withErrors([
                    'name' =>
                        'The Admin role is reserved and cannot be used.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Role
        |--------------------------------------------------------------------------
        */

        $exists = Role::where(
            'position',
            $position
        )
            ->where(
                'id',
                '!=',
                $role->id
            )
            ->exists();


        if ($exists) {

            return back()
                ->withErrors([
                    'name' =>
                        'This role already exists.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Update Role
        |--------------------------------------------------------------------------
        */

        $role->update([
            'position' =>
                $position,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Filter Admin-Only Permissions
        |--------------------------------------------------------------------------
        */

        $selectedPermissions =
            $this->filterAdminOnlyPermissions(
                $validated['permissions'] ?? []
            );


        /*
        |--------------------------------------------------------------------------
        | Update Permissions
        |--------------------------------------------------------------------------
        */

        $role->permissions()->sync(
            $selectedPermissions
        );


        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Role updated successfully.'
            );
    }


    /**
     * Delete role.
     */
    public function destroy(
        Role $role
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Protect Default System Roles
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $role->position,
                [
                    'admin',
                    'store_manager',
                    'store_keeper',
                ],
                true
            )
        ) {

            return back()->with(
                'error',
                'Default system roles cannot be deleted.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Do Not Delete Role While Staff Are Assigned
        |--------------------------------------------------------------------------
        */

        if ($role->users()->exists()) {

            return back()->with(
                'error',
                'This role cannot be deleted because staff members are still assigned to it.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Remove Permission Relationships
        |--------------------------------------------------------------------------
        */

        $role->permissions()->detach();


        /*
        |--------------------------------------------------------------------------
        | Delete Role
        |--------------------------------------------------------------------------
        */

        $role->delete();


        return redirect()
            ->route('roles.index')
            ->with(
                'success',
                'Role deleted successfully.'
            );
    }


    /**
     * Remove Admin-only permissions from custom roles.
     *
     * @param array<int|string> $permissionIds
     * @return array<int>
     */
    private function filterAdminOnlyPermissions(
        array $permissionIds
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Admin-Only Permissions
        |--------------------------------------------------------------------------
        */

        $adminOnlyPermissionIds =
            Permission::whereIn(
                'name',
                [
                    'manage_staff',
                    'manage_roles',
                ]
            )
                ->pluck('id')
                ->map(
                    fn ($id) => (int) $id
                )
                ->toArray();


        /*
        |--------------------------------------------------------------------------
        | Remove Restricted Permission IDs
        |--------------------------------------------------------------------------
        */

        return collect(
            $permissionIds
        )
            ->map(
                fn ($id) => (int) $id
            )
            ->reject(
                fn ($permissionId) =>
                    in_array(
                        $permissionId,
                        $adminOnlyPermissionIds,
                        true
                    )
            )
            ->values()
            ->toArray();
    }
}