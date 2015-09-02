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
 * @description jssdk接口
 **************************************************************************/
define('MOD_UNIQUEID','jssdk');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/jssdk_mode.php');
require_once(CUR_CONF_PATH . 'lib/jssdk_api_mode.php');

class jssdk extends outerUpdateBase
{
    private $mode;
    private $api_mode;
    public function __construct()
    {
        parent::__construct();
        $this->mode     = new jssdk_mode();
        $this->api_mode = new jssdk_api_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    
    //显示某个人的
    public function show()
    {
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
    }
    
    public function count()
    {
        $condition = $this->get_condition();
        $info = $this->mode->count($condition);
        echo json_encode($info);
    }
    
    public function get_condition()
	{
		$condition = '';

		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['is_open'])
		{
		    $condition .= " AND is_open = 1 ";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  domain  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	//验证url是否合法，如果合法需要返回该域名对应的可调用的api列表
	public function checkUrlIsOk()
	{
	    $url = trim($this->input['url']);
	    if(!$url)
	    {
	        $this->errorOutput(NO_URL);
	    }
	    //获取该链接的域名
	    $domain = Common::getUrlDomain($url);
	    if(!$domain)
	    {
	        $this->errorOutput(URL_NOT_VALID);
	    }
	    
	    //查询解析出来的域名在不在白名单里面
	    $whiteDomain = $this->mode->detail(''," AND domain = '" .$domain. "' AND is_open = 1 ");
	    if(!$whiteDomain)
	    {
	        $this->errorOutput(URL_NOT_IN_WHITE);
	    }
	    
	    //如果存在就返回对应层级的接口
	    $cond = " AND level >= '" .$whiteDomain['level']. "' AND is_open = 1 ";
	    $orderBy = " ORDER BY order_id DESC ";
	    $apiList = $this->api_mode->show($cond,$orderBy);
	    $apiNameList = array();
	    if($apiList)
	    {
	        foreach($apiList AS $k => $v)
	        {
	            $apiNameList[] = $v['ename'];
	        }
	    }
	    
	    //返回数据
	    $this->addItem(array(
	        'expire_in' => $this->settings['jssdk']['expire_in'],
	        'apilist'	=> $apiNameList,
	    ));
	    
	    $this->output();
	}
	
    public function create(){}
    public function update(){}
    public function delete(){}
    
    public function unkow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new jssdk();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unkow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();