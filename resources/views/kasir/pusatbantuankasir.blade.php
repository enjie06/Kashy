<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Pusat Bantuan – Kashy</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: { poppins: ['Poppins', 'sans-serif'] },
        colors: {
          terra:      '#C8966C',
          'terra-l':  '#E5B18A',
          'terra-ll': '#F0D7C7',
          'terra-xs': '#FAF2EC',
          muted:      '#9C8B7E',
          border:     '#EAE0D6',
          bg:         '#F5F0EB',
        },
        boxShadow: {
          'card': '0 2px 12px rgba(28,28,28,0.06)',
        }
      }
    }
  }
</script>

<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Poppins',sans-serif; background:#F5F0EB; }

  .faq-item { border-radius: 12px; overflow: hidden; }
  .faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s cubic-bezier(.4,0,.2,1);
  }
  .faq-answer.open { max-height: 400px; }
  .faq-chevron { transition: transform .3s ease; }
  .faq-chevron.open { transform: rotate(180deg); }
  .faq-trigger:hover { background-color: #FAF2EC; }

  @keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .anim   { animation: fadeUp .4s ease both; }
  .anim-1 { animation-delay: .05s; }
  .anim-2 { animation-delay: .12s; }
  .anim-3 { animation-delay: .19s; }
  .anim-4 { animation-delay: .26s; }
  .anim-5 { animation-delay: .33s; }
</style>
</head>

<body class="bg-bg font-poppins min-h-screen flex flex-col">

<!-- TOPBAR -->
<nav class="sticky top-0 z-50 bg-gray-900 flex items-center px-4 shadow-md h-12">
  <button onclick="window.history.back()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">
    <svg width="18" height="18" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24">
      <path d="m15 18-6-6 6-6"/>
    </svg>
  </button>
  <div class="absolute left-1/2 -translate-x-1/2">
    <span class="font-bold text-white text-lg tracking-wider">Kashy</span>
  </div>
</nav>

<!-- MAIN CONTENT -->
<main class="flex-1 overflow-y-auto pb-28">
  <div class="max-w-md mx-auto px-4 pt-5 space-y-5">

    <!-- Header -->
    <div class="anim anim-1">
      <h1 class="text-xl font-bold text-gray-900 mb-0.5">Pusat Bantuan</h1>
      <p class="text-xs text-muted">Bagaimana kami bisa membantu Anda hari ini?</p>
    </div>

    <!-- Search bar -->
    <div class="anim anim-2">
      <div class="relative flex items-center gap-2 bg-white border border-border rounded-xl px-3 py-2.5 focus-within:border-terra focus-within:ring-1 focus-within:ring-terra transition-all">
        <svg class="flex-shrink-0 text-muted" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
        <input type="text" placeholder="Cari topik bantuan..." class="flex-1 bg-transparent text-sm text-gray-900 placeholder-muted outline-none" oninput="filterFaq(this.value)">
        <button id="clearBtn" onclick="clearSearch()" class="hidden text-muted hover:text-gray-700">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
    </div>

    <!-- Label section -->
    <div class="anim anim-3">
      <p class="text-[10px] font-semibold tracking-wide uppercase text-muted" id="sectionLabel">Pertanyaan Populer</p>
    </div>

    <!-- FAQ Container -->
    <div class="anim anim-4 bg-white rounded-2xl border border-border shadow-sm overflow-hidden" id="faqContainer">

      <!-- item 1 -->
      <div class="faq-item border-b border-border" data-question="cara melakukan reset password sistem">
        <button class="faq-trigger w-full flex items-center justify-between gap-3 px-4 py-3 text-left transition" onclick="toggleFaq(this)">
          <span class="text-xs font-medium text-gray-900 leading-snug">Cara melakukan reset password sistem?</span>
          <svg class="faq-chevron flex-shrink-0 text-muted" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-answer bg-terra-xs">
          <div class="px-4 py-3 text-xs text-muted leading-relaxed">
            Untuk mereset password, buka halaman <strong class="text-gray-900">Login</strong>, klik <em>"Lupa Password?"</em>. Masukkan email terdaftar, cek email untuk tautan reset (berlaku 15 menit). Jika tidak ada, periksa folder spam.
          </div>
        </div>
      </div>

      <!-- item 2 -->
      <div class="faq-item border-b border-border" data-question="cara memproses transaksi pembayaran kasir">
        <button class="faq-trigger w-full flex items-center justify-between gap-3 px-4 py-3 text-left transition" onclick="toggleFaq(this)">
          <span class="text-xs font-medium text-gray-900 leading-snug">Cara memproses transaksi pembayaran?</span>
          <svg class="faq-chevron flex-shrink-0 text-muted" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-answer bg-terra-xs">
          <div class="px-4 py-3 text-xs text-muted leading-relaxed">
            Buka menu <strong class="text-gray-900">Transaksi</strong>, pilih produk lalu klik <em>"Bayar"</em>. Pilih metode pembayaran (Tunai, QRIS, atau Transfer). Masukkan nominal, sistem akan menghitung kembalian otomatis. Cetak struk setelah selesai.
          </div>
        </div>
      </div>

      <!-- item 3 -->
      <div class="faq-item border-b border-border" data-question="cara buka tutup shift kas kasir">
        <button class="faq-trigger w-full flex items-center justify-between gap-3 px-4 py-3 text-left transition" onclick="toggleFaq(this)">
          <span class="text-xs font-medium text-gray-900 leading-snug">Cara buka dan tutup shift kas?</span>
          <svg class="faq-chevron flex-shrink-0 text-muted" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-answer bg-terra-xs">
          <div class="px-4 py-3 text-xs text-muted leading-relaxed">
            Buka menu <strong class="text-gray-900">Shift Kasir</strong>, klik <em>"Buka Shift"</em> dan masukkan saldo awal kas. Saat selesai bertugas, klik <em>"Tutup Shift"</em> lalu masukkan saldo akhir. Sistem akan merekap selisih otomatis.
          </div>
        </div>
      </div>

      <!-- item 4 -->
      <div class="faq-item border-b border-border" data-question="cara melihat riwayat transaksi laporan penjualan harian">
        <button class="faq-trigger w-full flex items-center justify-between gap-3 px-4 py-3 text-left transition" onclick="toggleFaq(this)">
          <span class="text-xs font-medium text-gray-900 leading-snug">Cara melihat riwayat transaksi?</span>
          <svg class="faq-chevron flex-shrink-0 text-muted" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-answer bg-terra-xs">
          <div class="px-4 py-3 text-xs text-muted leading-relaxed">
            Buka menu <strong class="text-gray-900">Riwayat Transaksi</strong>. Data dikelompokkan per hari. Untuk laporan lengkap, buka <em>Laporan Transaksi</em> — tersedia filter tanggal dan opsi ekspor PDF.
          </div>
        </div>
      </div>

      <!-- item 5 -->
      <div class="faq-item border-b border-border" data-question="cara melakukan absensi check in check out kasir">
        <button class="faq-trigger w-full flex items-center justify-between gap-3 px-4 py-3 text-left transition" onclick="toggleFaq(this)">
          <span class="text-xs font-medium text-gray-900 leading-snug">Cara melakukan absensi?</span>
          <svg class="faq-chevron flex-shrink-0 text-muted" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-answer bg-terra-xs">
          <div class="px-4 py-3 text-xs text-muted leading-relaxed">
            Buka menu <strong class="text-gray-900">Absensi</strong>, tekan <em>Check-in</em> saat masuk dan <em>Check-out</em> saat pulang. Sistem mencatat waktu otomatis. Keterlambatan akan ditandai kuning pada riwayat.
          </div>
        </div>
      </div>

      <!-- item 6 -->
      <div class="faq-item border-b border-border" data-question="prosedur pengembalian barang cacat refund">
        <button class="faq-trigger w-full flex items-center justify-between gap-3 px-4 py-3 text-left transition" onclick="toggleFaq(this)">
          <span class="text-xs font-medium text-gray-900 leading-snug">Prosedur pengembalian barang / refund?</span>
          <svg class="faq-chevron flex-shrink-0 text-muted" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-answer bg-terra-xs">
          <div class="px-4 py-3 text-xs text-muted leading-relaxed">
            Laporkan dalam <strong class="text-gray-900">3x24 jam</strong> disertai foto barang dan nomor transaksi. Hubungi admin untuk proses pengembalian atau penggantian. Keputusan akhir ada di tangan owner/admin.
          </div>
        </div>
      </div>

      <!-- item 7 -->
      <div class="faq-item" data-question="jadwal cuti bersama libur nasional 2026">
        <button class="faq-trigger w-full flex items-center justify-between gap-3 px-4 py-3 text-left transition" onclick="toggleFaq(this)">
          <span class="text-xs font-medium text-gray-900 leading-snug">Jadwal cuti bersama tahun 2026?</span>
          <svg class="faq-chevron flex-shrink-0 text-muted" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="faq-answer bg-terra-xs">
          <div class="px-4 py-3 text-xs text-muted leading-relaxed">
            Mengikuti ketetapan pemerintah. Informasi lengkap tersedia di pengumuman dari admin. Pengajuan cuti disampaikan minimal <strong class="text-gray-900">3 hari sebelumnya</strong> langsung ke admin atau owner.
          </div>
        </div>
      </div>

      <!-- Empty state -->
      <div id="emptyState" class="hidden px-4 py-8 text-center">
        <svg class="mx-auto mb-2 text-border" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <p class="text-sm text-muted">Pertanyaan tidak ditemukan.</p>
        <p class="text-xs text-border mt-1">Coba kata kunci lain atau hubungi admin.</p>
      </div>
    </div>

    <!-- Contact Card -->
    <div class="anim anim-5 bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-5 text-center shadow-md">
      <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-3">
        <svg width="20" height="20" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
          <path d="M3 18v-6a9 9 0 0 1 18 0v6"/>
          <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>
        </svg>
      </div>
      <h2 class="text-white font-bold text-base mb-1">Butuh bantuan lebih lanjut?</h2>
      <p class="text-white/60 text-xs leading-relaxed mb-4">Admin tersedia pukul 09:00 – 18:00 WIB</p>
      <a href="https://wa.me/6285261246660"
         target="_blank"
         class="w-full bg-terra hover:bg-terra-l text-white font-semibold text-sm py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-lg">
        <svg width="14" height="14" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.15 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.06 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16.92z"/>
        </svg>
        Hubungi Admin
      </a>
    </div>

  </div>
</main>

<!-- Bottom Nav Kasir -->
@include('kasir.components.navbar')

<script>
  function toggleFaq(btn) {
    const item    = btn.closest('.faq-item');
    const answer  = item.querySelector('.faq-answer');
    const chevron = btn.querySelector('.faq-chevron');
    const isOpen  = answer.classList.contains('open');
    document.querySelectorAll('.faq-answer.open').forEach(a => {
      a.classList.remove('open');
      a.closest('.faq-item').querySelector('.faq-chevron').classList.remove('open');
    });
    if (!isOpen) {
      answer.classList.add('open');
      chevron.classList.add('open');
    }
  }

  function filterFaq(query) {
    const items    = document.querySelectorAll('.faq-item');
    const clearBtn = document.getElementById('clearBtn');
    const empty    = document.getElementById('emptyState');
    const label    = document.getElementById('sectionLabel');
    const q        = query.toLowerCase().trim();
    clearBtn.classList.toggle('hidden', q === '');
    label.textContent = q ? 'Hasil Pencarian' : 'Pertanyaan Populer';
    let found = 0;
    items.forEach(item => {
      const text  = item.dataset.question || '';
      const match = text.includes(q);
      item.style.display = match ? '' : 'none';
      if (match) found++;
    });
    empty.classList.toggle('hidden', found > 0);
  }

  function clearSearch() {
    const input = document.querySelector('input[placeholder="Cari topik bantuan..."]');
    input.value = '';
    filterFaq('');
    input.focus();
  }
</script>
</body>
</html>