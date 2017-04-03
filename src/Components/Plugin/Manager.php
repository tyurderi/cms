<?php

namespace CMS\Components\Plugin;

use CMS\Models\Plugin\Plugin;
use Favez\Mvc\App;

class Manager
{

    /**
     * Holds the instance of every plugin.
     *
     * @var Instance[]
     */
    private $instances;

    /**
     * The available plugin namespaces.
     *
     * @var string[]
     */
    private $namespaces;

    public function __construct()
    {
        $this->instances  = [];
        $this->namespaces = ['custom', 'system'];
    }

    /**
     * Load all plugins from directory and save them to database.
     */
    public function load()
    {
        $plugins = [];

        foreach ($this->namespaces as $namespace)
        {
            $iterator = new \IteratorIterator(new \DirectoryIterator($this->getPluginDirectory($namespace)));

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
                $model = $this->getModel($name);

                if (!($model instanceof Plugin))
                {
                    $model = new Plugin();
                    $model->active    = false;
                    $model->namespace = $namespace;
                    $model->name      = $name;
                    $model->label     = $name;
                    $model->created   = date('Y-m-d H:i:s');
                    $model->changed   = date('Y-m-d H:i:s');
                }

                $plugins[] = $this->loadInstance($namespace, $name, $model);

                $model->save();
            }
        }

        return $plugins;
    }

    /**
     * Creates and returns a plugin instance.
     *
     * @param string $namespace
     * @param string $name
     * @param Plugin $model
     *
     * @return Instance
     */
    public function loadInstance($namespace, $name, $model = null)
    {
        if (!isset($this->instances[$name]))
        {
            $className = $this->getClassName($name);
            $path      = $this->getPluginDirectory($namespace) . $name . '/';

            App::app()->loader()->setPsr4($name . '\\', $path);

            $instance = new Instance(new $className(), $path);

            if ($model instanceof Plugin)
            {
                $instance->setModel($model);
            }

            $this->instances[$name] = $instance;
        }

        return $this->instances[$name];
    }

    public function getClassName($name)
    {
        return $name . '\\Bootstrap';
    }

    public function getPluginDirectory($namespace)
    {
        $directory = App::app()->config('app.path') . App::app()->config('plugin.path') . $namespace. '/';

        if (!is_dir($directory))
        {
            throw new \Exception('Plugin directory not found!');
        }

        return $directory;
    }

    public function execute()
    {
        $plugins = Plugin::repository()->findBy(['active' => true]);

        /** @var Plugin $plugin */
        foreach ($plugins as $plugin)
        {
            $instance = $this->loadInstance($plugin->namespace, $plugin->name, $plugin);
            $instance->getInstance()->execute();
        }
    }

    public function install($name)
    {
        try
        {
            $model    = $this->getModel($name);
            $instance = $this->loadInstance($model->namespace, $name, $model);

            App::events()->publish('core.plugin.pre_install', ['instance' => $instance]);

            $result = $instance->getInstance()->install();

            App::events()->publish('core.plugin.post_install', ['instance' => $instance]);

            if ($result === true || is_array($result) && isset($result['success']) && $result['success'] === true)
            {
                $instance->getModel()->active = true;
                $instance->getModel()->save();
            }

            return $result;
        }
        catch (\Exception $ex)
        {
            return [
                'success' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }

    public function uninstall($name)
    {
        try
        {
            $model    = $this->getModel($name);
            $instance = $this->loadInstance($model->namespace, $name, $model);
            
            App::events()->publish('core.plugin.pre_uninstall', ['instance' => $instance]);
            
            $result = $instance->getInstance()->uninstall();

            App::events()->publish('core.plugin.post_uninstall', ['instance' => $instance]);

            if ($result === true || is_array($result) && isset($result['success']) && $result['success'] === true)
            {
                $instance->getModel()->active = false;
                $instance->getModel()->save();
            }

            return $result;
        }
        catch (\Exception $ex)
        {
            return [
                'success' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }

    public function getModel($name)
    {
        return Plugin::repository()->findOneBy(['name' => $name]);
    }

    /**
     * Returns the plugin bootstrap by name.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return Bootstrap
     */
    public function __call($name, $arguments = [])
    {
        if (!isset($this->instances[$name]))
        {
            $plugin   = $this->getModel($name);
            $instance = $this->loadInstance($plugin->namespace, $plugin->name, $plugin);

            return $instance->getInstance();
        }

        return $this->instances[$name]->getInstance();
    }

}