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
        Schema::table('users', function (Blueprint $table) {
            // Kolom untuk peran pengguna. Defaultnya adalah 'user'. Pilihan: 'admin', 'user' (enum UserRole)
            $table->string('role')->default('user')->comment('Pilihan: admin, user')->after('password');

            // Kolom untuk status akun. Defaultnya adalah 'active'. Pilihan: 'active', 'suspend' (enum UserStatus)
            $table->string('status')->default('active')->comment('Pilihan status, user')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
