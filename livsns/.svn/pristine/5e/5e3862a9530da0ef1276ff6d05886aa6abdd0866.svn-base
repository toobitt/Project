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
 * @description 外部调取获取地区的列表信息
 **************************************************************************/
define('MOD_UNIQUEID', 'getArea');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/area_mode.php');

class getArea extends outerReadBase
{
    private $mode;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new area_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function detail(){}
    public function count(){}

    /**
     * 获取省
     *
     * @access public
     * @param  无
     * @return array
     */
    public function show()
    {
        $ret = $this->mode->show();
        if(!empty($ret))
        {
            foreach($ret as $k => $v)
            {
                $this->addItem($v);
            }
            $this->output();
        }
    }

    /**
     * 获取城市
     *
     * @access public
     * @param  无
     * @return array
     */
    public function getCity()
    {
        $province_code = $this->input['province_code'];
        if(!$province_code)
        {
            $this->errorOutput(NO_PROVINCE_CODE);
        }

        $city = $this->mode->getCity($province_code);
        if(!empty($city))
        {
            foreach($city as $k => $v)
            {
                $this->addItem($v);
            }
            $this->output();
        }
    }

    /**
     * 获取区县
     *
     * @access public
     * @param  无
     * @return array
     */
    public function getDistrict()
    {
        $city_code = $this->input['city_code'];
        if(!$city_code)
        {
            $this->errorOutput(NO_CITY_CODE);
        }

        $district = $this->mode->getDistrict($city_code);
        if(!empty($district))
        {
            foreach($district as $k => $v)
            {
                $this->addItem($v);
            }
            $this->output();
        }
    }
}

$out = new getArea();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();