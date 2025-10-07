<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookImage;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Filter by category (optional)
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        // Pagination
        $books = $query->paginate(12);

        $categories = Category::all();

        return view('home', compact('books', 'categories'));
    }

    public function allBooks(Request $request)
    {
        $books      = Book::paginate(12); // แสดงทั้งหมด
        $categories = Category::all();
        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $relatedBooks = Book::where('id', '!=', $book->id)
            ->inRandomOrder()
            ->take(8)
            ->get();
        $images = BookImage::where('book_id', $book->id)->get();
        return view('books.show', compact('book', 'relatedBooks', 'images'));
    }
}
