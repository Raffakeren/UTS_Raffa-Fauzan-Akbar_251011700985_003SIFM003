@extends('layouts.main')
@section('title', 'Produk')
@section('content')
<div class="row g-4">
  <aside class="col-lg-3">
    <div class="panel">
      <form action="{{ route('produk') }}" method="GET" class="mb-4">
        <label for="q" class="form-label fw-semibold">Cari produk</label>
        <input id="q" class="form-control mb-2" name="q" value="{{ $q }}" placeholder="Nama, kategori, material">
        @if($kategori)<input type="hidden" name="kategori" value="{{ $kategori }}">@endif
        <button class="btn btn-azure w-100">Cari</button>
      </form>
      <h6 class="fw-bold mb-2">Kategori</h6>
      <nav class="side d-grid gap-1">
        <a href="{{ route('produk', array_filter(['q'=>$q])) }}" class="{{ !$kategori ? 'on' : '' }}">Semua produk <span>{{ $total }}</span></a>
        @foreach($categories as $c)
          <a href="{{ route('produk', array_filter(['kategori'=>$c->category,'q'=>$q])) }}" class="{{ $kategori==$c->category ? 'on' : '' }}">{{ $c->category }} <span>{{ $c->total }}</span></a>
        @endforeach
      </nav>
    </div>
  </aside>
  <section class="col-lg-9">
    <h1 class="h3 mb-1">{{ $q ? 'Hasil untuk "'.$q.'"' : ($kategori ?: 'Semua produk') }}</h1>
    <p class="text-secondary mb-4">Menampilkan {{ $items->count() }} dari {{ $total }} produk</p>
    <div class="row g-4">
      @forelse($items as $f)
        <div class="col-sm-6 col-xl-4">@include('partials.product-card')</div>
      @empty
        <div class="col-12"><div class="panel text-center py-5">Tidak ada produk yang cocok. <a href="{{ route('produk') }}">Tampilkan semua produk</a>.</div></div>
      @endforelse
    </div>
  </section>
</div>
@endsection