<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <!-- Bootstrap & MDBootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .hover-bg:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.25) !important;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-light">

    <div class="d-flex">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-dark text-white vh-100 p-3" style="width:250px;">
            <h4 class="text-center mb-4">📚 Admin Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white px-3 py-2 rounded hover-bg">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.books.index') }}" class="nav-link text-white px-3 py-2 rounded hover-bg">
                        <i class="fas fa-book me-2"></i> จัดการหนังสือ
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.books.bulk') }}" class="nav-link text-white px-3 py-2 rounded hover-bg">
                        <i class="fas fa-plus me-2"></i> เพิ่มหนังสือหลายเล่ม
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link text-white px-3 py-2 rounded hover-bg">
                        <i class="fas fa-chart-bar me-2"></i> ยอดการสั่งซื้อ
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4 rounded">
                <div class="container-fluid">
                    <button class="btn btn-dark d-lg-none" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="navbar-brand">Admin Dashboard</span>
                </div>
            </nav>

            @yield('content')
        </div>
    </div>

    <!-- MDBootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.js"></script>

    <script>
        // Toggle Sidebar สำหรับจอเล็ก
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            let sidebar = document.getElementById('sidebar');
            if (sidebar.style.display === 'none' || sidebar.style.display === '') {
                sidebar.style.display = 'block';
            } else {
                sidebar.style.display = 'none';
            }
        });
    </script>


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
