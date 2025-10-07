@extends('layouts.app')

@section('content')
    <div class="row">
        @include('components.sidebaruser')
        <div class="col-md-9">
            <h4 class="mb-4">ข้อมูลผู้ใช้งาน</h4>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>ชื่อ-นามสกุล</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>วันเกิด</label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $user->birthdate) }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label>อีเมล</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                </div>

                <button class="btn btn-primary">💾 บันทึกข้อมูล</button>
            </form>
        </div>
    </div>
@endsection
