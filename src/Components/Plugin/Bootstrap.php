<?php

namespace CMS\Components\Plugin;

class Bootstrap
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

}