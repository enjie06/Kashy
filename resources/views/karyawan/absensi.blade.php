<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Absensi – Kashy</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          terra: '#C8966C', 'terra-l': '#E5B18A', 'terra-ll': '#F0D7C7', 'terra-xs': '#FAF2EC',
          muted: '#9C8B7E', border: '#EAE0D6', bg: '#F5F0EB',
        },
        fontFamily: { 'poppins': ['Poppins', 'sans-serif'] },
        keyframes: {
          fadeUp: { from: { opacity: '0', transform: 'translateY(18px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
          clockTickStep: { '0%,100%': { opacity: '1' }, '50%': { opacity: '0.4' } },
          ringPulse: { '0%': { transform: 'scale(1)', opacity: '.55' }, '70%': { transform: 'scale(1.35)', opacity: '0' }, '100%': { transform: 'scale(1.35)', opacity: '0' } },
          shimmer: { '0%': { backgroundPosition: '-400px 0' }, '100%': { backgroundPosition: '400px 0' } },
          drawFp: { from: { strokeDashoffset: '400', opacity: '0' }, to: { strokeDashoffset: '0', opacity: '1' } },
          checkDraw: { from: { strokeDashoffset: '60' }, to: { strokeDashoffset: '0' } },
          successPop: { '0%': { transform: 'scale(0.7)', opacity: '0' }, '65%': { transform: 'scale(1.12)' }, '100%': { transform: 'scale(1)', opacity: '1' } }
        },
        animation: {
          'fade-up-1': 'fadeUp 0.45s cubic-bezier(0.22,1,0.36,1) 0.05s both',
          'fade-up-2': 'fadeUp 0.45s cubic-bezier(0.22,1,0.36,1) 0.12s both',
          'fade-up-3': 'fadeUp 0.45s cubic-bezier(0.22,1,0.36,1) 0.19s both',
          'fade-up-4': 'fadeUp 0.45s cubic-bezier(0.22,1,0.36,1) 0.26s both',
          'clock-sep': 'clockTickStep 1s step-end infinite',
          'ring-1': 'ringPulse 2.2s ease-out infinite',
          'ring-2': 'ringPulse 2.2s ease-out 0.55s infinite',
          'ring-3': 'ringPulse 2.2s ease-out 1.1s infinite',
          'shimmer-slow': 'shimmer 4s linear infinite',
          'draw-fp': 'drawFp 1.8s ease-out forwards',
          'check-draw': 'checkDraw 0.4s 0.3s ease forwards',
          'success-pop': 'successPop 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards'
        }
      }
    }
  }
</script>
<style>
  body { font-family: 'Poppins', sans-serif; }
  .scanning .fp-path { animation: drawFp 1.8s ease-out forwards; }
  .check-draw { stroke-dasharray: 60; stroke-dashoffset: 60; }
  .font-display { font-family: 'Poppins', sans-serif; }
  .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
  .bn-item.active .bn-icon { background: #F0D7C7; }
  .bn-item.active .bn-icon svg { stroke: #C8966C; }
  .bn-item.active .bn-label { color: #C8966C; font-weight: 600; }
  .fp-ring-track { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 130px; height: 130px; pointer-events: none; z-index: 20; }
  .fp-ring-track svg { transform: rotate(-90deg); width: 130px; height: 130px; }
  .fp-ring-bg { fill: none; stroke: rgba(255,255,255,.15); stroke-width: 4; }
  .fp-ring-fill { fill: none; stroke: #fff; stroke-width: 4; stroke-linecap: round; stroke-dasharray: 364; stroke-dashoffset: 364; transition: stroke-dashoffset linear; filter: drop-shadow(0 0 6px rgba(255,255,255,.7)); }
  @keyframes glowPulse { 0%,100% { box-shadow: 0 0 20px rgba(200,150,108,.4); } 50% { box-shadow: 0 0 50px rgba(229,177,138,.8); } }
  .scanning-glow { animation: glowPulse .7s ease-in-out infinite; }
  #pulangOverlay { opacity: 0; pointer-events: none; transition: opacity .25s ease; }
  #pulangOverlay.open { opacity: 1; pointer-events: all; }
  #pulangDialog { transform: scale(.92) translateY(12px); transition: transform .3s cubic-bezier(0.34,1.56,.64,1); }
  #pulangOverlay.open #pulangDialog { transform: scale(1) translateY(0); }
</style>
</head>
@include('karyawan.components.navbar')
<body class="bg-bg min-h-screen flex flex-col font-poppins">
@include('karyawan.components.topbar')

<main class="flex-1 overflow-y-auto pb-28">
  <div class="max-w-md mx-auto px-4 pt-5 space-y-5">

    <!-- Jam & Tanggal -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden fade-up-1 card-hover">
      <div class="shimmer-bar h-1 w-full bg-gradient-to-r from-terra via-terra-l via-terra-ll to-terra bg-[length:200%] animate-shimmer-slow"></div>
      <div class="px-4 py-4 text-center">
        <div class="font-poppins text-3xl sm:text-4xl font-bold text-gray-900 leading-none mb-1" id="liveClock">--<span class="animate-clock-sep">:</span>-- <span class="text-xl sm:text-2xl font-semibold text-muted">--</span></div>
        <p class="text-xs text-muted mt-1" id="liveDate">--</p>
      </div>
    </div>

    <!-- Fingerprint Scanner Card -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden fade-up-2 card-hover">
      <div class="px-4 py-5 flex flex-col items-center">
        <div class="relative flex items-center justify-center mb-5" style="width:200px; height:200px;">
          <div class="absolute rounded-full border-2 border-terra/30 inset-0 animate-ring-1"></div>
          <div class="absolute rounded-full border-2 border-terra/20 animate-ring-2" style="inset:-8px;border-radius:9999px;"></div>
          <div class="absolute rounded-full border-2 border-terra/10 animate-ring-3" style="inset:-18px;border-radius:9999px;"></div>
          <div id="fpGlow" class="absolute inset-4 rounded-full transition-all duration-500" style="background:radial-gradient(circle,#E5B18A 0%,#C8966C 60%,#a07050 100%);"></div>
          <div class="fp-ring-track">
            <svg viewBox="0 0 130 130">
              <circle class="fp-ring-bg" cx="65" cy="65" r="58"/>
              <circle class="fp-ring-fill" cx="65" cy="65" r="58" id="fpRingFill"/>
            </svg>
          </div>
          <button id="fpBtn" class="relative z-10 flex flex-col items-center justify-center gap-1 select-none focus:outline-none rounded-full" style="width:110px; height:110px; background:transparent; touch-action:none;" aria-label="Scan sidik jari">
            <svg width="48" height="48" viewBox="0 0 64 64" fill="none" class="drop-shadow-sm pointer-events-none">
              <path class="fp-path" d="M10 40 C8 18, 56 18, 54 40" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none"/>
              <path class="fp-path" d="M14 44 C12 24, 52 24, 50 44" stroke="white" stroke-width="2" stroke-linecap="round" fill="none" stroke-dasharray="3 1"/>
              <path class="fp-path" d="M18 47 C16 30, 48 30, 46 47" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
              <path class="fp-path" d="M22 49 C20 36, 44 36, 42 49" stroke="white" stroke-width="2" stroke-linecap="round" fill="none" stroke-dasharray="2 1"/>
              <path class="fp-path" d="M26 50.5 C24 40, 40 40, 38 50.5" stroke="white" stroke-width="1.8" stroke-linecap="round" fill="none"/>
              <path class="fp-path" d="M30 52 C29 44, 35 44, 34 52" stroke="white" stroke-width="1.8" stroke-linecap="round" fill="none"/>
              <path class="fp-path" d="M10 40 C10 50, 14 56, 18 58" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
              <path class="fp-path" d="M54 40 C54 50, 50 56, 46 58" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
              <path class="fp-path" d="M26 16 Q32 8 38 16" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none"/>
            </svg>
            <div class="text-center leading-tight pointer-events-none" id="fpTextGroup">
              <p id="fpLabel" class="text-[9px] font-bold text-white tracking-widest uppercase">Tahan</p>
              <p id="fpSub" class="text-[8px] text-white/70 mt-0.5">untuk scan</p>
            </div>
          </button>
          <div id="scanPercent" class="absolute inset-0 flex items-center justify-center z-20 pointer-events-none hidden">
            <span class="text-white font-bold text-xl">0%</span>
          </div>
          <div id="successCircle" class="absolute inset-4 rounded-full flex flex-col items-center justify-center hidden z-30 text-center" style="background:radial-gradient(circle,#34d399 0%,#10b981 60%,#059669 100%);">
            <div class="w-14 h-14 rounded-full border-2 border-white flex items-center justify-center mb-1">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                <path d="M5 13L10 18L19 7" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <p class="text-white font-bold text-xs">Terverifikasi</p>
          </div>
        </div>
        <div class="text-center space-y-1 mb-2" id="scanStatus">
          <p class="text-sm font-semibold text-gray-700" id="statusTitle">Memuat data...</p>
          <p class="text-xs text-muted" id="statusSub">Mohon tunggu</p>
        </div>
      </div>
    </div>

    <!-- Status Hari Ini -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden fade-up-3 card-hover">
      <div class="px-4 py-4">
        <p class="text-[10px] font-bold tracking-[.14em] uppercase text-muted mb-3">Status Hari Ini</p>
        <div class="grid grid-cols-2 gap-3">
          <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-stone-50 border border-stone-100">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg></div>
            <div><p class="text-[10px] text-muted font-medium uppercase tracking-wide">Masuk</p><p class="text-sm font-bold text-gray-900" id="todayMasuk">—</p></div>
          </div>
          <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-stone-50 border border-stone-100">
            <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg></div>
            <div><p class="text-[10px] text-muted font-medium uppercase tracking-wide">Pulang</p><p class="text-sm font-bold text-gray-900" id="todayPulang">—</p></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent History -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden fade-up-4 card-hover">
      <div class="px-4 pt-4 pb-2">
        <div class="flex items-center justify-between mb-3">
          <h3 class="font-semibold text-gray-900 text-sm">Riwayat Absensi</h3>
          <button onclick="window.location.href='{{ route('historyabsensi') }}'" class="text-[10px] text-terra font-medium hover:underline">Lihat semua</button>
        </div>
        <div class="space-y-2" id="historyList">
          <div class="flex justify-center py-6" id="historyLoading">
            <svg class="animate-spin h-5 w-5 text-terra" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
          </div>
          <div id="historyContent" class="hidden space-y-1.5"></div>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Modal Belum Pilih Shift -->
<div id="noShiftOverlay" class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4" style="opacity:0; pointer-events:none; transition: opacity .25s ease;">
  <div id="noShiftDialog" class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden" style="transform: scale(.92) translateY(12px); transition: transform .3s cubic-bezier(0.34,1.56,.64,1);">
    <div class="h-1 w-full bg-gradient-to-r from-terra via-terra-l via-terra-ll to-terra bg-[length:200%] animate-shimmer-slow"></div>
    <div class="px-5 pt-5 pb-6 text-center">
      <div class="w-12 h-12 rounded-xl bg-terra-xs flex items-center justify-center mx-auto mb-3">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2">
          <rect x="3" y="4" width="18" height="18" rx="2"/>
          <path d="M16 2v4M8 2v4M3 10h18"/>
          <path d="m9 16 2 2 4-4"/>
        </svg>
      </div>
      <h3 class="font-poppins text-lg font-bold text-gray-900 mb-1">Belum Pilih Shift</h3>
      <p class="text-xs text-muted">Kamu belum memilih shift hari ini. Pilih shift terlebih dahulu di dashboard.</p>
      <div class="flex gap-3 mt-5">
        <button onclick="closeNoShiftModal()" class="flex-1 py-2.5 rounded-lg border border-border text-xs font-semibold text-muted hover:bg-stone-50 transition-colors">Nanti</button>
        <button onclick="goToDashboard()" class="flex-1 py-2.5 rounded-lg text-white text-xs font-semibold transition-colors" style="background:#C8966C;">Pilih Shift</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Absen Pulang -->
<div id="pulangOverlay" class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
  <div id="pulangDialog" class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="h-1 w-full bg-gradient-to-r from-terra via-terra-l via-terra-ll to-terra bg-[length:200%] animate-shimmer-slow"></div>
    <div class="px-5 pt-5 pb-6 text-center">
      <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center mx-auto mb-3">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </div>
      <h3 class="font-poppins text-lg font-bold text-gray-900 mb-1">Akhiri Shift Anda Hari ini?</h3>
      <p class="text-xs text-muted">Absensi pulang akan dicatat pada waktu ini dan tidak bisa diubah.</p>
      <div class="flex gap-3 mt-5">
        <button onclick="closePulangModal()" class="flex-1 py-2.5 rounded-lg border border-border text-xs font-semibold text-muted hover:bg-stone-50 transition-colors">Batal</button>
        <button onclick="confirmPulang()" class="flex-1 py-2.5 rounded-lg bg-red-500 text-white text-xs font-semibold hover:bg-red-600 transition-colors">Ya, Pulang</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[60] px-4 py-2.5 rounded-full shadow-xl flex items-center gap-2 pointer-events-none transition-all duration-300 opacity-0 translate-y-4 bg-gray-900 text-white text-xs font-medium"><span id="toastMsg">—</span></div>

<script>
let shiftStatus = 'tidak_aktif';
let checkInTime = null;
let checkOutTime = null;
let scanState = 'idle';
let absenType = null;

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

const CIRCUMFERENCE = 2 * Math.PI * 58;
function setRingProgress(pct) {
  const offset = CIRCUMFERENCE * (1 - pct);
  const fill = document.getElementById('fpRingFill');
  fill.style.strokeDashoffset = offset;
  fill.style.strokeDasharray = CIRCUMFERENCE;
}
function resetRing() { setRingProgress(0); }

function getSelectedShift() {
  const today = new Date().toISOString().split('T')[0];
  const savedDate = localStorage.getItem('kashy_shift_date');
  if (savedDate !== today) {
    localStorage.removeItem('kashy_selected_shift');
    return null;
  }
  return localStorage.getItem('kashy_selected_shift');
}

function setFpBtnDisabled(disabled) {
  const fpBtn = document.getElementById('fpBtn');
  const fpGlow = document.getElementById('fpGlow');
  fpBtn.disabled = disabled;
  fpBtn.style.opacity = disabled ? '0.45' : '1';
  fpGlow.style.filter = disabled ? 'grayscale(1)' : '';
}

// ==================== MODAL BELUM PILIH SHIFT ====================
function openNoShiftModal() {
  const overlay = document.getElementById('noShiftOverlay');
  const dialog  = document.getElementById('noShiftDialog');
  overlay.style.opacity = '1';
  overlay.style.pointerEvents = 'all';
  dialog.style.transform = 'scale(1) translateY(0)';
  document.body.style.overflow = 'hidden';
}
function closeNoShiftModal() {
  const overlay = document.getElementById('noShiftOverlay');
  const dialog  = document.getElementById('noShiftDialog');
  overlay.style.opacity = '0';
  overlay.style.pointerEvents = 'none';
  dialog.style.transform = 'scale(.92) translateY(12px)';
  document.body.style.overflow = '';
}
function goToDashboard() {
  window.location.href = '{{ route("dashboard-karyawan") }}';
}

// ==================== MODAL KONFIRMASI PULANG ====================
function openPulangModal() {
  document.getElementById('pulangOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closePulangModal() {
  document.getElementById('pulangOverlay').classList.remove('open');
  document.body.style.overflow = '';
}
function confirmPulang() {
  closePulangModal();
  startFakeScan();
}

// ==================== LOAD HISTORY ====================
async function loadHistory() {
  try {
    const response = await fetch('{{ route("shift.recent-history") }}');
    const histories = await response.json();
    const container = document.getElementById('historyContent');
    const loadingEl = document.getElementById('historyLoading');
    if (histories.length === 0) {
      container.innerHTML = '<p class="text-center text-muted text-xs py-4">Belum ada riwayat absensi</p>';
    } else {
      container.innerHTML = histories.map(h => `
        <div class="flex items-center justify-between p-2.5 rounded-xl bg-stone-50 border border-stone-100">
          <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 rounded-full bg-terra-xs flex items-center justify-center">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </div>
            <div>
              <p class="text-xs font-medium text-gray-800">${h.date}</p>
              <p class="text-[9px] text-muted">${h.check_in} - ${h.check_out}</p>
            </div>
          </div>
          <span class="text-[9px] px-2 py-0.5 rounded-full ${h.status==='Terlambat'?'bg-yellow-100 text-yellow-700':'bg-green-100 text-green-700'} font-medium">${h.status}</span>
        </div>
      `).join('');
    }
    loadingEl.classList.add('hidden');
    container.classList.remove('hidden');
  } catch (err) {
    console.error('Gagal load history:', err);
    document.getElementById('historyLoading').innerHTML = '<p class="text-center text-muted text-xs py-4">Gagal memuat riwayat</p>';
  }
}

// ==================== LOAD STATUS ====================
async function loadData() {
  try {
    const response = await fetch('{{ route("shift.status") }}');
    const data = await response.json();
    shiftStatus  = data.shift_status;
    checkInTime  = data.check_in;
    checkOutTime = data.check_out;
    if (checkInTime)  document.getElementById('todayMasuk').textContent  = checkInTime  + ' WIB';
    if (checkOutTime) document.getElementById('todayPulang').textContent = checkOutTime + ' WIB';
    updateUIByServerStatus();
    await loadHistory();
  } catch (err) {
    console.error('Gagal load data:', err);
    showToast('Gagal memuat data absensi');
  }
}

function updateUIByServerStatus() {
  const statusTitle   = document.getElementById('statusTitle');
  const statusSub     = document.getElementById('statusSub');
  const selectedShift = getSelectedShift();

  if (shiftStatus === 'selesai') {
    statusTitle.textContent = 'Absensi Selesai';
    statusSub.textContent   = 'Terima kasih, Anda sudah absen hari ini';
    setFpBtnDisabled(true);
    absenType = null;
    return;
  }

  if (!selectedShift) {
    statusTitle.textContent = 'Belum Pilih Shift';
    statusSub.textContent   = 'Pilih shift di dashboard terlebih dahulu';
    setFpBtnDisabled(true);
    absenType = null;
    return;
  }

  if (shiftStatus === 'tidak_aktif') {
    statusTitle.textContent = 'Shift Belum Aktif';
    statusSub.textContent   = 'Tekan untuk memulai shift';
    setFpBtnDisabled(false);
    absenType = null;
  } else if (shiftStatus === 'aktif') {
    if (checkInTime && !checkOutTime) {
      statusTitle.textContent = 'Siap Absen Pulang';
      statusSub.textContent   = 'Tekan untuk mengakhiri shift';
      setFpBtnDisabled(false);
      absenType = 'pulang';
    } else if (!checkInTime) {
      statusTitle.textContent = 'Siap Absen Masuk';
      statusSub.textContent   = 'Tekan untuk memulai shift';
      setFpBtnDisabled(false);
      absenType = 'masuk';
    } else {
      setFpBtnDisabled(true);
    }
  }
}

// ==================== SCAN & BACKEND ====================
async function completeScan() {
  try {
    const selectedShift = getSelectedShift();
    const response = await fetch('{{ route("shift.handle") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ action: absenType, shift_type: selectedShift })
    });
    const result = await response.json();
    if (result.success) {
      document.getElementById('fpGlow').style.opacity = '0';
      const sc = document.getElementById('successCircle');
      sc.classList.remove('hidden');
      sc.style.display = 'flex';
      sc.classList.add('animate-success-pop');

      if (absenType === 'masuk') {
        const serverTime = result.check_in;
        document.getElementById('statusTitle').textContent = '✓ Absen Masuk Berhasil';
        document.getElementById('statusSub').textContent   = `Tercatat pukul ${serverTime} WIB`;
        document.getElementById('todayMasuk').textContent  = serverTime + ' WIB';
        showToast(result.message);
        localStorage.setItem('shift_updated', Date.now());
        setTimeout(() => { window.location.href = '{{ route("dashboard-karyawan") }}'; }, 1400);
      } else if (absenType === 'pulang') {
        const serverTime = result.check_out;
        document.getElementById('statusTitle').textContent = '✓ Absen Pulang Berhasil';
        document.getElementById('statusSub').textContent   = `Tercatat pukul ${serverTime} WIB`;
        document.getElementById('todayPulang').textContent = serverTime + ' WIB';
        showToast(result.message);
        localStorage.setItem('shift_updated', Date.now());
        setTimeout(() => { window.location.href = '{{ route("dashboard-karyawan") }}'; }, 1400);
      }
    } else {
      showToast(result.message || 'Terjadi kesalahan');
      resetScanner();
    }
  } catch (err) {
    console.error('Error:', err);
    showToast('Gagal melakukan absensi');
    resetScanner();
  }
  scanState = 'done';
  document.getElementById('fpBtn').style.pointerEvents = '';
  document.getElementById('fpGlow').style.boxShadow = '';
}

function resetScanner() {
  scanState = 'idle';
  document.getElementById('fpGlow').style.opacity = '1';
  document.getElementById('fpGlow').style.filter  = '';
  document.getElementById('successCircle').classList.add('hidden');
  document.getElementById('fpGlow').style.boxShadow = '';
  resetRing();
  updateUIByServerStatus();
  const scanPercentDiv = document.getElementById('scanPercent');
  if (scanPercentDiv) scanPercentDiv.classList.add('hidden');
  const textGroup = document.getElementById('fpTextGroup');
  if (textGroup) textGroup.style.display = 'flex';
}

// ==================== PROGRESS SCAN UI ====================
let scanInterval = null;
function startFakeScan() {
  if (scanState !== 'idle') return;
  if (document.getElementById('fpBtn').disabled) return;
  if (checkInTime && checkOutTime) { showToast('✅ Anda sudah absen hari ini.'); return; }

  scanState = 'scanning';
  const scanPercentDiv = document.getElementById('scanPercent');
  scanPercentDiv.classList.remove('hidden');
  document.querySelector('#fpBtn svg').style.display = 'none';
  document.getElementById('fpTextGroup').style.display = 'none';
  document.getElementById('fpGlow').classList.add('scanning-glow');
  document.getElementById('fpGlow').style.filter = '';
  document.getElementById('statusTitle').textContent = 'Memindai Sidik Jari';
  document.getElementById('statusSub').textContent   = 'Mohon tunggu...';
  document.getElementById('fpBtn').style.pointerEvents = 'none';

  let progress = 0;
  setRingProgress(0);
  if (scanInterval) clearInterval(scanInterval);
  scanInterval = setInterval(() => {
    progress += 2;
    if (progress > 100) progress = 100;
    setRingProgress(progress / 100);
    scanPercentDiv.querySelector('span').textContent = progress + '%';
    if (progress >= 100) {
      clearInterval(scanInterval);
      scanInterval = null;
      document.getElementById('fpGlow').classList.remove('scanning-glow');
      document.querySelector('#fpBtn svg').style.display = 'block';
      document.getElementById('fpTextGroup').style.display = 'flex';
      scanPercentDiv.classList.add('hidden');
      completeScan();
    }
  }, 50);
}

// ==================== CLOCK & INIT ====================
function updateClock() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2,'0');
  const m = String(now.getMinutes()).padStart(2,'0');
  const s = String(now.getSeconds()).padStart(2,'0');
  document.getElementById('liveClock').innerHTML = `${h}<span class="animate-clock-sep">:</span>${m}<span class="animate-clock-sep">:</span>${s}`;
  document.getElementById('liveDate').textContent = now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
}
updateClock();
setInterval(updateClock, 1000);

document.addEventListener('DOMContentLoaded', () => {
  resetRing();
  loadData();

  document.getElementById('pulangOverlay').addEventListener('click', function(e) {
    if (e.target === this) closePulangModal();
  });
  document.getElementById('noShiftOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeNoShiftModal();
  });

  document.getElementById('fpBtn').addEventListener('click', () => {
    if (scanState !== 'idle') return;

    if (!getSelectedShift()) {
      openNoShiftModal();
      return;
    }

    if (document.getElementById('fpBtn').disabled) return;
    if (checkInTime && checkOutTime) { showToast('✅ Anda sudah absen hari ini.'); return; }

    if (!checkInTime) {
      absenType = 'masuk';
      startFakeScan();
    } else if (checkInTime && !checkOutTime) {
      absenType = 'pulang';
      openPulangModal();
    }
  });
});
</script>
</body>
</html>