<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Membuat tabel pivot book_category
        Schema::create('book_category', function (Blueprint $table) {
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('category_id');

            // Foreign key constraints
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            // Primary key untuk mencegah duplikat
            $table->primary(['book_id', 'category_id']);
        });

        // Menghapus kolom category_id yang tidak lagi diperlukan dari tabel books
        // Pastikan Anda sudah mem-backup data jika perlu!
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['category_id']); // Hapus foreign key constraint dulu
            $table->dropColumn('category_id');   // Hapus kolomnya
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: Membuat kembali kolom category_id di tabel books
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->after('books_file_id'); // Sesuaikan posisi jika perlu
            $table->foreign('category_id')->references('id')->on('categories');
        });
        
        // Rollback: Menghapus tabel pivot book_category
        Schema::dropIfExists('book_category_pivot');
    }
};
