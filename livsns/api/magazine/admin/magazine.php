<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
require_once '../lib/magazine.class.php';
define('MOD_UNIQUEID','magazine');//模块标识
class Magezine extends adminReadBase
{
	function __construct()
	{
		$this->mPrmsMethods = array(
		'manage_maga'		=>'管理杂志',
		'manage_issue'		=>'管理期刊',
		'audit'				=>'审核',
		'_node'=>array(
			'name'=>'杂志名称',
			'filename'=>'magazine_node.php',
			'node_uniqueid'=>'magazine_node',
			),
		);
		parent::__construct();
		$this->maga = new MagazineClass();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
	}
	function show()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_maga'));
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY m.order_id  DESC';
		$condition = $this->get_condition();
		if($this->input['show_last_issue'])
		{
			$condition .= ' AND m.issue_id > 0 ';
		}
		$res = $this->maga->show($condition,$orderby,$offset,$count);
		if(!$this->input['show_last_issue'])
		{
			foreach ($res as $k=>$r)
			{
				$r['url'] = hg_material_link($r['host'], $r['dir'], $r['file_path'], $r['file_name'],$size);
				$this->addItem($r);
			}
		}
		else 
		{
			foreach ($res as $k => $v)
			{
				$v['url'] = hg_material_link($v['host'], $v['dir'], $v['file_path'], $v['file_name'],$size);
				$v['release_cycle'] = $this->settings['release_cycle'][$v['release_cycle']];
				$arr[] = $v;
			}
			$this->addItem($arr);
		}
		$this->output();
	}
	
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'magazine m WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	private function get_condition()
	{
		$condition = '';
		//权限判断
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他人数据
			/*if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND m.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND m.org_id IN (' . $this->user['slave_org'] .')';
			}*/
			
			//节点权限判断
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str && $authnode_str !=-1)
				{
					$condition .= ' AND m.id IN (' . $authnode_str . ')'; 
				}
			}
			else 
			{
				$this->errorOutput('没有权限管理杂志');
			}
		}
		
		if($this->input['k'])
		{
			$condition .= ' AND m.name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		if($this->input['user_name'])
		{
			$condition .= " AND m.user_name LIKE '%".$this->input['user_name']."%'";
		}
		//分类列表
		if ($this->input['maga_sort'] && intval($this->input['maga_sort'])!= -1)
		{
			$condition .= ' AND m.sort_id = '.$this->input['maga_sort'] ; 
		}
		//杂志id
		if ($this->input['id'])
		{
			$condition .= ' AND m.id = '.$this->input['id'] ; 
		}
		//节点id
		if($this->input['_id'])
		{
			$sql = "select * from " . DB_PREFIX	. "magazine_node where id = " . intval($this->input['_id']);
			$ret =  $this->db->query_first($sql);
			$condition .=" AND  m.sort_id in (" . $ret['childs'] . ")";
		}
		if (isset($this->input['maga_audit']) && $this->input['maga_audit'] != -1)
		{
			$condition .= ' AND i.state = '.$this->input['maga_audit'] ; 
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND m.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND m.create_time <= ".$end_time;
		}
		if($this->input['maga_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['maga_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  m.create_time > ".$yesterday." AND m.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  m.create_time > ".$today." AND m.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  m.create_time > ".$last_threeday." AND m. create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND m.create_time > ".$last_sevenday." AND m.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有id');
		}
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_maga'));
		
		$node = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
		
		if($node && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(implode(',', $node) != -1 && !in_array($id,$node))
			{
				$this->errorOutput('没有权限编辑此杂志');
			}
		}
				
		$res = $this->maga->detail($id);
		$this->addItem($res);
		$this->output();	
	}
	
	public function show_last_issue()
	{
		$this->input['show_last_issue'] = 1;
		$this->show();
	}
	function show_opration()
	{
		$this->show();
	}
	//查询杂志分类
	function append_sort()
	{
		$res = $this->maga->append_sort();
		$this->addItem($res);
		$this->output();
	}
}

$ouput= new Magezine();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();