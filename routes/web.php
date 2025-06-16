<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\{ProfileController, HodController, TeacherController, StudentController, EvaluationController, ReportController};
use App\Http\Middleware\{StrictAdminMiddleware, CheckHod, EnsureIsAdmin};
use App\Http\Controllers\Admin\ClassController;

// Authentication Routes
Auth::routes(['register' => false]);

// Redirects
Route::redirect('/', '/login');

// Dashboard redirect based on role
Route::get('/dashboard', function () {
    return redirect()->route('role.redirect');
})->name('dashboard')->middleware('auth');

Route::get('/home', function () {
    return redirect()->route('role.redirect');
})->name('home')->middleware('auth');

Route::get('/role-redirect', function () {
    $role = Auth::user()->role;
    switch ($role) {
        case 'admin': return redirect()->route('admin.dashboard');
        case 'hod': return redirect()->route('hod.dashboard');
        case 'teacher': return redirect()->route('teacher.dashboard');
        case 'student': return redirect()->route('student.dashboard');
        default: return redirect()->route('login');
    }
})->name('role.redirect')->middleware('auth');

// Profile routes (for all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (using StrictAdminMiddleware)
Route::prefix('admin')->middleware(['auth', StrictAdminMiddleware::class])->group(function() {
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/cycles', [UserController::class, 'cycles'])->name('admin.cycles');
    Route::post('/cycles', [UserController::class, 'storeCycle'])->name('admin.cycles.store');
    //Route::post('/cycles/{id}/activate', [UserController::class, 'activateCycle'])->name('admin.cycles.activate');
    Route::post('/evaluations/reset', [UserController::class, 'resetEvaluations'])->name('admin.reset-evaluations');
    
    // User management
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy'
    ]);

    Route::resource('classes', ClassController::class)->names([
    'index' => 'admin.classes.index',
    'create' => 'admin.classes.create',
    'store' => 'admin.classes.store',
    'edit' => 'admin.classes.edit',
    'update' => 'admin.classes.update',
    'destroy' => 'admin.classes.destroy',
]);

    // Reports
    //Route::get('/reports', [UserController::class, 'reports'])->name('admin.reports');
    Route::get('/reports', [ReportController::class, 'adminReports'])->name('admin.reports');
    Route::get('/reports/export', [ReportController::class, 'exportAdminReports'])->name('admin.reports.export');
    
    // Evaluations
    Route::get('/approvals', [UserController::class, 'approveEvaluations'])->name('admin.approvals');
});
// HOD routes
Route::prefix('hod')->middleware(['auth', CheckHod::class])->group(function () {
    Route::get('/dashboard', [HodController::class, 'dashboard'])->name('hod.dashboard');
    Route::get('/teachers', [HodController::class, 'teachers'])->name('hod.teachers');
    Route::get('/teachers/{teacher}', [HodController::class, 'showTeacher'])->name('hod.teachers.show');
    Route::get('/evaluation', [HodController::class, 'reviewEvaluations'])->name('hod.evaluations');
    Route::put('/evaluation/{evaluation}', [HodController::class, 'updateEvaluation'])->name('hod.evaluations.update');
    Route::get('/classes', [HodController::class, 'classes'])->name('hod.classes');
    Route::get('/report', [ReportController::class, 'hodReports'])->name('hod.report');
    Route::get('/report/export', [ReportController::class, 'exportHodReports'])->name('hod.report.export');
    Route::get('/hod/self-evaluations', [HodController::class, 'selfEvaluations'])->name('hod.self-evaluations');
    //Route::get('/hod/peer-evaluations', [HodController::class, 'peerEvaluations'])->name('hod.peer-evaluations')
    Route::post('/hod/self-evaluations/{id}/approve', [HodController::class, 'approveSelfEvaluation'])->name('hod.self-evaluations.approve');
    Route::post('/hod/self-evaluations/{id}/reject', [HodController::class, 'rejectSelfEvaluation'])->name('hod.self-evaluations.reject');

    
});



// Teacher routes
Route::prefix('teacher')->middleware(['auth', \App\Http\Middleware\CheckTeacher::class])->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('/teacher/class/{class}', [TeacherController::class, 'showClass'])->name('teacher.class');
    Route::get('/teacher/self-evaluate', [TeacherController::class, 'selfEvaluate'])->name('teacher.self-evaluate');
    Route::post('/teacher/self-evaluate', [TeacherController::class, 'storeSelfEvaluation'])->name('teacher.self-evaluate.store');
    //Route::get('/evaluation-report', [ReportController::class, 'teacherReport'])->name('teacher.evaluation-report');
    Route::get('/evaluation-report/export', [ReportController::class, 'exportTeacherReport'])->name('teacher.evaluation-report.export');
    //Route::get('/teacher/peer-evaluate', [TeacherController::class, 'peerEvaluate'])->name('teacher.peer-evaluate');
    Route::post('/teacher/peer-evaluate', [TeacherController::class, 'storePeerEvaluation'])->name('teacher.peer-evaluate.store');

});

// Student routes
Route::prefix('student')->middleware(['auth', \App\Http\Middleware\CheckStudent::class])->group(function () {
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/evaluations/{id}/form', [EvaluationController::class, 'showForm'])->name('evaluations.showForm');
    Route::post('/evaluations/store', [EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/evaluations/{evaluation}/results', [EvaluationController::class, 'showResults'])->name('evaluations.showResults');
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluations/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('evaluations.edit');
    Route::put('/evaluations/{evaluation}', [EvaluationController::class, 'update'])->name('evaluations.update');

});


// Home route
Route::get('/home', function () {
    return redirect()->route('admin.dashboard');
})->name('home')->middleware('auth');

// routes/web.php
Route::get('/debug-users', function() {
    return [
        'total_users' => User::count(),
        'sample_users' => User::take(5)->get(),
        'db_connection' => \DB::connection()->getDatabaseName()
    ];
});

// Routes for Evaluations
/*Route::prefix('evaluations')->middleware(['auth'])->group(function () {
    Route::get('/evaluations/{evaluation}/form', [EvaluationController::class, 'showForm'])->name('evaluations.showForm');
    Route::get('/create/{subject_id}', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('/store', [EvaluationController::class, 'store'])->name('evaluations.store');
});*/

Route::middleware(['auth'])->group(function () {
    Route::get('/teacher/peer-evaluate', [App\Http\Controllers\TeacherController::class, 'peerEvaluate'])->name('teacher.peer-evaluate');
    Route::post('/teacher/peer-evaluate', [App\Http\Controllers\TeacherController::class, 'storePeerEvaluation'])->name('teacher.peer-evaluate.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/teacher/evaluation-report', [App\Http\Controllers\ReportController::class, 'teacherReport'])->name('teacher.evaluation-report');
    Route::get('/evaluation-report/export', [ReportController::class, 'exportTeacherReport'])->name('teacher.evaluation-report.export');
    Route::get('/admin/evaluation-report/{teacher_id}', [App\Http\Controllers\Admin\UserController::class, 'evaluationReport'])->name('admin.evaluation-report');
    Route::get('/teacher/class/{class}', [TeacherController::class, 'viewClass'])->name('teacher.class');
});

/*Route::middleware(['auth'])->group(function () {
    Route::get('/hod/self-evaluations', [App\Http\Controllers\HodController::class, 'selfEvaluations'])->name('hod.self-evaluations');
    Route::post('/hod/self-evaluations/{id}/approve', [App\Http\Controllers\HodController::class, 'approveSelfEvaluation'])->name('hod.approve-self-evaluation');
    Route::post('/hod/self-evaluations/{id}/reject', [App\Http\Controllers\HodController::class, 'rejectSelfEvaluation'])->name('hod.reject-self-evaluation');
});*/

/*Route::middleware(['auth'])->group(function () {
    Route::get('/hod/self-evaluations', [App\Http\Controllers\HodController::class, 'selfEvaluations'])->name('hod.self-evaluations');
    Route::post('/hod/self-evaluations/{id}/approve', [App\Http\Controllers\HodController::class, 'approveSelfEvaluation'])->name('hod.approve-self-evaluation');
    Route::post('/hod/self-evaluations/{id}/reject', [App\Http\Controllers\HodController::class, 'rejectSelfEvaluation'])->name('hod.reject-self-evaluation');
    Route::get('/hod/evaluation-report/{teacher_id}', [App\Http\Controllers\HodController::class, 'evaluationReport'])->name('hod.evaluation-report');
});*/
