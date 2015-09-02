<?php 
/***************************************************************************

* $Id: vote_question.class.php 6446 2012-04-18 07:21:05Z lijiaying $

***************************************************************************/
define('MOD_UNIQUEID','vote');//模块标识
class voteQuestion extends BaseFrm
{
	private $mMaterial;
	public function __construct()
	{
		parent::__construct();
		include_once ROOT_PATH . 'lib/class/material.class.php';
		include_once ROOT_PATH . 'lib/class/livmedia.class.php';
		$this->mMaterial = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$data_limit,$type)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question ";
		$sql .= " WHERE 1 " . $condition . " ORDER BY order_id DESC " . $data_limit;
		$q = $this->db->query($sql);

		$vote = array();
		while($row = $this->db->fetch_array($q))
		{
			if ($row['end_time'])
			{
				$row['end_time_flag'] = ($row['end_time'] < TIMENOW) ? 0 : 1;
			}
			
			$row['appuniqueid'] = APP_UNIQUEID;
			$row['pictures_info'] = unserialize($row['pictures_info']);
			if ($row['pictures_info'])
			{
				$row['question_img'] = hg_material_link($row['pictures_info']['host'], $row['pictures_info']['dir'], $row['pictures_info']['filepath'], $row['pictures_info']['filename']);
				$row['question_img_small'] = hg_material_link($row['pictures_info']['host'], $row['pictures_info']['dir'], $row['pictures_info']['filepath'], $row['pictures_info']['filename'],'40x30/');
			}
			$vote[$row['id']] = $row;
		}

		$vote_question_ids = @array_keys($vote);

		if ($vote_question_ids)
		{
			$option_info = $this->getQuestionOption($vote_question_ids,$type);
		}
		
		$info = array();
		if ($option_info)
		{
			foreach ($vote AS $k => $v)
			{
				if ($option_info[$k])
				{
					$info[$k] = array_merge($vote[$k],$option_info[$k]);
				}
				else
				{
					$info[$k] = $vote[$k];
				}
			}
		}
		
		return $info;
	}
	
	public function news_refer_show($condition,$data_limit,$type)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question ";
		$sql .= " WHERE 1 " . $condition . " ORDER BY order_id DESC " . $data_limit;
		$q = $this->db->query($sql);
		$vote = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['pictures_info'] = unserialize($row['pictures_info']);
			$vote[$row['id']] = $row;
		}
		
		return $vote;
	}
	
	public function show2($condition,$device_token,$data_limit,$type)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question ";
		$sql .= " WHERE 1 " . $condition . " ORDER BY order_id DESC " . $data_limit;
		$q = $this->db->query($sql);
		$single = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['title'] = htmlspecialchars_decode($row['title'],ENT_QUOTES);
			$row['brief'] = htmlspecialchars_decode($row['brief'],ENT_QUOTES);
			$row['appuniqueid'] = APP_UNIQUEID;
			$row['pictures_info'] = unserialize($row['pictures_info']);
			$row['create_time'] = $row['create_time'] ? date('Y-m-d H:i:s', $row['create_time']) : 0;
			$row['update_time'] = $row['update_time'] ? date('Y-m-d H:i', $row['update_time']) : 0;
			$row['audit_time'] = $row['audit_time'] ? date('Y-m-d H:i', $row['audit_time']) : 0;
			//
			$_tmp_status = hg_get_vote_status_text($row['start_time'], $row['end_time']);
			$row['status_text'] = $_tmp_status['status_text'];
			$row['status_flag'] = $_tmp_status['status_flag'];
			//
			if(!$row['is_open'])
			{
				$row['status_flag'] = 'close';
				$row['status_text'] = '已关闭';
			}
			if(!$this->input['is_showdata'])
			{
				unset($row['total']);
				unset($row['ini_person']);
				unset($row['ini_total']);
			}
			$single[$row['id']] = $row;
		}
		$single_question_ids = array_keys($single);
		if ($single_question_ids)
		{
			$option_info = $this->getQuestionOption($single_question_ids,$type);
		}
		if($device_token)
		{
			$vote_ids = $single_question_ids ? implode(',',$single_question_ids) : 0;
			$sql = "SELECT qp.vote_question_id,qr.option_ids FROM ".DB_PREFIX."question_person qp LEFT JOIN ".DB_PREFIX."question_person_info qr ON qp.pid = qr.id WHERE qp.vote_question_id in( " . $vote_ids .") AND qp.device_token = '".$device_token."'";
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$options_id[$r['vote_question_id']][] = $r['option_ids'];
			}
		}			
		
		$info = array();
		if ($option_info)
		{
			foreach ($single AS $k => $v)
			{
				$single[$k]['deviced'] = count($options_id[$k])>0 ? 1: 0;
				$single[$k]['votefor'] = $options_id[$k] ? $options_id[$k] : array();
				if ($option_info[$k])
				{
					$info[$k] = array_merge($single[$k],$option_info[$k]);
				}
				else
				{
					$info[$k] = $single[$k];
				}
			}
		}
		return $info;
	}
	
	public function detail($cond = '',$offset = 0,$count = 100)
	{
		$id = urldecode($this->input['id']);
		$condition = $id ? ' AND id IN(' . $id .')' : ' ORDER BY id DESC LIMIT 1';
		$sql = "SELECT id, option_num,is_other,ini_total,total FROM " . DB_PREFIX . "vote_question WHERE 1 " . $cond. $condition;
		$vote = $this->db->query_first($sql);
		if(!$vote)
		{
			return false;
		}
		$sql = "select sum(single_total) AS  total from " . DB_PREFIX . "question_option where vote_question_id=" . intval($id);
		$sumtotal= $this->db->query_first($sql);
		$vote['total'] = $sumtotal['total'];
		$total =  $vote['is_other'] ? $vote['option_num']+1 : $vote['option_num']; //选项总个数（不含其他选项）
		$vote_total = $vote['total']; //投票总数(含其他总数)
		$ini_num = $vote['ini_total']; //投票初始总数

		if($offset < 1)  //第一页加载的时候需要将投票的所有数据都加载完，选项翻页之后只返回选项数据
		{		
			$id = $id ? $id : $vote['id'];
			$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE 1 " . $cond. ' AND id IN(' . $id .')';		
			$row = $this->db->query_first($sql);
			$this->setXmlNode('vote_question' , 'info');
			
			if(is_array($row) && $row)
			{
				$row['title'] = htmlspecialchars_decode($row['title']);
				$row['brief'] = htmlspecialchars_decode($row['brief']);
                $row['describes'] = htmlspecialchars_decode($row['describes']);
				$_tmp_status = hg_get_vote_status_text($row['start_time'], $row['end_time']);
				$row['status_text'] = $_tmp_status['status_text'];
				$row['status_flag'] = $_tmp_status['status_flag'];

				$row['other_info'] = array();			
				$row['create_time'] = $row['create_time'] ? date('Y-m-d H:i:s' , $row['create_time']) : 0;
				$row['update_time'] = $row['update_time'] ? date('Y-m-d H:i:s' , $row['update_time']) : 0;
				$row['start_time'] = $row['start_time'] ? date('Y-m-d H:i:s' , $row['start_time']) : 0;
				$row['end_time'] = $row['end_time'] ? date('Y-m-d H:i:s' , $row['end_time']) : 0;
				$row['audit_time'] = $row['audit_time'] ? date('Y-m-d H:i:s' , $row['audit_time']) : 0;
				
				$row['pictures_info'] = unserialize($row['pictures_info']);
				if ($row['pictures_info'])
				{
					$row['question_img'] = hg_fetchimgurl($row['pictures_info'],30,30);
				}
				$row['pictures'] ? $option_pic[] = $row['pictures'] : false;
				$row['publishcontent_id'] ? $option_quote[] = $row['publishcontent_id'] : false;
				$row['vod_id'] ? $option_video[] = $row['vod_id'] : false;
				unset($row['column_id']);
				unset($row['column_url']);
				
				$row['options'] = $this->get_options($id,$total,$offset,$count,$vote['is_other']);
				if(!$row['options'])
				{
					return false;
				}
				if($row['pictures'])
				{
					$option_pictures = $this->get_image($row['pictures']);
					$vote_pictures_id = explode(',',$row['pictures']);
					foreach ($vote_pictures_id as $vv)
					{
						$option_pictures[$vv] ? $row['other_info']['pictures'][] = $option_pictures[$vv]['pic_arr'] : false;  //投票获取多图
					}
				}
				
				if($row['publishcontent_id'])
				{
					$quotes_options = $this->get_quote($row['publishcontent_id']);
					$vote_public_id = explode(',',$row['publishcontent_id']);
					foreach ($vote_public_id as $vv)
					{
						if($quotes_options[$vv])
						{
							$row['other_info']['publishcontents'][] = $quotes_options[$vv];  //投票获取发布库内容
						}
					}
				}
				if($row['vod_id'])
				{
					$option_videos = $this->get_vod_info_by_id($row['vod_id']);
					$vote_vod_id = explode(',',$row['vod_id']);
					foreach ($vote_vod_id as $vv)
					{
						if($option_videos[$vv])
						{
							!$option_videos[$vv]['is_audio'] ? $row['other_info']['videos'][] = $option_videos[$vv] :false;  //投票获取视频音频
							$option_videos[$vv]['is_audio'] ? $row['other_info']['audios'][] = $option_videos[$vv] :false;  //投票获取视频音频
						}
					}
				}
				
				if ($row['pictures_info'])
				{
					$row['pictures'] = hg_fetchimgurl($row['pictures_info'],30,30);
				}
				$row['more_info'] = $row['more_info'] ? unserialize($row['more_info']) : array();
				
				//参与人数
				$sql = "SELECT * FROM " . DB_PREFIX . "question_count WHERE vote_question_id = " . $id;
				$q = $this->db->query($sql);
				$row['app_id'] = array();
				$row['preson_count'] = '';
				while ($r = $this->db->fetch_array($q))
				{
					$row['app_id'][$r['app_id']]['counts'] = $r['counts'];
					$row['app_id'][$r['app_id']]['app_name'] = $r['app_name'];
				}
				$sql = "SELECT count(*) as counts FROM " . DB_PREFIX . "question_person_info WHERE vote_question_id = " . $id;
				$query = $this->db->query_first($sql);
				$row['preson_count'] = $query['counts'];//查出实际总人数
				$row['person_total'] = $row['preson_count'] + $row['ini_person']; //实际总人数+初始人数
				$row['person_total'] = $row['person_total'] > 0 ? $row['person_total'] : 0;
				
				$row['pubstatus'] = $row['status'];
				$row['option_title'] = $row['options'];
			}
		}
		else
		{
			$row['option_title'] = $row['options'] = array();
			$options = $this->get_options($id,$total,$offset,$count,$vote['is_other']);
			if($options)
			{
				$row['option_title'] = $row['options'] = $options;
			}
		}
		$row['is_next_page'] = ($offset+$count) >= $total ? 0 : 1;//如果偏移量大于总数，则没有下一页;如果偏移量小于总数，则显示下一页
		$row['question_total'] = $vote_total;
		$row['question_total_ini'] = $ini_num + $row['question_total'];
		$row['question_total_ini'] = $row['question_total_ini'] >0 ? $row['question_total_ini'] : 0;
		$row['option_num'] = $total;
		$row['vote_total'] = $vote_total;
		$row['ini_num'] = $ini_num;
		if(!$this->input['is_showdata']) //去除真实数据的输出
		{
			unset($row['total']);
			unset($row['ini_total']);
			unset($row['ini_person']);
			unset($row['preson_count']);
			unset($row['question_total']);
			unset($row['vote_total']);
			unset($row['ini_num']);
		}
		return $row;
	}
	
	public function create()
	{
		$option_title = $this->input['option_title_0'];
		$option_describes = $this->input['option_describes'];

		$option_num = count(@array_filter($option_title));
		
		$option_ini_num = $this->input['ini_num'];
		$more_info = $this->input['more_info'] ? $this->input['more_info'] : '';
		if ($this->input['option_type'] == 1)
		{
			$max_option = 1;
			$min_option = 1;
		}
		else 
		{
			if ($this->input['max_option'] > $option_num)
			{
				$max_option = 0;
			}
			else 
			{
				if ($this->input['max_option'] < 2 && $this->input['max_option'] != 0)
				{
					$max_option = 2;
				}
				else 
				{
					$max_option = intval($this->input['max_option']);
				}
			}

			if ($this->input['min_option'] > $option_num)
			{
				$min_option = 2;
			}
			else
			{
				$min_option = intval($this->input['min_option']);
			}
		}
		
		$data = array(
			'title' => trim($this->input['title']),
			'describes' => trim($this->input['describes']),
			'start_time' => strtotime($this->input['start_time']),
			'end_time' => strtotime($this->input['end_time']),
			'ip_limit_time' => intval($this->input['ip_limit_time']),
			'userid_limit_time' => intval($this->input['userid_limit_time']),
			'is_ip' => $this->input['is_ip'],
			'is_userid' => $this->input['is_userid'],
			'is_verify_code' => $this->input['is_verify_code'],
			'option_num' => $option_num,
			'option_type' => intval($this->input['option_type']),
			'min_option' => $min_option,
			'max_option' => $max_option,
			'is_other' => intval($this->input['is_other']),
			'admin_id' => intval($this->user['user_id']),
			'admin_name' => $this->user['user_name'],
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'appid' => $this->user['appid'],
			'appname' => $this->user['display_name'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
			'more_info' => serialize($more_info),
			'is_user_login' => intval($this->input['is_user_login']),
			'source_type' => intval($this->input['source_type']),
			'node_id' => intval($this->input['node_id']),
			'weight' => intval($this->input['weight']),
		);
		$sql = "INSERT INTO " . DB_PREFIX . "vote_question SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		//创建投票图片
		if ($_FILES['question_files_0']['tmp_name'])
		{
			$file_q['Filedata'] = $_FILES['question_files_0'];
				
			$material_q = $this->mMaterial->addMaterial($file_q, $data['id']);
			
			$pictures_info = array();
			if (!empty($material_q))
			{
				$pictures_info['id'] = $material_q['id'];
				$pictures_info['type'] = $material_q['type'];
				$pictures_info['host'] = $material_q['host'];
				$pictures_info['dir'] = $material_q['dir'];
				$pictures_info['filepath'] = $material_q['filepath'];
				$pictures_info['name'] = $material_q['name'];
				$pictures_info['filename'] = $material_q['filename'];
				$pictures_info['url'] = $material_q['url'];
			}
		}
		
		$sql = "UPDATE " . DB_PREFIX . "vote_question SET order_id = " . $data['id'] . " , pictures_info = '" . serialize($pictures_info) . "' WHERE id=" . $data['id'];	
		$this->db->query($sql);
		
		if ($data['id'])
		{
			if ($option_title)
			{
				foreach ($option_title AS $k => $v)
				{
					if ($v)
					{
						$sql = "INSERT INTO " . DB_PREFIX . "question_option SET vote_question_id=" . $data['id'] . ",title='" . urldecode($v) . "' ,describes='". urldecode($option_describes[$k]) ."', state=1, ini_num=" . intval($option_ini_num[$k]);
						$this->db->query($sql);
						
						$ret['id'] = $this->db->insert_id();
						
						//更新投票选项图片
	
						if ($_FILES['option_files_0_'.$k]['tmp_name'])
						{
							$file_o['Filedata'] = $_FILES['option_files_0_'.$k];
						
							$material_o = $this->mMaterial->addMaterial($file_o, $ret['id']);
							
							$pictures_info_o = array();
							if (!empty($material_o))
							{
								$pictures_info_o['id'] = $material_o['id'];
								$pictures_info_o['type'] = $material_o['type'];
								$pictures_info_o['host'] = $material_o['host'];
								$pictures_info_o['dir'] = $material_o['dir'];
								$pictures_info_o['filepath'] = $material_o['filepath'];
								$pictures_info_o['name'] = $material_o['name'];
								$pictures_info_o['filename'] = $material_o['filename'];
								$pictures_info_o['url'] = $material_o['url'];
							}
							$sql = "UPDATE " . DB_PREFIX . "question_option SET pictures_info='" . serialize($pictures_info_o) . "' WHERE id=" . $ret['id'];	
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
		$option_id = $this->input['option_id'];	
		$option_title = $this->input['option_title_0'];
		$option_describes = $this->input['option_describes'];
		$option_num = count(@array_filter($option_title));
		$option_ini_num = $this->input['ini_num'];
		$more_info = $this->input['more_info'] ? $this->input['more_info'] : '';
		
		$option_info = array();
		foreach ($option_title AS $k => $v)
		{
			$option_info[$k]['id'] = $option_id[$k];
			$option_info[$k]['title'] = $v;
			$option_info[$k]['describes'] = $option_describes[$k];
			$option_info[$k]['ini_num'] = $option_ini_num[$k] ? $option_ini_num[$k] : 0;
		}
	
		if ($this->input['option_type'] == 1)
		{
			$max_option = 1;
		}
		else 
		{
			if ($this->input['max_option'] > $option_num)
			{
				$max_option = 0;
			}
			else 
			{
				if ($this->input['max_option'] < 2 && $this->input['max_option'] != 0)
				{
					$max_option = 2;
				}
				else 
				{
					$max_option = intval($this->input['max_option']);
				}
			}
				
			if ($this->input['min_option'] > $option_num)
			{
				$min_option = 2;
			}
			else
			{
				$min_option = intval($this->input['min_option']);
			}
		}
		$data = array(
			'title' => trim($this->input['title']),
			'describes' => trim($this->input['describes']),
			'start_time' => strtotime($this->input['start_time']),
			'end_time' => strtotime($this->input['end_time']),
			'ip_limit_time' => intval($this->input['ip_limit_time']),
			'userid_limit_time' => intval($this->input['userid_limit_time']),
			'is_ip' => $this->input['is_ip'],
			'is_userid' => $this->input['is_userid'],
			'is_verify_code' => $this->input['is_verify_code'],
			'option_num' => $option_num,
			'option_type' => intval($this->input['option_type']),
			'min_option' => $min_option,
			'max_option' => $max_option,
			'is_other' => intval($this->input['is_other']),
		//	'admin_id' => intval($this->user['user_id']),
		//	'admin_name' => urldecode($this->user['user_name']),
			'update_time' => TIMENOW,
			'more_info' => serialize($more_info),
			'is_user_login' => intval($this->input['is_user_login']),
			'node_id' => intval($this->input['node_id']),
			'weight' => intval($this->input['weight']),
		);
		
		$sql = "UPDATE " . DB_PREFIX . "vote_question SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id=" . $this->input['id'];
		$this->db->query($sql);
		
		$data['id'] = $this->input['id'];
		
		//更新投票图片

		if ($_FILES['question_files_0']['tmp_name'])
		{
			$file_q['Filedata'] = $_FILES['question_files_0'];
			
			$material_q = $this->mMaterial->addMaterial($file_q, intval($this->input['id']));
			
			$pictures_info = array();
			if (!empty($material_q))
			{
				$pictures_info['id'] = $material_q['id'];
				$pictures_info['type'] = $material_q['type'];
				$pictures_info['host'] = $material_q['host'];
				$pictures_info['dir'] = $material_q['dir'];
				$pictures_info['filepath'] = $material_q['filepath'];
				$pictures_info['name'] = $material_q['name'];
				$pictures_info['filename'] = $material_q['filename'];
				$pictures_info['url'] = $material_q['url'];
			}
		
			$sql = "UPDATE " . DB_PREFIX . "vote_question SET pictures_info = '" . serialize($pictures_info) . "' WHERE id=" . intval($this->input['id']);	
			$this->db->query($sql);
		}
				
		if ($option_info)
		{
			foreach ($option_info AS $k => $v)
			{
				if ($v['title'])
				{
					if ($v['id'])
					{
						$sql = "UPDATE " . DB_PREFIX . "question_option SET title='" . urldecode($v['title']) . "',describes='" . urldecode($v['describes']) . "', ini_num=" . $v['ini_num'] . " WHERE vote_question_id=" . $this->input['id'];
						$sql .= " AND id=" . $v['id'];
						$this->db->query($sql);
						
						$ret['id'] = $v['id'];
					}
					else 
					{
						$sql = "INSERT INTO " . DB_PREFIX . "question_option SET vote_question_id=" . $this->input['id'] . ",title='" . urldecode($v['title']) . "', describes='" . urldecode($v['describes']) . "',state=1, ini_num=" . $v['ini_num'];
						$this->db->query($sql);
						
						$ret['id'] = $this->db->insert_id();
					}
				
				
					//更新投票选项图片
						
					if ($_FILES['option_files_0_'.$k]['tmp_name'])
					{
						$file_o['Filedata'] = $_FILES['option_files_0_'.$k];
						
						$material_o = $this->mMaterial->addMaterial($file_o, $ret['id']);
						
						$pictures_info_o = array();
						if (!empty($material_o))
						{
							$pictures_info_o['id'] = $material_o['id'];
							$pictures_info_o['type'] = $material_o['type'];
							$pictures_info_o['host'] = $material_o['host'];
							$pictures_info_o['dir'] = $material_o['dir'];
							$pictures_info_o['filepath'] = $material_o['filepath'];
							$pictures_info_o['name'] = $material_o['name'];
							$pictures_info_o['filename'] = $material_o['filename'];
							$pictures_info_o['url'] = $material_o['url'];
						}
						$sql = "UPDATE " . DB_PREFIX . "question_option SET pictures_info='" . serialize($pictures_info_o) . "' WHERE id=" . $ret['id'];	
						$this->db->query($sql);
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

	public function getQuestionOption($vote_question_ids,$type)
	{
		if (!$vote_question_ids)
		{
			return false;
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id IN(" . implode(',', $vote_question_ids) . ") ORDER BY order_id ASC,id ASC";
		$q = $this->db->query($sql);
		$return = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['title'] = htmlspecialchars_decode($r['title'],ENT_QUOTES);
			$r['pictures_info'] = unserialize($r['pictures_info']);
			$r['describes'] = unserialize($r['describes']);
			$r['describes'] = $r['describes'][0];
			$r['describes'] = htmlspecialchars_decode($r['describes'],ENT_QUOTES);	
			$r['ini_single'] = $r['ini_num'] + $r['single_total'];
			$r['ini_single'] = $r['ini_single'] > 0 ? $r['ini_single'] : 0;
			if(!$this->input['is_showdata'])
			{
				unset($r['ini_num']);
				unset($r['single_total']);
			}
			if (!$type)
			{
				if (!$r['is_other'])
				{
					$return[$r['vote_question_id']]['question_option'][$r['id']] = $r; 
				}
				else
				{
					$return[$r['vote_question_id']]['other_question_option'][$r['id']] = $r; 
				}
			}
			else
			{
				if ($r['state'])
				{
					$return[$r['vote_question_id']]['question_option'][$r['id']] = $r; 
				}
			}
		}
		return $return;
	}
		
	/**
	 *
	 * 获取引用素材的索引图 标题 引用 栏目
	 * @param int $option_id
	 */
	public function get_quote($rids)
	{
		if(!$rids)
		{
			return false;
		}	
		require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$pub = new publishcontent();
		$pubs = new publishcontent();
		$ret = $pub->get_content_by_rids($rids);
		$return = $pubs->get_pub_content_type();
		if(!$ret)
		{
			return false;
		}
		if(is_array($return))
		{
			foreach($return as $k => $v)
			{
				$bundles[$v['bundle']] = $v['name'];
			}
		}
	    if(is_array($ret))
		{
			foreach($ret as $k => $v)
			{
		        $quote[$v['rid']] = array(
		        'id'   => $v['rid'],
		        'title'=> $v['title'],
		        'brief'=> $v['brief'],
		        //'title'=> $ret['title'],
		        'bundle_id' => $v['bundle_id'],
		        'module_id' => $v['module_id'],
		        'content_url' => $v['content_url'],
		        'content_fromid' => $v['content_fromid'],
		        'img_info'    => hg_material_link($v['indexpic']['host'], $v['indexpic']['dir'], $v['indexpic']['filepath'], $v['indexpic']['filename']),
		        'pic_arr'     => $v['indexpic'],
		        'upload_type' => '引用',
		        'module_name' => $bundles[$v['bundle_id']],
		        );
			}
		}
		return $quote;
	}
	

	public function get_image($ids)
	{
		if(!$ids)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE id IN (" . $ids . ")";
		$res = $this->db->query($sql);
		while ($mat = $this->db->fetch_array($res))
		{
			$p[$mat['id']] = array(
			    'id'  =>$mat['id'],
			    //'pic' =>unserialize($r['pic']),
			    'img_info' =>hg_material_link($mat['host'], $mat['dir'], $mat['filepath'], $mat['filename']),
			    'pic_arr' => array('host'  => $mat['host'],'dir'  => $mat['dir'],'filepath'  => $mat['filepath'],'filename'  => $mat['filename']),
			    'upload_type' =>'图片',
			);
		}
		
		return $p;
	}
	
	
	public function get_vod_info_by_id($vod_id)
	{
		if(!$vod_id)
		{
			return false;
		}
		if (!$this->settings['App_livmedia'])
		{
			return false;//$this->errorOutput('NO_APP_LIVMEDIA');
		}

		$mLivMedia = new livmedia();
		
		$video = $mLivMedia->getVodInfoById($vod_id);
		if(!$video)
		{
			return false;
		}
        if(is_array($video))
        {
        	foreach ($video as $k=>$v)
        	{
        		$return[$v['id']] 	= array(
        		    'id'  			=> $v['id'],
        		    'img_info' 		=> $v['img'] ? $v['img'] : '',
        		    'is_audio' 		=> $v['is_audio'],
			        'upload_type' 	=> $v['is_audio'] ? '音频' : '视频',
        		    'title'			=> $v['title'],
        		    'url'  			=> $v['video_url'],
        		    'pic_arr' 		=> $v['img_info']['filename'] ? $v['img_info'] : '',
        		    'video_arr' 	=> array('hostwork'=>$v['hostwork'],'video_base_path'=>$v['video_base_path'],'video_path'=>$v['video_path'],'video_filename'=>$v['video_filename']),
        			'duration'		=> trim(str_replace('\'',':',$v['duration']),'"'),
        		);
        	}
        }        
		return $return;
	}
	
	public function get_options($id,$total , $offset = 0,$count = 100,$is_other = 0)
	{
		if(!$id || !$count || !$total)
		{
			return false;
		}
		$title=($this->input['search']); //添加标题搜索
		$limit = ' LIMIT '.$offset.' , '.$count;
		//$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id  = ". $id . " ORDER BY order_id ASC,id ASC";
		$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id  = ". $id . " and title like '%$title%' ORDER BY ini_num+single_total desc";
		$sql .= $limit ;
		$q = $this->db->query($sql);
		$i=1;
		while ($r = $this->db->fetch_array($q))
		{
			$r['title'] = htmlspecialchars_decode($r['title'],ENT_QUOTES);	
			$r['describes'] = unserialize($r['describes']);
			$r['describes'] = htmlspecialchars_decode($r['describes'][0],ENT_QUOTES);	 //取数组第一条描述
			$r['feedback_id'] = $r['feedback_id'];  //从反馈表单推送来的选项id
			$r['order'] = ($this->input['page'] - 1) * $count + $i;
			//合并附件的id,一次性取出
			$r['pictures'] ? $option_pic[] = $r['pictures'] : false;
			$r['publishcontent_id'] ? $option_quote[] = $r['publishcontent_id'] : false;
			$r['vod_ids'] ? $option_video[] = $r['vod_ids'] : false;
			
			$r['ini_single'] = $r['single_total'] + $r['ini_num']; //取投票数
			$r['ini_single'] = $r['ini_single'] > 0 ? $r['ini_single'] : 0;
			$r['pictures_info'] = unserialize($r['pictures_info']);
			if ($r['pictures_info']) //取选项索引图
			{
				$r['option_img'] = hg_fetchimgurl($r['pictures_info'], 30,30);
			}
			if(!$this->input['is_showdata']) //去除真实数据的输出
			{
				unset($r['single_total']);
				unset($r['ini_num']);
			}
			$options[] = $r;
			$i=++$i;
		}
		$option_count = count($options);
		if($is_other && $option_count < $count && $offset+$option_count+1 <= $total)
		{
			$other_id = defined('OTHER_OPTION_ID') ? OTHER_OPTION_ID : -1;
			$sql = 'SELECT COUNT(id) as other_count FROM '.DB_PREFIX.'question_record WHERE vote_question_id = '.$id .' AND question_option_id = '.$other_id;
			$query = $this->db->query_first($sql);
			$options[] = array(
				'id'	=> $other_id,
				'vote_question_id'	=> $id,
				'title' => defined('OTHER_OPTION_TITLE') ? OTHER_OPTION_TITLE : '其他',
				'ini_single' => intval($query['other_count']),
				'single_total' => $query['other_count'],
			);
		}

		if($options)
		{
			$picture_ids = $option_pic ? implode(',',$option_pic) : '' ;
			$quote_ids = $option_quote ? implode(',',$option_quote) : '' ;
			$video_ids = $option_video ? implode(',',$option_video) : '' ;
			
			$picture_ids ? $option_pictures = $this->get_image($picture_ids) : '';
			$quote_ids ? $quotes_options = $this->get_quote($quote_ids) : '';
			$video_ids ? $option_videos = $this->get_vod_info_by_id($video_ids) : '';
			/*foreach ($options as $k => $v)
			{
				if($v['pictures']) //获取选项图片
				{
					$pictures_id = explode(',',$v['pictures']);
				    foreach ($pictures_id as $vv)
				    {
				    	$option_pictures[$vv] ? $options[$k]['other_info']['pictures'][] = $option_pictures[$vv]['pic_arr'] :false;  //获取多图
				    }
				}
				if($v['publishcontent_id'])//获取选项发布库内容
				{
					$publishcontent_id = explode(',',$v['publishcontent_id']);
				    foreach ($publishcontent_id as $vv)
				    {
					    $quotes_options[$vv] ? $options[$k]['other_info']['publishcontents'][] = $quotes_options[$vv] : false;    //获取引用
				    }
				}
				if($v['vod_ids'])//获取选项音视频文件
				{
					$video_ids = explode(',',$v['vod_ids']);
				    foreach ($video_ids as $vv)
				    {
					    if($option_videos[$vv])
					    {
						    !$option_videos[$vv]['is_audio'] ? $options[$k]['other_info']['videos'][] = $option_videos[$vv] : false; //获取视频
						    $option_videos[$vv]['is_audio'] ? $options[$k]['other_info']['audios'][] = $option_videos[$vv] : false; //获取音频
					    }
				    }
				}
			}*/
			return $options;
		}
		return false;
	}
	
}

?>