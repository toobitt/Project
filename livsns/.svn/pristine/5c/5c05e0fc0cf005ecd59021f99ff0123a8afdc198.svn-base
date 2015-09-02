<?php
require('global.php');
define('MOD_UNIQUEID','mode_sort');//模块标识
class modeSortUpdateApi extends adminUpdateBase
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
		include(CUR_CONF_PATH . 'lib/mode_sort.class.php');
		$this->obj = new modeSort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('mode_sort',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		//获取类型
		$name = $this->input['name'];
		if(!$name)
		{
			$this->errorOutput("请添加样式分类名称");
		}
		$sql = "select id from " . DB_PREFIX . "cell_mode_sort where name = '".$name ."'";
		$q= $this->db->query_first($sql);
		if($q)
		{
			$this->errorOutput("样式分类已存在");
		}
		$data = array(
			'name'			=> trim(urldecode($this->input['name'])),
            'fid'			=> '0',
            //'site_id'		=> $this->input['site_id'],
		);
		$ret = $this->obj->create($data);
		$this->addItem($ret);
		$this->output();
	}
	
	function update()
	{

		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('mode_sort',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$data = array(
			'id'       		=> intval($this->input['id']),
			'name'			=> urldecode($this->input['name']),
		);
		$ret = $this->obj->update($data);
		$this->addItem($ret);
		$this->output();
	}
	
	
	function edit_update()
	{	
		$data['content'] = htmlspecialchars_decode(urldecode($this->input['content']));
		$data['id'] = $this->input['id'];
		$data['type'] = $this->input['type'];
		
		$ret = $this->obj->edit_update($data);
		$this->addItem($ret);
		$this->output();
	}
	
	function upload_()
	{	
				
	}
	//样式上传
	function upload()
	{			
		if(empty($_FILES))
		{
			$this->errorOutput("请上传样式");
		}
		$content = file_get_contents($_FILES['file_data']['tmp_name']);
		
		$info = array(
			'id'       => intval($this->input['id']),
			'content'  => $content,
		);
		$ret = $this->obj->upload($info);
		$this->addItem($ret);
		$this->output();		
	}
	
	function delete()
	{			
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('mode_sort',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		
		$ids = $this->input['id'];
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的样式分类");
		}
		
		$sql = "select id from " . DB_PREFIX . "cell_mode where sort_id  IN(" . $ids . ")";
		$q= $this->db->query_first($sql);
		if($q)
		{
			$this->errorOutput("请删除分类下的样式");
		}
		
		$ret = $this->obj->delete($ids);
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

$out = new modeSortUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>