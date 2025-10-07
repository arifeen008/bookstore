@extends('layouts.app')

@section('content')
    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-lg-5 col-md-8">
            {{-- Modern Elevated Card (MDB Style) --}}
            <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">

                <div class="card-body">
                    <h2 class="mb-4 text-center fw-bold text-primary">
                        <i class="bi bi-person-add me-2"></i> สมัครสมาชิกใหม่
                    </h2>
                    <p class="text-center text-muted mb-4">เข้าร่วมกับเราเพื่อสิทธิพิเศษมากมาย</p>

                    <form method="POST" action="{{ route('register.submit') }}">
                        @csrf

                        {{-- Name Field (form-outline) --}}
                        <div class="form-outline mb-4">
                            <label class="form-label" for="name">ชื่อ - นามสกุล</label>
                            <input type="text" id="name"
                                class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Email Field (form-outline) --}}
                        <div class="form-outline mb-4">
                            <label class="form-label" for="email">อีเมล</label>
                            <input type="email" id="email"
                                class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Password Field (form-outline) --}}
                        <div class="form-outline mb-4">
                            <label class="form-label" for="password">รหัสผ่าน</label>
                            <input type="password" id="password"
                                class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input type="text" id="phone" name="phone" class="form-control" required>
                            <label class="form-label" for="phone">เบอร์โทรศัพท์</label>
                        </div>

                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input type="date" id="birthdate" name="birthdate" class="form-control">
                            <label class="form-label" for="birthdate">วันเกิด</label>
                        </div>

                        {{-- Confirm Password Field (form-outline) --}}
                        <div class="form-outline mb-4">
                            <label class="form-label" for="password-confirm">ยืนยันรหัสผ่าน</label>
                            <input type="password" id="password-confirm" class="form-control form-control-lg rounded-3"
                                name="password_confirmation" required autocomplete="new-password">
                        </div>

                        {{-- Submit Button (MDB style with shadow) --}}
                        <div class="d-grid mb-3">
                            <button class="btn btn-primary btn-lg rounded-pill shadow-lg" type="submit">
                                ลงทะเบียน <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                            </button>
                        </div>

                        {{-- Login Link --}}
                        <p class="text-center mt-4 text-muted">
                            มีบัญชีอยู่แล้วใช่ไหม? <a href="{{ route('login') }}"
                                class="text-primary fw-bold text-decoration-none">เข้าสู่ระบบที่นี่</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
