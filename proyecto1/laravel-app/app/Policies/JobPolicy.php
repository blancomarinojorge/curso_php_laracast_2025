<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    public function edit(User $user, Job $job): bool{
        return $job->employee->user->is($user);
    }
}
