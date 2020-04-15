<?php

namespace Royalcms\Tests\Config;

use Royalcms\Component\Config\FileLoader;
use Royalcms\Component\Config\Repository;
use Royalcms\Component\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    /**
     * @var \Royalcms\Component\Config\Repository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $config;

    protected function setUp(): void
    {
        $files = new Filesystem();
        $loader = new FileLoader($files, null);
        $this->repository = new Repository($loader, 'test', $this->config = [
            '*::test' => [
                'foo' => 'bar',
                'bar' => 'baz',
                'baz' => 'bat',
                'null' => null,
                'associate' => [
                    'x' => 'xxx',
                    'y' => 'yyy',
                ],
                'array' => [
                    'aaa',
                    'zzz',
                ],
                'x' => [
                    'z' => 'zoo',
                ],
            ]
        ]);

        parent::setUp();
    }

//    public function testConstruct()
//    {
//        $this->assertInstanceOf(Repository::class, $this->repository);
//    }

    public function testHasIsTrue()
    {
        $this->assertTrue($this->repository->has('test.foo'));
    }

    public function testHasIsFalse()
    {
        $this->assertFalse($this->repository->has('test.not-exist'));
    }

    public function testGet()
    {
        $this->assertSame('bar', $this->repository->get('test.foo'));
    }

//    public function testGetWithArrayOfKeys()
//    {
//        $this->assertSame([
//            'foo' => 'bar',
//            'bar' => 'baz',
//            'none' => null,
//        ], $this->repository->get([
//            'foo',
//            'bar',
//            'none',
//        ]));
//
//        $this->assertSame([
//            'x.y' => 'default',
//            'x.z' => 'zoo',
//            'bar' => 'baz',
//            'baz' => 'bat',
//        ], $this->repository->get([
//            'x.y' => 'default',
//            'x.z' => 'default',
//            'bar' => 'default',
//            'baz',
//        ]));
//    }

//    public function testGetMany()
//    {
//        $this->assertSame([
//            'foo' => 'bar',
//            'bar' => 'baz',
//            'none' => null,
//        ], $this->repository->getMany([
//            'foo',
//            'bar',
//            'none',
//        ]));
//
//        $this->assertSame([
//            'x.y' => 'default',
//            'x.z' => 'zoo',
//            'bar' => 'baz',
//            'baz' => 'bat',
//        ], $this->repository->getMany([
//            'x.y' => 'default',
//            'x.z' => 'default',
//            'bar' => 'default',
//            'baz',
//        ]));
//    }

    public function testGetWithDefault()
    {
        $this->assertSame('default', $this->repository->get('test.not-exist', 'default'));
    }

    public function testSet()
    {
        $this->repository->set('test.key', 'value');
        $this->assertSame('value', $this->repository->get('test.key'));
    }

//    public function testSetArray()
//    {
//        $this->repository->set([
//            'key1' => 'value1',
//            'key2' => 'value2',
//        ]);
//        $this->assertSame('value1', $this->repository->get('test.key1'));
//        $this->assertSame('value2', $this->repository->get('test.key2'));
//    }

    public function testPrepend()
    {
        $this->repository->prepend('test.array', 'xxx');
        $this->assertSame('xxx', $this->repository->get('test.array.0'));
    }

    public function testPush()
    {
        $this->repository->push('test.array', 'xxx');
        $this->assertSame('xxx', $this->repository->get('test.array.2'));
    }

    public function testAll()
    {
        $this->assertSame($this->config, $this->repository->all());
    }

    public function testOffsetExists()
    {
        $this->assertTrue(isset($this->repository['test.foo']));
        $this->assertFalse(isset($this->repository['test.not-exist']));
    }

    public function testOffsetGet()
    {
        $this->assertNull($this->repository['test.not-exist']);
        $this->assertSame('bar', $this->repository['test.foo']);
        $this->assertSame([
            'x' => 'xxx',
            'y' => 'yyy',
        ], $this->repository['test.associate']);
    }

    public function testOffsetSet()
    {
        $this->assertNull($this->repository['test.key']);

        $this->repository['test.key'] = 'value';

        $this->assertSame('value', $this->repository['test.key']);
    }

    public function testOffsetUnset()
    {
        $all = $this->repository->all();
        $items = $all['*::test'];
        $this->assertArrayHasKey('associate', $items);
        $this->assertSame($this->config['*::test']['associate'], $this->repository->get('test.associate'));

        unset($this->repository['test.associate']);

        $this->assertArrayHasKey('associate', $items);
        $this->assertNull($this->repository->get('test.associate'));
    }
}
