<?php
/**
 * Md5加密 验证
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/19
 * Time: 上午1:38
 */

class HgSha1 {

    /**
     * 签名字符串
     * @param $prestr 需要签名的字符串
     * @param $key 私钥
     * return 签名结果
     */
    static function encrypt($prestr, $key = '') {
        $prestr = $prestr . $key;
        return SHA1($prestr);
    }

    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    static function verify($prestr, $sign, $key) {
        $prestr = $prestr . $key;
        $mysgin = SHA1($prestr);

        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
    }
} 