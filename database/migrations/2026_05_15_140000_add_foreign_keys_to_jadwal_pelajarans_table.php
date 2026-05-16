<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_pelajarans', function (Blueprint $table) {
            $table->foreign('guru_id')->references('id')->on('gurus')->restrictOnDelete();
            $table->foreign('mata_pelajaran_id')->references('id')->on('mata_pelajarans')->restrictOnDelete();
            $table->foreign('kelas_id')->references('id')->on('kelas')->restrictOnDelete();
            $table->foreign('jam_pelajaran_id')->references('id')->on('jam_pelajarans')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_pelajarans', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropForeign(['kelas_id']);
            $table->dropForeign(['jam_pelajaran_id']);
        });
    }
};
