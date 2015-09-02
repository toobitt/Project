<?php
include_once('./global.php');
define('MOD_UNIQUEID','cp_report_m');//模块标识

class sortshowApi extends BaseFrm
{
	/**
	 * 
	 * Enter description here ...
	 */
	function __construct()
	{
		parent::__construct();
		require_once  'lib/helpLib.class.php';
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function unknow()
	{
		$this->errorOutput("你搜索得方法不存在");
	}
	
	public function getCondition()
	{
		$data = array();
		if(isset($this->input['sort_id']))
		{
			$data['sort_id'] = trim($this->input['sort_id']);
		}
		return $data;
	}
	
	/**
	 * 返回列表
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 6;
		
		$data = array();
		$data = $this->getCondition();
		$result = array();
		//初始化方法
		$this->libhelp = new helpLib();
		$result = $this->libhelp->get('help_sort','sort_id,sort_name,sort_desc,create_time,parent_id', $data, $offset, $count, array(), array(), array());
		if($result)
		{
			foreach($result as $k)
			{
				if(NEED_TIME_TO_UNIX)
				{
					if($k['create_time'] && is_numeric($k['create_time']))
					{
						$k['create_time'] = date("Y-m-d H:i:s", $k['create_time']);
					}
				}
				$this->addItem_withkey($k['sort_id'], $k);
			}
		}
		$this->output();
	}
	
	/**
	 * 返回对应总数
	 */
	public function count()
	{
		$data = array();
		$data = $this->getCondition();
		
		$result = 0;
		$this->libhelp = new helpLib();
		$result = $this->libhelp->get('help','count(sort_id) as total', $data, 0, 1, array(), array(), array());
		$this->addItem_withkey('total', $result);
		$this->output();
	}
	
	/**
	 * 显示具体某条举报
	 */
	public function detail()
	{
		$sort_id = intval($this->input['sort_id']);
		if($id)
		{
			$result = array();
			$this->libhelp = new helpLib();
			$result = $this->libhelp->get('help','*', array('id'=>$id), 0, 1, array(), array(), array());
			if($result && is_array($result))
			{
				if(NEED_TIME_TO_UNIX)
				{
					$result['create_time'] = date('Y-m-d H:i:d' , $result['create_time']);
				}
			}
			$this->addItem($result);
			$this->output();
		}
		else 
		{
			$this->errorOutput('未传入查询ID');
		}
	}
	/**
	 * 显示全部菜单
	 */
	public function detailMenu()
	{
		$result = array();
		$this->libhelp = new helpLib();
		$result = $this->libhelp->get('help_sort','*,concat( path, "-", sort_id ) path', array(), 0, -1, array('path'=>''), array(), array());
		
		if($result)
		{
			foreach($result as $k=>&$v)
			{
				$v['create_time'] = date('Y-m-d H:i:d' , $v['create_time']);
				$this->addItem($v);
			}
		}
		
		$this->output();
	}
	
	/**
	 * 动态加在菜单
	 * 初始化加载菜单的前两层，
	 * 输入具体某条菜单显示下子菜单，当子菜单为枝叶节点时自动加载对应下面的目录
	 */
	public function ajaxMenu()
	{
		$sort_id = $this->input['sort_id'] ? trim($this->input['sort_id']) : 0;
		$result = array();
		$this->libhelp = new helpLib();
		if($sort_id)
		{
			$result = $this->libhelp->get('help_sort','sort_id,sort_name,sort_desc,create_time,parent_id',array('parent_id'=>$sort_id),0,-1,array(),array(),array());			
		}
		else 
		{
			$sql = "select sort_id,sort_name,sort_desc,create_time,parent_id from ".DB_PREFIX."help_sort where sort_id in(select sort_id from ".DB_PREFIX."help_sort where parent_id =0) or parent_id in(select sort_id from ".DB_PREFIX."help_sort where parent_id =0)";
			$result = $this->db->fetch_all($sql);
		}
		if($result)
		{
			foreach($result as $k=>&$v)
			{
				$v['create_time'] = date('Y-m-d H:i:d' , $v['create_time']);
				$this->addItem($v);
			}
		}
		else 
		{
			if($sort_id)
			{
				//加载数据
				$ret = array();
				$ret = $this->libhelp->get('help_sort','sort_id,is_end,sort_name',array('sort_id'=>$sort_id),0,1,array(),array(),array());
				if($ret)	
				{
					if($ret['is_end'])
					{
						$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
						$count = isset($this->input['count']) ? intval($this->input['count']) : 6;
						$result = $this->libhelp->get(array('help'=>'t'),'t.id,t.subject,t.sort_id,t.create_time,t.content,t.related_num,t.key_num', array('sort_id'=>$sort_id), $offset, $count, array(), array(), array());
						if($result)
						{
							foreach($result as &$k)
							{
								if(NEED_TIME_TO_UNIX)
								{
									if($k['create_time'] && is_numeric($k['create_time']))
									{
										$k['create_time'] = date("Y-m-d H:i:s", $k['create_time']);
									}
									if($k['update_time'] && is_numeric($k['update_time']))
									{
										$k['update_time'] = date("Y-m-d H:i:s", $k['update_time']);
									}
								}
								$k['sort_name'] = $ret['sort_name'];
								$this->addItem_withkey($k['id'], $k);
							}
						}
					}
				}
				else 
				{
					$this->errorOutput('传入不存在的菜单查询ID');
				}		
			}
			else 
			{
				$this->addItem();
			}
		}
		
		$this->output();
	}
	/**
	 * (non-PHPdoc)
	 * @see BaseFrm::__destruct()
	 */
	function __destruct()
	{
		parent::__destruct();
	}
}
/**
 *  程序入口
 */
$out = new sortshowApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'unknow';
}
$out->$action();
?>
