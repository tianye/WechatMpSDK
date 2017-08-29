<?php

namespace Wechat\Utils\Code;

use Exception;
use Wechat\Utils\XML;

/**
 * Prpcrypt class
 *
 * 提供接收和推送给公众平台消息的加解密接口.
 */
class Prpcrypt
{
    protected $AESKey;
    protected $blockSize;

    public function __construct($k)
    {
        $this->AESKey = $k;

        $this->blockSize = 32;
    }

    /**
     * 对明文进行加密
     *
     * @param string $text 需要加密的明文
     *
     * @param string $appId
     *
     * @return array
     */
    public function encrypt($text, $appId)
    {
        try {
            //获得16位随机字符串，填充到明文之前
            $key    = $this->getAESKey();
            $random = $this->getRandomStr();
            $text   = $this->encode($random . pack('N', strlen($text)) . $text . $appId);

            $iv = substr($key, 0, 16);

            $encrypted = openssl_encrypt($text, 'aes-256-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);

            //print(base64_encode($encrypted));
            //使用BASE64对加密后的字符串进行编码
            return [ErrorCode::$OK, base64_encode($encrypted)];
        } catch (Exception $e) {
            //print $e;
            return [ErrorCode::$EncryptAESError, null];
        }
    }

    /**
     * @param string $encrypted 需要解密的密文
     * @param string $appId     APPID
     *
     * @return array|string
     */
    public function decrypt($encrypted, $appId)
    {
        try {
            //使用BASE64对需要解密的字符串进行解码
            $key        = $this->getAESKey();
            $ciphertext = base64_decode($encrypted, true);
            $iv         = substr($key, 0, 16);

            $decrypted = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);
        } catch (Exception $e) {
            return [ErrorCode::$DecryptAESError, null];
        }

        try {
            $result = $this->decode($decrypted);

            if (strlen($result) < 16) {
                return '';
            }

            $content   = substr($result, 16, strlen($result));
            $listLen   = unpack('N', substr($content, 0, 4));
            $xmlLen    = $listLen[1];
            $xml       = substr($content, 4, $xmlLen);
            $fromAppId = trim(substr($content, $xmlLen + 4));
        } catch (Exception $e) {
            //print $e;
            return [ErrorCode::$IllegalBuffer, null];
        }
        if ($fromAppId !== $appId) {
            return [ErrorCode::$ValidateAppidError, null];
        }

        $dataSet = json_decode($xml, true);
        if ($dataSet && (JSON_ERROR_NONE === json_last_error())) {
            // For mini-program JSON formats.
            // Convert to XML if the given string can be decode into a data array.
            $xml = XML::build($dataSet);
        }

        return [ErrorCode::$OK, $xml];
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

    /**
     * Return AESKey.
     *
     * @return string
     *
     * @throws Exception
     */
    protected function getAESKey()
    {
        if (empty($this->AESKey)) {
            throw new Exception("Configuration mission, 'aes_key' is required.");
        }

        if (strlen($this->AESKey) !== 43) {
            throw new Exception("The length of 'aes_key' must be 43.");
        }

        return base64_decode($this->AESKey . '=', true);
    }

    /**
     * Decode string.
     *
     * @param string $decrypted
     *
     * @return string
     */
    public function decode($decrypted)
    {
        $pad = ord(substr($decrypted, -1));

        if ($pad < 1 || $pad > $this->blockSize) {
            $pad = 0;
        }

        return substr($decrypted, 0, (strlen($decrypted) - $pad));
    }

    /**
     * Encode string.
     *
     * @param string $text
     *
     * @return string
     */
    public function encode($text)
    {
        $padAmount = $this->blockSize - (strlen($text) % $this->blockSize);

        $padAmount = $padAmount !== 0 ? $padAmount : $this->blockSize;

        $padChr = chr($padAmount);

        $tmp = '';

        for ($index = 0; $index < $padAmount; ++$index) {
            $tmp .= $padChr;
        }

        return $text . $tmp;
    }

}
