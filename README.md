# Laravel JPush
基于 `jpush/jpush` `3.6.6` 版本(当前最新版本), 三分钟完成极光推送开发.

## Install
> composer require mitoop/laravel-jpush

## Require
- Laravel ^7.0

## Configure
在 `config/services`里增加极光配置 : 
```
    'jpush' => [
        // 极光 app kye 
        'app_key' => env('JPUSH_APP_KEY') 
        // 极光 master secret
        'master_secret' => env('JPUSH_MASTER_SECRET'), 
        // 仅对iOS有效 开发环境设置为false 生产环境设置为true
        'apns_production' => env('JPUSH_APNS_PRODUCTION', false), 
        // 接口请求日志文件 为 null 不记录日志
        'log_file' => env('JPUSH_LOG_FILE'), 
    ],
```

如果需要`Facade` 在 `config/app.php` `alias` 数组下增加 

` 'JPush' => Mitoop\JPush\JPushServiceFacade::class,`

## Use
推送可以采用同步或异步任意一种方式(推荐异步)

一下代码示例假设配置了 `JPush` alias

    
同步推送
```php
JPush::pushNow('别名', '通知', '附加信息');
JPush::pushNow(['别名数组'], '通知', '附加信息');
JPush::pushNow('all', '通知', '附加信息'); // 推送给所有人
```

异步推送
```php
JPush::push('别名', '通知', '附加信息')->queue();
JPush::push(['别名数组'], '通知', '附加信息')->queue();
JPush::push('all', '通知', '附加信息')->queue(); // 推送给所有人
```

## Tips
```
在极光后台查看推送历史的时候, 有个选择框, Web/Api，
通过包推送的是 `Api` 这种方式，但是默认值是 `Web`，查看的时候要切换一下
```
