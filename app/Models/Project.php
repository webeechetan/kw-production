<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\User;
use App\Models\Scopes\OrganizationScope;
use App\Models\Task;
use App\Models\Activity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Project\ProjectObserver;
use App\Models\Scopes\MainClientScope;
use Illuminate\Support\Facades\Auth;

#[ObservedBy(ProjectObserver::class)]
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function setNameAttribute($value){
        $this->attributes['name'] = ucwords($value);
    }

    public function getInitialsAttribute(){
        $words = explode(' ', $this->name);
        
        if (empty($this->name)) {
            return '';
        }
        
        if (count($words) == 1) {
            return substr($this->name, 0, 2);
        }
        
        $firstInitial = !empty($words[0]) ? $words[0][0] : '';
        $secondInitial = !empty($words[1]) ? $words[1][0] : '';
        
        return $firstInitial . $secondInitial;
    }


    public function client(){
        return $this->belongsTo(Client::class)->withoutGlobalScope(MainClientScope::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }


    public function getMembersAttribute(){
        return $this->users;
    }

    public function getTeamsAttribute(){
        $teams = $this->users->pluck('main_team_id')->toArray();
        return Team::whereIn('id',$teams)->get();
    }

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function activities(){
        return $this->morphMany(Activity::class, 'activityable');
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrganizationScope);
    }

    public function scopeUserProjects($query,$role){
        // admin can see all projects manager can see all projects of there team members and user can see only assigned projects
        if($role == 'Admin'){
            return $query;
        }elseif($role == 'Manager'){
           if(Auth::user()->myTeam){
                $team = Auth::user()->myTeam;
                $projects = $team->projects;
                return $query->whereIn('id',$projects->pluck('id'));
           }else{
                $users_projects_ids = User::find(Auth::id())->projects->pluck('projects.id')->toArray();
                return $query->whereIn('id',$users_projects_ids);
           }
        }else{
            $users_projects_ids = User::find(Auth::id())->projects->pluck('id')->toArray();
            return $query->whereIn('id',$users_projects_ids);
        }
    }
}
