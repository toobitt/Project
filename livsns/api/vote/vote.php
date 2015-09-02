<?php
/***************************************************************************
* $Id: vote.php 43354 2015-01-05 08:02:39Z tandx $
* 投票新外部接口
***************************************************************************/
define('MOD_UNIQUEID', 'vote');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class voteApi extends outerReadBase
{
	private $mVote;
	function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/vote.class.php';
		$this->mVote = new vote();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 投票列表 (已审核, 不包含投票选项)
	 * $offset 分页参数
	 * $count 分页参数
	 */
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$device_token = trim($this->input['device_token']);
	
		$vote = $this->mVote->get_vote_info($condition,$device_token, $offset, $count);
		if (!empty($vote))
		{
			foreach ($vote AS $v)
			{
				$this->addItem($v);
			}
		}
		
		$this->output();
	}
	
	/**
	 * 取已审核投票具体内容 (包含投票选项、投票数目、参与人数)
	 * $id 投票id
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id && !$this->input['is_new']) //is_new=1，不传id的时候，默认取最新的一条
		{
			$this->errorOutput('投票不存在或已被删除');
		}
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 100;
		$device_token = trim($this->input['device_token']);
		
		$condition = ' AND status = 1 ';
		$lastvote = $id ? ' status = 1 AND id = '.$id : ' status = 1 ORDER BY id DESC LIMIT 1';
		//投票
		$list = $this->mVote->get_vote_list($lastvote , 'id,is_other,total,ini_total,option_num');
		$list = $list[0];
		$id = $id ? $id : $list['id'];
		if ($id)
		{
			$vote = $option = $other_option_title = array();
			//取选项总数，总选项投票数，总初始化数
			$vote_total = $list['total'];
			$ini_total = $list['ini_total'];
			$total = $list['is_other'] ? $list['option_num'] + 1 : $list['option_num'];			//取选项数
			$is_next_page = $offset + $count >= $total ? 0 : 1; //是否最后一页
			if($offset >= $total)
			{
				$this->errorOutput(NO_CONTENT);
			}
			//获取选项
			$limit = ' LIMIT '.$offset .','.$count ;
			$option = $this->mVote->get_vote_option($id, $option_condition,$limit);
			$option = $option[$id];
			if($list['is_other'] && (!$offset || !$is_next_page)) //勾选其他，在最后一页加载其他
			{
				//勾选其他选项的人数
				$sql = "SELECT count(*) as other_option_num FROM " . DB_PREFIX . "question_record WHERE vote_question_id = " . $id . " AND question_option_id = ".OTHER_OPTION_ID;
				$oc = $this->db->query_first($sql);
				
				$sql = "SELECT * FROM " . DB_PREFIX . "question_other_option WHERE vote_question_id IN (" . $id . ") ORDER BY id ASC";
		        $q = $this->db->query($sql);
		        while ($r = $this->db->fetch_array($q))
		        {
		        	$other_option_title[] = $r['other_option'];
		        }
		        $other[] = array( //投票给其他选项的人数统计
		    	    'id' => OTHER_OPTION_ID,
		    	    'title' => OTHER_OPTION_TITLE, 
		    	    'single_total'=> $oc['other_option_num'] ? $oc['other_option_num'] : count($other_option_title),
		    	    'ini_single'  => $oc['other_option_num'] ? $oc['other_option_num'] : count($other_option_title),
		        );
		        
		    }
			if($offset < 1) //加载第一页的时候，需要加载所有投票的信息
			{
				$vote_data = $this->mVote->get_vote_by_id($id, $condition);		
		 		$vote = $vote_data[0];
		 		$vote['vote_total'] = $ret['vote_total'];
				$vote['ini_num'] = $ret['ini_num'];
		 		$vote['other_vote_total'] = $oc['other_option_num']; //投票给其他选项的人数
		        $vote['other_option_num'] = count($other_option_title); //写了其他选项答案的人数
				$vote['other_option'] = $vote['is_other'] ? $other_option_title : array();//其他答案
				
				//参与人数
				$sql = "SELECT counts, app_name, app_id FROM " . DB_PREFIX . "question_count WHERE vote_question_id = " . $id;
				$q = $this->db->query($sql);
				$vote['preson_count'] = 0;
				while ($row = $this->db->fetch_array($q))
				{
					//参与人数数目
					$vote['preson_count'] += $row['counts'];
					$question_count[] = $row;
				}
				
				$vote['app_id'] = $question_count;
				$vote['person_total'] = $vote['preson_count'] + $vote['ini_person'];
				$vote['person_total'] = $vote['person_total'] > 0 ? $vote['person_total'] : 0;
				
				if($device_token && $vote['is_device'])
				{
					$sql = "SELECT qp.vote_question_id,qr.option_ids FROM ".DB_PREFIX."question_person qp LEFT JOIN ".DB_PREFIX."question_person_info qr ON qp.pid = qr.id WHERE qp.vote_question_id = " . $id ." AND qp.device_token = '".$device_token."'";
					$q = $this->db->query($sql);
					while ($r = $this->db->fetch_array($q))
					{
						$options_id[] = $r['option_ids'];
					}
					if($options_id && count($options_id)>0)
					{
						$vote['votefor'] = $options_id;
						$vote['deviced'] = 1;
					}
				}	
			}
			!$is_next_page && $other ?  $option = array_merge($option,$other) : false;
			$vote['options'] = $option;
			$vote['is_next_page'] = $is_next_page ; 
			$vote['question_total'] = $vote_total;
			$vote['question_total_ini'] = $vote_total + $ini_total ;
			$vote['question_total_ini'] = $vote['question_total_ini'] > 0 ? $vote['question_total_ini'] : 0;
			if(!$this->input['is_showdata'])
			{
				unset($vote['vote_total']);
				unset($vote['ini_num']);
				unset($vote['question_total']);
				unset($vote['preson_count']);
				unset($vote['total']);
				unset($vote['ini_total']);
				unset($vote['ini_person']);
				unset($vote['app_id']);
			}
		}
		$this->addItem($vote);
		$this->output();
	}
	
	/**
	 * 获取投票总数
	 */
	public function getTotal()
	{
		$ids = $this->input['id'];
    	$condition = "AND id IN(".$ids.")";
    	$return = $this->mVote->get_vote_info($condition);
    	$this->addItem($return);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$return = $this->mVote->count($condition);
		$this->addItem($return);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = ' AND status = 1 ';
		//$condition = ' AND is_open = 1 ';
		if(trim($this->input['id']))
		{			
			$condition .= ' AND id IN ('.trim($this->input['id']).')';
		}
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title LIKE \'%'.urldecode($this->input['k']).'%\'';
		}
		if (isset($this->input['uid']) && $this->input['uid'])
		{
			$condition .= ' AND user_id IN (' . trim($this->input['uid']) . ')';
		}
		if (isset($this->input['node_id']) && $this->input['node_id'])
		{
			$condition .= " AND node_id IN ( " . trim($this->input['node_id']) .")";
		}
		//查询权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .=" AND weight >= " . $this->input['start_weight'];
		}
		if($this->input['end_weight'] && $this->input['end_weight'] != -1)
		{
			$condition .=" AND weight <= " . $this->input['end_weight'];
		}
				
		return $condition;
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
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