<?php

use App\Http\Requests\jobs\StoreJobRequest;
use App\Models\Job;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $jobs = Job::latest()->paginate(10);
    return view('jobs.index',compact('jobs'));
});

Route::get('/jobs/create',function (){
    return view('jobs.create');
});

Route::get('/jobs/{id}',function ($id){
    $job = Job::find($id);

    return view('jobs.show',compact('job'));
});

Route::get('/jobs/{job}/edit',function (Job $job){
    return view('jobs.edit',compact('job'));
});

Route::patch('/jobs/{job}',function (StoreJobRequest $request, Job $job){
    $job->update($request->validated());
    return redirect('/jobs/'.$job->id);
});

Route::delete('jobs/{job}',function (Job $job){
    $job->delete();
    return redirect('/');
});


Route::post('/jobs',function (StoreJobRequest $request){
    Job::create([
        ...$request->validated(),
        "idEmployee" => 1
    ]);
    return redirect('/');
});
