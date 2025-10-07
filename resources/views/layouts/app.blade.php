<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Bookstore') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>

@php
    $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
@endphp

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">📚 Bookstore</a>
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                                👤 {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="{{ route('user.profile') }}">ข้อมูลผู้ใช้งาน</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.address.index') }}">ที่อยู่ของฉัน</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.orders') }}">รายการสั่งซื้อ</a></li>
                                
                                
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        <a class="dropdown-item">
                                            @csrf
                                            <button class="btn btn-primary" data-mdb-ripple-init type="submit">🚪
                                                ออกจากระบบ</button>

                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="fas fa-cart-shopping"></i>
                                @if ($cartCount > 0)
                                    <span class="badge rounded-pill badge-notification bg-danger">
                                        {{ $cartCount }}</span>
                                @endif
                            </a>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">เข้าสู่ระบบ</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">สมัครสมาชิก</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>


    <div class="container my-4">
        @yield('content')
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.umd.min.js"></script>
    @stack('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด!',
                text: '{{ session('error') }}',
                // timer: 2000,
                showConfirmButton: true
            });
        </script>
    @endif

</body>

</html>
