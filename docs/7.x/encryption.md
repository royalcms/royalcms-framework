# Royalcms 的加密解密机制

- [简介](#introduction)
- [设置](#configuration)
- [使用](#using-the-encrypter)

<a name="introduction"></a>
## 简介

Royalcms 的加密机制使用 OpenSSL 提供 AES-256 和 AES-128 的加密。强烈建议你使用 Royalcms 内置的加密机制，而不是用其他的加密算法。所有 Royalcms 加密之后的结果都会使用消息认证码 (MAC) 去签名，使其底层值不能在加密后修改。

<a name="configuration"></a>
## 设置

在使用 Royalcms 的加密程序之前, 你必须先设置 `config/app.php` 配置文件中的 `key` 选项。运行 royalcms 命令 `php royalcms key:generate`，它会使用 PHP 的安全随机字节生成器来构建密钥。如果这个 key 值没有被正确设置，则所有由 Royalcms 加密的值都将是不安全的。

<a name="using-the-encrypter"></a>
## 使用

#### 加密一个值

你可以使用辅助函数 `encrypt` 来加密一个值。所有加密值都使用 OpenSSL 与 `AES-256-CBC` 来进行加密。此外，所有加密过的值都会使用消息认证码（MAC）进行签名，以检测加密字符串是否被篡改过：

```php
<?php

namespace App\Http\Controllers;

use App\User;
use Royalcms\Component\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * 存储用户保密信息
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function storeSecret(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->fill([
            'secret' => encrypt($request->secret)
        ])->save();
    }
}
```

#### 无序列化加密

加密值在加密期间通过 `serialize` 传递，这允许对象和数组的加密。因此，接收加密值的非PHP客户端将需要 `unserialize` 数据。如果想在不序列化的情况下加密和解密值，可以使用 `Crypt` Facade 的 `encryptString` 和 `decryptString` 方法：

```php
use Royalcms\Component\Support\Facades\Crypt;

$encrypted = Crypt::encryptString('Hello world.');

$decrypted = Crypt::decryptString($encrypted);
```

#### 解密一个值

你可以使用辅助函数 `decrypt` 来解密一个值。如果该值不能被正确解密，例如当 MAC 无效时，会抛出异常 `Royalcms\Component\Contracts\Encryption\DecryptException`：

```php
use Royalcms\Component\Contracts\Encryption\DecryptException;

try {
    $decrypted = decrypt($encryptedValue);
} catch (DecryptException $e) {
    //
}
```
