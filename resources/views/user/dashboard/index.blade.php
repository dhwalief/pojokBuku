@extends('layouts.app')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Saya</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Selamat datang kembali, {{ Auth::user()->name }}!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Buku Dipinjam -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Buku Dipinjam</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalBooksRead ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Buku Sedang Dipinjam -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sedang Dipinjam</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $currentlyBorrowed ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Hari Membaca -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Hari Membaca</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalReadingDays ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Buku Sedang Dipinjam -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Buku Sedang Dipinjam</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Buku yang sedang Anda pinjam saat ini</p>
                </div>
                <div class="p-6">
                    @if($currentBorrows && $currentBorrows->count() > 0)
                        <div class="space-y-4">
                            @foreach($currentBorrows as $borrow)
                            <div class="flex items-start space-x-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <a href="{{ route('books.show', $borrow->book) }}">
                                        @if($borrow->book->url_cover)
                                            <img src="{{ $borrow->book->url_cover }}" alt="{{ $borrow->book->title }}" class="w-16 h-20 object-cover rounded-lg shadow-md">
                                        @else
                                            <div class="w-16 h-20 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('books.show', $borrow->book) }}" class="hover:text-blue-600">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $borrow->book->title }}</h3>
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $borrow->book->author }}</p>
                                    
                                    {{-- ====================================================== --}}
                                    {{-- PERUBAHAN DI SINI: Menambahkan Tag Kategori --}}
                                    {{-- ====================================================== --}}
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        @foreach($borrow->book->categories as $category)
                                            <span class="inline-block bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold px-2 py-0.5 rounded-full">
                                                {{ $category->category }}
                                            </span>
                                        @endforeach
                                    </div>
                                    {{-- ====================================================== --}}
                                    
                                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                        <span>Dipinjam: {{ \Carbon\Carbon::parse($borrow->date_borrowed)->format('d M Y') }}</span>
                                        <span>•</span>
                                        <span>Batas: {{ \Carbon\Carbon::parse($borrow->date_returned)->format('d M Y') }}</span>
                                    </div>
                                    @php
                                        $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($borrow->date_returned), false);
                                    @endphp
                                    @if($daysLeft > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 mt-2">
                                            {{ $daysLeft }} hari tersisa
                                        </span>
                                    @elseif($daysLeft == 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 mt-2">
                                            Jatuh tempo hari ini
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 mt-2">
                                            Terlambat {{ abs($daysLeft) }} hari
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada buku dipinjam</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum meminjam buku apapun saat ini.</p>
                            <div class="mt-6">
                                <a href="{{ route('books.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                    Jelajahi Buku
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Riwayat Peminjaman Terbaru -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Riwayat Terbaru</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Buku yang telah Anda baca</p>
                        </div>
                        <a href="{{ route('borrows.index') }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">
                            Lihat semua
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($recentBorrows && $recentBorrows->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBorrows as $borrow)
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <a href="{{ route('books.show', $borrow->book) }}">
                                        @if($borrow->book->url_cover)
                                            <img src="{{ $borrow->book->url_cover }}" alt="{{ $borrow->book->title }}" class="w-12 h-16 object-cover rounded-lg shadow-sm">
                                        @else
                                            <div class="w-12 h-16 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('books.show', $borrow->book) }}" class="hover:text-blue-600">
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $borrow->book->title }}</h3>
                                    </a>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $borrow->book->author }}</p>
                                    
                                    {{-- ====================================================== --}}
                                    {{-- PERUBAHAN DI SINI: Menambahkan Tag Kategori --}}
                                    {{-- ====================================================== --}}
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        @foreach($borrow->book->categories as $category)
                                            <span class="inline-block bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold px-2 py-0.5 rounded-full">
                                                {{ $category->category }}
                                            </span>
                                        @endforeach
                                    </div>
                                    {{-- ====================================================== --}}
                                    
                                    <div class="mt-1 flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ \Carbon\Carbon::parse($borrow->date_borrowed)->format('d M Y') }} - {{ $borrow->date_returned ? \Carbon\Carbon::parse($borrow->date_returned)->format('d M Y') : 'Sekarang' }}</span>
                                        @if($borrow->status === 'Selesai')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                Selesai
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada riwayat</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum pernah meminjam buku.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('books.index') }}" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                        <div class="flex-shrink-0"><svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></div>
                        <div class="ml-3"><p class="text-sm font-medium text-blue-900 dark:text-blue-300">Cari Buku</p><p class="text-xs text-blue-700 dark:text-blue-400">Temukan buku favorit</p></div>
                    </a>
                    <a href="{{ route('categories.index') }}" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                        <div class="flex-shrink-0"><svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></div>
                        <div class="ml-3"><p class="text-sm font-medium text-green-900 dark:text-green-300">Kategori</p><p class="text-xs text-green-700 dark:text-green-400">Jelajahi berdasarkan genre</p></div>
                    </a>
                    <a href="{{ route('borrows.index') }}" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                        <div class="flex-shrink-0"><svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></div>
                        <div class="ml-3"><p class="text-sm font-medium text-purple-900 dark:text-purple-300">Riwayat</p><p class="text-xs text-purple-700 dark:text-purple-400">Lihat semua peminjaman</p></div>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                        <div class="flex-shrink-0"><svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>
                        <div class="ml-3"><p class="text-sm font-medium text-orange-900 dark:text-orange-300">Profil</p><p class="text-xs text-orange-700 dark:text-orange-400">Edit informasi akun</p></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
