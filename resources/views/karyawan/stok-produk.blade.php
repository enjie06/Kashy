<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Stok Produk – Kashy</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>history.scrollRestoration = 'manual';</script> 
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          terra:      '#C8966C',
          'terra-l':  '#E5B18A',
          'terra-ll': '#F0D7C7',
          'terra-xs': '#FAF2EC',
          muted:      '#9C8B7E',
          border:     '#EAE0D6',
          bg:         '#F5F0EB',
        },
        fontFamily: {
          body: ['Poppins', 'sans-serif'],
        },
      }
    }
  }
</script>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: 'Poppins', sans-serif; background:#F5F0EB; }
  @keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .fade-up { animation: fadeUp 0.4s cubic-bezier(0.2,0.9,0.4,1.1) both; }
  .delay-1 { animation-delay:0.05s; }
  .delay-2 { animation-delay:0.12s; }
  .delay-3 { animation-delay:0.2s; }
  .bn-item.active .bn-icon { background:#F0D7C7; }
  .bn-item.active .bn-icon svg { stroke:#C8966C; }
  .bn-item.active .bn-label { color:#C8966C; font-weight:600; }
  .hide-scroll::-webkit-scrollbar { display:none; }
  .hide-scroll { scrollbar-width:none; }
  .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
  .badge-aman { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
  .badge-menipis { background:#fef9c3; color:#854d0e; border:1px solid #fde68a; }
  .badge-habis { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
</style>
</head>
@include('karyawan.components.topbar')
<body class="bg-bg min-h-screen flex flex-col">
@include('karyawan.components.navbar')

<main class="flex-1 overflow-y-auto pb-28">
  <div class="max-w-md mx-auto px-3 pt-4 space-y-3"> <!-- padding lebih kecil, jarak lebih rapat -->

    <!-- HEADER -->
    <div class="fade-up delay-1">
      <h1 class="text-lg font-bold text-gray-900 mt-0.5">Pencarian Stok</h1>
    </div>

    <!-- SEARCH (lebih ringkas) -->
    <div class="fade-up delay-1">
      <div class="flex items-center gap-2 bg-white border-2 border-border rounded-xl px-3 py-2 focus-within:border-terra transition-all">
        <svg width="14" height="14" fill="none" stroke="#9C8B7E" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Cari nama produk atau SKU..."
               class="border-0 outline-none bg-transparent text-xs text-gray-900 w-full placeholder-stone-300"
               oninput="filterProducts()">
      </div>
    </div>

    <!-- FILTER CHIPS (ukuran lebih kecil) -->
    <div class="overflow-x-auto hide-scroll fade-up delay-2">
      <div class="inline-flex gap-2 pb-1">
        <button class="filter-chip flex-shrink-0 px-3 py-1 rounded-full text-[11px] font-semibold border-2 bg-gray-900 border-gray-900 text-white transition-all" data-filter="semua" onclick="setFilter(this)">Semua Produk</button>
        <button class="filter-chip flex-shrink-0 px-3 py-1 rounded-full text-[11px] font-medium border-2 border-border bg-white text-muted hover:border-terra transition-all" data-filter="habis" onclick="setFilter(this)">Stok Habis</button>
      </div>
    </div>

    <!-- PRODUCT LIST (grid 2 kolom dengan ukuran card lebih kompak) -->
    <div class="fade-up delay-3 space-y-2" id="productList"></div>

    <!-- EMPTY STATE -->
    <div id="emptyState" class="hidden text-center py-10 fade-up">
      <svg class="w-10 h-10 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="#EAE0D6" stroke-width="1.5">
        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
        <line x1="3" y1="6" x2="21" y2="6"/>
        <path d="M16 10a4 4 0 0 1-8 0"/>
      </svg>
      <p class="text-xs font-medium text-muted">Produk tidak ditemukan</p>
    </div>

  </div>
</main>

<script>
@php
  $stokProducts = isset($products)
      ? $products->map(function ($product) {
          $gambar = $product->gambar;

          if ($gambar) {
              $img = \Illuminate\Support\Str::startsWith($gambar, ['http://', 'https://'])
                  ? $gambar
                  : asset('storage/' . $gambar);
          } else {
              $img = 'https://via.placeholder.com/300x350?text=Kashy';
          }

          return [
              'id' => $product->id,
              'name' => $product->nama_produk,
              'sku' => 'PRD-' . str_pad($product->id, 3, '0', STR_PAD_LEFT),
              'cat' => $product->category->nama_kategori ?? 'Tanpa Kategori',
              'stok' => (int) $product->stok,
              'min' => 5,
              'price' => (int) $product->harga,
              'img' => $img,
          ];
      })->values()
      : collect([
          [
              'id' => 1,
              'name' => 'Kaos Lengan Panjang',
              'sku' => 'SKU-001',
              'cat' => 'Atasan',
              'stok' => 24,
              'min' => 10,
              'price' => 15000,
              'img' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=200&q=80',
          ],
          [
              'id' => 2,
              'name' => 'Celana Kulot',
              'sku' => 'SKU-002',
              'cat' => 'Bawahan',
              'stok' => 8,
              'min' => 10,
              'price' => 15000,
              'img' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=200&q=80',
          ],
          [
              'id' => 3,
              'name' => 'Totebag Krem',
              'sku' => 'SKU-003',
              'cat' => 'Aksesori',
              'stok' => 3,
              'min' => 5,
              'price' => 15000,
              'img' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=200&q=80',
          ],
          [
              'id' => 4,
              'name' => 'Kemeja Silk',
              'sku' => 'SKU-004',
              'cat' => 'Atasan',
              'stok' => 0,
              'min' => 5,
              'price' => 15000,
              'img' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=200&q=80',
          ],
          [
              'id' => 5,
              'name' => 'Rok Midi Floral',
              'sku' => 'SKU-005',
              'cat' => 'Bawahan',
              'stok' => 15,
              'min' => 8,
              'price' => 15000,
              'img' => 'https://images.unsplash.com/photo-1551163943-3f6a855d1153?w=200&q=80',
          ],
          [
              'id' => 6,
              'name' => 'Cardigan Rajut',
              'sku' => 'SKU-006',
              'cat' => 'Atasan',
              'stok' => 2,
              'min' => 5,
              'price' => 15000,
              'img' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=200&q=80',
          ],
          [
              'id' => 7,
              'name' => 'Celana Jeans Slim',
              'sku' => 'SKU-007',
              'cat' => 'Bawahan',
              'stok' => 20,
              'min' => 8,
              'price' => 15000,
              'img' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=200&q=80',
          ],
          [
              'id' => 8,
              'name' => 'Kemeja Flannel',
              'sku' => 'SKU-008',
              'cat' => 'Atasan',
              'stok' => 0,
              'min' => 5,
              'price' => 15000,
              'img' => 'https://images.unsplash.com/photo-1589310243389-96a5483213a8?w=200&q=80',
          ],
      ]);
@endphp

const PRODUCTS = @json($stokProducts);

const ICONS = {
  Atasan:   `<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>`,
  Bawahan:  `<path d="M6 2h12v4l-3 16H9L6 6V2z"/><path d="M6 6h12M12 6v4"/>`,
  Aksesori: `<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>`,
};

let currentFilter = 'semua';
let currentSearch = '';

function getStatus(p) {
  if (p.stok === 0) return 'habis';
  if (p.stok < p.min) return 'menipis';
  return 'aman';
}

function filterProducts() {
  currentSearch = document.getElementById('searchInput').value.toLowerCase();
  render();
}

function setFilter(el) {
  currentFilter = el.dataset.filter;
  document.querySelectorAll('.filter-chip').forEach(c => {
    c.classList.remove('bg-gray-900', 'border-gray-900', 'text-white');
    c.classList.add('bg-white', 'border-border', 'text-muted');
  });
  el.classList.remove('bg-white', 'border-border', 'text-muted');
  el.classList.add('bg-gray-900', 'border-gray-900', 'text-white');
  render();
}

function render() {
  let filtered = PRODUCTS.filter(p => {
    const matchSearch =
      p.name.toLowerCase().includes(currentSearch) ||
      p.sku.toLowerCase().includes(currentSearch);

    const matchFilter =
      currentFilter === 'semua' || getStatus(p) === currentFilter;

    return matchSearch && matchFilter;
  });

  const list = document.getElementById('productList');
  const empty = document.getElementById('emptyState');

  if (!filtered.length) {
    list.innerHTML = '';
    empty.classList.remove('hidden');
    return;
  }

  empty.classList.add('hidden');

  list.innerHTML = `
    <div class="grid grid-cols-2 gap-2">
      ${filtered.map((p, i) => {
        const status = getStatus(p);
        const badgeClass =
          status === 'aman'
            ? 'badge-aman'
            : status === 'menipis'
            ? 'badge-menipis'
            : 'badge-habis';

        return `
          <div class="bg-white rounded-xl border border-border shadow-sm overflow-hidden card-hover"
               style="animation:fadeUp 0.35s ease ${i * 0.05}s both">

            <!-- GAMBAR: rasio 1:1 (persegi) agar lebih kompak di HP -->
            <div class="relative w-full overflow-hidden" style="aspect-ratio:1/1">
              <img src="${p.img}" alt="${p.name}"
                   class="w-full h-full object-cover">
              <span class="absolute top-1.5 right-1.5 text-[9px] font-bold px-1.5 py-0.5 rounded-full ${badgeClass}">
                ${status === 'aman' ? 'Aman' : status === 'menipis' ? 'Menipis' : 'Habis'}
              </span>
            </div>

            <!-- KONTEN CARD lebih ringkas -->
            <div class="p-2">
              <p class="text-[11px] font-semibold text-gray-900 leading-tight line-clamp-2">
                ${p.name}
              </p>

              <p class="text-[9px] text-muted mt-0.5">
                ${p.sku}
              </p>

              <div class="flex items-center justify-between mt-1">
                <span class="text-[10px] font-semibold text-gray-900">
                  ${p.stok} pcs
                </span>

                <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-terra-xs text-terra font-medium">
                  ${p.cat}
                </span>
              </div>

              <p class="text-[10px] text-muted mt-1">
                Rp.${p.price
                  ? Number(p.price).toLocaleString('id-ID')
                  : '0'}
              </p>
            </div>
          </div>
        `;
      }).join('')}
    </div>
  `;
}

render();
window.scrollTo(0, 0);
</script>
</body>
</html>