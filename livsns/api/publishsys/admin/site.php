<?php
require('global.php');
require(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','publishsys_site');//模块标识
class siteApi extends nodeFrm
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH."lib/common.php");
		include(CUR_CONF_PATH . 'lib/site.class.php');
		$this->obj = new site();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$site_id = '';
		$sitedata = array();
		if($site_id = intval($this->input['_id']))
		{
			$sitedata = $this->obj->get_site_by_id($site_id);
		}
		$this->addItem($sitedata);
		$this->output();
	}
	
	public function site_node()
	{
		$sitedata = $this->obj->get_site("id,site_name",'');
		$fid = $this->input['fid']?'0':$this->input['fid'];
		if($sitedata)
		{
			foreach($sitedata as $k=>$v)
			{
				$m = array('id'=>$v['id'],"name"=>$v['site_name'],"fid"=>$fid,"depth"=>1 ,'is_last'=>1);
				$this->addItem($m);
			}
		}
		else
		{
			$m = array('id'=>0,"name"=>'',"fid"=>'',"depth"=>1 ,'is_last'=>1);
			$this->addItem($m);
		}
		
		$this->output();
	}
	
	public function count()
	{
		$sql = "".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		$condition = '';
		return $condition;	
	}
	
	public function site_form()
	{
		
	}
	
	public function create_update()
	{
		$site_id = intval($this->input['site_id']);
		$data = array(
			'site_name' => urldecode($this->input['site_name']),
			'site_keywords' => urldecode($this->input['site_keywords']),
			'content' => urldecode($this->input['content']),
			'weburl' => urldecode($this->input['weburl']),
			'site_dir' => urldecode($this->input['site_dir']),
			'produce_format' => urldecode($this->input['produce_format']),
			'indexname' => urldecode($this->input['indexname']),
			'suffix' => urldecode($this->input['suffix']),
			'material_fmt' => urldecode($this->input['material_fmt']),
			'material_url' => urldecode($this->input['material_url']),
			'tem_material_url' => urldecode($this->input['tem_material_url']),
			'tem_material_dir' => urldecode($this->input['tem_material_dir']),
			'program_dir' => urldecode($this->input['program_dir']),
			'jsphpdir' => urldecode($this->input['jsphpdir']),
			'imagewidth' => intval($this->input['imagewidth']),
			'imageheight' => intval($this->input['imageheight']),
			'pro_page_num' => intval($this->input['pro_page_num']),
		);
		if(empty($data['site_name']) || empty($data['weburl']))
		{
			$this->errorOutput("填写信息不全");
		}
		if($site_id)
		{
			//先查询这个站点跟目录是否被应用
			if(!common::check_domain($data['weburl'],$data['site_dir'],$this->settings['domain_type']['site'],$site_id))
			{
				$this->errorOutput("域名或对应目录存在，请重新输入！");
			}
			//更新
			if(!$site = $this->obj->update_site($site_id,$data))
			{
				$this->errorOutput("更新失败！");
			}
			$this->addItem($site);
			$this->output();
		}
		else
		{
			//先查询这个站点跟目录是否被应用
			if(!common::check_domain($data['weburl'],$data['site_dir']))
			{
				$this->errorOutput("域名或对应目录存在，请重新输入！");
			}
			//插入
			if($site_id = $this->obj->insert_site($data))
			{
				common::insert_domain($data['weburl'],$data['site_dir'],$this->settings['domain_type']['site'],$site_id);
			}
			else
			{
				$this->errorOutput("添加失败！");
			}
		}
	}
	
	public function delete()
	{
		$site_id = intval($this->input['site_id']);
		if($site_id)
		{
			$this->obj->delete($site_id);
		}
		else
		{
			$this->errorOutput("删除失败！");
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

$out = new siteApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
