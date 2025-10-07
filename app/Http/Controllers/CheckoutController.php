<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkoutForm()
    {
        $cart = session('cart', []);
        return view('checkout.index', compact('cart'));
    }

    public function processPayment(Request $request)
    {
        $cart = session('cart', []);

        if(empty($cart)){
            return redirect()->route('cart.index')->with('error','ตะกร้าว่าง');
        }

        $subtotal = 0;
        foreach($cart as $id=>$item){
            $book = Book::find($id);
            if(!$book || $book->stock < $item['quantity']){
                return back()->with('error',"หนังสือ {$item['title']} ไม่พอใน stock");
            }
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Coupon
        $discount = 0;
        $coupon_code = $request->coupon;
        if($coupon_code){
            $coupon = Coupon::where('code',$coupon_code)->first();
            if($coupon){
                if($coupon->type=='percent'){
                    $discount = $subtotal * $coupon->value/100;
                }else{
                    $discount = min($coupon->value,$subtotal);
                }
            }
        }

        $total = $subtotal - $discount;

        DB::transaction(function() use($cart, $discount, $total, $coupon_code){
            $order = Order::create([
                'user_id' => Auth::id(),
                'subtotal' => array_sum(array_map(fn($i)=>$i['price']*$i['quantity'],$cart)),
                'discount' => $discount,
                'total' => $total,
                'status' => 'paid',
            ]);

            foreach($cart as $id=>$item){
                $book = Book::lockForUpdate()->find($id);
                $book->stock -= $item['quantity'];
                $book->save();

                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Update coupon usage
            if($coupon_code){
                $coupon = Coupon::where('code',$coupon_code)->first();
                if($coupon){
                    $coupon->used_count +=1;
                    $coupon->save();
                }
            }

            session()->forget('cart');
        });

        return redirect()->route('home')->with('success','สั่งซื้อเรียบร้อยแล้ว');
    }
}
