<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
require_once '../lib/issue.class.php';
define('MOD_UNIQUEID','issue');//模块标识
class Isuue extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->issue = new IssueClass();
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
		
		$orderby = ' ORDER BY i.total_issue DESC';
		
		$condition = $this->get_condition();
		
		$res = $this->issue->show($this->get_condition(),$orderby,$offset,$count);
		
		if($this->input['maga_id'])
		{
			$sql = "SELECT * FROM ".DB_PREFIX."magazine WHERE id = ".intval($this->input['maga_id']);
			$maga_info = $this->db->query_first($sql);
			$maga_info['sort_name'] = urldecode($this->input['sort_name']);
			if($maga_info['contract_way'])
			{
				$contract_way = unserialize($maga_info['contract_way']);
				
				$contract_name = $contract_way['contract_name'];
				$contract_value = $contract_way['contract_value'];
				
				if($contract_name)
				{
					foreach ($contract_name as $k => $v)
					{
						$contract_arr[$k]['contract_name'] = $v;
						$contract_arr[$k]['contract_value'] = $contract_value[$k];
						
					}
					$maga_info['contract_way'] = $contract_arr;
				}
			}
			$updata_tag = $this->user['group_type'] > MAX_ADMIN_TYPE ? 0 : 1;
			$maga_info['update_tag'] = $updata_tag;
		}
		if(!$res)
		{
			if($maga_info)
			{
				$res[] = $maga_info;
			}
		}
		else 
		{
			if($maga_info)
			{
				array_unshift($res,$maga_info);
			}
		}
		foreach ($res as $k=>$v)
		{
			//$v['complete'] = '';
			/*if(@file_get_contents($v['url']))
			{
				$v['complete'] = 0.2;
			}
			if($v['total_article'] && $v['article_num'])
			{
				$v['complete'] += round(0.8*($v['article_num']/$v['total_article']),2);
			}
			if($v['complete'])
			{
				$v['complete'] = abs($v['complete']) * 100 .'%';
			}
			else 
			{
				$v['complete'] = '0%';
			}*/
			$this->addItem($v);
		}
		$this->output();
	}
	
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'issue i WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	private function get_condition()
	{
		$condition = '';
		
		//权限判断
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND i.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND i.org_id IN (' . $this->user['slave_org'] . ')';
			}
			
			$prms_maga_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($prms_maga_ids)
			{
				$condition .= ' AND magazine_id IN ('.implode(',', $prms_maga_ids).')';
			}
		}
		if($this->input['k'])
		{
			$condition .= ' AND i.issue LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
			
		if($this->input['user_name'])
		{
			$condition .= " AND i.user_name LIKE '%".$this->input['user_name']."%'";
		}
		
		//期刊id
		if ($this->input['id'])
		{
			$condition .= ' AND i.id = '.$this->input['id'] ; 
		}
		//杂志id
		if ($this->input['maga_id'] && intval($this->input['maga_id'])!= -1)
		{
			$prms_maga_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_maga_ids)
			{
				if(!in_array($this->input['maga_id'],$prms_maga_ids))
				{
					$this->errorOutput('没有权限查看此杂志下的期刊');
				}
			}
			$condition .= ' AND i.magazine_id = '.intval($this->input['maga_id']);
		}
		
		if (isset($this->input['issue_audit']) && $this->input['issue_audit'] != -1)
		{
			
			$condition .= ' AND i.state = '.$this->input['issue_audit'] ; 
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND i.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND i.create_time <= ".$end_time;
		}
		if($this->input['issue_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['issue_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  i.create_time > ".$yesterday." AND i.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  i.create_time > ".$today." AND i.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  i.create_time > ".$last_threeday." AND i. create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND i.create_time > ".$last_sevenday." AND i.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	function detail()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有id');
		}
		$res = $this->issue->detail($id);
		$this->addItem($res);
		$this->output();		
	}
	function show_opration()
	{
		$this->show();
	}
	//查询文章信息
	function form_article()
	{
		$id = intval($this->input['id']);
		if($id)
		{
			$data = $this->issue->form_article($id);
			$this->addItem($data);
			$this->output();
		}
	}
	
	//查询期刊下的文章
	function get_article()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有期刊id');
		}
		$info = $this->issue->get_article($id);
		$this->addItem($info);
		$this->output();
	}
	
	//查询所有杂志
	function append_magazine()
	{
		$group = "SELECT id,name FROM " . DB_PREFIX . "magazine WHERE 1 ";
		//没有管理他人数据时只查询自己创建的杂志
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE && !$this->user['prms']['default_setting']['manage_other_data'])
		{
			$group .= ' AND user_id = '.$this->user['user_id'];
		}*/
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//过滤无权限的杂志
			$node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($node)
			{
				$group .= ' AND id IN (' . implode(',', $node) . ')';
			}
		}
		$g = $this->db->query($group);
		$return = array();
		while($j = $this->db->fetch_array($g))
		{
			$this->addItem($j);
		}
		$this->output();
	}
	//查找杂志当前期数
	function get_cur_nper()
	{
		$maga_id = intval($this->input['maga_id']);
		$sql = "SELECT current_nper,volume FROM " . DB_PREFIX . "magazine WHERE id = ".$maga_id;
		$q = $this->db->query_first($sql);
		$info = array(
			'current_nper' => $q['current_nper'],
			'volume' => $q['volume'],
		);
		$this->addItem($info);
		$this->output();
	}
}

$ouput= new Isuue();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();