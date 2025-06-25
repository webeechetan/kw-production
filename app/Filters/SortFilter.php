<?php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class SortFilter
{
   public function __construct(private ?string $value, private $for = null)
   {
    
   }

   public function __invoke($query, $next)
   {
        if(!$this->value || $this->value === 'all') {
            return $next($query);
        }

        switch ($this->value) {
            case 'a_z':
                $query->orderBy('name','asc');
                break;
            case 'z_a':
                $query->orderBy('name','desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
        }

        return $next($query);


   }
}

?>