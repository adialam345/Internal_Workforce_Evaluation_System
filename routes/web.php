<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEvaluationController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EvaluatorController;
use App\Http\Controllers\Admin\EvaluationQuestionController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Evaluator\EvaluatorDashboardController;
use App\Http\Controllers\Evaluator\EvaluatorEmployeeController;
use App\Http\Controllers\Evaluator\EvaluationController;
use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Root Redirect
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin() 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('evaluator.dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Shared Print Evaluation Route
Route::middleware('auth')->group(function () {
    Route::get('/evaluations/{evaluation}/print', [PrintController::class, 'printEvaluation'])->name('evaluations.print');
});

// Admin Routes (Protected by auth + role:admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Employees Management
    Route::post('/employees/bulk-assign', [EmployeeController::class, 'bulkAssign'])->name('employees.bulk_assign');
    Route::resource('employees', EmployeeController::class)->except(['show']);

    // Evaluators Management
    Route::post('/evaluators/{evaluator}/toggle', [EvaluatorController::class, 'toggleStatus'])->name('evaluators.toggle');
    Route::resource('evaluators', EvaluatorController::class);

    // Evaluations Overview & Unlocking
    Route::get('/evaluations', [AdminEvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluations/{evaluation}', [AdminEvaluationController::class, 'show'])->name('evaluations.show');
    Route::post('/evaluations/{evaluation}/unlock', [AdminEvaluationController::class, 'unlock'])->name('evaluations.unlock');

    // Evaluation Questions Bank
    Route::get('/questions', [EvaluationQuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions', [EvaluationQuestionController::class, 'store'])->name('questions.store');
    Route::put('/questions/{question}', [EvaluationQuestionController::class, 'update'])->name('questions.update');
    Route::post('/questions/{question}/toggle', [EvaluationQuestionController::class, 'toggleActive'])->name('questions.toggle');
    Route::delete('/questions/{question}', [EvaluationQuestionController::class, 'destroy'])->name('questions.destroy');

    // Import & Export
    Route::get('/import', [ImportExportController::class, 'showImport'])->name('import.index');
    Route::get('/import/template', [ImportExportController::class, 'downloadTemplate'])->name('import.template');
    Route::post('/import', [ImportExportController::class, 'processImport'])->name('import.process');
    Route::get('/export', [ImportExportController::class, 'showExport'])->name('export.index');
    Route::post('/export', [ImportExportController::class, 'processExport'])->name('export.process');

    // Audit Logs
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit_logs.index');
});

// Evaluator Routes (Protected by auth + role:evaluator)
Route::middleware(['auth', 'role:evaluator'])->prefix('evaluator')->name('evaluator.')->group(function () {
    Route::get('/dashboard', [EvaluatorDashboardController::class, 'index'])->name('dashboard');

    // Assigned Employees
    Route::get('/employees', [EvaluatorEmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/{employee}', [EvaluatorEmployeeController::class, 'show'])->name('employees.show');

    // Fill & Submit Evaluation
    Route::get('/evaluations/{employee}', [EvaluationController::class, 'form'])->name('evaluations.form');
    Route::post('/evaluations/{employee}', [EvaluationController::class, 'save'])->name('evaluations.save');
    Route::get('/evaluations/{evaluation}/result', [EvaluationController::class, 'show'])->name('evaluations.show');
});
