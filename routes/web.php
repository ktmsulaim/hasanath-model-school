<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApplicantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware("adminOrAdmissionDate")->group(function () {
    Route::get('/application/{uuid}/print', [ApplicantController::class, 'applicationPrint'])->name('applicationPrint');
});

Route::middleware("admissionBetween")->group(function () {
    Route::get('/admission/apply', [ApplicantController::class, 'create'])->name('apply');
    Route::post('/admission/apply', [ApplicantController::class, 'store']);

    Route::get('/applications', [ApplicantController::class, 'applications'])->name('applications');
    Route::post('/applications', [ApplicantController::class, 'search']);

    Route::get('/admission/success', [ApplicantController::class, 'success'])->name('applied');
});

Route::middleware("admissionNotBetween")->group(function () {
    Route::get('/admission', [ApplicantController::class, 'ended'])->name('admission-ended');
});

Route::get('/admission/results', [HomeController::class, 'results'])->middleware(['resultPublished'])->name('results');
Route::post('/admission/results', [HomeController::class, 'resultShow'])->middleware(['resultPublished']);

Route::get('/admin/dashboard', [ApplicantController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/admin/applicants/status', [HomeController::class, 'applicantStatus'])->middleware(['auth'])->name('status');
Route::post('/admin/applicants/status', [HomeController::class, 'updateApplicantStatus'])->middleware(['auth']);
Route::post('/admin/settings', [HomeController::class, 'settingsStore'])->middleware(['auth'])->name('settings');
Route::post('/admin/truncate', [ApplicantController::class, 'destroy'])->middleware(['auth'])->name('destroy');
Route::post('/admin/export', [ApplicantController::class, 'export'])->middleware(['auth'])->name('export');
Route::post('/admin/delete/{id}', [ApplicantController::class, 'delete'])->middleware(['auth'])->name('delete');
Route::get('/dashboard', function () {
    return redirect('/admin/dashboard');
});

require __DIR__ . '/auth.php';
