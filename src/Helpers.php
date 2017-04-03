<?php

if (!function_exists('isSuccess'))
{
    function isSuccess($data)
    {
        return $data === true || is_array($data) && isset($data['success']) && $data['success'] === true;
    }
}