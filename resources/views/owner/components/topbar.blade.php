  <!-- Header dengan logo Kashy di TENGAH (icon menu kiri, avatar kanan) -->
  <header class="sticky top-0 z-50 bg-kashy-dark shadow-md px-5 md:px-8 py-3 flex items-center justify-between">
    <!-- Kiri: Ikon menu hamburger -->
    <button id="global-menu-toggle" class="text-white focus:outline-none hover:scale-105 transition-transform" aria-label="Buka menu navigasi">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <line x1="3" y1="6" x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>

    <!-- Tengah: Tulisan Kashy (center) -->
    <div class="absolute left-1/2 transform -translate-x-1/2 md:relative md:left-auto md:transform-none md:flex-1 md:text-center">
      <span class="font-bold text-white text-xl tracking-wide">Kashy</span>
    </div>

<!-- Kanan: Nama + Avatar -->
<!-- Kanan: Nama + Avatar -->
<a href="{{ route('owner.profile') }}"
   class="flex items-center gap-2 hover:opacity-80 transition">

    <span class="text-white text-sm font-medium max-w-[120px] truncate">
        {{ Auth::user()->name }}
    </span>

    @if(Auth::user()->profile_photo)
        <img
            src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
            alt="Foto Profil"
            class="w-8 h-8 rounded-full object-cover border border-white/20 shadow-sm"
        >
    @else
        <div class="w-8 h-8 rounded-full bg-kashy-brown flex items-center justify-center text-white font-bold text-sm shadow-sm">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
    @endif

</a>
  </header>