@extends('layouts.main')
@section('title', 'Pesanan berhasil')
@section('content')
<div class="panel mx-auto" style="max-width:760px">
  <h1 class="h2">Pesanan Anda sudah kami terima.</h1>
  <p class="text-secondary">Simpan kode pesanan ini untuk menanyakan status pengiriman.</p>
  <div class="d-inline-block px-4 py-2 mb-4 rounded-3" style="background:var(--navy);color:#fff;font-size:1.7rem;font-weight:800;letter-spacing:.08em">{{ $order['kode'] }}</div>
  <table class="table"><tbody>
    <tr><th class="text-secondary fw-normal" style="width:10rem">Tanggal</th><td>{{ $order['tanggal'] }}</td></tr>
    <tr><th class="text-secondary fw-normal">Produk</th><td>{{ $order['produk'] }} &times; {{ $order['qty'] }}</td></tr>
    <tr><th class="text-secondary fw-normal">Penerima</th><td>{{ $order['nama'] }} ({{ $order['telepon'] }})</td></tr>
    <tr><th class="text-secondary fw-normal">Alamat</th><td>{{ $order['alamat'] }}, {{ $order['kota'] }} {{ $order['kode_pos'] }}</td></tr>
    <tr><th class="text-secondary fw-normal">Pembayaran</th><td>{{ $order['pembayaran'] }}</td></tr>
    <tr><th class="text-secondary fw-normal">Subtotal</th><td>Rp {{ number_format($order['subtotal'],0,',','.') }}</td></tr>
    <tr><th class="text-secondary fw-normal">Ongkos kirim</th><td>{{ $order['ongkir'] ? 'Rp '.number_format($order['ongkir'],0,',','.') : 'Gratis' }}</td></tr>
    <tr><th class="text-secondary fw-normal">Total bayar</th><td class="price">Rp {{ number_format($order['total'],0,',','.') }}</td></tr>
    <tr><th class="text-secondary fw-normal">Status</th><td>Menunggu pembayaran</td></tr>
  </tbody></table>
  <div class="d-flex flex-wrap gap-2 d-print-none">
    <button type="button" class="btn btn-azure" onclick="window.print()">Cetak nota</button>
    <a href="{{ route('produk') }}" class="btn btn-navy">Lanjut belanja</a>
    <a href="{{ route('home') }}" class="btn btn-outline-navy">Ke beranda</a>
  </div>
</div>
@endsection