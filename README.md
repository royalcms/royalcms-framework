# royalcms-framework

> Royalcms框架是由一个具有多年行业开发经验的iOS工程师设计、研发的一套适用于模块化开发的网站平台系统，您可以瞬间完成一个模块，展示您的创意。五层架构封装，让每一层都可以独立扩展，不受影响。

**https://royalcms.cn**

## Royalcms开发框架功能亮点：

1. 100%开源，没有任何加密文件

   Royalcms核心文件100%开源，没有任何加密文件，开发者可以放心使用而无需担心留有后门程序等。

2. 功能组件化封装，每一个功能都是独立的

   核心组件功能独立，十分利于开发者迅速阅读掌握调用。

3. 代码严谨，结构清晰

   Royalcms使用MVC开发模式，各个功能模块之间独立并目录结构统一。开发者可迅速掌握Royalcms的框架结构。

4. 二次开发文档十分完善

   我们提供了完善的Royalcms二次开发文档，便于开发者学习与查阅。

5. 高度集成微信接口

   Royalcms高度集成了微信公共号的自动回复、菜单管理、素材管理、模板消息、粉丝管理、微信支付等常用接口，您只需一个函数或2/3行代码即可实现原本需要很费时费力才能开发的功能。

6. 高度封装常用开发功能

   只需一两行固定的代码，您便可以写出列表分页、微信支付、文件上传、邮件发送、短信发送等功能。

7. 数据结构合理，负载强劲

   Royalcms集成了常见的内存级缓存（Memcache、Redis）、文件缓存处理方案，使得系统更符合大数据、大并发的公共号或网站使用。

8. 集成应用市场，功能拓展一瞬间

   Royalcms集成了应用市场并在线安装应用的功能，您可以在一瞬间安装完成其他开发者开发的功能模块。

## Core Pakcages

| Name       | Packagist     | 
| :------------- | :----------: | 
|  Auth | [royalcms/auth](https://packagist.org/packages/royalcms/auth)   | 
|  Broadcasting  | [royalcms/broadcasting](https://packagist.org/packages/royalcms/broadcasting) | 
|  Bus | [royalcms/bus](https://packagist.org/packages/royalcms/bus)   | 
|  Cache | [royalcms/cache](https://packagist.org/packages/royalcms/cache)   | 
|  ClassLoader | [royalcms/class-loader](https://packagist.org/packages/royalcms/class-loader)   | 
|  Config | [royalcms/config](https://packagist.org/packages/royalcms/config)   | 
|  Console | [royalcms/console](https://packagist.org/packages/royalcms/console)   | 
|  Container | [royalcms/container](https://packagist.org/packages/royalcms/container)   | 
|  Contracts | [royalcms/contracts](https://packagist.org/packages/royalcms/contracts)   | 
|  Cookie | [royalcms/cookie](https://packagist.org/packages/royalcms/cookie)   | 
|  Database | [royalcms/database](https://packagist.org/packages/royalcms/database)   | 
|  Encryption | [royalcms/encryption](https://packagist.org/packages/royalcms/encryption)   | 
|  Events | [royalcms/events](https://packagist.org/packages/royalcms/events)   | 
|  Exception | [royalcms/exception](https://packagist.org/packages/royalcms/exception)   | 
|  Filesystem | [royalcms/filesystem](https://packagist.org/packages/royalcms/filesystem)   | 
|  Foundation | [royalcms/foundation](https://packagist.org/packages/royalcms/foundation)   | 
|  Hashing | [royalcms/hashing](https://packagist.org/packages/royalcms/hashing)   | 
|  Http | [royalcms/http](https://packagist.org/packages/royalcms/http)   | 
|  Log | [royalcms/log](https://packagist.org/packages/royalcms/log)   | 
|  Mail | [royalcms/mail](https://packagist.org/packages/royalcms/mail)   | 
|  Notifications | [royalcms/notifications](https://packagist.org/packages/royalcms/notifications)   | 
|  Pagination | [royalcms/pagination](https://packagist.org/packages/royalcms/pagination)   | 
|  Pipeline | [royalcms/pipeline](https://packagist.org/packages/royalcms/pipeline)   | 
|  Preloader | [royalcms/preloader](https://packagist.org/packages/royalcms/preloader)   | 
|  Queue | [royalcms/queue](https://packagist.org/packages/royalcms/queue)   | 
|  Redis | [royalcms/redis](https://packagist.org/packages/royalcms/redis)   | 
|  Routing | [royalcms/routing](https://packagist.org/packages/royalcms/routing)   | 
|  Session | [royalcms/session](https://packagist.org/packages/royalcms/session)   | 
|  Support | [royalcms/support](https://packagist.org/packages/royalcms/support)   | 
|  Translation | [royalcms/translation](https://packagist.org/packages/royalcms/translation)   | 
|  Validation | [royalcms/validation](https://packagist.org/packages/royalcms/validation)   | 
|  View | [royalcms/view](https://packagist.org/packages/royalcms/view)   | 


### Royalcms 目录结构：

	.                             根目录
	├── content                   功能模块目录
	│   ├── apps                  应用模块目录
	│   │   ├── achievement				
	│   │   ├── adsense
	│   │   ├── affiliate
	│   │   ├── api
	│   │   ├── article
	│   │   ├── bonus
	│   │   ├── captcha
	│   │   ├── cart
	│   │   ├── client
	│   │   ├── comment
	│   │   ├── connect
	│   │   ├── coupon
	│   │   ├── cycleimage
	│   │   ├── database
	│   │   ├── dscapi
	│   │   ├── favourable
	│   │   ├── feedback
	│   │   ├── gongyun
	│   │   ├── goods
	│   │   ├── groupbuy
	│   │   ├── installer
	│   │   ├── integrate
	│   │   ├── logviewer
	│   │   ├── mail
	│   │   ├── main
	│   │   ├── maintain
	│   │   ├── merchant
	│   │   ├── mobile
	│   │   ├── orders
	│   │   ├── payment
	│   │   ├── promotion
	│   │   ├── push
	│   │   ├── seller
	│   │   ├── setting
	│   │   ├── shipping
	│   │   ├── sms
	│   │   ├── topic
	│   │   ├── toutiao
	│   │   ├── user
	│   │   └── visual
	│   ├── bootstrap           启动入口文件
	│   │   ├── autoload.php
	│   │   ├── cache
	│   │   ├── classalias.php
	│   │   ├── classmap.php
	│   │   ├── console.php
	│   │   ├── kernel.php
	│   │   └── royalcms.php
	│   ├── configs             配置文件
	│   │   ├── api.php
	│   │   ├── bundles.php
	│   │   ├── cache.php
	│   │   ├── command
	│   │   ├── compile.php
	│   │   ├── console.php
	│   │   ├── cookie.php
	│   │   ├── coreservice.php
	│   │   ├── database.php
	│   │   ├── facade.php
	│   │   ├── filesystems.php
	│   │   ├── logging.php
	│   │   ├── mail.php
	│   │   ├── multisites.php
	│   │   ├── namespaces.php
	│   │   ├── packages
	│   │   ├── provider.php
	│   │   ├── queue.php
	│   │   ├── release.php
	│   │   ├── route.php
	│   │   ├── session.php
	│   │   ├── site.php
	│   │   ├── storage.php
	│   │   ├── system.php
	│   │   └── upload.php
	│   ├── database              数据迁移文件
	│   │   └── migrations
	│   ├── kernel                内核文件
	│   │   ├── Console
	│   │   ├── Exceptions
	│   │   └── Http
	│   ├── plugins               插件目录
	│   │   ├── captcha_royalcms
	│   │   ├── login_mobile
	│   │   ├── pay_alipay
	│   │   ├── pay_balance
	│   │   ├── pay_bank
	│   │   ├── pay_bill24
	│   │   ├── pay_cash
	│   │   ├── pay_cod
	│   │   ├── pay_koolyun
	│   │   ├── pay_koolyun_alipay
	│   │   ├── pay_koolyun_unionpay
	│   │   ├── pay_koolyun_upmp
	│   │   ├── pay_koolyun_wxpay
	│   │   ├── pay_upmp
	│   │   ├── pay_wxpay
	│   │   ├── pay_wxpay_wap
	│   │   ├── ship_cac
	│   │   ├── ship_ems
	│   │   ├── ship_post_express
	│   │   ├── ship_post_mail
	│   │   ├── ship_presswork
	│   │   ├── ship_sf_express
	│   │   ├── ship_sto_express
	│   │   ├── ship_yto
	│   │   ├── ship_yunda
	│   │   ├── ship_zto
	│   │   ├── sms_ihuyi
	│   │   ├── sms_ihuyi_global
	│   │   ├── sms_messagebird
	│   │   ├── ucenter
	│   │   └── ueditor
	│   ├── resources             资源目录
	│   │   └── components
	│   ├── routes                路由目录
	│   │   ├── bootstrap.php
	│   │   ├── command.php
	│   │   ├── global.php
	│   │   ├── local.php
	│   │   └── routes.php
	│   ├── system                管理后台目录
	│   │   ├── apis
	│   │   ├── classes
	│   │   ├── configs
	│   │   ├── database
	│   │   ├── functions
	│   │   ├── languages
	│   │   ├── model
	│   │   ├── smarty
	│   │   ├── statics
	│   │   └── templates
	│   ├── tests                 测试用例目录
	│   │   ├── ApiTest
	│   │   ├── Bootstrap.php
	│   │   ├── CreatesApplication.php
	│   │   ├── ExampleTest.php
	│   │   ├── Feature
	│   │   ├── FrameworkTest
	│   │   ├── TestCase.php
	│   │   └── Unit
	│   └── uploads               附件上传目录
	│       └── data
	├── sites                     多站点目录
	│   ├── admincp               管理后台入口
	│   │   └── index.php
	│   ├── api                   API站点
	│   │   ├── content
	│   │   ├── index.php
	│   │   └── notify
	│   ├── app                   APP站点
	│   │   ├── content
	│   │   └── index.php
	│   ├── cron                  计划任务站点
	│   │   ├── content
	│   │   ├── index.php			
	│   │   └── vendor
	│   └── testapi               API测试工具
	│       ├── content
	│       └── index.php
	├── vendor                    框架目录
	├── index.php                 入口文件
	├── phpunit.php               单元测试入口
	├── phpunit.xml               单元测试配置文件



**[Royalcms 5.x 版本文档看此](docs/5-x/index)**



## ROYALCMS 核心组件

Royalcms 核心组件最新版是对接的 `Laravel 7.x` 版本的，以下基于Laravel组件的包装是为了兼容Royalcms框架之前的所有资源。

- royalcms/auth（[laravel/auth](https://learnku.com/docs/laravel/7.x/authentication/7474)）
- royalcms/broadcasting（[laravel/broadcasting](https://learnku.com/docs/laravel/7.x/broadcasting/7481)）
- royalcms/bus（laravel/bus）
- royalcms/cache（[laravel/cache](https://learnku.com/docs/laravel/7.x/cache/7482)）
- royalcms/class-loader
- royalcms/config（[laravel/config](https://learnku.com/docs/laravel/7.x/configuration/7448)）
- royalcms/console（[laravel/console](https://learnku.com/docs/laravel/7.x/artisan/7480)）
- royalcms/contracts（[laravel/contracts](https://learnku.com/docs/laravel/7.x/contracts/7457)）
- royalcms/cookie（laravel/cookie）
- royalcms/database（[laravel/database](https://learnku.com/docs/laravel/7.x/database/7493)）
- royalcms/encryption（[laravel/encryption](https://learnku.com/docs/laravel/7.x/encryption/7477)）
- royalcms/events（[laravel/events](https://learnku.com/docs/laravel/7.x/events/7484)）
- royalcms/exception
- royalcms/filesystem（[laravel/filesystem](https://learnku.com/docs/laravel/7.x/filesystem/7485)）
- royalcms/foundation（laravel/foundation）
- royalcms/hashing（[laravel/hashing](https://learnku.com/docs/laravel/7.x/hashing/7478)）
- royalcms/http（[laravel/http](https://learnku.com/docs/laravel/7.x/requests/7462)）
- royalcms/log（[laravel/log](https://learnku.com/docs/laravel/7.x/logging/7469)）
- royalcms/mail（[laravel/mail](https://learnku.com/docs/laravel/7.x/mail/7488)）
- royalcms/notifications（[laravel/notifications](https://learnku.com/docs/laravel/7.x/notifications/7489)）
- royalcms/pagination（[laravel/pagination](https://learnku.com/docs/laravel/7.x/pagination/7495)）
- royalcms/pipeline（laravel/pipeline）
- royalcms/preloader
- royalcms/queue（[laravel/queue](https://learnku.com/docs/laravel/7.x/queues/7491)）
- royalcms/redis（[laravel/redis](https://learnku.com/docs/laravel/7.x/redis/7498)）
- royalcms/routing（[laravel/routing](https://learnku.com/docs/laravel/7.x/routing/7458)）
- royalcms/session（[laravel/session](https://learnku.com/docs/laravel/7.x/session/7466)）
- royalcms/support（[laravel/support](https://learnku.com/docs/laravel/7.x/providers/7455)）
- royalcms/translation（[laravel/translation](https://learnku.com/docs/laravel/7.x/localization/7471)）
- royalcms/validation（[laravel/validation](https://learnku.com/docs/laravel/7.x/validation/7467)）
- royalcms/view（[laravel/view](https://learnku.com/docs/laravel/7.x/blade/7470)）

## 相关组件
- [royalcms/metable](/docs/metable/index)
- [royalcms/enum](/docs/enum/index)
- [royalcms/live](/docs/live/index)
- royalcms/agent
- royalcms/aliyun
- royalcms/api
- royalcms/app
- royalcms/convert
- royalcms/datetime
- royalcms/default-route
- royalcms/directory-hasher
- royalcms/editor
- [royalcms/elasticsearch](/docs/elasticsearch/index)
- royalcms/enum
- royalcms/environment
- royalcms/error
- royalcms/excel
- royalcms/gettext
- royalcms/hook
- royalcms/http-request
- royalcms/ide-helper
- royalcms/image
- royalcms/image-editor
- royalcms/ip-address
- royalcms/kses
- royalcms/log-viewer
- royalcms/memcache
- royalcms/model
- royalcms/native-session
- royalcms/package
- royalcms/page
- [royalcms/pay](/docs/pay/index)
- [royalcms/pinyin](/docs/pinyin/index)
- royalcms/plugin
- royalcms/purifier
- royalcms/qrcode
- royalcms/reflection
- royalcms/rememberable
- royalcms/repository
- royalcms/requests
- royalcms/rewrite
- royalcms/script
- royalcms/sentry
- royalcms/service
- royalcms/smarty-view
- royalcms/sms
- royalcms/storage
- [royalcms/temporary-directory](/docs/temporary-directory/index)
- royalcms/theme
- royalcms/timer
- royalcms/upload
- [royalcms/uploader](/docs/uploader/index)
- royalcms/url
- royalcms/uuid
- royalcms/variable
- royalcms/widget
- royalcms/xml-response

