@php
$user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeSwitcher()" :class="{ 'dark': isDark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PojokBuku') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Skrip untuk mencegah Flash of Unstyled Content (FOUC) saat dark mode aktif --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

{{-- Menambahkan class untuk background dan warna teks di dark mode --}}

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-200">
    <div class="min-h-screen">
        <!-- Navigation -->
        {{-- Menambahkan class untuk background, border, dan teks di dark mode --}}
        <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo & Navigation Links -->
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('home') }}" class="flex items-center">
                                <div class="text-2xl mr-2">📚</div>
                                <span class="text-xl font-bold text-gray-900 dark:text-white">
                                    PojokBuku
                                </span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:ml-10 sm:flex">
                            <a href="{{ route('home') }}"
                                class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-gray-700' : '' }}">
                                Beranda
                            </a>
                            <a href="{{ route('books.index') }}"
                                class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('books.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-gray-700' : '' }}">
                                Koleksi Buku
                            </a>
                            <a href="{{ route('categories.index') }}"
                                class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('categories.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-gray-700' : '' }}">
                                Kategori
                            </a>
                            @auth
                            <a href="{{ route('borrows.index') }}"
                                class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('borrows.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-gray-700' : '' }}">
                                Peminjaman Saya
                            </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Search Bar (Hidden on mobile) -->
                    <div class="hidden md:flex items-center flex-1 max-w-lg mx-8">
                        <form action="{{ route('books.search') }}" method="GET" class="w-full">
                            <div class="relative">
                                <input
                                    type="text"
                                    name="search"
                                    placeholder="Cari buku..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-100 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                    value="{{ request('search') }}">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Right Side: Theme Toggle & User Menu -->
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <!-- THEME TOGGLE SWITCH -->
                        <button @click="toggleTheme" type="button" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                            <span class="sr-only">Toggle theme</span>
                            {{-- Sun Icon --}}
                            <svg x-show="!isDark" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{-- Moon Icon --}}
                            <svg x-show="isDark" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </button>

                        @auth
                        {{-- Debug role - HAPUS SETELAH TESTING --}}
                        {{-- <pre>{{ Auth::user()->role }}</pre> --}}

                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 text-sm font-medium text-gray-900 dark:text-gray-200 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full">
                                <div class="h-8 w-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5">
                                <!-- User Info -->
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                </div>

                                <!-- Profile Link -->
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profil
                                </a>

                                {{-- Dashboard Links berdasarkan role --}}
                                @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') ?? '#' }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Dashboard Admin
                                </a>
                                @else
                                {{-- Default untuk user biasa --}}
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Dashboard
                                </a>
                                @endif

                                <!-- Separator -->
                                <hr class="my-1 border-gray-200 dark:border-gray-700">

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <div class="hidden sm:flex items-center space-x-2">
                            <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">Daftar</a>
                        </div>
                        @endauth

                        <!-- Mobile menu button -->
                        <div class="sm:hidden">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" class="sm:hidden border-t border-gray-200 dark:border-gray-700">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-blue-600 hover:bg-gray-50 dark:hover:bg-gray-700">Beranda</a>
                    <a href="{{ route('books.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-blue-600 hover:bg-gray-50 dark:hover:bg-gray-700">Koleksi Buku</a>
                    {{-- ... other mobile links --}}
                    @guest
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 pb-3">
                        <div class="flex items-center px-4">
                            <a href="{{ route('login') }}" class="flex-1 text-center text-gray-700 dark:text-gray-300 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">Daftar</a>
                        </div>
                    </div>
                    @endguest
                </div>
            </div>
        </nav>

        @if (session('success') || session('error') || session('warning') || $errors->any())
        <div
            x-data="{ show: true, timeout: null }"
            x-init="timeout = setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300 transform"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="fixed top-20 left-0 right-0 w-full flex justify-center z-40"
            style="display: none;" {{-- Mencegah FOUC (Flash of Unstyled Content) --}}>
            <div class="max-w-4xl w-full px-4 sm:px-6 lg:px-8">
                {{-- Pesan Sukses --}}
                @if (session('success'))
                <div class="relative bg-green-100 dark:bg-green-900/50 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg shadow-lg flex justify-between items-center" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button @click="show = false; clearTimeout(timeout);" class="ml-4 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                        <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                        </svg>
                    </button>
                </div>
                @endif

                {{-- Pesan Error --}}
                @if (session('error'))
                <div class="relative bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg shadow-lg flex justify-between items-center" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <button @click="show = false; clearTimeout(timeout);" class="ml-4 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                        <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                        </svg>
                    </button>
                </div>
                @endif

                {{-- Pesan Peringatan --}}
                @if (session('warning'))
                <div class="relative bg-yellow-100 dark:bg-yellow-900/50 border border-yellow-400 dark:border-yellow-600 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded-lg shadow-lg flex justify-between items-center" role="alert">
                    <span class="block sm:inline">{{ session('warning') }}</span>
                    <button @click="show = false; clearTimeout(timeout);" class="ml-4 text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200">
                        <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                        </svg>
                    </button>
                </div>
                @endif

                {{-- Error Validasi --}}
                @if ($errors->any())
                <div class="relative bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg shadow-lg" role="alert">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="font-bold block">Terjadi kesalahan!</span>
                            <ul class="list-disc list-inside mt-2">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false; clearTimeout(timeout);" class="ml-4 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                            <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>


        <!-- Footer -->
        <footer class="bg-gray-900 dark:bg-slate-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Logo & Description -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center mb-4">
                            <div class="text-2xl mr-2">📚</div>
                            <span class="text-xl font-bold">Perpustakaan Digital</span>
                        </div>
                        <p class="text-gray-300 mb-4">
                            Platform perpustakaan digital terdepan yang menyediakan akses mudah ke ribuan koleksi buku berkualitas untuk mendukung pembelajaran dan pengembangan diri.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.221.085.342-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Tautan Cepat</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="{{ route('books.index') }}" class="text-gray-300 hover:text-white transition-colors">Koleksi Buku</a></li>
                            <li><a href="{{ route('categories.index') }}" class="text-gray-300 hover:text-white transition-colors">Kategori</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Tentang Kami</a></li>
                            <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Kontak</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                info@perpusdigital.com
                            </li>
                            <li class="flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                +62 21 1234 5678
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Jl. Pendidikan No. 123<br>
                                Jakarta Selatan, 12345
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                    <p>&copy; {{ date('Y') }} Perpustakaan Digital. Semua hak dilindungi.</p>
                    <p class="mt-2">
                        <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a> •
                        <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>

    {{-- ALPINE JS THEME SWITCHER LOGIC --}}
    <script>
        function themeSwitcher() {
            return {
                isDark: false,
                init() {
                    // Cek tema dari localStorage saat komponen diinisialisasi
                    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        this.isDark = true;
                    } else {
                        this.isDark = false;
                    }
                },
                toggleTheme() {
                    this.isDark = !this.isDark;
                    if (this.isDark) {
                        localStorage.setItem('color-theme', 'dark');
                        document.documentElement.classList.add('dark');
                    } else {
                        localStorage.setItem('color-theme', 'light');
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }
    </script>
</body>

</html>