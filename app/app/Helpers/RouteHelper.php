<?php

namespace App\Helpers;

use Exception;
use Route;

class RouteHelper
{
    protected static function search($name)
    {
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            if ($route->getName() === $name) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Returns an empty placeholder route string
     *
     * @param string $route name of route to evaluate
     *
     * @return string
     */
    public static function routePlaceholder($name)
    {
        $route  = self::search($name);
        $params = [];

        if (empty($route)) {
            throw new Exception(sprintf('Given route not found: %s', $route));
        }

        foreach ($route->parameterNames() as $param) {
            $params[$param] = '#' . $param . '#';
        }

        return route($name, $params);
    }
}
