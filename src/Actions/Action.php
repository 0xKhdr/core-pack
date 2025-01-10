<?php

namespace Raid\Core\Actions;

use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Action implements Contracts\Action
{
    public static function exec(...$arguments): mixed
    {
        return app(static::class)->execute(...$arguments);
    }

    public function execute(...$arguments): mixed
    {
        try {
            return $this->handle(...$arguments);
        } catch (Throwable $throwable) {
            $this->log($throwable);
        }

        return null;
    }

    public function log(Throwable $throwable): void
    {
        Log::error(
            'Failed to execute action: '.static::class,
            [
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
            ],
        );
    }
}
