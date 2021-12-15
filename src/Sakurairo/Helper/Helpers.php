<?php

use Sakurairo\Container\Container;
use Sakurairo\Http\Http;

if (!function_exists('app')) {
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('http')) {
    function http(object $client = null)
    {
        return new Http($client);
    }
}
