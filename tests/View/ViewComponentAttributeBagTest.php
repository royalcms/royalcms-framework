<?php

namespace Illuminate\Tests\View;

use Illuminate\View\ComponentAttributeBag;
use PHPUnit\Framework\TestCase;

class ViewComponentAttributeBagTest extends TestCase
{
    public function testAttributeRetrieval()
    {
        $bag = new ComponentAttributeBag(['class' => 'font-bold', 'name' => 'test']);

        $this->assertSame('class="mt-4 font-bold" name="test"', (string) $bag->merge(['class' => 'mt-4']));
        $this->assertSame('class="mt-4 font-bold" name="test"', (string) $bag->merge(['class' => 'mt-4', 'name' => 'foo']));
        $this->assertSame('class="mt-4 font-bold" id="bar" name="test"', (string) $bag->merge(['class' => 'mt-4', 'id' => 'bar']));
        $this->assertSame('class="mt-4 font-bold" name="test"', (string) $bag(['class' => 'mt-4']));
        $this->assertSame('class="mt-4 font-bold"', (string) $bag->only('class')->merge(['class' => 'mt-4']));
        $this->assertSame('name="test" class="font-bold"', (string) $bag->merge(['name' => 'default']));
        $this->assertSame('class="font-bold" name="test"', (string) $bag->merge([]));
        $this->assertSame('class="mt-4 font-bold"', (string) $bag->merge(['class' => 'mt-4'])->only('class'));
        $this->assertSame('class="mt-4 font-bold"', (string) $bag->only('class')(['class' => 'mt-4']));
        $this->assertSame('font-bold', $bag->get('class'));
        $this->assertSame('bar', $bag->get('foo', 'bar'));
        $this->assertSame('font-bold', $bag['class']);

        $bag = new ComponentAttributeBag([]);

        $this->assertSame('class="mt-4"', (string) $bag->merge(['class' => 'mt-4']));

        $bag = new ComponentAttributeBag([
            'test-string' => 'ok',
            'test-null' => null,
            'test-false' => false,
            'test-true' => true,
            'test-0' => 0,
            'test-0-string' => '0',
            'test-empty-string' => '',
        ]);

        $this->assertSame('test-string="ok" test-true="test-true" test-0="0" test-0-string="0" test-empty-string=""', (string) $bag);
        $this->assertSame('test-string="ok" test-true="test-true" test-0="0" test-0-string="0" test-empty-string=""', (string) $bag->merge());

        $bag = (new ComponentAttributeBag)
            ->merge([
                'test-escaped' => '<tag attr="attr">',
            ]);

        $this->assertSame('test-escaped="&lt;tag attr=&quot;attr&quot;&gt;"', (string) $bag);

        $bag = (new ComponentAttributeBag)
            ->merge([
                'test-string' => 'ok',
                'test-null' => null,
                'test-false' => false,
                'test-true' => true,
                'test-0' => 0,
                'test-0-string' => '0',
                'test-empty-string' => '',
            ]);

        $this->assertSame('test-string="ok" test-true="test-true" test-0="0" test-0-string="0" test-empty-string=""', (string) $bag);
    }
}
