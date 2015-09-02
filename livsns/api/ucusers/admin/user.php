<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: user.php 12811 2012-10-22 09:10:44Z lijiaying $
***************************************************************************/
require('global.php');
class userApi extends BaseFrm
{
	private $mUser;
	public function __construct()
	{
		parent::__construct();
		require(CUR_CONF_PATH . 'lib/user.class.php');
		$this->mUser = new user();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$sqladd = " WHERE 1 " . $this->get_condition();
		$sqladd .= " ORDER BY uid DESC ";

		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
			
		$ppp = $this->input['count'] ? intval($this->input['count']) : 20;
		$totalnum = $this->mUser->uc_get_total_num($sqladd);
		
		$page = ceil($offset / $ppp) + 1;
		
		$infos = $this->mUser->uc_get_list($page, $ppp, $totalnum, $sqladd);
	
		if ($infos)
		{
			$info = array();
			foreach ($infos AS $k => $v)
			{
				$info[$v['uid']] = $v;
				$info[$v['uid']]['create_time'] = date('Y-m-d H:i:s',$v['regdate']);
			}
		}

		$this->addItem($info);
		$this->output();
	}
	
	public function show1()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$infos = $this->mUser->show($condition, $offset, $count, $field, $order);
		
		if ($infos)
		{
			$info = array();
			foreach ($infos AS $k => $v)
			{
				$info[$v['uid']] = $v;
				$info[$v['uid']]['create_time'] = $v['regdate'];
			}
		}

		$this->addItem($info);
		$this->output();
	}

	public function detail()
	{
		$id = urldecode($this->input['id']);
		$username = trim(urldecode($this->input['uname']));
		if($id && $username)
		{
			$ret = $this->mUser->uc_get_user($username);
			
			$row = array();
			if (!empty($ret))
			{
				$row['id'] = $ret[0];
				$row['uname'] = $ret[1];
				$row['email'] = $ret[2];
			}
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('用户不存在');	
		} 	
	}
	
	public function count()
	{
		$sqladd = $this->get_condition();
		$ret = $this->mUser->uc_get_total_num($sqladd);
		$ret = array('total'=>$ret);
		echo json_encode($ret);
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= " AND username LIKE \'%".urldecode($this->input['k'])."%\'";
		}
		
		return $condition;
	}

}

$out = new userApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>