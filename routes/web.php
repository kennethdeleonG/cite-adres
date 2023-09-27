<?php

use App\Domain\Asset\Models\Asset;
use App\Domain\Faculty\Models\Faculty;
use App\Http\Controllers\FacultyController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('register');
})->name('register.index');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/register', [FacultyController::class, 'store'])->name('register.store');

Route::get('faculty/email/verify/{faculty}/{hash}', function ($facultyId, $hash) {
    $faculty = Faculty::findOrFail($facultyId);
    $faculty->markEmailAsVerified();

    return redirect()->route('login')->withFlashSuccess(__('Email Verified, you can now login.'));
})->name('faculty.verification.verify');

Route::get('/download-singlefile', function () {
    /** @var Asset */
    $asset = Asset::where('slug', request('asset'))->firstorFail();

    if ($asset->file) {
        $url = Storage::disk('s3')->temporaryUrl($asset->file, now()->addMinutes(30));

        $filename = $asset->name . '.' . $asset->file_type;

        $redirect = request('redirect') ?? null;

        return view('web.download', compact('url', 'filename', 'redirect'));
    }

    abort(404);
})->name('download.single-file');
