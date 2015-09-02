<?php
class info extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	function message_info($condition='', $orderby = ' i.id DESC ', $offset = 0, $count = 20)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT i.*,f.id as fid,f.file_name,f.file_path 
				FROM '.DB_PREFIX.'interview i 
				LEFT  JOIN '.DB_PREFIX.'files f ON i.cover_pic = f.id 
				WHERE i.isclose=0 '.$condition.$orderby.$limit;
		$res = $this->db->query($sql);
		$k =array();
		while (!false ==($r = $this->db->fetch_array($res)))
		{
			$arr['id'] = $r['id'];
			$arr['title'] = $r['title'];
			$arr['start_time'] = $r['start_time'];
			$arr['end_time'] = $r['end_time'];
			
			//$a = unserialize($r['moderator']);
			//$r['moderator'] = $a[key($a)];
			$r['moderator'] = unserialize($r['moderator']);
			$arr['moderator'] = empty($r['moderator']) ? $r['moderator'] : '';
			
			$b = unserialize($r['honor_guests']);
			$arr['honor_guests'] = empty($b)? '' : $b;
			$arr['object_type'] = $this->settings['object_type'][$r['object_type']];
			$arr['description'] =$r['description'];
			$arr['is_pre_ask'] = $r['is_pre_ask'];
			$arr['need_login'] = $r['nedd_login'];
			$arr['cover_pic'] = $r['file_path']&&$r['file_name'] ? hg_material_link(IMG_URL, app_to_dir(APP_UNIQUEID), $r['file_path'], $r['file_name']) :'';
			$k[] = $arr;
			
		}
		return $k;
		
	}
	function pic_info($condition='', $orderby = 'order by id DESC ', $offset = 0, $count = 20)
	{	
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM '.DB_PREFIX.'files WHERE is_ban=0 '.$condition.$orderby.$limit;
		
		$res = $this->db->query($sql);
		$k =array();
		while (!false ==($r = $this->db->fetch_array($res)))
		{
			$arr['name'] = $r['name'];
			$arr['file_type'] = $r['file_type'];
			$arr['url']=array(
							'host'=>$r['host'],
							'dir'=>$r['dir'],
							'file_path'=>$r['file_path'],
							'file_name'=>$r['file_name']
						);
			$k[] = $arr;
			
		}
		return $k;
	}
	function content_info($condition, $orderby, $offset = 0, $count = 20)
	{
		
		$condition = $condition ?  $condition : '';
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM '.DB_PREFIX.'records WHERE is_pub=1 '.$condition.$orderby.$limit;
		$res = $this->db->query($sql);
		$k =array();
		while (!false ==($r = $this->db->fetch_array($res)))
		{
			$arr=array();
			$arr['id'] = $r['id'];
			$arr['user_name'] = $r['user_name'];
			$r['question'] = str_ireplace('[QUOTE]','<div class="quote">引用:',$r['question']);
			$r['question'] = str_ireplace('[/QUOTE]','</div>',$r['question']);
			$r['question'] = preg_replace("/\[:(\d+)\]/",'<img src="'.QQBIAOQING_DIR."\\1".$qqbiaoqing_type.'" />',$r['question']);
			$r['question'] = preg_replace("/\[IMG=(\S+)\]\[\/IMG\]/U",'<img src="${1}"/>',$r['question']);
	        $data = $this->get_content_color($r['user_id'], $r['interview_id']);
	    	$data['color'] = $data['color'] ? $data['color'] :'#000';
	    	$data['bgcolor'] = $data['bgcolor'] ? $data['bgcolor'] :'#fff';
	          
			$arr['question'] = $r['question'];
			$arr['create_time'] = $r['create_time'];
			$arr['color'] = $data['color'];
			$arr['bgcolor'] = $data['bgcolor'];
			$arr['interview_id'] = $r['interview_id'];
			$arr['state'] = $r['state']; 
			$arr['is_guests_reply'] = $r['is_guests_reply'];
			if ($arr['is_guests_reply'])
			{
				$arr['reply_user_name'] = $r['reply_user_name'];
				$arr['reply'] = $r['reply'];
			}
			
		
			$k[] = $arr;		
		}
		return $k;
	}
	//获取内容的背景色和字体颜色
	function get_content_color($userId,$interviewId)
	{
			
		$role = $this->role($userId, $interviewId);
		
		$prms = $this->prms($role, $interviewId);
	
		$data =array(
			'bgcolor'=>$prms[3],
			'color'=>$prms[4],
		);
	
		return $data;
		
	}
	/**
	 * 
	 * 访谈角色信息
	 * @param int $user_id
	 * @param int $interview_id
	 * @return int $r 		  角色ID
	 */
	
	public function role($user_id,$interview_id)
	{
		//参数接收
		$data = array(
			'user_id'=>$user_id,
			'interview_id'=>$interview_id,
		);
		if (!$data['user_id'] || !$data['interview_id']){
			return ;
		}
		//返回结果
		$sql = 'SELECT ug.role FROM ' .DB_PREFIX .'user_group ug 
				LEFT JOIN '.DB_PREFIX.'interview_user iu ON  ug.id=iu.group 
				where iu.user_id ='.$data['user_id'].' AND iu.interview_id ='.$data['interview_id'];
		$res = $this->db->query_first($sql);
		$r = $res['role']?$res['role']:0;
		return $r;
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
		if (!$data['role'] || !$data['interview_id']){
			return ;
		}
		//返回结果
		$sql = 'SELECT prms FROM ' .DB_PREFIX .'interview  where id ='.$data['interview_id'];
		$res = $this->db->query_first($sql);
		$prms = $res['prms'];
		$arr = unserialize($prms);
		$r = $arr[$role];
		return $r;
	}	


}
?>
