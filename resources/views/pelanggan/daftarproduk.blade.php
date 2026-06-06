<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Produk | SND STORE</title>
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
  body { font-family: 'Poppins', sans-serif; background: #F5F0EB; }
  .hide-scroll::-webkit-scrollbar { display: none; }
  .card.selected { border-color: #C8966C !important; box-shadow: 0 0 0 4px rgba(200,150,108,.18), 0 12px 40px rgba(28,28,28,.18) !important; transform: translateY(-4px) !important; }
  .pg-btn.active { background: #1C1C1C; border-color: #1C1C1C; color: white; font-weight: 600; }

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
  
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
</head>
<body class="bg-bg min-h-screen flex flex-col">

<!-- ════ TOPBAR ════ -->
<nav class="sticky top-0 z-50 bg-gray-900 px-5 py-3.5 flex items-center justify-between shadow-md relative">
  <!-- Kiri: back -->
  <a href="{{ route('katalog') }}" class="w-11 h-11 flex items-center justify-center rounded-xl text-white bg-white/10 hover:bg-white/20 active:scale-95 transition-all">
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
      <span class="block w-5 h-0.5 bg-white rounded transition-all"></span>
      <span class="block w-5 h-0.5 bg-white rounded transition-all"></span>
      <span class="block w-5 h-0.5 bg-white rounded transition-all"></span>
    </button>

    <div id="hamburger-menu">
      <a href="{{ route('katalog') }}" class="hb-item">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
        Beranda
      </a>
      <a href="{{ route('daftar-produk') }}" class="hb-item active">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        Daftar Produk
      </a>
    </div>
  </div>
</nav>
<div class="w-full px-4 pt-5 pb-2 text-center">
  <h1 id="mainTitle" class="text-2xl sm:text-3xl font-extrabold text-gray-900">Daftar Produk</h1>
  <p class="text-xs sm:text-sm text-muted mt-1">Pilih produk berdasarkan kategori.</p>
</div>

<!-- SEARCH & FILTER DROPDOWN -->
<div class="flex items-center justify-between gap-3 px-4 mt-2 mb-4">
  <!-- Search Bar -->
  <div class="flex-1 relative">
    <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <circle cx="11" cy="11" r="8"/>
      <path d="m21 21-4.35-4.35"/>
    </svg>
    <input type="text" id="searchInput" placeholder="Cari produk..." 
      class="w-full pl-9 pr-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-terra">
  </div>

  <!-- Filter Kategori Dropdown -->
  <div class="relative">
    <button id="filterBtn" onclick="toggleCategoryDropdown()" 
      class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:border-terra transition">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="4" y1="6" x2="20" y2="6"/>
        <line x1="8" y1="12" x2="16" y2="12"/>
        <line x1="10" y1="18" x2="14" y2="18"/>
      </svg>
      <span id="selectedCategoryText">Semua Kategori</span>
      <svg class="transition-transform duration-200" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="6 9 12 15 18 9"/>
      </svg>
    </button>

    <!-- Dropdown Kategori -->
    <div id="categoryDropdown" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-30 hidden">
      <div class="py-2">
        <button onclick="filterByCategory('all', 'Semua Kategori')" class="dropdown-item w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-terra-xs transition">Semua Kategori</button>
        @foreach($categories as $category)
        <button onclick="filterByCategory({{ $category->id }}, '{{ $category->nama_kategori }}')" class="dropdown-item w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-terra-xs transition" data-cat="{{ $category->id }}">
          {{ $category->nama_kategori }}
        </button>
        @endforeach
      </div>
    </div>
  </div>
</div>

<!-- PRODUCT GRID -->
<div class="grid grid-cols-2 gap-3 px-3 pb-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 sm:px-5 lg:px-7" id="productGrid"></div>

<!-- PAGINATION -->
<div class="flex flex-col items-center gap-2.5 px-4 py-4 pb-5">
  <div class="flex items-center justify-center gap-2 text-xs text-muted">
    <span>Produk Per halaman:</span>
    <div class="relative">
      <select id="perPageSelect" onchange="onPerPageChange()"
              class="border-2 border-border rounded-lg px-2.5 py-1.5 pr-7 text-xs font-body text-gray-900 bg-white cursor-pointer outline-none appearance-none focus:border-terra transition-colors">
        <option value="8">8</option>
        <option value="12">12</option>
        <option value="24" selected>24</option>
        <option value="48">48</option>
      </select>
      <svg class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
    </div>
  </div>
  <div class="text-xs text-muted text-center" id="pageInfo"></div>
  <div class="flex items-center gap-1 flex-wrap justify-center" id="pagination"></div>
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
  if (!btn.contains(e.target) && !menu.contains(e.target)) menu.classList.remove('open');
});

// ── Filter Kategori Dropdown ──
function toggleCategoryDropdown() {
  const dropdown = document.getElementById('categoryDropdown');
  if (dropdown) dropdown.classList.toggle('show');
}
document.addEventListener('click', function(e) {
  const filterBtn = document.getElementById('filterBtn');
  const dropdown = document.getElementById('categoryDropdown');
  if (filterBtn && dropdown && !filterBtn.contains(e.target) && !dropdown.contains(e.target)) {
    dropdown.classList.remove('show');
  }
});

let currentCategory = 'all';
let currentSearch = '';
let currentPage = 1;
let perPage = 24;
let products = @json($products);

function filterByCategory(categoryId, categoryName) {
  currentCategory = categoryId;
  document.getElementById('selectedCategoryText').textContent = categoryName;
  document.getElementById('categoryDropdown').classList.remove('show');
  currentPage = 1;
  render();
}

function getFiltered() {
  let filtered = [...products];
  if (currentCategory !== 'all') {
    filtered = filtered.filter(p => p.category_id === currentCategory);
  }
  if (currentSearch.trim() !== '') {
    const kw = currentSearch.toLowerCase();
    filtered = filtered.filter(p => p.nama_produk.toLowerCase().includes(kw));
  }
  return filtered;
}

function render() {
  const filtered = getFiltered();
  const total = filtered.length;
  const totalPages = Math.max(1, Math.ceil(total / perPage));
  if (currentPage > totalPages) currentPage = totalPages;
  const paged = filtered.slice((currentPage-1)*perPage, currentPage*perPage);
  const grid = document.getElementById('productGrid');

  if (paged.length === 0) {
    grid.innerHTML = `<div class="col-span-full text-center py-14 text-muted">
      <svg class="w-12 h-12 mx-auto mb-3 stroke-stone-300" viewBox="0 0 24 24" fill="none" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <p class="text-sm">Produk tidak ditemukan</p></div>`;
    renderPagination(total, totalPages);
    return;
  }

  grid.innerHTML = paged.map((p, i) => {
    // const imageUrl = p.gambar ? '/images/products/' + p.gambar.split('/').pop() : 'https://placehold.co/400x400?text=No+Image';
    const imageUrl = p.gambar
    ? (p.gambar.startsWith('products/')
        ? '/storage/' + p.gambar
        : '/images/products/' + p.gambar.split('/').pop())
    : 'https://placehold.co/400x400?text=No+Image';
    const priceFormatted = `Rp ${parseInt(p.harga).toLocaleString('id-ID')}`;
    let badge = '';
    if (p.is_discount) badge = `<span class="absolute top-2 right-2 z-20 text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-500 text-white">SALE</span>`;
    if (p.stok <= 0) badge = `<span class="absolute top-2 right-2 z-20 text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-500 text-white">HABIS</span>`;
    
    return `
    <div class="card relative bg-white rounded-2xl overflow-hidden shadow-sm cursor-pointer border-2 border-transparent transition-all duration-200 hover:-translate-y-1 hover:shadow-md hover:border-terra-l" style="animation:fadeUp .3s ease ${i*0.04}s both" onclick="window.location.href='/detail-produk/${p.id}'">
      ${badge}
      <div class="relative w-full aspect-square bg-gray-100">
  <img src="${imageUrl}" 
       class="absolute inset-0 w-full h-full object-cover"
       onerror="this.src='https://placehold.co/400x400?text=No+Image'">
</div>
      <div class="p-2.5 pb-3">
        <div class="text-xs font-semibold text-gray-900 mb-1 leading-tight line-clamp-2">${escapeHtml(p.nama_produk)}</div>
        <div class="flex items-baseline gap-1.5 flex-wrap">
          <span class="text-sm font-bold text-terra">${priceFormatted}</span>
        </div>
        <p class="text-[9px] text-gray-400 mt-1">Stok: ${p.stok}</p>
      </div>
    </div>`;
  }).join('');
  renderPagination(total, totalPages);
}

function renderPagination(total, totalPages) {
  document.getElementById('pageInfo').textContent = total ? `Halaman ${currentPage} dari ${totalPages}` : '';
  const pg = document.getElementById('pagination');
  if (totalPages <= 1) { pg.innerHTML = ''; return; }
  const pages = totalPages <= 7 ? Array.from({length:totalPages},(_,i)=>i+1) : buildPageList(currentPage, totalPages);
  let html = `<button onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''} class="w-9 h-9 rounded-xl border-2 border-stone-200 bg-white text-muted flex items-center justify-center cursor-pointer transition-all hover:border-terra-l hover:text-terra disabled:opacity-30">‹</button>`;
  pages.forEach(p => {
    if (p === '…') html += `<span class="w-9 h-9 flex items-center justify-center text-sm text-muted">…</span>`;
    else html += `<button onclick="goPage(${p})" class="pg-btn w-9 h-9 rounded-xl border-2 border-stone-200 bg-white text-muted text-sm font-medium flex items-center justify-center cursor-pointer transition-all hover:border-terra-l hover:text-terra ${p===currentPage?'active':''}">${p}</button>`;
  });
  html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?'disabled':''} class="w-9 h-9 rounded-xl border-2 border-stone-200 bg-white text-muted flex items-center justify-center cursor-pointer transition-all hover:border-terra-l hover:text-terra disabled:opacity-30">›</button>`;
  pg.innerHTML = html;
}

function buildPageList(cur, total) {
  const pages = [1];
  if (cur - 1 > 2) pages.push('…');
  for (let i = Math.max(2, cur-1); i <= Math.min(total-1, cur+1); i++) pages.push(i);
  if (cur + 1 < total - 1) pages.push('…');
  pages.push(total);
  return pages;
}

function goPage(page) {
  const total = getFiltered().length;
  const totalPages = Math.max(1, Math.ceil(total / perPage));
  if (page < 1 || page > totalPages) return;
  currentPage = page;
  render();
  document.getElementById('productGrid').scrollIntoView({ behavior:'smooth', block:'start' });
}

function onSearch() { 
  currentSearch = document.getElementById('searchInput').value; 
  currentPage = 1; 
  render(); 
}

function onPerPageChange() { 
  perPage = parseInt(document.getElementById('perPageSelect').value); 
  currentPage = 1; 
  render(); 
}

function escapeHtml(str) {
  if (!str) return '';
  return str.replace(/[&<>]/g, m => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;' })[m]);
}

document.getElementById('searchInput').addEventListener('input', onSearch);
document.title = "Daftar Produk | SND STORE";
render();
</script>
</body>
</html>