<?php
namespace Wechat\API;

/**
 * 微信客服相关接口.
 *
 * @author Huang.
 */
class KfaccountApi extends BaseApi
{
     /**
     * 客服账号管理-添加客服账号.
     *
     * @author Huang
     *
     * @param string $kf_account 客服账号
     *
     * @param string $nickname	 客服昵称
     *
     * @param string $password	 客服密码
     *
     * @return array 客服信息.
     */
    public function add($kf_account, $nickname, $password)
    {
        $kf_array = array(
            'kf_account' => $kf_account,
            'nickname'     => $nickname,
            'password'     => MD5($password)
        );

        $this->apitype = 'customservice';

        $this->module = 'kfaccount';

        $res = $this->_post('add', $kf_array);

        return  $res;
    }

     /**
     * 客服账号管理-修改客服账号.
     *
     * @author Huang
     *
     * @param string $kf_account 客服账号
     *
     * @param string $nickname	 客服昵称
     *
     * @param string $password	 客服密码
     *
     * @return array 客服信息.
     */
    public function update($kf_account, $nickname, $password)
    {
        $kf_array = array(
            'kf_account' => $kf_account,
            'nickname'     => $nickname,
            'password'     => MD5($password)
        );

        $this->apitype = 'customservice';

        $this->module = 'kfaccount';

        $res = $this->_post('update', $kf_array);

        return  $res;
    }

     /**
     * 客服账号管理-删除客服账号.
     *
     * @author Huang
     *
     * @param string $kf_account 客服账号
     *
     * @return array 客服信息.
     */
    public function del($kf_account)
    {
        $kf_array = array(
            'kf_account'    => $kf_account
        );

        $this->apitype = 'customservice';
        $this->module  = 'kfaccount';

        $res = $this->_get('del', $kf_array);

        return  $res;
    }

     /**
     * 客服账号管理-获取客服基本信息.
     *
     * @author Huang
     *
     * @return array 客服信息.
     */
    public function getkflist()
    {
        $kf_array = array();

        $this->module = 'customservice';

        $res = $this->_get('getkflist', $kf_array);

        return  $res;
    }

     /**
     * 客服账号管理-获取在线客服接待信息.
     *
     * @author Huang
     *
     * @return array 客服信息.
     */
    public function getonlinekflist()
    {
        $kf_array = array();

        $this->module = 'customservice';

        $res = $this->_get('getonlinekflist', $kf_array);

        return  $res;
    }

     /**
     * 客服账号管理-设置客服账号的头像.
     *
     * @author Huang
     *
     * @param string $kf_account 客服账号
     *
     * @return array 客服信息.
     */
    public function uploadheadimg($file, $kf_account)
    {
        if (!$file || !$kf_account) {
            $this->setError('参数缺失');

            return false;
        }

        if (!file_exists($file)) {
            $this->setError('文件路径不正确');

            return false;
        }

        $data = array();
        $data['media'] = '@'.realpath($file);

        Api::setPostQueryStr('kf_account', $kf_account);

        $this->apitype = 'customservice';
        $this->module  = 'kfaccount';

        $res =  $this->_post('uploadheadimg', $data, false);

        return $res;
    }

     /**
     * 获取客服聊天记录.
     *
     * @author Huang
     *
     * @param int $endtime		查询结束时间，UNIX时间戳，每次查询不能跨日查询
     *
     * @param int $pageindex	查询第几页，从1开始
     *
     * @param int $pagesize		每页大小，每页最多拉取50条
     *
     * @param int $starttime	查询开始时间,UNIX时间戳
     *
     * @return array 客服信息.
     */
    public function getrecord($endtime, $pageindex, $pagesize, $starttime)
    {
        $recond_array = array(
            'endtime'     => $endtime,
            'pageindex'     => $pageindex,
            'pagesize'     => $pagesize,
            'starttime'     => $starttime
        );

        $this->apitype = 'customservice';

        $this->module = 'msgrecord';

        $res = $this->_post('getrecord', $recond_array);

        return  $res;
    }


/**
                            ------------------------------------------发送客服消息------------------------------------------
*/

     /**
     * 图文消息- 自定义
     *
     * @param  boolean $articles  图文消息
     * @param  string  $openid    接收消息用户对应该公众号的openid
     * @param string   $kf_account  客服账号
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function customNews($articles = array(), $openid, $kf_account = '')
    {
        $queryStr = array();
        $queryStr['touser'] = $openid;
        $queryStr['msgtype']             = 'news';
        $queryStr['news']['articles']     = $articles;

        $queryStr['customservice']['kf_account']  = $kf_account;

        $this->module = 'message';

        $res = $this->_post('custom/send', $queryStr);

        return $res;
    }


    /**
     * 图文消息 - 微信
     *
     * @param  boolean $media_id  用于设定是否向全部用户发送，选择true该消息群发给所有用户，选择false可根据group_id发送给指定群组的用户
     * @param  string  $openid    接收消息用户对应该公众号的openid
     * @param  string  $type      接受用户 是 openid  还是 wxname
     * @param string   $kf_account  客服账号
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function customMpnews($media_id = '', $openid = '', $kf_account = '')
    {
        $queryStr = array();
        $queryStr['towxname'] = $openid;
        $queryStr['msgtype']             = 'mpnews';
        $queryStr['mpnews']['media_id']  = $media_id;

        $queryStr['customservice']['kf_account']  = $kf_account;

        $this->module = 'message';

        $res = $this->_post('custom/send', $queryStr);

        return $res;
    }

    /**
     * 文本消息
     *
     * @param  string  $content   文字
     * @param  string  $openid    接收消息用户对应该公众号的openid
     * @param string   $kf_account  客服账号
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function customText($content = '', $openid, $kf_account = '')
    {
        $queryStr = array();
        $queryStr['touser'] = $openid;
        $queryStr['msgtype']             = 'text';
        $queryStr['text']['content']     = $content;

        $queryStr['customservice']['kf_account']  = $kf_account;

        $this->module = 'message';

        $res = $this->_post('custom/send', $queryStr);

        return $res;
    }

    /**
     * 语音（注意此处media_id需通过基础支持中的上传下载多媒体文件来得到）
     *
     * @param  string  $media_id  用于群发的消息的media_id
     * @param  string  $openid    接收消息用户对应该公众号的openid
     * @param string   $kf_account  客服账号
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function customVoice($media_id = '', $openid, $kf_account = '')
    {
        $queryStr = array();
        $queryStr['touser'] = $openid;
        $queryStr['msgtype']             = 'voice';
        $queryStr['voice']['media_id']   = $media_id;

        $queryStr['customservice']['kf_account']  = $kf_account;

        $this->module = 'message';

        $res = $this->_post('custom/send', $queryStr);

        return $res;
    }

     /**
     * 图片（注意此处media_id需通过基础支持中的上传下载多媒体文件来得到）
     *
     * @param  string  $media_id  用于群发的消息的media_id
     * @param  string  $openid    接收消息用户对应该公众号的openid
     * @param string   $kf_account  客服账号
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function customImage($media_id = '', $openid, $kf_account = '')
    {
        $queryStr = array();
        $queryStr['touser'] = $openid;
        $queryStr['msgtype']             = 'image';
        $queryStr['image']['media_id']   = $media_id;

        $queryStr['customservice']['kf_account']  = $kf_account;

        $this->module = 'message';

        $res = $this->_post('custom/send', $queryStr);

        return $res;
    }

    /**
     * 视频（请注意，此处视频的media_id需通过POST请求到下述接口特别地得到）
     *
     * @param  string  $media_id  用于群发的消息的media_id
     * @param  string  $openid    接收消息用户对应该公众号的openid
     * @param  string  $title          标题
     * @param  string  $description    描述
     * @param string   $kf_account  客服账号
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function customMpvideo($media_id = '', $title = '', $description = '', $openid, $kf_account = '')
    {
        $queryStr = array();
        $queryStr['touser'] = $openid;
        $queryStr['msgtype']                 = 'mpvideo';
        $queryStr['mpvideo']['media_id']    = $media_id;
        $queryStr['mpvideo']['title']        = $title;
        $queryStr['mpvideo']['description'] = $description;

        $queryStr['customservice']['kf_account']  = $kf_account;

        $this->module = 'message';

        $res = $this->_post('custom/send', $queryStr);

        return $res;
    }

    /**
     * 卡券（注意图文消息的card_id需要通过上述方法来得到）
     *
     * @param  string  $card_id   card_id
     * @param  array   $card_ext  签名
     * @param  string  $openid    接收消息用户对应该公众号的openid
     * @param string   $kf_account  客服账号
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function customWxcard($card_id = '', $card_ext = array(), $openid, $kf_account = '')
    {
        $queryStr = array();
        $queryStr['touser'] = $openid;
        $queryStr['msgtype']             = 'wxcard';
        $queryStr['wxcard']['card_id']   = $card_id;
        $queryStr['wxcard']['card_ext']  = $card_ext;

        $queryStr['customservice']['kf_account']  = $kf_account;

        $this->module = 'message';

        $res = $this->_post('custom/send', $queryStr);

        return $res;
    }

    /**
     * 发送音乐消息
     *
     * @param  string $musicurl       音乐链接
     * @param  string $openid         接收消息用户对应该公众号的openid
     * @param  string $thumb_media_id 缩略图的媒体ID
     * @param  string $hqmusicurl     高品质音乐链接，wifi环境优先使用该链接播放音乐
     * @param  string $title          标题
     * @param  string $description    描述
     * @param string   $kf_account  客服账号
     *
     * @return msg_id
     */
    public function customMusic($musicurl = '', $openid = '', $thumb_media_id = '', $hqmusicurl = '', $title = '', $description = '', $kf_account = '')
    {
        $queryStr = array();
        $queryStr['touser'] = $openid;
        $queryStr['msgtype']                  = 'music';
        $queryStr['music']['title']          = $title;
        $queryStr['music']['description']     = $description;
        $queryStr['music']['musicurl']        = $musicurl;
        $queryStr['music']['hqmusicurl']      = $hqmusicurl;
        $queryStr['music']['thumb_media_id']  = $thumb_media_id;

        $queryStr['customservice']['kf_account']  = $kf_account;

        $this->module = 'message';

        $res = $this->_post('custom/send', $queryStr);

        return $res;
    }
}
