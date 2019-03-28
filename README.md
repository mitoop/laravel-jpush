# Laravel JPush
基于 `jpush/jpush` `3.6.3` 版本(当前最新版本), 三分钟完成极光推送开发.

## Install
> composer require mitoop/laravel-jpush

## Require
- Laravel 5.5+

## Configure
在 `config/services`里增加极光配置 : 
```
    'jpush' => [
        'app_key'         => env('JPUSH_APP_KEY') // 极光 app kye 
        'master_secret'   => env('JPUSH_MASTER_SECRET'), // 极光 master secret
        'apns_production' => env('JPUSH_APNS_PRODUCTION', false), // ios配置 开发版本 false 线上版本 true
        'log_file'        => env('JPUSH_LOG_FILE', storage_path('logs/jpush.log')), // 接口日志文件 为 null 不记录日志
    ],
```

如果需要`Facade` 在 `config/app.php` `alias` 数组下增加 

` 'JPush' => Mitoop\JPush\JPushServiceFacade::class,`

## Use
```
分为同步和异步两种推送方式(假设配置了 `JPush` alias)

同步推送
JPush::pushNow('别名', '通知', '附加信息');
JPush::pushNow(['别名数组'], '通知', '附加信息');
JPush::pushNow('all', '通知', '附加信息'); // 推送给所有人

异步推送
JPush::push('别名', '通知', '附加信息')->queue();
JPush::push(['别名数组'], '通知', '附加信息')->queue();
JPush::push('all', '通知', '附加信息')->queue(); // 推送给所有人

如果要自己组装推送信息 可以单独调用方法
JPush::setPlatform('all')->toAlias('别名')->notify('通知')->attachExtras('附加信息')->send();
JPush::setPlatform('all')->toAll()->notify('通知')->attachExtras('附加信息')->send();
JPush::setPlatform('all')->toTag('标签')->notify('通知')->attachExtras('附加信息')->send();

JPush::setPlatform('all')->toAlias('别名')->notify('通知')->attachExtras('附加信息')->queue();
JPush::setPlatform('all')->toAll()->notify('通知')->attachExtras('附加信息')->queue();
JPush::setPlatform('all')->toTag('标签')->notify('通知')->attachExtras('附加信息')->queue();

JPush 也可以直接调用极光 Payload 的方法 来完成更复杂的操作.
```
