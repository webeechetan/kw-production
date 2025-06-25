<?php

namespace App\Livewire\Clients\Components;

use Livewire\Component;
use App\Models\Client;
use App\Models\Pin;

class ClientTabs extends Component
{
    public $client;
    public $currentRoute;

    public function render()
    {
        return view('livewire.clients.components.client-tabs');
    }

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->currentRoute = request()->route()->getName();

    }

    public function changeClientStatus($status){

        if($status == 'archived'){
            $this->client->delete();
            $this->redirect(route('client.index'),navigate:true);
            // $this->dispatch('success', 'Client archived successfully.');
        }else{
            $this->client->restore();
            $this->dispatch('success', 'Client status updated successfully.');
        }
        $this->redirect(route('client.profile', $this->client->id),navigate:true);
    }

    public function emitEditClient($id)
    {
        $this->dispatch('editClient', $id);
    }

    public function forceDeleteClient($id)
    {
        // delete all related data
        $projects = $this->client->projects;
        foreach ($projects as $project) {
            $tasks = $project->tasks;
            $activities = $project->activities;
            foreach ($activities as $activity) {
                $activity->delete();
            }
            foreach ($tasks as $task) {
                $attachments = $task->attachments;
                foreach ($attachments as $attachment) {
                    $attachment->delete();
                }
                $comments = $task->comments;
                foreach ($comments as $comment) {
                    $comment->delete();
                }

                $task->delete();
            }
            $pins = Pin::where('pinnable_id', $project->id)->where('pinnable_type', 'App\Models\Project')->get();
            foreach ($pins as $pin) {
                $pin->delete();
            }
            $project->delete();
        }



        $client = Client::withTrashed()->find($id);
        $client->forceDelete();
        $this->dispatch('success', 'Client deleted successfully.');
        $this->redirect(route('client.index'),navigate:true);
    }
}
