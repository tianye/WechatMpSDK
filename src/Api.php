<?php
namespace Wechat;

use Wechat\API\BaseApi;

/**
 * 微信接口 客户端基类.
 *
 * @author Tian.
 */
class Api
{
    private static $error           = ''; // 错误信息;
    private static $selfInstanceMap = []; // 实例列表;
    private static $postQueryStr    = []; // post数据时 需要携带的查询字符串

    private static $apiData; //返回的数据

    private static $API_URL; // 微信接口地址

    private static $APP_ID; // 应用ID;
    private static $APP_SECRET; // 应用密钥;

    private static $ORIGINAL_ID; // 原始ID;

    private static $TOKEN; //TOKEN
    private static $ENCODING_AES_KEY; //ENCODING_AES_KEY

    /**
     * 接口初始化, 必须执行此方法才可以使用接口.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param string $apiurl     微信接口地址
     * @param string $appid      服务号APP_ID
     * @param string $appsecret  服务号APP_SECRET
     * @param string $originalid 服务号ORIGINAL_ID
     */
    public static function init($appid, $appsecret, $originalid = '', $token, $encoding_aes_key, $apiurl = 'https://api.weixin.qq.com/')
    {
        self::$API_URL          = $apiurl ? $apiurl : 'https://api.weixin.qq.com/';
        self::$APP_ID           = $appid;
        self::$APP_SECRET       = $appsecret;
        self::$ORIGINAL_ID      = $originalid;
        self::$TOKEN            = $token;
        self::$ENCODING_AES_KEY = $encoding_aes_key;
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
     */
    public static function setApiData($apiData)
    {
        self::$apiData = $apiData;
    }

    /**
     * 获取api原始返回值
     *
     * @return array
     */
    public static function getApiData()
    {
        return self::$apiData;
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
     * 设置API_URL参数.
     *
     * @author Tian
     *
     * @date   2015-08-03
     *
     * @param string $url 参数名
     */
    public static function setApiUrl($url)
    {
        self::$API_URL = $url;
    }

    /**
     * 获取允许访问的token.
     *
     * @author Tian
     *
     * @param bool $jus 强制刷新
     *
     * @date   2015-12-08
     *
     * @return string
     */
    public static function getAccessToken($jus = false)
    {
        $key = self::$APP_ID . 'access_token';
        //$token = self::cache($key);
        $token = S($key);
        if (false == $token || $jus) {
            $appid     = self::$APP_ID;
            $appsecert = self::$APP_SECRET;
            $module    = 'token';
            $queryStr  = [
                'grant_type' => 'client_credential',
                'appid'      => $appid,
                'secret'     => $appsecert,
            ];

            $res = self::_get($module, '', $queryStr, false);
            if (false === $res) {
                exit('获取AccessToken失败!');
            }

            $token = $res['access_token'];

            //self::cache($key, $token, 7200 - 300);

            S($key, $token, 3000);
        }

        return $token;
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

    /**
     * 获取ENCODING_AES_KEY
     *
     * @return string ENCODING_AES_KEY
     */
    public static function getEncoding_Aes_Key()
    {
        return self::$ENCODING_AES_KEY;
    }

    /**
     * 获取TOKEN
     *
     * @return string TOKEN
     */
    public static function getToken()
    {
        return self::$TOKEN;
    }

    /**
     * 获取AppSecret
     *
     * @return string AppSecret
     */
    public static function getAppSecret()
    {
        return self::$APP_SECRET;
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
     * @param array  $header   http头部附加信息
     *
     * @return array 错误时返回false
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
     *
     * @return array 错误时返回false;
     */
    public static function _post($module, $node, $data, $jsonEncode = true, $apitype = 'cgi-bin')
    {
        $postQueryStr = self::$postQueryStr;

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

        $php_version = explode('-', phpversion());
        $php_version = $php_version[0];
        if (strnatcasecmp($php_version, '5.6.0') >= 0) {
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

    //token 刷新后重试 post
    public static function post_retry($apiUrl, $data)
    {
        $urlarr = parse_url($apiUrl);
        parse_str($urlarr['query'], $parr);

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

    /**
     * 对接口返回的数据进行验证和组装.
     *
     * @author Tian
     *
     * @date   2015-12-08
     *
     * @param array $apiReturnData 由_post|| _get方法返回的数据.
     *
     * @return array
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
        $apiReturnData = json_decode($returnData, true);

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

        if (isset($apiReturnData['errcode']) && $apiReturnData['errcode'] == 42001) {
            $error = '错误码:' . $apiReturnData['errcode'] . ', 错误信息:' . $apiReturnData['errmsg'] . '-已重新刷新access_token';

            //强制刷新 AccessToken
            self::getAccessToken(true);

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
}
