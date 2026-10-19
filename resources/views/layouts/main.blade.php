<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'DecoRaffa') | DecoRaffa</title>
    <link rel="preconnect" href="https://googleapis.com">
    <link href="https://googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('Bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --navy: #0F172A;
            --deep: #1E3A8A;
            --azure: #3B82F6;
            --bg: #F8FAFC;
            --line: #E2E8F0;
            --mute: #64748B;
        }

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            top: 0 !important;
            left: 0 !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--navy);
            padding-top: 76px !important;
        }

        h1, h2, h3, h4, h5 {
            font-weight: 800;
            letter-spacing: -.02em;
        }

        a {
            color: var(--deep);
        }

        :focus-visible {
            outline: 3px solid var(--azure);
            outline-offset: 2px;
        }

        .navbar-dr.fixed-top {
            top: 0 !important;
            margin-top: 0 !important;
            position: fixed !important;
            right: 0 !important;
            left: 0 !important;
            z-index: 1030 !important;
        }

        .navbar-dr {
            background: var(--navy);
            padding: .9rem 0;
            box-shadow: 0 2px 14px rgba(15, 23, 42, .25);
        }

        .navbar-dr .navbar-brand {
            color: #fff;
            font-weight: 800;
            font-size: 1.4rem;
        }

        .navbar-dr .navbar-brand span {
            color: var(--azure);
        }

        .navbar-dr .nav-link {
            color: #CBD5E1;
            font-weight: 500;
            padding: .5rem 1rem;
            border-radius: 10px;
        }

        .navbar-dr .nav-link:hover, .navbar-dr .nav-link.active {
            color: #fff;
            background: rgba(59, 130, 246, .22);
        }

        .navbar-dr .form-control {
            border-radius: 10px 0 0 10px;
            border: 0;
        }

        .btn-azure {
            background: var(--azure);
            color: #fff;
            border: 0;
            border-radius: 10px;
            font-weight: 600;
            padding: .65rem 1.4rem;
        }

        .btn-azure:hover {
            background: #2563EB;
            color: #fff;
        }

        .btn-navy {
            background: var(--navy);
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
            padding: .65rem 1.4rem;
        }

        .btn-navy:hover {
            background: var(--deep);
            color: #fff;
        }

        .btn-outline-navy {
            border: 2px solid var(--navy);
            color: var(--navy);
            border-radius: 10px;
            font-weight: 600;
            padding: .6rem 1.4rem;
        }

        .btn-outline-navy:hover {
            background: var(--navy);
            color: #fff;
        }

        .badge-az {
            background: rgba(59, 130, 246, .12);
            color: var(--deep);
            font-weight: 600;
            border-radius: 8px;
            padding: .4rem .7rem;
        }

        .img-frame {
            background: linear-gradient(135deg, #DBEAFE, #E2E8F0);
            border-radius: 16px;
            overflow: hidden;
        }

        .img-frame img {
            width: 100%;
            object-fit: cover;
            display: block;
        }

        .fcard {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .fcard:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(30, 58, 138, .15);
            color: inherit;
        }

        .fcard .thumb {
            aspect-ratio: 4/3;
            overflow: hidden;
            background: linear-gradient(135deg, #DBEAFE, #E2E8F0);
        }

        .fcard .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }

        .fcard:hover .thumb img {
            transform: scale(1.06);
        }

        .fcard .body {
            padding: 1.1rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .price {
            color: var(--deep);
            font-weight: 800;
        }

        .hero {
            background: linear-gradient(120deg, var(--navy), var(--deep));
            border-radius: 24px;
            color: #fff;
            overflow: hidden;
        }

        .hero p {
            color: #CBD5E1;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .side a.on {
            background: var(--navy);
            color: #fff;
        }

        .side a {
            display: flex;
            justify-content: space-between;
            padding: .6rem .9rem;
            border-radius: 10px;
            text-decoration: none;
            color: var(--navy);
        }

        .side a:hover:not(.on) {
            background: #EFF6FF;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border-color: #CBD5E1;
            padding: .65rem .9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--azure);
            box-shadow: 0 0 0 .2rem rgba(59, 130, 246, .25);
        }

        .pay {
            border: 1px solid #CBD5E1;
            border-radius: 10px;
            padding: .8rem 1rem;
            display: block;
            cursor: pointer;
            background: #fff;
        }

        .pay:has(input:checked) {
            border-color: var(--azure);
            background: #EFF6FF;
        }

        footer.site {
            background: var(--navy);
            color: #94A3B8;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
        }

        footer.site h6 {
            color: #fff;
        }

        footer.site a {
            color: #94A3B8;
            text-decoration: none;
        }

        footer.site a:hover {
            color: var(--azure);
        }

        @media print {
            body {
                padding-top: 0;
                background: #fff;
            }
            .panel {
                border: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-dr fixed-top d-print-none">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Deco<span>Raffa</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav mx-lg-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('produk*') ? 'active' : '' }}" href="{{ route('produk') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kategori') ? 'active' : '' }}" href="{{ route('kategori') }}">Kategori</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang kami</a>
                </li>
            </ul>
            <form class="d-flex mt-3 mt-lg-0" action="{{ route('produk') }}" method="GET">
                <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="Cari furniture" aria-label="Cari furniture">
                <button class="btn btn-azure" style="border-radius: 0 10px 10px 0">Cari</button>
            </form>
        </div>
    </div>
</nav>

<main class="container my-5">
    @yield('content')
</main>

<footer class="site d-print-none">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <h5 class="text-white">Deco<span style="color: var(--azure)">Raffa</span></h5>
                <p style="max-width: 38ch">Katalog furniture premium untuk rumah yang tertata and tahan lama.</p>
            </div>
            <div class="col-6 col-lg-3">
                <h6>Jelajahi</h6>
                <a class="d-block" href="{{ route('home') }}">Beranda</a>
                <a class="d-block" href="{{ route('produk') }}">Semua produk</a>
                <a class="d-block" href="{{ route('kategori') }}">Kategori</a>
                <a class="d-block" href="{{ route('tentang') }}">Tentang kami</a>
            </div>
            <div class="col-6 col-lg-4">
                <h6>Proyek UTS</h6>
                <p class="mb-0">Rekayasa Web, kelas SIFM003<br>Tema 5: Katalog Furniture</p>
            </div>
        </div>
        <hr style="border-color: #1E293B" class="mt-4">
