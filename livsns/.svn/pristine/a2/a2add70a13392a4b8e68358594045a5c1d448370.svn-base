<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(CUR_CONF_PATH.'lib/interview_admin.class.php');
define('MOD_UNIQUEID','interview');//模块标识
class interview_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->obj = new interview_admin();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	function delete()
	{
		if (!$this->input['id'])
		{
			return ;
		}
		$arr = array(
			'interview_id'=>urldecode($this->input['id']),
		);
		//同时删除嘉宾访谈的用户记录,聊天记录,图片记录
		$this->obj->del_pic($arr['interview_id']);
		$this->obj->del_online($arr['interview_id']);
		$this->obj->del_record($arr['interview_id']);
		$this->obj->del_user($arr['interview_id']);	
		$sql = 'DELETE FROM '.DB_PREFIX.'interview WHERE id IN('.urldecode($this->input['id']).')';
		if ($this->db->query($sql))
		{
			$this->addItem($arr['interview_id']);
		}else {
		
			$this->addItem('error');
		}
		$this->output();
	}

	function create()
	{
		
		if (!trim($this->input['title']))
		{
			$this->errorOutput('请填写访谈标题');
			return ;
		}
		if ($this->input['notice_time'] > $this->input['start_time'] ||$this->input['notice_time'] ==$this->input['start_time'] ||! $this->input['notice_time'])
		{
			$this->errorOutput('预告时间必填,不能晚于或等于开始时间!');
		}
		if (!trim($this->input['start_time']))
		{
			$this->errorOutput('请选择开始时间');
		}
		if (!trim($this->input['input_time']))
		{
			$this->errorOutput('访谈所需时间不能为空!');
		}
		//参数预处理
		$moderator = serialize(array());
		$honor_guests = serialize(array());

		$interview_role = "a:7:{i:1;a:5:{i:0;s:1:\"2\";i:1;s:1:\"2\";i:2;i:1;i:3;s:4:\"#fff\";i:4;s:4:\"#000\";}i:2;a:5:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;i:1;i:3;s:4:\"#fff\";i:4;s:4:\"#000\";}i:3;a:5:{i:0;s:1:\"2\";i:1;s:1:\"2\";i:2;i:0;i:3;s:4:\"#fff\";i:4;s:4:\"#000\";}i:4;a:5:{i:0;s:1:\"2\";i:1;s:1:\"2\";i:2;i:0;i:3;s:4:\"#fff\";i:4;s:4:\"#000\";}i:5;a:5:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;i:0;i:3;s:4:\"#fff\";i:4;s:4:\"#000\";}i:6;a:5:{i:0;s:1:\"1\";i:1;s:1:\"1\";i:2;i:0;i:3;s:4:\"#fff\";i:4;s:4:\"#000\";}i:7;a:5:{i:0;s:1:\"0\";i:1;s:1:\"0\";i:2;i:0;i:3;s:4:\"#fff\";i:4;s:4:\"#000\";}}";
		$live_source = $this->input['live_source'] ? implode(',', $this->input['live_source']) : '';

		$notice_time = strtotime(urldecode($this->input['notice_time']));
		$start_time = strtotime(urldecode($this->input['start_time']));
		 
		$input_time = $this->input ['input_time'];
		$input_time_unit = $this->input ['input_time_unit'];
		switch( $input_time_unit ) {
			case '':
				$this->errorOutput('访谈所需时间必填');
			case 'day':
				$end_time = 86400 *$input_time +$start_time;
				break;
			case 'hour':
				$end_time = 3600 *$input_time +$start_time;
				break;
			case 'minute':
				$end_time = 60 *$input_time +$start_time;
				break;
		}
		//参数接收
		$data = array(
			'title'=>trim(urldecode($this->input['title'])),
		    'description'=>trim(urldecode($this->input['description'])),
			'notice_time'=>$notice_time,
			'start_time'=>$start_time,
			'end_time'=>$end_time,
			'moderator'=>$moderator,
			'honor_guests'=>$honor_guests,
			'create_time'=>TIMENOW,
			'live_source'=>$live_source,
			'is_pre_ask'=>trim(urldecode($this->input['is_pre_ask'])),
			'need_login'=>trim(urldecode($this->input['need_login'])),
			'object_type'=>trim(urldecode($this->input['object_type'])),
			'isclose'=>trim(urldecode($this->input['isclose'])),
			'prms'=>$interview_role,
			'is_lishi'=>trim(urldecode($this->input['is_lishi'])),
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
		);
		//数据库插入
		$sql = 'INSERT INTO '.DB_PREFIX.'interview SET title = "'.addslashes($data['title']).
		'",description = "'.addslashes($data['description']).
		'",notice_time = "'.$data['notice_time'].
		'",start_time = "'.$data['start_time'].
		'",end_time = "'.$data['end_time'].
		'",moderator = "'.addslashes($data['moderator']).
		'",honor_guests = "'.addslashes($data['honor_guests']).
		'",create_time = "'.$data['create_time'].
		'",live_source = "'.addslashes($data['live_source']).
		'",is_pre_ask = "'.$data['is_pre_ask'].
		'",need_login = "'.$data['need_login'].
		'",object_type = "'.$data['object_type'].
		'",isclose = "'.$data['isclose'].
		'",prms = "'.addslashes($data['prms']).
		'",user_id = "'.$data['user_id'].
		'",user_name = "'.$data['user_name'].
		'",is_lishi = "'.$data['is_lishi'].'"';
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		//更新排序id
		$sql = 'UPDATE '.DB_PREFIX.'interview SET order_id='.$data['id'].' WHERE id = '.$data['id'];
		$this->db->query($sql);
		$this->addItem($data['id']);
		$this->output();
	}
	
	function update()
	{
		if (!trim($this->input['title']))
		{
			$this->errorOutput('请填写访谈标题');
			return ;
		}
		if ($this->input['notice_time'] > $this->input['start_time'] ||$this->input['notice_time'] ==$this->input['start_time'] ||! $this->input['notice_time'])
		{
			$this->errorOutput('预告时间必填,不能晚于或等于开始时间!');
		}
		if (!trim($this->input['start_time']))
		{
			$this->errorOutput('请选择开始时间');
		}
		if (!trim($this->input['input_time']))
		{
			$this->errorOutput('访谈所需时间不能为空!');
		}
		//参数预处理
		$moderator = serialize(array());
		$honor_guests = serialize(array());
		$notice_time = strtotime(urldecode($this->input['notice_time']));
		$start_time = strtotime(urldecode($this->input['start_time']));
		$input_time = $this->input['input_time'];
		$input_time_unit = $this->input['input_time_unit'];
		switch( $input_time_unit ) {
			case '':
				$this->errorOutput('访谈所需时间必填');
			case 'day':
				$end_time = 86400 *$input_time +$start_time;
				break;
			case 'hour':
				$end_time = 3600 *$input_time +$start_time;
				break;
			case 'minute':
				$end_time = 60 *$input_time +$start_time;
				break;
		}
		
		$live_source = $this->input['live_source'] ? implode(',', $this->input['live_source']) : '';
		
		//参数接收
		$data = array(
			'title'=>trim(urldecode($this->input['title'])),
		    'description'=>trim(urldecode($this->input['description'])),
			'notice_time'=>$notice_time,
			'start_time'=>$start_time,
			'end_time'=>$end_time,
			'live_source'=>$live_source,
			'is_pre_ask'=>trim(urldecode($this->input['is_pre_ask'])),
			'need_login'=>trim(urldecode($this->input['need_login'])),
			'object_type'=>trim(urldecode($this->input['object_type'])),
			'isclose'=>trim(urldecode($this->input['isclose'])),
			'is_lishi'=>trim(urldecode($this->input['is_lishi'])),
		);
		
		//数据库更新
		$sql = 'UPDATE '.DB_PREFIX.'interview SET title = "'.addslashes($data['title']).
		'",description = "'.addslashes($data['description']).
		'",notice_time = "'.$data['notice_time'].
		'",start_time = "'.$data['start_time'].
		'",end_time = "'.$data['end_time'].
		'",live_source = "'.$data['live_source'].
		'",is_pre_ask = "'.$data['is_pre_ask'].
		'",need_login = "'.$data['need_login'].
		'",object_type = "'.$data['object_type'].
		'",isclose = "'.$data['isclose'].
		'",is_lishi = "'.$data['is_lishi'].'"
		WHERE id = '.urldecode($this->input['id']);
		$this->db->query($sql);
		$this->addItem('sucess');
		$this->output();
		
	}
	
	/**
	 * 权限更新控制
	 */
	function update_authority(){
		//参数预处理
		$id = $this->input['id'];
		$roles = array();
		foreach ($this->settings['roles'] as $key=>$val)
		{
			$arr = array();
			foreach ($this->input as $k=>$v)
			{
				switch ($k)
				{
					case 'speak_'.$key:$arr[0] = $v;continue;
					case 'revert_'.$key:$arr[1] = $v;continue;
					case 'edit_'.$key:$arr[2]=$v;continue;
					case 'bg_color_'.$key:$arr[3] = urldecode($v);continue;
					case 'font_color_'.$key:$arr[4] = urldecode($v);continue;
				}
				if(isset($arr[1]))
				{
					 $arr[2]=empty($arr[2])?0:1;
				}
			}
			$roles[$key]= $arr;
		}
		$this->db->query_first('update ' .DB_PREFIX .'interview set prms="'.addslashes(serialize($roles)).'" where id='.$id);
	    $this->addItem('success');
	    $this->output();
	
	
	}

	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
    public function audit()
    {
    
    }
    
    public function sort()
    {
    
    }

    public function publish()
    {
    
    }

}
$ouput= new interview_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();