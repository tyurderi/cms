<?php

namespace CMS\Components\Collector;

use Favez\Mvc\App;

class Vue
{
    
    /**
     * Webpack include aliases.
     *
     * These will be used to include vue components or scripts from plugin sources.
     *
     * @var string[]
     */
    protected $alias;
    
    public function __construct()
    {
        $this->alias = [];
    }
    
    public function collect()
    {
        App::events()->publish('vue.collector.run', ['collector' => $this]);
    }
    
    public function pushAlias($alias, $relativePath, $absolutePath)
    {
        $this->alias[$alias] = [
            'relativePath' => $relativePath,
            'absolutePath' => $absolutePath
        ];
    }
    
    public function getAlias()
    {
        return $this->alias;
    }
    
}