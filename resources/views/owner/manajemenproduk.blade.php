<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
  <title>Kashy – Manajemen Produk & Stok</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { poppins: ['Poppins','sans-serif'] },
          colors: {
            kashy: {
              dark:         '#1a1a1a',
              brown:        '#C49A6C',
              'brown-deep': '#7B4F2E',
              cream:        '#F5F0EB',
              'cream-dark': '#EDE5DB',
              muted:        '#8A7968',
              border:       '#E0D8CE',
            }
          },
          boxShadow: {
            card:    '0 2px 12px 0 rgba(60,40,10,.08)',
            sidebar: '2px 0 20px 0 rgba(60,40,10,.08)',
            btn:     '0 4px 14px 0 rgba(196,154,108,.35)',
          }
        }
      }
    }
  </script>
  <style>
    * { font-family:'Poppins',sans-serif; box-sizing:border-box; }
    body { background:#F5F0EB; margin:0; }

    #sidebar {
      position:fixed; top:0; left:0; height:100vh; width:280px;
      background:#fff; box-shadow:2px 0 24px rgba(60,40,10,.12);
      z-index:60; transition:transform .3s cubic-bezier(0.2,0.9,0.4,1.1);
      display:flex; flex-direction:column; overflow-y:auto;
      transform:translateX(-100%);
    }
    #sidebar.sidebar-open { transform:translateX(0); }
    #overlay {
      display:none; position:fixed; inset:0; background:rgba(0,0,0,.45);
      z-index:55; backdrop-filter:blur(3px);
    }
    #overlay.show { display:block; }

    @keyframes fadeUp {
      from { opacity:0; transform:translateY(18px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .fade-up { animation:fadeUp .4s ease both; }
    .d1 { animation-delay:.05s; } .d2 { animation-delay:.10s; }
    .d3 { animation-delay:.15s; } .d4 { animation-delay:.20s; }
    .d5 { animation-delay:.25s; }

    .nav-item {
      display:flex; align-items:center; gap:12px; padding:11px 18px;
      border-radius:12px; cursor:pointer; transition:all .15s;
      font-size:14px; font-weight:500; color:#1a1a1a;
      text-decoration:none; width:100%;
    }
    .nav-item:hover { background:#F5F0EB; }
    .nav-item.active { background:#F7EFE5; color:#7B4F2E; font-weight:600; }
    .nav-item.active svg { stroke:#7B4F2E; }

    .form-input {
      width:100%; padding:12px 14px; border:1.5px solid #E0D8CE;
      border-radius:12px; font-size:13px; font-family:'Poppins',sans-serif;
      color:#1a1a1a; background:#fff; outline:none;
      transition:border-color .2s;
    }
    .form-input:focus { border-color:#C49A6C; }
    .form-select {
      appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238A7968' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
      background-repeat:no-repeat; background-position:right 12px center;
      padding-right:36px;
    }
    .form-label {
      font-size:11px; font-weight:700; color:#8A7968;
      text-transform:uppercase; letter-spacing:.07em; margin-bottom:6px;
      display:block;
    }
    .required-star { color: #ef4444; margin-left: 2px; }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1rem;
    }
    .prod-img-wrap {
      width:100%; height:160px; border-radius:12px; overflow:hidden;
      position:relative; margin-bottom:8px;
    }
    .prod-img-wrap img { width:100%; height:100%; object-fit:cover; }
    .chip {
      display:inline-flex; align-items:center; gap:3px;
      padding:3px 8px; border-radius:8px;
      font-size:10px; font-weight:600;
      background:#F5F0EB; color:#8A7968; border:1px solid #E0D8CE;
    }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    .badge-produk {
      position: absolute; top: 8px; right: 8px;
      padding: 2px 8px; border-radius: 20px;
      font-size: 9px; font-weight: bold;
      background: #1a1a1a; color: white;
    }
    .badge-sale { background: #ef4444; }

    .img-dropzone {
      width:100%; border:2px dashed #E0D8CE; border-radius:14px;
      padding:28px 20px; text-align:center; cursor:pointer;
      transition:border-color .2s, background .2s; background:#FAF6F2;
      position:relative; overflow:hidden;
    }
    .img-dropzone:hover { border-color:#C49A6C; background:#FDF9F5; }
    .img-dropzone.has-image { border-color:#C49A6C; padding:0; height:180px; }
    .img-dropzone.has-image img {
      width:100%; height:100%; object-fit:cover; border-radius:12px; display:block;
    }
    .img-dropzone.has-image .dropzone-overlay {
      position:absolute; inset:0; background:rgba(0,0,0,.35);
      display:flex; align-items:center; justify-content:center;
      border-radius:12px; opacity:0; transition:opacity .2s;
    }
    .img-dropzone.has-image:hover .dropzone-overlay { opacity:1; }

    #toast {
      position: fixed; bottom: 2rem; left: 50%;
      transform: translateX(-50%) translateY(12px);
      background: #1c1c1c; color: white; font-size: 0.875rem; font-weight: 500;
      padding: 0.75rem 1.25rem; border-radius: 9999px;
      display: flex; align-items: center; gap: 0.5rem;
      z-index: 9999; opacity: 0;
      transition: opacity 0.25s ease, transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
      pointer-events: none;
    }
    #toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

    .modal-confirm {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 10000;
      display: flex; align-items: center; justify-content: center;
      visibility: hidden; opacity: 0; transition: visibility 0.2s, opacity 0.2s;
    }
    .modal-confirm.show { visibility: visible; opacity: 1; }
    .modal-confirm .modal-card {
      background: white; max-width: 380px; width: 90%; border-radius: 28px;
      padding: 24px; text-align: center; transform: scale(0.95);
      transition: transform 0.2s;
    }
    .modal-confirm.show .modal-card { transform: scale(1); }
    .modal-icon { background: #FEF2F2; width: 64px; height: 64px; border-radius: 60px;
      display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
    .modal-icon svg { width: 32px; height: 32px; stroke: #D94F4F; stroke-width: 1.8; }
    .modal-title { font-size: 20px; font-weight: 800; color: #1a1a1a; margin-bottom: 12px; }
    .modal-message { font-size: 14px; color: #6B5E52; margin-bottom: 24px; line-height: 1.5; }
    .modal-buttons { display: flex; gap: 12px; }
    .modal-btn { flex: 1; padding: 12px; border-radius: 40px; font-weight: 700; font-size: 14px;
      cursor: pointer; transition: all 0.2s; border: none; }
    .modal-btn-cancel { background: #F5F0EB; color: #1a1a1a; }
    .modal-btn-confirm { background: #D94F4F; color: white; }

    .pg-btn {
      width: 36px; height: 36px; border-radius: 12px;
      border: 1.5px solid #E0D8CE; background: white;
      color: #8A7968; font-size: 13px; font-weight: 500;
      display: inline-flex; align-items: center; justify-content: center;
      cursor: pointer; transition: all 0.2s;
    }
    .pg-btn:hover { border-color: #C49A6C; color: #C49A6C; background: #FDF9F5; }
    .pg-btn.active { background: #1a1a1a; border-color: #1a1a1a; color: white; font-weight: 600; }
    .pg-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .no-arrow-select {
      appearance: none;
      background-image: none;
    }
  </style>
</head>
@include('owner.components.sidebar')
<body>

<main id="main" class="min-h-screen bg-kashy-cream transition-all duration-300">
  @include('owner.components.topbar')

  <div class="px-4 md:px-6 py-5 max-w-2xl mx-auto">

    <div class="fade-up d1 mb-4">
      <h1 class="text-2xl md:text-3xl font-extrabold text-kashy-dark">Manajemen Produk & Stok</h1>
      <p class="text-xs text-kashy-muted mt-1">Kelola dan pantau inventaris produk Anda.</p>
    </div>

    <div class="fade-up d2 mb-4">
      <button onclick="openProductModal()" class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm tracking-widest text-white uppercase transition-all duration-200 hover:opacity-90 active:scale-[.98]" style="background:#C49A6C; box-shadow:0 4px 14px 0 rgba(196,154,108,.35);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Produk
      </button>
    </div>

    <!-- Filter: Search + Dropdown Kategori -->
    <div class="fade-up d2 mb-4">
      <div class="flex gap-3">
        <div class="relative flex-1">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#8A7968" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          </span>
          <input type="text" id="searchProduct" class="form-input py-2.5 text-sm" style="padding-left:36px;" placeholder="Cari nama produk..."/>
        </div>
        <div class="relative w-40">
          <select id="categoryFilter" class="form-input form-select py-2.5 pr-8 text-sm bg-white cursor-pointer">
            <option value="semua">Semua Kategori</option>
            @foreach($categories as $category)
            <option value="{{ $category->nama_kategori }}">{{ $category->nama_kategori }}</option>
            @endforeach
          </select>
          <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#8A7968" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
        </div>
      </div>
    </div>
    
    <div class="fade-up d2 mb-4" id="categoryStatsContainer">
      <p class="text-[10px] font-bold tracking-widest text-kashy-muted uppercase mb-2">Ringkasan Stok</p>
      <div id="categoryStatsGrid" class="grid grid-cols-1 gap-2"></div>
    </div>

    <div id="productContainer" class="product-grid"></div>

    <div class="fade-up d5 mt-6 mb-4">
      <div class="flex flex-col items-center gap-3">
        <div class="flex items-center justify-center gap-2 text-xs text-kashy-muted">
          <span>Produk Per halaman:</span>
          <div class="relative">
            <select id="perPageSelect" class="border border-kashy-border rounded-lg px-2 py-1.5 pr-7 text-xs bg-white cursor-pointer focus:border-kashy-brown transition-colors no-arrow-select">
              <option value="6">6</option>
              <option value="12" selected>12</option>
              <option value="24">24</option>
              <option value="48">48</option>
            </select>
            <svg class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#8A7968" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>
        <div class="text-xs text-kashy-muted text-center" id="pageInfo"></div>
        <div class="flex items-center gap-1 flex-wrap justify-center" id="pagination"></div>
      </div>
    </div>

  </div>
</main>

<!-- MODAL TAMBAH/EDIT PRODUK -->
<div id="productModal" class="hidden fixed inset-0 z-[999] bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-5 relative">
    <h2 id="modalTitle" class="text-xl font-bold text-kashy-dark mb-4">Tambah Produk</h2>
    <input type="hidden" id="editId" value="">

    <div class="mb-4">
      <label class="form-label">Foto Produk</label>
      <div class="img-dropzone" id="imgDropzone" onclick="document.getElementById('prodImage').click()">
        <input type="file" id="prodImage" accept="image/*" class="hidden" onchange="previewImage(this)"/>
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C49A6C" stroke-width="1.6" class="mx-auto mb-2">
          <rect x="3" y="3" width="18" height="18" rx="2"/>
          <circle cx="8.5" cy="8.5" r="1.5"/>
          <polyline points="21 15 16 10 5 21"/>
        </svg>
        <p class="text-sm font-semibold text-kashy-muted">Pilih gambar</p>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Nama Produk <span class="required-star">*</span></label>
      <input type="text" id="prodName" class="form-input py-2.5" placeholder="Nama produk..."/>
    </div>
    
    <div class="grid grid-cols-2 gap-3 mb-3">
      <div>
        <label class="form-label">Kategori <span class="required-star">*</span></label>
        <select id="prodCategory" class="form-input form-select py-2.5">
          @foreach($categories as $category)
          <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="form-label">Diskon</label>
        <select id="prodDiscount" class="form-input form-select py-2.5">
          <option value="0">Tidak</option>
          <option value="1">Ya (Sale)</option>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-3 mb-3">
      <div><label class="form-label">Harga (Rp) <span class="required-star">*</span></label><input type="text" id="prodPrice" class="form-input py-2.5" placeholder="0" oninput="formatRupiah(this)"/></div>
      <div><label class="form-label">Stok <span class="required-star">*</span></label><input type="number" id="prodStock" class="form-input py-2.5" placeholder="0"/></div>
    </div>

    <div class="grid grid-cols-2 gap-3 mb-3">
      <div><label class="form-label">Ukuran</label><input type="text" id="prodSize" class="form-input py-2.5" placeholder="S,M,L,XL"/></div>
      <div><label class="form-label">Warna</label><input type="text" id="prodColor" class="form-input py-2.5" placeholder="Hitam, Putih"/></div>
    </div>

    <div class="mb-3">
      <label class="form-label">Deskripsi</label>
      <textarea id="prodDesc" rows="2" class="form-input resize-none" placeholder="Deskripsi produk..."></textarea>
    </div>

    <div class="flex flex-col gap-2">
      <button id="saveProductBtn" class="w-full py-3 rounded-xl font-bold text-white text-sm tracking-wide transition-all duration-200 hover:opacity-90" style="background:#C49A6C;">Simpan Produk</button>
      <button onclick="closeProductModal()" class="w-full py-3 rounded-xl font-bold text-kashy-dark text-sm tracking-wide border-2 border-kashy-border transition-all duration-200 hover:bg-kashy-cream bg-white">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div id="confirmDeleteModal" class="modal-confirm">
  <div class="modal-card">
    <div class="modal-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></div>
    <div class="modal-title">Konfirmasi Hapus</div>
    <div class="modal-message" id="confirmDeleteMessage">Apakah Anda yakin ingin menghapus produk ini?</div>
    <div class="modal-buttons">
      <button id="deleteCancelBtn" class="modal-btn modal-btn-cancel">Batal</button>
      <button id="deleteConfirmBtn" class="modal-btn modal-btn-confirm">Ya, Hapus</button>
    </div>
  </div>
</div>

<div id="toast"><span id="toastMsg"></span></div>

<script>
  let products = @json($products);
  let categories = @json($categories);
  
  let currentFilter = "semua";
  let searchKeyword = "";
  let currentPage = 1;
  let perPage = 12;

  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  function getFiltered() {
    let f = [...products];
    if (currentFilter !== "semua") {
      const categoryId = categories.find(c => c.nama_kategori === currentFilter)?.id;
      if (categoryId) f = f.filter(p => p.category_id === categoryId);
    }
    if (searchKeyword.trim()) {
      const kw = searchKeyword.toLowerCase();
      f = f.filter(p => p.nama_produk.toLowerCase().includes(kw));
    }
    return f;
  }

  function renderCategoryStats() {
    const filtered = getFiltered();
    const habis = filtered.filter(p => p.stok === 0).length;
    const menipis = filtered.filter(p => p.stok > 0 && p.stok <= 5).length;
    const statsGrid = document.getElementById('categoryStatsGrid');
    if (statsGrid) {
      statsGrid.innerHTML = `
        <div class="bg-white rounded-xl p-1 shadow-card border border-kashy-border">
          <div class="grid grid-cols-2 gap-2">
            <div class="rounded-lg bg-red-50 border border-red-200 p-1.5 text-center">
              <p class="text-[9px] text-red-500 font-semibold">Stok Habis</p>
              <p class="text-base font-extrabold text-red-600">${habis}</p>
            </div>
            <div class="rounded-lg bg-orange-50 border border-orange-200 p-1.5 text-center">
              <p class="text-[9px] text-orange-500 font-semibold">Stok Menipis</p>
              <p class="text-base font-extrabold text-orange-600">${menipis}</p>
            </div>
          </div>
        </div>
      `;
    }
  }

  function renderPagination(total) {
    const totalPages = Math.max(1, Math.ceil(total / perPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const pageInfo = document.getElementById('pageInfo');
    if (pageInfo) pageInfo.innerHTML = total ? `Halaman ${currentPage} dari ${totalPages}` : '';
    const pgDiv = document.getElementById('pagination');
    if (!pgDiv) return;
    if (totalPages <= 1) { pgDiv.innerHTML = ''; return; }
    let pages = [];
    if (totalPages <= 7) for (let i=1; i<=totalPages; i++) pages.push(i);
    else {
      pages.push(1);
      if (currentPage-1 > 2) pages.push('…');
      for (let i=Math.max(2, currentPage-1); i<=Math.min(totalPages-1, currentPage+1); i++) pages.push(i);
      if (currentPage+1 < totalPages-1) pages.push('…');
      pages.push(totalPages);
    }
    let html = `<button class="pg-btn" onclick="goPage(${currentPage-1})" ${currentPage===1 ? 'disabled' : ''}>‹</button>`;
    pages.forEach(p => {
      if (p === '…') html += `<span class="w-9 h-9 flex items-center justify-center text-sm text-muted">…</span>`;
      else html += `<button class="pg-btn ${p===currentPage ? 'active' : ''}" onclick="goPage(${p})">${p}</button>`;
    });
    html += `<button class="pg-btn" onclick="goPage(${currentPage+1})" ${currentPage===totalPages ? 'disabled' : ''}>›</button>`;
    pgDiv.innerHTML = html;
  }

  function goPage(page) {
    const total = getFiltered().length;
    const totalPages = Math.max(1, Math.ceil(total / perPage));
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderProducts();
  }

  function changePerPage() {
    perPage = parseInt(document.getElementById('perPageSelect').value);
    currentPage = 1;
    renderProducts();
  }

  function renderProducts() {
    let filtered = getFiltered();
    const total = filtered.length;
    const totalPages = Math.max(1, Math.ceil(total / perPage));
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage-1)*perPage;
    const paged = filtered.slice(start, start+perPage);
    const container = document.getElementById('productContainer');
    if (!container) return;
    
    if (paged.length === 0) {
      container.innerHTML = `<div class="col-span-2 bg-white rounded-2xl p-6 text-center shadow-card"><p class="text-kashy-muted text-sm">Tidak ada produk ditemukan.</p></div>`;
    } else {
      container.innerHTML = paged.map(p => {
        const priceFormatted = `Rp ${parseInt(p.harga).toLocaleString('id-ID')}`;
        let badgeHtml = '';
        if (p.is_discount) badgeHtml = `<span class="badge-produk badge-sale">SALE</span>`;
        const stockStatus = p.stok === 0 ? '<p class="text-[8px] font-bold text-red-600 mt-0.5">⚠ Habis</p>' : (p.stok <=5 ? '<p class="text-[8px] font-bold text-orange-500 mt-0.5">⚠ Menipis</p>' : '');
        const imageUrl = p.gambar ? `/storage/${p.gambar}` : 'https://via.placeholder.com/400x400?text=No+Image';
        const categoryName = categories.find(c => c.id === p.category_id)?.nama_kategori || '-';
        const sizes = p.ukuran ? p.ukuran.split(',').map(v => `<span class="chip text-[9px] py-0 px-1.5">${v.trim()}</span>`).join('') : '';
        
        return `
          <div class="bg-white rounded-xl p-2 shadow-card fade-up">
            <div class="prod-img-wrap relative">
              <img src="${imageUrl}" loading="lazy" onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
              ${badgeHtml}
            </div>
            <p class="text-[9px] font-semibold text-kashy-muted uppercase mb-0.5">${categoryName}</p>
            <h3 class="text-xs font-bold text-kashy-dark line-clamp-1 mb-0.5">${escapeHtml(p.nama_produk)}</h3>
            <div class="flex items-center gap-1 flex-wrap mb-1">
              <p class="text-sm font-extrabold text-kashy-brown">${priceFormatted}</p>
            </div>
            <p class="text-[9px] text-kashy-muted line-clamp-2 mb-1.5">${escapeHtml(p.deskripsi || '')}</p>
            <div class="flex items-center justify-between mb-1">
              <div>
                <p class="text-[9px] text-kashy-muted">Stok: ${p.stok}</p>
                ${stockStatus}
              </div>
              <div class="flex flex-wrap gap-1">${sizes}</div>
            </div>
            <div class="flex items-center gap-2 pt-2 border-t border-kashy-border">
              <button class="flex items-center gap-1 text-[9px] font-semibold text-kashy-muted hover:text-kashy-dark" onclick="editProduct(${p.id})">Edit</button>
              <button class="flex items-center gap-1 text-[9px] font-semibold text-red-600 hover:text-red-700" onclick="deleteProduct(${p.id})">Hapus</button>
            </div>
          </div>
        `;
      }).join('');
    }
    renderCategoryStats();
    renderPagination(total);
  }

  function editProduct(id) {
    openProductModal(id);
  }

  function openProductModal(id = null) {
    const modal = document.getElementById('productModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    if (id) {
      const p = products.find(x => x.id === id);
      if (p) {
        document.getElementById('modalTitle').innerText = 'Edit Produk';
        document.getElementById('editId').value = p.id;
        document.getElementById('prodName').value = p.nama_produk;
        document.getElementById('prodCategory').value = p.category_id;
        document.getElementById('prodPrice').value = parseInt(p.harga).toLocaleString('id-ID');
        document.getElementById('prodStock').value = p.stok;
        document.getElementById('prodDiscount').value = p.is_discount ? '1' : '0';
        document.getElementById('prodSize').value = p.ukuran || '';
        document.getElementById('prodColor').value = p.warna || '';
        document.getElementById('prodDesc').value = p.deskripsi || '';
        if (p.gambar) {
          const dz = document.getElementById('imgDropzone');
          dz.innerHTML = `<img src="/storage/${p.gambar}" alt="preview"/><div class="dropzone-overlay"><span class="text-white text-xs font-semibold bg-black/50 px-3 py-1.5 rounded-lg">Ganti Gambar</span></div><input type="file" id="prodImage" accept="image/*" class="hidden" onchange="previewImage(this)"/>`;
          dz.classList.add('has-image');
          dz.onclick = () => document.getElementById('prodImage').click();
        } else {
          resetDropzone();
        }
      }
    } else {
      document.getElementById('modalTitle').innerText = 'Tambah Produk';
      document.getElementById('editId').value = '';
      document.getElementById('prodName').value = '';
      document.getElementById('prodCategory').value = categories[0]?.id || '';
      document.getElementById('prodPrice').value = '';
      document.getElementById('prodStock').value = '';
      document.getElementById('prodDiscount').value = '0';
      document.getElementById('prodSize').value = '';
      document.getElementById('prodColor').value = '';
      document.getElementById('prodDesc').value = '';
      resetDropzone();
    }
  }

  function closeProductModal() {
    const modal = document.getElementById('productModal');
    if (modal) modal.classList.add('hidden');
    document.body.style.overflow = '';
  }

  let deleteId = null;
  function deleteProduct(id) {
    deleteId = id;
    const modal = document.getElementById('confirmDeleteModal');
    if (modal) modal.classList.add('show');
  }

  document.getElementById('deleteConfirmBtn')?.addEventListener('click', function() {
    if (!deleteId) return;
    fetch(`/owner/produk/${deleteId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast(data.message);
        location.reload();
      } else {
        showToast(data.message, false);
      }
    })
    .catch(error => {
      showToast('Terjadi kesalahan', false);
    })
    .finally(() => {
      document.getElementById('confirmDeleteModal')?.classList.remove('show');
      deleteId = null;
    });
  });

  document.getElementById('deleteCancelBtn')?.addEventListener('click', function() {
    document.getElementById('confirmDeleteModal')?.classList.remove('show');
    deleteId = null;
  });

  document.getElementById('saveProductBtn')?.addEventListener('click', function() {
    const id = document.getElementById('editId').value;
    const formData = new FormData();
    formData.append('nama_produk', document.getElementById('prodName').value);
    formData.append('category_id', document.getElementById('prodCategory').value);
    formData.append('harga', parseRupiahToNumber(document.getElementById('prodPrice').value));
    formData.append('stok', document.getElementById('prodStock').value);
    formData.append('ukuran', document.getElementById('prodSize').value);
    formData.append('warna', document.getElementById('prodColor').value);
    formData.append('deskripsi', document.getElementById('prodDesc').value);
    formData.append('is_discount', document.getElementById('prodDiscount').value);
    
    const fileInput = document.getElementById('prodImage');
    if (fileInput.files[0]) {
      formData.append('gambar', fileInput.files[0]);
    }
    
    const url = id ? `/owner/produk/${id}` : '/owner/produk';
    
    if (id) formData.append('_method', 'PUT');
    
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast(data.message);
        location.reload();
      } else {
        showToast(data.message, false);
      }
    })
    .catch(error => {
      showToast('Terjadi kesalahan', false);
    });
  });

  // Event listeners
  document.getElementById('searchProduct')?.addEventListener('input', e => { 
    searchKeyword = e.target.value; 
    currentPage = 1; 
    renderProducts(); 
  });
  
  document.getElementById('perPageSelect')?.addEventListener('change', changePerPage);
  
  document.getElementById('categoryFilter')?.addEventListener('change', function(e) {
    currentFilter = e.target.value;
    currentPage = 1;
    renderProducts();
  });

  function showToast(msg, success=true) {
    const toast = document.getElementById('toast');
    if (!toast) return;
    document.getElementById('toastMsg').innerText = msg;
    toast.style.background = success ? '#1c1c1c' : '#ef4444';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2600);
  }

  function formatRupiah(input) {
    let val = input.value.replace(/[^0-9]/g, '');
    if (val === '') { input.value = ''; return; }
    input.value = new Intl.NumberFormat('id-ID').format(parseInt(val));
  }
  
  function parseRupiahToNumber(str) { return parseInt(str.replace(/[^0-9]/g, '')) || 0; }
  
  function escapeHtml(str) { 
    if (!str) return ''; 
    return str.replace(/[&<>]/g, m => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;' })[m]); 
  }

  function previewImage(input) {
    if (!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
      const dz = document.getElementById('imgDropzone');
      if (!dz) return;
      dz.innerHTML = `<img src="${e.target.result}" alt="preview"/><div class="dropzone-overlay"><span class="text-white text-xs font-semibold bg-black/50 px-3 py-1.5 rounded-lg">Ganti Gambar</span></div><input type="file" id="prodImage" accept="image/*" class="hidden" onchange="previewImage(this)"/>`;
      dz.classList.add('has-image');
      dz.onclick = () => document.getElementById('prodImage').click();
    };
    reader.readAsDataURL(input.files[0]);
  }
  
  function resetDropzone() {
    const dz = document.getElementById('imgDropzone');
    if (!dz) return;
    dz.classList.remove('has-image');
    dz.innerHTML = `<input type="file" id="prodImage" accept="image/*" class="hidden" onchange="previewImage(this)"/><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C49A6C" stroke-width="1.6" class="mx-auto mb-2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><p class="text-sm font-semibold text-kashy-muted">Pilih atau seret gambar</p><p class="text-xs text-kashy-muted mt-1">JPG, PNG — maks 5MB</p>`;
    dz.onclick = () => document.getElementById('prodImage').click();
  }

  // Render awal
  renderProducts();
  // Sidebar / hamburger menu
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const menuBtn = document.getElementById('global-menu-toggle');
  const navLinks = document.querySelectorAll('[data-nav]');

  function openSidebar() {
    sidebar?.classList.add('sidebar-open');
    overlay?.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    sidebar?.classList.remove('sidebar-open');
    overlay?.classList.remove('show');
    document.body.style.overflow = '';
  }

  function toggleSidebar() {
    if (sidebar?.classList.contains('sidebar-open')) {
      closeSidebar();
    } else {
      openSidebar();
    }
  }

  if (menuBtn) {
    menuBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      toggleSidebar();
    });
  }

  overlay?.addEventListener('click', closeSidebar);

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeSidebar();
    }
  });

  navLinks.forEach(link => {
    link.addEventListener('click', closeSidebar);
  });

  sidebar?.classList.remove('sidebar-open');
  overlay?.classList.remove('show');
</script>
</body>
</html>