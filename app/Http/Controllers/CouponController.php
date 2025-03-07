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
        try {
            $coupon = Coupon::find($request->id);
            if (!$coupon) {
                return $this->error('Coupon not found');
            }

            $request->validate([
                'code' => 'required|string|unique:coupons,code,' . $coupon->id,
                'type' => 'required|in:fixed,percentage',
                'discount' => 'required|numeric|min:0'
            ]);

            $coupon->code = $request->code;
            $coupon->type = $request->type;
            $coupon->discount = $request->discount;
            $coupon->save();

            return $this->success('Coupon updated successfully', $coupon);
        } catch (\Exception $e) {
            return $this->error('Failed to update coupon: ' . $e->getMessage());
        }
    }

    public function deleteCoupon(Request $request, $id) {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return $this->success('Coupon deleted successfully', $coupon);
    }
}
