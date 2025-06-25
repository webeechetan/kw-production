<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use App\Models\Client;
use App\Models\Project;
use App\Models\Team;
use App\Models\Task;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Scopes\OrganizationScope;
use App\Models\Scopes\MainClientScope;
use App\Models\Webhook;

class Organization extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $hidden = ['password'];

    public function clients(){
        return $this->hasMany(Client::class, 'org_id');
    }

    public function projects(){
        return $this->hasMany(Project::class);
    }

    public function teams(){
        return $this->hasMany(Team::class);
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function mainClient(){
        return $this->hasOne(Client::class, 'org_id')
        ->withoutGlobalScope(MainClientScope::class)
        ->where('is_main', 1);
    }

    public function webhooks(){
        return $this->hasMany(Webhook::class, 'org_id');
    }
}
