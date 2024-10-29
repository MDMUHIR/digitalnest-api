<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller {

    public function verifyCoupon(Request $request) {
        $coupon = Coupon::where('code', $request->code)->first();
        if (!$coupon) {
            return $this->error('Invalid coupon code');
        }
        return $this->success('Coupon code is valid', $coupon);
    }

    public function getCoupons() {
        $coupons = Coupon::all();
        return $this->success('Coupons retrieved successfully', $coupons);
    }

    public function addCoupon(Request $request) {
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->discount = $request->discount;
        $coupon->save();
        return $this->success('Coupon created successfully', $coupon);
    }

    public function updateCoupon(Request $request) {
        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->discount = $request->discount;
        $coupon->save();
        return $this->success('Coupon updated successfully', $coupon);
    }

    public function deleteCoupon(Request $request, $id) {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return $this->success('Coupon deleted successfully', $coupon);
    }
}
