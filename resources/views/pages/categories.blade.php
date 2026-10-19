@extends('layouts.main')
@section('title', 'Kategori')
@section('content')
<h1 class="h2 mb-1">Kategori produk</h1>
<p class="text-secondary mb-4">Pilih furniture sesuai ruangan yang ingin Anda isi.</p>
<div class="row g-4">
  @foreach($categories as $c)
  <div class="col-6 col-lg-3">
    <a href="{{ route('produk', ['kategori'=>$c->category]) }}" class="fcard">
      <div class="thumb" style="aspect-ratio:1/1"><img src="{{ $c->image }}" alt="{{ $c->category }}" loading="lazy" onerror="this.style.display='none'"></div>
      <div class="body"><h3 class="h5 mb-0">{{ $c->category }}</h3><small class="text-secondary">{{ $c->total }} produk</small></div>
    </a>
  </div>
  @endforeach
</div>
@endsection