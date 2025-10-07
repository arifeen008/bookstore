@extends('layouts.app')

@section('content')
    <div class="row">
        @include('components.sidebaruser')
        <div class="col-md-9">
            <div class="container py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>📦 ที่อยู่ของฉัน</h5>
                    <button class="btn btn-primary btn-sm" data-mdb-ripple-init data-mdb-modal-init
                        data-mdb-target="#addAddressModal">
                        <i class="fas fa-plus me-2"></i> เพิ่มที่อยู่ใหม่
                    </button>
                </div>

                @foreach ($addresses as $address)
                    <div class="card mb-3 shadow-sm border-{{ $address->is_default ? 'success' : 'light' }}">
                        <div class="card-body">
                            <h6 class="fw-bold mb-1">{{ $address->fullname }}</h6>
                            <p class="mb-1 text-muted">{{ $address->phone }}</p>
                            <p class="mb-1">{{ $address->address_line }}, {{ $address->district }},
                                {{ $address->province }} {{ $address->zipcode }}</p>

                            <div class="d-flex justify-content-end gap-2">
                                @if (!$address->is_default)
                                    <form method="POST" action="{{ route('user.address.default', $address->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-success btn-sm">ตั้งเป็นที่อยู่หลัก</button>
                                    </form>
                                @else
                                    <span class="badge bg-success align-self-center">ที่อยู่หลัก</span>
                                @endif

                                <button class="btn btn-warning btn-sm" data-mdb-ripple-init data-mdb-modal-init
                                    data-mdb-target="#editAddressModal{{ $address->id }}">
                                    แก้ไข
                                </button>

                                <form method="POST" action="{{ route('user.address.delete', $address->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal แก้ไขที่อยู่ --}}
                    <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('user.address.update', $address->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">แก้ไขที่อยู่</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="fullname" class="form-control mb-2"
                                            value="{{ $address->fullname }}" placeholder="ชื่อ-นามสกุล" required>
                                        <input type="text" name="phone" class="form-control mb-2"
                                            value="{{ $address->phone }}" placeholder="เบอร์โทร" required>
                                        <textarea name="address_line" class="form-control mb-2" required>{{ $address->address_line }}</textarea>
                                        <input type="text" name="district" class="form-control mb-2"
                                            value="{{ $address->district }}" placeholder="อำเภอ/เขต">
                                        <input type="text" name="province" class="form-control mb-2"
                                            value="{{ $address->province }}" placeholder="จังหวัด">
                                        <input type="text" name="zipcode" class="form-control"
                                            value="{{ $address->zipcode }}" placeholder="รหัสไปรษณีย์">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">บันทึก</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">ยกเลิก</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal เพิ่มที่อยู่ --}}
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('user.address.store') }}">
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input type="text" id="full_name" name="full_name" class="form-control" required />
                            <label class="form-label" for="full_name">ชื่อ-นามสกุล</label>
                        </div>

                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input type="text" id="phone" name="phone" class="form-control" required />
                            <label class="form-label" for="phone">เบอร์โทรศัพท์</label>
                        </div>

                        <div class="form-outline mb-4" data-mdb-input-init>
                            <textarea id="address_line" name="address_line" class="form-control" rows="2" required></textarea>
                            <label class="form-label" for="address_line">ที่อยู่ (บ้านเลขที่ หมู่ ถนน)</label>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" id="district" name="district" class="form-control"
                                        required />
                                    <label class="form-label" for="district">อำเภอ / เขต</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text" id="province" name="province" class="form-control"
                                        required />
                                    <label class="form-label" for="province">จังหวัด</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input type="text" id="zipcode" name="zipcode" class="form-control" required />
                            <label class="form-label" for="zipcode">รหัสไปรษณีย์</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-mdb-dismiss="modal">❌ ปิด</button>
                        <button type="submit" class="btn btn-success">💾 บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
