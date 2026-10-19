@extends('layouts.main')
@section('title', 'Checkout')
@section('content')
<nav class="small text-secondary mb-3"><a href="{{ route('home') }}">Beranda</a> / <a href="{{ route('produk.show', $f->id) }}">{{ $f->name }}</a> / Checkout</nav>
<h1 class="h2 mb-4">Checkout</h1>
<form action="{{ route('checkout.store') }}" method="POST" class="row g-4">
  @csrf
  <input type="hidden" name="furniture_id" value="{{ $f->id }}">
  <input type="hidden" name="qty" value="{{ $qty }}">
  <div class="col-lg-7">
    <div class="panel">
      <h5 class="mb-3">Data pemesan</h5>
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Nama lengkap</label>
          <input name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror"><div class="invalid-feedback">{{ $errors->first('nama') }}</div></div>
        <div class="col-md-6"><label class="form-label">Nomor telepon</label>
          <input name="telepon" value="{{ old('telepon') }}" class="form-control @error('telepon') is-invalid @enderror"><div class="invalid-feedback">{{ $errors->first('telepon') }}</div></div>
        <div class="col-12"><label class="form-label">Email <span class="text-secondary">(opsional)</span></label>
          <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror"><div class="invalid-feedback">{{ $errors->first('email') }}</div></div>
        <div class="col-12"><label class="form-label">Alamat lengkap</label>
          <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea><div class="invalid-feedback">{{ $errors->first('alamat') }}</div></div>
        <div class="col-md-8"><label class="form-label">Kota</label>
          <input name="kota" value="{{ old('kota') }}" class="form-control @error('kota') is-invalid @enderror"><div class="invalid-feedback">{{ $errors->first('kota') }}</div></div>
        <div class="col-md-4"><label class="form-label">Kode pos</label>
          <input name="kode_pos" maxlength="5" value="{{ old('kode_pos') }}" class="form-control @error('kode_pos') is-invalid @enderror"><div class="invalid-feedback">{{ $errors->first('kode_pos') }}</div></div>
      </div>
      <h5 class="mt-4 mb-3">Metode pembayaran</h5>
      <div class="d-grid gap-2">
        @foreach(['Transfer Bank','QRIS','Bayar di Tempat (COD)'] as $m)
        <label class="pay"><input class="form-check-input me-2" type="radio" name="pembayaran" value="{{ $m }}" @checked(old('pembayaran','Transfer Bank')==$m)>{{ $m }}</label>
        @endforeach
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="panel position-sticky" style="top:96px">
      <h5>Ringkasan pesanan</h5>
      <div class="d-flex gap-3 my-3 align-items-center">
        <img src="{{ $f->image }}" alt="" width="80" height="80" style="object-fit:cover;border-radius:12px;background:#DBEAFE" onerror="this.style.visibility='hidden'">
        <div><div class="fw-semibold">{{ $f->name }}</div><small class="text-secondary">{{ $f->rupiah }} &times; {{ $qty }}</small></div>
      </div>
      <table class="table table-borderless mb-3"><tbody>
        <tr><td>Subtotal</td><td class="text-end">Rp {{ number_format($subtotal,0,',','.') }}</td></tr>
        <tr><td>Ongkos kirim</td><td class="text-end">{{ $ongkir ? 'Rp '.number_format($ongkir,0,',','.') : 'Gratis' }}</td></tr>
        <tr class="border-top"><th>Total</th><th class="text-end price">Rp {{ number_format($subtotal+$ongkir,0,',','.') }}</th></tr>
      </tbody></table>
      <button class="btn btn-azure w-100">Buat pesanan</button>
      <a class="d-block text-center mt-3" href="{{ route('produk.show', $f->id) }}">Kembali ke produk</a>
    </div>
  </div>
</form>
@endsection