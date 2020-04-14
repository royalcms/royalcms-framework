<?php

namespace Illuminate\Tests\Routing;

use Illuminate\Routing\SortedMiddleware;
use PHPUnit\Framework\TestCase;

class RoutingSortedMiddlewareTest extends TestCase
{
    public function testMiddlewareCanBeSortedByPriority()
    {
        $priority = [
            'First',
            'Second',
            'Third',
        ];

        $middleware = [
            'Something',
            'Something',
            'Something',
            'Something',
            'Second',
            'Otherthing',
            'First:api',
            'Third:foo',
            'First:foo,bar',
            'Third',
            'Second',
        ];

        $expected = [
            'Something',
            'First:api',
            'First:foo,bar',
            'Second',
            'Otherthing',
            'Third:foo',
            'Third',
        ];

        $this->assertEquals($expected, (new SortedMiddleware($priority, $middleware))->all());

        $this->assertEquals([], (new SortedMiddleware(['First'], []))->all());
        $this->assertEquals(['First'], (new SortedMiddleware(['First'], ['First']))->all());
        $this->assertEquals(['First', 'Second'], (new SortedMiddleware(['First', 'Second'], ['Second', 'First']))->all());

        $closure = function () {
            //
        };
        $this->assertEquals(['Second', $closure], (new SortedMiddleware(['First', 'Second'], ['Second', $closure]))->all());
    }
}
