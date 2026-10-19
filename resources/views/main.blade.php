<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DecoRaffa Premium Furniture')</title>
    
    <!-- 100% MANUAL OFFLINE: Mengubah jalur menjadi huruf kecil sesuai standar folder public Laragon -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    
    <style>
        :root {
            --primary-navy: #0F172A;   /* Navy Gelap Mewah */
            --secondary-navy: #1E293B; /* Navy Medium */
            --accent-blue: #3B82F6;    /* Soft Azure Blue */
            --bg-clean: #F8FAFC;       /* Abu-abu Sangat Soft */
            --text-dark: #334155;      /* Karbon Elegan */
        }

        body {
            background-color: var(--bg-clean);
            color: var(--text-dark);
            font-family: system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background-color: var(--primary-navy) !important;
            padding: 15px 0;
            border-bottom: 4px solid var(--accent-blue);
        }

        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.5rem;
            color: #ffffff !important;
        }

        .navbar-brand-custom span {
            color: var(--accent-blue);
        }

        .nav-link-custom {
            color: #94A3B8 !important;
            font-weight: 600;
            padding: 8px 16px !important;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: #ffffff !important;
        }

        .search-input-custom {
            background-color: var(--secondary-navy);
            border: 1px solid #334155;
            color: #ffffff;
            border-radius: 8px;
            padding: 7px 14px;
        }

        .btn-custom-accent {
            background-color: var(--accent-blue);
            color: #ffffff;
            font-weight: 600;
            border-radius: 8px;
            padding: 7px 18px;
            border: none;
        }

        /* STRUKTUR BOXED LAYOUT DI TENGAH LAYAR */
        .main-container-boxed {
            background-color: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
            padding: 40px;
            margin-top: 40px;
            margin-bottom: 40px;
            flex: 1;
        }

        .detail-img-large {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 20px;
        }

        .footer-custom {
            background-color: var(--primary-navy);
            color: #64748B;
            padding: 25px 0;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom" href="/">Deco<span>Raffa</span>.</a>
            <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarDecoRaffa">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link nav-link-custom {{ Request::is('/') ? 'active' : '' }}" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom {{ Request::is('produk*') ? 'active' : '' }}" href="/produk">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom {{ Request::is('kategori*') ? 'active' : '' }}" href="/kategori">Kategori</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-custom {{ Request::is('tentang-kami*') ? 'active' : '' }}" href="/tentang-kami">Tentang Kami</a></li>
                </ul>
                <form action="/produk" method="GET" class="d-flex gap-2">
                    <input type="text" name="q" class="form-control search-input-custom" placeholder="Cari furniture..." value="{{ request('q') }}">
                    <button type="submit" class="btn btn-custom-accent">Cari</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <main class="main-container-boxed">
            @yield('content')
        </main>
    </div>

    <footer class="footer-custom">
        <div class="container">
            <p class="mb-1 text-white font-weight-bold">DecoRaffa Premium Furniture Catalog</p>
            <p class="mb-2 small">© 2026 Tugas UTS Rekayasa Web - Kelas SIFM003</p>
            <p class="mb-0 small font-monospace text-info">Raffa Fauzan Akbar | NIM: 251011700985</p>
        </div>
    </footer>

    <!-- 100% MANUAL OFFLINE JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
