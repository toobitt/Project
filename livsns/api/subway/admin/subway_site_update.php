<?php
require('global.php');
define('MOD_UNIQUEID','subway_site');//模块标识
class subwaySiteUpdateApi extends adminUpdateBase
{

	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/subway_site.class.php');
		$this->obj = new subwaySite();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create() 
	{	
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填写地铁站点名称");
		}
		$data = array(
			'title'				=> $title,
            'sub_id'			=> $this->input['sub_id'],
            'sign'				=> $this->input['sign'],
            'brief'				=> $this->input['brief'],
            'longitude'			=> $this->input['longitude'],
            'latitude'			=> $this->input['latitude'],
			'pic'           	=> $this->input['log'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'user_id'	 		=> $this->user['user_id'],
			'user_name'	  		=> $this->user['user_name'],			 	
			'ip'          		=> $this->user['ip'],
			'org_id'			=> $this->user['org_id'],
		);
		$ret = $this->obj->create($data);
		$data['id'] = $ret;
		$this->addLogs('新增地铁站点' , '' , $data , $data['title']);
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{	
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填写地铁站点名称");
		}
		$data = array(
			'id'				=> $this->input['id'],
			'title'				=> $title,
            'sub_id'			=> $this->input['sub_id'],
            'sign'				=> $this->input['sign'],
            'brief'				=> $this->input['brief'],
            'longitude'			=> $this->input['longitude'],
            'latitude'			=> $this->input['latitude'],
			'pic'           	=> $this->input['log'],
			'create_time'		=> TIMENOW,
		);
		
		$s =  "SELECT * FROM " . DB_PREFIX . "subway_site WHERE id = " . $this->input['id'];
		$pre_data = $this->db->query_first($s);
		
		$ret = $this->obj->update($data);	
	
		$sq =  "SELECT * FROM " . DB_PREFIX . "subway_site WHERE id = " . $this->input['id'];
		$up_data = $this->db->query_first($sq);
		
		$this->addLogs('更新地铁站点' , $pre_data , $up_data , $pre_data['title']);
		$this->addItem($ret);
		$this->output();
	}
	
	
	function delete()
	{	
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的地铁站点");
		}
		
		$sqll =  "SELECT * FROM " . DB_PREFIX . "subway_site WHERE id IN (" . $ids . ")";
		$sll = $this->db->query($sqll);
		$ret = array();
		while($rowl = $this->db->fetch_array($sll))
		{
			$pre_data[] = $rowl;
		}
		
		$ret = $this->obj->delete($ids);
		if($ret)
		{
			$this->addLogs('删除地铁站点' , $pre_data , '', '删除地铁站点'.$ids);
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

$out = new subwaySiteUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>