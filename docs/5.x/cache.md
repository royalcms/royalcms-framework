# Royalcms 的缓存系统

- [配置信息](#configuration)
    - [驱动的前提条件](#driver-prerequisites)
- [缓存的使用](#cache-usage)
    - [获取缓存实例](#obtaining-a-cache-instance)
    - [从缓存中获取数据](#retrieving-items-from-the-cache)
    - [在缓存中存储数据](#storing-items-in-the-cache)
    - [删除缓存中的数据](#removing-items-from-the-cache)
    - [辅助函数 Cache](#the-cache-helper)
- [缓存标记](#cache-tags)
    - [写入被标记的缓存数据](#storing-tagged-cache-items)
    - [访问被标记的缓存数据](#accessing-tagged-cache-items)
    - [移除被标记的缓存数据](#removing-tagged-cache-items)
- [增加自定义缓存驱动](#adding-custom-cache-drivers)
    - [写驱动](#writing-the-driver)
    - [注册驱动](#registering-the-driver)
- [事件](#events)

<a name="configuration"></a>
## 配置信息

Royalcms 为各种后端缓存提供丰富而统一的 API，而其配置信息位于 `config/cache.php` 文件中，你可以指定默认的缓存驱动程序。Royalcms 支持当前流行的后段缓存，例如 [Memcached](https://memcached.org) 和 [Redis](http://redis.io)。

`cache.php` 文件中包含了很多选项，这些选项都附有清晰说明注释，因此本文不会在此多加详解。Royalcms 默认使用 `file` 缓存驱动，将序列化的缓存对象保存在文件系统中。对于大型应用程序而言，推荐你使用更强大的缓存驱动，如 Memcached 或者 Redis。你甚至可以为同一个驱动程序配置多个缓存。

<a name="driver-prerequisites"></a>
### 驱动的前提条件

#### 数据库

当使用 `database` 缓存驱动时，你需要配置一个表来存放缓存数据，下面是构建缓存数据表结构的 `RC_Schema` 声明示例：

```php
RC_Schema::create('cache', function ($table) {
    $table->string('key')->unique();
    $table->text('value');
    $table->integer('expiration');
});
```

> {primary} 你也可以使用 royalcms 命令 `php royalcms cache:table` 来生成合适的迁移。

#### Memcached

使用 Memcached 驱动需要安装 [Memcached PECL 扩展包](https://pecl.php.net/package/memcached) 。你可以把所有 Memcached 服务器都列在  `config/cache.php` 配置文件中：

```php
'memcached' => [
    [
        'host' => '127.0.0.1',
        'port' => 11211,
        'weight' => 100
    ],
],
```

你可以将 `host` 选项设置为 UNIX 的 socket 路径。如果你这样配置了，记得 `port` 选项应该设置为 `0`:

```php
'memcached' => [
    [
        'host' => '/var/run/memcached/memcached.sock',
        'port' => 0,
        'weight' => 100
    ],
],
```

#### Redis

在使用 Royalcms 的 Redis 缓存之前，你需要通过 Composer 安装 `predis/predis` 扩展包 (~1.0) 或者使用 PECL 安装 PhpRedis PHP 拓展。

如需了解更多配置 Redis 的信息，请参考 [Royalcms Redis 文档](/docs/redis#configuration).

<a name="cache-usage"></a>
## 缓存的使用

<a name="obtaining-a-cache-instance"></a>
### 获取缓存实例

`Royalcms\Component\Contracts\Cache\Factory` 和 `Royalcms\Component\Contracts\Cache\Repository` [contracts](/docs/contracts) 提供了 Royalcms 缓存服务的访问机制。 `Factory`  contract  为你的应用程序定义了访问所有缓存驱动的机制。 `Repository` contract 通常是由 `cache` 配置文件指定的默认缓存驱动实现的。

不过，你也可以使用 `RC_Cache` facade，我们将在后续的文档中介绍。`RC_Cache` facade 为 Royalcms 缓存 contract 底层的实现提供了方便又简洁的方法：

```php
<?php

namespace App\Http\Controllers;

use Royalcms\Component\Support\Facades\Cache;

class UserController extends Controller
{
    /**
     * Show a list of all users of the application.
     *
     * @return Response
     */
    public function index()
    {
        $value = Cache::get('key');

        //
    }
}
```

#### 访问多个缓存存储

使用 `RC_Cache` facade，你可以通过 `store` 方法来访问各种缓存存储。 传入 `store` 方法的键应该对应 `cache` 配置信息文件中的 `stores` 配置数组中所列的存储之一：

```php
$value = RC_Cache::store('file')->get('foo');

RC_Cache::store('redis')->put('bar', 'baz', 10);
```

<a name="retrieving-items-from-the-cache"></a>
### 从缓存中获取数据

`RC_Cache`  facade 中的 `get`  方法是用来从缓存中获取数据的方法。如果该数据不存在于缓存中，则该方法返回 `null`。你也可以向 `get` 方法传递第二个参数，用来指定如果查找的数据不存在时，你希望返回的默认值：

```php
$value = RC_Cache::get('key');

$value = RC_Cache::get('key', 'default');
```

你甚至可以传递 `Closure` 作为默认值。如果指定的数据不存在于缓存中，将返回 `Closure` 的结果。传递闭包的方法可以允许你从数据库或其他外部服务中获取默认值：

```php
$value = RC_Cache::get('key', function () {
    return DB::table(...)->get();
});
```

#### 确认项目是否存在

`has` 方法可用于确定缓存中是否存在项目。如果值为 `null` 或 `false`，则此方法将返回 `false`：

```php
if (RC_Cache::has('key')) {
    //
}
```

#### 递增与递减值

`increment` 和 `decrement` 方法可以用来调整高速缓存中整数项的值。这两个方法都可以传入第二个可选参数，用来指示要递增或递减值的数量：

```php
RC_Cache::increment('key');
RC_Cache::increment('key', $amount);
RC_Cache::decrement('key');
RC_Cache::decrement('key', $amount);
```

#### 获取和更新

有时你可能想从缓存中找出一个数据，而当在请求的数据不存在时，程序能为你存储默认值。例如，你可能会想从缓存中取出所有用户，如果缓存中不存在用户数据时，就从数据库中将这些用户取出并放入缓存中。你可以使用 `RC_Cache::remember` 方法来做到这一点：

```php
$value = RC_Cache::remember('users', $minutes, function () {
    return DB::table('users')->get();
});
```

如果缓存中不存在你想找的数据，则传递给 `remember` 方法的 `Closure` 将被执行，然后将其结果返回并放置在缓存中。

你还可以使用  `rememberForever` 方法从缓存中查找数据或永久存储它：

```php
$value = RC_Cache::rememberForever('users', function() {
    return DB::table('users')->get();
});
```

#### 获取和删除

如果你需要从缓存中获取到数据之后再删除它，你可以使用 `pull` 方法。和 `get` 方法一样，如果缓存中不存在该数据， 则返回 `null` :

```php
$value = RC_Cache::pull('key');
```

<a name="storing-items-in-the-cache"></a>
### 在缓存中存储数据

你可以使用 `RC_Cache` facade 的 `put` 方法来将数据存储到缓存中。当你在缓存中存放数据时，你需要使用第三个参数来设定缓存的过期时间：

```php
RC_Cache::put('key', 'value', $minutes);
```

除了以整数形式传递过期的分钟数，还可以传递一个 `DateTime` 实例来表示该数据的过期时间：

```php
$expiresAt = Carbon::now()->addMinutes(10);

RC_Cache::put('key', 'value', $expiresAt);
```

#### 只存储没有的数据

`add` 方法将不存在于缓存中的数据放入缓存中，如果存放成功返回 `true` ，否则返回 `false` ：

```php
RC_Cache::add('key', 'value', $minutes);
```

#### 数据永久存储

`forever` 方法可以用来将数据永久存入缓存中。因为这些缓存数据不会过期，所以必须通过 `forget` 方法从缓存中手动删除它们：

```php
RC_Cache::forever('key', 'value');
```

> {primary} 如果你使用 Memcached 驱动，那么当缓存数量达到其大小限制时，可能会删除「永久」存储的数据。

<a name="removing-items-from-the-cache"></a>
### 删除缓存中的数据

你可以使用 `forget` 方法从缓存中删除数据：

```php
RC_Cache::forget('key');
```

你也可以使用 `flush` 方法清空所有缓存：

```php
RC_Cache::flush();
```

> {info} 清空缓存的方法并不会考虑缓存前缀，会将缓存中所有的内容删除。因此在清除与其它应用程序共享的缓存时请谨慎考虑。

<a name="the-cache-helper"></a>
### 辅助函数 Cache

除了可以使用 `RC_Cache` facade 或者 cache contract 之外，你也可以使用全局帮助函数 `cache` 来获取和保存缓存数据。当 `cache`  只接收一个字符串参数的时候，它将会返回给定键对应的值：

```php
$value = cache('key');
```

如果你向函数提供了一组键值对和到期时间，它会在指定时间内在缓存中存储数据：

```php
cache(['key' => 'value'], $minutes);

cache(['key' => 'value'], Carbon::now()->addSeconds(10));
```

> {primary} 如果在测试中使用全局函数 `cache`，可以使用 `RC_Cache::shouldReceive`  方法，就像正在测试 facade一样。

<a name="cache-tags"></a>
## 缓存标记

> {info} 缓存标记并不支持使用 `file` 或 `database` 的缓存驱动。此外，当在「永久」存储的高速缓存中使用多个标记时，类似 `memcached` 这种驱动的性能会是最好的，它会自动清除旧的记录。

<a name="storing-tagged-cache-items"></a>
### 写入被标记的缓存数据

所谓的缓存标记，就是对缓存的数据打上相关的标记，以便清空所有被分配指定标记的缓存值。你可以通过传入标记名称的有序数组来为缓存数据写入标记。例如，我们可以将值 `put` 进缓存的同时标记它：

```php
RC_Cache::tags(['people', 'artists'])->put('John', $john, $minutes);

RC_Cache::tags(['people', 'authors'])->put('Anne', $anne, $minutes);
```

<a name="accessing-tagged-cache-items"></a>
### 访问被标记的缓存数据

若要获取一个被标记的缓存数据，请将相同的有序标记数组传递给 `tags` 方法，然后调用 `get` 方法来获取你要检索的键：

```php
$john = RC_Cache::tags(['people', 'artists'])->get('John');

$anne = RC_Cache::tags(['people', 'authors'])->get('Anne');
```

<a name="removing-tagged-cache-items"></a>
### 移除被标记的缓存数据

你可以清空有单个标记或是一组标记的所有缓存数据。例如，下面的语句会删除被标记为 `people`、`authors` 或两者都有的缓存。所以，`Anne` 与 `John` 都会从缓存被删除：

```php
RC_Cache::tags(['people', 'authors'])->flush();
```

相比之下，下面的语句只会删除被标记为 `authors` 的缓存，所以 `Anne` 会被移除，但 `John` 不会：

```php
RC_Cache::tags('authors')->flush();
```

<a name="adding-custom-cache-drivers"></a>
## 增加自定义的缓存驱动

<a name="writing-the-driver"></a>
### 写驱动

要创建自定义的缓存驱动程序，首先需要实现 `Royalcms\Component\Contracts\Cache\Store` contract。因此，MongoDB 的缓存实现看起来会像这样：

```php
<?php

namespace App\Extensions;

use Royalcms\Component\Contracts\Cache\Store;

class MongoStore implements Store
{
    public function get($key) {}
    public function many(array $keys);
    public function put($key, $value, $minutes) {}
    public function putMany(array $values, $minutes);
    public function increment($key, $value = 1) {}
    public function decrement($key, $value = 1) {}
    public function forever($key, $value) {}
    public function forget($key) {}
    public function flush() {}
    public function getPrefix() {}
}
```

我们只需要使用 MongoDB 的连接来实现这些方法。关于如何实现这些方法的示例，可以参阅框架源代码中的 `Royalcms\Component\Cache\MemcachedStore`。一旦我们完成方法的实现，可以像下面这样完成自定义驱动的注册了。

```php
RC_Cache::extend('mongo', function ($app) {
    return RC_Cache::repository(new MongoStore);
});
```

> {primary} 如果你想自定义的缓存驱动代码的放置，你可以在 `app` 目录下创建一个 `Extensions` 命名空间。Royalcms 没有硬性规定应用程序的结构，你可以根据自己的喜好自由组织你的应用程序。

<a name="registering-the-driver"></a>
### 注册驱动

要使用 Royalcms 来注册自定义的缓存驱动，就要在 `RC_Cache` facade 上使用 `extend` 方法。对 `RC_Cache::extend` 的调用可以在最新的 Royalcms 应用程序中附带的 `App\Providers\AppServiceProvider` 的 `boot` 方法中完成，或者你可以创建自己的服务提供器来放置这些扩展，只是不要忘记在 `config/app.php` 中的 `providers` 数组中注册提供器：

```php
<?php

namespace App\Providers;

use App\Extensions\MongoStore;
use Royalcms\Component\Support\Facades\Cache;
use Royalcms\Component\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::extend('mongo', function ($app) {
            return Cache::repository(new MongoStore);
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
```

传递给 `extend` 方法的第一个参数是驱动程序的名称。这将与 `config/cache.php` 配置文件的 `driver` 选项相对应。第二个参数是应该返回 `Royalcms\Component\Cache\Repository` 实例的闭包。这个闭包将传递一个服务容器的 `$app` 实例。

你的自定义的扩展注册后，需要将 `config/cache.php` 配置文件中的 `driver` 选项更新为你的扩展名。

<a name="events"></a>
## 事件

你可以监听缓存触发的 [事件](/docs/events) 来对每个缓存的操作执行代码。为此，你应该要将这些事件监听器放在 `EventServiceProvider` 中:

```php
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    'Royalcms\Component\Cache\Events\CacheHit' => [
        'App\Listeners\LogCacheHit',
    ],

    'Royalcms\Component\Cache\Events\CacheMissed' => [
        'App\Listeners\LogCacheMissed',
    ],

    'Royalcms\Component\Cache\Events\KeyForgotten' => [
        'App\Listeners\LogKeyForgotten',
    ],

    'Royalcms\Component\Cache\Events\KeyWritten' => [
        'App\Listeners\LogKeyWritten',
    ],
];
```

