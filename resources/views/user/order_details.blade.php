@extends('layouts.app')

@section('content')
    <div class="row g-0">
        {{-- User Sidebar Inclusion --}}
        @include('components.sidebaruser')

        <div class="col-md-9">
            <div class="p-4 p-md-5">
                {{-- Back Button --}}
                <div class="mb-4">
                    <a href="{{ route('user.orders') }}" class="btn btn-sm btn-outline-secondary rounded-pill" data-mdb-ripple-init>
                        <i class="fas fa-arrow-left me-1"></i> กลับไปหน้าประวัติคำสั่งซื้อ
                    </a>
                </div>

                <h2 class="mb-4 fw-bold text-primary">
                    <i class="fas fa-file-invoice me-2"></i> รายละเอียดคำสั่งซื้อ #{{ $order->id }}
                </h2>

                {{-- PHP Logic for Status Display --}}
                @php
                    // กำหนดสีและข้อความตามสถานะการสั่งซื้อ
                    $statusMapping = [
                        'pending' => ['color' => 'warning', 'text' => 'รอการชำระเงิน/ยืนยัน', 'icon' => 'fa-hourglass-half'], 
                        'shipped' => ['color' => 'info', 'text' => 'กำลังจัดส่ง', 'icon' => 'fa-truck'], 
                        'delivered' => ['color' => 'success', 'text' => 'จัดส่งสำเร็จ', 'icon' => 'fa-check-circle'], 
                        'cancelled' => ['color' => 'danger', 'text' => 'ยกเลิกแล้ว', 'icon' => 'fa-times-circle'],
                    ];
                    $currentStatus = $statusMapping[$order->status] ?? ['color' => 'secondary', 'text' => 'ไม่ทราบสถานะ', 'icon' => 'fa-question-circle'];
                @endphp

                <div class="card border-0 rounded-4 shadow-sm mb-5">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row mb-4">
                            {{-- Order Meta (ID and Date) --}}
                            <div class="col-md-6">
                                <p class="small text-muted mb-1">คำสั่งซื้อเมื่อ</p>
                                <h5 class="fw-bold mb-0">{{  thaidate('j F Y',$order->created_at) }}</h5>
                            </div>
                            {{-- Order Status --}}
                            <div class="col-md-6 text-md-end">
                                <p class="small text-muted mb-1">สถานะ</p>
                                <span class="badge rounded-pill bg-{{ $currentStatus['color'] }} fs-6 px-3 py-2 text-uppercase">
                                    <i class="fas {{ $currentStatus['icon'] }} me-1"></i> {{ $currentStatus['text'] }}
                                </span>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            {{-- Shipping Information --}}
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-shipping-fast me-2"></i> ข้อมูลการจัดส่ง</h5>
                                <div class="p-3 border rounded-3 bg-light-subtle">
                                    <p class="mb-1"><strong>ผู้รับ:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                                    <p class="mb-1"><strong>โทร:</strong> {{ $order->phone ?? '-' }}</p>
                                    <p class="mb-1"><strong>ที่อยู่:</strong> {{ $order->address }}</p>
                                    <p class="mb-1"><strong>อำเภอ/เขต:</strong> {{ $order->district }}</p>
                                    <p class="mb-1"><strong>จังหวัด:</strong> {{ $order->province }}</p>
                                    <p class="mb-0"><strong>รหัสไปรษณีย์:</strong> {{ $order->zip }}</p>
                                </div>
                            </div>

                            {{-- Financial Summary --}}
                            <div class="col-lg-6">
                                <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-receipt me-2"></i> สรุปยอดชำระ</h5>
                                <div class="p-3 border rounded-3 bg-light-subtle">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>ยอดรวมสินค้า:</span>
                                        <span class="fw-bold">{{ number_format($order->subtotal, 2) }} ฿</span>
                                    </div>
                                    <div class="d-flex justify-content-between small mb-2 {{ $order->discount > 0 ? 'text-success' : 'text-muted' }}">
                                        <span>ส่วนลดคูปอง:</span>
                                        <span class="fw-bold">- {{ number_format($order->discount, 2) }} ฿</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between fw-bolder fs-5 text-danger">
                                        <span>ยอดสุทธิที่ต้องชำระ:</span>
                                        <span>{{ number_format($order->total, 2) }} ฿</span>
                                    </div>
                                    <p class="text-start small text-muted mt-2 mb-0">
                                        วิธีการชำระเงิน: {{ $order->payment_method ?? 'Cash on Delivery (COD)' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Items List Table --}}
                <h4 class="mb-3 fw-bold text-dark"><i class="fas fa-box me-2"></i> สินค้าในคำสั่งซื้อ ({{ $order->orderItems->count() }} รายการ)</h4>
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="fw-bold">สินค้า</th>
                                        <th class="fw-bold text-center">ราคา/เล่ม</th>
                                        <th class="fw-bold text-center">จำนวน</th>
                                        <th class="fw-bold text-end">ราคารวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <p class="fw-bold mb-1">{{ $item->book->title }}</p>
                                                <p class="text-muted mb-0 small">รหัส: {{ $item->book->isbn ?? $item->book_id }}</p>
                                            </td>
                                            <td class="text-center">{{ number_format($item->price, 2) }} ฿</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity, 2) }} ฿</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
