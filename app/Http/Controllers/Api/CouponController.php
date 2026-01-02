<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * اعمال کوپن
     */
    public function apply(Request $request)
    {
        // منطق اعمال کوپن
        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'discount' => 0
        ]);
    }
    
    /**
     * نمایش کوپن‌ها
     */
    public function index()
    {
        return response()->json([
            'coupons' => []
        ]);
    }
}