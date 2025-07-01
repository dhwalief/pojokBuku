@extends('layouts.app')

@section('content')

@php
/**
* Fungsi helper untuk mendapatkan ikon SVG berdasarkan nama kategori.
* Ini disalin dari categories.index untuk konsistensi.
*/
function getCategoryIcon($categoryName) {
$normalizedCategory = strtolower($categoryName);
$icon = '';

// Daftar kata kunci dan ikon yang sesuai
$iconMap = [
['keys' => ['fiksi', 'novel', 'cerita'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
</svg>'],
['keys' => ['sains', 'teknologi', 'komputer', 'pemrograman'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
</svg>'],
['keys' => ['sejarah', 'sosial'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>'],
['keys' => ['biografi', 'memoar'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
</svg>'],
['keys' => ['anak', 'dongeng'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
</svg>'],
['keys' => ['bisnis', 'ekonomi', 'keuangan'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414-.336.75-.75.75h-.75m0-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>'],
['keys' => ['agama'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.658-.463 1.243-1.119 1.243H4.51c-.655 0-1.188-.585-1.119-1.243l1.263-12A1.125 1.125 0 015.625 9h12.75c.621 0 1.125.504 1.125 1.125z" />
</svg>'],
];

foreach ($iconMap as $map) {
foreach ($map['keys'] as $key) {
if (str_contains($normalizedCategory, $key)) {
return $map['icon'];
}
}
}

// Ikon default jika tidak ada yang cocok
return '<svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
</svg>';
}
@endphp

{{-- Menambahkan kelas dark mode untuk background utama --}}
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-slate-900 dark:via-gray-900 dark:to-black">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-fade-in-up">
                    📚 PojokBuku
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90 animate-fade-in-up animation-delay-200">
                    Jelajahi dan baca ribuan koleksi buku digital terbaik
                </p>
                <div class="max-w-2xl mx-auto animate-fade-in-up animation-delay-400">
                    <form action="{{ route('books.search') }}" method="GET" class="relative flex items-center">
                        {{-- Menggunakan kelas dark mode untuk input pencarian --}}
                        <label for="search" class="sr-only">Cari Buku</label>
                        <input
                            type="text"
                            name="search"
                            placeholder="Cari judul buku, penulis, atau ISBN..."
                            class="w-full px-6 py-4 text-gray-900 rounded-full border-0 shadow-lg focus:ring-4 focus:ring-blue-300 text-lg dark:bg-gray-800 dark:text-gray-200 dark:placeholder-gray-400 dark:focus:ring-blue-500/50"
                            value="{{ request('search') }}">
                        <button
                            type="submit"
                            class="absolute right-2 top-2 bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors duration-200">
                            🔍 Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>
        {{-- gradasi di bawah hero --}}
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-transparent to-transparent dark:from-slate-900"></div>
    </div>

    <!-- Stats Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            {{-- Menambahkan kelas dark mode untuk kartu statistik --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-transform duration-200">
                <div class="text-3xl text-blue-600 mb-4">📖</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ number_format($totalBooks ?? 0) }}</div>
                <div class="text-gray-600 dark:text-gray-400">Total Buku</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-transform duration-200">
                <div class="text-3xl text-green-600 mb-4">👥</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ number_format($totalUsers ?? 0) }}</div>
                <div class="text-gray-600 dark:text-gray-400">Pengguna Aktif</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-transform duration-200">
                <div class="text-3xl text-orange-600 mb-4">📋</div>
                <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ number_format($totalBorrows ?? 0) }}</div>
                <div class="text-gray-600 dark:text-gray-400">Total Peminjaman</div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-8 text-center">Kategori Populer</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse($categories as $category)
            <a href="{{ route('books.index', ['category' => $category->id]) }}"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center hover:shadow-lg transform hover:scale-105 transition-all duration-200 group">
                <div class="text-blue-600 dark:text-blue-400 mb-3 group-hover:text-blue-500 dark:group-hover:text-blue-300 group-hover:scale-110 transition-all duration-300">
                    {{-- Menggunakan fungsi helper untuk menampilkan ikon SVG --}}
                    {!! getCategoryIcon($category->category) !!}
                </div>
                <div class="font-semibold text-gray-900 group-hover:text-blue-600 dark:text-gray-200 dark:group-hover:text-blue-400 transition-colors">
                    {{ $category->category }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $category->books_count ?? 0 }} buku
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-8">
                <div class="text-gray-400 dark:text-gray-500 text-lg">Belum ada kategori tersedia</div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Featured Books Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Buku Terbaru</h2>
            <a href="{{ route('books.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-semibold flex items-center gap-2">
                Lihat Semua
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($latestBooks as $book)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transform hover:scale-105 transition-all duration-300 group">
                <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                    @if($book->url_cover)
                    <img src="{{ $book->url_cover }}"
                        alt="{{ $book->title }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                        loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-6xl text-gray-400 dark:text-gray-600">
                        📖
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                </div>

                <div class="p-6">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-2 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                        {{ $book->title }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $book->author }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        {{ $book->category->category ?? 'Uncategorized' }} • {{ $book->year_published }}
                    </p>

                    <div class="flex gap-2">
                        <a href="{{ route('books.show', $book->id) }}"
                            class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-semibold">
                            Detail
                        </a>
                        @auth
                        <form action="{{ route('borrows.store') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="submit"
                                class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors duration-200 font-semibold">
                                Pinjam
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}"
                            class="flex-1 bg-gray-600 dark:bg-slate-600 text-white text-center py-2 px-4 rounded-lg hover:bg-gray-700 dark:hover:bg-slate-500 transition-colors duration-200 font-semibold">
                            Login
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-6xl text-gray-300 dark:text-gray-700 mb-4">📚</div>
                <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300 mb-2">Belum Ada Buku</h3>
                <p class="text-gray-500 dark:text-gray-400">Koleksi buku akan segera ditambahkan</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- CTA Section (Already dark, no changes needed) -->
    @guest
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h2 class="text-3xl font-bold mb-4">Bergabunglah dengan Komunitas Pembaca</h2>
            <p class="text-xl mb-8 opacity-90">Daftar sekarang dan nikmati akses ke ribuan buku digital</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                    class="bg-white text-blue-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors duration-200 transform hover:scale-105">
                    Daftar Gratis
                </a>
                <a href="{{ route('login') }}"
                    class="border-2 border-white text-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-blue-600 transition-all duration-200 transform hover:scale-105">
                    Masuk
                </a>
            </div>
        </div>
    </div>
    @endguest
</div>

<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.6s ease-out forwards;
    }

    .animation-delay-200 {
        animation-delay: 0.2s;
    }

    .animation-delay-400 {
        animation-delay: 0.4s;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection