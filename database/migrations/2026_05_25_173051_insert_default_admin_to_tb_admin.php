<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Gunakan updateOrInsert agar tidak error/duplikat jika username 'admin' sudah ada
        DB::table('tb_admin')->updateOrInsert(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin123'),
                'nama_admin' => 'Pemilik Wisma AAM',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tb_admin')->where('username', 'admin')->delete();
    }
};
