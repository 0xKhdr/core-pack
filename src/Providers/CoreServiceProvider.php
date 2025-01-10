<?php

declare(strict_types=1);

namespace Raid\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Raid\Core\Traits\WithProviders;

class CoreServiceProvider extends ServiceProvider
{
    use WithProviders;

    public function register(): void
    {
        $this->publishConfig();
    }

    public function boot(): void
    {
        //
    }
}
