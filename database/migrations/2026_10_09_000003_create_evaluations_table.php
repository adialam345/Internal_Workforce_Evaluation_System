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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('evaluator_id');
            $table->enum('status', ['draft', 'submitted'])->default('draft')->index();
            $table->decimal('total_score', 5, 2)->nullable();
            $table->text('strengths')->nullable(); // Kelebihan
            $table->text('areas_for_improvement')->nullable(); // Area pengembangan
            $table->text('recommendations')->nullable(); // Rekomendasi
            $table->text('evaluator_notes')->nullable(); // Catatan umum
            $table->timestamp('submitted_at')->nullable()->index();
            $table->unsignedBigInteger('unlocked_by')->nullable();
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();

            $table->unique('employee_id', 'evaluations_employee_id_unique');
            $table->index('evaluator_id', 'evaluations_evaluator_id_index');

            $table->foreign('employee_id', 'fk_evaluations_employee')->references('id')->on('employees')->cascadeOnDelete();
            $table->foreign('evaluator_id', 'fk_evaluations_evaluator')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('unlocked_by', 'fk_evaluations_unlocked_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
