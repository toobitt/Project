<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require("./lib/interview.class.php");
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require("./lib/pic.class.php");
require("./lib/check.class.php");
class interviewApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->interview = new interview();
		$this->pic = new pic();
		$this->check = new check();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 接收预提问
	 * @param int $interview_id  	访谈ID	 
	 * @param string $question  	预提问内容
	 * @return string
	 */
	public function pre_ask()
	{
				//参数接收
		$data =array(
			'interview_id'=>intval($this->input['interview_id']),		
			'question'=>addslashes(htmlspecialchars($this->input['question'])),
			'user_id'=>intval($this->user['user_id']),
			'user_name'=>trim($this->user['user_name']),
			'ip'=>trim($this->user['ip']),
			'create_time'=>TIMENOW,
			'is_pub'=>1
		);
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>$data['user_id'],
			'interview_id'=>$data['interview_id']));
		
		$data['user_id'] = $data['user_id'] ?$data['user_id'] : 0;
		$data['user_name'] = $data['user_name']?$data['user_name'] :'网友';
		if (!$data['interview_id'])
		{
			$this->errorOutput('访谈ID为空');
		}
		if (!$data['question'])
		{
			$this->errorOutput('提问内容为空');
		}
		//
		//预提问是否关闭
		$q = 'SELECT is_pre_ask FROM '.DB_PREFIX.'interview WHERE id='.$data['interview_id'];
		$r = $this->db->query_first($q);
		if (!$r['is_pre_ask']){
			$this->errorOutput('预提问已被关闭');
		}else {
			
			$this->interview->pre_ask($data);
			$this->addItem('success');
		}
		$this->output();	
	}
	/**
	 * 新增发言或者引用
	 * @param int interview_id		访谈ID
	 * @param str content			发言的内容
	 * @param int user_id			用户
	 * @param str user_name			用户名
	 * @param str ip				IP
	 * @return Array $return        提示信息
	 */
	public function speech(){
		//参数接收
		
		$data = array(
	    	'interview_id'=>$this->input['interview_id'],
			'content'=>addslashes(strip_tags(trim(urldecode($this->input['content'])))),
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'create_time'=>TIMENOW,
			'ip'=>$this->user['ip'],
		);
		if (!$data['user_id'])
		{
			$data['user_id'] = 0;
			
		}
		if (!$data['user_name'])
		{
			$data['user_name'] = $this->input['nickname'] ? addslashes(trim(urldecode($this->input['nickname']))) : '网友';
		}
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>$data['user_id'],
			'name'=>$data['user_name'],
			'interview_id'=>$data['interview_id']));
		
		//权限验证
		$role = $this->interview->role($data['user_id'],$data['interview_id']);
		$prms = $this->interview->prms($role,$data['interview_id']);
		$prm = $prms[0];
		$return = array();
		if (empty($data['content']))
		{
			$this->errorOutput(NODATA);
		}elseif (!$prm){
			$this->errorOutput('对不起，您没有发言的权限');
		}else {		
			if ($prm==2){
				$data['audit'] = TIMENOW;
				$data['is_pub'] = 1;
			}else {
				$data['audit'] = 0;
				$data['is_pub'] = 1;
			}
			//指定嘉宾
			if (isset($this->input['guest_id']))
			{
				$data['guest_id'] = intval(urldecode($this->input['guest_id']));
			}
			if (isset($this->input['is_pub']))
			{
				$data['is_pub'] = intval(urldecode($this->input['is_pub']));
			}				
			//插入数据库		
			$return = $this->interview->speech($data['interview_id'], $data['content'],
			 $data['user_id'], $data['user_name'], $data['create_time'], $data['ip'],
			  $prm,$data['audit'],$data['guest_id'],$data['is_pub']);
		}
		$this->addItem($return);
		$this->output();
	}
	/**
	 * 回复
	 * Enter description here ...
	 */
	public function reply()
	{
		//参数接收
		$data = array(
			'reply_user_id'=>intval(urldecode($this->user['user_id'])),
			'reply_user_name'=>intval(urldecode($this->user['user_name'])),
			'reply'=>addslashes(trim(urldecode($this->input['reply']))),
			'reply_time'=>TIMENOW,
			'reply_record_id'=>intval(urldecode($this->input['reply_id'])),
			'reply_ip'=>$this->user['ip'],
			'is_guests_reply'=>1,
			'interview_id'=>intval(urldecode($this->input['interview_id']))
		);
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>$data['repy_user_id'],
			'name'=>$data['reply_user_name'],
			'interview_id'=>$data['interview_id']));
		
		$codition = array('id'=>$data['reply_record_id']);
		if (!$data['reply_record_id'])
		{
			$this->errorOutput(NOID);
		}
		if (!$data['reply'])
		{
			$this->errorOutput(NODATA);
		}
		//数据库插入
		$this->interview->reply($data,$codition);
		$this->addItem($data);
		$this->output();
		
	}
	/**
	 * 在线人数
	 * @param int  $interview_id   访谈ID
	 * @return unknown_type
	 */
	public function get_online()
	{
		//检查是否有用户退出
		$this->check->check_online(TIMENOW);
		//参数接收
		$data =array(
			'interview_id'=>intval(urldecode($this->input['interview_id'])),
		);
		$num = $this->interview->get_online($data['interview_id']);
		$this->addItem($num);
		$this->output();
	}
	/**
	 * 获取用户角色
	 * @param int $user_id  用户ID
	 * @param int $interview_id  访谈ID
	 */
	public function role()
	{
		//参数接收
		$data = array(
			'user_id'=>intval(urldecode($this->input['user_id'])),
			'interview_id'=>intval(urldecode($this->input['interview_id'])),			
		);
		if (!$data['user_id'] || !$data['interview_id']){
			$this->errorOutput(NOID);
		}
		
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>$data['user_id'],
			'interview_id'=>$data['interview_id']));
		
		
		$role = $this->interview->role($data['user_id'], $data['interview_id']);
		$this->addItem($role);
		$this->output();
	}
	/**
	 * 关闭访谈
	 * @param int  访谈ID
	 */
	public function close_interview()
	{
		//参数接收
		$data = array(
			'interview_id'=>intval(urldecode($this->input['interview_id'])),
			'user_id'=>$this->user['user_id'],
		);
		if (!$data['interview_id'])
		{
			$this->errorOutput(NOID);
		}
		//关闭时，清除所有在线人员
		$this->check->clear_online($data['interview_id']);
		
		
		$role = $this->interview->role($data['user_id'], $data['interview_id']);
		
		if ($role ==2 || $role==1)
		{
			$this->interview->close_interview($data['interview_id']);
			$this->addItem('sucess');
		}else {
			$this->errorOutput('关闭失败');
		}
		$this->output();
	}
	/**
	 * 获取所有嘉宾
	 *
	 */
	public function guests()
	{
		$condition = '';
		if ($this->input['interview_id'])
		{
			$condition.= ' AND id='.intval(urldecode($this->input['interview_id']));
		}
		//$this->setXmlNode('guests','item');
		$guests = $this->interview->guests($condition);	
		$this->addItem($guests);
		$this->output();
	}
	/**
	 * 文件上传
	 */
	public function upload()
	{
		//参数接收
		$data =array(
			'interview_id'=>intval(urldecode($this->input['interview_id'])),
			'user_id'=>intval(urldecode($this->user['user_id'])),
			'user_name'=>addslashes(trim(urldecode($this->user['user_name']))),
			'name'=>$this->input['name'],
		);
		if (!$data['user_name'])
		{
			$data['user_name'] = addslashes(trim(urldecode($this->input['nickname'])));
			if (!$data['user_name'])
			{
				$data['user_name'] = '匿名用户';
			}
		}
		
		$data['size'] = $this->input['size'] ? trim(urldecode($this->input['size'])).'/' : '';
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), 
								  array('user_id'=>$data['user_id'],'name'=>$data['user_name'],interview_id=>$data['interview_id'])
								  );
	    //图片信息处理		
		if ($_FILES['photos'])
		{
			$count = count($_FILES['photos']['error']);
			for($i = 0;$i<$count;$i++)
			{
				//存储入库数据
				$arr = array();
				if ($_FILES['photos']['error'][$i]==0)
				{
					$pics = array();
					foreach($_FILES['photos'] AS $k =>$v)
					{
						$pics['Filedata'][$k] = $_FILES['photos'][$k][$i];
					}
					$material = $this->pic->interview_uplaod($pics,$data['interview_id']); //插入各类服务器
					if ($material)
					{
						//准备好入库数据
						$arr['interview_id'] = $data['interview_id'];
						$arr['user_id'] = $data['user_id'];
						$arr['user_name'] = $data['user_name'];
						$arr['name'] = $data['name'][$i];
						$arr['file_name'] = $material['filename'];
						$arr['file_path'] = $material['filepath'];
						$arr['file_type'] = $material['type'];
						$arr['file_size'] = $material['filesize'];
						$arr['original_id'] = $material['id'];
						$arr['is_img'] = 1;
						$arr['create_time'] = TIMENOW;
						$arr['is_ban'] = 0;
						$arr['show_pos'] = 2;
						$ret = $this->interview->upload($arr);
						if ($ret)
						{
							$data['photos'][$i]['url'] = hg_material_link($material['host'], $material['dir'], $material['filepath'], $material['filename'] ,$data['size']);
							$data['photos'][$i]['name'] = $data['name'][$i];
						}
						
					}
				}		
			}
		
		}
		$this->addItem($data);
		$this->output();
	}
	public function video()
	{
		//参数接受
		$data =array(
			'interview_id'=>intval(urldecode($this->input['interview_id'])),
			'user_id'=>intval(urldecode($this->user['user_id'])),
		);
		if (!$data['interview_id'])
		{
			$this->errorOutput(NOID);
		}
		//获取访谈信息时更新用户的在线时间
		$this->check->update_time(array('login_time'=>TIMENOW), array(
			'user_id'=>$data['user_id'],
			'interview_id'=>$data['interview_id']));
		//输出流信息
		$addr = $this->interview->videoInfo($data['interview_id']);
		$this->addItem($addr);
		$this->output();
		
	}
}
$ouput= new interviewApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>