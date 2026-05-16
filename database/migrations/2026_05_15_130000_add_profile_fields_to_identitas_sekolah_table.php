<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('identitas_sekolah', function (Blueprint $table) {
            $table->string('npsn')->nullable()->after('nama_sekolah');
            $table->string('website')->nullable()->after('email');
            $table->string('nama_kepala_sekolah')->nullable()->after('website');
        });
    }

    public function down(): void
    {
        Schema::table('identitas_sekolah', function (Blueprint $table) {
            $table->dropColumn(['npsn', 'website', 'nama_kepala_sekolah']);
        });
    }
};
