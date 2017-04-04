<?php

namespace CMS\Components\Database;

use Exception;
use Favez\Mvc\DI\Injectable;
use Illuminate\Container\Container;
use Illuminate\Database\Schema\Blueprint;
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
    
    /**
     * Helper method to create a table for a model.
     *
     * @param string|\Favez\Mvc\ORM\Entity $className
     *
     * @throws \Exception
     */
    public function create($className)
    {
        if (!self::models()->isValid($className))
        {
            throw new Exception('Invalid entity.');
        }
        
        if (!method_exists($className, 'createSchema'))
        {
            throw new Exception('Entity method "createSchema" does not exist.');
        }
        
        $this->schema()->create(
            $className::getSource(),
            function(Blueprint $blueprint) use($className)
            {
                return $className::createSchema($blueprint);
            }
        );
    }
    
    /**
     * Helper method to drop a table for a model.
     *
     * @param string|\Favez\Mvc\ORM\Entity $className
     *
     * @return bool
     * @throws \Exception
     */
    public function drop($className)
    {
        if (!self::models()->isValid($className))
        {
            throw new Exception('Invalid entity');
        }
        
        $this->schema()->dropIfExists($className::getSource());
        
        return true;
    }
    
}