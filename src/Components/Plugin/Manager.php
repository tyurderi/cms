<?php

namespace CMS\Components\Plugin;

use CMS\Models\Plugin\Plugin;
use Favez\Mvc\DI\Injectable;

class Manager
{
    use Injectable;

    /**
     * Hols the instance of every plugin.
     *
     * @var array
     */
    private $instances;

    public function __construct()
    {
        $this->instances = [];
    }

    /**
     * Load all plugins from directory and save them to database.
     */
    public function load()
    {
        $iterator = new \IteratorIterator(new \DirectoryIterator($this->getPluginDirectory()));
        $plugins  = [];

        /**
         * @var integer            $key
         * @var \DirectoryIterator $file
         */
        foreach ($iterator as $key => $file)
        {
            if ($file->isDot() || $file->isFile())
            {
                continue; // Skip "." and ".." and normal files.
            }

            $name  = $file->getBasename();
            $model = Plugin::repository()->findOneBy(['name' => $name]);

            if (!($model instanceof Plugin))
            {
                $model = new Plugin();
                $model->active  = false;
                $model->name    = $name;
                $model->label   = $name;
                $model->created = date('Y-m-d H:i:s');
                $model->changed = date('Y-m-d H:i:s');
            }

            $instance = $this->loadInstance($name);
            $instance->setModel($model);

            $model->save();

            $plugins[] = $instance;
        }

        return $plugins;
    }

    /**
     * Creates and returns a plugin instance.
     *
     * @param string $name
     * @return Instance
     */
    public function loadInstance($name)
    {
        if (!isset($this->instances[$name]))
        {
            $className = $this->getClassName($name);
            $path      = $this->getPluginDirectory() . $name . '/';

            self::app()->loader()->addPsr4($name . '\\', $path);

            $this->instances[$name] = new Instance(new $className(), $path);
        }

        return $this->instances[$name];
    }

    public function getClassName($name)
    {
        return $name . '\\Bootstrap';
    }

    public function getPluginDirectory()
    {
        $directory = self::config('app.path') . self::config('plugin.path');

        if (!is_dir($directory))
        {
            throw new \Exception('Plugin directory not found!');
        }

        return $directory;
    }

}