<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(protected AdminService $adminService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admins = $this->adminService->paginate($request->per_page ?? 20);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        $this->adminService->create($request->validated());

        return redirect()->route('admin.admins.index')->with('success', __('Admin created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Export the resource.
     */
    public function export($filetype)
    {
        return $this->adminService->export($filetype);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $this->adminService->update($admin, $request->validated());

        return redirect()->route('admin.admins.index')->with('success', __('Admin updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $admin)
    {
        $this->adminService->delete($admin, $request->rows);

        return redirect()->route('admin.admins.index')->with('success', __('Admin deleted successfully'));
    }
}
