<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Notifications\Notifiable;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'is_read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function markAllAsRead($user_id)
    {
        $notifications = Notification::where('user_id', $user_id)->get();
        foreach ($notifications as $notification) {
            $notification->update(['is_read' => 1]);
        }
    }

    public static function clearAll($user_id)
    {
        $notifications = Notification::where('user_id', $user_id)->get();
        foreach ($notifications as $notification) {
            $notification->delete();
        }
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
