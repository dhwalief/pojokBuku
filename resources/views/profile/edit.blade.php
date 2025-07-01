@extends('layouts.app')

@section('content')
{{-- Latar belakang gradien yang konsisten --}}
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Header Halaman -->
        <header class="mb-10 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white animate-fade-in-up">
                Pengaturan Akun
            </h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400 animate-fade-in-up animation-delay-200">
                Kelola informasi profil, kata sandi, dan pengaturan akun Anda.
            </p>
        </header>

        <div class="max-w-3xl mx-auto space-y-12">
            <!-- Kartu Informasi Profil -->
            <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl transition-shadow duration-300">
                <section>
                    <header class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Informasi Profil
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Perbarui nama dan alamat email akun Anda.
                        </p>
                    </header>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama</label>
                            <input id="name" name="name" type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-shadow" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                            <input id="email" name="email" type="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white transition-shadow" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-4 text-sm text-yellow-800 dark:text-yellow-300 bg-yellow-100 dark:bg-yellow-900/50 p-4 rounded-lg">
                                <p>
                                    Alamat email Anda belum diverifikasi.
                                    <button form="send-verification" class="underline hover:text-yellow-900 dark:hover:text-yellow-200 transition-colors">
                                        Klik di sini untuk mengirim ulang email verifikasi.
                                    </button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-green-700 dark:text-green-400">
                                    Tautan verifikasi baru telah dikirim ke alamat email Anda.
                                </p>
                                @endif
                            </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="w-auto px-6 py-3 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-all transform hover:scale-105">
                                Simpan Perubahan
                            </button>

                            @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 dark:text-green-400">
                                Tersimpan.
                            </p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>

            <!-- Kartu Perbarui Kata Sandi -->
            <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl transition-shadow duration-300">
                <section>
                    <header class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Perbarui Kata Sandi
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.
                        </p>
                    </header>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kata Sandi Saat Ini</label>
                            <input id="current_password" name="current_password" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-shadow" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kata Sandi Baru</label>
                            <input id="password" name="password" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-shadow" autocomplete="new-password">
                            @error('password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Kata Sandi</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-blue-200 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-shadow" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="w-auto px-6 py-3 text-sm font-semibold text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-all transform hover:scale-105">
                                Simpan Kata Sandi
                            </button>

                            @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 dark:text-green-400">
                                Tersimpan.
                            </p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>

            <!-- Kartu Hapus Akun -->
            <div class="bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-2xl shadow-xl transition-shadow duration-300">
                <section class="space-y-6" x-data="{ openModal: false }">
                    <header>
                        <h2 class="text-2xl font-bold text-red-600 dark:text-red-400">
                            Hapus Akun
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.
                        </p>
                    </header>

                    <button @click="openModal = true" type="button" class="w-auto px-6 py-3 text-sm font-semibold text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition-all transform hover:scale-105">
                        Hapus Akun
                    </button>

                    <!-- Modal Konfirmasi Hapus -->
                    <div x-show="openModal" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div @click.away="openModal = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 max-w-lg w-full mx-4 transform transition-all" x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    Apakah Anda yakin ingin menghapus akun Anda?
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Tindakan ini tidak dapat diurungkan. Mohon konfirmasi dengan memasukkan kata sandi Anda.
                                </p>

                                <div>
                                    <label for="password_delete" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 sr-only">Kata Sandi</label>
                                    <input id="password_delete" name="password" type="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-4 focus:ring-red-200 focus:border-red-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-shadow" placeholder="Kata Sandi Anda" required>
                                    @error('password', 'userDeletion')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end gap-4">
                                    <button type="button" @click="openModal = false" class="px-6 py-2 text-sm font-medium text-gray-700 bg-gray-100 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800">
                                        Hapus Akun
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
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

    [x-cloak] {
        display: none !important;
    }
</style>
@endsection