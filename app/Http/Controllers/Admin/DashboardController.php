<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // นับข้อมูลสำคัญ
        $booksCount  = Book::count();
        $ordersCount = Order::count();
        $usersCount  = User::count();

        // ยอดขายรวม
        $totalSales = Order::sum('total');

        // ยอดขายรายเดือนล่าสุด 6 เดือน (array month => total)
        $salesByMonth = Order::select(
                DB::raw("DATE_FORMAT(created_at,'%Y-%m') as month"),
                DB::raw("SUM(total) as total")
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // หนังสือขายดี 5 อันดับ (book_id => qty)
        $topBooks = DB::table('order_items')
            ->select('book_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('book_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->pluck('total_qty', 'book_id');

        // ดึงรายละเอียดหนังสือสำหรับแสดงชื่อ
        $topBooksDetails = Book::whereIn('id', $topBooks->keys())->get()->keyBy('id');

        // คำสั่งซื้อล่าสุด (สำหรับตาราง)
        $latestOrders = Order::with('user', 'orderItems.book')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'booksCount',
            'ordersCount',
            'usersCount',
            'totalSales',
            'salesByMonth',
            'topBooks',
            'topBooksDetails',
            'latestOrders'
        ));
    }
}
