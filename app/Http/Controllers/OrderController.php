<?php
namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Book;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // แสดงหน้า Checkout
    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'ตะกร้าว่าง!');
        }
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = session('coupon_discount', 0);
        $total    = max($subtotal - $discount, 0);

        $addresses = Address::where('user_id', Auth::id())->get();
        return view('checkout.checkout', compact('cart', 'subtotal', 'discount', 'total', 'addresses'));
    }

    public function placeOrder(Request $request)
    {
        // 1. UPDATE VALIDATION RULES to match the Blade file
        $request->validate([
            'recipient_name' => 'required|string|max:200', // Matches input name from Blade
            'phone'          => 'required|string|max:20',
            'full_address'   => 'required|string|max:255', // Matches input name from Blade
            'province'       => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'zipcode'        => 'required|string|max:10',          // Matches input name from Blade
            'payment_method' => 'required|string|in:COD,TRANSFER', // Matches input name from Blade (and values)
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'ตะกร้าว่าง!');
        }

        // Simple logic to split recipient_name into first_name and last_name for DB compatibility
        $nameParts = explode(' ', $request->recipient_name, 2);
        $firstName = $nameParts[0];
        $lastName  = $nameParts[1] ?? '';

        // dd($nameParts);

        // dd($request->all());

        DB::beginTransaction();
        try {
            $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
            $discount = session('coupon_discount', 0);
            $total    = max($subtotal - $discount, 0);

            $order = Order::create([
                'user_id'        => Auth::id(),
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'total'          => $total,
                'status'         => 'pending',

                // Shipping info - Mapped to new request field names
                'first_name'     => $firstName, // Mapped from recipient_name
                'last_name'      => $lastName,  // Mapped from recipient_name
                'phone'          => $request->phone,
                'address'        => $request->full_address, // Mapped from full_address
                'province'       => $request->province,
                'district'       => $request->district,
                'zip'            => $request->zipcode, // Mapped from zipcode

                                                              // Payment
                'payment_method' => $request->payment_method, // Mapped from payment_method
                'payment_meta'   => json_encode(['method' => $request->payment_method]),
            ]);

            foreach ($cart as $item) {
                $book = Book::lockForUpdate()->find($item['id']);
                if (! $book || $book->stock < $item['quantity']) {
                    throw new \Exception('สต็อกไม่พอ: ' . $item['title']);
                }
                $book->stock -= $item['quantity'];
                $book->save();

                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id'  => $book->id,
                    'quantity' => $item['quantity'],
                    'price'    => $book->price,
                ]);
            }

            if (session('coupon_code')) {
                $coupon = Coupon::where('code', session('coupon_code'))->first();
                if ($coupon) {
                    $coupon->used_count += 1;
                    $coupon->save();
                }
            }

            DB::commit();

            session()->forget(['cart', 'coupon_code', 'coupon_discount']);
            return redirect()->route('user.orders')->with('success', 'สั่งซื้อเรียบร้อยแล้ว!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

}
