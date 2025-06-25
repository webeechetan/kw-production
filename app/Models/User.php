<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Team;
use App\Models\UserDetail;
use App\Models\Project;
use App\Models\Notification;
use App\Models\Client;
use App\Models\Scopes\OrganizationScope;
use App\Models\Organization;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\FuncCall;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected function getDefaultGuardName(): string { return 'web'; }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        if (request()->is('api/*')) {
            static::withoutGlobalScopes();
        }else{
            static::addGlobalScope(new OrganizationScope);
        }
    }

    public function setNameAttribute($value){
        $this->attributes['name'] = ucwords($value);
    }

    public function routeNotificationForSlack($notification){
        return env('SLACK_TASK_NOTIFICATION_WEBHOOK_URL');
    }

    public function teams(){
        return $this->belongsToMany(Team::class);
    }

    public function tasks(){
        return $this->belongsToMany(Task::class);
    }

    public function notifiedTasks(){
        // task_user_notify is the pivot table
        return $this->belongsToMany(Task::class, 'task_user_notify');
    }
    

    public function clients(){
        return $this->belongsToMany(Client::class);
    }

    public function projects(){
        return $this->belongsToMany(Project::class);
        
    }

    public function organization(){
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function getProjectsAttribute(){

        return $this->projects();

        // $users_projects_ids = $this->tasks->pluck('project_id')->toArray();
        // $notified_tasks_projects_ids = $this->notifiedTasks->pluck('project_id')->toArray();
        // $users_projects_ids = array_merge($users_projects_ids, $notified_tasks_projects_ids);
        // $users_projects_ids = array_unique($users_projects_ids);
        // $projects = Project::whereIn('id', $users_projects_ids)->get();
        // return $projects;

    }

    public function details(){
        return $this->hasOne(UserDetail::class);
    }

    public function getInitialsAttribute(){
        $name = $this->name;
        $name = explode(' ', $name);
        if(count($name) == 1){
            return strtoupper(substr($name[0], 0, 2));
        }else{
            return strtoupper(substr($name[0], 0, 1).substr($name[1], 0, 1));
        }
        
    }

    public function notifications(){
        return $this->hasMany(Notification::class);
    }

    public function getIsManagerAttribute(){
        $teams = Team::where('manager_id', $this->id)->get();
        if($teams->count() > 0){
            return true;
        }
        return false;
    }

    public function myTeam(){
        return $this->hasOne(Team::class,'manager_id');
    }

    public function mainTeam(){
        return $this->belongsTo(Team::class,'main_team_id');
    }
}
