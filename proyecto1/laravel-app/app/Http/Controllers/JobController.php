<?php

namespace App\Http\Controllers;

use App\Http\Requests\jobs\StoreJobRequest;
use App\Mail\JobPosted;
use App\Models\Job;
use Illuminate\Support\Facades\Mail;


class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Job::paginate(10);
        return view('jobs.index',compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request)
    {
        $job = Job::create([
            ...$request->validated(),
            "idEmployee" => 1
        ]);
        Mail::to($request->user()->email)->send(
            new JobPosted($job)
        );
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return view('jobs.show',compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        return view('jobs.edit',compact('job'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreJobRequest $request, Job $job)
    {
        $job->update($request->validated());
        return redirect('/jobs/'.$job->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return redirect('/');
    }
}
