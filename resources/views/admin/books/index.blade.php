<!-- resources/views/admin/books/index.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h3 class="mb-4">📚 จัดการหนังสือ</h3>

        <a href="{{ route('admin.books.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus me-2"></i> เพิ่มหนังสือใหม่
        </a>
        <a href="{{ route('admin.books.bulk') }}" class="btn btn-success mb-3">
            <i class="fas fa-plus-circle me-2"></i> เพิ่มหลายเล่ม
        </a>

        <table class="table table-hover bg-white shadow-sm rounded">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ชื่อหนังสือ</th>
                    <th>ผู้แต่ง</th>
                    <th>ราคา</th>
                    <th>สต็อก</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr>
                        <td>{{ $book->id }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>{{ number_format($book->price, 2) }} ฿</td>
                        <td>{{ $book->stock }}</td>
                        <td>
                            <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('ลบหนังสือ?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $books->links() }}
    </div>
@endsection
