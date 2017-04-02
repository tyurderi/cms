<?php

namespace CMS;

use Favez\Mvc\App;
use Favez\Mvc\DI\Container;

class Application
{

    private $app;

    public function __construct()
    {
        /** @var \Composer\Autoload\ClassLoader $loader */
        $loader    = require_once __DIR__ . '/../vendor/autoload.php';
        $this->app = \Favez\Mvc\App::instance(require __DIR__ . '/../config.inc.php');
        $this->app->setLoader($loader);

        $this->registerServices($this->app->di());
        $this->registerRoutes($this->app);

        $this->app->plugins()->execute();
    }

    public function run()
    {
        return $this->app->run();
    }

    protected function registerServices(Container $container)
    {
        $container->registerShared('auth',    function() { return new \CMS\Components\Auth();           });
        $container->registerShared('plugins', function() { return new \CMS\Components\Plugin\Manager(); });
    }

    protected function registerRoutes(App $app)
    {
        $app->any('/api/[{controller}[/{action}]]', 'api:{controller}:{action}');
    }

}