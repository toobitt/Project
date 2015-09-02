<?php
//nousing
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','special_content_sort');//模块标识
class specialContentSortUpdateApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/special_content_sort.class.php');
		$this->obj = new specialContentSort();	
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{
		if(empty($this->input['name']))
		{
			$this->errorOutput("请填写主题分类名称");
		}
		$info = array(
			'name'			=> 		$this->input['name'],
			'brief'			=> 		$this->input['brief'],
			'speid'			=> 		$this->input['speid'],
		);
		$info['fid'] = 0;
		/*if(intval($this->input['fid']))
		{
			$info['fid'] = intval($this->input['fid']);
		}
		else 
		{
			$info['fid'] = 0;
		}*/
		$ret = $this->obj->create($info);
		$this->addItem($ret);
		$this->output();
	}
	
	function delete()
	{			
		$id = intval($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput("请选择需要删除的专题分类");
		}
		$sql = 'SELECT id
				FROM '.DB_PREFIX.'special_content WHERE content_sort_id = '.$id;
		$r = $this->db->query_first($sql);
		if($r)
		{
			$this->errorOutput("请先删除分类下的内容");
		}
		$ret = $this->obj->delete($id);
		$this->addItem($ret);
		$this->output();	
	}
	
	function update()
	{	
		if(empty($this->input['name']))
		{
			$this->errorOutput("请填写专题分类名称");
		}
		$info = array(
			'id'			=> intval($this->input['id']),
			'name'			=> urldecode($this->input['name']),
			'brief'			=> urldecode($this->input['brief']),
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

$out = new specialContentSortUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>