<?php
namespace Wechat\API;

use Wechat\Utils\Url;
use Wechat\Api;

/**
 * 微信Auth相关接口.
 *
 * @author Tian.
 */
class AuthApi extends BaseApi
{
    const API_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    protected static $authorizedUser;

    /**
     * 生成outh URL
     *
     * @param string $to
     * @param string $scope
     * @param string $state
     *
     * @return string
     */
    public function url($to = null, $scope = 'snsapi_userinfo', $state = 'STATE')
    {
        $to !== null || $to = Url::current();

        $queryStr = [
            'appid'         => $this->getAppId(),
            'redirect_uri'  => $to,
            'response_type' => 'code',
            'scope'         => $scope,
            'state'         => $state,
        ];

        return self::API_URL . '?' . http_build_query($queryStr) . '#wechat_redirect';
    }

    /**
     * 直接跳转
     *
     * @param string $to
     * @param string $scope
     * @param string $state
     */
    public function redirect($to = null, $scope = 'snsapi_userinfo', $state = 'STATE')
    {
        header('Location:' . $this->url($to, $scope, $state));

        exit;
    }

    /**
     * 获取用户信息
     *
     * @param string $openId
     * @param string $accessToken
     *
     * @return array
     */
    public function getUser($openId, $accessToken)
    {
        $queryStr = [
            'access_token' => $accessToken,
            'openid'       => $openId,
            'lang'         => 'zh_CN',
        ];

        $this->apitype = 'sns';
        $this->module  = 'userinfo';
        $res           = $this->_get('', $queryStr);

        return $res;
    }

    /**
     * 获取已授权用户
     *
     * @return array $user
     */
    public function user()
    {
        if (self::$authorizedUser || !$_GET['state'] || (!$code = $_GET['code']) && $_GET['state']) {
            return self::$authorizedUser;
        }

        $permission = $this->getAccessPermission($code);

        if ($permission['scope'] !== 'snsapi_userinfo') {
            $user = ['openid' => $permission['openid']];
        } else {
            $user = $this->getUser($permission['openid'], $permission['access_token']);
        }

        return $this->authorizedUser = $user;
    }

    /**
     * 通过授权获取用户
     *
     * @param null   $to
     * @param string $scope
     * @param string $state
     *
     * @return array
     */
    public function authorize($to = null, $scope = 'snsapi_userinfo', $state = 'STATE')
    {
        if (!$_GET['state'] && !$code = $_GET['code']) {
            $this->redirect($to, $scope, $state);
        }

        return $this->user();
    }

    /**
     * 检查 Access Token 是否有效
     *
     * @param string $accessToken
     * @param string $openId
     *
     * @return boolean
     */
    public function accessTokenIsValid($accessToken, $openId)
    {
        $params = [
            'openid'       => $openId,
            'access_token' => $accessToken,
        ];

        $this->apitype = 'sns';
        $this->module  = 'auth';

        $res = $this->_get('', $params);

        return $res;
    }

    /**
     * 刷新 access_token
     *
     * @param $refreshToken
     *
     * @return bool|array
     */
    public function refresh($refreshToken)
    {
        $queryStr = [
            'appid'         => $this->getAppId(),
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        $this->apitype = 'sns';
        $this->module  = 'oauth2';
        $res           = $this->_get('refresh_token', $queryStr);

        return $res;
    }

    /**
     * 获取access token
     *
     * @param string $code
     *
     * @return string
     */
    public function getAccessPermission($code)
    {
        $queryStr = [
            'appid'      => $this->getAppId(),
            'secret'     => $this->getAppSecret(),
            'code'       => $code,
            'grant_type' => 'authorization_code',
        ];

        $this->apitype = 'sns';
        $this->module  = 'oauth2';
        $res           = $this->_get('access_token', $queryStr);

        return $res;
    }
}
