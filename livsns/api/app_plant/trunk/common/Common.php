<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-18
 * @encoding    UTF-8
 * @description 公共函数类
 **************************************************************************/
class Common
{
    /**
     * 生成目录结构
     *
     * @access public
     * @param  无
     * @return array
     */
    public static function buildDirStruct($user_id = '')
    {
        $dirNames  = TIMENOW . hg_rand_num(2);
        $dir = date('Y/m/d/',TIMENOW);
        if($user_id)
        {
            $dir = $user_id . '/' . $dir;
        }
        else
        {
            $dir = 'system/' . $dir;//系统图标
        }
        return array($dirNames,$dir);
    }
    

    /*
     * 转换颜色
     * $color:颜色值例如：#123456
     **/
    public static function convertColor($color = '',$alpha = 1)
    {
        if(!$color || !is_string($color) || !strstr($color, '#'))
        {
            //没有颜色输出黑颜色
            return array(
                'aColor' => '#ff000000',
                'color'  => '#000000',
                'alpha'  => 1,
                'drawable' => '',
                'isRepeat' => FALSE,
                'noColor'  => 1,//标识这个实际是没有颜色到
            );
        }
        
        if($alpha > 1)
        {
            $alpha = 1;
        }
        
        if(!$alpha || $alpha < 0)
        {
            $alpha = 0;
        }
        
        $_dex_alpha = dechex($alpha * 255) . '';
        if(strlen($_dex_alpha) < 2)
        {
            $_dex_alpha .= '0';
        }
        
        return array(
            'aColor' => '#' . $_dex_alpha . substr($color, 1),
            'color'  => $color,
            'alpha'  => (float)$alpha,
            'drawable' => '',
            'isRepeat' => FALSE,
        );
    }
    
    //获取图片名
    public static function pickPicName($picName = '')
    {
        if(!$picName)
        {
            return '';
        }   
        
        $arr = explode('.', $picName);
        if($arr)
        {
            return $arr[0];
        }
        else 
        {
            $picName;
        }
    }
    
    //产生一个编号
    public static function getSerialNumber()
    {
        return date('Ymd',TIMENOW) . TIMENOW;
    }
    
    //产生一个叮当号
    public static function getOrderNumber($num = 2)
    {
        return date('YmdHis',TIMENOW) . hg_rand_num($num);
    }
    
    //产生订单号根据用户id
    public static function getOrderNumberByUserID($user_id = '')
    {
        return date('is',TIMENOW) . hg_rand_num(1) . $user_id . hg_rand_num(1);
    }
    
    //返回格式化的扩展字段配置
    public static function getFormatExtendConfig($extend_fields = array())
    {
        if(!$extend_fields || !is_array($extend_fields))
        {
             return array();
        }

        $listUIConfig = array();
        foreach ($extend_fields AS $_item => $_field)
        {
            if ($_field['is_price'])
            {
                $listUIConfig['advanced'] = array(
                    'type'        => 'price',
                    'style'       => 1,
                    'key'         => $_field['field_type'],
                    'isVisiable'  => TRUE,
                );
            }
            else
            {
                //图标+数值  和  数值  text为空
                if(in_array($_field['style_type'],array(2,4)))
                {
                    $_field_text = '';
                }
                else 
                {
                    $_field_text = $_field['text'] . ' ';
                }
                
                //名称+数值  和 数值  icon为空
                if(in_array($_field['style_type'],array(3,4)))
                {
                    $_field_icon = '';
                }
                else 
                {
                    $_field_icon = $_field['icon'];
                }

                $listUIConfig['extend']['extend' . $_field['position']] = array(
                    'key'       => $_field['field_type'],
                    'text'      => $_field_text,
                    'icon'      => $_field_icon,
                    'textSize'  => 10,
                    'textColor' => Common::convertColor('#888888'),
                );
            }
        }
        return $listUIConfig;
    }
    
    //获取格式化的角标的配置
    public static function getFormatCornerConfig($_conerArr = array())
    {
        if(!$_conerArr || !is_array($_conerArr))
        {
             return array();
        }
        
        //加入角标
        $listUIConfig = array(
            'isVisiable'       => (bool) $_conerArr['is_visiable'],
            'textDirection'    => $_conerArr['text_direction'],
            'position'         => $_conerArr['position'],
            'marginBottom'     => $_conerArr['margin_bottom'],
            'marginTop'        => $_conerArr['margin_top'],
            'marginRight'      => $_conerArr['margin_right'],
            'marginLeft'       => $_conerArr['margin_left'],
            'key'              => $_conerArr['field_type'],
            'textColor'        => Common::convertColor('#ffffff'),
            'textSize'         => 14,
            'icon'             => $_conerArr['icon'],
        );
        return $listUIConfig;
    }
    
    
    /**
     * 生成随机的10位的app_mark
     * @return string $rand
     */
    public static function getRandString($length)
    {
    	$char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    	$char = str_shuffle($char);
    	for($i = 0, $rand = '', $l = strlen($char) - 1; $i < $length; $i ++) {
    		$rand .= $char{mt_rand(0, $l)};
    	}
    	return $rand;
    }
    
    /**
     * 获取某个url的根域名
     *
     * @param   string  $url
     * @return  string  $domain | FALSE
     */
    public static function getUrlDomain($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
	    if($host === FALSE)
	    {
	        return FALSE;
	    }
        else
        {
            $domain = self::getUrlToDomain($host);
            return $domain;
        }
    }
    
    //获取域名的根域名
    public static function getUrlToDomain($domain = '')
    {
        $re_domain = '';
        if(!$domain)
        {
            return $re_domain;
        }
        $domain_postfix_cn_array = array("com", "net", "org", "gov", "edu", "com.cn", "cn");
        $array_domain = explode(".", $domain);
        $array_num = count($array_domain) - 1;
        if ($array_domain[$array_num] == 'cn')
        {
            if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) 
            {
                $re_domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
            } 
            else 
            {
                $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
            }
        }
        else
        {
            $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
        return $re_domain;
    }
    
    //获取图片宽高比
    public static function getImageAspect($img_url = '')
    {
        if(!$img_url)
        {
            return 0;
        }
        
        if($arr = getimagesize($img_url))
        {
            return $arr[1]/$arr[0];
        }
        else 
        {
            return 0;
        }
    }
}