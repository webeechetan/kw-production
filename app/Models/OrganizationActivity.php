<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrganizationActivityTask;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrganizationActivity extends Model
{
    use HasFactory, SoftDeletes;

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

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function tasks(){
        return $this->hasMany(OrganizationActivityTask::class);
    }

    public function users(){
        $tasks = OrganizationActivityTask::where('organization_activity_id',$this->id)->get();
        $task_users = $tasks->pluck('users')->toArray();
        $user_ids = [];
        foreach($task_users as $task_user){
            foreach($task_user as $user){
                $user_ids[] = $user['id'];
            }
        }

        $user_ids = array_unique($user_ids);

        return User::whereIn('id',$user_ids)->get();


    }


}
