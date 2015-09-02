<?php
class Message extends InitFrm
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
	 * Enter description here ...
	 * @param string $field			需要显示字段
	 * @param string $condition		查询条件
	 * @param string $orderby		排序
	 * @param string $limit
	 * @param string $join			连表
	 */
	function show($field,$condition,$orderby,$limit,$join='')
	{

		if (!$orderby)
		{
			$orderby = ' ORDER BY m.order_id DESC ';
		}
		//确定表名
		$tableName = $this->confirm_tablename();

		$sql = "SELECT " . $field . "
				FROM ".DB_PREFIX . $tableName . " m 
				" . $join . "
				WHERE 1 " .$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$res = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['pubtime'] = $r['pub_time'];
			$r['pub_time'] = date('Y-m-d H:i',$r['pub_time']);
			$r['tablename'] = $tableName;
				
			$res[] = $r;
		}
		return $res;
	}

	/**
	 *添加留言
	 *
	 *@param array 	$message 评论信息
	 *@param int 	$rate 评论频率限制
	 *
	 *@return array 	$data  评论入库信息
	 */
	public function add_message($message,$rate='')
	{
		if(!$message)
		{
			return false;
		}
		$data = array(
			'title'			=> $message['title'],
			'username'		=> $message['username'],
			'userid' 		=> $message['userid'],
			'org_id' 		=> $message['org_id'],
			'author' 		=> $message['author'],
			'member_id' 	=> $message['member_id'],
			'content'		=> $message['content'],
			'pub_time'		=> $message['pub_time'],
			'ip'			=> $message['ip'],
			'groupid'		=> $message['groupid'],
			'contentid'		=> $message['contentid'],
			'content_title' => $message['content_title'],
			'content_url'	=> $message['content_url'],
			'cmid' 			=> $message['cmid'],
			'state'			=> $message['state'],
			'app_uniqueid'	=> $message['app_uniqueid'],
			'mod_uniqueid'	=> $message['mod_uniqueid'],
			'site_id' 		=> $message['site_id'],
			'column_id' 	=> $message['column_id'],
			'appid'			=> $message['appid'],
			'appname'		=> $message['appname'],
			'long' 			=> $message['long'],
			'lati' 			=> $message['lati'],
			'banword'		=> $message['banword'],
			'fid'			=> $message['fid'],
			'member_type'	=> $message['member_type'],
			'ip_info'		=> $message['ip_info'],
			
			'baidu_longitude' => $message['baidu_longitude'],
			'baidu_latitude' => $message['baidu_latitude'],
			'address'		=> $message['address'],
		);

		//评论类型，0发布库评论，1栏目评论,2非发布库应用评论
		$com_type =  $data['cmid'] ? 0 : 1;

		//cmid不存在，且标志不为栏目则为非发布库评论
		if($com_type && $data['app_uniqueid'] != 'column')
		{
			$com_type = 2;
		}

		//栏目评论，栏目id=内容id，方便通过栏目节点检索
		if($com_type == 1)
		{
			$data['column_id'] = $data['contentid'];
		}

		$cmid = $com_type ? $data['contentid'] : $data['cmid'];

		//根据app_uniqueid，创建分类
		if($data['app_uniqueid'] && $data['mod_uniqueid'] && !$data['groupid'])
		{
			$sort_id = $this->get_sort_id($data['app_uniqueid'], $data['mod_uniqueid'], $data['contentid']);
			$data['groupid'] = $sort_id ? $sort_id : 0;
		}
		

		//查询此内容是否创建过评论
		$sql = 'SELECT id,sort_id,create_time,comment_count,table_name FROM ' . DB_PREFIX . "comment_index
				WHERE  cmid='{$cmid}' AND com_type = '{$com_type}' AND sort_id = '{$data['groupid']}'";
		$res = $this->db->query_first($sql);

		$count = $res['comment_count']+1;

		//内容创建过评论，根据创建时间确定表名
		if($res['table_name'])
		{
			$tableName = $res['table_name'];
		}
		elseif($res['create_time'])
		{
			$year = date('Y',$res['create_time']);
			$tableName = $this->check_table_name($year);
		}
		else
		{
			$res['create_time'] = TIMENOW;
			$year = date('Y',TIMENOW);
			$tableName = $this->check_table_name($year,1);
		}

		//评论频率限制
		if($rate)
		{
			$sql = "SELECT pub_time FROM ".DB_PREFIX . $tableName . " WHERE 1 ";
			
			if($data['member_id'])
			{
				$sql .= " AND member_id = " . $data['member_id'];
			}
			else if($data['ip'])
			{
				$sql .= " AND ip = '" . $data['ip'] . "'";
			}
			else 
			{
				return false;
			}
			
			if($data['cmid'])
			{
				$sql .= " AND cmid = " . $data['cmid']; 
			}
			else 
			{
				$sql .= " AND contentid=" . $data['contentid']. " AND app_uniqueid = '".$data['app_uniqueid']."' AND mod_uniqueid = '".$data['mod_uniqueid']."'";
			}
			$sql .= " ORDER BY pub_time DESC LIMIT 0,1";
			$pub_time = $this->db->query_first($sql);
			$distance_time = TIMENOW - $pub_time['pub_time'];
			if($distance_time<$rate)
			{
				return false;
			}
		}

		//查询总楼层
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . $tableName . " WHERE app_uniqueid = '" . $data['app_uniqueid'] . "' AND mod_uniqueid = '" . $data['mod_uniqueid'] . "' AND contentid = " . $data['contentid'];
		$floor_res = $this->db->query_first($sql);
		$data['floor'] = $floor_res['total'] + 1;
		//回复楼层处理
		if($data['fid'])
		{
			//查询被回复的楼层
			$sql = "SELECT floor FROM " . DB_PREFIX . $tableName . " WHERE id = " . $data['fid'];
			$floor_reply = $this->db->query_first($sql);
			$data['floor_reply'] = $floor_reply['floor'];
		}
		//入库
		$sql = 'INSERT INTO '.DB_PREFIX . $tableName . ' SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');

		if($this->db->query($sql))
		{
			$data['id'] = $this->db->insert_id();
			//更新排序
			$update_sql = 'UPDATE '.DB_PREFIX . $tableName . ' SET order_id = '.$data['id'].' WHERE id = '.$data['id'];
			$this->db->query($update_sql);
				
			//更新评论索引表
			$sql = "REPLACE INTO " . DB_PREFIX . "comment_index (table_name, cmid, com_type, sort_id, comment_count, create_time, update_time) VALUES
					('{$tableName}', '{$cmid}', '{$com_type}','{$data['groupid']}',  " . $count . ",  '{$res['create_time']}', " . TIMENOW . ")";
			$this->db->query($sql);
				
			//如果是回复，将回复内容更新到评论last_reply
			if($data['fid'])
			{
				$reply = array(
					'title'		=> $data['title'],
					'content'	=> $data['content'],
					'member_id'	=> $data['member_id'],
					'username'	=> $data['username'],
					'user_id'	=> $data['user_id'],
					'long' 		=> $data['long'],
					'lati' 		=> $data['lati'],
					'banword'	=> $data['banword'],
				);

				$reply = serialize($reply);

				$sql = 'UPDATE ' . DB_PREFIX. $tableName . ' SET reply_num = reply_num + 1,last_reply='."'".$reply."'".' WHERE id = '.$data['fid'];
				$this->db->query($sql);
			}
			$data['tableame'] = $tableName;
			return $data;
		}
		else
		{
			return false;
		}
	}

	public function update($data, $table, $where = '')
	{
		if($table == '' or $where == '')
		{
			return false;
		}
		$where = ' WHERE '.$where;
		$field = '';
		if(is_string($data) && $data != '')
		{
			$field = $data;
		}
		elseif (is_array($data) && count($data) > 0)
		{
			$fields = array();
			foreach($data as $k=>$v)
			{
				$fields[] = $k."='".$v . "'";
			}
			$field = implode(',', $fields);
		}
		else
		{
			return false;
		}
		$sql = 'UPDATE '. DB_PREFIX . $table . ' SET '.$field.$where;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $data
	 * @param unknown_type $tablename
	 */
	public function reply_comment($id,$data,$tablename)
	{
		if(!$data)
		{
			return false;
		}
		//将最新一条回复插到留言表中
		$reply = serialize($data);
		$sql = 'UPDATE ' . DB_PREFIX. $tablename . ' SET last_reply='."'".$reply."'".' WHERE id = '.$id;
		$this->db->query($sql);

		//查询总楼层
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . $tablename . " WHERE app_uniqueid = '" . $data['app_uniqueid'] . "' AND mod_uniqueid = '" . $data['mod_uniqueid'] . "' AND contentid = " . $data['contentid'];
		$floor_res = $this->db->query_first($sqls);
		$data['floor'] = $floor_res['total'] + 1;
		//回复楼层处理
		if($data['fid'])
		{
			//查询被回复的楼层
			$sql = "SELECT floor FROM " . DB_PREFIX . $tablename . " WHERE id = " . $data['fid'];
			$floor_reply = $this->db->query_first($sql);
			$data['floor_reply'] = $floor_reply['floor'];
		}
		//插入评论表
		$sql = 'INSERT INTO ' . DB_PREFIX . $tablename . ' SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');

		if($this->db->query($sql))
		{
			$data['id'] = $this->db->insert_id();
			
			//更新排序
			$update_sql = 'UPDATE '.DB_PREFIX . $tablename . ' SET order_id = '.$data['id'].' WHERE id = '.$data['id'];
			$this->db->query($update_sql);
			return $data;
		}
		else
		{
			return false;
		}
	}
	/**
	 *查找具体某一个留言信息
	 *
	 *@param $id int 评论id
	 *@param $tableName string 评论所在表名
	 *@return $return array 评论详细内容
	 *
	 */
	public function detail($id,$tableName)
	{
		if(!$id || !$tableName)
		{
			return false;
		}
		$condition = ' WHERE m.id =' . $id;
		//查出留言记录
		$sql = "SELECT m.content_title,m.content_url,m.id,m.title,m.app_uniqueid,m.mod_uniqueid,m.userid,m.username,m.member_id,m.author,m.pub_time,m.ip,m.state,m.content,m.useful,m.yawp,m.contentid,m.cmid,m.appname,m.order_id,m.site_id,m.column_id,n.name as groupname
				FROM " . DB_PREFIX . $tableName ." m 
				LEFT JOIN ".DB_PREFIX."message_node n 
				ON m.groupid = n.id ".$condition;
		$res = $this->db->query_first($sql);

		return $res;
	}
	/*
	 *查询留言所有回复
	 */
	public function message_reply($condition)
	{
		$sql = "SELECT r.id,r.content_reply,r.answerer,r.reply_time,r.ip,m.content,m.contentid,g.groupname FROM ".DB_PREFIX."message_reply r
		LEFT JOIN ".DB_PREFIX."message m ON m.id = r.contentid 
		LEFT JOIN ".DB_PREFIX."message_group g ON m.groupid = g.groupid 
		WHERE 1 " .$condition;

		$res = $this->db->query($sql);
		while($info = $this->db->fetch_array($res))
		{
			$info['reply_time'] = date('Y-m-d H:i:s',$info['reply_time']);
			$return['info'][] = $info;
		}
		return $return;
	}
	/**
	 *根据发布id获取发布内容的具体内容
	 *
	 * @param $id int 内容在发布系统中id
	 * @param $type int 标识是查询栏目还是发布库中的信息
	 * @return $res array 被评论内容信息
	 */
	public function get_publish_content($id,$type = '')
	{
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$host = $this->settings['App_publishcontent']['host'];
		$dir = $this->settings['App_publishcontent']['dir'];

		include_once(ROOT_PATH.'lib/class/curl.class.php');
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		if($type)
		{
			$file_name = 'column.php';
			$curl->addRequestData('id',$id);
		}
		else
		{
			$file_name = 'content.php';
			$curl->addRequestData('a','get_content');
			$curl->addRequestData('content_id',$id);
		}
		$res = $curl->request($file_name);
		if($res)
		{
			$res = $res[0];
			if ($type)
			{
				//评论对象为栏目名称
				$this->input['content_title'] = $res['name'];
			}
			else
			{
				$this->input['site_id'] 		= $res['site_id'];
				$this->input['column_id'] 		= $res['column_id'];
				$this->input['content_id'] 		= $res['content_fromid'];
				$this->input['contentid'] 		= $res['content_fromid'];
				$this->input['app_uniqueid'] 	= $res['bundle_id'];
				$this->input['mod_uniqueid'] 	= $res['module_id'];
				$this->input['content_title'] 	= $res['title'];
				$this->input['content_url']		= $res['content_url'];
			}
		}
	}
	/**
	 *根据标识和内容id获取发布系统中content_id
	 *
	 * @param $con array 内容信息
	 * @return $ret array 文章信息
	 */
	public function get_content_by_other($con)
	{
		$host = $this->settings['App_publishcontent']['host'];
		$dir = $this->settings['App_publishcontent']['dir'];

		include_once(ROOT_PATH.'lib/class/curl.class.php');
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','get_content_by_other');
		if(!empty($con))
		{
			foreach ($con as $k => $v)
			{
				$curl->addRequestData($k,$v);
			}
		}

		$res = $curl->request('content.php');

		return $res;
	}

	//根据内容id获取发布id
	public function get_id_by_cid($con)
	{
		$host = $this->settings['App_publishcontent']['host'];
		$dir = $this->settings['App_publishcontent']['dir'];

		include_once(ROOT_PATH.'lib/class/curl.class.php');
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','get_id_by_cid');
		if(!empty($con))
		{
			foreach ($con as $k => $v)
			{
				$curl->addRequestData($k,$v);
			}
		}
		$res = $curl->request('content.php');
		return $res;
	}

	/*
	 *取出总的留言记录数
	 */
	public function count($condition)
	{
		$tableName = $this->confirm_tablename();
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX . $tableName . ' m WHERE 1'.$condition;
		echo json_encode($this->db->query_first($sql));
	}

	/*
	 *取出总的留言记录数
	 */
	public function return_count($condition,$tableName='')
	{
		if(empty($tableName))
		{
			$tableName = $this->confirm_tablename();
		}
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX . $tableName . ' m WHERE 1'.$condition;
		return $this->db->query_first($sql);
	}

	//检查评论表名
	public function check_table_name($year,$flag='')
	{
		if ($this->settings['fixmessagetab'])
		{
			return  $this->settings['fixmessagetab'];
		}
		if(!empty($year) && $year > 2013)
		{
			$tableName = 'message_'.$year;
			if($flag)
			{
				//初始化评论记录表
				$sql = "SELECT count(*) as total FROM ".DB_PREFIX."comment_point ";
				$re = $this->db->query_first($sql);
				if(!$re['total'])
				{
					$this->init_comment_point();
				}

				$sql = "CREATE TABLE IF NOT EXISTS " . DB_PREFIX . $tableName . " LIKE " . DB_PREFIX ."message";
				$this->db->query($sql);

				$sql = "SELECT table_name FROM ".DB_PREFIX."comment_point WHERE table_name = '".$tableName."'";
				$res = $this->db->query_first($sql);
				if(!$res['table_name'])
				{
					//插入分表索引表
					$brief = $year.'年';
					$sql = "INSERT INTO ".DB_PREFIX."comment_point SET table_name = '".$tableName."',brief = '".$brief."'";
					$this->db->query($sql);
				}
			}
		}
		else
		{
			$tableName = "message";
		}
		return $tableName;
	}

	//确定评论表名
	public function confirm_tablename()
	{
		//后台检索时确定表名，支持跨年
		if($this->input['start_time'])
		{
			$year = substr($this->input['start_time'], 0,4);
			if($year == '2013')
			{
				$sql = "SELECT count(*) as total FROM ".DB_PREFIX."comment_point ";
				$re = $this->db->query_first($sql);
				if(!$re['total'])
				{
					$this->init_comment_point();
				}
			}
			$tableName = $this->check_table_name($year);
				
		}
		if($this->input['comment_year'])
		{
			//$year = date('Y',TIMENOW) - 1;
			//$tableName = $this->check_table_name($year);
			$sql = "SELECT table_name FROM ".DB_PREFIX."comment_point WHERE id = ".intval($this->input['comment_year']);
			$res = $this->db->query_first($sql);
			if($res['table_name'])
			{
				$tableName = $res['table_name'];
			}
		}

		if($this->input['tablename'])
		{
			$tableName = $this->input['tablename'];
		}
		if($tableName)
		{
			return $tableName;
		}

		//评论类型，0发布库评论，1栏目评论,2非发布库应用评论
		$com_type =  $this->input['cmid'] ? 0 : 1;

		//cmid不存在，且标志不为栏目则为非发布库评论
		if($com_type && $this->input['app_uniqueid'] != 'column')
		{
			$com_type = 2;
				
		}
		//非发布库的，用内容id查询
		if($this->input['content_id'] && !$this->input['cid'])
		{
			$this->input['cid'] = $this->input['content_id'];
		}

		$cmid = $com_type ? $this->input['cid'] : $this->input['cmid'];
		if($cmid)
		{
			$sql = 'SELECT create_time,table_name FROM ' . DB_PREFIX . "comment_index WHERE  cmid='{$cmid}' AND com_type = '{$com_type}'";
				
			//如果为非发布库应用内容评论，需要sort_id来确定评论入库的创建时间
			if($com_type == 2)
			{
				//需要得到分类,调用get_sort_id方法
				$sort_id = $this->get_sort_id($this->input['app_uniqueid'], $this->input['mod_uniqueid']);

				if($sort_id)
				{
					$sql .= " AND sort_id = '{$sort_id}'";
				}
			}
				
			$res = $this->db->query_first($sql);
			if($res)
			{
				if($res['table_name'])
				{
					$tableName = $res['table_name'];
				}
				elseif ($res['create_time'])
				{
					$year = date('Y',$res['create_time']);
					$tableName = $this->check_table_name($year);
				}
				return $tableName;
			}
		}

		$year = date('Y',TIMENOW);
		$tableName = $this->check_table_name($year);

		return $tableName;
	}
	/**
	 * 查询应用，模块，栏目名称，创建评论分类
	 * @param string $contentid  栏目id
	 */
	function curl_app_mod($file_name,$app_uniqueid,$mod_uniqueid='',$contentid='')
	{
		if(!$file_name || !$app_uniqueid)
		{
			return false;
		}

		if($app_uniqueid == 'column' && !$mod_uniqueid)
		{
			return '栏目';
		}
		if($app_uniqueid == 'column' && $contentid)
		{
			$file_name = 'column.php';
			$host = $this->settings['App_publishcontent']['host'];
			$dir = $this->settings['App_publishcontent']['dir'];
		}
		else
		{
			$host = $this->settings['App_auth']['host'];
			$dir = $this->settings['App_auth']['dir'];
		}

		include_once(ROOT_PATH.'lib/class/curl.class.php');
		$curl = new curl($host,$dir);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a','show');
		if($app_uniqueid == 'column' && $contentid)
		{
			$curl->addRequestData('id',$contentid);
		}
		else
		{
			$curl->addRequestData('app_uniqueid',$app_uniqueid);
			if($mod_uniqueid)
			{
				$curl->addRequestData('mod_uniqueid',$mod_uniqueid);
			}
			$curl->addRequestData('fields','name');
		}
		$info = $curl->request($file_name);
		if($info)
		{
			return $info[0]['name'];
		}
	}
	/**
	 *
	 * 查询节点id,不存在直接创建
	 * @param string $node_name 节点名称
	 * @param int $fid 节点父id
	 */
	function get_comment_node($node_name,$fid)
	{
		$sql = "SELECT id FROM ".DB_PREFIX."message_node WHERE name = '".$node_name."' AND fid = ".$fid;
		$res = $this->db->query_first($sql);
		if(!$res)
		{
			$host = $this->settings['App_message']['host'];
			$dir = $this->settings['App_message']['dir'].'admin/';
			$curl = new curl($host,$dir);
			$curl->setSubmitType('post');
			$curl->initPostData();
			$curl->addRequestData('a','create');
				
			$curl->addRequestData('fid',$fid);
			$curl->addRequestData('name',$node_name);
				
			$fid = $curl->request('message_sort_update.php');
			if($fid)
			{
				$fid = $fid[0]['id'];
			}
		}
		else
		{
			$fid = $res['id'];
		}
		return $fid;
	}
	/**
	 * 根据标识获取分类id
	 * @param $app_uniqueid string 应用标识
	 * @param $mod_uniqueid string 模块标识
	 * @param $column_id int 栏目id,不需要筛选栏目的时候可不传
	 * return $sort_id 分类id
	 */
	function get_sort_id($app_uniqueid,$mod_uniqueid,$column_id='')
	{
		if(!$app_uniqueid || !$mod_uniqueid)
		{
			return 0;
		}
		//查询应用名称
		$app_name = $this->curl_app_mod('applications.php', $app_uniqueid);
		if($app_name)
		{
			//查询应用名称是否存在分类表
			$fid = $this->get_comment_node($app_name, 0);
			if($fid)
			{
				//查询模块名称
				$mod_name = $this->curl_app_mod('modules.php', $app_uniqueid,$mod_uniqueid,$column_id);
				if($mod_name)
				{
					$sort_id = $this->get_comment_node($mod_name, $fid);
				}
				$sort_id = $sort_id ? $sort_id : $fid;
			}
		}
		$sort_id = $sort_id ? $sort_id : 0;
		return $sort_id;
	}

	/**
	 * 评论投票
	 * Enter description here ...
	 * @param unknown_type $data 评论Id和用户id
	 * @param unknown_type $field 顶或踩
	 */
	function comment_vote($data,$field='useful')
	{
		$id 		= $data['id'];
		$ip			= $data['ip'];
		$user_id 	= $data['user_id'];

		$type = 1;
		if($field != 'useful')
		{
			$field = 'yawp';
			$type = -1;
		}


		$tableName = $this->confirm_tablename();
		if(!$tableName)
		{
			return array('status' => -1, 'text' => '数据异常,请稍后再试!');
		}


		//查询用户是否投票过该评论,根据表名，用户id，评论Id,投票类型type(1顶,-1踩)
		$sql = 'SELECT id FROM ' . DB_PREFIX . 'feedback WHERE user_id=' . $user_id . ' AND com_id='.$id.' AND tab_name="'.$tableName.'" AND type = '.$type;
		if($this->db->query_first($sql))
		{
			return array('status' => -2,'text' => '您已点赞!');
		}

		//更新点赞数
		$up_sql = 'UPDATE ' . DB_PREFIX . $tableName . ' SET ' . $field . '=' . $field . '+1 WHERE id=' . $id;
		$this->db->query($up_sql);

		//记录用户点赞记录
		$sql = "INSERT INTO ".DB_PREFIX."feedback SET com_id=".$id.",user_id=" . $user_id . ",tab_name='".$tableName."',ip='".$ip."',type=".$type;
		$this->db->query($sql);

		//返回点赞数
		$sql = "SELECT " . $field . " FROM " . DB_PREFIX . $tableName . " WHERE id=" . $id;
		$res = $this->db->query_first($sql);
		if(!empty($res))
		{
			$res['status'] = 1;
		}
		else 
		{
			$res = array();
		}
		return $res;
	}


	//创建评论设置缓存
	public function build_comment_set_cache()
	{
		//查询各应用系统配置
		$sql = "SELECT * FROM ".DB_PREFIX."app_settings WHERE content_id=0";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$arr[$r['var_name']] = unserialize($r['value']);
		}
		if($arr && count($arr))
		{
			if(!is_dir(MESSAGE_SET_CACHE_DIR))
			{
				hg_mkdir(MESSAGE_SET_CACHE_DIR);
			}
			if(file_exists('../cache/comment_set_cache.php'))
			{
				unlink('../cache/comment_set_cache.php');
			}
			@file_put_contents(MESSAGE_SET_CACHE_DIR .'comment_set_cache.php', "<?php\r\n");
			foreach ($arr as $k => $v)
			{
				@file_put_contents(MESSAGE_SET_CACHE_DIR .'comment_set_cache.php', "\r\n\$gGlobalConfig['" . $k . "'] = ".var_export($v,true).";\n",FILE_APPEND);
			}
			@file_put_contents(MESSAGE_SET_CACHE_DIR .'comment_set_cache.php', "\n?>",FILE_APPEND);
		}
	}


	/**
	 * 初始化评论分表记录表
	 * Enter description here ...
	 */
	public function init_comment_point()
	{
		$data = array(
		0 => array(
    			'table_name' => 'message',
    			'brief'		 => '2013年',
		),
		1 => array(
    			'table_name' => 'message_2014',
    			'brief'		 => '2014年',
		),
		);
		if($data)
		{
			foreach ($data as $key => $val)
			{
				$sql = 'INSERT INTO '.DB_PREFIX . 'comment_point SET ';
				foreach($val as $k=>$v)
				{
					$sql .= '`'.$k . '`="' . $v . '",';
				}
				$sql = rtrim($sql,',');
				$this->db->query($sql);
			}
		}
	}
	

}
?>