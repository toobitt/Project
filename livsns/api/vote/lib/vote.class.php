<?php 
/***************************************************************************

* $Id: vote.class.php 31688 2013-11-20 01:05:48Z jiyuting $

***************************************************************************/
require_once(ROOT_PATH . 'lib/class/material.class.php');
include ROOT_PATH . 'lib/class/livmedia.class.php';
include ROOT_PATH . 'lib/class/verifycode.class.php';
include ROOT_PATH . 'lib/class/feedback.class.php';
class vote extends InitFrm
{
	public function __construct()
	{
		$this->material = new material();
		$this->verifycode = new verifycode();
		$this->feedback = new feedback();
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition, $offset = 0, $count = 20, $orderby = '')
	{
		$orderby = $orderby ? $orderby : " ORDER BY order_id DESC ";
		$limit 	 = " LIMIT " . $offset . "," . $count;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);

		$return = array();
		$sort = $this->get_sort_name();
		while($row = $this->db->fetch_array($q))
		{
			$entrip = create_filename($row['create_time'].$row['id'],APP_UNIQUEID);
			if(file_exists(DATA_DIR.$entrip.'/index.html'))
			{
				$row['url'] = VOTE_DOMAIN.$entrip.'/index.html';
			}
			$row['create_time'] = date('Y-m-d H:i', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i', $row['update_time']);
			if(strtotime(date('Y-m-d',TIMENOW)) == $row['end_time'])
			{
				$row['end_time_flag'] = $row['end_time'] + 86400 -1 > TIMENOW ? 1 : 0;
				$row['start_time']  = $row['start_time'] ? date('Y-m-d H:i', $row['start_time']) : 0;
				$row['end_time'] 	= $row['end_time'] ? date('Y-m-d H:i', $row['end_time']) : 0;
			}else 
			{
				$row['end_time_flag'] = $row['end_time'] && $row['end_time'] > TIMENOW ? 1 : 0;
				$row['start_time']  = $row['start_time'] ? date('Y-m-d', $row['start_time']) : 0;
				$row['end_time'] 	= $row['end_time'] ? date('Y-m-d', $row['end_time']) : 0;
			}
			$row['pictures_info'] = unserialize($row['pictures_info']);
			if($row['pictures_info'])
			{
				$row['pictures_info_url'] = hg_material_link($row['pictures_info']['host'], $row['pictures_info']['dir'], $row['pictures_info']['filepath'], $row['pictures_info']['filename'], '40x30/');
			}
			$row['more_info'] 	  = unserialize($row['more_info']);
			$row['appuniqueid']   = APP_UNIQUEID;
			
			$row['column_id'] 	  = unserialize($row['column_id']);
			$row['column_url'] 	  = unserialize($row['column_url']);
			$row['vod_pic'] 	  = unserialize($row['vod_pic']);
			$row['sort_name']     = $sort[$row['node_id']];
			
			$return[] = $row;
		}
		return $return;
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
	
	public function detail($id ,$condition = '')
	{
		$condition .=  $id ? " AND id IN (" . $id . ")" : " ORDER BY id DESC LIMIT 1";
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE 1 " . $condition;
		
		$row = $this->db->query_first($sql);
		$sort = $this->get_sort_name();
		if (!empty($row))
		{			
			$row['create_time'] = $row['create_time'] ? date('Y-m-d H:i:s', $row['create_time']) : 0;
			$row['update_time'] = $row['update_time'] ? date('Y-m-d H:i:s', $row['update_time']) : 0;
			$row['start_time'] 	= $row['start_time'] ? date('Y-m-d H:i', $row['start_time']) : 0;
			$row['end_time'] 	= $row['end_time'] ? date('Y-m-d H:i', $row['end_time']) : 0;
			$row['more_info'] 	= unserialize($row['more_info']);
			$row['pictures_info'] = unserialize($row['pictures_info']);
			$row['column_id'] 	  = unserialize($row['column_id']);
			$row['column_url'] 	  = unserialize($row['column_url']);
			$row['vod_pic'] 	  = unserialize($row['vod_pic']);
			$row['sort_id'] 	  = $row['node_id'];
			$row['sort_name']     = $sort[$row['node_id']];
			$row['column'] = $row['column_id'];
			if(is_array($row['column_id']))
			{
				$column_id = array();
				foreach($row['column_id'] AS $k => $v)
				{
					$column_id[] = $k;
					$column_name[] = $v;
				}
				$column_id = implode(',',$column_id);
				$column_name = implode(',',$column_name);
				$row['column_id'] = $column_id;
				$row['column_name'] = $column_name;
			}
			if($row['feedback_id'] && $row['is_feedback'])
			{
				$feed = $this->feedback->detail($row['feedback_id']);
				$row['feedback_title'] = $feed['title'];
			}
			
			if ($row['pub_time'])
			{
				$row['pub_time'] = date('Y-m-d H:i:s', $row['pub_time']);
			}
			
			if ($row['pictures_info'])
			{
				$row['index_img'] = hg_material_link($row['pictures_info']['host'], $row['pictures_info']['dir'], $row['pictures_info']['filepath'], $row['pictures_info']['filename']);
			}
			$row['pictures_other'] = array();
			$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id IN (" . $id . ") ORDER BY order_id ASC,id ASC";
			file_put_contents(CACHE_DIR.'66.txt', $sql);
			$q = $this->db->query($sql);
			
			$row['options']  =  array();
			
			while ($r = $this->db->fetch_array($q))
			{				
			    if (unserialize($r['describes']))
			    {
			        $r['describes'] = unserialize($r['describes']);
			    }
			    
				$r['pictures_info'] = unserialize($r['pictures_info']);
				if ($r['pictures_info'])
				{
					$r['option_img'] = hg_material_link($r['pictures_info']['host'], $r['pictures_info']['dir'], $r['pictures_info']['filepath'], $r['pictures_info']['filename']);
				}
				
				$r['ini_single'] = $r['single_total'] + $r['ini_num'];
				$r['ini_single'] = $r['ini_single'] > 0 ? $r['ini_single'] : 0;
				$r['pictures_other'] = array();			
				if (!$r['is_other'])
				{
					$row['options'][] = $r;
				}
				$r['pictures'] = $r['pictures'] ? $r['pictures'] : 0;
				$r['publishcontent_id'] = $r['publishcontent_id']  ? $r['publishcontent_id'] : 0;
				$r['vod_ids'] = $r['vod_ids'] ? $r['vod_ids'] : 0;
				$option_pic .= $r['pictures'] . ',';
				$option_quote .= $r['publishcontent_id'] . ',';
				$option_video .= $r['vod_ids'] . ',';
			}
			$option_pic = $option_pic.$row['pictures'];
			$option_quote = $option_quote.$row['publishcontent_id'];
			$option_video = $option_video.$row['vod_id'];
			$picture_ids = trim($option_pic,',') ;
			$quote_ids = trim($option_quote,',') ;
			$video_ids = trim($option_video,',') ;
			$option_pictures = $this->get_image($picture_ids);
			$quote = $this->get_quote($quote_ids);
			$option_videos = $this->get_vod_info_by_id($video_ids);
			$vote_pictures_id = explode(',',$row['pictures']);
			$vote_public_id = explode(',',$row['publishcontent_id']);
			$vote_vod_id = explode(',',$row['vod_id']);
			if(is_array($vote_pictures_id))
			{
				foreach ($vote_pictures_id as $vv)
				{
					if($option_pictures[$vv])
					{
						$row['pictures_other'][] = $option_pictures[$vv];  //投票获取多图
					}
				}
			}
			if(is_array($vote_public_id))
			{
				foreach ($vote_public_id as $vv)
				{
					if($quote[$vv])
					{
						$row['pictures_other'][] = $quote[$vv];  //投票获取发布库内容
					}
				}
			}
			if(is_array($vote_vod_id))
			{
				foreach ($vote_vod_id as $vv)
				{
					if($option_videos[$vv])
					{
						$row['pictures_other'][] = $option_videos[$vv];  //投票获取视频音频
					}
				}
			}
			foreach ($row['options'] as $k => $v)
			{
				$pictures_id = explode(',',$v['pictures']);
			    if(is_array($pictures_id))
			    {
				    foreach ($pictures_id as $vv)
				    {
					    if($option_pictures[$vv])
					    {
						    $row['options'][$k]['pictures_other'][] = $option_pictures[$vv];  //投票选项获取多图
					    }
				    }
			    }	
				$publishcontent_id = explode(',',$v['publishcontent_id']);
			    if(is_array($publishcontent_id))
			    {
				    foreach ($publishcontent_id as $vv)
				    {
					    if($quote[$vv])
					    {
						    $row['options'][$k]['pictures_other'][] = $quote[$vv];    //投票选项获取引用内容
					    }
				    }
			    }	
				$video_ids = explode(',',$v['vod_ids']);
			    if(is_array($video_ids))
			    {
				    foreach ($video_ids as $vv)
				    {
					    if($option_videos[$vv])
					    {
						    $row['options'][$k]['pictures_other'][] = $option_videos[$vv]; //投票选项获取视频
					    }
				    }
			    }	
			}
			$sql = "SELECT * FROM " . DB_PREFIX . "question_other_option WHERE vote_question_id IN (" . $id . ") ORDER BY id ASC";
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$row['other_options'][] = $r['other_option'];
			}
		}
		return $row;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "vote_question WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}
	
	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "vote_question SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			return $data;
		}
		return false;
	}
	
	public function update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "vote_question SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id = " . $data['id'];
		
		$q = $this->db->query($sql);
		if ($data['id'])
		{
			$data['affected_rows'] = 0;
			if ($this->db->affected_rows($q))
			{
				$data['affected_rows'] = 1;
			}
			return $data;
		}
		return false;
	}
	
	public function delete($id)
	{
 		//主表
		$sql = "DELETE FROM " . DB_PREFIX . "vote_question ";
		$sql.= " WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		//选项表
		$sql = "DELETE FROM " . DB_PREFIX . "question_option ";
		$sql.= " WHERE vote_question_id IN (" . $id . ")";
		$this->db->query($sql);

		//其他选项表
		$sql = "DELETE FROM " . DB_PREFIX . "question_other_option ";
		$sql.= " WHERE vote_question_id IN (" . $id . ")";
		$this->db->query($sql);
		
		//选项记录表
		$sql = "DELETE FROM " . DB_PREFIX . "question_record ";
		$sql.= " WHERE vote_question_id IN (" . $id . ")";
		$this->db->query($sql);
		
		//用户投票表
		$sql = "DELETE FROM " . DB_PREFIX . "question_person_info ";
		$sql.= " WHERE vote_question_id IN (" . $id . ")";
		$this->db->query($sql);
		
		//投票参与人数
		$sql = "DELETE FROM " . DB_PREFIX . "question_person ";
		$sql.= " WHERE vote_question_id IN (" . $id . ")";
		
		//投票参与人数
		$sql = "DELETE FROM " . DB_PREFIX . "material ";
		$sql.= " WHERE vid IN (" . $id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function option_create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "question_option SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		if ($data['id'])
		{
			$data['affected_rows'] = 1;
			return $data;
		}
		return false;
	}
	
	public function option_update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "question_option SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id = " . $data['id'];
		
		$q = $this->db->query($sql);
		
		if ($data['id'])
		{
			$data['affected_rows'] = 0;
			if ($this->db->affected_rows($q))
			{
				$data['affected_rows'] = 1;
			}
			return $data;
		}
		return false;
	}
	
	public function option_delete($option_id)
	{
		//选项表
		$sql = "DELETE FROM " . DB_PREFIX . "question_option ";
		$sql.= " WHERE id IN (" . $option_id . ")";
		$this->db->query($sql);
		
		//选项记录表
		$sql = "DELETE FROM " . DB_PREFIX . "question_record ";
		$sql.= " WHERE question_option_id IN (" . $option_id . ")";
		
		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}
	
	public function add_material($file, $id)
	{	
		$files['Filedata'] = $file;
		$material = $this->material->addMaterial($files, $id);			
		$return = array();
		if (!empty($material))
		{
			$return = array(
			'mid'      => $material['id'],
			'name'     => $material['name'],
			'host'     => $material['host'],
			'dir'      => $material['dir'],
			'filepath' => $material['filepath'],
			'filename' => $material['filename'],
			'type'     => $material['type'],
			'imgwidth' => $material['imgwidth'],
			'imgheight'=> $material['imgheight'],
			'filesize' => $material['filesize'],
			);
		}
		
		return $return;
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
			$r['pictures_info'] = unserialize($r['pictures_info']);
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
	
	/***
	 * 外部列表接口
	 */
	public function get_vote_info($condition,$device_token='', $offset = 0, $count = 20, $orderby = '', $filed = ' * ')
	{
		$orderby = $orderby ? $orderby : " ORDER BY order_id DESC ";
		$limit 	 = " LIMIT " . $offset . "," . $count;
	
		$sql = "SELECT {$filed} FROM " . DB_PREFIX . "vote_question ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		
		$q = $this->db->query($sql);

		$return = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['title'] = htmlspecialchars_decode($row['title'],ENT_QUOTES);
			$row['brief'] = htmlspecialchars_decode($row['brief'],ENT_QUOTES);
			$row['create_time'] = $row['create_time'] ? date('Y-m-d H:i', $row['create_time']) : 0;
			$row['update_time'] = $row['update_time'] ? date('Y-m-d H:i', $row['update_time']) : 0;
			$row['audit_time'] = $row['audit_time'] ? date('Y-m-d H:i', $row['audit_time']) : 0;
			$row['start_time']  = $row['start_time'] ? date('Y-m-d H:i', $row['start_time']) : 0;
			$row['end_time'] 	= $row['end_time'] ? date('Y-m-d H:i', $row['end_time']) : 0;
			$row['pictures_info'] = @unserialize($row['pictures_info']);
			$row['more_info'] 	  = @unserialize($row['more_info']);
			
			$row['column_id'] 	  = @unserialize($row['column_id']);
			$row['column_url'] 	  = @unserialize($row['column_url']);
					//
			$vote_id[] = $row['id'];
			$return[$row['id']] = $row;
		}
		if(!$vote_id && count($vote_id)<1)
		{
			return false;
		}

		$vote_ids = implode(',',$vote_id);
		
		$option = $this->get_vote_option($vote_ids);
		
		$sql = "SELECT vote_question_id, count(*) as other_option_num FROM " . DB_PREFIX . "question_record WHERE vote_question_id in ( " . $vote_ids . " ) AND question_option_id = -1 GROUP BY vote_question_id";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$oc[$r['vote_question_id']] = $r['other_option_num']; //勾选其他选项的人数
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "question_other_option WHERE vote_question_id IN (" . $vote_ids . ") ORDER BY id ASC";
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			$other_option_title[$r['vote_question_id']][] = $r['other_option']; //其他选项的填写内容
		}
		//参与人数
		$sql = "SELECT 	vote_question_id,counts, app_name, app_id FROM " . DB_PREFIX . "question_count WHERE vote_question_id in( " . $vote_ids .")";
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			//参与人数数目
			$p[$r['vote_question_id']]['preson_count'] += $r['counts'];
			$question_count[$r['vote_question_id']][] = array(
			    'count'   =>$r['counts'],
			    'app_name'=>$r['app_name'],
			    'app_id'  =>$r['app_id'],
 			);
			$p[$r['vote_question_id']]['app_id'] = $question_count[$r['vote_question_id']];
		}
		if($device_token)
		{
			$sql = "SELECT qp.vote_question_id,qr.option_ids FROM ".DB_PREFIX."question_person qp LEFT JOIN ".DB_PREFIX."question_person_info qr ON qp.pid = qr.id WHERE qp.vote_question_id in( " . $vote_ids .") AND qp.device_token = '".$device_token."'";
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$options_id[$r['vote_question_id']][] = $r['option_ids'];
			}
		}			
		if(is_array($return))
		{
			foreach ($return as $k=>$vo)
			{
				$return[$k]['option'] = array();
				$return[$k]['option'] = $option[$k];
				if (!empty($option[$k]))
				{
					$option_title = array();
				    foreach ($option[$k] AS $kK => $vv)
					{
						$return[$k]['vote_total'] += $vv['single_total'];
						$return[$k]['ini_num'] += $vv['ini_num'];
						if(!$this->input['is_showdata'])
						{
							unset($return[$k]['option'][$kK]['single_total']);
							unset($return[$k]['option'][$kK]['ini_num']);
						}
					}
					if($vo['is_other'])
					{
				        $other[] = array(
			    	    'id' => OTHER_OPTION_ID,
			    	    'title' => OTHER_OPTION_TITLE, 
			    	    'single_total'=> $oc[$k] ? $oc[$k] : count($other_option_title[$k]),
			    	    'ini_num'     => 0,
			    	    'ini_single'  => $oc[$k] ? $oc[$k] : count($other_option_title[$k]),
				        );
				        $return[$k]['other_vote_total'] = $oc[$k];//勾选其他选项的人数
				        $return[$k]['other_option_num'] = count($other_option_title[$k]);//有内容的其他选项个数
				        $return[$k]['option'] = array_merge($return[$k]['option'],$other);
				    }
				}
				//真实投票总数 = 编辑选项数目 + 其他数目
				$return[$k]['question_total'] = $return[$k]['vote_total'] + $return[$k]['other_vote_total'];
				//投票总数 = 初始化数据 + 真实数据
				$return[$k]['question_total_ini'] = $return[$k]['ini_num'] + $return[$k]['question_total'];
				$return[$k]['question_total_ini'] = $return[$k]['question_total_ini'] > 0 ? $return[$k]['question_total_ini'] : 0;
				
				$return[$k]['preson_count'] = $p[$vo['id']]['preson_count'] ?$p[$vo['id']]['preson_count'] :0 ;
				$return[$k]['person_total'] = $p[$vo['id']]['preson_count'] ?$p[$vo['id']]['preson_count'] + $return[$vo['id']]['ini_person']:0 ;
				$return[$k]['person_total'] = $return[$k]['person_total'] > 0 ? $return[$k]['person_total'] : 0;
				$return[$k]['app_id'] = $p[$vo['id']]['app_id'] ? $p[$vo['id']]['app_id'] : array();
				$return[$k]['votefor'] = $options_id[$vo['id']] ? $options_id[$vo['id']] : array();
				$return[$k]['deviced'] = count($options_id[$vo['id']])>0 ? 1 : 0;
				if(!$this->input['is_showdata'])
				{
					unset($return[$k]['vote_total']);
					unset($return[$k]['ini_num']);
					unset($return[$k]['question_total']);
					unset($return[$k]['preson_count']);
					unset($return[$k]['total']);
					unset($return[$k]['ini_total']);
					unset($return[$k]['ini_person']);
					unset($return[$k]['app_id']);
				}
			}
		}	
		
		return $return;
	}
	
	/***
	 * 外部列表接口
	 */
	public function get_vote_by_id($id, $condition = '', $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "vote_question  WHERE 1 ";
		if($id)
		{
			$sql.= "AND id IN (" . $id . ") " . $condition;
		}
		else
		{
			$sql.=  $condition;
		}
		$q = $this->db->query($sql);

		if($this->settings['verifycode'])
		{
			$verify_type = $this->verifycode->get_verify_type();
			if($verify_type && is_array($verify_type))
			{
				foreach ($verify_type as $v)
				{
					$vt[$v['id']] = $v['is_dipartite'];
				}
			}
		}
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			//
			$_tmp_status = hg_get_vote_status_text($row['start_time'], $row['end_time']);
			$row['status_text'] = $_tmp_status['status_text'];
			$row['status_flag'] = $_tmp_status['status_flag'];
			if ($row['start_time'])
			{
				$row['start_time'] = date('Y-m-d H:i:s' , $row['start_time']);
			}
			if ($row['end_time'])
			{
				$row['end_time'] = date('Y-m-d H:i:s' , $row['end_time']);
			}
			//
			if ($row['create_time'])
			{
				$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			}
			
			if ($row['update_time'])
			{
				$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			}
			
			if ($row['audit_time'])
			{
				$row['audit_time'] = date('Y-m-d H:i:s' , $row['audit_time']);
			}
			
		    if ($row['pub_time'])
			{
				$row['pub_time'] = date('Y-m-d H:i:s' , $row['pub_time']);
			}
			
			if ($row['pictures_info'])
			{
				$row['pictures_info'] = unserialize($row['pictures_info']);
			}
			
		    if ($row['pictures_info'])
			{
				$row['index_img'] = hg_material_link($row['pictures_info']['host'], $row['pictures_info']['dir'], $row['pictures_info']['filepath'], $row['pictures_info']['filename']);
			}
			
			if ($row['more_info'])
			{
				$row['more_info'] = unserialize($row['more_info']);
			}
			$row['other_info'] = array();
			
			if($row['pictures'])
			{
				$pic_id[] = $row['pictures'];
			}
			
			if($row['publishcontent_id'])
			{
				$quo_id[] = $row['publishcontent_id'];
			}
							
			if($row['verify_type'])
			{
				$row['is_verify_dipartite'] = $vt[$row['verify_type']]['is_dipartite'] ? $vt[$row['verify_type']]['is_dipartite'] : 0;
			}
			if($row['vod_id'])
			{
				$vod_id[] = $row['vod_id'];
			}
			unset($row['column_url']);
			$row['column_id'] = unserialize($row['column_id']);
			$row['title'] = htmlspecialchars_decode($row['title'],ENT_QUOTES);
			$row['brief'] = htmlspecialchars_decode($row['brief'],ENT_QUOTES);
			$return[] = $row;
		}
		if(count($pic_id)>0)
		{
			$pic_ids = implode(',',$pic_id);
		}
		if(count($quo_id)>0)
		{
			$quo_ids = implode(',',$quo_id);
		}
		if(count($vod_id)>0)
		{
			$vod_ids = implode(',',$vod_id);
		}
		$pictures = $this->get_image($pic_ids);
		$quote = $this->get_quote($quo_ids);
		$video = $this->get_vod_info_by_id($vod_ids);

		
		$pid = explode(',',$pic_ids);
		foreach ($return as $k=>$v)
		{
			$pictures_id = explode(',',$v['pictures']);
			foreach ($pictures_id as $vv)
			{
				if($pictures[$vv])
				{
					$return[$k]['other_info'][] = $pictures[$vv];  //获取多图
				}
			}
			
		    $publishcontentid = explode(',',$v['publishcontent_id']);
			foreach ($publishcontentid as $vv)
			{
				if($quote[$vv])
				{
					$return[$k]['other_info'][] = $quote[$vv];  //获取多图
				}
			}
			
		    $vodid = explode(',',$v['vod_id']);
			foreach ($vodid as $vv)
			{
				if($video[$vv])
				{
					$return[$k]['other_info'][] = $video[$vv];  //获取多图
				}
			}
		}	
		return $return;
	}
	
	public function get_vote_option($vote_id, $condition = '',$limit = '')
	{
		if(!$vote_id)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "question_option ";
		$sql.= " WHERE 1 " . $condition . " AND vote_question_id IN (" . $vote_id . ") ORDER BY order_id ASC,id ASC ";
		$sql.=  $limit;
		$q = $this->db->query($sql);
		
		$return[$vote_id] = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['title'] = htmlspecialchars_decode($row['title'],ENT_QUOTES);
			
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['pictures_info'] = unserialize($row['pictures_info']);
			$row['describes'] = unserialize($row['describes']);
			if(is_array($row['describes']))
			{
				foreach ($row['describes'] as $k=>$describes)
				{
					$row['describes'][$k] = htmlspecialchars_decode($describes,ENT_QUOTES);
				}
			}
			if ($row['pictures_info'])
			{
				$row['index_img'] = hg_material_link($row['pictures_info']['host'], $row['pictures_info']['dir'], $row['pictures_info']['filepath'], $row['pictures_info']['filename']);
			}
			$row['pictures'] = $row['pictures'] ? $row['pictures'] : 0;
			$row['publishcontent_id'] = $row['publishcontent_id']  ? $row['publishcontent_id'] : 0;
			$row['vod_ids'] = $row['vod_ids'] ? $row['vod_ids'] : 0;
			$option_pic .= $row['pictures'] . ',';
			$option_quote .= $row['publishcontent_id'] . ',';
			$option_video .= $row['vod_ids'] . ',';
			$row['ini_single'] = $row['single_total'] + $row['ini_num'];
			$r['ini_single'] = $r['ini_single'] > 0 ? $r['ini_single'] : 0;
			if(!$this->input['is_showdata'])
			{
				unset($row['single_total']);
				unset($row['ini_num']);
			}
			$return[$row['vote_question_id']][] = $row;
		}
		$picture_ids = trim($option_pic,',') ;
		$quote_ids = trim($option_quote,',') ;
		$video_ids = trim($option_video,',') ;
		$option_pictures = $this->get_image($picture_ids);
		$quote = $this->get_quote($quote_ids);
		$option_videos = $this->get_vod_info_by_id($video_ids);
		foreach ($return[$vote_id] as $k => $v)
		{
			$pictures_id = explode(',',$v['pictures']);
		    if(is_array($pictures_id))
		    {
			    foreach ($pictures_id as $vv)
			    {
				    if($option_pictures[$vv])
				    {
					    $return[$vote_id][$k]['other_info'][] = $option_pictures[$vv];  //获取多图
				    }
			    }
		    }	
			$publishcontent_id = explode(',',$v['publishcontent_id']);
		    if(is_array($publishcontent_id))
		    {
			    foreach ($publishcontent_id as $vv)
			    {
				    if($quote[$vv])
				    {
					    $return[$vote_id][$k]['other_info'][] = $quote[$vv];    //获取引用
				    }
			    }
		    }	
			$video_ids = explode(',',$v['vod_ids']);
		    if(is_array($video_ids))
		    {
			    foreach ($video_ids as $vv)
			    {
				    if($option_videos[$vv])
				    {
					    $return[$vote_id][$k]['other_info'][] = $option_videos[$vv]; //获取视频
				    }
			    }
		    }	
		}
		return $return;
	}
	
	public function create_data($table, $data, $is_id = false)
	{
		$sql = "INSERT INTO " . DB_PREFIX . $table . " SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		
		$this->db->query($sql);

				
		if ($is_id)
		{
			$data['id'] = $this->db->insert_id();
		}
		
		return $data;
	}

		
	public function update_data($table, $data)
	{
		$sql = "UPDATE " . DB_PREFIX . $table . " SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= "WHERE id = ". $data['id'] ; 
		
		$this->db->query($sql);
		$data['affected_rows'] = $this->db->affected_rows();
		return $data;
	}

	public function get_image($ids,$pic_arr = 0,$need_key = 1)
	{
		if(!$ids)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE id IN (" . $ids . ")";
		$res = $this->db->query($sql);
		while ($mat = $this->db->fetch_array($res))
		{
			$pic[] = $p[$mat['id']] = array(
			    'id'  =>$mat['id'],
			    //'pic' =>unserialize($r['pic']),
			    'img_info' =>hg_material_link($mat['host'], $mat['dir'], $mat['filepath'], $mat['filename']),
			    'pic_arr' => array('host'  => $mat['host'],'dir'  => $mat['dir'],'filepath'  => $mat['filepath'],'filename'  => $mat['filename']),
			    'upload_type' => '图片',
				);
		}
		
		return $need_key ? $p : $pic;
	}
	
	public function get_sort_name()
	{
	    $sql = "SELECT id,name FROM " . DB_PREFIX . "vote_node WHERE 1";
		$res = $this->db->query($sql);
		while ($nod = $this->db->fetch_array($res))
		{
			$p[$nod['id']] = $nod['name'];
		}
		return $p;
	}
		
	
	public function get_one_sort_name($id)
	{
	    $sql = "SELECT name FROM " . DB_PREFIX . "vote_node WHERE id = ".$id;
		$res = $this->db->query_first($sql);
		return $res['name'];
	}
	
	/**
	 * 
	 * @Description  获取视频的配置
	 * @author Kin
	 * @date 2013-4-13 下午04:48:54
	 */
	public function getVideoConfig()
	{
		$videoConfig = array();
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','__getConfig');
		$ret = $curl->request('index.php');
		if (empty($ret))
		{
			return false;
		}
		$temp = explode(',', $ret[0]['video_type']['allow_type']);
		$videoConfig['type'] = $temp;
		if (is_array($temp) && !empty($temp))
		{
			foreach ($temp as $val)
			{
				$videoType[] = ltrim($val,'.');
				//$videoConfig['type'][] = 'video/'.ltrim($val,'.');
			}
			$videoConfig['hit'] = implode(',', $videoType);
			
		}
		return $videoConfig;
	}
		
	/**
	 * 
	 * @Description 视频上传
	 * @author Kin
	 * @date 2013-4-13 下午04:34:29
	 */
	public function uploadToVideoServer($file,$title,$brief)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($file);
		$curl->addRequestData('title', $title);
		$curl->addRequestData('comment',$brief);
		$curl->addRequestData('vod_leixing',2);
		$ret = $curl->request('create.php');
		return $ret[0];
	}
	
		//根据url上传图片
	public function localMaterial($url,$cid)
	{
		$material = $this->material->localMaterial($url,$cid);
		return $material[0];
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
		        //'title'=> $ret['title'],
		        'brief'=> $v['brief'],
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
	
	public function get_vod_info_by_id($vod_id,$need_key = 1)
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
		
		$video = $mLivMedia->getVodInfoById($vod_id,100);
		if(!$video)
		{
			return false;
		}
        if(is_array($video))
        {
        	foreach ($video as $k=>$v)
        	{
        		$mp4 = $v['hostwork'] . '/'.$v['video_path'].$v['video_filename'];
        		$m3u8 = str_replace('.mp4', '.m3u8', $mp4);
        		$vid[] = $return[$v['id']] = array(
        		    'id'  => $v['id'],
        		    'img_info' =>$v['img'] ? $v['img'] : '',
        		    'is_audio' => $v['is_audio'],
			        'upload_type' =>$v['is_audio'] ? '音频' : '视频',
        		    'title'=> $v['title'],
        		    'url'  =>  $v['video_url'],
        			'm3u8' => $m3u8,
        		    'pic_arr' => $v['img_info'],
        		    'video_arr' => array('hostwork'=>$v['hostwork'],'video_base_path'=>$v['video_base_path'],'video_path'=>$v['video_path'],'video_filename'=>$v['video_filename']),
        		);
        	}
        }        
		return $need_key ? $return : $vid;
	}
	
	
	public function get_vote_list($cond, $field="*")
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."vote_question WHERE 1 AND " . $cond;
		$q = $this->db->fetch_all($sql);
		return $q;
	}
	
	public function get_vote_catalog($id,$data_limit = '',$condition = '')
	{
		if(!$id)
		{
			return false;
		}
		$condition .= 'AND vote_id = ' . $id ;
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_catalog ";
		$sql .= " WHERE 1 " . $condition . $data_limit;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$info[] = @unserialize($r['info']);
		}
		return $info;
	}
	
	//后台获取投票结果
	public function getResult($id ,$condition = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE id = ".$id;
		$row = $this->db->query_first($sql);
		if (!empty($row))
		{			
			$row['start_time'] 	= $row['start_time'] ? date('Y-m-d H:i', $row['start_time']) : 0;
			$row['end_time'] 	= $row['end_time'] ? date('Y-m-d H:i', $row['end_time']) : 0;
			$row['more_info'] 	= unserialize($row['more_info']);
			$row['pictures_info'] = unserialize($row['pictures_info']);
			$row['column_id'] 	  = unserialize($row['column_id']);
			$row['column_url'] 	  = unserialize($row['column_url']);
			$row['sort_name']     = $this->get_one_sort_name($row['node_id']);
			$row['column'] = $row['column_id'];
			if(is_array($row['column_id']))
			{
				$column_id = array();
				foreach($row['column_id'] AS $k => $v)
				{
					$column_id[] = $k;
					$column_name[] = $v;
				}
				$column_id = implode(',',$column_id);
				$column_name = implode(',',$column_name);
				$row['column_id'] = $column_id;
				$row['column_name'] = $column_name;
			}
			if ($row['pictures_info'])
			{
				$row['index_img'] = hg_fetchimgurl($row['pictures_info'],114,114);
			}
			$row['pictures_other'] = array();
			$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id = {$id} ORDER BY order_id ASC,id ASC";
			$q = $this->db->query($sql);
			$row['options']  =  array();
			while ($r = $this->db->fetch_array($q))
			{				
				$r['pictures_info'] = unserialize($r['pictures_info']);
				if ($r['pictures_info'])
				{
					$r['option_img'] = hg_fetchimgurl($r['pictures_info'],100,75);
				}
				
				$r['ini_single'] = $r['single_total'] + $r['ini_num'];
				$r['ini_single'] = $r['ini_single'] > 0 ? $r['ini_single'] : 0;
				$r['pictures_other'] = array();			
				$row['options'][] = $r;
			}
			$sql = "SELECT * FROM " . DB_PREFIX . "question_other_option WHERE vote_question_id = {$id} ORDER BY id ASC";
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$row['other_options'][] = $r['other_option'];
			}
		}
		return $row;
	}
	
	/**
	 * 获取未做处理的投票数据
	 * Enter description here ...
	 */
	public function get_simple_vote($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE id = " . $id;
		$row = $this->db->query_first($sql);
		$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id = ".$id." ORDER BY order_id ASC,id ASC ";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$options[] = $r;
		}
		$row['options'] = $options;
		return $row;
	}
	
	/**
     * 获取全局属性（云投票使用）
     */
	public function get_vote($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE id = " . $id;
		$row = $this->db->query_first($sql);
		if (!empty($row))
		{			
			$row['update_time'] = $row['update_time'] ? date('Y-m-d', $row['update_time']) : 0;
			$row['start_time'] 	= $row['start_time'] ? date('Y-m-d', $row['start_time']) : 0;
			$row['end_time'] 	= $row['end_time'] ? date('Y-m-d', $row['end_time']) : 0;
			$row['more_info'] 	= $row['more_info'] ? unserialize($row['more_info']) : array();
			$row['indexpic'] = $row['pictures_info'] ? unserialize($row['pictures_info']) : array();
			if($row['feedback_id'] && $row['is_feedback'])
			{
				$feed = $this->feedback->detail($row['feedback_id']);
				$row['feedback_title'] = $feed['title'];
			}
			$row['picture_ids'] = $row['pictures'];
			$row['pictures'] = $row['pictures'] ? $this->get_image($row['pictures'],0,0) : array();
			if($row['vod_id'])
			{
				$medias = $this->get_vod_info_by_id($row['vod_id'],0);
				if($medias)
				{
					foreach ($medias as $v)
					{
						if($v['is_audio'])
						{
							$row['audios'][] = $v;
						}else 
						{
							$row['videos'][] = $v;
						}
					}
				}
			}
			$row['publicontents'] = $row['publishcontent_id'] ? $this->get_quote($row['publishcontent_id']) : array();
			$row['header_info'] = $row['header_info'] ? unserialize($row['header_info']) : array();
			$row['footer_info'] = $row['footer_info'] ? unserialize($row['footer_info']) : array();
		}
		return $row;
	
	}
	
	 /**
     * 获取所有选项（云投票使用）
     * Enter description here ...
     */
	public function get_vote_options($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id = ".$id." ORDER BY order_id ASC,id ASC ";
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			$r['pictures_info'] = $r['pictures_info'] ? unserialize($r['pictures_info']) : array();
			$r['describes'] = unserialize($r['describes']) ? unserialize($r['describes']) : $r['describes'] ;
			if($r['pictures'])
			{
				$picture_ids[] = $r['pictures'];
			}
			if($r['vod_ids'])
			{
				$vod_ids[] = $r['vod_ids'];
			}
			if($r['publishcontent_id'])
			{
				$publishcontent_id[] = $r['publishcontent_id'];
			}
			$return[] = $r;
		}
		if($picture_ids)
		{
			$option_pictures = $this->get_image(implode(',',$picture_ids));
		}
		if($vod_ids)
		{
			$option_videos = $this->get_vod_info_by_id(implode(',',$vod_ids));
		}
		if($picture_ids)
		{
			$option_publishs = $this->get_quote(implode(',',$publishcontent_id));
		}
		if($return)
		{
			foreach ($return as $k=>$v)
			{
				if($v['pictures'])
				{
					$pictures_id = explode(',',$v['pictures']);
					$v['picture_id'] = $v['pictures'];
					$v['pictures'] = array();
					foreach ($pictures_id as $vv)
					{
						$v['pictures'][] = $option_pictures[$vv];
					}
				}
				if($v['vod_ids'])
				{
					$video_ids = explode(',',$v['vod_ids']);
					foreach ($video_ids as $vv)
					{
						if($option_videos[$vv]['is_audio'])
						{
							$v['audios'][] = $option_videos[$vv];
						}else 
						{
							$v['videos'][] = $option_videos[$vv];
						}
					}
				}
				if($v['publishcontent_id'])
				{
					$publishs_id = explode(',',$v['publishcontent_id']);
					foreach ($publishs_id as $vv)
					{
						$v['publishcontents'][] = $option_publishs[$vv];
					}
				}
				$ret[] = $v;
			}
		}
		return $ret;
	}
	
	/**
	 * 处理选项
	 * Enter description here ...
	 * @param unknown_type $options
	 * @param unknown_type $id
	 */
	public function process_options($options,$id)
	{
		if(!$options)
		{
			return false;
		}
		foreach ($options as $k=>$v)
		{
			$data = array(
				'vote_question_id'	=> $id,
				'title'				=> $v['title'],
				'describes'			=> is_array($v['describes']) ? serialize($v['describes']) : $v['describes'],
			    'pictures'		 	=> $v['picture_id'],
				'state'				=> 1,
				'ini_num'			=> $v['ini_num'],
				'user_id'			=> $this->user['user_id'],
				'user_name'			=> $this->user['user_name'],
				'publishcontent_id'	=> $v['publishcontent_id'],
				'create_time' 		=> TIMENOW,
				'update_time' 		=> TIMENOW,
		        'vod_ids'           => $v['vod_ids'],
			    'pictures_info'	    => $v['pictures'] ? serialize($v['pictures']) : '',
				'order_id'	    	=> $k,
			);
			if($v['id'])
			{
				$data['id'] = $v['id']; 
				$ret = $this->update_data('question_option', $data);
				$affected_rows = $data['affected_rows'] ? 1 : $ret['affected_rows'];
			}else{
				$insert = $this->create_data('question_option', $data,1);
				$affected_rows = $affected_rows || $insert['id'] ? 1 : 0 ;
			}
		}
		return $affected_rows;
	}
}

?>