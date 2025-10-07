@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <h2 class="mb-5 text-center fw-light text-primary">
            <i class="fas fa-truck me-2"></i> ขั้นตอนสุดท้าย - ยืนยันการสั่งซื้อ
        </h2>

        {{-- Alert Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm">
                {{ session('success') }}
                <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm">
                {{ session('error') }}
                <button type="button" class="btn-close" data-mdb-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-5">

            {{-- LEFT: Address & Payment (col-lg-8) --}}
            <div class="col-lg-8">
                <form method="POST" action="{{ route('orders.place') }}" id="order-form">
                    @csrf

                    {{-- 1. ADDRESS CARD (Inline Input Form) --}}
                    <div class="card shadow-lg border-0 rounded-4 mb-4">
                        {{-- Header (Removed Add New Address button) --}}
                        <div class="card-header bg-primary text-white rounded-top-4 py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2"></i> กรอกที่อยู่จัดส่ง</h5>
                        </div>

                        {{-- Body: Address Input Fields --}}
                        <div class="card-body p-4">
                            <p class="text-muted small mb-4">กรุณากรอกที่อยู่สำหรับจัดส่งสินค้าในคำสั่งซื้อนี้</p>

                            <div class="row">
                                {{-- 1. ชื่อ-นามสกุลผู้รับ --}}
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text" id="recipient_name" name="recipient_name" class="form-control"
                                            required />
                                        <label class="form-label" for="recipient_name">ชื่อ-นามสกุลผู้รับ</label>
                                    </div>
                                </div>

                                {{-- 2. เบอร์โทรศัพท์ --}}
                                <div class="col-md-6 mb-4">
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="tel" id="phone" name="phone" class="form-control" required />
                                        <label class="form-label" for="phone">เบอร์โทรศัพท์</label>
                                    </div>
                                </div>

                                {{-- 3. ที่อยู่ (บ้านเลขที่ หมู่ ถนน ซอย) --}}
                                <div class="col-12 mb-4">
                                    <div class="form-outline" data-mdb-input-init>
                                        <textarea id="full_address" name="full_address" class="form-control" rows="2" required></textarea>
                                        <label class="form-label" for="full_address">ที่อยู่ (บ้านเลขที่ หมู่ ถนน
                                            ซอย)</label>
                                    </div>
                                </div>

                                {{-- 4. อำเภอ / เขต --}}
                                <div class="col-md-4 mb-4">
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text" id="district" name="district" class="form-control"
                                            required />
                                        <label class="form-label" for="district">อำเภอ / เขต</label>
                                    </div>
                                </div>

                                {{-- 5. จังหวัด --}}
                                <div class="col-md-4 mb-4">
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text" id="province" name="province" class="form-control"
                                            required />
                                        <label class="form-label" for="province">จังหวัด</label>
                                    </div>
                                </div>

                                {{-- 6. รหัสไปรษณีย์ --}}
                                <div class="col-md-4 mb-4">
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text" id="zipcode" name="zipcode" class="form-control" required />
                                        <label class="form-label" for="zipcode">รหัสไปรษณีย์</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. PAYMENT METHOD CARD --}}
                    <div class="card shadow-lg border-0 rounded-4 mb-4">
                        <div class="card-header bg-primary text-white rounded-top-4 py-3">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2"></i> วิธีการชำระเงิน</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                    value="COD" checked>
                                <label class="form-check-label fw-bold" for="cod">ชำระเงินปลายทาง (COD)</label>
                                <p class="text-muted small ms-4 mb-0">ชำระเงินเมื่อได้รับสินค้า</p>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="transfer"
                                    value="TRANSFER">
                                <label class="form-check-label fw-bold" for="transfer">โอนเงินผ่านธนาคาร</label>
                                <p class="text-muted small ms-4 mb-0">ต้องแนบหลักฐานการโอนหลังจากยืนยันคำสั่งซื้อ</p>
                            </div>
                        </div>
                    </div>

                    {{-- FINAL SUBMIT BUTTON (Inside the form) --}}
                    <div class="text-end mt-4">
                        <button class="btn btn-success btn-lg rounded-pill shadow-lg" type="submit">
                            <i class="fas fa-check-circle me-2"></i> ยืนยันคำสั่งซื้อ
                        </button>
                    </div>
                </form>
            </div>

            {{-- RIGHT: Order Summary (col-lg-4) --}}
            <div class="col-lg-4">
                <div class="card card-body shadow-lg rounded-4 p-4 bg-body-tertiary sticky-top" style="top: 20px;">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-2"><i class="fas fa-receipt me-2"></i>
                        สรุปรายการคำสั่งซื้อ</h5>

                    {{-- Item List --}}
                    @foreach ($cart as $item)
                        <div class="d-flex justify-content-between small text-muted border-bottom py-2">
                            <span>{{ $item['title'] }} (x{{ $item['quantity'] }})</span>
                            <span>{{ number_format($item['price'] * $item['quantity'], 2) }} ฿</span>
                        </div>
                    @endforeach

                    {{-- Totals --}}
                    <div class="mt-3 d-flex justify-content-between">
                        <span class="text-dark">ยอดรวมสินค้า</span>
                        <span class="text-dark">{{ number_format($subtotal, 2) }} ฿</span>
                    </div>
                    <div class="d-flex justify-content-between text-success mb-3">
                        <span class="text-success">ส่วนลด</span>
                        <span class="text-success fw-bold">-{{ number_format($discount, 2) }} ฿</span>
                    </div>

                    <div class="d-flex justify-content-between fw-bolder fs-5 text-danger border-top pt-3">
                        <span>ยอดสุทธิที่ต้องชำระ</span>
                        <span>{{ number_format($total, 2) }} ฿</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL and Custom CSS for address selection are completely removed --}}
@endsection
