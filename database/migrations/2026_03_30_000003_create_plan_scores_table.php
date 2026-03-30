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
        Schema::create('plan_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_plan_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('base_score');
            $table->decimal('multiplier', 3, 2);
            $table->decimal('final_score', 8, 2);
            $table->timestamp('calculated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_scores');
    }
};
