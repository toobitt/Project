<?php
require('global.php');
define('MOD_UNIQUEID','page_manage');//模块标识
class pageManageApi extends adminBase
{
	
	public function __construct()
	{	
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/page_manage.class.php');
		$this->obj = new pageManage();
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		include(CUR_CONF_PATH.'lib/common.php');
	}

	public function __destruct()
	{	
		parent::__destruct();
	}
	
	/*public function show()
	{	
		file_put_contents('0',var_export($this->input,1));
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):20;
		$limit = " limit {$offset}, {$count}";
		if(intval($this->input['fid']))
		{
			file_put_contents('03',intval($this->input['fid']));
			include_once(CUR_CONF_PATH.'lib/common.php');
			$page_data = common::get_page_data($this->input['fid'],$offset,$count,0);
			$ret = $page_data['page_data'];
		}
		else
		{
			$ret = $this->obj->show($limit,$condition);
		}
		
		file_put_contents('01',var_export($ret,1));
		foreach($ret as $v)
		{
			$this->addItem($v);
		}
		$this->output();
				
	}*/
	
	public function show()
	{
//		if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if(!in_array('page_manage',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		$site_id = intval($this->input['site_id']);
                $site_idstr = 'site'.($site_id?$site_id:1);
		$fid = ($this->input['fid'])?($this->input['fid']):$site_idstr;
//		$fid = $this->input['fid']?'page_id8':$fid;
		if(empty($fid))
		{
			$sites = $this->pub_config->get_site(' id,site_name ');;
			foreach($sites as $k=>$v)	
			{
				$m = array('id'=>'site'.$v['id'].$this->settings['separator'].$v['site_name'],"name"=>$v['site_name'],"fid"=>0,"depth"=>1);
				//获取页面类型
				$page_type = common::get_page_manage($v['id']);
				if(empty($page_type))
				{
					$m['is_last'] = 1;
				}
				else
				{
					$m['is_last'] = 0;
				}
				$this->addItem($m);
			}
		}
		else
		{
			if(strstr($fid,"site")!==false)
			{
				//点击的站点
				$site_id = str_replace('site','',$fid);
				$get_page = explode($this->settings['separator'],$site_id);
				$page_type = common::get_page_manage($get_page[0]);
				foreach($page_type as $k=>$v)
				{
					$m = array('id'=>'page_id'.$v['id'].$this->settings['separator'].$v['title'],"name"=>$v['title'],"fid"=>'page_id'.$v['id'],"depth"=>1);
					$page_data = common::get_page_data($v['id'],0,1);
					if(empty($page_data['page_data'][0]))
					{
						if(!$v['has_content']&&$v['is_last'])
						{
							$m['is_last'] = 1;
						}
						else
						{
							$m['is_last'] = 0;
						}
					}
					else
					{
						$m['is_last'] = 0;
					}
					$m['is_root'] = 1;
					$a[] = $m;
					$this->addItem($m);
				}
			}
			else if(strstr($fid,"page_id")!==false)
			{
				//点击的页面类型
				$page_id = str_replace('page_id','',$fid);
				$get_page = explode($this->settings['separator'],$page_id);
				$sql = "SELECT * FROM  ". DB_PREFIX ."page_manage WHERE id = ".$get_page[0]." AND fid=0 ";
				$r = $this->db->query_first($sql);
				if($r['app'] || $r['host'])
				{
				
					$page_data = common::get_page_data($get_page[0],0,100);
					foreach($page_data['page_data'] as $k=>$v)
					{
						$m_id = 'page_data_id'.$page_data['page_info']['id'].$this->settings['separator'].$v['id'].$this->settings['separator'].$v['name'];
						$m = array('id'=>$m_id,"name"=>$v['name'],"fid"=>'page_data_id'.$page_data['page_info']['id'],"depth"=>1);
						$m['is_last'] = $v['is_last'];
						$this->addItem($m);
					}
				}
				else
				{
					$fid = $get_page[0];
					$condition = $this->get_condition($fid);
					$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
					$count = $this->input['count']?intval(urldecode($this->input['count'])):20;
					$limit = " limit {$offset}, {$count}";
					$ret = $this->obj->show($limit,$condition);
					foreach($ret as $v)
					{
						$this->addItem($v);
					}
				}
			}
			else if(strstr($fid,"page_data_id")!==false)
			{
				//点击的页面数据
				$page_data_id = str_replace('page_data_id','',$fid);
				$get_page = explode($this->settings['separator'],$page_data_id);
				$page_data = common::get_page_data($get_page[0],'','',$get_page[1]);
				foreach($page_data['page_data'] as $k=>$v)
				{
					$m_id = 'page_data_id'.$page_data['page_info']['id'].$this->settings['separator'].$v['id'].$this->settings['separator'].$v['name'];
					$m = array('id'=>$m_id,"name"=>$v['name'],"fid"=>'page_data_id'.$page_data['page_info']['id'],"depth"=>1);
					$m['is_last'] = $v['is_last'];
					$this->addItem($m);
				}
			}
			else
			{
				$condition = $this->get_condition($fid);
				$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
				$count = $this->input['count']?intval(urldecode($this->input['count'])):20;
				$limit = " limit {$offset}, {$count}";
				$ret = $this->obj->show($limit,$condition);
				foreach($ret as $v)
				{
					$this->addItem($v);
				}
			}
			
		}
		$this->output();
	}
	
	
	
	public function detail()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('page_manage',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$fid = $this->input['id'];
		if(strstr($fid,"page_id")!==false)
		{
			$site_id = str_replace('page_id','',$fid);
			$get_page = explode($this->settings['separator'],$site_id);
			$fid = $get_page[0];
		}
		elseif(strstr($fid,"page_data_id")!==false)
		{
			$this->errorOutput("不能编辑");
		}
		else
		{
			$fid = $fid ;
		}
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'page_manage WHERE id = '.$fid;
		$r = $this->db->query_first($sql);
		$r['argument'] = $r['argument']?unserialize($r['argument']):array();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count 	= $this->input['count']?intval(urldecode($this->input['count'])):50;
		
		$re = common::get_page_data($fid,$offset,$count,'0');
		$r['data'] = $re;
		$this->addItem($r);
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'page_manage WHERE 1 '.$this->get_condition();;
		$page_manage_total = $this->db->query_first($sql);
		echo json_encode($page_manage_total);	
	}
	

	/**
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function get_condition($fid= '')
	{	
		$condition = '';
		//查询应用分组
		//获取站点id或者父分类id
	 	//$sql = "SELECT site_id FROM ".DB_PREFIX."site_tem_sort WHERE id =".intval($this->input['_id']);
		//$r = $this->db->query_first($sql);
		$site_id = isset($this->input['site_id']) ? $this->input['site_id'] :1;
		if($site_id)
		{
			$condition .=" AND site_id=".$site_id;
		}
		if($fid)
		{
			$condition .=" AND fid =". $fid;
		}
		else
		{
			$condition .=" AND fid = 0";
		}
		if($this->input['k'])
		{
			$condition = " AND name like '%".urldecode($this->input['k'])."%' ";
		}
		return $condition;
	}
	
	public function get_page_data()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count 	= $this->input['count']?intval(urldecode($this->input['count'])):500;
		$page_id = intval($this->input['page_id']);
		$fid 	= intval($this->input['fid']);
		
		include_once(CUR_CONF_PATH.'lib/common.php');
		$data = common::get_page_data($page_id,$offset,$count,$fid);
		$this->addItem($data);
		$this->output();
	}
	
	public function get_page_manage()
	{
		$site_id = intval($this->input['site_id']);
		include_once(CUR_CONF_PATH.'lib/common.php');
		$page_type = common::get_page_manage($site_id);
		$this->addItem($page_type);
		$this->output();
	}
	
	//获取应用
	public function get_app()
	{
		$apps = $this->auth->get_app();
		if(is_array($apps))
		{
			foreach($apps as $k=>$v)
			{
				$ret[$v['bundle']] = $v['name'];
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	function index()
	{	
	}
}
	
$out = new pageManageApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
