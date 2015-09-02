<?php 
class interview extends InitFrm
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
	 * 预提问
	 * @param int $id				访谈ID
	 * @param string $question		预提问
	 */
	public function pre_ask($data)
	{	
		// 数据库插入
		$sql = 'INSERT INTO '.DB_PREFIX.'records SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",'; 
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		//更新order_id，默认与ID值相同
		$u_sql = 'UPDATE '.DB_PREFIX.'records SET order_id = '.$data['id'].' WHERE id ='.$data['id'];
		$this->db->query($u_sql);
		return $data['id'];
	
	}
	public function speech($interview_id, $content, $user_id, $user_name, $time, $ip,
					$state, $audit_time, $guest_id = 0, $pub = 1)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.'records SET interview_id = '.$interview_id.
		',question = "'.$content.
		'",user_id = '.$user_id.
		',user_name = "'.$user_name.
		'",create_time = "'.$time.
		'",ip = "'.$ip.
		'",state = '.$state.
		',audit_time = '.$audit_time.
		',is_pub = '.$pub.
		',guests_id = "'.$guest_id.'"';
		$this->db->query($sql);
		$id = $this->db->insert_id();
		//更新order_id，默认与ID值相同
		$ord_sql = 'UPDATE '.DB_PREFIX.'records SET order_id = '.$id.' WHERE id ='.$id;
		$this->db->query($ord_sql);	
		return $id;
	
	}
	/**
	 * 
	 * 登陆 
	 * @param int $uid  用户ID
	 * @param string $name  用户名
	 * @param string $token  token值
	 * @param int $interview_id  访谈ID
	 */
	public function dologin($data)
	{
		
		$sql = 'INSERT INTO '.DB_PREFIX.'online SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",'; 
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		
		//登陆某个访谈，返回角色信息
		if ($data['interview_id'])
		{
			
			$sql = 'SELECT ug.id as gid,ug.role,iu.interview_id  
					FROM '.DB_PREFIX.'user_group ug 
					LEFT  JOIN '.DB_PREFIX.'interview_user iu ON ug.id = iu.group 
					WHERE iu.interview_id='.$data['interview_id'].' AND iu.user_id='.$data['user_id'];
			$res = $this->db->query_first($sql);
	       
			if ($res['role'])
			{
				$data['role']= $this->settings['roles'][$res['role']];
				//返回对应的权限
				$sql = 'SELECT prms FROM '.DB_PREFIX.'interview WHERE id='.$data['interview_id'];
				$r = $this->db->query_first($sql);
				$prms = unserialize($r['prms']);
				$data['prms'] = $prms[$res['role']];
			}else {
				$data['role'] = $this->settings['roles'][7];
				$data['prms'] = $this->settings['roleoption'][7];
			}
		}
	
		return $data;
		
		
	}
	/**
	 * 
	 * 退出
	 * @param int $id    用户ID
	 * @param string $token  token值
	 * @param int $interview_id   访谈ID
	 */
	public function logout($id,$token,$interview_id=0)
	{
		$data = array(
			'user_id'=>$id,
			'token'=>$token,
			'interview_id'=>$interview_id,
		);
		$condition = '';
		if ($interview_id)
		{
			$condition.=' AND interview_id='.$data['interview_id'];
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'online WHERE user_id='.$data['user_id'].' AND token="'.$data['token'].'"'.$condition;
		$this->db->query($sql);
		return true;
	
	}
	/**
	 * 
	 * 获取在线人数
	 * @param int $interview_id
	 */
	public function get_online($interview_id)
	{
		//参数接收
		$data = array(
					'interview_id'=>$interview_id,
				);
		$condition = '';
		if ($data['interview_id'])
		{
			$condition.= '  AND interview_id='.$data['interview_id'];
		}
		$sql = 'SELECT count(*) as total FROM ' . DB_PREFIX . 'online WHERE ' . TIMENOW . '-login_time < 36000000000 '.$condition;
		$row = $this->db->query_first($sql);
		$total_num = $row['total'];
		return $total_num;
	
	
	}
	/**
	 * 
	 * 关闭访谈
	 * @param int $interview_id  访谈ID
	 */
	public function close_interview($interview_id)
	{
		$data = array(
			'interview_id'=>$interview_id,
		);	
		$sql = 'UPDATE ' . DB_PREFIX . 'interview SET isclose=1,is_lishi=1 WHERE id=' . $data['interview_id'];
		$this->db->query($sql);
		return TRUE;
	}
	/**
	 * 访谈的视频
	 * @param int $interview_id  访谈ID
	 * @return string $str       访谈的视频地址
	 */
	public function videoInfo($interview_id)
	{
		//参数接收
		$data = array(
			'interview_id'=>$interview_id,
		);
		if (!$data['interview_id']){
			return ;
		}
		//返回结果
		$arr = array();
		$sql = 'SELECT video_addr FROM '.DB_PREFIX.'interview WHERE id='.$data['interview_id'];
		$res = $this->db->query_first($sql);
		$video = unserialize($res['video_addr']);
		$video_addr = !empty($video) ? $video : array();
		return $video_addr;
	}
	/**
	 * 
	 * 访谈角色信息
	 * @param int $user_id
	 * @param int $interview_id
	 * @return str $r 		  角色
	 */
	
	public function role($user_id,$interview_id)
	{
		//参数接收
		$data = array(
			'user_id'=>$user_id,
			'interview_id'=>$interview_id,
		);
		if (!isset($data['user_id']) || !$data['interview_id']){
			return ;
		}
		//返回结果
		$sql = 'SELECT ug.role FROM ' .DB_PREFIX .'user_group ug 
				LEFT JOIN '.DB_PREFIX.'interview_user iu ON  ug.id=iu.group 
				where iu.user_id ='.$data['user_id'].' AND iu.interview_id ='.$data['interview_id'];
		$res = $this->db->query_first($sql);
		$role = $res['role']?$res['role']:0;
		return $role;
	}
	
	/**
	 * 
	 * 访谈权限
	 * @param int $role   角色
	 * @param int $interview_id  访谈ID
	 * @return Array $r 		权限组，0是发言，1是回复，2是修改，3是背景颜色，4是字体颜色
	 */
	public function prms($role,$interview_id)
	{
		//参数接收
		$data = array(
			'role'=>$role,
			'interview_id'=>$interview_id,
		);
		
		if (!isset($data['role']) || !$data['interview_id']){
			return ;
		}
		if (!$data['role'])
		{
			$data['role'] = 7;
		}
		//返回结果
		$sql = 'SELECT prms FROM ' .DB_PREFIX .'interview  where id ='.$data['interview_id'];
		$res = $this->db->query_first($sql);
		$prms = $res['prms'];
		$arr = unserialize($prms);
		$r = $arr[$data['role']];
		return $r;
	}
	/**
	 * 
	 * 获取嘉宾
	 */
	public function guests($condition='')
	{

		$sql = 'SELECT id,honor_guests FROM ' . DB_PREFIX . 'interview WHERE 1 ' .$condition;	
		
		$query = $this->db->query($sql);
		$arr =array();
		while(!false==($row=$this->db->fetch_array($query)))
		{
			$arr[] = $row;
		}
		$num = count($arr);
		if (!$num){
			$guests=array();
		}
		if ($num==1){
			$guests=unserialize($arr[0]['honor_guests']);
		}
		if ($num>1){
			foreach ($arr as $v){
				$guests[] = unserialize($v['honor_guests']);
			}
		}
		return  $guests;
	}
	/**
	 * 
	 * 获取嘉宾访谈所有用户
	 * @param int $interview_id  访谈ID
	 * @return Array $arr        所有访谈成员
	 */
	public function all_users($interview_id)
	{
		//参数接收
		$data = array(
			'interview_id'=>$interview_id,
		);
		if (!$data['interview_id']){
			return ;
		}
		$sql = 'SELECT user_id,role FROM ' .DB_PREFIX. 'interview_user WHERE interview_id=' .$data['interview_id'];
		$res = $this->db->query($sql);
		$r = array();
		while ($row = $this->db->fetch_array($res))
		{
			$r['user_id'] = $row['user_id'];
			$r['role'] = $row['role'];
			$arr[] =$r; 
		}
		return $arr;
	}
	/**
	 * 文件上传
	 * 
	 */
	public function upload($data)
	{	
		if (is_array($data) && !empty($data))
		{
			$sql = 'INSERT INTO '.DB_PREFIX.'files SET ';
			foreach ($data as $key=>$val)
			{
				$sql .= $key.'="'.$val.'",';
			}
			$sql = rtrim($sql,',');
			
			$this->db->query($sql);	
			$oid = $this->db->insert_id();
			//是否存在封面图片，不存在则把第一张图片当封面图片
			$sql = 'SELECT cover_pic FROM '.DB_PREFIX.'interview WHERE id='.$data['interview_id'];
			$query = $this->db->query_first($sql);
			$indexpic = $query['cover_pic'];
			if(!$indexpic)
			{
				$indexpic = $oid;
			}
			$sql = 'UPDATE '.DB_PREFIX.'interview SET cover_pic = '.$indexpic.' WHERE id = '.$data['interview_id'];
			$this->db->query($sql);
			$sql = 'UPDATE '.DB_PREFIX.'files SET order_id = '.$oid.' WHERE id = '.$oid;
			$this->db->query($sql);
			return true;
		}else {
			return false;
		}		
	
	}
	public function reply($data,$condition)
	{
		$sql = 'UPDATE '.DB_PREFIX.'records SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",' ; 
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE 1 ';
		foreach ($condition as $k=>$v)
		{
			$sql .= ' AND '.$k.'="'.$v.'"' ;
		}
		$this->db->query($sql);
		return true;
	}
	public function checkname($data)
	{
		$sql = 'SELECT id FROM '.DB_PREFIX .'online WHERE 1';
		foreach ($data as $key=>$val)
		{
			$sql .= ' AND '.$key .'="'.$val.'"';
		}
		$ret = $this->db->query_first($sql);
		if ($ret['id'])
		{
			return false;
		}else {
			return true;
		}
	}
	public function nlogout($data)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'online WHERE 1';
		foreach ($data as $key=>$val)
		{
			$sql .= ' AND '.$key.'="'.$val.'"';
		}
		$this->db->query($sql);
		return true;
	}
}




