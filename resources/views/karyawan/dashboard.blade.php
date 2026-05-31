<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard Karyawan – Kashy</title>
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
          display: ['Poppins', 'sans-serif'],
          body:    ['Poppins', 'sans-serif'],
        },
      }
    }
  }
</script>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: 'Poppins',sans-serif; background:#F5F0EB; }
  @keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .fade-up { animation: fadeUp 0.45s cubic-bezier(0.2,0.9,0.4,1.1) both; }
  .delay-1 { animation-delay:0.05s; }
  .delay-2 { animation-delay:0.12s; }
  .delay-3 { animation-delay:0.2s; }
  .delay-4 { animation-delay:0.28s; }
  @keyframes pulse { 0%,100%{ opacity:1; transform:scale(1); } 50%{ opacity:0.7; transform:scale(1.2); } }
  .pulse-dot { animation: pulse 1.4s infinite; }
  .shimmer-bar {
    background: linear-gradient(90deg, #C8966C, #E5B18A, #F0D7C7, #E5B18A, #C8966C);
    background-size: 200%;
    animation: shimmer 3s infinite;
  }
  @keyframes shimmer { 0%{background-position:100%} 100%{background-position:-100%} }
  .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }

  /* Shift Modal - CENTERED */
  #shiftModal {
    opacity:0; pointer-events:none;
    transition:opacity .25s ease;
  }
  #shiftModal.open { opacity:1; pointer-events:all; }
  #shiftModalDialog {
    transform:scale(.92) translateY(20px);
    transition:transform .32s cubic-bezier(0.34,1.56,.64,1);
  }
  #shiftModal.open #shiftModalDialog { transform:scale(1) translateY(0); }

  .shift-option {
    display:flex; align-items:center; gap:14px;
    padding:16px 18px; border-radius:16px;
    border:2px solid #EAE0D6; background:#fff;
    cursor:pointer; transition:all .2s;
    position:relative; overflow:hidden;
  }
  .shift-option:hover:not(.disabled) {
    border-color:#C8966C; background:#FAF2EC;
    transform:translateY(-1px); box-shadow:0 6px 18px rgba(200,150,108,.2);
  }
  .shift-option.disabled {
    opacity:.5; cursor:not-allowed; background:#f9f9f9;
  }
  .shift-option-icon {
    width:44px; height:44px; border-radius:14px;
    display:flex; align-items:center; justify-content:center; flex-shrink:0;
  }
  .shift-option .unavail-badge {
    position:absolute; top:10px; right:12px;
    font-size:9px; font-weight:700; letter-spacing:.05em;
    padding:2px 8px; border-radius:20px;
    background:#F3F4F6; color:#9CA3AF;
  }

  .btn-absensi-card {
    width:100%; padding:10px; border-radius:12px;
    background:#1a1a1a; color:#fff;
    font-size:12px; font-weight:600; letter-spacing:.02em;
    border:none; cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:6px;
    transition:background .2s, transform .15s;
    font-family:'Poppins',sans-serif;
  }
  .btn-absensi-card:hover { background:#333; transform:translateY(-1px); }
  .btn-absensi-card:active { transform:scale(.98); }

  /* Toast responsif (disamakan dengan profile) */
  #toast {
    max-width: calc(100% - 32px);
    white-space: normal;
    text-align: center;
    font-size: 11px;
    padding: 8px 16px;
    border-radius: 40px;
  }
  @media (max-width: 480px) {
    #toast {
      font-size: 10px;
      padding: 6px 14px;
      max-width: 90%;
    }
  }
</style>
</head>
@include('karyawan.components.topbar')
<body class="bg-bg min-h-screen flex flex-col">
@include('karyawan.components.navbar')

<main class="flex-1 overflow-y-auto pb-28">
  <div class="max-w-md mx-auto px-4 pt-5 space-y-5">

    <!-- GREETING (ukuran lebih kecil, seperti profile) -->
    <div class="fade-up delay-1">
      <p class="text-[10px] text-muted font-medium uppercase tracking-wide">Dashboard Karyawan</p>
      <h1 class="font-display text-xl font-bold text-gray-900 mt-0.5" id="greetingText">
        Selamat Pagi, <span class="text-terra">Karyawan</span>
      </h1>
      <p class="text-xs text-muted mt-1 leading-relaxed">Berikut ringkasan aktivitas dan shift anda hari ini.</p>
    </div>

    <!-- SHIFT CARD (diperkecil style-nya seperti card di profile) -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden fade-up delay-2">
      <div class="shimmer-bar h-1 w-full"></div>
      <div class="p-4">
        <div class="flex flex-wrap justify-between items-start gap-2 mb-3">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-terra-xs flex items-center justify-center">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 text-base">Shift Saat Ini</h3>
              <p class="text-[10px] text-muted" id="shiftHariTanggal"></p>
            </div>
          </div>
          <!-- Badge -->
          <div id="shiftBadge" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full border">
            <span id="badgeDot" class="w-2 h-2 rounded-full"></span>
            <span id="badgeText" class="text-[10px] font-semibold">Memuat...</span>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
          <div>
            <p class="text-[9px] text-muted uppercase tracking-wide">Mulai</p>
            <p class="text-sm font-semibold text-gray-900" id="shiftMulai">--:--</p>
          </div>
          <div>
            <p class="text-[9px] text-muted uppercase tracking-wide">Berakhir</p>
            <p class="text-sm font-semibold text-gray-900" id="shiftBerakhir">--:--</p>
          </div>
        </div>

        <!-- Pesan terlambat -->
        <div id="lateWarning" class="hidden mb-2 text-[10px] text-red-600 bg-red-50 p-1.5 rounded-lg flex items-center gap-1.5">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span>⚠️ Terlambat</span>
        </div>

        <!-- Tombol Pilih Shift -->
        <div id="selectShiftButtonContainer" class="mb-3">
          <button class="btn-absensi-card" onclick="openShiftModal()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <rect x="3" y="4" width="18" height="18" rx="2"/>
              <path d="M16 2v4M8 2v4M3 10h18"/>
              <path d="m9 16 2 2 4-4"/>
            </svg>
            Pilih Shift Hari Ini
          </button>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 text-[10px] text-muted border-t border-border pt-3">
          <div class="flex items-center gap-1.5">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            <span>Karyawan: <strong class="text-gray-800 text-[10px]" id="infoNamaCard">—</strong></span>
          </div>
          <div class="flex items-center gap-1.5">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 10h18"/>
            </svg>
            <span>Posisi: <strong class="text-gray-800 text-[10px]" id="infoPosisiCard">Karyawan</strong></span>
          </div>
        </div>
      </div>
    </div>

    <!-- TO-DO LIST (diperkecil) -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden fade-up delay-3">
      <div class="p-4">
        <div class="flex items-center justify-between mb-3 flex-wrap gap-1.5">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-terra-xs flex items-center justify-center">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 text-sm">To-Do List Hari Ini</h3>
              <p class="text-[9px] text-muted">Tambah tugas yang harus diselesaikan</p>
            </div>
          </div>
          <div class="flex gap-2">
            <button id="showTodoFormBtn" class="px-2.5 py-1.5 bg-terra text-white rounded-lg text-[10px] font-semibold hover:bg-terra-l transition flex items-center gap-1">
              <svg width="10" height="10" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
              Tambah
            </button>
            <button id="deleteAllBtn" class="px-2.5 py-1.5 bg-red-50 text-red-500 rounded-lg text-[10px] font-medium hover:bg-red-100 transition flex items-center gap-1">
              <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
              Hapus Semua
            </button>
          </div>
        </div>

        <div id="todoForm" class="hidden mb-3 p-2 bg-stone-50 rounded-lg border border-stone-200">
          <div class="flex flex-col gap-1.5">
            <textarea id="todoInput" rows="2" placeholder="Tulis tugas baru..." class="w-full px-2 py-1.5 border border-border rounded-lg text-xs focus:outline-none focus:border-terra bg-white resize-none"></textarea>
            <div class="flex justify-between gap-2">
              <button onclick="hideTodoForm()" class="px-2 py-1 bg-gray-200 text-gray-700 rounded-lg text-[10px] font-semibold flex-1">Batal</button>
              <button onclick="tambahTugas()" class="px-2 py-1 bg-terra text-white rounded-lg text-[10px] font-semibold flex-1">Simpan</button>
            </div>
          </div>
        </div>

        <div id="todoListContainer" class="space-y-1.5 max-h-56 overflow-y-auto"></div>
        <p id="todoEmpty" class="text-center text-muted text-[10px] py-3 hidden">Belum ada tugas. Klik "Tambah" untuk membuat daftar.</p>
      </div>
    </div>

    <!-- QUOTE BANNER (diperkecil) -->
    <div class="rounded-2xl overflow-hidden relative min-h-[110px] shadow-sm fade-up delay-4" style="background-image:url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&q=80'); background-size:cover; background-position:center 30%;">
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>
      <div class="relative z-10 flex flex-col justify-end h-full p-3 min-h-[110px]">
        <div class="w-6 h-0.5 bg-terra rounded-full mb-1.5"></div>
        <blockquote class="font-display text-sm italic font-semibold text-white leading-snug">"Kesederhanaan adalah kecanggihan tertinggi."</blockquote>
        <p class="text-[9px] text-white/60 mt-1">— Leonardo da Vinci</p>
      </div>
    </div>
  </div>
</main>

<!-- TOAST -->
<div id="toast" class="fixed bottom-20 left-1/2 -translate-x-1/2 z-30 bg-gray-900 text-white shadow-xl flex items-center gap-1.5 pointer-events-none transition-all duration-300 opacity-0 translate-y-4">
  <span id="toastMsg">—</span>
</div>

<!-- Modal Hapus Semua Todo (diperkecil ukurannya) -->
<div id="confirmDeleteAllModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
  <div class="bg-white rounded-2xl max-w-xs w-full mx-4 p-4 shadow-2xl">
    <div class="text-center">
      <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-2">
        <svg width="18" height="18" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
      </div>
      <h3 class="text-sm font-semibold text-gray-900 mb-1">Hapus Semua Tugas?</h3>
      <p class="text-[10px] text-muted mb-4">Tindakan ini tidak dapat dibatalkan.</p>
      <div class="flex gap-2">
        <button id="cancelDeleteAllBtn" class="flex-1 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-[11px] font-medium">Batal</button>
        <button id="confirmDeleteAllBtn" class="flex-1 px-3 py-1.5 bg-red-500 text-white rounded-lg text-[11px] font-medium">Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PILIH SHIFT (CENTERED, ukuran lebih pas di HP) -->
<div id="shiftModal" class="fixed inset-0 z-[60] flex items-center justify-center p-3 bg-black/50" style="backdrop-filter:blur(4px);">
  <div id="shiftModalDialog" class="w-full max-w-[320px] bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="shimmer-bar h-1 w-full"></div>
    <div class="px-4 pt-5 pb-6">
      <div class="flex items-center justify-between mb-1">
        <div>
          <h3 class="text-base font-bold text-gray-900">Pilih Shift Kerja</h3>
          <p class="text-[10px] text-muted mt-0.5" id="modalDateLabel">—</p>
        </div>
        <button onclick="closeShiftModal()" class="w-7 h-7 rounded-lg border border-border flex items-center justify-center text-muted hover:bg-terra-xs">
          <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="flex flex-col gap-2 mb-5">
        <div id="optPagi" class="shift-option" onclick="pilihShift('pagi')">
          <div class="shift-option-icon w-9 h-9">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2">
              <circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="22"/>
              <line x1="4.22" y1="4.22" x2="6.34" y2="6.34"/><line x1="17.66" y1="17.66" x2="19.78" y2="19.78"/>
              <line x1="2" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="22" y2="12"/>
            </svg>
          </div>
          <div class="flex-1"><p class="text-xs font-bold text-gray-900">Shift Pagi</p><p class="text-[9px] text-muted">09:00 – 17:00</p></div>
          <svg class="w-4 h-4 text-terra" id="arrowPagi" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
          <span class="unavail-badge hidden" id="unavailPagi">Unavailable</span>
        </div>
        <div id="optMalam" class="shift-option" onclick="pilihShift('malam')">
          <div class="shift-option-icon w-9 h-9" style="background:#F0F4FF;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7B4F2E" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
          </div>
          <div class="flex-1"><p class="text-xs font-bold text-gray-900">Shift Malam</p><p class="text-[9px] text-muted">15:00 – 23:00</p></div>
          <svg class="w-4 h-4" id="arrowMalam" fill="none" stroke="#7B4F2E" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
          <span class="unavail-badge hidden" id="unavailMalam">Unavailable</span>
        </div>
      </div>
      <div class="flex items-center justify-center gap-1.5 py-1.5 rounded-lg" style="background:#F5F0EB;">
        <svg width="11" height="11" fill="none" stroke="#9C8B7E" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <p class="text-[9px] text-muted">Waktu sekarang: <strong class="text-gray-800" id="modalCurrentTime">—</strong></p>
      </div>
    </div>
  </div>
</div>

<script>
/* ═══════════════════════════════════════════════════════════════
   SHIFT CONFIGURATION (TIDAK BERUBAH)
═══════════════════════════════════════════════════════════════ */
const SHIFT_CONFIG = {
  pagi:  { nama:'Shift Pagi',  mulai:'09:00', selesai:'17:00', mulaiJam:9,  endHour:17, allowedFrom:9  },
  malam: { nama:'Shift Malam', mulai:'15:00', selesai:'23:00', mulaiJam:15, endHour:23, allowedFrom:15 },
};

const LS_SHIFT_KEY      = 'kashy_selected_shift';
const LS_SHIFT_DATE_KEY = 'kashy_shift_date';

function getTodayShift() {
  const today = new Date().toISOString().split('T')[0];
  const savedDate = localStorage.getItem(LS_SHIFT_DATE_KEY);
  if (savedDate !== today) {
    localStorage.removeItem(LS_SHIFT_KEY);
    localStorage.setItem(LS_SHIFT_DATE_KEY, today);
    return null;
  }
  return localStorage.getItem(LS_SHIFT_KEY);
}

function saveShiftChoice(shiftType) {
  const today = new Date().toISOString().split('T')[0];
  localStorage.setItem(LS_SHIFT_KEY, shiftType);
  localStorage.setItem(LS_SHIFT_DATE_KEY, today);
}

function isShiftAvailable(shiftType) {
  const now = new Date();
  const hour = now.getHours();
  const min = now.getMinutes();
  const nowMins = hour * 60 + min;
  const cfg = SHIFT_CONFIG[shiftType];
  if (!cfg) return false;
  const startMins = cfg.mulaiJam * 60;
  const endMins = cfg.endHour * 60;
  return (nowMins >= startMins && nowMins <= endMins);
}

/* ═══════════════════════════════════════════════════════════════
   RENDER SHIFT CARD + BADGE (hanya Aktif / Tidak Aktif)
═══════════════════════════════════════════════════════════════ */
let currentServerData = null;

function renderShiftCard(shiftType, serverData) {
  const mulaiEl   = document.getElementById('shiftMulai');
  const berakhirEl= document.getElementById('shiftBerakhir');
  const badgeWrap = document.getElementById('shiftBadge');
  const badgeDot  = document.getElementById('badgeDot');
  const badgeText = document.getElementById('badgeText');
  const selectBtnContainer = document.getElementById('selectShiftButtonContainer');
  const lateWarning = document.getElementById('lateWarning');

  if (serverData) {
    document.getElementById('infoNamaCard').textContent   = serverData.nama   || '—';
    document.getElementById('infoPosisiCard').textContent = serverData.posisi || 'Karyawan';
  }

  if (serverData && serverData.terlambat === true && shiftType !== null) {
    lateWarning.classList.remove('hidden');
  } else {
    lateWarning.classList.add('hidden');
  }

  if (!shiftType) {
    mulaiEl.textContent    = '--:--';
    berakhirEl.textContent = '--:--';
    if (selectBtnContainer) selectBtnContainer.classList.remove('hidden');
    badgeDot.className = 'w-2 h-2 rounded-full bg-red-500';
    badgeText.textContent = 'Tidak Aktif';
    badgeWrap.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-red-300 bg-red-100';
    return;
  }

  const cfg = SHIFT_CONFIG[shiftType];
  if (cfg) {
    mulaiEl.textContent    = cfg.mulai;
    berakhirEl.textContent = cfg.selesai;
  } else {
    mulaiEl.textContent    = '--:--';
    berakhirEl.textContent = '--:--';
  }
  if (selectBtnContainer) selectBtnContainer.classList.add('hidden');

  badgeDot.className = 'w-2 h-2 rounded-full bg-green-500 pulse-dot';
  badgeText.textContent = 'Aktif';
  badgeWrap.className = 'flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-green-300 bg-green-100';
}

/* ═══════════════════════════════════════════════════════════════
   LOAD SHIFT STATUS
═══════════════════════════════════════════════════════════════ */
async function loadShiftStatus() {
  try {
    const response = await fetch('{{ route("shift.status") }}');
    const data     = await response.json();
    currentServerData = data;
    
    if (data.shift_status === 'tidak_aktif') {
      localStorage.removeItem('kashy_selected_shift');
    }
    const opts = { weekday:'long', year:'numeric', month:'long', day:'numeric' };
    document.getElementById('shiftHariTanggal').textContent =
      new Date().toLocaleDateString('id-ID', opts);

    const savedShift = getTodayShift();
    renderShiftCard(savedShift, data);

    if (data.terlambat && savedShift !== null) {
      showToast('⚠️ Telat Absensi');
    }
  } catch (err) {
    console.error('Gagal load shift status:', err);
    const savedShift = getTodayShift();
    renderShiftCard(savedShift, null);
  }
}

/* ═══════════════════════════════════════════════════════════════
   MODAL SHIFT LOGIC
═══════════════════════════════════════════════════════════════ */
function openShiftModal() {
  updateModalTime();
  updateShiftOptionState();
  document.getElementById('shiftModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeShiftModal() {
  document.getElementById('shiftModal').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('shiftModal')?.addEventListener('click', function(e) {
  if (e.target === this) closeShiftModal();
});

function updateShiftOptionState() {
  const now = new Date();
  const hour = now.getHours();
  const min = now.getMinutes();
  const nowMins = hour * 60 + min;
  updateModalTime();

  const pagiStart = SHIFT_CONFIG.pagi.mulaiJam * 60;
  const pagiEnd   = SHIFT_CONFIG.pagi.endHour * 60;
  const pagiAvail = (nowMins >= pagiStart && nowMins <= pagiEnd);
  const optPagi = document.getElementById('optPagi');
  if (pagiAvail) {
    optPagi.classList.remove('disabled');
    optPagi.style.pointerEvents = '';
    document.getElementById('unavailPagi').classList.add('hidden');
    document.getElementById('arrowPagi').classList.remove('hidden');
  } else {
    optPagi.classList.add('disabled');
    optPagi.style.pointerEvents = 'none';
    document.getElementById('unavailPagi').classList.remove('hidden');
    document.getElementById('arrowPagi').classList.add('hidden');
  }

  const malamStart = SHIFT_CONFIG.malam.mulaiJam * 60;
  const malamEnd   = SHIFT_CONFIG.malam.endHour * 60;
  const malamAvail = (nowMins >= malamStart && nowMins <= malamEnd);
  const optMalam = document.getElementById('optMalam');
  if (malamAvail) {
    optMalam.classList.remove('disabled');
    optMalam.style.pointerEvents = '';
    document.getElementById('unavailMalam').classList.add('hidden');
    document.getElementById('arrowMalam').classList.remove('hidden');
  } else {
    optMalam.classList.add('disabled');
    optMalam.style.pointerEvents = 'none';
    document.getElementById('unavailMalam').classList.remove('hidden');
    document.getElementById('arrowMalam').classList.add('hidden');
  }
}

function updateModalTime() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2,'0');
  const m = String(now.getMinutes()).padStart(2,'0');
  document.getElementById('modalCurrentTime').textContent = h + ':' + m + ' WIB';
  const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
  const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  document.getElementById('modalDateLabel').textContent =
    days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();
}

function pilihShift(type) {
  if (!isShiftAvailable(type)) {
    showToast(`Shift ${type === 'pagi' ? 'Pagi' : 'Malam'} sudah tidak tersedia (melewati jam kerja).`);
    closeShiftModal();
    return;
  }
  saveShiftChoice(type);
  closeShiftModal();
  showToast('Shift ' + (type === 'pagi' ? 'Pagi' : 'Malam') + ' dipilih. Silakan lakukan absensi.');
  setTimeout(() => { window.location.href = '{{ route("absensi") }}'; }, 500);
}

/* ═══════════════════════════════════════════════════════════════
   TOAST & TO-DO LIST (TIDAK BERUBAH)
═══════════════════════════════════════════════════════════════ */
function showToast(msg) {
  const t = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  t.style.opacity = '1';
  t.style.transform = 'translateX(-50%) translateY(0)';
  clearTimeout(window._toastTimeout);
  window._toastTimeout = setTimeout(() => {
    t.style.opacity = '0';
    t.style.transform = 'translateX(-50%) translateY(16px)';
  }, 2600);
}

let todos = [];
function loadTodos() {
  const stored = localStorage.getItem('kashy_todos');
  try { todos = stored ? JSON.parse(stored) : []; } catch(e) { todos = []; }
  renderTodos();
}
function saveTodos() { localStorage.setItem('kashy_todos', JSON.stringify(todos)); }
function renderTodos() {
  const container = document.getElementById('todoListContainer');
  const emptyMsg  = document.getElementById('todoEmpty');
  if (!container) return;
  if (todos.length === 0) {
    container.innerHTML = '';
    emptyMsg.classList.remove('hidden');
    return;
  }
  emptyMsg.classList.add('hidden');
  container.innerHTML = todos.map(todo => `
    <div class="flex items-start justify-between gap-1.5 p-1.5 rounded-lg bg-stone-50 border border-stone-100 hover:bg-terra-xs transition">
      <div class="flex gap-1.5 flex-1 min-w-0">
        <input type="checkbox" ${todo.completed ? 'checked' : ''} onchange="toggleTodo(${todo.id})" class="w-3.5 h-3.5 mt-0.5 rounded flex-shrink-0">
        <span class="text-[10px] text-gray-800 break-words ${todo.completed ? 'line-through text-muted' : ''}">${escapeHtml(todo.text)}</span>
      </div>
      <button onclick="deleteTodo(${todo.id})" class="flex-shrink-0 text-red-400 hover:text-red-600">
        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
      </button>
    </div>
  `).join('');
}
function showTodoForm() { document.getElementById('todoForm').classList.remove('hidden'); document.getElementById('showTodoFormBtn').classList.add('hidden'); document.getElementById('todoInput').focus(); }
function hideTodoForm() { document.getElementById('todoForm').classList.add('hidden'); document.getElementById('showTodoFormBtn').classList.remove('hidden'); document.getElementById('todoInput').value = ''; }
function tambahTugas() {
  const text = document.getElementById('todoInput').value.trim();
  if (!text) { showToast('Tugas tidak boleh kosong!'); return; }
  todos.push({ id:Date.now(), text, completed:false });
  saveTodos(); renderTodos(); hideTodoForm();
  showToast('Tugas berhasil ditambahkan');
}
function toggleTodo(id) {
  const t = todos.find(t => t.id === id);
  if (t) { t.completed = !t.completed; saveTodos(); renderTodos(); }
}
function deleteTodo(id) { todos = todos.filter(t => t.id !== id); saveTodos(); renderTodos(); showToast('Tugas dihapus'); }
function deleteAllTodos() {
  if (!todos.length) { showToast('Tidak ada tugas untuk dihapus'); return; }
  const modal = document.getElementById('confirmDeleteAllModal');
  modal.classList.remove('hidden');
  const cb = document.getElementById('confirmDeleteAllBtn');
  const cn = document.getElementById('cancelDeleteAllBtn');
  const done = () => { modal.classList.add('hidden'); cb.onclick = null; cn.onclick = null; };
  cb.onclick = () => { todos = []; saveTodos(); renderTodos(); showToast('Semua tugas dihapus'); done(); };
  cn.onclick = done;
}
function escapeHtml(s) { return s.replace(/[&<>]/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;'}[m])); }

/* ═══════════════════════════════════════════════════════════════
   GREETING + INIT (TIDAK BERUBAH)
═══════════════════════════════════════════════════════════════ */
(function() {
  const h = new Date().getHours();
  let greet = 'Selamat Pagi';
  if (h >= 11 && h < 15) greet = 'Selamat Siang';
  else if (h >= 15 && h < 18) greet = 'Selamat Sore';
  else if (h >= 18) greet = 'Selamat Malam';
  document.getElementById('greetingText').innerHTML = `${greet}, <span class="text-terra">{{ Auth::user()->name ?? 'Karyawan' }}</span>`;
})();

document.addEventListener('DOMContentLoaded', () => {
  loadShiftStatus();
  loadTodos();
  const showBtn      = document.getElementById('showTodoFormBtn');
  const deleteAllBtn = document.getElementById('deleteAllBtn');
  if (showBtn)      showBtn.onclick      = showTodoForm;
  if (deleteAllBtn) deleteAllBtn.onclick = deleteAllTodos;

  setInterval(loadShiftStatus, 30000);
  setInterval(updateShiftOptionState, 60000);
});
</script>
</body>
</html>