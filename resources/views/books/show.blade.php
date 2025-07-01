@extends('layouts.app')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-4 h-4 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('books.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Koleksi Buku</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400 truncate">{{ $book->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Kolom Kiri: Sampul Buku -->
                <div class="md:col-span-1">
                    <img src="{{ $book->url_cover ?: 'https://placehold.co/400x600/e2e8f0/334155?text=No+Cover' }}"
                        alt="Sampul buku {{ $book->title }}"
                        class="w-full h-auto object-cover rounded-xl shadow-lg aspect-[3/4] transform hover:scale-105 transition-transform duration-300">
                </div>

                <!-- Kolom Kanan: Detail Buku dan Aksi -->
                <div class="md:col-span-2 flex flex-col">
                    <div>
                        <div class="flex flex-wrap gap-2">
                            @forelse($book->categories as $category)
                            <a href="{{ route('books.index', ['category' => $category->id]) }}" class="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full dark:bg-blue-900/50 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-900/80 transition">
                                {{ $category->category }}
                            </a>
                            @empty
                            <span class="inline-block bg-gray-100 text-gray-800 text-sm font-semibold px-3 py-1 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                Tanpa Kategori
                            </span>
                            @endforelse
                        </div>

                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mt-4">
                            {{ $book->title }}
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400 mt-2">
                            Oleh: <span class="font-semibold">{{ $book->author }}</span>
                        </p>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    <!-- Tombol Aksi (Pinjam / Baca / Kembalikan) -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        @guest
                        <a href="{{ route('login') }}" class="w-full text-center bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                            Masuk untuk Meminjam
                        </a>
                        @endguest

                        @auth
                        {{-- ====================================================== --}}
                        {{-- PERUBAHAN LOGIKA DI SINI --}}
                        {{-- ====================================================== --}}
                        @if($activeBorrow)
                        {{-- Jika user sedang meminjam buku ini --}}
                        <a href="{{ route('books.pdf.viewer', ['book' => $book->id]) }}" class="w-full sm:w-auto flex-1 text-center bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-md flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Baca Sekarang
                        </a>

                        {{-- Form untuk mengembalikan buku --}}
                        <form action="{{ route('borrows.return', $activeBorrow) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full text-center bg-gray-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors duration-200 shadow-md">
                                Kembalikan Buku
                            </button>
                        </form>
                        @else
                        {{-- Jika user belum meminjam buku ini --}}
                        <form action="{{ route('borrows.store') }}" method="POST" class="w-full sm:w-auto flex-1">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="submit" class="w-full text-center bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                                Pinjam Buku Ini
                            </button>
                        </form>
                        @endif
                        {{-- ====================================================== --}}
                        {{-- AKHIR PERUBAHAN --}}
                        {{-- ====================================================== --}}
                        @endauth
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    <!-- Detail Tambahan -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">Detail Buku</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="text-gray-600 dark:text-gray-400">Penerbit</div>
                            <div class="font-medium text-gray-800 dark:text-gray-200">{{ $book->publisher ?: 'N/A' }}</div>

                            <div class="text-gray-600 dark:text-gray-400">Tahun Terbit</div>
                            <div class="font-medium text-gray-800 dark:text-gray-200">{{ $book->year_published ?: 'N/A' }}</div>

                            <div class="text-gray-600 dark:text-gray-400">ISBN</div>
                            <div class="font-medium text-gray-800 dark:text-gray-200">{{ $book->isbn ?: 'N/A' }}</div>

                            <div class="text-gray-600 dark:text-gray-400">Bahasa</div>
                            <div class="font-medium text-gray-800 dark:text-gray-200">{{ $book->language ?: 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Sinopsis -->
                    @if($book->description)
                    <div class="mt-8 flex-grow flex flex-col">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-2">Sinopsis</h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm">
                            {{ $book->description }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- SEKSI BUKU TERKAIT -->
        @if($relatedBooks->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                Buku Lainnya dalam Kategori Ini
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 sm:gap-8">
                @foreach($relatedBooks as $relatedBook)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                    <a href="{{ route('books.show', $relatedBook) }}" class="block">
                        <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-700 relative">
                            <img src="{{ $relatedBook->url_cover ?: 'https://placehold.co/300x450/e2e8f0/334155?text=No+Cover' }}"
                                alt="Cover buku {{ $relatedBook->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                loading="lazy">
                            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-md font-bold text-gray-800 dark:text-white truncate group-hover:text-blue-600 transition-colors" title="{{ $relatedBook->title }}">
                                {{ $relatedBook->title }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 truncate" title="{{ $relatedBook->author }}">{{ $relatedBook->author }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection