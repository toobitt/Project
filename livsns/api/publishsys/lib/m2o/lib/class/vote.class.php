<?php
class vote
{
	function __construct()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_vote']['host'], $gGlobalConfig['App_vote']['dir']);
	}

	function __destruct()
	{
	}

	public function voteList()
	{	
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		return $this->curl->request('vote_question.php');
	}
	public function questionList($id)
	{	
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('vote_id', $id);
		$this->curl->addRequestData('a','show2');
		return $this->curl->request('vote_question.php');
	}

	public function submitVote1()
	{
		$vote_id = intval($_REQUEST['vote_id']);
		$ip = urldecode($_REQUEST['ip']);
		$single_total = $_REQUEST['single_total'];
		$other_title = $_REQUEST['other_title'];
		$username =  urldecode($_REQUEST['username']);
		$sex = intval($_REQUEST['sex']);
		$moblie = intval($_REQUEST['moblie']);
		$id_card = intval($_REQUEST['id_card']);
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','option_update');
		$this->curl->addRequestData('vote_id', $vote_id);
		$this->curl->addRequestData('ip', $ip);
		
		if ($single_total)
		{
			foreach ($single_total AS $k => $v)
			{
				$this->curl->addRequestData('single_total['.$k.']', urldecode($single_total[$k]));
			}
		}
		if ($other_title)
		{
			foreach ($other_title AS $k => $v)
			{
				$this->curl->addRequestData('other_title['.$k.']', urldecode($other_title[$k]));
			}
		}

		$this->curl->addRequestData('username', $username);
		$this->curl->addRequestData('sex', $sex);
		$this->curl->addRequestData('moblie', $moblie);
		$this->curl->addRequestData('id_card', $id_card);
		$ret =  $this->curl->request('vote_question.php');
		return $ret;
	}
		public function singleQuestionList()
	{
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		return $this->curl->request('single_question.php');
	}

	public function singleQuestionConti($id)
	{
		$id = $id;
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'detail');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('single_question.php');
$ret = $ret[0];
return $ret;
	}

	public function singleQuestionCon($id)
	{
		$id = $id;
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'detail');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('single_question.php');
//echo '<!-- ' . var_export($ret, 1) . ' -->';
return $ret;

	}

	public function submitVote()
	{
		$id = intval($_REQUEST['vote_id']);
		$ip = urldecode($_REQUEST['ip']);
		$single_total = $_REQUEST['single_total'];
		$other_title = $_REQUEST['other_title'];
        $verify_code = $_REQUEST['verify_code'];
		
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','single_option_update');
		$this->curl->addRequestData('question_id', $id);
        $this->curl->addRequestData('verify_code', $verify_code);
		$this->curl->addRequestData('ip', $ip);
		if ($single_total)
		{
			foreach ($single_total AS $k => $v)
			{
				$this->curl->addRequestData('single_total['.$k.']', urldecode($single_total[$k]));
			}
		}
		if ($other_title)
		{
			foreach ($other_title AS $k => $v)
			{
				$this->curl->addRequestData('other_title['.$k.']', urldecode($other_title[$k]));
			}
		}
		
		$ret =  $this->curl->request('single_question.php');
		return $ret;
	}
	public function submitQuestion()
	{
		$id = intval($_REQUEST['id']);
		$ip = urldecode($_REQUEST['ip']);
		$single_total = $_REQUEST['single_total'];
		$other_title = $_REQUEST['other_title'];
		
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','single_option_update');
		$this->curl->addRequestData('question_id', $id);
		$this->curl->addRequestData('ip', $ip);
		if ($single_total)
		{
			foreach ($single_total AS $k => $v)
			{
				$this->curl->addRequestData('single_total['.$k.']', urldecode($single_total[$k]));
			}
		}
		if ($other_title)
		{
			foreach ($other_title AS $k => $v)
			{
				$this->curl->addRequestData('other_title['.$k.']', urldecode($other_title[$k]));
			}
		}
		
		$ret =  $this->curl->request('single_question.php');
		return $ret;
	}
}
?>