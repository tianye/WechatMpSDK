<?php
namespace Wechat\API;

/**
 * 高级群发接口.
 *
 * @author Tian.
 */
class MassSendApi extends BaseApi
{

    /**
     * ------------------------------------------------Group------------------------------------------------
     */

    /**
     * 图文消息
     *
     * @param  string  $media_id  用于群发的消息的media_id
     * @param  integer $group_id  群发到的分组的group_id，参加用户管理中用户分组接口，若is_to_all值为true，可不填写group_id
     * @param  boolean $is_to_all 用于设定是否向全部用户发送，选择true该消息群发给所有用户，选择false可根据group_id发送给指定群组的用户
     *
     * @return int       msg_id      发送出去的消息ID
     */
    public function allMpnews($media_id = '', $is_to_all = false, $group_id = 0)
    {
        $queryStr                        = [];
        $queryStr['filter']['is_to_all'] = $is_to_all;
        if ($is_to_all == false) {
            $queryStr['filter']['group_id'] = $group_id;
        }
        $queryStr['mpnews']['media_id'] = $media_id;
        $queryStr['msgtype']            = 'mpnews';

        $this->module = 'message';

        $res = $this->_post('mass/sendall', $queryStr);

        return $res;
    }

    /**
     * 文本消息
     *
     * @param  string  $content   文字
     * @param  integer $group_id  群发到的分组的group_id，参加用户管理中用户分组接口，若is_to_all值为true，可不填写group_id
     * @param  boolean $is_to_all 用于设定是否向全部用户发送，选择true该消息群发给所有用户，选择false可根据group_id发送给指定群组的用户
     *
     * @return int       msg_id      发送出去的消息ID
     */
    public function allText($content = '', $is_to_all = false, $group_id = 0)
    {
        $queryStr                        = [];
        $queryStr['filter']['is_to_all'] = $is_to_all;
        if ($is_to_all == false) {
            $queryStr['filter']['group_id'] = $group_id;
        }
        $queryStr['text']['content'] = $content;
        $queryStr['msgtype']         = 'text';

        $this->module = 'message';

        $res = $this->_post('mass/sendall', $queryStr);

        return $res;
    }

    /**
     * 语音（注意此处media_id需通过基础支持中的上传下载多媒体文件来得到）
     *
     * @param  string  $media_id  用于群发的消息的media_id
     * @param  integer $group_id  群发到的分组的group_id，参加用户管理中用户分组接口，若is_to_all值为true，可不填写group_id
     * @param  boolean $is_to_all 用于设定是否向全部用户发送，选择true该消息群发给所有用户，选择false可根据group_id发送给指定群组的用户
     *
     * @return int       msg_id      发送出去的消息ID
     */
    public function allVoice($media_id = '', $is_to_all = false, $group_id = 0)
    {
        $queryStr                        = [];
        $queryStr['filter']['is_to_all'] = $is_to_all;
        if ($is_to_all == false) {
            $queryStr['filter']['group_id'] = $group_id;
        }
        $queryStr['voice']['media_id'] = $media_id;
        $queryStr['msgtype']           = 'voice';

        $this->module = 'message';

        $res = $this->_post('mass/sendall', $queryStr);

        return $res;
    }

    /**
     * 图片（注意此处media_id需通过基础支持中的上传下载多媒体文件来得到）
     *
     * @param  string  $media_id  用于群发的消息的media_id
     * @param  integer $group_id  群发到的分组的group_id，参加用户管理中用户分组接口，若is_to_all值为true，可不填写group_id
     * @param  boolean $is_to_all 用于设定是否向全部用户发送，选择true该消息群发给所有用户，选择false可根据group_id发送给指定群组的用户
     *
     * @return int       msg_id      发送出去的消息ID
     */
    public function allImage($media_id = '', $is_to_all = false, $group_id = 0)
    {
        $queryStr                        = [];
        $queryStr['filter']['is_to_all'] = $is_to_all;
        if ($is_to_all == false) {
            $queryStr['filter']['group_id'] = $group_id;
        }
        $queryStr['image']['media_id'] = $media_id;
        $queryStr['msgtype']           = 'image';

        $this->module = 'message';

        $res = $this->_post('mass/sendall', $queryStr);

        return $res;
    }

    /**
     * 视频（请注意，此处视频的media_id需通过POST请求到下述接口特别地得到）
     *
     * @param  string  $media_id  用于群发的消息的media_id
     * @param  integer $group_id  群发到的分组的group_id，参加用户管理中用户分组接口，若is_to_all值为true，可不填写group_id
     * @param  boolean $is_to_all 用于设定是否向全部用户发送，选择true该消息群发给所有用户，选择false可根据group_id发送给指定群组的用户
     *
     * @return int       msg_id      发送出去的消息ID
     */
    public function allMpvideo($media_id = '', $is_to_all = false, $group_id = 0)
    {
        $queryStr                        = [];
        $queryStr['filter']['is_to_all'] = $is_to_all;
        if ($is_to_all == false) {
            $queryStr['filter']['group_id'] = $group_id;
        }
        $queryStr['mpvideo']['media_id'] = $media_id;
        $queryStr['msgtype']             = 'mpvideo';

        $this->module = 'message';

        $res = $this->_post('mass/sendall', $queryStr);

        return $res;
    }

    /**
     * 卡券（注意图文消息的card_id需要通过上述方法来得到）
     *
     * @param  string  $card_id   card_id
     * @param  integer $group_id  群发到的分组的group_id，参加用户管理中用户分组接口，若is_to_all值为true，可不填写group_id
     * @param  boolean $is_to_all 用于设定是否向全部用户发送，选择true该消息群发给所有用户，选择false可根据group_id发送给指定群组的用户
     *
     * @return int       msg_id      发送出去的消息ID
     */
    public function allWxcard($card_id = '', $is_to_all = false, $group_id = 0)
    {
        $queryStr                        = [];
        $queryStr['filter']['is_to_all'] = $is_to_all;
        if ($is_to_all == false) {
            $queryStr['filter']['group_id'] = $group_id;
        }
        $queryStr['wxcard']['card_id'] = $card_id;
        $queryStr['msgtype']           = 'wxcard';

        $this->module = 'message';

        $res = $this->_post('mass/sendall', $queryStr);

        return $res;
    }


    /**
     * ------------------------------------------------Openid------------------------------------------------
     */

    /**
     * 图文消息
     *
     * @param  boolean $media_id 用于设定是否向全部用户发送，选择true该消息群发给所有用户，选择false可根据group_id发送给指定群组的用户
     * @param  array   $openids  填写图文消息的接收者，一串OpenID列表，OpenID最少2个，最多10000个
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function openidMpnews($media_id = '', $openids = [])
    {
        $queryStr                       = [];
        $queryStr['filter']             = $openids;
        $queryStr['mpnews']['media_id'] = $media_id;
        $queryStr['msgtype']            = 'mpnews';

        $this->module = 'message';

        $res = $this->_post('mass/send', $queryStr);

        return $res;
    }

    /**
     * 文本消息
     *
     * @param  string $content 文字
     * @param  array  $openids 填写图文消息的接收者，一串OpenID列表，OpenID最少2个，最多10000个
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function openidText($content = '', $openids = [])
    {
        $queryStr                    = [];
        $queryStr['filter']          = $openids;
        $queryStr['text']['content'] = $content;
        $queryStr['msgtype']         = 'text';

        $this->module = 'message';

        $res = $this->_post('mass/send', $queryStr);

        return $res;
    }

    /**
     * 语音（注意此处media_id需通过基础支持中的上传下载多媒体文件来得到）
     *
     * @param  string $media_id 用于群发的消息的media_id
     * @param  array  $openids  填写图文消息的接收者，一串OpenID列表，OpenID最少2个，最多10000个
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function openidVoice($media_id = '', $openids = [])
    {
        $queryStr                      = [];
        $queryStr['filter']            = $openids;
        $queryStr['voice']['media_id'] = $media_id;
        $queryStr['msgtype']           = 'voice';

        $this->module = 'message';

        $res = $this->_post('mass/send', $queryStr);

        return $res;
    }

    /**
     * 图片（注意此处media_id需通过基础支持中的上传下载多媒体文件来得到）
     *
     * @param  string $media_id 用于群发的消息的media_id
     * @param  array  $openids  填写图文消息的接收者，一串OpenID列表，OpenID最少2个，最多10000个
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function openidImage($media_id = '', $openids = [])
    {
        $queryStr                      = [];
        $queryStr['filter']            = $openids;
        $queryStr['image']['media_id'] = $media_id;
        $queryStr['msgtype']           = 'image';

        $this->module = 'message';

        $res = $this->_post('mass/send', $queryStr);

        return $res;
    }

    /**
     * 视频（请注意，此处视频的media_id需通过POST请求到下述接口特别地得到）
     *
     * @param  string $media_id 用于群发的消息的media_id
     * @param  array  $openids  填写图文消息的接收者，一串OpenID列表，OpenID最少2个，最多10000个
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function openidVideo($media_id = '', $openids = [], $title = '', $description = '')
    {
        $queryStr                      = [];
        $queryStr['filter']            = $openids;
        $queryStr['video']['media_id'] = $media_id;

        if (!empty($title)) {
            $queryStr['video']['title'] = $title;
        }

        if (!empty($description)) {
            $queryStr['video']['description'] = $description;
        }

        $queryStr['msgtype'] = 'video';

        $this->module = 'message';

        $res = $this->_post('mass/send', $queryStr);

        return $res;
    }

    /**
     * 卡券（注意图文消息的card_id需要通过上述方法来得到）
     *
     * @param  string $card_id card_id
     * @param  array  $openids 填写图文消息的接收者，一串OpenID列表，OpenID最少2个，最多10000个
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function openidWxcard($card_id = '', $openids = [])
    {
        $queryStr                      = [];
        $queryStr['filter']            = $openids;
        $queryStr['wxcard']['card_id'] = $card_id;
        $queryStr['msgtype']           = 'wxcard';

        $this->module = 'message';

        $res = $this->_post('mass/send', $queryStr);

        return $res;
    }


    /**
     * ------------------------------------------------预览接口------------------------------------------------
     */

    /**
     * 图文消息
     *
     * @param  boolean $media_id 用于设定是否向全部用户发送，选择true该消息群发给所有用户，选择false可根据group_id发送给指定群组的用户
     * @param  string  $userid   接收消息用户对应该公众号的openid，该字段也可以改为towxname，以实现对微信号的预览
     * @param  string  $type     接受用户 是 openid  还是 wxname
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function previewMpnews($media_id = '', $userid = '', $type = 'openid')
    {
        $queryStr = [];

        if ($type == 'towxname') {
            $queryStr['towxname'] = $userid;
        } else {
            $queryStr['filter'] = $userid;
        }

        $queryStr['mpnews']['media_id'] = $media_id;
        $queryStr['msgtype']            = 'mpnews';

        $this->module = 'message';

        $res = $this->_post('mass/preview', $queryStr);

        return $res;
    }

    /**
     * 文本消息
     *
     * @param  string $content 文字
     * @param  string $userid  接收消息用户对应该公众号的openid，该字段也可以改为towxname，以实现对微信号的预览
     * @param  string $type    接受用户 是 openid  还是 wxname
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function previewText($content = '', $userid = '', $type = 'openid')
    {
        $queryStr = [];

        if ($type == 'towxname') {
            $queryStr['towxname'] = $userid;
        } else {
            $queryStr['filter'] = $userid;
        }

        $queryStr['text']['content'] = $content;
        $queryStr['msgtype']         = 'text';

        $this->module = 'message';

        $res = $this->_post('mass/preview', $queryStr);

        return $res;
    }

    /**
     * 语音（注意此处media_id需通过基础支持中的上传下载多媒体文件来得到）
     *
     * @param  string $media_id 用于群发的消息的media_id
     * @param  string $userid   接收消息用户对应该公众号的openid，该字段也可以改为towxname，以实现对微信号的预览
     * @param  string $type     接受用户 是 openid  还是 wxname
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function previewVoice($media_id = '', $userid = '', $type = 'openid')
    {
        $queryStr = [];

        if ($type == 'towxname') {
            $queryStr['towxname'] = $userid;
        } else {
            $queryStr['filter'] = $userid;
        }

        $queryStr['voice']['media_id'] = $media_id;
        $queryStr['msgtype']           = 'voice';

        $this->module = 'message';

        $res = $this->_post('mass/preview', $queryStr);

        return $res;
    }

    /**
     * 图片（注意此处media_id需通过基础支持中的上传下载多媒体文件来得到）
     *
     * @param  string $media_id 用于群发的消息的media_id
     * @param  string $userid   接收消息用户对应该公众号的openid，该字段也可以改为towxname，以实现对微信号的预览
     * @param  string $type     接受用户 是 openid  还是 wxname
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function previewImage($media_id = '', $userid = '', $type = 'openid')
    {
        $queryStr = [];

        if ($type == 'towxname') {
            $queryStr['towxname'] = $userid;
        } else {
            $queryStr['filter'] = $userid;
        }

        $queryStr['image']['media_id'] = $media_id;
        $queryStr['msgtype']           = 'image';

        $this->module = 'message';

        $res = $this->_post('mass/preview', $queryStr);

        return $res;
    }

    /**
     * 视频（请注意，此处视频的media_id需通过POST请求到下述接口特别地得到）
     *
     * @param  string $media_id 用于群发的消息的media_id
     * @param  string $userid   接收消息用户对应该公众号的openid，该字段也可以改为towxname，以实现对微信号的预览
     * @param  string $type     接受用户 是 openid  还是 wxname
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function previewMpvideo($media_id = '', $userid = '', $type = 'openid')
    {
        $queryStr = [];

        if ($type == 'towxname') {
            $queryStr['towxname'] = $userid;
        } else {
            $queryStr['filter'] = $userid;
        }

        $queryStr['mpvideo']['media_id'] = $media_id;
        $queryStr['msgtype']             = 'mpvideo';

        $this->module = 'message';

        $res = $this->_post('mass/preview', $queryStr);

        return $res;
    }

    /**
     * 卡券（注意图文消息的card_id需要通过上述方法来得到）
     *
     * @param  string $card_id card_id
     * @param  string $userid  接收消息用户对应该公众号的openid，该字段也可以改为towxname，以实现对微信号的预览
     * @param  string $type    接受用户 是 openid  还是 wxname
     *
     * @return int     msg_id     发送出去的消息ID
     */
    public function previewWxcard($card_id = '', $userid = '', $type = 'openid')
    {
        $queryStr = [];

        if ($type == 'towxname') {
            $queryStr['towxname'] = $userid;
        } else {
            $queryStr['filter'] = $userid;
        }

        $queryStr['wxcard']['card_id'] = $card_id;
        $queryStr['msgtype']           = 'wxcard';

        $this->module = 'message';

        $res = $this->_post('mass/preview', $queryStr);

        return $res;
    }

    /**
     * ------------------------------------------------删除群发------------------------------------------------
     */

    /**
     * 删除群发【订阅号与服务号认证后均可用】
     * 群发只有在刚发出的半小时内可以删除，发出半小时之后将无法被删除
     *
     * @param int $msg_id 发送出去的消息ID
     */
    public function delete($msg_id)
    {
        if (empty($msg_id)) {
            $this->setError('参数缺失');

            return false;
        }

        $queryStr           = [];
        $queryStr['msg_id'] = $msg_id;

        $this->module = 'message';

        $res = $this->_post('mass/delete', $queryStr);

        return $res;
    }

    /**
     * ------------------------------------------------查询群发消息发送状态------------------------------------------------
     */

    /**
     * 查询群发消息发送状态【订阅号与服务号认证后均可用】
     *
     * @param int $msg_id 发送出去的消息ID
     */
    public function massGet($msg_id)
    {
        if (empty($msg_id)) {
            $this->setError('参数缺失');

            return false;
        }

        $queryStr           = [];
        $queryStr['msg_id'] = $msg_id;

        $this->module = 'message';

        $res = $this->_post('mass/get', $queryStr);

        return $res;
    }

}