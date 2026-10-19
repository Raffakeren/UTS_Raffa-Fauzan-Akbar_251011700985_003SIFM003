@extends('layouts.main')

@section('title', 'Tentang Kami - DecoRaffa Premium')

@section('content')
<!-- BAGIAN UTAS: BANNER JUDUL PREMIUM -->
<div class="row justify-content-center text-center my-5">
    <div class="col-lg-8">
        <span class="badge bg-primary text-white px-3 py-2 rounded-pill fw-bold small text-uppercase mb-3" style="letter-spacing: 1px; font-size: 11px;">
            Kisah Brand Kami
        </span>
        <h1 class="fw-extrabold text-dark mb-3" style="letter-spacing: -1px; font-size: 2.8rem; font-weight: 800;">
            DecoRaffa dibuat untuk rumah yang dipakai setiap hari.
        </h1>
        <div class="mx-auto my-3" style="width: 60px; height: 4px; background-color: #3b82f6; border-radius: 2px;"></div>
        <p class="text-secondary lead px-md-4 mt-3" style="font-size: 1.15rem; line-height: 1.7;">
            Kami menyediakan furniture dengan desain modern, fungsional, dan material yang dipilih satu per satu supaya ruang hidup Anda lebih nyaman, estetik, dan tertata rapi.
        </p>
    </div>
</div>

<!-- PERBAIKAN EKSTREM: KOTAK DIATUR BERJAJAR 3 KOLOM KE SAMPING (TIDAK MEMANJANG KE BAWAH) -->
<div class="row g-4 mb-5">
    <!-- Kolom 1: Material -->
    <div class="col-md-4">
        <div class="card h-100 border-0 p-4 shadow-sm text-center text-md-start" style="background-color: #f8fafc; border: 1px solid #e2e8f0 !important; border-radius: 16px;">
            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 mb-3 text-primary shadow-sm" style="width: 50px; height: 50px; min-width: 50px;">
                <svg xmlns="http://w3.org" width="22" height="22" fill="#3b82f6" class="bi bi-gem" viewBox="0 0 16 16">
                    <path d="M3.1.5a.5.5 0 0 1 .4-.2h9.1a.5.5 0 0 1 .4.2l2.9 4.3a.5.5 0 0 1 0 .6l-7.7 9.8a.5.5 0 0 1-.8 0L.2 5.4a.5.5 0 0 1 0-.6zm.75.75L1.5 5h13l-2.35-3.5z"/>
                </svg>
            </div>
            <h5 class="fw-bold text-dark mb-2" style="letter-spacing: -0.3px;">Material Pilihan</h5>
            <p class="text-muted small mb-0" style="line-height: 1.6; font-size: 0.88rem;">
                Kayu jati solid, walnut premium, marmer alami, dan kain rajut pilihan yang awet bertahun-tahun untuk menopang kenyamanan maksimal keluarga Anda.
            </p>
        </div>
    </div>

    <!-- Kolom 2: Desain Modern -->
    <div class="col-md-4">
        <div class="card h-100 border-0 p-4 shadow-sm text-center text-md-start" style="background-color: #f8fafc; border: 1px solid #e2e8f0 !important; border-radius: 16px;">
            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 mb-3 text-primary shadow-sm" style="width: 50px; height: 50px; min-width: 50px;">
                <svg xmlns="http://w3.org" width="22" height="22" fill="#3b82f6" class="bi bi-compass" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.5-12.5A.5.5 0 0 1 9 4v1.5a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5"/>
                </svg>
            </div>
            <h5 class="fw-bold text-dark mb-2" style="letter-spacing: -0.3px;">Desain Modern</h5>
            <p class="text-muted small mb-0" style="line-height: 1.6; font-size: 0.88rem;">
                Setiap perabot dirancang dengan struktur garis bersih dan warna netral mewah yang sangat mudah dipadukan dengan berbagai konsep dekorasi interior rumah.
            </p>
        </div>
    </div>

    <!-- Kolom 3: Pesan Tanpa Ribet -->
    <div class="col-md-4">
        <div class="card h-100 border-0 p-4 shadow-sm text-center text-md-start" style="background-color: #f8fafc; border: 1px solid #e2e8f0 !important; border-radius: 16px;">
            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-3 mb-3 text-primary shadow-sm" style="width: 50px; height: 50px; min-width: 50px;">
                <svg xmlns="http://w3.org" width="22" height="22" fill="#3b82f6" class="bi bi-credit-card" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1z"/>
                </svg>
            </div>
            <h5 class="fw-bold text-dark mb-2" style="letter-spacing: -0.3px;">Pesan Tanpa Ribet</h5>
            <p class="text-muted small mb-0" style="line-height: 1.6; font-size: 0.88rem;">
                Sistem katalog yang interaktif memudahkan Anda memilih perabot, mengisi form dummy pemesanan, dan langsung mendapatkan nomor pesanan digital secara instan.
            </p>
        </div>
    </div>
</div>
@endsection
