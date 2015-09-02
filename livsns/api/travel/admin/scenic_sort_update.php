<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','scenic_sort');//模块标识
class scenicSortUpdateApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/scenic_sort.class.php');
		$this->obj = new scenicSort();	
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{
		if(empty($this->input['name']))
		{
			$this->errorOutput("请填写景区分类名称");
		}
		$info = array(
			'name'		=>		$this->input['name'],
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
			$this->errorOutput("请选择需要删除的景区分类");
		}
		if($id)
		{	
			$sql = "SELECT *
					FROM  " . DB_PREFIX ."scenic 
					WHERE scenic_sort = ".$id;
			$re = $this->db->query_first($sql);
			if($re)
			{
				$this->errorOutput("请先删除景区分类下的景区");
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
			$this->errorOutput("请填写景区分类名称");
		}
		$info = array(
			'id'			=> intval($this->input['id']),
			'name'			=> $this->input['name'],
			'brief'			=> $this->input['brief'],
		);
		$ret = $this->obj->update($info);
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

$out = new scenicSortUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>