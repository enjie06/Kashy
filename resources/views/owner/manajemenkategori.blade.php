<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Manajemen Kategori | Kashy</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
  tailwind.config = {
    theme: {
      extend: {
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
        fontFamily: { poppins: ['Poppins','sans-serif'] },
        boxShadow: {
          card: '0 2px 18px 0 rgba(60,40,10,.07)',
          btn:  '0 4px 14px 0 rgba(196,154,108,.35)',
        }
      }
    }
  }
</script>
<style>
  * { box-sizing:border-box; margin:0; padding:0; }
  body { font-family:'Poppins',sans-serif; background:#F5F0EB; }

  #sidebar {
    position:fixed; top:0; left:0; height:100vh; width:280px;
    background:#fff; box-shadow:2px 0 24px rgba(60,40,10,.12);
    z-index:60; transition:transform .3s cubic-bezier(0.2,0.9,0.4,1.1);
    display:flex; flex-direction:column; overflow-y:auto;
    transform:translateX(-100%);
  }
  #sidebar.sidebar-open { transform:translateX(0); }
  #overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,.45); z-index:55; backdrop-filter:blur(3px);
  }
  #overlay.show { display:block; }

  @keyframes fadeUp {
    from { opacity:0; transform:translateY(18px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .fade-up { animation:fadeUp .4s ease both; }
  .d1{animation-delay:.05s} .d2{animation-delay:.10s}
  .d3{animation-delay:.15s} .d4{animation-delay:.20s}
  .d5{animation-delay:.25s}

  .nav-item {
    display:flex; align-items:center; gap:12px; padding:11px 18px;
    border-radius:12px; cursor:pointer; transition:all .15s;
    font-size:14px; font-weight:500; color:#1a1a1a;
    text-decoration:none; width:100%;
  }
  .nav-item:hover { background:#F5F0EB; }
  .nav-item.active { background:#F7EFE5; color:#7B4F2E; font-weight:600; }
  .nav-item.active svg { stroke:#7B4F2E; }

  .cat-img {
    width:100%; height:160px; object-fit:cover;
    display:block;
  }
  .cat-img-placeholder {
    width:100%; height:160px; background:#F5F0EB;
    display:flex; align-items:center; justify-content:center;
  }

  .form-label {
    font-size:11px; font-weight:700; color:#8A7968;
    text-transform:uppercase; letter-spacing:.07em;
    margin-bottom:6px; display:block;
  }
  .form-input {
    width:100%; padding:12px 14px; border:1.5px solid #E0D8CE;
    border-radius:12px; font-size:13px; font-family:'Poppins',sans-serif;
    color:#1a1a1a; background:#FAF6F2; outline:none;
    transition:border-color .2s;
  }
  .form-input:focus { border-color:#C49A6C; background:#fff; }
  .form-input::placeholder { color:#BFB4AC; }

  .img-dropzone {
    width:100%; border:2px dashed #E0D8CE; border-radius:14px;
    padding:28px 20px; text-align:center; cursor:pointer;
    transition:border-color .2s, background .2s; background:#FAF6F2;
    position:relative; overflow:hidden;
  }
  .img-dropzone:hover { border-color:#C49A6C; background:#FDF9F5; }
  .img-dropzone.has-image { border-color:#C49A6C; padding:0; height:180px; }
  .img-dropzone.has-image img { width:100%; height:100%; object-fit:cover; border-radius:12px; display:block; }
  .img-dropzone.has-image .dropzone-overlay {
    position:absolute; inset:0; background:rgba(0,0,0,.35);
    display:flex; align-items:center; justify-content:center;
    border-radius:12px; opacity:0; transition:opacity .2s;
  }
  .img-dropzone.has-image:hover .dropzone-overlay { opacity:1; }

  .modal-fixed {
    position: fixed; top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000;
    display: flex; align-items: center; justify-content: center;
    visibility: hidden; opacity: 0;
    transition: visibility 0.2s, opacity 0.2s;
  }
  .modal-fixed.show { visibility: visible; opacity: 1; }
  .modal-card {
    background: white; max-width: 500px; width: 90%; max-height: 90vh;
    overflow-y: auto; border-radius: 28px; padding: 24px;
    transform: scale(0.95); transition: transform 0.2s;
  }
  .modal-fixed.show .modal-card { transform: scale(1); }

  .modal-confirm {
    position: fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:2000;
    display:flex; align-items:center; justify-content:center;
    visibility:hidden; opacity:0; transition:visibility 0.2s, opacity 0.2s;
  }
  .modal-confirm.show { visibility:visible; opacity:1; }
  .modal-confirm .modal-card { background:white; max-width:380px; width:90%; border-radius:28px; padding:24px; text-align:center; transform:scale(0.95); transition:transform 0.2s; }
  .modal-confirm.show .modal-card { transform:scale(1); }
  .modal-icon { width:64px; height:64px; border-radius:60px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; }
  .modal-icon-danger { background:#FEF2F2; }
  .modal-icon-danger svg { stroke:#D94F4F; }
  .modal-title { font-size:20px; font-weight:800; color:#1a1a1a; margin-bottom:12px; }
  .modal-message { font-size:14px; color:#6B5E52; margin-bottom:24px; line-height:1.5; }
  .modal-buttons { display:flex; gap:12px; }
  .modal-btn { flex:1; padding:12px; border-radius:40px; font-weight:700; font-size:14px; cursor:pointer; transition:all 0.2s; border:none; }
  .modal-btn-cancel { background:#F5F0EB; color:#1a1a1a; }
  .modal-btn-cancel:hover { background:#EDE5DB; transform:scale(0.97); }
  .modal-btn-confirm-danger { background:#D94F4F; color:white; box-shadow:0 4px 10px rgba(217,79,79,0.3); }
  .modal-btn-confirm-danger:hover { background:#b53b3b; transform:scale(0.97); }

  #toast {
    position:fixed; bottom:2rem; left:50%; transform:translateX(-50%) translateY(12px);
    background:#1c1c1c; color:white; font-size:0.875rem; font-weight:500;
    padding:0.75rem 1.25rem; border-radius:9999px; display:flex; align-items:center; gap:0.5rem;
    z-index:9999; opacity:0; transition:opacity 0.25s, transform 0.3s; pointer-events:none;
  }
  #toast.show { opacity:1; transform:translateX(-50%) translateY(0); }

  .log-table { width:100%; border-collapse:collapse; }
  .log-table thead th {
    font-size:11px; font-weight:700; color:#8A7968;
    padding:10px 16px; text-align:left;
    border-bottom:1.5px solid #E0D8CE;
  }
  .log-table tbody tr { border-bottom:1px solid #E0D8CE; }
  .log-table tbody tr:last-child { border-bottom:none; }
  .log-table tbody td {
    padding:12px 16px; font-size:12px; color:#1a1a1a;
  }

  ::-webkit-scrollbar { width:4px; }
  ::-webkit-scrollbar-track { background:transparent; }
  ::-webkit-scrollbar-thumb { background:#C49A6C; border-radius:10px; }
</style>
</head>

@include('owner.components.sidebar')

<body>
<main id="main" class="min-h-screen bg-kashy-cream">
  @include('owner.components.topbar')

  <div class="px-5 md:px-8 py-6 max-w-2xl mx-auto">

    <div class="fade-up d1 mb-5">
      <h1 class="text-2xl md:text-3xl font-extrabold text-kashy-dark leading-tight">Manajemen Kategori</h1>
      <p class="text-xs text-kashy-muted mt-1">Susun dan tata katalog produk koleksi Anda dengan tepat dan elegan.</p>
    </div>

    <div class="fade-up d2 mb-5">
      <button onclick="openModal()"
        class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl font-bold text-sm tracking-widest text-white uppercase transition-all duration-200 hover:opacity-90 active:scale-[.98] shadow-btn"
        style="background:#C49A6C; box-shadow:0 4px 14px 0 rgba(196,154,108,.35);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Kategori
      </button>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex flex-col gap-4 fade-up d3" id="kategoriList"></div>

    <div class="fade-up d5 mt-8 mb-6">
      <h2 class="text-base font-bold text-kashy-dark mb-3">Log Aktivitas Terbaru</h2>
      <div class="bg-white rounded-2xl overflow-hidden shadow-card">
        <table class="log-table">
          <thead>
            <tr><th>Aktivitas</th><th>Kategori</th><th>Waktu</th></tr>
          </thead>
          <tbody id="logTableBody">
            <tr><td colspan="3" class="text-center text-gray-400 py-4">Belum ada aktivitas</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</main>

<!-- MODAL TAMBAH/EDIT KATEGORI -->
<div id="modalKategori" class="modal-fixed">
  <div class="modal-card">
    <h2 id="modalTitle" class="text-xl font-bold text-kashy-dark mb-5">Tambah Kategori</h2>
    <form id="kategoriForm" method="POST" action="/owner/kategori" enctype="multipart/form-data">
      @csrf
      <input type="hidden" id="editId" name="id" value="">
      <input type="hidden" id="methodField" name="_method" value="POST">
      
      <!-- UPLOAD GAMBAR -->
      <div class="mb-4">
        <label class="form-label">Gambar Kategori <span class="normal-case font-normal">(opsional)</span></label>
        <div class="img-dropzone" id="modalImgDropzone" onclick="document.getElementById('modalImgInput').click()">
          <input type="file" id="modalImgInput" name="gambar" accept="image/*" class="hidden" onchange="previewModalImage(this)"/>
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C49A6C" stroke-width="1.6" class="mx-auto mb-2">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
          </svg>
          <p class="text-sm font-semibold text-kashy-muted">Pilih atau seret gambar</p>
          <p class="text-xs text-kashy-muted mt-1">JPG, PNG — maks 5MB</p>
        </div>
      </div>
      
      <!-- NAMA KATEGORI -->
      <div class="mb-4">
        <label class="form-label">Nama Kategori <span class="required-star">*</span></label>
        <input type="text" id="inputNama" name="nama_kategori" class="form-input" placeholder="cth: Kemeja Batik" required>
      </div>
      
      <div class="flex gap-3">
        <button type="button" onclick="closeModal()" class="flex-1 py-4 rounded-2xl font-bold text-sm text-kashy-muted border-2 border-kashy-border bg-white hover:bg-kashy-cream transition-all">Batal</button>
        <button type="submit" class="flex-1 py-4 rounded-2xl font-bold text-sm text-white transition-all hover:opacity-90" style="background:#C49A6C;">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div id="confirmModal" class="modal-confirm">
  <div class="modal-card">
    <div class="modal-icon modal-icon-danger">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <path d="M12 7V13"/>
        <path d="M12 17H12.01"/>
      </svg>
    </div>
    <div class="modal-title">Hapus Kategori</div>
    <div class="modal-message" id="confirmMessage"></div>
    <div class="modal-buttons">
      <button id="modalCancelBtn" class="modal-btn modal-btn-cancel">Batal</button>
      <button id="modalConfirmBtn" class="modal-btn modal-btn-confirm-danger">Ya, Hapus</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C49A6C" stroke-width="2.5">
    <polyline points="20 6 9 17 4 12"/>
  </svg>
  <span id="toastMsg">Berhasil!</span>
</div>

<script>
  // Sidebar
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const menuBtn = document.getElementById('global-menu-toggle');
  function openSidebar()  { sidebar.classList.add('sidebar-open'); overlay.classList.add('show'); document.body.style.overflow='hidden'; }
  function closeSidebar() { sidebar.classList.remove('sidebar-open'); overlay.classList.remove('show'); document.body.style.overflow=''; }
  if (menuBtn) menuBtn.addEventListener('click', e => { e.stopPropagation(); openSidebar(); });
  if (overlay) overlay.addEventListener('click', closeSidebar);
  document.addEventListener('keydown', e => { if (e.key==='Escape') { closeSidebar(); closeModal(); closeConfirmModal(); } });

  // Data dari DATABASE (bukan dummy!)
  let categories = @json($categories);
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

  function renderCategories() {
    const container = document.getElementById('kategoriList');
    if (!container) return;
    container.innerHTML = '';
    
    if (!categories || categories.length === 0) {
      container.innerHTML = '<div class="text-center py-8 text-gray-500">Belum ada kategori. Silakan tambah kategori baru.</div>';
      return;
    }
    
    categories.forEach(cat => {
      const card = document.createElement('div');
      card.className = 'bg-white rounded-2xl overflow-hidden shadow-card';
      card.setAttribute('data-id', cat.id);
      
      // Ambil gambar dari produk terbaru di kategori ini
      const latestProduct = cat.products && cat.products.length > 0 ? cat.products[0] : null;
      const imageUrl = latestProduct && latestProduct.gambar 
    ? '/images/products/' + latestProduct.gambar.split('/').pop() 
    : null;
      const imageHtml = imageUrl 
        ? `<img src="${imageUrl}" class="cat-img" alt="${escapeHtml(cat.nama_kategori)}"/>` 
        : `<div class="cat-img-placeholder"><svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#E0D8CE" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m3 9 4-4 4 4 4-4 4 4"/><path d="M3 14h18"/></svg></div>`;
      
      card.innerHTML = `
        ${imageHtml}
        <div class="flex items-center justify-between px-4 py-3">
          <div>
            <p class="text-sm font-bold text-kashy-dark">${escapeHtml(cat.nama_kategori)}</p>
            <p class="text-xs text-kashy-muted">${cat.products_count || 0} produk</p>
          </div>
          <div class="flex items-center gap-3">
            <button onclick="editKategori(${cat.id})" class="text-kashy-muted hover:text-kashy-dark transition-colors">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <button onclick="hapusKategori(${cat.id})" class="text-red-500 hover:text-red-700 transition-colors">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
            </button>
          </div>
        </div>
      `;
      container.appendChild(card);
    });
  }

  function escapeHtml(str) { 
    if (!str) return ''; 
    return str.replace(/[&<>]/g, function(m){ 
      if(m==='&') return '&amp;'; 
      if(m==='<') return '&lt;'; 
      if(m==='>') return '&gt;'; 
      return m;
    }); 
  }

  let currentEditId = null;
  let currentImageData = null;

  function openModal(editId = null) {
    currentEditId = editId;
    const modal = document.getElementById('modalKategori');
    const form = document.getElementById('kategoriForm');
    
    if (editId) {
        const cat = categories.find(c => c.id === editId);
        if (cat) {
            document.getElementById('modalTitle').innerText = 'Edit Kategori';
            document.getElementById('editId').value = cat.id;
            document.getElementById('inputNama').value = cat.nama_kategori;
            form.action = `/owner/kategori/${editId}`;
            document.getElementById('methodField').value = 'PUT';
        }
    } else {
        document.getElementById('modalTitle').innerText = 'Tambah Kategori';
        document.getElementById('editId').value = '';
        document.getElementById('inputNama').value = '';
        form.action = '/owner/kategori';
        document.getElementById('methodField').value = 'POST';
    }
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

// Hapus fungsi simpanKategori() yang lama, ganti dengan submit form biasa
// Form akan submit secara normal, bukan AJAX

  function editKategori(id) { openModal(id); }
  
  function closeModal() {
    document.getElementById('modalKategori').classList.remove('show');
    document.body.style.overflow = '';
    currentEditId = null;
    currentImageData = null;
  }

  function previewModalImage(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
      currentImageData = e.target.result;
      const dz = document.getElementById('modalImgDropzone');
      dz.innerHTML = `<img src="${currentImageData}" alt="preview"/><div class="dropzone-overlay"><span class="text-white text-xs font-semibold bg-black/50 px-3 py-1.5 rounded-lg">Ganti Gambar</span></div><input type="file" id="modalImgInput" accept="image/*" class="hidden" onchange="previewModalImage(this)"/>`;
      dz.classList.add('has-image');
      dz.onclick = () => document.getElementById('modalImgInput').click();
    };
    reader.readAsDataURL(input.files[0]);
  }

  function setModalPreview(url) {
    const dz = document.getElementById('modalImgDropzone');
    dz.innerHTML = `<img src="${url}" alt="preview"/><div class="dropzone-overlay"><span class="text-white text-xs font-semibold bg-black/50 px-3 py-1.5 rounded-lg">Ganti Gambar</span></div><input type="file" id="modalImgInput" accept="image/*" class="hidden" onchange="previewModalImage(this)"/>`;
    dz.classList.add('has-image');
    dz.onclick = () => document.getElementById('modalImgInput').click();
    currentImageData = url;
  }

  function resetModalDropzone() {
    const dz = document.getElementById('modalImgDropzone');
    dz.classList.remove('has-image');
    dz.innerHTML = `<input type="file" id="modalImgInput" accept="image/*" class="hidden" onchange="previewModalImage(this)"/><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C49A6C" stroke-width="1.6" class="mx-auto mb-2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg><p class="text-sm font-semibold text-kashy-muted">Pilih atau seret gambar</p><p class="text-xs text-kashy-muted mt-1">JPG, PNG — maks 5MB</p>`;
    dz.onclick = () => document.getElementById('modalImgInput').click();
    currentImageData = null;
  }

  // function simpanKategori() {
  //   const nama = document.getElementById('inputNama').value.trim();
  //   if (!nama) {
  //     showToast('Nama kategori wajib diisi', false);
  //     document.getElementById('inputNama').focus();
  //     return;
  //   }

  //   const formData = new FormData();
  //   formData.append('_token', csrfToken);
  //   formData.append('nama_kategori', nama);
    
  //   const fileInput = document.getElementById('modalImgInput');
  //   if (fileInput.files && fileInput.files[0]) {
  //     formData.append('gambar', fileInput.files[0]);
  //   }

  //   const url = currentEditId ? `/owner/kategori/${currentEditId}` : '/owner/kategori';
  //   if (currentEditId) formData.append('_method', 'PUT');

  //   fetch(url, {
  //     method: 'POST',
  //     headers: {
  //       'X-CSRF-TOKEN': csrfToken
  //     },
  //     body: formData
  //   })
  //   .then(response => response.json())
  //   .then(data => {
  //     if (data.success) {
  //       showToast(data.message, true);
  //       location.reload();
  //     } else {
  //       showToast(data.message || 'Gagal menyimpan kategori', false);
  //     }
  //   })
  //   .catch(error => {
  //     showToast('Terjadi kesalahan', false);
  //   });

  //   closeModal();
  // }

  let pendingDeleteId = null;
  
  function hapusKategori(id) {
    pendingDeleteId = id;
    const cat = categories.find(c => c.id === id);
    document.getElementById('confirmMessage').innerHTML = `Apakah Anda yakin ingin menghapus kategori <strong>${escapeHtml(cat.nama_kategori)}</strong>?`;
    document.getElementById('confirmModal').classList.add('show');
  }

  function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('show');
    pendingDeleteId = null;
  }

  function confirmDelete() {
    if (!pendingDeleteId) return;
    
    fetch(`/owner/kategori/${pendingDeleteId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast(data.message, true);
        location.reload();
      } else {
        showToast(data.message || 'Gagal menghapus kategori', false);
      }
    })
    .catch(error => {
      showToast('Terjadi kesalahan', false);
    });
    
    closeConfirmModal();
  }

  let toastTimer;
  function showToast(msg, success = true) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').innerText = msg;
    toast.style.background = success ? '#1c1c1c' : '#ef4444';
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 2600);
  }

  renderCategories();
  document.getElementById('modalCancelBtn').addEventListener('click', closeConfirmModal);
  document.getElementById('modalConfirmBtn').addEventListener('click', confirmDelete);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeConfirmModal(); });
</script>
</body>
</html>