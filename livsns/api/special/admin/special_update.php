<?php
require('global.php');
require_once('../lib/functions.php');
define('MOD_UNIQUEID','special');//模块标识
require_once(ROOT_PATH.'lib/class/curl.class.php');
class specialUpdateApi extends adminUpdateBase
{

	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/special.class.php');
		$this->obj = new special();	
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{	
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'special_sort WHERE id IN('.$this->input['sort_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$nodes['column_id'] = $this->input['column_id'];
		$nodes['published_column_id'] = '';
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		/********创建数据上限判断**********/
		$create_data_limit = $this->user['prms']['default_setting']['create_data_limit'];
		if($create_data_limit)
		{
			$sql = "SELECT count(*) FROM ".DB_PREFIX."special WHERE user_id = ".$this->user['user_id'];
			$count = $this->db->query_first($sql);
			if($count['count']>$create_data_limit)
			{
				$return = array(
				'error'    => '您只能添加'.$create_data_limit.'条数据',
				);
				$this->addItem($return);
				$this->output();exit;
			}
		}
		/********创建数据上限判断**********/	
		
		$name = $this->input['name'];
		if(!$name)
		{
			$return = array(
				'error'    => '请填写专题名称',
			);
			$this->addItem($return);
			$this->output();exit;
		}
		$name = $this->input['name'];
		/*$sql = "select id from " . DB_PREFIX . "special where name = '".$name."'";
		$q = $this->db->query_first($sql);
		if($q['id'])
		{
			$return = array(
				'error'    => '专题名已存在',
			);
			$this->addItem($return);
			$this->output();exit;
		}*/	
			
		$info = $pic_info = $top_pic = $client_pic = array();
		$pub_time  = $this->input['pub_time']?strtotime($this->input['pub_time']):TIMENOW;
		
		if($this->input['column_dir'])
		{
			$column_dir = trim(urldecode($this->input['column_dir']),'/');
			$column_dir = '/'.$column_dir;
		}
		else
		{
			$column_dir = '';
		}
		
		$info = array(
			'name'			=> $name,
            'sort_id'		=> intval($this->input['sort_id']),
            'tcolor' 		=> $this->input['tcolor'],
			'isbold' 		=> intval($this->input['isbold']),
			'isitalic' 		=> intval($this->input['isitalic']),
			'weight' 		=> intval($this->input['weight']),
			'keywords' 		=> str_replace(' ',',',trim($this->input['keywords'])),
            'user_id'		=> $this->user['user_id'],
			'column_id'		=> $this->input['column_id'],
			'pub_time' 		=> $pub_time,
			'custom_filename'=> $this->input['custom_filename'],
            'org_id'		=> $this->user['org_id'],
            'user_name'		=> $this->user['user_name'],
            'ip'			=> $this->user['ip'],
			'update_time'	=> TIMENOW,
			'create_time'	=> TIMENOW,
			'maketype'			=> $this->input['maketype'],
			'column_domain'		=> $this->input['column_domain'],
			'column_dir'		=> $column_dir,
		);
		$pregreplace           = array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
        $pregfind              = array(' ', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
        $info['brief']          = addslashes(str_replace($pregfind, $pregreplace, $this->input['brief']));
        
		if($_FILES['Filedata'])
		{
			$file['Filedata'] = $_FILES['Filedata'];
			$pic_info = $this->material->addMaterial($file,'','','-1'); //插入示意图
			if($pic_info)
			{
				$arr = array(
					'host'			=>$pic_info['host'],
					'dir'			=>$pic_info['dir'],
					'filepath'		=>$pic_info['filepath'],
					'filename'		=>$pic_info['filename'],
				);
				$info['pic'] =	serialize($arr);
			}
		}
		if($_FILES['bigFiledata'])
		{
			$top_file['Filedata'] = $_FILES['bigFiledata'];
			$top_pic = $this->material->addMaterial($top_file,'','','-1'); //插入示意图
			if($top_pic)
			{
				$arr_ = array(
					'host'			=>$top_pic['host'],
					'dir'			=>$top_pic['dir'],
					'filepath'		=>$top_pic['filepath'],
					'filename'		=>$top_pic['filename'],
				);
				$info['top_pic'] =	serialize($arr_);
			}
		}
		
		if($this->input['client_pic'])
		{
			foreach($this->input['client_pic'] as $k=>$v)
			{
				$client_pic[$k] = unserialize(html_entity_decode($v));
			}
			$info['client_pic'] =	serialize($client_pic);
		}
		else
		{
			$info['client_pic'] = '';
		}
		
		if($this->input['client_top_pic'])
		{
			foreach($this->input['client_top_pic'] as $k=>$v)
			{
				$client_top_pic[$k] = unserialize(html_entity_decode($v));
			}
			$info['client_top_pic'] =	serialize($client_top_pic);
		}
		else
		{
			$info['client_top_pic'] = '';
		}
		
		$this->check_weight_prms($info['weight']);
		$this->verify_content_prms($nodes);
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();		
		$column_id = $info['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
		$info['column_id'] = $info['column_id'] ? serialize($info['column_id']) : '';
		// if ($this->user['group_type'] <= MAX_ADMIN_TYPE)
        // {
			// $info['state'] = $info['column_id'] ? '1' : $this->get_status_setting('create');
        // }
        // else
        // {
        	// if (in_array('audit', (array) $this->user['prms']['app_prms'][APP_UNIQUEID]['action']))
	        // {
	        	// $info['state'] = $info['column_id'] ? '1' : $this->get_status_setting('create');
	        // }
        // }
        
        $info['state'] = $this->get_status_setting('create');
       
		$ret = $this->obj->create($info);
		$this->obj->update_special(array('order_id' => $ret), 'special', " id IN({$ret})");
		
		$column_info = array(
			'column_name'			=>  DEFAULT_COLUMN ? DEFAULT_COLUMN : '默认栏目',
            'special_id'			=>  $ret,
		);
		$col_id = $this->obj->insert_data($column_info,'special_columns');
		$this->obj->update_special(array('order_id' => $col_id), 'special_columns', " id IN({$col_id})");
		
		if($this->input['new-summary'])
		{
			foreach($this->input['new-summary'] as $k=>$v)
			{
				if($v)
				{
					$new_summary[] = $v;
					$new_detail[] = $this->input['new-detail'][$k];
				}
			}
			if($new_summary[0])
			{
				$this->obj->insert_special_summary($ret,$new_summary,$new_detail);
			}
		}
		if($this->input['new-attach-id'])
		{
			$mater_ids = implode(',',$this->input['new-attach-id']);
			$this->obj->update_special(array('special_id' => $ret), 'special_material', " material_id IN({$mater_ids})");
		}
		
		if($this->input['new-video-id'])
		{
			$video_ids = implode(',',$this->input['new-video-id']);
			$this->obj->update_special(array('special_id' => $ret), 'special_material', " id IN({$video_ids})");
		}
		//放入发布队列
		if(intval($info['state']) == 1 && !empty($column_id))
		{
			$op = 'insert';
			$this->publish_insert_query($ret,$op);
		}		
		$info['id']	 = $ret;
		$this->addLogs('新增专题','',$info,$info['name']);
		
		$return = array(
			'success'    => true,
			'id'         => $ret,
		);
		if($this->input['flag'])
		{
			$info['app'] = APP_UNIQUEID;
			$info['module'] = MOD_UNIQUEID;
			$return = $info;
		}
		$this->addItem($return);
		$this->output();
	}
	
	public function update()
	{	
		$name = $this->input['name'];
		if(!$name)
		{
			$return = array(
				'error'    => '请填写专题名称',
			);
			$this->addItem($return);
			$this->output();exit;
		}
		$special_id = intval($this->input['id']);
		//查询修改专题之前的信息
		$sql = "SELECT * FROM " . DB_PREFIX ."special WHERE id = " . $special_id;
		$q = $this->db->query_first($sql);
		$pre_data = $q;
		
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_sort_ids = '';
			if($q['sort_id'])
			{
				$_sort_ids = $q['sort_id'];
			}
			if($this->input['sort_id'])
			{
				$_sort_ids  = $_sort_ids ? $_sort_ids . ',' . $this->input['sort_id'] : $this->input['sort_id'];
			}
			if($_sort_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'special_sort WHERE id IN('.$_sort_ids.')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$data['nodes'][$row['id']] = $row['parents'];
				}
			}
		}
		#####节点权限
		
		$data['id'] = $special_id;
		$data['user_id'] = $q['user_id'];
		$data['org_id'] = $q['org_id'];
		$data['column_id'] = $this->input['column_id'];
		$q['column_id'] = unserialize($q['column_id']);
		$data['published_column_id'] = '';
		$data['weight'] = $q['weight'];
		###获取默认数据状态
		if(!empty($q['column_id']))
		{
			$status = $this->get_status_setting('update_publish', $q['state']);
			//$this->errorOutput('true'.$status);
		}
		else 
		{			
			if(intval($q['state']) == 1)
			{
				$status = $this->get_status_setting('update_audit', $q['state']);
			}
			//$this->errorOutput('false'.$status);
		}
		######获取默认数据状态
		
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
			$data['published_column_id'] = implode(',', $ori_column_id);
		}
		$this->verify_content_prms($data);
		
		$info = $pic_info = $top_pic = $client_pic = array();
		$pub_time  = $this->input['pub_time']?strtotime($this->input['pub_time']):TIMENOW;
		
		if($this->input['column_dir'])
		{
			$column_dir = trim(urldecode($this->input['column_dir']),'/');
			$column_dir = '/'.$column_dir;
		}
		else
		{
			$column_dir = '';
		}
		$info = array(
			'id'			=> intval($this->input['id']),
			'name'			=> $name,
            'sort_id'		=> intval($this->input['sort_id']),
            'tcolor' 		=> $this->input['tcolor'],
			'isbold' 		=> intval($this->input['isbold']),
			'isitalic' 		=> intval($this->input['isitalic']),
			'weight' 		=> intval($this->input['weight']),
			'state'    		=> $status,
			'keywords' 		=> str_replace(' ',',',trim($this->input['keywords'])),
            'org_id'		=> $this->user['org_id'],
            'column_id'		=> $this->input['column_id'],
            'pub_time' 		=> $pub_time,
            'custom_filename'=> $this->input['custom_filename'],
			'update_time'	=> TIMENOW,
			'maketype'		=> $this->input['maketype'],
			'column_domain'	=> $this->input['column_domain'],
			'column_dir'	=> $column_dir,
		);
        
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $this->publish_column = new publishconfig();
        $info['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
        $info['column_id'] = $info['column_id'] ? serialize($info['column_id']) : '';        
		
		$pregreplace           = array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
        $pregfind              = array(' ', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
        $info['brief']          = addslashes(str_replace($pregfind, $pregreplace, $this->input['brief']));
        
		if($_FILES['Filedata'])
		{
			$file['Filedata'] = $_FILES['Filedata'];
			$pic_info = $this->material->addMaterial($file,'','','-1'); //插入示意图
			if($pic_info)
			{
				$arr = array(
					'host'			=>$pic_info['host'],
					'dir'			=>$pic_info['dir'],
					'filepath'		=>$pic_info['filepath'],
					'filename'		=>$pic_info['filename'],
					'imgwidth'		=>$pic_info['imgwidth'],
					'imgheight'		=>$pic_info['imgheight'],
				);
				$info['pic'] =	serialize($arr);
			}
		}
		if($_FILES['bigFiledata'])
		{
			$top_file['Filedata'] = $_FILES['bigFiledata'];
			$top_pic = $this->material->addMaterial($top_file,'','','-1'); //插入示意图
			if($top_pic)
			{
				$arr_ = array(
					'host'			=>$top_pic['host'],
					'dir'			=>$top_pic['dir'],
					'filepath'		=>$top_pic['filepath'],
					'filename'		=>$top_pic['filename'],
				);
				$info['top_pic'] =	serialize($arr_);
			}
		}
		
		if($this->input['client_pic'])
		{
			foreach($this->input['client_pic'] as $k=>$v)
			{
				$client_pic[$k] = unserialize(html_entity_decode($v));
			}
			$info['client_pic'] =	serialize($client_pic);
		}
		else
		{
			$info['client_pic'] = '';
		}
		
		if($this->input['client_top_pic'])
		{
			foreach($this->input['client_top_pic'] as $k=>$v)
			{
				$client_top_pic[$k] = unserialize(html_entity_decode($v));
			}
			$info['client_top_pic'] =	serialize($client_top_pic);
		}
		else
		{
			$info['client_top_pic'] = '';
		}
		
		$this->check_weight_prms($info['weight'], $data['weight']);
		
		$ret = $this->obj->update($info);
		
		$attach_id = $new_attach_id = array();
		if($this->input['new-attach-id'])
		{
			$new_attach_id = array_values($this->input['new-attach-id']);
			$nattachs = implode(",",$new_attach_id);
			$this->obj->update_special(array('special_id' => $this->input['id']), 'special_material', " material_id IN({$nattachs})");
		}
		
		if($this->input['attach-id'])
		{
			$attach_id = array_values($this->input['attach-id']);
		}
		
		if($del_attachs = array_diff($attach_id,$new_attach_id))
		{
			$deattachs = implode(",",$del_attachs);
			$this->obj->update_special(array('del' => '1'), 'special_material', " material_id IN({$deattachs})");
		}
		$new_video_id = array();
		if($this->input['new-video-id'])
		{
			$new_video_id = array_values($this->input['new-video-id']);
			$nvideos = implode(",",$new_video_id);
			$this->obj->update_special(array('special_id' => $this->input['id']), 'special_material', " id IN({$nvideos})");
		}
		
		if($this->input['video-id'])
		{
			$video_id = array_values($this->input['video-id']);
			if($del_videos = array_diff($video_id,$new_video_id))
			{
				$devideos = implode(",",$del_videos);
				$this->obj->update_special(array('del' => '1'), 'special_material', " id IN({$devideos})");
			}
		}
		
		if($this->input['summary']||$this->input['detail'])
		{
			foreach($this->input['summary'] as $k=>$v)
			{
				$this->obj->update_special(array('title' => $v,'content' => $this->input['detail'][$k]), 'special_summary', " id IN({$k})");
				$sum_id[] = $k;
			}
			$sumids = implode(',',$sum_id);
			$this->obj->update_special(array('del' => '1'), 'special_summary', " special_id = ".$info['id']." AND id NOT IN({$sumids})");
		}
		else
		{
			$this->obj->update_special(array('del' => '1'), 'special_summary', " special_id = ".$info['id']);
		}
		
		if($this->input['new-summary'])
		{
			foreach($this->input['new-summary'] as $k=>$v)
			{
				if($v)
				{
					$new_summary[] = $v;
					$new_detail[] = $this->input['new-detail'][$k];
				}
			}
			if($new_summary[0])
			{
				$this->obj->insert_special_summary($info['id'],$new_summary,$new_detail);
			}
		}
		$this->addLogs('修改专题',$pre_data,$info,$info['name']);	
		
		//更改专题后发布的栏目
		$ret = $this->obj->get_special(" id = {$special_id}", '*');
        $ret['column_id'] = unserialize($ret['column_id']);
        $new_column_id = array();
        if(is_array($ret['column_id']))
        {
            $new_column_id = array_keys($ret['column_id']);
        }        
        
		if(intval($ret['state']) ==1)
		{
			if(!empty($ret['expand_id']))   //已经发布过，对比修改先后栏目
			{					
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->publish_insert_query($ret['id'], 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->publish_insert_query($ret['id'], 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->publish_insert_query($ret['id'], 'update',$same_column);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				$this->publish_insert_query($ret['id'],$op);
			}
		}
		else    //打回
		{
			if(!empty($row['expand_id']))
			{
				$op = "delete";
				$this->publish_insert_query($ret['id'],$op);
			}
		}
		
		$return = array(
			'success'    => true,
			'id'         =>  $this->input['id'],
		);
		if($this->input['flag'])
		{
			$return = $ret;
		}
		$this->addItem($return);
		$this->output();
	}
	
	
	public function upload()
	{	
		$spe_mater = $material = array();
		$material = $this->material->addMaterial($_FILES,'','','-1');
		if($material)
		{
			$material['pic'] = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
			$material['pic'] = serialize($material['pic']);
			
			$spe_mater =	array(
					'material_id'       => $material['id'],
					'material'       	=> $material['pic'],
					'name'       		=> $material['name'],
					'mark'       		=> $material['mark'],
					'type'       		=> $material['type'],
					'filesize'   		=> $material['filesize'],
					'ip'				=> $this->user['ip'],
					'create_time'		=> TIMENOW,
			);
			
			$this->obj->insert_data($spe_mater,"special_material");
			
			$material['filesize'] = hg_bytes_to_size($material['filesize']);
			$return = array(
				'success'    => true,
				'id'         => $material['id'],
				'filename'   => $material['filename'] . '?' . hg_generate_user_salt(4),
				'name'       => $material['name'],
				'mark'       => $material['mark'],
				'type'       => $material['type'],
				'filesize'   => $material['filesize'],
				'path'       => $material['host'] . $material['dir'],
				'dir'        => $material['filepath'],
				'pic'        => $material['pic'],
			);
		}
		else
		{
			$return = array(
				'error' => '文件上传失败',
			);
		}
		
		$this->addItem($return);
		$this->output();		
	}
	
	public function select_video()
	{	
		$material = serialize($this->getvideobyid($this->input['material']));
		if($material)
		{
			$spe_mater =	array(
					'material_id'       => addslashes($this->input['material']),
					'material'       	=> $material,
					'mark'       		=> 'video',
					'ip'				=> $this->user['ip'],
					'create_time'		=> TIMENOW,
			);
			
			$re = $this->obj->insert_data($spe_mater,"special_material");
			
			$return = array(
				'success'    		=> true,
				'id'         		=> $re,
				'material'       	=> unserialize($material),
			);
		}
		else
		{
			$return = array(
				'error' => '视频选择失败',
			);
		}
		
		$this->addItem($return);
		$this->output();		
	}
	
	public function getvideobyid($id)
	{
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','id2videoid');
		$curl->addRequestData('id',$id);
		$ret = $curl->request('vod.php');
		$vodinfo = $ret[0];
		return $vodinfo = array('id'=>$vodinfo['id'],'img'=>$vodinfo['img_info'],'title'=>$vodinfo['title'],'host'=>$vodinfo['hostwork'],'dir'=>$vodinfo['video_path']);
	}
	
	public function delete()
	{		
		$ids = $this->input['id'];
		if(empty($ids))
		{
			$this->errorOutput('请选择需要删除的专题');
		}
		
		$sql_ = "SELECT * FROM " . DB_PREFIX  ."special WHERE id IN (" . $ids . ")";
		$q_ = $this->db->query($sql_);
		while($row = $this->db->fetch_array($q_))
		{
			$sort_arr[] = $row['sort_id'];
			$column_id = @unserialize($row['column_id']);
			if(intval($row['state']) == 1 && ($row['expand_id'] || $column_id))
			{
				$op = "delete";
				$this->publish_insert_query($row['id'],$op);
			}
			$data[$row['id']] = array(
				'title' => $row['name'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
				'catid' => $row['sort_id'],
				'user_id'=>$row['user_id'],
				'org_id'=>$row['org_id'],
				'id'=>$row['id'],
			);
			$sort_ids[] = $row['sort_id'];
		}
		
		$sql = "select id from " . DB_PREFIX . "special_content where special_id  IN (" . $ids . ")";
		$q = $this->db->query_first($sql);
		if($q['id'])
		{
			$this->errorOutput('请删除专题下的内容');
		}	
		
		if($sort_ids)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'special_sort WHERE id IN('.implode(',',$sort_ids).')';
			$query = $this->db->query($sql);
			$sort_ids_array = array();
			while($row = $this->db->fetch_array($query))
			{
				$sort_ids_array[$row['id']] = $row['parents'];
			}
		}
		#####整合数据进行权限
		if(!empty($data))
		{
			foreach ($data as $key=>$value)
			{
				if($value['catid'])
				{
					$value['nodes'][$value['catid']] = $sort_ids_array[$value['catid']];
				}
				$this->verify_content_prms($value);
			}
		}
		#####整合数据进行权限结束
		
		$ret = $this->obj->delete($ids);
		$this->addItem($ids);
		$this->output();
	}
	
	public function drag_order()
	{
		$ids       = explode(',',urldecode($this->input['content_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " .DB_PREFIX . "special  SET order_id = '".$order_ids[$k]."'  WHERE id = '".$v."'";
			$this->db->query($sql);
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX ."special WHERE id IN(" . $this->input['content_id'] . ")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			if(!empty($row['column_id']) && !empty($row['expand_id']))
			{
				$this->publish_insert_query($row['id'], 'update');
			}
		}
		$ids = explode(',',$this->input['content_id']);
		$this->addItem(array('id' =>$ids));
		$this->output();
	}
	
	public function sort()
	{
	}
	
	public function audit()
	{
		$this->verify_content_prms();
		
		$id = urldecode($this->input['id']); 
		if(!$id)
		{
			$this->errorOutput("未传入专题ID");
		}		
		$idArr = explode(',',$id);
		
		if(intval($this->input['audit']) == 1)
		{
			$this->obj->update_special(array('state' => 1), 'special', " id IN({$id})");
			$ret = $this->obj->get_special_list(" id IN({$id})");
			if(is_array($ret) && count($ret) > 0 )
			{
				foreach($ret as $info)
				{
					if(!empty($info['expand_id']))
					{
						$op = "update";			
					}
					else
					{
						if(@unserialize($info['column_id']))
						{
							$op = "insert";
						}			
					}
					$this->publish_insert_query($info['id'], $op);
					$stat_id[] = $info['id'];
					$stat_user_id[] = $info['user_id'];
					$stat_user_name[] = $info['user_name'];
				}
			}
			$return = array('status' => 1,'id'=> $idArr);	
			//审核通过
			$stat_opration = 'verify_suc';	
			$opration = '审核专题';	
		}
		else if(intval($this->input['audit']) == 0)
		{
			$this->obj->update_special(array('state' => 2), 'special', " id IN({$id})");
			$ret = $this->obj->get_special_list(" id IN({$id})");
			if(is_array($ret) && count($ret) > 0 )
			{
				foreach($ret as $info)
				{				
					$info['column_id'] = @unserialize($info['column_id']);
					if(!empty($info['expand_id']) || $info['column_id'])
					{
						$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
					}		
					else 
					{
						$op = "";
					}
					$this->publish_insert_query($info['id'], $op);
					
					$stat_id[] = $info['id'];
					$stat_user_id[] = $info['user_id'];
					$stat_user_name[] = $info['user_name'];
				}
			}
			$return = array('status' =>2,'id' => $idArr);
			
			$stat_opration = 'verify_fail';		
			$opration = '打回专题';	
		}
		
		if(!empty($stat_id))
		{
			$stat_data = array(
				'content_id' => implode(',',$stat_id),
				'contentfather_id' => '',
				'type' => $stat_opration,
				'user_id' => implode(',',$stat_user_id),
				'user_name' => implode(',',$stat_user_name),
				'before_data' => '',
				'last_data' => '',
				'num' => 1,
			);
			$this->addStatistics($stat_data);	
		}
		
		//$this->addLogs($opration,'','',$opration . '+' . $id);	
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 设置权重
	 * @name 		update_weight
	 */
	public function check_weight_prms($input_weight =  0, $org_weight = 0)
	{
		if($this->user['group_type'] < MAX_ADMIN_TYPE)
		{
			return;
		}
		$set_weight_limit = $this->user['prms']['default_setting']['set_weight_limit'];
		if($set_weight_limit)
		{
			if($org_weight > $set_weight_limit)
			{
				$this->errorOutput(MAX_WEIGHT_LIMITED);
			}
			if($input_weight > $set_weight_limit)
			{
				$this->errorOutput(MAX_WEIGHT_LIMITED);
			}
		}
	}
	
	public function update_weight()
	{
		//检测
		if(empty($this->input['data']))
		{
			$this->errorOutput(NO_DATA);
		}
		$data = $this->input['data'];
		$data = htmlspecialchars_decode($data);
		$data = json_decode($data,1);
		$id = @array_keys($data);
		if(!$id)
		{
			$this->errorOutput(INVALID_ARTICLE);
		}
		$sql = 'SELECT id,weight FROM '.DB_PREFIX.'special WHERE id IN('.implode(',', $id).')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$org_weight[$row['id']] = $row['weight'];
		}
		$sql = "CREATE TEMPORARY TABLE tmp (id int primary key, weight int)";
		$this->db->query($sql);
		$sql = "INSERT INTO tmp VALUES ";
		$space = '';
		$id = array();
		foreach ($data as $k => $v)
		{
			$sql .= $space . "(" . $k . ", ". $v .")"; 
			$this->check_weight_prms($v, $org_weight[$k]);
			$space = ',';
		}
		$this->db->query($sql);
		$sql = "UPDATE " . DB_PREFIX . "special a,tmp SET a.weight = tmp.weight WHERE a.id = tmp.id";
		$this->db->query($sql);		
		
		$id = implode(',',$id);
		$this->addLogs('修改权重','','', '修改权重' . $id);
		$this->addItem('true');
		$this->output();
	}	
	
	/**
	 * 移动
	 */
	public function move()
	{
		$id = urldecode($this->input['content_id']);
		$node_id = urldecode($this->input['node_id']);
		if(!$id)
		{
			$this->errorOutput('专题ID不能为空');
		}
		if($node_id)
		{
			$this->db->update_data(array('sort_id'=>$node_id), 'special', ' id IN('.$id.')');
		}
		$ret = array('success' => true, 'id' => $id);
		$this->addItem($ret);
		$this->output();
	}
	
	 /* 即时发布
	 * @param id  int   文章id
	 * @param column_id string  发布的栏目id
	 */
	public function publish()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');
		}
		$id = urldecode($this->input['id']);
		
		$this->input['pub_time']= '';
		$pub_time = $this->input['pub_time'] ? strtotime($this->input['pub_time']) : TIMENOW;
		$custom_filename = $this->input['custom_filename'];
		$column_id = urldecode($this->input['column_id']);
		$isbatch = strpos($id, ',');
		if($isbatch !== false && !$column_id)
		{
			$this->addItem(true);
			$this->output();
		}
		$new_column_id = explode(',',$column_id);
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();		
		$column_id = $this->publish_column->get_columnname_by_ids('id,name,parents',$column_id);
		//查询修改文章之前已经发布到的栏目
		$sql = "SELECT * FROM " . DB_PREFIX ."special WHERE id IN( " . $id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$column_url = unserialize($row['column_url']);
			$row['column_id'] = unserialize($row['column_id']);
			$ori_column_id = array();
			if(is_array($row['column_id']))
			{
				$ori_column_id = array_keys($row['column_id']);
			}
			$ori_column_id_str = $ori_column_id ? implode(',', $ori_column_id) : '';
			if($isbatch !== false)     //批量发布只能新增，so需要合并已经发布的栏目
			{
				$column = is_array($row['column_id']) ? ($row['column_id'] + $column_id) : $column_id;
				$column = $column ? serialize($column) : '';
			}
			else
			{
				$column = $column_id ? serialize($column_id) : '';
			}
			/***************************权限控制***************************************/
			$this->verify_content_prms(array('column_id' =>$this->input['column_id'], 'published_column_id'=>$ori_column_id_str));
			/***************************权限控制***************************************/
			$column_ids = unserialize($column);
			if($column_ids)
			{
				if($column_url && is_array($column_url))
				{
					foreach($column_url as $k=>$v)
					{
						if(!$column_ids[$k])
						{
							unset($column_url[$k]);
						}
					}
				}
				$column_url = $column_url ? serialize($column_url) : '';
				$expand_id = $row['expand_id'];
			}
			else
			{
				$column_url = '';
				$expand_id = '0';
			}
			
			$sql_ = "UPDATE " . DB_PREFIX ."special SET column_id = '". $column ."',pub_time = '".$pub_time."',custom_filename = '".$custom_filename."',column_url = '".$column_url."',expand_id = '".$expand_id."' WHERE id = " . $row['id'];
			$this->db->query($sql_);
			$sq = "SELECT * FROM " . DB_PREFIX ."special WHERE id = " . $row['id'];
			$ql = $this->db->query_first($sq);
			if(intval($ql['state']) ==1)
			{
				if(!empty($row['expand_id']))   //已经发布过，对比修改先后栏目
				{
					if($isbatch !== false)   //批量发布时，只能新增栏目，不能删除,不需要对比
					{
						$this->publish_insert_query($row['id'],'insert',$new_column_id);
					}
					else
					{						
						$del_column = array_diff($ori_column_id,$new_column_id);
						if(!empty($del_column))
						{
							$this->publish_insert_query($row['id'], 'delete',$del_column);
						}
						$add_column = array_diff($new_column_id,$ori_column_id);
						if(!empty($add_column))
						{
							$this->publish_insert_query($row['id'], 'insert',$add_column);
						}
						$same_column = array_intersect($ori_column_id,$new_column_id);
						if(!empty($same_column))
						{
							$this->publish_insert_query($row['id'], 'update',$same_column);
						}
					}
				}
				else 							//未发布，直接插入
				{
					$op = "insert";
					$this->publish_insert_query($row['id'],$op);
				}
			}
			else    //打回
			{
				if(!empty($row['expand_id']))
				{
					$op = "delete";
					$this->publish_insert_query($row['id'],$op);
				}
			}
		}
		$this->addItem('true');
		$this->output();
	}			
	

	/**
	 * 放入发布队列
	 */
	public function publish_insert_query($special_id,$op,$column_id = array(),$child_queue = 0,$is_childId = 0)
	{
		$id = intval($special_id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		if($is_childId)
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."special_material WHERE id = " . $id;
		}
		else 
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."special WHERE id = " . $id;
		}
		$info = $this->db->query_first($sql);
		if(empty($column_id))
		{		
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}			
		}
		else 
		{
			$column_id = implode(',',$column_id);
		}

 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=>	PUBLISH_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['name'],
			'action_type' => $op,
			'publish_time'  => $info['pub_time'],
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		if($child_queue)
		{
			$data['set_id'] = PUBLISH_SET_SECOND_ID;
		}
		if($is_childId)
		{
			$data['title'] = $info['name'];
		}
		$ret = $plan->insert_queue($data);
		return $ret;
	}	
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new specialUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>