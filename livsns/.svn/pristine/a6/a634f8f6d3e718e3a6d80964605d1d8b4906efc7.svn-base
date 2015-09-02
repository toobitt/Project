<?php
define('MOD_UNIQUEID','page');//模块标识
require('global.php');
class pageApi extends adminBase
{
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
	
	function  show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit);
		foreach($ret as $v)
		{
			$this->addItem($v);	
		}
		$this->output();		
	}

	function detail()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'page WHERE id = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		$ret[] = $r;
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'page WHERE 1 '.$this->get_condition();
		$page_total = $this->db->query_first($sql);
		echo json_encode($page_total);	
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function get_condition()
	{		
		$condition = '';
		//查询应用分组
		if(intval($this->input['ptype']) && intval($this->input['ptype']) != '-1')
		{
			$condition .=" AND page_type =".intval($this->input['ptype']);
		}
		if(intval($this->input['fid']))
		{
			$condition .=" AND fid =". intval($this->input['fid']);
		}
		else
		{
			$condition .=" AND fid = 0";
		}
		if($this->input['k'])
		{
			$condition = " AND name like '%".urldecode($this->input['k'])."%' ";
		}
		if(isset($this->input['is_push']))
		{
			$condition = " AND is_push=".intval($this->input['is_push']);
		}
		return $condition;
	}
	
	//获取页面类型
	public function get_page_type()	
	{	
		$page_types = $this->settings['page_type'];
		$this->addItem($page_types);
		$this->output();
	}	
	
	//获取专题
	public function get_special()	
	{	
		require_once(ROOT_PATH . 'lib/class/special.class.php');
		$this->spe = new special();
		$sort_id = intval($this->input['sort_id']);
		$special = $this->spe->get_special($sort_id);
		if(!$special)
		{
			$special[] = '无专题';
		}
		$this->addItem($special);
		$this->output();
	}
	
	//获取客户端名称
	public function get_client()	
	{	
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
		$clients = $this->pub->get_client();
		foreach($clients as $ke =>$va)
		{
			$client[$va['id']] = $va['name'];
		}
		$this->addItem($client);
		$this->output();
	}	
	
	public function page_block()
	{
		$site_id = intval($this->input['site_id']);
		$fid = intval($this->input['fid']);
		$fid = 1;
		$sql = "SELECT id,name,fid,is_push,childs,depath,is_last,order_id FROM ".DB_PREFIX."page WHERE is_push=1 AND fid=".$fid ;
		if(!$fid)
		{
			$sql .= " AND site_id=".$site_id;
		}
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$pages[] = $row;
		}
		
		if($fid)
		{
			$sql = "SELECT p.page_id,b.id as block_id,b.name as block_name FROM ".DB_PREFIX."page_block_relation p LEFT JOIN ".DB_PREFIX."block b ON p.block_id=b.id WHERE p.page_id=".$fid;
			$info = $this->db->query($sql);
			while($row = $this->db->fetch_array($info))
			{
				$blocks[] = $row;
			}
		}
		
		$result['page'] = $pages;
		$result['block'] = $blocks;
		$this->addItem($result);
		$this->output();
	}	

}

$out = new pageApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>