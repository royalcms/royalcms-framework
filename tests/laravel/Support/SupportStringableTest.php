<?php

namespace Illuminate\Tests\Support;

use Illuminate\Support\Stringable;
use PHPUnit\Framework\TestCase;

class SupportStringableTest extends TestCase
{
    /**
     * @param  string  $string
     * @return \Illuminate\Support\Stringable
     */
    protected function stringable($string = '')
    {
        return new Stringable($string);
    }

    public function testIsAscii()
    {
        $this->assertTrue($this->stringable('A')->isAscii());
        $this->assertFalse($this->stringable('ù')->isAscii());
    }

    public function testPluralStudly()
    {
        $this->assertSame('LaraCon', (string) $this->stringable('LaraCon')->pluralStudly(1));
        $this->assertSame('LaraCons', (string) $this->stringable('LaraCon')->pluralStudly(2));
        $this->assertSame('LaraCon', (string) $this->stringable('LaraCon')->pluralStudly(-1));
        $this->assertSame('LaraCons', (string) $this->stringable('LaraCon')->pluralStudly(-2));
    }

    public function testMatch()
    {
        $stringable = $this->stringable('foo bar');

        $this->assertSame('bar', (string) $stringable->match('/bar/'));
        $this->assertSame('bar', (string) $stringable->match('/foo (.*)/'));
        $this->assertTrue($stringable->match('/nothing/')->isEmpty());

        $this->assertEquals(['bar', 'bar'], $this->stringable('bar foo bar')->matchAll('/bar/')->all());

        $stringable = $this->stringable('bar fun bar fly');

        $this->assertEquals(['un', 'ly'], $stringable->matchAll('/f(\w*)/')->all());
        $this->assertTrue($stringable->matchAll('/nothing/')->isEmpty());
    }

    public function testTrim()
    {
        $this->assertSame('foo', (string) $this->stringable(' foo ')->trim());
    }

    public function testCanBeLimitedByWords()
    {
        $this->assertSame('Taylor...', (string) $this->stringable('Taylor Otwell')->words(1));
        $this->assertSame('Taylor___', (string) $this->stringable('Taylor Otwell')->words(1, '___'));
        $this->assertSame('Taylor Otwell', (string) $this->stringable('Taylor Otwell')->words(3));
    }

    public function testWhenEmpty()
    {
        $this->assertSame('empty', (string) $this->stringable()->whenEmpty(function () {
            return 'empty';
        }));

        $this->assertSame('not-empty', (string) $this->stringable('not-empty')->whenEmpty(function () {
            return 'empty';
        }));
    }

    public function testTrimmedOnlyWhereNecessary()
    {
        $this->assertSame(' Taylor Otwell ', (string) $this->stringable(' Taylor Otwell ')->words(3));
        $this->assertSame(' Taylor...', (string) $this->stringable(' Taylor Otwell ')->words(1));
    }

    public function testTitle()
    {
        $this->assertSame('Jefferson Costella', (string) $this->stringable('jefferson costella')->title());
        $this->assertSame('Jefferson Costella', (string) $this->stringable('jefFErson coSTella')->title());
    }

    public function testWithoutWordsDoesntProduceError()
    {
        $nbsp = chr(0xC2).chr(0xA0);
        $this->assertSame(' ', (string) $this->stringable(' ')->words());
        $this->assertEquals($nbsp, (string) $this->stringable($nbsp)->words());
    }

    public function testAscii()
    {
        $this->assertSame('@', (string) $this->stringable('@')->ascii());
        $this->assertSame('u', (string) $this->stringable('ü')->ascii());
    }

    public function testAsciiWithSpecificLocale()
    {
        $this->assertSame('h H sht Sht a A ia yo', (string) $this->stringable('х Х щ Щ ъ Ъ иа йо')->ascii('bg'));
        $this->assertSame('ae oe ue Ae Oe Ue', (string) $this->stringable('ä ö ü Ä Ö Ü')->ascii('de'));
    }

    public function testStartsWith()
    {
        $this->assertTrue($this->stringable('jason')->startsWith('jas'));
        $this->assertTrue($this->stringable('jason')->startsWith('jason'));
        $this->assertTrue($this->stringable('jason')->startsWith(['jas']));
        $this->assertTrue($this->stringable('jason')->startsWith(['day', 'jas']));
        $this->assertFalse($this->stringable('jason')->startsWith('day'));
        $this->assertFalse($this->stringable('jason')->startsWith(['day']));
        $this->assertFalse($this->stringable('jason')->startsWith(null));
        $this->assertFalse($this->stringable('jason')->startsWith([null]));
        $this->assertFalse($this->stringable('0123')->startsWith([null]));
        $this->assertTrue($this->stringable('0123')->startsWith(0));
        $this->assertFalse($this->stringable('jason')->startsWith('J'));
        $this->assertFalse($this->stringable('jason')->startsWith(''));
        $this->assertFalse($this->stringable('7')->startsWith(' 7'));
        $this->assertTrue($this->stringable('7a')->startsWith('7'));
        $this->assertTrue($this->stringable('7a')->startsWith(7));
        $this->assertTrue($this->stringable('7.12a')->startsWith(7.12));
        $this->assertFalse($this->stringable('7.12a')->startsWith(7.13));
        $this->assertTrue($this->stringable(7.123)->startsWith('7'));
        $this->assertTrue($this->stringable(7.123)->startsWith('7.12'));
        $this->assertFalse($this->stringable(7.123)->startsWith('7.13'));
        // Test for multibyte string support
        $this->assertTrue($this->stringable('Jönköping')->startsWith('Jö'));
        $this->assertTrue($this->stringable('Malmö')->startsWith('Malmö'));
        $this->assertFalse($this->stringable('Jönköping')->startsWith('Jonko'));
        $this->assertFalse($this->stringable('Malmö')->startsWith('Malmo'));
    }

    public function testEndsWith()
    {
        $this->assertTrue($this->stringable('jason')->endsWith('on'));
        $this->assertTrue($this->stringable('jason')->endsWith('jason'));
        $this->assertTrue($this->stringable('jason')->endsWith(['on']));
        $this->assertTrue($this->stringable('jason')->endsWith(['no', 'on']));
        $this->assertFalse($this->stringable('jason')->endsWith('no'));
        $this->assertFalse($this->stringable('jason')->endsWith(['no']));
        $this->assertFalse($this->stringable('jason')->endsWith(''));
        $this->assertFalse($this->stringable('jason')->endsWith([null]));
        $this->assertFalse($this->stringable('jason')->endsWith(null));
        $this->assertFalse($this->stringable('jason')->endsWith('N'));
        $this->assertFalse($this->stringable('7')->endsWith(' 7'));
        $this->assertTrue($this->stringable('a7')->endsWith('7'));
        $this->assertTrue($this->stringable('a7')->endsWith(7));
        $this->assertTrue($this->stringable('a7.12')->endsWith(7.12));
        $this->assertFalse($this->stringable('a7.12')->endsWith(7.13));
        $this->assertTrue($this->stringable(0.27)->endsWith('7'));
        $this->assertTrue($this->stringable(0.27)->endsWith('0.27'));
        $this->assertFalse($this->stringable(0.27)->endsWith('8'));
        // Test for multibyte string support
        $this->assertTrue($this->stringable('Jönköping')->endsWith('öping'));
        $this->assertTrue($this->stringable('Malmö')->endsWith('mö'));
        $this->assertFalse($this->stringable('Jönköping')->endsWith('oping'));
        $this->assertFalse($this->stringable('Malmö')->endsWith('mo'));
    }

    public function testBefore()
    {
        $this->assertSame('han', (string) $this->stringable('hannah')->before('nah'));
        $this->assertSame('ha', (string) $this->stringable('hannah')->before('n'));
        $this->assertSame('ééé ', (string) $this->stringable('ééé hannah')->before('han'));
        $this->assertSame('hannah', (string) $this->stringable('hannah')->before('xxxx'));
        $this->assertSame('hannah', (string) $this->stringable('hannah')->before(''));
        $this->assertSame('han', (string) $this->stringable('han0nah')->before('0'));
        $this->assertSame('han', (string) $this->stringable('han0nah')->before(0));
        $this->assertSame('han', (string) $this->stringable('han2nah')->before(2));
    }

    public function testBeforeLast()
    {
        $this->assertSame('yve', (string) $this->stringable('yvette')->beforeLast('tte'));
        $this->assertSame('yvet', (string) $this->stringable('yvette')->beforeLast('t'));
        $this->assertSame('ééé ', (string) $this->stringable('ééé yvette')->beforeLast('yve'));
        $this->assertSame('', (string) $this->stringable('yvette')->beforeLast('yve'));
        $this->assertSame('yvette', (string) $this->stringable('yvette')->beforeLast('xxxx'));
        $this->assertSame('yvette', (string) $this->stringable('yvette')->beforeLast(''));
        $this->assertSame('yv0et', (string) $this->stringable('yv0et0te')->beforeLast('0'));
        $this->assertSame('yv0et', (string) $this->stringable('yv0et0te')->beforeLast(0));
        $this->assertSame('yv2et', (string) $this->stringable('yv2et2te')->beforeLast(2));
    }

    public function testBetween()
    {
        $this->assertSame('abc', (string) $this->stringable('abc')->between('', 'c'));
        $this->assertSame('abc', (string) $this->stringable('abc')->between('a', ''));
        $this->assertSame('abc', (string) $this->stringable('abc')->between('', ''));
        $this->assertSame('b', (string) $this->stringable('abc')->between('a', 'c'));
        $this->assertSame('b', (string) $this->stringable('dddabc')->between('a', 'c'));
        $this->assertSame('b', (string) $this->stringable('abcddd')->between('a', 'c'));
        $this->assertSame('b', (string) $this->stringable('dddabcddd')->between('a', 'c'));
        $this->assertSame('nn', (string) $this->stringable('hannah')->between('ha', 'ah'));
        $this->assertSame('a]ab[b', (string) $this->stringable('[a]ab[b]')->between('[', ']'));
        $this->assertSame('foo', (string) $this->stringable('foofoobar')->between('foo', 'bar'));
        $this->assertSame('bar', (string) $this->stringable('foobarbar')->between('foo', 'bar'));
    }

    public function testAfter()
    {
        $this->assertSame('nah', (string) $this->stringable('hannah')->after('han'));
        $this->assertSame('nah', (string) $this->stringable('hannah')->after('n'));
        $this->assertSame('nah', (string) $this->stringable('ééé hannah')->after('han'));
        $this->assertSame('hannah', (string) $this->stringable('hannah')->after('xxxx'));
        $this->assertSame('hannah', (string) $this->stringable('hannah')->after(''));
        $this->assertSame('nah', (string) $this->stringable('han0nah')->after('0'));
        $this->assertSame('nah', (string) $this->stringable('han0nah')->after(0));
        $this->assertSame('nah', (string) $this->stringable('han2nah')->after(2));
    }

    public function testAfterLast()
    {
        $this->assertSame('tte', (string) $this->stringable('yvette')->afterLast('yve'));
        $this->assertSame('e', (string) $this->stringable('yvette')->afterLast('t'));
        $this->assertSame('e', (string) $this->stringable('ééé yvette')->afterLast('t'));
        $this->assertSame('', (string) $this->stringable('yvette')->afterLast('tte'));
        $this->assertSame('yvette', (string) $this->stringable('yvette')->afterLast('xxxx'));
        $this->assertSame('yvette', (string) $this->stringable('yvette')->afterLast(''));
        $this->assertSame('te', (string) $this->stringable('yv0et0te')->afterLast('0'));
        $this->assertSame('te', (string) $this->stringable('yv0et0te')->afterLast(0));
        $this->assertSame('te', (string) $this->stringable('yv2et2te')->afterLast(2));
        $this->assertSame('foo', (string) $this->stringable('----foo')->afterLast('---'));
    }

    public function testContains()
    {
        $this->assertTrue($this->stringable('taylor')->contains('ylo'));
        $this->assertTrue($this->stringable('taylor')->contains('taylor'));
        $this->assertTrue($this->stringable('taylor')->contains(['ylo']));
        $this->assertTrue($this->stringable('taylor')->contains(['xxx', 'ylo']));
        $this->assertFalse($this->stringable('taylor')->contains('xxx'));
        $this->assertFalse($this->stringable('taylor')->contains(['xxx']));
        $this->assertFalse($this->stringable('taylor')->contains(''));
    }

    public function testContainsAll()
    {
        $this->assertTrue($this->stringable('taylor otwell')->containsAll(['taylor', 'otwell']));
        $this->assertTrue($this->stringable('taylor otwell')->containsAll(['taylor']));
        $this->assertFalse($this->stringable('taylor otwell')->containsAll(['taylor', 'xxx']));
    }

    public function testParseCallback()
    {
        $this->assertEquals(['Class', 'method'], $this->stringable('Class@method')->parseCallback('foo'));
        $this->assertEquals(['Class', 'foo'], $this->stringable('Class')->parseCallback('foo'));
        $this->assertEquals(['Class', null], $this->stringable('Class')->parseCallback());
    }

    public function testSlug()
    {
        $this->assertSame('hello-world', (string) $this->stringable('hello world')->slug());
        $this->assertSame('hello-world', (string) $this->stringable('hello-world')->slug());
        $this->assertSame('hello-world', (string) $this->stringable('hello_world')->slug());
        $this->assertSame('hello_world', (string) $this->stringable('hello_world')->slug('_'));
        $this->assertSame('user-at-host', (string) $this->stringable('user@host')->slug());
        $this->assertSame('سلام-دنیا', (string) $this->stringable('سلام دنیا')->slug('-', null));
        $this->assertSame('sometext', (string) $this->stringable('some text')->slug(''));
        $this->assertSame('', (string) $this->stringable('')->slug(''));
        $this->assertSame('', (string) $this->stringable('')->slug());
    }

    public function testStart()
    {
        $this->assertSame('/test/string', (string) $this->stringable('test/string')->start('/'));
        $this->assertSame('/test/string', (string) $this->stringable('/test/string')->start('/'));
        $this->assertSame('/test/string', (string) $this->stringable('//test/string')->start('/'));
    }

    public function testFinish()
    {
        $this->assertSame('abbc', (string) $this->stringable('ab')->finish('bc'));
        $this->assertSame('abbc', (string) $this->stringable('abbcbc')->finish('bc'));
        $this->assertSame('abcbbc', (string) $this->stringable('abcbbcbc')->finish('bc'));
    }

    public function testIs()
    {
        $this->assertTrue($this->stringable('/')->is('/'));
        $this->assertFalse($this->stringable('/')->is(' /'));
        $this->assertFalse($this->stringable('/a')->is('/'));
        $this->assertTrue($this->stringable('foo/bar/baz')->is('foo/*'));

        $this->assertTrue($this->stringable('App\Class@method')->is('*@*'));
        $this->assertTrue($this->stringable('app\Class@')->is('*@*'));
        $this->assertTrue($this->stringable('@method')->is('*@*'));

        // is case sensitive
        $this->assertFalse($this->stringable('foo/bar/baz')->is('*BAZ*'));
        $this->assertFalse($this->stringable('foo/bar/baz')->is('*FOO*'));
        $this->assertFalse($this->stringable('a')->is('A'));

        // Accepts array of patterns
        $this->assertTrue($this->stringable('a/')->is(['a*', 'b*']));
        $this->assertTrue($this->stringable('b/')->is(['a*', 'b*']));
        $this->assertFalse($this->stringable('f/')->is(['a*', 'b*']));

        // numeric values and patterns
        $this->assertFalse($this->stringable(123)->is(['a*', 'b*']));
        $this->assertTrue($this->stringable(11211)->is(['*2*', 'b*']));

        $this->assertTrue($this->stringable('blah/baz/foo')->is('*/foo'));

        $valueObject = new StringableObjectStub('foo/bar/baz');
        $patternObject = new StringableObjectStub('foo/*');

        $this->assertTrue($this->stringable($valueObject)->is('foo/bar/baz'));
        $this->assertTrue($this->stringable($valueObject)->is($patternObject));

        // empty patterns
        $this->assertFalse($this->stringable('test')->is([]));
    }

    public function testKebab()
    {
        $this->assertSame('laravel-php-framework', (string) $this->stringable('LaravelPhpFramework')->kebab());
    }

    public function testLower()
    {
        $this->assertSame('foo bar baz', (string) $this->stringable('FOO BAR BAZ')->lower());
        $this->assertSame('foo bar baz', (string) $this->stringable('fOo Bar bAz')->lower());
    }

    public function testUpper()
    {
        $this->assertSame('FOO BAR BAZ', (string) $this->stringable('foo bar baz')->upper());
        $this->assertSame('FOO BAR BAZ', (string) $this->stringable('foO bAr BaZ')->upper());
    }

    public function testLimit()
    {
        $this->assertSame('Laravel is...',
            (string) $this->stringable('Laravel is a free, open source PHP web application framework.')->limit(10)
        );
        $this->assertSame('这是一...', (string) $this->stringable('这是一段中文')->limit(6));

        $string = 'The PHP framework for web artisans.';
        $this->assertSame('The PHP...', (string) $this->stringable($string)->limit(7));
        $this->assertSame('The PHP', (string) $this->stringable($string)->limit(7, ''));
        $this->assertSame('The PHP framework for web artisans.', (string) $this->stringable($string)->limit(100));

        $nonAsciiString = '这是一段中文';
        $this->assertSame('这是一...', (string) $this->stringable($nonAsciiString)->limit(6));
        $this->assertSame('这是一', (string) $this->stringable($nonAsciiString)->limit(6, ''));
    }

    public function testLength()
    {
        $this->assertSame(11, $this->stringable('foo bar baz')->length());
        $this->assertSame(11, $this->stringable('foo bar baz')->length('UTF-8'));
    }

    public function testReplaceArray()
    {
        $this->assertSame('foo/bar/baz', (string) $this->stringable('?/?/?')->replaceArray('?', ['foo', 'bar', 'baz']));
        $this->assertSame('foo/bar/baz/?', (string) $this->stringable('?/?/?/?')->replaceArray('?', ['foo', 'bar', 'baz']));
        $this->assertSame('foo/bar', (string) $this->stringable('?/?')->replaceArray('?', ['foo', 'bar', 'baz']));
        $this->assertSame('?/?/?', (string) $this->stringable('?/?/?')->replaceArray('x', ['foo', 'bar', 'baz']));
        $this->assertSame('foo?/bar/baz', (string) $this->stringable('?/?/?')->replaceArray('?', ['foo?', 'bar', 'baz']));
        $this->assertSame('foo/bar', (string) $this->stringable('?/?')->replaceArray('?', [1 => 'foo', 2 => 'bar']));
        $this->assertSame('foo/bar', (string) $this->stringable('?/?')->replaceArray('?', ['x' => 'foo', 'y' => 'bar']));
    }

    public function testReplaceFirst()
    {
        $this->assertSame('fooqux foobar', (string) $this->stringable('foobar foobar')->replaceFirst('bar', 'qux'));
        $this->assertSame('foo/qux? foo/bar?', (string) $this->stringable('foo/bar? foo/bar?')->replaceFirst('bar?', 'qux?'));
        $this->assertSame('foo foobar', (string) $this->stringable('foobar foobar')->replaceFirst('bar', ''));
        $this->assertSame('foobar foobar', (string) $this->stringable('foobar foobar')->replaceFirst('xxx', 'yyy'));
        $this->assertSame('foobar foobar', (string) $this->stringable('foobar foobar')->replaceFirst('', 'yyy'));
        // Test for multibyte string support
        $this->assertSame('Jxxxnköping Malmö', (string) $this->stringable('Jönköping Malmö')->replaceFirst('ö', 'xxx'));
        $this->assertSame('Jönköping Malmö', (string) $this->stringable('Jönköping Malmö')->replaceFirst('', 'yyy'));
    }

    public function testReplaceLast()
    {
        $this->assertSame('foobar fooqux', (string) $this->stringable('foobar foobar')->replaceLast('bar', 'qux'));
        $this->assertSame('foo/bar? foo/qux?', (string) $this->stringable('foo/bar? foo/bar?')->replaceLast('bar?', 'qux?'));
        $this->assertSame('foobar foo', (string) $this->stringable('foobar foobar')->replaceLast('bar', ''));
        $this->assertSame('foobar foobar', (string) $this->stringable('foobar foobar')->replaceLast('xxx', 'yyy'));
        $this->assertSame('foobar foobar', (string) $this->stringable('foobar foobar')->replaceLast('', 'yyy'));
        // Test for multibyte string support
        $this->assertSame('Malmö Jönkxxxping', (string) $this->stringable('Malmö Jönköping')->replaceLast('ö', 'xxx'));
        $this->assertSame('Malmö Jönköping', (string) $this->stringable('Malmö Jönköping')->replaceLast('', 'yyy'));
    }

    public function testSnake()
    {
        $this->assertSame('laravel_p_h_p_framework', (string) $this->stringable('LaravelPHPFramework')->snake());
        $this->assertSame('laravel_php_framework', (string) $this->stringable('LaravelPhpFramework')->snake());
        $this->assertSame('laravel php framework', (string) $this->stringable('LaravelPhpFramework')->snake(' '));
        $this->assertSame('laravel_php_framework', (string) $this->stringable('Laravel Php Framework')->snake());
        $this->assertSame('laravel_php_framework', (string) $this->stringable('Laravel    Php      Framework   ')->snake());
        // ensure cache keys don't overlap
        $this->assertSame('laravel__php__framework', (string) $this->stringable('LaravelPhpFramework')->snake('__'));
        $this->assertSame('laravel_php_framework_', (string) $this->stringable('LaravelPhpFramework_')->snake('_'));
        $this->assertSame('laravel_php_framework', (string) $this->stringable('laravel php Framework')->snake());
        $this->assertSame('laravel_php_frame_work', (string) $this->stringable('laravel php FrameWork')->snake());
        // prevent breaking changes
        $this->assertSame('foo-bar', (string) $this->stringable('foo-bar')->snake());
        $this->assertSame('foo-_bar', (string) $this->stringable('Foo-Bar')->snake());
        $this->assertSame('foo__bar', (string) $this->stringable('Foo_Bar')->snake());
        $this->assertSame('żółtałódka', (string) $this->stringable('ŻółtaŁódka')->snake());
    }

    public function testStudly()
    {
        $this->assertSame('LaravelPHPFramework', (string) $this->stringable('laravel_p_h_p_framework')->studly());
        $this->assertSame('LaravelPhpFramework', (string) $this->stringable('laravel_php_framework')->studly());
        $this->assertSame('LaravelPhPFramework', (string) $this->stringable('laravel-phP-framework')->studly());
        $this->assertSame('LaravelPhpFramework', (string) $this->stringable('laravel  -_-  php   -_-   framework   ')->studly());

        $this->assertSame('FooBar', (string) $this->stringable('fooBar')->studly());
        $this->assertSame('FooBar', (string) $this->stringable('foo_bar')->studly());
        $this->assertSame('FooBar', (string) $this->stringable('foo_bar')->studly()); // test cache
        $this->assertSame('FooBarBaz', (string) $this->stringable('foo-barBaz')->studly());
        $this->assertSame('FooBarBaz', (string) $this->stringable('foo-bar_baz')->studly());
    }

    public function testCamel()
    {
        $this->assertSame('laravelPHPFramework', (string) $this->stringable('Laravel_p_h_p_framework')->camel());
        $this->assertSame('laravelPhpFramework', (string) $this->stringable('Laravel_php_framework')->camel());
        $this->assertSame('laravelPhPFramework', (string) $this->stringable('Laravel-phP-framework')->camel());
        $this->assertSame('laravelPhpFramework', (string) $this->stringable('Laravel  -_-  php   -_-   framework   ')->camel());

        $this->assertSame('fooBar', (string) $this->stringable('FooBar')->camel());
        $this->assertSame('fooBar', (string) $this->stringable('foo_bar')->camel());
        $this->assertSame('fooBar', (string) $this->stringable('foo_bar')->camel()); // test cache
        $this->assertSame('fooBarBaz', (string) $this->stringable('Foo-barBaz')->camel());
        $this->assertSame('fooBarBaz', (string) $this->stringable('foo-bar_baz')->camel());
    }

    public function testSubstr()
    {
        $this->assertSame('Ё', (string) $this->stringable('БГДЖИЛЁ')->substr(-1));
        $this->assertSame('ЛЁ', (string) $this->stringable('БГДЖИЛЁ')->substr(-2));
        $this->assertSame('И', (string) $this->stringable('БГДЖИЛЁ')->substr(-3, 1));
        $this->assertSame('ДЖИЛ', (string) $this->stringable('БГДЖИЛЁ')->substr(2, -1));
        $this->assertSame('', (string) $this->stringable('БГДЖИЛЁ')->substr(4, -4));
        $this->assertSame('ИЛ', (string) $this->stringable('БГДЖИЛЁ')->substr(-3, -1));
        $this->assertSame('ГДЖИЛЁ', (string) $this->stringable('БГДЖИЛЁ')->substr(1));
        $this->assertSame('ГДЖ', (string) $this->stringable('БГДЖИЛЁ')->substr(1, 3));
        $this->assertSame('БГДЖ', (string) $this->stringable('БГДЖИЛЁ')->substr(0, 4));
        $this->assertSame('Ё', (string) $this->stringable('БГДЖИЛЁ')->substr(-1, 1));
        $this->assertSame('', (string) $this->stringable('Б')->substr(2));
    }
}
