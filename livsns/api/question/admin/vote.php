<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: vote.php 17934 2013-02-26 01:52:15Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','question');//模块标识
class voteApi extends adminReadBase
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include vote.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/vote.class.php');
		$this->obj = new vote();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 问卷列表显示
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $offset 分页参数
	 * @param $count 分页显示记录数
	 * @param $vote array 问卷所属数据 
	 * @return $v array 问卷内容信息
	 */
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		
		$vote = $this->obj->show($condition,$data_limit);

		$this->setXmlNode('vote','info');
		if (!empty($vote))
		{
			foreach ($vote AS $v)
			{
				$v['create_time'] = date('Y-m-d H:i:s' , $v['create_time']);
				$v['start_time'] = date('Y-m-d H:i:s' , $v['start_time']);
				$v['end_time'] = date('Y-m-d H:i:s' , $v['end_time']);
				$v['describes'] = substr($v['describes'], 0, 200);
				$v['question_total'] = count($v['questions']);
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	//查看已审核的且没有过期的
	public function show_quick_select()
	{
		$condition = '';
		if(!empty($this->input['user']))
		{
			$user = urldecode($this->input['user']);
			$condition .=" and v.admin_name='" . $user . "'";
		}
		if(!empty($this->input['key']))
		{
			$key = urldecode($this->input['user']);
			$condition .=" and v.title like '%".$key."%'";
		}
		$condition .= ' and v.state=1 and v.end_time > '.TIMENOW;
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 10;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		if(!empty($key))
		{
			$data_limit='';
		}
		
		$vote = $this->obj->show($condition,$data_limit);

		$this->setXmlNode('vote','info');
		if (!empty($vote))
		{
			foreach ($vote AS $v)
			{
				$v['create_time'] = date('Y-m-d H:i:s' , $v['create_time']);
				$v['start_time'] = date('Y-m-d H:i:s' , $v['start_time']);
				$v['end_time'] = date('Y-m-d H:i:s' , $v['end_time']);
				$v['describes'] = substr($v['describes'], 0, 200);
				$v['question_total'] = count($v['questions']);
				$v['time'] = hg_tran_time($v['create_time']);
				$v['filepath'] = $v['logo_info']['filepath'] . $v['logo_info']['filename'];
				$v['app_bundle'] = APP_UNIQUEID;
				$v['module_bundle'] = MOD_UNIQUEID;
				$this->addItem($v);
			}
		}
		$this->output();
	}	

	/**
	 * 单条信息
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 问卷ID
	 * @return $row array 单条问卷信息
	 */
	public function detail()
	{
		$id = urldecode($this->input['id']);
		if($id)
		{
			$row = $this->obj->detail($id);
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('问卷不存在');	
		} 	
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $ret string 总数，json串
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vote AS v WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$ret = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($ret);
	}
	
	
	//返回已审核的总数
	public function quick_select_count()
	{
		$sql = "select count(*) as total from " . DB_PREFIX ."vote where 1 and state=1 and end_time > ".TIMENOW;
		if(!empty($this->input['user']))
		{
			$condition='';
			$user = urldecode($this->input['user']);
			$condition = ' and admin_name='.$user;
			$sql = $sql . $condition;
		}
		$total = $this->db->query_first($sql);
		$this->addItem($total);
		$this->output();
	}
	
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND v.title like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		if(isset($this->input['id']) && $this->input['id'])
		{
			$condition .= ' AND v.id IN('.trim(urldecode($this->input['id'])).')';
		}
		
		if(isset($this->input['_id']) && $this->input['_id'])
		{
			$condition .= ' AND v.node_id IN('.trim(urldecode($this->input['_id'])).')';
		}
/*	
		if(isset($this->input['node_type']) && $this->input['node_type'])
		{
			$condition .= ' AND v.group_id IN('.trim(urldecode($this->input['node_type'])).')';
		}
*/	
		if( isset($this->input['start_time']) && $this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND v.create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && $this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND v.create_time <= '".$end_time."'";
		}
		
		if(isset($this->input['state']) && $this->input['state'] && urldecode($this->input['state'])!= -1)
		{
			$condition .= " AND v.state = '".urldecode($this->input['state'])."'";
		}
		else if(urldecode($this->input['state']) == '0')//此处为了区分状态0的情况与传过来的值为空的情况，为空的时候查出所有
		{
			$condition .= " AND v.state = 0 ";
		}
		
		if(isset($this->input['date_search']) && $this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  v.create_time > '".$yesterday."' AND v.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  v.create_time > '".$today."' AND v.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  v.create_time > '".$last_threeday."' AND v.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  v.create_time > '".$last_sevenday."' AND v.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}
	
	public function index()
	{
		
	}
}

$out = new voteApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>