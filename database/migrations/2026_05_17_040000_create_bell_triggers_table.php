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
        Schema::create('bell_triggers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaturan_bel_id')->constrained('pengaturan_bels')->cascadeOnDelete();
            $table->string('nama');
            $table->string('tipe_bel');
            $table->string('audio_path')->nullable();
            $table->timestamp('triggered_at');
            $table->string('status')->default('pending');
            $table->timestamp('played_at')->nullable();
            $table->string('played_by_browser')->nullable();
            $table->string('failure_reason')->nullable();
            $table->timestamps();

            $table->unique(['pengaturan_bel_id', 'triggered_at']);
            $table->index(['status', 'triggered_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bell_triggers');
    }
};
