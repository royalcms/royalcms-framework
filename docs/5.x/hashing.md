# Royalcms 的哈希加密

- [简介](#introduction)
- [基本用法](#basic-usage)

<a name="introduction"></a>
## 简介

Royalcms `Hash` Facade提供安全的 Bcrypt 哈希保存用户密码。 如果应用程序中使用了 Royalcms 内置的 `LoginController` 和 `RegisterController` 类，它们将自动使用 Bcrypt 进行注册和身份验证。

> {primary} Bcrypt 是哈希密码的理想选择，因为它的「加密系数」可以任意调整，这意味着生成哈希所需的时间可以随着硬件功率的增加而增加。

<a name="basic-usage"></a>
## 基本用法

你可以通过调用 `Hash` Facade 的 `make` 方法来填写密码：

```php
<?php

namespace App\Http\Controllers;

use Royalcms\Component\Http\Request;
use Royalcms\Component\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UpdatePasswordController extends Controller
{
    /**
     *  更新用户密码
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        // Validate the new password length...

        $request->user()->fill([
            'password' => Hash::make($request->newPassword)
        ])->save();
    }
}
```

`make` 方法还能使用 `rounds` 选项来管理 bcrypt 哈希算法的加密系数。然而，大多数应用程序还是能接受默认值的：

```php
$hashed = Hash::make('password', [
    'rounds' => 12
]);
```

#### 根据哈希值验证密码

`check` 方法可以验证给定的纯文本字符串对应于给定的散列。 如果使用 [Royalcms 内置的](/docs/authentication) `LoginController`，则不需要直接使用该方法，因为该控制器会自动调用此方法：

```php
if (Hash::check('plain-text', $hashedPassword)) {
    // 密码对比...
}
```

#### 检查密码是否需要重新加密

`needsRehash` 函数允许你检查已加密的密码所使用的加密系数是否被修改：

```php
if (Hash::needsRehash($hashed)) {
    $hashed = Hash::make('plain-text');
}
```
