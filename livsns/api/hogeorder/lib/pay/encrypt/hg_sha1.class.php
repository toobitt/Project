<?php
/**
 * Md5���� ��֤
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/19
 * Time: ����1:38
 */

class HgSha1 {

    /**
     * ǩ���ַ���
     * @param $prestr ��Ҫǩ�����ַ���
     * @param $key ˽Կ
     * return ǩ�����
     */
    static function encrypt($prestr, $key = '') {
        $prestr = $prestr . $key;
        return SHA1($prestr);
    }

    /**
     * ��֤ǩ��
     * @param $prestr ��Ҫǩ�����ַ���
     * @param $sign ǩ�����
     * @param $key ˽Կ
     * return ǩ�����
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