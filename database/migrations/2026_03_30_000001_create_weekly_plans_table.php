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
        Schema::create('weekly_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->index();
            $table->string('title');
            $table->text('expected_output');
            $table->enum('category', ['improvement', 'problem', 'maintenance']);
            $table->enum('category_corrected', ['improvement', 'problem', 'maintenance'])->nullable();
            $table->enum('impact_level', ['low', 'medium', 'high']);
            $table->date('week_start_date')->index();
            $table->date('week_end_date');
            $table->enum('status', ['planned', 'completed', 'completed_no_impact', 'not_completed', 'extended'])->default('planned')->index();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index(['user_id', 'week_start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_plans');
    }
};
