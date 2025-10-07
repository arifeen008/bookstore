@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-fluid">
        <h2 class="fw-bold mb-4">📊 แผงควบคุม (Dashboard)</h2>

        {{-- Stats Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <h6>หนังสือทั้งหมด</h6>
                    <h3>{{ $booksCount }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <h6>คำสั่งซื้อทั้งหมด</h6>
                    <h3>{{ $ordersCount }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <h6>ผู้ใช้งาน</h6>
                    <h3>{{ $usersCount }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm p-3">
                    <h6>ยอดขายรวม</h6>
                    <h3>{{ number_format($totalSales, 2) }} ฿</h3>
                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                คำสั่งซื้อล่าสุด
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ผู้สั่ง</th>
                            <th>ยอดรวม</th>
                            <th>วันที่</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ number_format($order->total, 2) }} ฿</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
