<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use App\Models\Plan;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(protected CouponService $couponService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $coupons = $this->couponService->paginate($request->per_page ?? 20);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = Plan::where('status', true)->orderBy('name')->get();
        return view('admin.coupons.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCouponRequest $request)
    {
        $this->couponService->create($request->validated());

        return redirect()->route('admin.coupons.index')->with('success', __('Coupon created successfully'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        $plans = Plan::where('status', true)->orderBy('name')->get();
        return view('admin.coupons.edit', compact('coupon', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $this->couponService->update($coupon, $request->validated());

        return redirect()->route('admin.coupons.index')->with('success', __('Coupon updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $coupon)
    {
        $this->couponService->delete($coupon, $request->rows);

        return redirect()->route('admin.coupons.index')->with('success', __('Coupon deleted successfully'));
    }
}
