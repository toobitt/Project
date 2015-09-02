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
 * @description 排名接口
 **************************************************************************/
define('MOD_UNIQUEID','app_rank');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');

class app_rank extends adminReadBase
{
    private $_app;
    public function __construct()
	{
		parent::__construct();
		$this->_app = new app();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
    public function detail(){}
    public function count(){}

	public function show()
	{
	    //去统计应用取数据
        $curl = new curl($this->settings['App_dingdonestatistics']['host'], $this->settings['App_dingdonestatistics']['dir']);
        $curl->setSubmitType('get');
        $curl->initPostData();
        $curl->addRequestData('a','getInstallRank');
        $curl->addRequestData('order_num',20);//取多少位的排名
        $ret  = $curl->request('getActivateStatistics.php');
        if($ret)
        {
            $appIdArr = array();
            foreach ($ret AS $k => $v)
            {
               $appIdArr[] = $v['app_id'];
            }
            
            //取应用的名称
            $appInfo = $this->_app->getAppList(" AND id IN (" .implode(',', $appIdArr). ")");
            $this->addItem_withkey('app', $appInfo);
            $this->addItem_withkey('rank', $ret);
            $this->output();
        }
	}
}

$out = new app_rank();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();