<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledJob extends Model
{
    use HasFactory;
    protected $fillable = ['job_name', 'email_subject', 'email_body', 'execution_time', 'frequency', 'is_active'];
}
