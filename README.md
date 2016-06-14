# yii2-alidayu

## 配置组件

```php
'alidayu' => [
    'appKey' => 'xxx',
    'appSecret' => 'yyy',
],
```


## 发送短信

```php
/* @var \cdcchen\yii\alidayu\Client $client */
$client = Yii::$app->get('alidayu');
$result = $client->sendSms('手机号', '短信签名', '短信模板代码', '模板参数', '回传参数');
```

> **手机号码** 如果为多个，使用数组方式；  
> **模板参数** 具体根据模板要求；  
> **回传参数** 为可选参数，具体根据业务需求；

## 查询短信日志

```php
/* @var \cdcchen\yii\alidayu\Client $client */
$client = Yii::$app->get('alidayu');
$result = $client->querySms('186xxxxxxxx', '日期', '当前页码', '每页条数', '流水号');
```

> **日期** 格式：20160607；  
> **当前页码** 从·q`1`开始；  
> **每页条数** 默认`20`