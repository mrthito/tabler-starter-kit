<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCommissionTypeRequest;
use App\Services\CommissionTypeService;
use Illuminate\Http\Request;

class CommissionTypeController extends Controller
{
    public function __construct(protected CommissionTypeService $commissionTypeService) {}

    /**
     * Show the form for editing the commission type settings.
     */
    public function edit()
    {
        $commissionType = $this->commissionTypeService->get();
        return view('admin.commission-types.edit', compact('commissionType'));
    }

    /**
     * Update the commission type settings.
     */
    public function update(UpdateCommissionTypeRequest $request)
    {
        $this->commissionTypeService->update($request->validated());

        return redirect()->route('admin.commission-types.edit')->with('success', __('Commission Type updated successfully'));
    }
}
