<?php

class LocalValetDriver extends LaravelValetDriver
{

    public function serves($sitePath, $siteName, $uri)
    {
        return true;
    }

    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        $filename = $sitePath . $uri;

        if (is_file($filename))
        {
            return $filename;
        }

        return $sitePath . '/index.php';
    }

    public function isStaticFile($sitePath, $siteName, $uri)
    {
        if (strpos($uri, '/api') === 0)
        {
            return false;
        }

        if (strpos($uri, '/backend') === 0)
        {
            if (rtrim($uri, '/') === '/backend')
            {
                $uri .= '/index.html';
            }

            return $sitePath . $uri;
        }

        if (strpos($uri, '.php') !== strlen($uri) - 4)
        {
            return $sitePath . $uri;
        }

        return false;
    }

}