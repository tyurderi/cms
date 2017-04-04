<?php

namespace CMS\Components\Plugin;

use CMS\Models\Plugin\Dependency;
use CMS\Models\Plugin\Plugin;
use Exception;
use Favez\Mvc\App;
use Favez\Mvc\DI\Injectable;

class Manager
{
    use Injectable;
    
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
    
    /**
     * The plugin dependency manager.
     *
     * @var \CMS\Components\Plugin\DependencyManager
     */
    private $dependencyManager;

    public function __construct()
    {
        $this->instances         = [];
        $this->namespaces        = ['custom', 'system'];
        $this->dependencyManager = new DependencyManager();
    }

    /**
     * @return Instance[]
     */
    public function list()
    {
        $plugins   = Plugin::repository()->findAll();
        $instances = [];

        /** @var Plugin $plugin */
        foreach ($plugins as $plugin)
        {
            $instances[] = $this->loadInstance($plugin->namespace, $plugin->name, $plugin);
        }

        return $instances;
    }

    /**
     * Synchronize plugins from database with filesystem and the other way around.
     */
    public function synchronize()
    {
        // Loop through all plugins in database and disable a plugin when it does not exists in filesystem.
        $plugins = Plugin::repository()->findAll();

        /** @var Plugin $plugin */
        foreach ($plugins as $plugin)
        {
            if (!$this->exists($plugin->namespace, $plugin->name))
            {
                $plugin->active = false;
                $plugin->save();
            }
        }

        // Loop through all plugins in filesystem and write/update it in database.
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
                    $model->active    = 0;
                    $model->namespace = $namespace;
                    $model->name      = $name;
                    $model->created   = date('Y-m-d H:i:s');
                    $model->changed   = date('Y-m-d H:i:s');
                }

                $instance = $this->loadInstance($model->namespace, $model->name, $model);
                $info     = $instance->getInfo();

                $model->label       = $info->getLabel();
                $model->description = $info->getDescription();
                $model->author      = $info->getAuthor();
                $model->website     = $info->getWebsite();
                $model->email       = $info->getEmail();

                // Do not update the model version every time for future updates.
                if (empty($model->version))
                {
                    $model->version = $info->getVersion();
                }

                $model->save();

                $this->dependencyManager->update($instance);
            }
        }

        return $plugins;
    }

    /**
     * Checks whether the plugin exists on filesystem or not.
     *
     * @param string $namespace
     * @param string $name
     * @param string $className
     * @param string $path
     * @return bool
     */
    public function exists($namespace, $name, &$className = null, &$path = null)
    {
        $className = $this->getClassName($name);
        $path      = $this->getPluginDirectory($namespace) . $name . '/';

        self::app()->loader()->setPsr4($name . '\\', $path);

        return class_exists($className);
    }

    /**
     * Creates and returns a plugin instance.
     *
     * @param string $namespace
     * @param string $name
     * @param Plugin $model
     *
     * @throws \Exception
     * @return Instance
     */
    public function loadInstance($namespace, $name, $model = null)
    {
        if (!isset($this->instances[$name]))
        {
            if (!$this->exists($namespace, $name, $className, $path))
            {
                throw new Exception('Trying to access unknown plugin: ' . $name);
            }

            $bootstrap = new $className();
            $instance  = new Instance($bootstrap, $path);

            if ($model instanceof Plugin)
            {
                $instance->setModel($model);
            }

            $this->instances[$name] = $instance;
        }

        return $this->instances[$name];
    }
    
    /**
     * The preferred plugin bootstrap class name.
     *
     * @param string $name
     *
     * @return string
     */
    public function getClassName($name)
    {
        return $name . '\\Bootstrap';
    }
    
    /**
     * Returns the actually plugin directory for the given namespace.
     *
     * @param string $namespace
     *
     * @return string
     * @throws \Exception Usually when the namespace does not exists.
     */
    public function getPluginDirectory($namespace)
    {
        $directory = self::config('app.path') . self::config('plugin.path') . $namespace. '/';

        if (!is_dir($directory))
        {
            throw new \Exception('Plugin directory not found!');
        }

        return $directory;
    }
    
    /**
     * Execute all enabled plugins.
     */
    public function execute()
    {
        $plugins = Plugin::repository()->findBy(['active' => true]);

        /** @var Plugin $plugin */
        foreach ($plugins as $plugin)
        {
            if (!$this->exists($plugin->namespace, $plugin->name))
            {
                $plugin->active = 0;
                $plugin->save();
                continue;
            }
            
            $instance = $this->loadInstance($plugin->namespace, $plugin->name, $plugin);
            $instance->getBootstrap()->execute();
        }
    }
    
    /**
     * Install a plugin by its name.
     *
     * @param string $name
     *
     * @return array|bool
     */
    public function install($name)
    {
        try
        {
            $model    = $this->getModel($name);
            $instance = $this->loadInstance($model->namespace, $model->name, $model);

            if ((int) $model->active === 1)
            {
                throw new Exception('Plugin already installed!');
            }

            // Check if the required plugins within the required version are installed. Throws an exception if not.
            $this->dependencyManager->checkRequirements($instance);

            self::events()->publish('core.plugin.pre_install', ['instance' => $instance]);

            $result = $instance->getBootstrap()->install();
    
            self::events()->publish('core.plugin.post_install', ['instance' => $instance]);

            if (isSuccess($result))
            {
                $model->active = 1;
                $model->save();
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
    
    /**
     * Uninstall a plugin by its name.
     *
     * @param string $name
     *
     * @return array|bool
     */
    public function uninstall($name)
    {
        try
        {
            $model    = $this->getModel($name);
            $instance = $this->loadInstance($model->namespace, $model->name, $model);

            if ((int) $model->active === 0)
            {
                throw new Exception('Plugin already uninstalled.');
            }

            if ($dependencies = $this->dependencyManager->getDependencies($name))
            {
                throw new Exception('Unable to uninstall because of the following depended plugins: ' . implode(', ', $dependencies));
            }
    
            self::events()->publish('core.plugin.pre_uninstall', ['instance' => $instance]);
            
            $result = $instance->getBootstrap()->uninstall();
    
            self::events()->publish('core.plugin.post_uninstall', ['instance' => $instance]);

            if (isSuccess($result))
            {
                $model->active = 0;
                $model->save();
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

    /**
     * Returns the plugin model identified by its unique name.
     *
     * @param string $name
     * @return \Favez\ORM\EntityInterface|Plugin
     */
    public function getModel($name)
    {
        return Plugin::repository()->findOneBy(['name' => $name]);
    }

    /**
     * Returns the plugin bootstrap by name.
     *
     * @param string $name
     *
     * @return Bootstrap
     */
    public function get($name)
    {
        if (!isset($this->instances[$name]))
        {
            $model = $this->getModel($name);
            
            $this->loadInstance($model->namespace, $model->name, $model);
        }

        return $this->instances[$name]->getBootstrap();
    }

}