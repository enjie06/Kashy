<header class="sticky top-0 z-50 bg-gray-900 shadow-sm h-12 px-4 flex items-center justify-between">
    {{-- Tombol Back --}}
    <button onclick="window.history.back()"
            class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 transition">

        <svg width="20" height="20"
             viewBox="0 0 24 24"
             fill="none"
             stroke="white"
             stroke-width="2.5">

            <path d="M19 12H5"/>
            <path d="M12 19L5 12L12 5"/>

        </svg>

    </button>

    {{-- Logo --}}
    <span class="font-bold text-white text-lg tracking-wider absolute left-1/2 -translate-x-1/2">
        Kashy
    </span>

    {{-- Spacer kanan (biar logo tetap di tengah) --}}
    <div class="w-8"></div>

</header>