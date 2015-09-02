<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 6930 2012-05-31 07:16:07Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','statistics');//模块标识
class statisticsApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		$this->mPrmsMethods = array(
			'manage'		=>'管理',
		);
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/statistics.class.php');
		$this->obj = new statistics();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$uniqueid = '';
		$appname = array();
		$app = $this->obj->get_apps($this->get_condition());
		$this->addItem($app);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."app a1 LEFT JOIN ".DB_PREFIX."app a2 ON a1.father=a2.id ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		$bundle = urldecode($this->input['_id']);
		$condition = " WHERE a1.father!=0";
		if(!empty($bundle))
		{
			$condition = " WHERE a1.father!=0 AND a2.bundle='".$bundle."'";
		}
		return $condition;	
	}

	public function get_settings()
	{	
		if($module_uniqueid = urldecode($this->input['module_uniqueid']))
		{
			$app_uniqueid = urldecode($this->input['app_uniqueid']);
			$appset = $this->obj->get_app_set($module_uniqueid,$app_uniqueid);
			
//			if(empty($appset))
//			{
//				foreach($this->settings['statistics_app_set'] as $k=>$v)
//				{
//					$arr[] = array('var_name'=>$k,'value'=>$v);
//				}
//				$appset = $arr;
//			}
			
			$data['app_uniqueid'] = $app_uniqueid;
			$data['module_uniqueid'] = $module_uniqueid;
			$data['appset'] = $appset;
			$this->addItem($data);
			$this->output();
		}
		else
		{
			$this->errorOutput("无相对模块");
		}
	}

	public function detail()
	{
	
		$ret = array();
		if($id = $this->input['id'])
		{
			$ret = $this->obj->get_account_by_id($id);
			$ret['picurl'] = UPLOAD_THUMB_URL.$ret['picurl'];
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		$app_uniqueid = $this->input['app_uniqueid'];
		$module_uniqueid = $this->input['module_uniqueid'];
		$status = $this->input['status'];
		if(!$module_uniqueid || !$app_uniqueid)
		{
			$this->errorOutput("缺少应用模块标识");
		}
		$appset = $this->obj->get_app_set($module_uniqueid,$app_uniqueid);
		$setdata = array(
			'app_uniqueid' => $app_uniqueid,
			'module_uniqueid' => $module_uniqueid,
			'status' => $status,
		);
		if($appset)
		{
			
			$this->obj->update_app_set($setdata);
		}
		else
		{
			$this->obj->insert_app_set($setdata);
		}
	}
	
	public function create()
	{
		
	}
	
	//统计设置liv_app 应用模块标识添加
	public function insert_app()
	{
		$stat_app_arr = urldecode($this->input['stat_app_arr']);
		if($stat_app_arr['uniqueid'] && $stat_app_arr['name'])
		{
			if(!$this->obj->insert_app($stat_app_arr))
			{
				$this->errorOutput("设置添加失败");
			}
		}
		else
		{
			$this->errorOutput("设置添加失败");
		}
	}
	
	//统计设置liv_app_setting 应用模块标识各种设置添加
	public function insert_app_set()
	{
		$stat_app_arr = urldecode($this->input['stat_app_arr']);
		if($stat_app_arr['app_uniqueid'] && $stat_app_arr['module_uniqueid'])
		{
			if(!$this->obj->insert_app_set($stat_app_arr))
			{
				$this->errorOutput("设置添加失败");
			}
		}
		else
		{
			$this->errorOutput("设置添加失败");
		}
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new statisticsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			