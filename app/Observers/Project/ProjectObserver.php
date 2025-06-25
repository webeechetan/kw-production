<?php

namespace App\Observers\Project;
use App\Models\Project;
use App\Models\Activity;

class ProjectObserver
{
    public function created($project)
    {
        $activity = new Activity();
        $activity->org_id = $project->org_id;
        $activity_text = auth()->guard(session('guard'))->user()->name.' created a project '.$project->name;
        $activity->text = $activity_text;
        $activity->activityable_id = $project->id;
        $activity->activityable_type = 'App\Models\Project';
        $activity->created_by = auth()->guard(session('guard'))->user()->id;
        $activity->save();
    }

    public function updated($project)
    {
        $activity = new Activity();
        $activity->org_id = $project->org_id;
        $activity_text = 'Updated ';

        // check if description is updated if yes then don't create activity 
        if($project->isDirty('description')){
            return false;
        }

        // check if image is updated if yes then don't create activity
        if($project->isDirty('image')){
            return false;
        }
    
        if($project->isDirty('client_id')){
            return false;
        }

        $original = $project->getOriginal();
        $updated = $project->getAttributes();

        foreach($updated as $key => $value){
            if($key == 'updated_at' || $key == 'created_at'){
                continue;
            }
            if($original[$key] != $value){
                $keysValue = ['start_date' => 'Start Date', 'end_date' => 'End Date'];

                if(array_key_exists($key, $keysValue)){
                    $activity_text .= $keysValue[$key].' from <b>'.$original[$key].'</b> to <b>'.$value .'</b> </br>';
                }else{
                    $activity_text .= $key.' to <b>'.$value .'</b> </br>';
                }
            }
        }

        $activity->text = $activity_text;
        $activity->activityable_id = $project->id;
        $activity->activityable_type = 'App\Models\Project';
        $activity->created_by = auth()->guard(session('guard'))->user()->id;
        $activity->save();
    }

}
