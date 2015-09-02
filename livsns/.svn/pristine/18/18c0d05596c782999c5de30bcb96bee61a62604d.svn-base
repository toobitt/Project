<?php
define('MOD_UNIQUEID','article');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/article_mode.php');
class article_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new article_mode();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		/*增加新闻 权限判断*/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'manage_period')); //期刊操作权限
			/**************节点权限*************/
			$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($prms_epaper_ids && implode(',', $prms_epaper_ids) != -1 &&!in_array($this->input['epaper_id'],$prms_epaper_ids))
			{
				$this->errorOutput('没有权限');
			}
			/*********************************/
		}
		$title = trim($this->input['news_title']);
		if(!$title)
		{
			$this->errorOutput('请填文章标题');
		}
		$content = trim($this->input['content']);
		if(!$content)
		{
			$this->errorOutput('请填写文章内容');
		}
		if(!$this->input['epaper_id'])
		{
			$this->errorOutput('缺少epaper_id');
		}
		if(!$this->input['period_id'])
		{
			$this->errorOutput('报刊下面没有期刊或缺少period_id');
		}
		if(!$this->input['page_id'])
		{
			$this->errorOutput('缺少page_id');
		}
		
		$subtitle = trim($this->input['subtitle']);
		$data = array(
			'title'			=>	$title,								//标题
			'brief'			=>  trim($this->input['brief']),
			'period_id'		=>	$this->input['period_id'],			//往期id
			'stack_id'		=>	$this->input['stack_id'],			//叠id
			'page_id'		=>	$this->input['page_id'],			//版页id
			'author'		=>	trim($this->input['author']),		//作者
			'photoer'		=>	trim($this->input['photoer']),		//摄影记者
			'source'		=>	trim($this->input['source']),		//文章来源
			'org_id'		=>	$this->user['org_id'],				
			'user_id'		=>	$this->user['user_id'],				//用户ID
			'user_name'		=>	$this->user['user_name'],			//发布者
			'create_time'	=>	TIMENOW,							//创建时间
			'update_time'	=>	TIMENOW,							//更新时间
			'ip'			=>	hg_getip(),
			'appid'			=>	$this->input['appid'],				//客户端id
			'appname'		=>	$this->input['appname'],			//客户端名称
			'epaper_id'		=>	$this->input['epaper_id'],			//报刊id
		);
		
		if(!$this->input['subtitle_type'])
		{
			$data['subtitle'] = $subtitle;
		}
		else 
		{
			$data['subtitle1'] = $subtitle;
		}
		
		
		$content=str_replace("&nbsp;"," ",$this->input['content']);
		//将html实体转换为html标记，便于正则表达式匹配
		$content = html_entity_decode($content);
		
		$ret = $this->mode->create($data,$content);
		if($ret)
		{
			//更新素材
		    $material_id=$this->input['material_id'];
		    if(!empty($material_id))
			{
				$article_id = $ret['id'];
				$material_id = is_array($material_id) ? $material_id : explode(',',$material_id);
				$material_history = array();
				if(trim(urldecode($this->input['material_history'])))
				{
					$material_history = explode(',',urldecode($this->input['material_history']));
				}
				$del_material = array_diff($material_history,$material_id);
				$mid_str = implode(',',$material_id);
				if(!empty($del_material))
				{
					$del_material = implode(',',$del_material);
					$this->mater->delMaterialById($del_material); 
					
					$sql="DELETE FROM " . DB_PREFIX . "material WHERE material_id IN(" . $del_material . ")";
					$this->db->query($sql);
				}
				$this->mater->updateMaterial($mid_str,$article_id);  //更新cid
				
				$sql = "UPDATE " . DB_PREFIX . "material SET cid=" . $article_id . " WHERE material_id IN (" . $mid_str . ")";
				$this->db->query($sql);
			}
			
			$this->addLogs('创建',$ret,'','添加新闻' . $ret['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function update()
	{
		$id = intval($this->input['news_title_id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$epaper_id = $this->input['epaper_id'];
		if(!$epaper_id)
		{
			$sql = 'SELECT epaper_id FROM ' . DB_PREFIX . 'article WHERE id = ' .$id;
			$re = $this->db->query_first($sql);
			$epaper_id = $re['epaper_id'];
		}
		
		/************** 节点权限判断 *************/
		$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_epaper_ids && implode(',', $prms_epaper_ids) != -1 && !in_array($epaper_id,$prms_epaper_ids))
		{
			$this->errorOutput('您没有更新此新闻的权限');
		}
		/***************************************/
		/**************更新他人数据权限判断***************/
		$sql = "select * from " . DB_PREFIX ."article where id = " . $id;
		$q = $this->db->query_first($sql);
		$info['id'] = $id;
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage_period';
		$this->verify_content_prms($info);
		/*********************************************/
		
		$title = trim($this->input['news_title']);
		if(!$title)
		{
			$this->errorOutput('请填文章标题');
		}
		$content = trim($this->input['content']);
		if(!$content)
		{
			$this->errorOutput('请填写文章内容');
		}
		$subtitle = trim($this->input['subtitle']);
		$update_data = array(
			'title'			=>	trim($this->input['news_title']),	//标题
			'period_id'		=>	$this->input['period_id'],			//往期id
			'stack_id'		=>	$this->input['stack_id'],			//叠id
			'page_id'		=>	$this->input['page_id'],			//版页id
			'brief'			=>	trim($this->input['brief']),		//副标题
			'author'		=>	trim($this->input['author']),		//作者
			'photoer'		=>	trim($this->input['photoer']),		//摄影记者
			'source'		=>	trim($this->input['source']),		//文章来源
			'org_id'		=>	$this->user['org_id'],				
			'user_id'		=>	$this->user['user_id'],				//用户ID
			'user_name'		=>	$this->user['user_name'],			//发布者
			'create_time'	=>	TIMENOW,							//创建时间
			'update_time'	=>	TIMENOW,							//更新时间
			'ip'			=>	hg_getip(),
			'appid'			=>	$this->input['appid'],				//客户端id
			'appname'		=>	$this->input['appname'],			//客户端名称
			'epaper_id'		=>	$this->input['epaper_id'],			//报刊id
		);
		
		if(!$this->input['subtitle_type'])
		{
			$update_data['subtitle'] = $subtitle;
		}
		else 
		{
			$update_data['subtitle1'] = $subtitle;
		}
		
		$content = $this->input['content'];							//文章内容
		$ret = $this->mode->update($id,$update_data,$content);
		
		if($ret)
		{
			//更新素材
		    $material_id=$this->input['material_id'];
		    
		    if($material_id)
		    {
		    	$material_id = is_array($material_id) ? $material_id : explode(',',$material_id);
		    }
		    else 
		    {
		    	$material_id = array();
		    }
		    
		    
			//新增图片
		    if(!empty($material_id))
			{
				$mid_str = implode(',',$material_id);
				$this->mater->updateMaterial($mid_str,$id);  //更新cid
				
				$sql = "UPDATE " . DB_PREFIX . "material SET cid=" . $id . " WHERE material_id IN (" . $mid_str . ")";
				$this->db->query($sql);
			}
			
			//删除图片
			$material_history = array();
			if(trim(urldecode($this->input['material_history'])))
			{
				$material_history = explode(',',urldecode($this->input['material_history']));
			}
			$del_material = array_diff($material_history,$material_id);
			if(!empty($del_material))
			{
				$del_material = implode(',',$del_material);
				$this->mater->delMaterialById($del_material); 
				
				$sql="DELETE FROM " . DB_PREFIX . "material WHERE material_id IN(" . $del_material . ")";
				$this->db->query($sql);
			}
			
			$this->addLogs('更新',$ret,'','更新新闻' . $this->input['news_title_id']);//此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if(!$epaper_id)
		{
			$sql = 'SELECT epaper_id FROM ' . DB_PREFIX . 'article WHERE id IN ('.$this->input['id'].')';
			$re = $this->db->query_first($sql);
			$epaper_id = $re['epaper_id'];
		}
		/************** 节点权限判断 *************/
		$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_epaper_ids && implode(',', $prms_epaper_ids) != -1 && !in_array($epaper_id,$prms_epaper_ids))
		{
			$this->errorOutput('您没有删除此新闻的权限');
		}
		/***************************************/
		/**************更新他人数据权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'article WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage_period'));
			}
		}
		/*********************************************/
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除',$ret,'','删除新闻' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		
	}
	//上传附件
	public function upload()
	{	
		$material = $this->mater->addMaterial($_FILES,0,0,intval($this->input['water_config_id']));
		if(!empty($material))
		{
			$material['material_id'] = $material['id'];
			/*$material['pic'] = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
			$material['pic'] = serialize($material['pic']);
			unset($material['bundle_id'], $material['mid'], $material['id'], $material['url']);
			*/
			
			$data = array(
				'material_id' 	=> $material['id'],
				'name'			=> $material['name'],
				'host'			=> $material['host'],
				'dir'			=> $material['dir'],
				'filepath'		=> $material['filepath'],
				'filename'		=> $material['filename'],
				'type'			=> $material['type'],
				'mark'			=> $material['mark'],
				'imgwidth'		=> $material['imgwidth'],
				'imgheight'		=> $material['imgheight'],
				'filesize'		=> $material['filesize'],
				'create_time'	=> $material['create_time'],
				'ip'			=> $material['ip'],
			);
			//插入素材库
			$this->mode->insert_data($data,"material");
			
			$material['filesize'] = hg_bytes_to_size($material['filesize']);
			$return = array(
				'success'    => true,
				'id'         => $material['material_id'],
				'filename'   => $material['filename'] . '?' . hg_generate_user_salt(4),
				'name'       => $material['name'],
				'mark'       => $material['mark'],
				'type'       => $material['type'],
				'filesize'   => $material['filesize'],
				'path'       => $material['host'] . $material['dir'],
				'dir'        => $material['filepath'],
			);
		}
		else
		{
			$return = array(
				'error' => '文件上传失败',
			);
		}
		$this->addLogs('上传图片','','', $return['name']);
		$this->addItem($return);
		$this->output();		
	}
	
	public function update_title()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('文章id不存在');
		}
		
		$epaper_id = $this->input['epaper_id'];
		if(!$epaper_id)
		{
			$sql = 'SELECT epaper_id FROM ' . DB_PREFIX . 'article WHERE id = ' .$id;
			$re = $this->db->query_first($sql);
			$epaper_id = $re['epaper_id'];
		}
		
		/************** 节点权限判断 *************/
		$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_epaper_ids && implode(',', $prms_epaper_ids) != -1  && !in_array($epaper_id,$prms_epaper_ids))
		{
			$this->errorOutput('您没有更新此新闻的权限');
		}
		/***************************************/
		/**************更新他人数据权限判断***************/
		$sql = "select * from " . DB_PREFIX ."article where id = " . $id;
		$q = $this->db->query_first($sql);
		$info['id'] = $id;
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage_period';
		$this->verify_content_prms($info);
		/*********************************************/
		
		$title = trim($this->input['title']);
		$data = array(
			'title' => $title,
		);
		
		$res = $this->mode->update($id,$data);
		
		//更新热区中的新闻标题
		if($res)
		{
			$page_id = $q['page_id'];
			$sql = "SELECT hot_area FROM ".DB_PREFIX."page WHERE id = ".$page_id;
			$q = $this->db->query_first($sql);
			
			if($q['hot_area'])
			{
				$hot_area = unserialize($q['hot_area']);
				foreach ($hot_area as $k => $v)
				{
					if($v['id'] == $id)
					{
						$v['title'] = $title;
					}
					$new_hot_area[] = $v;
				}
				
				if($new_hot_area)
				{
					$new_hot_area = serialize($new_hot_area);
				}
				else 
				{
					$new_hot_area = '';
				}
				$sql = "UPDATE ".DB_PREFIX."page SET hot_area = '".$new_hot_area."'  WHERE id  = ".$page_id;
				$this->db->query($sql);
			}
		}
		$this->addItem($data);
		
		$this->output();
	}

	public function create_page()
	{
		$stack_id = intval($this->input['stack_id']);
		
		if(!$stack_id)
		{
			$this->errorOutput('叠id不存在');
		}
		
		
		$period_id = intval($this->input['period_id']);
		if(!$period_id)
		{
			$this->errorOutput('期id不存在');
		}
		
		
		$page_num = $this->input['page_num'];
		$data = array(
			'period_id'			=> $period_id,
			'stack_id'			=> $stack_id,
			'page'				=> $page_num,
		);
		
		//创建版页
		$sql = " INSERT INTO " . DB_PREFIX . "page SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$page_id = $this->db->insert_id();
		
		
		//更新期下页数叠数
		$this->mode->update_period($period_id);
		
		$this->addItem($page_id);
		
		$this->output();
	}
	
	//删除某一页
	public function del_page()
	{
		$page_id = intval($this->input['page_id']);
		if(!$page_id)
		{
			$this->errorOutput('没有page_id');
		}
		
		$period_id = intval($this->input['period_id']);
		
		$sql = "SELECT jpg_id,pdf_id FROM ".DB_PREFIX."page WHERE id = ".$page_id;
		$res = $this->db->query_first($sql);
		$mater_ids = implode(',', $res);
		
		$sql = "DELETE FROM ".DB_PREFIX."page WHERE id = ".$page_id;
		$this->db->query($sql);
		
		//更新期下页数叠数
		$this->mode->update_period($period_id);
		
		if($mater_ids)
		{
			$sql = "DELETE FROM ".DB_PREFIX."material WHERE id IN (".$mater_ids.")";
			$this->db->query($sql);
			$this->addItem('sucess');
		}
		$this->output();
	}
	
	public function del_stack()
	{
		
		$period_id = intval($this->input['period_id']);
		if(!$period_id)
		{
			$this->errorOutput('期id不存在');
		}
		
		$stack_id = intval($this->input['stack_id']);
		if(!$stack_id)
		{
			$this->errorOutput('叠id不存在');
		}
		
		$sql = "SELECT id FROM ".DB_PREFIX."page WHERE period_id = ".$period_id." AND stack_id = ".$stack_id;
		$res = $this->db->query_first($sql);
		
		if($res)
		{
			$this->errorOutput('请先删除叠下的页');
		}
		
		$sql = "DELETE FROM ".DB_PREFIX."stack WHERE id = ".$stack_id;
		$this->db->query($sql);
		
		//更新期下叠和页数目
		$this->mode->update_period($period_id);
		
		$this->addItem('success');
		$this->output();
		
	}
	
	/**
	 * 更新页上的热区信息
	 * Enter description here ...
	 */
	public function update_link()
	{
		$page_id = intval($this->input['page_id']);
		if(!$page_id)
		{
			$this->errorOutput('页id不存在');
		}
		
		$hotInfo = $this->input['hotInfo'];
		
		$hotInfo = serialize($hotInfo);
		
		$sql = "UPDATE ".DB_PREFIX."page SET hot_area = '".$hotInfo."' WHERE id = ".$page_id;
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
	
	//上传水印图片
	public function upload_water()
	{		
		$material = $this->mater->upload_water($_FILES['Filedata']);
		if(!$material)
		{
			$this->addItem($material);
			$this->output();
		}
		else
		{
			$this->errorOutput('上传失败');
		}
		$this->addLogs('上传水印图片','','', $material['name']);
	}
	
	//添加新的水印配置
	public function create_water_config()
	{
		if(empty($this->input['config_name']))
		{
			$this->errorOutput('配置名称不能为空');
		}
			
		if(intval($this->input['water_type'])==1 && empty($this->input['water_filename']))
		{
			$this->errorOutput('水印图片不能为空');
		}

		if(intval($this->input['water_type'])==0 && empty($this->input['water_text']))
		{
			$this->errorOutput('水印文字不能为空');
		}
		$water_info = array(
			'config_name'=> ($this->input['config_name']),
			'type' => intval($this->input['water_type']),
			'position' => intval($this->input['get_photo_waterpos']),
			'filename' => ($this->input['water_filename']),
			'margin_x'=> ($this->input['margin_x']),
			'margin_y'=> ($this->input['margin_y']),
			'water_text' => ($this->input['water_text']),
			'water_angle' => intval($this->input['water_angle']),
			'water_font' => ($this->input['water_font']),
			'font_size'  => intval($this->input['font_size']),
			'opacity' => ($this->input['opacity']),
			'water_color' => ($this->input['water_color']),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
			'user_name' => trim(($this->user['user_name'])),			
		);					
		$ret = $this->mater->create_water_config($water_info);
		if(!$ret)
		{
			$this->errorOutput('添加失败');
		}
		else
		{
			$this->addLogs('添加水印配置','',$water_info, $water_info['config_name']);
			$this->addItem($ret);
			$this->output();
		}
	}	
	//查看水印配置列表
	public function water_config_list()
	{
		$return = $this->mater->water_config_list();
		if(empty($return))
		{
			$this->errorOutput('获取失败');
		}
		else
		{
			foreach($return as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}	
	//using
	public function pic_water_list()
	{	
		$info = $this->mater->waterSystem();
		$this->addItem($info);
		$this->output();
	}
	/*
	*	旋转图片
	*
	*	@param material_id 附件ID
	*	@param direction 旋转方向 1左旋转 2右转转
	*
	*/
	public function revolveImg()
	{
		$material_id = intval($this->input['material_id']);
		$direction = intval($this->input['direction']);
		if(!$material_id)
		{
			$this->errorOutput('附件ID不能为空');
		}
		if(!$direction)
		{
			$this->errorOutput('旋转方向不能为空');
		}	
		$return = $this->mater->revolveImg($material_id, $direction);
		if($return)
		{
			
			$this->addItem($return);
			$this->output();
		}
		else
		{
			$this->errorOutput('旋转失败');
		}
	}
	
	/**
	 * 本地化图片 ...
	 * @name 		img_local
	 * @copyright	hogesoft
	 */
	function img_local()
	{
		if(!$this->input['url'])
		{
			$this->errorOutput('请传入URL');
		}
		$url = urldecode($this->input['url']);	
		$water_id = urldecode($this->input['water_id']);				//如果设置了水印则要传水印id	
		$material = $this->mater->localMaterial($url,0,0,$water_id); 	//调用图片服务器本地化接口
		if(!empty($material))
		{
			$url_arr = explode(',', $url);
			$info = array();
			foreach ($material as $k => $v)
			{
				if(!empty($v))
				{
					if(in_array($v['remote_url'], $url_arr))
					{
						$info[$v['remote_url']] = array('id' => $v['id'],'remote_url'=>$v['remote_url'],'path' => $v['host'].$v['dir'],'dir' => $v['filepath'],'filename' => $v['filename'],'error' => $v['error']);
					}
				}
			}
			foreach($material as $key => $value)
			{
				if(!empty($value) && !$value['error'])
				{
					$value['pic'] = array(
						'host' => $value['host'],
						'dir' => $value['dir'],
						'filepath' => $value['filepath'],
						'filename' => $value['filename'],
					);
					$value['pic'] = serialize($value['pic']);
					
					$data = array(
						'material_id' 	=> $value['id'],
						'name'			=> $value['name'],
						'host'			=> $value['host'],
						'dir'			=> $value['dir'],
						'filepath'		=> $value['filepath'],
						'filename'		=> $value['filename'],
						'type'			=> $value['type'],
						'mark'			=> $value['mark'],
						'imgwidth'		=> $value['imgwidth'],
						'imgheight'		=> $value['imgheight'],
						'filesize'		=> $value['filesize'],
						'create_time'	=> $value['create_time'],
						'ip'			=> $value['ip'],
						'pic'			=> $value['pic'],
					);
					//unset($value['mid'],$value['id'],$value['bundle_id']);
					$this->mode->insert_data($data,"material");
				}
			}
		}
		if(!empty($info))
		{
			$this->addLogs('文章本地化图片','',$material, $material['name']);
			$this->addItem($info);
			$this->output();
		}
		else
		{
			$this->errorOutput('图片本地化失败');
		}
	}	
	
	public function sort()
	{
		$table_name = 'article';
		$order_name = 'order_id';
	
		$ids       = explode(',',urldecode($this->input['id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . $table_name . " SET " . $order_name . " = '" . $order_ids[$k] . "' WHERE id = '" . $v . "'";
			$this->db->query($sql);
			if($this->db->affected_rows())
			{
				$this->addLogs('版页排序','','','');
			}
		}
		
		$this->addItem($ids);
		$this->output();	
		
	}
	
	public function import_article()
	{
		$file = $_FILES['file'];	
		
		if(!$file)
		{
			return false;
		}
		
		
	}
	
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new article_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>