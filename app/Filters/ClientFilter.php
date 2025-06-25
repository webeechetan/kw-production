<?php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ClientFilter
{
   public function __construct(private ?string $value, private $for = null)
   {
    
   }

   public function __invoke(Builder $query, $next)
   {
        if(!$this->value || $this->value === 'all') {
            return $next($query);
        }

        $query->where('client_id', $this->value);
        return $next($query);
   }
}

?>