@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Tambah Buku Baru
                </h1>
                <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">
                    Isi detail buku di bawah ini.
                </p>
            </div>
            <a href="{{ route('admin.books.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg">
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Buku <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penulis <span class="text-red-500">*</span></label>
                            <input type="text" name="author" id="author" value="{{ old('author') }}" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="category_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori (bisa pilih lebih dari satu) <span class="text-red-500">*</span></label>

                            <select id="category_ids" name="category_ids[]" required multiple class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                {{-- <option value="" disabled>Pilih Kategori</option> --}}
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (is_array(old('category_ids')) && in_array($category->id, old('category_ids'))) ? 'selected' : '' }}>
                                    {{ $category->category }}
                                </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tahan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu.</p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                            <textarea id="description" name="description" rows="8" class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="space-y-6" x-data="{ coverUrl: '{{ old('url_cover', '') }}', fileName: '' }">
                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ISBN</label>
                            <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="publisher" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penerbit</label>
                            <input type="text" name="publisher" id="publisher" value="{{ old('publisher') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bahasa</label>
                            <input type="text" name="language" id="language" value="{{ old('language') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="year_published" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Terbit</label>
                            <input type="number" name="year_published" id="year_published" value="{{ old('year_published') }}" min="1000" max="{{ date('Y') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="url_cover" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL Gambar Sampul</label>
                            <input type="url" name="url_cover" id="url_cover" x-model="coverUrl" class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="https://example.com/cover.jpg">
                            <template x-if="coverUrl">
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Pratinjau Sampul:</p>
                                    <img :src="coverUrl" alt="Pratinjau Sampul" class="h-48 w-32 rounded object-cover shadow-lg">
                                </div>
                            </template>
                        </div>

                        <div>
                            <label for="book_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">File Buku (PDF) <span class="text-red-500">*</span></label>
                            <input type="file" name="book_file" id="book_file" required class="hidden" @change="fileName = $event.target.files[0].name">
                            <label for="book_file" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <p class="pl-1">Klik untuk mengunggah atau seret dan lepas</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">PDF hingga 10MB</p>
                                </div>
                            </label>
                            <p x-show="fileName" class="mt-2 text-sm text-gray-500 dark:text-gray-400">File terpilih: <span x-text="fileName" class="font-semibold"></span></p>
                        </div>
                    </div>

                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700 flex justify-end items-center space-x-4">
                    <a href="{{ route('admin.books.index') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Batal</a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Simpan Buku
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- Contoh menggunakan library seperti Tom Select atau Select2 akan membuat UI lebih ramah pengguna --}} -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet"> --}}
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script> --}}
<script>
    new TomSelect('#category_ids', {
        plugins: ['remove_button'],
        create: false,
    });
</script>
@endpush

@endsection