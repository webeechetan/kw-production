<?php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Team;
use App\Models\Project;

class TeamFilter
{
   public function __construct(private ?string $value, private $for = null)
   {
    
   }

   public function __invoke(Builder $query, $next)
   {
        if(!$this->value || $this->value === 'all') {
            return $next($query);
        }

        if($this->for === 'PROJECT'){
            $team = Team::find($this->value);
            if($team){
                $projects = $team->projects->pluck('id');
                $query->whereIn('id', $projects);
                return $next($query);
            }
        }

        // this is for filtering tasks for teams in tasks -> teams

        if($this->for === 'TASK-TEAMS'){
            $team = Team::find($this->value);
            if($team){
                $query->whereHas('users', function($q) use ($team){
                    $q->where('users.main_team_id', $team->id);
                });
                return $next($query);
            }
        }

        return $next($query);


   }
}

?>