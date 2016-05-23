# WechatMpSDK
微信服务号SDK

```
<?
  /**
    *
    * @param string $appid 
    * @param string $appsecret
    * @param string $originalid
    * @param string $apiurl
    */
Api::init($appid, $appsecret, $originalid, $apiurl);

$api = Api::factory('Xxxx');
$res = $api->xxxx();
if (false === $res) {
    var_dump(Api::getError());
    var_dump($api->getError());
}
    var_dump($res);
```
