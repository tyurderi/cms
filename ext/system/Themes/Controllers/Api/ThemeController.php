<?php

namespace CMS\Controllers\Api;

use CMS\Components\Controller;
use Themes\Components\ThemeService;
use Themes\Models\Theme\Compile;
use Themes\Models\Theme\Theme;

class ThemeController extends Controller
{
    
    public function listAction()
    {
        $themeService = new ThemeService();
        $themeService->synchronize();
        
        return self::json()->success([
            'data' => Theme::repository()->getQuery()->fetchAll(),
        ]);
    }
    
    public function getStatisticsAction()
    {
        $themeID  = $this->request()->getParam('id');
        $compiles = Compile::findBy(['themeID' => $themeID]);
        
        // calculate time
        $totalTime = 0;
        /** @var Compile $compile */
        foreach ($compiles as $compile)
        {
            $totalTime += $compile->duration;
        }
        
        return self::json()->success([
            'compiles' => $compiles->count(),
            'duration' => [
                'total' => $totalTime,
                'avg'   => ($compiles->count() ? $totalTime / $compiles->count() : 0)
            ]
        ]);
    }
    
    public function compileAction()
    {
        set_time_limit(0);
        
        $module = $this->request()->getParam('module');
        $name   = $this->request()->getParam('name');
        
        if ($this->verifyModule($module) && $this->verifyName($module, $name))
        {
            $script = 'sh ' . path(self::path(), 'themes', $module, $name, 'compile.sh');
            $time   = microtime(true);
            $result = shell_exec($script);
            $time   = microtime(true) - $time;
            
            $this->logCompile($module, $name, $time);
            
            self::json()->assign('script', $script);
            self::json()->assign('result', $result);
            
            return self::json()->success();
        }
        
        return self::json()->failure();
    }
    
    protected function logCompile($module, $name, $time)
    {
        $theme = Theme::findOneBy(['module' => $module, 'name' => $name]);
        
        if ($theme instanceof Theme)
        {
            $compile = new Compile();
            $compile->themeID  = $theme->id;
            $compile->created  = date('Y-m-d H:i:s');
            $compile->duration = $time;
            $compile->save();
            
            return true;
        }
        
        return false;
    }
    
    private function verifyModule($module)
    {
        return !empty($module) && is_dir(path(self::path(), 'themes', $module));
    }
    
    private function verifyName($module, $name)
    {
        return !empty($name) && is_dir(path(self::path(), 'themes', $module, $name));
    }
    
}