<?php
namespace Modules;

use File;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $listModule = array_map('basename', File::directories(__DIR__));
        foreach ($listModule as $module) {
            if (is_dir(__DIR__ . '/' . $module . '/Views')) {
                $this->loadViewsFrom(__DIR__ . '/' . $module . '/Views', $module);
            }
        }
        if (is_dir(__DIR__ . '/Layout')) {
            $this->loadViewsFrom(__DIR__ . '/Layout', 'Layout');
        }
    }

    public function register()
    {
        $listModule = array_map('basename', File::directories(__DIR__));
        foreach ($listModule as $module) {
            $class = "\Modules\\".ucfirst($module)."\\ModuleProvider";
            $classRoute = "\Modules\\".ucfirst($module)."\\RouterServiceProvider";
            if(class_exists($class)) {
                $this->app->register($class);
            }
            if(class_exists($classRoute)) {
                $this->app->register($classRoute);
            }
        }
    }
}
