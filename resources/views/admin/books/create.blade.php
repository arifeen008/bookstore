@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4 text-primary fw-bold">เพิ่มหนังสือใหม่</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">ชื่อหนังสือ</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">ผู้แต่ง</label>
                <input type="text" name="author" class="form-control" value="{{ old('author') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">หมวดหมู่</label>
                <select name="category_id" class="form-select" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">ราคา (฿)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" step="0.01"
                    min="0" required>
            </div>

            <div class="mb-3">
                <label class="form-label">สต็อก</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" min="0"
                    required>
            </div>

            <div class="mb-3">
                <label class="form-label">คำอธิบาย</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            {{-- NEW: Cover Image Upload --}}
            <div class="mb-3">
                <label class="form-label">รูปปกหลัก (ไฟล์)</label>
                <input type="file" name="cover_image" class="form-control">
                <small class="form-text text-muted">แนะนำให้อัปโหลดไฟล์รูปภาพปกหลักที่นี่</small>
            </div>

            {{-- NEW: Additional Images Upload (Multiple) --}}
            <div class="mb-3">
                <label class="form-label">รูปภาพเพิ่มเติม (เลือกได้หลายไฟล์)</label>
                <input type="file" name="additional_images[]" class="form-control" multiple>
                <small class="form-text text-muted">ใช้สำหรับรูปภาพเสริม เช่น รูปด้านใน หรือ รูปมุมอื่นๆ</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">เพิ่มหนังสือ</button>
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">กลับไปหน้าจัดการหนังสือ</a>
            </div>
        </form>

    </div>
@endsection
