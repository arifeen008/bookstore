@extends('layouts.admin')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">📦 รายการคำสั่งซื้อ</h2>

        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="ค้นหาโดย ID, ชื่อ, อีเมล หรือสถานะ">
                <button class="btn btn-primary" type="submit">ค้นหา</button>
            </div>
        </form>

        @if ($orders->count())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ผู้สั่งซื้อ</th>
                        <th>ยอดรวม</th>
                        <th>ส่วนลด</th>
                        <th>รวมสุทธิ</th>
                        <th>สถานะ</th>
                        <th>วันที่</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? '-' }} <br> <small>{{ $order->user->email ?? '-' }}</small></td>
                            <td>{{ number_format($order->subtotal, 2) }} ฿</td>
                            <td>{{ number_format($order->discount, 2) }} ฿</td>
                            <td>{{ number_format($order->total, 2) }} ฿</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                    class="btn btn-sm btn-info">ดูรายละเอียด</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $orders->links() }}
        @else
            <p>ไม่พบคำสั่งซื้อ</p>
        @endif
    </div>
@endsection
