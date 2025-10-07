@extends('layouts.app')

@section('content')
    <div class="row g-0">
        {{-- User Sidebar Inclusion (components.sidebaruser) --}}
        @include('components.sidebaruser')

        <div class="col-md-9">
            <div class="p-4 p-md-5">
                <h2 class="mb-4 fw-bold text-primary">
                    <i class="fas fa-history me-2"></i> ประวัติคำสั่งซื้อ
                </h2>
                <p class="text-muted mb-5">ตรวจสอบสถานะและยอดรวมของคำสั่งซื้อทั้งหมดของคุณ</p>

                @if (count($orders) > 0)
                    <div class="row g-4">
                        @foreach ($orders as $order)
                            {{-- PHP Logic for Status Display --}}
                            @php
                                // กำหนดสีและข้อความตามสถานะการสั่งซื้อ
                                $statusMapping = [
                                    'pending' => [
                                        'color' => 'warning',
                                        'text' => 'รอการชำระเงิน/ยืนยัน',
                                        'icon' => 'fa-hourglass-half',
                                    ],
                                    'shipped' => ['color' => 'info', 'text' => 'กำลังจัดส่ง', 'icon' => 'fa-truck'],
                                    'delivered' => [
                                        'color' => 'success',
                                        'text' => 'จัดส่งสำเร็จ',
                                        'icon' => 'fa-check-circle',
                                    ],
                                    'cancelled' => [
                                        'color' => 'danger',
                                        'text' => 'ยกเลิกแล้ว',
                                        'icon' => 'fa-times-circle',
                                    ],
                                ];
                                $currentStatus = $statusMapping[$order->status] ?? [
                                    'color' => 'secondary',
                                    'text' => 'ไม่ทราบสถานะ',
                                    'icon' => 'fa-question-circle',
                                ];
                            @endphp

                            <div class="col-12">
                                <div class="card mb-3 border-0 rounded-4 shadow-sm">
                                    {{-- Card Header: Order ID, Date, and Status --}}
                                    <div
                                        class="card-header d-flex justify-content-between align-items-center bg-light p-3 rounded-top-4 border-bottom">

                                        {{-- 1. Order ID & Date --}}
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">คำสั่งซื้อ #{{ $order->id }}</h6>
                                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>
                                                สั่งซื้อเมื่อ: {{ $order->created_at->format('d/m/Y H:i') }}</small>
                                        </div>

                                        {{-- 2. Status Badge --}}
                                        <span
                                            class="badge rounded-pill bg-{{ $currentStatus['color'] }} fs-6 px-3 py-2 text-uppercase">
                                            <i class="fas {{ $currentStatus['icon'] }} me-1"></i>
                                            {{ $currentStatus['text'] }}
                                        </span>
                                    </div>

                                    {{-- Card Body: Items, Total and Action Button --}}
                                    <div class="card-body p-4">

                                        {{-- Items List (รายการหนังสือ) --}}
                                        <h6 class="mb-3 fw-bold text-secondary"><i class="fas fa-book-open me-1"></i>
                                            รายการสินค้า</h6>
                                        <ul class="list-group list-group-flush mb-4">
                                            @foreach ($order->orderItems as $item)
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent border-0">
                                                    <div class="text-truncate" style="max-width: 70%;">
                                                        {{ $item->book->title }}
                                                        <span
                                                            class="badge bg-primary-subtle text-primary ms-2">{{ $item->quantity }}
                                                            เล่ม</span>
                                                    </div>
                                                    <span
                                                        class="small text-muted">{{ number_format($item->price * $item->quantity, 2) }}
                                                        ฿</span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <hr class="mt-0 mb-3">

                                        <div class="d-flex justify-content-between align-items-start">
                                            {{-- 3. Total Summary (Subtotal, Discount, Total) --}}
                                            <div class="w-50">
                                                <div class="d-flex justify-content-between small text-muted">
                                                    <span>ยอดรวมสินค้า:</span>
                                                    <span class="fw-bold">{{ number_format($order->subtotal, 2) }} ฿</span>
                                                </div>
                                                <div
                                                    class="d-flex justify-content-between small mb-2 {{ $order->discount > 0 ? 'text-success' : 'text-muted' }}">
                                                    <span>ส่วนลด:</span>
                                                    <span class="fw-bold">- {{ number_format($order->discount, 2) }}
                                                        ฿</span>
                                                </div>
                                                <hr class="my-2">
                                                <div class="d-flex justify-content-between fw-bolder fs-5 text-danger">
                                                    <span>ยอดสุทธิ:</span>
                                                    <span>{{ number_format($order->total, 2) }} ฿</span>
                                                </div>
                                            </div>

                                            {{-- Action Button (View Details only) --}}
                                            <div class="text-end">
                                                <a href="{{ route('user.orders.show', $order->id) }}"
                                                    class="btn btn-primary rounded-pill shadow-sm mt-3"
                                                    data-mdb-ripple-init>
                                                    ดูรายละเอียด <i class="fas fa-chevron-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination (If $orders collection supports it) --}}
                    @if (method_exists($orders, 'links'))
                        <div class="mt-5 d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    {{-- No Orders State --}}
                    <div class="text-center py-5 border rounded-4 bg-light">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <p class="fs-5 text-muted">คุณยังไม่มีคำสั่งซื้อในประวัติ</p>
                        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill shadow-sm mt-2">
                            เริ่มช้อปปิ้งเลย <i class="fas fa-shopping-bag ms-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
