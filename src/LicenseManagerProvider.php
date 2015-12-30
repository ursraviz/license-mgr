<?php

namespace Lanser\LicenseManager;

use Illuminate\Support\ServiceProvider;

class LicenseManagerServiceProvider extends ServiceProvider
{
    /**
     * Indicate if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Get array of files in a given directory and with a given extension.
     *
     * @param  string $dir
     * @param  string $ext
     * @return array
     */
    protected function getFileList($dir, $ext) {
        return array_filter(scandir($dir), function($item) use($ext) {
            return preg_match('/\.'.$ext.'/i', $item);
        });
    }

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        // Publish our migrations
        $list  = [];
        $dir   = __DIR__.'/../database/migrations';
        $files = $this->getFileList($dir, '.php');

        foreach($files as $file){
            $list[$dir .'/'. $file] = base_path('/database/migrations/'. $file);
        }
        $this->publishes($list, 'migrations');

        // Publish our seeds
        $list  = [];
        $dir   = __DIR__.'/../database/seeds';
        $files = $this->getFileList($dir, '.php');

        foreach($files as $file){
            $list[$dir .'/'. $file] = base_path('/database/seeds/'. $file);
        }
        $this->publishes($list);

        // Publish the model factory file
        $this->publishes([
            __DIR__.'/../database/factories/LicenseManagerModelFactory.php' => base_path('/database/factories/LicenseManagerModelFactory.php'),
        ]);

        // Publish the config file
        $this->publishes([
            __DIR__.'/../config/license-mgr.php' => config_path('license-mgr.php'),
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
