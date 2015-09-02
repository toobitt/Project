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
define('MOD_UNIQUEID','share');//模块标识
require(ROOT_PATH.'lib/class/auth.class.php');
class shareappApi extends adminBase
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
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$this->auth = new Auth();
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$apparr = $pd = $appidarr = array();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):1500;
		
		$datas = $this->auth->get_auth_list($offset,$count);
		if(!is_array($datas))
		{
			$this->errorOutput('NO_AUTH');
		}
		foreach($datas as $k=>$v)
		{
			$appidarr[] = $v['appid'];
		}
		if($appids = implode(',',$appidarr))
		{
			$auth_data = $this->obj->get_auth_app($appids);
		}
		
//		$datas = $this->obj->get_app_datas($offset,$count,$this->get_condition());
		$platdatas = $this->obj->get_all_plat();
		foreach($platdatas as $k=>$v)
		{
			$pd[$v['id']] = $v['name'];
		}
		$all['apparr'] = $datas;
		$all['platdata'] = $pd;
		$all['auth_data'] = $auth_data;
		
		$this->addItem($all);
		$this->output();
	}
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."plat ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		if($status = intval($this->input['_id']))
		{
			$condition = " WHERE status='".$status."'";
		}
		
		return $condition;	
	}

	public function update()
	{
		if($id = intval($this->input['id']))
		{
			if($all = $this->obj->update_app_by_Id($id))
			{
				$this->addItem($all);
				$this->output();
			}
			else
			{
				$this->errorOutput('更新失败');
			}
		}
		else
		{
			$this->errorOutput('更新失败');
		}
		
	}
	
	public function detail()
	{
		$ret = array();
		if($id = intval($this->input['id']))
		{
			$authdata = $this->auth->get_auth_detail($id);
			$data1 = $this->obj->get_app_by_Id($id);
			$authdata['platIds'] = empty($data1['platIds'])?'':$data1['platIds'];
			$authdata['status'] = $data1['status'];
			$platdatas = $this->obj->get_all_plat();
			
			if($platdatas)
			{
				foreach($platdatas as $k=>$v)
				{
					$pd[$v['id']] = $v['name'];
				}
				$ret['apparr'] = $authdata;
				$ret['platdata'] = $pd;
				
				$this->addItem($ret);
				$this->output();
			}
			
		}
	}
	
	public function show_opration()
	{
		if($id = intval($this->input['id']))
		{
			$authdata = $this->auth->get_auth_detail($id);
			$data1 = $this->obj->get_app_by_Id($id);
			$authdata['platIds'] = empty($data1['platIds'])?'':$data1['platIds'];
			$authdata['status'] = $data1['status'];
			$platdatas = $this->obj->get_all_plat();
			
			if($platdatas)
			{
				foreach($platdatas as $k=>$v)
				{
					$pd[$v['id']] = $v['name'];
				}
				$ret['apparr'] = $authdata;
				$ret['platdata'] = $pd;
				
				$this->addItem($ret);
				$this->output();
			}
			
		}
		else
		{
			$this->errorOutput('查询失败');
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

$out = new shareappApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			