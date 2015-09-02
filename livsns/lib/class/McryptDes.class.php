<?php
/*
 * DES 加密类
 */
class McryptDes
{
	//加密
	public function des_encrypt($string, $key) 
	{
	    $size = mcrypt_get_block_size('des', 'ecb');
	    $string = mb_convert_encoding($string, 'GBK', 'UTF-8');
	    $pad = $size - (strlen($string) % $size);
	    $string = $string . str_repeat(chr($pad), $pad);
	    $td = mcrypt_module_open('des', '', 'ecb', '');
	    $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	    @mcrypt_generic_init($td, $key, $iv);
	    $data = mcrypt_generic($td, $string);
	    mcrypt_generic_deinit($td);
	    mcrypt_module_close($td);
	    $data = base64_encode($data);
	    return $data;
	}
	
	//解密，解密后返回的是json格式的字符串
	public function des_decrypt($string, $key)
	{
	    $string = base64_decode($string);
	    $td = mcrypt_module_open('des', '', 'ecb', '');
	    $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	    $ks = mcrypt_enc_get_key_size($td);
	    @mcrypt_generic_init($td, $key, $iv);
	    $decrypted = mdecrypt_generic($td, $string);
	    mcrypt_generic_deinit($td);
	    mcrypt_module_close($td);
	    $pad = ord($decrypted{strlen($decrypted) - 1});
	    if($pad > strlen($decrypted)) {
	        return false;
	    }
	    if(strspn($decrypted, chr($pad), strlen($decrypted) - $pad) != $pad) {
	        return false;
	    }
	    $result = substr($decrypted, 0, -1 * $pad);
	    $result = mb_convert_encoding($result, 'UTF-8', 'GBK');
	    return $result;
	}
}