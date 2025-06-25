<?php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class DateFilter
{
   public function __construct(private ?string $start_date = null, private ?string $end_date = null, private $for = null)
   {
    
   }

   public function __invoke($query, $next)
   {
        if(!$this->start_date && !$this->end_date) {
            return $next($query);
        }

        // this is for filtering date in a project tasks
        if($this->for === 'PROJECT-TASKS'){
            if($this->start_date && $this->end_date){
                $query->whereBetween('due_date', [$this->start_date, $this->end_date]);
                return $next($query);
            }
        }

        // this is for filtering date in a team tasks
        if($this->for === 'TEAM-TASKS'){
            if($this->start_date && $this->end_date){
                $query->whereBetween('due_date', [$this->start_date, $this->end_date]);
                return $next($query);
            }
        }

        // this is for filtering date in a task -> teams

        if($this->for === 'TASK-TEAMS'){
            if($this->start_date && $this->end_date){
                $query->whereBetween('due_date', [$this->start_date, $this->end_date]);
                return $next($query);
            }
        }

        return $next($query);
   }
}

?>