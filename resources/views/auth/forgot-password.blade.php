@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 dark:slate-black bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-slate-900 dark:via-gray-900 dark:to-black">
    <div class="max-w-md w-full space-y-8">
        <div>
            {{-- Menggunakan ikon kunci untuk menandakan reset password --}}
            <div class="mx-auto h-12 w-12 flex items-center justify-center bg-blue-600 rounded-full">
                <span class="text-2xl">🔑</span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Lupa Password Anda?
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Tidak masalah. Masukkan email Anda di bawah ini dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
            </p>
        </div>

        <!-- Session Status: Pesan feedback setelah email dikirim -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm dark:bg-gray-800 dark:text-gray-200 dark:placeholder-gray-400 dark:border-none dark:focus:ring-blue-500/50  @error('email') border-red-500 @enderror" 
                           placeholder="Alamat Email" value="{{ old('email') }}">
                    {{-- Menampilkan error validasi untuk email --}}
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        {{-- Ikon email/surat --}}
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </span>
                    Kirim Tautan Reset Password
                </button>
            </div>

            <div class="text-sm text-center">
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    &larr; Kembali ke halaman login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
