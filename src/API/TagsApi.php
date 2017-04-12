<?php
namespace Wechat\API;

/**
 * 微信标签相关接口.
 *
 * @author  Jia.
 *
 * @date    2017-04-10
 */
class TagsApi extends BaseApi
{
    /**
     * 创建标签.
     *
     * @author Jia <jiayanyang@digilinx.cn>
     * @date   2017-04-10
     *
     * @param  string $name 标签名称
     *
     * @return array  标签信息 id和标签名.
     */
    public function setTagsName($name)
    {
        $queryStr = [
            'tag' => [
                'name' => $name,
            ],
        ];

        $res = $this->_post('create', $queryStr);

        return $res;
    }

    /**
     * 获取标签列表.
     *
     * @author  Jia <jiayanyang@digilinx.cn>
     * @date    2017-04-10
     *
     * @return array|bool
     */
    public function getTagsList()
    {
        $queryStr = [];

        $res = $this->_get('get', $queryStr);

        return $res;
    }

    /**
     * 更新标签名称
     *
     * @author Jia <jiayanyang@digilinx.cn>
     *
     * @date   2017-04-10
     *
     * @param  int    $tagid 标签id
     * @param  string $name  标签名
     *
     * @return array
     */
    public function updateTagsName($tagid, $name)
    {
        $queryStr = [
            'tag' => [
                'id'   => $tagid,
                'name' => $name,
            ],
        ];

        $res = $this->_post('update', $queryStr);

        return $res;
    }

    /**
     * 删除标签
     *
     * @author Jia <jiayanyang@digilinx.cn>
     *
     * @date   2017-04-10
     *
     * @param  int $tagid 标签id
     *
     * @return array
     */
    public function deleteTags($tagid)
    {
        $queryStr = [
            'tag' => [
                'id' => $tagid,
            ],
        ];

        $res = $this->_post('delete', $queryStr);

        return $res;
    }

    /**
     * 批量为用户打标签.
     *
     * @author  Jia <jiayanyang@digilinx.cn>
     * @date    2017-04-10
     *
     * @param  array $openid_list 用户openid列表
     * @param  int   $tagid       标签id
     *
     * @return array|bool
     */
    public function setUsersTag($tagid, array $openid_list)
    {
        if (!is_array($openid_list)) {
            $this->setError('参数必须为一个数组');

            return false;
        }

        $queryStr = [
            'openid_list' => $openid_list,
            'tagid'       => $tagid,
        ];

        $res = $this->_post('members/batchtagging', $queryStr);

        return $res;
    }

    /**
     * 批量为用户取消标签
     *
     * @author  Jia <jiayanyang@digilinx.cn>
     * @date    2017-04-10
     *
     * @param  array $openid_list 用户openid列表
     * @param  int   $tagid       标签id
     *
     * @return array|bool
     */
    public function unsetUsersTag($tagid, array $openid_list)
    {
        if (!is_array($openid_list)) {
            $this->setError('参数必须为一个数组');

            return false;
        }

        $queryStr = [
            'openid_list' => $openid_list,
            'tagid'       => $tagid,
        ];

        $res = $this->_post('members/batchuntagging', $queryStr);

        return $res;
    }

    /**
     * 获取用户身上的标签列表
     *
     * @author  Jia <jiayanyang@digilinx.cn>
     * @date    2017-04-10
     *
     * @param  string $openid 用户openid
     *
     * @return array
     */
    public function getUserTags($openid)
    {

        $queryStr = [
            'openid' => $openid,
        ];

        $res = $this->_post('getidlist', $queryStr);

        return $res;
    }

}
