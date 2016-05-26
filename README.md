# WechatMpSDK
微信服务号SDK

安装: `composer require tianye/wechat-mp-sdk dev-master -vvv`

```
<?
  /**
    * 初始化服务号配置
    *
    * @param string $appid 
    * @param string $appsecret
    * @param string $originalid
    * @param string $apiurl
    */
Api::init($appid, $appsecret, $originalid, $apiurl);

//实例化 CardApi
$api = Api::factory('Card');

//调用CardApi 下 获取 颜色接口
$res = $api->getcolors();

if (false === $res) {
    //接口原始数据
    var_dump($api::getApiData());
    //接口返回错误信息
    var_dump($api->getError());
}
    //接口返回值
    var_dump($res);
```

*联系QQ:3217834*
