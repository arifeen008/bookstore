<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart     = session('cart', []);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $discount = session('coupon_discount', 0);
        $total    = max($subtotal - $discount, 0);

        return view('cart.cart', compact('cart', 'subtotal', 'discount', 'total'));
    }

    public function add(Book $book)
    {
        if ($book->stock <= 0) {
            return back()->with('error', 'สินค้าหมด ไม่สามารถเพิ่มลงตะกร้าได้');
        }

        $cart = session('cart', []);

        // ตรวจสอบว่ามี item อยู่แล้ว
        if (isset($cart[$book->id])) {
            if ($cart[$book->id]['quantity'] >= $book->stock) {
                return back()->with('error', 'จำนวนในสต็อกไม่พอ');
            }
            $cart[$book->id]['quantity']++;
        } else {
            $cart[$book->id] = [
                'id'       => $book->id,
                'title'    => $book->title,
                'price'    => $book->price,
                'quantity' => 1,
                'cover'    => $book->cover,
            ];
        }

        session(['cart' => $cart]); // จัดเก็บ session
        return back()->with('success', 'เพิ่มลงตะกร้าเรียบร้อย!');
    }

    public function update(Request $request, Book $book)
    {
        $quantity = max(1, (int) $request->quantity);
        $cart     = session('cart', []);

        if (isset($cart[$book->id])) {
            if ($quantity > $book->stock) {
                return back()->with('error', 'จำนวนในสต็อกไม่พอ');
            }
            $cart[$book->id]['quantity'] = $quantity;
            session(['cart' => $cart]);
            return redirect()->back();
        }

        return back()->with('error', 'ไม่พบหนังสือในตะกร้า');
    }

    public function remove(Book $book)
    {
        $cart = session('cart', []);
        if (isset($cart[$book->id])) {
            unset($cart[$book->id]);
            session(['cart' => $cart]);
            return back()->with('success', 'ลบหนังสือออกจากตะกร้าเรียบร้อย!');
        }
        return back()->with('error', 'ไม่พบหนังสือในตะกร้า');
    }

    public function clear()
    {
        session()->forget('cart');
        session()->forget('coupon_discount');
        return back()->with('success', 'ล้างตะกร้าสินค้าเรียบร้อย!');
    }
}
