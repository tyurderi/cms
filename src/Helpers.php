<?php

if (!function_exists('isSuccess'))
{
    function isSuccess ($data)
    {
        return $data === true || is_array($data) && isset($data['success']) && $data['success'] === true;
    }
}

if (!function_exists('path'))
{
    function path ()
    {
        $args  = func_get_args();
        $paths = [];
        
        foreach ($args as $arg)
        {
            $paths = array_merge($paths, (array) $arg);
        }
        
        $paths = array_map(function ($p) {
            return rtrim($p, "/\\");
        }, $paths);
        
        $paths = array_filter($paths);
        
        return join(DIRECTORY_SEPARATOR, $paths);
    }
}