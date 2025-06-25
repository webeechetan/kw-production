<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Client;

class Pin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pinnable_id', 'pinnable_type'];

    public function pinnable()
    {
        return $this->morphTo();
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'pinnable_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'pinnable_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'pinnable_id');
    }
    
}
