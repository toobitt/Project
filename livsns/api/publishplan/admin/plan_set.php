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
define('MOD_UNIQUEID','publish_plan_set');
class plan_setApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include plan_set.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		include(CUR_CONF_PATH . 'lib/plan_set.class.php');
		$this->obj = new plan_set();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$father_set = array();
		$set_id = intval($this->input['set_id']);
		$num = intval($this->input['selectnum']);
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$info = $this->obj->get_set_by_fid(' id,name ',$this->get_condition(),$offset,$count);
		$set_idnum = intval($this->input['set_id'.$num]);
		if($set_idnum && $set_idnum!=-1)
		{
			$set_id = $set_idnum;
		}
		//查询出上级配置
		if($set_id)
		{
			$this->obj->get_father_set($set_id,$father_set);
		}
		$alldata['set_data'] = $info;
		$alldata['father_set'] = $father_set;
		$this->addItem($alldata);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."plan_set ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	private function get_condition()
	{
		$condition = '';
		$set_id = intval($this->input['set_id']);
		$selectnum = intval($this->input['selectnum']);
		$set_idnum = intval($this->input['set_id'.$selectnum]);
		if($set_idnum && $set_idnum!=-1)
		{
			$set_id = $set_idnum;
		}
		if($set_id)
		{
			$condition .= " AND fid=".$set_id;
		}
		else
		{
			$condition .= " AND fid=0";
		}
		$condition .= " ORDER BY id ASC ";
		return $condition;
	}
	
	
	public function form()
	{
		$id = $this->input['id'];
		$info = array();
		if($id)
		{
			$info = $this->obj->get_set($id);
			if(empty($info))
			{
				$this->errorOutput('没有相关配置信息');
			}
		}
		
		//查询出所有配置
		$allset = $this->obj->get_all_set(' id,name ');
		$info['allset'] = $allset;
		$this->addItem($info);
		$this->output();
	}
	
	public function update()
	{
		$id = $this->input['id'];
		if($id)
		{
			$data = array(
				'name' => urldecode($this->input['name']),
				'bundle_id' => urldecode($this->input['bundle_id']),
				'module_id' => urldecode($this->input['module_id']),
				'struct_id' => urldecode($this->input['struct_id']),
				'struct_ast_id' => urldecode($this->input['struct_ast_id']),
				'num' => intval($this->input['num']),
				'host' => urldecode($this->input['host']),
				'path' => urldecode($this->input['path']),
				'filename' => urldecode($this->input['filename']),
				'action_get_content' => urldecode($this->input['action_get_content']),
				'action_insert_contentid' => urldecode($this->input['action_insert_contentid']),
			);
			$this->obj->update_set($data,$id);
			$info = $this->obj->get_set($id);
			$this->addItem($info);
			$this->output();
		}
		else
		{
			$this->errorOutput('更新失败');
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

$out = new plan_setApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			