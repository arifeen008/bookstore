<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    public function address()
    {
        return view('user.address');
    }

    public function shelf()
    {
        return view('user.shelf');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('user.orders', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        // ตรวจสอบว่า order นี้เป็นของ user ที่ล็อกอินอยู่หรือไม่
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
            redirect()->route('user.orders')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงคำสั่งซื้อนี้');
        }

        $order = Order::with('items.book')->findOrFail($order->id);
        return view('user.order_details', compact('order'));
    }
    public function coupons()
    {
        return view('user.coupons');
    }

    public function points()
    {
        $user = Auth::user();

        // ดึงประวัติการได้คะแนน (เชื่อมกับตาราง point_histories)
        // $histories = \App\Models\PointHistory::where('user_id', $user->id)
        //     ->latest()
        //     ->get();
        return view('user.points', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email',
            'phone'     => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
        ]);

        tap(Auth::user())->update($request->only(['name', 'email', 'phone', 'birthdate']));

        return back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว!');
    }
}
