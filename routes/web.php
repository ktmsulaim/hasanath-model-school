<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApplicantController;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

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

$settings = Cache::rememberForever('settings', function () {
    $settings_all = Setting::all();
    $settings_ = new \stdClass;
    foreach ($settings_all as $name) {
        $settings_->{$name->name} = $name->value;
    }
    return $settings_;
});

Route::get('/artisan/dev/dfdfdfdf/Gbcnxdf', function () {
    if (request()->___scret !== 'HmokBnJkLm') {
        abort(404);
    }
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    Artisan::call('optimize --force');
    return redirect('/admin/dashboard')->with('status', 'Cache Cleared');
})->middleware('auth');

$adm_date_start = $settings->starting_at ?? \Carbon\Carbon::today()->format('Y-m-d');
$adm_date_end = $settings->ending_at ?? \Carbon\Carbon::today()->format('Y-m-d');

Route::middleware("adminOrAdmissionDate:$adm_date_start,$adm_date_end")->group(function () {
    Route::get('/hallticket/{slug}/print', [ApplicantController::class, 'hallTicket'])->name('hallticket');
    Route::get('/application/{slug}/print', [ApplicantController::class, 'applicationPrint'])->name('applicationPrint');
    Route::get('/documents/{slug}/print', [ApplicantController::class, 'documents'])->name('documents');
});

Route::middleware("admissionBetween:$adm_date_start,$adm_date_end")->group(function () {
    Route::get('/admission/apply', [ApplicantController::class, 'create'])->name('apply');
    Route::post('/admission/apply', [ApplicantController::class, 'store']);

    Route::get('/applications', [ApplicantController::class, 'applications'])->name('applications');
    Route::post('/applications', [ApplicantController::class, 'search']);

    Route::get('/admission/success', [ApplicantController::class, 'success'])->name('applied');
});

Route::middleware("admissionNotBetween:$adm_date_start,$adm_date_end")->group(function () {
    Route::get('/admission', [ApplicantController::class, 'ended'])->name('admission-ended');
});

Route::get('/admission/results', [HomeController::class, 'results'])->middleware(['resultPublished'])->name('results');
Route::post('/admission/results', [HomeController::class, 'resultShow'])->middleware(['resultPublished']);

Route::get('/admin/dashboard', [ApplicantController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/admin/applicants/status', [HomeController::class, 'applicantStatus'])->middleware(['auth'])->name('status');
Route::post('/admin/applicants/status', [HomeController::class, 'updateApplicantStatus'])->middleware(['auth']);
Route::post('/admin/settings', [HomeController::class, 'settingsStore'])->middleware(['auth'])->name('settings');
Route::post('/admin/truncate', [ApplicantController::class, 'destroy'])->middleware(['auth'])->name('destroy');
Route::post('/admin/delete/{id}', [ApplicantController::class, 'delete'])->middleware(['auth'])->name('delete');
Route::get('/dashboard', function () {
    return redirect('/admin/dashboard');
});

Route::get('/exam-results-2022', [HomeController::class, 'marksheet'])->name('marksheet');
Route::post('/exam-results-2022', [HomeController::class, 'marksheetPost']);

require __DIR__ . '/auth.php';
