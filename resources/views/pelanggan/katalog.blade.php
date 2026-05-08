<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Katalog – Kashy Store</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          terra:   '#C8966C',
          'terra-l':  '#E5B18A',
          'terra-ll': '#F0D7C7',
          'terra-xs': '#FAF2EC',
          muted:   '#9C8B7E',
          border:  '#EAE0D6',
          bg:      '#F5F0EB',
        },
        fontFamily: {
          display: ['Cormorant Garamond', 'serif'],
          body:    ['DM Sans', 'sans-serif'],
        },
        keyframes: {
          fadeUp: {
            '0%':   { opacity: '0', transform: 'translateY(14px)' },
            '100%': { opacity: '1', transform: 'translateY(0)' },
          },
          shimmer: {
            '0%':   { backgroundPosition: '-400px 0' },
            '100%': { backgroundPosition: '400px 0' },
          }
        },
        animation: {
          'fade-up': 'fadeUp 0.35s ease both',
        }
      }
    }
  }
</script>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'DM Sans', sans-serif; }
  .hide-scroll::-webkit-scrollbar { display: none; }
  .hide-scroll { scrollbar-width: none; -webkit-overflow-scrolling: touch; }
  .card-anim { animation: fadeUp 0.35s ease both; }
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .cat-btn.active .cat-icon { border-color: #C8966C; background: #F0D7C7; transform: translateY(-3px); box-shadow: 0 6px 18px rgba(200,150,108,.28); }
  .cat-btn.active .cat-icon svg { stroke: #C8966C; }
  .cat-btn.active .cat-label { color: #C8966C; font-weight: 600; }
  .wish-btn.on { background: #C8966C !important; }
  .wish-btn.on svg path { stroke: white !important; fill: white !important; }
  .card.selected { border-color: #C8966C !important; box-shadow: 0 0 0 4px rgba(200,150,108,.18), 0 12px 40px rgba(28,28,28,.18) !important; transform: translateY(-4px) !important; }
  .card.selected::after { content:''; position:absolute; inset:0; background:linear-gradient(180deg,rgba(200,150,108,.06) 0%,transparent 50%); pointer-events:none; }
  .bn-item.active .bn-icon { background: #F0D7C7; }
  .bn-item.active .bn-icon svg { stroke: #C8966C; }
  .bn-item.active .bn-label { color: #C8966C; font-weight: 600; }
</style>
</head>
<body class="bg-bg min-h-screen flex flex-col">

<!-- ════ TOPBAR ════ -->
<nav class="sticky top-0 z-50 bg-gray-900 px-5 py-3.5 flex items-center justify-center shadow-md">
  <a href="#" class="absolute left-5 flex items-center gap-1.5 text-xs text-gray-500 hover:text-white transition-colors no-underline">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
    Kembali
  </a>
  <span class="font-display text-xl font-bold text-white tracking-widest italic">Kashy</span>
</nav>

<!-- ════ HERO ════ -->
<div class="mx-4 mt-3 mb-5 rounded-2xl overflow-hidden h-56 relative bg-amber-900 shadow-lg">
  <div class="absolute inset-0 bg-cover bg-center opacity-60" style="background-image:url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=900&q=80')"></div>
  <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
  <div class="relative z-10 flex flex-col justify-end h-full p-5">
    <span class="inline-block bg-terra text-white text-xs font-bold tracking-wider px-3 py-1 rounded-full mb-2 w-fit uppercase">New Arrival!!</span>
    <div class="text-xl font-semibold text-white mb-1">Promo koleksi kemeja terbaru</div>
    <div class="text-sm text-white/70">Buruan dibeli sebelum habis!!!</div>
  </div>
</div>

<!-- ════ KATEGORI ════ -->
<div class="flex items-center justify-between px-5 pb-3">
  <span class="text-base font-bold text-gray-900">Kategori</span>
</div>

<div class="overflow-x-auto hide-scroll pt-2 pb-6">
  <div class="inline-flex gap-10 px-5 min-w-full justify-center items-start">

    <button class="cat-btn active flex flex-col items-center gap-2 bg-transparent border-0 p-0 cursor-pointer font-body" data-cat="semua" onclick="setCat(this)">
      <div class="cat-icon w-15 h-15 rounded-full bg-white border-2 border-border flex items-center justify-center transition-all duration-200 hover:border-terra hover:bg-terra-xs hover:-translate-y-1 hover:shadow-lg" style="width:60px;height:60px;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
          <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
        </svg>
      </div>
      <span class="cat-label text-xs font-medium text-muted whitespace-nowrap">Beranda</span>
    </button>

    <button class="cat-btn flex flex-col items-center gap-2 bg-transparent border-0 p-0 cursor-pointer font-body" data-cat="kemeja" onclick="setCat(this)">
      <div class="cat-icon w-15 h-15 rounded-full bg-white border-2 border-border flex items-center justify-center transition-all duration-200 hover:border-terra hover:bg-terra-xs hover:-translate-y-1 hover:shadow-lg" style="width:60px;height:60px;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>
        </svg>
      </div>
      <span class="cat-label text-xs font-medium text-muted whitespace-nowrap">Kemeja</span>
    </button>

    <button class="cat-btn flex flex-col items-center gap-2 bg-transparent border-0 p-0 cursor-pointer font-body" data-cat="rok" onclick="setCat(this)">
      <div class="cat-icon w-15 h-15 rounded-full bg-white border-2 border-border flex items-center justify-center transition-all duration-200 hover:border-terra hover:bg-terra-xs hover:-translate-y-1 hover:shadow-lg" style="width:60px;height:60px;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <rect x="7" y="2" width="10" height="4" rx="1"/>
          <path d="M7 6 4 20h16L17 6"/>
        </svg>
      </div>
      <span class="cat-label text-xs font-medium text-muted whitespace-nowrap">Rok</span>
    </button>

    <button class="cat-btn flex flex-col items-center gap-2 bg-transparent border-0 p-0 cursor-pointer font-body" data-cat="cardigan" onclick="setCat(this)">
      <div class="cat-icon w-15 h-15 rounded-full bg-white border-2 border-border flex items-center justify-center transition-all duration-200 hover:border-terra hover:bg-terra-xs hover:-translate-y-1 hover:shadow-lg" style="width:60px;height:60px;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 6.5 7.5 3h3L12 5l1.5-2h3L21 6.5l-3 2.5v11a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V9L3 6.5z"/>
          <path d="M12 5v15"/>
        </svg>
      </div>
      <span class="cat-label text-xs font-medium text-muted whitespace-nowrap">Cardigan</span>
    </button>

    <button class="cat-btn flex flex-col items-center gap-2 bg-transparent border-0 p-0 cursor-pointer font-body" data-cat="celana" onclick="setCat(this)">
      <div class="cat-icon w-15 h-15 rounded-full bg-white border-2 border-border flex items-center justify-center transition-all duration-200 hover:border-terra hover:bg-terra-xs hover:-translate-y-1 hover:shadow-lg" style="width:60px;height:60px;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M6 2h12v4l-3 16H9L6 6V2z"/>
          <path d="M6 6h12"/><path d="M12 6v4"/>
        </svg>
      </div>
      <span class="cat-label text-xs font-medium text-muted whitespace-nowrap">Celana</span>
    </button>

    <button class="cat-btn flex flex-col items-center gap-2 bg-transparent border-0 p-0 cursor-pointer font-body" data-cat="jaket" onclick="setCat(this)">
      <div class="cat-icon w-15 h-15 rounded-full bg-white border-2 border-border flex items-center justify-center transition-all duration-200 hover:border-terra hover:bg-terra-xs hover:-translate-y-1 hover:shadow-lg" style="width:60px;height:60px;">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 7 7 3h2.5L12 6l2.5-3H17l4 4-2.5 2L17 7v13a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V7L4.5 9 3 7z"/>
          <path d="M12 6v14"/>
        </svg>
      </div>
      <span class="cat-label text-xs font-medium text-muted whitespace-nowrap">Jaket</span>
    </button>

  </div>
</div>

<!-- ════ PRODUK TERBARU ════ -->
<div class="flex items-center justify-between px-5 pt-1 pb-3">
  <span class="text-base font-bold text-gray-900">Produk Terbaru</span>
</div>

<div class="grid grid-cols-2 gap-3 px-3.5 pb-5 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 md:px-6 lg:px-8" id="productGrid"></div>

<!-- ════ DISKON SPESIAL ════ -->
<div class="h-1.5 bg-bg"></div>
<div class="flex items-center justify-between px-5 pt-3 pb-3">
  <span class="text-base font-bold text-gray-900">Diskon Spesial</span>
  <span class="text-xs font-medium text-terra cursor-pointer hover:underline">Lihat semua</span>
</div>

<div class="overflow-x-auto hide-scroll pb-6">
  <div class="inline-flex gap-3 px-3.5 min-w-full" id="diskonRow">

    <!-- Diskon cards -->
    <div class="flex-shrink-0 w-36 bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 border-transparent hover:-translate-y-1 hover:shadow-md hover:border-terra-l transition-all duration-200">
      <div class="w-full h-28 flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#ede0d4,#d9c5b0)">
        <svg class="w-12 h-12 transition-transform duration-300 hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.55)" stroke-width="1.5" stroke-linecap="round"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/></svg>
        <span class="absolute bottom-1.5 left-1.5 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">-50%</span>
      </div>
      <div class="p-2.5 pb-3">
        <div class="text-xs font-medium text-gray-900 mb-1 leading-tight">Kaos Cream Pita</div>
        <div class="flex items-baseline gap-1.5 flex-wrap">
          <span class="text-sm font-bold text-terra">Rp 27.000</span>
          <span class="text-xs text-muted line-through">Rp 37.000</span>
        </div>
      </div>
    </div>

    <div class="flex-shrink-0 w-36 bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 border-transparent hover:-translate-y-1 hover:shadow-md hover:border-terra-l transition-all duration-200">
      <div class="w-full h-28 flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#e0d4c9,#ccbfb0)">
        <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.55)" stroke-width="1.5" stroke-linecap="round"><path d="M3 6.5 7.5 3h3L12 5l1.5-2h3L21 6.5l-3 2.5v11a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V9L3 6.5z"/><path d="M12 5v15"/></svg>
        <span class="absolute bottom-1.5 left-1.5 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">-40%</span>
      </div>
      <div class="p-2.5 pb-3">
        <div class="text-xs font-medium text-gray-900 mb-1 leading-tight">Cardigan Krem Rajut</div>
        <div class="flex items-baseline gap-1.5 flex-wrap">
          <span class="text-sm font-bold text-terra">Rp 42.000</span>
          <span class="text-xs text-muted line-through">Rp 70.000</span>
        </div>
      </div>
    </div>

    <div class="flex-shrink-0 w-36 bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 border-transparent hover:-translate-y-1 hover:shadow-md hover:border-terra-l transition-all duration-200">
      <div class="w-full h-28 flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#d4cbbf,#c2b8a9)">
        <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.55)" stroke-width="1.5" stroke-linecap="round"><path d="M6 2h12v4l-3 16H9L6 6V2z"/><path d="M6 6h12M12 6v4"/></svg>
        <span class="absolute bottom-1.5 left-1.5 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">-30%</span>
      </div>
      <div class="p-2.5 pb-3">
        <div class="text-xs font-medium text-gray-900 mb-1 leading-tight">Kulot Cokelat Linen</div>
        <div class="flex items-baseline gap-1.5 flex-wrap">
          <span class="text-sm font-bold text-terra">Rp 53.000</span>
          <span class="text-xs text-muted line-through">Rp 76.000</span>
        </div>
      </div>
    </div>

    <div class="flex-shrink-0 w-36 bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 border-transparent hover:-translate-y-1 hover:shadow-md hover:border-terra-l transition-all duration-200">
      <div class="w-full h-28 flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#e8ddd4,#d4c8bc)">
        <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.55)" stroke-width="1.5" stroke-linecap="round"><rect x="7" y="2" width="10" height="4" rx="1"/><path d="M7 6 4 20h16L17 6"/></svg>
        <span class="absolute bottom-1.5 left-1.5 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">-25%</span>
      </div>
      <div class="p-2.5 pb-3">
        <div class="text-xs font-medium text-gray-900 mb-1 leading-tight">Rok Putih Layer</div>
        <div class="flex items-baseline gap-1.5 flex-wrap">
          <span class="text-sm font-bold text-terra">Rp 53.000</span>
          <span class="text-xs text-muted line-through">Rp 70.000</span>
        </div>
      </div>
    </div>

    <div class="flex-shrink-0 w-36 bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 border-transparent hover:-translate-y-1 hover:shadow-md hover:border-terra-l transition-all duration-200">
      <div class="w-full h-28 flex items-center justify-center relative overflow-hidden" style="background:linear-gradient(135deg,#c9d0d8,#b8c2cc)">
        <svg class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.55)" stroke-width="1.5" stroke-linecap="round"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/></svg>
        <span class="absolute bottom-1.5 left-1.5 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">-20%</span>
      </div>
      <div class="p-2.5 pb-3">
        <div class="text-xs font-medium text-gray-900 mb-1 leading-tight">Kemeja Flannel Biru</div>
        <div class="flex items-baseline gap-1.5 flex-wrap">
          <span class="text-sm font-bold text-terra">Rp 68.000</span>
          <span class="text-xs text-muted line-through">Rp 85.000</span>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- ════ BOTTOM NAV ════ -->
<nav class="sticky bottom-0 z-50 bg-white border-t border-border flex justify-around py-2.5 pb-4 shadow-[0_-2px_12px_rgba(28,28,28,0.06)]">

  <button class="bn-item active flex flex-col items-center gap-1 flex-1 border-0 bg-transparent cursor-pointer font-body p-0"
          onclick="window.location.href='{{ route('katalog') }}'">
    <div class="bn-icon w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 hover:bg-terra-xs hover:-translate-y-0.5">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </div>
    <span class="bn-label text-xs font-medium text-muted transition-colors">Beranda</span>
  </button>

  <button class="bn-item flex flex-col items-center gap-1 flex-1 border-0 bg-transparent cursor-pointer font-body p-0"
          onclick="window.location.href='{{ route('daftar-produk') }}'">
    <div class="bn-icon w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 hover:bg-terra-xs hover:-translate-y-0.5">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </div>
    <span class="bn-label text-xs font-medium text-muted transition-colors">Daftar Produk</span>
  </button>

  <button class="bn-item flex flex-col items-center gap-1 flex-1 border-0 bg-transparent cursor-pointer font-body p-0" onclick="setNav(this)">
    <div class="bn-icon w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 hover:bg-terra-xs hover:-translate-y-0.5">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
      </svg>
    </div>
    <span class="bn-label text-xs font-medium text-muted transition-colors">Wishlist</span>
  </button>

  <button class="bn-item flex flex-col items-center gap-1 flex-1 border-0 bg-transparent cursor-pointer font-body p-0" onclick="setNav(this)">
    <div class="bn-icon w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 hover:bg-terra-xs hover:-translate-y-0.5">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.15 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.06 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16.92z"/>
      </svg>
    </div>
    <span class="bn-label text-xs font-medium text-muted transition-colors">Kontak</span>
  </button>

</nav>

<script>
const PRODUCTS = [
  { id:1, name:'Kulot Modis',      cat:'celana',   price:'Rp 76.000', old:'',          badge:'new',     bg:'linear-gradient(135deg,#e8ddd4,#d4c5b4)' },
  { id:2, name:'Kemeja Cokelat',   cat:'kemeja',   price:'Rp 52.000', old:'',          badge:'',        bg:'linear-gradient(135deg,#d4c9bc,#c4b8a8)' },
  { id:3, name:'Rok Putih Layer',  cat:'rok',      price:'Rp 70.000', old:'',          badge:'new',     bg:'linear-gradient(135deg,#ede0d4,#ddd0c4)' },
  { id:4, name:'Cardigan Rajut',   cat:'cardigan', price:'Rp 47.000', old:'',          badge:'',        bg:'linear-gradient(135deg,#e0d4c9,#d0c4b9)' },
  { id:5, name:'Kemeja Flannel',   cat:'kemeja',   price:'Rp 85.000', old:'',          badge:'branded', bg:'linear-gradient(135deg,#d9cfc4,#c9bfb4)' },
  { id:6, name:'Celana Cargo',     cat:'celana',   price:'Rp 120.000',old:'',          badge:'branded', bg:'linear-gradient(135deg,#cfc4b9,#bfb4a9)' },
  { id:7, name:'Jaket Denim',      cat:'jaket',    price:'Rp 150.000',old:'',          badge:'branded', bg:'linear-gradient(135deg,#c9cfe0,#b9c2d4)' },
  { id:8, name:'Rok Mini Plaid',   cat:'rok',      price:'Rp 95.000', old:'Rp 120.000',badge:'sale',    bg:'linear-gradient(135deg,#e0c9c9,#d0b9b9)' },
];

const ICONS = {
  kemeja:   `<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>`,
  celana:   `<path d="M6 2h12v4l-3 16H9L6 6V2z"/><path d="M6 6h12M12 6v4"/>`,
  rok:      `<rect x="7" y="2" width="10" height="4" rx="1"/><path d="M7 6 4 20h16L17 6"/>`,
  cardigan: `<path d="M3 6.5 7.5 3h3L12 5l1.5-2h3L21 6.5l-3 2.5v11a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V9L3 6.5z"/><path d="M12 5v15"/>`,
  jaket:    `<path d="M3 7 7 3h2.5L12 6l2.5-3H17l4 4-2.5 2L17 7v13a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V7L4.5 9 3 7z"/><path d="M12 6v14"/>`,
  default:  `<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>`,
};
const BADGE_LABELS = { new:'New', branded:'Branded', sale:'Sale' };
const BADGE_CLASSES = { new:'bg-gray-900 text-white', branded:'bg-terra text-white', sale:'bg-red-500 text-white' };

let currentCat = 'semua';
let wishlist   = new Set();
let selected   = null;

function render() {
  const grid = document.getElementById('productGrid');
  const filtered = PRODUCTS.filter(p => currentCat === 'semua' || p.cat === currentCat);
  if (!filtered.length) {
    grid.innerHTML = `<div class="col-span-full text-center py-12 text-muted">
      <svg class="w-12 h-12 mx-auto mb-3 stroke-border" viewBox="0 0 24 24" fill="none" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <p class="text-sm">Produk tidak ditemukan</p>
    </div>`;
    return;
  }
  grid.innerHTML = filtered.map((p, i) => {
    const icon = ICONS[p.cat] || ICONS.default;
    const badgeHtml = p.badge
      ? `<span class="absolute top-2 right-2 z-20 text-xs font-bold px-2 py-0.5 rounded-full tracking-wide ${BADGE_CLASSES[p.badge]||'bg-gray-500 text-white'}">${BADGE_LABELS[p.badge]||p.badge}</span>` : '';
    const isW = wishlist.has(p.id);
    const isS = selected === p.id;
    return `
    <div class="card relative bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-terra-l ${isS ? 'selected' : 'border-transparent'}" style="animation:fadeUp .35s ease ${i*.06}s both" onclick="selectCard(${p.id})">
      <button class="wish-btn absolute top-2 left-2 z-30 w-8 h-8 rounded-full bg-white/90 border-0 flex items-center justify-center shadow cursor-pointer transition-all duration-200 hover:scale-110 ${isW ? 'on' : ''}" onclick="toggleWish(event,${p.id})">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round">
          <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" stroke="#9C8B7E" fill="none"/>
        </svg>
      </button>
      ${badgeHtml}
      <div class="relative w-full overflow-hidden" style="aspect-ratio:3/3.6">
        <div class="absolute inset-0 transition-transform duration-500 group-hover:scale-105" style="background:${p.bg}"></div>
        <div class="relative z-10 w-full h-full flex items-center justify-center">
          <svg class="transition-transform duration-300 hover:scale-110" style="width:52%;height:52%" viewBox="0 0 24 24" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">${icon}</svg>
        </div>
      </div>
      <div class="p-2.5 pb-3">
        <div class="text-xs font-medium text-gray-900 mb-1.5 leading-tight">${p.name}</div>
        <div class="flex items-baseline gap-1.5">
          <span class="text-sm font-bold text-gray-900">${p.price}</span>
          ${p.old ? `<span class="text-xs text-muted line-through">${p.old}</span>` : ''}
        </div>
      </div>
    </div>`;
  }).join('');
}

function selectCard(id) {
  selected = selected === id ? null : id;
  render();
}
function toggleWish(e, id) {
  e.stopPropagation();
  wishlist.has(id) ? wishlist.delete(id) : wishlist.add(id);
  render();
}
function setCat(el) {
  currentCat = el.dataset.cat;
  selected   = null;
  document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  render();
}
function setNav(el) {
  document.querySelectorAll('.bn-item').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
}
render();
</script>
</body>
</html>