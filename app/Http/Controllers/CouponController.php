<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', $request->code)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })->first();

        if (! $coupon) {
            return back()->with('error', 'โค้ดไม่ถูกต้องหรือหมดอายุ');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'ตะกร้าว่าง!');
        }

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        if ($coupon->type == 'percent') {
            $discount = $subtotal * $coupon->value / 100;
        } else {
            $discount = min($coupon->value, $subtotal);
        }

        session([
            'coupon_code'     => $coupon->code,
            'coupon_discount' => $discount,
        ]);

        return back()->with('success', 'ใช้โค้ดสำเร็จ! ลดราคา ' . number_format($discount, 2) . ' ฿');
    }

}
