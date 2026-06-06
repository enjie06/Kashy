<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Katalog | SND STORE</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
          display: ['Poppins', 'serif'],
          body:    ['Poppins', 'sans-serif'],
        },
      }
    }
  }
</script>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Poppins', sans-serif; background-color: #F5F0EB; }
  .hide-scroll::-webkit-scrollbar { display: none; }
  .hide-scroll { scrollbar-width: none; -webkit-overflow-scrolling: touch; }
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Hamburger dropdown */
  #hamburger-menu {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 12px;
    background: #1f2937;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    overflow: hidden;
    min-width: 160px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    z-index: 200;
  }
  #hamburger-menu.open { display: block; animation: fadeUp .2s ease; }
  .hb-item {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; color: #d1d5db; font-size: 13px; font-weight: 500;
    text-decoration: none; transition: background .15s;
    border-bottom: 1px solid rgba(255,255,255,0.06);
  }
  .hb-item:last-child { border-bottom: none; }
  .hb-item:hover { background: rgba(200,150,108,0.15); color: #C8966C; }
  .hb-item.active { color: #C8966C; font-weight: 600; }

  /* Dropdown kategori */
  #categoryDropdown {
    transition: all 0.2s ease;
  }
  #categoryDropdown.show {
    display: block;
    animation: fadeUp 0.2s ease;
  }
  .dropdown-item.active {
    background-color: #FAF2EC;
    color: #C8966C;
    font-weight: 600;
  }
</style>
</head>
<body class="bg-bg min-h-screen flex flex-col">

<!-- ════ TOPBAR ════ -->
<nav class="sticky top-0 z-50 bg-gray-900 px-5 py-3.5 flex items-center justify-between shadow-md relative">
  <!-- Kiri: back -->
  <a href="{{ route('landing') }}"
   class="w-11 h-11 flex items-center justify-center rounded-xl text-white bg-white/10 hover:bg-white/20 active:scale-95 transition-all">
  <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2.7" viewBox="0 0 24 24">
    <path d="M19 12H5m7-7-7 7 7 7"/>
  </svg>
</a>

  <!-- Tengah -->
  <div class="absolute left-1/2 -translate-x-1/2 text-center">
    <span class="font-sans text-xl font-bold text-white tracking-normal">SND STORE</span>
  </div>

  <!-- Kanan: hamburger -->
  <div class="relative">
    <button id="hamburger-btn" onclick="toggleMenu()" class="flex flex-col gap-1.5 p-1.5 rounded-lg hover:bg-white/10 transition-colors">
      <span class="block w-5 h-0.5 bg-white rounded transition-all" id="hb-line1"></span>
      <span class="block w-5 h-0.5 bg-white rounded transition-all" id="hb-line2"></span>
      <span class="block w-5 h-0.5 bg-white rounded transition-all" id="hb-line3"></span>
    </button>

    <!-- Dropdown menu -->
    <div id="hamburger-menu">
      <a href="{{ route('katalog') }}" class="hb-item active">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        Beranda
      </a>
      <a href="{{ route('daftar-produk') }}" class="hb-item">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        Daftar Produk
      </a>
    </div>
  </div>
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

<!-- ════ PRODUK TERBARU ════ -->
<div class="flex items-center justify-between px-5 pt-1 pb-3">
  <span class="text-base font-bold text-gray-900">Produk Terbaru</span>
</div>
<div class="grid grid-cols-2 gap-3 px-3.5 pb-5 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5" id="productGrid"></div>

<!-- ════ DISKON SPESIAL ════ -->
<div class="h-1.5 bg-bg"></div>
<div class="flex items-center justify-between px-5 pt-3 pb-3">
  <span class="text-base font-bold text-gray-900">Diskon Spesial</span>
  <span class="text-xs font-medium text-terra cursor-pointer hover:underline">Lihat semua</span>
</div>
<div class="overflow-x-auto hide-scroll pb-6 pt-3">
  <div class="inline-flex gap-3 px-3.5 min-w-full" id="diskonRow"></div>
</div>

<!-- ════ FOOTER ════ -->
<div class="mt-5 bg-gray-900 px-5 pt-8 pb-10">
  <div class="mb-5">
    <span class="font-sans text-3xl font-bold text-white tracking-normal">Hubungi Kami</span>
    <div class="w-8 h-0.5 bg-terra mt-1 mb-2"></div>
  </div>
  <div class="flex flex-col sm:flex-row gap-3 mb-5">
    <a href="https://wa.me/6285261246660" target="_blank" class="flex items-center gap-3 bg-white/5 hover:bg-terra/20 border border-white/10 hover:border-terra/40 rounded-xl p-4 transition-all duration-200 group flex-1">
      <div class="w-10 h-10 bg-terra rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
      </div>
      <div class="min-w-0">
        <p class="text-xs text-gray-500 uppercase tracking-widest mb-0.5">WhatsApp</p>
        <p class="text-terra text-sm font-bold group-hover:text-terra-l transition-colors">0852-6124-6660</p>
      </div>
      <svg class="w-4 h-4 text-gray-600 group-hover:text-terra transition-colors ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
    </a>
    <a href="https://www.instagram.com/snd_store___?igsh=cmloaGdna3ptbnp5" target="_blank" class="flex items-center gap-3 bg-white/5 hover:bg-terra/20 border border-white/10 hover:border-terra/40 rounded-xl p-4 transition-all duration-200 group flex-1">
      <div class="w-10 h-10 bg-terra rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
        </svg>
      </div>
      <div class="min-w-0">
        <p class="text-xs text-gray-500 uppercase tracking-widest mb-0.5">Instagram</p>
        <p class="text-white text-sm font-medium group-hover:text-terra transition-colors">@snd_store___</p>
      </div>
      <svg class="w-4 h-4 text-gray-600 group-hover:text-terra transition-colors ml-auto flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
    </a>
  </div>
  <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden mb-5">
    <div class="px-4 py-3 flex items-center gap-3 border-b border-white/10">
      <div class="w-8 h-8 bg-terra/20 rounded-lg flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-terra" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
      </div>
      <div>
        <p class="text-xs text-gray-500 uppercase tracking-widest">Lokasi Toko</p>
        <p class="text-white text-sm font-medium">Jl. Bromo No.171 C, Binjai, Kec. Medan Denai,</p>
        <p class="text-white text-sm font-medium">Kota Medan, Sumatera Utara 20227</p>
      </div>
      <a href="https://maps.app.goo.gl/AwT71cgeBkxNmFse9" target="_blank" class="ml-auto text-xs text-terra hover:text-terra-l transition-colors font-medium flex-shrink-0 whitespace-nowrap">Buka Maps →</a>
    </div>
    <div class="w-full h-52">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.0!2d98.6892!3d3.5952!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMy41OTUyLDk4LjY4OTI!5e0!3m2!1sid!2sid!4v1620000000000!5m2!1sid!2sid" width="100%" height="100%" style="border:0; filter: grayscale(30%) contrast(1.1);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
  <div class="relative bg-gray-950 rounded-xl overflow-hidden px-6 py-8 mb-5">
    <div class="relative z-10 text-center">
      <div class="font-sans text-3xl font-bold text-white tracking-normal mb-3">SND STORE</div>
      <div class="w-12 h-0.5 bg-terra mx-auto mb-4"></div>
      <p class="text-gray-400 text-xs leading-relaxed max-w-xs mx-auto mb-6">
        SND Store adalah Toko Thrift terbaik untuk Anda. Kami menghadirkan beragam pilihan kategori pakaian yang selalu up to date dengan tren fashion masa kini.
      </p>
      <p class="text-gray-600 text-xs">© 2026 SND Store. All rights reserved.</p>
    </div>
  </div>
</div>

<script>
// ── Hamburger toggle ──
function toggleMenu() {
  const menu = document.getElementById('hamburger-menu');
  if (menu) menu.classList.toggle('open');
}
document.addEventListener('click', function(e) {
  const btn = document.getElementById('hamburger-btn');
  const menu = document.getElementById('hamburger-menu');
  if (btn && menu && !btn.contains(e.target) && !menu.contains(e.target)) menu.classList.remove('open');
});

// ── Filter Kategori Dropdown ──
function toggleCategoryDropdown() {
  const dropdown = document.getElementById('categoryDropdown');
  if (dropdown) dropdown.classList.toggle('show');
}
function closeCategoryDropdown() {
  const dropdown = document.getElementById('categoryDropdown');
  if (dropdown) dropdown.classList.remove('show');
}
document.addEventListener('click', function(e) {
  const filterBtn = document.getElementById('filterBtn');
  const dropdown = document.getElementById('categoryDropdown');
  if (filterBtn && dropdown && !filterBtn.contains(e.target) && !dropdown.contains(e.target)) dropdown.classList.remove('show');
});

function filterByCategory(categoryId, categoryName) {
  currentCategory = categoryId;
  const selectedText = document.getElementById('selectedCategoryText');
  if (selectedText) selectedText.textContent = categoryName;
  closeCategoryDropdown();
  render();
  renderDiskon();
}

// ── Fungsi Search ──
function setupSearch() {
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      currentSearch = e.target.value;
      render();
      renderDiskon();
    });
  }
}

function escapeHtml(str) {
  if (!str) return '';
  return str.replace(/[&<>]/g, function(m) {
    if (m === '&') return '&amp;';
    if (m === '<') return '&lt;';
    if (m === '>') return '&gt;';
    return m;
  });
}

// ========== DATA PRODUK & KATEGORI DARI DATABASE ==========
const productsFromDB = @json($products);
const categoriesFromDB = @json($categories);

console.log('Jumlah produk dari DB:', productsFromDB.length);

// Warna background untuk card
const BG_COLORS = [
  'linear-gradient(135deg,#e8ddd4,#d4c5b4)',
  'linear-gradient(135deg,#d4c9bc,#c4b8a8)',
  'linear-gradient(135deg,#ede0d4,#ddd0c4)',
  'linear-gradient(135deg,#e0d4c9,#d0c4b9)',
  'linear-gradient(135deg,#d9cfc4,#c9bfb4)',
  'linear-gradient(135deg,#cfc4b9,#bfb4a9)',
  'linear-gradient(135deg,#c9cfe0,#b9c2d4)',
  'linear-gradient(135deg,#e0c9c9,#d0b9b9)'
];

let currentCategory = 'all';
let currentSearch = '';

// ── Render produk (dengan filter) ──
function render() {
  const grid = document.getElementById('productGrid');
  if (!grid) return;
  
  let filteredProducts = [...productsFromDB];
  
  if (currentCategory !== 'all') {
    filteredProducts = filteredProducts.filter(p => p.category_id === currentCategory);
  }
  
  if (currentSearch.trim() !== '') {
    const searchLower = currentSearch.toLowerCase();
    filteredProducts = filteredProducts.filter(p => 
      (p.nama_produk && p.nama_produk.toLowerCase().includes(searchLower)) ||
      (p.brand && p.brand.toLowerCase().includes(searchLower))
    );
  }
  
  if (filteredProducts.length === 0) {
    grid.innerHTML = '<div class="col-span-full text-center py-12 text-gray-500">Tidak ada produk yang sesuai.</div>';
    return;
  }
  
  grid.innerHTML = filteredProducts.map((p, i) => {
    const formattedPrice = 'Rp ' + new Intl.NumberFormat('id-ID').format(p.harga);
    const imageUrl = p.gambar ? '/images/products/' + p.gambar.split('/').pop() : 'https://placehold.co/400x400?text=No+Image';
    const bgColor = BG_COLORS[i % BG_COLORS.length];
    
    let badge = '';
    if (p.stok <= 0) {
      badge = '<span class="absolute top-2 right-2 z-20 text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-500 text-white">Habis</span>';
    } else if (p.is_discount) {
      badge = '<span class="absolute top-2 right-2 z-20 text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-500 text-white">Sale</span>';
    }
    
    return `
    <div class="relative bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 border-transparent transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-terra-l" style="animation:fadeUp .35s ease ${i*0.05}s both" onclick="window.location.href='/detail-produk/${p.id}'">
      ${badge}
      <div class="relative w-full aspect-square" style="background:${bgColor}">
        <img src="${imageUrl}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x400?text=No+Image'">
      </div>
      <div class="p-2.5 pb-3">
        <div class="text-xs font-semibold text-gray-900 mb-1 leading-tight">${escapeHtml(p.nama_produk)}</div>
        <div class="flex items-baseline gap-1.5 flex-wrap">
          <span class="text-sm font-bold text-terra">${formattedPrice}</span>
        </div>
      </div>
    </div>`;
  }).join('');
}

// ── Render produk diskon ──
function renderDiskon() {
  const row = document.getElementById('diskonRow');
  if (!row) return;
  
  let filteredProducts = [...productsFromDB];
  if (currentCategory !== 'all') {
    filteredProducts = filteredProducts.filter(p => p.category_id === currentCategory);
  }
  if (currentSearch.trim() !== '') {
    const searchLower = currentSearch.toLowerCase();
    filteredProducts = filteredProducts.filter(p => 
      (p.nama_produk && p.nama_produk.toLowerCase().includes(searchLower)) ||
      (p.brand && p.brand.toLowerCase().includes(searchLower))
    );
  }
  
  const discountedProducts = filteredProducts.filter(p => p.is_discount === 1 && p.stok > 0);
  
  if (discountedProducts.length === 0) {
    row.innerHTML = '<div class="text-center text-gray-500 py-4 w-full">Tidak ada produk diskon yang sesuai.</div>';
    return;
  }
  
  row.innerHTML = discountedProducts.map((p, idx) => {
    const formattedPrice = 'Rp ' + new Intl.NumberFormat('id-ID').format(p.harga);
    const imageUrl = p.gambar ? '/images/products/' + p.gambar.split('/').pop() : 'https://placehold.co/400x400?text=No+Image';
    const bgColor = BG_COLORS[idx % BG_COLORS.length];
    
    return `
    <div onclick="window.location.href='/detail-produk/${p.id}'" class="flex-shrink-0 w-36 bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 border-transparent hover:-translate-y-1 hover:shadow-md hover:border-terra-l transition-all duration-200">
      <div class="w-full h-28 flex items-center justify-center relative overflow-hidden" style="background:${bgColor}">
        <img src="${imageUrl}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x400?text=No+Image'">
        <span class="absolute bottom-1.5 left-1.5 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">Sale</span>
      </div>
      <div class="p-2.5 pb-3">
        <div class="text-xs font-medium text-gray-900 mb-1 leading-tight">${escapeHtml(p.nama_produk)}</div>
        <div class="flex items-baseline gap-1.5 flex-wrap">
          <span class="text-sm font-bold text-terra">${formattedPrice}</span>
        </div>
      </div>
    </div>`;
  }).join('');
}

// ── INITIALISASI ──
document.addEventListener('DOMContentLoaded', function() {
  setupSearch();
  render();
  renderDiskon();
});
</script>
</body>
</html>