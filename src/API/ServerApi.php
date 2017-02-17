<?php
namespace Wechat\API;

use Wechat\Api;
use Wechat\Utils\Code\WXBizMsgCrypt;

/**
 * 微信接收回调相关接口.
 *
 * @author Tian.
 */
class ServerApi extends BaseApi
{
    private static $XML_OBJECT;

    /**
     * 监听
     *
     * @param string          $target
     * @param string|callable $event
     * @param callable        $callback
     *
     * @return bool Server
     */

    public function on($target, $event, $callback = null)
    {
        $echostr        = isset($_GET['echostr']) ? $_GET['echostr'] : null;
        $checkSignature = $this->checkSignature();

        if (!$checkSignature) {
            exit("签名验证失败");
        }

        if (!empty($echostr)) {
            echo $echostr;
            exit;
        }

        if (is_null($callback)) {
            $callback = $event;
            $event    = '*';
        }

        if (!is_callable($callback)) {
            exit("$callback 不是一个可调用的函数或方法");
        }

        $rest = $this->$target($event);

        $datas         = [];
        $datas['data'] = $rest;

        if (!empty($rest)) {
            $rest_user                 = [];
            $rest_user['ToUserName']   = $rest['FromUserName'];
            $rest_user['FromUserName'] = $rest['ToUserName'];
            $rest_msg                  = call_user_func_array($callback, $datas);

            if ($rest_msg == 'success') {
                exit('success');
            }

            $rest_array = array_merge($rest_user, $rest_msg);

            $rest_xml = $this->arrayToXml($rest_array);

            $msg_signature = isset($_GET['msg_signature']) ? $_GET['msg_signature'] : null;
            if (!empty($msg_signature) && $msg_signature != '') {
                $rest_xml = $this->encryptMsg($rest_xml);
            }

            exit($rest_xml);
        } else {
            return false;
        }
    }

    /**
     * 验证签名
     *
     * string $signature [签名]
     * int    $timestamp [时间戳]
     * string $nonce     [随机字符串]
     *
     * @param string $token [token]
     *
     * @return mixed|bool
     */
    public function checkSignature($token = null)
    {
        $config_token = Api::getToken();
        $signature    = isset($_GET['signature']) ? $_GET['signature'] : null;
        $timestamp    = isset($_GET['timestamp']) ? $_GET['timestamp'] : null;
        $nonce        = isset($_GET['nonce']) ? $_GET['nonce'] : null;
        $token        = empty($token) ? $config_token : $token;
        $tmpArr       = [$token, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature && $signature != null) {
            return $this;
        }

        return false;
    }

    /**
     * 生成签名 - 用作 转发到第三方
     *
     * @param string $token
     * @param string $time
     * @param string $nonce
     *
     * @return bool|string
     */
    public function makeSignature($token = null, $time = null, $nonce = null)
    {
        if (!is_string($token) || empty($token)) {
            exit("$token 参数错误");
        }

        $timestamp = !empty($time) ? $time : $_GET['timestamp'];
        $nonce     = !empty($nonce) ? $nonce : $_GET['nonce'];
        $tmpArr    = [$token, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        return $tmpStr;
    }

    /**
     * 推送到第三方
     *
     * @param string $url      [地址]
     * @param string $token    [token]
     * @param string $xml      [XML]
     * @param bool   $encipher [是否加密]
     *
     * @return array|bool XML
     */
    public function receiveAgent($url = '', $token = '', $xml = '', $encipher = false)
    {
        $object = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $object = json_decode(json_encode($object), true);

        if (isset($object['Encrypt']) && !empty($object['Encrypt']) && $object['Encrypt'] != '') {
            $xml = $this->decryptMsg($xml);
        }

        if ($encipher) {
            $xml = $this->encryptMsg($xml);

            $object_enc                    = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
            $object_enc                    = json_decode(json_encode($object_enc), true);
            $postQueryStr                  = [];
            $postQueryStr['timestamp']     = $object_enc['TimeStamp'];
            $postQueryStr['nonce']         = $object_enc['Nonce'];
            $postQueryStr['signature']     = $this->makeSignature($token, $postQueryStr['timestamp'], $postQueryStr['nonce']);
            $postQueryStr['msg_signature'] = $object_enc['MsgSignature'];

            $array               = [];
            $array['ToUserName'] = $object['ToUserName'];
            $array['Encrypt']    = $object_enc['Encrypt'];

            $xml = $this->arrayToXml($array);
        } else {
            $postQueryStr['timestamp'] = time();
            $postQueryStr['nonce']     = $this->getRandomStr();
            $postQueryStr['signature'] = $this->makeSignature($token, $postQueryStr['timestamp'], $postQueryStr['nonce']);
        }

        asort($postQueryStr);
        $postQueryStr = http_build_query($postQueryStr);

        $rest = $this->https_xml($url . '?' . $postQueryStr, $xml);

        if ($rest) {
            $object_rest = simplexml_load_string($rest, 'SimpleXMLElement', LIBXML_NOCDATA);
            $object_rest = json_decode(json_encode($object_rest), true);

            if (!empty($object_rest['MsgSignature']) && !empty($object_rest['TimeStamp']) && !empty($object_rest['Nonce']) && !empty($object_rest['Encrypt'])) {
                $rest = $this->decryptMsg($rest, $object_rest['MsgSignature'], $object_rest['TimeStamp'], $object_rest['Nonce']);
            }
            $object_rest = simplexml_load_string($rest, 'SimpleXMLElement', LIBXML_NOCDATA);
            $rest_array  = json_decode(json_encode($object_rest), true);

            return $rest_array;
        }

        return false;
    }

    /**
     * 加密XML
     *
     * @param $xml
     *
     * @return string
     */
    public function encryptMsg($xml)
    {
        $appId          = Api::getAppId();
        $token          = Api::getToken();
        $encodingAesKey = Api::getEncoding_Aes_Key();

        if (empty($token) || !$token || empty($encodingAesKey) || !$encodingAesKey) {
            return $xml;
        }

        $timeStamp = time();
        $nonce     = $this->getRandomStr();

        $pc         = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
        $encryptMsg = '';
        $errCode    = $pc->encryptMsg($xml, $timeStamp, $nonce, $encryptMsg);
        if ($errCode == 0) {
            return $encryptMsg;
        } else {
            exit($errCode);
        }
    }

    /**
     * 解密XML
     *
     * @param      $xml
     * @param null $msg_signature
     * @param null $timeStamp
     * @param null $nonce
     *
     * @return string
     */
    public function decryptMsg($xml, $msg_signature = null, $timeStamp = null, $nonce = null)
    {
        $appId          = Api::getAppId();
        $token          = Api::getToken();
        $encodingAesKey = Api::getEncoding_Aes_Key();

        $msg_signature = !empty($msg_signature) ? $msg_signature : $_GET['msg_signature'];
        $timeStamp     = !empty($timeStamp) ? $timeStamp : $_GET['timestamp'];
        $nonce         = !empty($nonce) ? $nonce : $_GET['nonce'];

        $pc  = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
        $msg = '';

        $errCode = $pc->decryptMsg($msg_signature, $timeStamp, $nonce, $xml, $msg);

        if ($errCode == 0) {
            return $msg;
        } else {
            exit($errCode);
        }
    }

    /**
     * 魔术方法 处理 返回结果
     *
     * @param  string $target [事件]
     * @param         $event
     *
     * @return string $event  [类型]
     * @return array  $xml    [xml]
     */
    public function __call($target, $event)
    {
        $object = self::$XML_OBJECT;
        if (!$object || !is_object($object) || empty($object)) {
            $xml = file_get_contents('php://input');

            $msg_signature = isset($_GET['msg_signature']) ? $_GET['msg_signature'] : null;

            if (!empty($msg_signature) && $msg_signature != '') {
                $xml = $this->decryptMsg($xml);
            }

            $object           = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
            self::$XML_OBJECT = $object;
        }

        $target = strtolower(trim($target));

        $event = strtolower(trim(reset($event)));

        $rx_target = strtolower(trim($object->MsgType));
        $rx_event  = strtolower(trim($object->Event));

        if ($target == $rx_target) {
            if ($event == '*') {
                $array = json_decode(json_encode($object), true);
            } elseif ($event == $rx_event) {
                $array = json_decode(json_encode($object), true);
            } else {
                $array = false;
            }
        } else {
            $array = false;
        }

        return $array;
    }

    /**
     * array转xml.
     *
     * @param array $arr
     * @param bool  $flag
     * @param bool  $especial
     *
     * @return string
     */
    public function arrayToXml($arr = [], $flag = true, $especial = false)
    {
        if ($flag) {
            $xml = '<xml>';
        } else {
            $xml = '';
        }

        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
            } elseif (is_array($val)) {
                if (strtolower($key) == 'item') {
                    $xml .= $this->arrayToXml($val, false, true);
                } elseif ($especial == true) {
                    $xml .= '<item>';
                    $xml .= $this->arrayToXml($val, false, true);
                    $xml .= '</item>';
                } else {
                    $xml .= !is_numeric($key) ? '<' . ucfirst($key) . '>' : '';
                    $xml .= $this->arrayToXml($val, false);
                    $xml .= !is_numeric($key) ? '</' . ucfirst($key) . '>' : '';
                }
            } else {
                $xml .= '<' . $key . '><![CDATA[' . $val . ']]></' . $key . '>';
            }
        }

        if ($flag) {
            $xml .= '</xml>';
        }

        return $xml;
    }

    public function getXmlObject()
    {
        return self::$XML_OBJECT;
    }

    /**
     * 第三方 post 推送
     *
     * @param      $url
     * @param null $xml
     *
     * @return mixed
     */
    public function https_xml($url, $xml = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_error($ch);
        }
        curl_close($ch);

        return $response;
    }

    /**
     * 随机生成16位字符串
     *
     * @return string 生成的字符串
     */
    public function getRandomStr()
    {
        $str     = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max     = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }

        return $str;
    }
}