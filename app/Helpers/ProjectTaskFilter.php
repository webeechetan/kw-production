<?php
namespace App\Helpers;

class ProjectTaskFilter{

    public static function byUser($query, $user_id){
        if($user_id != 'all'){
            return $query->whereHas('users', function($q) use ($user_id){
                $q->where('user_id', $user_id);
            });
        }
        return $query;
    }

    public static function filterTasks($query, $byUser, $sort, $startDate, $dueDate, $status){
        $query = self::byUser($query, $byUser);

        if($startDate){
            $query->whereDate('due_date', '>=', $startDate);
        }

        if($dueDate){
            $query->whereDate('due_date', '<=', $dueDate);
        }

        if($status != 'all'){
            if($status == 'overdue'){
                $query->whereDate('due_date', '<', now())
                ->where('status', '!=', 'completed');;
            }else{
                $query->where('status', $status);
            }
            return $query;
        }

        switch ($sort) {
            case 'a_z':
                return $query->orderBy('name');
            case 'z_a':
                return $query->orderByDesc('name');
            case 'newest':
                return $query->latest();
            case 'oldest':
                return $query->oldest();
            default:
                return $query;
        }
    }
}
?>