<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* @public function show|show2|single_option_update|detail|count
* @private function get_condition
* 
* $Id: single_question.php 6446 2012-04-18 07:21:05Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'vote');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class singleQuestionApi extends outerReadBase
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include vote_question.class.php
	 */
	private $mVoteQuestion;
	private $mVerifyCode;
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/vote_question.class.php');
		$this->mVoteQuestion = new voteQuestion();
		
		require_once ROOT_PATH . 'lib/class/verifycode.class.php';
		$this->mVerifyCode = new verifyCode();
		
		$this->user['user_id'] = intval($this->user['user_id']);
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 所有投票信息内容 (单独的投票，已审核，在有效期内)
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $v array 所有投票信息
	 */
	public function show()
	{	
		$condition = $this->get_condition();
		$condition .= ' AND status=1 ';
	//	$condition .= ' AND start_time < ' . TIMENOW . ' AND end_time > ' . TIMENOW;
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		
		$single_question = $this->mVoteQuestion->show2($condition,$device_token,$data_limit,$type);
	
		if ($single_question)
		{
			foreach ($single_question AS $v)
			{
				$v['display'] = 1;
				if ($v['end_time'] && $v['end_time'] < TIMENOW)
				{
					$v['display'] = 0;
				}
				$question_option = array();
				if (!empty($v['question_option']))
				{
					foreach ($v['question_option'] AS $vv)
					{
						$question_option[] = $vv;
					}
				}
				$v['question_option'] = $question_option;
				$this->addItem($v);
			}
		}
		$this->output();
	}
		
	/**
	 * 单条投票信息内容
	 * @name show2
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 投票ID
	 * @return $v array 单条投票信息
	 */
	public function show2()
	{	
		if (!$this->input['id'])
		{
			$this->errorOutput('该投票不存在或已过期');
		}
	
		$condition .= ' AND id= ' . $this->input['id'];
		$condition .= ' AND status=1 ';
	//	$condition .= ' AND start_time < ' . TIMENOW . ' AND end_time > ' . TIMENOW;
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		
		$single_question = $this->mVoteQuestion->show2($condition,$data_limit,1);

		if ($single_question)
		{
			foreach ($single_question AS $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	/**
	 * 查看单个投票结果
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 投票ID
	 * @return $row array 单条投票信息
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('投票不存在或已被删除');
		}
		$info = $this->mVoteQuestion->detail(' AND status=1');

		$this->addItem($info);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vote_question WHERE status=1 " . $condition;
		$ret = $this->db->query_first($sql);
		echo json_encode($ret);
	}
	
	/**
	 * 投票接口
	 * @param $question_id int 投票ID
	 * @param $single_total array 选项id作为索引下标的数组
	 * @param $verify_code string 验证码
	 * @param $other_title string 用户提交过来的其他选项
	 * 
	 */
	public function single_option_update()
	{
		$ip = hg_getip();
		$app_id = $this->user['appid'] ? $this->user['appid'] : 0;
		$app_name = $app_id ? $this->user['display_name'] : '网页';
		
		$id = intval($this->input['question_id']);
		if (!$id)
		{
			$this->errorOutput('该投票不存在');
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE id = " . $id;
		$vote_question = $this->db->query_first($sql);

		if (empty($vote_question))
		{
			$this->errorOutput('该投票不存在或已被删除');
		}
		
		//审核状态
		if (!$vote_question['status'])
		{
			$this->errorOutput('该投票已关闭');
		}
		
		//有效期验证
		if ($vote_question['end_time'] && $vote_question['end_time'] < TIMENOW)
		{
			$this->errorOutput('该投票已过期');
		}
		
		//投票类型 单选
		$single_total =  $this->input['single_total'];
		
		if (empty($single_total))
		{
			$this->errorOutput('请选择投票选项');
		}
		
		if ($vote_question['option_type'] == 1 && count($single_total) != 1)
		{
			$this->errorOutput('请选择一个投票选项');
		}
		
		//投票类型 多选
		if ($vote_question['option_type'] == 2)
		{
			if (count($single_total) > $vote_question['max_option'] && $vote_question['max_option'])
			{
				$this->errorOutput('投票选项已超过' . $vote_question['max_option'] . '个');
			}
			
			if (count($single_total) < $vote_question['min_option'])
			{
				$this->errorOutput('投票选项不能少于' . $vote_question['min_option'] . '个');
			}
		}
		
		//验证码
		if ($this->settings['App_verifycode'] && $vote_question['is_verify_code'])
		{
			if (isset($this->input['verify_code']))
			{
				if (!$verify_code)
				{
					$this->errorOutput('验证码不能为空');
				}
				
				//调用验证码接口				
				$ret_verify_code = $this->mVerifyCode->verify_verify_code(trim($this->input['verify_code']));
				
				if ($ret_verify_code != 'SUCCESS')
				{
					$this->errorOutput('验证码不存在或已过期');
				}
			}
		}
		
		//是否需要用户登陆
		if ($vote_question['is_user_login'] && $this->user['user_id'] <= 0)
		{
			$this->errorOutput('请登陆后在参与投票');
		}
		
		//用户投票限制
		if ($vote_question['is_userid'])
		{
			$user_toff = $vote_question['userid_limit_time'] * 3600;
			$user_time = TIMENOW - $user_toff;
			
			$sql = "SELECT vote_question_id FROM " . DB_PREFIX . "question_person WHERE vote_question_id = " . $id . " AND create_time > " . $user_time . " AND user_id = " . $this->user['user_id'];
			$user_question_preson = $this->db->query_first($sql);
			
			if (!empty($user_question_preson))
			{
				$this->errorOutput('同一用户在' . $vote_question['userid_limit_time'] . '小时内 不能再次投票');
			}
		}
		
		//ip投票限制
		if ($vote_question['is_ip'])
		{
			$ip_toff = $vote_question['ip_limit_time'] * 3600;
			$ip_time = TIMENOW - $ip_toff;
			
			$sql = "SELECT vote_question_id FROM " . DB_PREFIX . "question_person WHERE vote_question_id = " . $id . " AND create_time > " . $ip_time . " AND ip = '" . $ip . "'";
			$ip_question_preson = $this->db->query_first($sql);

			if (!empty($ip_question_preson))
			{
				$this->errorOutput('同一IP地址在' . $vote_question['ip_limit_time'] . '小时内 不能再次投票');
			}
		}
		
		foreach ($single_total AS $k=>$v)
		{
			//更新选项 投票数
			$sql = "UPDATE " . DB_PREFIX . "question_option SET single_total=(single_total+1) WHERE id=" . $k;
			$this->db->query($sql);
			
			//记录选项 投票
			$sql = "INSERT INTO " . DB_PREFIX . "question_record SET "
								. " question_option_id=" . $k 
								. ", vote_question_id="  . $id 
								. ", ip='" . $ip 
								. "', num=1, start_time=" . TIMENOW;
			$this->db->query($sql);
			
		}
		
		//记录其他 投票
		if (isset($this->input['other_title']) && urldecode($this->input['other_title']))
		{
			$sql = "INSERT INTO " . DB_PREFIX . "question_option SET is_other=1, single_total=1"
								. ", vote_question_id=" . $id
								. ", title='" . urldecode($this->input['other_title'])
								. "', user_id=" . $this->user['user_id']
								. ", create_time=" . TIMENOW;
			
			$other_option_id = $this->db->insert_id();
			$this->db->query($sql);
			
			if ($other_option_id)
			{
				//记录选项 投票
				$sql = "INSERT INTO " . DB_PREFIX . "question_record SET "
									. " question_option_id=" . $other_option_id 
									. ", vote_question_id="  . $id 
									. ", ip='" . $ip 
									. "', num=1, start_time=" . TIMENOW;
				$this->db->query($sql);
			}
		}
		
		//记录参与人数
		$sql = "INSERT INTO " . DB_PREFIX . "question_person SET "
							. " vote_question_id=" . $id
							. ", user_id=" . intval($this->user['user_id'])
							. ", app_id=" . $app_id
							. ", create_time=" . TIMENOW
							. ", ip='" . $ip . "'";
		$this->db->query($sql);
		
		//记录参与人数 所投选项
		$question_option_ids = @array_keys($single_total);
		
		$sql = "INSERT INTO " . DB_PREFIX . "question_person_info SET "
							. " vote_question_id=" . $id
							. ", user_id=" . $this->user['user_id']
							. ", option_ids='" . implode(',', $question_option_ids) . "'"; 
		$this->db->query($sql);
		
		//统计参与人数
		$sql = "SELECT vote_question_id FROM " . DB_PREFIX . "question_count WHERE vote_question_id=" . $id . " AND app_id = " . $app_id;
		$question_count = $this->db->query_first($sql);
		if (empty($question_count))
		{
			$sql = "INSERT INTO " . DB_PREFIX . "question_count SET "
								. " vote_question_id=" . $id
								. ", app_id=" . $app_id
								. ", app_name='" . $app_name
								. "', counts=1"; 
			$this->db->query($sql);
		}
		else
		{
			$sql = "UPDATE " . DB_PREFIX . "question_count SET counts=(counts+1) WHERE vote_question_id=" . $id . " AND app_id = " . $app_id;
			$this->db->query($sql);
		}
		
		$this->addItem('success');
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
			$condition .= ' AND title like \'%'.urldecode($this->input['k']).'%\'';
		}
		if(isset($this->input['sortid']) && !empty($this->input['sortid']))
		{
			$condition .= " AND node_id = " . intval($this->input['sortid']);
		}
		
		return $condition;
	}
	
	public function index()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput('未被实现的空方法');
	}
}

$out = new singleQuestionApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>