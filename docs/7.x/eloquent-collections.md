# Eloquent：集合

- [简介](#introduction)
- [可用的方法](#available-methods)
- [自定义集合](#custom-collections)

<a name="introduction"></a>
## 简介

Eloquent 返回的所有多结果集都是 `Royalcms\Component\Database\Eloquent\Collection` 对象的实例，

默认情况下 Eloquent 返回的都是一个 `Royalcms\Component\Database\Eloquent\Collection` 对象的实例，包括通过 `get` 方法检索或通过访问关联关系获取到的结果。Eloquent 的集合对象继承了 Royalcms 的 [集合基类](/docs/collections)，因此它自然也继承了数十种能优雅地处理 Eloquent 模型底层数组的方法。

当然，所有的集合都可以作为迭代器，可以就像简单的 PHP 数组一样来遍历它们：

```php
$users = App\User::where('active', 1)->get();

foreach ($users as $user) {
    echo $user->name;
}
```

然而，集合比数组更加强大，它通过更直观的接口暴露出可链式调用的 map/reduce 等操作。举个例子，我们要删除模型中所有未激活的并收集剩余用户的名字：

```php
$users = App\User::where('active', 1)->get();

$names = $users->reject(function ($user) {
    return $user->active === false;
})
->map(function ($user) {
    return $user->name;
});
```

> {info} 大多数 Eloquent 集合方法会返回新的 Eloquent 集合实例，但是 `pluck`, `keys`, `zip`, `collapse`, `flatten` 和 `flip` 方法除外，它们会返回 [集合基类](/docs/collections) 实例。同样，如果 `map` 操作返回的集合不包含任何 Eloquent 模型，那么它会被自动转换成集合基类。


<a name="available-methods"></a>
## 可用的方法

### 集合基类

所有 Eloquent 集合都继承了基础的 [Royalcms 集合](/docs/collections) 对象。因此，它们也继承了所有集合基类提供的强大的方法：

<style>
    #collection-method-list > p {
        column-count: 3; -moz-column-count: 3; -webkit-column-count: 3;
        column-gap: 2em; -moz-column-gap: 2em; -webkit-column-gap: 2em;
    }

    #collection-method-list a {
        display: block;
    }
</style>

<div id="collection-method-list" markdown="1">

[all](/docs/collections#method-all)
[average](/docs/collections#method-average)
[avg](/docs/collections#method-avg)
[chunk](/docs/collections#method-chunk)
[collapse](/docs/collections#method-collapse)
[combine](/docs/collections#method-combine)
[contains](/docs/collections#method-contains)
[containsStrict](/docs/collections#method-containsstrict)
[count](/docs/collections#method-count)
[diff](/docs/collections#method-diff)
[diffKeys](/docs/collections#method-diffkeys)
[each](/docs/collections#method-each)
[every](/docs/collections#method-every)
[except](/docs/collections#method-except)
[filter](/docs/collections#method-filter)
[first](/docs/collections#method-first)
[flatMap](/docs/collections#method-flatmap)
[flatten](/docs/collections#method-flatten)
[flip](/docs/collections#method-flip)
[forget](/docs/collections#method-forget)
[forPage](/docs/collections#method-forpage)
[get](/docs/collections#method-get)
[groupBy](/docs/collections#method-groupby)
[has](/docs/collections#method-has)
[implode](/docs/collections#method-implode)
[intersect](/docs/collections#method-intersect)
[isEmpty](/docs/collections#method-isempty)
[isNotEmpty](/docs/collections#method-isnotempty)
[keyBy](/docs/collections#method-keyby)
[keys](/docs/collections#method-keys)
[last](/docs/collections#method-last)
[map](/docs/collections#method-map)
[mapWithKeys](/docs/collections#method-mapwithkeys)
[max](/docs/collections#method-max)
[median](/docs/collections#method-median)
[merge](/docs/collections#method-merge)
[min](/docs/collections#method-min)
[mode](/docs/collections#method-mode)
[nth](/docs/collections#method-nth)
[only](/docs/collections#method-only)
[partition](/docs/collections#method-partition)
[pipe](/docs/collections#method-pipe)
[pluck](/docs/collections#method-pluck)
[pop](/docs/collections#method-pop)
[prepend](/docs/collections#method-prepend)
[pull](/docs/collections#method-pull)
[push](/docs/collections#method-push)
[put](/docs/collections#method-put)
[random](/docs/collections#method-random)
[reduce](/docs/collections#method-reduce)
[reject](/docs/collections#method-reject)
[reverse](/docs/collections#method-reverse)
[search](/docs/collections#method-search)
[shift](/docs/collections#method-shift)
[shuffle](/docs/collections#method-shuffle)
[slice](/docs/collections#method-slice)
[sort](/docs/collections#method-sort)
[sortBy](/docs/collections#method-sortby)
[sortByDesc](/docs/collections#method-sortbydesc)
[splice](/docs/collections#method-splice)
[split](/docs/collections#method-split)
[sum](/docs/collections#method-sum)
[take](/docs/collections#method-take)
[tap](/docs/collections#method-tap)
[toArray](/docs/collections#method-toarray)
[toJson](/docs/collections#method-tojson)
[transform](/docs/collections#method-transform)
[union](/docs/collections#method-union)
[unique](/docs/collections#method-unique)
[uniqueStrict](/docs/collections#method-uniquestrict)
[values](/docs/collections#method-values)
[when](/docs/collections#method-when)
[where](/docs/collections#method-where)
[whereStrict](/docs/collections#method-wherestrict)
[whereIn](/docs/collections#method-wherein)
[whereInStrict](/docs/collections#method-whereinstrict)
[whereNotIn](/docs/collections#method-wherenotin)
[whereNotInStrict](/docs/collections#method-wherenotinstrict)
[zip](/docs/collections#method-zip)

</div>

<a name="custom-collections"></a>
## 自定义集合


如果你需要在自己的扩展方法中使用自定义的 `Collection` 对象，可以在你自己的模型中重写 `newCollection` 方法：

```php
<?php

namespace App;

use App\CustomCollection;
use Royalcms\Component\Database\Eloquent\Model;

class User extends Model
{
    /**
     * 创建一个新的 Eloquent 集合实例对象。
     *
     * @param  array  $models
     * @return \Royalcms\Component\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new CustomCollection($models);
    }
}
```

一旦你定义了 `newCollection` 方法，任何时候都可以在 Eloquent 返回的模型的 `Collection` 实例中获取你的自定义集合实例。如果你想要在应用程序的每个模型中使用自定义集合，则应该在所有的模型继承的模型基类中重写 `newCollection` 方法。
