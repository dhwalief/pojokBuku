@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Tambah Kategori Baru
                </h1>
                <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">
                    Buat kategori baru untuk mengelompokkan buku.
                </p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>

        <!-- Category Creation Form -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg">
            {{--
                Form ini akan mengirim data ke 'admin.categories.store'.
                Pastikan Anda sudah membuat route dan controller method yang sesuai.
                Route: Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
            --}}
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="p-6 md:p-8"
                    x-data="{ 
                         categoryName: '{{ old('category', '') }}', 
                         generateSlug(text) {
                             return text.toString().toLowerCase()
                                 .replace(/\s+/g, '-')           // Ganti spasi dengan -
                                 .replace(/[^\w\-]+/g, '')       // Hapus karakter non-word
                                 .replace(/\-\-+/g, '-')         // Ganti -- dengan satu -
                                 .replace(/^-+/, '')             // Hapus - dari awal
                                 .replace(/-+$/, '');            // Hapus - dari akhir
                         }
                     }">

                    <!-- Nama Kategori -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text"
                            name="category"
                            id="category"
                            x-model="categoryName"
                            required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Contoh: Fiksi Ilmiah">
                        @error('category')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pratinjau Slug -->
                    <div class="mt-4" x-show="categoryName.length > 0">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pratinjau URL (Slug)</label>
                        <div class="mt-1 flex items-center p-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-700/50">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">{{ url('/categories/') }}/</span>
                            <span class="text-gray-900 dark:text-white font-mono text-sm" x-text="generateSlug(categoryName)"></span>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Slug ini akan dibuat secara otomatis saat kategori disimpan.</p>
                    </div>

                </div>
                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex justify-end items-center space-x-4">
                    <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Batal</a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection