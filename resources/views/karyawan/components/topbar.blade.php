<header class="sticky top-0 z-50 bg-gray-900 shadow-sm h-12 px-4 flex items-center justify-between">
    <div class="w-6"></div>

    <div class="absolute left-1/2 -translate-x-1/2">
        <span class="font-bold text-white text-lg tracking-wider">
            Kashy
        </span>
    </div>

    @if (!request()->routeIs('karyawan.profile'))
        <a href="{{ route('karyawan.profile') }}"
           class="flex items-center gap-1.5 hover:opacity-80 transition">

            <div class="w-7 h-7 rounded-full bg-[#C8966C] flex items-center justify-center text-white font-semibold text-xs">
                {{ strtoupper(substr(Auth::user()->name ?? 'K', 0, 1)) }}
            </div>

        </a>
    @else
        <div class="w-6"></div>
    @endif

</header>