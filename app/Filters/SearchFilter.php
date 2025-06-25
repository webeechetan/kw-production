<?php
namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class SearchFilter
{
   public function __construct(private ?string $value, private $for = null)
   {
    
   }

   public function __invoke($query, $next)
   {
        if(!$this->value) {
            return $next($query);
        }

        // this is for seraching in clients
        if($this->for === 'CLIENT'){
            $query->where('name', 'like', '%'.$this->value.'%')
            ->orWhere('brand_name', 'like', '%'.$this->value.'%');
            return $next($query);
        }


        if($this->for === 'PROJECT'){
            $query->where('name', 'like', '%'.$this->value.'%')
            ->orWhereHas('client', function ($query) {
                $query->where('name', 'like', '%'.$this->value.'%')
                    ->orWhere('brand_name', 'like', '%'.$this->value.'%');
            });
        }else{
            $query->where('name', 'like', '%'.$this->value.'%');
        }

        return $next($query); 

   }
}

?>