<?php
include ROOT_PATH . 'lib/class/livmedia.class.php';
include_once ROOT_PATH . 'lib/class/curl.class.php';
class survey_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "survey  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		$sort_name = $this->get_all_node();
		while($r = $this->db->fetch_array($q))
		{
			if(strtotime(date('Y-m-d',TIMENOW)) == $r['end_time'])
			{
				$r['end_time_flag'] = $r['end_time'] + 86400 -1 > TIMENOW ? 1 : 0;
			}else 
			{
				$r['end_time_flag'] = $r['end_time'] && $r['end_time'] > TIMENOW ? 1 : 0;
			}
			$r['start_time'] = $r['start_time'] ? date('Y-m-d',$r['start_time']) : 0;
			$r['end_time'] = $r['end_time'] ? date('Y-m-d',$r['end_time']) : 0;
			if(file_exists(DATA_DIR.$r['create_time'].$r['id'].'/index.html'))
			{
				$r['url'] = $r['gen_url'] ? $r['gen_url'] : SV_DOMAIN.'/'.$r['create_time'].$r['id'].'/index.html';
			}
			if(date('Y',$r['create_time']) == date('Y'))
			{
				$r['create_time'] = $r['create_time'] ? date('m-d H:i',$r['create_time']) : 0;
			}else 
			{
				$r['create_time'] = $r['create_time'] ? date('Y-m-d H:i',$r['create_time']) : 0;
			}
			$r['update_time'] = $r['update_time'] ? date('Y-m-d H:i',$r['update_time']) : 0;
			$r['audit_time'] = $r['audit_time'] ? date('Y-m-d H:i',$r['audit_time']) : 0;
			$r['sort_name'] = $sort_name[$r['node_id']]['name'];
			$r['indexpic'] = $r['indexpic'] ? unserialize($r['indexpic']) : array();
			$r['column'] = $r['column_id'] = unserialize($r['column_id']);
			if(is_array($r['column_id']) && count($r['column_id'])>0)
			{
				$column_id = array();
				$column_name = array();
				foreach($r['column_id'] AS $k => $v)
				{
					$column_id[] = $k;
					$column_name[] = $v;
				}
				$column_id = @implode(',',$column_id);
				$column_name = @implode(',',$column_name);
				$r['column_id'] = $column_id;
				$r['column_name'] = $column_name;
			}
			$r['column_url'] = unserialize($r['column_url'] );
			if($r['picture_ids'])
			{
				$pids[] = intval($r['picture_ids']);
			}
			unset($r['header_info']);
			unset($r['footer_info']);
			$info[] = $r;
			if($pids)
			{
				$pictures = $this->get_images(implode(',',$pids),1);
			}
			if($pictures)
			{
				foreach ($info as $k=>$v)
				{
					if(!$v['indexpic'] && $v['picture_ids'])
					{
						$pid = explode(',',$v['picture_ids']);
						$v['indexpic'] = $pictures[$pid[0]]['img_arr'];
					}
					$info[$k] = $v;
				}
			}
		}
		return $info;
	}

	//外部接口使用
	public function show_list($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "survey  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		$sort_name = $this->get_all_node();
		while($r = $this->db->fetch_array($q))
		{
			$r['start_time'] = $r['start_time'] ? date('Y-m-d H:i',$r['start_time']) : 0;
			$r['end_time'] = $r['end_time'] ? date('Y-m-d H:i',$r['end_time']) : 0;
			$r['create_time'] = $r['create_time'] ? date('Y-m-d H:i:s',$r['create_time']) : 0;
			$r['update_time'] = $r['update_time'] ? date('Y-m-d H:i:s',$r['update_time']) : 0;
			$r['audit_time'] = $r['audit_time'] ? date('Y-m-d H:i:s',$r['audit_time']) : 0;
			$r['sort_name'] = $sort_name[$r['node_id']]['name'];
			if($r['indexpic'])
			{
				$r['indexpic'] = unserialize($r['indexpic']);
				$r['indexpic_url'] = hg_material_link($r['indexpic']['host'],$r['indexpic']['dir'], $r['indexpic']['filepath'],$r['indexpic']['filename']);
			}
			$ids[] = $r['id']; 
			$info[] = $r;
		}
		if($ids)
		{
			$all_id = implode(',',$ids);
			$problems = $this->get_problems($all_id);
			if($info)
			{
				foreach ($info as $k=>$v)
				{
					$info[$k]['problems'] = $problems;
				}
			}
		}
		return $info;
	}
	
	public function create($table,$data = array(),$order = 1)
	{
		if(!$data)
		{
			return false;
		}
	    if(!$table)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX .$table . " SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		if($order)
		{
			$sql = " UPDATE ".DB_PREFIX.$table." SET order_id = {$vid}  WHERE id = {$vid}";
			$this->db->query($sql);
			$data['order_id'] = $vid;
		}
		$data['id'] = $vid;
		return $data;
	}
        
        /*
         * @title 创建组件
         * @param $data array 组件信息
         * @return mix 如果创建成功则返回新的组件信息；否则返回false
         */
	public function create_component($survey_id,$problem = array(),$options = array())
        {
            $sql = 'select id, problem_num from '.DB_PREFIX.'survey where id='.$survey_id.'';
            $survey = $this->db->query_first($sql);
            
            if($survey)
            {
                //$problem['order_id'] = $survey['problem_num'];
                $insert_data = $this->insert_datas('problem',array($problem),true);
                $insert_id = $insert_data[0]['id'];
		if($insert_id)//插入成功
                {
                    //插入options
                    //$orderid = 0;
                    foreach($options as $k=>$v)
                    {
                        $options[$k]['survey_id'] = $survey_id;
                        $options[$k]['problem_id'] = $insert_id;
                        //$options[$k]['order_id'] = $insert_id;
                        //$this->db->insert_data($options[$k],'options',false);
                    }
                    $this->insert_datas('options',$options,true);
                    //surver表problem_num加1
                    $totle = intval($survey['problem_num'])+1;
                    $sql = 'update '.DB_PREFIX.'survey set problem_num='.$totle.' where id='.$survey['id'];
                    $this->db->query($sql);
                    
                    //return $this->getProblem($insert_id);
                    $problem['id'] = $insert_id;
                    return $problem;
                }
                else//插入失败
                {
                    return false;
                }
                
            }
            else//没有此表
            {
                return false;
            }
        }

        //zs-2015-5-19
        public function getProblem($pid)
        {
            $sql = 'select * from '.DB_PREFIX.'problem where id='.$pid;
            $result = $this->db->query($sql);
            $row = $this->db->fetch_array($result);
            if($row)
            {
                if($row['picture'])
                {
                    $row['picture_arr'] = @unserialize($row['picture']);
                    $row['picture'] = hg_material_link($row['picture_arr']['host'],$row['picture_arr']['dir'], $row['picture_arr']['filepath'],$row['picture_arr']['filename']);
                }
                //获取所有选项
                $sql = 'select * from '.DB_PREFIX.'options where problem_id='.$pid.' order by order_id asc';
                $result = $this->db->query($sql);
                while($option = $this->db->fetch_array($result))
                {
                    $row['options'][] = $option;
                }
            }
            
            return $row;
        }
        
        //zs-2015-5-19
        //return mix 更新成功返回更新成功后的信息，失败返回false
        public function update_component($pid,$problem = array(),$options = array())
        {
            //获取问题的问卷id
            $sql = 'select survey_id from '.DB_PREFIX.'problem where id='.$pid;
            $sid_query = $this->db->query($sql);
            $prow = $this->db->fetch_array($sid_query);
            $survey_id = $prow['survey_id'];
            
            //更新问题
            $updated = $this->update($pid,'problem',$problem) ? true : false;
                
            $sql = 'select id from '.DB_PREFIX.'options where problem_id='.$pid;
            $ret = $this->db->query($sql);
            $oids = array();
            $ids = array();
            while($row = $this->db->fetch_array($ret))
            {
                $oids[] = $row['id'];
            }
            
            foreach($options as $k=>$v)
            {
                if(!isset($v['id']))//新增
                {
                    $options[$k]['problem_id'] = $pid;
                    $options[$k]['survey_id'] = $survey_id;
                    $this->insert_datas('options', array($options[$k]),true);
                    $updated = true;
                }
                else if(in_array($v['id'],$oids))//更新
                {
                    if($this->update($v['id'], 'options', $options[$k]))
                    {
                        $updated = true;
                    }
                    $ids[] = $v['id'];
                    
                }
                
            }
            
            $del_ids = array_diff($oids,$ids);
            if(count($del_ids) > 0)//删除
            {
                $sql = 'delete from '.DB_PREFIX.'options where id in('.  implode(',', $del_ids).')';
                $this->db->query($sql);
                if($this->db->affected_rows() > 0)
                {
                    $updated = true;
                }
            }
            return $updated ? 1 : 0;
        }

        public function update($id,$table,$data = array(),$keys = 'id')
	{
		if(!$data || !$id)
		{
			return false;
		}
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . $table . " SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE {$keys} = '"  .$id. "'";
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "survey  WHERE id = '" .$id. "'";
		$row = $this->db->query_first($sql);
		if(!$row)
		{
			return false;
		}
		$row['start_time'] = $row['start_time'] ? date('Y-m-d H:i:s',$row['start_time']) : 0;
		$row['end_time'] = $row['end_time'] ? date('Y-m-d H:i:s',$row['end_time']) : 0;
		$row['create_time'] = $row['create_time'] ? date('Y-m-d H:i:s',$row['create_time']) : 0;
		$row['update_time'] = $row['update_time'] ? date('Y-m-d H:i:s',$row['update_time']) : 0;
		$row['audit_time'] = $row['audit_time'] ? date('Y-m-d H:i:s',$row['audit_time']) : 0;
		
		if($row['indexpic'])  //初始化索引图
		{
			$row['indexpic'] = unserialize($row['indexpic']);
			$row['indexpic_url'] = hg_material_link($row['indexpic']['host'],$row['indexpic']['dir'], $row['indexpic']['filepath'],$row['indexpic']['filename']);
		}
		$sort_name = $this->get_all_node();
		$row['sort_id'] = $row['node_id'];
		$row['sort_name'] = $sort_name[$row['node_id']]['name'];
		$row['column_id'] 	  = unserialize($row['column_id']);
		if(is_array($row['column_id']) && count($row['column_id']))//栏目id和栏目名称
		{
			$column_id = array();
			foreach($row['column_id'] AS $k => $v)
			{
				$column_id[] = $k;
				$column_name[] = $v;
			}
			$column_id = @implode(',',$column_id);
			$column_name = @implode(',',$column_name);
			$row['column_id'] = $column_id;
			$row['column_name'] = $column_name;
		}
		if($row['question_time']) //初始化需要用时 时分秒
		{
			$row['use_hour'] = intval($row['question_time']/3600);
			$row['use_minute'] = intval(($row['question_time']%3600)/60);
			$row['use_second'] = intval(($row['question_time']%3600)%60);
		}
		if($row['picture_ids'])
		{
			$row['pictures'] = $this->get_images($row['picture_ids']); //获取图片
		}
		if($row['video_ids'])
		{
			$row['videos'] = $this->get_vod_info_by_id($row['video_ids']); //获取视频信息
		}
		if($row['audio_ids'])
		{
			$row['audios'] = $this->get_vod_info_by_id($row['audio_ids']); //获取视频信息
		}
		if($row['publicontent_ids'])
		{
			$row['publicontents'] = $this->get_publicontent($row['publicontent_ids']); //获取引用信息
		}
		$row['is_publish'] = $row['expand_id'] ? 1 : 0;
		//获取题目信息
		$row['problems'] = $this->get_problems($id, $orderby, $limit); //获取问卷的所有问题
		//$info = $row;
		return $row;
	}
	
	/**
	 * 
	 * 获取某一调查的所有问题
	 * @param $id 问卷的ID
	 * @param $orderby
	 * @param $limit
	 */
	public function get_problems($id , $orderby = ' ORDER BY order_id ASC ,id ASC' , $limit = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "problem  WHERE survey_id in( " . $id .  ') ORDER BY order_id ASC ,id ASC'  . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['picture'])
			{
				$r['indexpic_arr'] = unserialize($r['picture']);
				$r['picture'] = hg_material_link($r['indexpic_arr']['host'],$r['indexpic_arr']['dir'], $r['indexpic_arr']['filepath'],$r['indexpic_arr']['filename']);
			}
			$r['type_name'] = $this->settings['type'][$r['type']]; //获取类型名称 单选，多选，填空，问答
			$ids[] = $r['id'];
			$info[] = $r;
		}
		if(!$ids && count($ids)<1)
		{
			return false;
		}
		if(!$info && count($info)<1)
		{
			return false;
		}
		$problem_ids = implode(',',$ids); //合并问题ID，获取所有的问题选项		
		$sql = "SELECT * FROM " . DB_PREFIX . "options  WHERE problem_id in (" . $problem_ids .") " .  ' ORDER BY order_id ASC ,id ASC' ;
		$query = $this->db->query($sql);
		$op_info = array();
		while ($row = $this->db->fetch_array($query))
		{
			if($row['picture'])
			{
				$row['picture_arr'] = @unserialize($row['picture']);
				$row['picture'] = hg_material_link($row['picture_arr']['host'],$row['picture_arr']['dir'], $row['picture_arr']['filepath'],$row['picture_arr']['filename']);
			}
			$row['initnum'] = $row['ini_num'];
			$options[$row['problem_id']][] = $row;
		}
		foreach ($info as $k=>$v)  //对应各个选项到各个问题上
		{
			$info[$k]['options'] = array();
			$info[$k]['options'] = $options[$v['id']];
		}
		return $info;
	}
	
	/**
	 * 
	 * 获取某一调查的所有问题
	 * @param $id 问卷的ID
	 * @param $orderby
	 * @param $limit
	 */
	public function problems($id = '')
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT id,problem_id,char_num,name FROM ' . DB_PREFIX . 'options  WHERE survey_id = '.$id  ;
		$query = $this->db->query($sql);
		$op_info = array();
		while ($row = $this->db->fetch_array($query))
		{
			$options_id[$row['problem_id']][] = $row['id'];
			if($row['char_num'])
			{
				$options_char[$row['problem_id']][$row['id']] = array(
					'name'	=> $row['name'],
					'char_num'	=> $row['char_num']
				);
			}
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'problem  WHERE survey_id = '.$id ;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['options'] = $options_id[$r['id']] ? $options_id[$r['id']] : array();
			$r['char_limit'] = $options_char[$r['id']] ? $options_char[$r['id']] : array();
			$info[] = $r;
		}
		return $info ? $info : false;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "survey WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	/**
	 * 删除问卷
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	public function delete($id = '')
	{
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "survey WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
			$r['indexpic'] = @unserialize($r['indexpic']);
			if($r['indexpic']['id'])
			{
				$mat_id[] = $r['indexpic']['id'];
			}
		}
	    //查询出原来问题
		$sql = " SELECT * FROM " .DB_PREFIX. "problem WHERE survey_id IN (" . $id . ")";
		$pro = $this->db->query($sql);
		$pre_pro_data = array();
		while ($r = $this->db->fetch_array($pro))
		{
			$pre_pro_data[] 	= $r;
			$r['picture'] = @unserialize($r['picture']);
			if($r['picture']['id'])
			{
				$mat_id[] = $r['picture']['id'];
			}
		}
		
		//查询出原来选项
		$sql = " SELECT * FROM " .DB_PREFIX. "options WHERE survey_id IN (" . $id . ")";
		$op = $this->db->query($sql);
		$pre_op_data = array();
		while ($r = $this->db->fetch_array($op))
		{
			$pre_pro_data[] 	= $r;
			$r['picture'] = @unserialize($r['picture']);
			if($r['picture']['id'])
			{
				$mat_id[] = $r['picture']['id'];
			}
		}
				
		$mat_id = @implode(',',$mat_id);
		if(!$pre_data)
		{
			return false;
		}
		$this->material->delMaterialById($mat_id);
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "survey WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		//删除问题表
		$sql = " DELETE FROM " .DB_PREFIX. "problem WHERE survey_id IN (" . $id . ")";
		$this->db->query($sql);
		//删除选项表
		$sql = " DELETE FROM " .DB_PREFIX. "options WHERE survey_id IN (" . $id . ")";
		$this->db->query($sql);
		//删除素材表
		$sql = " DELETE FROM " .DB_PREFIX. "material WHERE cid IN (" . $id . ")";
		$this->db->query($sql);
		return $id;
	}
	
	/**
	 * 审核
	 * Enter description here ...
	 * @param $id
	 * @param $pre_status 需要执行的动作  打回-0 审核-1
	 */
	public function audit($id = '',$pre_status)
	{
		if(!$id)
		{
			return false;
		}
		switch (intval($pre_status))
		{
			case 0:$status = 2;break;//打回
			case 1:$status = 1;break;//审核
		}
		$data = array(
		    'status'           => $status,
		    'audit_user_id'    => $this->user['user_id'],
		    'audit_user_name'  => $this->user['user_name'],
		    'audit_time'       => TIMENOW,
		);
		$sql = " UPDATE " . DB_PREFIX . "survey SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id IN ("  .$id. ")";
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;
	}
	
	/**
	 * 获取视频服务器上传配置
	 * Enter description here ...
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
	
	/**
	 * 通过ID获取视频信息
	 * Enter description here ...
	 * @param $vod_id
	 */
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
        		$m3u8 = $v['hostwork'].'/'.$v['video_path'].$v['video_filename'];
        		$m3u8 = str_replace('.mp4', '.m3u8', $m3u8);
        		$return[] = array(
        		    'id'  => $v['id'],
        		    'img_info' =>$v['img'] ? $v['img'] : '',
        		    'is_audio' => $v['is_audio'],
			        'upload_type' =>$v['is_audio'] ? '音频' : '视频',
        		    'title'=> $v['title'],
        		    'play_url'  =>  $v['video_url'],
        			'm3u8' => $m3u8,
        		    'video_arr' => array('hostwork' => $v['hostwork'],'video_base_path' => $v['video_base_path'],'video_path' => $v['video_path'],'video_filename' => $v['video_filename'])
        		);
        	}
        }        
		return $return;
	}
	
	/**
	 *
	 * 获取引用素材的索引图 标题 引用 栏目
	 * @param int $option_id
	 */
	public function get_publicontent($rids)
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
		        $quote[] = array(
		        'id'   => $v['rid'],
		        'title'=> $v['title'],
		        'brief' => $v['brief'],
		        'bundle_id' => $v['bundle_id'],
		        'module_id' => $v['module_id'],
		        'content_url' => $v['content_url'],
		        'content_fromid' => $v['content_fromid'],
		        'img_info'    => hg_material_link($v['indexpic']['host'], $v['indexpic']['dir'], $v['indexpic']['filepath'], $v['indexpic']['filename']),
			    'img_arr' => array('host'=>$v['indexpic']['host'],'dir'=>$v['indexpic']['dir'],'filepath'=>$v['indexpic']['filepath'],'filename'=>$v['indexpic']['filename'],),
		        'upload_type' => '引用',
		        'module_name' => $bundles[$v['bundle_id']],
		        );
			}
		}
		return $quote;
	}
	
	/**
	 * 获取多图上传图片
	 * Enter description here ...
	 * @param unknown_type $ids
	 */
	public function get_images($ids,$needkey = 0)
	{
		if(!$ids)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE id IN (" . $ids . ")";
		$res = $this->db->query($sql);
		while ($mat = $this->db->fetch_array($res))
		{
			$pk[$mat['id']] = $p[] = array(
			    'id'  =>$mat['id'],
			    'img_info' =>hg_material_link($mat['host'], $mat['dir'], $mat['filepath'], $mat['filename']),
			    'upload_type' =>'图片',
			    'img_arr' => array('host'=>$mat['host'],'dir'=>$mat['dir'],'filepath'=>$mat['filepath'],'filename'=>$mat['filename'],)
			);
		}
		return $needkey ? $pk : $p;
	}
	
	public function get_all_node()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "survey_node WHERE 1";
		$ret = $this->db->query($sql);
		while ($r = $this->db->fetch_array($ret))
		{
			$p[$r['id']] = array(
			    'id'    => $r['id'],
			    'name'  => $r['name'],
			);
		}
		if($p)
		{
			return $p;
		}
	}
	
	
	public function get_survey($cond, $field = '*')
	{
		$sql = "SELECT ". $field ." FROM " . DB_PREFIX . "survey WHERE 1 AND " . $cond;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_survey_list($cond, $field="*")
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."survey WHERE 1 AND " . $cond;
		$q = $this->db->fetch_all($sql);
		return $q;
	}
	
	/**
	 * 多行插入数据到数据库
	 * Enter description here ...
	 * @param  $table 表名
	 * @param  $data 插入数据数组
	 * @param  $orders 是否按ID进行排序
	 */	
	public function insert_datas($table,$data,$orders = false)
	{
		if(!$table)
		{
			return false;
		}
		if(!is_array($data) || empty($data))
		{
			return false;
		}
		foreach ($data AS $key => $value)
		{
			foreach ($value as $k=>$v)
			{
				$field[$key][]=$k;
				$val[$key][] = "'".$v."'";
			}
			$alldata[] = '('.implode(',',$val[$key]).')';
		}
		$fields = '('.implode(',',$field[0]).')';
		$val = implode(',',$alldata);
		$sql =" INSERT INTO " . DB_PREFIX .$table .' '.$fields ." VALUES " . $val;
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		foreach ($data as $key=>$value)
		{
			$data[$key]['id'] = $vid;
		    if($orders)
			{
				$order['order_id'] = $vid;
			    $this->update($vid, $table,$order);
			    $data[$key]['order_id'] = $vid;
			}
			$vid++;
		}
		return $data;
	}
	
	/**
	public function create_problem($table,$data,$pro_num='')
	{
		if($pro_num)
		{
			$all_char_num = explode('@',$pro_num);
		    $number = 0;
		}
		$p_data = $data;
		foreach ($p_data as $k=>$p)
		{
			unset($p_data[$k]['options']);
		}
		$problem = $this->insert_datas($table, $p_data);
		foreach ($problem as $k=>$v)
		{
			if($v['options'])
			{
				$options = explode('|',$v['options']);
				if($v['type'] == 3 && $all_char_num)
				{
					$char_num = explode('|',$all_char_num[$number]);
					$number++;
				}
				$op_data = array();
				foreach ($options as $kk=>$vv)
				{
					$op_data[] = array(
					    'survey_id'     => $v['survey_id'],
					    'problem_id'    => $v['id'],
					    'name'          => $vv,
					    'type'          => $v['type'],
					    'char_num'      => $char_num[$kk],
					    'is_other'      => 0,
					    );
				}
				$option = $this->insert_datas('options', $op_data,1);
				$problem[$k]['options'] = $option;
			}
			else
			{
				unset($problem[$k]['options']);
			}
		}
		return $problem;
	}
	**/

	
	public function create_option($table,$problem,$options,$char_num='')
	{
		if($options && is_array($options))
		{
			foreach ($options as $kk=>$vv)
			{
				$op_data[] = array(
					'survey_id'     => $problem['survey_id'],
					'problem_id'    => $problem['id'],
					'name'          => $vv,
					'type'          => $problem['type'],
					'char_num'      => intval($char_num[$kk]),
					'ini_num'       => $problem['ini_num'],
					'is_other'      => 0,
					 );
			}
			$option = $this->insert_datas('options', $op_data,1);
			$problem['options'] = $option;
		}
		else 
		{
			return false;
		}
		return $problem;
	}
	
	
	public function get_result($id)
	{
		if(!$id)
		{
			return false;
		}
		$ret = $this->detail($id);
		
		//得到以问题id为键值,答案为值的数组
		if(!$ret['problems'])
		{
			return $ret;
		}
		foreach($ret['problems'] as $k => $v)
		{
			if($v['is_other'] || $v['type'] == '3' || $v['type'] == '4')
			{
				$problem_id[] = $v['id'];
			}
		}
		if($problem_id)
		{
			$problem_id = implode(',',$problem_id);
			$sql = "SELECT r.*,p.user_id FROM " .DB_PREFIX. "result r LEFT JOIN " .DB_PREFIX. "record_person p ON r.person_id=p.id WHERE problem_id IN(" .$problem_id. ")  ";
			$q = $this->db->fetch_all($sql);
			foreach($q as $k => $v)
			{
				if($v['option_id'])
				{
					$option_id = explode(',',$v['option_id']);
					if(in_array('-1',$option_id))
					{
						$other_arr[$v['problem_id']][] = $v['answer'];
					}
				}
				if($v['answer'])
				{
					$arr[$v['problem_id']][] = $v['answer'];
				}
			}
		}
		//计算出每个选项被选择的百分比; 将上面得到的答案放到相对应的问题中
		foreach($ret['problems'] as $k => $v)
		{
			if($v['is_other'])
			{
				$ret['problems'][$k]['options'][] = $v['options'][] = array(
				    'id' => -1,
				    'name' => '其他',
				    'survey_id' => $ret['id'],
				    'problem_id' => $v['id'],
				    'is_other'  => 1,
				    'total' => count($other_arr[$v['id']]),
				    'other_total' => count($arr[$v['id']]),
				);
			}
			//$answer_total = $v['counts'];
			$answer_total = 0 + count($other_arr[$v['id']]);;
			if($v['options'] && $v['type'] != 3 &&  $v['type'] != 4)
			{
				foreach ($v['options'] as $ks=>$vs)
				{
					$answer_total += $vs['total'];
				}	
			}
			if($v['options'] && ($v['type'] == 1 || $v['type'] == 2))
			{
				foreach($v['options'] as $ke => $va)
				{
					if($answer_total)
					{
						$ret['problems'][$k]['options'][$ke]['percent'] = (round($va['total']/$answer_total*100)).'%';
					}
					else
					{
						$ret['problems'][$k]['options'][$ke]['percent'] = '0%';
					}
				}
			}
			if($v['type'] != 1)
			{
				if($v['type'] == 3)
				{
					for($i=0;$i<count($arr[$v['id']]);$i++)
					{
						$ret['problems'][$k]['answer'][] = unserialize($arr[$v['id']][$i]);
					}
				}
				else
				{
					$ret['problems'][$k]['answer'][] = $arr[$v['id']];
				}
				if($v['type'] == 4)
				{
					$ret['problems'][$k]['answer_count'] = count($ret['problems'][$k]['answer'][0]);
				}
				else
				{
					$ret['problems'][$k]['answer_count'] = count($ret['problems'][$k]['answer']);
				}
			}
		}
		return $ret;
	}
	
	/**
	 * 得到其他答案和对应的用户信息
	 * Enter description here ...
	 * @param unknown_type $problem_id 题目id
	 */
	public function get_other_result($problem_id = '',$limit)
	{
		//取问题类型
		$sql = "SELECT type,title FROM " .DB_PREFIX. "problem WHERE id=" .$problem_id;
		$type_result = $this->db->query_first($sql);
		$type = $type_result['type'];
		$title = $type_result['title'];
		//取填空题选项
		if($type == '3')
		{
			$sql = "SELECT id,name FROM " .DB_PREFIX. "options WHERE problem_id=" .$problem_id;
			$options = $this->db->fetch_all($sql);
		}
		
		//取答案
		$sql = "SELECT r.*,p.user_id FROM " .DB_PREFIX. "result r LEFT JOIN " .DB_PREFIX. "record_person p ON r.person_id=p.id WHERE problem_id=" .$problem_id. " AND answer != ''";
		$sql .= $limit;
		$q = $this->db->fetch_all($sql);
		if(count($q)<1)
		{
			$arr[0]['title'] = $title;
			return $arr;
		}
		//取会员信息
		foreach($q as $k => $v)
		{
			$member_ids[] = $v['user_id'];
		}
  		if($member_ids)
		{
			$member_ids = implode(',',$member_ids);
		}
		
        global $gGlobalConfig;
        if(!$gGlobalConfig['App_members'])
        {
            return false;
        }
        if($member_ids)
        {
        	$this->curl = new curl($this->settings['App_members']['host'], $this->settings['App_members']['dir']);
        	$this->curl->setSubmitType('post');
        	$this->curl->setReturnFormat('json');
        	$this->curl->initPostData();
        	$this->curl->addRequestData('member_id',$member_ids);
        	$this->curl->addRequestData('a','show');
        	$member_infos = $this->curl->request('member.php');
        }
        if(is_array($member_infos) && count($member_infos)>0)
        {
        	foreach($member_infos as $k => $v)
        	{
        		$member_info[$v['member_id']] = $v; //得到以member_id为键,用户信息为值的新的用户数组
        	}
        }
		foreach($q as $k => $v)
		{
			$arr[$k]['type'] = $type;
			$arr[$k]['title'] = $title;
			if($type == '3')
			{
				$arr[$k]['answer'] =  unserialize($v['answer']);
				if(is_array($options) && count($options))
				{
					foreach ($options as $op)
					{
						$arr[$k]['options'][$op['id']]= $op['name'];
					}
				}
			}
			else
			{
				$arr[$k]['answer'] =  $v['answer'];
			}
			$arr[$k]['member_info'] = $member_info[$v['user_id']];
		}
		
		return $arr;
	}
	
	/**
	 * 剔除数组中空元素,返回结果数组
	 * Enter description here ...
	 * @param $arr 被处理数组
	 */
	/*
	public function qukong($arr = array())
	{
		if(is_array($arr))
		{
			foreach($arr as $k => $v)
			{
				if(is_array($v))
				{
					foreach($v as $key => $val)
					{
						if(!trim($val))
						{
							unset($arr[$k][$key]);
						}
					}
				}
			}
		}
		return $arr;
	}
	
	*/
	
	/**
	 * 获取全局信息（云问卷使用）
	 * @param unknown_type $id
	 */
	public function get_survey_info($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "survey  WHERE id = '" .$id. "'";
		$row = $this->db->query_first($sql);
		if(!$row)
		{
			return false;
		}
		$row['start_time'] = $row['start_time'] ? date('Y-m-d H:i:s',$row['start_time']) : 0;
		$row['end_time'] = $row['end_time'] ? date('Y-m-d H:i:s',$row['end_time']) : 0;
		$row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : array();
		$row['header_info']	= $row['header_info'] ? unserialize($row['header_info']) : array();
		$row['footer_info']	= $row['footer_info'] ? unserialize($row['footer_info']) : array();
		if($row['question_time']) //初始化需要用时 时分秒
		{
			$row['use_hour'] = intval($row['question_time']/3600);
			$row['use_minute'] = intval(($row['question_time']%3600)/60);
			$row['use_second'] = intval(($row['question_time']%3600)%60);
		}
		if($row['picture_ids'])
		{
			$row['pictures'] = $this->get_images($row['picture_ids']); //获取图片
		}
		if($row['video_ids'])
		{
			$row['videos'] = $this->get_vod_info_by_id($row['video_ids']); //获取视频信息
		}
		if($row['audio_ids'])
		{
			$row['audios'] = $this->get_vod_info_by_id($row['audio_ids']); //获取视频信息
		}
		if($row['publicontent_ids'])
		{
			$row['publicontents'] = $this->get_publicontent($row['publicontent_ids']); //获取引用信息
		}
		return $row;
	
	}	
	
	public function get_child_problem($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "options  WHERE survey_id = " . $id .  ' ORDER BY order_id ASC ,id ASC';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$op[$row['problem_id']][] = $row;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "problem  WHERE survey_id = " . $id .  ' ORDER BY order_id ASC ,id ASC';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['name'] 		= $r['title'];
			$r['picture'] 	= $r['picture'] ? unserialize($r['picture']) : array();
			$r['type_name'] = $this->settings['type'][$r['type']]; //获取类型名称 单选，多选，填空，问答
			$r['mode_type'] = $this->settings['mode_type'][$r['type']]; //获取类型
			$r['form_type'] = $r['type']; //获取类型
			$r['max'] 		= $r['max_option'];
			$r['min'] 		= $r['min_option'];
			$r['options'] 	= $op[$r['id']] ? $op[$r['id']] : array();
			if($r['is_other'])
			{
				$r['options'][] = array(
					'id'			=> '-1',
					'survey_id'		=>	$id,
					'problem_id'	=>	$r['id'],
					'name'			=> '其他',
					'is_other'		=> 1,
				);
			}
			$r['unique_name'] = 'answer['.$r['id'].']';
			if($r['type'] == 2)
			{
				$r['unique_name'] .= '[]';
			}
			$info[] = $r;
		}
		if(!$info)
		{
			return false;
		}
		return $info;
	}
	
	public function process_data($data,$id,$is_create = 0)
	{
		if(!$data)
		{
			return false;
		}
		if(!$is_create)
		{
			$sql = 'SELECT id,problem_id FROM '.DB_PREFIX.'options WHERE survey_id = '.$id;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$_option_id[$r['problem_id']][] = $r['id'];
			}
		}
		foreach ($data as $k=>$v)
		{
			$problems = array(
				'survey_id'    => $id,
	    		'title'        => $v['title'],
	    		'description'  => $v['description'] ? $v['description'] : $v['brief'],
	    		'type'         => $v['type'] ? $v['type'] : $v['form_type'],
	    		'is_required'  => $v['is_required'] ? 1 : 0,
	    		'is_other'     => $v['is_other'] ? 1 : 0,
	    		'tips'         => $v['tips'],
	    		'min_option'   => $v['min'] ? $v['min'] : $v['min_option'],
	    		'max_option'   => $v['max'] ? $v['max'] : $v['max_option'],
	    		'order_id'     => $k,
			);
			$v['options'] = $v['options'] ? $v['options'] : $v['optArr'];
			if($v['id'] && !$is_create)
			{
				$affected_rows = $this->update($v['id'], 'problem',$problems);
			}
			else
			{
				if($pid = $this->create('problem',$problems,0))
				{
					$affected_rows = 1;
					$v['id'] = $pid['id'];
				}
			}
			if($v['options'])
			{
				foreach ($v['options'] as $kk=>$value)
				{
					$options = array(
						'survey_id'     => $id,
						'problem_id'    => $v['id'],
						'name'          => $value['name'],
						'type'          => $v['type'],
						'char_num'      => $v['char_num'] ? $v['char_num'] : 0,
						'order_id'		=> $kk,
					);
					if($value['id'])
					{
						$option_id[$v['id']][] = $value['id'];
						$affected_rows = $this->update($value['id'],'options', $options) || $affected_rows ? 1 : 0 ;
					}
					else 
					{
						$insert_option[] = $options;
					}
				}
				if(!$is_create && $_option_id[$v['id']] && $option_id[$v['id']])
				{
					$del_option_id = array_diff($_option_id[$v['id']],$option_id[$v['id']]);
					if($del_option_id)
					{
						$del_option_ids[] = implode(',',$del_option_id);
					}
				}
			}
		}
		if($del_option_ids)
		{
			$detele_options = implode(',',$del_option_ids);
			//删除选项表
			$sql = " DELETE FROM " .DB_PREFIX. "options WHERE id IN (" . $detele_options . ")";
			$this->db->query($sql);
			$affected_rows = 1;
		}
		$affected_rows = $this->insert_datas('options', $insert_option) || $affected_rows ? 1 : 0;
		return $affected_rows;
	}
	
	/**
	 * 统计调查结果
	 * Enter description here ...
	 * @param  $id 问卷id
	 */
	
	public function get_survey_result($id)
	{
		if(!$id)
		{
			return false;
		}
		$ret = $this->get_survey('id='.$id);
		$problem = $this->get_child_problem($id);
		//得到以问题id为键值,答案为值的数组
		if(!$problem || !$ret)
		{
			return false;
		}
		foreach($problem as $v)
		{
			if($v['is_other'] || $v['type'] == '3' || $v['type'] == '4')
			{
				$problem_id[] = $v['id'];
			}
		}
		
		if($problem_id)
		{
			$problem_id = implode(',',$problem_id);
			$sql = "SELECT * FROM " .DB_PREFIX. "result r left join " .DB_PREFIX. "record_person rp on r.person_id = rp.id WHERE r.problem_id IN(" .$problem_id. ")  ";
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				if($r['option_id'] && in_array('-1',explode(',',$r['option_id'])))
				{
					$other_arr[$r['problem_id']][] = array(
							'title' => $r['answer'],
							'id'	=> $r['user_id'],
							'name'	=> $r['user_name'],
						);
				}
				if($r['answer'])
				{
					$arr[$r['problem_id']][] = array(
							'title' => $r['answer'],
							'id'	=> $r['user_id'],
							'name'	=> $r['user_name'],
						); 
				}
				if($r['user_id'])
				{
					$member_id[] = $r['user_id'];
				}
			}
		}
		if($member_id && $this->settings['App_members'])
		{
			$member_ids = implode(',',$member_id);
        	$this->curl = new curl($this->settings['App_members']['host'], $this->settings['App_members']['dir']);
        	$this->curl->setSubmitType('post');
        	$this->curl->setReturnFormat('json');
        	$this->curl->initPostData();
        	$this->curl->addRequestData('member_id',$member_ids);
        	$this->curl->addRequestData('a','show');
        	$member_infos = $this->curl->request('member.php');
			if(is_array($member_infos) && count($member_infos)>0)
	        {
	        	foreach($member_infos as $k => $v)
	        	{
	        		$member_info[$v['member_id']] = $v; //得到以member_id为键,用户信息为值的新的用户数组
	        	}
	        }
		}
		
		//计算出每个选项被选择的百分比; 将上面得到的答案放到相对应的问题中
		foreach($problem as $k => $v)
		{
			if($arr[$v['id']])
			{
				foreach ($arr[$v['id']] as $kk=>$vv)
				{
					$arr[$v['id']][$kk]['avatar'] = $member_info[$vv['user_id']]['avatar'] ? $member_info[$vv['user_id']]['avatar'] : array();
				}
			}
			$v['answer'] = $arr[$v['id']] ? $arr[$v['id']] : array();
			//$answer_total = $v['counts'];
			$answer_total = 0 + count($other_arr[$v['id']]);;
			if($v['options'] && $v['type'] != 3 &&  $v['type'] != 4)
			{
				foreach ($v['options'] as $ks=>$vs)
				{
					$answer_total += $vs['total'];
				}	
			}
			if($v['options'] && $v['type'] != 3 &&  $v['type'] != 4 )
			{
				foreach($v['options'] as $ke => $va)
				{
					if($va['id'] == '-1')
					{
						$va['total'] = count($other_arr[$v['id']]);
						$va['percent'] = $v['counts'] ? (round(count($other_arr[$v['id']])/$answer_total*100,2)).'%' :  '0%';
						$va['other_total'] = count($arr[$v['id']]);
					}
					else{
						$va['percent'] = $v['counts'] ? (round($va['total']/$answer_total*100,2)).'%' :  '0%';
					}
					$v['options'][$ke] = $va;
				}
			
			}else
			{
				$v['answer_count'] = count($v['answer']);
			}
			$pro[] = $v;
		}
		$ret['problems'] = $pro;
		return $ret;
	}
	
	public function get_one_problem($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'problem WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$sql = 'SELECT * FROM '.DB_PREFIX.'options WHERE problem_id = '.$id;
		$query = $this->db->query($sql);
		while($r = $this->db->fetch_array($query))
		{
			$ret['options'][] = $r;
		}
		return $ret;
	}
	
	
	/**
	 * 外部接口显示
	 */
	public function getResult($id = '',$nq = 0)
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = 'SELECT submit_num,ini_num FROM '.DB_PREFIX.'survey WHERE id = '.$id;
		$c = $this->db->query_first($sql);
		$total = $c['submit_num'] + $c['ini_num'];
		
		$is_other = 0;
		$sql = 'SELECT id,counts,title,ini_num,more,is_other,type FROM '.DB_PREFIX.'problem WHERE survey_id = '.$id.' ORDER BY order_id asc';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$is_other = $r['is_other'] ? 1 : $is_other;
			$arr = array(
					'id'	=> $r['id'],
					'title'	=> $r['title'],
					'name'	=> $r['title'],
					'more'	=> $r['more'],
					'counts'	=> 0,//$r['counts'] + $r['ini_num'],
					'is_other'	=> $r['is_other'],
					'type'		=> $r['type'],
					'mode_type'		=> $this->settings['mode_type'][$r['type']],
				);
			if($r['type'] <= 2)
			{
				$p[$r['id']] = $arr;
			}elseif($nq)
			{
				$p[$r['id']] = $arr;
			}
		}
		$sql = 'SELECT id,total,problem_id,name,ini_num FROM '.DB_PREFIX.'options WHERE survey_id = '.$id.' ORDER BY order_id asc';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$p[$r['problem_id']]['counts'] += ($r['total'] + $r['ini_num']); 
			$o[$r['problem_id']][] = array(
				'id'	=> $r['id'],
				'name'	=> $r['name'],
				'total'	=> $r['total'] + $r['ini_num'],
				//'percent'	=> $p[$r['problem_id']]['counts'] ? round(($r['total'] + $r['ini_num'])/$p[$r['problem_id']]['counts']*100,2).'%'  : 0,
			);
		}
		if($is_other)
		{
			$sql = 'SELECT problem_id,count(*) as total,answer FROM '.DB_PREFIX.'result WHERE survey_id = '.$id.' AND option_id LIKE "%-1%" GROUP BY problem_id';
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$p[$r['problem_id']]['counts'] += $r['total'] ; 
				$o[$r['problem_id']][] = array(
					'id'	=> -1,
					'name'	=> '其他',
					'total'	=> intval($r['total']),
					//'percent'	=> $p[$r['problem_id']]['counts'] ? round(($r['total'] + $r['ini_num'])/$p[$r['problem_id']]['counts']*100,2).'%'  : 0,
				);
			}
		}
		if($nq)
		{
			$sql = 'SELECT problem_id,answer FROM '.DB_PREFIX.'result WHERE survey_id = '.$id;
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				if($r['answer'])
				{
					$p[$r['problem_id']]['answer'][] = array(
						'title'	=> $r['answer'],
					); 
				}
			}
		}
		if($o)
		{
			foreach ($o as $k=>$v)
			{
				if($v)
				{
					foreach ($v as $kk=>$vv)
					{
						$o[$k][$kk]['percent'] = $p[$k]['counts'] ? round($vv['total']/$p[$k]['counts']*100,2).'%' : '0%';
						$data['p_'.$k.'_'.$vv['id']] = $vv['total'];
					}
				}
			}
		}
		if($p)
		{
			foreach ($p as $v)
			{
				$v['options'] = $o[$v['id']] ? $o[$v['id']] : array();
				$v['answer_count']	= count($v['answer']);
				$problem[] = $v;
			}
		}
		$ret['total'] = $total ? $total : 0;
		$ret['data'] = $data ? $data : array();
		$ret['problems'] = $problem ? $problem : array();
		return $ret;
	}
	
	public function result($id = '')
	{
		$info = $this->get_survey('id =' .$id,'title,brief,create_time,ini_num,submit_num');
		$result = $this->getResult($id,1);
		$ret = array(
			'id'			=> $id,
			'title'			=> $info['title'],
			'brief'			=> $info['brief'],
			'create_time'	=> date('Y-m-d H:s',$info['create_time']),
			'total'			=> $result['total'],
			'problems'		=> $result['problems'],
			'status_flag'	=> $info['end_time'] && $info['end_time'] < TIMENOW ? '已结束' : '正在进行中',
		);
		return $ret;
	}
}
?>