@extends('layouts.app')

@push('styles')
    <style>
        .transition-300 {
            transition: all 0.3s ease-in-out;
        }

        .hover-shadow-lg:hover {
            /* Shadow effect combined with a slight lift */
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
            transform: translateY(-5px);
        }

        /* Ensure card height is consistent for a clean grid */
        .book-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Styling for the pagination container */
        .pagination-container {
            margin-top: 3rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
        }
    </style>
@endpush
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h3 class="text-primary fw-bold mb-0">📚 หนังสือทั้งหมด</h3>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($books as $book)
            <div class="col">
                {{-- Use the book-card class for consistent height --}}
                <div class="card book-card shadow-sm border-0 transition-300 hover-shadow-lg">

                    {{-- Book Image / Cover --}}
                    <a href="{{ route('books.show', $book->id) }}" class="flex-shrink-0">

                        <img src="{{ (!empty($book->cover) && file_exists(public_path('storage/' . $book->cover))) ? asset('storage/' . $book->cover) : 'https://placehold.co/400x550/E9ECEF/495057?text=No Cover' }}" class="card-img-top object-fit-cover rounded-top" alt="{{ $book->title }}" style="height: 280px; width: 100%; object-fit: cover;">
                    </a>

                    {{-- Card Body --}}
                    <div class="card-body d-flex flex-column p-3">

                        {{-- Title and Author --}}
                        <h5 class="card-title text-truncate-2 mb-1" style="min-height: 2.5em;">
                            <a href="{{ route('books.show', $book->id) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $book->title }}
                            </a>
                        </h5>
                        <p class="text-muted small mb-3 flex-grow-0">{{ $book->author }}</p>

                        {{-- Price & Author Name (แทนที่ Stock Status) --}}
                        <div class="mt-auto pt-2">
                            {{-- แสดงราคาและหน่วยบาท --}}
                            <p class="fw-bolder fs-5 text-danger mb-2">
                                {{ number_format($book->price, 2) }} บาท
                            </p>

                            
                        </div>


                        {{-- Action Buttons --}}
                        <div class="d-grid gap-2 mt-3">
                            @if ($book->stock > 0)
                                <form method="POST" action="{{ route('cart.add', $book->id) }}">
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

    {{-- Pagination Section --}}
    <div class="pagination-container">
        {{ $books->links() }}
    </div>
@endsection
