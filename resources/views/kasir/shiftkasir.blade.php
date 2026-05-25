<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Kashy - Buka / Tutup Shift</title>
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
  .fade-up { animation: fadeUp 0.4s cubic-bezier(0.2,0.9,0.4,1.1) both; }
  .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
  .card-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0,0,0,0.08); }
  .pulse-dot { animation: pulse 1.4s infinite; }
  @keyframes pulse { 0%,100%{ opacity:1; transform:scale(1); } 50%{ opacity:0.7; transform:scale(1.2); } }
  input[type="text"]::-webkit-outer-spin-button,
  input[type="text"]::-webkit-inner-spin-button { -webkit-appearance: none; margin:0; }
  .loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #fff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 0.6s linear infinite;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>
</head>
<body class="bg-bg min-h-screen flex flex-col">

<!-- NAVBAR COMPONENT -->
@include('kasir.components.navbar')

<!-- MAIN CONTENT -->
<main class="flex-1 max-w-2xl mx-auto w-full px-4 pt-6 pb-28">
  
  <!-- Loading State -->
  <div id="loadingState" class="flex justify-center items-center py-20">
    <div class="loading-spinner w-8 h-8 border-4 border-terra border-t-transparent"></div>
    <span class="ml-3 text-muted">Memuat data shift...</span>
  </div>

  <!-- ======================= BUKA SHIFT SECTION ======================= -->
  <div id="openShiftSection" class="fade-up" style="display: none;">
    <div class="flex items-center gap-2 mb-1">
      <div class="w-1 h-5 bg-terra rounded-full"></div>
      <p class="text-xs text-muted font-semibold uppercase tracking-wider">Sesi Aktif</p>
    </div>
    <h2 class="text-3xl font-extrabold text-gray-900 mb-6 tracking-tight">Buka Shift</h2>

    <div class="bg-white rounded-2xl shadow-sm border border-border p-6 card-hover">
      <p class="text-sm text-muted mb-5 leading-relaxed">
        Masukkan saldo kas awal untuk memulai sesi penjualan harianmu. (Minimal Rp 50.000)
      </p>
      
      <div class="mb-4">
        <label class="block text-xs font-semibold text-muted uppercase tracking-wider mb-2">Saldo Kas Awal</label>
        <div class="relative">
          <input type="text" id="initialBalance" 
            class="w-full border-2 border-border rounded-xl py-3.5 px-4 text-gray-900 font-semibold text-base focus:border-terra focus:ring-1 focus:ring-terra outline-none transition bg-white" 
            placeholder="Rp 0" 
            oninput="formatRupiahWithValidate(this)">
        </div>
        <p id="minSaldoWarning" class="text-[11px] text-red-500 mt-1 hidden"></p>
      </div>

      <button onclick="bukaShift()" id="btnBukaShift" class="w-full bg-gradient-to-r from-terra to-terra-l text-white font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all card-hover">
        <span class="text-lg">▶</span>
        Mulai Shift
      </button>
    </div>
  </div>

  <!-- ======================= TUTUP SHIFT SECTION ======================= -->
  <div id="closeShiftSection" class="fade-up" style="display: none;">
    <div class="flex items-center gap-2 mb-1">
      <div class="w-1 h-5 bg-terra rounded-full"></div>
      <p class="text-xs text-muted font-semibold uppercase tracking-wider">Ringkasan Sesi</p>
    </div>
    <h2 class="text-3xl font-extrabold text-gray-900 mb-6 tracking-tight">Tutup Shift</h2>

    <!-- STATUS SHIFT AKTIF -->
    <div class="bg-green-50 border-l-4 border-green-500 rounded-xl p-4 mb-5 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-[10px] font-semibold text-green-600 uppercase tracking-wider mb-1">Status Saat Ini</p>
          <p class="text-lg font-bold text-green-700">Shift Aktif</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 6v6l4 2"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Ringkasan Transaksi -->
    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden mb-6">
      <div class="p-5 space-y-3">
        <div class="flex justify-between items-center p-3 bg-terra-xs rounded-xl">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
              <svg width="20" height="20" fill="none" stroke="#C8966C" stroke-width="1.8" viewBox="0 0 24 24">
                <rect x="2" y="6" width="20" height="12" rx="2"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </div>
            <div>
              <p class="font-semibold text-gray-800">Cash</p>
              <p class="text-xs text-muted">Tunai</p>
            </div>
          </div>
          <span class="font-extrabold text-gray-900 text-base" id="totalCash">Rp 0</span>
        </div>

        <div class="flex justify-between items-center p-3 bg-terra-xs rounded-xl">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">
              <svg width="20" height="20" fill="none" stroke="#C8966C" stroke-width="1.8" viewBox="0 0 24 24">
                <rect x="2" y="5" width="20" height="14" rx="2"/>
                <line x1="2" y1="10" x2="22" y2="10"/>
              </svg>
            </div>
            <div>
              <p class="font-semibold text-gray-800">Non-Cash</p>
              <p class="text-xs text-muted">(Debit/QRIS)</p>
            </div>
          </div>
          <span class="font-extrabold text-gray-900 text-base" id="totalNonCash">Rp 0</span>
        </div>
      </div>
    </div>

    <!-- Laporan Saldo Akhir -->
    <div class="bg-gradient-to-br from-gray-800 to-gray-900 text-white rounded-2xl p-6 shadow-md mb-6 relative overflow-hidden">
      <div class="absolute -right-6 -top-6 w-24 h-24 bg-terra/20 rounded-full blur-2xl"></div>
      <div class="relative">
        <div class="text-xs font-semibold uppercase tracking-wider text-terra-ll">Saldo Awal Kas</div>
        <div class="text-2xl font-bold mt-1" id="saldoAwalKas">Rp 0</div>
        <div class="text-xs font-semibold uppercase tracking-wider text-terra-ll mt-3">Total Penjualan</div>
        <div class="text-2xl font-bold" id="totalPenjualan">Rp 0</div>
        <div class="text-xs font-semibold uppercase tracking-wider text-terra-ll mt-3 border-t border-white/20 pt-2">Ekspektasi Uang Tunai</div>
        <div class="text-xl font-bold" id="ekspektasiTunai">Rp 0</div>
      </div>
    </div>

    <!-- Verifikasi Uang Tunai -->
    <div class="mb-6">
      <label class="block text-xs font-semibold text-muted uppercase tracking-wider mb-2">Jumlah Total Uang Tunai</label>
      <input type="text" id="finalCash" class="w-full border-2 border-dashed border-border rounded-xl py-3.5 px-4 text-gray-900 font-semibold text-base focus:border-terra focus:ring-1 focus:ring-terra outline-none transition bg-white" placeholder="Masukkan jumlah untuk verifikasi" oninput="formatRupiah(this)">
      <p class="text-[11px] text-muted mt-1 flex items-center gap-1">🔍 Masukkan nominal uang tunai yang ada di laci kas</p>
    </div>

    <!-- Tombol Tutup -->
    <button onclick="showTutupShiftModal()" id="btnTutupShift" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 shadow-md transition-all card-hover">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
      </svg>
      Tutup Shift
    </button>
  </div>
</main>

<!-- ========== MODAL KONFIRMASI BUKA SHIFT ========== -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
  <div class="bg-white rounded-2xl max-w-sm w-full mx-4 shadow-2xl transform transition-all">
    <div class="p-6">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-terra-xs flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 6v6l4 2"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">Konfirmasi Buka Shift</h3>
      </div>
      <p class="text-sm text-muted mb-4">Apakah Anda yakin ingin memulai shift dengan data berikut?</p>
      <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5">
        <div class="flex justify-between text-sm">
          <span class="text-muted">Saldo Kas Awal</span>
          <span id="modalBalance" class="font-bold text-gray-900">Rp 0</span>
        </div>
        <div class="flex justify-between text-sm mt-2">
          <span class="text-muted">Waktu Mulai</span>
          <span id="modalStartTime" class="font-bold text-gray-900">--:--</span>
        </div>
      </div>
      <div class="flex gap-3">
        <button onclick="closeModal()" class="flex-1 py-2.5 rounded-xl border border-border bg-white text-gray-700 text-sm font-semibold hover:bg-gray-100 transition">Batal</button>
        <button onclick="confirmBukaShift()" id="confirmBukaBtn" class="flex-1 py-2.5 rounded-xl bg-green-500 text-white text-sm font-semibold hover:bg-green-600 transition">Ya, Mulai Shift</button>
      </div>
    </div>
  </div>
</div>

<!-- ========== MODAL KONFIRMASI TUTUP SHIFT ========== -->
<div id="confirmTutupModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
  <div class="bg-white rounded-2xl max-w-sm w-full mx-4 shadow-2xl transform transition-all">
    <div class="p-6">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-terra-xs flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8966C" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 6v6l4 2"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900">Konfirmasi Tutup Shift</h3>
      </div>
      <p class="text-sm text-muted mb-4">Periksa kembali data verifikasi tunai berikut:</p>
      <div id="verifikasiDetail" class="bg-gray-50 rounded-xl p-4 mb-5 space-y-2 text-sm"></div>
      <div class="flex gap-3">
        <button onclick="closeTutupModal()" class="flex-1 py-2.5 rounded-xl border border-border bg-white text-gray-700 text-sm font-semibold hover:bg-gray-100 transition">Batal</button>
        <button id="confirmTutupBtn" class="flex-1 py-2.5 rounded-xl text-white text-sm font-semibold transition">Ya, Tutup Shift</button>
      </div>
    </div>
  </div>
</div>

<!-- ========== MODAL ERROR POP UP ========== -->
<div id="errorModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
  <div class="bg-white rounded-2xl max-w-sm w-full mx-4 shadow-2xl transform transition-all">
    <div class="p-6">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900" id="errorModalTitle">Error</h3>
      </div>
      <p class="text-sm text-gray-700 mb-6" id="errorModalMessage">Terjadi kesalahan</p>
      <button onclick="closeErrorModal()" class="w-full py-2.5 rounded-xl bg-red-500 text-white text-sm font-semibold hover:bg-red-600 transition">
        Mengerti
      </button>
    </div>
  </div>
</div>

<!-- TOAST NOTIFICATION -->
<div id="toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-30 bg-gray-900 text-white text-sm font-medium px-5 py-3 rounded-full shadow-xl flex items-center gap-2 pointer-events-none transition-all duration-300 opacity-0 translate-y-4">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
  <span id="toastMsg">—</span>
</div>

<script>
let minSaldo = 50000;
let currentShift = null;
let isLoading = false;

// ========== FORMAT RUPIAH ==========
function formatRupiah(input) {
    let val = input.value.replace(/[^\d]/g, '');
    if (val) {
        let num = parseInt(val, 10);
        input.value = num.toLocaleString('id-ID');
    } else {
        input.value = '';
    }
}

function parseRupiah(str) {
    if (!str) return 0;
    let cleaned = str.replace(/[^\d]/g, '');
    return parseInt(cleaned, 10) || 0;
}

// ========== VALIDASI VISUAL ==========
function validateSaldo() {
    const balanceInput = document.getElementById('initialBalance');
    const balanceStr = balanceInput.value;
    const balanceNum = parseRupiah(balanceStr);
    const warningEl = document.getElementById('minSaldoWarning');
    
    if (balanceNum === 0 && balanceStr !== '') {
        balanceInput.classList.add('border-red-500', 'bg-red-50');
        warningEl.classList.remove('hidden');
        warningEl.innerHTML = '⚠️ Saldo kas awal tidak boleh kosong!';
        return false;
    } else if (balanceNum > 0 && balanceNum < minSaldo) {
        balanceInput.classList.add('border-red-500', 'bg-red-50');
        warningEl.classList.remove('hidden');
        warningEl.innerHTML = `⚠️ Saldo minimal Rp ${minSaldo.toLocaleString('id-ID')}`;
        return false;
    } else {
        balanceInput.classList.remove('border-red-500', 'bg-red-50');
        warningEl.classList.add('hidden');
        return true;
    }
}

function formatRupiahWithValidate(input) {
    let val = input.value.replace(/[^\d]/g, '');
    if (val) {
        let num = parseInt(val, 10);
        input.value = num.toLocaleString('id-ID');
    } else {
        input.value = '';
    }
    validateSaldo();
}

// ========== AMBIL MINIMAL SALDO ==========
async function getMinSaldo() {
    try {
        const response = await fetch('{{ route("kasir.shift.minSaldo") }}');
        const data = await response.json();
        minSaldo = data.min_saldo;
    } catch (error) {
        console.error('Gagal ambil min saldo:', error);
    }
}

// ========== CEK STATUS SHIFT ==========
async function cekStatusShift() {
    try {
        const response = await fetch('{{ route("kasir.shift.status") }}');
        const data = await response.json();
        
        document.getElementById('loadingState').style.display = 'none';
        
        if (data.shift_active && data.shift) {
            currentShift = data.shift;
            document.getElementById('openShiftSection').style.display = 'none';
            document.getElementById('closeShiftSection').style.display = 'block';
            
            const totalPenjualan = data.shift.total_penjualan || 0;
            const penjualanTunai = data.shift.penjualan_tunai || 0;
            const penjualanNonTunai = (data.shift.penjualan_qris || 0) + (data.shift.penjualan_debit || 0);
            const saldoAwal = data.shift.saldo_awal || 0;
            const ekspektasiTunai = saldoAwal + penjualanTunai;
            
            document.getElementById('totalCash').innerText = 'Rp ' + penjualanTunai.toLocaleString('id-ID');
            document.getElementById('totalNonCash').innerText = 'Rp ' + penjualanNonTunai.toLocaleString('id-ID');
            document.getElementById('saldoAwalKas').innerText = 'Rp ' + saldoAwal.toLocaleString('id-ID');
            document.getElementById('totalPenjualan').innerText = 'Rp ' + totalPenjualan.toLocaleString('id-ID');
            document.getElementById('ekspektasiTunai').innerText = 'Rp ' + ekspektasiTunai.toLocaleString('id-ID');
        } else {
            document.getElementById('openShiftSection').style.display = 'block';
            document.getElementById('closeShiftSection').style.display = 'none';
        }
    } catch (error) {
        console.error('Gagal cek status shift:', error);
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('openShiftSection').style.display = 'block';
        showToast('Gagal memuat status shift');
    }
}

// ========== BUKA SHIFT ==========
async function bukaShift() {
    if (isLoading) return;
    
    const balanceInput = document.getElementById('initialBalance');
    const balanceStr = balanceInput.value;
    const balanceNum = parseRupiah(balanceStr);
    
    if (balanceNum === 0) {
        balanceInput.classList.add('border-red-500', 'bg-red-50');
        document.getElementById('minSaldoWarning').classList.remove('hidden');
        document.getElementById('minSaldoWarning').innerHTML = '⚠️ Saldo kas awal tidak boleh kosong!';
        return;
    }
    
    if (balanceNum < minSaldo) {
        balanceInput.classList.add('border-red-500', 'bg-red-50');
        document.getElementById('minSaldoWarning').classList.remove('hidden');
        document.getElementById('minSaldoWarning').innerHTML = `⚠️ Saldo minimal Rp ${minSaldo.toLocaleString('id-ID')}`;
        return;
    }
    
    balanceInput.classList.remove('border-red-500', 'bg-red-50');
    document.getElementById('minSaldoWarning').classList.add('hidden');
    
    document.getElementById('modalBalance').innerText = 'Rp ' + balanceNum.toLocaleString('id-ID');
    const now = new Date();
    const waktuMulai = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    document.getElementById('modalStartTime').innerText = waktuMulai;
    
    document.getElementById('confirmModal').classList.remove('hidden');
    document.getElementById('confirmModal').classList.add('flex');
    
    window.pendingSaldo = balanceNum;
}

async function confirmBukaShift() {
    if (isLoading) return;
    isLoading = true;
    
    const btn = document.getElementById('confirmBukaBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="loading-spinner"></span> Memproses...';
    btn.disabled = true;
    
    try {
        const response = await fetch('{{ route("kasir.shift.buka") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ saldo_awal: window.pendingSaldo })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message);
            closeModal();
            await cekStatusShift();
            localStorage.setItem('kasir_shift_updated', Date.now());
        } else {
            // Tutup modal konfirmasi
            closeModal();
            
            // Tampilkan modal error pop up
            if (result.message && result.message.includes('sudah melakukan shift hari ini')) {
                showErrorModal('⚠️ Gagal Buka Shift', result.message);
            } else {
                showToast(result.message || 'Gagal membuka shift');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        closeModal();
        showToast('Gagal membuka shift');
    } finally {
        isLoading = false;
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

function closeModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    document.getElementById('confirmModal').classList.remove('flex');
}

// ========== MODAL ERROR POP UP ==========
function showErrorModal(title, message) {
    const errorModal = document.getElementById('errorModal');
    document.getElementById('errorModalTitle').innerHTML = title;
    document.getElementById('errorModalMessage').innerHTML = message;
    errorModal.classList.remove('hidden');
    errorModal.classList.add('flex');
}

function closeErrorModal() {
    const errorModal = document.getElementById('errorModal');
    errorModal.classList.add('hidden');
    errorModal.classList.remove('flex');
}

// ========== TUTUP SHIFT ==========
let finalCashValue = 0;
let expectedCash = 0;
let selisih = 0;

function showTutupShiftModal() {
    const finalCashStr = document.getElementById('finalCash').value;
    const finalCashNum = parseRupiah(finalCashStr);
    
    if (finalCashNum === 0) {
        showToast('Masukkan jumlah uang tunai fisik untuk verifikasi!');
        return;
    }
    
    finalCashValue = finalCashNum;
    const saldoAwal = currentShift ? currentShift.saldo_awal : 0;
    const penjualanTunai = currentShift ? currentShift.penjualan_tunai : 0;
    expectedCash = saldoAwal + penjualanTunai;
    selisih = finalCashValue - expectedCash;
    
    let detailHtml = `
        <div class="flex justify-between text-sm">
            <span class="text-muted">Saldo awal kas:</span>
            <span class="font-semibold">Rp ${(saldoAwal || 0).toLocaleString('id-ID')}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-muted">Penjualan tunai:</span>
            <span class="font-semibold">Rp ${(penjualanTunai || 0).toLocaleString('id-ID')}</span>
        </div>
        <div class="flex justify-between text-sm border-t border-gray-200 pt-2 mt-1">
            <span class="text-muted">Ekspektasi uang tunai:</span>
            <span class="font-semibold">Rp ${expectedCash.toLocaleString('id-ID')}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-muted">Uang tunai aktual:</span>
            <span class="font-semibold">Rp ${finalCashValue.toLocaleString('id-ID')}</span>
        </div>
    `;
    
    if (selisih > 0) {
        detailHtml += `<div class="flex justify-between text-sm text-green-600 bg-green-50 p-2 rounded-lg mt-2">
            <span>💰 Kelebihan tunai:</span>
            <span class="font-bold">Rp ${selisih.toLocaleString('id-ID')}</span>
        </div>`;
    } else if (selisih < 0) {
        detailHtml += `<div class="flex justify-between text-sm text-red-600 bg-red-50 p-2 rounded-lg mt-2">
            <span>⚠️ Kekurangan tunai:</span>
            <span class="font-bold">Rp ${Math.abs(selisih).toLocaleString('id-ID')}</span>
        </div>`;
    } else {
        detailHtml += `<div class="flex justify-between text-sm text-green-600 bg-green-50 p-2 rounded-lg mt-2">
            <span>✅ Pas! Kas sesuai.</span>
        </div>`;
    }
    
    document.getElementById('verifikasiDetail').innerHTML = detailHtml;
    
    const confirmBtn = document.getElementById('confirmTutupBtn');
    confirmBtn.className = "flex-1 py-2.5 rounded-xl bg-green-500 text-white text-sm font-semibold hover:bg-green-600 transition";
    confirmBtn.innerText = "Ya, Tutup Shift";
    
    document.getElementById('confirmTutupModal').classList.remove('hidden');
    document.getElementById('confirmTutupModal').classList.add('flex');
}

function closeTutupModal() {
    document.getElementById('confirmTutupModal').classList.add('hidden');
    document.getElementById('confirmTutupModal').classList.remove('flex');
}

async function confirmTutupShift() {
    if (isLoading) return;
    isLoading = true;
    
    const btn = document.getElementById('confirmTutupBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="loading-spinner"></span> Memproses...';
    btn.disabled = true;
    
    try {
        const response = await fetch('{{ route("kasir.shift.tutup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ uang_tunai_aktual: finalCashValue })
        });
        
        const result = await response.json();
        
        if (result.success) {
            let selisihText = '';
            if (result.selisih > 0) {
                selisihText = ` Kelebihan: Rp ${result.selisih.toLocaleString('id-ID')}`;
            } else if (result.selisih < 0) {
                selisihText = ` Kekurangan: Rp ${Math.abs(result.selisih).toLocaleString('id-ID')}`;
            } else {
                selisihText = ` Pas!`;
            }
            showToast(result.message + selisihText);
            closeTutupModal();
            await cekStatusShift();
            localStorage.setItem('kasir_shift_updated', Date.now());
        } else {
            showToast(result.message || 'Gagal menutup shift');
            closeTutupModal();
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Gagal menutup shift');
        closeTutupModal();
    } finally {
        isLoading = false;
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

document.getElementById('confirmTutupBtn').onclick = confirmTutupShift;

function showToast(msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').innerText = msg;
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(16px)';
    }, 2600);
}

// ========== INIT ==========
getMinSaldo();
cekStatusShift();

window.addEventListener('storage', (event) => {
    if (event.key === 'kasir_shift_updated') {
        cekStatusShift();
    }
});
</script>
</body>
</html>