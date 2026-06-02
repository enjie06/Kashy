<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Absensi Kasir – Kashy</title>
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
        fontFamily: { body: ['Poppins', 'sans-serif'] },
        keyframes: {
          fadeUp:     { from: { opacity:'0', transform:'translateY(18px)' }, to: { opacity:'1', transform:'translateY(0)' } },
          clockSep:   { '0%,100%': { opacity:'1' }, '50%': { opacity:'0.4' } },
          ringPulse:  { '0%': { transform:'scale(1)', opacity:'.55' }, '70%': { transform:'scale(1.35)', opacity:'0' }, '100%': { transform:'scale(1.35)', opacity:'0' } },
          shimmer:    { '0%': { backgroundPosition:'-400px 0' }, '100%': { backgroundPosition:'400px 0' } },
          successPop: { '0%': { transform:'scale(0.7)', opacity:'0' }, '65%': { transform:'scale(1.12)' }, '100%': { transform:'scale(1)', opacity:'1' } },
        },
        animation: {
          'fade-up':    'fadeUp 0.45s cubic-bezier(0.22,1,0.36,1) both',
          'clock-sep':  'clockSep 1s step-end infinite',
          'ring-1':     'ringPulse 2.2s ease-out infinite',
          'ring-2':     'ringPulse 2.2s ease-out 0.55s infinite',
          'ring-3':     'ringPulse 2.2s ease-out 1.1s infinite',
          'shimmer':    'shimmer 4s linear infinite',
          'success-pop':'successPop 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards',
        }
      }
    }
  }
</script>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Poppins',sans-serif; background:#F5F0EB; }
  .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(0,0,0,0.08); }
  .fp-ring-track { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:130px; height:130px; pointer-events:none; z-index:20; }
  .fp-ring-track svg { transform:rotate(-90deg); width:130px; height:130px; }
  .fp-ring-bg   { fill:none; stroke:rgba(255,255,255,.15); stroke-width:4; }
  .fp-ring-fill { fill:none; stroke:#fff; stroke-width:4; stroke-linecap:round; stroke-dasharray:364; stroke-dashoffset:364; transition:stroke-dashoffset linear; filter:drop-shadow(0 0 6px rgba(255,255,255,.7)); }
  @keyframes glowPulse { 0%,100%{ box-shadow:0 0 20px rgba(200,150,108,.4); } 50%{ box-shadow:0 0 50px rgba(229,177,138,.8); } }
  .scanning-glow { animation: glowPulse .7s ease-in-out infinite; }
</style>
</head>
<body class="bg-bg min-h-screen flex flex-col">

<!-- TOPBAR — tanpa navbar/sidebar -->
<nav class="sticky top-0 z-50 bg-gray-900 px-5 py-3.5 flex items-center justify-center shadow-md">
  <div class="text-center">
    <span class="font-bold text-white text-xl tracking-wide">Kashy</span>
    <p class="text-[10px] text-gray-400 leading-none mt-0.5" id="navSubtitle">Absensi</p>
  </div>
</nav>

<main class="flex-1 max-w-md mx-auto w-full px-4 pt-6 pb-10 space-y-5">

  <!-- Jam & Tanggal -->
  <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden animate-fade-up card-hover">
    <div class="h-1 w-full bg-gradient-to-r from-terra via-terra-l to-terra-ll bg-[length:200%] animate-shimmer"></div>
    <div class="px-4 py-4 text-center">
      <div class="text-3xl font-bold text-gray-900 leading-none mb-1" id="liveClock">
        --<span class="animate-clock-sep">:</span>--<span class="animate-clock-sep">:</span>--
      </div>
      <p class="text-xs text-muted mt-1" id="liveDate">--</p>
    </div>
  </div>

  <!-- Fingerprint Card -->
  <div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden animate-fade-up card-hover">
    <div class="px-4 py-6 flex flex-col items-center">

      <div class="relative flex items-center justify-center mb-5" style="width:200px;height:200px;">
        <div class="absolute rounded-full border-2 border-terra/30 inset-0 animate-ring-1"></div>
        <div class="absolute rounded-full border-2 border-terra/20 animate-ring-2" style="inset:-8px;border-radius:9999px;"></div>
        <div class="absolute rounded-full border-2 border-terra/10 animate-ring-3" style="inset:-18px;border-radius:9999px;"></div>

        <div id="fpGlow" class="absolute inset-4 rounded-full transition-all duration-500"></div>

        <div class="fp-ring-track">
          <svg viewBox="0 0 130 130">
            <circle class="fp-ring-bg" cx="65" cy="65" r="58"/>
            <circle class="fp-ring-fill" cx="65" cy="65" r="58" id="fpRingFill"/>
          </svg>
        </div>

        <button id="fpBtn"
          class="relative z-10 flex flex-col items-center justify-center gap-1 select-none focus:outline-none rounded-full"
          style="width:110px;height:110px;background:transparent;touch-action:none;"
          aria-label="Scan sidik jari">
          <svg width="48" height="48" viewBox="0 0 64 64" fill="none" id="fpIcon">
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
          <div id="fpTextGroup" class="text-center leading-tight">
            <p id="fpLabel" class="text-[9px] font-bold text-white tracking-widest uppercase">Tahan</p>
            <p id="fpSub"   class="text-[8px] text-white/70 mt-0.5">untuk scan</p>
          </div>
        </button>

        <div id="scanPercent" class="absolute inset-0 flex items-center justify-center z-20 pointer-events-none hidden">
          <span class="text-white font-bold text-xl">0%</span>
        </div>

        <div id="successCircle"
          class="absolute inset-4 rounded-full flex-col items-center justify-center hidden z-30 text-center"
          style="background:radial-gradient(circle,#34d399 0%,#10b981 60%,#059669 100%);">
          <div class="w-14 h-14 rounded-full border-2 border-white flex items-center justify-center mb-1">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
              <path d="M5 13L10 18L19 7" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <p class="text-white font-bold text-xs">Terverifikasi</p>
        </div>
      </div>

      <div class="text-center space-y-1">
        <p class="text-sm font-semibold text-gray-700" id="statusTitle">Memuat...</p>
        <p class="text-xs text-muted"        id="statusSub">Mohon tunggu</p>
      </div>
    </div>
  </div>

  <!-- Status Hari Ini -->
<div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden animate-fade-up card-hover">
  <div class="px-4 py-4">
    <p class="text-[10px] font-bold tracking-[.14em] uppercase text-muted mb-3">Status Hari Ini</p>

    <div class="grid grid-cols-2 gap-3">
      <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-stone-50 border border-stone-100">
        <div class="w-8 h-8 rounded-xl bg-terra-xs flex items-center justify-center">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2.5">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/>
            <line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
        </div>
        <div>
          <p class="text-[10px] text-muted font-medium uppercase tracking-wide">Masuk</p>
          <p class="text-sm font-bold text-gray-900" id="todayMasuk">—</p>
        </div>
      </div>

      <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-stone-50 border border-stone-100">
        <div class="w-8 h-8 rounded-xl bg-terra-xs flex items-center justify-center">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2.5">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
        </div>
        <div>
          <p class="text-[10px] text-muted font-medium uppercase tracking-wide">Pulang</p>
          <p class="text-sm font-bold text-gray-900" id="todayPulang">—</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Riwayat Absensi -->
<div class="bg-white rounded-2xl border border-border shadow-sm overflow-hidden animate-fade-up card-hover">
  <div class="px-4 pt-4 pb-4">
    <h3 class="font-semibold text-gray-900 text-sm mb-3">Riwayat Absensi</h3>

    <div class="space-y-2" id="historyContent">
      <div class="flex items-center justify-between p-2.5 rounded-xl bg-stone-50 border border-stone-100">
        <div>
          <p class="text-xs font-medium text-gray-800">Hari ini</p>
          <p class="text-[10px] text-muted">
            Masuk: <span id="historyMasuk">—</span> · Pulang: <span id="historyPulang">—</span>
          </p>
        </div>
        <span class="text-[10px] px-2 py-0.5 rounded-full bg-terra-xs text-terra font-semibold">
          Kasir
        </span>
      </div>
    </div>
  </div>
</div>

  <!-- Info kasir -->
  <div class="bg-white rounded-2xl border border-border shadow-sm px-4 py-4 animate-fade-up">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm" id="avatarEl">
        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
      </div>
      <div>
        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
        <p class="text-xs text-muted" id="infoSub">Kasir</p>
      </div>
    </div>
  </div>

</main>

<!-- Toast -->
<div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-gray-900 text-white text-xs font-medium px-5 py-3 rounded-full shadow-xl flex items-center gap-2 pointer-events-none transition-all duration-300 opacity-0 translate-y-4">
  <span id="toastMsg">—</span>
</div>

<script>
// ── Deteksi tipe absensi dari URL param: ?type=masuk atau ?type=pulang ──
const urlParams = new URLSearchParams(window.location.search);
const absenType = urlParams.get('type') === 'pulang' ? 'pulang' : 'masuk';
const isPulang  = absenType === 'pulang';

// ── Set warna glow & teks sesuai tipe ──
document.getElementById('fpGlow').style.background =
  'radial-gradient(circle,#E5B18A 0%,#C8966C 60%,#a07050 100%)';

document.getElementById('navSubtitle').textContent  = isPulang ? 'Absensi Pulang' : 'Absensi Masuk';
document.getElementById('statusTitle').textContent  = isPulang ? 'Siap Absen Pulang' : 'Siap Absen Masuk';
document.getElementById('statusSub').textContent    = isPulang ? 'Tekan tombol untuk absensi pulang' : 'Tekan tombol untuk absensi masuk';
document.getElementById('infoSub').textContent      = isPulang ? 'Kasir · Absensi Pulang' : 'Kasir · Absensi Masuk';

const avatarEl = document.getElementById('avatarEl');
avatarEl.style.background = isPulang ? '#FAF2EC' : '#FAF2EC';
avatarEl.style.color      = isPulang ? '#C8966C' : '#C8966C';

// ── Clock ──
function updateClock() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2,'0');
  const m = String(now.getMinutes()).padStart(2,'0');
  const s = String(now.getSeconds()).padStart(2,'0');
  document.getElementById('liveClock').innerHTML =
    `${h}<span class="animate-clock-sep">:</span>${m}<span class="animate-clock-sep">:</span>${s}`;
  document.getElementById('liveDate').textContent =
    now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
}
updateClock();
setInterval(updateClock, 1000);

// ── Ring progress ──
const CIRCUMFERENCE = 2 * Math.PI * 58;
function setRingProgress(pct) {
  document.getElementById('fpRingFill').style.strokeDashoffset = CIRCUMFERENCE * (1 - pct);
  document.getElementById('fpRingFill').style.strokeDasharray  = CIRCUMFERENCE;
}
setRingProgress(0);

// ── Toast ──
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

// ── Scan ──
let scanState   = 'idle';
let scanInterval = null;

function startScan() {
  if (scanState !== 'idle') return;
  scanState = 'scanning';

  document.getElementById('scanPercent').classList.remove('hidden');
  document.getElementById('fpIcon').style.display      = 'none';
  document.getElementById('fpTextGroup').style.display = 'none';
  document.getElementById('fpGlow').classList.add('scanning-glow');
  document.getElementById('statusTitle').textContent   = 'Memindai Sidik Jari';
  document.getElementById('statusSub').textContent     = 'Mohon tunggu...';
  document.getElementById('fpBtn').style.pointerEvents = 'none';

  let progress = 0;
  setRingProgress(0);

  scanInterval = setInterval(() => {
    progress += 2;
    if (progress > 100) progress = 100;
    setRingProgress(progress / 100);
    document.querySelector('#scanPercent span').textContent = progress + '%';
    if (progress >= 100) {
      clearInterval(scanInterval);
      scanInterval = null;
      completeScan();
    }
  }, 50);
}

async function completeScan() {
  document.getElementById('fpGlow').style.opacity = '0';
  document.getElementById('scanPercent').classList.add('hidden');
  document.getElementById('fpIcon').style.display = 'block';
  document.getElementById('fpTextGroup').style.display = 'flex';

  const sc = document.getElementById('successCircle');
  sc.classList.remove('hidden');
  sc.style.display = 'flex';
  sc.classList.add('animate-success-pop');

  document.getElementById('statusTitle').textContent = isPulang
    ? '✓ Absen Pulang Berhasil'
    : '✓ Absen Masuk Berhasil';

  document.getElementById('statusSub').textContent = 'Absensi berhasil dicatat';

  showToast('Absensi berhasil! Mengalihkan ke beranda...');

  setTimeout(() => {
    window.location.href = "{{ route('dashboard-kasir') }}";
  }, 1500);
}

function resetScanner() {
  scanState = 'idle';
  document.getElementById('fpGlow').style.opacity   = '1';
  document.getElementById('fpGlow').classList.remove('scanning-glow');
  document.getElementById('fpGlow').style.boxShadow = '';
  document.getElementById('successCircle').classList.add('hidden');
  document.getElementById('successCircle').classList.remove('animate-success-pop');
  document.getElementById('scanPercent').classList.add('hidden');
  document.getElementById('fpIcon').style.display       = 'block';
  document.getElementById('fpTextGroup').style.display  = 'flex';
  document.getElementById('fpBtn').style.pointerEvents  = '';
  document.getElementById('statusTitle').textContent = isPulang ? 'Siap Absen Pulang' : 'Siap Absen Masuk';
  document.getElementById('statusSub').textContent   = isPulang ? 'Tekan tombol untuk absensi pulang' : 'Tekan tombol untuk absensi masuk';
  setRingProgress(0);
}

document.getElementById('fpBtn').addEventListener('click', startScan);
if (isPulang) {
  document.getElementById('todayPulang').textContent = waktu || new Date().toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  }) + ' WIB';

  document.getElementById('historyPulang').textContent = document.getElementById('todayPulang').textContent;
} else {
  document.getElementById('todayMasuk').textContent = waktu || new Date().toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
  }) + ' WIB';

  document.getElementById('historyMasuk').textContent = document.getElementById('todayMasuk').textContent;
}

</script>
</body>
</html>