<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: group.php 8313 2012-07-24 06:47:25Z daixin $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','cp_group_m');//模块标识
require(ROOT_DIR . 'global.php');

/**
 * 
 * 地盘数据获取API
 * 
 * 提供的方法：
 * 1) 获取所有地盘数据
 * 2) 获取单条地盘数据
 * 3) 获取指定地盘的总数
 * 
 * @author chengqing
 *
 */
class groupShowApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 获取所有地盘数据
	 */
	public function show()
	{
		//分页参数设置
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		

		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;

		$sql = "SELECT g.group_id AS logo, g.* , t.type_name 
				FROM " . DB_PREFIX."group g 
				LEFT JOIN " .DB_PREFIX ."group_type t 
				ON g.group_type = t.typeid WHERE 1 "; 
		
		//获取查询条件
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $data_limit;		
		$q = $this->db->query($sql);
		
		$this->setXmlNode('group_info' , 'group');
		while(false != ($row = $this->db->fetch_array($q)))
		{
			//$row['logo'] = GROUP_URL . 'logo_' . $row['group_id'] . '.jpg';
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['audit'] = $row['state'];
			$row['logo'] = $this->get_group_logo($row['group_id'],70);
			if (!$row['user_name'])
			{
				$row['user_name'] = '';
			}
			$row['state_tags'] = $this->settings['state'][$row['state']];
			if($this->settings['rewrite'])
			{
				$row['link'] = SNS_TOPIC."group-".$row['group_id'].".html";	
			}
			else 
			{
				$row['link'] = SNS_TOPIC."?m=thread&group_id=".$row['group_id']."&";	
			}
			$row['id'] = $row['group_id'];
			$this->addItem($row);
		}
		print_r($row);//exit;
		$this->output();
	}
	
	/**
	 * @param $group_id
	 * return $info
	 * 
	 * 
	 * 根据地盘ID查询地盘详情
	 */
	public function more()
	{
		$group_id = intval($this->input['group_id']);
		if(!$group_id)
		{
			$this->errorOutput('未传入地盘ID');
		}
		$q = "select g.*,t.type_name  from  " . DB_PREFIX . "group g LEFT JOIN " . DB_PREFIX . "group_type t ON g.group_type = t.typeid WHERE group_id = " . $group_id ;
		$group = $this->db->query_first($q);
		$group['logo'] = $this->get_group_logo($group['group_id'],50);
		$group['create_time'] = date("Y-m-d H:i:s",$group['create_time']);
		if($this->settings['rewrite'])
		{
			$group['link'] = SNS_TOPIC . 'group-' . $group['group_id'] . '.html';
		}
		else 
		{
			$group['link'] = SNS_TOPIC . '?m=thread&amp;group_id=' . $group['group_id'];
		}
		/*echo "<pre>";
		print_r($group);
		exit;*/
		$this->setXmlNode('group_info' , 'group');
		$this->addItem($group);
		$this->output();
	}
	
	function get_group_logo($group_id, $size = '')
	{
		$path = 'group/oth/70/';
		if ($size) 
		{
			$size = ' width="' . $size . '"';
		}
		$src  = $path . 'logo_' . $group_id . '.jpg';
		return $src = $this->settings['livime_upload_url'] . $src; 
	}
	
	/**
	 * 获取地盘总数
	 * 默认为全部地盘的总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "group g WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);

		echo json_encode($r);
	}
	
	/**
	 * 获取单条地盘信息
	 */
	public function detail()
	{
		if (isset($this->input['id']))
		{
			$this->input['group_id'] = urldecode($this->input['id']);
		}

		$this->input['group_id'] = urldecode($this->input['group_id']);

		if(!$this->input['group_id'])
		{
			$condition = ' ORDER BY g.group_id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE g.group_id in(' . $this->input['group_id'] .')';
		}	
		$sql = "SELECT g.* , t.type_name 
				FROM " . DB_PREFIX."group g 
				LEFT JOIN " .DB_PREFIX ."group_type t 
				ON g.group_type = t.typeid " . $condition; 		
		$r = $this->db->query_first($sql);
		$this->setXmlNode('group_info' , 'group');
		
		if(is_array($r) && $r)
		{
			$r['create_time'] = date('Y-m-d H:i:s' , $r['create_time']);
			$r['update_time'] = date('Y-m-d H:i:s' , $r['update_time']);
			$r['last_update'] = date('Y-m-d H:i:s' , $r['last_update']);
			if($this->settings['rewrite'])
			{
				$r['link'] = SNS_TOPIC."group-".$r['group_id'].".html";	
			}
			else 
			{
				$r['link'] = SNS_TOPIC."?m=thread&group_id=".$r['group_id']."&";
			}

			$r['status'] = $r['state'] ? 2 : 0;
			$r['pubstatus'] = $r['status'] ? 1 : 0; 
			$r['id'] = $r['group_id'];
			
			$this->addItem($r);
			$this->output();
		}
		else
		{
			$this->errorOutput('地盘不存在');	
		}			 		
	}
	
	/**
	 * 
	 * 获取父级地盘
	 */
	public function father_group()
	{
		$q = $this->db->query('select * from ' . DB_PREFIX . 'group where fatherid = 0 and state != 2');
		while(false !=($r = $this->db->fetch_array($q)))
		{
			$this->addItem($r);
		}
		$this->output();
	}
	
	/**
	 * 
	 * 获取所有地盘的类型
	 */
	public function group_type()
	{
		$q = $this->db->query('select * from ' . DB_PREFIX . 'group_type where 1');
		while(false !=($r = $this->db->fetch_array($q)))
		{
			$this->addItem($r);
		}
		$this->output();
	}
	
	/**
	 * 获取查询条件
	 */
	public function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['k'])
		{
			$condition .= " AND g.name LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
			
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND g.create_time >= " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND g.create_time <= " . strtotime($this->input['end_time']);	
		}

        //查询发布的时间
        if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  g.create_time > '".$yesterday."' AND g.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  g.create_time > '".$today."' AND g.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  g.create_time > '".$last_threeday."' AND g.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  g.create_time > '".$last_sevenday."' AND g.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		//查询地盘的状态
		if (isset($this->input['state']))
		{
			$state = intval($this->input['state']);
			switch ($state)
			{
					case 1://所有状态
						$condition .= " ";
						break;
					case 2: //待审核
						$condition .= " AND g.state = 0";
						break;
					case 3://已审核
						$condition .= " AND g.state = 1";
						break;
					default:
						break;
			}
		}
		//查询地盘的类型
		if($this->input['group_type'])
		{
			if($this->input['group_type']==-1)
			{
				$condition .= " ";
			}
			else
			{
				$condition .="AND g.group_type=" . intval($this->input['group_type']);
			}
		}
		
		//查询的子地盘
		if($this->input['fatherid'])
		{
			$condition .= " AND g.fatherid = " . intval($this->input['fatherid']);		
		}
		
		$group_type_hgupdn=array(
				1 => 'update_time',
				2 =>'post_count',
				3 =>'total_visit',
				4 =>'group_member_count',
		);
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (!in_array($this->input['hgorder'], $group_type_hgupdn))
		{
			$this->input['hgorder'] = 'create_time';
		}
		if($this->input['_type'])
		{
			$this->input['hgorder']=$group_type_hgupdn[$this->input['_type']];
		}
		
		$orderby = ' ORDER BY g.' . $this->input['hgorder']  . ' ' . $descasc ;

		
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $orderby;
		
		return $condition;	
	}
}

/**
 *  程序入口
 */
$out = new groupShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>



