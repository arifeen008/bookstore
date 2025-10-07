<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. กำหนดกฎการตรวจสอบ (Validation Rules)
        $validatedData = $request->validate([
            'title'               => 'required|string|max:255',
            'author'              => 'required|string|max:255',
            'price'               => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'category_id'         => 'required|exists:categories,id',
            'description'         => 'nullable|string',

            // เปลี่ยนจาก 'cover' เป็น 'cover_image' (ตามฟอร์มที่ปรับปรุง)
            'cover_image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',

            // กฎสำหรับรูปภาพเพิ่มเติม
            'additional_images'   => 'nullable|array',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // 2. สร้างเรคคอร์ดหนังสือเบื้องต้น (โดยที่ยังไม่มี path รูปปก)
        // การสร้างก่อนเพื่อให้ได้ Book ID
        $book = Book::create([
            'title'       => $validatedData['title'],
            'author'      => $validatedData['author'],
            'price'       => $validatedData['price'],
            'stock'       => $validatedData['stock'],
            'category_id' => $validatedData['category_id'],
            'description' => $validatedData['description'],
            'cover'       => null, // กำหนดเป็น null ไว้ก่อน
        ]);

        $bookId    = $book->id;
        $coverPath = null;

        // 3. จัดการการอัปโหลดรูปปกหลัก (books/covers)
        if ($request->hasFile('cover_image')) {
            $file      = $request->file('cover_image');
            $extension = $file->getClientOriginalExtension();

            // สร้างชื่อไฟล์ใหม่: slug-book-title-timestamp.ext
            $fileName = $bookId . '-' . time() . '.' . $extension;

            // เก็บไฟล์ใน storage/app/public/books/covers
            $coverPath = $file->storeAs('books/covers', $fileName, 'public');

            // อัปเดต path รูปปกในเรคคอร์ดหนังสือ
            $book->update(['cover' => $coverPath]);
        }

        // 4. จัดการรูปภาพเพิ่มเติม (books/gallery/{book_id})
        if ($request->hasFile('additional_images')) {
            // กำหนด directory path โดยใช้ ID ของหนังสือ
            $galleryDirectory = "books/gallery/{$bookId}";

            foreach ($request->file('additional_images') as $index => $image) {
                $extension = $image->getClientOriginalExtension();

                // สร้างชื่อไฟล์ใหม่: book-id-index-timestamp.ext
                $fileName = "{$bookId}-{$index}-" . time() . '.' . $extension;

                // เก็บไฟล์ใน storage/app/public/books/gallery/{book_id}
                $imagePath = $image->storeAs($galleryDirectory, $fileName, 'public');

                // สร้างเรคคอร์ดในตาราง BookImage ที่เชื่อมโยงกับหนังสือ
                $book->images()->create([
                    'path'       => $imagePath,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.books.create')->with('success', 'เพิ่มหนังสือและรูปภาพเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $book       = Book::findOrFail($id);
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'cover'       => 'nullable|url',
        ]);

        $book->update($request->only('title', 'author', 'price', 'stock', 'category_id', 'description', 'cover'));

        return redirect()->route('admin.books.index')->with('success', 'แก้ไขหนังสือเรียบร้อยแล้ว');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'ลบหนังสือเรียบร้อย');
    }

    // เพิ่มหลายเล่ม
    public function bulk()
    {
        return view('admin.books.bulk');
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'books'          => 'required|array',
            'books.*.title'  => 'required',
            'books.*.author' => 'required',
            'books.*.price'  => 'required|numeric',
            'books.*.stock'  => 'required|integer',
        ]);

        foreach ($request->books as $data) {
            Book::create($data);
        }

        return redirect()->route('admin.books.index')->with('success', 'เพิ่มหนังสือหลายเล่มเรียบร้อย');
    }
}
