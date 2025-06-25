<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class NotificationEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $message;
  public $receiver_data;
  public $sender_data;
  public $data;

  public function __construct($receiver_data = null,$sender_data = null,$data = null)
  {
    Log::info('Sender Data: '.$sender_data->id);
    Log::info('Receiver Data: '.json_encode($receiver_data));
    Log::info('Data: '.json_encode($data));
    $this->receiver_data = $receiver_data;
    $this->sender_data = $sender_data;
    $this->data = $data;
  }

  public function broadcastOn()
  {
      return ['notification-channel-'.$this->receiver_data->id];
  }

  public function broadcastAs()
  {
      return 'notification-event-'.$this->receiver_data->id;
  }

    


}