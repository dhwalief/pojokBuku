@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Detail Pengguna
                </h1>
                <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">
                    Informasi lengkap dan riwayat aktivitas pengguna.
                </p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                Kembali ke Daftar
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: User Profile & Stats -->
            <div class="lg:col-span-1 space-y-8">
                <!-- User Profile Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <div class="flex flex-col items-center text-center">
                        <div class="flex-shrink-0 h-24 w-24 mb-4">
                            <div class="h-24 w-24 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                <span class="text-4xl font-semibold text-blue-700 dark:text-blue-300">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        <div class="mt-4">
                            @if($user->status == \App\Enums\UserStatus::Active)
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-300">Aktif</span>
                            @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/60 dark:text-red-300">Suspend</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 space-y-3">
                        {{-- Update Status Form --}}
                        <form action="{{ route('admin.users.updateStatus', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $user->status == \App\Enums\UserStatus::Active ? 'suspend' : 'active' }}">
                            <button type="submit" class="w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $user->status == \App\Enums\UserStatus::Active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-yellow-500">
                                {{ $user->status == \App\Enums\UserStatus::Active ? 'Suspend Pengguna' : 'Aktifkan Pengguna' }}
                            </button>
                        </form>
                        {{-- Delete User Form --}}
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus pengguna akan menghilangkan data mereka secara permanen (kecuali riwayat peminjaman). Apakah Anda yakin?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-center px-4 py-2 border border-red-300 dark:border-red-600 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-red-500">
                                Hapus Pengguna
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Stats Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik Peminjaman</h3>
                    <dl class="space-y-4">
                        <div class="flex justify-between items-baseline">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Peminjaman</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->borrows->count() }}</dd>
                        </div>
                        <div class="flex justify-between items-baseline">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sedang Dipinjam</dt>
                            <dd class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">{{ $user->borrows->where('status', 'Dipinjam')->count() }}</dd>
                        </div>
                        <div class="flex justify-between items-baseline">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telah Selesai</dt>
                            <dd class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $user->borrows->where('status', '!=', 'Dipinjam')->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Right Column: Borrowing History -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Peminjaman</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Buku</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Pinjam</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Kembali</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($user->borrows as $borrow)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-14 w-10">
                                                <img class="h-14 w-10 rounded-md object-cover" src="{{ $borrow->book->url_cover ?? 'https://placehold.co/40x56/e2e8f0/e2e8f0?text=.' }}" alt="Cover">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $borrow->book->title ?? 'Buku tidak ditemukan' }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $borrow->book->author ?? 'Penulis tidak diketahui' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($borrow->date_borrowed)->isoFormat('D MMMM YYYY') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $borrow->date_returned ? \Carbon\Carbon::parse($borrow->date_returned)->isoFormat('D MMMM YYYY') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($borrow->status == 'Dipinjam')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/60 dark:text-yellow-300">Dipinjam</span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-300">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-16">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak Ada Riwayat Peminjaman</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pengguna ini belum pernah meminjam buku.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection