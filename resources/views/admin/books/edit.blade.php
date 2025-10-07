@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4 text-primary fw-bold">แก้ไขหนังสือ</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.books.update', $book->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">ชื่อหนังสือ</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">ผู้แต่ง</label>
                <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">หมวดหมู่</label>
                <select name="category_id" class="form-select" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">ราคา (฿)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $book->price) }}"
                    step="0.01" min="0" required>
            </div>

            <div class="mb-3">
                <label class="form-label">สต็อก</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $book->stock) }}"
                    min="0" required>
            </div>

            <div class="mb-3">
                <label class="form-label">คำอธิบาย</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $book->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">URL รูปปก</label>
                <input type="url" name="cover" class="form-control" value="{{ old('cover', $book->cover) }}">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">บันทึกการแก้ไข</button>
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">กลับไปหน้าจัดการหนังสือ</a>
            </div>
        </form>
    </div>
@endsection
