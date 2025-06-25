<?php

namespace App\Livewire\Developers;

use Livewire\Component;
use App\Models\Webhook;
use Illuminate\Support\Str;

class Webhooks extends Component
{
    public $webhooks = [];
    public $webhookName;
    public $webhookType = 'outgoing';
    public $webhookFor = 'task';
    public $webhookEvent = 'created';
    public $webhookUrl;

    public function render()
    {
        return view('livewire.developers.webhooks');
    }

    public function mount(){
        $this->webhooks = Webhook::where('created_by', auth()->id())->get();
    }

    public function createWebhook(){
        $this->validate([
            'webhookName' => 'required',
            'webhookType' => 'required',
            'webhookFor' => 'required',
            'webhookEvent' => 'required',
            'webhookUrl' => 'required|url',
        ]);

        $webhook = new Webhook();
        $webhook->uuid = (string) Str::uuid();
        $webhook->name = $this->webhookName;
        $webhook->type = $this->webhookType;
        $webhook->for = $this->webhookFor;
        $webhook->event = $this->webhookEvent;
        $webhook->url = $this->webhookUrl;
        $webhook->created_by = auth()->id();
        $webhook->org_id = auth()->user()->org_id;
        $webhook->save();

        $this->webhooks = Webhook::where('created_by', auth()->id())->get();

        $this->resetForm();
        $this->dispatch('success', 'Webhook created successfully');
        
    }

    public function resetForm(){
        $this->webhookName = '';
        $this->webhookType = 'outgoing';
        $this->webhookFor = 'project';
        $this->webhookEvent = 'created';
        $this->webhookUrl = '';
    }

    public function deleteWebhook($id){
        $webhook = Webhook::find($id);
        $webhook->delete();
        $this->dispatch('success', 'Webhook deleted successfully');
        $this->webhooks = Webhook::where('created_by', auth()->id())->get();
    }
}
