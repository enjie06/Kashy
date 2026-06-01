<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Login | Kashy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Poppins', 'sans-serif'],
                        body:    ['Poppins', 'sans-serif'],
                    },
                    keyframes: {
                        slideUp: {
                            from: { opacity: '0', transform: 'translateY(32px)' },
                            to: { opacity: '1', transform: 'translateY(0)' }
                        },
                        lineGrow: {
                            from: { width: '0' },
                            to: { width: '3rem' }
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% center' },
                            '100%': { backgroundPosition: '200% center' }
                        },
                        shake: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '20%, 60%': { transform: 'translateX(-4px)' },
                            '40%, 80%': { transform: 'translateX(4px)' }
                        },
                        spinSlow: {
                            '0%': { transform: 'rotate(0deg)' },
                            '100%': { transform: 'rotate(360deg)' }
                        }
                    },
                    animation: {
                        'slide-up': 'slideUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both',
                        'line-grow': 'lineGrow 0.6s 0.4s cubic-bezier(0.22, 1, 0.36, 1) both',
                        'shimmer-slow': 'shimmer 4s linear infinite',
                        'shake-fast': 'shake 0.4s ease',
                        'spin-slow': 'spinSlow 1s linear infinite'
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .input-field:focus { outline: none; }
    </style>
</head>
<body class="bg-[#F5F0EB] min-h-screen flex flex-col">

<!-- Topbar (tidak diubah) -->
<nav class="sticky top-0 z-50 bg-gray-900 h-12 px-4 flex items-center justify-center relative shadow-sm">
    <a href="javascript:history.back()"
       class="absolute left-4 flex items-center gap-1.5 text-xs text-gray-500 hover:text-white transition-colors no-underline">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M19 12H5m7-7-7 7 7 7"/>
        </svg>
    </a>
    <span class="font-display text-lg font-bold text-white tracking-wider">
        Kashy
    </span>
    <div id="loadingIndicator"
         class="absolute right-5 w-6 h-6 hidden items-center justify-center">
        <svg class="w-5 h-5 text-[#C8966C] animate-spin-slow"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2">
            <circle cx="12" cy="12" r="10" stroke-opacity="0.3"/>
            <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/>
        </svg>
    </div>
</nav>

<!-- Main Content - menggunakan ukuran card yang sama seperti awal (tidak terlalu lebar di tab) -->
<div class="flex-1 flex items-center justify-center px-4 py-8 sm:py-12 md:py-14">
    <!-- Kembali ke ukuran asli: max-w-sm untuk HP, sm:max-w-md untuk tablet, lg:max-w-lg untuk desktop -->
    <div class="w-full max-w-sm sm:max-w-md lg:max-w-lg">

        <!-- Card dengan padding internal yang nyaman di HP -->
        <div class="relative bg-white rounded-3xl shadow-[0_8px_48px_rgba(28,28,28,0.10)] overflow-hidden animate-slide-up [animation-delay:0.05s]">

            <!-- Top shimmer bar -->
            <div class="h-1.5 w-full bg-gradient-to-r from-[#C8966C] via-[#E5B18A] via-[#F0D7C7] via-[#E5B18A] to-[#C8966C] bg-[length:200%] animate-shimmer-slow"></div>

            <!-- Padding dalam card: lebih lega di tablet, tetap nyaman di HP -->
            <div class="px-5 sm:px-8 md:px-10 pt-6 sm:pt-8 md:pt-10 pb-7 sm:pb-8 md:pb-10">

                <!-- Header -->
                <div class="mb-6 sm:mb-8">
                    <div class="flex items-start justify-between mb-1">
                        <!-- Ukuran judul agak kecil di HP agar proporsional -->
                        <h1 class="font-sans text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 tracking-wide leading-none">
                            SND STORE
                        </h1>
                    </div>
                    <div class="h-0.5 bg-[#C8966C] rounded-full mb-3 sm:mb-4 w-12 animate-line-grow"></div>
                    <p class="text-xs sm:text-sm text-stone-400 font-light leading-relaxed">
                        Masuk ke akun Anda untuk mengakses dashboard
                    </p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-2.5 text-sm text-emerald-700">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5 sm:space-y-5" id="loginForm">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500 mb-1.5 sm:mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C0AFA3" stroke-width="2">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   placeholder="nama@email.com" autocomplete="email"
                                   class="w-full pl-11 pr-4 py-3.5 border border-[#EAE0D6] rounded-xl bg-[#FAF8F6] text-sm text-gray-900 placeholder-stone-300 focus:border-[#C8966C] focus:shadow-[0_0_0_3px_rgba(200,150,108,0.15)] transition-all duration-200 @error('email') border-red-300 bg-red-50/30 @enderror"
                                   required>
                        </div>
                        @error('email')
                            <div class="mt-2 flex items-center gap-1.5 text-red-400 text-xs animate-shake-fast">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                            <label for="password" class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-gray-500">
                                Password
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C0AFA3" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>
                            <input type="password" id="password" name="password"
                                   placeholder="••••••••" autocomplete="current-password"
                                   class="w-full pl-11 pr-12 py-3.5 border border-[#EAE0D6] rounded-xl bg-[#FAF8F6] text-sm text-gray-900 placeholder-stone-300 focus:border-[#C8966C] focus:shadow-[0_0_0_3px_rgba(200,150,108,0.15)] transition-all duration-200 @error('password') border-red-300 bg-red-50/30 @enderror"
                                   required>
                            <button type="button" id="togglePwd"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-400 hover:text-[#C8966C] transition-colors focus:outline-none">
                                <svg id="eyeOpen" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg id="eyeClosed" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="hidden">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="mt-2 flex items-center gap-1.5 text-red-400 text-xs animate-shake-fast">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6M9 9l6 6"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3 sm:pt-4">
                        <button type="submit" id="loginButton"
                                class="w-full py-3.5 sm:py-4 bg-[#1c1c1c] hover:bg-[#C8966C] text-white font-semibold rounded-xl flex items-center justify-center gap-2.5 text-sm sm:text-base tracking-wide transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(200,150,108,0.35)] active:translate-y-0">
                            <span>Login</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security note -->
        <div class="mt-6 sm:mt-5 flex items-center justify-center gap-2">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9C8B7E" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <span class="text-xs text-stone-400">Koneksi aman & terenkripsi</span>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePasswordVisibility() {
        const pwdInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');
        const isPassword = pwdInput.type === 'password';
        pwdInput.type = isPassword ? 'text' : 'password';
        eyeOpen.classList.toggle('hidden', isPassword);
        eyeClosed.classList.toggle('hidden', !isPassword);
    }
    document.getElementById('togglePwd').addEventListener('click', togglePasswordVisibility);

    // Loading feedback di navbar saat form disubmit
    const form = document.getElementById('loginForm');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const loginButton = document.getElementById('loginButton');
    const originalButtonText = loginButton.innerHTML;

    form.addEventListener('submit', function(e) {
        loadingIndicator.classList.remove('hidden');
        loadingIndicator.classList.add('flex');
        loginButton.disabled = true;
        loginButton.innerHTML = '<span>Memproses...</span><svg class="w-4 h-4 animate-spin-slow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.3"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-linecap="round"/></svg>';
    });

    window.addEventListener('load', function() {
        loadingIndicator.classList.add('hidden');
        loadingIndicator.classList.remove('flex');
        if (loginButton) {
            loginButton.disabled = false;
            loginButton.innerHTML = originalButtonText;
        }
    });
</script>
</body>
</html>