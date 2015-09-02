<?php
require('global.php');
define('MOD_UNIQUEID','template_tag');//模块标识
class templateTagUpdateApi extends adminUpdateBase
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
		include(CUR_CONF_PATH . 'lib/template_tag.class.php');
        $this->obj = new templateTag();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{	
		if(!$this->input['name'])
		{
			$this->errorOutput("请填写模板标签名");
		}
		$data = array();
		//新建页面默认值
		$data = array(
			'name'			=> $this->input['name'],
		);
		$ret = $this->obj->create($data);
		
		$data['id'] = $ret;
		
		$this->addLogs('新增模板标签名' , '' , $data , $data['name']);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{	
		
		if(!$this->input['name'])
		{
			$this->errorOutput("请填写模板标签名");
		}
		
		$data = array(
			'id'					=>	$this->input['id'],
			'name'					=>	$this->input['name'],
		);
		
		$s =  "SELECT * FROM " . DB_PREFIX . "template_tag WHERE id = " . $this->input['id'];
		$pre_data = $this->db->query_first($s);
		
		$data_te = $data;
		$re = $this->obj->update($data_te,'template_tag');
		if($re)
		{
			$ret = $this->obj->update($data,'template_tag');
		
			$sq =  "SELECT * FROM " . DB_PREFIX . "template_tag WHERE id = " . $this->input['id'];
			$up_data = $this->db->query_first($sq);
			
			$this->addLogs('更新模板标签' , $pre_data , $up_data , $pre_data['name']);
		}
		else
		{
				$ret = $this->obj->update($data,'template_tag');
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function delete()
	{		
		$ids = $this->input['id'];
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的模板标签");
		}
		
		$sqll =  "SELECT * FROM " . DB_PREFIX . "template_tag WHERE id IN (" . $ids . ")";
		$sll = $this->db->query($sqll);
		$ret = array();
		while($rowl = $this->db->fetch_array($sll))
		{
			$pre_data[] = $rowl;
		}
		
		$ret = $this->obj->delete($ids);
		if($ret)
		{
			$this->addLogs('删除模板标签' , $pre_data , '', '删除模板标签'.$ids);
		}
		
		$this->addItem($ret);
		$this->output();
		
	}
	
	public function audit()
	{
	}
	public function sort()
	{
	}
	public function publish()
	{
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

$out = new templateTagUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>