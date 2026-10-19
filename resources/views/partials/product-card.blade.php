<a href="{{ route('produk.show', $f->id) }}" class="fcard">
  <div class="thumb"><img src="{{ $f->image }}" alt="{{ $f->name }}" loading="lazy" onerror="this.style.display='none'"></div>
  <div class="body">
    <span class="badge-az align-self-start mb-2">{{ $f->category }}</span>
    <h3 class="h6 mb-1">{{ $f->name }}</h3>
    <small class="text-secondary">{{ $f->material }}</small>
    <div class="price mt-auto pt-3">{{ $f->rupiah }}</div>
  </div>
</a>