@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">📝 รายละเอียดคำสั่งซื้อ #{{ $order->id }}</h2>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ข้อมูลผู้สั่งซื้อ --}}
        <div class="card mb-4">
            <div class="card-header">ข้อมูลผู้สั่งซื้อ & ที่อยู่จัดส่ง</div>
            <div class="card-body">
                <p><strong>ชื่อ:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
                <p><strong>ที่อยู่:</strong> {{ $order->address }}, {{ $order->district }}, {{ $order->province }},
                    {{ $order->zip }}</p>
                <p><strong>ยอดรวม:</strong> {{ number_format($order->subtotal, 2) }} ฿</p>
                <p><strong>ส่วนลด:</strong> {{ number_format($order->discount, 2) }} ฿</p>
                <p><strong>ยอดสุทธิ:</strong> {{ number_format($order->total, 2) }} ฿</p>
                <p><strong>สถานะ:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>ชำระเงิน:</strong> {{ json_decode($order->payment_meta)->method ?? '-' }}</p>
            </div>
        </div>

        {{-- รายการหนังสือ --}}
        <div class="card mb-4">
            <div class="card-header">รายการหนังสือ</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>หนังสือ</th>
                            <th>ราคา/หน่วย</th>
                            <th>จำนวน</th>
                            <th>รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->book->title ?? '-' }}</td>
                                <td>{{ number_format($item->price, 2) }} ฿</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price * $item->quantity, 2) }} ฿</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ฟอร์มแก้ไขสถานะ --}}
        <div class="card mb-4">
            <div class="card-header">แก้ไขสถานะคำสั่งซื้อ</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label for="status" class="form-label">สถานะ</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">อัปเดตสถานะ</button>
                </form>
            </div>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">กลับไปหน้ารายการคำสั่งซื้อ</a>
    </div>
@endsection
