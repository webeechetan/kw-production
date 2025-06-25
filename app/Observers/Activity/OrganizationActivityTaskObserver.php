<?php

namespace App\Observers\Activity;
use App\Models\Activity;
use App\Models\OrganizationActivity;
use App\Models\OrganizationActivityTask;
use App\Notifications\ActivityTaskStatusChangeNotification;


class OrganizationActivityTaskObserver
{
    public function updating(OrganizationActivityTask $activitytask)
    {

       

        if($activitytask->organization_activity_id == null){
            return;
        }

        if($activitytask->isDirty('status')){
            $oldStatus = $activitytask->getOriginal('status');
            $newStatus = $activitytask->status;
            $changedBy = auth()->guard(session('guard'))->user();

            foreach($activitytask->users as $user){
                $user->notify(new ActivityTaskStatusChangeNotification($activitytask , $oldStatus, $newStatus, $changedBy));
            }
        }
    }

    public function created($activitytask)
    {
       
    }
}
