<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_listing';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'name' => "string"
        ];
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'idEmployee');
    }
}
