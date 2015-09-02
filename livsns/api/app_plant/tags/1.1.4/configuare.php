<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 修改config预处理
 **************************************************************************/
define('ROOT_DIR', '../../');
define('WITHOUT_DB', true);
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');

class configuare extends configuareFrm
{
    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * 获取配置文件信息，再次可以做一些预处理
     *
     * @access public
     * @param  无
     * @return array
     */
    public function settings()
    {
        include_once ROOT_PATH . 'lib/class/applant.class.php';
        $api = new applant();
        /*
         $template_info = $api->getTemplate(array('count' => -1));
         $this->addItem_withkey('template', $template_info);
         */
        $ui_info = $api->getInterface(array('count' => -1));
        $this->addItem_withkey('interface', $ui_info);

        $body_tpl = $api->getBodyTpl();
        $this->addItem_withkey('body_tpl', $body_tpl);
        parent::settings();
    }

    /**
     * 修改配置文件预处理
     *
     * @access public
     * @param  base
     * @return array
     */
    public function doset()
    {
        $baseinfo = $this->input['base'];
        if ($baseinfo['names'])
        {
            $setting_info = array();
            foreach ($baseinfo['names'] as $k => $v)
            {
                $setting_info[$baseinfo['marks'][$k]] = array(
                    'name' => $v,
                    'url' => $baseinfo['urls'][$k]
                );
            }
            unset($this->input['base']['names']);
            unset($this->input['base']['marks']);
            unset($this->input['base']['urls']);
            $this->input['base']['data_url']['file'] = $setting_info;
        }
        //启动方式
        if ($baseinfo['appEffect_identifiers'])
        {
            $effect_info = array();
            foreach ($baseinfo['appEffect_identifiers'] as $k => $v)
            {
                $effect_info[] = array(
		            'identifier' => $v,
		            'option' => $baseinfo['appEffect_options'][$k],
		            'value' => $baseinfo['appEffect_values'][$k],
		            'default' => intval($baseinfo['appEffect_default'][$k])
                );
            }
            unset($this->input['base']['appEffect_identifiers']);
            unset($this->input['base']['appEffect_options']);
            unset($this->input['base']['appEffect_values']);
            unset($this->input['base']['appEffect_default']);
            $this->input['base']['app_effect'] = $effect_info;
        }

        //版权文字大小
        if ($baseinfo['cpTextSize_identifiers'])
        {
            $text_info = array();
            foreach ($baseinfo['cpTextSize_identifiers'] as $k => $v)
            {
                $text_info[] = array(
		            'identifier' => $v,
		            'option' => $baseinfo['cpTextSize_options'][$k],
		            'value' => $baseinfo['cpTextSize_values'][$k],
		            'default' => intval($baseinfo['cpTextSize_default'][$k])
                );
            }
            unset($this->input['base']['cpTextSize_identifiers']);
            unset($this->input['base']['cpTextSize_options']);
            unset($this->input['base']['cpTextSize_values']);
            unset($this->input['base']['cpTextSize_default']);
            $this->input['base']['cpTextSize'] = $text_info;
        }

        //引导图效果
        if ($baseinfo['guideEffect_identifiers'])
        {
            $guide_info = array();
            foreach ($baseinfo['guideEffect_identifiers'] as $k => $v)
            {
                $guide_info[] = array(
		            'identifier' => $v,
		            'option' => $baseinfo['guideEffect_options'][$k],
		            'value' => $baseinfo['guideEffect_values'][$k],
		            'default' => intval($baseinfo['guideEffect_default'][$k])
                );
            }
            unset($this->input['base']['guideEffect_identifiers']);
            unset($this->input['base']['guideEffect_options']);
            unset($this->input['base']['guideEffect_values']);
            unset($this->input['base']['guideEffect_default']);
            $this->input['base']['guideEffect'] = $guide_info;
        }

        //引导图动画
        if ($baseinfo['animation_identifiers'])
        {
            $animation_info = array();
            foreach ($baseinfo['animation_identifiers'] as $k => $v)
            {
                $animation_info[$v] = array(
		            'identifier' => $v,
		            'option' => $baseinfo['animation_options'][$k],
		            'value' => $baseinfo['animation_values'][$k],
		            'default' => intval($baseinfo['animation_default'][$k])
                );
            }
            unset($this->input['base']['animation_identifiers']);
            unset($this->input['base']['animation_options']);
            unset($this->input['base']['animation_values']);
            unset($this->input['base']['animation_default']);
            $this->input['base']['guideAnimation'] = $animation_info;
        }

        //引导图页脚标记
        if ($baseinfo['shape_signs'])
        {
            $shape_signs = array();
            foreach ($baseinfo['shape_signs'] as $k => $v)
            {
                $shape_signs[] = array(
		            'sign' => $v,
		            'default' => intval($baseinfo['sign_default'][$k])
                );
            }
            unset($this->input['base']['shape_signs']);
            unset($this->input['base']['sign_default']);
            $this->input['base']['shapeSign'] = $shape_signs;
        }

        //VIP用户设置
        if ($baseinfo['vip_user'])
        {
            $this->input['base']['vip_user'] = explode('|', $baseinfo['vip_user']);
        }

        $size = array();
        foreach ($baseinfo['icon_size'] as $k => $v)
        {
            $val = explode('|', $v);
            foreach ($val as $vv)
            {
                $arr = explode(',', $vv);
                $size[$k][] = array(
		            'width'  => $arr[0],
		            'height' => $arr[1],
		            'key'	 => $arr[2],
		            'thumb'	 => $arr[3],
                );
            }
        }
        $size['max_size'] = $baseinfo['icon_max_size'];
        unset($this->input['base']['icon_max_size']);
        $this->input['base']['icon_size'] = $size;

        $size = array();
        foreach ($baseinfo['startup_size'] as $k => $v)
        {
            $val = explode('|', $v);
            foreach ($val as $vv)
            {
                $arr = explode(',', $vv);
                $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1],
		        	'key'	 => $arr[2],
		            'thumb'	 => $arr[3],
                );
            }
        }
        $size['max_size'] = $baseinfo['startup_max_size'];
        unset($this->input['base']['startup_max_size']);
        $this->input['base']['startup_size'] = $size;

        $size = array();
        foreach ($baseinfo['guide_size'] as $k => $v)
        {
            $val = explode('|', $v);
            foreach ($val as $vv)
            {
                $arr = explode(',', $vv);
                $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1],
		            'key'	 => $arr[2],
		            'thumb'	 => $arr[3],
		            'effect2'=> $arr[4],
                );
            }
        }
        $size['max_size'] = $baseinfo['guide_max_size'];
        unset($this->input['base']['guide_max_size']);
        $this->input['base']['guide_size'] = $size;

        $size = array();
        foreach ($baseinfo['module_size'] as $k => $v)
        {
            $val = explode('|', $v);
            foreach ($val as $vv)
            {
                $arr = explode(',', $vv);
                $size[$k][] = array(
		            'width'  => $arr[0],
		            'height' => $arr[1],
		        	'key'    => $arr[2],
      				'thumb'  => $arr[3],
                );
            }
        }
        $size['max_size'] = $baseinfo['module_max_size'];
        unset($this->input['base']['module_max_size']);
        $this->input['base']['module_size'] = $size;

        $size = array();
        foreach ($baseinfo['navBarTitle_size'] as $k => $v)
        {
            $val = explode('|', $v);
            foreach ($val as $vv)
            {
                $arr = explode(',', $vv);
                $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1]
                );
            }
        }
        $size['max_size'] = $baseinfo['nav_max_size'];
        unset($this->input['base']['nav_max_size']);
        $this->input['base']['navBarTitle_size'] = $size;

        $size = array();
        foreach ($baseinfo['magazine_size'] as $k => $v)
        {
            $val = explode('|', $v);
            foreach ($val as $vv)
            {
                $arr = explode(',', $vv);
                $size[$k][] = array(
		            'width' => $arr[0],
		            'height' => $arr[1]
                );
            }
        }
        $size['max_size'] = $baseinfo['magazine_max_size'];
        unset($this->input['base']['magazine_max_size']);
        $this->input['base']['magazine_size'] = $size;
        parent::doset();
    }
}
$module = 'configuare';
$$module = new $module();

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
    $func = 'show';
}
$$module->$func();