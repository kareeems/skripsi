{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #28a745; /* Warna hijau */
            padding-top: 20px;
            position: fixed;
            transition: width 0.5s ease-in-out;
            width: 250px;
        }
        .sidebar h2 {
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar a {
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover, .sidebar .active {
            background-color: #218838;
        }

        .sidebar-body {
            display: block;
            padding: 8px 16px;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #2ccc17; /* Warna oranye */
        }
        .logout-btn {
            color: #dc3545; /* Warna merah */
            border: none;
            background: none;
            cursor: pointer;
        }
        .alert-danger > ul {
            margin: 0;
        }
        /* Responsif */
        @media (max-width: 768px) {
            .sidebar {
                display: none; /* Sembunyikan sidebar di perangkat kecil */
            }
            .content {
                margin-left: 0; /* Menghilangkan margin saat di perangkat kecil */
            }
            .sidebar.open {
                display: block; /* Menampilkan sidebar saat terbuka */
            }
        }
        @media (min-width: 769px) {
            .sidebar {
                display: block; /* Menampilkan sidebar di perangkat besar */
            }
        }
    </style>
    @yield('style')
</head>
<body>

<div class="d-flex">
    <nav class="sidebar" id="sidebar" aria-label="sidebar">
        <h2 class="sidebar-title">Dashboard</h2>
        <a href="{{ route('users.index') }}" class="sidebar-body {{ request()->routeIs('users*') ? 'active' : '' }}">Manajemen User</a>
        <a href="{{ route('items.index') }}" class="sidebar-body {{ request()->routeIs('items*') ? 'active' : '' }}">Kategori Tagihan</a>
        <a href="{{ route('transactions.index') }}" class="sidebar-body {{ request()->routeIs('transactions*') ? 'active' : '' }}">Manajemen Transaksi</a>
        <a href="{{ route('payments.index') }}" class="sidebar-body {{ request()->routeIs('payments*') ? 'active' : '' }}">Laporan Pembayaran</a>
    </nav>

    <div class="content flex-grow-1">
        <div class="header">
            <button id="sidebarToggle" class="btn btn-success d-lg-none">
                <i class="fas fa-bars"></i>
            </button>
            <h1>Aplikasi Pembayaran</h1>
            <button class="btn btn-outline-danger logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout <i class="fas fa-sign-out-alt ml-3"></i>
            </button>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb')</li>
            </ol>
        </nav>

        <div class="container">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    sidebarToggle.onclick = function(event) {
        event.stopPropagation();
        sidebar.classList.toggle('open');
        if (sidebar.classList.contains('open')) {
            sidebar.style.display = 'block';
        } else {
            sidebar.style.display = 'none';
        }
    };

    // Menyembunyikan sidebar jika ukuran layar lebih besar dari 768px
    window.onresize = function() {
        if (window.innerWidth > 768) {
            sidebar.style.display = 'block';
            sidebar.classList.remove('open');
        } else {
            sidebar.style.display = 'none';
        }
    };

    // Menutup sidebar jika mengklik di luar sidebar
    document.addEventListener('click', function(event) {
        const isClickInside = sidebar.contains(event.target) || sidebarToggle.contains(event.target);
        if (!isClickInside && sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
            sidebar.style.display = 'none';
        }
    });
</script>
@yield('script')
</body>
</html>
