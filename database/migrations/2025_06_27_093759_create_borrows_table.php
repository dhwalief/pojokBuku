<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Perubahan:
     * 1. Mengganti nama kolom `date_return` menjadi `due_date` untuk kejelasan.
     * Ini adalah kolom untuk BATAS WAKTU pengembalian.
     * 2. Menambahkan kolom `returned_at` (nullable) untuk mencatat KAPAN buku
     * benar-benar dikembalikan. Kolom ini akan kosong selama buku masih dipinjam.
     */
    public function up(): void
    {
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');

            $table->timestamp('date_borrowed')->comment('Tanggal saat buku dipinjam');
            $table->timestamp('due_date')->comment('Batas waktu pengembalian buku');
            $table->timestamp('returned_at')->nullable()->comment('Tanggal aktual buku dikembalikan');
            
            $table->string('status', 50)->default('dipinjam')->comment('Status: dipinjam, dikembalikan');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrows');
    }
};
