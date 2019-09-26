<?php

namespace Royalcms\Component\Mail;

use Swift_Mailer;
use Royalcms\Component\Support\Arr;
use Royalcms\Component\Support\Str;
use Royalcms\Component\Support\ServiceProvider;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{

    /**
     * The application instance.
     *
     * @var \Royalcms\Component\Contracts\Foundation\Royalcms
     */
    protected $royalcms;

    /**
     * Create a new service provider instance.
     *
     * @param  \Royalcms\Component\Contracts\Foundation\Royalcms  $royalcms
     * @return void
     */
    public function __construct($royalcms)
    {
        parent::__construct($royalcms);

        $this->royalcms = $royalcms;
    }

    /**
     * Register the Swift Transport instance.
     *
     * @return void
     */
    protected function registerSwiftTransport()
    {
        $this->royalcms->singleton('swift.transport', function ($royalcms) {
            return new TransportManager($royalcms);
        });
    }

}
