<?php
namespace Wechat\API;

/**
 * 微信用户分组相关接口.
 *
 * @author Tian.
 */
class GroupsApi extends BaseApi
{
    /**
     * 创建用户组.
     *
     * @param string $name 分组名 30字符以内
     *
     * @return array id 分组ID  , name 分组名.
     */
    public function create($name)
    {
        if (!is_string($name) || strlen($name) >= 30) {
            $this->setError('参数错误,请传入字符串或名字的长度不能大于30字符');

            return false;
        }

        $queryStr = [
            'group' => ['name' => $name],
        ];

        $res = $this->_post('create', $queryStr);

        return $res;
    }

    /**
     *  查询分组
     *
     * @return array 所有分组信息 如没有分组返回false.
     */
    public function get()
    {
        $queryStr = [];

        $res = $this->_get('get', $queryStr);

        return $res;
    }

    /**
     *  查询用户所在分组
     *
     * @param string $openid 用户ID
     *
     * @return int.
     */
    public function getid($openid)
    {
        if (!$openid) {
            $this->setError('参数错误,缺少Openid');

            return false;
        }

        $queryStr = ['openid' => $openid];

        $res = $this->_post('getid', $queryStr);

        return $res;
    }

    /**
     *  修改分组名
     *
     * @param int|string $id 分组ID,$name 分组名
     *
     * @return bool.
     */
    public function update($id, $name)
    {
        if (!is_numeric($id) || !is_string($name) || strlen($name) >= 30) {
            $this->setError('参数错误,分组ID不是数字或分组名不是有效的字符串或名字的长度大于30字符');

            return false;
        }

        $queryStr = [
            'group' => ['id' => $id, 'name' => $name],
        ];

        $res = $this->_post('update', $queryStr);

        return $res;
    }

    /**
     *  删除指定分组
     *
     * @param int $id 分组ID
     *
     * @return bool. 注：删除成功时返回的是 '' ,删除失败时返回的是false,(包括分组ID不是数字或分组ID已经不存在)
     */
    public function delete($id)
    {
        if (!is_numeric($id)) {
            $this->setError('参数错误,分组ID不是数字');

            return false;
        }

        $queryStr = [
            'group' => ['id' => $id],
        ];

        $res = $this->_post('delete', $queryStr);

        return $res;
    }

    /**
     *  移动用户到指定分组
     *
     * @param string $openid 用户ID,to_groupid 指定分组ID
     *
     * @return bool.
     */

    public function moveUser($openid, $to_groupid)
    {
        if (!is_numeric($to_groupid) || !is_string($openid)) {
            $this->setError('参数错误,用户ID格式不正确或组ID不是数字');

            return false;
        }

        $queryStr = ['openid' => $openid, 'to_groupid' => $to_groupid];

        $res = $this->_post('members/update', $queryStr);

        return $res;
    }

    /**
     *  批量移动用户到指定分组
     *
     * @param array $openid_list 用户ID,to_groupid 指定分组ID
     *
     * @return bool. 注：其中批量移动时，其中有一个用户的id是错的，那么其他的也不会移动,若用户已经在目标组里那么返回值也是true
     */
    public function MoveUserlistGroup($openid_list, $to_groupid)
    {
        if (!is_array($openid_list)) {
            $this->setError('参数必须为一个数组');

            return false;
        }

        $queryStr = ['openid_list' => $openid_list, 'to_groupid' => $to_groupid];

        $res = $this->_post('members/batchupdate', $queryStr);

        return $res;
    }
}
