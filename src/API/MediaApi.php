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
     * 上传临时媒体文件.   $file 为  /logo/img.jpeg 服务器上图片路径
     *
     * @author Tian
     *
     * @date   2015-10-10
     *
     * @param string $file 文件路径
     * @param string $type 文件类型
     *
     * @return bool|array 接口返回结果
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

        $data          = [];
        $data['media'] = '@' . realpath($file);

        Api::setPostQueryStr('type', $type);

        $node = 'upload';

        $res = $this->_post($node, $data, false);

        return $res;
    }

    /**
     * 上传临时媒体文件.
     *
     * @author Tian
     *
     * @date   2015-08-02
     *
     * @param string $file 文件  为 form 表单的 $_FILES['xxx'];
     * @param string $type 文件类型
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

        $param       = [];
        $param[$key] = $filecontent;

        $node = 'upload';

        $res = $this->_post($node, $param, false);

        return $res;
    }

    /**
     * 上传临时媒体文件. $url 为 http://itse.cc/img.jpeg
     *
     * @param        $url
     * @param string $type
     *
     * @return array|bool
     */
    public function uploadCurl($url, $type = 'image')
    {
        if (!$url || !$type) {
            $this->setError('参数缺失');

            return false;
        }

        $rest = self::curl_get($url);

        $file_tail = explode("/", $rest['type']);

        /* 判断是不是阿里云服务器 */
        if ($rest['header']['Server'] == 'AliyunOSS') {
            $imgType      = substr($url, strrpos($url, '.') + 1);
            $file_type    = $type . '/' . $imgType;
            $file_tail[1] = $imgType;
        } else {
            $file_type = $rest['type'];
        }

        $this->setPostQueryStr('type', $type);

        $name      = 'media'; // 设置上传文件键名 固定为media
        $file_name = md5(uniqid(rand(1000, 9999))) . '.' . $file_tail[1]; // 设置上传文件名
        $key       = "name=\"{$name}\"; filename=\"{$file_name}\"\r\nContent-Type: {$file_type}\r\n"; // curl设置上传文件的一种方法.

        $param       = [];
        $param[$key] = $rest['content'];

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
        $node     = 'get';
        $queryStr = ['media_id' => $mediaId];

        $res = $this->_get($node, $queryStr);

        return $res;
    }

    /**
     * 上传图文消息内的图片.
     *
     * @param  string $file 媒体文件路径 图片仅支持jpg/png格式，大小必须在1MB以下
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

        $data          = [];
        $data['media'] = '@' . realpath($file);

        $node = 'uploadimg';

        $res = $this->_post($node, $data, false);

        return $res;
    }

    /**
     * 上传图文消息内的视频.
     *
     * @param string $media_id    媒体Id
     * @param string $title       标题
     * @param string $description 描述
     *
     * @return array.  "type":"video","media_id":"IhdaAQXuvJtGzwwc0abfXnzeezfO0NgPK6AQYShD8RQYMTtfzbLdBIQkQziv2XJc","created_at":1398848981
     */
    public function uploadvideo($media_id, $title, $description)
    {
        if (empty($media_id)) {
            $this->setError('参数缺失');

            return false;
        }

        $queryStr                = [];
        $queryStr['media_id']    = $media_id;
        $queryStr['title']       = $title;
        $queryStr['description'] = $description;

        $res = $this->_post('uploadvideo', $queryStr);

        return $res;
    }

    /**
     * 上传图文消息素材【订阅号与服务号认证后均可用】
     *
     * @param array $articles 图文消息，一个图文消息支持1到8条图文
     *
     * string $articles ->thumb_media_id          图文消息缩略图的media_id，可以在基础支持-上传多媒体文件接口中获得
     * string $articles ->author                  图文消息的作者
     * string $articles ->title                   图文消息的标题
     * utl    $articles ->content_source_url      在图文消息页面点击“阅读原文”后的页面
     * html   $articles ->content                 图文消息页面的内容，支持HTML标签。具备微信支付权限的公众号，可以使用a标签，其他公众号不能使用
     * string $articles ->digest                  图文消息的描述
     * int    $articles ->show_cover_pic          是否显示封面，1为显示，0为不显示
     *
     * @return bool|array
     */
    public function uploadnews($articles = [])
    {
        if (empty($articles) || !is_array($articles)) {
            $this->setError('参数缺失');

            return false;
        }

        $queryStr             = [];
        $queryStr['articles'] = $articles;

        $res = $this->_post('uploadnews', $queryStr);

        return $res;
    }

    /**
     * 上传卡券logo图片. $file 为 /logo/img.jpeg
     *
     * @param  string $file 媒体文件路径 图片仅支持jpg/png格式，大小必须在1MB以下
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

        $data           = [];
        $data['buffer'] = '@' . realpath($file);

        $node = 'uploadimg';

        $res = $this->_post($node, $data, false);

        return $res;
    }

    /**
     * 上传卡券logo图片. $file为 $_FILES['xxx'];
     *
     * @param  string $file 文件
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

        $param       = [];
        $param[$key] = $filecontent;

        $res = $this->_post($node, $param, false);

        return $res;
    }

    /**
     * 上传卡券logo图片. $url 为 http://itse.cc/img.jpeg
     *
     * @param $url
     *
     * @return array|bool
     */
    function curlCardUpload_img($url)
    {
        $node = 'uploadimg';

        $rest      = self::curl_get($url);
        $file_tail = explode("/", $rest['type']);

        /* 判断是不是阿里云服务器 */
        if ($rest['header']['Server'] == 'AliyunOSS') {
            $imgType      = substr($url, strrpos($url, '.') + 1);
            $file_type    = 'image/' . $imgType;
            $file_tail[1] = $imgType;
        } else {
            $file_type = $rest['type'];
        }

        $name      = 'media';
        $file_name = md5(uniqid(rand(1000, 9999))) . '.' . $file_tail[1]; // 设置上传文件名
        $imgSize   = $rest['size'];
        //1M = 1048576字节 微信允许上传的最大文件
        if ($imgSize >= 1048576) {
            $this->error = '文件过大';

            return false;
        }
        // 读取临时文件里 上传文件的信息
        $key = "name=\"{$name}\"; filename=\"{$file_name}\"\r\nContent-Type: {$file_type}\r\n";

        $param       = [];
        $param[$key] = $rest['content'];

        $res = $this->_post($node, $param, false);

        return $res;
    }

    /**
     * curl 抓取图片 + 头信息
     *
     * @param $url
     *
     * @return array
     */
    private static function curl_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
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
            $header = Api::http_parse_headers($header);
        }

        $apiReturnData            = [];
        $apiReturnData['type']    = $header['Content-Type'];
        $apiReturnData['size']    = $header['Content-Length'];
        $apiReturnData['header']  = $header;
        $apiReturnData['content'] = $body;

        return $apiReturnData;
    }
}
