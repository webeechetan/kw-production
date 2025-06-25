<?php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class StatusFilter
{
   public function __construct(private ?string $value, private $for = null)
   {
    
   }

   public function __invoke($query, $next)
   {
        // dd($this->value);

        if(!$this->value || $this->value === 'all') {
            return $next($query);
        }

        // this is for filtering status in a project or client
        if($this->for === 'PROJECT' || $this->for === 'CLIENT'){
            switch ($this->value) {
                case 'active':
                    $query->where('status', 'active');
                    break;
                case 'archived':
                    $query->onlyTrashed();
                    break;
                case 'completed':
                    $query->where('status', 'completed');
                    break;
                case 'overdue':
                    $query->where('due_date', '<', Carbon::today());
                    break;
            }
            return $query;
        }

        
        // this is for filtering status in a project tasks
        if($this->for === 'PROJECT-TASKS'){
            if($this->value != 'all'){
                if($this->value == 'overdue'){
                    $query->whereDate('due_date', '<', now())
                    ->where('status', '!=', 'completed');;
                }else{
                    $query->where('status', $this->value);
                }
                return $next($query);
            }
        }

         // this is for filtering status in tasks -> teams
         if($this->for === 'TASK-TEAMS'){
            if($this->value != 'all'){
                if($this->value == 'overdue'){
                    $query->whereDate('due_date', '<', now())
                    ->where('status', '!=', 'completed');;
                }else{
                    $query->where('status', $this->value);
                }
                return $next($query);
            }
        }

        // this if for fitering teams task

        if($this->for == 'TEAM-TASKS'){
            if($this->value != 'all'){
                if($this->value == 'overdue'){
                    $query->whereDate('due_date', '<', now())
                    ->where('status', '!=', 'completed');;
                }else{
                    $query->where('status', $this->value);
                }
                return $next($query);
            }
        }

        return $next($query);


   }
}

?>