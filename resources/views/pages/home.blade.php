@extends('layouts.main')
@section('title', 'Beranda')
@section('content')
@php
  $ruang = ['Sofa'=>'Ruang Tamu','Meja'=>'Ruang Makan','Kursi'=>'Ruang Kerja','Lemari'=>'Ruang Ganti','Tempat Tidur'=>'Kamar Tidur','Dekorasi'=>'Aksen Ruangan'];
@endphp
<section class="hero p-4 p-lg-5 mb-5">
  <div class="row align-items-center g-4">
    <div class="col-lg-6">
      <span class="badge-az mb-3 d-inline-block" style="background:rgba(255,255,255,.12);color:#fff">Koleksi {{ date('Y') }}</span>
      <h1 class="display-5 mb-3">Furniture premium untuk ruang yang tenang.</h1>
      <p class="mb-4" style="max-width:46ch">Sofa, meja, kursi, sampai tempat tidur dengan material pilihan. Pilih, isi data pengiriman, dan pesanan Anda langsung tercatat.</p>
      <a href="{{ route('produk') }}" class="btn btn-azure me-2">Lihat koleksi</a>
      <a href="{{ route('kategori') }}" class="btn btn-outline-light" style="border-radius:10px;padding:.65rem 1.4rem">Jelajahi kategori</a>
    </div>
    <div class="col-lg-6">
      @if($hero)<div class="img-frame"><img src="{{ $hero->image }}" alt="{{ $hero->name }}" style="height:380px;max-height:380px" onerror="this.style.display='none'"></div>@endif
    </div>
  </div>
</section>

<section class="mb-5">
  <div class="d-flex justify-content-between align-items-end mb-4">
    <h2 class="h3 mb-0">Koleksi terbaru</h2><a href="{{ route('produk') }}">Lihat semua produk</a>
  </div>
  <div class="row g-4">
    @foreach($terbaru as $f)<div class="col-6 col-lg-3">@include('partials.product-card')</div>@endforeach
  </div>
</section>

<section>
  <h2 class="h3 mb-4">Belanja per ruangan</h2>
  <div class="row g-4">
    @foreach($categories as $c)
    <div class="col-6 col-md-4 col-lg-3">
      <a href="{{ route('produk', ['kategori'=>$c->category]) }}" class="fcard">
        <div class="thumb"><img src="{{ $c->image }}" alt="{{ $c->category }}" loading="lazy" onerror="this.style.display='none'"></div>
        <div class="body"><h3 class="h6 mb-0">{{ $ruang[$c->category] ?? $c->category }}</h3><small class="text-secondary">{{ $c->category }}, {{ $c->total }} produk</small></div>
      </a>
    </div>
    @endforeach
  </div>
</section>
@endsection