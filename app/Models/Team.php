<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Client;
use App\Models\Scopes\OrganizationScope;
use App\Models\Scopes\MainClientScope;
use Illuminate\Database\Eloquent\SoftDeletes;

use PDO;

class Team extends Model
{
    use HasFactory, SoftDeletes;

    public function setNameAttribute($value){
        $this->attributes['name'] = ucwords($value);
    }

    public function users(){
        return $this->hasMany(User::class,'main_team_id'); 
    }

    public function manager(){
        return $this->belongsTo(User::class,'manager_id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new OrganizationScope);
    }

    public function getProjectsAttribute(){
        $users = $this->users->pluck('id')->toArray();
        $projects = Project::whereHas('users', function ($query) use ($users) {
            $query->whereIn('user_id', $users);
        })->get();

        return $projects;
    }

    public function getClientsAttribute(){
        $projects = $this->projects->pluck('id')->toArray(); 
        $projects = array_unique($projects);
        $clients = Project::whereIn('id', $projects)->pluck('client_id')->toArray();
        return Client::whereIn('id', $clients)->get();

    }

    public function getTasksAttribute(){
        $users = $this->users->pluck('id')->toArray();
        return Task::whereHas('users', function ($query) use ($users) {
            $query->whereIn('user_id', $users);
        })->get();

    }

    public function getTasksQueryAttribute(){
        $users = $this->users->pluck('id')->toArray();
        return Task::whereHas('users', function ($query) use ($users) {
            $query->whereIn('user_id', $users);
        });
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
    

    // public function getInitialsAttribute(){
    //     return $this->name;
    // }

}
