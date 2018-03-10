<?php

namespace YuuApp\MyApp;
// namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Laratrust;

class MyMenuFilter implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
    	if (!empty($item['module_code'])) {
    		if (!checkAccess($item['module_code'])) {
          		return false;
    		}
    	}

    	if (isset($item['hidden']) && $item['hidden']) {
    		return false;
    	}

        return $item;
    }

}