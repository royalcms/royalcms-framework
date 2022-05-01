# Royalcms 的分页功能

- [简介](#introduction)
- [基本用法](#basic-usage)
    - [查询构造器分页](#paginating-query-builder-results)
    - [Eloquent 分页](#paginating-eloquent-results)
    - [手动创建分页](#manually-creating-a-paginator)
- [显示分页结果](#displaying-pagination-results)
    - [将结果转换为 JSON](#converting-results-to-json)
- [自定义分页视图](#customizing-the-pagination-view)
- [分页器实例方法](#paginator-instance-methods)

<a name="introduction"></a>
## 简介

在大多数的框架中，分页无不令人十分头疼。 Royalcms 的分页器与[查询构造器](/docs/queries)、[Eloquent ORM](/docs/eloquent) 集成在一起，并提供方便易用的数据结果集分页。分页器生成的 HTML 与 Bootstrap CSS 框架兼容。

<a name="basic-usage"></a>
## 基本用法

<a name="paginating-query-builder-results"></a>
### 查询构造器分页

有几种方法可以对数据进行分页。最简单的是在 [查询语句构造器](/docs/queries)  或 [Eloquent 查询](/docs/eloquent) 中使用 `paginate` 方法。 `paginate` 方法会自动根据用户正在查看的页面来设置限制和偏移量。默认情况下，当前页面通过 HTTP 请求所带的参数 `page` 的值来检测。这个值是被 Royalcms 自动检测到的，也会自动插入到由分页器生产的链接中。

在下面这个例子中，传递给 `paginate` 方法的唯一参数是「每页」显示的项目数量。下面是每页显示 `15` 条数据的例子：

```php
<?php

namespace App\Http\Controllers;

use Royalcms\Component\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * 展示应用中的所有用户
     *
     * @return Response
     */
    public function index()
    {
        $users = DB::table('users')->paginate(15);

        return view('user.index', ['users' => $users]);
    }
}
```

> {info} 目前，Royalcms 无法高效执行使用 `groupBy` 语句的分页操作。如果你需要在分页结果集中使用 `groupBy`，建议你查询数据库并手动创建分页器。

#### 「简单分页」

如果你只需要在分页视图中显示简单的「下一页」和「上一页」的链接，即不需要显示每个页码的链接，更推荐使用 `simplePaginate` 方法来执行更高效的查询：

```php
$users = RC_DB::table('users')->simplePaginate(15);
```

<a name="paginating-eloquent-results"></a>
### Eloquent 模型分页

你也可以对 [Eloquent](/docs/eloquent) 查询进行分页。下面的例子中对 `User` 模型进行了分页并且每页显示 `15` 条数据。正如你看到的，所使用的语法几乎与基于查询语句构造器分页时的完全相同：

```php
$users = App\User::paginate(15);
```

当然，你可以在设置了查询的其他约束条件之后调用 `paginate` 方法，例如 `where` 语句：

```php
$users = User::where('votes', '>', 100)->paginate(15);
```

你也可以在 `Eloquent` 模型中使用 `simplePaginate` 方法进行分页：

```php
$users = User::where('votes', '>', 100)->simplePaginate(15);
```

<a name="manually-creating-a-paginator"></a>
### 手动创建分页

如果你想手动创建分页实例并且最终得到一个数组类型的结果，可以根据需求来创建 `Royalcms\Component\Pagination\Paginator` 或者 `Royalcms\Component\Pagination\LengthAwarePaginator` 实例来实现。

`Paginator` 类不需要知道结果集中的数据量，因此该类没有检索最后一页索引的方法。而 `LengthAwarePaginator` 接收的参数几乎和 `Paginator` 一样，但它却需要计算结果集中的数据量。

说白了就是，`Paginator` 相当于查询语句构造器和 Eloquent 中的 `simplePaginate` 方法，而 `LengthAwarePaginator` 相当于 `paginate` 方法。

> {info} 手动创建分页器实例时，你应该手动「切割」传递给分页器的结果集数组。如果你不确定如何去做到这一点，请查阅 PHP 的 [array_slice](https://secure.php.net/manual/en/function.array-slice.php) 函数。

<a name="displaying-pagination-results"></a>
## 显示分页结果

在调用 `paginate` 方法时，你将会接收到一个 `Royalcms\Component\Pagination\LengthAwarePaginator` 实例。当调用 `simplePaginate` 方法时，你将会接收到一个 `Royalcms\Component\Pagination\Paginator` 实例。这些对象提供了一些用于渲染结果集的函数。除了这些辅助函数，分页器实例是一个迭代器，也可以作为数组循环。因此，一旦检测到结果集，你可以使用Blade模板显示结果集并渲染页面链接：

```html
<div class="container">
    @foreach ($users as $user)
        {{ $user->name }}
    @endforeach
</div>

{{ $users->links() }}
```

`links` 方法将会链接渲染到结果集中其余的页面。每个链接都包含了正确的 `page` 查询字符串变量。记住，`links` 方法生成的 HTML 与 [Bootstrap CSS 框架](https://getbootstrap.com) 兼容。

#### 自定义分页器的 URI

`withPath` 方法允许你在生成链接时自定义分页器所使用的 URI。例如，如果你想要分页器生成像 `http://example.com/custom/url?page=N` 这样的链接，那就传递 `custom/url` 到 `withPath` 方法：

```php
RC_Route::get('users', function () {
    $users = App\User::paginate(15);

    $users->withPath('custom/url');

    //
});
```

#### 附加参数到分页链接中

你可以使用 `appends` 方法将查询参数附加到分页链接中。例如，要将 `sort=votes` 附加到每个分页链接，可以这样调用 `appends` 方法：

```html
{{ $users->appends(['sort' => 'votes'])->links() }}
```

如果想将「哈希片段」附加到分页器的链接中，就使用 `fragment` 方法。例如，要将 `#foo` 附加到每个分页链接的末尾，应该这样调用 `fragment` 方法：

```html
{{ $users->fragment('foo')->links() }}
```

<a name="converting-results-to-json"></a>
### 将结果转换为 JSON

Royalcms 分页器结果类实现了 `Royalcms\Component\Contracts\Support\Jsonable` 接口契约并且提供 `toJson` 方法，因此将分页结果转换为 JSON 非常简单。你也可以在路由或控制器操作中简单地将分页实例转换为 JSON 返回：

```php
RC_Route::get('users', function () {
    return App\User::paginate();
});
```

分页器中获取的 JSON 将包含元信息，如：`total`、`current_page`、`last_page` 等等。实际的结果对象将通过 JSON 数组中的 `data` 键来获取。 以下是一个从路由返回的分页器实例创建的 JSON 示例：

```json
{
   "total": 50,
   "per_page": 15,
   "current_page": 1,
   "last_page": 4,
   "first_page_url": "http://Royalcms.app?page=1",
   "last_page_url": "http://Royalcms.app?page=4",
   "next_page_url": "http://Royalcms.app?page=2",
   "prev_page_url": null,
   "path": "http://Royalcms.app",
   "from": 1,
   "to": 15,
   "data":[
        {
            // Result Object
        },
        {
            // Result Object
        }
   ]
}
```

<a name="customizing-the-pagination-view"></a>
## 自定义分页视图

默认情况下，渲染分页链接的视图与 Bootstrap CSS 框架兼容。但是，如果你不使用 Bootstrap，可以自由定义视图来渲染这些链接。在分页器实例中调用 `links` 方法时，只需将视图名称作为第一个参数传递给方法：

```html
{{ $paginator->links('view.name') }}

// Passing data to the view...
{{ $paginator->links('view.name', ['foo' => 'bar']) }}
```

自定义分页视图的制作最简单的方法是使用 `vendor:publish` 命令将它们导出到 `resources/views/vendor` 目录：

```shell
php royalcms vendor:publish --tag=Royalcms-pagination
```

这个命令将这些视图放置在 `resources/views/vendor/pagination` 目录中。这个目录下的 `default.blade.php` 文件对应默认的分页视图，你可以简单地编辑这个文件来修改分页的 HTML 。

<a name="paginator-instance-methods"></a>
## 分页器实例方法

每个分页器实例可以通过以下方法获取额外的分页信息：

- `$results->count()`
- `$results->currentPage()`
- `$results->firstItem()`
- `$results->hasMorePages()`
- `$results->lastItem()`
- `$results->lastPage()` （使用 simplePaginate 时不可用）
- `$results->nextPageUrl()`
- `$results->perPage()`
- `$results->previousPageUrl()`
- `$results->total()` （使用 simplePaginate 时不可用）
- `$results->url($page)`
