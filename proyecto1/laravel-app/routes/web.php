<?php

use App\Models\Job;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $jobs = Job::all();
    $job = Job::find(1);
    Job::destroy(1);
    dd($job);
    //return view('home');
});

Route::get('/jobs', function (){
    $jobs = Job::all();

    return view('jobs',compact('jobs'));
});

Route::get('jobs/{id}',function ($id){
    $job = Job::findById($id);

    return view('job',compact('job'));
});
