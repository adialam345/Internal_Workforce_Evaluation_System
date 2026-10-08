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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action', 100)->index(); // LOGIN, LOGOUT, CREATE_EMPLOYEE, UPDATE_EMPLOYEE, IMPORT_EMPLOYEE, ASSIGN_EMPLOYEE, SUBMIT_EVALUATION, UNLOCK_EVALUATION, EXPORT_DATA
            $table->text('description');
            $table->string('target_type', 100)->nullable();
            $table->unsignedBigInteger('target_id')->nullable()->index();
            $table->json('details')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('user_id', 'audit_logs_user_id_index');
            $table->foreign('user_id', 'fk_audit_logs_user')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
