<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function books()
    {
        $books = Book::with('category')->get();
        return view('admin.books.index', compact('books'));
    }

    public function createBook()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function storeBook(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        Book::create($request->all());

        return redirect()->route('admin.books')->with('success', 'Book added!');
    }

    public function orders()
    {
        $orders = Order::with('items.book', 'user')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Order $order, Request $request)
    {
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order updated!');
    }
}
