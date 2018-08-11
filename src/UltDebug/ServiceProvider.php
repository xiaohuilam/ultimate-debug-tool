<?php

namespace Xiaohuilam\UltDebug;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * 启动.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(realpath(__DIR__.'/../../views/debug_tool/'), 'views/debug_tool');
        $this->publishes([
            realpath(__DIR__.'/../../views/debug_tool/') => resource_path('views/debug_tool/'),
        ]);
    }
}
