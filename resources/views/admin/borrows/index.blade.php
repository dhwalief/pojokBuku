@extends('layouts.app')
<!-- admin.borrows.index -->
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Header dan Judul Halaman -->
        <header class="mb-10 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white animate-fade-in-up">
                Peminjaman
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 animate-fade-in-up animation-delay-200">
                Kelola, pantau, dan perbarui status semua transaksi peminjaman.
            </p>
        </header>

        <!-- Formulir Filter dan Pencarian yang Ditingkatkan -->
        <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl mb-12 transform hover:-translate-y-1 transition-transform duration-300">
            <form action="{{ route('borrows.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-10 gap-6 items-end">
                    <!-- Kolom Pencarian -->
                    <div class="md:col-span-5">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Peminjaman</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="search" name="search" id="search" placeholder="Judul buku, penulis, peminjam..." value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-shadow">
                        </div>
                    </div>

                    <!-- Kolom Filter Status -->
                    <div class="md:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-shadow">
                            <option value="">Semua Status</option>
                            <option value="{{ App\Enums\BorrowStatus::Borrowed->value }}" {{ request('status') == App\Enums\BorrowStatus::Borrowed->value ? 'selected' : '' }}>Dipinjam</option>
                            <option value="{{ App\Enums\BorrowStatus::Returned->value }}" {{ request('status') == App\Enums\BorrowStatus::Returned->value ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
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

        <!-- Tabel Data Peminjaman -->
        @if ($borrows->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Buku & Peminjam</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($borrows as $borrow)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        <div class="h-12 w-12 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-800 dark:to-blue-900 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300 font-bold text-lg shadow-lg">
                                            {{ strtoupper(substr($borrow->user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="{{ $borrow->book->title }}">
                                            {{ Str::limit($borrow->book->title, 35) }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400" title="{{ $borrow->user->name }}">
                                            {{ $borrow->user->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <span class="font-medium">Pinjam:</span> {{ \Carbon\Carbon::parse($borrow->date_borrowed)->isoFormat('D MMM YYYY') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-medium">Tempo:</span> {{ \Carbon\Carbon::parse($borrow->due_date)->isoFormat('D MMM YYYY') }}
                                    </div>
                                    @if($borrow->returned_at)
                                    <div class="text-sm text-green-600 dark:text-green-400">
                                        <span class="font-medium">Kembali:</span> {{ \Carbon\Carbon::parse($borrow->returned_at)->isoFormat('D MMM YYYY') }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $isOverdue = !$borrow->returned_at && \Carbon\Carbon::now()->gt($borrow->due_date);
                                $statusClass = '';
                                $statusText = '';

                                if ($borrow->status === App\Enums\BorrowStatus::Returned) {
                                $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border border-green-200 dark:border-green-700';
                                $statusText = 'Dikembalikan';
                                } elseif ($isOverdue) {
                                $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 border border-red-200 dark:border-red-700';
                                $statusText = 'Terlambat';
                                } else {
                                $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700';
                                $statusText = 'Dipinjam';
                                }
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }} shadow-sm">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($borrow->status === App\Enums\BorrowStatus::Borrowed)
                                <form action="{{ route('borrows.return', $borrow) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menandai buku ini sebagai telah dikembalikan?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition-all transform hover:scale-105 shadow-md">
                                        Kembalikan
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 dark:text-gray-500 italic font-medium">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <!-- Pesan jika tidak ada data yang ditemukan -->
        <div class="text-center bg-white dark:bg-gray-800 py-16 px-6 rounded-2xl shadow-xl">
            <div class="text-6xl text-gray-300 dark:text-gray-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white">Tidak Ada Data Peminjaman</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                @if(request('search') || request('status'))
                Tidak ada hasil yang cocok dengan kriteria pencarian Anda. Coba ubah kata kunci atau reset filter.
                @else
                Saat ini belum ada transaksi peminjaman yang tercatat dalam sistem.
                @endif
            </p>
            <a href="{{ route('borrows.index') }}" class="mt-8 inline-block px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition transform hover:scale-105 shadow-md">
                Reset Semua Filter
            </a>
        </div>
        @endif

        <!-- Paginasi -->
        <div class="mt-16">
            {{ $borrows->withQueryString()->links() }}
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