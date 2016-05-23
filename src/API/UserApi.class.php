<?php
namespace Wechat\API;

/**
 * 微信用户相关接口.
 *
 * @author Tian.
 */
class UserApi extends BaseApi
{
    /**
     * 获取用户信息.
     *
     * @author Tian
     *
     * @param string $openid     用户openid
     *
     * @return array 用户信息.
     */
    public function getUserMsg($openid, $lang = 'zh_CN')
    {
        $queryStr = array(
            'openid' => $openid,
            'lang' => $lang,
        );

        $res = $this->_get('info', $queryStr);

        return $res;
    }

    /**
     * 批量获取用户基本信息.
     *
     * @author Tian
     *
     * @param  array $openidList     用户openid列表
     *
     * @return array 用户信息.
     */
    public function getUserList(array $user_list, $isarray = true)
    {
        if (!is_array($user_list)) {
            $this->setError('参数必须为一个数组');

            return false;
        }

        $this->module = 'user';

        $res = $this->_post('info/batchget', $user_list);

        return $res;
    }

    /**
     * 获取用户Openid列表.
     *
     * @author Tian
     *
     * @param string $next_openid     下一个openid
     *
     * @return array Openid列表.
     */
    public function getUserOpenidList($next_openid = '')
    {
        $queryStr = array(
            'next_openid' => $next_openid,
        );

        $res = $this->_get('get', $queryStr);

        return $res;
    }

    /**
     *  设置用户备注名.
     *
     * @author Tian
     *
     * @param string $openid   用户openid  sting
     * @param string $remark  用户备注名，长度必须小于30字符
     *
     * @return string bool.
     */
    public function setUserRemark($openid, $remark = "")
    {
        $queryStr = array(
            'openid' => $openid,
            'remark' => $remark,
        );

        $res = $this->_post('info/updateremark', $queryStr);

        return $res;
    }
}
