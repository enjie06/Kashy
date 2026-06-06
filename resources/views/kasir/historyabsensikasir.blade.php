<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>History Absensi Kasir – Kashy</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          terra: '#C8966C', 'terra-l': '#E5B18A', 'terra-ll': '#F0D7C7', 'terra-xs': '#FAF2EC',
          muted: '#9C8B7E', border: '#EAE0D6', bg: '#F5F0EB',
        },
        fontFamily: { poppins: ['Poppins', 'sans-serif'] },
      }
    }
  }
</script>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: 'Poppins', sans-serif; background: #F5F0EB; }
  @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
  .fade-up { animation: fadeUp 0.4s cubic-bezier(0.2,0.9,0.4,1.1) both; }
  .delay-1 { animation-delay: 0.05s; }
  .delay-2 { animation-delay: 0.12s; }
  .delay-3 { animation-delay: 0.20s; }
  .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
  .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.25rem; text-align: center; }
  .calendar-day { padding: 0.4rem 0; font-size: 0.7rem; font-weight: 500; border-radius: 0.5rem; cursor: pointer; transition: all 0.15s; }
  .calendar-day:hover { background-color: #F0D7C7; color: #C8966C; }
  .calendar-day.selected { background-color: #C8966C; color: white; }
</style>
</head>
<body class="bg-bg min-h-screen flex flex-col font-poppins">

<!-- TOPBAR dengan tombol back -->
<nav class="sticky top-0 z-50 bg-gray-900 px-4 py-3.5 flex items-center gap-3 shadow-md">
  <button onclick="window.location.href='{{ route('kasir.absensikasir') }}'"
    class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors flex-shrink-0">
    <svg width="16" height="16" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
      <path d="M19 12H5M12 5l-7 7 7 7"/>
    </svg>
    <span class="font-bold text-white text-lg tracking-wider absolute left-1/2 -translate-x-1/2">
        Kashy
    </span>
  </button>
</nav>

<main class="flex-1 overflow-y-auto pb-28">
  <div class="max-w-2xl mx-auto w-full px-4 pt-6 space-y-5">
    <h1 class="text-2xl font-bold text-gray-900 mb-3 fade-up delay-1">Riwayat Transaksi</h1>
    <!-- Summary Stats -->
    <div class="grid grid-cols-3 gap-1.5 fade-up delay-1">
      <div class="bg-white rounded-lg border border-border shadow-sm p-2 flex items-center gap-1.5 card-hover">
        <div class="w-6 h-6 bg-green-50 rounded-md flex items-center justify-center flex-shrink-0">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
        </div>
        <div>
          <p class="text-sm font-bold text-gray-900 leading-tight" id="totalHadir">0</p>
          <p class="text-[8px] text-muted font-medium uppercase tracking-wide">Hadir</p>
        </div>
      </div>
      <div class="bg-white rounded-lg border border-border shadow-sm p-2 flex items-center gap-1.5 card-hover">
        <div class="w-6 h-6 bg-yellow-50 rounded-md flex items-center justify-center flex-shrink-0">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <div>
          <p class="text-sm font-bold text-gray-900 leading-tight" id="totalTerlambat">0</p>
          <p class="text-[8px] text-muted font-medium uppercase tracking-wide">Terlambat</p>
        </div>
      </div>
      <div class="bg-white rounded-lg border border-border shadow-sm p-2 flex items-center gap-1.5 card-hover">
        <div class="w-6 h-6 bg-red-50 rounded-md flex items-center justify-center flex-shrink-0">
          <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
        </div>
        <div>
          <p class="text-sm font-bold text-gray-900 leading-tight" id="totalTidakHadir">0</p>
          <p class="text-[8px] text-muted font-medium uppercase tracking-wide">Tidak Hadir</p>
        </div>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl border border-border shadow-sm p-3 space-y-2 fade-up delay-2">
      <div class="flex items-center gap-1.5 overflow-x-auto pb-1">
        <button onclick="setFilter('semua')" id="filterSemua"
          class="filter-btn px-2.5 py-1 rounded-md text-[10px] font-semibold whitespace-nowrap transition bg-terra text-white">Semua</button>
        <button onclick="setFilter('hadir')" id="filterHadir"
          class="filter-btn px-2.5 py-1 rounded-md text-[10px] font-semibold whitespace-nowrap transition bg-stone-100 text-muted hover:bg-terra-xs">Hadir</button>
        <button onclick="setFilter('terlambat')" id="filterTerlambat"
          class="filter-btn px-2.5 py-1 rounded-md text-[10px] font-semibold whitespace-nowrap transition bg-stone-100 text-muted hover:bg-terra-xs">Terlambat</button>
        <button onclick="setFilter('tidak_hadir')" id="filterTidakHadir"
          class="filter-btn px-2.5 py-1 rounded-md text-[10px] font-semibold whitespace-nowrap transition bg-stone-100 text-muted hover:bg-terra-xs">Tidak Hadir</button>
      </div>

      <div class="flex flex-wrap items-center gap-1.5 pt-1.5 border-t border-stone-100">
        <div class="relative flex-1 min-w-[130px]">
          <input type="text" id="filterPreviewInput" readonly placeholder="Pilih tanggal..."
            class="w-full pl-8 pr-2 py-1.5 border border-border rounded-lg text-[11px] font-medium text-gray-700 bg-stone-50 cursor-default">
          <svg class="absolute left-2 top-1/2 -translate-y-1/2 text-muted" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>
          </svg>
        </div>
        <button id="openCalendarBtn"
          class="flex-shrink-0 w-8 h-8 bg-terra text-white rounded-lg flex items-center justify-center hover:bg-terra-l transition shadow-sm">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>
          </svg>
        </button>
        <button id="resetCalendarFilter"
          class="flex-shrink-0 px-2.5 py-1.5 bg-stone-100 rounded-lg text-[10px] font-semibold text-muted hover:bg-stone-200 transition">Reset</button>
      </div>
    </div>

    <!-- History List -->
    <div class="space-y-2 fade-up delay-3" id="historyContainer">
      <div class="flex justify-center py-6" id="historyLoading">
        <svg class="animate-spin h-5 w-5 text-terra" viewBox="0 0 24 24" fill="none">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
      </div>
      <div id="historyContent" class="hidden space-y-2"></div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="hidden bg-white rounded-xl border border-border shadow-sm p-6 text-center">
      <div class="w-12 h-12 mx-auto mb-2 bg-stone-100 rounded-full flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
      </div>
      <p class="text-xs font-semibold text-gray-900 mb-0.5">Tidak ada data</p>
      <p class="text-[10px] text-muted">Tidak ada riwayat absensi yang sesuai filter</p>
    </div>

  </div>
</main>

<!-- MODAL KALENDER -->
<div id="calendarModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
  <div class="bg-white rounded-xl w-[90%] max-w-[300px] mx-4 shadow-2xl">
    <div class="p-3 pb-1 text-center">
      <h3 class="text-sm font-bold text-gray-800 tracking-wide">PILIH TANGGAL</h3>
    </div>
    <div class="px-3 pb-1">
      <div class="bg-stone-50 rounded-lg p-1 text-center border border-stone-100">
        <span id="previewDate" class="text-[10px] font-semibold text-gray-700">-</span>
      </div>
    </div>
    <div class="px-3 flex gap-2 mb-2">
      <select id="monthSelect" class="flex-1 border border-border rounded-lg py-1 px-2 text-[10px] font-medium focus:border-terra outline-none">
        <option value="0">Januari</option><option value="1">Februari</option><option value="2">Maret</option>
        <option value="3">April</option><option value="4">Mei</option><option value="5">Juni</option>
        <option value="6">Juli</option><option value="7">Agustus</option><option value="8">September</option>
        <option value="9">Oktober</option><option value="10">November</option><option value="11">Desember</option>
      </select>
      <select id="yearSelect" class="flex-1 border border-border rounded-lg py-1 px-2 text-[10px] font-medium focus:border-terra outline-none"></select>
    </div>
    <div class="px-3">
      <div class="calendar-grid text-[9px] font-semibold text-muted mb-0.5">
        <span>M</span><span>S</span><span>S</span><span>R</span><span>K</span><span>J</span><span>S</span>
      </div>
      <div id="calendarDaysGrid" class="calendar-grid mb-3"></div>
    </div>
    <div class="flex border-t border-border">
      <button id="cancelCalendarBtn" class="flex-1 py-2 text-center text-[10px] font-semibold text-muted hover:bg-stone-50 transition rounded-bl-xl">BATAL</button>
      <div class="w-px bg-border"></div>
      <button id="selectCalendarBtn" class="flex-1 py-2 text-center text-[10px] font-semibold text-terra hover:bg-terra-xs transition rounded-br-xl">PILIH</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-30 bg-gray-900 text-white text-[11px] font-medium px-3 py-1.5 rounded-full shadow-xl flex items-center gap-1.5 pointer-events-none transition-all duration-300 opacity-0 translate-y-4">
  <span id="toastMsg">—</span>
</div>

<script>
const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

let currentStatus = 'semua';
let currentDate   = null;
let currentYear   = null;
let currentMonth  = null;
let selectedYear  = new Date().getFullYear();
let selectedMonth = new Date().getMonth();
let selectedDay   = new Date().getDate();

async function loadData() {
  document.getElementById('historyLoading').classList.remove('hidden');
  document.getElementById('historyContent').classList.add('hidden');
  document.getElementById('emptyState').classList.add('hidden');

  try {
    let url    = '{{ route("shift.full-history") }}';
    let params = [];
    if (currentDate) {
      params.push(`date=${currentDate}`);
    } else if (currentYear !== null && currentMonth !== null) {
      params.push(`year=${currentYear}`);
      params.push(`month=${currentMonth + 1}`);
    }
    if (currentStatus !== 'semua') params.push(`status=${currentStatus}`);
    if (params.length > 0) url += '?' + params.join('&');

    const response = await fetch(url);
    const data     = await response.json();

    document.getElementById('totalHadir').textContent      = data.stats?.hadir       || 0;
    document.getElementById('totalTerlambat').textContent  = data.stats?.terlambat   || 0;
    document.getElementById('totalTidakHadir').textContent = data.stats?.tidak_hadir || 0;

    renderHistory(data.histories || []);
  } catch (err) {
    showToast('Gagal memuat riwayat absensi');
    document.getElementById('historyLoading').classList.add('hidden');
  }
}

function renderHistory(histories) {
  const container  = document.getElementById('historyContent');
  const loadingEl  = document.getElementById('historyLoading');
  const emptyState = document.getElementById('emptyState');

  loadingEl.classList.add('hidden');

  if (!histories || histories.length === 0) {
    container.innerHTML = '';
    container.classList.add('hidden');
    emptyState.classList.remove('hidden');
    return;
  }

  emptyState.classList.add('hidden');
  container.classList.remove('hidden');

  container.innerHTML = histories.map(h => {
    const cfg      = getStatusConfig(h.status);
    const checkIn  = h.check_in  || '-';
    const checkOut = h.check_out || '-';
    const shiftInfo = h.shift_type
      ? `<p class="text-[8px] text-muted mt-1">Shift ${h.shift_type === 'pagi' ? 'Pagi' : 'Malam'} (${h.shift_start} – ${h.shift_end})</p>`
      : '';

    return `
      <div class="bg-white rounded-lg border border-border shadow-sm p-2.5 card-hover">
        <div class="flex items-start justify-between gap-2">
          <div class="w-7 h-7 ${cfg.bgColor} rounded-md flex items-center justify-center flex-shrink-0 mt-0.5">
            ${cfg.icon}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-[11px] font-bold text-gray-900">${h.date || '-'}</p>
            <div class="flex items-center gap-1.5 mt-1">
              <div class="flex items-center gap-0.5">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5">
                  <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                <span class="text-[9px] font-medium text-gray-800">${checkIn}</span>
              </div>
              <span class="text-muted text-[7px]">→</span>
              <div class="flex items-center gap-0.5">
                <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span class="text-[9px] font-medium text-gray-800">${checkOut}</span>
              </div>
            </div>
            ${shiftInfo}
          </div>
          <span class="px-1.5 py-0.5 rounded-full text-[8px] font-bold ${cfg.badgeClass} flex-shrink-0">${cfg.label}</span>
        </div>
      </div>`;
  }).join('');
}

function getStatusConfig(status) {
  const map = {
    'Hadir':       { label: 'Hadir',       badgeClass: 'bg-green-100 text-green-700',  bgColor: 'bg-green-50',
      icon: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>' },
    'Terlambat':   { label: 'Terlambat',   badgeClass: 'bg-yellow-100 text-yellow-700', bgColor: 'bg-yellow-50',
      icon: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' },
    'Tidak Hadir': { label: 'Tidak Hadir', badgeClass: 'bg-red-100 text-red-700',       bgColor: 'bg-red-50',
      icon: '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>' },
  };
  return map[status] || map['Hadir'];
}

function setFilter(status) {
  currentStatus = status;
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.classList.remove('bg-terra', 'text-white');
    btn.classList.add('bg-stone-100', 'text-muted');
  });
  const idMap = { semua: 'filterSemua', hadir: 'filterHadir', terlambat: 'filterTerlambat', tidak_hadir: 'filterTidakHadir' };
  const activeBtn = document.getElementById(idMap[status]);
  if (activeBtn) {
    activeBtn.classList.remove('bg-stone-100', 'text-muted');
    activeBtn.classList.add('bg-terra', 'text-white');
  }
  loadData();
}

// ── Kalender ──
function buildCalendar(year, month) {
  const firstDay = new Date(year, month, 1).getDay();
  const offset   = (firstDay + 6) % 7;
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const grid = [];
  for (let i = 0; i < offset; i++) grid.push(null);
  for (let d = 1; d <= daysInMonth; d++) grid.push(d);
  return grid;
}

function renderCalendarGrid(year, month) {
  const grid      = buildCalendar(year, month);
  const container = document.getElementById('calendarDaysGrid');
  container.innerHTML = '';
  grid.forEach(day => {
    const div = document.createElement('div');
    div.className = 'calendar-day';
    if (!day) {
      div.classList.add('opacity-0', 'cursor-default');
    } else {
      div.textContent = day;
      if (day === selectedDay && year === selectedYear && month === selectedMonth) div.classList.add('selected');
      div.addEventListener('click', () => {
        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
        div.classList.add('selected');
        selectedDay = day; selectedMonth = month; selectedYear = year;
        updatePreviewDate();
      });
    }
    container.appendChild(div);
  });
}

function updatePreviewDate() {
  document.getElementById('previewDate').textContent = `${selectedDay} ${monthNames[selectedMonth]} ${selectedYear}`;
}

function populateYearSelect() {
  const now    = new Date().getFullYear();
  const select = document.getElementById('yearSelect');
  select.innerHTML = '';
  for (let y = now - 3; y <= now; y++) {
    const opt = document.createElement('option');
    opt.value = y; opt.textContent = y;
    if (y === selectedYear) opt.selected = true;
    select.appendChild(opt);
  }
}

function openCalendarModal() {
  populateYearSelect();
  document.getElementById('monthSelect').value = selectedMonth;
  document.getElementById('yearSelect').value  = selectedYear;
  renderCalendarGrid(selectedYear, selectedMonth);
  updatePreviewDate();
  document.getElementById('calendarModal').classList.remove('hidden');
}

function closeCalendarModal() {
  document.getElementById('calendarModal').classList.add('hidden');
}

function applyCalendarSelection() {
  const month   = parseInt(document.getElementById('monthSelect').value);
  const year    = parseInt(document.getElementById('yearSelect').value);
  const dateStr = `${year}-${String(month + 1).padStart(2,'0')}-${String(selectedDay).padStart(2,'0')}`;
  currentDate  = dateStr;
  currentYear  = null;
  currentMonth = null;
  document.getElementById('filterPreviewInput').value = `${selectedDay} ${monthNames[month]} ${year}`;
  closeCalendarModal();
  loadData();
  showToast(`Menampilkan ${selectedDay} ${monthNames[month]} ${year}`);
}

function resetCalendarFilter() {
  currentDate = null; currentYear = null; currentMonth = null;
  selectedYear = new Date().getFullYear();
  selectedMonth = new Date().getMonth();
  selectedDay   = new Date().getDate();
  document.getElementById('filterPreviewInput').value = '';
  loadData();
  showToast('Filter direset');
}

function showToast(msg) {
  const t = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  t.style.opacity = '1';
  t.style.transform = 'translateX(-50%) translateY(0)';
  clearTimeout(t._t);
  t._t = setTimeout(() => {
    t.style.opacity = '0';
    t.style.transform = 'translateX(-50%) translateY(16px)';
  }, 2600);
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('openCalendarBtn').addEventListener('click', openCalendarModal);
  document.getElementById('resetCalendarFilter').addEventListener('click', resetCalendarFilter);
  document.getElementById('cancelCalendarBtn').addEventListener('click', closeCalendarModal);
  document.getElementById('selectCalendarBtn').addEventListener('click', applyCalendarSelection);
  document.getElementById('monthSelect').addEventListener('change', () => {
    renderCalendarGrid(parseInt(document.getElementById('yearSelect').value), parseInt(document.getElementById('monthSelect').value));
    updatePreviewDate();
  });
  document.getElementById('yearSelect').addEventListener('change', () => {
    renderCalendarGrid(parseInt(document.getElementById('yearSelect').value), parseInt(document.getElementById('monthSelect').value));
    updatePreviewDate();
  });
  document.getElementById('calendarModal').addEventListener('click', function(e) {
    if (e.target === this) closeCalendarModal();
  });
  loadData();
});
</script>
</body>
</html>