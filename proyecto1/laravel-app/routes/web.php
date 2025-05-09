<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\UserSessionController;
use App\Mail\JobPosted;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Mail;

Route::view('/','home');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create')->middleware('auth');
Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit')
    ->middleware('auth')
    ->can('edit','job');
Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');





Route::get('/register',[UserRegistrationController::class,'create']);
Route::post('/register',[UserRegistrationController::class,'store']);

Route::get('/login',[UserSessionController::class,'create'])->name('login');
Route::post('/login',[UserSessionController::class,'store']);
Route::delete('/login',[UserSessionController::class,'destroy']);

