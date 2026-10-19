@extends('layouts.main')
@section('title', $f->name)
@section('content')
<nav class="small text-secondary mb-4"><a href="{{ route('home') }}">Beranda</a> / <a href="{{ route('produk') }}">Produk</a> / <a href="{{ route('produk', ['kategori'=>$f->category]) }}">{{ $f->category }}</a> / {{ $f->name }}</nav>
<div class="row g-5 align-items-start">
  <div class="col-md-6">
    <div class="img-frame">
      <img src="{{ $f->image }}" alt="{{ $f->name }}" style="object-fit:cover;max-height:500px;width:100%;border-radius:16px;aspect-ratio:4/5" onerror="this.style.display='none'">
    </div>
  </div>
  <div class="col-md-6">
    <span class="badge-az">{{ $f->category }}</span>
    <h1 class="mt-3 mb-2" style="font-size:2.2rem">{{ $f->name }}</h1>
    <div class="price mb-4" style="font-size:1.8rem">{{ $f->rupiah }}</div>
    <table class="table mb-4"><tbody>
      <tr><th class="text-secondary fw-normal" style="width:8rem">Kategori</th><td>{{ $f->category }}</td></tr>
      <tr><th class="text-secondary fw-normal">Material</th><td>{{ $f->material }}</td></tr>
      <tr><th class="text-secondary fw-normal">Dimensi</th><td>{{ $f->dimensions }}</td></tr>
    </tbody></table>
    <form action="{{ route('checkout', $f->id) }}" method="GET" class="d-flex gap-3 align-items-stretch">
      <div class="input-group" style="width:150px">
        <button type="button" class="btn btn-outline-navy" id="minus" aria-label="Kurangi">-</button>
        <input type="number" name="qty" id="qty" value="1" min="1" max="20" class="form-control text-center px-1" aria-label="Jumlah">
        <button type="button" class="btn btn-outline-navy" id="plus" aria-label="Tambah">+</button>
      </div>
      <button class="btn btn-azure flex-grow-1">Checkout sekarang</button>
    </form>
    <small class="text-secondary d-block mt-3">Gratis ongkir untuk pembelian mulai Rp 10.000.000.</small>
  </div>
</div>

<section class="mt-5 pt-3">
  <h2 class="h4 mb-4">Produk terkait</h2>
  <div class="row g-4">
    @foreach($terkait as $f)<div class="col-6 col-lg-3">@include('partials.product-card')</div>@endforeach
  </div>
</section>
@endsection
@push('scripts')
<script>
const qty = document.getElementById('qty');
document.getElementById('minus').addEventListener('click', () => { qty.value = Math.max(1, (parseInt(qty.value) || 1) - 1); });
document.getElementById('plus').addEventListener('click', () => { qty.value = Math.min(20, (parseInt(qty.value) || 1) + 1); });
</script>
@endpush