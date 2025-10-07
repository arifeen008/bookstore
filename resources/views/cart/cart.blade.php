@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="fw-bold mb-5 text-primary"><i class="fas fa-shopping-cart me-2"></i> ตะกร้าสินค้าของคุณ</h1>

        @if (empty($cart))
            {{-- Empty Cart State --}}
            <div class="text-center py-5 border rounded-4 bg-light">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="fs-5 text-muted">ตะกร้าสินค้าว่างเปล่า</p>
                {{-- Changed route('books.index') to route('home') --}}
                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill shadow-sm mt-2">
                    เริ่มช้อปปิ้งเลย <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        @else
            <div class="row g-4">
                {{-- Left Column: Cart Items List --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-0">
                            {{-- Start of Redesigned Cart Item Table --}}
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="py-3">รายการสินค้า ({{ count($cart) }} ชิ้น)</th>
                                            <th width="120" class="text-center py-3">ราคา/เล่ม</th>
                                            <th width="150" class="text-center py-3">จำนวน</th>
                                            <th width="120" class="text-end py-3">รวม</th>
                                            <th width="50" class="py-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart as $item)
                                            <tr>
                                                {{-- Item Details (Image, Title, Author) --}}
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        {{-- Mock Image (Replace with actual image path if available) --}}
                                                        <img src="{{ (!empty($item['cover']) && file_exists(public_path('storage/'.$item['cover']))) ? asset('storage/' . $item['cover']) : 'https://placehold.co/90x120/868e96/FFFFFF?text=BOOK' }}     " 
                                                        
                                                        class="img-fluid rounded-3 me-3 border" 
                                                             style="width: 70px; height: 90px; object-fit: cover;" 
                                                             alt="Book Cover">
                                                        <div>
                                                            <p class="fw-bold mb-1 text-dark">{{ $item['title'] }}</p>
                                                            <p class="text-muted mb-0 small">{{ $item['author'] ?? 'ผู้แต่งไม่ระบุ' }}</p>
                                                        </div>
                                                    </div>
                                                </td>

                                                {{-- Price Per Unit --}}
                                                <td class="text-center fw-medium">
                                                    {{ number_format($item['price'], 2) }} ฿
                                                </td>

                                                {{-- Quantity Control (Redesigned) --}}
                                                <td class="text-center">
                                                    <form id="update-cart-{{ $item['id'] }}" method="POST" action="{{ route('cart.update', $item['id']) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="input-group input-group-sm d-flex justify-content-center" style="width: 120px; margin: 0 auto;">
                                                            {{-- Decrease Button --}}
                                                            <button class="btn btn-outline-secondary btn-minus" type="button" 
                                                                    onclick="this.closest('form').querySelector('input[name=\'quantity\']').stepDown(); this.closest('form').submit();"
                                                                    data-mdb-ripple-init>
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            {{-- Quantity Input (Shows current quantity) --}}
                                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                                                min="1" class="form-control text-center quantity-input" 
                                                                onchange="this.closest('form').submit()" 
                                                                style="max-width: 40px; border-left: 0; border-right: 0;">
                                                            {{-- Increase Button --}}
                                                            <button class="btn btn-outline-secondary btn-plus" type="button" 
                                                                    onclick="this.closest('form').querySelector('input[name=\'quantity\']').stepUp(); this.closest('form').submit();"
                                                                    data-mdb-ripple-init>
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            {{-- Note: Form submits on button click or input change --}}
                                                        </div>
                                                    </form>
                                                </td>

                                                {{-- Subtotal --}}
                                                <td class="text-end fw-bold text-danger">
                                                    {{ number_format($item['price'] * $item['quantity'], 2) }} ฿
                                                </td>

                                                {{-- Remove Button --}}
                                                <td class="text-center">
                                                    <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger rounded-pill p-2" data-mdb-toggle="tooltip" title="ลบสินค้า" data-mdb-ripple-init>
                                                            <i class="fas fa-times small"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{-- End of Redesigned Cart Item Table --}}
                        </div>
                    </div>

                    {{-- Continue Shopping/Clear Cart Buttons --}}
                    <div class="d-flex justify-content-between mt-3">
                        {{-- Changed route('books.index') to route('home') --}}
                        <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill" data-mdb-ripple-init>
                            <i class="fas fa-chevron-left me-2"></i> เลือกซื้อสินค้าต่อ
                        </a>
                        <form method="POST" action="{{ route('cart.clear') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary rounded-pill" data-mdb-ripple-init>
                                ล้างตะกร้า <i class="fas fa-trash-alt ms-2"></i>
                            </button>
                        </form>
                    </div>

                </div>

                {{-- Right Column: Cart Summary (Integrated Coupon Form) --}}
                <div class="col-lg-4">
                    {{-- Cart Summary Card --}}
                    @php
                        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
                        $discount = session('coupon_discount', 0);
                        $total = max($subtotal - $discount, 0);
                    @endphp
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-primary text-white fw-bold fs-5 rounded-top-4">
                            สรุปยอดคำสั่งซื้อ
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>ยอดรวมสินค้า:</span>
                                <span class="fw-bold">{{ number_format($subtotal, 2) }} ฿</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 text-success">
                                <span>ส่วนลดคูปอง:</span>
                                <span class="fw-bold">- {{ number_format($discount, 2) }} ฿</span>
                            </div>
                            
                            {{-- Coupon Form integrated using route('coupon.apply') --}}
                            <form method="POST" action="{{ route('coupon.apply') }}" class="mb-4">
                                @csrf
                                <label for="couponCode" class="form-label fw-medium small text-muted">ใช้โค้ดส่วนลด</label>
                                <div class="input-group">
                                    <input type="text" name="code" id="couponCode" class="form-control rounded-start-pill"
                                        placeholder="กรอกโค้ด" value="{{ old('code') }}">
                                    <button type="submit" class="btn btn-info text-white rounded-end-pill" data-mdb-ripple-init>
                                        <i class="fas fa-tag me-1"></i> ใช้
                                    </button>
                                </div>
                            </form>
                            
                            <hr class="my-3">
                            
                            <div class="d-flex justify-content-between fw-bolder fs-4 text-danger">
                                <span>ยอดสุทธิ:</span>
                                <span>{{ number_format($total, 2) }} ฿</span>
                            </div>
                            
                            {{-- Checkout button changed to route('orders.checkout') --}}
                            <a href="{{ route('orders.checkout') }}" class="btn btn-success btn-lg rounded-pill w-100 mt-4 shadow-sm" data-mdb-ripple-init>
                                ดำเนินการชำระเงิน <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
