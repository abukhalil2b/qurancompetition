<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
	// Route::get('register', [RegisteredUserController::class, 'create'])
	// 	->name('register');

	// Route::post('register', [RegisteredUserController::class, 'store']);

	Route::get('login', [AuthenticatedSessionController::class, 'create'])
		->name('login');

	Route::post('login', [AuthenticatedSessionController::class, 'store']);

	Route::get('/forgot-password-phone', function () {
		return view('auth.forgot-password-phone');
	})->name('password.sms');

	Route::get('create_account_form', [PasswordController::class, 'createAccountForm'])->name('create_account_form');

	Route::get('forgot_password_form', [PasswordController::class, 'forgotPasswordForm'])->name('forgot_password_form');

	Route::post('request_otp', [PasswordController::class, 'requestOtp'])->name('request_otp');
	// GET route to display the OTP verification form
	Route::get('verify_otp_form', [PasswordController::class, 'verifyOtpForm'])->name('verify_otp_form');
	// POST route to handle the form submission
	Route::post('verify_phone_otp', [PasswordController::class, 'verifyOtp'])->name('verify_phone_otp');

	Route::post('send_temp_password', [PasswordController::class, 'sendTempPassword'])->name('send_temp_password');
});

Route::middleware('auth')->group(function () {

	Route::put('password', [PasswordController::class, 'update'])->name('password.update');

	Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
		->name('logout');
});

Route::get('check_national_id', [RegisteredUserController::class, 'checkNationalId'])
	->middleware('throttle:10,1') // 10 requests per minute
	->name('check_national_id');
