<?php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter
{
   public function __construct(private ?string $value, private $for = null)
   {
    
   }

   public function __invoke($query, $next)
   {
        if(!$this->value || $this->value === 'all') {
            return $next($query);
        }

        // this is for filtering users in a project 
        if($this->for === 'PROJECT'){
            $query->whereHas('users', function($q) {
                $q->where('user_id', $this->value);
            });
            return $next($query);
        }

        // this is for filtering users in a team
        if($this->for === 'TEAM'){
            $query->whereHas('users', function($q) {
                $q->where('users.id', $this->value);
            });
            return $next($query);
        }

        // this is for filtering users in a project tasks
        if($this->for === 'PROJECT-TASKS'){
            if($this->value != 'all'){
                $query->whereHas('users', function($q) {
                    $q->where('user_id', $this->value);
                });
                return $next($query);
            }
        }

        // this is for filtering users in a team tasks

        if($this->for === 'TEAM-TASKS'){
            if($this->value != 'all'){
                $query->whereHas('users', function($q) {
                    $q->where('user_id', $this->value);
                });
                return $next($query);
            }
        }

        // this is for filtering tasks for users in tasks -> teams

        if($this->for === 'TASK-TEAMS'){
            if($this->value != 'all'){
                $query->whereHas('users', function($q) {
                    $q->where('user_id', $this->value);
                });
                return $next($query);
            }
        }

        return $next($query);



   }
}

?>