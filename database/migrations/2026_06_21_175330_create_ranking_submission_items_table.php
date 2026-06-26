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
        Schema::create('ranking_submission_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ranking_submission_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('ranking_candidate_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('position');
            $table->integer('points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_submission_items');
    }
};
