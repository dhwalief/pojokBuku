@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Header Halaman --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Edit Kategori
                </h1>
                <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">
                    Ubah nama kategori di bawah ini.
                </p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>

        {{-- Konten Form --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg">
            {{-- Form menunjuk ke route 'update' dengan method PUT --}}
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8">
                    {{-- Input untuk Nama Kategori --}}
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        {{--
                            Menggunakan helper old() untuk menjaga input jika validasi gagal.
                            Parameter kedua ($category->category) adalah nilai default dari database.
                        --}}
                        <input
                            type="text"
                            name="category"
                            id="category"
                            value="{{ old('category', $category->category) }}"
                            required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('category') border-red-500 @enderror"
                            autofocus>

                        {{-- Menampilkan pesan error validasi --}}
                        @error('category')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Footer Form dengan Tombol Aksi --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex justify-end items-center space-x-4">
                    <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection