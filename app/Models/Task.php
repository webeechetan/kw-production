<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Organization;
use App\Models\Team;
use App\Models\Project;
use App\Models\Comment;
use App\Models\Scopes\OrganizationScope;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use App\Models\Attachment;
use App\Models\VoiceNote;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Task\TaskObserver;
use App\Models\Scopes\MainClientScope;

#[ObservedBy(TaskObserver::class)]
class Task extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'org_id',
        'assigned_by',
        'status',
        'name',
        'description',
        'due_date',
        'project_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new OrganizationScope);
    }

    public function routeNotificationForSlack($notification){
        return env('SLACK_TASK_NOTIFICATION_WEBHOOK_URL');
    }

    public function addClientId($clientId){
        $this->client_id = $clientId;
        return $this;
    } 

    public function setProjectIdAttribute($value){
        if($value == null){
            return;
        }
        $project = Project::find($value);
        $client = Client::withoutGlobalScope(MainClientScope::class)->find($project->client_id);
        
        $this->addClientId($client->id);
        $this->attributes['project_id'] = $value;
    }

    public function users(){
        return $this->belongsToMany(User::class); 
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function assignedBy(){
        return $this->belongsTo(User::class, 'assigned_by');
    } 

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function notifiers(){
        return $this->belongsToMany(User::class, 'task_user_notify');
    }

    public function attachments(){
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function voiceNotes(){
        return $this->morphMany(VoiceNote::class, 'voice_noteable');
    }

    // scopes 

    public function scopeTasksByUserType($query,$assignedByMe = false, $is_admin = false){
        if(session('guard') == 'web'){
            if($assignedByMe){
                return $query->whereHas('assignedBy',function($q){
                            $q->where('assigned_by', auth()->user()->id);
                        });
            }else{
                if(auth()->user()->hasRole('Admin') && $is_admin){
                    return $query->whereHas('users', function($q){
                        $q->where('user_id', auth()->user()->id);
                    });
                }else{
                    return $query->whereHas('users', function($q){
                        $q->where('user_id', auth()->user()->id);
                    });
                }
            }
            
        }
    }

    public function scopeTasksByAdminType($query){
        return $query->whereHas('users', function($q){
            $q->where('user_id', auth()->user()->id);
        });
    }

    // public function scopeManagerTeamsTasks($query){
    //     return $query->whereHas('team', function($q){
    //         $q->where('manager_id', auth()->user()->id);
    //     });
    // }

    public function scopeAssignedByMe($query){
        return $query->where('assigned_by', auth()->user()->id);
    }

    public function scopeMarkedToMe($query){
        return $query->whereHas('notifiers', function($q){
            $q->where('user_id', auth()->user()->id);
        });
    }


}
