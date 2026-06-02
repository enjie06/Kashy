<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
  <title>Kashy – Laporan Keuangan | Owner</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { poppins: ['Poppins','sans-serif'] },
          colors: {
            kashy: {
              dark: '#1a1a1a', brown: '#C49A6C', 'brown-deep': '#7B4F2E',
              cream: '#F5F0EB', 'cream-dark': '#EDE5DB', muted: '#8A7968', border: '#E0D8CE',
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
    #sidebar {
      position:fixed; top:0; left:0; height:100vh; width:280px;
      background:#fff; box-shadow:2px 0 24px rgba(60,40,10,.12);
      z-index:60; transition:transform .3s cubic-bezier(0.2,0.9,0.4,1.1);
      display:flex; flex-direction:column; overflow-y:auto;
      transform:translateX(-100%);
    }
    #sidebar.sidebar-open { transform:translateX(0); }
    #overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:55; backdrop-filter:blur(3px); }
    #overlay.show { display:block; }
    @keyframes fadeUp {
      from { opacity:0; transform:translateY(18px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .fade-up { animation:fadeUp .4s ease both; }
    .d1{animation-delay:.04s}.d2{animation-delay:.08s}.d3{animation-delay:.12s}
    .d4{animation-delay:.16s}.d5{animation-delay:.20s}.d8{animation-delay:.32s}
    .nav-item {
      display:flex; align-items:center; gap:12px; padding:11px 18px;
      border-radius:12px; cursor:pointer; transition:all .15s;
      font-size:14px; font-weight:500; color:#1a1a1a;
      text-decoration:none; width:100%;
    }
    .nav-item:hover { background:#F5F0EB; }
    .nav-item.active { background:#F7EFE5; color:#7B4F2E; font-weight:600; }
    .form-input {
      width:100%; padding:12px 14px; border:1.5px solid #E0D8CE;
      border-radius:12px; font-size:13px; font-family:'Poppins',sans-serif;
      color:#1a1a1a; background:#fff; outline:none; transition:border-color .2s;
    }
    .form-input:focus { border-color:#C49A6C; }
    .form-select {
      appearance:none;
      background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238A7968' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
      background-repeat:no-repeat; background-position:right 12px center; padding-right:36px; cursor:pointer;
    }
    .bar-track { height:5px; background:#EDE5DB; border-radius:99px; flex:2; }
    .bar-fill-sm { height:5px; border-radius:99px; background:#C49A6C; transition:width .6s ease; }
    ::-webkit-scrollbar { width:4px; }
    ::-webkit-scrollbar-thumb { background:#C49A6C; border-radius:10px; }
  </style>
</head>

@include('owner.components.sidebar')

<body>
<main id="main" class="min-h-screen bg-kashy-cream">
  @include('owner.components.topbar')

  <div class="px-5 md:px-8 py-6 max-w-2xl mx-auto">

    {{-- ── HEADER + EXPORT ── --}}
    <div class="fade-up d1 flex flex-wrap items-start justify-between gap-3 mb-5">
      <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-kashy-dark leading-tight">Laporan Keuangan</h1>
        <p class="text-xs text-kashy-muted mt-1">Ringkasan performa bisnis & analitik penjualan mendalam.</p>
      </div>
      {{-- Export CSV: kirim periode + filter aktif --}}
      <a id="btnExport"
         href="{{ route('owner.laporan.export', ['periode' => $periode, 'start' => $startDate, 'end' => $endDate]) }}"
         class="flex-shrink-0 flex items-center gap-2 px-3 py-2 md:px-4 md:py-2.5 rounded-xl text-white text-xs font-bold tracking-wide hover:opacity-90 active:scale-95 transition-all shadow-btn"
         style="background:#C49A6C; box-shadow:0 4px 14px 0 rgba(196,154,108,.35);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        <span class="hidden sm:inline">Export CSV</span>
      </a>
    </div>

    {{-- ── SYNC BANNER ── --}}
    <div class="fade-up d1 mb-6 bg-gradient-to-r from-[#1a1a1a] to-[#2d2d2d] rounded-2xl p-4 flex items-center gap-3 text-white shadow-card">
      <div class="w-9 h-9 rounded-xl bg-kashy-brown/30 flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4 text-kashy-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <polyline points="1 4 1 10 7 10"/><polyline points="23 20 23 14 17 14"/>
          <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10M23 14l-4.64 4.36A9 9 0 0 1 3.51 15"/>
        </svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-xs font-bold text-white/90 uppercase tracking-wider">Sinkronisasi Kasir</p>
        <p class="text-[11px] text-white/50 mt-0.5">
          Data real-time dari transaksi kasir · Terakhir diperbarui:
          <span id="lastSync">{{ now()->format('H:i:s') }}</span>
        </p>
      </div>
      <div class="flex items-center gap-1.5 flex-shrink-0">
        <div class="w-2 h-2 rounded-full bg-green-400"></div>
        <span class="text-xs font-semibold text-green-400">Live</span>
      </div>
    </div>

    {{-- ── FILTER ── --}}
    <form method="GET" action="{{ route('owner.laporan.keuangan') }}" id="filterForm">
      <div class="fade-up d2 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-stretch">

          {{-- Tanggal --}}
          <div class="flex-1 flex flex-col">
            <div class="flex gap-3 mb-1 px-1">
              <div class="flex-1 min-w-[120px]">
                <label class="block text-[10px] font-semibold text-kashy-muted uppercase tracking-wide">Mulai dari</label>
              </div>
              <div class="flex-1 min-w-[120px]">
                <label class="block text-[10px] font-semibold text-kashy-muted uppercase tracking-wide">Berakhir</label>
              </div>
              <div class="w-[82px]"></div>
            </div>
            <div class="bg-white border border-kashy-border rounded-xl p-3 shadow-sm h-full flex items-center">
              <div class="flex flex-wrap items-center gap-3 w-full">
                <div class="flex-1 min-w-[120px]">
                  <input type="date" name="start" id="startDate"
                    value="{{ $startDate }}"
                    class="border-none bg-transparent text-sm focus:outline-none w-full py-1">
                </div>
                <div class="flex-1 min-w-[120px]">
                  <input type="date" name="end" id="endDate"
                    value="{{ $endDate }}"
                    class="border-none bg-transparent text-sm focus:outline-none w-full py-1">
                </div>
                <button type="submit"
                  class="bg-[#C49A6C] text-white text-xs font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition shadow-sm whitespace-nowrap">
                  Terapkan
                </button>
              </div>
            </div>
          </div>

          {{-- Filter periode --}}
          <div class="sm:w-48 w-full flex flex-col">
            <label class="block text-[10px] font-semibold text-kashy-muted uppercase tracking-wide mb-1">Filter</label>
            <div class="bg-white border border-kashy-border rounded-xl p-3 shadow-sm h-full flex items-center">
              <select name="periode" id="periodeSelect"
                class="form-input form-select w-full py-2 pr-8 bg-white border-0 focus:ring-0"
                onchange="this.form.submit()">
                <option value="harian"  {{ $periode === 'harian'  ? 'selected' : '' }}>Harian</option>
                <option value="bulanan" {{ $periode === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                <option value="tahunan" {{ $periode === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
              </select>
            </div>
          </div>

        </div>
      </div>
    </form>

    {{-- ── STAT CARDS ── --}}
    <div class="mb-6">
      <div class="fade-up d3 bg-white rounded-2xl p-5 shadow-card border-l-4 border-kashy-brown w-full mb-4">
        <div class="flex items-start justify-between mb-3">
          <span class="text-[11px] font-semibold tracking-widest text-kashy-muted uppercase">Total Pendapatan</span>
          <div class="w-8 h-8 rounded-xl bg-kashy-cream flex items-center justify-center">
            <svg class="w-4 h-4 text-kashy-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
              <rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/>
              <path d="M6 12h.01M18 12h.01"/>
            </svg>
          </div>
        </div>
        <p class="text-2xl font-bold text-kashy-dark">
          Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="fade-up d3 bg-white rounded-2xl p-5 shadow-card border-l-4 border-kashy-brown">
          <div class="flex items-start justify-between mb-3">
            <span class="text-[11px] font-semibold tracking-widest text-kashy-muted uppercase">Transaksi</span>
            <div class="w-8 h-8 rounded-xl bg-kashy-cream flex items-center justify-center">
              <svg class="w-4 h-4 text-kashy-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
              </svg>
            </div>
          </div>
          <p class="text-2xl font-bold text-kashy-dark">
            {{ number_format($transaksiTotal, 0, ',', '.') }}
          </p>
        </div>

        <div class="fade-up d3 bg-white rounded-2xl p-5 shadow-card border-l-4 border-emerald-600">
          <div class="flex items-start justify-between mb-3">
            <span class="text-[11px] font-semibold tracking-widest text-kashy-muted uppercase">Produk Terjual</span>
            <div class="w-8 h-8 rounded-xl bg-kashy-cream flex items-center justify-center">
              <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path d="M20 7h-4.18A3 3 0 0 0 16 5.18V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1.18A3 3 0 0 0 8.18 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                <circle cx="12" cy="14" r="4"/>
              </svg>
            </div>
          </div>
          <p class="text-2xl font-bold text-kashy-dark">
            {{ number_format($produkTerjual, 0, ',', '.') }}
          </p>
        </div>
      </div>
    </div>

    {{-- ── GRAFIK ── --}}
    <div class="fade-up d5 bg-white rounded-2xl p-4 md:p-5 mb-6 shadow-card">
      <div class="flex flex-wrap items-start justify-between gap-2 mb-5">
        <div>
          <h3 class="text-base font-bold text-kashy-dark" id="chartTitle">
            @if($periode === 'bulanan') Grafik Pendapatan Bulanan
            @elseif($periode === 'tahunan') Grafik Pendapatan Tahunan
            @elseif($periode === 'custom') Grafik Pendapatan (Kustom)
            @else Grafik Pendapatan Harian
            @endif
          </h3>
          <p class="text-xs text-kashy-muted mt-0.5">
            @if($periode === 'bulanan') 12 bulan terakhir
            @elseif($periode === 'tahunan') 6 tahun terakhir
            @elseif($periode === 'custom') Rentang tanggal pilihan
            @else 7 hari terakhir
            @endif
          </p>
        </div>
        <div class="flex gap-1">
          <button onclick="setChartType('bar')" id="btnBar"
            class="w-8 h-8 rounded-lg flex items-center justify-center transition-all text-kashy-muted"
            style="background:#F5F0EB;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <rect x="3" y="12" width="4" height="9"/><rect x="10" y="7" width="4" height="14"/>
              <rect x="17" y="3" width="4" height="18"/>
            </svg>
          </button>
          <button onclick="setChartType('line')" id="btnLine"
            class="w-8 h-8 rounded-lg flex items-center justify-center transition-all text-kashy-muted hover:bg-kashy-cream">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
          </button>
        </div>
      </div>
      <div style="height:210px;"><canvas id="mainChart"></canvas></div>
    </div>

    {{-- ── METODE PEMBAYARAN ── --}}
    <div class="fade-up d8 bg-white rounded-2xl p-4 md:p-5 mb-4 shadow-card">
      <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
        <h3 class="text-base font-bold text-kashy-dark">Metode Pembayaran</h3>
        <span class="text-xs text-kashy-muted font-medium">Dari kasir realtime</span>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-4">
        <div class="flex items-center justify-center" style="height:140px;">
          <canvas id="donutChart"></canvas>
        </div>
        <div class="flex flex-col justify-center gap-2.5" id="donutLegend">
          @foreach($metode as $m)
          @php
            $colors = ['QRIS'=>'#C49A6C','Tunai'=>'#1a1a1a','Transfer'=>'#E0D8CE'];
            $color  = $colors[$m['name']] ?? '#C49A6C';
          @endphp
          <div class="flex items-center gap-2 justify-between">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 rounded-full flex-shrink-0" style="background:{{ $color }};"></div>
              <span class="text-xs font-medium text-kashy-dark">{{ $m['name'] }}</span>
            </div>
            <span class="text-xs font-bold text-kashy-muted">{{ $m['pct'] }}%</span>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Bar list metode pembayaran --}}
      @foreach($metode as $m)
      @php $color = $colors[$m['name']] ?? '#C49A6C'; @endphp
      <div class="flex items-center gap-3 py-2.5 border-b border-kashy-border/70 last:border-0">
        <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $color }};"></div>
        <div class="flex-1">
          <div class="flex justify-between mb-1.5">
            <span class="text-sm font-medium text-kashy-dark">{{ $m['name'] }}</span>
            <span class="text-xs font-bold text-kashy-muted">{{ $m['pct'] }}%</span>
          </div>
          <div class="bar-track">
            <div class="bar-fill-sm" style="width:{{ $m['pct'] }}%; background:{{ $color }};"></div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
</main>

{{-- ── SCRIPTS ── --}}
<script>
  // Data dari PHP → JS
  const CHART_LABELS = @json($labels);
  const CHART_DATA   = @json($pendapatanChart);
  const METODE_DATA  = @json($metode);

  const METODE_COLORS = { 'QRIS':'#C49A6C', 'Tunai':'#1a1a1a', 'Transfer':'#E0D8CE' };

  let currentType = 'bar';
  let mainChart, donutChart;

  const fmt = n => 'Rp ' + new Intl.NumberFormat('id-ID').format(n);

  // ── MAIN CHART ────────────────────────────────────────────────────────────
  function buildMainChart(type) {
    if (mainChart) mainChart.destroy();
    const ctx   = document.getElementById('mainChart').getContext('2d');
    const isBar = type === 'bar';
    const maxVal = Math.max(...CHART_DATA);

    mainChart = new Chart(ctx, {
      type: type,
      data: {
        labels: CHART_LABELS,
        datasets: [{
          label: 'Pendapatan',
          data: CHART_DATA,
          backgroundColor: isBar
            ? CHART_DATA.map(v => v === maxVal ? '#C49A6C' : '#E0D8CE')
            : 'rgba(196,154,108,.12)',
          borderColor: '#C49A6C',
          borderWidth: isBar ? 0 : 2.2,
          borderRadius: isBar ? 6 : 0,
          tension: 0.4,
          fill: !isBar,
          pointBackgroundColor: '#C49A6C',
          pointRadius: isBar ? 0 : 3.5,
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: { label: ctx => fmt(ctx.raw) },
            backgroundColor: '#1a1a1a',
            titleFont: { family: 'Poppins' },
            bodyFont:  { family: 'Poppins' }
          }
        },
        scales: {
          x: { grid: { display: false }, ticks: { font: { family:'Poppins', size:10 }, color:'#8A7968' } },
          y: {
            grid: { color: '#F0EBE5' },
            ticks: {
              font: { family:'Poppins', size:10 }, color:'#8A7968',
              callback: v => v >= 1e9 ? (v/1e9).toFixed(1)+'M' : v >= 1e6 ? (v/1e6).toFixed(0)+'jt' : v
            }
          }
        }
      }
    });
  }

  // ── DONUT CHART ───────────────────────────────────────────────────────────
  function buildDonutChart() {
    if (donutChart) donutChart.destroy();
    const ctx = document.getElementById('donutChart').getContext('2d');
    const colors = METODE_DATA.map(m => METODE_COLORS[m.name] || '#C49A6C');
    donutChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: METODE_DATA.map(m => m.name),
        datasets: [{
          data: METODE_DATA.map(m => m.pct || 1), // minimal 1 supaya donut muncul
          backgroundColor: colors,
          borderWidth: 0,
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false, cutout: '72%',
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1a1a1a',
            titleFont: { family:'Poppins' }, bodyFont: { family:'Poppins' },
            callbacks: { label: ctx => ctx.label + ': ' + ctx.raw + '%' }
          }
        }
      }
    });
  }

  function setChartType(type) {
    currentType = type;
    document.getElementById('btnBar').style.background  = type === 'bar'  ? '#F5F0EB' : 'transparent';
    document.getElementById('btnLine').style.background = type === 'line' ? '#F5F0EB' : 'transparent';
    buildMainChart(type);
  }

  // ── Sidebar toggle ────────────────────────────────────────────────────────
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const menuBtn = document.getElementById('global-menu-toggle');
  function openSidebar()  { sidebar.classList.add('sidebar-open'); overlay.classList.add('show'); document.body.style.overflow='hidden'; }
  function closeSidebar() { sidebar.classList.remove('sidebar-open'); overlay.classList.remove('show'); document.body.style.overflow=''; }
  if (menuBtn) menuBtn.addEventListener('click', e => { e.stopPropagation(); openSidebar(); });
  if (overlay) overlay.addEventListener('click', closeSidebar);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

  // ── Live clock sync banner ────────────────────────────────────────────────
  setInterval(() => {
    const now = new Date();
    const pad = n => n.toString().padStart(2,'0');
    document.getElementById('lastSync').textContent = pad(now.getHours())+':'+pad(now.getMinutes())+':'+pad(now.getSeconds());
  }, 1000);

  // ── Init ──────────────────────────────────────────────────────────────────
  buildMainChart('bar');
  buildDonutChart();
</script>
</body>
</html>