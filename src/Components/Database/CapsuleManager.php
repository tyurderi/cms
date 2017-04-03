<?php

namespace CMS\Components\Database;

use Favez\Mvc\DI\Injectable;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

class CapsuleManager
{
    use Injectable;
    
    /**
     * @var Capsule
     */
    protected $manager;
    
    public function __construct()
    {
        $manager = new Capsule();
        $manager->addConnection([
            'driver'    => 'mysql',
            'host'      => self::config('database.host'),
            'database'  => self::config('database.shem'),
            'username'  => self::config('database.user'),
            'password'  => self::config('database.pass'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci'
        ]);
    
        $manager->setEventDispatcher(new Dispatcher(new Container()));
        $manager->setAsGlobal();
        $manager->bootEloquent();
        
        $this->setManager($manager);
    }
    
    public function setManager($manager)
    {
        $this->manager = $manager;
    }
    
    public function capsule()
    {
        return $this->manager;
    }
    
    public function schema()
    {
        return $this->manager->schema();
    }
    
}