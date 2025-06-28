{{-- resources/views/borrows/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Peminjaman Saya</h1>
                <p class="text-gray-600">Kelola dan pantau status peminjaman buku Anda</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-600">Total Peminjaman</p>
                    <p class="text-2xl font-bold text-blue-900">{{ $borrows->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-600">Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-yellow-900">{{ $borrows->where('status', 'Dipinjam')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-600">Selesai</p>
                    <p class="text-2xl font-bold text-green-900">{{ $borrows->where('status', 'Selesai')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-600">Terlambat</p>
                    <p class="text-2xl font-bold text-red-900">
                        {{ $borrows->where('status', 'Dipinjam')->where('date_returned', '<', now())->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('borrows.index') }}" class="flex flex-col sm:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1">
                <label for="search" class="sr-only">Cari buku</label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        id="search"
                        placeholder="Cari berdasarkan judul buku atau penulis..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ request('search') }}"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="sm:w-48">
                <label for="status" class="sr-only">Filter Status</label>
                <select 
                    name="status" 
                    id="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Semua Status</option>
                    <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            >
                Filter
            </button>

            <!-- Reset Button -->
            <a 
                href="{{ route('borrows.index') }}" 
                class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center"
            >
                Reset
            </a>
        </form>
    </div>

    <!-- Borrows List -->
    @if($borrows->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Desktop Table View -->
            <div class="hidden md:block">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Buku
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Pinjam
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Kembali
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($borrows as $borrow)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-12">
                                            @if($borrow->book->url_cover)
                                                <img class="h-16 w-12 rounded object-cover" src="{{ $borrow->book->url_cover }}" alt="{{ $borrow->book->title }}">
                                            @else
                                                <div class="h-16 w-12 bg-gray-200 rounded flex items-center justify-center">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $borrow->book->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $borrow->book->author }}</div>
                                            <div class="text-xs text-gray-400">{{ $borrow->book->category->category }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $borrow->date_borrowed->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $borrow->date_borrowed->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $borrow->date_returned->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $borrow->date_returned->format('H:i') }}</div>
                                    @if($borrow->status == 'Dipinjam' && $borrow->date_returned < now())
                                        <div class="text-xs text-red-600 font-medium">Terlambat {{ $borrow->date_returned->diffForHumans() }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($borrow->status == 'Dipinjam')
                                        @if($borrow->date_returned < now())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                Dipinjam
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('books.show', $borrow->book) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors">
                                            Lihat Buku
                                        </a>
                                        @if($borrow->status == 'Dipinjam')
                                            <span class="text-gray-300">|</span>
                                            <form method="POST" action="{{ route('borrows.return', $borrow) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 transition-colors"
                                                        onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($borrows as $borrow)
                        <div class="p-4">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    @if($borrow->book->url_cover)
                                        <img class="h-20 w-15 rounded object-cover" src="{{ $borrow->book->url_cover }}" alt="{{ $borrow->book->title }}">
                                    @else
                                        <div class="h-20 w-15 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $borrow->book->title }}</h3>
                                            <p class="text-sm text-gray-500">{{ $borrow->book->author }}</p>
                                            <p class="text-xs text-gray-400">{{ $borrow->book->category->category }}</p>
                                        </div>
                                        <div class="ml-2">
                                            @if($borrow->status == 'Dipinjam')
                                                @if($borrow->date_returned < now())
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Terlambat
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Dipinjam
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Selesai
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 grid grid-cols-2 gap-4 text-xs text-gray-500">
                                        <div>
                                            <span class="font-medium">Dipinjam:</span><br>
                                            {{ $borrow->date_borrowed->format('d M Y, H:i') }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Kembali:</span><br>
                                            {{ $borrow->date_returned->format('d M Y, H:i') }}
                                            @if($borrow->status == 'Dipinjam' && $borrow->date_returned < now())
                                                <br><span class="text-red-600 font-medium">{{ $borrow->date_returned->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3 flex space-x-3">
                                        <a href="{{ route('books.show', $borrow->book) }}" 
                                        class="text-blue-600 hover:text-blue-900 text-sm font-medium transition-colors">
                                            Lihat Buku
                                        </a>
                                        @if($borrow->status == 'Dipinjam')
                                            <form method="POST" action="{{ route('borrows.return', $borrow) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 text-sm font-medium transition-colors"
                                                        onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($borrows instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-6">
                {{ $borrows->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Peminjaman</h3>
            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                Anda belum memiliki riwayat peminjaman buku. Mulai jelajahi koleksi buku kami dan pinjam buku favorit Anda.
            </p>
            <div class="mt-6">
                <a href="{{ route('books.index') }}" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Jelajahi Koleksi Buku
                </a>
            </div>
        </div>
    @endif
</div>
@endsection