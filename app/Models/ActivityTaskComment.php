<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityTaskComment extends Model
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
}
