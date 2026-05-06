<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOSQU - Management System</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>KOSQU</h2>
            <p>MANAGEMENT SYSTEM</p>
        </div>

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="fa-solid fa-table-cells-large"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-bed"></i>
                    Data Kamar
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-user-group"></i>
                    Data Penghuni
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-file-invoice"></i>
                    Transaksi & Validasi
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-wallet"></i>
                    Laporan Keuangan
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <li class="nav-item" style="list-style: none;">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-gear"></i>
                    Settings
                </a>
            </li>
            <li class="nav-item" style="list-style: none;">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Logout
                </a>
            </li>
        </div>
    </div>

    <div class="main-content">
        <header>
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted); font-size: 0.9rem;"></i>
                <input type="text" placeholder="Cari data...">
            </div>
            
            <div class="header-right">
                <div class="notification-btn">
                    <i class="fa-regular fa-bell"></i>
                    <div class="notification-dot"></div>
                </div>

                <div class="header-separator"></div>

                <div class="user-profile">
                    <div style="text-align: right;">
                        <div style="font-weight: 700; font-size: 0.95rem; color: #1E293B;">Admin Utama</div>
                        <div style="font-size: 0.8rem; color: #94A3B8;">Super Admin</div>
                    </div>
                    <img src="{{ asset('images/admin-profile.jpg') }}" alt="Profile" class="profile-img">
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
