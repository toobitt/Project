<?php 
/***************************************************************************

* $Id: vote.class.php 17934 2013-02-26 01:52:15Z repheal $

***************************************************************************/
define('MOD_UNIQUEID','question');//模块标识
class vote extends InitFrm
{
	private $mMaterial;
	public function __construct()
	{
		parent::__construct();
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();

	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$data_limit)
	{
		$sql = "SELECT v.*, qn.name AS n_name FROM " . DB_PREFIX . "vote v LEFT JOIN " . DB_PREFIX . "question_node qn ON v.node_id=qn.id ";
		$sql .= " WHERE 1 " . $condition . " ORDER BY v.order_id DESC " . $data_limit;
		$q = $this->db->query($sql);
		$vote_info = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['logo_info'] = unserialize($row['logo_info']);
			
			$row['end_time_flag'] = ($row['end_time'] < TIMENOW) ? 0 : 1;
			
			$row['appuniqueid'] = APP_UNIQUEID;
			
			if ($row['logo_info'])
			{
				$row['vote_img'] = hg_material_link($row['logo_info']['host'], $row['logo_info']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename']);
				$row['vote_img_small'] = hg_material_link($row['logo_info']['host'], $row['logo_info']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename'],'40x30/');
			}

			$vote_info[$row['id']] = $row;
		}
		$vote_ids = @array_keys($vote_info);
		$question_info = $this->getQuestion($vote_ids);
		$info = array();
		if ($question_info)
		{
			foreach ($vote_info AS $k => $v)
			{
				if ($question_info[$k])
				{
					$info[$k] = @array_merge($vote_info[$k],$question_info[$k]);
				}
				else 
				{
					$info[$k] = $vote_info[$k];
				}
			}
		}
		
		return $info;
	}
	
	public function getQuestion($vote_ids)
	{
		if (empty($vote_ids))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE vote_id IN(" . implode(',', $vote_ids) . ") ORDER BY id ASC";
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['pictures_info'] = unserialize($row['pictures_info']);
			$info[$row['vote_id']]['questions'][$row['id']] = $row;
		}
		return $info;
	}
	public function detail($id)
	{
	//	$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN(' . $id .')';
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "vote " . $condition;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('vote' , 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['start_time'] = date('Y-m-d H:i:s' , $row['start_time']);
			$row['end_time'] = date('Y-m-d H:i:s' , $row['end_time']);
			
			$row['logo_info'] = unserialize($row['logo_info']);
			$row['logo'] = $row['logo_info']['filename'];
			if ($row['logo_info'])
			{
				$row['vote_img'] = hg_material_link($row['logo_info']['host'], $row['logo_info']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename'], '30x30/');
			}
			
			$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE vote_id IN (" . $id . ") ORDER BY id ASC";
			$q = $this->db->query($sql);
			$questions = $questions_ids = array();
			while ($r = $this->db->fetch_array($q))
			{
				$r['pictures_info'] = unserialize($r['pictures_info']);
				if ($r['pictures_info'])
				{
					$r['question_img'] = hg_material_link($r['pictures_info']['host'], $r['pictures_info']['dir'], $r['pictures_info']['filepath'], $r['pictures_info']['filename'], '30x30/');
				}
				$questions[$r['id']] = $r;
				$questions_ids[] = $r['id'];
			}
			if ($questions)
			{
				if ($questions_ids)
				{
					$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id IN (" . implode(',', $questions_ids) . ") ORDER BY id ASC";
					$q = $this->db->query($sql);
				
					while ($r = $this->db->fetch_array($q))
					{
						if (!$r['is_other'])
						{
							$r['pictures_info'] = unserialize($r['pictures_info']);
							if ($r['pictures_info'])
							{
								$r['option_img'] = hg_material_link($r['pictures_info']['host'], $r['pictures_info']['dir'], $r['pictures_info']['filepath'], $r['pictures_info']['filename'], '30x30/');
							}
							$questions[$r['vote_question_id']]['options'][] = $r;
						}
						else 
						{
							$questions[$r['vote_question_id']]['other_options'][] = $r;
						}
						
					}
				}
				foreach ($questions AS $k => $v)
				{
					if ($v['options'])
					{
						$questions[$k]['vote_total'] = "";
						foreach ($v['options'] AS $vv)
						{
							$questions[$k]['vote_total'] = $vv['single_total'] + $questions[$k]['vote_total'];
						}
					}
					
					if ($v['other_options'])
					{
						$questions[$k]['other_vote_total'] = "";
						foreach ($v['other_options'] AS $vv)
						{
							$questions[$k]['other_vote_total'] = $vv['single_total'] + $questions[$k]['other_vote_total'];
						}
					}
					$questions[$k]['question_total'] = $questions[$k]['vote_total'] + $questions[$k]['other_vote_total'];
				}
				
				foreach ($questions AS $v)
				{
					$row['questions'][] = $v;
				}
			}
			
			$row['pubstatus'] = $row['state'];
			$row['status'] = $row['state'] ? 2 : 0;
			return $row;
		}
	}
	
	public function create()
	{
		$q_title = $this->input['q_title'];
		
		$option_title = array();
		for ($i = 0; $i < count($q_title); $i++)
		{
			$option_title[$i] = $this->input['option_title_' . $i];
		}
		
		$data = array(
			'title' => trim(urldecode($this->input['title'])),
			'describes' => trim(urldecode($this->input['describes'])),
			'start_time' => strtotime(urldecode($this->input['start_time'])),
			'end_time' => strtotime(urldecode($this->input['end_time'])),
			'ip_limit_time' => intval($this->input['ip_limit_time']),
			'userid_limit_time' => intval($this->input['userid_limit_time']),
			'is_ip' => $this->input['is_ip'],
			'is_userid' => $this->input['is_userid'],
			'is_verify_code' => $this->input['is_verify_code'],
		//	'group_id' => intval($this->input['group_id']),
			'node_id' => intval($this->input['node_id']),
			'state' => intval($this->input['state']),
			'is_logo' => intval($this->input['is_logo']),
			'admin_id' => intval($this->user['user_id']),
			'admin_name' => urldecode($this->user['user_name']),
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'appid' => $this->user['appid'],
			'appname' => $this->user['display_name'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
			'is_uesrname' => $this->input['is_uesrname'],
			'is_sex' => $this->input['is_sex'],
			'is_moblie' => $this->input['is_moblie'],
			'is_id_card' => $this->input['is_id_card'],
			'is_other_info' => $this->input['is_other_info']
		);
		$sql = "INSERT INTO " . DB_PREFIX . "vote SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
	
		if ($_FILES['files']['tmp_name'])
		{
			$file_v['Filedata'] = $_FILES['files'];
			
			$material_v = $this->mMaterial->addMaterial($file_v, $data['id']);
			
			$logo_info = array();
			if (!empty($material_v))
			{
				$logo_info['id'] = $material_v['id'];
				$logo_info['type'] = $material_v['type'];
				$logo_info['filepath'] = $material_v['filepath'];
				$logo_info['name'] = $material_v['name'];
				$logo_info['filename'] = $material_v['filename'];
				$logo_info['url'] = $material_v['url'];
			}
		}
		
		$sql = "UPDATE " . DB_PREFIX . "vote SET order_id=" . $data['id'] . ", logo_info='" . serialize($logo_info) . "' WHERE id=" . $data['id'];
		$this->db->query($sql);
		
		//创建投票
		if ($data['id'])
		{
			foreach ($this->input['q_title'] AS $k => $v)
			{
				$option_num = count($option_title[$k]);
			
				if ($this->input['option_type'][$k] == 1)
				{
					$max_option = 1;
					$min_option = 1;
				}
				else 
				{
					if ($this->input['max_option'][$k] > $option_num)
					{
						$max_option = 0;
					}
					else 
					{
						if ($this->input['max_option'][$k] < 2 && $this->input['max_option'][$k] != 0)
						{
							$max_option = 2;
						}
						else 
						{
							$max_option = intval($this->input['max_option'][$k]);
						}
					}

					if ($this->input['min_option'][$k] > $option_num)
					{
						$min_option = 2;
					}
					else
					{
						$min_option = intval($this->input['min_option'][$k]);
					}
				}
				
				$question_data = array(
				'vote_id' => $data['id'],
				'title' => trim(urldecode($v)),
				'option_num' => $option_num,
				'option_type' => intval($this->input['option_type'][$k]),
				'min_option' => $min_option,
				'max_option' => $max_option,
				'is_other' => intval($this->input['is_other'][$k]),
				'admin_id' => intval($this->user['user_id']),
				'admin_name' => urldecode($this->user['user_name']),
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'ip' => hg_getip()
				);
				$sql = "INSERT INTO " . DB_PREFIX . "vote_question SET ";
				$space_q = "";
				foreach ($question_data AS $key => $value)
				{
					$sql .= $space_q . $key . "=" . "'" . $value . "'";
					$space_q = ",";
				}
				$this->db->query($sql);
				
				$question_data['id'] = $this->db->insert_id();
				
				//创建投票图片
				if ($_FILES['question_files_'.$k]['tmp_name'])
				{
					$file_q['Filedata'] = $_FILES['question_files_'.$k];
					
					$material_q = $this->mMaterial->addMaterial($file_q, $question_data['id']);
					
					$pictures_info = array();
					if (!empty($material_q))
					{
						$pictures_info['id'] = $material_q['id'];
						$pictures_info['type'] = $material_q['type'];
						$pictures_info['filepath'] = $material_q['filepath'];
						$pictures_info['name'] = $material_q['name'];
						$pictures_info['filename'] = $material_q['filename'];
						$pictures_info['url'] = $material_q['url'];
					}
				}
				$sql = "UPDATE " . DB_PREFIX . "vote_question SET order_id = " . $question_data['id'] . " , pictures_info = '" . serialize($pictures_info) . "' WHERE id=" . $question_data['id'];	
				$this->db->query($sql);
				//创建投票选项
				if ($question_data['id'])
				{
					foreach ($option_title[$k] AS $kk => $vv)
					{
						$sql = "INSERT INTO " . DB_PREFIX . "question_option SET vote_question_id=" . $question_data['id'] . ",title='" . urldecode($vv) . "',state=1";
						$this->db->query($sql);
						
						$option_ids = $this->db->insert_id();
						
						//更新投票选项图片

						if ($_FILES['option_files_'.$k.'_'.$kk]['tmp_name'])
						{
							$file_o['Filedata'] = $_FILES['option_files_'.$k.'_'.$kk];
							
							$material_o = $this->mMaterial->addMaterial($file_o, $option_ids);
							
							$pictures_info_o = array();
							if (!empty($material_o))
							{
								$pictures_info_o['id'] = $material_o['id'];
								$pictures_info_o['type'] = $material_o['type'];
								$pictures_info_o['filepath'] = $material_o['filepath'];
								$pictures_info_o['name'] = $material_o['name'];
								$pictures_info_o['filename'] = $material_o['filename'];
								$pictures_info_o['url'] = $material_o['url'];
							}
							$sql = "UPDATE " . DB_PREFIX . "question_option SET pictures_info='" . serialize($pictures_info_o) . "' WHERE id=" . $option_ids;	
							$this->db->query($sql);
						}
					
					}
				}
			}
			
		}
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function update()
	{
		$data = array(
			'title' => trim(urldecode($this->input['title'])),
			'describes' => trim(urldecode($this->input['describes'])),
			'start_time' => strtotime(urldecode($this->input['start_time'])),
			'end_time' => strtotime(urldecode($this->input['end_time'])),
			'ip_limit_time' => intval($this->input['ip_limit_time']),
			'userid_limit_time' => intval($this->input['userid_limit_time']),
			'is_ip' => $this->input['is_ip'],
			'is_userid' => $this->input['is_userid'],
			'is_verify_code' => $this->input['is_verify_code'],
		//	'group_id' => intval($this->input['group_id']),
			'node_id' => intval($this->input['node_id']),
			'is_logo' => 1,
		//	'admin_id' => intval($this->user['user_id']),
		//	'admin_name' => urldecode($this->user['user_name']),
			'update_time' => TIMENOW,
			'is_uesrname' => $this->input['is_uesrname'],
			'is_sex' => $this->input['is_sex'],
			'is_moblie' => $this->input['is_moblie'],
			'is_id_card' => $this->input['is_id_card'],
			'is_other_info' => $this->input['is_other_info']
		);
		
		$sql = "UPDATE " . DB_PREFIX . "vote SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id=" . $this->input['id'];
		$this->db->query($sql);
	
		$data['id'] = $this->input['id'];
		
		//更新问卷图片
		
		if ($_FILES['files']['tmp_name'])
		{
			$file_v['Filedata'] = $_FILES['files'];
			
			$material_v = $this->mMaterial->addMaterial($file_v, intval($this->input['id']));
			
			$logo_info = array();
			if (!empty($material_v))
			{
				$logo_info['id'] = $material_v['id'];
				$logo_info['type'] = $material_v['type'];
				$logo_info['filepath'] = $material_v['filepath'];
				$logo_info['name'] = $material_v['name'];
				$logo_info['filename'] = $material_v['filename'];
				$logo_info['url'] = $material_v['url'];
			}
			$sql = "UPDATE " . DB_PREFIX . "vote SET logo_info='" . serialize($logo_info) . "' WHERE id=" . intval($this->input['id']);
			$this->db->query($sql);
		}
		
		
		//更新投票
		$q_title = $this->input['q_title'];
		$option_title = $option_id = array();
		for ($i = 0; $i < count($q_title); $i++)
		{
			$option_title[$i] = $this->input['option_title_' . $i];
			$option_id[$i] = $this->input['option_id_' . $i];
		}
		
		if ($data['id'])
		{
			foreach ($this->input['q_title'] AS $k => $v)
			{
				$option_num = count($option_title[$k]);
					
				if ($this->input['option_type'][$k] == 1)
				{
					$max_option = 1;
					$min_option = 1;
				}
				else 
				{
					if ($this->input['max_option'][$k] > $option_num)
					{
						$max_option = 0;
					}
					else 
					{
						if ($this->input['max_option'][$k] < 2 && $this->input['max_option'][$k] != 0)
						{
							$max_option = 2;
						}
						else 
						{
							$max_option = intval($this->input['max_option'][$k]);
						}
					}

					if ($this->input['min_option'][$k] > $option_num)
					{
						$min_option = 2;
					}
					else
					{
						$min_option = intval($this->input['min_option'][$k]);
					}
				}
				
				if ($this->input['q_id'][$k])
				{
					$question_data = array(
						'title' => trim(urldecode($v)),
						'option_num' => $option_num,
						'option_type' => intval($this->input['option_type'][$k]),
						'min_option' => $min_option,
						'max_option' => $max_option,
						'is_other' => intval($this->input['is_other'][$k]),
						'admin_id' => intval($this->user['user_id']),
						'admin_name' => urldecode($this->user['user_name']),
						'update_time' => TIMENOW
					);
					
					$sql = "UPDATE " . DB_PREFIX . "vote_question SET ";
					$space_q = "";
					foreach ($question_data AS $key => $value)
					{
						$sql .= $space_q . $key . "=" . "'" . $value . "'";
						$space_q = ",";
					}
					$sql .= " WHERE id=" . $this->input['q_id'][$k];
					$this->db->query($sql);
					
					$question_id = $this->input['q_id'][$k];
					
				}
				else 
				{
					$question_data = array(
					'vote_id' => $data['id'],
					'title' => trim(urldecode($v)),
					'option_num' => $option_num,
					'option_type' => intval($this->input['option_type'][$k]),
					'max_option' => $max_option,
					'is_other' => intval($this->input['is_other'][$k]),
					'admin_id' => intval($this->user['user_id']),
					'admin_name' => urldecode($this->user['user_name']),
					'create_time' => TIMENOW,
					'update_time' => TIMENOW,
					'ip' => hg_getip()
					);
					$sql = "INSERT INTO " . DB_PREFIX . "vote_question SET ";
					$space_q = "";
					foreach ($question_data AS $key => $value)
					{
						$sql .= $space_q . $key . "=" . "'" . $value . "'";
						$space_q = ",";
					}
					$this->db->query($sql);
					
					$question_data['id'] = $this->db->insert_id();
					
					$sql = "UPDATE " . DB_PREFIX . "vote_question SET order_id=" . $question_data['id'] . " WHERE id=" . $question_data['id'];
					$this->db->query($sql);

					$question_id = $question_data['id'];
				
				}
				
				//更新投票图片

				if ($_FILES['question_files_'.$k]['tmp_name'])
				{
					$file_q['Filedata'] = $_FILES['question_files_'.$k];
					
					$material_q = $this->mMaterial->addMaterial($file_q, $question_id);
					
					$pictures_info = array();
					if (!empty($material_q))
					{
						$pictures_info['id'] = $material_q['id'];
						$pictures_info['type'] = $material_q['type'];
						$pictures_info['filepath'] = $material_q['filepath'];
						$pictures_info['name'] = $material_q['name'];
						$pictures_info['filename'] = $material_q['filename'];
						$pictures_info['url'] = $material_q['url'];
					}
				
					$sql = "UPDATE " . DB_PREFIX . "vote_question SET pictures_info = '" . serialize($pictures_info) . "' WHERE id=" . $question_id;	
					$this->db->query($sql);
				}
				
				if ($question_id)
				{
					foreach ($option_title[$k] AS $kk => $vv)
					{
						if ($option_id[$k][$kk])
						{
							$sql = "UPDATE " . DB_PREFIX . "question_option SET title='" . trim(urldecode($vv)) . "' WHERE id=" . $option_id[$k][$kk];
							$this->db->query($sql);
							
							$option_ids = $option_id[$k][$kk];
						}
						else
						{
							$sql = "INSERT INTO " . DB_PREFIX . "question_option SET vote_question_id=" . $question_id . ",title='" . trim(urldecode($vv)) . "',state=1";
							$this->db->query($sql);
							
							$option_ids = $this->db->insert_id();
						}
					
						//更新投票选项图片
					
						if ($_FILES['option_files_'.$k.'_'.$kk]['tmp_name'])
						{
							$file_o['Filedata'] = $_FILES['option_files_'.$k.'_'.$kk];
							
							$material_o = $this->mMaterial->addMaterial($file_o, $option_ids);
							
							$pictures_info_o = array();
							if (!empty($material_o))
							{
								$pictures_info_o['id'] = $material_o['id'];
								$pictures_info_o['type'] = $material_o['type'];
								$pictures_info_o['filepath'] = $material_o['filepath'];
								$pictures_info_o['name'] = $material_o['name'];
								$pictures_info_o['filename'] = $material_o['filename'];
								$pictures_info_o['url'] = $material_o['url'];
							}
							$sql = "UPDATE " . DB_PREFIX . "question_option SET pictures_info='" . serialize($pictures_info_o) . "' WHERE id=" . $option_ids;	
							$this->db->query($sql);
						}
					
					}
				}
				
			}
		}
		//
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function delete()
	{
		
		
		$sql = "select * from " . DB_PREFIX . "vote where id in(" . urldecode($this->input['id']).")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['title'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['vote'] = $row;
		}
		if($data2)
		{
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				
			}	
			//放入回收站结束
		}
		
		if($res['sucess'])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "vote WHERE id IN (" . urldecode($this->input['id']) . ")";
			$this->db->query($sql);
			if (urldecode($this->input['id'])) 
			{
				return urldecode($this->input['id']);
			}
		}
		return false;
	}
	
	//彻底删除
	public function delete_comp()
	{
		if(empty($this->input['cid']))
		{
			return false;
		}
		$cid = urldecode($this->input['cid']);
		
		$sql = "SELECT id FROM " . DB_PREFIX . "vote_question WHERE vote_id IN (" . $cid . ") ORDER BY id ASC";
		$q = $this->db->query($sql);
		$vote_question_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$vote_question_id[$row['id']]= $row['id'];
		}
		
		if ($vote_question_id)
		{
			$sql = "DELETE FROM " . DB_PREFIX . "vote_question WHERE id IN (" . implode(',', $vote_question_id) . ")";
			$this->db->query($sql);
			
			$sql = "DELETE FROM " . DB_PREFIX . "question_option WHERE vote_question_id IN (" . implode(',', $vote_question_id) . ")";
			$this->db->query($sql);
		}
		return $cid;
	}
	//还原
	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		//还原投票问题表
		if(!empty($content['vote']))
		{
			$sql = "insert into " . DB_PREFIX . "vote set ";
			$space='';
			foreach($content['vote'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}
		return $data;
	}*/	
	public function updateOtherTitle()
	{
		$other_title = $this->input['other_title'];
		$hiddenFlag = $this->input['hiddenFlag'];
		if ($other_title)
		{
			foreach ($other_title AS $id => $title)
			{
				if ($hiddenFlag[$id] == 1)
				{
					$sql = "UPDATE " . DB_PREFIX . "question_option SET title = '" . urldecode($title) . "', flag=1, update_time = " . TIMENOW . " WHERE id = " . intval($id);
					$this->db->query($sql);
				}
			}
		}
		
		return $other_title;
	}
}

?>