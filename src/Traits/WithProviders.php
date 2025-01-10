<?php

namespace Raid\Core\Traits;

trait WithProviders
{
    protected function publishConfig(): void
    {
        $configResources = glob(__DIR__.'/../../config/*.php');

        foreach ($configResources as $configPath) {
            $this->publishes([
                $configPath => config_path(basename($configPath)),
            ], 'core-pack');

            $this->mergeConfigFrom($configPath, 'core-pack');
        }
    }
}
