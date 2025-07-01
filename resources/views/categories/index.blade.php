@extends('layouts.app')

@section('content')

@php
/**
* Fungsi helper untuk mendapatkan ikon SVG berdasarkan nama kategori.
* Ini bisa diperluas dengan lebih banyak kategori atau bahkan dipindahkan ke model Category jika diperlukan.
*/
function getCategoryIcon($categoryName) {
$normalizedCategory = strtolower($categoryName);
$icon = '';

// Daftar kata kunci dan ikon yang sesuai
$iconMap = [
['keys' => ['fiksi', 'novel', 'cerita'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
</svg>'],
['keys' => ['sains', 'teknologi', 'komputer', 'pemrograman'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
</svg>'],
['keys' => ['sejarah', 'sosial'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>'],
['keys' => ['biografi', 'memoar'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
</svg>'],
['keys' => ['anak', 'dongeng'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
</svg>'],
['keys' => ['bisnis', 'ekonomi', 'keuangan'], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414-.336.75-.75.75h-.75m0-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
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
return '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
</svg>';
}
@endphp

{{-- Latar belakang gradien yang konsisten --}}
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-slate-900 dark:via-gray-900 dark:to-black">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Header Halaman -->
        <header class="mb-12 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white animate-fade-in-up">
                Jelajahi Berdasarkan Kategori
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 animate-fade-in-up animation-delay-200">
                Temukan buku berdasarkan genre atau topik yang Anda minati.
            </p>
        </header>

        <!-- Grid untuk Daftar Kategori -->
        @if ($categories->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 sm:gap-8">
            @foreach ($categories as $category)
            <a href="{{ route('books.index', ['category' => $category->id]) }}"
                class="block bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 text-center group transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 hover:bg-blue-600 dark:hover:bg-blue-700 flex flex-col items-center justify-center aspect-square">

                <!-- Stiker/Ikon Kategori -->
                <div class="mb-3 text-blue-600 dark:text-blue-400 group-hover:text-white transition-colors duration-300">
                    {!! getCategoryIcon($category->category) !!}
                </div>

                <!-- Nama Kategori -->
                <h3 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-white transition-colors duration-300">
                    {{ $category->category }}
                </h3>

                <!-- Jumlah Buku dalam Kategori -->
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-200 transition-colors duration-300">
                    {{ $category->books_count }} {{ Str::plural('Buku', $category->books_count) }}
                </p>
            </a>
            @endforeach
        </div>
        @else
        <!-- Pesan jika tidak ada kategori yang ditemukan -->
        <div class="text-center bg-white dark:bg-gray-800 py-16 px-6 rounded-2xl shadow-xl">
            <div class="text-6xl text-gray-300 dark:text-gray-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">Belum Ada Kategori</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Saat ini belum ada kategori buku yang tersedia untuk dijelajahi.</p>
            <a href="{{ route('books.index') }}" class="mt-8 inline-block px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition transform hover:scale-105">
                Lihat Semua Buku
            </a>
        </div>
        @endif

    </div>
</div>

{{-- CSS untuk animasi, sama seperti di halaman lain --}}
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