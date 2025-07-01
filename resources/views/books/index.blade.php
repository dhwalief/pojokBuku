@extends('layouts.app')
<!-- books.index -->
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-slate-900 dark:via-gray-900 dark:to-black">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Header dan Judul Halaman -->
        <header class="mb-10 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white animate-fade-in-up">
                Jelajahi Koleksi Buku Kami
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 animate-fade-in-up animation-delay-200">
                Temukan, filter, dan telusuri judul-judul favorit Anda.
            </p>
        </header>

        <!-- Formulir Filter dan Pencarian yang Ditingkatkan -->
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl mb-12 transform hover:-translate-y-1 transition-transform duration-300">
            <form action="{{ route('books.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-10 gap-6 items-end">
                    <!-- Kolom Pencarian -->
                    <div class="md:col-span-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Buku</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </span>
                            <input type="search" name="search" id="search" placeholder="Judul, penulis, ISBN..." value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-shadow">
                        </div>
                    </div>

                    <!-- Kolom Filter Kategori -->
                    <div class="md:col-span-2">
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                        <select name="category" id="category"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-shadow">
                            <option value="">Semua</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->category }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kolom Filter Tahun -->
                    <div class="md:col-span-2">
                        <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun Terbit</label>
                        <input type="number" name="year" id="year" placeholder="e.g., 2023" value="{{ request('year') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-shadow [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="md:col-span-2 flex items-center justify-end gap-3 mt-4 md:mt-0">
                        <a href="{{ route('books.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 transition-colors">Reset</a>
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-3 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-all transform hover:scale-105">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid untuk Koleksi Buku -->
        @if ($books->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6 sm:gap-8">
            @foreach ($books as $book)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                <a href="{{ route('books.show', $book) }}" class="block">
                    <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-700 relative">
                        <img src="{{ $book->url_cover ?: 'https://placehold.co/300x450/e2e8f0/334155?text=No+Cover' }}"
                            alt="Cover buku {{ $book->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                            loading="lazy">
                        <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-4">
                        @if ($book->category)
                        <span class="text-xs text-blue-600 dark:text-blue-400 font-bold uppercase tracking-wider">{{ $book->category->category }}</span>
                        @endif
                        <h3 class="mt-1 text-md font-bold text-gray-800 dark:text-white truncate group-hover:text-blue-600 transition-colors" title="{{ $book->title }}">
                            {{ $book->title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate" title="{{ $book->author }}">{{ $book->author }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <!-- Pesan jika tidak ada buku yang ditemukan -->
        <div class="text-center bg-white dark:bg-gray-800 py-16 px-6 rounded-2xl shadow-xl">
            <div class="text-6xl text-gray-300 dark:text-gray-500 mb-4">📚</div>
            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">Tidak Ada Buku yang Ditemukan</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Coba ubah kata kunci pencarian atau reset filter Anda untuk melihat semua koleksi.</p>
            <a href="{{ route('books.index') }}" class="mt-8 inline-block px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition transform hover:scale-105">
                Reset Semua Filter
            </a>
        </div>
        @endif

        <!-- Paginasi -->
        <div class="mt-16">
            {{ $books->withQueryString()->links() }}
        </div>

    </div>
</div>

<style>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
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
</style>
@endsection
