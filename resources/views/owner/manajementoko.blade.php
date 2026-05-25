<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Toko | Kashy</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        kashy: {
          dark: '#1a1a1a',
          brown: '#C49A6C',
          'brown-deep': '#7B4F2E',
          cream: '#F8F5F1',
          'cream-dark': '#EDE5DB',
          muted: '#8A7968',
          border: '#E0D8CE',
        }
      },
      fontFamily: {
        poppins: ['Poppins', 'sans-serif']
      },
      boxShadow: {
        card: '0 2px 18px 0 rgba(60,40,10,.07)',
        btn: '0 4px 14px 0 rgba(196,154,108,.35)',
      }
    }
  }
}
</script>

<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: 'Poppins', sans-serif;
}

body {
  background: #F8F5F1;
}

#sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 280px;
  background: #fff;
  box-shadow: 2px 0 24px rgba(60,40,10,.12);
  z-index: 60;
  transition: transform .3s cubic-bezier(0.2,0.9,0.4,1.1);
  display: flex;
  flex-direction: column;
  overflow-y: auto;
  transform: translateX(-100%);
}

#sidebar.sidebar-open {
  transform: translateX(0);
}

#overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.45);
  z-index: 55;
  backdrop-filter: blur(3px);
}

#overlay.show {
  display: block;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(18px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-up { animation: fadeUp .45s ease both; }
.d1 { animation-delay: .05s; }
.d2 { animation-delay: .10s; }
.d3 { animation-delay: .15s; }
.d4 { animation-delay: .20s; }
.d5 { animation-delay: .25s; }

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 11px 18px;
  border-radius: 12px;
  cursor: pointer;
  transition: all .15s;
  font-size: 14px;
  font-weight: 500;
  color: #1a1a1a;
  text-decoration: none;
  width: 100%;
}

.nav-item:hover {
  background: #F5F0EB;
}

.nav-item.active {
  background: #F7EFE5;
  color: #7B4F2E;
  font-weight: 600;
}

.nav-item.active svg {
  stroke: #7B4F2E;
}

.form-label {
  font-size: 11px;
  font-weight: 700;
  color: #8A7968;
  text-transform: uppercase;
  letter-spacing: .07em;
  margin-bottom: 6px;
  display: block;
}

.form-input {
  width: 100%;
  padding: 14px 16px;
  border: 1px solid #E8DED3;
  border-radius: 16px;
  font-size: 13px;
  color: #1a1a1a;
  background: #fff;
  outline: none;
  transition: all .25s ease;
  box-shadow: 0 2px 8px rgba(60,40,10,.03);
}

.form-input:focus {
  border-color: #C49A6C;
  box-shadow: 0 0 0 4px rgba(196,154,108,.08);
}

.form-input::placeholder {
  color: #BFB4AC;
}

select.form-input {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238A7968' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 14px center;
  padding-right: 36px;
  cursor: pointer;
}

.section-title {
  font-size: 13px;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 12px;
}

#toast {
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%);
  background: #1a1a1a;
  color: #fff;
  font-size: 12px;
  font-weight: 500;
  padding: 12px 20px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 8px;
  opacity: 0;
  pointer-events: none;
  transition: opacity .3s, transform .3s;
  z-index: 70;
}

#toast.show {
  opacity: 1;
  transform: translateX(-50%) translateY(-8px);
}

::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-thumb {
  background: #C49A6C;
  border-radius: 10px;
}
</style>
</head>

<body>

@include('owner.components.sidebar')

<main id="main" class="min-h-screen bg-kashy-cream">

@include('owner.components.topbar')

<div class="px-5 md:px-8 py-6 max-w-2xl mx-auto">

  <!-- Header -->
  <div class="bg-white rounded-2xl border border-kashy-border shadow-card p-8 mb-8 fade-up d1">
    <h1 class="text-4xl font-extrabold text-kashy-dark">Manajemen Toko</h1>
    <p class="text-sm text-kashy-muted mt-2">
      Kelola identitas merek dan informasi operasional toko Anda.
    </p>
  </div>

  <form id="storeForm" onsubmit="simpanProfil(event)" class="flex flex-col gap-7">

    <!-- Nama Toko -->
    <div class="bg-white rounded-2xl border border-kashy-border shadow-card p-6 fade-up d2">
      <label class="form-label">Nama Toko</label>
      <input type="text" class="form-input" value="SND STORE" placeholder="Nama toko Anda"/>
    </div>

    <!-- Informasi Kontak -->
    <div class="bg-white rounded-2xl border border-kashy-border shadow-card p-6 fade-up d3">
      <p class="section-title">Informasi Kontak</p>

      <div class="flex flex-col gap-3">
        <div>
          <label class="form-label">Alamat Email</label>
          <input type="email" class="form-input" value="contact@earthandgrace.com"/>
        </div>

        <div>
          <label class="form-label">Nomor Handphone</label>
          <input type="tel" class="form-input" value="+62 812-3456-7890"/>
        </div>
      </div>
    </div>

    <!-- Alamat -->
    <div class="bg-white rounded-2xl border border-kashy-border shadow-card p-6 fade-up d4">
      <p class="section-title">Alamat Fisik</p>

      <div class="flex flex-col gap-3">
        <div>
          <label class="form-label">Jalan</label>
          <input type="text" class="form-input" value="Jl. Gatot Subroto No.123"/>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="form-label">Kota</label>
            <input type="text" class="form-input" value="Medan"/>
          </div>

          <div>
            <label class="form-label">Kode Pos</label>
            <input type="text" class="form-input" value="20112"/>
          </div>
        </div>

        <div>
          <label class="form-label">Negara</label>
          <select class="form-input">
            <option selected>Indonesia</option>
            <option>Malaysia</option>
            <option>Singapura</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Tombol -->
    <div class="fade-up d5 flex flex-col gap-3 mt-1">

      <button
        type="submit"
        class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl font-bold text-sm tracking-widest text-white uppercase transition-all duration-200 hover:opacity-90 active:scale-[.98]"
        style="background:#C49A6C; box-shadow:0 4px 14px rgba(196,154,108,.35);">
        Simpan Profil
      </button>

      <button
        type="button"
        onclick="buangPerubahan()"
        class="w-full py-4 rounded-2xl font-bold text-kashy-dark text-sm tracking-widest uppercase border border-kashy-border transition-all duration-200 hover:bg-kashy-cream active:scale-[.98] bg-white">
        Buang Perubahan
      </button>

    </div>

  </form>
</div>
</main>

<!-- Toast -->
<div id="toast">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#C49A6C" stroke-width="2.5">
    <polyline points="20 6 9 17 4 12"/>
  </svg>
  <span id="toastMsg">Profil toko berhasil disimpan!</span>
</div>

<div id="overlay"></div>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const menuBtn = document.getElementById('global-menu-toggle');

function openSidebar() {
  if (!sidebar || !overlay) return;
  sidebar.classList.add('sidebar-open');
  overlay.classList.add('show');
  document.body.style.overflow = 'hidden';
}

function closeSidebar() {
  if (!sidebar || !overlay) return;
  sidebar.classList.remove('sidebar-open');
  overlay.classList.remove('show');
  document.body.style.overflow = '';
}

if (menuBtn) {
  menuBtn.addEventListener('click', e => {
    e.stopPropagation();
    openSidebar();
  });
}

if (overlay) {
  overlay.addEventListener('click', closeSidebar);
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeSidebar();
});

let toastTimer;

function showToast(msg) {
  const toast = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;

  toast.classList.add('show');

  clearTimeout(toastTimer);

  toastTimer = setTimeout(() => {
    toast.classList.remove('show');
  }, 2500);
}

function simpanProfil(e) {
  e.preventDefault();
  showToast('Profil toko berhasil disimpan!');
}

function buangPerubahan() {
  if (confirm('Buang semua perubahan?')) {
    document.getElementById('storeForm').reset();
    showToast('Perubahan dibuang.');
  }
}
</script>

</body>
</html>