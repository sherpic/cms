<?php

namespace Soda\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

abstract class AbstractSodaServiceProvider extends ServiceProvider {

    /**
     * Register dependies conditionally (e.g. dev dependencies)
     *
     * @param array $services
     */
    protected function registerDependencies(array $services) {
        foreach ($services as $service) {
            $this->app->register($service);
        }
    }


    /**
     * @param array $facades
     */
    protected function registerFacades(array $facades) {
        foreach ($facades as $facade => $class) {
            AliasLoader::getInstance()->alias($facade, $class);
        }
    }

    /**
     * Merge the given configuration with the existing configuration, recursively.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigRecursivelyFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge_recursive(require $path, $config));
    }
}
