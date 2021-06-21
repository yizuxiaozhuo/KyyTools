<?php
/**
 * 一个加密解密的类
 * @author: Hahn
 * @date: 2021/6/21
 * @time: 20:15 下午
 * @description：
 */

namespace Kyy\Tools;
use Kyy\Traits\Single;

class Security {

    use Single;

    private $secret_key;

    /**
     * Security constructor.
     * @param string $secret_key 加密密钥
     */
    public function __construct($secret_key = '') {
        if (empty($secret_key)) {
            $secret_key = 'bc35801c4f8ff4f223a11f5e9cf8359b';
        }
        $this->secret_key = $secret_key;
    }


    /**
     * 加密
     * @param string $transmit_data 要加密的字符串
     * @param int $expire 过期时间 (单位:秒)
     * @return string|string[]
     */
    public function encrypt(string $transmit_data, $expire = 0) {
        $data = base64_encode($transmit_data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($this->secret_key);
        $char = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($this->secret_key, $x, 1);
            $x++;
        }
        $str = sprintf('%010d', $expire ? $expire + time() : 0);
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
        }
        $str = base64_encode($str);
        $str = str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), $str);
        return $str;
    }

    /**
     * 解密
     * @param string $decrypt_data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
     * @return string
     */
    public function decrypt(string $decrypt_data): string {
        $data   = str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $decrypt_data);
        $x      = 0;
        $data   = base64_decode($data);
        $expire = substr($data, 0, 10);
        $data   = substr($data, 10);
        if ($expire > 0 && $expire < time()) {
            return '';
        }
        $len  = strlen($data);
        $l    = strlen($this->secret_key);
        $char = $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($this->secret_key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return base64_decode($str);
    }
}
