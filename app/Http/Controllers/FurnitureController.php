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