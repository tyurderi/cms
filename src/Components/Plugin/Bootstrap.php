<?php

namespace CMS\Components\Plugin;

use Favez\Mvc\App;

abstract class Bootstrap
{

    /**
     * @var Instance
     */
    protected $instance;

    final public function __construct()
    {

    }

    public function setInstance($instance)
    {
        $this->instance = $instance;
    }

    public function getPath()
    {
        return $this->instance->getPath();
    }

    public function getInfo()
    {
        return $this->instance->getInfo();
    }

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    abstract public function execute();

    protected function registerController($moduleName, $controllerName, $registerRoutes = true)
    {
        $controllerClass = 'CMS\\Controllers\\' . $moduleName . '\\' . $controllerName . 'Controller';
        $eventName       = 'controller.resolve.' . strtolower($moduleName) . '.' . $controllerName;
        $controllerFile  = $this->getPath() . 'Controllers/' . $moduleName . '/' . $controllerName . 'Controller.php';

        $this->subscribeEvent($eventName, function() use($controllerFile, $controllerClass) {
            if (!class_exists($controllerClass))
            {
                require_once $controllerFile;
            }
        });

        if ($registerRoutes)
        {
            $pattern = '/' . strtolower($moduleName) . '/' . strtolower($controllerName) . '[/{action}]';
            $target  = strtolower($moduleName) . ':' . $controllerName . ':{action}';

            App::instance()->any($pattern, $target);
        }
    }

    protected function subscribeEvent($eventName, $handler)
    {
        if (!is_callable($handler))
        {
            if (is_string($handler) && method_exists($this, $handler))
            {
                $handler = [$this, $handler];
            }
            else
            {
                throw new \Exception('Invalid event handler.');
            }
        }

        App::events()->subscribe($eventName, $handler);
    }

}