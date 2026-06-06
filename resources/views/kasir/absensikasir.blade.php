<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Absensi Kasir – Kashy</title>
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
          'success-pop': 'successPop 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards'
        }
      }
    }
  }
</script>
<style>
  body { font-family: 'Poppins', sans-serif; }
  .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
  .fp-ring-track { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 130px; height: 130px; pointer-events: none; z-index: 20; }
  .fp-ring-track svg { transform: rotate(-90deg); width: 130px; height: 130px; }
  .fp-ring-bg { fill: none; stroke: rgba(255,255,255,.15); stroke-width: 4; }
  .fp-ring-fill { fill: none; stroke: #fff; stroke-width: 4; stroke-linecap: round; stroke-dasharray: 364; stroke-dashoffset: 364; transition: stroke-dashoffset linear; filter: drop-shadow(0 0 6px rgba(255,255,255,.7)); }
  @keyframes glowPulse { 0%,100% { box-shadow: 0 0 20px rgba(200,150,108,.4); } 50% { box-shadow: 0 0 50px rgba(229,177,138,.8); } }
  .scanning-glow { animation: glowPulse .7s ease-in-out infinite; }

  /* Modal pulang */
  #pulangOverlay { opacity: 0; pointer-events: none; transition: opacity .25s ease; }
  #pulangOverlay.open { opacity: 1; pointer-events: all; }
  #pulangDialog { transform: scale(.92) translateY(12px); transition: transform .3s cubic-bezier(0.34,1.56,.64,1); }
  #pulangOverlay.open #pulangDialog { transform: scale(1) translateY(0); }
</style>
</head>
<body class="bg-bg min-h-screen flex flex-col font-poppins">

<nav class="sticky top-0 z-50 bg-gray-900 px-5 py-3.5 flex items-center justify-center shadow-md">
  <span class="font-bold text-white text-xl tracking-wide">Kashy</span>
</nav>

<main class="flex-1 overflow-y-auto pb-10">
  <div class="max-w-md mx-auto px-4 pt-5 space-y-5">

    <!-- Jam & Tanggal -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden animate-fade-up-1 card-hover">
      <div class="h-1 w-full bg-gradient-to-r from-terra via-terra-l via-terra-ll to-terra bg-[length:200%] animate-shimmer-slow"></div>
      <div class="px-4 py-4 text-center">
        <div class="font-poppins text-3xl sm:text-4xl font-bold text-gray-900 leading-none mb-1" id="liveClock">
          --<span class="animate-clock-sep">:</span>--<span class="animate-clock-sep">:</span>--
        </div>
        <p class="text-xs text-muted mt-1" id="liveDate">--</p>
      </div>
    </div>

    <!-- Fingerprint Scanner -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden animate-fade-up-2 card-hover">
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
          <button id="fpBtn"
            class="relative z-10 flex flex-col items-center justify-center gap-1 select-none focus:outline-none rounded-full"
            style="width:110px; height:110px; background:transparent; touch-action:none;"
            aria-label="Scan sidik jari">
            <svg width="48" height="48" viewBox="0 0 64 64" fill="none" class="drop-shadow-sm pointer-events-none" id="fpIcon">
              <path d="M10 40 C8 18, 56 18, 54 40" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none"/>
              <path d="M14 44 C12 24, 52 24, 50 44" stroke="white" stroke-width="2" stroke-linecap="round" fill="none" stroke-dasharray="3 1"/>
              <path d="M18 47 C16 30, 48 30, 46 47" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
              <path d="M22 49 C20 36, 44 36, 42 49" stroke="white" stroke-width="2" stroke-linecap="round" fill="none" stroke-dasharray="2 1"/>
              <path d="M26 50.5 C24 40, 40 40, 38 50.5" stroke="white" stroke-width="1.8" stroke-linecap="round" fill="none"/>
              <path d="M30 52 C29 44, 35 44, 34 52" stroke="white" stroke-width="1.8" stroke-linecap="round" fill="none"/>
              <path d="M10 40 C10 50, 14 56, 18 58" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
              <path d="M54 40 C54 50, 50 56, 46 58" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
              <path d="M26 16 Q32 8 38 16" stroke="white" stroke-width="2.2" stroke-linecap="round" fill="none"/>
            </svg>
            <div class="text-center leading-tight pointer-events-none" id="fpTextGroup">
              <p id="fpLabel" class="text-[9px] font-bold text-white tracking-widest uppercase">Tahan</p>
              <p id="fpSub" class="text-[8px] text-white/70 mt-0.5">untuk scan</p>
            </div>
          </button>
          <div id="scanPercent" class="absolute inset-0 flex items-center justify-center z-20 pointer-events-none hidden">
            <span class="text-white font-bold text-xl">0%</span>
          </div>
          <div id="successCircle" class="absolute inset-4 rounded-full flex flex-col items-center justify-center hidden z-30 text-center"
            style="background:radial-gradient(circle,#34d399 0%,#10b981 60%,#059669 100%);">
            <div class="w-14 h-14 rounded-full border-2 border-white flex items-center justify-center mb-1">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                <path d="M5 13L10 18L19 7" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <p class="text-white font-bold text-xs">Terverifikasi</p>
          </div>
        </div>
        <div class="text-center space-y-1 mb-2">
          <p class="text-sm font-semibold text-gray-700" id="statusTitle">Memuat data...</p>
          <p class="text-xs text-muted" id="statusSub">Mohon tunggu sebentar</p>
        </div>
      </div>
    </div>

    <!-- Status Hari Ini -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden animate-fade-up-3 card-hover">
      <div class="px-4 py-4">
        <p class="text-[10px] font-bold tracking-[.14em] uppercase text-muted mb-3">Status Hari Ini</p>
        <div class="grid grid-cols-2 gap-3">
          <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-stone-50 border border-stone-100">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
              </svg>
            </div>
            <div>
              <p class="text-[10px] text-muted font-medium uppercase tracking-wide">Masuk</p>
              <p class="text-sm font-bold text-gray-900" id="todayMasuk">—</p>
            </div>
          </div>
          <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-stone-50 border border-stone-100">
            <div class="w-8 h-8 rounded-xl bg-red-50 flex items-center justify-center">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
            </div>
            <div>
              <p class="text-[10px] text-muted font-medium uppercase tracking-wide">Pulang</p>
              <p class="text-sm font-bold text-gray-900" id="todayPulang">—</p>
            </div>
          </div>
        </div>
        <div id="lateWarning" class="hidden mt-2 text-[10px] text-red-600 bg-red-50 p-2 rounded-xl flex items-center gap-1.5">
          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="lateWarningText">⚠️ Terlambat</span>
        </div>
      </div>
    </div>

    <!-- Riwayat Absensi -->
    <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden animate-fade-up-4 card-hover">
      <div class="px-4 pt-4 pb-2">
        <div class="flex items-center justify-between mb-3">
          <h3 class="font-semibold text-gray-900 text-sm">Riwayat Absensi</h3>
          <button onclick="window.location.href='{{ route('kasir.historyabsensi') }}'" class="text-[10px] text-terra font-medium hover:underline">Lihat semua</button>
        </div>
        <div class="space-y-2" id="historyList">
          <div class="flex justify-center py-6" id="historyLoading">
            <svg class="animate-spin h-5 w-5 text-terra" viewBox="0 0 24 24" fill="none">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
          </div>
          <div id="historyContent" class="hidden space-y-1.5"></div>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Modal Konfirmasi Absen Pulang -->
<div id="pulangOverlay" class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
  <div id="pulangDialog" class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden">
    <div class="h-1 w-full bg-gradient-to-r from-terra via-terra-l via-terra-ll to-terra bg-[length:200%] animate-shimmer-slow"></div>
    <div class="px-5 pt-5 pb-6 text-center">
      <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center mx-auto mb-3">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </div>
      <h3 class="font-poppins text-lg font-bold text-gray-900 mb-1">Akhiri shift hari ini?</h3>
      <p class="text-xs text-muted">Absensi pulang akan dicatat pada waktu ini dan tidak bisa diubah.</p>
      <div class="flex gap-3 mt-5">
        <button onclick="closePulangModal()" class="flex-1 py-2.5 rounded-lg border border-border text-xs font-semibold text-muted hover:bg-stone-50 transition-colors">Batal</button>
        <button onclick="confirmPulang()" class="flex-1 py-2.5 rounded-lg bg-red-500 text-white text-xs font-semibold hover:bg-red-600 transition-colors">Ya, pulang</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[60] px-4 py-2.5 rounded-full shadow-xl flex items-center gap-2 pointer-events-none transition-all duration-300 opacity-0 translate-y-4 bg-gray-900 text-white text-xs font-medium">
  <span id="toastMsg">—</span>
</div>

<script>
const SHIFT_CONFIG = {
  pagi:  { nama: 'Shift Pagi',  mulai: '09:00', selesai: '17:00', mulaiJam: 9,  selesaiJam: 17 },
  malam: { nama: 'Shift Malam', mulai: '15:00', selesai: '23:00', mulaiJam: 15, selesaiJam: 23 },
};

const LS_SHIFT_KEY      = 'kashy_kasir_selected_shift';
const LS_SHIFT_DATE_KEY = 'kashy_kasir_shift_date';

let checkInTime  = null;
let checkOutTime = null;
let scanState    = 'idle';
let absenType    = null;
let selectedShift = null;
let scanInterval  = null;

function showToast(msg) {
  const t = document.getElementById('toast');
  const toastMsg = document.getElementById('toastMsg');
  if (!t || !toastMsg) return;
  toastMsg.textContent = msg;
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
  const fill = document.getElementById('fpRingFill');
  if (fill) fill.style.strokeDashoffset = CIRCUMFERENCE * (1 - pct);
}
function resetRing() { setRingProgress(0); }

function setFpBtnDisabled(disabled) {
  const fpBtn = document.getElementById('fpBtn');
  const fpGlow = document.getElementById('fpGlow');
  if (fpBtn) {
    fpBtn.disabled = disabled;
    fpBtn.style.opacity = disabled ? '0.45' : '1';
  }
  if (fpGlow) fpGlow.style.filter = disabled ? 'grayscale(1)' : '';
}

function getShiftFromStorage() {
  const today     = new Date().toISOString().split('T')[0];
  const savedDate = localStorage.getItem(LS_SHIFT_DATE_KEY);
  if (savedDate !== today) { 
    localStorage.removeItem(LS_SHIFT_KEY); 
    return null; 
  }
  return localStorage.getItem(LS_SHIFT_KEY);
}

function updateUIByStatus() {
  const statusTitle = document.getElementById('statusTitle');
  const statusSub   = document.getElementById('statusSub');
  
  const storedShift = getShiftFromStorage();
  if (storedShift && SHIFT_CONFIG[storedShift]) {
    selectedShift = storedShift;
  } else {
    selectedShift = null;
  }

  // Jika sudah absen masuk dan pulang
  if (checkInTime && checkOutTime) {
    if (statusTitle) statusTitle.textContent = 'Absensi selesai';
    if (statusSub) statusSub.textContent = 'Anda sudah absen masuk dan pulang hari ini';
    setFpBtnDisabled(true);
    absenType = null;
    return;
  }
  
  // Jika belum pilih shift
  if (!selectedShift) {
    if (statusTitle) statusTitle.textContent = 'Shift belum dipilih';
    if (statusSub) statusSub.textContent = 'Kembali ke dashboard untuk memilih shift';
    setFpBtnDisabled(true);
    absenType = null;
    showToast('Silakan pilih shift di dashboard terlebih dahulu');
    setTimeout(() => { window.location.href = '{{ route("dashboard-kasir") }}'; }, 1800);
    return;
  }
  
  // Jika sudah absen masuk tapi belum pulang
  if (checkInTime && !checkOutTime) {
    if (statusTitle) statusTitle.textContent = 'Siap absen pulang';
    if (statusSub) statusSub.textContent = 'Tekan tombol untuk mengakhiri shift';
    setFpBtnDisabled(false);
    absenType = 'pulang';
    return;
  }
  
  // Belum absen sama sekali
  const cfg = SHIFT_CONFIG[selectedShift];
  if (statusTitle) statusTitle.textContent = `Siap absen masuk · ${cfg ? cfg.nama : ''}`;
  if (statusSub) statusSub.textContent = 'Tekan tombol untuk memulai shift';
  setFpBtnDisabled(false);
  absenType = 'masuk';
}

function openPulangModal() {
  const overlay = document.getElementById('pulangOverlay');
  if (overlay) {
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
}

function closePulangModal() {
  const overlay = document.getElementById('pulangOverlay');
  if (overlay) {
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }
}

function confirmPulang() {
  closePulangModal();
  startFakeScan();
}

async function loadHistory() {
  try {
    const response = await fetch('{{ route("shift.full-history") }}');
    const result   = await response.json();
    const container  = document.getElementById('historyContent');
    const loadingEl  = document.getElementById('historyLoading');
    const data = Array.isArray(result) ? result : (result.histories || []);
    
    if (!container || !loadingEl) return;
    
    if (data.length === 0) {
      container.innerHTML = '<p class="text-center text-muted text-xs py-4">Belum ada riwayat absensi</p>';
    } else {
      container.innerHTML = data.slice(0, 7).map(h => {
        const statusColor = h.status === 'Terlambat'
          ? 'bg-yellow-100 text-yellow-700'
          : h.status === 'Tidak Hadir'
            ? 'bg-red-100 text-red-600'
            : 'bg-green-100 text-green-700';
        return `
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-stone-50 border border-stone-100">
            <div class="flex items-center gap-2.5">
              <div class="w-7 h-7 rounded-full bg-terra-xs flex items-center justify-center">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-800">${h.date || '-'}</p>
                <p class="text-[9px] text-muted">${h.check_in || '-'} – ${h.check_out || '-'}</p>
              </div>
            </div>
            <span class="text-[9px] px-2 py-0.5 rounded-full font-medium ${statusColor}">${h.status || 'Hadir'}</span>
          </div>`;
      }).join('');
    }
    loadingEl.classList.add('hidden');
    container.classList.remove('hidden');
  } catch (err) {
    console.error('Load history error:', err);
    const loadingEl = document.getElementById('historyLoading');
    if (loadingEl) {
      loadingEl.innerHTML = '<p class="text-center text-muted text-xs py-4">Gagal memuat riwayat</p>';
    }
  }
}

async function loadData() {
  try {
    const absenRes  = await fetch('{{ route("shift.status") }}');
    const absenData = await absenRes.json();
    checkInTime  = absenData.check_in  || null;
    checkOutTime = absenData.check_out || null;
    
    const todayMasuk = document.getElementById('todayMasuk');
    const todayPulang = document.getElementById('todayPulang');
    if (todayMasuk && checkInTime) todayMasuk.textContent = checkInTime + ' WIB';
    if (todayPulang && checkOutTime) todayPulang.textContent = checkOutTime + ' WIB';
    
    const lateWarning = document.getElementById('lateWarning');
    const lateWarningText = document.getElementById('lateWarningText');
    if (absenData.terlambat && checkInTime && lateWarning && lateWarningText) {
      lateWarning.classList.remove('hidden');
      if (absenData.terlambat_menit) {
        lateWarningText.textContent = `⚠️ Terlambat ${absenData.terlambat_menit} menit`;
      }
    } else if (lateWarning) {
      lateWarning.classList.add('hidden');
    }

    selectedShift = getShiftFromStorage();
    updateUIByStatus();
    await loadHistory();
  } catch (err) {
    console.error('Load data error:', err);
    showToast('Gagal memuat data absensi');
  }
}

async function completeScan() {
  try {
    const shift = getShiftFromStorage();
    if (!shift) {
      showToast('Pilih shift terlebih dahulu di dashboard');
      resetScanner();
      return;
    }
    const response = await fetch('{{ route("shift.handle") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ action: absenType, shift_type: shift })
    });
    const result = await response.json();

    const fpGlow = document.getElementById('fpGlow');
    const successCircle = document.getElementById('successCircle');
    if (fpGlow) fpGlow.style.opacity = '0';
    if (successCircle) {
      successCircle.classList.remove('hidden');
      successCircle.style.display = 'flex';
      successCircle.classList.add('animate-success-pop');
    }

    if (result.success) {
      if (absenType === 'masuk') {
        const waktu = result.check_in;
        const statusTitle = document.getElementById('statusTitle');
        const statusSub = document.getElementById('statusSub');
        const todayMasuk = document.getElementById('todayMasuk');
        if (statusTitle) statusTitle.textContent = '✓ Absen masuk berhasil';
        if (statusSub) statusSub.textContent = `Tercatat pukul ${waktu} WIB`;
        if (todayMasuk) todayMasuk.textContent = waktu + ' WIB';
        showToast(result.message);
        localStorage.setItem('kasir_shift_updated', Date.now());
        setTimeout(() => { window.location.href = '{{ route("kasir.shiftkasir") }}'; }, 1400);
      } else if (absenType === 'pulang') {
        const waktu = result.check_out;
        const statusTitle = document.getElementById('statusTitle');
        const statusSub = document.getElementById('statusSub');
        const todayPulang = document.getElementById('todayPulang');
        if (statusTitle) statusTitle.textContent = '✓ Absen pulang berhasil';
        if (statusSub) statusSub.textContent = `Tercatat pukul ${waktu} WIB`;
        if (todayPulang) todayPulang.textContent = waktu + ' WIB';
        showToast(result.message);
        localStorage.setItem('kasir_shift_updated', Date.now());
        setTimeout(() => { window.location.href = '{{ route("dashboard-kasir") }}'; }, 1400);
      }
    } else {
      showToast(result.message || 'Terjadi kesalahan');
      resetScanner();
    }
  } catch (err) {
    console.error('Complete scan error:', err);
    showToast('Gagal melakukan absensi');
    resetScanner();
  }
  scanState = 'done';
  const fpBtn = document.getElementById('fpBtn');
  if (fpBtn) fpBtn.style.pointerEvents = '';
}

function resetScanner() {
  scanState = 'idle';
  const fpGlow = document.getElementById('fpGlow');
  const successCircle = document.getElementById('successCircle');
  const fpIcon = document.getElementById('fpIcon');
  const fpTextGroup = document.getElementById('fpTextGroup');
  const scanPercent = document.getElementById('scanPercent');
  const fpBtn = document.getElementById('fpBtn');
  
  if (fpGlow) {
    fpGlow.style.opacity = '1';
    fpGlow.style.filter = '';
    fpGlow.style.boxShadow = '';
  }
  if (successCircle) successCircle.classList.add('hidden');
  if (fpIcon) fpIcon.style.display = 'block';
  if (fpTextGroup) fpTextGroup.style.display = 'flex';
  if (fpBtn) fpBtn.style.pointerEvents = '';
  if (scanPercent) scanPercent.classList.add('hidden');
  resetRing();
  updateUIByStatus();
}

function startFakeScan() {
  if (scanState !== 'idle') return;
  const fpBtn = document.getElementById('fpBtn');
  if (fpBtn && fpBtn.disabled) return;
  
  scanState = 'scanning';
  const scanPercent = document.getElementById('scanPercent');
  const fpIcon = document.getElementById('fpIcon');
  const fpTextGroup = document.getElementById('fpTextGroup');
  const fpGlow = document.getElementById('fpGlow');
  const statusTitle = document.getElementById('statusTitle');
  const statusSub = document.getElementById('statusSub');
  
  if (scanPercent) scanPercent.classList.remove('hidden');
  if (fpIcon) fpIcon.style.display = 'none';
  if (fpTextGroup) fpTextGroup.style.display = 'none';
  if (fpGlow) fpGlow.classList.add('scanning-glow');
  if (statusTitle) statusTitle.textContent = 'Memindai sidik jari';
  if (statusSub) statusSub.textContent = 'Mohon tunggu...';
  if (fpBtn) fpBtn.style.pointerEvents = 'none';
  
  let progress = 0;
  setRingProgress(0);
  if (scanInterval) clearInterval(scanInterval);
  scanInterval = setInterval(() => {
    progress += 2;
    if (progress > 100) progress = 100;
    setRingProgress(progress / 100);
    const scanPercentSpan = document.querySelector('#scanPercent span');
    if (scanPercentSpan) scanPercentSpan.textContent = progress + '%';
    if (progress >= 100) {
      clearInterval(scanInterval);
      scanInterval = null;
      if (fpGlow) fpGlow.classList.remove('scanning-glow');
      if (fpIcon) fpIcon.style.display = 'block';
      if (fpTextGroup) fpTextGroup.style.display = 'flex';
      if (scanPercent) scanPercent.classList.add('hidden');
      completeScan();
    }
  }, 50);
}

function updateClock() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2, '0');
  const m = String(now.getMinutes()).padStart(2, '0');
  const s = String(now.getSeconds()).padStart(2, '0');
  const liveClock = document.getElementById('liveClock');
  if (liveClock) {
    liveClock.innerHTML = `${h}<span class="animate-clock-sep">:</span>${m}<span class="animate-clock-sep">:</span>${s}`;
  }
  const liveDate = document.getElementById('liveDate');
  if (liveDate) {
    liveDate.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
  }
}
updateClock();
setInterval(updateClock, 1000);

// ── INIT ──
document.addEventListener('DOMContentLoaded', () => {
  resetRing();
  loadData();

  const pulangOverlay = document.getElementById('pulangOverlay');
  if (pulangOverlay) {
    pulangOverlay.addEventListener('click', function(e) {
      if (e.target === this) closePulangModal();
    });
  }

  const fpBtn = document.getElementById('fpBtn');
  if (fpBtn) {
    fpBtn.addEventListener('click', () => {
      if (scanState !== 'idle') return;
      if (fpBtn.disabled) return;

      if (absenType === 'pulang') {
        openPulangModal();
      } else {
        startFakeScan();
      }
    });
  }
});
</script>
</body>
</html>