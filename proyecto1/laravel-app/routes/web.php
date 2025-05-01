<?php

use App\Models\Job;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $jobs = Job::latest()->paginate(10);
    return view('jobs.index',compact('jobs'));
});

Route::get('/jobs/create',function (){
    return view('jobs.create');
});

Route::get('jobs/{id}',function ($id){
    $job = Job::findById($id);

    return view('jobs.show',compact('job'));
});

Route::post('jobs',function (){
    Job::create([
        'name' => request()->get('name'),
        'salary' => request()->get('salary'),
        'idEmployee' => 1
    ]);

    return redirect('/');
});
