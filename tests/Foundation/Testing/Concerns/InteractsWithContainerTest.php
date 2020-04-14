<?php

namespace Illuminate\Tests\Foundation\Testing\Concerns;

use Illuminate\Foundation\Mix;
use Orchestra\Testbench\TestCase;

class InteractsWithContainerTest extends TestCase
{
    public function testWithoutMixBindsEmptyHandlerAndReturnsInstance()
    {
        $instance = $this->withoutMix();

        $this->assertSame('', mix('path/to/asset.png'));
        $this->assertSame($this, $instance);
    }

    public function testWithMixRestoresOriginalHandlerAndReturnsInstance()
    {
        $handler = new \stdClass();
        $this->app->instance(Mix::class, $handler);

        $this->withoutMix();
        $instance = $this->withMix();

        $this->assertSame($handler, resolve(Mix::class));
        $this->assertSame($this, $instance);
    }
}
