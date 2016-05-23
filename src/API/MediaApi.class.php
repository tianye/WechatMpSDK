<?php

namespace Wechat\API;

use Wechat\Api;

/**
 * 媒体文件相关接口.
 *
 * @author Tian.
 */
class MediaApi extends BaseApi
{
    /**
     * 上传临时媒体文件.
     *
     * @author Tian
     *
     * @date   2015-10-10
     *
     * @param string $file 文件路径
     * @param string $type 文件类型
     *
     * @return 接口返回结果
     */
    public function upload($file, $type)
    {
        if (!$file || !$type) {
            $this->setError('参数缺失');

            return false;
        }

        if (!file_exists($file)) {
            $this->setError('文件路径不正确');

            return false;
        }

        $data = array();
        $data['media'] = '@'.realpath($file);

        Api::setPostQueryStr('type', $type);

        $node = 'upload';

        $res =  $this->_post($node, $data, false);

        return $res;
    }

     /**
     * 上传临时媒体文件.
     *
     * @author Tian
     *
     * @date   2015-08-02
     *
     * @param string $file  文件
     * @param string $type  文件类型
     *
     * @return array 接口返回结果
     */
    public function uploadFrom($file, $type = 'image')
    {
        if (!$file || !$type) {
            $this->setError('参数缺失');

            return false;
        }

        $this->setPostQueryStr('type', $type);

        $name        = 'media'; // 设置上传文件键名 固定为media
        $filename    = $file['name']; // 设置上传文件名
        $filetype    = $file['type'];
        $filecontent = file_get_contents($file['tmp_name']); // 读取临时文件里 上传文件的信息
        $key         = "name=\"{$name}\"; filename=\"{$filename}\"\r\nContent-Type: {$filetype}\r\n"; // curl设置上传文件的一种方法.

        $param = array();
        $param[$key]  = $filecontent;

        $node = 'upload';

        $res = $this->_post($node, $param, false);

        return $res;
    }

    /**
     * 根据mediaID获取媒体文件.
     *
     * @author Tian
     *
     * @date   2015-10-10
     *
     * @param string $mediaId 由上传接口获取的媒体文件
     *
     * @return array 如果成功则返回 content是由base64编码过的文件内容 解码后为正常的文件内容.
     */
    public function get($mediaId)
    {
        $node = 'get';
        $queryStr = array('media_id' => $mediaId);

        $res = $this->_get($node, $queryStr);

        return $res;
    }

    /**
     * 上传图文消息内的图片.
     *
     * @param   $file  媒体文件路径 图片仅支持jpg/png格式，大小必须在1MB以下
     *
     * @return string.  获取地址  http://mmbiz.qpic.cn/mmbiz/NdxGKqW8jE9GbAqUEPdSgSvbUbProSmE8NbUFwIYnp0Duibs611ZsCLza6b2dS8Ex3CO5dtv0u1HP9QY32djCxA/0
     */
    public function uploadimg($file)
    {
        if (!$file) {
            $this->setError('参数缺失');
            return false;
        }

        if (!file_exists($file)) {
            $this->setError('文件路径不正确');
            return false;
        }

        $data = array();
        $data['media'] = '@'.realpath($file);

        $node = 'uploadimg';

        $res = $this->_post($node, $data, false);

        return $res;
    }

     /**
     * 上传图文消息内的视频.
     *
     * @param string $media_id     媒体Id
     * @param string $title        标题
     * @param string $description  描述
     *
     * @return array.  "type":"video","media_id":"IhdaAQXuvJtGzwwc0abfXnzeezfO0NgPK6AQYShD8RQYMTtfzbLdBIQkQziv2XJc","created_at":1398848981
     */
    public function uploadvideo($media_id, $title, $description)
    {
        if (empty($media_id)) {
            $this->setError('参数缺失');
            return false;
        }

        $queryStr = array();
        $queryStr['media_id'] = $media_id;
        $queryStr['title'] = $title;
        $queryStr['description'] = $description;

        $res = $this->_post('uploadvideo', $queryStr);

        return $res;
    }


    /**
     * 上传图文消息素材【订阅号与服务号认证后均可用】
     *
     * @param array  $articles 图文消息，一个图文消息支持1到8条图文
     *
     * @param string $articles->thumb_media_id          图文消息缩略图的media_id，可以在基础支持-上传多媒体文件接口中获得
     * @param string $articles->author                  图文消息的作者
     * @param string $articles->title                   图文消息的标题
     * @param utl    $articles->content_source_url      在图文消息页面点击“阅读原文”后的页面
     * @param html   $articles->content                 图文消息页面的内容，支持HTML标签。具备微信支付权限的公众号，可以使用a标签，其他公众号不能使用
     * @param string $articles->digest                  图文消息的描述
     * @param int    $articles->show_cover_pic          是否显示封面，1为显示，0为不显示
     *
     * @return
     */
    public function uploadnews($articles = array())
    {
        if (empty($articles) || !is_array($articles)) {
            $this->setError('参数缺失');
            return false;
        }

        $queryStr = array();
        $queryStr['articles'] = $articles;

        $res = $this->_post('uploadnews', $queryStr);

        return $res;
    }

    /**
     * 上传卡券logo图片.
     *
     * @param   $file  媒体文件路径 图片仅支持jpg/png格式，大小必须在1MB以下
     *
     * @return string.  获取地址  http://mmbiz.qpic.cn/mmbiz/NdxGKqW8jE9GbAqUEPdSgSvbUbProSmE8NbUFwIYnp0Duibs611ZsCLza6b2dS8Ex3CO5dtv0u1HP9QY32djCxA/0
     */
    public function cardUploadimg($file)
    {
        if (!$file) {
            $this->setError('参数缺失');
            return false;
        }

        if (!file_exists($file)) {
            $this->setError('文件路径不正确');
            return false;
        }

        $data = array();
        $data['buffer'] = '@'.realpath($file);

        $node = 'uploadimg';

        $res = $this->_post($node, $data, false);

        return $res;
    }

     /**
     * 上传卡券logo图片.
     *
     * @param  $file    文件
     *
     * @return string.  获取地址  http://mmbiz.qpic.cn/mmbiz/NdxGKqW8jE9GbAqUEPdSgSvbUbProSmE8NbUFwIYnp0Duibs611ZsCLza6b2dS8Ex3CO5dtv0u1HP9QY32djCxA/0
     */
    public function cardUpload_img($file)
    {
        if (!$file) {
            $this->setError('参数缺失');
            return false;
        }
        
        $node = 'uploadimg';

        $name        = 'media'; // 设置上传文件键名 固定为media
        $filename    = $file['name']; // 设置上传文件名
        $filetype    = $file['type'];
        $filecontent = file_get_contents($file['tmp_name']); // 读取临时文件里 上传文件的信息
        $key         = "name=\"{$name}\"; filename=\"{$filename}\"\r\nContent-Type: {$filetype}\r\n"; // curl设置上传文件的一种方法.

        $param = array();
        $param[$key]  = $filecontent;


        $res = $this->_post($node, $param, false);

        return $res;
    }
}
