<?php
namespace Wechat;

use Wechat\API\BaseApi;
use Wechat\CacheDriver\BaseDriver;
use Wechat\Api;

/**
 * 微信第三方平台接口 客户端基类.
 *
 * @author Tian.
 */
class ComponentApi
{
    public static  $instantiation   = false;
    private static $selfInstanceMap = []; // 实例列表;
    private static $postQueryStr    = []; // post数据时 需要携带的查询字符串
    private static $CACHE_DRIVER    = ''; // 接口缓存驱动类名
    private static $error           = ''; // 错误信息;
    private static $errorCode       = ''; // 错误码;
    private static $apiData; // 信息;
    private static $APP_ID; // 服务号app_id;
    private static $COMPONENT_APPID; // 第三方COMPONENT_APPID
    private static $COMPONENT_APPSECRET; // 第三方COMPONENT_APPSECRET
    private static $COMPONENT_TOKEN; // 第三方COMPONENT_TOKEN
    private static $COMPONENT_ENCODING_AES_KEY; // 第三方COMPONENT_ENCODING_AES_KEY
    private static $AUTHORIZER_ACCESS_TOKEN; // 第三方给授权服务号的授权token
    private static $API_URL; // 微信接口地址

    /**
     * 第三方平台初始化
     *
     * @param        $appid
     * @param        $component_appid
     * @param        $component_appsecret
     * @param        $component_token
     * @param        $encoding_aes_key
     * @param        $authorizer_access_token
     * @param string $apiurl
     * @param string $cacheDriver
     */
    public static function init($appid, $component_appid, $component_appsecret, $component_token, $encoding_aes_key, $authorizer_access_token, $apiurl = 'https://api.weixin.qq.com/', $cacheDriver = 'File')
    {
        self::$APP_ID                     = $appid;
        self::$COMPONENT_APPID            = $component_appid;
        self::$COMPONENT_APPSECRET        = $component_appsecret;
        self::$COMPONENT_TOKEN            = $component_token;
        self::$COMPONENT_ENCODING_AES_KEY = $encoding_aes_key;
        self::$AUTHORIZER_ACCESS_TOKEN    = $authorizer_access_token;
        self::$CACHE_DRIVER               = $cacheDriver;
        self::$API_URL                    = $apiurl;
    }

    /**
     * 获取授权服务号的token
     *
     * @author YangYang <yangyang@iwork365.com>
     *
     * @date   2017-01-09
     *
     * @return mixed
     */
    public static function getAccessToken()
    {

        return self::$AUTHORIZER_ACCESS_TOKEN;
    }

    /**
     * 工厂+多例模式 获取接口实例.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param string $className 接口类名.
     *
     * @return object
     */
    public static function factory($className)
    {
        if (!$className || !is_string($className)) {
            exit('类名参数不正确');
        }

        $className = __NAMESPACE__ . '\\API\\' . $className . 'Api';

        if (!array_key_exists($className, self::$selfInstanceMap)) {
            $api = new $className();
            if (!$api instanceof BaseApi) {
                exit($className . ' 必须继承 BaseApi');
            }
            self::$selfInstanceMap[$className] = $api;
        }
        self::$instantiation = true;
        Api::$instantiation  = false;

        return self::$selfInstanceMap[$className];
    }

    /**
     * 设置错误信息.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param string $errorText 错误信息
     */
    public static function setError($errorText)
    {
        self::$error = $errorText;
    }

    /**
     * 获取错误信息.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @return string
     */
    public static function getError()
    {
        return self::$error;
    }

    /**
     * 设置api原始返回值
     *
     * @param $apiData
     */
    public static function setApiData($apiData)
    {
        self::$apiData = $apiData;
    }

    /**
     * 设置post操作的get参数.
     *
     * @author Tian
     *
     * @date   2015-08-03
     *
     * @param string $name  参数名
     * @param string $value 值
     */
    public static function setPostQueryStr($name, $value)
    {
        self::$postQueryStr[$name] = $value;
    }

    /**
     * 获取api原始返回值
     *
     */
    public static function getApiData()
    {
        return self::$apiData;
    }

    /**
     * 设置错误码.
     *
     * @author Cui
     *
     * @date   2015-07-27
     *
     * @param string $errorCode 错误Code
     */
    public static function setErrorCode($errorCode)
    {
        self::$errorCode = $errorCode;
    }

    /**
     * 获取微信服务器ip.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @return array
     */

    public static function getWxIpList()
    {
        $module   = 'getcallbackip';
        $queryStr = [];
        $res      = self::_get($module, '', $queryStr);
        if (!$res) {
            exit(self::getError());
        }

        return $res;
    }

    /**
     * 获取AppId
     *
     * @return string AppId
     */
    public static function getAppId()
    {
        return self::$APP_ID;
    }

    public static function getComponentAppId()
    {
        return self::$COMPONENT_APPID;
    }

    /**
     * 获取ENCODING_AES_KEY
     *
     * @return string ENCODING_AES_KEY
     */
    public static function getEncoding_Aes_Key()
    {
        return self::$COMPONENT_ENCODING_AES_KEY;
    }

    /**
     * 获取TOKEN
     *
     * @return string TOKEN
     */
    public static function getToken()
    {
        return self::$COMPONENT_TOKEN;
    }

    /**
     * 获取AppSecret
     *
     * @return string AppSecret
     */
    public static function getAppSecret()
    {
        return self::$COMPONENT_APPSECRET;
    }

    /**
     * 用get的方式访问接口.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param string $module   指定接口模块
     * @param string $node     指定接口模块的节点
     * @param array  $queryStr 查询字符串
     * @param bool   $arsort   是否排序
     * @param string $apitype  api类型
     *
     * @return array|bool 错误时返回false
     */
    public static function _get($module, $node, $queryStr = [], $arsort = true, $apitype = 'cgi-bin')
    {
        //不需要 token 参数
        $no_module = ['token', 'showqrcode'];

        $no_apitye = ['sns'];

        if (in_array($apitype, $no_apitye) || in_array($module, $no_module)) {
            //$queryStr = $queryStr;
        } elseif ($module != 'token') {
            $info = self::getAccessToken();

            if (false == $info) {
                return false;
            }
            $queryStr['access_token'] = $info;
        }

        if ($arsort) {
            arsort($queryStr);
        }

        $queryStr = http_build_query($queryStr);

        if (!empty($node)) {
            $node = '/' . $node;
        }

        $apiUrl = rtrim(self::$API_URL . $apitype . '/' . $module . $node, '/');
        $apiUrl .= '?' . $queryStr;

        $apiUrl = urldecode($apiUrl);
        $ch     = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $res      = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $header = '';
        $body   = $res;
        if ($httpcode == 200) {
            list($header, $body) = explode("\r\n\r\n", $res, 2);
            $header = self::http_parse_headers($header);
        }

        $result           = [];
        $result['info']   = $body;
        $result['header'] = $header;
        $result['status'] = $httpcode;

        $rest = self::packData($result);
        if ($rest == 'retry') {
            return self::get_retry($apiUrl);
        } else {
            return $rest;
        }
    }

    /**
     * 用post的方式访问接口.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param string $module     指定接口模块
     * @param string $node       指定接口模块的节点
     * @param array  $data       要发送的数据
     * @param bool   $jsonEncode 是否转换为jsons数据
     * @param string $apitype    Api类型
     *
     * @return array|bool 错误时返回false;
     */
    public static function _post($module, $node, $data, $jsonEncode = true, $apitype = 'cgi-bin')
    {
        //$postQueryStr = self::$postQueryStr;

        $postQueryStr['access_token'] = self::getAccessToken();

        if (false == $postQueryStr) {
            return false;
        }

        if (!empty($node)) {
            $node = '/' . $node;
        }

        $apiUrl = rtrim(self::$API_URL . $apitype . '/' . $module . $node, '/');

        asort($postQueryStr);

        $postQueryStr = http_build_query($postQueryStr);

        $apiUrl .= '?' . $postQueryStr;

        if ($jsonEncode) {
            if (is_array($data)) {
                if (!defined('JSON_UNESCAPED_UNICODE')) {
                    // 解决php 5.3版本 json转码时 中文编码问题.
                    $data = json_encode($data);
                    $data = preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $data);
                } else {
                    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
                }
            }
        }

        $apiUrl = urldecode($apiUrl);
        $ch     = curl_init();

        // CURLOPT_SAFE_UPLOAD php 5.5 中添加，默认值为false，5.6中默认值为true，7.0+ 没有该设置项
        if (version_compare(PHP_VERSION, '5.5', '>=') && version_compare(PHP_VERSION, '7', '<')) {
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        }

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $res      = trim(curl_exec($ch));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $header = '';
        $body   = $res;
        if ($httpcode == 200) {
            list($header, $body) = explode("\r\n\r\n", $res, 2);
            //list($header, $body) = explode("keep-alive", $res, 2);
            $header = self::http_parse_headers($header);
        }

        $result           = [];
        $result['info']   = $body;
        $result['header'] = $header;
        $result['status'] = $httpcode;

        $rest = self::packData($result);

        if ($rest === 'retry') {
            return self::post_retry($apiUrl, $data);
        } else {
            return $rest;
        }
    }

    /**
     * 对接口返回的数据进行验证和组装.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param array $apiReturnData 由_post|| _get方法返回的数据.
     *
     * @return array|bool
     */
    public static function packData($apiReturnData)
    {
        $status     = $apiReturnData['status'];
        $header     = $apiReturnData['header'];
        $returnData = $apiReturnData['info'];

        if ($status != 200 && empty($returnData)) {
            self::setError('接口服务器连接失败.');

            return false;
        }
        $apiReturnData     = json_decode($returnData, true);
        $apiData           = [];
        $apiData['status'] = $status;
        $apiData['header'] = $header;
        $apiData['info']   = $apiReturnData;

        self::setApiData($apiData);

        if (!$apiReturnData && substr($header['Content-Type'], 0, 16) != 'application/json') {
            $apiReturnData            = [];
            $apiReturnData['content'] = $returnData;
            $apiReturnData['type']    = $header['Content-Type'];
            $apiReturnData['size']    = $header['Content-Length'];

            return $apiReturnData;
        }

        if ($status != 200 && !$apiReturnData) {
            self::setError($returnData);

            return false;
        }

        if (isset($apiReturnData['errcode']) && ($apiReturnData['errcode'] == 42001 || $apiReturnData['errcode'] == 40001)) {
            $error = '错误码:' . $apiReturnData['errcode'] . ', 错误信息:' . $apiReturnData['errmsg'] . '-已重新刷新access_token';

            //强制刷新 AccessToken
            self::getAccessToken();

            self::setError($error);

            $rest = 'retry';

            return $rest;
        }

        if (isset($apiReturnData['errcode']) && $apiReturnData['errcode'] != 0) {
            $error = '错误码:' . $apiReturnData['errcode'] . ', 错误信息:' . $apiReturnData['errmsg'];

            self::setError($error);

            return false;
        }

        if (isset($apiReturnData['errcode'])) {
            unset($apiReturnData['errcode']);
        }

        if (count($apiReturnData) > 1 && isset($apiReturnData['errmsg'])) {
            unset($apiReturnData['errmsg']);
        }

        if (count($apiReturnData) == 1) {
            $apiReturnData = reset($apiReturnData);
        }

        return $apiReturnData;
    }

    /**
     * 接口加密方法.
     *
     * @param string $data   要加密的字符串
     * @param string $key    加密密钥
     * @param int    $expire 过期时间 单位 秒
     *
     * @return string
     */
    public static function encrypt($data, $key, $expire = 0)
    {
        $data = base64_encode($data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }

        $str = sprintf('%010d', $expire ? $expire + time() : 0);

        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
        }

        return rawurlencode(str_replace('=', '', base64_encode($str)));
    }

    /**
     * 解析头信息
     *
     * @param $raw_headers
     *
     * @return array
     */
    public static function http_parse_headers($raw_headers)
    {
        $headers = [];
        $key     = ''; // [+]

        foreach (explode("\n", $raw_headers) as $i => $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    // $tmp = array_merge($headers[$h[0]], array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge($headers[$h[0]], [trim($h[1])]); // [+]
                } else {
                    // $tmp = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge([$headers[$h[0]]], [trim($h[1])]); // [+]
                }

                $key = $h[0]; // [+]
            } else {
                // [+]
                // [+]
                if (substr($h[0], 0, 1) == "\t") {
                    // [+]
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } // [+]
                elseif (!$key) {
                    // [+]
                    $headers[0] = trim($h[0]);
                }
                trim($h[0]); // [+]
            } // [+]
        }

        return $headers;
    }

    /**
     * 缓存方法
     *
     * @param string $name    缓存名
     * @param string $value   缓存值 如果不输入值 则根据缓存名返回缓存值.
     * @param int    $expires 缓存过期时间 默认0 即永不超时. 单位秒
     *
     * @return bool|null|string
     */
    public static function cache($name, $value = '', $expires = 0)
    {
        if (!$name || !is_string($name)) {
            self::setError('参数错误!');

            return false;
        }

        /** @var BaseDriver $cacheDriver */
        static $cacheDriver;

        if (!isset($cacheDriver)) {
            $cacheDriver = __NAMESPACE__ . '\\CacheDriver\\' . self::$CACHE_DRIVER . 'Driver';
            $cacheDriver = new $cacheDriver(__DIR__ . '/Cache/');
        }

        if (!$value && $value !== 0) {
            $value = $cacheDriver->_get($name);
            if (false == $value) {
                $value = null;
            }

            return $value;
        }

        $res = $cacheDriver->_set($name, $value, $expires);

        return $res ? true : false;
    }

    //token 刷新后重试 post
    public static function post_retry($apiUrl, $data)
    {
        $urlarr = parse_url($apiUrl);
        parse_str($urlarr['query'], $parr);

        usleep(500000);

        $parr['access_token'] = self::getAccessToken();

        $apiUrl = $urlarr['scheme'] . '://' . $urlarr['host'] . $urlarr['path'];
        $apiUrl .= '?' . http_build_query($parr);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $res      = trim(curl_exec($ch));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $header = '';
        $body   = $res;
        if ($httpcode == 200) {
            list($header, $body) = explode("\r\n\r\n", $res, 2);
            //list($header, $body) = explode("keep-alive", $res, 2);
            $header = self::http_parse_headers($header);
        }

        $result           = [];
        $result['info']   = $body;
        $result['header'] = $header;
        $result['status'] = $httpcode;

        $rest_retry = self::packData($result);
        if ($rest_retry === 'retry') {
            return false;
        }

        return $rest_retry;
    }

    //token 刷新后重试 get
    public static function get_retry($apiUrl)
    {
        $urlarr = parse_url($apiUrl);
        parse_str($urlarr['query'], $parr);

        usleep(500000);

        $parr['access_token'] = self::getAccessToken();

        $apiUrl = $urlarr['scheme'] . '://' . $urlarr['host'] . $urlarr['path'];
        $apiUrl .= '?' . http_build_query($parr);

        $apiUrl = urldecode($apiUrl);
        $ch     = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $res      = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $header = '';
        $body   = $res;
        if ($httpcode == 200) {
            list($header, $body) = explode("\r\n\r\n", $res, 2);
            $header = self::http_parse_headers($header);
        }

        $result           = [];
        $result['info']   = $body;
        $result['header'] = $header;
        $result['status'] = $httpcode;

        $rest_retry = self::packData($result);

        if ($rest_retry === 'retry') {
            return false;
        }

        return $rest_retry;
    }
}
