{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6">
                    Perpustakaan 
                    <span class="text-yellow-300">Digital</span> 
                    Terdepan
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    Akses ribuan koleksi buku berkualitas kapan saja, di mana saja. 
                    Mulai perjalanan pembelajaran Anda bersama kami.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('books.index') }}" 
                       class="bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-semibold px-8 py-3 rounded-full transition-colors inline-flex items-center justify-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Jelajahi Koleksi
                    </a>
                    @guest
                        <a href="{{ route('register') }}" 
                           class="border-2 border-white hover:bg-white hover:text-blue-800 text-white font-semibold px-8 py-3 rounded-full transition-colors inline-flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Daftar Sekarang
                        </a>
                    @endguest
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-white/10 rounded-3xl transform rotate-6"></div>
                    <div class="relative bg-white/20 backdrop-blur-sm rounded-3xl p-8">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/30 rounded-2xl p-4 text-center">
                                <div class="text-3xl font-bold">{{ number_format($stats['total_books'] ?? 1000) }}+</div>
                                <div class="text-blue-100">Koleksi Buku</div>
                            </div>
                            <div class="bg-white/30 rounded-2xl p-4 text-center">
                                <div class="text-3xl font-bold">{{ number_format($stats['total_users'] ?? 500) }}+</div>
                                <div class="text-blue-100">Pengguna Aktif</div>
                            </div>
                            <div class="bg-white/30 rounded-2xl p-4 text-center">
                                <div class="text-3xl font-bold">{{ number_format($stats['total_categories'] ?? 15) }}+</div>
                                <div class="text-blue-100">Kategori</div>
                            </div>
                            <div class="bg-white/30 rounded-2xl p-4 text-center">
                                <div class="text-3xl font-bold">{{ number_format($stats['total_borrows'] ?? 2000) }}+</div>
                                <div class="text-blue-100">Total Peminjaman</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Cari Buku Favorit Anda</h2>
            <p class="text-xl text-gray-600">Temukan ribuan koleksi buku dari berbagai kategori</p>
        </div>
        
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('books.search') }}" method="GET" class="relative">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Masukkan judul buku, pengarang, atau kata kunci..." 
                    class="w-full pl-12 pr-32 py-4 text-lg border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-lg"
                    value="{{ request('search') }}"
                >
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button 
                    type="submit" 
                    class="absolute inset-y-0 right-0 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2 rounded-full mr-2 my-2 transition-colors"
                >
                    Cari
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Kategori Populer</h2>
            <p class="text-xl text-gray-600">Jelajahi berbagai kategori buku pilihan kami</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @forelse($categories as $category)
                <a href="{{ route('books.index', ['category' => $category->id]) }}" 
                   class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 text-center hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                        {{ $category->category }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $category->books_count ?? 0 }} buku
                    </p>
                </a>
            @empty
                <!-- Default categories jika data kosong -->
                <div class="bg-white rounded-2xl p-6 shadow-lg text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Teknologi</h3>
                    <p class="text-sm text-gray-500 mt-1">150+ buku</p>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('categories.index') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                Lihat Semua Kategori
                <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Latest Books -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Buku Terbaru</h2>
            <p class="text-xl text-gray-600">Koleksi buku terbaru yang baru saja ditambahkan</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($latest_books as $book)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow group">
                    <div class="aspect-w-3 aspect-h-4 bg-gray-200">
                        @if($book->url_cover)
                            <img src="{{ $book->url_cover }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="mb-2">
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">
                                {{ $book->category->category ?? 'Umum' }}
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            {{ $book->title }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">{{ $book->author }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ $book->year_published ?? 'N/A' }}</span>
                            <a href="{{ route('books.show', $book->id) }}" 
                               class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Placeholder jika tidak ada buku -->
                <div class="col-span-full text-center py-12">
                    <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Buku Terbaru</h3>
                    <p class="text-gray-600">Koleksi buku terbaru akan segera ditambahkan.</p>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('books.index') }}" 
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-full transition-colors">
                Lihat Semua Buku
                <svg class="h-4 w-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

@auth
<!-- User Dashboard -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Dashboard Saya</h2>
                <p class="text-xl text-gray-600">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Current Borrows -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-blue-900">Sedang Dipinjam</h3>
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-blue-900 mb-2">
                        {{ $user_stats['current_borrows'] ?? 0 }}
                    </div>
                    <p class="text-blue-700 text-sm">Buku yang sedang Anda pinjam</p>
                    <a href="{{ route('borrows.index') }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm mt-4">
                        Lihat Detail
                        <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <!-- Total Borrows -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-green-900">Total Peminjaman</h3>
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-green-900 mb-2">
                        {{ $user_stats['total_borrows'] ?? 0 }}
                    </div>
                    <p class="text-green-700 text-sm">Semua peminjaman Anda</p>
                </div>

                <!-- Overdue Books -->
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-red-900">Terlambat</h3>
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-red-900 mb-2">
                        {{ $user_stats['overdue_books'] ?? 0 }}
                    </div>
                    <p class="text-red-700 text-sm">Buku yang terlambat dikembalikan</p>
                    @if(($user_stats['overdue_books'] ?? 0) > 0)
                        <a href="{{ route('borrows.index', ['status' => 'overdue']) }}" 
                           class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold text-sm mt-4">
                            Segera Kembalikan
                            <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endauth

<!-- Features Section -->
<section class="py-16 bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">Mengapa Memilih Kami?</h2>
            <p class="text-xl text-gray-300">Fitur-fitur unggulan yang membuat pengalaman membaca Anda lebih baik</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Gratis Selamanya</h3>
                <p class="text-gray-300">Akses semua koleksi buku tanpa biaya berlangganan</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Akses Mobile</h3>
                <p class="text-gray-300">Baca di mana saja dengan dukungan perangkat mobile</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Pencarian Canggih</h3>
                <p class="text-gray-300">Temukan buku dengan mudah menggunakan filter pencarian</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Koleksi Favorit</h3>
                <p class="text-gray-300">Simpan dan kelola daftar buku favorit Anda</p>
            </div>
        </div>
    </div>
</section>
@endsection