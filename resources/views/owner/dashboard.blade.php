<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
  <title>Kashy – Dashboard Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { poppins: ['Poppins','sans-serif'] },
          colors: {
            kashy: {
              dark:        '#1a1a1a',
              brown:       '#C49A6C',
              'brown-deep':'#7B4F2E',
              cream:       '#F5F0EB',
              'cream-dark':'#EDE5DB',
              muted:       '#8A7968',
              border:      '#E0D8CE',
              sidebar:     '#ffffff',
            }
          },
          boxShadow: {
            card:    '0 2px 18px 0 rgba(60,40,10,.07)',
            sidebar: '2px 0 20px 0 rgba(60,40,10,.08)',
            btn:     '0 4px 14px 0 rgba(196,154,108,.35)',
          }
        }
      }
    }
  </script>
  <style>
    * { font-family:'Poppins',sans-serif; box-sizing:border-box; }
    body { background:#F5F0EB; margin:0; }

    /* ── Sidebar (sliding drawer) ── */
    #sidebar {
      position: fixed; top:0; left:0; height:100vh; width:280px;
      background:#fff; box-shadow: 2px 0 24px rgba(60,40,10,.12);
      z-index:60; transition: transform .3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
      display:flex; flex-direction:column; overflow-y:auto;
      transform: translateX(-100%);
    }
    #sidebar.sidebar-open {
      transform: translateX(0%);
    }

    /* Overlay gelap */
    #overlay {
      display:none; position:fixed; inset:0; background:rgba(0,0,0,.45);
      z-index:55; backdrop-filter:blur(3px);
    }
    #overlay.show { display:block; }

    /* Animasi fade-up */
    @keyframes fadeUp {
      from { opacity:0; transform:translateY(18px); }
      to   { opacity:1; transform:translateY(0); }
    }
    .fade-up { animation: fadeUp .45s ease both; }
    .d1 { animation-delay:.05s; }
    .d2 { animation-delay:.10s; }
    .d3 { animation-delay:.15s; }
    .d4 { animation-delay:.20s; }
    .d5 { animation-delay:.25s; }

    /* Stat card left accent */
    .stat-card { position:relative; overflow:hidden; }
    .stat-card::before {
      content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
      background:#C49A6C; border-radius:4px 0 0 4px;
    }

    /* Nav items sidebar */
    .nav-item {
      display:flex; align-items:center; gap:12px; padding:11px 18px;
      border-radius:12px; cursor:pointer; transition: all .15s;
      font-size:14px; font-weight:500; color:#1a1a1a;
      text-decoration:none; width:100%;
    }
    .nav-item:hover { background:#F5F0EB; }
    .nav-item.active { background:#F7EFE5; color:#7B4F2E; font-weight:600; }
    .nav-item.active svg { stroke:#7B4F2E; }

    /* Module buttons */
    .mod-btn {
      display:flex; align-items:center; gap:12px; width:100%;
      background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.12);
      border-radius:12px; padding:14px 16px; cursor:pointer;
      transition: background .18s, transform .12s;
      color:#fff; font-size:13.5px; font-weight:500;
    }
    .mod-btn:hover { background:rgba(255,255,255,.13); transform:translateX(3px); }

    /* Scrollbar */
    ::-webkit-scrollbar { width:4px; }
    ::-webkit-scrollbar-track { background:transparent; }
    ::-webkit-scrollbar-thumb { background:#C49A6C; border-radius:10px; }
  </style>
</head>
@include('owner.components.sidebar')
<body>

<!-- ================= MAIN CONTENT ================= -->
<main id="main" class="min-h-screen bg-kashy-cream">
   @include('owner.components.topbar')

  <!-- Dashboard Content (responsif tablet) -->
  <div class="px-5 md:px-8 py-6 max-w-7xl mx-auto">
    <!-- Hero -->
    <div class="fade-up mb-6">
      <h1 class="text-3xl md:text-4xl font-extrabold text-kashy-dark leading-tight">
        Gambaran Umum<br/>Kinerja Toko
      </h1>
      <p class="mt-2 text-kashy-muted text-sm leading-relaxed max-w-md">
        Selamat Datang Kembali, Admin.<br/>
        Berikut ringkasan terkurasi tentang aktivitas terkini sistem Anda.
      </p>
      <button class="mt-4 flex items-center gap-2 px-5 py-3 rounded-xl bg-kashy-brown text-white text-sm font-semibold shadow-btn hover:bg-kashy-brown-deep active:scale-[.97] transition-all duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Ekspor Laporan
      </button>
    </div>

    <!-- Stat Cards Grid (grid responsif: 1 kolom mobile, 2 tablet, 3 desktop) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
      <!-- Total Pendapatan -->
      <div class="fade-up d1 stat-card bg-white rounded-2xl p-5 shadow-card">
        <div class="flex items-start justify-between mb-3">
          <span class="text-[11px] font-semibold tracking-widest text-kashy-muted uppercase">Total Pendapatan</span>
          <div class="w-9 h-9 rounded-xl bg-kashy-cream flex items-center justify-center">
            <svg class="w-5 h-5 text-kashy-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
              <rect x="2" y="6" width="20" height="12" rx="2"/>
              <circle cx="12" cy="12" r="3"/>
              <path d="M6 12h.01M18 12h.01"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-kashy-dark">$142,850<span class="text-lg">.00</span></p>
      </div>

      <!-- Total Pesanan -->
      <div class="fade-up d2 stat-card bg-white rounded-2xl p-5 shadow-card">
        <div class="flex items-start justify-between mb-3">
          <span class="text-[11px] font-semibold tracking-widest text-kashy-muted uppercase">Total Pesanan</span>
          <div class="w-9 h-9 rounded-xl bg-kashy-cream flex items-center justify-center">
            <svg class="w-5 h-5 text-kashy-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
              <line x1="3" y1="6" x2="21" y2="6"/>
              <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-kashy-dark">1,284</p>
      </div>

      <!-- Diskon Aktif -->
      <div class="fade-up d3 stat-card bg-white rounded-2xl p-5 shadow-card">
        <div class="flex items-start justify-between mb-3">
          <span class="text-[11px] font-semibold tracking-widest text-kashy-muted uppercase">Diskon Aktif</span>
          <div class="w-9 h-9 rounded-xl bg-kashy-cream flex items-center justify-center">
            <svg class="w-5 h-5 text-kashy-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
              <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
              <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-kashy-dark">8</p>
      </div>
    </div>

    <!-- Modul Manajemen (grid responsif tablet) -->
    <div class="fade-up d4 bg-kashy-dark rounded-2xl p-6 mb-6">
      <p class="text-[11px] font-semibold tracking-widest text-white/50 uppercase mb-4">Modul Manajemen</p>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <button class="mod-btn">
          <svg class="w-5 h-5 flex-shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <rect x="2" y="3" width="20" height="14" rx="2"/>
            <line x1="8" y1="21" x2="16" y2="21"/>
            <line x1="12" y1="17" x2="12" y2="21"/>
          </svg>
          Kontrol Inventaris
        </button>
        <button class="mod-btn">
          <svg class="w-5 h-5 flex-shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
            <circle cx="9" cy="7" r="4"/>
            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
          </svg>
          Hubungan Pelanggan
        </button>
        <button class="mod-btn">
          <svg class="w-5 h-5 flex-shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
          </svg>
          Pemasaran &amp; SEO
        </button>
        <button class="mod-btn">
          <svg class="w-5 h-5 flex-shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <line x1="18" y1="20" x2="18" y2="10"/>
            <line x1="12" y1="20" x2="12" y2="4"/>
            <line x1="6"  y1="20" x2="6"  y2="14"/>
            <rect x="3" y="2" width="18" height="20" rx="2" opacity=".2" fill="currentColor" stroke="none"/>
          </svg>
          Rangkaian Analisis Lengkap
        </button>
      </div>
    </div>

    <!-- Dukungan Admin -->
    <div class="fade-up d5 bg-white rounded-2xl p-6 shadow-card flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div class="flex items-start gap-3">
        <div class="w-10 h-10 rounded-xl bg-kashy-cream flex items-center justify-center flex-shrink-0">
          <svg class="w-5 h-5 text-kashy-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.29 6.29l.95-.94a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
          </svg>
        </div>
        <div>
          <p class="font-semibold text-kashy-dark text-base">Dukungan Admin</p>
          <p class="text-sm text-kashy-muted mt-1 max-w-xs leading-relaxed">
            Akses langsung ke tim dukungan perusahaan kami yang berdedikasi untuk bantuan teknis.
          </p>
        </div>
      </div>
      <button class="flex-shrink-0 px-6 py-3 border-2 border-kashy-dark rounded-xl font-semibold text-kashy-dark text-sm hover:bg-kashy-dark hover:text-white active:scale-[.97] transition-all duration-200">
        Hubungi Dukungan
      </button>
    </div>
  </div>
</main>

<script>
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const menuBtn = document.getElementById('global-menu-toggle');
  const navLinks = document.querySelectorAll('[data-nav]');

  function openSidebar() {
    sidebar.classList.add('sidebar-open');
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
  }

  function closeSidebar() {
    sidebar.classList.remove('sidebar-open');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
  }

  function toggleSidebar() {
    if (sidebar.classList.contains('sidebar-open')) {
      closeSidebar();
    } else {
      openSidebar();
    }
  }

  if (menuBtn) {
    menuBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleSidebar();
    });
  }

  function setActiveNavItem(clickedItem) {
    navLinks.forEach(link => {
      link.classList.remove('active');
      const svg = link.querySelector('svg');
      if (svg) svg.setAttribute('stroke', 'currentColor');
    });
    clickedItem.classList.add('active');
    const activeSvg = clickedItem.querySelector('svg');
    if (activeSvg) activeSvg.setAttribute('stroke', '#7B4F2E');
    if (sidebar.classList.contains('sidebar-open')) closeSidebar();
  }

  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      setActiveNavItem(link);
    });
    if (link.classList.contains('active')) {
      const svg = link.querySelector('svg');
      if (svg) svg.setAttribute('stroke', '#7B4F2E');
    } else {
      const svg = link.querySelector('svg');
      if (svg) svg.setAttribute('stroke', 'currentColor');
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && sidebar.classList.contains('sidebar-open')) closeSidebar();
  });

  // Pastikan sidebar tertutup saat load
  sidebar.classList.remove('sidebar-open');
  overlay.classList.remove('show');
  document.body.style.overflow = '';
</script>
</body>
</html>