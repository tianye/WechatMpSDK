# WechatMpSDK
微信服务号SDK
<p align="center">
<a href="https://travis-ci.org/tianye/wechat-mp-sdk"><img src="https://travis-ci.org/tianye/wechat-mp-sdk.svg?branch=master" alt="Build Status"></a>
<a href="https://packagist.org/packages/tianye/wechat-mp-sdk"><img src="https://poser.pugx.org/tianye/wechat-mp-sdk/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/tianye/wechat-mp-sdk"><img src="https://poser.pugx.org/tianye/wechat-mp-sdk/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://scrutinizer-ci.com/g/tianye/WechatMpSDK/build-status/master"><img src="https://scrutinizer-ci.com/g/tianye/WechatMpSDK/badges/build.png?b=master" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/tianye/WechatMpSDK/?branch=master"><img src="https://scrutinizer-ci.com/g/tianye/WechatMpSDK/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"></a>
<a href="https://scrutinizer-ci.com/g/tianye/WechatMpSDK/?branch=master"><img src="https://scrutinizer-ci.com/g/tianye/WechatMpSDK/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="https://packagist.org/packages/tianye/wechat-mp-sdk"><img src="https://poser.pugx.org/tianye/wechat-mp-sdk/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/tianye/wechat-mp-sdk"><img src="https://poser.pugx.org/tianye/wechat-mp-sdk/license" alt="License"></a>
</p>

安装: `composer require tianye/wechat-mp-sdk dev-master -vvv`

调用使用 实例: [WechatMpSDK-demo](https://github.com/tianye/WechatMpSDK-demo/tree/master/Application/Home/Controller)

### 0.0初始化服务号配置

```php
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
```



### 1.授权相关接口（AuthApi.php）

​	如果用户在微信客户端中访问第三方网页，公众号可以通过微信网页授权机制，来获取用户基本信息，进而实现业务逻辑。

###### 1.1  生成outh URL

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| url  |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Auth');
$ret   = $WxApi->url();  //无参数请求
```

接口返回值示例：

```php
string(223) "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx23732ac865d46fa2&redirect_uri=http%3A%2F%2Fwww.home-mpdemo.com%2Findex.php%2FHome%2FAuth%2Furl&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect"
```



###### 1.2  直接跳转

|   接口名称   | HTTP请求方式 |
| :------: | :------: |
| redirect |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Auth');
$ret   = $WxApi->redirect();  //无参数请求
```



###### 1.3  获取用户信息

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| getUser |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Auth');
if (!I('get.state', false, 'htmlspecialchars') || (!$code = I('get.code', false, 'htmlspecialchars')) && I('get.state', false, 'htmlspecialchars')) {
  	$redirect = $WxApi->redirect(); //直接跳转
}

$permission = $this->getAccessPermission($code); //通过 code 获取 openid 和 access_token
$ret = $WxApi->getUser($permission['openid'], $permission['access_token']);//获取用户信息
```

接口请求失败返回值：

```php
string(85) "错误码:41001, 错误信息:access_token missing, hints: [ req_id: RJ0HeA0325ns47 ]"
```



###### 1.4  获取已授权用户

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| user |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Auth');
if (!I('get.state', false, 'htmlspecialchars') || (!$code = I('get.code', false, 'htmlspecialchars')) && I('get.state', false, 'htmlspecialchars')) {
  	$redirect = $WxApi->redirect(); //直接跳转
}
$ret = $WxApi->user(); //获取授权用户信息
```

接口请求失败返回值：

```php
string(0) ""
```



###### 1.5  通过授权获取用户

|   接口名称    | HTTP请求方式 |
| :-------: | :------: |
| authorize |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Auth');
$ret   = $WxApi->authorize();
```



###### 1.6  检查 Access Token 是否有效

|        接口名称        | HTTP请求方式 |
| :----------------: | :------: |
| accessTokenIsValid |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Auth');
if (!I('get.state', false, 'htmlspecialchars') || (!$code = I('get.code', false, 'htmlspecialchars')) && I('get.state', false, 'htmlspecialchars')) {
  $redirect = $WxApi->redirect(); //直接跳转
}

$permission = $this->getAccessPermission($code); //通过 code 获取 openid 和 access_token
$ret = $WxApi->accessTokenIsValid($permission['openid'], $permission['access_token']); //获取用户信息
```



###### 1.7  刷新 access_token

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| refresh |   get    |

接口请求代码示例：

```php

```



###### 1.8  获取 access_token

​	注意：由于公众号的secret和获取到的access_token安全级别都非常高，必须只保存在服务器，不允许传给客户端。后续刷新access_token、通过access_token获取用户信息等步骤，也必须从服务器发起。

|        接口名称         | HTTP请求方式 |
| :-----------------: | :------: |
| getAccessPermission |   get    |

接口请求代码示例：

```php

```



### 2.卡券相关接口（CardApi.php）

###### 2.1  获取卡券颜色

|   接口名称    | HTTP请求方式 |
| :-------: | :------: |
| getcolors |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Card');
$ret = $WxApi->getcolors();
```

接口请求返回代码示例：

```php
array(14) {
  [0]=>
  array(2) {
    ["name"]=>
    string(8) "Color010"
    ["value"]=>
    string(7) "#63b359"
  }
  [1]=>
  array(2) {
    ["name"]=>
    string(8) "Color020"
    ["value"]=>
    string(7) "#2c9f67"
  }
  [2]=>
  array(2) {
    ["name"]=>
    string(8) "Color030"
    ["value"]=>
    string(7) "#509fc9"
  }
  [3]=>
  array(2) {
    ["name"]=>
    string(8) "Color040"
    ["value"]=>
    string(7) "#5885cf"
  }
  [4]=>
  array(2) {
    ["name"]=>
    string(8) "Color050"
    ["value"]=>
    string(7) "#9062c0"
  }
  [5]=>
  array(2) {
    ["name"]=>
    string(8) "Color060"
    ["value"]=>
    string(7) "#d09a45"
  }
  [6]=>
  array(2) {
    ["name"]=>
    string(8) "Color070"
    ["value"]=>
    string(7) "#e4b138"
  }
  [7]=>
  array(2) {
    ["name"]=>
    string(8) "Color080"
    ["value"]=>
    string(7) "#ee903c"
  }
  [8]=>
  array(2) {
    ["name"]=>
    string(8) "Color081"
    ["value"]=>
    string(7) "#f08500"
  }
  [9]=>
  array(2) {
    ["name"]=>
    string(8) "Color082"
    ["value"]=>
    string(7) "#a9d92d"
  }
  [10]=>
  array(2) {
    ["name"]=>
    string(8) "Color090"
    ["value"]=>
    string(7) "#dd6549"
  }
  [11]=>
  array(2) {
    ["name"]=>
    string(8) "Color100"
    ["value"]=>
    string(7) "#cc463d"
  }
  [12]=>
  array(2) {
    ["name"]=>
    string(8) "Color101"
    ["value"]=>
    string(7) "#cf3e36"
  }
  [13]=>
  array(2) {
    ["name"]=>
    string(8) "Color102"
    ["value"]=>
    string(7) "#5E6671"
  }
}

```

返回数据说明：

|  背景颜色名称  |   色值    |
| :------: | :-----: |
| Color010 | #63b359 |
| Color020 | #2c9f67 |
| Color030 | #509fc9 |
| Color040 | #5885cf |
| Color050 | #9062c0 |
| Color060 | #d09a45 |
| Color070 | #e4b138 |
| Color080 | #ee903c |
| Color081 | #f08500 |
| Color082 | #a9d92d |
| Color090 | #dd6549 |
| Color100 | #cc463d |
| Color101 | #cf3e36 |
| Color102 | #5E6671 |



###### 2.2  创建卡券

|  接口名称  | HTTP请求方式 |
| :----: | :------: |
| create |   post   |

接口请求代码示例：

```php
$type = 'groupon';

$base_info                  = [];
$base_info['logo_url']      = 'http://mmbiz.qpic.cn/mmbiz/2aJY6aCPatSeibYAyy7yct9zJXL9WsNVL4JdkTbBr184gNWS6nibcA75Hia9CqxicsqjYiaw2xuxYZiaibkmORS2oovdg/0';
$base_info['brand_name']    = '测试商户造梦空间';
$base_info['code_type']     = 'CODE_TYPE_QRCODE';
$base_info['title']         = '测试标题2';
$base_info['sub_title']     = '测试副标题';
$base_info['color']         = 'Color010';
$base_info['notice']        = '测试使用时请出示此券';
$base_info['service_phone'] = '15311931577';
$base_info['description']   = "测试不可与其他优惠同享\n如需团购券发票，请在消费时向商户提出\n店内均可使用，仅限堂食";

$base_info['date_info']                     = [];
$base_info['date_info']['type']             = 'DATE_TYPE_FIX_TERM';
$base_info['date_info']['fixed_term']       = 90; //表示自领取后多少天内有效，不支持填写0
$base_info['date_info']['fixed_begin_term'] = 0; //表示自领取后多少天开始生效，领取后当天生效填写0。

$base_info['sku']             = [];
$base_info['sku']['quantity'] = '500000'; //自定义code时设置库存为0

$base_info['get_limit']       = 1;
$base_info['use_custom_code'] = false; //自定义code时必须为true
// $base_info['get_custom_code_mode'] = "GET_CUSTOM_CODE_MODE_DEPOSIT";  //自定义code时设置
$base_info['bind_openid']          = false;
$base_info['can_share']            = true;
$base_info['can_give_friend']      = false;
$base_info['center_title']         = '顶部居中按钮';
$base_info['center_sub_title']     = '按钮下方的wording';
$base_info['center_url']           = 'http://www.qq.com';
$base_info['custom_url_name']      = '立即使用';
$base_info['custom_url']           = 'http://www.qq.com';
$base_info['custom_url_sub_title'] = '6个汉字tips';
$base_info['promotion_url_name']   = '更多优惠';
$base_info['promotion_url']        = 'http://www.qq.com';
$base_info['source']               = '造梦空间';

$especial                = [];
$especial['deal_detail'] = "deal_detail";

$WxApi = Api::factory('Card');

$ret = $WxApi->create($type, $base_info, $especial);
```

请求参数列表：

|         参数名          |  必填  |      类型      |                   示例值                    | 描述                                       |
| :------------------: | :--: | :----------: | :--------------------------------------: | :--------------------------------------- |
|       logo_url       |  是   | string(128)  | [http://mmbiz.qpic.cn/](http://mmbiz.qpic.cn/) | 卡券的商户logo，建议像素为300*300。                  |
|      brand_name      |  是   |  string（36）  |                   海底捞                    | 商户名字,字数上限为12个汉字。                         |
|      code_type       |  是   |  string(16)  |              CODE_TYPE_TEXT              | 码型："CODE_TYPE_TEXT"文本；"CODE_TYPE_BARCODE"一维码 "CODE_TYPE_QRCODE"二维码"CODE_TYPE_ONLY_QRCODE",二维码无code显示；"CODE_TYPE_ONLY_BARCODE",一维码无code显示；CODE_TYPE_NONE，不显示code和条形码类型 |
|        title         |  是   |  string（27）  |               双人套餐100元兑换券                | 卡券名，字数上限为9个汉字。(建议涵盖卡券属性、服务及金额)。          |
|      sub_title       |  是   |  String(54)  |                   副标题                    | 卡券副标题，字数上限为18个汉字                         |
|        color         |  是   |  string（16）  |                 Color010                 | 券颜色。按色彩规范标注填写Color010-Color100。详情见[获取颜色列表接口](http://mp.weixin.qq.com/wiki/19/39f3e3d4d9442ed77aa27257b38eda37.html#.E8.8E.B7.E5.8F.96.E9.A2.9C.E8.89.B2.E5.88.97.E8.A1.A8.E6.8E.A5.E5.8F.A3) |
|        notice        |  是   |  string（48）  |                  请出示二维码                  | 卡券使用提醒，字数上限为16个汉字。                       |
|    service_phone     |  否   |  string（24）  |                 40012234                 | 客服电话。                                    |
|     description      |  是   | string（3072） |                不可与其他优惠同享                 | 卡券使用说明，字数上限为1024个汉字。                     |
|      date_info       |  是   |    JSON结构    |                  见上述示例。                  | 使用日期，有效期的信息。                             |
|         type         |  是   |    string    | DATE_TYPE_FIX_TIME_RANGE 表示固定日期区间，DATE_TYPE_FIX_TERM表示固定时长（自领取后按天算。 | 使用时间的类型，旧文档采用的1和2依然生效。                   |
|   begin_timestamp    |  是   | unsigned int |                 14300000                 | type为DATE_TYPE_FIX_TIME_RANGE时专用，表示起用时间。从1970年1月1日00:00:00至起用时间的秒数，最终需转换为字符串形态传入。（东八区时间，单位为秒） |
|    end_timestamp     |  是   | unsigned int |                 15300000                 | 表示结束时间，建议设置为截止日期的23:59:59过期。（东八区时间，单位为秒） |
|      fixed_term      |  是   |     int      |                    15                    | type为DATE_TYPE_FIX_TERM时专用，表示自领取后多少天内有效，不支持填写0。 |
|   fixed_begin_term   |  是   |     int      |                    0                     | type为DATE_TYPE_FIX_TERM时专用，表示自领取后多少天开始生效，领取后当天生效填写0。（单位为天） |
|         sku          |  是   |    JSON结构    |                  见上述示例。                  | 商品信息。                                    |
|       quantity       |  是   |     int      |                  100000                  | 卡券库存的数量，上限为100000000。                    |
|      get_limit       |  否   |     int      |                    1                     | 每人可领券的数量限制,不填写默认为50。                     |
|   use_custom_code    |  否   |     bool     |                   true                   | 是否自定义Code码。填写true或false，默认为false。通常自有优惠码系统的开发者选择自定义Code码，并在卡券投放时带入Code码，详情见[是否自定义Code码](http://mp.weixin.qq.com/wiki/15/e33671f4ef511b77755142b37502928f.html#.E6.98.AF.E5.90.A6.E8.87.AA.E5.AE.9A.E4.B9.89Code.E7.A0.81)。 |
|     bind_openid      |  否   |     bool     |                   true                   | 是否指定用户领取，填写true或false。默认为false。通常指定特殊用户群体投放卡券或防止刷券时选择指定用户领取。 |
|      can_share       |  否   |     bool     |                  false                   | 卡券领取页面是否可分享。                             |
|   can_give_friend    |  否   |     bool     |                  false                   | 卡券是否可转赠。                                 |
|     center_title     |  否   |  string（18）  |                   立即使用                   | 卡券顶部居中的按钮，仅在卡券状态正常(可以核销)时显示              |
|   center_sub_title   |  否   |  string（24）  |                  立即享受优惠                  | 显示在入口下方的提示语，仅在卡券状态正常(可以核销)时显示。           |
|      center_url      |  否   | string（128）  |                www.qq.com                | 顶部居中的url，仅在卡券状态正常(可以核销)时显示。              |
|   custom_url_name    |  否   |  string（15）  |                   立即使用                   | 自定义跳转外链的入口名字。详情见[活用自定义入口](http://mp.weixin.qq.com/wiki/15/e33671f4ef511b77755142b37502928f.html#.E6.B4.BB.E7.94.A8.E8.87.AA.E5.AE.9A.E4.B9.89.E5.85.A5.E5.8F.A3) |
|      custom_url      |  否   | string（128）  |                www.qq.com                | 自定义跳转的URL。                               |
| custom_url_sub_title |  否   |  string（18）  |                   更多惊喜                   | 显示在入口右侧的提示语。                             |
|  promotion_url_name  |  否   |  string（15）  |                   产品介绍                   | 营销场景的自定义入口名称。                            |
|    promotion_url     |  否   | string（128）  |                www.qq.com                | 入口跳转外链的地址链接。                             |
|        source        |  否   |  string（36）  |                   大众点评                   | 第三方来源名，例如同程旅游、大众点评。                      |

创建成功返回示例：

```php
string(28) "pdkJ9uCzKWebwgNjxosee0ZuO3Os"
```

创建失败时返回示例：

```php
string(142) "错误码:41011, 错误信息:missing required fields! please check document and request json! hint: [DbUV3a0983ent1] m_data.base_info().title"
```



###### 2.3  创建二维码

|  接口名称  | HTTP请求方式 |
| :----: | :------: |
| qrcode |   post   |

接口请求代码示例：

```php
$card                                          = [];
$card['action_name']                           = 'QR_CARD';
$card['expire_seconds']                        = 1800;
$card['action_info']['card']['card_id']        = 'pdkJ9uGXfjw6Thm7iJakUSru5sqE';
$card['action_info']['card']['is_unique_code'] = false;
$card['action_info']['card']['outer_id']       = 1;

//领取多张卡券
$card_list                                              = [];
$card_list['action_name']                               = 'QR_MULTIPLE_CARD';
$card_list['action_info']['multiple_card']['card_list'] = [
  ['card_id' => 'pdkJ9uGXfjw6Thm7iJakUSru5sqE'],
  // ['card_id' => 'pdkJ9uItT7iUpBp4GjZp8Cae0Vig'],
  // ['card_id' => 'pdkJ9uFiwIfiNod7J6zqTI3zbiyU'],
  ];

$WxApi = Api::factory('Card');

$ret = $WxApi->qrcode($card_list);
```

请求参数列表：

|      参数名       |  必填  |      类型      |             示例值              | 描述                                       |
| :------------: | :--: | :----------: | :--------------------------: | :--------------------------------------- |
|  action_name   |  是   |    String    |           QR_SCENE           | 二维码类型，QR_SCENE为临时,QR_LIMIT_SCENE为永久,QR_LIMIT_STR_SCENE为永久的字符串参数值 |
| expire_seconds |  否   | unsigned int |              60              | 指定二维码的有效时间，范围是60 ~ 1800秒。不填默认为365天有效     |
|    card_id     |  否   |  string(32)  | pFS7Fjg8kV1IdDz01r4SQwMkuCKc | 卡券ID。                                    |
| is_unique_code |  否   |     bool     |            false             | 指定下发二维码，生成的二维码随机分配一个code，领取后不可再次扫描。填写true或false。默认false，注意填写该字段时，卡券须通过审核且库存不为0。 |
|    outer_id    |  否   |     int      |              12              | 领取场景值，用于领取渠道的数据统计，默认值为0，字段类型为整型，长度限制为60位数字。用户领取卡券后触发的[事件推送](http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025274&token=&lang=zh_CN)中会带上此自定义场景值。 |

请求成功返回值示例：

```php
array(4) {
  ["ticket"]=>
  string(96) "gQHa7joAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xLzdrUFlQMHJsV3Zvanc5a2NzV1N5AAIEJUVyVwMEAKd2AA=="
  ["expire_seconds"]=>
  int(7776000)
  ["url"]=>
  string(43) "http://weixin.qq.com/q/7kPYP0rlWvojw9kcsWSy"
  ["show_qrcode_url"]=>
  string(151) "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQHa7joAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xLzdrUFlQMHJsV3Zvanc5a2NzV1N5AAIEJUVyVwMEAKd2AA%3D%3D"
}
```

成功返回值列表说明：

|       参数名       | 描述                                       |
| :-------------: | :--------------------------------------- |
|     ticket      | 获取的二维码ticket，凭借此ticket调用[通过ticket换取二维码接口](http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542&token=&lang=zh_CN)可以在有效时间内换取二维码。 |
| expire_seconds  | 二维码的有效时间                                 |
|       url       | 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片        |
| show_qrcode_url | 二维码显示地址，点击后跳转二维码页面                       |

失败时返回示例：

```php
string(68) "错误码:40073, 错误信息:invalid card id hint: [rScPNA0536ent2]"
```



###### 2.4  ticket 换取二维码图片

|    接口名称    | HTTP请求方式 |
| :--------: | :------: |
| showqrcode |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Card');
$ticket = 'gQFF8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL01VTzN0T0hsS1BwUlBBYUszbVN5AAIEughxVwMEAKd2AA==';
$ret = $WxApi->showqrcode($ticket);
```

请求参数列表：

|   参数   |                  说明                  |
| :----: | :----------------------------------: |
| ticket | 获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。 |

返回一个二维码的图片。



###### 2.5  ticket 换取二维码链接

|      接口名称      | HTTP请求方式 |
| :------------: | :------: |
| showqrcode_url |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Card');
$ticket = 'gQFF8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL01VTzN0T0hsS1BwUlBBYUszbVN5AAIEughxVwMEAKd2AA==';
$ret = $WxApi->showqrcode_url($ticket);
```

请求参数列表：

|   参数   |                  说明                  |
| :----: | :----------------------------------: |
| ticket | 获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。 |

请求返回值示例：

```php
string(145) "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQFF8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL01VTzN0T0hsS1BwUlBBYUszbVN5AAIEughxVwMEAKd2AA"
```



###### 2.6  获取 卡券 Api_ticket

|     接口名称      | HTTP请求方式 |
| :-----------: | :------: |
| cardApiTicket |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory("Card");
$ret = $WxApi->cardApiTicket(true);
```

请求参数列表：

|  参数  |                  说明                  |
| :--: | :----------------------------------: |
| jus  | 获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。 |

请求返回值示例：

```php
string(86) "E0o2-at6NcC2OsJiQTlwlMzp8db8D6ZMid6Kw29Gy8v4n0lXbKq4N7fX6WaGG1jvlNr58WBUcfz5i8ghXuU_AQ"
```



###### 2.7  js-sdk 调用添加卡券接口 数组

|     接口名称      | HTTP请求方式 |
| :-----------: | :------: |
| wxCardPackage |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Card');
$card_list = [
  ['card_id' => 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY', 'outer_id' => 2],
  // ['card_id' => 'pdkJ9uJ37aU-tyRj4_grs8S45k1c', 'outer_id' => 3],
  // ['card_id' => 'pdkJ9uFiwIfiNod7J6zqTI3zbiyU', 'outer_id' => 4],
];
$ret = $WxApi->wxCardPackage($card_list);
```

请求参数列表：

|   参数名    |  必填  |     类型     |             示例值             | 描述                                       |
| :------: | :--: | :--------: | :-------------------------: | :--------------------------------------- |
| card_id  |  否   | string(32) | p1Pj9jr90_SQRaVqYI239Ka1erk | 卡券ID，用于拉起指定cardId的卡券列表，当cardId为空时，默认拉起所有卡券的列表，非必填。 |
| outer_id |  否   |  int(60)   |              6              | 领取场景值，用于领取渠道的数据统计，默认值为0，字段类型为整型，长度限制为60位数字。用户领取卡券后触发的事件推送中会带上此自定义场景值，不参与签名。 |

请求返回值示例：

```php
string(199) "{"cardList":[{"cardId":"pdkJ9uLRSbnB3UFEjZAgUxAJrjeY","cardExt":"{\"code\":\"\",\"openid\":\"\",\"timestamp\":1467109797,\"signature\":\"73ba424fc1aabfa62e752ee5fd8fee4610e2a610\",\"outer_id\":2}"}]}"
```

请求返回值列表：

|    变量名    | 说明                                       |
| :-------: | :--------------------------------------- |
|  card_id  | 卡券ID                                     |
|   code    | 指定的卡券code码，只能被领一次。use_custom_code字段为true的卡券必须填写，非自定义code不必填写。 |
|  openid   | 指定领取者的openid，只有该用户能领取。bind_openid字段为true的卡券必须填写，bind_openid字段为false不必填写。 |
| timestamp | 时间戳，商户生成从1970年1月1日00:00:00至今的秒数,即当前的时间,且最终需要转换为字符串形式;由商户生成后传入,不同添加请求的时间戳须动态生成，若重复将会导致领取失败！。 |
| signature | 签名，商户将接口列表中的参数按照指定方式进行签名,签名方式使用SHA1,具体签名方案参见下文;由商户按照规范签名后传入。 |
| outer_id  | 领取场景值，用于领取渠道的数据统计，默认值为0，字段类型为整型，长度限制为60位数字。用户领取卡券后触发的事件推送中会带上此自定义场景值，不参与签名。 |



###### 2.8  创建货架接口

|    接口名称     | HTTP请求方式 |
| :---------: | :------: |
| landingpage |   post   |

接口请求代码示例：

```php
$banner     = 'http://mmbiz.qpic.cn/mmbiz/iaL1LJM1mF9aRKPZJkmG8xXhiaHqkKSVMMWeN3hLut7X7hicFN';
$page_title = '惠城优惠大派送';
$can_share  = true;

//SCENE_NEAR_BY          附近
//SCENE_MENU             自定义菜单
//SCENE_QRCODE             二维码
//SCENE_ARTICLE             公众号文章
//SCENE_H5                 h5页面
//SCENE_IVR                 自动回复
//SCENE_CARD_CUSTOM_CELL 卡券自定义cell
$scene = 'SCENE_NEAR_BY';
$card_list = [
  ['card_id' => 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY', 'thumb_url' => 'http://test.digilinx.cn/wxApi/Uploads/test.png'],
  // ['card_id' => 'pdkJ9uJ37aU-tyRj4_grs8S45k1c', 'thumb_url' => 'http://test.digilinx.cn/wxApi/Uploads/aa.jpg'],
];

$WxApi = Api::factory('Card');
$ret = $WxApi->landingpage($banner, $page_title, $can_share, $scene, $card_list);
```

请求参数列表：

|     字段      | 说明                                       | 是否必填 |
| :---------: | ---------------------------------------- | :--: |
|   banner    | 页面的banner图片链接，须调用，建议尺寸为640*300。          |  是   |
| page_titlee | 页面的title。                                |  是   |
|  can_share  | 页面是否可以分享,填入true/false                    |  是   |
|    scene    | 投放页面的场景值；SCENE_NEAR_BY 附近 SCENE_MENU	自定义菜单 SCENE_QRCODE	二维码 SCENE_ARTICLE	公众号文章 SCENE_H5	h5页面 SCENE_IVR	自动回复 SCENE_CARD_CUSTOM_CELL	卡券自定义cell |  是   |
|  card_list  | 卡券列表，每个item有两个字段                         |  是   |
|   card_id   | 所要在页面投放的card_id                          |  是   |

成功返回数据示例：

```php
array(2) {
  ["url"]=>
  string(256) "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3be6367203f983ac&redirect_uri=https%3A%2F%2Fmp.weixin.qq.com%2Fbizmall%2Fcardlandingpage%3Fbiz%3DMzA5NTIxNjc1OA%3D%3D%26page_id%3D7%26scene%3D1&response_type=code&scope=snsapi_base#wechat_redirect"
  ["page_id"]=>
  int(7)
}
```

成功返回数据列表：

| 变量名     | 说明            |
| ------- | ------------- |
| url     | 货架链接。         |
| page_id | 货架ID。货架的唯一标识。 |

失败返回示例：

```php
string(68) "错误码:40073, 错误信息:invalid card id hint: [Pc._7a0871ent3]"
```



###### 2.9  导入code接口

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| deposit |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLCEF_HSKO7JdQOUcZ-PUzo';
$code    = ['11111', '22222', '33333'];

$WxApi = Api::factory('Card');
$ret = $WxApi->deposit($card_id, $code);
```

请求参数列表：

|   字段    | 说明                         | 是否必填 |
| :-----: | -------------------------- | :--: |
| card_id | 需要进行导入code的卡券ID。           |  是   |
|  code   | 需导入微信卡券后台的自定义code，上限为100个。 |  是   |

请求成功返回值示例：

```php
array(3) {
  ["succ_code"]=>
  array(0) {
  }
  ["duplicate_code"]=>
  array(3) {
    [0]=>
    string(5) "11111"
    [1]=>
    string(5) "22222"
    [2]=>
    string(5) "33333"
  }
  ["fail_code"]=>
  array(0) {
  }
}
```

请求成功返回值列表：

|       字段       | 说明               |
| :------------: | ---------------- |
|   succ_code    | 成功个数             |
| duplicate_code | 重复导入的code会自动被过滤。 |
|   fail_code    | 失败个数。            |

请求失败返回值示例：

```php
string(68) "错误码:40073, 错误信息:invalid card id hint: [wlqR7a0200ent3]"
```



###### 2.10  查询导入code数目

|      接口名称       | HTTP请求方式 |
| :-------------: | :------: |
| getdepositcount |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLCEF_HSKO7JdQOUcZ-PUzo';

$WxApi = Api::factory('Card');
$ret = $WxApi->getdepositcount($card_id);
```

请求参数列表：

|   字段    | 说明   | 是否必填 |
| :-----: | ---- | :--: |
| card_id | 卡券ID |  是   |

请求成功返回示例：

```php
int(12)
```

请求失败返回示例：

```php
string(68) "错误码:40073, 错误信息:invalid card id hint: [VkCjKa0425ent2]"
```



######2.11  核查code接口
|   接口名称    | HTTP请求方式 |
| :-------: | :------: |
| checkcode |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLCEF_HSKO7JdQOUcZ-PUzo';
$code    = ['807732265476', '22222', '33333'];

$WxApi = Api::factory('Card');
$ret = $WxApi->checkcode($card_id, $code);
```

请求参数列表：

|   字段    | 说明                         | 是否必填 |
| :-----: | -------------------------- | :--: |
| card_id | 卡券ID                       |  是   |
|  code   | 已经微信卡券后台的自定义code，上限为100个。s |  是   |

请求成功返回示例：

```php
array(2) {
  ["exist_code"]=>
  array(2) {
    [0]=>
    string(5) "22222"
    [1]=>
    string(5) "33333"
  }
  ["not_exist_code"]=>
  array(1) {
    [0]=>
    string(12) "807732265476"
  }
}
```
请求成功返回列表：
|       字段       | 说明           |
| :------------: | ------------ |
|   exist_code   | 已经成功存入的code。 |
| not_exist_code | 没有存入的code。   |

请求失败返回示例：

```php
string(68) "错误码:40073, 错误信息:invalid card id hint: [XD3LHA0836ent3]"
```



###### 2.12  图文消息群发卡券

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| gethtml |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY11';

$WxApi = Api::factory('Card');
$ret = $WxApi->gethtml($card_id);
```

请求参数列表：

|   字段    | 说明   | 是否必填 |
| :-----: | ---- | :--: |
| card_id | 卡券ID |  是   |

请求成功返回示例：

| 变量名     | 描述                                       |
| ------- | ---------------------------------------- |
| content | 返回一段html代码，可以直接嵌入到图文消息的正文里。即可以把这段代码嵌入到[上传图文消息素材接口](http://mp.weixin.qq.com/wiki/15/40b6865b893947b764e2de8e4a1fb55f.html#.E4.B8.8A.E4.BC.A0.E5.9B.BE.E6.96.87.E6.B6.88.E6.81.AF.E7.B4.A0.E6.9D.90.E3.80.90.E8.AE.A2.E9.98.85.E5.8F.B7.E4.B8.8E.E6.9C.8D.E5.8A.A1.E5.8F.B7.E8.AE.A4.E8.AF.81.E5.90.8E.E5.9D.87.E5.8F.AF.E7.94.A8.E3.80.91)中的content字段里。 |

请求失败返回示例：

```php
string(68) "错误码:40073, 错误信息:invalid card id hint: [dJj46a0155ent1]"
```



###### 2.13  设置测试白名单

​	同时支持“openid”、“username”两种字段设置白名单，总数上限为10个

​	设置测试白名单接口为全量设置，即测试名单发生变化时需调用该接口重新传入所有测试人员的ID。

​	白名单用户领取该卡券时将无视卡券失效状态，请开发者注意。

|     接口名称      | HTTP请求方式 |
| :-----------: | :------: |
| testwhitelist |   post   |

接口请求代码示例：

```php
$openid   = [];
$username = ['tianye0327'];

$WxApi = Api::factory('Card');
$ret = $WxApi->testwhitelist($openid, $username);$card_id = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY11';
```

请求参数列表：

|   参数名    |  必填  |     类型     |             示例值             | 描述           |
| :------: | :--: | :--------: | :-------------------------: | ------------ |
|  openid  |  否   | string(20) | o1Pj9jmZvwSyyyyyyBa4aULW2mA | 测试的openid列表。 |
| username |  否   | string(32) |            eddy             | 测试的微信号列表。    |

返回值示例：

```php
array(3) {
  ["white_list_size"]=>
  int(1)
  ["success_openid"]=>
  array(0) {
  }
  ["success_username"]=>
  array(1) {
    [0]=>
    string(10) "tianye0327"
  }
}
```
请求成功返回列表：

|       变量名        | 描述            |
| :--------------: | ------------- |
| white_list_size  | 白名单列表大小       |
|  success_openid  | 成功的测试openid列表 |
| success_username | 成功的测试微信号列表    |



######2.14  查询Code接口
|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| codeGet |   post   |

接口请求代码示例：

```php
$code          = '8077322654761';
$check_consume = true;
$card_id       = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY';

$WxApi = Api::factory('Card');
$ret = $WxApi->codeGet($code, $check_consume, $card_id);

```

请求参数列表：

|      参数名      |  必填  |     类型     |             示例值              | 描述                                       |
| :-----------: | :--: | :--------: | :--------------------------: | ---------------------------------------- |
|     code      |  是   | string(20) |         110201201245         | 单张卡券的唯一标准。                               |
|    card_id    |  是   | string(32) | pFS7Fjg8kV1IdDz01r4SQwMkuCKc | 卡券ID代表一类卡券。自定义code卡券必填。                  |
| check_consume |  否   |    bool    |             true             | 是否校验code核销状态，填入true和false时的code异常状态返回数据不同。 |

**注：当check_consume为true时返回数据**

请求成功返回示例：

```php
array(4) {
  ["card"]=>
  array(4) {
    ["card_id"]=>
    string(28) "pdkJ9uGXfjw6Thm7iJakUSru5sqE"
    ["begin_time"]=>
    int(1467043200)
    ["end_time"]=>
    int(1474819199)
    ["code"]=>
    string(12) "801586007419"
  }
  ["openid"]=>
  string(28) "odkJ9uDUz26RY-7DN1mxkznfo9xU"
  ["can_consume"]=>
  bool(true)
  ["user_card_status"]=>
  string(6) "NORMAL"
}
```

请求成功返回列表：

|       参数名        | 描述                                       |
| :--------------: | ---------------------------------------- |
|     card_id      | 卡券ID                                     |
|    begin_time    | 起始使用时间                                   |
|     end_time     | 结束时间                                     |
|       code       | 单张卡券的唯一标准                                |
|      openid      | 用户openid                                 |
|   can_consume    | 是否可以核销，true为可以核销，false为不可核销              |
| user_card_status | 当前code对应卡券的状态NORMAL          正常 CONSUMED     已核销 EXPIRE              已过期 GIFTING            转赠中GIFT_TIMEOUT  转赠超时 DELETE              已删除UNAVAILABLE   已失效 code未被添加或被转赠领取的情况则统一报错：invalid serial code |

请求失败返回示例：

```php
string(72) "错误码:40056, 错误信息:invalid serial code hint: [Na1y7a0585ent3]"
```



###### 2.15  核销Code接口

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| consume |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY';
$code    = '807732265476';

$WxApi = Api::factory('Card');
$ret = $WxApi->consume($code, $card_id);
```

请求参数列表：

| 参数名     | 必填   | 类型         | 示例值                          | 描述                                       |
| ------- | ---- | ---------- | ---------------------------- | ---------------------------------------- |
| card_id | 否    | string(32) | pFS7Fjg8kV1IdDz01r4SQwMkuCKc | 卡券ID。创建卡券时use_custom_code填写true时必填。非自定义Code不必填写。 |
| code    | 是    | string(20) | 1231231                      | 需核销的Code码。                               |

请求成功返回示例：

```php
array(2) {
  	["card"]=> array(1) { 
    	["card_id"]=> string(28) "pdkJ9uLRSbnB3UFEjZAgUxAJrjeY"
  	} 
  	["openid"]=> string(28) "odkJ9uDUz26RY-7DN1mxkznfo9xU"
}
```

请求成功返回列表：

|   变量名   | 描述               |
| :-----: | ---------------- |
| openid  | 用户在该公众号内的唯一身份标识。 |
| card_id | 卡券ID。            |

请求失败返回：

```php
string(90) "错误码:40099, 错误信息:invalid code, this code has consumed. hint: [W0HW.a0437ent2]"
```



###### 2.16  Code解码接口

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| decrypt |   post   |

接口请求代码示例：
```php
$encrypt_code = 'XXIzTtMqCxwOaawoE91+VJdsFmv7b8g0VZIZkqf4GWA60Fzpc8ksZ/5ZZ0DVkXdE';

$WxApi = Api::factory('Card');
$ret = $WxApi->decrypt($encrypt_code);
```

接口请求参数列表：

|     参数名      |  必填  |     类型      | 示例值                                      | 描述          |
| :----------: | :--: | :---------: | ---------------------------------------- | ----------- |
| encrypt_code |  是   | string(128) | XXIzTtMqCxwOaawoE91+ VJdsFmv7b8g0VZI Zkqf4GWA60Fzpc8ksZ/5ZZ0DVkXdE | 经过加密的Code码。 |

请求成功返回示例：
```php
string(12) "992718526867"  //解密后获取的真实Code码
```
请求失败返回示例：
```php
string(71) "错误码:40075, 错误信息:invalid encrypt code hint: [ohmq0908ent1]"
```



###### 2.17  获取用户已领取卡券接口

|    接口名称     | HTTP请求方式 |
| :---------: | :------: |
| getcardlist |   post   |

接口请求代码示例：

```php
$openid  = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY';
$card_id = ''; //卡券ID。不填写时默认查询当前appid下的卡券。

$WxApi = Api::factory('Card');
$ret = $WxApi->getcardlist($openid, $card_id);
```

接口请求参数列表：

|   参数名   |  必填  |     类型     |          示例值           | 描述                        |
| :-----: | :--: | :--------: | :--------------------: | ------------------------- |
| openid  |  是   | string(64) |        1231231         | 需要查询的用户openid             |
| card_id |  否   | string(32) | pFS7Fjg8kV1IdDz01xxxxx | 卡券ID。不填写时默认查询当前appid下的卡券。 |

请求成功返回示例：

```php
array(2) {
  ["card_list"]=>
  array(8) {
    [0]=>
    array(2) {
      ["card_id"]=>
      string(28) "pdkJ9uDgnm0pKfrTb1yV0dFMO_Gk"
      ["code"]=>
      string(12) "736052543512"
    }
    [1]=>
    array(2) {
      ["card_id"]=>
      string(28) "pdkJ9uDgnm0pKfrTb1yV0dFMO_Gk"
      ["code"]=>
      string(12) "774759815535"
    }
  }
  ["has_share_card"]=>
  bool(false)
}
```

请求成功返回列表：

|    参数名    | 描述        |
| :-------: | --------- |
| card_list | 卡券列表      |
|  card_id  | 卡券ID      |
|   code    | 单张卡券的唯一标准 |

请求失败返回示例：

```php
string(67) "错误码:40003, 错误信息:invalid openid hint: [.TpvQA0500ent3]"
```



###### 2.18  查看卡券详情

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| cardGet |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY';

$WxApi = Api::factory('Card');
$ret = $WxApi->cardGet($card_id);
```

接口请求参数列表：

|   参数名   |  必填  |     类型     |          示例值           | 描述                        |
| :-----: | :--: | :--------: | :--------------------: | ------------------------- |
| card_id |  否   | string(32) | pFS7Fjg8kV1IdDz01xxxxx | 卡券ID。不填写时默认查询当前appid下的卡券。 |

请求成功返回示例：

```php
array(2) {
  ["card_type"]=>
  string(7) "GROUPON"
  ["groupon"]=>
  array(3) {
    ["base_info"]=>
    array(31) {
      ["id"]=>
      string(28) "pdkJ9uLRSbnB3UFEjZAgUxAJrjeY"
      ["logo_url"]=>
      string(122) "http://mmbiz.qpic.cn/mmbiz/2aJY6aCPatSeibYAyy7yct9zJXL9WsNVL4JdkTbBr184gNWS6nibcA75Hia9CqxicsqjYiaw2xuxYZiaibkmORS2oovdg/0"
      ["code_type"]=>
      string(16) "CODE_TYPE_QRCODE"
      ["brand_name"]=>
      string(24) "测试商户造梦空间"
      ["title"]=>
      string(12) "测试标题"
      ["sub_title"]=>
      string(15) "测试副标题"
      ["date_info"]=>
      array(3) {
        ["type"]=>
        string(18) "DATE_TYPE_FIX_TERM"
        ["fixed_term"]=>
        int(90)
        ["fixed_begin_term"]=>
        int(0)
      }
      ["color"]=>
      string(7) "#63b359"
      ["notice"]=>
      string(30) "测试使用时请出示此券"
      ["service_phone"]=>
      string(11) "15311931577"
      ["description"]=>
      string(122) "测试不可与其他优惠同享
如需团购券发票，请在消费时向商户提出
店内均可使用，仅限堂食"
      ["source"]=>
      string(12) "造梦空间"
      ["location_id_list"]=>
      array(0) {
      }
      ["get_limit"]=>
      int(1)
      ["can_share"]=>
      bool(true)
      ["can_give_friend"]=>
      bool(false)
      ["use_custom_code"]=>
      bool(false)
      ["bind_openid"]=>
      bool(false)
      ["status"]=>
      string(21) "CARD_STATUS_VERIFY_OK"
      ["sku"]=>
      array(2) {
        ["quantity"]=>
        int(499999)
        ["total_quantity"]=>
        int(500000)
      }
      ["create_time"]=>
      int(1467025553)
      ["update_time"]=>
      int(1467025563)
      ["custom_url_name"]=>
      string(12) "立即使用"
      ["custom_url"]=>
      string(17) "http://www.qq.com"
      ["custom_url_sub_title"]=>
      string(14) "6个汉字tips"
      ["promotion_url"]=>
      string(17) "http://www.qq.com"
      ["promotion_url_name"]=>
      string(12) "更多优惠"
      ["center_title"]=>
      string(18) "顶部居中按钮"
      ["center_sub_title"]=>
      string(22) "按钮下方的wording"
      ["center_url"]=>
      string(17) "http://www.qq.com"
      ["area_code_list"]=>
      array(0) {
      }
    }
    ["deal_detail"]=>
    string(11) "deal_detail"
    ["advanced_info"]=>
    array(5) {
      ["time_limit"]=>
      array(0) {
      }
      ["text_image_list"]=>
      array(0) {
      }
      ["business_service"]=>
      array(0) {
      }
      ["consume_share_card_list"]=>
      array(0) {
      }
      ["share_friends"]=>
      bool(false)
    }
  }
}
```

请求成功返回列表：

|        参数名        | 描述                                       |
| :---------------: | ---------------------------------------- |
|     card_type     | 卡券类型。团购券：GROUPON; 折扣券：DISCOUNT; 礼品券：GIFT; 代金券：CASH; 通用券：GENERAL_COUPON; 会员卡：MEMBER_CARD; 景点门票：SCENIC_TICKET；电影票：MOVIE_TICKET； 飞机票：BOARDING_PASS； 会议门票：MEETING_TICKET； 汽车票：BUS_TICKET; |
|     base_info     | 基本的卡券数据，见下表，所有卡券通用。                      |
|    deal_detail    | 团购券专用字段，团购详情。                            |
|       gift        | 礼品券专用，表示礼品名字。                            |
|    least_cost     | least_cost字段为代金券专用，表示起用金额（单位为分）。         |
|    reduce_cost    | 代金券专用，表示减免金额（单位为分）                       |
|     discount      | 折扣券专用字段，表示打折额度（百分比），例：填30为七折团购详情。        |
|  supply_balance   | 会员卡专属字段，表示是否支持积分，填写true或false，如填写true，积分相关字段均为必填，会员卡专用。 |
|   supply_bonus    | 会员卡专属字段，表示否支持储值，填写true或false，如填写true，储值相关字段均为必填，会员卡专用。 |
|   bonus_cleared   | 积分清零规则，会员卡专用。                            |
|    bonus_rules    | 积分规则，会员卡专用。                              |
|   balance_rules   | 储值规则，会员卡专用。                              |
|    prerogative    | 会员卡专属字段，表示特权说明，会员卡专用。                    |
| bind_old_card_url | 绑定旧卡的url，会员卡专用。                          |
|   activate_url    | 激活会员卡，会员卡专用。                             |
| need_push_on_view | 进入会员卡时是否推送事件，填写true或false，会员卡专用。         |
|       from        | 飞机票的起点，上限为18个汉字，机票专用。                    |
|        to         | 飞机票的终点，上限为18个汉字，机票专用。                    |
|      flight       | 航班，机票专用。                                 |
|  departure_time   | 起飞时间，机票专用。（Unix时间戳格式）                    |
|   landing_time    | 降落时间，机票专用。（Unix时间戳格式）                    |
|   check_in_url    | 在线值机的链接，机票专用。                            |
|       gate        | 登机口。如发生登机口变更，建议商家实时调用该接口变更，机票专用。         |
|   boarding_time   | 登机时间，只显示“时分”不显示日期，机票专用。（Unix时间戳格式）       |
|  meeting_detail   | 会议详情，会议门票专用。                             |
|      map_url      | 会场导览图，会议门票专用。                            |

**base_info字段：**

|           参数名           | 描述                                       |
| :---------------------: | :--------------------------------------- |
|        logo_url         | 卡券的商户logo，建议像素为300*300。                  |
|        code_type        | "CODE_TYPE_TEXT"，文本；"CODE_TYPE_BARCODE"，一维码 ；"CODE_TYPE_QRCODE"，二维码；"CODE_TYPE_ONLY_QRCODE",二维码无code显示；"CODE_TYPE_ONLY_BARCODE",一维码无code显示； |
|       brand_name        | 商户名字（填写直接提供服务的商户名，第三方商户名填写在source字段）。    |
|          title          | 卡券名。                                     |
|          color          | 卡券的背景颜色。                                 |
|         notice          | 使用提醒，字数上限为16个汉字。                         |
|       description       | 使用说明。长文本描述。                              |
|        date_info        | 使用日期，有效期的信息。                             |
|          type           | 使用时间的类型DATE_TYPE_FIX_TIME_RANGE 表示固定日期区间，DATE_TYPE_FIX_TERM表示固定时长（自领取后按天算），DATE_TYPE_PERMANENT 表示永久有效（会员卡类型专用）。 |
|     begin_timestamp     | type为DATE_TYPE_FIX_TIME_RANGE时专用，表示起用时间。从1970年1月1日00:00:00至起用时间的秒数，最终需转换为字符串形态传入，下同。（单位为秒） |
|      end_timestamp      | type为DATE_TYPE_FIX_TIME_RANGE时专用，表示结束时间。（单位为秒） |
|       fixed_term        | type为DATE_TYPE_FIX_TERM时专用，表示自领取后多少天内有效，领取后当天有效填写0。（单位为天） |
|    fixed_begin_term     | type为DATE_TYPE_FIX_TERM时专用，表示自领取后多少天开始生效。（单位为天） |
|           sku           | 商品信息                                     |
|        quantity         | 卡券现有库存的数量                                |
|     total_quantity      | 卡券全部库存的数量，上限为100000000。                  |
|    location_id_list     | 门店位置ID。                                  |
|    use_all_locations    | 支持全部门店，填写true或false，与location_id_list互斥  |
|     use_custom_code     | 是否自定义Code码。填写true或false，默认为false。        |
|       bind_openid       | 是否指定用户领取，填写true或false。默认为否。              |
|        can_share        | 卡券是否可转赠，填写true或false,true代表可转赠默认为true。   |
|      service_phone      | 客服电话。                                    |
|         source          | 第三方来源名，例如同程旅游、大众点评。                      |
|     custom_url_name     | 商户自定义入口名称。                               |
|       custom_url        | 商户自定义入口跳转外链的地址链接,跳转页面内容需与自定义cell名称保持匹配。  |
|  custom_url_sub_title   | 显示在入口右侧的tips，长度限制在6个汉字内。                 |
|   promotion_url_name    | 营销场景的自定义入口。                              |
|      promotion_url      | 入口跳转外链的地址链接。                             |
| promotion_url_sub_title | 显示在营销入口右侧的提示语。                           |
|     custom_url_name     | 商户自定义入口名称。                               |
|         status          | “CARD_STATUS_NOT_VERIFY”,待审核；“CARD_STATUS_VERIFY_FAIL”,审核失败；“CARD_STATUS_VERIFY_OK”，通过审核；“CARD_STATUS_USER_DELETE”，卡券被商户删除；“CARD_STATUS_DISPATCH”，在公众平台投放过的卡券； |

**1.对于部分有特殊权限的商家，查询卡券详情得到的返回可能含特殊接口的字段。

**2.由于卡券字段会持续更新，实际返回字段包含但不限于文档中的字段，建议开发者开发时对于不理解的字段不做处理，以免出错。

请求失败返回示例：

```php
string(66) "错误码:40073, 错误信息:invalid card id hint: [iAoZ0925ent3]"
```



###### 2.19  批量查询卡列表

|   接口名称   | HTTP请求方式 |
| :------: | :------: |
| batchget |   post   |

接口请求代码示例：

```php
$offset      = 0;
$count       = 5;
$status_list = 'CARD_STATUS_VERIFY_OK';

$WxApi = Api::factory('Card');
$ret = $WxApi->batchget($offset, $count, $status_list);
```

接口请求参数列表：

| 参数名         | 必填   | 类型   | 示例值                   | 描述                                       |
| ----------- | ---- | ---- | --------------------- | ---------------------------------------- |
| offset      | 是    | int  | 0                     | 查询卡列表的起始偏移量，从0开始，即offset: 5是指从从列表里的第六个开始读取。 |
| count       | 是    | int  | 10                    | 需要查询的卡片的数量（数量最大50）。                      |
| status_list | 否    | int  | CARD_STATUS_VERIFY_OK | 支持开发者拉出指定状态的卡券列表“CARD_STATUS_NOT_VERIFY”,待审核；“CARD_STATUS_VERIFY_FAIL”,审核失败；“CARD_STATUS_VERIFY_OK”，通过审核；“CARD_STATUS_USER_DELETE”，卡券被商户删除；“CARD_STATUS_DISPATCH”，在公众平台投放过的卡券； |

接口返回示例：

```php
array(3) {
  ["card_id_list"]=>
  array(5) {
    [0]=>
    string(28) "pdkJ9uCAqQedZpqIEIucVDD5DYrQ"
    [1]=>
    string(28) "pdkJ9uDI9jmoPr3g_jP0SBJ29XzE"
    [2]=>
    string(28) "pdkJ9uFYOiKy6RzFt7WmKE1rbXoA"
    [3]=>
    string(28) "pdkJ9uNqoc5Edu0kthl1dAlsF3tY"
    [4]=>
    string(28) "pdkJ9uFO0_ewQpG7p4N3Tt-PDhj4"
  }
  ["total_num"]=>
  int(57)
  ["card_list"]=>
  array(0) {
  }
}
```

接口返回列表：

|     参数名      | 描述           |
| :----------: | ------------ |
| card_id_list | 卡券ID列表。      |
|  total_num   | 该商户名下卡券ID总数。 |
|  card_list   | *未知的返回值*     |



###### 2.20  更改卡券信息接口

|  接口名称  | HTTP请求方式 |
| :----: | :------: |
| update |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uCzKWebwgNjxosee0ZuO3Os';

$type = 'groupon';

$base_info                         = [];
$base_info['logo_url']             = 'http://mmbiz.qpic.cn/mmbiz/2aJY6aCPatSeibYAyy7yct9zJXL9WsNVL4JdkTbBr184gNWS6nibcA75Hia9CqxicsqjYiaw2xuxYZiaibkmORS2oovdg/0';
$base_info['center_title']         = '顶部居中按钮';
$base_info['center_sub_title']     = '按钮下方的wording';
$base_info['center_url']           = 'http://www.baidu.com';
$base_info['custom_url_name']      = '立即使用';
$base_info['custom_url']           = 'http://www.qq.com';
$base_info['custom_url_sub_title'] = '6个汉字tips';
$base_info['promotion_url_name']   = '更多优惠';
$base_info['promotion_url']        = 'http://www.qq.com';

$WxApi = Api::factory('Card');
$ret = $WxApi->update($card_id, $type, $base_info);
```

接口请求参数列表：

**通用字段修改：**

| 参数名                     | 是否提审 | 类型           | 示例值                                     | 描述                                       |
| ----------------------- | ---- | ------------ | --------------------------------------- | ---------------------------------------- |
| base_info               | -    | JSON接口       | 见上述示例                                   | 卡券基础信息字段。                                |
| logo_url                | 是    | string(128)  | [mmbiz.qpic.cn/](http://mmbiz.qpic.cn/) | 卡券的商户logo，建议像素为300*300。                  |
| notice                  | 是    | string（48）   | 请出示二维码核销卡券。                             | 使用提醒，字数上限为16个汉字。                         |
| description             | 是    | string（3072） | 不可与其他优惠同享                               | 使用说明。                                    |
| service_phone           | 否    | string（24）   | 40012234                                | 客服电话。                                    |
| color                   | 是    | string（3072） | Color010                                | 卡券颜色。                                    |
| location_id_list        | 否    | string（3072） | 1234,2314                               | 支持更新适用门店列表。                              |
| center_title            | 否    | string（18）   | 快速使用                                    | 顶部居中的自定义cell。                            |
| center_sub_title        | 否    | string（24）   | 点击快速核销卡券                                | 顶部居中的自定义cell说明。                          |
| center_url              | 否    | string（128）  | www.xxx.com                             | 顶部居中的自定义cell的跳转链接。                       |
| location_id_list        | 否    | string（3072） | 1234,2314                               | 支持更新适用门店列表，清空门店更新时传“0”                   |
| custom_url_name         | 否    | string（16）   | 立即使用                                    | 自定义跳转入口的名字。                              |
| custom_url              | 否    | string（128）  | "xxxx.com"。                             | 自定义跳转的URL。                               |
| custom_url_sub_title    | 否    | string（18）   | 更多惊喜                                    | 显示在入口右侧的提示语。                             |
| promotion_url_name      | 否    | string（16）   | 产品介绍。                                   | 营销场景的自定义入口名称。                            |
| promotion_url           | 否    | string（128）  | XXXX.com；                               | 入口跳转外链的地址链接。                             |
| promotion_url_sub_title | 否    | string（18）   | 卖场大优惠。                                  | 显示在营销入口右侧的提示语。                           |
| code_type               | 否    | string（16）   | CODE_TYPE_TEXT                          | Code码展示类型，"CODE_TYPE_TEXT"文本；"CODE_TYPE_BARCODE"，一维码 ；"CODE_TYPE_QRCODE"，二位码；"CODE_TYPE_ONLY_QRCODE",二维码无code显示；"CODE_TYPE_ONLY_BARCODE",一维码无code显示； |
| get_limit               | 否    | int          | 1                                       | 每人可领券的数量限制。                              |
| can_share               | 否    | bool         | false                                   | 卡券原生领取页面是否可分享。                           |
| can_give_friend         | 否    | bool         | false                                   | 卡券是否可转赠。                                 |
| date_info               | 否    | Json结构       | 见上述示例                                   | 使用日期，有效期的信息，有效期时间修改仅支持有效区间的扩大。           |
| type                    | 否    | string       | DATE_TYPE_FIX_TIME_RANGE                | 有效期类型，仅支持更改type为DATE_TYPE_FIX_TIME_RANGE 的时间戳，不支持填入DATE_TYPE_FIX_TERM。 |
| begin_timestamp         | 否    | unsigned int | 14300000                                | 固定日期区间专用，表示起用时间。（单位为秒）                   |
| end_timestamp           | 否    | unsigned int | 15300000                                | 固定日期区间专用，表示结束时间。结束时间仅支持往后延长。             |

**不同类型卡券专属字段修改：**  （特别注意，以下支持更新的字段不在基本信息**base_info**的结构中。）

| 参数名            | 是否提审 | 类型           | 示例值                                      | 描述                                       |
| -------------- | ---- | ------------ | ---------------------------------------- | ---------------------------------------- |
| bonus_cleared  | 是    | string(3072) | 每年12月30号积分清0。                            | 积分清零规则，会员卡专用。                            |
| bonus_rules    | 是    | string(3072) | 每消费1元增加1积分。                              | 积分规则，会员卡专用。                              |
| balance_rules  | 是    | string(3072) | 支持在线充入余额。                                | 储值说明，会员卡专用。                              |
| prerogative    | 是    | string(3072) | XX会员可享有全场商品8折优惠。                         | 特权说明，会员卡专用。                              |
| custom_field1  | 否    | JSON结构       | 见[创建会员卡示例](http://mp.weixin.qq.com/wiki/15/de148cc4b5190c80002eaf4f6f26c717.html#.E6.AD.A5.E9.AA.A4.E4.B8.80.EF.BC.9A.E5.88.9B.E5.BB.BA.E4.BC.9A.E5.91.98.E5.8D.A1)。 | 自定义会员信息类目，会员卡激活后显示，会员卡专用。                |
| custom_field2  | 否    | JSON结构       | 见[创建会员卡示例](http://mp.weixin.qq.com/wiki/15/de148cc4b5190c80002eaf4f6f26c717.html#.E6.AD.A5.E9.AA.A4.E4.B8.80.EF.BC.9A.E5.88.9B.E5.BB.BA.E4.BC.9A.E5.91.98.E5.8D.A1)。 | 自定义会员信息类目，会员卡激活后显示，会员卡专用。                |
| custom_field3  | 否    | JSON结构       | 见[创建会员卡示例](http://mp.weixin.qq.com/wiki/15/de148cc4b5190c80002eaf4f6f26c717.html#.E6.AD.A5.E9.AA.A4.E4.B8.80.EF.BC.9A.E5.88.9B.E5.BB.BA.E4.BC.9A.E5.91.98.E5.8D.A1)。 | 自定义会员信息类目，会员卡激活后显示，会员卡专用。                |
| name_type      | 否    | string(24)   | FIELD_NAME_TYPE_LEVEL                    | 会员信息类目名称。FIELD_NAME_TYPE_LEVEL等级；FIELD_NAME_TYPE_COUPON优惠券；FIELD_NAME_TYPE_STAMP印花；FIELD_NAME_TYPE_DISCOUNT折扣；FIELD_NAME_TYPE_ACHIEVEMEN成就；FIELD_NAME_TYPE_MILEAGE里程。 |
| url            | 否    | string（128）  | xxx.com                                  | 点击类目跳转外链url                              |
| custom_cell1   | 否    | JSON结构       | 见上述示例。                                   | 自定义会员信息类目，会员卡激活后显示。                      |
| detail         | 是    | string(3072) | 电影名：复仇者联盟2。/n放映时间：2015年5月12日23:00。/n票类型：3D。 | 电影票详情。                                   |
| departure_time | 否    | unsigned int | 1431271351                               | 起飞时间。                                    |
| landing_time   | 否    | unsigned int | 1441271351                               | 降落时间。                                    |
| gate           | 否    | string(12)   | 3号                                       | 登机口。如发生登机口变更，建议商家实时调用该接口变更。              |
| boarding_time  | 否    | unsigned int | 1431271351                               | 登机时间，只显示“时分”不显示日期，按Unix时间戳格式填写。如发生登机时间变更，建议商家实时调用该接口变更。 |
| guide_url      | 否    | string(128)  | www.qq.com                               | 景区门票的导览图URL。                             |
| map_url        | 否    | string(128)  | xxx.com。                                 | 会场导览图。                                   |



###### 2.21  设置微信买单接口

|    接口名称    | HTTP请求方式 |
| :--------: | :------: |
| paycellSet |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY';
$is_open = true;

$WxApi = Api::factory('Card');
$ret = $WxApi->paycellSet($card_id, $is_open);
```

接口请求参数列表：

|   参数名   |  必填  |     类型     |          示例值           | 描述                            |
| :-----: | :--: | :--------: | :--------------------: | ----------------------------- |
| card_id |  是   | string(32) | pFS7Fjg8kV1IdDz01xxxxx | 卡券ID。不填写时默认查询当前appid下的卡券。     |
| is_open |  否   |    bool    |          true          | 是否开启买单功能，填true/false，不填默认true |

请求成功返回示例：



请求失败返回示例：

```php
string(74) "错误码:43017, 错误信息:require location id! hint: [.l9Kha0938ent3]"
```



###### 2.22  修改库存接口

|    接口名称     | HTTP请求方式 |
| :---------: | :------: |
| modifystock |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY';
$stock   = 'increase'; //increase 增加   reduce 减少
$value   = 100;

$WxApi = Api::factory('Card');
$ret = $WxApi->modifystock($card_id, $stock, $value);
```

接口请求参数列表：

|   参数名   |  必填  |     类型     |          示例值           | 描述                         |
| :-----: | :--: | :--------: | :--------------------: | -------------------------- |
| card_id |  是   | string(32) | pFS7Fjg8kV1IdDz01xxxxx | 卡券ID。不填写时默认查询当前appid下的卡券。  |
|  stock  |  是   | string(16) |        increase        | 操作 increase(增加) reduce(减少) |
|  value  |  否   |  int(11)   |          100           | 修改多少库存，支持不填或填0             |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(68) "错误码:40073, 错误信息:invalid card id hint: [Sx3mrA0133ent3]"
```



###### 2.23  更改Code接口

​	为确保转赠后的安全性，微信允许自定义Code的商户对已下发的code进行更改。
​	注：为避免用户疑惑，建议仅在发生转赠行为后（发生转赠后，微信会通过事件推送的方式告知商户被转赠的卡券Code）对用户的Code进行更改。

|    接口名称    | HTTP请求方式 |
| :--------: | :------: |
| codeUpdate |   post   |

接口请求代码示例：

```php
$code     = '801192810944';
$new_code = '123456789101';
//$card_id  = 'pFS7Fjg8kV1IdDz01r4SQwMkuCKc';

$WxApi = Api::factory('Card');
$ret = $WxApi->codeUpdate($code, $new_code, $card_id);
```

接口请求参数列表：

| 参数名      | 必填   | 类型         | 示例值                          | 描述                  |
| -------- | ---- | ---------- | ---------------------------- | ------------------- |
| card_id  | 否    | string(32) | pFS7Fjg8kV1IdDz01r4SQwMkuCKc | 卡券ID。自定义Code码卡券为必填。 |
| code     | 是    | string(16) | 110201201245                 | 需变更的Code码。          |
| new_code | 是    | string(64) | 1231231                      | 变更后的有效Code码。        |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(72) "错误码:40056, 错误信息:invalid serial code hint: [lvjija0435ent3]"
```



###### 2.24  删除卡券接口

​	删除卡券接口允许商户删除任意一类卡券。删除卡券后，该卡券对应已生成的领取用二维码、添加到卡包JS API均会失效。 

​	注意：如用户在商家删除卡券前已领取一张或多张该卡券依旧有效。即删除卡券不能删除已被用户领取，保存在微信客户端中的卡券。

|    接口名称    | HTTP请求方式 |
| :--------: | :------: |
| cardDelete |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY';

$WxApi = Api::factory('Card');
$ret = $WxApi->cardDelete($card_id);
```

接口请求参数列表：

| 参数名     | 必填   | 类型         | 示例值                          | 描述                  |
| ------- | ---- | ---------- | ---------------------------- | ------------------- |
| card_id | 否    | string(32) | pFS7Fjg8kV1IdDz01r4SQwMkuCKc | 卡券ID。自定义Code码卡券为必填。 |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(66) "错误码:40073, 错误信息:invalid card id hint: [1dLE0293ent3]"
```



###### 2.25  设置卡券失效接口	

​	为满足改票、退款等异常情况，可调用卡券失效接口将用户的卡券设置为失效状态。

|    接口名称     | HTTP请求方式 |
| :---------: | :------: |
| unavailable |   post   |

接口请求代码示例：

```php
$code    = '358962893266';
$card_id = '';

$WxApi = Api::factory('Card');
$ret = $WxApi->unavailable($code, $card_id);
```

接口请求参数列表：

|   参数名   |  必填  |     类型     |             示例值              | 描述                  |
| :-----: | :--: | :--------: | :--------------------------: | ------------------- |
| card_id |  否   | string(32) | pFS7Fjg8kV1IdDz01r4SQwMkuCKc | 卡券ID， 非自定义code 可不填。 |
|  code   |  是   | string(20) |           1231231            | 设置失效的Code码。         |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(12) "缺少错误"
```



###### 2.26  拉取卡券概况数据接口  

|       接口名称        | HTTP请求方式 |
| :---------------: | :------: |
| getcardbizuininfo |   post   |

接口请求代码示例：

```php
$begin_date  = '2015-12-01';
$end_date    = '2015-12-21';
$cond_source = 1; //卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据

$WxApi = Api::factory('Card');
$ret = $WxApi->getcardbizuininfo($begin_date, $end_date, $cond_source);
```

接口请求参数列表：

| 字段          | 说明                              | 是否必填 | 类型           | 示例值        |
| ----------- | ------------------------------- | ---- | ------------ | ---------- |
| begin_date  | 查询数据的起始时间。                      | 是    | string(16)   | 2015-06-15 |
| end_date    | 查询数据的截至时间。                      | 是    | string(16)   | 2015-06-30 |
| cond_source | 卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据 | 是    | unsigned int | 0          |

*特别注意：* 

*1. 查询时间区间需<=62天，否则报错{errcode: 61501，errmsg: "date range error"}；*

*2. 传入时间格式需严格参照示例填写”2015-06-15”，否则报错{errcode":61500,"errmsg":"date format error"}*

*3. 该接口只能拉取非当天的数据，不能拉取当天的卡券数据，否则报错。*

请求成功返回示例：

```php
array(2) {
  [0]=>
  array(17) {
    ["ref_date"]=>
    string(10) "2016-06-27"
    ["view_cnt"]=>
    int(1)
    ["view_user"]=>
    int(1)
    ["receive_cnt"]=>
    int(1)
    ["receive_user"]=>
    int(1)
    ["verify_cnt"]=>
    int(0)
    ["verify_user"]=>
    int(0)
    ["given_cnt"]=>
    int(0)
    ["given_user"]=>
    int(0)
    ["expire_cnt"]=>
    int(0)
    ["expire_user"]=>
    int(0)
    ["view_friends_cnt"]=>
    int(0)
    ["view_friends_user"]=>
    int(0)
    ["receive_friends_cnt"]=>
    int(0)
    ["receive_friends_user"]=>
    int(0)
    ["verify_friends_cnt"]=>
    int(0)
    ["verify_friends_user"]=>
    int(0)
  }
}
```

返回成功列表：

| 字段                   | 说明   |
| -------------------- | ---- |
| ref_date             | 日期信息 |
| view_cnt             | 浏览次数 |
| view_user            | 浏览人数 |
| receive_cnt          | 领取次数 |
| receive_user         | 领取人数 |
| verify_cnt           | 使用次数 |
| verify_user          | 使用人数 |
| given_cnt            | 转赠次数 |
| given_user           | 转赠人数 |
| expire_cnt           | 过期次数 |
| expire_user          | 过期人数 |
| view_friends_cnt     |      |
| view_friends_user    |      |
| receive_friends_cnt  |      |
| receive_friends_user |      |
| verify_friends_cnt   |      |
| verify_friends_user  |      |

请求失败返回示例：

```php
string(69) "错误码:61501, 错误信息:date range error hint: [0AkRta0797ube1]"
```



###### 2.27  获取免费券数据接口

|      接口名称       | HTTP请求方式 |
| :-------------: | :------: |
| getcardcardinfo |   post   |

接口请求代码示例：

```php
$begin_date  = '2016-06-20';
$end_date    = '2016-06-28';
$cond_source = 1; //卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
$card_id     = '';

$WxApi = Api::factory('Card');
$ret = $WxApi->getcardcardinfo($begin_date, $end_date, $cond_source, $card_id);
```

接口请求参数列表：

|     字段      | 说明                              | 是否必填 |      类型      |             示例值              |
| :---------: | :------------------------------ | :--: | :----------: | :--------------------------: |
| begin_date  | 查询数据的起始时间。                      |  是   |  string(16)  |          2015-06-15          |
|  end_date   | 查询数据的截至时间。                      |  是   |  string(16)  |          2015-06-30          |
| cond_source | 卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据 |  是   | unsigned int |              0               |
|   card_id   | 卡券ID。填写后，指定拉出该卡券的相关数据。          |  否   |  string(32)  | po8pktyDLmakNY2fn2VyhkiEPqGE |

*特别注意：*

*1. 该接口目前仅支持拉取免费券（优惠券、团购券、折扣券、礼品券）的卡券相关数据，暂不支持特殊票券（电影票、会议门票、景区门票、飞机票）数据。*

*2. 查询时间区间需<=62天，否则报错{"errcode:" 61501，errmsg: "date range error"}；*

*3. 传入时间格式需严格参照示例填写如”2015-06-15”，否则报错｛"errcode":"date format error"｝*

*4. 该接口只能拉取非当天的数据，不能拉取当天的卡券数据，否则报错。*

请求成功返回示例：

```php
 array(3) {
  [0]=>
  array(17) {
    ["ref_date"]=>
    string(10) "2016-06-27"
    ["card_id"]=>
    string(28) "pdkJ9uLRSbnB3UFEjZAgUxAJrjeY"
    ["card_type"]=>
    int(1)
    ["is_pay"]=>
    int(0)
    ["view_cnt"]=>
    int(1)
    ["view_user"]=>
    int(1)
    ["receive_cnt"]=>
    int(1)
    ["receive_user"]=>
    int(1)
    ["verify_cnt"]=>
    int(0)
    ["verify_user"]=>
    int(0)
    ["given_cnt"]=>
    int(0)
    ["given_user"]=>
    int(0)
    ["expire_cnt"]=>
    int(0)
    ["expire_user"]=>
    int(0)
    ["verify_noself_cnt"]=>
    int(0)
    ["verify_noself_user"]=>
    int(0)
    ["isfriendscard"]=>
    int(0)
  }
}
```

返回成功列表：

|         字段         | 说明                                       |
| :----------------: | ---------------------------------------- |
|      ref_date      | 日期信息                                     |
|      card_id       | 卡券ID                                     |
|     card_type      | cardtype:0：折扣券，1：代金券，2：礼品券，3：优惠券，4：团购券（暂不支持拉取特殊票券类型数据，电影票、飞机票、会议门票、景区门票） |
|       is_pay       |                                          |
|      view_cnt      | 浏览次数                                     |
|     view_user      | 浏览人数                                     |
|    receive_cnt     | 领取次数                                     |
|    receive_user    | 领取人数                                     |
|     verify_cnt     | 使用次数                                     |
|    verify_user     | 使用人数                                     |
|     given_cnt      | 转赠次数                                     |
|     given_user     | 转赠人数                                     |
|     expire_cnt     | 过期次数                                     |
|    expire_user     | 过期人数                                     |
| verify_noself_cnt  |                                          |
| verify_noself_user |                                          |
|   isfriendscard    |                                          |

请求失败返回示例：

```php
string(69) "错误码:61501, 错误信息:date range error hint: [0AkRta0797ube1]"
```



###### 2.28  拉取会员卡数据接口

|         接口名称          | HTTP请求方式 |
| :-------------------: | :------: |
| getcardmembercardinfo |   post   |

接口请求代码示例：

```php
$begin_date  = '2015-12-01';
$end_date    = '2015-12-21';
$cond_source = 1; //卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据

$WxApi = Api::factory('Card');
$ret = $WxApi->getcardmembercardinfo($begin_date, $end_date, $cond_source);
```

接口请求参数列表：

| 字段          | 说明                              | 是否必填 | 类型           | 示例值        |
| ----------- | ------------------------------- | ---- | ------------ | ---------- |
| begin_date  | 查询数据的起始时间。                      | 是    | string(16)   | 2015-06-15 |
| end_date    | 查询数据的截至时间。                      | 是    | string(16)   | 2015-06-30 |
| cond_source | 卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据 | 是    | unsigned int | 0          |

请求成功返回示例：

```php
{
   "list": [
       {
           "ref_date": "2015-06-23",
           "view_cnt": 0,
              "view_user": 0,
              "receive_cnt": 0,
              "receive_user": 0,
              "active_user": 0,
              "verify_cnt": 0,
              "verify_user": 0,
              "total_user": 86,
              "total_receive_user": 95
   ]
}
```

成功返回列表：

|         字段         | 说明         |
| :----------------: | ---------- |
|      ref_date      | 日期信息       |
|      view_cnt      | 浏览次数       |
|     view_user      | 浏览人数       |
|    receive_cnt     | 领取次数       |
|    receive_user    | 领取人数       |
|     verify_cnt     | 使用次数       |
|    verify_user     | 使用人数       |
|    active_user     | 激活人数       |
|     total_user     | 有效会员总人数    |
| total_receive_user | 历史领取会员卡总人数 |

请求失败返回示例：

```php
string(69) "错误码:61501, 错误信息:date range error hint: [feJ7ja0919re46]"
```



###### 2.29  会员卡激活

|   接口名称   | HTTP请求方式 |
| :------: | :------: |
| activate |   post   |

接口请求代码示例：

```php
 $activate = [
       'membership_number' => '357898858', //会员卡编号，由开发者填入，作为序列号显示在用户的卡包里。可与Code码保持等值。
       'code' => '1231123', //创建会员卡时获取的初始code。
       'activate_begin_time' => '1397577600', //激活后的有效起始时间。若不填写默认以创建时的 data_info 为准。Unix时间戳格式
       'activate_end_time' => '1422724261', //激活后的有效截至时间。若不填写默认以创建时的 data_info 为准。Unix时间戳格式。
       'init_bonus' => '持白金会员卡到店消费，可享8折优惠。', //初始积分，不填为0。
       'init_balance' => '持白金会员卡到店消费，可享8折优惠。', //初始余额，不填为0。
       'init_custom_field_value1' => '白银', //创建时字段custom_field1定义类型的初始值，限制为4个汉字，12字节。
       'init_custom_field_value2' => '9折', //创建时字段custom_field2定义类型的初始值，限制为4个汉字，12字节。
       'init_custom_field_value3' => '200', //创建时字段custom_field3定义类型的初始值，限制为4个汉字，12字节。
 ];

$WxApi = Api::factory('Card');
$ret = $WxApi->activate($activate);
```

接口请求参数列表：

| 参数名                      | 必填   | 类型           | 描述                                       |
| ------------------------ | ---- | ------------ | ---------------------------------------- |
| membership_number        | 是    | string(20)   | 会员卡编号，由开发者填入，作为序列号显示在用户的卡包里。可与Code码保持等值。 |
| code                     | 是    | string(20)   | 创建会员卡时获取的初始code。                         |
| card_id                  | 否    | string（32）   | 卡券ID                                     |
| background_pic_url       | 否    | string（128）  | 商家自定义会员卡背景图，须                先调用[上传图片接口](http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025056&token=&lang=zh_CN&anchor=2.3.1)将背景图上传至CDN，否则报错，卡面设计请遵循[微信会员卡自定义背景设计规范 ](https://mp.weixin.qq.com/cgi-bin/readtemplate?t=cardticket/card_cover_tmpl&type=info&lang=zh_CN) |
| activate_begin_time      | 否    | unsigned int | 激活后的有效起始时间。若不填写默认以创建时的 data_info 为准。Unix时间戳格式。 |
| activate_end_time        | 否    | unsigned int | 激活后的有效截至时间。若不填写默认以创建时的 data_info 为准。Unix时间戳格式。 |
| init_bonus               | 否    | int          | 初始积分，不填为0。                               |
| init_balance             | 否    | int          | 初始余额，不填为0。                               |
| init_custom_field_value1 | 否    | string（12）   | 创建时字段custom_field1定义类型的初始值，限制为4个汉字，12字节。 |
| init_custom_field_value2 | 否    | string（12）   | 创建时字段custom_field2定义类型的初始值，限制为4个汉字，12字节。 |
| init_custom_field_value3 | 否    | string（12）   | 创建时字段custom_field3定义类型的初始值，限制为4个汉字，12字节。 |

请求成功返回示例：

```php

```

请求失败返回示例：

```php
string(71) "错误码:40056, 错误信息:invalid serial code hint: [ccUd80760ent2]"
```



###### 2.30  设置开卡字段接口

|       接口名称       | HTTP请求方式 |
| :--------------: | :------: |
| activateuserform |   post   |

接口请求代码示例：

```php
$card_id = 'pdkJ9uLRSbnB3UFEjZAgUxAJrjeY';

$required_form                         = [];
$required_form['common_field_id_list'] = [
  "USER_FORM_INFO_FLAG_MOBILE",
  "USER_FORM_INFO_FLAG_LOCATION",
  "USER_FORM_INFO_FLAG_BIRTHDAY",
];
$required_form['custom_field_list'] = ['喜欢的食物'];

$optional_form                         = [];
$optional_form['common_field_id_list'] = [
  'USER_FORM_INFO_FLAG_EMAIL',
];
$optional_form['custom_field_list'] = ['喜欢的电影'];

$WxApi = Api::factory('Card');
$ret = $WxApi->activateuserform($card_id, $required_form, $optional_form);
```

接口请求参数列表：

| 参数名                  | 必填   | 类型          | 描述                                       |
| -------------------- | ---- | ----------- | ---------------------------------------- |
| card_id              | 是    | string(32)  | 卡券ID。                                    |
| required_form        | 否    | JSON结构      | 会员卡激活时的必填选项。                             |
| optional_form        | 否    | JSON结构      | 会员卡激活时的选填项。                              |
| common_field_id_list | 否    | arry        | 微信格式化的选项类型。见以下列表。                        |
| custom_field_list    | 否    | arry        | 自定义选项名称。                                 |
| rich_field_list      | 否    | arry        | 自定义富文本类型，包含以下三个字段                        |
| type                 | 否    | string(32)  | 富文本类型                FORM_FIELD_RADIO   自定义单选                FORM_FIELD_SELECT   自定义选择项                FORM_FIELD_CHECK_BOX 自定义多选 |
| name                 | 否    | string(32)  | 字段名                                      |
| values               | 否    | arry        | 选择项                                      |
| service_statement    | 否    | JSON结构      | 服务声明，用于放置商户会员卡守                则         |
| name                 | 否    | string(32)  | 会员声明字段名称                                 |
| url                  | 否    | string(128) | 自定义url                                   |
| bind_old_card        | 否    | JSON结构      | 绑定老会员链接                                  |
| name                 | 否    | string(32)  | 链接名称                                     |
| url                  | 否    | string(128) | 自定义url                                   |

**common_field_id_list，支持开发者使用以下选项类型**

| 字段值                                   | 描述   |
| ------------------------------------- | ---- |
| USER_FORM_INFO_FLAG_MOBILE            | 手机号  |
| USER_FORM_INFO_FLAG_SEX               | 性别   |
| USER_FORM_INFO_FLAG_NAME              | 姓名   |
| USER_FORM_INFO_FLAG_BIRTHDAY          | 生日   |
| USER_FORM_INFO_FLAG_IDCARD            | 身份证  |
| USER_FORM_INFO_FLAG_EMAIL             | 邮箱   |
| USER_FORM_INFO_FLAG_DETAIL_LOCATION   | 详细地址 |
| USER_FORM_INFO_FLAG_EDUCATION_BACKGRO | 教育背景 |
| USER_FORM_INFO_FLAG_CAREER            | 职业   |
| USER_FORM_INFO_FLAG_INDUSTRY          | 行业   |
| USER_FORM_INFO_FLAG_INCOME            | 收入   |
| USER_FORM_INFO_FLAG_HABIT             | 兴趣爱好 |

请求成功返回示例：

```php

```

请求失败返回示例：

```php
string(156) "错误码:47001, 错误信息:data format error hint: [XiF6va0109ent1] Error before ":["USER_FORM_INFO_FLAG_EMAIL"],"custom_field_list":["喜欢的电影"]}"
```



###### 2.31  拉取会员信息接口

|        接口名称        | HTTP请求方式 |
| :----------------: | :------: |
| membercardUserinfo |   post   |

接口请求代码示例：

```php
$card_id = 'pbLatjtZ7v1BG_ZnTjbW85GYc_E8';
$code    = '916679873278';

$WxApi = Api::factory('Card');
$ret = $WxApi->membercardUserinfo($card_id, $code);
```

接口请求参数列表：

|   参数名   |  必填  |     类型     |             示例值              | 描述                  |
| :-----: | :--: | :--------: | :--------------------------: | ------------------- |
| card_id |  是   | string(32) | pFS7Fjg8kV1IdDz01r4SQwMkuCKc | 卡券ID， 非自定义code 可不填。 |
|  code   |  是   | string(20) |           1231231            | 设置失效的Code码。         |

请求成功返回示例：

```php
array(9) {
  ["openid"]=>
  string(28) "odkJ9uKRfgL2tclcoBpDX46wKswo"
  ["nickname"]=>
  string(9) "达尔文"
  ["membership_number"]=>
  string(12) "916679873278"
  ["bonus"]=>
  int(0)
  ["balance"]=>
  int(0)
  ["sex"]=>
  string(4) "MALE"
  ["user_info"]=>
  array(2) {
    ["common_field_list"]=>
    array(4) {
      [0]=>
      array(2) {
        ["name"]=>
        string(26) "USER_FORM_INFO_FLAG_MOBILE"
        ["value"]=>
        string(11) "13247668386"
      }
      [1]=>
      array(2) {
        ["name"]=>
        string(28) "USER_FORM_INFO_FLAG_LOCATION"
        ["value"]=>
        string(61) "广东省-广州市-海珠区-新港中路397号 TIT创意园"
      }
      [2]=>
      array(2) {
        ["name"]=>
        string(28) "USER_FORM_INFO_FLAG_BIRTHDAY"
        ["value"]=>
        string(9) "2015-4-30"
      }
      [3]=>
      array(2) {
        ["name"]=>
        string(25) "USER_FORM_INFO_FLAG_EMAIL"
        ["value"]=>
        string(15) "darwinxu@qq.com"
      }
    }
    ["custom_field_list"]=>
    array(2) {
      [0]=>
      array(2) {
        ["name"]=>
        string(15) "喜欢的食物"
        ["value"]=>
        string(6) "公告"
      }
      [1]=>
      array(2) {
        ["name"]=>
        string(15) "喜欢的电影"
        ["value"]=>
        string(3) "bbn"
      }
    }
  }
  ["user_card_status"]=>
  string(6) "DELETE"
  ["has_active"]=>
  bool(true)
}
```

请求成功返回列表：

|        参数名        | 说明                                       |
| :---------------: | ---------------------------------------- |
|      openid       | 用户在本公众号内唯一识别码                            |
|     nickname      | 用户昵称                                     |
|       bonus       | 积分信息                                     |
|      balance      | 余额信息                                     |
|        sex        | 用户性别                                     |
|     user_info     | 会员信息                                     |
| custom_field_list | 开发者设置的会员卡会员信息类目，如等级。                     |
|       name        | 会员信息类目名称                                 |
|       value       | 会员卡信息类目值，比如等级值等                          |
| user_card_status  | 当前用户的会员卡状态，NORMAL 正常 EXPIRE 已过期 GIFTING 转赠中 GIFT_SUCC 转赠成功 GIFT_TIMEOUT 转赠超时 DELETE 已删除，UNAVAILABLE 已失效 |

请求失败返回示例：

```php
string(72) "错误码:40056, 错误信息:invalid serial code hint: [WPkBoA0556ent2]"
```



###### 2.32  更新会员信息

|         接口名称         | HTTP请求方式 |
| :------------------: | :------: |
| membercardUpdateuser |   post   |

接口请求代码示例：

```php
$updateuser = [
      'code' => '916679873278', //卡券Code码。
      'card_id' => 'pbLatjtZ7v1BG_ZnTjbW85GYc_E8', //卡券ID。
      'record_bonus' => '消费30元，获得3积分', //商家自定义积分消耗记录，不超过14个汉字。
      'bonus' => '100', //需要设置的积分全量值，传入的数值会直接显示，如果同时传入add_bonus和bonus,则前者无效。
      'balance' => '持白金会员卡到店消费，可享8折优惠。', //需要设置的余额全量值，传入的数值会直接显示，如果同时传入add_balance和balance,则前者无效。
      'record_balance' => '持白金会员卡到店消费，可享8折优惠。', //商家自定义金额消耗记录，不超过14个汉字。
      'custom_field_value1' => '100', //创建时字段custom_field1定义类型的最新数值，限制为4个汉字，12字节。
      'custom_field_value2' => '200', //创建时字段custom_field2定义类型的最新数值，限制为4个汉字，12字节。
      'custom_field_value3' => '300', //创建时字段custom_field3定义类型的最新数值，限制为4个汉字，12字节。
];

$WxApi = Api::factory('Card');
$ret = $WxApi->membercardUpdateuser($updateuser);
```

接口请求参数列表：

​	值得注意的是，如果开发者做不到实时同步积分、余额至微信端，我们强烈建议开发者可以在每天的固定时间点变更积分，一天不超过三次。当传入的积分值与之前无变化时（传入的bonus=原来的bonus），不会有积分变动通知。

|         参数名         |  必填  |     类型      |                   示例值                    | 描述                                       |
| :-----------------: | :--: | :---------: | :--------------------------------------: | ---------------------------------------- |
|        code         |  是   | string(20)  |                 1231123                  | 卡券Code码。                                 |
|       card_id       |  是   | string（32）  | p1Pj9jr90_SQ                RaVqYI239Ka1erkI | 卡券ID。                                    |
| background_pic_url  |  否   | string（128） |         https://mmbiz.qlogo.cn/          | 支持商家激活时针对单个会员卡分配自定义的会员卡背景。               |
|        bonus        |  是   |     int     |                   100                    | 需要设置的积分全量值，传入的数值会直接显示                    |
|    record_bonus     |  否   | string(42)  |               消费30元，获得3积分                | 商家自定义积分消耗记录，不超过14个汉字。                    |
|       balance       |  是   |     int     |                   100                    | 需要设置的余额全量值，传入的数值会直接显示                    |
|   record_balance    |  否   | string(42)  |    购买焦糖玛                琪朵一杯，扣除金额30元。    | 商家自定义金额消耗记录，不超过14个汉字。                    |
| custom_field_value1 |  否   | string（12）  |                    白金                    | 创建时字段custom_field1定义类型的最新数值，限制为4个汉字，12字节。 |
| custom_field_value2 |  否   | string（12）  |                    8折                    | 创建时字段custom_field2定义类型的最新数值，限制为4个汉字，12字节。 |
| custom_field_value3 |  否   | string（12）  |                   500                    | 创建时字段custom_field3定义类型的最新数值，限制为4个汉字，12字节。 |

请求成功返回示例：

```php

```

请求失败返回示例：

```php
string(63) "错误码:40013, 错误信息:invalid appid hint: [OcL0736ent3]"
```



###### 2.33  添加子商户

|    接口名称     | HTTP请求方式 |
| :---------: | :------: |
| submerchant |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Card');
$ret = $WxApi->submerchant();
```

接口请求参数列表：

|          参数名          |  必填  |      类型      |                   示例                    | 说明                                       |
| :-------------------: | :--: | :----------: | :-------------------------------------: | ---------------------------------------- |
|         info          |  是   |    json结构    |                                         |                                          |
|        app_id         |  否   |  String(36)  |              wxxxxxxxxxxx               | 子商户的公众号app_id，配置后子商户卡券券面上的app_id为该app_id。注意：该app_id须经过认证 |
|      brand_name       |  是   |  String(36)  |                  兰州拉面                   | 子商户名称（12个汉字内），该名称将在制券时填入并显示在卡券页面上        |
|       logo_url        |  是   | string(128)  | [http://mmbiz.xxxx](http://mmbiz.xxxx/) | 子商户logo，可通过[上传logo接口](http://mp.weixin.qq.com/wiki/13/650e7e480dceeaaa0348d92f92f5e55d.html)获取。该logo将在制券时填入并显示在卡券页面上 |
|       protocol        |  是   |  String(36)  |               mdasdfkl ：                | 授权函ID，即通过[上传临时素材接口](http://mp.weixin.qq.com/wiki/0/6ebbf79d99f7a435ed60cfb094867174.html)上传授权函后获得的meida_id |
|       end_time        |  是   | unsigned int |                15300000                 | 授权函有效期截止时间（东八区时间，单位为秒），需要与提交的扫描件一致       |
|  primary_category_id  |  是   |     int      |                    2                    | 一级类目id,可以通过本文档中接口查询                      |
| secondary_category_id |  是   |     int      |                    2                    | 二级类目id，可以通过本文档中接口查询                      |
|  agreement_media_id   |  否   |  string(36)  |               2343343424                | 营业执照或个体工商户营业执照彩照或扫描件                     |
|   operator_media_id   |  否   |  string(36)  |               2343343424                | 营业执照内登记的经营者身份证彩照或扫描件                     |

*备注：授权函请在*[*《第三方代制模式指引文档》*](https://mp.weixin.qq.com/cgi-bin/announce?action=getannouncement&key=1459357007&version=1&lang=zh_CN&platform=2)*内下载，手填并加盖鲜章后，上传彩色扫描件或彩照。*

*1、授权函必须加盖企业公章，或个体户店铺章、发票专用章、财务章、合同章等具备法律效力的盖章，不可使用个人私章；*

*2、若子商户是个体工商户，且无上述公章，授权函可用个体工商户经营者手印代替公章，且须同时额外上传《个体工商户营业执照》及该执照内登记的经营者的身份证彩照。（本方案仅适用于子商户是个体工商户，且无公章的场景。其他场景必须在授权函加盖公章）*

请求成功返回示例：

```php

```

请求失败返回示例：

```php
string(72) "错误码:52000, 错误信息:pic is not from cdn hint: [zk3JZa0670ent3]"
```



###### 2.34  卡券开放类目查询接口

|       接口名称       | HTTP请求方式 |
| :--------------: | :------: |
| getapplyprotocol |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Card');
$ret = $WxApi->getapplyprotocol();
```



请求成功返回示例：

```php
array(1) {
  [0]=>
  array(1) {
    ["primary_category_id"]=>
    int(1)
    ["category_name"]=>
    string(6) "美食"
    ["secondary_category"]=>
    array(1) {
      [0]=>
      array(5) {
        ["secondary_category_id"]=>
        int(101)
        ["category_name"]=>
        string(6) "粤菜"
        ["need_qualification_stuffs"]=>
        array(2) {
          [0]=>
          string(23) "food_service_license_id"
          [1]=>
          string(32) "food_service_license_bizmedia_id"
        }
        ["can_choose_prepaid_card"]=>
        int(1)
        ["can_choose_payment_card"]=>
        int(1)
      }
    }
  }
}
```

请求成功返回列表：

|          参数名          | 描述     |
| :-------------------: | ------ |
|  primary_category_id  | 一级目录id |
| secondary_category_id | 二级目录id |



### 3.用户分组相关接口（GroupsApi.php）

###### 3.1  创建用户组

|  接口名称  | HTTP请求方式 |
| :----: | :------: |
| create |   post   |

接口请求代码示例：

```php
$name = 'TestGroup'; 

$WxApi = Api::factory('Groups');
$ret = $WxApi->create($name);
```

接口请求列表：

| 参数名  |  必填  |   类型   |    示例     | 说明       |
| :--: | :--: | :----: | :-------: | -------- |
| name |  是   | String | testGroup | 创建的用户组名称 |

请求成功返回示例：

```php
array(2) {
  ["id"]=>
  int(153)
  ["name"]=>
  string(9) "TestGroup"
}
```

请求成功返回列表：

| 参数名  | 描述        |
| :--: | --------- |
|  id  | 用户组的关键字id |
| name | 用户组名称     |

请求失败返回示例：

```php
string(71) "错误码:40051, 错误信息:invalid group name hint: [YQVvhA0948vr22]"
```



###### 3.2  查询分组

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| get  |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Groups');
$ret = $WxApi->get();  //所有分组信息 如没有分组返回false.
```

接口请求列表：

| 参数名  |  必填  |   类型   |    示例     | 说明       |
| :--: | :--: | :----: | :-------: | -------- |
| name |  是   | String | testGroup | 创建的用户组名称 |

请求成功返回示例：

```php
array(2) {
  [0]=>
  array(3) {
    ["id"]=>
    int(2)
    ["name"]=>
    string(9) "星标组"
    ["count"]=>
    int(0)
  }
  [1]=>
  array(3) {
    ["id"]=>
    int(106)
    ["name"]=>
    string(9) "TestGroup"
    ["count"]=>
    int(0)
  }
}
```

请求成功返回列表：

|  参数名  | 描述        |
| :---: | --------- |
|  id   | 用户组的关键字id |
| name  | 用户组名称     |
| count |           |



###### 3.3  查询用户所在分组

| 接口名称  | HTTP请求方式 |
| :---: | :------: |
| getid |   get    |

接口请求代码示例：

```php
$openid = 'odkJ9uE2f1BTY2rBKpFKvCcVoMvM';

$WxApi = Api::factory('Groups');
$ret = $WxApi->getid($openid);
```

接口请求列表：

|  参数名   |  必填  |   类型   |              示例              | 说明      |
| :----: | :--: | :----: | :--------------------------: | ------- |
| openid |  是   | String | odkJ9uE2f1BTY2rBKpFKvCcVoMvM | 用户的唯一标识 |

请求成功返回示例：

```php
int(2)
```

请求失败返回示例：

```php
string(25) "参数错误,缺少Openid"
```



###### 3.4  修改分组名

|  接口名称  | HTTP请求方式 |
| :----: | :------: |
| update |   post   |

接口请求代码示例：

```php
$id   = 100;
$name = 'testUpdate';

$WxApi = Api::factory('Groups');
$ret = $WxApi->update($id, $name);
```

接口请求列表：

| 参数名  |  必填  |   类型    |  示例  | 说明        |
| :--: | :--: | :-----: | :--: | --------- |
|  id  |  是   | int(11) |  6   | 用户组的关键字id |
| name |  是   | String  | test | 用户组名称     |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(62) "错误码:-1, 错误信息:system error hint: [.8_VXa0873vr23]"
```



###### 3.5  删除分组

|  接口名称  | HTTP请求方式 |
| :----: | :------: |
| delete |   post   |

接口请求代码示例：

```php
$id = I('get.id', null, 'htmlspecialchars');

if (!is_numeric($id) || empty($id)) {
  return false;
}

$WxApi = Api::factory('Groups');
$ret = $WxApi->delete($id);
```

接口请求列表：

| 参数名  |  必填  |   类型    |  示例  | 说明        |
| :--: | :--: | :-----: | :--: | --------- |
|  id  |  是   | int(11) |  6   | 用户组的关键字id |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(69) "错误码:40152, 错误信息:invalid group id hint: [ewuN8a0070vr22]"
```



###### 3.6  移动用户到指定分组

|   接口名称   | HTTP请求方式 |
| :------: | :------: |
| moveUser |   post   |

接口请求代码示例：

```php
$openid     = 'odkJ9uEnEIJSNnr0Bk9_eA70ZS8o';
$to_groupid = 103; 

$WxApi = Api::factory('Groups');
$ret = $WxApi->moveUser($openid, $to_groupid);
```

接口请求列表：

|    参数名     |  必填  |   类型    |              示例              | 说明      |
| :--------: | :--: | :-----: | :--------------------------: | ------- |
|   openid   |  是   | String  | odkJ9uEnEIJSNnr0Bk9_eA70ZS8o | 用户的唯一标识 |
| to_groupid |  否   | int(11) |              6               | 指定分组ID  |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(67) "错误码:40003, 错误信息:invalid openid hint: [y9Cwta0422vr23]"
```



###### 3.7  批量移动用户到指定分组

|       接口名称        | HTTP请求方式 |
| :---------------: | :------: |
| MoveUserlistGroup |   post   |

接口请求代码示例：

```php
$openid_list = ['odkJ9uEnEIJSNnr0Bk9_eA70ZS8o', 'odkJ9uE2f1BTY2rBKpFKvCcVoMvM'];
$to_groupid  = 103;

$WxApi       = Api::factory('Groups');
$ret         = $WxApi->MoveUserlistGroup($openid_list, $to_groupid);
```

接口请求列表：

|     参数名     |  必填  |   类型    |                    示例                    | 说明        |
| :---------: | :--: | :-----: | :--------------------------------------: | --------- |
| openid_list |  是   |  array  | ['odkJ9uEnEIJSNnr0Bk9_eA70ZS8o', 'odkJ9uE2f1BTY2rBKpFKvCcVoMvM']; | 用户的唯一标识列表 |
| to_groupid  |  否   | int(11) |                    6                     | 指定分组ID    |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(27) "参数必须为一个数组"
```



### 4.JSSDK相关接口（JSSDKApi.php）

​	微信JS-SDK是[微信公众平台](https://mp.weixin.qq.com/cgi-bin/loginpage?t=wxm2-login&lang=zh_CN)面向网页开发者提供的基于微信内的网页开发工具包。

​	通过使用微信JS-SDK，网页开发者可借助微信高效地使用拍照、选图、语音、位置等手机系统的能力，同时可以直接使用微信分享、扫一扫、卡券、支付等微信特有的能力，为微信用户提供更优质的网页体验。



### 5.客服相关接口（KfaccountApi.php）

###### 5.1添加客服账号

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| add  |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Kfaccount');

$kf_account = '009@DremQzone';
$nickname	= 'Maaahuanghuang';
$password	= '123456';

$ret = $WxApi->add($kf_account, $nickname, $password);
```

接口请求列表：

| 参数         | 是否必须 | 说明                                       |
| ---------- | ---- | ---------------------------------------- |
| kf_account | 是    | 完整客服账号，格式为：账号前缀@公众号微信号，账号前缀最多10个字符，必须是英文或者数字字符。如果没有公众号微信号，请前往微信公众平台设置。 |
| nickname   | 是    | 客服昵称，最长6个汉字或12个英文字符                      |
| password   | 是    | 客服账号登录密码，格式为密码明文的32位加密MD5值               |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(42) "错误码:61450, 错误信息:system error"
```



###### 5.2  修改客服账号

|  接口名称  | HTTP请求方式 |
| :----: | :------: |
| update |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Kfaccount');

$kf_account = '009@DremQzone';
$nickname   = 'UaDD';
$password   = '654321';

$ret = $WxApi->update($kf_account, $nickname, $password);
```

接口请求列表：

| 参数         | 是否必须 | 说明                                       |
| ---------- | ---- | ---------------------------------------- |
| kf_account | 是    | 完整客服账号，格式为：账号前缀@公众号微信号，账号前缀最多10个字符，必须是英文或者数字字符。 |
| nickname   | 是    | 客服昵称，最长6个汉字或12个英文字符                      |
| password   | 是    | 客服账号登录密码，格式为密码明文的32位加密MD5值               |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
array(3) {
  ["content"]=>
  string(73) "{
    "errcode": invalid kf_account,
    "errmsg": "invalid kf_account"
}"
  ["type"]=>
  string(25) "text/html; charset=gb2312"
  ["size"]=>
  string(2) "74"
}
```



###### 5.3  删除客服账号

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| del  |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Kfaccount');

$kf_account = '008@DremQzone';

$ret = $WxApi->del($kf_account);
```

接口请求列表：

| 参数         | 是否必须 | 说明                                       |
| ---------- | ---- | ---------------------------------------- |
| kf_account | 是    | 完整客服账号，格式为：账号前缀@公众号微信号，账号前缀最多10个字符，必须是英文或者数字字符。 |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(48) "错误码:61452, 错误信息:invalid kf_account"
```



###### 5.4  获取客服基本信息

|   接口名称    | HTTP请求方式 |
| :-------: | :------: |
| getkflist |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Kfaccount');
$ret = $WxApi->getkflist();
```

请求成功返回示例：

```php

```

请求失败返回示例：

```php
string(62) "错误码:-1, 错误信息:system error hint: [Ms_H3a0860vr22]"
```



###### 5.5  获取在线客服接待信息

|      接口名称       | HTTP请求方式 |
| :-------------: | :------: |
| getonlinekflist |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Kfaccount');
$ret = $WxApi->getonlinekflist();
```

请求成功返回示例：

```php

```

请求失败返回示例：

```php
string(62) "错误码:-1, 错误信息:system error hint: [Ms_H3a0860vr22]"
```



###### 5.6  设置客服账号的头像

|     接口名称      | HTTP请求方式 |
| :-----------: | :------: |
| uploadheadimg |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Kfaccount');

$file       = 'Uploads/test.png';
$kf_account = '002@DremQzone';

$ret = $WxApi->uploadheadimg($file, $kf_account);
```

接口请求列表：

| 参数         | 是否必须 | 说明                     |
| ---------- | ---- | ---------------------- |
| file       | 是    | 文件路径不正确                |
| kf_account | 是    | 完整客服账号，格式为：账号前缀@公众号微信号 |

请求成功返回示例：

```php

```

请求失败返回示例：

```php
string(62) "错误码:-1, 错误信息:system error hint: [Ms_H3a0860vr22]"
```



###### 5.7  获取客服聊天记录

|   接口名称    | HTTP请求方式 |
| :-------: | :------: |
| getrecord |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Kfaccount');

$endtime   = '987654321';
$pageindex = '1';
$pagesize  = '10';
$starttime = '123456789';

$ret = $WxApi->getrecord($endtime, $pageindex, $pagesize, $starttime);
```

接口请求列表：

| 参数        | 是否必须 | 说明                        |
| --------- | ---- | ------------------------- |
| starttime | 是    | 查询开始时间，UNIX时间戳            |
| endtime   | 是    | 查询结束时间，UNIX时间戳，每次查询不能跨日查询 |
| pagesize  | 是    | 每页大小，每页最多拉取50条            |
| pageindex | 是    | 查询第几页，从1开始                |

请求成功返回示例：

```php

```

请求失败返回示例：

```php
string(62) "错误码:-1, 错误信息:system error hint: [Ms_H3a0860vr22]"
```



### 6.素材相关接口（MaterialApi.php）

###### 6.1  获取永久素材列表

|   接口名称   | HTTP请求方式 |
| :------: | :------: |
| batchget |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Material');

//$type 获取素材类型, $offset 偏移 ,$count 数量 不能大于20	

$ret = $WxApi->batchget($type='image', 0, 20);
```

接口请求列表：

| 参数     | 是否必须 | 说明     |
| ------ | :--: | ------ |
| type   |  是   | 获取素材类型 |
| offset |  否   | 偏移     |
| count  |  否   | 数量     |

请求成功返回示例：

```php
array(3) {
  ["item"]=>
  array(1) {
    [0]=>
    array(4) {
      ["media_id"]=>
      string(43) "9xQAI7XmtAaFkE6SrlfZKA4aLigUg0seur9VPnZ2V9U"
      ["name"]=>
      string(20) "api_mpnews_cover.jpg"
      ["update_time"]=>
      int(1450837760)
      ["url"]=>
      string(0) ""
    } 
  }
  ["total_count"]=>
  int(11)
  ["item_count"]=>
  int(12)
}
```

请求失败返回示例：

```php
string(31) "参数错误,类型参数错误"
```



###### 6.2  新增永久图文素材  

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| addNews |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Material');

$data                          = [];
$data[0]['title']              = '标题';
$data[0]['thumb_media_id']     = 'b8lv6hmgqH9wcTS9VZ8wVPwjFlPXsLlgld8wUNd5uV8'; //图文消息的封面图片素材id（必须是永久mediaID）
$data[0]['author']             = '作者';
$data[0]['digest']             = '图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空';
$data[0]['show_cover_pic']     = '1'; //是否显示封面，0为false，即不显示，1为true，即显示
$data[0]['content']            = '<p>图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS</p>';
$data[0]['content_source_url'] = 'http://www.baidu.com';

$ret = $WxApi->addNews($data);
```

接口请求列表：

| 参数                 | 是否必须 | 说明                                       |
| ------------------ | ---- | ---------------------------------------- |
| title              | 是    | 标题                                       |
| thumb_media_id     | 是    | 图文消息的封面图片素材id（必须是永久mediaID）              |
| author             | 是    | 作者                                       |
| digest             | 是    | 图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空              |
| show_cover_pic     | 是    | 是否显示封面，0为false，即不显示，1为true，即显示           |
| content            | 是    | 图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS |
| content_source_url | 是    | 图文消息的原文地址，即点击“阅读原文”后的URL                 |

请求成功返回示例：

```php
string(43) "BqvF0gQa9kd2v1kMl1pkHfKyWA8zcO96iHAYQs1H-Kw"
```

请求失败返回示例：

```php
string(66) "错误码:44004, 错误信息:empty content hint: [4Lvu7a0318e292]"
```



###### 6.3  新增其它永久素材

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| add  |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Material');

$file = 'Uploads/test.png';
$type = 'image';

//新增永久视频素材附加字段 (其他素材 此字段无效)
$info = ['title' => '视频素材的标题', 'introduction' => '视频素材的描述'];

$ret = $WxApi->add($file, $type, $info);
```

接口请求列表：

| 参数           | 是否必须 | 说明      |
| ------------ | ---- | ------- |
| title        | 是    | 视频素材的标题 |
| introduction | 是    | 视频素材的描述 |

请求成功返回示例：

```php
string(43) "BqvF0gQa9kd2v1kMl1pkHfKyWA8zcO96iHAYQs1H-Kw"
```

请求失败返回示例：

```php
string(21) "文件路径不正确"
```



###### 6.4  获取永久素材

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| get  |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Material');

$media_id = 'ma2Rg8kmqPkObTlWWfxTX-mmAVOw0V51wBxTalWcmKg';

$ret = $WxApi->get($media_id);
```

接口请求列表：

| 参数       | 是否必须 | 说明              |
| -------- | ---- | --------------- |
| media_id | 是    | 要获取的素材的media_id |

请求成功返回示例：

```php
array(3) {
  ["news_item"]=>
  array(1) {
    [0]=>
    array(9) {
      ["title"]=>
      string(6) "标题"
      ["author"]=>
      string(6) "作者"
      ["digest"]=>
      string(81) "图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空"
      ["content"]=>
      string(112) "
图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS

"
      ["content_source_url"]=>
      string(20) "http://www.baidu.com"
      ["thumb_media_id"]=>
      string(43) "b8lv6hmgqH9wcTS9VZ8wVPwjFlPXsLlgld8wUNd5uV8"
      ["show_cover_pic"]=>
      int(1)
      ["url"]=>
      string(107) "http://mp.weixin.qq.com/s?__biz=MzA5NTIxNjc1OA==&mid=501527430&idx=1&sn=693d51c15e22b46c913deb06da45dfe8#rd"
      ["thumb_url"]=>
      string(129) "http://mmbiz.qpic.cn/mmbiz/2aJY6aCPatQhicDS61u27u1wuHE6icQCfQVbMcrwnvz0gBIVHVNNbbclkvx11drqxue3wncEcE4YdYDGAF2v0vrw/0?wx_fmt=jpeg"
    }
  }
  ["create_time"]=>
  int(1467198008)
  ["update_time"]=>
  int(1467198008)
}
```

请求成功返回列表：

| 参数                 | 描述                                       |
| ------------------ | ---------------------------------------- |
| title              | 图文消息的标题                                  |
| thumb_media_id     | 图文消息的封面图片素材id（必须是永久mediaID）              |
| show_cover_pic     | 是否显示封面，0为false，即不显示，1为true，即显示           |
| author             | 作者                                       |
| digest             | 图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空              |
| content            | 图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS |
| url                | 图文页的URL                                  |
| content_source_url | 图文消息的原文地址，即点击“阅读原文”后的URL                 |

请求失败返回示例：

```php
string(69) "错误码:40007, 错误信息:invalid media_id hint: [_OVLra0733e297]"
```



###### 6.5  删除永久素材

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| del  |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Material');

$media_id = 'ma2Rg8kmqPkObTlWWfxTX-mmAVOw0V51wBxTalWcmKg';

$ret = $WxApi->del($media_id);
```

接口请求列表：

| 参数       | 是否必须 | 说明              |
| -------- | ---- | --------------- |
| media_id | 是    | 要获取的素材的media_id |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(69) "错误码:40007, 错误信息:invalid media_id hint: [LsXLra0996ure1]"
```



###### 6.6  修改永久图文素材

|    接口名称    | HTTP请求方式 |
| :--------: | :------: |
| updateNews |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Material');

$media_id = 'ma2Rg8kmqPkObTlWWfxTX9j_-BxI0tdyPrAhPMDDCAc';

$articles                       = [];
$articles['title']              = '标题-修改';
$articles['thumb_media_id']     = 'b8lv6hmgqH9wcTS9VZ8wVPwjFlPXsLlgld8wUNd5uV8'; //图文消息的封面图片素材id（必须是永久mediaID）
$articles['author']             = '作者';
$articles['digest']             = '图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空';
$articles['show_cover_pic']     = '1'; //是否显示封面，0为false，即不显示，1为true，即显示
$articles['content']            = '<p>图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS</p>';
$articles['content_source_url'] = 'http://www.baidu.com';

$index = 0;

$ret = $WxApi->updateNews($media_id, $articles, $index);
```

接口请求列表：

| 参数                 | 是否必须 | 说明                                       |
| ------------------ | ---- | ---------------------------------------- |
| media_id           | 是    | 要修改的图文消息的id                              |
| index              | 是    | 要更新的文章在图文消息中的位置（多图文消息时，此字段才有意义），第一篇为0    |
| title              | 是    | 标题                                       |
| thumb_media_id     | 是    | 图文消息的封面图片素材id（必须是永久mediaID）              |
| author             | 是    | 作者                                       |
| digest             | 是    | 图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空              |
| show_cover_pic     | 是    | 是否显示封面，0为false，即不显示，1为true，即显示           |
| content            | 是    | 图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS |
| content_source_url | 是    | 图文消息的原文地址，即点击“阅读原文”后的URL                 |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(69) "错误码:40007, 错误信息:invalid media_id hint: [NfbCHA0133e298]"
```



###### 6.7  获取素材总数

|   接口名称   | HTTP请求方式 |
| :------: | :------: |
| getCount |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Material');
$ret   = $WxApi->getCount();
```

请求成功返回示例：

```php
array(4) {
  ["voice_count"]=>
  int(0)
  ["video_count"]=>
  int(0)
  ["image_count"]=>
  int(11)
  ["news_count"]=>
  int(7)
}
```

请求成功返回列表：

| 参数          | 描述    |
| ----------- | ----- |
| voice_count | 语音总数量 |
| video_count | 视频总数量 |
| image_count | 图片总数量 |
| news_count  | 图文总数量 |



### 7.媒体文件相关接口（MediaApi.php）



### 8.菜单相关接口（MenuApi.php）

自定义菜单接口可实现多种类型按钮，如下：

```
1、click：点击推事件用户点击click类型按钮后，微信服务器会通过消息接口推送消息类型为event的结构给开发者（参考消息接口指南），并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互；
2、view：跳转URL用户点击view类型按钮后，微信客户端将会打开开发者在按钮中填写的网页URL，可与网页授权获取用户基本信息接口结合，获得用户基本信息。
3、scancode_push：扫码推事件用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL），且会将扫码的结果传给开发者，开发者可以下发消息。
4、scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者，同时收起扫一扫工具，然后弹出“消息接收中”提示框，随后可能会收到开发者下发的消息。
5、pic_sysphoto：弹出系统拍照发图用户点击按钮后，微信客户端将调起系统相机，完成拍照操作后，会将拍摄的相片发送给开发者，并推送事件给开发者，同时收起系统相机，随后可能会收到开发者下发的消息。
6、pic_photo_or_album：弹出拍照或者相册发图用户点击按钮后，微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”。用户选择后即走其他两种流程。
7、pic_weixin：弹出微信相册发图器用户点击按钮后，微信客户端将调起微信相册，完成选择操作后，将选择的相片发送给开发者的服务器，并推送事件给开发者，同时收起相册，随后可能会收到开发者下发的消息。
8、location_select：弹出地理位置选择器用户点击按钮后，微信客户端将调起地理位置选择工具，完成选择操作后，将选择的地理位置发送给开发者的服务器，同时收起位置选择工具，随后可能会收到开发者下发的消息。
9、media_id：下发消息（除文本消息）用户点击media_id类型按钮后，微信服务器会将开发者填写的永久素材id对应的素材下发给用户，永久素材类型可以是图片、音频、视频、图文消息。请注意：永久素材id必须是在“素材管理/新增永久素材”接口上传后获得的合法id。
10、view_limited：跳转图文消息URL用户点击view_limited类型按钮后，微信客户端将打开开发者在按钮中填写的永久素材id对应的图文消息URL，永久素材类型只支持图文消息。请注意：永久素材id必须是在“素材管理/新增永久素材”接口上传后获得的合法id。
```

*请注意，3到8的所有事件，仅支持微信iPhone5.4.1以上版本，和Android5.4以上版本的微信用户，旧版本微信用户点击后将没有回应，开发者也不能正常接收到事件推送。9和10，是专门给第三方平台旗下未微信认证（具体而言，是资质认证未通过）的订阅号准备的事件类型，它们是没有事件推送的，能力相对受限，其他类型的公众号不必使用。*

###### 8.1  设置菜单

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| set  |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Menu');

$button = new MenuItem("菜单");

$menus = array(
  new MenuItem("今日歌曲", 'click', 'V1001_TODAY_MUSIC'),
  $button->buttons(array(
    new MenuItem('搜索', 'view', 'http://www.soso.com/'),
    new MenuItem('视频', 'view', 'http://v.qq.com/'),
    new MenuItem('赞一下我们', 'click', 'V1001_GOOD'),
  )),
);

$ret = $WxApi->set($menus);
```

接口请求参数列表：

| 参数         | 是否必须                        | 说明                         |
| ---------- | --------------------------- | -------------------------- |
| button     | 是                           | 一级菜单数组，个数应为1~3个            |
| sub_button | 否                           | 二级菜单数组，个数应为1~5个            |
| type       | 是                           | 菜单的响应动作类型                  |
| name       | 是                           | 菜单标题，不超过16个字节，子菜单不超过40个字节  |
| key        | click等点击类型必须                | 菜单KEY值，用于消息接口推送，不超过128字节   |
| url        | view类型必须                    | 网页链接，用户点击菜单可打开链接，不超过1024字节 |
| media_id   | media_id类型和view_limited类型必须 | 调用新增永久素材接口返回的合法media_id    |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(77) "错误码:40018, 错误信息:invalid button name size hint: [VpvS5a0143vr23]"
```



###### 8.2  获取菜单

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| get  |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Menu');
$ret = $WxApi->get();
```

请求成功返回示例：

```php
array(1) {
  ["button"]=>
  array(2) {
    [0]=>
    array(4) {
      ["type"]=>
      string(5) "click"
      ["name"]=>
      string(12) "今日歌曲"
      ["key"]=>
      string(17) "V1001_TODAY_MUSIC"
      ["sub_button"]=>
      array(0) {
      }
    }
    [1]=>
    array(2) {
      ["name"]=>
      string(6) "菜单"
      ["sub_button"]=>
      array(3) {
        [0]=>
        array(4) {
          ["type"]=>
          string(4) "view"
          ["name"]=>
          string(6) "搜索"
          ["url"]=>
          string(20) "http://www.soso.com/"
          ["sub_button"]=>
          array(0) {
          }
        }
        [1]=>
        array(4) {
          ["type"]=>
          string(4) "view"
          ["name"]=>
          string(6) "视频"
          ["url"]=>
          string(16) "http://v.qq.com/"
          ["sub_button"]=>
          array(0) {
          }
        }
        [2]=>
        array(4) {
          ["type"]=>
          string(5) "click"
          ["name"]=>
          string(15) "赞一下我们"
          ["key"]=>
          string(10) "V1001_GOOD"
          ["sub_button"]=>
          array(0) {
          }
        }
      }
    }
  }
}
```

请求成功返回列表：

| 参数         | 说明                         |
| ---------- | -------------------------- |
| button     | 一级菜单数组，个数应为1~3个            |
| sub_button | 二级菜单数组，个数应为1~5个            |
| type       | 菜单的响应动作类型                  |
| name       | 菜单标题，不超过16个字节，子菜单不超过40个字节  |
| key        | 菜单KEY值，用于消息接口推送，不超过128字节   |
| url        | 网页链接，用户点击菜单可打开链接，不超过1024字节 |

请求失败示例：

```php
string(66) "错误码:46003, 错误信息:menu no exist hint: [uhOYFA0305vr22]"
```



###### 8.3  获取自定义菜单配置

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| current |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Menu');
$ret = $WxApi->current();
```

请求成功返回示例：

```php

array(2) {
  ["is_menu_open"]=>
  int(1)
  ["selfmenu_info"]=>
  array(1) {
    ["button"]=>
    array(2) {
      [0]=>
      array(3) {
        ["type"]=>
        string(5) "click"
        ["name"]=>
        string(12) "今日歌曲"
        ["key"]=>
        string(17) "V1001_TODAY_MUSIC"
      }
      [1]=>
      array(2) {
        ["name"]=>
        string(6) "菜单"
        ["sub_button"]=>
        array(1) {
          ["list"]=>
          array(3) {
            [0]=>
            array(3) {
              ["type"]=>
              string(4) "view"
              ["name"]=>
              string(6) "搜索"
              ["url"]=>
              string(20) "http://www.soso.com/"
            }
            [1]=>
            array(3) {
              ["type"]=>
              string(4) "view"
              ["name"]=>
              string(6) "视频"
              ["url"]=>
              string(16) "http://v.qq.com/"
            }
            [2]=>
            array(3) {
              ["type"]=>
              string(5) "click"
              ["name"]=>
              string(15) "赞一下我们"
              ["key"]=>
              string(10) "V1001_GOOD"
            }
          }
        }
      }
    }
  }
}
```

成功返回列表：

| 参数               | 说明                                       |
| ---------------- | ---------------------------------------- |
| is_menu_open     | 菜单是否开启，0代表未开启，1代表开启                      |
| selfmenu_info    | 菜单信息                                     |
| button           | 菜单按钮                                     |
| type             | 菜单的类型，公众平台官网上能够设置的菜单类型有view（跳转网页）、text（返回文本，下同）、img、photo、video、voice。使用API设置的则有8种，详见《自定义菜单创建接口》 |
| name             | 菜单名称                                     |
| value、url、key等字段 | 对于不同的菜单类型，value的值意义不同。官网上设置的自定义菜单：Text:保存文字到value； Img、voice：保存mediaID到value； Video：保存视频下载链接到value； News：保存图文消息到news_info，同时保存mediaID到value； View：保存链接到url。使用API设置的自定义菜单： click、scancode_push、scancode_waitmsg、pic_sysphoto、pic_photo_or_album、	pic_weixin、location_select：保存值到key；view：保存链接到url |
| news_info        | 图文消息的信息                                  |
| title            | 图文消息的标题                                  |
| digest           | 摘要                                       |
| author           | 作者                                       |
| show_cover       | 是否显示封面，0为不显示，1为显示                        |
| cover_url        | 封面图片的URL                                 |
| content_url      | 正文的URL                                   |
| source_url       | 原文的URL，若置空则无查看原文入口                       |



###### 8.4  删除菜单

|  接口名称  | HTTP请求方式 |
| :----: | :------: |
| delete |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Menu');
$ret = $WxApi->delete();
```

请求成功返回示例：

```php
string(2) "ok"
```



### 9.被动回复相关接口（MessageApi.php）



### 10.微信二维码相关接口（QrcodeApi.php）

###### 10.1  创建临时二维码

|  接口名称  | HTTP请求方式 |
| :----: | :------: |
| create |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Qrcode');

$scene_id       = 1; //场景ID
$expire_seconds = 30; //有效期秒

$ret = $WxApi->create($scene_id, $expire_seconds);
```

接口请求列表：

|       参数       | 是否必须 |   类型    | 说明                           |
| :------------: | :--: | :-----: | ---------------------------- |
|    scene_id    |  是   | Int(11) | 场景ID，0<=scene_id<=4294967295 |
| expire_seconds |  是   | Int(11) | 有效期 秒                        |

请求成功返回示例：

```php
array(3) {
  ["ticket"]=>
  string(96) "gQEK8ToAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL1RFT1VJVkRsQS1wNmJISVgtVzJ5AAIEgOJ0VwMEPAAAAA=="
  ["expire_seconds"]=>
  int(60)
  ["url"]=>
  string(43) "http://weixin.qq.com/q/TEOUIVDlA-p6bHIX-W2y"
}
```

请求成功返回列表：

|      参数名       | 描述                                       |
| :------------: | :--------------------------------------- |
|     ticket     | 获取的二维码ticket，凭借此ticket调用[通过ticket换取二维码接口](http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542&token=&lang=zh_CN)可以在有效时间内换取二维码。 |
| expire_seconds | 二维码的有效时间                                 |
|      url       | 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片        |

请求失败返回示例：

```php
string(62) "scene_id 必须为整数,且 不能 小于 0 大于 4294967295"
```



###### 10.2  创建永久二维码 - 场景值ID(Int)

|      接口名称      | HTTP请求方式 |
| :------------: | :------: |
| createLimitInt |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Qrcode');

$scene_id = 2; //场景ID

$ret = $WxApi->createLimitInt($scene_id)
```

接口请求列表：

|    参数    | 是否必须 |   类型    | 说明                           |
| :------: | :--: | :-----: | ---------------------------- |
| scene_id |  是   | Int(11) | 场景ID，0<=scene_id<=4294967295 |

请求成功返回示例：

```php
array(2) {
  ["ticket"]=>
  string(96) "gQGr7zoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL1JFUFY1dVhsV1BvaEZIakt2R195AAIE6jdqVgMEAAAAAA=="
  ["url"]=>
  string(43) "http://weixin.qq.com/q/REPV5uXlWPohFHjKvG_y"
}
```

请求成功返回列表：

|  参数名   | 描述                                       |
| :----: | :--------------------------------------- |
| ticket | 获取的二维码ticket，凭借此ticket调用[通过ticket换取二维码接口](http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542&token=&lang=zh_CN)可以在有效时间内换取二维码。 |
|  url   | 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片        |

请求失败返回示例：

```php
string(62) "scene_id 必须为整数,且 不能 小于 0 大于 4294967295"
```



###### 10.3  创建永久二维码 - 场景值Str(Str)

|      接口名称      | HTTP请求方式 |
| :------------: | :------: |
| createLimitStr |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Qrcode');

$queryStr = 'abc'; //场景ID

$ret = $WxApi->createLimitStr($queryStr);
```

接口请求列表：

|    参数    | 是否必须 |   类型   | 说明   |
| :------: | :--: | :----: | ---- |
| scene_id |  是   | String | 场景ID |

请求成功返回示例：

```php
array(2) {
  ["ticket"]=>
  string(96) "gQE58DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL0trT01ZUlRsRi1wdUNCSmI1V3V5AAIEmzlqVgMEAAAAAA=="
  ["url"]=>
  string(43) "http://weixin.qq.com/q/KkOMYRTlF-puCBJb5Wuy"
}
```

请求成功返回列表：

|  参数名   | 描述                                       |
| :----: | :--------------------------------------- |
| ticket | 获取的二维码ticket，凭借此ticket调用[通过ticket换取二维码接口](http://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542&token=&lang=zh_CN)可以在有效时间内换取二维码。 |
|  url   | 二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片        |

请求失败返回示例：

```php
string(95) "错误码:40053, 错误信息:invalid action info, please check document hint: [3iGzPA0516vr22]"
```



###### 10.4  通过ticket换取二维码

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| show |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Qrcode');

$ticket = 'gQE58DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL0trT01ZU lRsRi1wdUNCSmI1V3V5AAIEmzlqVgMEAAAAAA=='; //场景ID

$ret = $WxApi->show($ticket);
```

接口请求列表：

|   参数   | 是否必须 |   类型   | 说明                                   |
| :----: | :--: | :----: | ------------------------------------ |
| ticket |  是   | String | 获取的二维码ticket，凭借此ticket可以在有效时间内换取二维码。 |

请求成功返回一个二维码的图片。



### 11.接收回调相关接口（ServerApi.php）



### 12.微信Url相关接口（ShortApi.php）

​	将一条长链接转成短链接。

​	主要使用场景： 开发者用于生成二维码的原链接（商品、支付二维码等）太长导致扫码速度和成功率下降，将原长链接通过此接口转成短链接再生成二维码将大大提升扫码速度和成功率。

###### 12.1  生成短连接  

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| url  |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Short');

$long_url = 'http://wap.koudaitong.com/v2/showcase/goods?alias=128wi9shh& spm=h56083&redirect_count=1'; 
$action   = 'long2short';

$ret = $WxApi->url($long_url, $action);
```

接口请求列表：

| 参数       | 是否必须 | 说明                                       |
| -------- | ---- | ---------------------------------------- |
| action   | 是    | 此处填long2short，代表长链接转短链接                  |
| long_url | 是    | 需要转换的长链接，支持http://、https://、weixin://wxpay 格式的url |

请求成功返回示例：

```php
string(25) "http://w.url.cn/s/A6CwkUZ"  //短链接
```

请求失败返回示例：

```php
string(62) "错误码:-1, 错误信息:system error hint: [k9Hm0a0192vr23]"
```



###### 12.2  获取当前url

|  接口名称   | HTTP请求方式 |
| :-----: | :------: |
| current |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('Short');
$ret = $WxApi->current();
```

请求成功返回示例：

```php
string(55) "http://www.local-test.com/index.php/Home/Short/current"
```



### 13.模板相关接口（TemplateApi.php）

###### 13.1  设置所属行业

|      接口名称      | HTTP请求方式 |
| :------------: | :------: |
| apiSetIndustry |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Template');

$industry_id1   = '1'; 
$industry_id2   = '2';

$ret = $WxApi->apiSetIndustry($industry_id1, $industry_id2);
```

接口请求参数列表：

| 参数           | 是否必须 | 说明            |
| ------------ | ---- | ------------- |
| industry_id1 | 是    | 公众号模板消息所属行业编号 |
| industry_id2 | 是    | 公众号模板消息所属行业编号 |

**行业代码查询**

| 主行业     | 副行业         | 代码   |
| ------- | ----------- | ---- |
| IT科技    | 互联网/电子商务    | 1    |
| IT科技    | IT软件与服务     | 2    |
| IT科技    | IT硬件与设备     | 3    |
| IT科技    | 电子技术        | 4    |
| IT科技    | 通信与运营商      | 5    |
| IT科技    | 网络游戏        | 6    |
| 金融业     | 银行          | 7    |
| 金融业     | 基金\|理财\|信托  | 8    |
| 金融业     | 保险          | 9    |
| 餐饮      | 餐饮          | 10   |
| 酒店旅游    | 酒店          | 11   |
| 酒店旅游    | 旅游          | 12   |
| 运输与仓储   | 快递          | 13   |
| 运输与仓储   | 物流          | 14   |
| 运输与仓储   | 仓储          | 15   |
| 教育      | 培训          | 16   |
| 教育      | 院校          | 17   |
| 政府与公共事业 | 学术科研        | 18   |
| 政府与公共事业 | 交警          | 19   |
| 政府与公共事业 | 博物馆         | 20   |
| 政府与公共事业 | 公共事业\|非盈利机构 | 21   |
| 医药护理    | 医药医疗        | 22   |
| 医药护理    | 护理美容        | 23   |
| 医药护理    | 保健与卫生       | 24   |
| 交通工具    | 汽车相关        | 25   |
| 交通工具    | 摩托车相关       | 26   |
| 交通工具    | 火车相关        | 27   |
| 交通工具    | 飞机相关        | 28   |
| 房地产     | 建筑          | 29   |
| 房地产     | 物业          | 30   |
| 消费品     | 消费品         | 31   |
| 商业服务    | 法律          | 32   |
| 商业服务    | 会展          | 33   |
| 商业服务    | 中介服务        | 34   |
| 商业服务    | 认证          | 35   |
| 商业服务    | 审计          | 36   |
| 文体娱乐    | 传媒          | 37   |
| 文体娱乐    | 体育          | 38   |
| 文体娱乐    | 娱乐休闲        | 39   |
| 印刷      | 印刷          | 40   |
| 其它      | 其它          | 41   |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(83) "错误码:43100, 错误信息:change template too frequently hint: [OLQx5a0823vr20]"
```



###### 13.2  获得模板ID

|      接口名称      | HTTP请求方式 |
| :------------: | :------: |
| apiAddTemplate |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Template');
$template_id_short   = 'TM00015'; 
$ret = $WxApi->apiAddTemplate($template_id_short);
```

接口请求参数列表：

| 参数                | 是否必须 | 说明                                |
| ----------------- | ---- | --------------------------------- |
| template_id_short | 是    | 模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式 |

请求成功返回示例：

```php
string(43) "sxi_AJ0pXpd6ngfLQ4bBasUVIsuN6YaG1Xc1dDxf7ps"
```



###### 13.3  发送模板消息

| 接口名称 | HTTP请求方式 |
| :--: | :------: |
| send |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('Template');
		
$touser = 'odkJ9uJ9qhdvV3SVjC5-n2roAv_s';
$template_id = 'sA7Z3V_REY7DXj5PeGExhVfCbiUz9PRhclaEaPmSlf4';
$url = 'http://www.baidu.com';
$data = array(
  'first'    => array('value' => '秒杀成功', 'color' => '#173177'),
  'keyword1' => array('value' => 'MacBook Pro', 'color' => '#173177'),
  'keyword2' => array('value' => '1元', 'color' => '#173177'),
  'remark'   => array('value' => '点击付款', 'color' => '#173177')
);

$ret = $WxApi->send($touser, $template_id, $url, $data);
```

接口请求参数列表：



请求成功返回示例：

```php
int(407741624)
```



### 14.用户相关接口（UserApi.php）

###### 14.1  获取用户信息

|    接口名称    | HTTP请求方式 |
| :--------: | :------: |
| getUserMsg |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('User');

$openid = 'odkJ9uE2f1BTY2rBKpFKvCcVoMvM';

$ret = $WxApi->getUserMsg($openid);
```

接口请求参数列表：

| 参数     | 是否必须 | 说明               |
| ------ | ---- | ---------------- |
| openid | 是    | 普通用户的标识，对当前公众号唯一 |

请求成功返回示例：

```php
array(14) {
  ["subscribe"]=>
  int(1)
  ["openid"]=>
  string(28) "odkJ9uE2f1BTY2rBKpFKvCcVoMvM"
  ["nickname"]=>
  string(18) "阳光真美"
  ["sex"]=>
  int(2)
  ["language"]=>
  string(5) "zh_CN"
  ["city"]=>
  string(0) ""
  ["province"]=>
  string(0) ""
  ["country"]=>
  string(9) "安道尔"
  ["headimgurl"]=>
  string(117) "http://wx.qlogo.cn/mmopen/PiajxSqBRaELLTIXyb2g0ZshhJH8OWgIGDZVicOpSUmMIDZS0ZPcNBt38YaO9PB60KIgMCP1owxlG8V6TLUpvib2A/0"
  ["subscribe_time"]=>
  int(1444456066)
  ["unionid"]=>
  string(28) "oh8xHxB0f-Aftn141bMdTwfnEVAo"
  ["remark"]=>
  string(0) ""
  ["groupid"]=>
  int(0)
  ["tagid_list"]=>
  array(0) {
  }
}
```

请求成功返回列表：

| 参数             | 说明                                       |
| -------------- | ---------------------------------------- |
| subscribe      | 用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。 |
| openid         | 用户的标识，对当前公众号唯一                           |
| nickname       | 用户的昵称                                    |
| sex            | 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知            |
| city           | 用户所在城市                                   |
| country        | 用户所在国家                                   |
| province       | 用户所在省份                                   |
| language       | 用户的语言，简体中文为zh_CN                         |
| headimgurl     | 用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。 |
| subscribe_time | 用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间           |
| unionid        | 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。           |
| remark         | 公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注 |
| groupid        | 用户所在的分组ID（兼容旧的用户分组接口）                    |
| tagid_list     | 用户被打上的标签ID列表                             |

请求失败返回示例：

```php
string(67) "错误码:40003, 错误信息:invalid openid hint: [QHjQ5a0370vr18]"
```



###### 14.2  批量获取用户基本信息

|    接口名称     | HTTP请求方式 |
| :---------: | :------: |
| getUserList |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('User');

$user_list              = [];
$user_list['user_list'] = [
  ['openid' => 'odkJ9uEnEIJSNnr0Bk9_eA70ZS8o', 'lang' => 'zh-CN'],
  ['openid' => 'odkJ9uE2f1BTY2rBKpFKvCcVoMvM', 'lang' => 'zh-CN'],
];

$ret = $WxApi->getUserList($user_list);
```

接口请求参数列表：

| 参数     | 是否必须 | 说明                                       |
| ------ | ---- | ---------------------------------------- |
| openid | 是    | 用户的标识，对当前公众号唯一                           |
| lang   | 否    | 国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语，默认为zh-CN |

请求成功返回示例：

```php
array(2) {
  [0]=>
  array(14) {
    ["subscribe"]=>
    int(1)
    ["openid"]=>
    string(28) "odkJ9uDUz26RY-7DN1mxkznfo9xU"
    ["nickname"]=>
    string(3) "妞"
    ["sex"]=>
    int(2)
    ["language"]=>
    string(5) "zh_CN"
    ["city"]=>
    string(7) "Haidian"
    ["province"]=>
    string(7) "Beijing"
    ["country"]=>
    string(5) "China"
    ["headimgurl"]=>
    string(131) "http://wx.qlogo.cn/mmopen/R8p5f5Oic6pCGRXk7AmkBYXc9du3CibxicXluKzgzUeoCFnzs6Y74XjTibx3UywicGVzQibzHJkLo3npc5NU92SUn4SibsAh1nG5IsQ/0"
    ["subscribe_time"]=>
    int(1462877622)
    ["unionid"]=>
    string(28) "oh8xHxPKHoaW1pk8ArKJ5wMIVc-4"
    ["remark"]=>
    string(0) ""
    ["groupid"]=>
    int(0)
    ["tagid_list"]=>
    array(0) {
    }
  }
  [1]=>
  array(14) {
    ["subscribe"]=>
    int(1)
    ["openid"]=>
    string(28) "odkJ9uE2f1BTY2rBKpFKvCcVoMvM"
    ["nickname"]=>
    string(18) "阳光真美"
    ["sex"]=>
    int(2)
    ["language"]=>
    string(5) "zh_CN"
    ["city"]=>
    string(0) ""
    ["province"]=>
    string(0) ""
    ["country"]=>
    string(7) "Andorra"
    ["headimgurl"]=>
    string(117) "http://wx.qlogo.cn/mmopen/PiajxSqBRaELLTIXyb2g0ZshhJH8OWgIGDZVicOpSUmMIDZS0ZPcNBt38YaO9PB60KIgMCP1owxlG8V6TLUpvib2A/0"
    ["subscribe_time"]=>
    int(1444456066)
    ["unionid"]=>
    string(28) "oh8xHxB0f-Aftn141bMdTwfnEVAo"
    ["remark"]=>
    string(0) ""
    ["groupid"]=>
    int(0)
    ["tagid_list"]=>
    array(0) {
    }
  }
}
```

请求成功返回列表：

| 参数             | 说明                                       |
| -------------- | ---------------------------------------- |
| subscribe      | 用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息，只有openid和UnionID（在该公众号绑定到了微信开放平台账号时才有）。 |
| openid         | 用户的标识，对当前公众号唯一                           |
| nickname       | 用户的昵称                                    |
| sex            | 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知            |
| city           | 用户所在城市                                   |
| country        | 用户所在国家                                   |
| province       | 用户所在省份                                   |
| language       | 用户的语言，简体中文为zh_CN                         |
| headimgurl     | 用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。 |
| subscribe_time | 用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间           |
| unionid        | 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。           |
| remark         | 公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注 |
| groupid        | 用户所在的分组ID（暂时兼容用户分组旧接口）                   |
| tagid_list     | 用户被打上的标签ID列表                             |

请求失败返回示例：

```php
string(67) "错误码:40003, 错误信息:invalid openid hint: [O5Z2Ra0873vr22]"
```



###### 14.3  获取用户Openid列表

|       接口名称        | HTTP请求方式 |
| :---------------: | :------: |
| getUserOpenidList |   get    |

接口请求代码示例：

```php
$WxApi = Api::factory('User');

$next_openid = '';

$ret = $WxApi->getUserOpenidList($next_openid);
```

接口请求参数列表：

| 参数          | 是否必须 | 说明                      |
| ----------- | ---- | ----------------------- |
| next_openid | 是    | 第一个拉取的OPENID，不填默认从头开始拉取 |

接口请求成功示例：

```php
array(4) {
  ["total"]=>
  int(3)
  ["count"]=>
  int(3)
  ["data"]=>
  array(1) {
    ["openid"]=>
    array(3) {
      [0]=>
      string(28) "odkJ9uEnEIJSNnr0Bk9_eA70ZS8o"
      [1]=>
      string(28) "odkJ9uGkUJEOANc70XPM42cvPw10"
      [2]=>
      string(28) "odkJ9uPuWos7IR5ksT9sA0bV4MTY"
    }
  }
  ["next_openid"]=>
  string(28) "odkJ9uPuWos7IR5ksT9sA0bV4MTY"
}

```

请求成功返回列表：

| 参数          | 说明                    |
| ----------- | --------------------- |
| total       | 关注该公众账号的总用户数          |
| count       | 拉取的OPENID个数，最大值为10000 |
| data        | 列表数据，OPENID的列表        |
| next_openid | 拉取列表的最后一个用户的OPENID    |



###### 14.4  设置用户备注名

|     接口名称      | HTTP请求方式 |
| :-----------: | :------: |
| setUserRemark |   post   |

接口请求代码示例：

```php
$WxApi = Api::factory('User');

$openid = 'odkJ9uEnEIJSNnr0Bk9_eA70ZS8o';
$remark = '杨Boss';

$ret = $WxApi->setUserRemark($openid, $remark);
```

接口请求参数列表：

| 参数     | 说明               |
| ------ | ---------------- |
| openid | 用户标识             |
| remark | 新的备注名，长度必须小于30字符 |

请求成功返回示例：

```php
string(2) "ok"
```

请求失败返回示例：

```php
string(67) "错误码:40003, 错误信息:invalid openid hint: [l2FChA0127vr21]"
```



*联系QQ:3217834*
