<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Kashy – Manajemen Diskon</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
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
            card: '0 2px 18px 0 rgba(60,40,10,.07)',
            btn:  '0 4px 14px 0 rgba(196,154,108,.35)',
          }
        }
      }
    }
  </script>
  <style>
    * { font-family:'Poppins',sans-serif; box-sizing:border-box; }
    body { background:#F5F0EB; margin:0; }

    #sidebar { position:fixed; top:0; left:0; height:100vh; width:280px; background:#fff; box-shadow:2px 0 24px rgba(60,40,10,.12); z-index:60; transition:transform .3s cubic-bezier(0.2,0.9,0.4,1.1); display:flex; flex-direction:column; overflow-y:auto; transform:translateX(-100%); }
    #sidebar.sidebar-open { transform:translateX(0); }
    #overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:55; backdrop-filter:blur(3px); }
    #overlay.show { display:block; }

    @keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation:fadeUp .4s ease both; }
    .d1{animation-delay:.05s} .d2{animation-delay:.10s} .d3{animation-delay:.15s}

    .nav-item { display:flex; align-items:center; gap:12px; padding:11px 18px; border-radius:12px; cursor:pointer; transition:all .15s; font-size:14px; font-weight:500; color:#1a1a1a; text-decoration:none; width:100%; }
    .nav-item:hover { background:#F5F0EB; }
    .nav-item.active { background:#F7EFE5; color:#7B4F2E; font-weight:600; }
    .nav-item.active svg { stroke:#7B4F2E; }

    .tab-btn { padding:10px 4px; font-size:13px; font-weight:600; color:#8A7968; border-bottom:2px solid transparent; cursor:pointer; transition:all .2s; background:none; border-top:none; border-left:none; border-right:none; }
    .tab-btn.active { color:#1a1a1a; border-bottom-color:#C49A6C; }

    .promo-img-wrap { width:100%; height:160px; border-radius:14px; overflow:hidden; position:relative; margin-bottom:12px; }
    .promo-img-wrap img { width:100%; height:100%; object-fit:cover; }
    .promo-badge { position:absolute; top:10px; right:10px; font-size:10px; font-weight:700; letter-spacing:.05em; padding:3px 10px; border-radius:20px; }

    .form-input { width:100%; padding:12px 14px; border:1.5px solid #E0D8CE; border-radius:12px; font-size:13px; font-family:'Poppins',sans-serif; color:#1a1a1a; background:#FAF6F2; outline:none; transition:border-color .2s; }
    .form-input:focus { border-color:#C49A6C; background:#fff; }
    .form-select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238A7968' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 12px center; padding-right:36px; cursor:pointer; }
    .form-label { font-size:11px; font-weight:700; color:#8A7968; text-transform:uppercase; letter-spacing:.07em; margin-bottom:6px; display:block; }

    .img-dropzone { width:100%; border:2px dashed #E0D8CE; border-radius:14px; padding:28px 20px; text-align:center; cursor:pointer; transition:border-color .2s, background .2s; background:#FAF6F2; position:relative; overflow:hidden; }
    .img-dropzone:hover { border-color:#C49A6C; background:#FDF9F5; }
    .img-dropzone.has-image { border-color:#C49A6C; padding:0; height:180px; }
    .img-dropzone.has-image img { width:100%; height:100%; object-fit:cover; border-radius:12px; display:block; }
    .dropzone-overlay { position:absolute; inset:0; background:rgba(0,0,0,.35); display:flex; align-items:center; justify-content:center; border-radius:12px; opacity:0; transition:opacity .2s; }
    .img-dropzone.has-image:hover .dropzone-overlay { opacity:1; }

    /* Tag kategori */
    .category-tag { display:inline-flex; align-items:center; gap:5px; background:#F7EFE5; color:#7B4F2E; font-size:12px; font-weight:600; padding:4px 10px; border-radius:20px; border:1px solid #E8D5C0; }
    .category-tag button { background:none; border:none; cursor:pointer; color:#C49A6C; padding:0; line-height:1; font-size:14px; display:flex; align-items:center; }
    .category-tag button:hover { color:#7B4F2E; }

    /* Chip semua produk */
    .chip-semua { display:inline-flex; align-items:center; gap:5px; background:#EDE5DB; color:#7B4F2E; font-size:12px; font-weight:700; padding:4px 12px; border-radius:20px; }

    /* Btn semua produk toggle */
    .btn-semua-produk { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; border:2px solid #E0D8CE; background:#FAF6F2; color:#8A7968; cursor:pointer; transition:all .2s; }
    .btn-semua-produk.active { border-color:#C49A6C; background:#F7EFE5; color:#7B4F2E; }

    /* Category search dropdown */
    .category-search-wrap { position:relative; }
    .category-dropdown { position:absolute; top:calc(100% + 4px); left:0; right:0; background:#fff; border:1.5px solid #E0D8CE; border-radius:12px; max-height:200px; overflow-y:auto; z-index:100; box-shadow:0 8px 24px rgba(60,40,10,.12); }
    .category-dropdown-item { padding:10px 14px; font-size:13px; cursor:pointer; transition:background .15s; display:flex; align-items:center; }
    .category-dropdown-item:hover { background:#F7EFE5; }
    .category-dropdown-item.already-selected { opacity:0.4; cursor:not-allowed; }
    .category-dropdown-item.already-selected:hover { background:transparent; }

    /* Modal */
    .modal-fixed { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:1000; display:flex; align-items:center; justify-content:center; visibility:hidden; opacity:0; transition:visibility 0.2s, opacity 0.2s; }
    .modal-fixed.show { visibility:visible; opacity:1; }
    .modal-card { background:white; max-width:580px; width:90%; max-height:90vh; overflow-y:auto; border-radius:28px; padding:24px; transform:scale(0.95); transition:transform 0.2s; }
    .modal-fixed.show .modal-card { transform:scale(1); }

    .modal-confirm { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:2000; display:flex; align-items:center; justify-content:center; visibility:hidden; opacity:0; transition:visibility 0.2s, opacity 0.2s; }
    .modal-confirm.show { visibility:visible; opacity:1; }
    .modal-confirm .modal-card { background:white; max-width:380px; width:90%; border-radius:28px; padding:24px; text-align:center; transform:scale(0.95); transition:transform 0.2s; }
    .modal-confirm.show .modal-card { transform:scale(1); }
    .modal-icon { width:64px; height:64px; border-radius:60px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; }
    .modal-icon-success { background:#E8F5EA; }
    .modal-icon-success svg { stroke:#3A9E6F; }
    .modal-icon-danger { background:#FEF2F2; }
    .modal-icon-danger svg { stroke:#D94F4F; }
    .modal-title-text { font-size:20px; font-weight:800; color:#1a1a1a; margin-bottom:12px; }
    .modal-message { font-size:14px; color:#6B5E52; margin-bottom:24px; line-height:1.5; }
    .modal-buttons { display:flex; gap:12px; }
    .modal-btn { flex:1; padding:12px; border-radius:40px; font-weight:700; font-size:14px; cursor:pointer; transition:all 0.2s; border:none; }
    .modal-btn-cancel { background:#F5F0EB; color:#1a1a1a; }
    .modal-btn-cancel:hover { background:#EDE5DB; }
    .modal-btn-confirm-success { background:#3A9E6F; color:white; }
    .modal-btn-confirm-success:hover { background:#2e7d57; }
    .modal-btn-confirm-danger { background:#D94F4F; color:white; }
    .modal-btn-confirm-danger:hover { background:#b53b3b; }

    #toast { position:fixed; bottom:2rem; left:50%; transform:translateX(-50%) translateY(12px); background:#1c1c1c; color:white; font-size:0.875rem; font-weight:500; padding:0.75rem 1.25rem; border-radius:9999px; display:flex; align-items:center; gap:0.5rem; z-index:9999; opacity:0; transition:opacity 0.25s, transform 0.3s; pointer-events:none; }
    #toast.show { opacity:1; transform:translateX(-50%) translateY(0); }
  </style>
</head>

@include('owner.components.sidebar')

<body>
<main id="main" class="min-h-screen bg-kashy-cream">
  @include('owner.components.topbar')

  <div class="px-5 md:px-8 py-6 max-w-2xl mx-auto">

    <div class="fade-up d1 mb-5">
      <h1 class="text-3xl font-extrabold text-kashy-dark">Manajemen Diskon</h1>
      <p class="text-sm text-kashy-muted mt-1">Kelola diskon produk koleksi anda.</p>
    </div>

    <div class="fade-up d2 mb-5">
      <button onclick="openDiskonModal()" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl font-bold text-sm tracking-widest text-white uppercase transition-all duration-200 hover:opacity-90 active:scale-[.98]" style="background:#C49A6C; box-shadow:0 4px 14px 0 rgba(196,154,108,.35);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Diskon Baru
      </button>
    </div>

    <div class="fade-up d2 flex gap-6 border-b border-kashy-border mb-5">
      <button class="tab-btn active" onclick="switchTab('aktif', this)">Diskon Aktif <span id="badgeAktif">(0)</span></button>
      <button class="tab-btn" onclick="switchTab('nonaktif', this)">Diskon Tidak Aktif <span id="badgeNonaktif">(0)</span></button>
    </div>

    <div id="tab-aktif" class="flex flex-col gap-4"></div>
    <div id="tab-nonaktif" class="hidden flex flex-col gap-4"></div>

  </div>
</main>

<!-- MODAL TAMBAH/EDIT DISKON -->
<div id="diskonModal" class="modal-fixed">
  <div class="modal-card">
    <div class="flex items-center justify-between mb-5">
      <h2 id="modalTitle" class="text-xl font-bold text-kashy-dark">Tambah Diskon</h2>
      <button onclick="closeDiskonModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <input type="hidden" id="editId" value="">

    <!-- Gambar Promosi -->
    <div class="mb-4">
      <label class="form-label">Gambar Promosi <span class="normal-case font-normal text-kashy-muted">(opsional)</span></label>
      <input type="file" id="modalImgInput" accept="image/*" class="hidden" onchange="previewModalImage(this)"/>
      <div class="img-dropzone" id="modalImgDropzone" onclick="document.getElementById('modalImgInput').click()">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C49A6C" stroke-width="1.6" class="mx-auto mb-2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        <p class="text-sm font-semibold text-kashy-muted">Pilih atau seret gambar</p>
        <p class="text-xs text-kashy-muted mt-1">JPG, PNG — maks 5MB</p>
      </div>
    </div>

    <!-- Nama Promosi -->
    <div class="mb-4">
      <label class="form-label">Nama Promosi</label>
      <input type="text" id="modalNamaPromosi" class="form-input" placeholder="Contoh: Flash Sale Lebaran...">
    </div>

    <!-- Tipe & Nilai -->
    <div class="grid grid-cols-2 gap-3 mb-4">
      <div>
        <label class="form-label">Tipe <span class="text-red-400">*</span></label>
        <select id="modalTipe" class="form-input form-select">
          <option value="persen">Persentase</option>
          <option value="fixed">Nominal (Rp)</option>
        </select>
      </div>
      <div>
        <label class="form-label">Nilai <span class="text-red-400">*</span></label>
        <div class="relative">
          <input type="number" id="modalNilai" class="form-input pr-10" placeholder="20" min="1"/>
          <span id="nilaiSuffix" class="absolute right-3 top-1/2 -translate-y-1/2 text-kashy-muted text-sm font-semibold">%</span>
        </div>
      </div>
    </div>

    <!-- Tanggal -->
    <div class="grid grid-cols-2 gap-3 mb-4">
      <div>
        <label class="form-label">Mulai Dari <span class="text-red-400">*</span></label>
        <input type="date" id="modalMulai" class="form-input"/>
      </div>
      <div>
        <label class="form-label">Berakhir <span class="text-red-400">*</span></label>
        <input type="date" id="modalAkhir" class="form-input"/>
      </div>
    </div>

    <!-- Berlaku Untuk -->
    <div class="mb-6">
      <label class="form-label">Berlaku Untuk <span class="text-red-400">*</span></label>

      <!-- Tombol Semua Produk + tag kategori terpilih -->
      <div id="selectedCategoryTags" class="flex flex-wrap gap-2 mb-3 min-h-[32px] items-center">
        <!-- diisi JS -->
      </div>

      <!-- Search kategori -->
      <div class="category-search-wrap" id="categorySearchWrap">
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-kashy-muted" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input type="text" id="categorySearchInput" class="form-input pl-8 text-sm"
            placeholder="Cari &amp; tambah kategori (Enter)..." autocomplete="off"
            oninput="filterCategoryDropdown(this.value)"
            onfocus="showCategoryDropdown()"
            onkeydown="handleCategorySearchKey(event)">
        </div>
        <div id="categoryDropdown" class="category-dropdown hidden"></div>
      </div>
    </div>

    <!-- Buttons -->
    <div class="flex flex-col gap-2">
      <button type="button" onclick="saveDiskon()" id="saveDiskonBtn" class="w-full py-4 rounded-2xl font-bold text-sm text-white transition-all hover:opacity-90 active:scale-[.98]" style="background:#C49A6C; box-shadow:0 4px 14px 0 rgba(196,154,108,.35);">Simpan Diskon</button>
      <button type="button" onclick="closeDiskonModal()" class="w-full py-4 rounded-2xl font-bold text-sm text-kashy-muted border-2 border-kashy-border bg-white hover:bg-kashy-cream transition-all active:scale-[.98]">Batal</button>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI -->
<div id="confirmModal" class="modal-confirm">
  <div class="modal-card">
    <div class="modal-icon" id="confirmIcon"></div>
    <div class="modal-title-text" id="confirmTitle">Konfirmasi</div>
    <div class="modal-message" id="confirmMessage"></div>
    <div class="modal-buttons">
      <button id="confirmCancelBtn" class="modal-btn modal-btn-cancel">Batal</button>
      <button id="confirmOkBtn" class="modal-btn">Ya, Lanjutkan</button>
    </div>
  </div>
</div>

<div id="toast"><span id="toastMsg"></span></div>

<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  @php
    $discountsArray = $discounts->map(function($d) {
        return [
            'id'              => $d->id,
            'nama_promosi'    => $d->nama_promosi ?? '',
            'semua_produk'    => (bool) ($d->semua_produk ?? false),
            'category_ids'    => $d->categories->pluck('id')->toArray(),
            'category_names'  => $d->categories->pluck('nama_kategori')->toArray(),
            'tipe_diskon'     => $d->tipe_diskon,
            'nilai_diskon'    => $d->nilai_diskon,
            'tanggal_mulai'   => $d->tanggal_mulai ? $d->tanggal_mulai->format('Y-m-d') : '',
            'tanggal_selesai' => $d->tanggal_selesai ? $d->tanggal_selesai->format('Y-m-d') : '',
            'gambar'          => $d->gambar,
            'active'          => $d->tanggal_selesai ? ($d->tanggal_selesai->gte(now()) ? true : false) : true,
        ];
    })->values();

    $allCategoriesForJs = \App\Models\Category::select('id','nama_kategori')
        ->orderBy('nama_kategori')->get()
        ->map(fn($c) => ['id' => $c->id, 'name' => $c->nama_kategori])
        ->values();
  @endphp

  let discounts       = @json($discountsArray);
  const allCategories = @json($allCategoriesForJs);

  // ── State modal ──
  let selectedCategoryIds = []; // [{id, name}]
  let semuaProduk = false;

  // ── Render kartu ──
  function renderAll() { renderActive(); renderInactive(); }

  function renderActive() {
    const list = discounts.filter(d => d.active);
    document.getElementById('badgeAktif').innerText = `(${list.length})`;
    const el = document.getElementById('tab-aktif');
    if (!list.length) { el.innerHTML = `<div class="bg-white rounded-2xl p-8 text-center text-kashy-muted text-sm shadow-card">Belum ada diskon aktif.</div>`; return; }
    el.innerHTML = list.map(d => cardHtml(d, true)).join('');
  }

  function renderInactive() {
    const list = discounts.filter(d => !d.active);
    document.getElementById('badgeNonaktif').innerText = `(${list.length})`;
    const el = document.getElementById('tab-nonaktif');
    if (!list.length) { el.innerHTML = `<div class="bg-white rounded-2xl p-8 text-center text-kashy-muted text-sm shadow-card">Tidak ada diskon nonaktif.</div>`; return; }
    el.innerHTML = list.map(d => cardHtml(d, false)).join('');
  }

  function cardHtml(d, active) {
    const imgSrc = d.gambar
      ? (d.gambar.startsWith('discounts/') ? '/storage/' + d.gambar : '/images/discounts/' + d.gambar.split('/').pop())
      : 'https://placehold.co/600x160?text=No+Image';

    const badge = active
      ? `<span class="promo-badge" style="background:#EBF5EF;color:#3A9E6F;">Aktif</span>`
      : `<span class="promo-badge" style="background:#FEF2F2;color:#D94F4F;">Nonaktif</span>`;

    const tipeLabel = d.tipe_diskon === 'persen' ? `${d.nilai_diskon}%` : `Rp ${parseInt(d.nilai_diskon).toLocaleString('id-ID')}`;
    const selesai   = d.tanggal_selesai ? new Date(d.tanggal_selesai).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'}) : '-';
    const namaPromo = d.nama_promosi ? `<p class="text-xs text-kashy-muted mb-1 font-medium italic">${escapeHtml(d.nama_promosi)}</p>` : '';

    // Tags kategori / semua produk
    let tags = '';
    if (d.semua_produk) {
      tags = `<span class="chip-semua">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        Semua Produk
      </span>`;
    } else {
      tags = (d.category_names || []).map(n =>
        `<span class="category-tag">${escapeHtml(n)}</span>`
      ).join('') || '<span class="text-xs text-kashy-muted">Tidak ada kategori</span>';
    }

    const actions = active
      ? `<button class="text-sm font-semibold text-kashy-muted hover:text-kashy-dark" onclick="editDiskon(${d.id})">Edit</button>
         <button class="text-sm font-semibold text-red-600 hover:text-red-700" onclick="akhiriDiskon(${d.id})">Akhiri</button>
         <button class="text-sm font-semibold text-red-400 hover:text-red-600" onclick="hapusDiskon(${d.id})">Hapus</button>`
      : `<button class="text-sm font-semibold text-green-600 hover:text-green-700" onclick="aktifkanDiskon(${d.id})">Aktifkan</button>
         <button class="text-sm font-semibold text-red-400 hover:text-red-600" onclick="hapusDiskon(${d.id})">Hapus</button>`;

    return `
      <div class="fade-up d3 bg-white rounded-2xl overflow-hidden shadow-card ${active ? '' : 'opacity-75'}">
        <div class="promo-img-wrap">
          <img src="${imgSrc}" alt="" onerror="this.src='https://placehold.co/600x160?text=No+Image'"/>
          ${badge}
        </div>
        <div class="p-4">
          ${namaPromo}
          <h3 class="text-base font-bold text-kashy-dark mb-2">${d.tipe_diskon === 'persen' ? 'Persentase' : 'Nominal'} · Diskon ${tipeLabel}</h3>
          <div class="flex flex-wrap gap-1.5 mb-3">${tags}</div>
          <div class="flex gap-6 mb-3">
            <div><p class="text-[10px] font-semibold text-kashy-muted uppercase">Diskon</p><p class="text-base font-bold text-kashy-brown">${tipeLabel}</p></div>
            <div><p class="text-[10px] font-semibold text-kashy-muted uppercase">Berakhir</p><p class="text-base font-bold text-kashy-dark">${selesai}</p></div>
          </div>
          <div class="flex items-center gap-4 pt-3 border-t border-kashy-border">${actions}</div>
        </div>
      </div>`;
  }

  // ── Tab ──
  function switchTab(tab, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-aktif').classList.toggle('hidden', tab !== 'aktif');
    document.getElementById('tab-nonaktif').classList.toggle('hidden', tab !== 'nonaktif');
  }

  // ── Tipe suffix ──
  document.getElementById('modalTipe').addEventListener('change', function() {
    document.getElementById('nilaiSuffix').textContent = this.value === 'persen' ? '%' : 'Rp';
  });

  // ── Category Tag Logic ──
  function renderSelectedCategoryTags() {
    const container = document.getElementById('selectedCategoryTags');

    // Tombol Semua Produk selalu tampil pertama
    const btnActive = semuaProduk;
    let html = `
      <button type="button" id="btnSemuaProduk" onclick="toggleSemuaProduk()"
        class="btn-semua-produk ${btnActive ? 'active' : ''}">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
          <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        Semua Produk${btnActive ? ' ×' : ''}
      </button>`;

    // Tampilkan tag kategori jika tidak semua produk
    if (!semuaProduk) {
      html += selectedCategoryIds.map(c => `
        <span class="category-tag">
          ${escapeHtml(c.name)}
          <button type="button" onclick="removeCategoryTag(${c.id})" title="Hapus">×</button>
        </span>
      `).join('');
    }

    container.innerHTML = html;
  }

  function removeCategoryTag(id) {
    selectedCategoryIds = selectedCategoryIds.filter(c => c.id !== id);
    renderSelectedCategoryTags();
  }

  function toggleSemuaProduk() {
    semuaProduk = !semuaProduk;
    if (semuaProduk) {
      selectedCategoryIds = [];
      document.getElementById('categorySearchWrap').style.display = 'none';
    } else {
      document.getElementById('categorySearchWrap').style.display = '';
    }
    renderSelectedCategoryTags();
  }

  // ── Category Dropdown ──
  function filterCategoryDropdown(query) {
    const dd  = document.getElementById('categoryDropdown');
    const q   = query.toLowerCase().trim();
    let list  = allCategories;
    if (q) list = allCategories.filter(c => c.name.toLowerCase().includes(q));

    if (!list.length) {
      dd.innerHTML = `<div class="p-3 text-center text-kashy-muted text-sm">Kategori tidak ditemukan</div>`;
      dd.classList.remove('hidden');
      return;
    }

    dd.innerHTML = list.map(c => {
      const alreadySelected = selectedCategoryIds.find(x => x.id === c.id);
      return `<div class="category-dropdown-item ${alreadySelected ? 'already-selected' : ''}"
        onclick="${alreadySelected ? '' : `addCategoryTag(${c.id}, '${escapeHtmlAttr(c.name)}')`}">
        <span class="flex-1">${escapeHtml(c.name)}</span>
        ${alreadySelected ? '<span class="text-kashy-muted text-xs ml-auto">Sudah dipilih</span>' : ''}
      </div>`;
    }).join('');
    dd.classList.remove('hidden');
  }

  function showCategoryDropdown() {
    filterCategoryDropdown(document.getElementById('categorySearchInput').value);
  }

  function addCategoryTag(id, name) {
    if (!selectedCategoryIds.find(c => c.id === id)) {
      selectedCategoryIds.push({ id, name });
      renderSelectedCategoryTags();
    }
    document.getElementById('categorySearchInput').value = '';
    document.getElementById('categoryDropdown').classList.add('hidden');
  }

  function handleCategorySearchKey(e) {
    if (e.key === 'Enter') {
      const q     = e.target.value.trim().toLowerCase();
      const match = allCategories.find(c =>
        c.name.toLowerCase().includes(q) && !selectedCategoryIds.find(x => x.id === c.id)
      );
      if (match) addCategoryTag(match.id, match.name);
    }
    if (e.key === 'Escape') document.getElementById('categoryDropdown').classList.add('hidden');
  }

  document.addEventListener('click', e => {
    if (!e.target.closest('#categorySearchWrap')) {
      document.getElementById('categoryDropdown').classList.add('hidden');
    }
  });

  // ── Modal Open/Close ──
  function openDiskonModal(id = null) {
    const modal = document.getElementById('diskonModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';

    // Reset state
    document.getElementById('modalImgInput').value = '';
    selectedCategoryIds = [];
    semuaProduk = false;
    document.getElementById('categorySearchWrap').style.display = '';
    document.getElementById('nilaiSuffix').textContent = '%';

    if (id) {
      const d = discounts.find(x => x.id === id);
      if (!d) return;

      document.getElementById('modalTitle').innerText    = 'Edit Diskon';
      document.getElementById('editId').value            = d.id;
      document.getElementById('modalNamaPromosi').value  = d.nama_promosi || '';
      document.getElementById('modalTipe').value         = d.tipe_diskon;
      document.getElementById('nilaiSuffix').textContent = d.tipe_diskon === 'persen' ? '%' : 'Rp';
      document.getElementById('modalNilai').value        = d.nilai_diskon;
      document.getElementById('modalMulai').value        = d.tanggal_mulai;
      document.getElementById('modalAkhir').value        = d.tanggal_selesai;

      if (d.semua_produk) {
        semuaProduk = true;
        document.getElementById('categorySearchWrap').style.display = 'none';
      } else {
        selectedCategoryIds = (d.category_ids || []).map((cid, i) => ({
          id:   cid,
          name: d.category_names[i] || ''
        }));
      }

      if (d.gambar) setDropzoneImage('/storage/' + d.gambar);
      else resetDropzone();
    } else {
      document.getElementById('modalTitle').innerText   = 'Tambah Diskon';
      document.getElementById('editId').value           = '';
      document.getElementById('modalNamaPromosi').value = '';
      document.getElementById('modalTipe').value        = 'persen';
      document.getElementById('modalNilai').value       = '';
      document.getElementById('modalMulai').value       = '';
      document.getElementById('modalAkhir').value       = '';
      resetDropzone();
    }

    renderSelectedCategoryTags();
  }

  function closeDiskonModal() {
    document.getElementById('diskonModal').classList.remove('show');
    document.body.style.overflow = '';
  }

  function previewModalImage(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => setDropzoneImage(e.target.result);
    reader.readAsDataURL(input.files[0]);
  }

  function setDropzoneImage(src) {
    const dz = document.getElementById('modalImgDropzone');
    dz.classList.add('has-image');
    dz.innerHTML = `<img src="${src}" alt="preview"/><div class="dropzone-overlay"><span class="text-white text-xs font-semibold bg-black/50 px-3 py-1.5 rounded-lg">Ganti Gambar</span></div>`;
  }

  function resetDropzone() {
    const dz = document.getElementById('modalImgDropzone');
    dz.classList.remove('has-image');
    dz.innerHTML = `
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C49A6C" stroke-width="1.6" class="mx-auto mb-2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
      <p class="text-sm font-semibold text-kashy-muted">Pilih atau seret gambar</p>
      <p class="text-xs text-kashy-muted mt-1">JPG, PNG — maks 5MB</p>`;
  }

  // ── Save ──
  async function saveDiskon() {
    const nilai = document.getElementById('modalNilai').value;
    const mulai = document.getElementById('modalMulai').value;
    const akhir = document.getElementById('modalAkhir').value;

    if (!nilai) { showToast('Nilai diskon harus diisi', false); return; }
    if (!mulai) { showToast('Tanggal mulai harus diisi', false); return; }
    if (!akhir) { showToast('Tanggal selesai harus diisi', false); return; }
    if (!semuaProduk && selectedCategoryIds.length === 0) {
      showToast('Pilih minimal 1 kategori atau aktifkan Semua Produk', false); return;
    }

    const id       = document.getElementById('editId').value;
    const formData = new FormData();
    formData.append('nama_promosi',    document.getElementById('modalNamaPromosi').value);
    formData.append('tipe_diskon',     document.getElementById('modalTipe').value);
    formData.append('nilai_diskon',    nilai);
    formData.append('tanggal_mulai',   mulai);
    formData.append('tanggal_selesai', akhir);
    formData.append('semua_produk',    semuaProduk ? '1' : '0');

    if (!semuaProduk) {
      selectedCategoryIds.forEach(c => formData.append('category_ids[]', c.id));
    }

    const fileInput = document.getElementById('modalImgInput');
    if (fileInput && fileInput.files[0]) formData.append('gambar', fileInput.files[0]);

    const url = id ? `/owner/diskon/${id}` : '/owner/diskon';
    if (id) formData.append('_method', 'PUT');

    const btn = document.getElementById('saveDiskonBtn');
    btn.disabled  = true;
    btn.innerText = 'Menyimpan...';

    try {
      const res  = await fetch(url, { method:'POST', headers:{'X-CSRF-TOKEN': csrfToken}, body: formData });
      const data = await res.json();
      if (data.success) { showToast(data.message); closeDiskonModal(); location.reload(); }
      else showToast(data.message || 'Gagal menyimpan', false);
    } catch (e) { showToast('Terjadi kesalahan', false); }
    finally { btn.disabled = false; btn.innerText = 'Simpan Diskon'; }
  }

  function editDiskon(id) { openDiskonModal(id); }

  async function akhiriDiskon(id) {
    const confirmed = await showConfirm('Akhiri diskon ini? Diskon akan dipindahkan ke tab Tidak Aktif.', 'danger', 'Ya, Akhiri');
    if (!confirmed) return;
    const d = discounts.find(x => x.id === id);
    if (!d) return;

    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    const yy = yesterday.getFullYear(),
          mm = String(yesterday.getMonth()+1).padStart(2,'0'),
          dd = String(yesterday.getDate()).padStart(2,'0');

    const formData = new FormData();
    formData.append('_method',         'PUT');
    formData.append('nama_promosi',    d.nama_promosi || '');
    formData.append('tipe_diskon',     d.tipe_diskon);
    formData.append('nilai_diskon',    d.nilai_diskon);
    formData.append('tanggal_mulai',   d.tanggal_mulai);
    formData.append('tanggal_selesai', `${yy}-${mm}-${dd}`);
    formData.append('semua_produk',    d.semua_produk ? '1' : '0');
    (d.category_ids || []).forEach(cid => formData.append('category_ids[]', cid));

    try {
      const res  = await fetch(`/owner/diskon/${id}`, { method:'POST', headers:{'X-CSRF-TOKEN': csrfToken}, body: formData });
      const data = await res.json();
      if (data.success) { showToast('Diskon berhasil diakhiri'); location.reload(); }
      else showToast(data.message || 'Gagal', false);
    } catch (e) { showToast('Terjadi kesalahan', false); }
  }

  async function aktifkanDiskon(id) {
    const confirmed = await showConfirm('Aktifkan kembali diskon ini?', 'success', 'Ya, Aktifkan');
    if (!confirmed) return;
    const d = discounts.find(x => x.id === id);
    if (!d) return;

    const future = new Date();
    future.setDate(future.getDate() + 30);
    const yy = future.getFullYear(),
          mm = String(future.getMonth()+1).padStart(2,'0'),
          dd = String(future.getDate()).padStart(2,'0');

    const formData = new FormData();
    formData.append('_method',         'PUT');
    formData.append('nama_promosi',    d.nama_promosi || '');
    formData.append('tipe_diskon',     d.tipe_diskon);
    formData.append('nilai_diskon',    d.nilai_diskon);
    formData.append('tanggal_mulai',   new Date().toISOString().split('T')[0]);
    formData.append('tanggal_selesai', `${yy}-${mm}-${dd}`);
    formData.append('semua_produk',    d.semua_produk ? '1' : '0');
    (d.category_ids || []).forEach(cid => formData.append('category_ids[]', cid));

    try {
      const res  = await fetch(`/owner/diskon/${id}`, { method:'POST', headers:{'X-CSRF-TOKEN': csrfToken}, body: formData });
      const data = await res.json();
      if (data.success) { showToast('Diskon berhasil diaktifkan'); location.reload(); }
      else showToast(data.message || 'Gagal', false);
    } catch (e) { showToast('Terjadi kesalahan', false); }
  }

  async function hapusDiskon(id) {
    const confirmed = await showConfirm('Hapus diskon ini secara permanen?', 'danger', 'Ya, Hapus');
    if (!confirmed) return;
    try {
      const res  = await fetch(`/owner/diskon/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN': csrfToken, 'Accept':'application/json'} });
      const data = await res.json();
      if (data.success) { showToast(data.message); location.reload(); }
      else showToast(data.message || 'Gagal', false);
    } catch (e) { showToast('Terjadi kesalahan', false); }
  }

  function showConfirm(message, type = 'success', confirmText = 'Ya') {
    return new Promise(resolve => {
      const modal     = document.getElementById('confirmModal');
      const icon      = document.getElementById('confirmIcon');
      const title     = document.getElementById('confirmTitle');
      const msg       = document.getElementById('confirmMessage');
      const okBtn     = document.getElementById('confirmOkBtn');
      const cancelBtn = document.getElementById('confirmCancelBtn');
      msg.innerText   = message;
      title.innerText = 'Konfirmasi Tindakan';
      okBtn.innerText = confirmText;
      if (type === 'danger') {
        icon.className = 'modal-icon modal-icon-danger';
        icon.innerHTML = `<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 7V13"/><path d="M12 17H12.01"/></svg>`;
        okBtn.className = 'modal-btn modal-btn-confirm-danger';
      } else {
        icon.className = 'modal-icon modal-icon-success';
        icon.innerHTML = `<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 6L9 17l-5-5"/></svg>`;
        okBtn.className = 'modal-btn modal-btn-confirm-success';
      }
      modal.classList.add('show');
      const cleanup  = () => { modal.classList.remove('show'); okBtn.removeEventListener('click', onOk); cancelBtn.removeEventListener('click', onCancel); };
      const onOk     = () => { cleanup(); resolve(true); };
      const onCancel = () => { cleanup(); resolve(false); };
      okBtn.addEventListener('click', onOk);
      cancelBtn.addEventListener('click', onCancel);
    });
  }

  function showToast(msg, success = true) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').innerText = msg;
    toast.style.background = success ? '#1c1c1c' : '#ef4444';
    toast.classList.add('show');
    clearTimeout(toast._t);
    toast._t = setTimeout(() => toast.classList.remove('show'), 2600);
  }

  function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, m => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;' })[m]);
  }

  function escapeHtmlAttr(str) {
    if (!str) return '';
    return str.replace(/'/g, "\\'").replace(/"/g, '&quot;');
  }

  // Sidebar
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const menuBtn = document.getElementById('global-menu-toggle');
  if (menuBtn) menuBtn.addEventListener('click', e => { e.stopPropagation(); sidebar.classList.add('sidebar-open'); overlay.classList.add('show'); document.body.style.overflow='hidden'; });
  if (overlay) overlay.addEventListener('click', () => { sidebar.classList.remove('sidebar-open'); overlay.classList.remove('show'); document.body.style.overflow=''; });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { sidebar.classList.remove('sidebar-open'); overlay.classList.remove('show'); document.body.style.overflow=''; } });

  renderAll();
</script>
</body>
</html>