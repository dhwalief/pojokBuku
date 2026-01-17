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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('books_file_id');
            $table->foreign('books_file_id')->references('id')->on('books_file')->onDelete('cascade');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('title')->comment('Title of the book');
            $table->string('author')->nullable()->comment('Author of the book');
            $table->text('description')->nullable()->comment('Description of the book');
            $table->string('isbn', 20)->unique()->nullable()->comment('ISBN number of the book');
            $table->string('publisher')->nullable()->comment('Publisher of the book');
            $table->string('language')->nullable()->comment('Language of the book');
            $table->year('year_published')->nullable()->comment('Publication date of the book');
            $table->string('url_cover')->nullable()->comment('URL of the book cover image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
