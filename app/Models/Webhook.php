<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organization;
use App\Models\Task;
use Illuminate\Support\Facades\Http;



class Webhook extends Model
{
    use HasFactory;

    public function organization(){
        return $this->belongsTo(Organization::class);
    }

    public function send($task){
        $task = Task::with('assignedBy','users','notifiers','project')->find($task->id);
        // dd($task->users);
        // post task on $this->url
        // $formData['task_name'] = $task->name;
        // $formData['task_assigned_by'] = $task->assignedBy->name;
        // $formData['task_project'] = $task->project->name;
        // $formData['task_url'] = env('APP_URL').'/'. session('org_name').'/task/view/'.$task->id;
        $formData = [
            'notification_text' => $task->assignedBy->name.' created a task '.$task->name,
            'task_url' => env('APP_URL').'/'. session('org_name').'/task/view/'.$task->id,
            'task_project' => $task->project->name,
        ];


        $response = Http::asForm()->post($this->url, $formData);
        
    }
}
