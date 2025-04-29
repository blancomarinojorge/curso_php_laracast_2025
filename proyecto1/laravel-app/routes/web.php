<?php

use App\Models\Employee;
use App\Models\Job;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $posts = Post::with('tags')->get();
    $posts->each(function ($post){
        dump($post->name);
        $post->tags->each(fn($tag) => dump($tag->name));
    });
});

Route::get('/jobs', function (){
    $jobs = Job::all();

    return view('jobs',compact('jobs'));
});

Route::get('jobs/{id}',function ($id){
    $job = Job::findById($id);

    return view('job',compact('job'));
});
