<?php
/**
 * DecoRaffa - setup otomatis UTS Rekayasa Web
 * Raffa Fauzan Akbar | 251011700985 | SIFM003
 * Jalankan di ROOT proyek Laravel:  php setup_uts.php
 */
if (!file_exists('artisan')) { exit("Jalankan script ini di root folder Laravel (folder yang berisi file artisan).\n"); }
function w(string $path, string $content): void {
    if (!is_dir(dirname($path))) { mkdir(dirname($path), 0777, true); }
    file_put_contents($path, $content);
    echo "OK  $path\n";
}

// ---------- .env ----------
if (file_exists('.env')) {
    $env = file_get_contents('.env');
    $set = ['DB_CONNECTION'=>'mysql','DB_HOST'=>'127.0.0.1','DB_PORT'=>'3306','DB_DATABASE'=>'db_uts_251011700985','DB_USERNAME'=>'root','DB_PASSWORD'=>'','APP_NAME'=>'DecoRaffa'];
    foreach ($set as $k => $v) {
        $line = "$k=$v";
        if (preg_match("/^#?\s*$k=.*/m", $env)) { $env = preg_replace("/^#?\s*$k=.*/m", $line, $env, 1); }
        else { $env .= "\n$line"; }
    }
    file_put_contents('.env', $env);
    echo "OK  .env (DB_DATABASE=db_uts_251011700985)\n";
}

// ---------- 1. MIGRATION ----------
foreach (glob('database/migrations/*_create_furnitures_table.php') as $old) { unlink($old); }
w('database/migrations/'.date('Y_m_d_His').'_create_furnitures_table.php', <<<'X_END'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('furnitures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('material');
            $table->unsignedInteger('price');
            $table->string('dimensions');
            $table->string('image', 500);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('furnitures'); }
};
X_END);

// ---------- MODEL ----------
w('app/Models/Furniture.php', <<<'X_END'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Furniture extends Model
{
    protected $table = 'furnitures';
    protected $fillable = ['name','category','material','price','dimensions','image'];

    public function getRupiahAttribute(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }
}
X_END);

// ---------- 2. SEEDER ----------
w('database/seeders/FurnitureSeeder.php', <<<'X_END'
<?php
namespace Database\Seeders;

use App\Models\Furniture;
use Illuminate\Database\Seeder;

class FurnitureSeeder extends Seeder
{
    public function run(): void
    {
        $u = fn ($id) => "https://images.unsplash.com/photo-$id?auto=format&fit=crop&w=900&q=80";
        $rows = [
            ['Sofa Velvet Navy 3 Dudukan', 'Sofa', 'Beludru dan rangka kayu jati', 8900000, '220 x 92 x 80 cm', $u('1555041469-a586c61ea9bc')],
            ['Sofa Lounge Linen Grey', 'Sofa', 'Linen premium dan busa HR', 6750000, '200 x 90 x 78 cm', $u('1540574163026-643ea20ade25')],
            ['Meja Makan Jati Solid', 'Meja', 'Kayu jati solid finishing natural', 7400000, '180 x 90 x 75 cm', $u('1493663284031-b7e3aefcae8e')],
            ['Meja Kopi Marmer Bulat', 'Meja', 'Marmer dan kaki besi hitam', 3250000, 'Diameter 80 x 42 cm', $u('1586023492125-27b2c045efd7')],
            ['Kursi Makan Scandinavian', 'Kursi', 'Kayu oak dan jok kulit sintetis', 1450000, '48 x 52 x 84 cm', $u('1567538096630-e0c55bd6374c')],
            ['Kursi Lounge Premium', 'Kursi', 'Kain boucle dan kaki kayu', 3150000, '75 x 78 x 80 cm', $u('1519710164239-da123dc03ef4')],
            ['Tempat Tidur King Walnut', 'Tempat Tidur', 'Kayu walnut dan headboard berlapis', 11500000, '210 x 190 x 110 cm', $u('1505693416388-ac5ce068fe85')],
            ['Tempat Tidur Queen Minimalis', 'Tempat Tidur', 'Kayu mahoni dan kain tenun', 8200000, '210 x 170 x 100 cm', $u('1616594039964-ae9021a400a0')],
            ['Lemari Pakaian 3 Pintu', 'Lemari', 'Kayu lapis HPL anti rayap', 6300000, '150 x 55 x 200 cm', $u('1524758631624-e2822e304c36')],
            ['Rak Pajangan Aksen Emas', 'Dekorasi', 'Besi powder coating dan kaca', 2100000, '90 x 35 x 160 cm', $u('1618220179428-22790b461013')],
        ];
        foreach ($rows as [$name, $category, $material, $price, $dimensions, $image]) {
            Furniture::create(compact('name', 'category', 'material', 'price', 'dimensions', 'image'));
        }
    }
}
X_END);

// ---------- 3. CONTROLLER ----------
w('app/Http/Controllers/FurnitureController.php', <<<'X_END'
<?php
namespace App\Http\Controllers;

use App\Models\Furniture;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FurnitureController extends Controller
{
    const ONGKIR = 100000;
    const GRATIS_ONGKIR = 10000000;

    private function categories()
    {
        return Furniture::selectRaw('category, count(*) as total, min(image) as image')
            ->groupBy('category')->orderBy('category')->get();
    }

    public function home()
    {
        return view('pages.home', [
            'terbaru'    => Furniture::latest('id')->take(4)->get(),
            'categories' => $this->categories(),
            'hero'       => Furniture::orderBy('id')->first(),
        ]);
    }

    public function products(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $kategori = $request->query('kategori');

        $items = Furniture::query()
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                  ->orWhere('category', 'like', "%$q%")
                  ->orWhere('material', 'like', "%$q%");
            }))
            ->when($kategori, fn ($query) => $query->where('category', $kategori))
            ->orderBy('id', 'desc')
            ->get();

        return view('pages.products', [
            'items' => $items, 'q' => $q, 'kategori' => $kategori,
            'categories' => $this->categories(), 'total' => Furniture::count(),
        ]);
    }

    public function show($id)
    {
        $f = Furniture::findOrFail($id);
        $terkait = Furniture::where('category', $f->category)->where('id', '!=', $f->id)->take(4)->get();
        if ($terkait->count() < 4) {
            $terkait = $terkait->concat(
                Furniture::where('category', '!=', $f->category)->inRandomOrder()->take(4 - $terkait->count())->get()
            );
        }
        return view('pages.show', compact('f', 'terkait'));
    }

    public function categoriesPage() { return view('pages.categories', ['categories' => $this->categories()]); }

    public function about() { return view('pages.about'); }

    public function checkout(Request $request, $id)
    {
        $f = Furniture::findOrFail($id);
        $qty = max(1, min(20, (int) $request->query('qty', 1)));
        $subtotal = $f->price * $qty;
        $ongkir = $subtotal >= self::GRATIS_ONGKIR ? 0 : self::ONGKIR;
        return view('pages.checkout', compact('f', 'qty', 'subtotal', 'ongkir'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'furniture_id' => 'required|exists:furnitures,id',
            'qty'          => 'required|integer|min:1|max:20',
            'nama'         => 'required|string|max:100',
            'telepon'      => 'required|string|max:20',
            'email'        => 'nullable|email',
            'alamat'       => 'required|string|max:300',
            'kota'         => 'required|string|max:60',
            'kode_pos'     => 'required|digits:5',
            'pembayaran'   => 'required|in:Transfer Bank,QRIS,Bayar di Tempat (COD)',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'digits'   => 'Kode pos harus 5 digit angka.',
            'email'    => 'Format email belum benar.',
        ]);

        $f = Furniture::findOrFail($data['furniture_id']);
        $subtotal = $f->price * $data['qty'];
        $ongkir = $subtotal >= self::GRATIS_ONGKIR ? 0 : self::ONGKIR;

        session()->put('order', $data + [
            'kode' => 'DR-'.strtoupper(Str::random(6)),
            'produk' => $f->name, 'harga' => $f->price,
            'subtotal' => $subtotal, 'ongkir' => $ongkir, 'total' => $subtotal + $ongkir,
            'tanggal' => now()->format('d M Y, H:i'),
        ]);
        return redirect()->route('sukses');
    }

    public function success()
    {
        $order = session('order');
        if (!$order) { return redirect()->route('home'); }
        return view('pages.success', compact('order'));
    }
}
X_END);

// ---------- 4. ROUTES ----------
w('routes/web.php', <<<'X_END'
<?php
use App\Http\Controllers\FurnitureController as F;
use Illuminate\Support\Facades\Route;

Route::get('/', [F::class, 'home'])->name('home');
Route::get('/produk', [F::class, 'products'])->name('produk');
Route::get('/produk/{id}', [F::class, 'show'])->name('produk.show');
Route::get('/kategori', [F::class, 'categoriesPage'])->name('kategori');
Route::get('/tentang-kami', [F::class, 'about'])->name('tentang');
Route::get('/checkout/{id}', [F::class, 'checkout'])->name('checkout');
Route::post('/checkout', [F::class, 'store'])->name('checkout.store');
Route::get('/pesanan/sukses', [F::class, 'success'])->name('sukses');
X_END);

// ---------- 5. LAYOUT ----------
w('resources/views/layouts/main.blade.php', <<<'X_END'
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'DecoRaffa') | DecoRaffa</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('Bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<style>
:root{--navy:#0F172A;--deep:#1E3A8A;--azure:#3B82F6;--bg:#F8FAFC;--line:#E2E8F0;--mute:#64748B}
body{font-family:'Plus Jakarta Sans','Segoe UI',sans-serif;background:var(--bg);color:var(--navy);padding-top:76px}
h1,h2,h3,h4,h5{font-weight:800;letter-spacing:-.02em}
a{color:var(--deep)}
:focus-visible{outline:3px solid var(--azure);outline-offset:2px}
.navbar-dr{background:var(--navy);padding:.9rem 0;box-shadow:0 2px 14px rgba(15,23,42,.25)}
.navbar-dr .navbar-brand{color:#fff;font-weight:800;font-size:1.4rem}
.navbar-dr .navbar-brand span{color:var(--azure)}
.navbar-dr .nav-link{color:#CBD5E1;font-weight:500;padding:.5rem 1rem;border-radius:10px}
.navbar-dr .nav-link:hover,.navbar-dr .nav-link.active{color:#fff;background:rgba(59,130,246,.22)}
.navbar-dr .form-control{border-radius:10px 0 0 10px;border:0}
.btn-azure{background:var(--azure);color:#fff;border:0;border-radius:10px;font-weight:600;padding:.65rem 1.4rem}
.btn-azure:hover{background:#2563EB;color:#fff}
.btn-navy{background:var(--navy);color:#fff;border-radius:10px;font-weight:600;padding:.65rem 1.4rem}
.btn-navy:hover{background:var(--deep);color:#fff}
.btn-outline-navy{border:2px solid var(--navy);color:var(--navy);border-radius:10px;font-weight:600;padding:.6rem 1.4rem}
.btn-outline-navy:hover{background:var(--navy);color:#fff}
.badge-az{background:rgba(59,130,246,.12);color:var(--deep);font-weight:600;border-radius:8px;padding:.4rem .7rem}
.img-frame{background:linear-gradient(135deg,#DBEAFE,#E2E8F0);border-radius:16px;overflow:hidden}
.img-frame img{width:100%;object-fit:cover;display:block}
.fcard{background:#fff;border:1px solid var(--line);border-radius:16px;overflow:hidden;height:100%;display:flex;flex-direction:column;text-decoration:none;color:inherit;transition:transform .25s ease,box-shadow .25s ease}
.fcard:hover{transform:translateY(-4px);box-shadow:0 14px 30px rgba(30,58,138,.15);color:inherit}
.fcard .thumb{aspect-ratio:4/3;overflow:hidden;background:linear-gradient(135deg,#DBEAFE,#E2E8F0)}
.fcard .thumb img{width:100%;height:100%;object-fit:cover;transition:transform .4s ease}
.fcard:hover .thumb img{transform:scale(1.06)}
.fcard .body{padding:1.1rem;display:flex;flex-direction:column;flex:1}
.price{color:var(--deep);font-weight:800}
.hero{background:linear-gradient(120deg,var(--navy),var(--deep));border-radius:24px;color:#fff;overflow:hidden}
.hero p{color:#CBD5E1}
.panel{background:#fff;border:1px solid var(--line);border-radius:16px;padding:1.5rem}
.side a.on{background:var(--navy);color:#fff}
.side a{display:flex;justify-content:space-between;padding:.6rem .9rem;border-radius:10px;text-decoration:none;color:var(--navy)}
.side a:hover:not(.on){background:#EFF6FF}
.form-control,.form-select{border-radius:10px;border-color:#CBD5E1;padding:.65rem .9rem}
.form-control:focus,.form-select:focus{border-color:var(--azure);box-shadow:0 0 0 .2rem rgba(59,130,246,.25)}
.pay{border:1px solid #CBD5E1;border-radius:10px;padding:.8rem 1rem;display:block;cursor:pointer;background:#fff}
.pay:has(input:checked){border-color:var(--azure);background:#EFF6FF}
footer.site{background:var(--navy);color:#94A3B8;padding:3rem 0 1.5rem;margin-top:4rem}
footer.site h6{color:#fff}footer.site a{color:#94A3B8;text-decoration:none}footer.site a:hover{color:var(--azure)}
@media print{body{padding-top:0;background:#fff}.panel{border:0}}
@media (prefers-reduced-motion:reduce){*{transition:none!important}}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-dr fixed-top d-print-none">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">Deco<span>Raffa</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Buka menu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav mx-lg-auto">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('produk*') ? 'active' : '' }}" href="{{ route('produk') }}">Produk</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('kategori') ? 'active' : '' }}" href="{{ route('kategori') }}">Kategori</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang kami</a></li>
      </ul>
      <form class="d-flex mt-3 mt-lg-0" action="{{ route('produk') }}" method="GET">
        <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="Cari furniture" aria-label="Cari furniture">
        <button class="btn btn-azure" style="border-radius:0 10px 10px 0">Cari</button>
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
        <h5 class="text-white">Deco<span style="color:var(--azure)">Raffa</span></h5>
        <p style="max-width:38ch">Katalog furniture premium untuk rumah yang tertata dan tahan lama.</p>
      </div>
      <div class="col-6 col-lg-3"><h6>Jelajahi</h6>
        <a class="d-block" href="{{ route('home') }}">Beranda</a><a class="d-block" href="{{ route('produk') }}">Semua produk</a>
        <a class="d-block" href="{{ route('kategori') }}">Kategori</a><a class="d-block" href="{{ route('tentang') }}">Tentang kami</a></div>
      <div class="col-6 col-lg-4"><h6>Proyek UTS</h6>
        <p class="mb-0">Rekayasa Web, kelas SIFM003<br>Tema 5: Katalog Furniture</p></div>
    </div>
    <hr style="border-color:#1E293B" class="mt-4">
    <small>&copy; 2026 DecoRaffa. Dibuat oleh Raffa Fauzan Akbar - 251011700985</small>
  </div>
</footer>
<script src="{{ asset('Bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
X_END);

// ---------- 6. PARTIAL KARTU ----------
w('resources/views/partials/product-card.blade.php', <<<'X_END'
<a href="{{ route('produk.show', $f->id) }}" class="fcard">
  <div class="thumb"><img src="{{ $f->image }}" alt="{{ $f->name }}" loading="lazy" onerror="this.style.display='none'"></div>
  <div class="body">
    <span class="badge-az align-self-start mb-2">{{ $f->category }}</span>
    <h3 class="h6 mb-1">{{ $f->name }}</h3>
    <small class="text-secondary">{{ $f->material }}</small>
    <div class="price mt-auto pt-3">{{ $f->rupiah }}</div>
  </div>
</a>
X_END);

// ---------- 7. HOME ----------
w('resources/views/pages/home.blade.php', <<<'X_END'
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
X_END);

// ---------- 8. PRODUCTS ----------
w('resources/views/pages/products.blade.php', <<<'X_END'
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
X_END);

// ---------- 9. SHOW ----------
w('resources/views/pages/show.blade.php', <<<'X_END'
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
X_END);

// ---------- 10. CATEGORIES ----------
w('resources/views/pages/categories.blade.php', <<<'X_END'
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
X_END);

// ---------- 11. ABOUT ----------
w('resources/views/pages/about.blade.php', <<<'X_END'
@extends('layouts.main')
@section('title', 'Tentang kami')
@section('content')
<div class="hero p-4 p-lg-5 mb-5">
  <h1 class="display-6 mb-3" style="max-width:20ch">DecoRaffa dibuat untuk rumah yang dipakai setiap hari.</h1>
  <p class="mb-0" style="max-width:60ch">Kami menyediakan furniture dengan desain modern, fungsional, dan material yang dipilih satu per satu supaya ruang hidup Anda lebih nyaman dan tertata.</p>
</div>
<div class="row g-4">
  @foreach([['Material pilihan','Kayu jati, walnut, marmer, dan kain premium yang awet bertahun-tahun.'],['Desain modern','Garis bersih dan warna netral yang mudah dipadukan dengan isi rumah.'],['Pesan tanpa ribet','Pilih produk, isi data, dan nomor pesanan langsung Anda terima.']] as [$t,$d])
  <div class="col-md-4"><div class="panel h-100" style="border-top:4px solid var(--azure)"><h3 class="h5">{{ $t }}</h3><p class="text-secondary mb-0">{{ $d }}</p></div></div>
  @endforeach
</div>
@endsection
X_END);

// ---------- 12. CHECKOUT ----------
w('resources/views/pages/checkout.blade.php', <<<'X_END'
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
X_END);

// ---------- 13. SUCCESS ----------
w('resources/views/pages/success.blade.php', <<<'X_END'
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
X_END);

echo "\nSelesai. Lanjutkan:\n  php artisan config:clear\n  php artisan migrate:fresh --seed --seeder=FurnitureSeeder\n  php artisan serve\n";
