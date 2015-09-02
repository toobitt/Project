<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','special_sort');//模块标识
class specialSortUpdateApi extends nodeFrm
{
	public function __construct()
	{
	    //检测是否具有管理权限
	   
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/special_sort.class.php');
		$this->obj = new specialSort();	
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{
		$info = array(
			'name'			=> urldecode($this->input['name']),
		);
		if(intval($this->input['fid']))
		{
			$info['fid'] = intval($this->input['fid']);
		}
		else 
		{
			$info['fid'] = 0;
		}
		$ret = $this->obj->create($info);
		$tmp = array();
		$tmp['id'] = $ret;
		$this->addItem($tmp);
		$this->output();
	}
	
	function delete()
	{			
		$id = intval($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput("请选择需要删除的专题分类");
		}
		if($id)
		{	
			$sql = "SELECT *
					FROM  " . DB_PREFIX ."special 
					WHERE sort_id = ".$id;
			$re = $this->db->query_first($sql);
			if($re)
			{
				$this->errorOutput("请先删除专题分类下的专题");
			}
		}
		$ret = $this->obj->delete($id);
		$this->addItem($ret);
		$this->output();	
	}
	
	function update()
	{	
		if(empty($this->input['name']))
		{
			$this->errorOutput("请填写主题分类名称");
		}
		 $data = array(
			'id' => intval($this->input['id']),
			'name' => trim(urldecode($this->input['name'])),
			'brief' => trim(urldecode($this->input['brief'])),
			'update_time' =>TIMENOW,
            'user_name'=>$this->user['user_name'],
            'ip'=>  hg_getip(),
            'fid'=>intval($this->input['fid']),
		);
		$ret = $this->obj->update($data);
		$this->addItem($ret);
		$this->output();
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

$out = new specialSortUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>