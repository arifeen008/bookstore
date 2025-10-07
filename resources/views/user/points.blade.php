@extends('layouts.app')

@section('content')
<div class="row">
    {{-- Sidebar ด้านซ้าย --}}
    @include('components.sidebaruser')

    {{-- ส่วนเนื้อหา --}}
    <div class="col-md-9">
        <div class="container py-5">

            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-primary text-white rounded-top-4 py-3 d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><i class="fas fa-star me-2"></i> คะแนนสะสมของฉัน</h4>
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                        {{ number_format(Auth::user()->points ?? 0) }} คะแนน
                    </span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-0">
                        คุณสามารถใช้คะแนนสะสมเพื่อเป็นส่วนลดในการสั่งซื้อครั้งถัดไป
                        หรือรับสิทธิพิเศษต่าง ๆ ตามที่ร้านกำหนด
                    </p>
                </div>
            </div>

            {{-- ประวัติการได้รับคะแนน --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-history me-2"></i> ประวัติการได้รับคะแนน</h5>
                </div>
                <div class="card-body p-0">
                    {{-- @if ($histories->count() > 0)
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>วันที่</th>
                                    <th>คำสั่งซื้อ</th>
                                    <th>คะแนนที่ได้รับ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($histories as $item)
                                    <tr>
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                        <td>#{{ $item->order_id }}</td>
                                        <td class="text-success fw-bold">+{{ $item->points }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p class="mb-0">ยังไม่มีประวัติการได้รับคะแนน</p>
                        </div>
                    @endif --}}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
