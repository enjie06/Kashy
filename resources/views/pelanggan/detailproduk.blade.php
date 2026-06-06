<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Produk | SND STORE</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          terra: '#C8966C', 'terra-l': '#E5B18A', 'terra-ll': '#F0D7C7',
          'terra-xs': '#FAF2EC', muted: '#9C8B7E', border: '#EAE0D6', bg: '#F5F0EB',
        },
        fontFamily: { display: ['Poppins','serif'], body: ['Poppins','sans-serif'] },
      }
    }
  }
</script>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Poppins', sans-serif; }
  .fade-up { animation: fadeUp 0.4s ease; }
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .bn-item.active .bn-icon { background: #F0D7C7; }
  .bn-item.active .bn-icon svg { stroke: #C8966C; }
  .bn-item.active .bn-label { color: #C8966C; font-weight: 600; }

  #hamburger-menu {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: #1f2937;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    overflow: hidden;
    min-width: 170px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    z-index: 200;
  }
  #hamburger-menu.open { display: block; animation: fadeUp .2s ease; }
  .hb-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    color: #d1d5db;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    border-bottom: 1px solid rgba(255,255,255,0.06);
  }
  .hb-item:last-child { border-bottom: none; }
  .hb-item:hover { background: rgba(200,150,108,0.15); color: #C8966C; }
</style>
</head>
<body class="bg-bg min-h-screen flex flex-col">

<!-- ════ TOPBAR ════ -->
<nav class="sticky top-0 z-50 bg-gray-900 px-4 py-3 flex items-center justify-between shadow-md relative">
  <a href="javascript:history.back()" class="w-11 h-11 flex items-center justify-center rounded-xl text-white bg-white/10 hover:bg-white/20 active:scale-95 transition-all">
    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.7" viewBox="0 0 24 24">
      <path d="M19 12H5m7-7-7 7 7 7"/>
    </svg>
  </a>
  <div class="absolute left-1/2 -translate-x-1/2 text-center">
    <span class="text-lg sm:text-xl font-bold text-white">SND STORE</span>
  </div>
  <!-- <div class="relative">
    <button id="hamburger-btn" onclick="toggleMenu()" class="flex flex-col gap-1.5 p-2 rounded-lg hover:bg-white/10">
      <span class="block w-6 h-0.5 bg-white rounded"></span>
      <span class="block w-6 h-0.5 bg-white rounded"></span>
      <span class="block w-6 h-0.5 bg-white rounded"></span>
    </button>
    <div id="hamburger-menu">
      <a href="{{ route('katalog') }}" class="hb-item">Beranda</a>
      <a href="{{ route('daftar-produk') }}" class="hb-item">Daftar Produk</a>
    </div>
  </div> -->
</nav>

<!-- KONTEN UTAMA -->
<div class="flex-1 pb-6">
  <div class="max-w-4xl mx-auto px-4 md:px-8 py-5">
    <div class="flex flex-col lg:flex-row gap-8 items-start" id="detailContainer">
      
      <!-- GAMBAR -->
      <div class="w-full lg:w-[45%] fade-up">
        <div class="relative w-full max-w-[380px] mx-auto rounded-3xl overflow-hidden shadow-md" style="aspect-ratio:1/1; background: linear-gradient(135deg,#e8ddd4,#d4c5b4)">
          @if($product->gambar)
            <img src="{{ $product->gambar && str_starts_with($product->gambar, 'products/') 
    ? asset('storage/' . $product->gambar) 
    : asset('images/products/' . basename($product->gambar ?? '')) }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x400?text=No+Image'">
          @else
            <div class="w-full h-full flex items-center justify-center">
              <svg width="140" height="140" viewBox="0 0 24 24" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <path d="M16 2v4M8 2v4M3 10h18"/>
              </svg>
            </div>
          @endif
          @if($product->is_discount)
            <span class="absolute top-4 right-4 z-10 text-xs font-bold px-2.5 py-1 rounded-full bg-red-500 text-white">SALE</span>
          @endif
        </div>
      </div>

      <!-- INFO -->
      <div class="w-full lg:flex-1 fade-up">
        <div class="flex items-center gap-4 mb-4 flex-wrap">
          <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $product->nama_produk }}</h1>
          @if($product->stok > 0)
            <div class="flex items-center gap-2">
              <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
              </div>
              <span class="text-sm font-medium text-green-700">Stok Tersedia</span>
              <span class="text-xs font-bold bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $product->stok }} pcs</span>
            </div>
          @else
            <div class="flex items-center gap-2">
              <div class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
              </div>
              <span class="text-sm font-medium text-red-700">Stok Habis</span>
            </div>
          @endif
        </div>

        <!-- Harga -->
        <div class="flex items-baseline gap-3 mb-4">
          <span class="text-2xl font-bold text-terra">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
        </div>

        <!-- Ukuran -->
        @if($product->ukuran)
        <div class="mb-4 p-3 bg-white rounded-xl border border-border shadow-sm">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-terra flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span class="font-semibold text-gray-800 text-sm">Ukuran:</span>
            <span class="text-gray-900 font-medium text-sm">{{ $product->ukuran }}</span>
          </div>
        </div>
        @endif

        <!-- Deskripsi -->
        <p class="text-sm leading-relaxed text-gray-600 mb-5">{{ $product->deskripsi ?: 'Tidak ada deskripsi untuk produk ini.' }}</p>

        <!-- Detail Produk -->
        <div class="mb-6">
          <h3 class="text-sm font-semibold text-gray-800 mb-2">Detail Produk</h3>
          <div class="overflow-hidden rounded-2xl border border-border bg-white shadow-sm">
            <table class="w-full">
              <tbody>
                <tr class="border-b border-border">
                  <td class="bg-terra-xs px-4 py-3 font-semibold text-gray-700 w-[42%] text-sm">Kategori</td>
                  <td class="px-4 py-3 text-gray-600 text-sm">{{ $product->category->nama_kategori ?? '-' }}</td>
                </tr>
                @if($product->brand)
                <tr class="border-b border-border">
                  <td class="bg-terra-xs px-4 py-3 font-semibold text-gray-700 w-[42%] text-sm">Brand</td>
                  <td class="px-4 py-3 text-gray-600 text-sm">{{ $product->brand }}</td>
                </tr>
                @endif
                @if($product->warna)
                <tr class="border-b border-border">
                  <td class="bg-terra-xs px-4 py-3 font-semibold text-gray-700 w-[42%] text-sm">Warna</td>
                  <td class="px-4 py-3 text-gray-600 text-sm">{{ $product->warna }}</td>
                </tr>
                @endif
                @if($product->gender)
                <tr class="border-b border-border">
                  <td class="bg-terra-xs px-4 py-3 font-semibold text-gray-700 w-[42%] text-sm">Gender</td>
                  <td class="px-4 py-3 text-gray-600 text-sm">{{ $product->gender }}</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function toggleMenu() {
    document.getElementById('hamburger-menu').classList.toggle('open');
  }
  document.addEventListener('click', function(e) {
    const btn = document.getElementById('hamburger-btn');
    const menu = document.getElementById('hamburger-menu');
    if (!btn.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.remove('open');
    }
  });
</script>
</body>
</html>