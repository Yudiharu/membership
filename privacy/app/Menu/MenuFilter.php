<?php

namespace App\Menu;

use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class MenuFilter implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
        if (isset($item['permission']) && ! auth()->user()->can($item['permission'])){
            return false;
        }

        return $item;
    }
}