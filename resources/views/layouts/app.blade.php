<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $title ?? 'libellis-shop' }} | {{ $siteName }}</title>
        <meta name="description" content="Toko kelontong online sederhana libellis-shop untuk sembako, kebutuhan rumah, dan pakaian." />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#EEEEEE] text-slate-900 antialiased overflow-x-hidden">
        <div class="min-h-screen flex flex-col">
            <header class="bg-white/95 backdrop-blur border-b border-slate-200 sticky top-0 z-40 shadow-sm">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between gap-3 py-4">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 text-slate-900">
                            <div class="h-11 w-11 rounded-2xl bg-[#6FCF97] grid place-items-center text-white text-lg font-bold shadow-sm">L</div>
                            <div>
                                <p class="text-sm uppercase tracking-[0.25em] font-semibold text-slate-500">libellis-shop</p>
                                <p class="font-semibold text-xl">Toko Kelontong</p>
                            </div>
                        </a>

                        <nav class="hidden md:flex items-center gap-4 text-sm font-medium text-slate-700">
                            <a href="{{ route('home') }}" class="hover:text-[#2FA884] transition">Beranda</a>
                            <a href="{{ route('products.index') }}" class="hover:text-[#2FA884] transition">Produk</a>
                            <a href="{{ route('chatbot.index') }}" class="hover:text-[#2FA884] transition">Chatbot</a>
                            @auth
                                <a href="{{ route('order.history') }}" class="hover:text-[#2FA884] transition">Riwayat Pesanan</a>
                            @endauth
                        </nav>

                        <div class="flex flex-wrap items-center gap-3 justify-end">
                            <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm hover:border-[#2FA884] transition">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 7.5M17 13l1.5 7.5M6 21h12" />
                                </svg>
                                <span class="rounded-full bg-[#6FCF97] px-2 py-0.5 text-xs text-white">{{ $navbarCartCount }}</span>
                            </a>

                            @guest
                                <a href="{{ route('login') }}" class="text-sm text-slate-700 hover:text-[#2FA884] transition">Login</a>
                                <a href="{{ route('register') }}" class="inline-flex items-center rounded-full bg-[#6FCF97] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#2FA884] transition">Register</a>
                            @else
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white p-2 text-slate-700 shadow-sm transition hover:border-[#2FA884] hover:text-[#2FA884]" aria-label="Profil">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A10.022 10.022 0 0112 15c2.5 0 4.79.92 6.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm font-semibold text-[#2FA884] hover:text-[#1c7f67] transition">Logout</button>
                                    </form>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                @if(session('success'))
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
                        <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-900 shadow-sm">{{ session('success') }}</div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-900 shadow-sm">{{ session('error') }}</div>
                    </div>
                @endif
                @yield('content')
            </main>

            <footer class="bg-white border-t border-slate-200">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 text-sm text-slate-600">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <p>© {{ date('Y') }} {{ $siteName }}. Toko kelontong untuk kebutuhan rumah dan sembako sehari-hari.</p>
                        <p>Pengiriman cepat, mudah, dan aman untuk belanja kebutuhan keluarga.</p>
                    </div>
                </div>
            </footer>
        </div>

        <div class="fixed left-4 right-4 bottom-4 z-50 flex w-full max-w-[360px] flex-col gap-3 px-0 sm:left-auto sm:right-5 sm:bottom-24 sm:w-auto sm:max-w-none sm:items-end sm:px-0">
            <a href="{{ route('chatbot.index') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#2FA884] px-4 py-3 text-sm font-semibold text-white shadow-2xl transition hover:scale-105 sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                    <path d="M21 15a2 2 0 0 1-2 2H8l-4 4V5a2 2 0 0 1 2 2h11a2 2 0 0 1 2 2v10zM7 9h10M7 13h7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                </svg>
                Chatbot
            </a>
            <a href="https://wa.me/{{ config('services.whatsapp.number') }}?text={{ urlencode('Halo ' . $siteName . ', saya ingin bertanya tentang produk anda') }}" target="_blank" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#25D366] px-4 py-3 text-sm font-semibold text-white shadow-2xl transition hover:scale-105 sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                    <path d="M21.707 4.293a1 1 0 0 0-1.414 0L15.586 9H9a5 5 0 0 0-5 5v.586L2.293 14.293A1 1 0 0 0 1 15.293V19a1 1 0 0 0 1 1h3.707a1 1 0 0 0 .707-.293l2.708-2.707H14a7 7 0 0 0 7-7V5a1 1 0 0 0-.293-.707z" />
                </svg>
                WhatsApp
            </a>
        </div>
    </body>
</html>
