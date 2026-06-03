<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Riwayat Transaksi – Kashy</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
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
          display: ['Poppins', 'sans-serif'],
          body:    ['Poppins', 'sans-serif'],
        },
      }
    }
  }
</script>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Poppins',sans-serif; background:#F5F0EB; }

  /* ── Search ── */
  .search-wrapper { position:relative; }
  .search-wrapper .search-icon {
    position:absolute; left:14px; top:50%;
    transform:translateY(-50%); pointer-events:none;
  }
  .search-input {
    width:100%; padding:13px 14px 13px 42px;
    background:#fff; border:1.5px solid #EAE0D6;
    border-radius:16px; font-size:14px; font-family:'Poppins',sans-serif;
    color:#1C1C1C; outline:none; transition:border-color .2s;
  }
  .search-input::placeholder { color:#BFB4AC; }
  .search-input:focus { border-color:#C8966C; }

  /* ── Quick filter chips ── */
  .qf-btn {
    padding:9px 18px; border-radius:30px; font-size:13px;
    font-weight:500; border:1.5px solid #EAE0D6;
    background:#fff; color:#1C1C1C; cursor:pointer;
    transition:all .2s; white-space:nowrap;
    font-family:'Poppins',sans-serif; flex-shrink:0;
  }
  .qf-btn.active {
    border-color:#C8966C; background:#FDF1E8; color:#C8966C; font-weight:600;
  }

  /* ── Date label ── */
  .date-label {
    font-size:11px; font-weight:600; color:#9C8B7E;
    letter-spacing:.07em; text-transform:uppercase;
  }

  /* ── Trx card ── */
  .trx-card {
    background:#fff; border-radius:16px;
    border:1.5px solid #EAE0D6; overflow:hidden; transition:box-shadow .2s;
  }
  .trx-card:hover { box-shadow:0 4px 20px rgba(200,150,108,.13); }
  .trx-card-header { padding:16px; cursor:pointer; user-select:none; }
  .trx-id   { font-size:11px; font-weight:600; color:#9C8B7E; letter-spacing:.04em; }
  .trx-name { font-size:15px; font-weight:700; color:#1C1C1C; margin-top:2px; }
  .trx-time { font-size:12px; color:#BFB4AC; display:flex; align-items:center; gap:4px; margin-top:4px; }
  .trx-amount { font-size:16px; font-weight:800; color:#1C1C1C; text-align:right; line-height:1.2; }

  .badge { display:inline-block; font-size:10px; font-weight:700; letter-spacing:.04em; padding:3px 10px; border-radius:20px; margin-top:5px; }
  .badge-success  { background:#E8F5E9; color:#2E7D32; }
  .badge-cancel   { background:#FDECEA; color:#C62828; }
  .badge-pending  { background:#FFF8E1; color:#F57F17; }

  /* ── Accordion ── */
  .trx-detail { max-height:0; overflow:hidden; transition:max-height .35s ease; }
  .trx-detail.open { max-height:400px; }
  .trx-detail-inner { padding:14px 16px 16px; border-top:1.5px dashed #EAE0D6; }
  .detail-section-label { font-size:10px; font-weight:700; color:#9C8B7E; text-transform:uppercase; letter-spacing:.08em; margin-bottom:10px; }
  .detail-row { display:flex; justify-content:space-between; align-items:center; font-size:13px; color:#1C1C1C; padding:4px 0; }
  .detail-row .item-name { color:#9C8B7E; }
  .detail-footer { display:flex; align-items:center; justify-content:space-between; margin-top:14px; padding-top:12px; border-top:1.5px dashed #EAE0D6; gap:8px; }
  .metode-tag { display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#9C8B7E; background:#F5F0EC; padding:7px 13px; border-radius:10px; flex-shrink:0; }
  .cetak-btn { display:flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#C8966C; background:#FDF1E8; border:none; padding:8px 14px; border-radius:10px; cursor:pointer; transition:background .2s; font-family:'Poppins',sans-serif; flex-shrink:0; }
  .cetak-btn:hover { background:#F5E3D0; }

  /* ── Filter Modal ── */
  .filter-overlay { display:none; position:fixed; inset:0; background:rgba(28,28,28,.5); z-index:200; align-items:center; justify-content:center; padding:20px; }
  .filter-overlay.show { display:flex; }
  .filter-modal { width:100%; max-width:420px; background:#fff; border-radius:20px; padding:24px 20px 20px; animation:popIn .25s cubic-bezier(.2,.9,.4,1.1); max-height:90vh; overflow-y:auto; }
  @keyframes popIn { from { transform:scale(.94); opacity:0; } to { transform:scale(1); opacity:1; } }
  .filter-modal-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
  .filter-modal-header h3 { font-size:16px; font-weight:700; color:#1C1C1C; }
  .reset-btn { font-size:12px; font-weight:600; color:#C8966C; background:none; border:none; cursor:pointer; font-family:'Poppins',sans-serif; padding:4px 8px; border-radius:8px; transition:background .15s; }
  .reset-btn:hover { background:#FDF1E8; }
  .filter-group { margin-bottom:18px; }
  .filter-group > p { font-size:10px; font-weight:700; color:#9C8B7E; text-transform:uppercase; letter-spacing:.08em; margin-bottom:10px; }
  .filter-chips-row { display:flex; flex-wrap:wrap; gap:8px; }
  .fchip { padding:8px 16px; border-radius:30px; font-size:13px; font-weight:500; border:1.5px solid #EAE0D6; background:#fff; color:#1C1C1C; cursor:pointer; transition:all .15s; font-family:'Poppins',sans-serif; user-select:none; }
  .fchip.selected { background:#FDF1E8; border-color:#C8966C; color:#C8966C; font-weight:600; }
  .filter-modal-footer { display:flex; gap:10px; margin-top:4px; }
  .apply-btn { flex:1; padding:13px; background:#C8966C; color:#fff; font-size:14px; font-weight:700; border:none; border-radius:12px; cursor:pointer; transition:background .2s; font-family:'Poppins',sans-serif; }
  .apply-btn:hover { background:#b5844f; }

  /* ── Scrollbar hide ── */
  .scrollbar-hide::-webkit-scrollbar { display:none; }
  .scrollbar-hide { -ms-overflow-style:none; scrollbar-width:none; }

  /* ── Fade animation ── */
  @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
  .fade-up { animation:fadeUp .4s cubic-bezier(.2,.9,.4,1.1) both; }
  .delay-1 { animation-delay:.05s; }
  .delay-2 { animation-delay:.12s; }
  .delay-3 { animation-delay:.20s; }

  /* navbar active */
  .bn-item.active .bn-icon { background:#F0D7C7; }
  .bn-item.active .bn-icon svg { stroke:#C8966C; }
  .bn-item.active .bn-label { color:#C8966C; font-weight:600; }
</style>
</head>

@include('kasir.components.navbar')

<body class="bg-bg min-h-screen flex flex-col">

<!-- TOPBAR — fixed, tidak ikut scroll -->
<header class="sticky top-0 z-50 bg-gray-900 shadow-sm h-12 px-4 flex items-center justify-between">
    <div class="w-6"></div>
    <div class="absolute left-1/2 -translate-x-1/2">
        <span class="font-bold text-white text-lg tracking-wider">
            Kashy
        </span>
    </div>
    <a href="{{ route('kasir.profil') }}"
       class="flex items-center gap-2 hover:opacity-80 transition">
        <span class="text-white text-xs font-medium max-w-[90px] truncate">
            {{ Auth::user()->name }}
        </span>
        @if (Auth::user()->profile_photo)
            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                 alt="Foto Profil"
                 class="w-7 h-7 rounded-full object-cover border border-white/30">
        @else
            <div class="w-7 h-7 rounded-full bg-[#C8966C] flex items-center justify-center text-white font-semibold text-xs">
                {{ strtoupper(substr(Auth::user()->name ?? 'K', 0, 1)) }}
            </div>
        @endif
    </a>
</header>

<main class="flex-1 overflow-y-auto pb-28 max-w-2xl mx-auto w-full">

  <!-- ── Sticky subheader (search + filter) — tepat di bawah topbar ── -->
  <!-- mt-[52px] = tinggi topbar biar konten tidak tertutup -->
  <div class="sticky top-[52px] z-30 bg-bg px-4 pt-3 pb-3 mt-[52px]">

    <h1 class="text-2xl font-bold text-gray-900 mb-3 fade-up delay-1">Riwayat Transaksi</h1>

    <!-- Search -->
    <div class="search-wrapper mb-3 fade-up delay-1">
      <span class="search-icon">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
      </span>
        <input 
        type="text" 
        id="searchInput" 
        class="search-input" 
        placeholder="Cari invoice / nama pelanggan..."
        value="{{ request('search') }}">
    </div>

    <!-- Quick filter -->
    <div class="flex gap-2 overflow-x-auto scrollbar-hide fade-up delay-2">
      <button class="qf-btn {{ request('tanggal', 'hariini') == 'hariini' ? 'active' : '' }}" onclick="setQuickFilter('hariini')">Hari Ini</button>

      <button class="qf-btn {{ request('tanggal') == 'minggu' ? 'active' : '' }}" onclick="setQuickFilter('minggu')">7 Hari</button>

      <button class="qf-btn {{ request('tanggal') == 'bulan' ? 'active' : '' }}" onclick="setQuickFilter('bulan')">Bulan Ini</button>
      <button class="qf-btn" onclick="openFilter()">
        <span style="display:flex;align-items:center;gap:6px;">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="10" y1="18" x2="14" y2="18"/>
          </svg>
          Filter
        </span>
      </button>
    </div>
  </div>

  <!-- Transaction list -->
<div class="px-4 pt-3 flex flex-col gap-5" id="trxList">

    @forelse($transactions as $tanggal => $items)
    <div class="trx-group fade-up">
        <p class="date-label mb-3">{{ $tanggal }}</p>
        <div class="flex flex-col gap-3">
            
            @foreach($items as $transaction)
            <div class="trx-card" data-status="{{ strtolower($transaction->status ?? 'berhasil') }}" data-id="{{ $transaction->invoice_number }}">
                <div class="trx-card-header" onclick="toggleDetail(this)">
                    <div class="flex justify-between items-start gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="trx-id">{{ $transaction->invoice_number }}</p>
                            <p class="trx-name">{{ $transaction->customer_name ?? 'Guest' }}</p>
                            <p class="trx-time">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#BFB4AC" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                </svg>
                                {{ $transaction->created_at->format('H:i') }} · {{ $transaction->created_at->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="trx-amount">Rp {{ number_format($transaction->grand_total ?? $transaction->total, 0, ',', '.') }}</p>
                            <span class="badge badge-success">BERHASIL</span>
                        </div>
                    </div>
                </div>
                <div class="trx-detail">
                    <div class="trx-detail-inner">
                        <p class="detail-section-label">Detail Produk</p>
                        
                        @foreach($transaction->details as $detail)
                        <div class="detail-row">
                            <span class="item-name">{{ $detail->product_name }} × {{ $detail->qty }}</span>
                            <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        
                        <div class="detail-footer">
                            <div class="metode-tag">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="2">
                                    <rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>
                                </svg>
                                Metode: {{ $transaction->metode_pembayaran ?? $transaction->payment_method ?? '-' }}
                            </div>
                            <button class="cetak-btn" onclick="cetakStruk('{{ $transaction->invoice_number }}')">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 6 2 18 2 18 9"/>
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                    <rect x="6" y="14" width="12" height="8"/>
                                </svg>
                                Cetak Ulang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-muted">
        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="2" y="5" width="20" height="14" rx="2"/>
            <path d="M2 10h20"/>
        </svg>
        <p>Belum ada transaksi</p>
    </div>
    @endforelse
    
</div>
</main>

<!-- FILTER MODAL -->
<div class="filter-overlay" id="filterOverlay" onclick="closeFilterOutside(event)">
  <div class="filter-modal" onclick="event.stopPropagation()">
    <div class="filter-modal-header">
      <h3>Filter Transaksi</h3>
      <button class="reset-btn" onclick="resetFilter()">↺ Reset</button>
    </div>
    <div class="filter-group">
      <p>Status</p>
      <div class="filter-chips-row" data-group="status">
        <button class="fchip selected" onclick="selectChip(this)">Semua</button>
        <button class="fchip" onclick="selectChip(this)">Berhasil</button>
        <button class="fchip" onclick="selectChip(this)">Dibatalkan</button>
        <button class="fchip" onclick="selectChip(this)">Pending</button>
      </div>
    </div>
    <div class="filter-group">
      <p>Metode Pembayaran</p>
      <div class="filter-chips-row" data-group="metode">
        <button class="fchip selected" onclick="selectChip(this)">Semua</button>
        <button class="fchip" onclick="selectChip(this)">Tunai</button>
        <button class="fchip" onclick="selectChip(this)">QRIS</button>
        <button class="fchip" onclick="selectChip(this)">Debit</button>
      </div>
    </div>
    <div class="filter-group">
      <p>Rentang Tanggal</p>
      <div class="filter-chips-row" data-group="tanggal">
          <button class="fchip selected" onclick="selectChip(this)">Hari Ini</button>
          <button class="fchip" onclick="selectChip(this)">7 Hari</button>
          <button class="fchip" onclick="selectChip(this)">Bulan Ini</button>
          <button class="fchip" onclick="selectChip(this); showCustomDate()">Custom</button>
      </div>
      <div id="customDateBox" class="hidden mt-3">
          <div class="grid grid-cols-2 gap-3">
              <div>
                  <label class="text-xs text-gray-500">Dari</label>
                  <input
                      type="date"
                      id="startDate"
                      class="w-full mt-1 px-3 py-2 border border-[#EAE0D6] rounded-xl">
              </div>
              <div>
                  <label class="text-xs text-gray-500">Sampai</label>
                  <input
                      type="date"
                      id="endDate"
                      class="w-full mt-1 px-3 py-2 border border-[#EAE0D6] rounded-xl" >
              </div>
          </div>
      </div>
    <div class="filter-modal-footer">
      <button class="apply-btn" onclick="applyFilter()">Terapkan Filter</button>
    </div>
    <div id="customDateBox" class="hidden mt-3">
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="text-xs text-gray-500">Dari</label>
            <input
                type="date"
                id="startDate"
                class="w-full mt-1 px-3 py-2 border border-[#EAE0D6] rounded-xl"
            >
        </div>

        <div>
            <label class="text-xs text-gray-500">Sampai</label>
            <input
                type="date"
                id="endDate"
                class="w-full mt-1 px-3 py-2 border border-[#EAE0D6] rounded-xl"
            >
        </div>
    </div>
</div>
  </div>
</div>

<script>
  function toggleDetail(header) {
    header.nextElementSibling.classList.toggle('open');
  }

  const currentUrl = "{{ url()->current() }}";
  let selectedTanggal = "{{ request('tanggal', 'hariini') }}";
  let selectedMetode = "{{ request('metode', 'semua') }}";

  function goFilter(paramsObj = {}) {
    const params = new URLSearchParams();

    const search = document.getElementById('searchInput').value.trim();

    if (search) {
      params.set('search', search);
    }

    const tanggal = paramsObj.tanggal || selectedTanggal || 'hariini';
    params.set('tanggal', tanggal);

    const metode = paramsObj.metode || selectedMetode || 'semua';

    if (metode !== 'semua') {
      params.set('metode', metode);
    }

    if (tanggal === 'custom') {
      const startDate = document.getElementById('startDate')?.value;
      const endDate = document.getElementById('endDate')?.value;

      if (startDate) {
        params.set('start_date', startDate);
      }

      if (endDate) {
        params.set('end_date', endDate);
      }
    }

    window.location.href = currentUrl + '?' + params.toString();
  }

  function setQuickFilter(key) {
    selectedTanggal = key;
    goFilter({
      tanggal: key
    });
  }

  let searchTimer;
  document.getElementById('searchInput').addEventListener('input', function () {
    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
      goFilter();
    }, 600);
  });

  document.getElementById('searchInput').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      clearTimeout(searchTimer);
      goFilter();
    }
  });

  function openFilter() {
    document.getElementById('filterOverlay').classList.add('show');

    setSelectedChip('tanggal', selectedTanggal);
    setSelectedChip('metode', selectedMetode);
  }

  function showCustomDate() {
    document.getElementById('customDateBox').classList.remove('hidden');
  }

  function closeFilterOutside(e) {
    document.getElementById('filterOverlay').classList.remove('show');
  }

  function selectChip(el) {
    el.parentElement.querySelectorAll('.fchip').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
  }

  function setSelectedChip(group, value) {
    const chips = document.querySelectorAll(`[data-group="${group}"] .fchip`);

    chips.forEach(chip => {
      const text = chip.textContent.trim().toLowerCase();

      chip.classList.remove('selected');

      if (group === 'tanggal') {
        if (value === 'hariini' && text === 'hari ini') chip.classList.add('selected');
        if (value === 'minggu' && text === '7 hari') chip.classList.add('selected');
        if (value === 'bulan' && text === 'bulan ini') chip.classList.add('selected');
      }

      if (group === 'metode') {
        if (value === 'semua' && text === 'semua') chip.classList.add('selected');
        if (value === 'tunai' && text === 'tunai') chip.classList.add('selected');
        if (value === 'qris' && text === 'qris') chip.classList.add('selected');
        if (value === 'debit' && text === 'debit') chip.classList.add('selected');
      }
    });
  }

  function applyFilter() {
    const tanggalText = document.querySelector('[data-group="tanggal"] .fchip.selected')?.textContent.trim().toLowerCase();
    const metodeText = document.querySelector('[data-group="metode"] .fchip.selected')?.textContent.trim().toLowerCase();

    let tanggal = 'hariini';
    let metode = 'semua';

    if (tanggalText === '7 hari') {
      tanggal = 'minggu';
    } else if (tanggalText === 'bulan ini') {
      tanggal = 'bulan';
    } else if (tanggalText === 'custom') {
      tanggal = 'custom';
    } else {
      tanggal = 'hariini';
    }

    if (metodeText === 'tunai') {
      metode = 'tunai';
    } else if (metodeText === 'qris') {
      metode = 'qris';
    } else if (metodeText === 'debit') {
      metode = 'debit';
    } else {
      metode = 'semua';
    }

    selectedTanggal = tanggal;
    selectedMetode = metode;

    goFilter({
      tanggal: tanggal,
      metode: metode
    });
  }

  function resetFilter() {
    window.location.href = currentUrl + '?tanggal=hariini';
  }

  function cetakStruk(id) {
    alert('Mencetak struk: ' + id);
  }
</script>
</body>
</html>