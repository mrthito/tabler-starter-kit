<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct(protected PlanService $planService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $plans = $this->planService->paginate($request->per_page ?? 20);

        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlanRequest $request)
    {
        $this->planService->create($request->validated());

        return redirect()->route('admin.plans.index')->with('success', __('Plan created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $this->planService->update($plan, $request->validated());

        return redirect()->route('admin.plans.index')->with('success', __('Plan updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $plan)
    {
        $this->planService->delete($plan, $request->rows);

        return redirect()->route('admin.plans.index')->with('success', __('Plan deleted successfully'));
    }
}
