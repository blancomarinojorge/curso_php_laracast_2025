<?php

use App\Http\Requests\jobs\StoreJobRequest;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    $jobs = Job::latest()->paginate(10);
    return response()->json(compact('jobs'));
});

Route::post('/jobs',function (StoreJobRequest $request){
    $job = Job::create([
        ...$request->validated(),
        "idEmployee" => 1
    ]);
    return response()->json(compact('job'), 201);
});
