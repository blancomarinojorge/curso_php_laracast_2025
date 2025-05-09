<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "user_id"
    ];

    public function jobs(){
        return $this->hasMany(Job::class,'idEmployee');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
