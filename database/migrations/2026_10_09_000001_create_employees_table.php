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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code', 50)->unique()->index(); // NIK
            $table->string('nama')->index();
            $table->string('jabatan')->nullable();
            $table->string('divisi')->nullable()->index();
            $table->string('department')->nullable()->index();
            $table->string('unit')->nullable();
            $table->unsignedBigInteger('evaluator_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();

            $table->index('evaluator_id', 'employees_evaluator_id_index');
            $table->foreign('evaluator_id', 'fk_employees_evaluator')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
