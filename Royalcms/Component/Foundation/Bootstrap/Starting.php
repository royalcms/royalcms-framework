<?php

namespace Royalcms\Component\Foundation\Bootstrap;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Illuminate\Support\Env;
use Royalcms\Component\Contracts\Foundation\Royalcms;

class Starting
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Royalcms\Component\Contracts\Foundation\Royalcms|\Royalcms\Component\Foundation\Royalcms
     *
     * \Foundation\Royalcms  $royalcms
     * @return void
     */
    public function bootstrap(Royalcms $royalcms)
    {
        /*
        |--------------------------------------------------------------------------
        | Set PHP Error Reporting Options
        |--------------------------------------------------------------------------
        |
        | Here we will set the strictest error reporting options, and also turn
        | off PHP's error reporting, since all errors will be handled by the
        | framework and we don't want any output leaking back to the user.
        |
        */
        error_reporting(-1);

        /*
        |--------------------------------------------------------------------------
        | Check Extensions
        |--------------------------------------------------------------------------
        |
        | Royalcms requires a few extensions to function. Here we will check the
        | loaded extensions to make sure they are present. If not we'll just
        | bail from here. Otherwise, Composer will crazily fall back code.
        |
        */


        /*
        |--------------------------------------------------------------------------
        | Detect The Royalcms Environment
        |--------------------------------------------------------------------------
        |
        | Royalcms takes a dead simple approach to your application environments
        | so you can just specify a machine name for the host that matches a
        | given environment, then we will automatically detect it for you.
        |
        */

        try {
            $royalcms->useEnvironmentPath(SITE_PATH);
            if (! file_exists($royalcms->environmentFilePath())) {
                $royalcms->useEnvironmentPath(SITE_ROOT);
            }

            Dotenv::create(
                $royalcms->environmentPath(),
                $royalcms->environmentFile(),
                Env::getFactory()
            );
        }
        catch (InvalidPathException $e) {
            //
        }

        $royalcms->detectEnvironment(function() {

            return env('ROYALCMS_ENV', 'production');

        });

    }
}
