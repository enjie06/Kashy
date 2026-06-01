@php
  $tanggal = $tanggal ?? now()->toDateString();
  $stockOpnames = $stockOpnames ?? collect();

  try {
      $tanggalLabel = \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y');
  } catch (\Exception $e) {
      $tanggalLabel = now()->locale('id')->translatedFormat('l, d F Y');
  }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Kashy – Stok Opname</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { poppins: ['Poppins', 'sans-serif'] },
          colors: {
            kashy: {
              dark: '#1a1a1a',
              brown: '#C49A6C',
              'brown-deep': '#7B4F2E',
              cream: '#F5F0EB',
              'cream-dark': '#EDE5DB',
              muted: '#8A7968',
              border: '#E0D8CE',
              red: '#D94F4F',
              green: '#3A9E6F',
            }
          },
          boxShadow: {
            card: '0 2px 18px 0 rgba(60,40,10,.07)',
            btn: '0 4px 14px 0 rgba(196,154,108,.35)',
          }
        }
      }
    }
  </script>
  <style>
    * { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
    body { background: #F5F0EB; margin: 0; }

    /* Sidebar (sama seperti dashboard) */
    #sidebar {
      position: fixed; top: 0; left: 0; height: 100vh; width: 280px;
      background: #fff; box-shadow: 2px 0 24px rgba(60,40,10,.12);
      z-index: 60; transition: transform .3s cubic-bezier(0.2,0.9,0.4,1.1);
      display: flex; flex-direction: column; overflow-y: auto;
      transform: translateX(-100%);
    }
    #sidebar.sidebar-open { transform: translateX(0); }
    #overlay {
      display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
      z-index: 55; backdrop-filter: blur(3px);
    }
    #overlay.show { display: block; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp .4s ease both; }
    .d1 { animation-delay: .05s; }
    .d2 { animation-delay: .10s; }
    .d3 { animation-delay: .15s; }
    .d4 { animation-delay: .20s; }
    .d5 { animation-delay: .25s; }

    /* Nav items */
    .nav-item {
      display: flex; align-items: center; gap: 12px; padding: 11px 18px;
      border-radius: 12px; cursor: pointer; transition: all .15s;
      font-size: 14px; font-weight: 500; color: #1a1a1a;
      text-decoration: none; width: 100%;
    }
    .nav-item:hover { background: #F5F0EB; }
    .nav-item.active { background: #F7EFE5; color: #7B4F2E; font-weight: 600; }
    .nav-item.active svg { stroke: #7B4F2E; }

    /* Shimmer bar */
    @keyframes shimmer {
      0%   { background-position: -400px 0; }
      100% { background-position: 400px 0; }
    }
    .shimmer-bar {
      background: linear-gradient(90deg, #C49A6C, #E5B18A, #F0D7C7, #E5B18A, #C49A6C);
      background-size: 200%; animation: shimmer 4s linear infinite;
    }

    /* Form inputs */
    .form-input {
      width: 100%; padding: 12px 14px; border: 1.5px solid #E0D8CE;
      border-radius: 12px; font-size: 13px; font-family: 'Poppins', sans-serif;
      color: #1a1a1a; background: #fff; outline: none;
      transition: border-color .2s, box-shadow .2s;
    }
    .form-input:focus { border-color: #C49A6C; box-shadow: 0 0 0 3px rgba(196,154,108,.12); }
    .form-input::placeholder { color: #BFB4AC; }
    .form-select {
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238A7968' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
      background-repeat: no-repeat; background-position: right 14px center;
      padding-right: 36px; cursor: pointer;
    }
    .form-label {
      font-size: 11px; font-weight: 700; color: #8A7968;
      text-transform: uppercase; letter-spacing: .07em; margin-bottom: 6px; display: block;
    }

    /* Modal */
    #modal-overlay {
      opacity: 0; pointer-events: none;
      transition: opacity .25s ease;
    }
    #modal-overlay.open { opacity: 1; pointer-events: all; }
    #modal-dialog {
      transform: scale(.93) translateY(16px);
      transition: transform .32s cubic-bezier(0.34,1.56,.64,1);
    }
    #modal-overlay.open #modal-dialog { transform: scale(1) translateY(0); }

    /* Toast */
    #toast {
      opacity: 0; transform: translateX(-50%) translateY(12px);
      transition: opacity .25s ease, transform .3s cubic-bezier(0.34,1.56,.64,1);
      pointer-events: none;
    }
    #toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

    /* Entry card */
    .entry-card { transition: all .18s; }
    .entry-card:hover { background: #FDFAF7; }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: #C49A6C; border-radius: 10px; }
  </style>
</head>

@include('owner.components.sidebar')

<body>

<main id="main" class="min-h-screen bg-kashy-cream">
  @include('owner.components.topbar')

  <!-- KONTEN UTAMA: ukuran dan padding DISAMAKAN dengan Laporan Keuangan (max-w-2xl, px-5 md:px-8, py-6) -->
  <div class="px-5 md:px-8 py-6 max-w-2xl mx-auto">

    <div class="fade-up d1 mb-6">
      <h1 class="text-3xl font-extrabold text-kashy-dark">Stok Opname</h1>
      <p class="text-sm text-kashy-muted mt-1">Pilih tanggal untuk melihat atau mencatat data stok opname.</p>
    </div>

    @if (session('success'))
      <div class="fade-up d1 mb-4 rounded-2xl border border-green-100 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="fade-up d1 mb-4 rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-600">
        <p class="font-bold mb-1">Data belum bisa disimpan:</p>
        <ul class="list-disc pl-5 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Tombol Tambah Stok Opname (card style) -->
    <div class="fade-up d2 mb-6">
      <button type="button" onclick="openModal()"
        class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl font-bold text-sm tracking-widest text-white uppercase transition-all duration-200 hover:opacity-90 active:scale-[.98] shadow-btn"
        style="background:#C49A6C; box-shadow:0 4px 14px 0 rgba(196,154,108,.35);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="12" y1="5" x2="12" y2="19"/>
          <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Stok Opname
      </button>
    </div>

    <!-- Pilih Tanggal (card putih) -->
    <div class="bg-white rounded-2xl p-5 shadow-card mb-6">
      <label class="text-[11px] font-bold uppercase tracking-wider text-kashy-muted mb-1.5 block">
        Pilih Tanggal
      </label>
      <input
        type="date"
        id="simpleDate"
        value="{{ $tanggal }}"
        class="w-full border-[1.5px] border-kashy-border rounded-xl px-3 py-3 text-sm text-kashy-dark outline-none transition-colors focus:border-kashy-brown"
        style="font-family:'Poppins',sans-serif;"
        onchange="window.location.href='{{ route('stokopname') }}?tanggal=' + this.value"
      >
    </div>

    <!-- Area Entri Stok Opname -->
    <div id="entrySection" class="fade-up d4 bg-white rounded-2xl shadow-card overflow-hidden mb-6">
      <div class="shimmer-bar h-1"></div>
      <div class="px-5 py-4 border-b border-kashy-border flex items-center justify-between">
        <div>
          <p class="font-bold text-kashy-dark text-base" id="entrySectionTitle">Entri Stok Opname</p>
          <p class="text-[11px] text-kashy-muted" id="entrySectionDate">{{ $tanggalLabel }}</p>
        </div>
        <span id="entryCount" class="px-3 py-1 rounded-full bg-kashy-cream text-kashy-brown text-xs font-bold border border-kashy-border">
          {{ $stockOpnames->count() }} entri
        </span>
      </div>

      <div id="entryList" class="divide-y divide-kashy-border/60">
        @forelse ($stockOpnames as $item)
          @php
            $diffColor = 'text-kashy-muted';
            $badgeClass = 'bg-green-50 text-green-700';
            $badgeText = 'Sesuai';

            if ($item->status === 'short') {
                $diffColor = 'text-red-500';
                $badgeClass = 'bg-red-50 text-red-600';
                $badgeText = 'Selisih';
            } elseif ($item->status === 'excess') {
                $diffColor = 'text-blue-600';
                $badgeClass = 'bg-blue-50 text-blue-600';
                $badgeText = 'Lebih';
            } elseif ($item->status === 'match') {
                $diffColor = 'text-green-600';
            }
          @endphp

          <div class="entry-card flex items-start gap-4 px-5 py-4 transition-colors">
            <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center" style="background:#F7EFE5;">
              <svg class="w-5 h-5" fill="none" stroke="#C49A6C" viewBox="0 0 24 24" stroke-width="1.8">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
              </svg>
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 flex-wrap mb-0.5">
                <p class="text-sm font-semibold text-kashy-dark truncate">{{ $item->nama_produk }}</p>
                <span class="px-2.5 py-0.5 rounded-full {{ $badgeClass }} text-[10px] font-bold">{{ $badgeText }}</span>
              </div>

              <p class="text-[11px] text-kashy-muted mb-1">
                {{ $item->kategori }} · Rp {{ number_format($item->harga, 0, ',', '.') }}
              </p>

              <div class="flex gap-4 text-[11px] flex-wrap">
                <span class="text-kashy-muted">Sistem: <b class="text-kashy-dark">{{ $item->stok_sistem }}</b></span>
                <span class="text-kashy-muted">Fisik: <b class="text-kashy-dark">{{ $item->stok_fisik }}</b></span>
                <span class="text-kashy-muted">Selisih:
                  <b class="{{ $diffColor }}">{{ $item->selisih > 0 ? '+' . $item->selisih : $item->selisih }}</b>
                </span>
              </div>

              @if($item->catatan)
                <p class="text-[11px] text-kashy-muted mt-1 italic">"{{ $item->catatan }}"</p>
              @endif
            </div>

            <form method="POST" action="{{ route('stok-opname.destroy', $item->id) }}" class="flex-shrink-0 mt-0.5">
              @csrf
              @method('DELETE')
              <button type="submit" onclick="return confirm('Hapus entri ini?')" class="text-kashy-border hover:text-red-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6l-1 14H6L5 6"/>
                  <path d="M10 11v6M14 11v6"/>
                  <path d="M9 6V4h6v2"/>
                </svg>
              </button>
            </form>
          </div>
        @empty
          <div id="entryEmpty" class="flex flex-col items-center justify-center py-10 px-6 text-center">
            <svg class="w-10 h-10 text-kashy-border mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
              <rect x="9" y="3" width="6" height="4" rx="1"/>
              <line x1="9" y1="12" x2="15" y2="12"/>
              <line x1="9" y1="16" x2="12" y2="16"/>
            </svg>
            <p class="text-kashy-muted text-sm font-medium">Belum ada entri pada tanggal ini</p>
          </div>
        @endforelse
      </div>
    </div>

  </div>
</main>

<!-- MODAL – ukuran responsif, sama dengan gaya card laporan keuangan -->
<div id="modal-overlay" class="fixed inset-0 z-[70] bg-black/50 backdrop-blur-sm flex items-end sm:items-center justify-center p-4">
  <div id="modal-dialog" class="w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
    <form method="POST" action="{{ route('stok-opname.store') }}">
      @csrf

      <div class="shimmer-bar h-1"></div>
      <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-kashy-border">
        <div>
          <h2 class="text-lg font-bold text-kashy-dark">Tambah Stok Opname</h2>
          <p class="text-[11px] text-kashy-muted mt-0.5" id="modalDateLabel">{{ $tanggalLabel }}</p>
        </div>
        <button type="button" onclick="closeModal()" class="w-9 h-9 rounded-xl border border-kashy-border flex items-center justify-center text-kashy-muted hover:bg-kashy-cream hover:border-kashy-brown transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      <div class="px-6 py-5 space-y-4">
        <div>
          <label class="form-label">Tanggal Opname</label>
          <input type="date" id="formDate" name="tanggal" value="{{ old('tanggal', $tanggal) }}" class="form-input" required>
        </div>

        <div>
          <label class="form-label">Nama Produk</label>
          <input type="text" id="formProduct" name="nama_produk" value="{{ old('nama_produk') }}" class="form-input" placeholder="Cari atau ketik nama produk..." required>
        </div>

        <div>
          <label class="form-label">Kategori</label>
          <select id="formCategory" name="kategori" class="form-input form-select" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
              <option value="{{ $category->nama_kategori }}" @selected(old('kategori') === $category->nama_kategori)>
                {{ $category->nama_kategori }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label">Harga Barang</label>
          <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-kashy-muted text-sm font-medium">Rp</span>
            <input type="text" id="formHarga" name="harga" value="{{ old('harga') }}" class="form-input" style="padding-left:42px;" placeholder="0" oninput="formatRupiah(this)">
          </div>
        </div>

        <div class="grid grid-cols-3 gap-3">
          <div>
            <label class="form-label">Stok Sistem</label>
            <input type="number" id="formStokSistem" name="stok_sistem" value="{{ old('stok_sistem') }}" class="form-input" placeholder="0" min="0" oninput="calcSelisih()" required>
          </div>
          <div>
            <label class="form-label">Stok Fisik (Aktual)</label>
            <input type="number" id="formStokFisik" name="stok_fisik" value="{{ old('stok_fisik') }}" class="form-input" placeholder="0" min="0" oninput="calcSelisih()" required>
          </div>
          <div>
            <label class="form-label">Selisih</label>
            <div class="relative">
              <input type="text" id="formSelisih" class="form-input" placeholder="—" readonly style="background:#F5F0EB; cursor:default;">
              <span id="selisihIcon" class="absolute right-3 top-1/2 -translate-y-1/2 text-lg"></span>
            </div>
          </div>
        </div>

        <div id="statusPreview" class="hidden">
          <label class="form-label">Status</label>
          <div id="statusBadge" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold"></div>
        </div>

        <div>
          <label class="form-label">Deskripsi / Catatan</label>
          <textarea id="formDesc" name="catatan" rows="3" class="form-input resize-none" placeholder="Tambahkan catatan mengenai kondisi stok, penyebab selisih, dll...">{{ old('catatan') }}</textarea>
        </div>
      </div>

      <div class="px-6 pb-6 flex flex-col gap-3">
        <button type="submit"
          class="w-full py-4 rounded-2xl font-bold text-white text-sm tracking-wide transition-all duration-200 hover:opacity-90 active:scale-[.98]"
          style="background:#C49A6C; box-shadow:0 4px 14px 0 rgba(196,154,108,.35);">
          Simpan Stok Opname
        </button>
        <button type="button" onclick="closeModal()"
          class="w-full py-4 rounded-2xl font-bold text-kashy-dark text-sm tracking-wide border-2 border-kashy-border transition-all duration-200 hover:bg-kashy-cream active:scale-[.98] bg-white">
          Batal
        </button>
      </div>
    </form>
  </div>
</div>

<div id="toast" class="fixed bottom-8 left-1/2 z-[80] text-white text-sm font-medium px-5 py-3 rounded-full shadow-xl flex items-center gap-2">
  <span id="toastMsg"></span>
</div>
<div id="overlay" onclick="closeSidebar()"></div>

<script>
  // ── Sidebar ──
  const sidebar = document.getElementById('sidebar');
  const overlayEl = document.getElementById('overlay');
  const menuBtn = document.getElementById('global-menu-toggle');

  function openSidebar()  { sidebar.classList.add('sidebar-open'); overlayEl.classList.add('show'); document.body.style.overflow='hidden'; }
  function closeSidebar() { sidebar.classList.remove('sidebar-open'); overlayEl.classList.remove('show'); document.body.style.overflow=''; }
  function toggleSidebar(){ sidebar.classList.contains('sidebar-open') ? closeSidebar() : openSidebar(); }

  if (menuBtn) menuBtn.addEventListener('click', e => { e.stopPropagation(); toggleSidebar(); });
  document.addEventListener('keydown', e => {
    if(e.key === 'Escape') {
      closeSidebar();
      closeModal();
    }
  });

  // ── Fungsi Stok Opname ──
  const DAYS_ID = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  const MONTHS_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  const selectedDate = @json($tanggal);

  function formatTanggalIndonesia(dateVal) {
    const d = new Date(dateVal + 'T00:00:00');
    if (isNaN(d.getTime())) return '';
    return `${DAYS_ID[d.getDay()]}, ${d.getDate()} ${MONTHS_ID[d.getMonth()]} ${d.getFullYear()}`;
  }

  function openModal() {
    const modalOverlay = document.getElementById('modal-overlay');
    modalOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';

    const formDate = document.getElementById('formDate');
    const activeDate = document.getElementById('simpleDate').value || selectedDate;
    formDate.value = formDate.value || activeDate;

    document.getElementById('modalDateLabel').textContent = formatTanggalIndonesia(formDate.value);

    formDate.onchange = function() {
      document.getElementById('modalDateLabel').textContent = formatTanggalIndonesia(this.value);
    };

    calcSelisih();
  }

  function closeModal() {
    document.getElementById('modal-overlay').classList.remove('open');
    document.body.style.overflow = '';
  }

  function calcSelisih() {
    const sistem  = parseInt(document.getElementById('formStokSistem').value) || 0;
    const fisikEl = document.getElementById('formStokFisik');
    const selEl   = document.getElementById('formSelisih');
    const iconEl  = document.getElementById('selisihIcon');
    const prevEl  = document.getElementById('statusPreview');
    const badgeEl = document.getElementById('statusBadge');
    const fisik = parseInt(fisikEl.value);

    if(isNaN(fisik) || fisikEl.value.trim() === ''){
      selEl.value = '—';
      selEl.style.color = '';
      iconEl.textContent = '';
      prevEl.classList.add('hidden');
      return;
    }

    const diff = fisik - sistem;
    selEl.value = diff > 0 ? `+${diff}` : diff === 0 ? '0' : diff;
    prevEl.classList.remove('hidden');

    if(diff === 0){
      selEl.style.color='#3A9E6F';
      iconEl.textContent='✓';
      iconEl.style.color='#3A9E6F';
      badgeEl.className='inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-green-50 text-green-700';
      badgeEl.textContent='Sesuai';
    } else if(diff < 0){
      selEl.style.color='#D94F4F';
      iconEl.textContent='↓';
      iconEl.style.color='#D94F4F';
      badgeEl.className='inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-red-50 text-red-600';
      badgeEl.textContent='Selisih (Kurang)';
    } else {
      selEl.style.color='#3B82F6';
      iconEl.textContent='↑';
      iconEl.style.color='#3B82F6';
      badgeEl.className='inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-600';
      badgeEl.textContent='Lebih';
    }
  }

  function formatRupiah(el) {
    let val = el.value.replace(/[^0-9]/g,'');
    el.value = val ? parseInt(val).toLocaleString('id-ID') : '';
  }

  function showToast(msg, success=true) {
    const t = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    t.style.background = success ? '#1c1c1c' : '#ef4444';
    t.classList.add('show');
    clearTimeout(t._t);
    t._t = setTimeout(() => t.classList.remove('show'), 2600);
  }

  @if (session('success'))
    showToast(@json(session('success')));
  @endif

  @if ($errors->any())
    openModal();
    calcSelisih();
  @endif
</script>
</body>
</html>
