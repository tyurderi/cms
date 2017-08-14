<?php

namespace Themes\Components;

use Favez\Mvc\App;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Themes\Models\Theme\Theme;

class ThemeService
{
    
    /**
     * @var Filesystem
     */
    private $fs;
    
    public function __construct()
    {
        $this->fs = new Filesystem(new Local(path(App::path(), App::instance()->config('view.theme_path'))));
    }
    
    public function synchronize()
    {
        $result  = [];
        $modules = $this->findModules();
        
        foreach ($modules as $module)
        {
            $themes = $this->findThemes($module);
            
            foreach ($themes as $theme)
            {
                $data  = $this->loadTheme($theme);
                $theme = Theme::findOneBy([
                    'module' => $data['module'],
                    'name'   => $data['name']
                ]);
                
                if (!($theme instanceof Theme))
                {
                    $theme = new Theme();
                    $theme->module      = $data['module'];
                    $theme->name        = $data['name'];
                    $theme->label       = $data['label'];
                    $theme->description = $data['description'];
                    $theme->created     = date('Y-m-d H:i:s');
                    $theme->changed     = date('Y-m-d H:i:s');
                    $theme->save();
                }
                
                $result[] = $theme;
            }
        }
        
        return $result;
    }
    
    /**
     * Loads meta information for a theme.
     *
     * @param array $theme
     *
     * @return array
     */
    private function loadTheme($theme)
    {
        $info = $this->loadInfo($theme);
        
        if (is_array($info))
        {
            $theme = array_merge_recursive($theme, $info);
            $theme['preview'] = path(
                App::instance()->config('view.theme_path'),
                $theme['module'],
                $theme['name'],
                'preview.png'
            );
        }
        
        return $theme;
    }
    
    private function loadInfo($theme)
    {
        $filename = path($theme['module'], $theme['name'], 'info.json');
        
        if ($this->fs->has($filename))
        {
            $contents = $this->fs->read($filename);
            $contents = json_decode($contents, true);
            
            return $contents;
        }
        
        return null;
    }
    
    private function findThemes($module)
    {
        $themes = [];
        $files  = $this->fs->listContents($module);
        
        foreach ($files as $file)
        {
            if ($file['type'] === 'dir')
            {
                $themes[] = [
                    'module' => $module,
                    'name'   => $file['basename']
                ];
            }
        }
        
        return $themes;
    }
    
    private function findModules()
    {
        $modules = [];
        $files   = $this->fs->listContents();
        
        foreach ($files as $file)
        {
            if ($file['type'] === 'dir')
            {
                $modules[] = $file['basename'];
            }
        }
        
        return $modules;
    }

}