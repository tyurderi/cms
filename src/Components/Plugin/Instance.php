<?php

namespace CMS\Components\Plugin;

use CMS\Models\Plugin\Plugin;

class Instance
{

    const INFO_FILENAME = 'plugin.json';

    /**
     * The actually plugin instance.
     * @var Bootstrap
     */
    private $instance;

    /**
     * Hols the information from 'plugin.json'
     *
     * @var Information
     */
    private $info;

    /**
     * The absolute location of the plugin.
     *
     * @var string
     */
    private $path;

    /**
     * The associated plugin model instance.
     *
     * @var Plugin
     */
    private $model;

    public function __construct($instance, $path)
    {
        $this->instance = $instance;
        $this->path     = $path;

        $this->loadInfo();

        $this->instance->setInstance($this);
    }

    /**
     * Returns the internal name of the plugin.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getModel()->name;
    }

    /**
     * Returns the external plugin bootstrap.
     *
     * @return Bootstrap
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Return all plugin information stored in 'plugin.json'
     *
     * @return Information
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Return the associated plugin model.
     *
     * @return Plugin
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Return the absolute plugin directory.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    protected function loadInfo()
    {
        $filename = $this->path . self::INFO_FILENAME;

        if (!is_file($filename) || !is_readable($filename))
        {
            throw new \Exception('Plugin info file not found or readable!');
        }

        $this->info = new Information($filename);
    }

}