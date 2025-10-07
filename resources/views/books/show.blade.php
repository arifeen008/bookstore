@extends('layouts.app')


@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css" />
@endpush

{{-- CONTENT --}}
@section('content')
    <div class="container py-5">

        {{-- BOOK DETAILS SECTION --}}
        <div class="row g-5 mb-5">

            <div class="col-lg-5">
                <a data-fancybox="gallery" data-src="{{ asset('storage/' . $book->cover) }}" data-caption="{{ $book->title }}">
                    <img src="{{ asset('storage/' . $book->cover) }}" class="img-fluid w-100" alt="" />
                </a>

                <div class="row row-cols-4 g-2">


                    @if (!empty($book->images))
                        @foreach ($book->images as $index => $image)
                            <div class="col">
                                <a data-fancybox="gallery" data-src="{{ asset('storage/' . $image->path) }}"
                                    data-caption="{{ $book->title }} - Image {{ $index + 1 }}">
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                        class="img-fluid rounded shadow-sm border"
                                        style="height: 80px; width: 100%; object-fit: cover;" alt="Thumbnail" />
                                </a>
                            </div>
                        @endforeach
                    @endif


                </div>
            </div>

            {{-- DETAILS AND ACTIONS (Right Column - col-lg-7) --}}
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold text-dark mb-2">{{ $book->title }}</h1>

                {{-- Author and Category --}}
                <p class="lead text-muted border-bottom pb-3 mb-4">
                    ผู้แต่ง: <span class="fw-semibold text-primary">{{ $book->author }}</span>
                    | หมวดหมู่: <span class="fw-semibold text-success">{{ $book->category->name ?? 'ไม่ระบุ' }}</span>
                </p>

                {{-- Price and Stock Status --}}
                <div class="mb-4">
                    <h2 class="fw-bolder text-danger display-4">{{ number_format($book->price, 2) }} ฿</h2>

                    @if ($book->stock > 0)
                        <p class="text-success mt-2">เหลือ : {{ $book->stock }} เล่ม</p>
                    @else
                        <span class="badge bg-secondary fs-6 py-2 px-3"><i class="bi bi-x-circle-fill me-1"></i>
                            สินค้าหมด</span>
                        <p class="text-danger mt-2">สินค้านี้หมดชั่วคราว</p>
                    @endif
                </div>

                {{-- Add to Cart Form --}}
                @if ($book->stock > 0)
                    <form method="POST" action="{{ route('cart.add', $book->id) }}"
                        class="d-flex align-items-center mb-5">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg shadow-lg text-uppercase me-3"
                            style="min-width: 250px;">
                            <i class="bi bi-cart-plus-fill me-2"></i> เพิ่มลงตะกร้า
                        </button>
                    </form>
                @endif

                {{-- Description --}}
                <h4 class="mb-3 text-primary border-bottom pb-2">รายละเอียดสินค้า</h4>
                <div class="card card-body bg-light">
                    <p>{{ $book->description }}</p>
                </div>
            </div>
        </div>

        <hr class="my-5">

        {{-- RELATED BOOKS SECTION --}}
        <h3 class="mb-4 text-primary fw-bold">หนังสืออื่นๆ ที่น่าสนใจ</h3>

        @if (!empty($relatedBooks) && $relatedBooks->count() > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($relatedBooks as $relatedBook)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            {{-- Book Cover --}}
                            <a href="{{ route('books.show', $relatedBook->id) }}" class="flex-shrink-0">
                                @php
                                    $relatedCover =
                                        $relatedBook->cover &&
                                        file_exists(public_path('storage/' . $relatedBook->cover))
                                            ? asset('storage/' . $relatedBook->cover)
                                            : 'https://placehold.co/400x550/E9ECEF/495057?text=No Cover';
                                @endphp

                                <img src="{{ $relatedCover }}" class="card-img-top object-fit-cover rounded-top"
                                    alt="{{ $relatedBook->title }}" style="height: 280px; width: 100%; object-fit: cover;">
                            </a>

                            {{-- Card Body --}}
                            <div class="card-body d-flex flex-column p-3">
                                <h6 class="card-title text-truncate-2 mb-1">
                                    <a href="{{ route('books.show', $relatedBook->id) }}"
                                        class="text-decoration-none text-dark fw-bold">
                                        {{ $relatedBook->title }}
                                    </a>
                                </h6>

                                <p class="text-muted small mb-3 flex-grow-0">{{ $relatedBook->author }}</p>

                                {{-- Price and Cart Action --}}
                                <div class="mt-auto pt-2">
                                    <p class="fw-bolder fs-6 text-danger mb-2">{{ number_format($relatedBook->price, 2) }}
                                        ฿</p>

                                    @if ($relatedBook->stock > 0)
                                        <form method="POST" action="{{ route('cart.add', $relatedBook->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100 btn-sm shadow-sm">
                                                <i class="bi bi-cart-plus-fill me-1"></i> เพิ่มลงตะกร้า
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-secondary w-100 btn-sm" disabled>
                                            <i class="bi bi-x-circle me-1"></i> สินค้าหมด
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">ไม่พบหนังสืออื่น ๆ ในหมวดหมู่เดียวกัน</p>
        @endif

    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.umd.js"></script>
        <script>
            Fancybox.bind("[data-fancybox='gallery']", {});
        </script>
    @endpush
@endsection
