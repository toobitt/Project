<?php
require('global.php');
define('MOD_UNIQUEID','pageUpdate');//模块标识
class pageUpdateApi extends adminBase
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
		include(CUR_CONF_PATH . 'lib/page.class.php');
		$this->obj = new page();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{	
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		
		$name = $this->input['name'];
		if(!$name = $this->input['name'])
		{
			$this->errorOutput("请填写页面标题");
		}
		
		//新建页面默认值
		$info = array(
			'site_id'		=> $this->input['siteid'],
			'name'			=> $name,
			'file_type'		=> '.php',
			'file_name'		=> 'index',
			'client'		=> '2',
		
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
		$dir = 'catalog_'.$ret;
		$sql = "UPDATE " . DB_PREFIX ."page SET  dir = "."'".$dir."'"." WHERE id =".$ret;
		$this->db->query($sql);		
		$tmp = array();
		$tmp['id'] = $ret;
		$this->addItem($tmp);
		$this->output();
	}
	
	function update()
	{	
		$name = $this->input['name'];
		if(!$name = $this->input['name'])
		{
			$this->errorOutput("请填写页面标题");
		}
		
		$data = array(
			'id'			=> $this->input['id'],
			'name'			=> $name,
            'page_type'		=> $this->input['page_type'],
			'client'		=> $this->input['client'],
            'brief'			=> $this->input['brief'],
            'dir'			=> $this->input['dir'],
		 	'file_name'		=> $this->input['file_name'],
			'file_type'		=> $this->input['file_type'],
			'domain_name'	=> $this->input['domain_name'],
			'seo'			=> $this->input['seo'],
			'create_time'	=> TIMENOW,
		);
		if($column_id = $this->input['column_id'])
		{
			$data['column_id'] = $column_id;
		}
		
		$ret = $this->obj->update($data);
		$this->addItem($ret);
		$this->output();
	}
	
	
	function delete()
	{	
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}		
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = urldecode($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput("请选择需要删除的模板");
		}
		$ret = $this->obj->delete($id);
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

$out = new pageUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>