<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(protected RoleService $roleService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = $this->roleService->paginate($request->per_page ?? 20);

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $this->roleService->create($request->validated());

        return redirect()->route('admin.roles.index')->with('success', __('Role created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show($filetype)
    {
        return $this->roleService->export($filetype);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->roleService->update($role, $request->validated());

        return redirect()->route('admin.roles.index')->with('success', __('Role updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $role)
    {
        $this->roleService->delete($role, $request->rows);

        return redirect()->route('admin.roles.index')->with('success', __('Role deleted successfully'));
    }
}
