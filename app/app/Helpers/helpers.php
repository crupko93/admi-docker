<?php

use App\Helpers\ResponseHelper;

if (!function_exists('l')) {
    /**
     * Outputs given variable to log file
     *
     * @param mixed $var The variable to log
     */
    function l($var) {
        Log::info(print_r($var, true));
    }
}

if (!function_exists('debug')) {
    /**
     * Dumps formatted data to screen
     *
     * @param      $data
     * @param bool $exit
     */
    function debug($data, $exit = true)
    {
        $clone = new VarCloner();

        $dumper = 'cli' === PHP_SAPI ? new CliDumper : new Debug;
        $dumper->dump($clone->cloneVar($data));

        if ($exit) {
            die(1);
        }
    }
}

if (!function_exists('envmix')) {
    /**
     * Environment aware wrapper for the default Laravel Mix helper
     *
     * @param string $path the resource path to evaluate
     *
     * @return \Illuminate\Support\HtmlString|string
     */
    function envmix($path)
    {
        // $inProduction = config('app.env') === 'production';
        // return mix(($inProduction ? '' : '/public/') . $path, $inProduction ? '' : 'dev');

        $inLocal = config('app.env') === 'local';
        return mix(($inLocal ? '/public/' : '') . $path, $inLocal ? 'dev' : '');
    }
}

if (!function_exists('success')) {
    /**
     * [@see ResponseHelper::success()] Returns a successful response with optional data
     *
     * @param array $data The array data to be merged
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function success($data = [])
    {
        return ResponseHelper::success($data);
    }
}

if (!function_exists('error')) {
    /**
     * [@see ResponseHelper::error()] Returns an error response with optional message
     *
     * @param string $message The error message
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function error($message = '')
    {
        return ResponseHelper::error($message);
    }
}

if (!function_exists('routePlaceholder')) {
    /**
     * [@see RouteHelper::routePlaceholder()] Returns an empty placeholder route string
     *
     * @param string $route name of route to evaluate
     *
     * @return string
     */
    function routePlaceholder($route)
    {
        return RouteHelper::routePlaceholder($route);
    }
}

if (!function_exists('set_active')) {
    /**
     * Handles the active state for menu links
     *
     * @param $path
     * @param string $active
     * @return string
     */
    function set_active($path, $active = 'alternative--text')
    {
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }
}
