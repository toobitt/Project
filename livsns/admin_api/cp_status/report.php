<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: report.php 7884 2012-07-13 07:13:19Z wangleyuan $
***************************************************************************/
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','mblog_report_m');
require(ROOT_DIR . 'global.php');
class reportShowApi extends BaseFrm
{
	private $mUser;
	function __construct() {
		parent::__construct();
		include_once(ROOT_DIR.'lib/user/user.class.php');
		$this->mUser = new user();
	}
	function __destruct() {
		parent::__destruct();
		$this->db->close();
	}
	/*
	 * 查看具体某一条举报信息
	 */
	function detail()
	{
		$this->input['id'] = urldecode($this->input['id']);
		if(!$this->input['id'])
		{
			return;
		}
		if($this->input['id'] == 'lastest')
		{
			$condition = ' ORDER BY  id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id in('.$this->input['id'].')';
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "report".$condition;		
		$r = $this->db->query_first($sql);
		$this->setXmlNode('reports' , 'report');
		if(is_array($r) && $r)
		{
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$this->addItem($r);
			$this->output();
		}
	}		
	/**
	*	获取举报的信息
	*/
	function show()
	{
		global $gReportType;
		$this->input['count'] = (isset($this->input['count']) && (int)$this->input['count']>0)
		?(int)$this->input['count'] 
		:20;
		$this->input['offset'] = (isset($this->input['offset']) && (int)$this->input['offset']>0) 
		?(int)$this->input['offset'] 
		:0;
		$condition=$this->get_condition();
		$sql = 'select * from '.DB_PREFIX.'report';
		$sql .= ' where 1 '.$condition;
		$sql .= ' limit '.$this->input['offset'].','.$this->input['count'];
		$report_all_data = $this->db->query($sql);
		$user_ids = array();
		$u_ids = array();
		$report_info = array();
		while($result = $this->db->fetch_array($report_all_data))
		{
			$result['create_time'] = date('Y-m-d H:i:s',$result['create_time']);
			$result['audit'] = $result['state'];
			$result['state_tags'] = $result['state']?'已审核':'待审核';
			switch($k = $result['type'])
			{
				case 0:$result['type']=$gReportType[$k];break;
				case 1:$result['type']=$gReportType[$k];break;
				case 2:$result['type']=$gReportType[$k];break;
				case 3:$result['type']=$gReportType[$k];break;
				case 4:$result['type']=$gReportType[$k];break;
				case 5:$result['type']=$gReportType[$k];break;
				case 6:$result['type']=$gReportType[$k];break;
				case 7:$result['type']=$gReportType[$k];break;
				case 8:$result['type']=$gReportType[$k];break;
				case 9:$result['type']=$gReportType[$k];break;
				case 10:$result['type']=$gReportType[$k];break;
				case 11:$result['type']=$gReportType[$k];break;
				case 12:$result['type']=$gReportType[$k];break;
				default:$result['type'] = '未知';break;
			}
			//$result['content'] = '<a href="'.$result[url].'">'.$result['content'].'</a>';
			//举报人
			$user_ids[] = $result['user_id'];
			//被举报人
			$u_ids[] = $result['uid'];
			//$this->addItem($result);
			$report_info[] = $result;
		}
		$user_info = $this->get_user_info($user_ids);//举报人
		$u_info = $this->get_user_info($u_ids);//被举报人
		if($report_info)
		{
			foreach($report_info as $v)
			{
				if($v)
				{
					$v['user'] = $user_info[$v['user_id']];
					$v['reported_user'] = $u_info[$v['uid']];
					//file_put_contents('1.php',$v['user_user']);				
					$this->addItem($v);
				}
			}
		}
		$this->output();
	}
	/**
	*	取出总的微博记录数
	*/
	function count()
	{
		$sql = 'select count(*) as total from '.DB_PREFIX.'report where 1 '.$this->get_condition();
		$report_total_item = $this->db->query_first($sql);
		echo json_encode($report_total_item);
	}
	/**
	 * 获取用户信息
	 * @pams $ids 用户的ID
	 */
	function get_user_info($ids)
	{
		if(!ids)
		{
			return;
		}
		$userinfo = $this->mUser->getUserById(implode(',',array_unique($ids)));
		if(!empty($userinfo))
		{
			foreach ($userinfo as $key=>$value)
			{
				$userinfo_tmp[$value['id']] = $value;
			}
			unset($userinfo);
			return $userinfo_tmp;
		}
		else
		{
			return false;
		}
	}
	/**
	 * 条件筛选
	 */
	private function get_condition()
	{
		$condition = '';
		if($this->input['start_time'])
		{
			$condition .= " AND create_time >= " . strtotime($this->input['start_time']);
		}
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND create_time <= " . strtotime($this->input['end_time']);	
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
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		//查询的关键字
		if($this->input['k'])
		{
			$condition .= " AND content LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}

		//查询地盘的状态
		if(isset($this->input['state']))
		{
			$state = intval($this->input['state']);
			switch ($state)
			{
					case 1://所有状态
						break;
					case 2: //待审核
						$condition .= " AND state = 0";
						break;
					case 3://已审核
						$condition .= " AND state = 1";
						break;
					default:
						break;
			}
		}
		
		
		if(!empty($this->input['_id']))
		{
			$condition .= ' and type = '.$this->input['_id'];
		}
        
		//排序方式及字段
		$report_hgorder=array(
			1 => 'create_time',
			2 =>'cid',
		);
		$descasc=strtoupper($this->input['hgupdn']);
		if($descasc !='ASC')
		{
			$descasc='DESC';
		}
		if(!in_array($this->input['hgorder'],$report_hgorder))
		{
			$this->input['hgorder']='create_time';
		}
		if(!empty($this->input['_type']))
		{
			$this->input['hgorder']=$report_hgorder[$this->input['_type']];
		}
		$orderby=' ORDER BY ' . $this->input['hgorder'] . ' ' . $descasc;
		$condition .=$orderby;
		return $condition;
	}
}
$reportShowApi = new reportShowApi();
if(!method_exists($reportShowApi, $_INPUT['a']))
{
	$_INPUT['a'] = 'show';
}
$reportShowApi->$_INPUT['a']();
?>