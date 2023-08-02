# Laravel JPush
基于 `jpush/jpush` `3.6.6` 版本(当前最新版本), 三分钟完成极光推送开发.

## Install
```shell
composer require mitoop/laravel-jpush
```

## Require
- Laravel ^9|^10

## Configure
在 `config/services`里增加极光配置 : 
```
    'jpush' => [
        // 极光 app kye 
        'app_key' => env('JPUSH_APP_KEY'), 
        // 极光 master secret
        'master_secret' => env('JPUSH_MASTER_SECRET'), 
        // 仅对iOS有效 开发环境设置为false 生产环境设置为true
        'apns_production' => env('JPUSH_APNS_PRODUCTION', false), 
        // 接口请求日志文件 为 null 不记录日志
        'log_file' => env('JPUSH_LOG_FILE'), 
    ],
```

## Use
推送可以采用同步或异步任意一种方式(推荐异步)

一下代码示例假设使用 `JPush` alias

    
同步推送
```php
JPush::pushNow('别名', '通知', '附加信息');
JPush::pushNow(['别名数组'], '通知', '附加信息');
JPush::pushNow('all', '通知', '附加信息'); // 推送给所有人
```

异步推送
```php
JPush::pushQueue('别名', '通知', '附加信息');
JPush::pushQueue(['别名数组'], '通知', '附加信息');
JPush::pushQueue('all', '通知', '附加信息'); // 推送给所有人
```

更多：
```
如果上面两种方式不能满足使用
尝试查看 `Mitoop\JPush\JPushService` 里面的方法可以组合链式调用
```

## Tips
```
在极光后台查看推送历史的时候, 有个选择框, Web/Api，
通过包推送的是 `Api` 这种方式，但是默认值是 `Web`，查看的时候要切换一下
```

## Links
[http://docs.jiguang.cn/jpush/guideline/intro/](http://docs.jiguang.cn/jpush/guideline/intro/)

[http://docs.jiguang.cn/jpush/server/push/rest_api_v3_push/](http://docs.jiguang.cn/jpush/server/push/rest_api_v3_push/)

## 
极光文档我觉得是推送服务商里写的最好的文档



