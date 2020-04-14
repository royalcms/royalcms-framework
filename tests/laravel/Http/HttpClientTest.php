<?php

namespace Illuminate\Tests\Http;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Str;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    /**
     * @var \Illuminate\Http\Client\Factory
     */
    protected $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new Factory;
    }

    public function testStubbedResponsesAreReturnedAfterFaking()
    {
        $this->factory->fake();

        $response = $this->factory->post('http://laravel.com/test-missing-page');

        $this->assertTrue($response->ok());
    }

    public function testUrlsCanBeStubbedByPath()
    {
        $this->factory->fake([
            'foo.com/*' => ['page' => 'foo'],
            'bar.com/*' => ['page' => 'bar'],
            '*' => ['page' => 'fallback'],
        ]);

        $fooResponse = $this->factory->post('http://foo.com/test');
        $barResponse = $this->factory->post('http://bar.com/test');
        $fallbackResponse = $this->factory->post('http://fallback.com/test');

        $this->assertSame('foo', $fooResponse['page']);
        $this->assertSame('bar', $barResponse['page']);
        $this->assertSame('fallback', $fallbackResponse['page']);

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/test' &&
                   $request->hasHeader('Content-Type', 'application/json');
        });
    }

    public function testCanSendJsonData()
    {
        $this->factory->fake();

        $this->factory->withHeaders([
            'X-Test-Header' => 'foo',
            'X-Test-ArrayHeader' => ['bar', 'baz'],
        ])->post('http://foo.com/json', [
            'name' => 'Taylor',
        ]);

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/json' &&
                   $request->hasHeader('Content-Type', 'application/json') &&
                   $request->hasHeader('X-Test-Header', 'foo') &&
                   $request->hasHeader('X-Test-ArrayHeader', ['bar', 'baz']) &&
                   $request['name'] === 'Taylor';
        });
    }

    public function testCanSendFormData()
    {
        $this->factory->fake();

        $this->factory->asForm()->post('http://foo.com/form', [
            'name' => 'Taylor',
            'title' => 'Laravel Developer',
        ]);

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/form' &&
                   $request->hasHeader('Content-Type', 'application/x-www-form-urlencoded') &&
                   $request['name'] === 'Taylor';
        });
    }

    public function testSpecificRequestIsNotBeingSent()
    {
        $this->factory->fake();

        $this->factory->post('http://foo.com/form', [
            'name' => 'Taylor',
        ]);

        $this->factory->assertNotSent(function (Request $request) {
            return $request->url() === 'http://foo.com/form' &&
                $request['name'] === 'Peter';
        });
    }

    public function testNoRequestIsNotBeingSent()
    {
        $this->factory->fake();

        $this->factory->assertNothingSent();
    }

    public function testCanSendMultipartData()
    {
        $this->factory->fake();

        $this->factory->asMultipart()->post('http://foo.com/multipart', [
            [
                'name' => 'foo',
                'contents' => 'data',
                'headers' => ['X-Test-Header' => 'foo'],
            ],
        ]);

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/multipart' &&
                   Str::startsWith($request->header('Content-Type')[0], 'multipart') &&
                   $request[0]['name'] === 'foo';
        });
    }

    public function testFilesCanBeAttached()
    {
        $this->factory->fake();

        $this->factory->attach('foo', 'data', 'file.txt', ['X-Test-Header' => 'foo'])
                ->post('http://foo.com/file');

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/file' &&
                   Str::startsWith($request->header('Content-Type')[0], 'multipart') &&
                   $request[0]['name'] === 'foo' &&
                   $request->hasFile('foo', 'data', 'file.txt');
        });
    }

    public function testItCanSendToken()
    {
        $this->factory->fake();

        $this->factory->withToken('token')->post('http://foo.com/json');

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/json' &&
                $request->hasHeader('Authorization', 'Bearer token');
        });
    }

    public function testSequenceBuilder()
    {
        $this->factory->fake([
            '*' => $this->factory->sequence()
                ->push('Ok', 201)
                ->push(['fact' => 'Cats are great!'])
                ->pushFile(__DIR__.'/fixtures/test.txt')
                ->pushStatus(403),
        ]);

        $response = $this->factory->get('https://example.com');
        $this->assertSame('Ok', $response->body());
        $this->assertSame(201, $response->status());

        $response = $this->factory->get('https://example.com');
        $this->assertSame(['fact' => 'Cats are great!'], $response->json());
        $this->assertSame(200, $response->status());

        $response = $this->factory->get('https://example.com');
        $this->assertSame('This is a story about something that happened long ago when your grandfather was a child.'.PHP_EOL, $response->body());
        $this->assertSame(200, $response->status());

        $response = $this->factory->get('https://example.com');
        $this->assertSame('', $response->body());
        $this->assertSame(403, $response->status());

        $this->expectException(OutOfBoundsException::class);

        // The sequence is empty, it should throw an exception.
        $this->factory->get('https://example.com');
    }

    public function testSequenceBuilderCanKeepGoingWhenEmpty()
    {
        $this->factory->fake([
            '*' => $this->factory->sequence()
                ->dontFailWhenEmpty()
                ->push('Ok'),
        ]);

        $response = $this->factory->get('https://laravel.com');
        $this->assertSame('Ok', $response->body());

        // The sequence is empty, but it should not fail.
        $this->factory->get('https://laravel.com');
    }

    public function testAssertSequencesAreEmpty()
    {
        $this->factory->fake([
            '*' => $this->factory->sequence()
                ->push('1')
                ->push('2'),
        ]);

        $this->factory->get('https://example.com');
        $this->factory->get('https://example.com');

        $this->factory->assertSequencesAreEmpty();
    }

    public function testFakeSequence()
    {
        $this->factory->fakeSequence()
            ->pushStatus(201)
            ->pushStatus(301);

        $this->assertSame(201, $this->factory->get('https://example.com')->status());
        $this->assertSame(301, $this->factory->get('https://example.com')->status());
    }

    public function testWithCookies()
    {
        $this->factory->fakeSequence()->pushStatus(200);

        $response = $this->factory->withCookies(
            ['foo' => 'bar'], 'https://laravel.com'
        )->get('https://laravel.com');

        $this->assertCount(1, $response->cookies()->toArray());

        /** @var \GuzzleHttp\Cookie\CookieJarInterface $responseCookies */
        $responseCookie = $response->cookies()->toArray()[0];

        $this->assertSame('foo', $responseCookie['Name']);
        $this->assertSame('bar', $responseCookie['Value']);
        $this->assertSame('https://laravel.com', $responseCookie['Domain']);
    }

    public function testGetWithArrayQueryParam()
    {
        $this->factory->fake();

        $this->factory->get('http://foo.com/get', ['foo' => 'bar']);

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/get?foo=bar';
        });
    }

    public function testGetWithStringQueryParam()
    {
        $this->factory->fake();

        $this->factory->get('http://foo.com/get', 'foo=bar');

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/get?foo=bar';
        });
    }

    public function testGetWithQuery()
    {
        $this->factory->fake();

        $this->factory->get('http://foo.com/get?foo=bar&page=1');

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/get?foo=bar&page=1';
        });
    }

    public function testGetWithQueryWontEncode()
    {
        $this->factory->fake();

        $this->factory->get('http://foo.com/get?foo;bar;1;5;10&page=1');

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/get?foo;bar;1;5;10&page=1';
        });
    }

    public function testGetWithArrayQueryParamOverwrites()
    {
        $this->factory->fake();

        $this->factory->get('http://foo.com/get?foo=bar&page=1', ['hello' => 'world']);

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/get?hello=world';
        });
    }

    public function testGetWithArrayQueryParamEncodes()
    {
        $this->factory->fake();

        $this->factory->get('http://foo.com/get', ['foo;bar; space test' => 'laravel']);

        $this->factory->assertSent(function (Request $request) {
            return $request->url() === 'http://foo.com/get?foo%3Bbar%3B%20space%20test=laravel';
        });
    }
}
