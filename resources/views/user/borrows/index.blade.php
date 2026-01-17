@extends('layouts.app')

@section('content')
{{-- Latar belakang gradien yang konsisten --}}
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-slate-900 dark:via-gray-900 dark:to-black">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Header Halaman -->
        <header class="mb-10 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white animate-fade-in-up">
                Riwayat Peminjaman Saya
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 animate-fade-in-up animation-delay-200">
                Lihat dan kelola semua buku yang sedang Anda pinjam atau sudah dikembalikan.
            </p>
        </header>

        <!-- Filter dan Pencarian -->
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl mb-12 transform hover:-translate-y-1 transition-transform duration-300">
            <form action="{{ route('borrows.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-10 gap-6 items-end">
                    <!-- Kolom Pencarian -->
                    <div class="md:col-span-5">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Buku</label>
                        <input type="search" name="search" id="search" placeholder="Cari berdasarkan judul atau penulis..." value="{{ request('search') }}"
                            class="w-full pl-4 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-shadow">
                    </div>

                    <!-- Kolom Filter Status -->
                    <div class="md:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-shadow">
                            <option value="">Semua Status</option>
                            <option value="{{ App\Enums\BorrowStatus::Borrowed->value }}" {{ request('status') == App\Enums\BorrowStatus::Borrowed->value ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="{{ App\Enums\BorrowStatus::Returned->value }}" {{ request('status') == App\Enums\BorrowStatus::Returned->value ? 'selected' : '' }}>Sudah Dikembalikan</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="md:col-span-2 flex items-center justify-end gap-3 mt-4 md:mt-0">
                        <a href="{{ route('borrows.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 transition-colors">Reset</a>
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-3 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-all transform hover:scale-105">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid Daftar Peminjaman -->
        @if ($borrows->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach ($borrows as $borrow)
            @php
            $isReturned = $borrow->status === App\Enums\BorrowStatus::Returned;
            $dueDate = \Carbon\Carbon::parse($borrow->due_date);
            $borrowDate = \Carbon\Carbon::parse($borrow->date_borrowed);
            $isOverdue = !$isReturned && now()->gt($dueDate);

            // Kalkulasi untuk progress bar
            $totalDuration = $dueDate->diffInDays($borrowDate);
            $daysPassed = now()->diffInDays($borrowDate);
            $progressPercentage = $totalDuration > 0 ? min(100, ($daysPassed / $totalDuration) * 100) : 0;
            if($isReturned) $progressPercentage = 100;
            if($isOverdue) $progressPercentage = 100;
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden flex flex-col sm:flex-row transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                <!-- Book Cover -->
                <div class="flex-shrink-0 sm:w-1/3 md:w-1/4">
                    <a href="{{ route('books.show', $borrow->book) }}">
                        <img src="{{ $borrow->book->url_cover ?: 'https://placehold.co/300x450/e2e8f0/334155?text=No+Cover' }}"
                            alt="Cover buku {{ $borrow->book->title }}"
                            class="w-full h-64 sm:h-full object-cover">
                    </a>
                </div>

                <!-- Borrow Details -->
                <div class="p-6 flex flex-col flex-grow">
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 dark:text-white mb-1 line-clamp-2">
                            <a href="{{ route('books.show', $borrow->book) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $borrow->book->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $borrow->book->author }}</p>
                    </div>

                    <!-- Status Badge -->
                    <div class="mb-4">
                        @if ($isReturned)
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Dikembalikan
                        </span>
                        @elseif ($isOverdue)
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Terlambat
                        </span>
                        @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Dipinjam
                        </span>
                        @endif
                    </div>

                    <!-- Dates Info -->
                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2 mb-4">
                        <p><strong>Tgl. Pinjam:</strong> {{ $borrowDate->isoFormat('D MMMM YYYY') }}</p>
                        <p><strong>Batas Waktu:</strong> {{ $dueDate->isoFormat('D MMMM YYYY') }}</p>
                        @if($isReturned)
                        <p class="text-green-600 dark:text-green-400"><strong>Tgl. Kembali:</strong> {{ \Carbon\Carbon::parse($borrow->returned_at)->isoFormat('D MMMM YYYY') }}</p>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    @if(!$isReturned)
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                        <div @class([ 'h-2.5 rounded-full' , 'bg-red-500'=> $isOverdue,
                            'bg-blue-600' => !$isOverdue,
                            ]) style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        @if($isOverdue)
                        Terlambat {{ now()->diffInDays($dueDate) }} hari.
                        @else
                        Sisa waktu {{ max(0, $dueDate->diffInDays(now())) }} hari.
                        @endif
                    </p>
                    @endif

                    <!-- Action Button -->
                    <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                        @if (!$isReturned)
                        <form action="{{ route('borrows.return', $borrow) }}" method="POST" onsubmit="return confirm('Anda yakin ingin mengembalikan buku ini?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full text-center px-4 py-2 text-sm font-semibold text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition-all transform hover:scale-105">
                                Kembalikan Buku
                            </button>
                        </form>
                        @else
                        <p class="text-sm text-center text-gray-500 dark:text-gray-400 italic">Peminjaman telah selesai.</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Pesan jika tidak ada data -->
        <div class="text-center bg-white dark:bg-gray-800 py-16 px-6 rounded-2xl shadow-xl">
            <div class="text-6xl text-gray-300 dark:text-gray-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">Anda Belum Meminjam Buku</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                @if(request('search') || request('status'))
                Tidak ada buku pinjaman yang cocok dengan kriteria Anda.
                @else
                Jelajahi koleksi kami dan mulailah membaca hari ini.
                @endif
            </p>
            <a href="{{ route('books.index') }}" class="mt-8 inline-block px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition transform hover:scale-105">
                Jelajahi Koleksi Buku
            </a>
        </div>
        @endif

        <!-- Paginasi -->
        <div class="mt-16">
            {{ $borrows->withQueryString()->links() }}
        </div>
    </div>
</div>

{{-- CSS untuk animasi --}}
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

    .line-clamp-2 {
        display: -webkit-box;
        -line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection