<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Task\CommentObserver;

#[ObservedBy(CommentObserver::class)]
class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'user_id',
        'task_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
