<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use App\Models\Notification , User;

class NotificationDropDown extends Component
{
    public $notifications;
    public $unreadNotifications;

    public function render()
    {
        return view('livewire.notifications.notification-drop-down');
    }

    public function mount()
    {
        $this->notifications = Notification::where('user_id', auth()->user()->id)->latest()->get();
        $this->unreadNotifications = $this->notifications->where('is_read', 0);
    }

    public function markAllAsRead()
    {
        Notification::markAllAsRead(auth()->user()->id);
    }

    public function clearAll()
    {
        Notification::clearAll(auth()->user()->id);
        return $this->redirect(route('dashboard'),navigate:true);
    }

    public function refreshNotifications()
    {
        $this->mount();
    }
}
