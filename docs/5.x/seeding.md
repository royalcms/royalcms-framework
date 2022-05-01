# Royalcms 数据库之：数据填充

- [简介](#introduction)
- [编写 Seeders](#writing-seeders)
    - [使用模型工厂](#using-model-factories)
    - [调用其他 Seeders](#calling-additional-seeders)
- [ 运行 Seeders](#running-seeders)

<a name="introduction"></a>
## 简介

Royalcms 可以用 seed 类轻松地为数据库填充测试数据。所有的 seed 类都存放在 `database/seeds` 目录下。你可以任意为 seed 类命名，但是更应该遵守类似 `UsersTableSeeder` 的命名规范。Royalcms 默认定义的一个 `DatabaseSeeder` 类。可以在这个类中使用 `call` 方法来运行其它的 seed 类从而控制数据填充的顺序。

<a name="writing-seeders"></a>
## 编写 Seeders

通过运行 [royalcms 命令](/docs/royalcms)  `make:seeder` 来生成 Seeder。由框架生成的 seeders 都将被放置在 `database/seeds` 目录下：

```
php royalcms make:seeder UsersTableSeeder
```

一个 seeder 类只包含一个默认方法：`run`。这个方法会在 [royalcms 命令](/docs/royalcms)  `db:seed` 被执行时调用。在 `run` 方法里你可以根据需要在数据库中插入数据。你也可以用 [查询构造器](/docs/queries) 或 [Eloquent 模型工厂](/docs/database-testing#writing-factories) 来手动插入数据。

> {primary} 在数据填充期间，[批量赋值保护](https://Royalcms.com/docs/5.5/eloquent#mass-assignment) 会自动禁用。

如下所示，在默认的 `DatabaseSeeder` 类中的 `run` 方法中添加一条数据插入语句：

```php
<?php

use Royalcms\Component\Database\Seeder;
use Royalcms\Component\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * 运行数据库填充
     *
     * @return void
     */
    public function run()
    {
        RC_DB::table('users')->insert([
            'name' => str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
```

<a name="using-model-factories"></a>
### 使用模型工厂

手动为每个模型填充指定属性很麻烦。作为替代方案，你可以使用 [模型工厂](/docs/database-testing#writing-factories) 来轻松地生成大量数据库数据。首先，阅读 [模型工厂文档](/docs/database-testing#writing-factories) 来学习如何使用工厂，然后就可以使用 `factory` 这个辅助函数来向数据库中插入数据。

例如，创建 50 个用户并为每个用户创建关联：

```php
/**
 * 运行数据库填充
 *
 * @return void
 */
public function run()
{
    factory(App\User::class, 50)->create()->each(function ($u) {
        $u->posts()->save(factory(App\Post::class)->make());
    });
}
```

<a name="calling-additional-seeders"></a>
### 调用其他 Seeders

在 `DatabaseSeeder` 类中，你可以使用 `call` 方法来运行其他的 seed 类。使用 `call` 方法可以将数据填充拆分成多个文件，这样就不会使单个 seeder 变得非常大。只需简单传递要运行的 seeder 类名称即可：

```php
/**
 * Run the database seeds.
 *
 * @return void
 */
public function run()
{
    $this->call([
        UsersTableSeeder::class,
        PostsTableSeeder::class,
        CommentsTableSeeder::class,
    ]);
}
```

<a name="running-seeders"></a>
## 运行 Seeders

完成 seeder 类的编写之后，你可能需要使用 `dump-autoload` 命令重新生成 Composer 的自动加载器：

```
composer dump-autoload
```

接着就可以使用 royalcms 命令 `db:seed` 来填充数据库了。默认情况下，`db:seed` 命令将运行 `DatabaseSeeder` 类，这个类可以用来调用其它 Seed 类。不过，你也可以使用 `--class` 选项来指定一个特定的 seeder 类：

```
php royalcms db:seed

php royalcms db:seed --class=UsersTableSeeder
```

你也可以使用 `migrate:refresh` 命令来填充数据库，该命令会回滚并重新运行所有迁移。这个命令可以用来重建数据库：

```
php royalcms migrate:refresh --seed
```
