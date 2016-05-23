<?php
namespace Wechat\API;

/**
 * 微信Url相关接口.
 *
 * @author Tian.
 */
class ShortApi extends BaseApi
{
    /**
     *  生成短连接
     *
     * @param string $long_url 需要转换的长链接，支持http://、https://、weixin://wxpay 格式的url
     * @param string $action   此处填long2short，代表长链接转短链接
     *
     * @return bool.  成功返回ok
     */
    public function url($long_url, $action = 'long2short')
    {
        if (!is_string($long_url)) {
            $this->setError('参数错误');

            return false;
        }

        $queryStr = [
            "action"   => $action,
            "long_url" => $long_url,
        ];

        $this->module = 'shorturl';

        $res = $this->_post('', $queryStr);

        return $res;
    }

    /**
     * 获取当前URL
     *
     * @return string
     */
    public static function current()
    {
        $protocol = (!empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';

        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            $host = $_SERVER['HTTP_HOST'];
        }

        return $protocol . $host . $_SERVER['REQUEST_URI'];
    }
}
