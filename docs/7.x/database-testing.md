# Royalcms 测试之 - 数据库测试

- [简介](#introduction)
- [每次测试后重置数据库](#resetting-the-database-after-each-test)
    - [使用迁移](#using-migrations)
    - [使用事务](#using-transactions)
- [创建模型工厂](#writing-factories)
    - [工厂状态](#factory-states)
- [在测试中使用模型工厂](#using-factories)
    - [创建模型](#creating-models)
    - [持久化模型](#persisting-models)
    - [模型关联](#relationships)
- [可用的断言方法](#available-assertions)

<a name="introduction"></a>
## 简介

Royalcms 提供了多种有用的工具来让你更容易的测试使用数据库的应用程序。首先，你可以使用 `assertDatabaseHas` 辅助函数，来断言数据库中是否存在与指定条件互相匹配的数据。举例来说，如果我们想验证 `users` 数据表中是否存在 `email` 值为 `sally@example.com` 的数据，我们可以按照以下的方式来做测试：

```php
    public function testDatabase()
    {
        // 创建调用至应用程序...
    
        $this->assertDatabaseHas('users', [
            'email' => 'sally@example.com'
        ]);
    }
```

你也可以使用 `assertDatabaseMissing` 辅助函数来断言数据不在数据库中。

当然，使用 `assertDatabaseHas` 方法及其它的辅助函数只是为了方便。你也可以随意使用 PHPUnit 内置的所有断言方法来扩充测试。

<a name="resetting-the-database-after-each-test"></a>
## 每次测试后重置数据库

在每次测试结束后都需要对数据进行重置，这样前面的测试数据就不会干扰到后面的测试。

<a name="using-migrations"></a>
### 使用迁移

其中有一种方式就是在每次测试后都还原数据库，并在下次测试前运行迁移。Royalcms 提供了简洁的 `DatabaseMigrations` trait，它会自动帮你处理好这些操作。你只需在测试类中使用此 trait 即可：

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Royalcms\Component\Foundation\Testing\WithoutMiddleware;
use Royalcms\Component\Foundation\Testing\DatabaseMigrations;
use Royalcms\Component\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * 基本的功能测试示例。
     *
     * @return void
     */
    public function testBasicExample()
    {
        $response = $this->get('/');

        // ...
    }
}
```

<a name="using-transactions"></a>
### 使用事务

另一个方式，就是将每个测试案例都包含在数据库事务中。Royalcms 提供了一个简洁的 `DatabaseTransactions` trait 来自动帮你处理好这些操作。

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Royalcms\Component\Foundation\Testing\WithoutMiddleware;
use Royalcms\Component\Foundation\Testing\DatabaseMigrations;
use Royalcms\Component\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 基本的功能测试示例。
     *
     * @return void
     */
    public function testBasicExample()
    {
        $response = $this->get('/');

        // ...
    }
}
```

> {info} 此 trait 的事务只包含默认的数据库连接。 如果你的应用程序使用多个数据库连接，你需要在你的测试类中定义一个 `$connectionsToTransact` 属性，然后你就可以把你测试中需要用到的数据库连接名称以数组的形式放到这个属性中。

<a name="writing-factories"></a>
## 创建模型工厂

测试时，常常需要在运行测试之前写入一些数据到数据库中。创建测试数据时，除了手动的来设置每个字段的值，还可以使用 [Eloquent 模型](/docs/eloquent) 的「工厂」来设置每个属性的默认值。在开始之前，你可以先查看下应用程序的 `database/factories/UserFactory.php` 文件。此文件包含一个现成的模型工厂定义：

```php
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
```

闭包内为模型工厂的定义，你可以返回模型中所有属性的默认测试值。在该闭包内会接收到 [Faker](https://github.com/fzaninotto/Faker) PHP 函数库的实例，它可以让你很方便的生成各种随机数据以进行测试。

为了更好的组织代码，你也可以自己为每个数据模型创建对应的模型工厂类。比如说，你可以在 `database/factories` 文件夹下创建 `UserFactory.php` 和 `CommentFactory.php` 文件。在 `factories` 目录中的文件都会被 Royalcms 自动加载。

<a name="factory-states"></a>
### 工厂状态

工厂状态可以让你任意组合你的模型工厂，仅需要做出适当差异化的修改，就可以达到让模型拥有多种不同的状态。例如，你的 `用户` 模型中可以修改某个默认属性值来达到标识一种 `欠款` 的状态。你可以使用 `state` 方法来进行这种状态转换。对于简单的工厂状态，你可以直接传入要修改的属性数组。

```php
$factory->state(App\User::class, 'delinquent', [
    'account_status' => 'delinquent',
]);
```

如果你的工厂状态需要计算或者需要使用 `$faker` 实例，你可以使用闭包方法来实现状态属性的修改：

```php
$factory->state(App\User::class, 'address', function ($faker) {
    return [
        'address' => $faker->address,
    ];
});
```

​        

<a name="using-factories"></a>
## 在测试中使用模型工厂

<a name="creating-models"></a>
### 创建模型

在模型工厂定义后，就可以在测试或是数据库的填充文件中，通过全局的 `factory` 函数来生成模型实例。接着让我们先来看看几个创建模型的例子。首先我们会使用 `make` 方法创建模型，但不将它们保存至数据库：

```php
public function testDatabase()
{
    $user = factory(App\User::class)->make();

    // 在测试中使用模型...
}
```

你也可以创建一个含有多个模型的集合，或创建一个指定类型的模型：

```php
// 创建一个 App\User 实例
$users = factory(App\User::class, 3)->make();
```

#### 应用模型工厂状态

你可能需要在你的模型中应用不同的 [模型工厂状态](#factory-states)。如果你想模型加上多种不同的状态，你只须指定每个你想添加的状态名称即可：

```php
$users = factory(App\User::class, 5)->states('delinquent')->make();

$users = factory(App\User::class, 5)->states('premium', 'delinquent')->make();
```

#### 重写模型属性

如果你想重写模型中的某些默认值，则可以传递一个包含数值的数组至 `make` 方法。只有指定的数值会被替换，其它剩余的数值则会按照模型工厂指定的默认值来设置：

```php
$user = factory(App\User::class)->make([
    'name' => 'Abigail',
]);
```

<a name="persisting-models"></a>
### 持久化模型

`create` 方法不仅会创建模型实例，同时会使用 Eloquent 的 `save` 方法来将它们保存至数据库：

```php
public function testDatabase()
{
    // 创建一个 App\User 实例
    $user = factory(App\User::class)->create();

    // 创建 3 个 App\User 实例
    $users = factory(App\User::class, 3)->create();

    // 在测试中使用模型...
}
```

同样的，你可以在数组传递至 `create` 方法时重写模型的属性

```php
$user = factory(App\User::class)->create([
    'name' => 'Abigail',
]);
```

<a name="relationships"></a>
### 模型关联

在本例中，我们还会增加关联至我们所创建的模型。当使用 `create` 方法创建多个模型时，它会返回一个 Eloquent [集合实例](/docs/eloquent-collections)，让你能够使用集合所提供的便利函数，像是 `each`：

```php
    $users = factory(App\User::class, 3)
               ->create()
               ->each(function ($u) {
                    $u->posts()->save(factory(App\Post::class)->make());
                });
```

#### 关联和属性闭包

你可以使用闭包参数来创建模型关联。例如如果你想在创建一个 `Post` 的顺便创建一个 `User` 实例：

```php
$factory->define(App\Post::class, function ($faker) {
    return [
        'title' => $faker->title,
        'content' => $faker->paragraph,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});
```

这些闭包也可以获取到生成的模型工厂包含的属性数组：

```php
$factory->define(App\Post::class, function ($faker) {
    return [
        'title' => $faker->title,
        'content' => $faker->paragraph,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'user_type' => function (array $post) {
            return App\User::find($post['user_id'])->type;
        }
    ];
});
```

<a name="available-assertions"></a>
## 可用的断言方法

Royalcms 为你的 [PHPUnit](https://phpunit.de/) 测试提供了一些数据库断言方法：

方法名  | 描述
------------- | -------------
`$this->assertDatabaseHas($table, array $data);`  |  断言数据库里含有指定数据。
`$this->assertDatabaseMissing($table, array $data);`  |  断言表里没有指定数据。
`$this->assertSoftDeleted($table, array $data);`  |  断言指定记录已经被软删除。

