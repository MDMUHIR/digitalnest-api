<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller {

    public function verifyCoupon(Request $request) {
        try {
            $request->validate([
                'code' => 'required|string'
            ]);

            $coupon = Coupon::where('code', $request->code)->first();
            if (!$coupon) {
                return $this->error('Invalid coupon code');
            }

            // Check if coupon is active
            if (isset($coupon->is_active) && !$coupon->is_active) {
                return $this->error('This coupon is no longer active');
            }

            // Check expiration if exists
            if (isset($coupon->expires_at) && now()->gt($coupon->expires_at)) {
                return $this->error('This coupon has expired');
            }

            // Check usage limit if exists
            if (isset($coupon->usage_limit) && isset($coupon->times_used) && $coupon->times_used >= $coupon->usage_limit) {
                return $this->error('This coupon has reached its usage limit');
            }

            return $this->success('Coupon code is valid', $coupon);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error($e->getMessage());
        } catch (\Exception $e) {
            return $this->error('Failed to verify coupon: ' . $e->getMessage());
        }
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
