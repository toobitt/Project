<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once('../lib/functions.php');
require_once(CUR_CONF_PATH . 'core/message.dat.php');
define('MOD_UNIQUEID','message');//模块标识
class MessageContentUpdate extends adminUpdateBase
{
	private $curl;
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{
		//评论内容
		if(!$this->input['content'])
		{
			$this->errorOutput('请输入内容');
		}
		//评论类型  0是对发布库内容评论 1是对栏目评论
		$com_type = intval($this->input['com_type']);

		$cmid = intval($this->input['cmid']);
		if(!$cmid)
		{
			$this->errorOutput('请输入内容id');
		}

		$mes = new Message();

		//针对发布库内容评论
		if(!$com_type)
		{
			//被评论内容发布库中的id
			$mes->get_publish_content($cmid);
		}
		else if($com_type == 1)//针对栏目
		{
			$this->input['content_id'] = $cmid;
			$cmid = 0;
			$this->input['app_uniqueid'] = 'column';
			$this->input['mod_uniqueid'] = 'column';
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		/*if($this->input['groupid'] && $this->user['group_type'] >= MAX_ADMIN_TYPE)
		 {
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'message_node WHERE id IN('.$this->input['groupid'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
			$nodes['nodes']['message_sort'][$row['id']] = $row['parents'];
			}
			}
			else
			{
			$nodes = array('nodes'=>array('message_sort'=>array(0=>0)));
			}
			$this->verify_content_prms($nodes);*/
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点

		/********创建数据上限判断**********/
		$create_data_limit = $this->user['prms']['default_setting']['create_data_limit'];
		if($create_data_limit)
		{
			$sql = "SELECT count(*) FROM ".DB_PREFIX."message WHERE userid = ".$this->user['user_id'];
			$count = $this->db->query_first($sql);
			if($count['count']>$create_data_limit)
			{
				$this->errorOutput('您只能添加'.$create_data_limit.'条数据');
			}
		}

		$data = array(
			'title'			=> $this->input['title'],
			'author'		=> $this->input['username'],
			'username'		=> $this->user['user_name'],
			'userid' 		=> $this->user['user_id'],
			'member_id' 	=> $this->input['member_id'],
			'org_id' 		=> $this->user['org_id'],
			'content'		=> trim($this->input['content']),
			'pub_time'		=> TIMENOW,
			'ip'			=> hg_getip(),
			'groupid'		=> $this->input['groupid'],
			'site_id' 		=> $this->input['site_id'],
			'column_id' 	=> $this->input['column_id'],
			'contentid'		=> $this->input['content_id'],
			'cmid' 			=> $cmid,
			'app_uniqueid'	=> $this->input['app_uniqueid'],
			'mod_uniqueid'	=> $this->input['mod_uniqueid'],
			'state' 		=> $this->get_status_setting('create'),
			'content_title' => $this->input['content_title'],
			'content_url'	=> $this->input['content_url'],
		);
		$res = $mes->add_message($data);

		if($res)
		{
			//更新内容评论计数，除了栏目评论
			if($res['app_uniqueid'] && $res['app_uniqueid'] != 'column' && $res['state'] == 1)
			{
				$this->update_comment_count($res['id'], 'audit', $res['tableame']);
			}
			$this->addLogs('添加评论', '', $res,'添加评论'.$res['id']);
			$this->addItem($res);
		}
		$this->output();
	}

	public function delete()
	{
		$ids = trim(urldecode($this->input['id']));

		$condition = '';
		//添加ip检索
		if($this->input['ip'])
		{
			$condition .= " AND ip LIKE '%" . $this->input['ip'] . "%'";
		}
		else if(!$ids)
		{
			$this->errorOutput(NOID);
		}

		$tableName = $this->input['tablename'];
		if(!$tableName)
		{
			$this->errorOutput(NOTABLE);
		}

		
		$sql = "SELECT * FROM " . DB_PREFIX . $tableName ." WHERE 1 ";
		//放入回收箱开始
		if($condition)
		{
			$sql .= $condition;
		}
		elseif ($ids)
		{
			$sql .= " AND id IN (" . $ids . ")";
		}
		
		$q = $this->db->query($sql);

		$backCreditsMemberId = array();
		$comm_id = array();
		while($row = $this->db->fetch_array($q))
		{
			$comm_id[] = $row['id'];
			$group_ids[] = $row['groupid'];

			$conInfor[] = $row;

			if(!$row['title'])
			{
				$row['title'] = '无标题';
			}
			if($row['member_type']&&$row['member_id']>0)
			{
				$backCreditsMemberId[] = $row['member_id'];//需要扣除积分的会员
			}
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['title'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content'][$tableName] = $row;
		}
		if($comm_id)
		{
			$ids = implode(',', $comm_id);
		}
		
		if(!$ids)
		{
			$this->errorOutput(NOID);
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		/*if($group_ids)
		 {
			$gids = implode(',', $group_ids);
			}

			if($gids && $this->user['group_type'] >= MAX_ADMIN_TYPE)
			{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'message_node WHERE id IN('.$gids.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
			$data['nodes']['message_sort'][$row['id']] = $row['parents'];
			}
			}
			else
			{
			$data = array('nodes'=>array('message_sort'=>array('0'=>'0')));
			}

			$this->verify_content_prms($data);*/

		//能否修改他人数据
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['userid'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		#####结束

		/*$sql = "SELECT * FROM " . DB_PREFIX . "message_reply WHERE contentid IN (" . $ids . ")";
		 $q = $this->db->query($sql);
		 $vote_question_id = array();
		 while ($row = $this->db->fetch_array($q))
		 {
			$data2[$row['contentid']]['content']['message_reply'][] = $row;
			}*/
		if($this->settings['App_recycle'] && !empty($data2))
		{
			include_once(ROOT_PATH . 'lib/class/recycle.class.php');
			$this->recycle = new recycle();
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				$result = $res['sucess'];
				$is_open = $res['is_open'];
			}
			//放入回收站结束

			if (!$result)
			{
				$this->errorOutput('放入回收站失败，数据不完整');
			}
			
			//删除评论
			$sql = 'DELETE FROM '.DB_PREFIX . $tableName . ' WHERE id in('.$ids.')';
			$this->db->query($sql);
			
		}
		else
		{
			//删除评论
			$sql = 'DELETE FROM '.DB_PREFIX . $tableName . ' WHERE id in('.$ids.')';
			$this->db->query($sql);
		}

		//更新各内容评论计数
		$this->update_comment_count($ids,'back',$tableName,'',$q);
		$this->backcredits($backCreditsMemberId);

		/***********添加日志***********/
		$this->addLogs('删除评论', $data2, '','删除评论'.$ids);

		$this->addItem('success');
		$this->output();
	}
	public function backcredits($backCreditsMemberId)
	{		
		/*******************调用积分规则,START*****************/
		if($this->settings['App_members'])
		{
			include (ROOT_PATH.'lib/class/members.class.php');
			$Members = new members();
			/***审核增加积分**/
			if(is_array($backCreditsMemberId))
			{
				foreach ($backCreditsMemberId as $val)
				{
					$Members->Initoperation();//初始化
					$Members->operation = APP_UNIQUEID.'_admin_del';
					$Members->get_credit_rules($val,APP_UNIQUEID);
				}
			}
		}

		/********************调用积分规则,END*****************/
	}
	/**
	 * 删除内容所有评论
	 * @param string $cmid 内容发布库id
	 *
	 */
	public function delete_content_comment()
	{
		$cmid 			= $this->input['cmid'];
		$content_id 	= $this->input['content_id'];
		$app_uniqueid 	= $this->input['app_uniqueid'];
		$mod_uniqueid 	= $this->input['mod_uniqueid'];

		if(!$cmid && !$content_id && !$app_uniqueid && !$mod_uniqueid)
		{
			$this->errorOutput(PARALACK);
		}
		$cmid_arr 		= $cmid ? explode(',', $cmid) : array();
		$content_id_arr = $content_id ? explode(',', $content_id) :array();

		$obj = new Message();
		if(is_array($cmid_arr) && !empty($cmid_arr))
		{
			foreach ($cmid_arr as $v)
			{
				$this->input['cmid'] = $v;
				$table_name = $obj->confirm_tablename();

				if(!$table_name || !$v)
				{
					continue;
				}

				$sql = '';
				$sql = "DELETE FROM " . DB_PREFIX . $table_name . " WHERE cmid = " . $v;
				$this->db->query($sql);
			}
		}
		else if (is_array($content_id_arr) && !empty($content_id_arr))
		{
			if(!$app_uniqueid || !$mod_uniqueid)
			{
				$this->errorOutput(NOUNIQUEID);
			}
			foreach ($content_id_arr as $v)
			{
				$this->input['content_id'] = $v;
				$table_name = $obj->confirm_tablename();

				if(!$table_name || !$v)
				{
					continue;
				}
				$sql = "DELETE FROM " . DB_PREFIX . $table_name . " WHERE app_uniqueid = '" . $app_uniqueid . "' AND mod_uniqueid = '" . $mod_uniqueid . "' AND contentid = " . $v;
				$this->db->query($sql);
			}
		}

		$this->addItem('success');
		$this->output();
	}
	//编辑留言
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$content = trim($this->input['content']);
		if(!$content)
		{
			$this->errorOutput(NOCONTENT);
		}
		$tableName = $this->input['tablename'];
		if(!$tableName)
		{
			$this->errorOutput(NOTABLENAME);
		}

		//查询修改文章之前已经发布到的栏目
		$sql = "SELECT * FROM " . DB_PREFIX ."message WHERE id = " . $id;
		$q = $this->db->query_first($sql);

		//修改已审核数据
		$state = $this->user['prms']['default_setting']['update_audit_content'];
		$update_state_tag = false;
		if($q['state'] == 1 && $state)
		{
			$update_state_tag = true;
			$update_state = $this->get_status_setting('update_audit');
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		/*if($this->user['group_type'] >= MAX_ADMIN_TYPE)
		 {
			$_sort_ids = '';
			$_sort_ids = $q['groupid'];

			if($this->input['groupid'])
			{
			$_sort_ids  = $_sort_ids ? $_sort_ids . ',' . $this->input['groupid'] : $this->input['groupid'];
			}
			if($_sort_ids)
			{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'message_node WHERE id IN('.$_sort_ids.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
			$data['nodes']['message_sort'][$row['id']] = $row['parents'];
			}
			}
			else
			{
			$data = array('nodes'=>array('message_sort'=>array('0'=>'0')));
			}
			}*/
		#####节点权限

		#####修改他人数据
		$data['id'] = $id;
		$data['org_id'] = $q['org_id'];
		$data['user_id'] = $q['userid'];
		$data['_action'] = 'manage';
		$this->verify_content_prms($data);
		#####结束

		$data = array();
		$data = array(
			'title'=>$this->input['title'],
		//'org_id' => $this->user['org_id'],
		//'userid' => $this->user['user_id'],
		   'username'=>$this->input['username'],
			'content'=>addslashes($this->input['content']),
			'pub_time'=>TIMENOW,
			'groupid'=>intval($this->input['groupid']),
		);
		if($update_state_tag)
		{
			$data['state'] = $update_state;
		}
		$obj = new Message();
		$where = ' id = ' . $id;
		$res = $obj->update($data, $tableName, $where);

		if($res)
		{
			//添加日志
			$this->addLogs('更新评论', $q, $data,'更新评论'.$id);
		}
		$this->addItem($res);
		$this->output();
	}

	/**
	 * 审核，打回
	 *
	 * @param $audit int 操作状态  1审核  2打回
	 * @return $arr array id=>ids,status=>回调状态标识
	 */
	public function audit()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput(NOID);
		}

		$tableName = $this->input['tablename'];
		if(!$tableName)
		{
			$this->errorOutput(NOTABLENAME);
		}

		$audit = $this->input['audit'];
		$arr_id = explode(',',$ids);

		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		/*$sql = "SELECT groupid FROM ".DB_PREFIX . $tableName . " WHERE id IN (".$ids.")";
		 $q = $this->db->query($sql);
		 while ($r = $this->db->fetch_array($q))
		 {
			$group_ids[] = $r['groupid'];
			}

			if($group_ids)
			{
			$gids = implode(',', $group_ids);
			}

			if($gids && $this->user['group_type'] >= MAX_ADMIN_TYPE)
			{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'message_node WHERE id IN('.$gids.')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
			$data['nodes']['message_sort'][$row['id']] = $row['parents'];
			}
			}
			else
			{
			$data = array('nodes'=>array('message_sort'=>array(0=>0)));
			}

			$this->verify_content_prms($data);*/
		#####结束

		$op = '';
		$obj = new Message();
		if($audit == 1)//审核
		{
			$op = '审核评论';
			//排除被审核评论中已审核评论
			$sql = 'SELECT id,is_credits,member_id,member_type,app_uniqueid,mod_uniqueid,column_id,contentid FROM '.DB_PREFIX . $tableName . ' WHERE state IN (0,2,3) AND id IN ('.$ids.')';
			$q = $this->db->query($sql);
			$credit_rules_uid=array();//需增加积分的会员id
			while ($r = $this->db->fetch_array($q))
			{
				$wsh_ids[] = $r['id'];
				if($r['member_id']&&$r['is_credits']>0&&$r['member_type'])
				{
					$credit_rules_uid[$r['id']]=$r;
				}
			}
			//如果批量审核的评论中存在待审核或者打回的评论，进行更新操作
			if($wsh_ids)
			{
				/*******************调用积分规则,给已审核评论增加积分START*****************/

				if($this->settings['App_members'])
				{
					include (ROOT_PATH.'lib/class/members.class.php');
					$Members = new members();

					/***审核增加积分**/
					if($credit_rules_uid&&is_array($credit_rules_uid))
					{
						foreach ($credit_rules_uid as $key => $v)
						{
							$Members->Initoperation();//初始化
							$Members->Setoperation(APP_UNIQUEID,'','','extra');
							$Members->get_credit_rules($v['member_id'],$v['app_uniqueid'],$v['mod_uniqueid'],$v['column_id'],$v['contentid']);
							$this->db->query("UPDATE " . DB_PREFIX . "{$tableName} SET is_credits=0 WHERE id=".$key);//更新获得积分字段
						}
					}
				}

				/********************调用积分规则,给已审核评论增加积分END*****************/
				$ids = implode(',', $wsh_ids);
				$where = " id IN (".$ids.")";
				$res = $obj->update('state = 1', $tableName, $where);
				if($res)
				{
					//更新各内容评论计数
					$this->update_comment_count($ids,'audit',$tableName);
				}
			}
			$arr = array('id' => $arr_id,'status' => 1);
		}
		else if($audit == 2) //打回
		{
			$op = '打回评论';
			//排除被打回评论中已打回评论
			$sql = 'SELECT id,state FROM '.DB_PREFIX . $tableName . ' WHERE state IN (0,1) AND id IN ('.$ids.')';
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				if($r['state'] == 1)
				{
					$back_arr[] = $r['id']; 
				}
				$wdh_ids[] = $r['id'];
			}
			//如果批量打回的评论中存在待审核或者已审核的评论，进行更新操作
			if($wdh_ids)
			{
				$ids = implode(',', $wdh_ids);
				$where = " id IN (".$ids.")";
				$res = $obj->update('state = 2', $tableName, $where);

				if($res)
				{
					$back_str = implode(',', $back_arr);
					//更新各内容评论计数
					$this->update_comment_count($back_str,'back',$tableName);
				}
			}
			$arr = array('id' => $arr_id,'status' => 2);
		}

		//添加日志
		$this->addLogs($op,'','',$op . '+' . $ids);

		$this->addItem($arr);
		$this->output();
	}
	//排序
	function sort()
	{
		$tableName = $this->input['table_name'];
		if(!$tableName)
		{
			$this->errorOutput(NOTABLENAME);
		}

		$ids = explode(',',urldecode($this->input['content_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));

		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . $tableName . " SET order_id = '" . $order_ids[$k] . "' WHERE id = '" . $v . "'";
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();
	}
	//回复留言
	function reply_comment()
	{
		$fid = intval($this->input['id']);
		if(!$fid)
		{
			$this->errorOutput('没有回复对象');
		}
		$content = urldecode($this->input['reply_content']);
		if(!$content)
		{
			$this->errorOutput("请输入回复内容");
		}
		$tablename = $this->input['tablename'];
		if(!$tablename)
		{
			$this->errorOutput('tablename不存在');
		}
		$data = array(
			'fid'				=> $fid,
			'title'				=> $this->input['title'],
			'content'			=> $content,
			'member_id'			=> $this->input['member_id'],
			'pub_time'			=> TIMENOW,
			'username'			=> $this->input['member_id'] ? $this->input['member_id'] : $this->user['user_name'],
			'userid' 			=> $this->user['user_id'],
			'org_id'			=> $this->user['org_id'],
			'cmid'				=> $this->input['cmid'],
			'contentid'			=> $this->input['contentid'],
			'app_uniqueid'		=> $this->input['app_uniqueid'],
			'mod_uniqueid'		=> $this->input['mod_uniqueid'],
			'site_id' 			=> $this->input['site_id'],
			'column_id' 		=> $this->input['column_id'],
			'content_title'		=> $this->input['content_title'],
		);

		$mes = new Message();
		$res = $mes->reply_comment($fid,$data,$tablename);
		$this->addItem($res);

		$this->output();
	}
	/**
	 * 更新内容评论数
	 *
	 * @param string $id  评论ids
	 * @param string $type 操作类型
	 * @param string $tableName 表名
	 * @param string $cond 自动审核时传递的时间限制条件
	 */
	private function update_comment_count($id,$type,$tableName,$cond = '',$query = '')
	{
		if(!$tableName)
		{
			return false;
		}

		if(!$id && !$cond)
		{
			return false;
		}

		//审核，打回批量操作中有的状态可能不一致，加条件筛选
		/*if($type == 'back')
		 {
			$con = ' AND state = 2 ';
			}
			else if($type == 'audit')
			{
			$con = ' AND state = 1 ';
			}
			else //删除的时候不需要条件判断
			{
			$con = '';
			$type = 'back';
			}*/
		$sql = "SELECT app_uniqueid,mod_uniqueid,contentid,member_id FROM ".DB_PREFIX . $tableName . " WHERE 1 ";
		//获得审核通过的评论的应用，模块标识，和内容id

		if($id)
		{
			$sql .= " AND id IN (".$id.")";
		}
		elseif($cond)
		{
			$sql .= $cond ;
		}

		/*if($con)
		 {
			$sql .= $con;
			}*/
		if($query)
		{
			mysql_data_seek($query,0);
		}
		$q = $query ? $query : $this->db->query($sql);
		$num = 1;
		$update_tag = false;
		//统计各应用下各内容的评论数
		while ($r = $this->db->fetch_array($q))
		{
			if($query && $r['state'] != 1)
			{
				continue;
			}
			if(!$r['app_uniqueid'] || !$r['mod_uniqueid'] || $r['app_uniqueid'] == 'column')
			{
				continue;
			}

			if($app_info[$r['app_uniqueid']][$r['mod_uniqueid']][$r['contentid']])
			{
				$app_info[$r['app_uniqueid']][$r['mod_uniqueid']][$r['contentid']] = ++$num;
			}
			else
			{
				if($this->settings['App_' . $r['app_uniqueid']])
				{
					$update_tag = true;
					$app_info[$r['app_uniqueid']][$r['mod_uniqueid']]['path']['host'] = $this->settings['App_' . $r['app_uniqueid']]['host'];
					$app_info[$r['app_uniqueid']][$r['mod_uniqueid']]['path']['dir'] = $this->settings['App_' . $r['app_uniqueid']]['dir'].'admin/';
				}
				else
				{
					continue;
				}

				$app_info[$r['app_uniqueid']][$r['mod_uniqueid']][$r['contentid']] = 1;
			}
			//收集会员id
			if($r['member_id'])
			{
				if(!$member_ids[$r['member_id']])
				{
					$member_ids[$r['member_id']] = 1;
				}
				else
				{
					$member_ids[$r['member_id']] += 1;
				}
			}
		}
		
		//curl请求各应用接口，更新内容评论计数
		if(is_array($app_info) && count($app_info) && $update_tag)
		{
			include_once(ROOT_PATH.'lib/class/curl.class.php');
			foreach ($app_info as $app_tag => $mod_info)
			{
				foreach ($mod_info as $mod_tag => $info)
				{
					$path = array();
					$path = $info['path'];
					unset($info['path']);

					$host = '';
					$dir = '';
					$filename = '';

					$host = $path['host'];
					$dir = $path['dir'];
					$filename = $app_tag;
					//视频标识与文件名不同
					if($app_tag == 'livmedia')
					{
						$filename = 'vod';
					}
					elseif($app_tag == 'cheapbuy')
					{
						$filename = 'product';
					}
					foreach ($info as $contentid => $comment_count)
					{
						$curl = new curl($host,$dir);
						$curl->setSubmitType('post');
						$curl->initPostData();
						$curl->addRequestData('a','update_comment_count');

						$curl->addRequestData('id',$contentid);
						$curl->addRequestData('comment_count',$comment_count);
						$curl->addRequestData('type',$type);
						$curl->request($filename.'_update.php');
					}
				}
			}
		}
		//更新每个会员的评论计数
		if($member_ids && $this->settings['App_members'] && $type)
		{
			$path['host'] = $this->settings['App_members']['host'];
			$path['dir'] = $this->settings['App_members']['dir'];
			if($type == 'back')
		 	{
		 		$math = 2;
			}
			elseif ($type == 'audit')
			{
				$math = 1;
			}
			if($path)
			{
				include_once(ROOT_PATH.'lib/class/curl.class.php');
				$curl = new curl($path['host'],$path['dir']);
				foreach((array)$member_ids as $member_id => $totalsum)
				{
					$curl->setSubmitType('post');
					$curl->initPostData();
					$curl->addRequestData('a','create');
					$curl->addRequestData('mark','mymessage');
					$curl->addRequestData('totalsum',$totalsum);
					$curl->addRequestData('summath',$math);
					$curl->addRequestData('member_id',$member_id);
					$curl->request('member_my_update.php');
				}
			}
		}
	}

	//计划任务审核
	public function planAudit()
	{
		$start_time = intval($this->input['start_time']);
		$end_time = intval($this->input['end_time']);
		$status = intval($this->input['status']);
		$state = '';
		if ($start_time && $end_time && $status)
		{
			switch ($status)
			{
				case 1:$state = 0;break;
				case 2:$state = 1;break;
				case 3:$state = 2;break;
			}

			$sql = 'SELECT update_time FROM ' . DB_PREFIX . 'comment_index WHERE update_time>'.$start_time.' AND update_time<'.$end_time;
			$q = $this->db->query($sql);

			$year = array();
			while ($r = $this->db->fetch_array($q))
			{
				$year[date('Y',$r['update_time'])] = 1;
			}

			//只有审核，0->1更新,0->2不做处理
			if($state == 1)
			{
				$type = 'audit';
			}

			if(!empty($year))
			{
				foreach ($year as $k => $v)
				{
					$tableName = 'message';
					if($k != '2013')
					{
						$tableName = 'message_'.$k;
					}
					if($state == 1)
					{
						/*******************调用积分规则,给已审核评论增加积分START*****************/

						if($this->settings['App_members'])
						{
							include (ROOT_PATH.'lib/class/members.class.php');
							$Members = new members();
							$sql = 'SELECT id,is_credits,member_id,app_uniqueid,mod_uniqueid,column_id,contentid FROM '.DB_PREFIX . $tableName . ' WHERE member_type=1 AND is_credits>0 AND state = 0 AND pub_time>'.$start_time.' AND pub_time<'.$end_time;
							$q = $this->db->query($sql);
							$credit_rules_uid=array();//需增加积分的会员id
							while ($r = $this->db->fetch_array($q))
							{
								if($r['member_id'])
								{
									$credit_rules_uid[$r['id']]=$r;
								}
							}
							/***审核增加积分**/
							if($credit_rules_uid&&is_array($credit_rules_uid))
							{
								foreach ($credit_rules_uid as $key => $val)
								{
									$Members->Initoperation();//初始化
									$Members->Setoperation(APP_UNIQUEID,'','','extra');
									$Members->get_credit_rules($val['member_id'],$v['app_uniqueid'],$v['mod_uniqueid'],$v['column_id'],$v['contentid']);
									$this->db->query("UPDATE " . DB_PREFIX . "{$tableName} SET is_credits=0 WHERE id=".$key);//更新获得积分字段
								}
							}
						}

						/********************调用积分规则,给已审核评论增加积分END*****************/

					}

					//更新计数条件
					$con = ' AND pub_time>'.$start_time.' AND pub_time<'.$end_time . " AND state = 0";
			
					$sql = 'UPDATE '.DB_PREFIX . $tableName . ' SET state = '.$state.'
							WHERE 1 ' . $con;
					$this->db->query($sql);
					if($type)
					{
						$this->update_comment_count('', $type, $tableName, $con);
					}
				}
			}
		}
		$this->addItem(true);
		$this->output();
	}

	function publish()
	{
	}

	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new MessageContentUpdate();
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