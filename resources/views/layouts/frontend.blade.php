<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keuportal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        .card-icon {
            font-size: 2.5rem;
            color: #2c3e50;
        }
        .card-title {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .navbar {
            background-color: #2c3e50;
            color: white;
        }
        .navbar a {
            color: white !important;
        }
        .navbar .dropdown-menu {
    background-color: #ffffff; /* Warna latar belakang dropdown */
    color: #000000; /* Warna teks dropdown */
    padding: 5px; /* Padding dalam dropdown */
    border-radius: 5px; /* Membuat sudut dropdown melengkung */
    overflow: hidden; /* Mencegah isi dropdown meluas */
    width: auto; /* Lebar otomatis sesuai isi */
    min-width: unset; /* Menghapus batas lebar minimum */
    right: 50px; /* Mengatur jarak dropdown dari tepi kanan */
}


.navbar .dropdown-menu .dropdown-item {
    white-space: nowrap; /* Mencegah teks membungkus ke baris berikutnya */
    overflow: hidden; /* Menyembunyikan teks yang terlalu panjang */
    text-overflow: ellipsis; /* Menambahkan "..." untuk teks yang terlalu panjang */
    color: #000000 !important;
}

.navbar .dropdown-menu .dropdown-item:hover {
    background-color: #f1f1f1; /* Warna latar belakang saat hover */
    color: #000000 !important; /* Warna teks saat hover */
}
.dropdown-item i {
    margin-left: 3px; /* Memberi jarak antara ikon dan teks */
    color: #000000; /* Warna ikon */
}
.dropdown-item:hover i {
    color: #f00; /* Warna ikon saat hover */
}



        .profile-info {
            font-size: 1.1rem;
            font-weight: bold;
        }
        a.card-link {
            text-decoration: none;
            color: inherit;
        }
    </style>
    @yield('css')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Keuportal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link active" href="home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="tagihan">Tagihan</a></li>
                    <li class="nav-item"><a class="nav-link" href="transaksi_online">Transaksi Online</a></li>
                    <li class="nav-item"><a class="nav-link" href="riwayat">Riwayat Pembayaran</a></li>
                    <li class="nav-item"><a class="nav-link" href="bantuan">Bantuan</a></li>
                    <li class="nav-item dropdown">
                </ul>
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Muhammad Abdul Karim
</a>
<ul class="dropdown-menu dropdown-menu-end">
    <li>
        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout <i class="fas fa-sign-out-alt"></i> 
        </a>
    </li>
</ul>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
                    
                </li>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
    @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
