<?php declare(strict_types=1);

namespace App;


/**
 * Class Routes
 *
 * @package App\Controllers
 */
class Routes
{
    /**
     * @return array
     */
    public function handler()
    {
        $routes = [];

        $routes['/']['GET'] = '\App\Controllers\PageController::index';

        $routes['/page/{id}']['GET'] = '\App\Controllers\PageController::page';

        $routes['/api']['GET'] = '\App\Controllers\PageController::api';

        return $routes;
    }
}
