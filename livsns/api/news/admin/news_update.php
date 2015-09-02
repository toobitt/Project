<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * @public function create|update|delete|audit|unknow
 *
 * $Id: news_update.php 46540 2015-07-07 08:35:32Z jitao $
 ***************************************************************************/
define('MOD_UNIQUEID','news');//模块标识
require('global.php');
class newsUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/news.class.php');
		$this->obj = new news();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 创建新文章
	 * @name create
	 * @param $title string 标题
	 * @param $subtitle string 副标题
	 * @param $keywords string 关键词
	 * @param $brief string 简介
	 * @param $outlink string 外链
	 * @param $content string 正文内容
	 * @param $author string 作者
	 * @param $source string 来源
	 * @param $columnid string 栏目ID
	 * @return $ret array 文章信息
	 */
	public function create()
	{
		if(!$this->input['title'])
		{
			$this->errorOutput("标题不能为空");
		}
		if(!$this->input['content'] && !$this->input['outlink'])
		{
			$this->errorOutput('内容不能为空');
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN('.$this->input['sort_id'].')';
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
		if(urldecode($this->input['outlink']) && urldecode($this->input['outlink']) !='请填写超链接！')
		{
			$content = '';
		}
		else
		{
			$check_info = hg_check_content(html_entity_decode($this->input['content']));
			$content=addslashes($this->input['content']);
		}

		if(!(strpos(urldecode($this->input['indexpic']),'http://') === false))
		{
			$this->input['indexpic'] = $this->indexpic_img_local(urldecode($this->input['indexpic']));
		}
		
		$spe_idarr = explode(',',$this->input['special_id']);
		$col_namearr = explode(',',$this->input['column_name']);
		$col_idarr = explode(',',$this->input['col_id']);
		$sname_idarr = explode(',',$this->input['show_name']);
		$spe_arr = array();
		if($col_idarr)
		{
			foreach($col_idarr as $k=>$v)
			{
			    if(!$v)
			    {
			        continue;
			    }
				$spe_arr[$v]['id'] = $v;
				$spe_arr[$v]['name'] = $col_namearr[$k];
				$spe_arr[$v]['special_id'] = $spe_idarr[$k];
				$spe_arr[$v]['show_name'] = $sname_idarr[$k];
			}
		}

        //是否开启评论  优先使用iscomment设置
        if ( isset($this->input['iscomment']) )
        {
            $this->input['other_settings']['closecomm'] = !intval($this->input['iscomment']);
        }

		$info = array(
			'title' => hg_daddslashes($this->input['title']),
			'page_title' => hg_daddslashes($this->input['pagetitles']),
			'tcolor' => ($this->input['tcolor']),
			'isbold' => intval($this->input['isbold']),
			'isitalic' => intval($this->input['isitalic']),
			'istop' => $this->input['istop']==1 ? 1 : 0, 
			'iscomment' => !$this->input['other_settings']['closecomm'],
			'is_praise' => intval($this->input['is_praise']),
			'istpl' => $this->input['istpl']==1 ? 1 : 0,
			'subtitle' => hg_daddslashes($this->input['subtitle']),
			'keywords' => str_replace(' ',',',hg_daddslashes(trim($this->input['keywords']))),
			'brief' => hg_daddslashes($this->input['brief']),
			'author' => hg_daddslashes($this->input['author']),
			'source' => hg_daddslashes($this->input['source']),
			'indexpic' => intval($this->input['indexpic']),
			'outlink' => hg_daddslashes($this->input['outlink']),
			'sort_id' => intval($this->input['sort_id']),
			'column_id' => $this->input['column_id'],
			'user_id'   => $this->user['user_id'] ? intval($this->user['user_id']): $this->input['user_id'],
			'org_id'   => intval($this->user['org_id']),
			'user_name' => $this->user['user_name'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
//			'pub_time' =>strtotime(($this->input['publish_time'])),
			'ip' => hg_getip(),
			'weight' => intval($this->input['weight']),
			'water_id' => $this->input['water_config_id'],
			'water_name' => ($this->input['water_config_name']),
			'is_img' => $check_info['is_img'],
			'is_video' => $check_info['is_video'],
			'is_tuji'  => $check_info['is_tuji'],
			'is_vote' => $check_info['is_vote'],
			'appid'   => intval($this->user['appid']),
			'appname'  => trim(($this->user['display_name'])),
		//获取状态设置值
			'state'    => $this->get_status_setting('create'),
			'pub_time' => strtotime($this->input['pub_time']),
			'app'      => $this->input['app_uniqueid'] ? $this->input['app_uniqueid'] : APP_UNIQUEID,
			'module'   => $this->input['module_uniqueid'] ? $this->input['module_uniqueid'] : MOD_UNIQUEID,
			'para'     => $this->input['para'],
			'other_settings' => $this->input['other_settings'] ? serialize($this->input['other_settings']) : '',
			'ori_url'  => hg_daddslashes($this->input['ori_url']),
			'special'	=> hg_daddslashes(serialize($spe_arr)),
			'template_sign'	=> intval($this->input['template_sign']),	//叮当的最佳样式
		);
		$this->check_weight_prms($info['weight']);
		//$this->verify_content_prms($nodes);
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
		$column_id = $info['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
		$info['column_id'] = $info['column_id'] ? serialize($info['column_id']) : '';
		$article_id = $this->obj->insert_data($info,"article");
        //记录文稿发布库栏目分发表
        $this->obj->update_pub_column($article_id, $this->input['column_id']);
        //记录文稿发布库栏目分发表
        
		$this->obj->update(array('order_id' => $article_id),"article","id={$article_id}");
		//内容表
		$infoCon = array(
			'articleid'  => $article_id,
			'content'    => $content,
		);
		$this->obj->insert_data($infoCon,"article_contentbody");
        ###更新索引图ID
        $this->input['indexpic'] && $this->obj->update(array('cid'=>$article_id), 'material', ' material_id=' . $this->input['indexpic']);
		//更新素材
		$material_id=$this->input['material_id'];
		if(!empty($material_id))
		{
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
				$condition = " material_id IN(".$del_material.")";
				$this->obj->delete('material',$condition);
			}
			$this->mater->updateMaterial($mid_str,$article_id,$info['sort_id']);  //更新cid,catid
			$this->obj->update(array('cid' => $article_id),"material","material_id IN({$mid_str})");
		}
//		//查询文章所属的所有父级分类
//		$sort = $this->obj->getSortByFather($info['sort_id']);
//		//入水印关系表
//		$this->mater->insertMaterialWater($sort,$article_id,intval($this->input['water_config_id']));
        $info['id'] = $article_id;
		//放入发布队列
		if(intval($info['state']) == 1 && !empty($column_id))
		{
			$op = 'insert';
            publish_insert_query($info,$op);
		}
		if($info['id'])
		{
            if($this->settings['autoSaveDraft'])
            {
			 //删除自动创建用户草稿
              $sql = "DELETE FROM ".DB_PREFIX."draft WHERE user_id=". $this->user['user_id'] . " AND isauto = 1";
              $this->db->query($sql);
             //删除自动创建用户草稿
            }
			$stat_data = array(
				'content_id' => $article_id,
				'contentfather_id' => '',
				'type' => 'insert',
				'user_id' => $this->user['user_id'],
				'user_name' => $this->user['user_name'],
				'before_data' => '',
				'last_data' => $this->input['title'],
				'num' => 1,
			);
			$this->addStatistics($stat_data);
			//编目添加
			//叮当平台需要记录索引图
            if ($this->input['need_indexpic'] && $info['indexpic']) {
                $info['indexpic_url'] = $this->obj->getIndexpic($info['indexpic']);
                $info['pic'] = $info['indexpic_url'];//统一索引图输出格式
            }			
			$this->catalog('create',$article_id,'article');
			$this->addLogs('创建文稿','',$info,$info['title']);
			$this->addItem($info);
			$this->output();
		}
		else
		{
			$this->errorOutput("添加失败！");
		}
	}


	/**
	 * 上传外链索引图片
	 */
	public function upload_indexpic()
	{
		//外链索引图片
		$material = parent::upload_indexpic();
		if(!empty($material) && is_array($material))
		{
			$material['pic'] = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
            $data = array(
                'material_id' => $material['id'],
                'name'        => $material['name'],
                'pic'         => serialize($material['pic']), 
                'host'        => $material['host'],
                'dir'         => $material['dir'],
                'filepath'    => $material['filepath'],
                'filename'    => $material['filename'],
                'type'        => $material['type'],
                'mark'        => $material['mark'],
                'imgwidth'    => $material['imgwidth'],
                'imgheight'   => $material['imgheight'],
                'filesize'    => $material['filesize'],
                'create_time' => $material['create_time'],
                'ip'          => $material['ip'],
                'remote_url'  => $material['remote_url'],      
            );            
			$this->obj->insert_data($data,'material');
			$material['filesize'] = hg_bytes_to_size($material['filesize']);
			$material['success'] = true;
			$material['material_id'] = $material['id'];
			$this->addItem($material);
		}
		else
		{
			$return = array(
				'error' => '文件上传失败',
			);
			$this->addItem($return);
		}
		$this->output();
	}
	//数据更新到material中
	public function insert_news_material(){
		$avaData = array(
				'host'        => $this->input['host'],
				'dir'         => $this->input['dir'],
				'filepath'    => $this->input['filepath'],
				'filename'    => $this->input['filename'],	
				'imgwidth'    => $this->input['imgwidth'],
				'imgheight'   => $this->input['imgheight'],
		);
		$data = array(
				'material_id' => $this->input['material_id'],
				'name'        => $this->input['name'],
				'pic'         => serialize($avaData),
				'host'        => $this->input['host'],
				'dir'         => $this->input['dir'],
				'filepath'    => $this->input['filepath'],
				'filename'    => $this->input['filename'],
				'type'        => $this->input['type'],
				'mark'        => $this->input['mark'],
				'imgwidth'    => $this->input['imgwidth'],
				'imgheight'   => $this->input['imgheight'],
				'filesize'    => $this->input['filesize'],
				'create_time' => $this->input['create_time'],
				'ip'          => $this->input['ip'],
				'remote_url'  => $this->input['remote_url'],
		);
		$id = $this->obj->insert_data($data,'material');
		$this->addItem('id',$id);
		$this->output();
	}
	
	/**
	 * 更新文章内容
	 * @name update
	 * @param $id int 文章ID
	 * @param $title string 标题
	 * @param $subtitle string 副标题
	 * @param $keywords string 关键词
	 * @param $brief string 简介
	 * @param $outlink string 外链
	 * @param $content string 正文内容
	 * @param $author string 作者
	 * @param $source string 来源
	 * @param $columnid string 栏目ID
	 * @return $ret array 文章信息
	 */
	public function update()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput("文章ID不能为空");
		}
		$this->input['title'] = trim($this->input['title']);
		if(!$this->input['title'])
		{
			$this->errorOutput("标题不能为空");
		}
		//if(!$this->input['content'] && !$this->input['outlink'])
		//{
		//	$this->errorOutput('内容不能为空');
		//}
		$article_id = intval($this->input['id']);
		//查询修改文章之前已经发布到的栏目
		$sql = "SELECT * FROM " . DB_PREFIX ."article WHERE id = " . $article_id;
		$q = $this->db->query_first($sql);
		$stat_article_detail = $q;
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
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN('.$_sort_ids.')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$data['nodes'][$row['id']] = $row['parents'];
				}
				//$this->errorOutput(var_export($data['nodes']['news_node'],1));
			}
		}
		#####节点权限

		$data['id'] = $article_id;
		$data['user_id'] = $q['user_id'];
		$data['org_id'] = $q['org_id'];
		$data['column_id'] = $this->input['column_id'];
		/*
		 if($this->user['group_type'] > MAX_ADMIN_TYPE && $data['column_id'] && !$this->user['prms']['news']['publish'])
		 {
			$this->errorOutput(NO_PRIVILEGE);
			}
			*/
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

		#####结束
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
			$data['published_column_id'] = implode(',', $ori_column_id);
		}
        //存在时验证create权限
//        if ($this->input['is_first_hand_save']) {
//            $data['_action'] = 'create';
//        }

		$this->verify_content_prms($data);
		if(urldecode($this->input['outlink']) && urldecode($this->input['outlink']) !='请填写超链接！')
		{
			$content = '';
		}
		else
		{
			$check_info = hg_check_content(html_entity_decode($this->input['content']));
			$content=addslashes(trim($this->input['content']));
		}

		if(!(strpos(urldecode($this->input['indexpic']),'http://') === false))
		{
			$this->input['indexpic'] = $this->indexpic_img_local(urldecode($this->input['indexpic']));
		}

		$spe_idarr = explode(',',$this->input['special_id']);
		$col_namearr = explode(',',$this->input['column_name']);
		$col_idarr = explode(',',$this->input['col_id']);
		$sname_idarr = explode(',',$this->input['show_name']);
		$spe_arr = array();
		if($col_idarr)
		{
			foreach($col_idarr as $k=>$v)
			{
				$spe_arr[$v]['id'] = $v;
				$spe_arr[$v]['name'] = $col_namearr[$k];
				$spe_arr[$v]['special_id'] = $spe_idarr[$k];
				$spe_arr[$v]['show_name'] = $sname_idarr[$k];
			}
		}

        //是否开启评论  优先使用iscomment设置
        if ( isset($this->input['iscomment']) )
        {
            $this->input['other_settings']['closecomm'] = !intval($this->input['iscomment']);
        }

		$info = array(
			'title' 				=> addslashes($this->input['title']),
			'page_title' 			=> addslashes($this->input['pagetitles']),
			'tcolor' 				=> ($this->input['tcolor']),
			'isbold' 				=> intval($this->input['isbold']),
			'isitalic' 				=> intval($this->input['isitalic']),
			'istop' 				=> $this->input['istop']==1 ? 1 : 0, 
			'iscomment' 		    => !$this->input['other_settings']['closecomm'],
			'is_praise'				=> intval($this->input['is_praise']),
			'istpl' 				=> $this->input['istpl']==1 ? 1 : 0,
			'subtitle' 				=> addslashes($this->input['subtitle']),
			'keywords' 				=> ($this->input['keywords']),
			'brief' 				=> addslashes($this->input['brief']),
 			'author' 				=> addslashes($this->input['author']),
			'source' 				=> addslashes($this->input['source']),
			'outlink' 				=> addslashes($this->input['outlink']),
			'indexpic' 				=> intval($this->input['indexpic']),
			'sort_id' 				=> intval($this->input['sort_id']),
			'column_id'				=> $this->input['column_id'],
			'org_id'  				=> intval($this->user['org_id']),
			'weight' 				=> intval($this->input['weight']),
			'water_id' 				=> $this->input['water_config_id'],
			'water_name' 			=> urldecode($this->input['water_config_name']),
			'is_img' 				=> $check_info['is_img'],
			'is_video' 				=> $check_info['is_video'],
			'is_tuji'  				=> $check_info['is_tuji'],
			'is_vote' 				=> $check_info['is_vote'],
			'appid'   				=> intval($this->user['appid']),
			'appname'  				=> trim(urldecode($this->user['display_name'])),
			'pub_time' 				=> strtotime($this->input['pub_time']),
			'other_settings'        => $this->input['other_settings'] ? serialize($this->input['other_settings']) : '',
			'ori_url'               => $this->input['ori_url'],
			'special'				=> addslashes(serialize($spe_arr)),
			'template_sign'			=> intval($this->input['template_sign']),	//叮当的最佳样式
			'click_num'				=> intval($this->input['click_num']),
		);
		$this->check_weight_prms($info['weight'], $data['weight']);
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
		$info['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
		$info['column_id'] = $info['column_id'] ? serialize($info['column_id']) : '';

		$old_material = $this->obj->getMaterialById($article_id);//原文章内容的素材
		$old_material_id = array();
		foreach($old_material as $k => $v)
		{
			$old_material_id[] = $v['material_id'];//原文章内容的素材ID
		}
		$ret_history = $this->add_history($article_id,implode(',',$old_material_id));
		$article_update_ret = $this->obj->update($info,"article","id={$article_id}");
        //记录文稿发布库栏目分发表
        $this->obj->update_pub_column($article_id, $this->input['column_id']);
        //记录文稿发布库栏目分发表
        
		//更新内容表
		$content_update_ret = $this->obj->update(array('content' => $content),"article_contentbody","articleid={$article_id}");
		//更新素材表
		//编目更新
		$this->catalog('update',$article_id,'article',$ret['catalog']);
		$material_id = $this->input['material_id'];
		//if(!empty($material_id))
		//{
			$material_id = is_array($material_id) ? $material_id : explode(',',$material_id) ;
			$material_history = explode(',',urldecode($this->input['material_history']));
			$del_material = array_diff($material_history,$material_id);
			$mid_str = implode(',',$material_id);
			$del_material = implode(',',$del_material);
			if(!empty($del_material))//在某篇内容中操作最后不需要的图片
			{
				$this->mater->delMaterialById($del_material);
				$condition = " material_id IN(".$del_material.")";
				$this->obj->delete('material',$condition);
			}
			if ($mid_str) {
				$this->mater->updateMaterial($mid_str,$article_id,$info['sort_id']);   //更新cid,catid
				$material_update_ret = $this->obj->update(array('cid' => $article_id),"material","material_id IN({$mid_str})");
			}
			$new_material_str = $mid_str;
		//}
		if(!empty($new_material_str))
		{
			// 此次新闻的所有素材ID 中有的是历史记录的已经被删除的素材，so，所有的需要复原操作
			$q = $this->obj->get_deled_material_by_mid($new_material_str);//对已经被删除的 进行复原
			$recover_material_id = $space = "";
			if(is_array($q) && count($q)>0)
			{
				foreach($q as $k => $v)
				{
					$recover_material_id .= $space . $v['material_id'];
					$space = ",";
				}
			}
			if(!empty($recover_material_id))
			{
				$this->mater->recoverMaterialState($recover_material_id); //////恢复数据--恢复文件名///////////////
				$this->obj->update(array('isdel'=>1),"material","material_id IN ({$recover_material_id})");
			}
			// 此次新闻的所有素材ID 中有的是历史记录的已经被删除的素材，so，所有的需要复原操作
        }
			
        $new_material_id = explode(',',$new_material_str);
        $del_old = array_unique(array_diff($old_material_id, $new_material_id));
        if(!empty($del_old))
        {
            $del_old_str = implode(',',$del_old); //旧的
            $sql = "SELECT  * FROM " . DB_PREFIX ."material WHERE material_id IN(" .$del_old_str .")";
            $material_ret = $this->db->query($sql);
            while($row = $this->db->fetch_array($material_ret))
            {
                if($row['expand_id'])
                {
                    publish_insert_query($row, 'delete', '', 1, 'name');
                }
            }
            $this->mater->deleteMaterialState($del_old_str); //////改数据--改文件名///////////////
            $this->obj->update(array('isdel' => 0),"material","material_id IN ({$del_old_str})");
        }
		//查询文章所属的所有父级分类
		//$sort = $this->obj->getSortByFather($info['sort_id']);
		//入水印关系表
		//$this->mater->insertMaterialWater($sort,$article_id,intval($this->input['water_config_id']));
		//发布系统
		$ret = $this->obj->get_article(" id = {$article_id}", 'column_id,state,expand_id,catalog');
		//更改文章后发布的栏目
		$ret['column_id'] = unserialize($ret['column_id']);
		$new_column_id = array();
		if(is_array($ret['column_id']))
		{
			$new_column_id = array_keys($ret['column_id']);
		}
        $info['id'] = $article_id;
		if(intval($ret['state']) == 1)
		{
			if(!empty($ret['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					publish_insert_query($info, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					publish_insert_query($info, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					publish_insert_query($info, 'update',$same_column);
					//有新插入素材时需插入子队列
					publish_insert_query($info, 'insert',$same_column,1);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				publish_insert_query($info, $op);
			}
		}
		else    //打回
		{
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				publish_insert_query($info,$op);
			}
		}
		if($info)
		{
			if($article_update_ret || $content_update_ret || $material_update_ret)
			{
				$info['state'] = $status;
				$data = array('modifier' => $this->user['user_name'],'modifier_id' => $this->user['user_id'],'update_time'  => TIMENOW,'state' => $status );
				$this->obj->update($data,'article',"id={$article_id}");
				$stat_data = array(
					'content_id' => $article_id,
					'contentfather_id' => '',
					'type' => 'update',
					'user_id' => $stat_article_detail['user_id'],
					'user_name' => $stat_article_detail['user_name'],
					'before_data' => $stat_article_detail['title'],
					'last_data' => $this->input['title'],
					'num' => 1,
				);
				$this->addStatistics($stat_data);
				$this->addLogs('修改文章',$stat_article_detail,$info,$info['title']);
			}
            
            if ($this->input['need_indexpic'] && $info['indexpic']) {
                $info['indexpic_url'] = $this->obj->getIndexpic($info['indexpic']);
                $info['pic'] = $info['indexpic_url'];//统一索引图输出格式
            }
			$this->addItem($info);
			$this->output();
		}
		else
		{
			$this->errorOutput("更新失败！");
		}
	}

	/**
	 * 审核文章的状态（支持批量）
	 * @name audit
	 * @access public
	 * @param $id int 文章ID
	 * @return $ret array 文章ID,状态
	 */
	public function audit()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput("未传入文章ID");
		}
		$idArr = explode(',',$id);
		#####
		$sql = 'SELECT id,sort_id,org_id,user_id FROM '.DB_PREFIX.'article WHERE id IN('.$id.')';
		$query = $this->db->query($sql);
		$data = array();
		$sort_ids = array();
		while($row = $this->db->fetch_array($query))
		{
			$data[$row['id']] = $row;
			$sort_ids[] = $row['sort_id'];
		}
		if($sort_ids)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sort_ids).')';
			$query = $this->db->query($sql);
			$sort_ids_array = array();
			while($row = $this->db->fetch_array($query))
			{
				$sort_ids_array[$row['id']] = $row['parents'];
			}
		}
		foreach ($data as $arc_id=>$value)
		{
			unset($value['id']);
			if(intval($value['sort_id']))
			{
				$value['nodes'][$value['sort_id']] = $sort_ids_array[$value['sort_id']];
			}
			$this->verify_content_prms($value);
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if(intval($this->input['audit']) == 1)
		{
			$this->obj->update(array('state' => 1), 'article', " id IN({$id})");
			$ret = $this->obj->get_article_list(" id IN({$id})");
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
					publish_insert_query($info, $op);
					$stat_id[] = $info['id'];
					$stat_user_id[] = $info['user_id'];
					$stat_user_name[] = $info['user_name'];
				}
			}
			$return = array('status' => 1,'id'=> $idArr);
			//审核通过
			$stat_opration = 'verify_suc';
			$opration = '审核文稿';
		}
		else if(intval($this->input['audit']) == 0)
		{
			$this->obj->update(array('state' => 2), 'article', " id IN({$id})");
			$ret = $this->obj->get_article_list(" id IN({$id})");
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
					publish_insert_query($info, $op);
					
					$stat_id[] = $info['id'];
					$stat_user_id[] = $info['user_id'];
					$stat_user_name[] = $info['user_name'];
				}
			}
			$return = array('status' =>2,'id' => $idArr);
				
			$stat_opration = 'verify_fail';
			$opration = '打回文稿';
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
		$this->addLogs($opration,'','',$opration . '+' . $id);
		$this->addItem($return);
		$this->output();
	}

	/**
	 * 根据ID删除文章（支持批量）
	 * @name delete
	 * @param $id int 文章ID
	 * @return $ret int 文章ID
	 */
	public function delete()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput("文章ID不能为空");
		}
		$id = urldecode($this->input['id']); //支持批量
		$sid=explode(',',$id);
		//文章表
		$sql = "SELECT * FROM " . DB_PREFIX . "article WHERE id IN(" . $id .")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$column_id = @unserialize($row['column_id']);
			if(intval($row['state']) == 1 && ($row['expand_id'] || $column_id))
			{
				$op = "delete";
				publish_insert_query($row,$op);
			}
			$data[$row['id']] = array(
				'title' => $row['title'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
				'catid' => $row['sort_id'],
				'user_id'=>$row['user_id'],
				'org_id'=>$row['org_id'],
				'id'=>$row['id'],
			);
			$sort_ids[] = $row['sort_id'];
			$data[$row['id']]['content']['article'] = $row;
				
			$stat_id[] = $row['id'];
			$stat_user_id[] = $row['user_id'];
			$stat_user_name[] = $row['user_name'];
		}

		if($sort_ids)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sort_ids).')';
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
		//内容表
		$sql = "SELECT * FROM " . DB_PREFIX . "article_contentbody WHERE articleid  IN(" . $id .")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$data[$row['articleid']]['content']['article_contentbody'] = $row;
		}
		//放入回收站
		$recycle_ret = true;
		$is_open = true;
		if(!empty($data) && $this->settings['App_recycle'])
		{
			include_once(ROOT_PATH . 'lib/class/recycle.class.php');
			$this->recycle = new recycle();
			foreach($data as $key => $value)
			{
				$ret = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content'],$value['catid']);
				$recycle_ret = $ret['sucess'];
				$is_open = $ret['is_open'];
			}
		}
		if($recycle_ret)		//判断传入的信息是否完整
		{
			if($is_open)		//判断回收站是否开启，开启则放入回收站，，没开启直接彻底删除
			{
				//删除文章表记录
				$sql = "DELETE FROM " . DB_PREFIX . "article WHERE id IN(" . $id . ")";
				$this->db->query($sql);
				//删除内容表记录
				$sql = "DELETE FROM " . DB_PREFIX . "article_contentbody WHERE articleid IN(" . $id . ")";
				$this->db->query($sql);
			}
			else				//没开启
			{
				//删除文章表记录
				$sql = "DELETE FROM " . DB_PREFIX . "article WHERE id IN(" . $id . ")";
				$this->db->query($sql);
				//删除内容表记录
				$sql = "DELETE FROM " . DB_PREFIX . "article_contentbody WHERE articleid IN(" . $id . ")";
				$this->db->query($sql);
				//删除历史记录表
				$sql = "DELETE FROM " . DB_PREFIX . "article_history WHERE aid IN(" . $id . ")";
				$this->db->query($sql);
				//删除附件
				$sql = "SELECT material_id FROM " . DB_PREFIX . "material WHERE cid IN (" . $id . ")";
				$q = $this->db->query($sql); //对已经被软删除的 进行复原
				$material_id = $recover_material_id = $space = "";
				while(false != ($row = $this->db->fetch_array($q)))
				{
					$material_id .= $space . $row['material_id'];
					if(!$row['isdel'])
					{
						$recover_material_id .= $space . $row['material_id'];
					}
					$space = ",";
				}
				if(!empty($recover_material_id))
				{
					$this->mater->recoverMaterialState($recover_material_id); //////恢复数据--恢复文件名///////////////
				}
				$this->mater->delMaterialById($material_id);
				//删除附件缓存表
				$sql = "DELETE FROM " . DB_PREFIX . "material WHERE cid IN(" . $id . ")";
				$this->db->query($sql);
			}
			if(!empty($stat_id))
			{
				$stat_data = array(
					'content_id' => implode(',',$stat_id),
					'contentfather_id' => '',
					'type' => 'delete',
					'user_id' => implode(',',$stat_user_id),
					'user_name' => implode(',',$stat_user_name),
					'before_data' => '',
					'last_data' => '',
					'num' => 1,
				);
				$this->addStatistics($stat_data);
				//删除编目
				$this->catalog('delete',$stat_data['content_id']);
			}
			$this->addLogs('删除文稿',$data,'', '删除文稿+' . $id);
			$this->addItem($id);
			$this->output();
		}
		else
		{
			$this->errorOutput('删除失败，信息不完整');
		}
	}

	/**
	 * 根据ID删除文章（支持批量）
	 * @name delete_comp
	 * @param $cnt 内容ID
	 *
	 */
	public function delete_comp()
	{
		$cid = urldecode($this->input['cid']);
		if(!$cid)
		{
			$this->errorOutput("请传入内容ID");
		}
		//删除历史记录表
		$sql = "DELETE FROM " . DB_PREFIX . "article_history WHERE aid IN(" . $cid . ")";
		$this->db->query($sql);
		//删除附件
		$sql = "SELECT material_id FROM " . DB_PREFIX . "material WHERE cid IN (" . $cid . ")";
		$q = $this->db->query($sql); //对已经被删除的 进行复原
		$material_id = $recover_material_id = $space = "";
		while(false != ($row = $this->db->fetch_array($q)))
		{
			$material_id .= $space . $row['material_id'];
			if(!$row['isdel'])
			{
				$recover_material_id .= $space . $row['material_id'];
			}
			$space = ",";
		}
		if(!empty($recover_material_id))
		{
			$this->mater->recoverMaterialState($recover_material_id); //////恢复数据--恢复文件名///////////////
		}
		$this->mater->delMaterialById($material_id);
		//删除附件缓存表
		$sql = "DELETE FROM " . DB_PREFIX . "material WHERE cid IN(" . $cid . ")";
		$this->db->query($sql);
		$this->addItem($cid);
		$this->output();
	}

	public function upload()
	{
		$material = $this->mater->addMaterial($_FILES,0,0,intval($this->input['water_config_id']));
		if(!empty($material) && is_array($material))
		{
			$material['pic'] = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
            $code = $material['code'];
            $data = array(
                'material_id' => $material['id'],
                'name'        => $material['name'],
                'pic'         => serialize($material['pic']), 
                'host'        => $material['host'],
                'dir'         => $material['dir'],
                'filepath'    => $material['filepath'],
                'filename'    => $material['filename'],
                'type'        => $material['type'],
                'mark'        => $material['mark'],
                'imgwidth'    => $material['imgwidth'],
                'imgheight'   => $material['imgheight'],
                'filesize'    => $material['filesize'],
                'create_time' => $material['create_time'],
                'ip'          => $material['ip'],
                'remote_url'  => $material['remote_url'],      
            );
			$this->obj->insert_data($data,"material");
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
				'code'       => $code,
				'_host'      => $material['host'],
				'_dir'	     => $material['dir'],
				'_filepath'	 => $material['filepath'],
				'_filename'	 => $material['filename'],
				'imgwidth'	 => $material['imgwidth'],
				'imgheight'  => $material['imgheight']
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
				if(is_array($value) && !empty($value) && !$value['error'])
				{
					$value['pic'] = array(
						'host' => $value['host'],
						'dir' => $value['dir'],
						'filepath' => $value['filepath'],
						'filename' => $value['filename'],
					);
					$value['material_id'] = $value['id'];
                    $data = array(
                        'material_id' => $value['material_id'],
                        'name'        => $value['name'],
                        'pic'         => serialize($value['pic']), 
                        'host'        => $value['host'],
                        'dir'         => $value['dir'],
                        'filepath'    => $value['filepath'],
                        'filename'    => $value['filename'],
                        'type'        => $value['type'],
                        'mark'        => $value['mark'],
                        'imgwidth'    => $value['imgwidth'],
                        'imgheight'   => $value['imgheight'],
                        'filesize'    => $value['filesize'],
                        'create_time' => $value['create_time'],
                        'ip'          => $value['ip'],
                        'remote_url'  => $value['remote_url'],      
                    );
					$this->obj->insert_data($data,"material");
				}
			}
		}
		if(!empty($info))
		{
			$this->addLogs('文稿本地化图片','',$material, $material['name']);
			$this->addItem($info);
			$this->output();
		}
		else
		{
			$this->errorOutput('图片本地化失败');
		}
	}

	/**
	 * 设置权重
	 * @name 		update_weight
	 */
	function check_weight_prms($input_weight =  0, $org_weight = 0)
	{
		if($this->user['group_type'] < MAX_ADMIN_TYPE)
		{
			return;
		}
		$set_weight_limit = $this->user['prms']['default_setting']['set_weight_limit'];
		if(!$set_weight_limit)
		{
			return;
		}
		if($org_weight > $set_weight_limit)
		{
			$this->errorOutput(MAX_WEIGHT_LIMITED);
		}
		if($input_weight > $set_weight_limit)
		{
			$this->errorOutput(MAX_WEIGHT_LIMITED);
		}
	}
	function update_weight()
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
		$sql = 'SELECT id,weight FROM '.DB_PREFIX.'article WHERE id IN('.implode(',', $id).')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$org_weight[$row['id']] = $row['weight'];
		}
		$sql = "CREATE TEMPORARY TABLE tmp (id int primary key, weight int)";
		$this->db->query($sql);
		$sql = "INSERT INTO tmp VALUES ";
		$space = '';

		foreach ($data as $k => $v)
		{
			$sql .= $space . "(" . $k . ", ". $v .")";
			$this->check_weight_prms($v, $org_weight[$k]);
			$space = ',';
		}
		$this->db->query($sql);
		$sql = "UPDATE " . DB_PREFIX . "article a,tmp SET a.weight = tmp.weight WHERE a.id = tmp.id";
		$this->db->query($sql);
		//		发布系统
		//		$article_ids = array_keys($data);
		//		$article_ids = $article_ids ? implode(',',$article_ids) : $article_ids;
		//		$sql = "SELECT  * FROM  " . DB_PREFIX . "article WHERE id IN(" . $article_ids . ")";
		//		$ret = $this->db->query($sql);
		//		while($row = $this->db->fetch_array($ret))
		//		{
		//			if($row['expand_id'])  //如果为真则已经发不过
		//			{
		//				$op = "update";
		//				publish_insert_query($row,$op);
		//			}
		//		}
		$id = implode(',',$id);
		$this->addLogs('修改权重','','', '修改权重+' . $id);
		$this->addItem('true');
		$this->output();
		}

		/**
		 * 同步访问统计
		 */
	 public function access_sync()
	 {
	 	if(!$this->input['id'])
	 	{
	 		$this->errorOutput('NOID');
	 	}
	 	$id = intval($this->input['id']);
	 	$data = array();
	 	if($this->input['click_num'])
	 	{
	 		$data['click_num'] = intval($this->input['click_num']);
	 	}
	 	$this->obj->update($data,"article"," id = {$id}");
	 	$this->addItem($data);
	 	$this->output();
	 }
	 	

	 private function indexpic_img_local($url)
	 {
	 	$material = $this->mater->localMaterial($url,0,0);
	 	$material = $material[0];
	 	if(!empty($material) && is_array($material) && !$material['error'])
	 	{
	 		$material['pic'] = array(
					'host' => $material['host'],
					'dir' => $material['dir'],
					'filepath' => $material['filepath'],
					'filename' => $material['filename'],
	 		);
            $data = array(
                'material_id' => $material['id'],
                'name'        => $material['name'],
                'pic'         => serialize($material['pic']), 
                'host'        => $material['host'],
                'dir'         => $material['dir'],
                'filepath'    => $material['filepath'],
                'filename'    => $material['filename'],
                'type'        => $material['type'],
                'mark'        => $material['mark'],
                'imgwidth'    => $material['imgwidth'],
                'imgheight'   => $material['imgheight'],
                'filesize'    => $material['filesize'],
                'create_time' => $material['create_time'],
                'ip'          => $material['ip'],
                'remote_url'  => $material['remote_url'],      
            );            
	 		$this->obj->insert_data($data,'material');
	 	}
	 	return $material['id'];
	 }

	 //using
	 //引用素材接口
	 //$this->input['host'],$this->input['dir'],$this->input['filename'],$this->input['fid']
	 //当返回值中is_last为真时需传递$this->input['sort_id']
	 public function get_material_node()
	 {
	 	$host = urldecode($this->input['host']);
	 	$dir = urldecode($this->input['dir']);
	 	$filename = urldecode($this->input['filename']);
	 	if(isset($this->input['fid']) && !isset($this->input['sort_id']))
	 	{
	 		$fid = intval($this->input['fid']);
	 		$sort = $this->get_refer_sort($host,$dir,$filename,$fid);
	 		if(!empty($sort))
	 		{
	 			foreach ($sort as $k => $v)
	 			{
	 				$this->addItem($v);
	 			}
	 		}
	 	}
	 	else if((isset($this->input['sort_id']) && $this->input['search_type'] != 1) || $this->input['key'])
	 	{
	 		$sort_id = intval($this->input['sort_id']);
	 		$refer_material = $this->get_all_mateiral($host,$dir,$filename,$sort_id);
	 		if(!empty($refer_material))
	 		{
	 			foreach($refer_material as $k => $v)
	 			{
	 				$this->addItem($v);
	 			}
	 		}
	 	}
	 	else if($this->input['search_type'] == 1)
	 	{
	 		$refer_material = $this->get_search();
	 		if(!empty($refer_material))
	 		{
	 			foreach($refer_material as $k => $v)
	 			{
	 				$this->addItem($v);
	 			}
	 		}
	 	}
	 	else
	 	{
	 		if(is_array($this->settings['refer_module']) && count($this->settings['refer_module']) > 0)
	 		{
	 			foreach($this->settings['refer_module'] as $k => $v)
	 			{
	 				if($this->settings['App_' . $k])
	 				{
	 					$module = array(
								'name' 		 => $v,
								'bundle' 	 => $k,
								'host'       => $this->settings['App_' . $k]['host'],
								'dir'        => $this->settings['App_' . $k]['dir'] . 'admin/',
								'fid'        => 0,
								'is_last'    => 0,
	 					);
	 					switch($k){
	 						case 'vote':
	 							$module['filename'] = 'vote_question';
	 							break;
	 						case 'livmedia':
	 							$module['filename'] = 'vod';
	 							break;
	 						default:
	 							$module['filename'] = $k;
	 							break;
	 					}
	 					$this->addItem($module);
	 				}
	 			}
	 		}
	 	}
	 	$this->output();

	 }

	 //using
	 /**
	 * 取引用素材的分类节点
	 */

	 private function get_refer_sort($host,$dir,$filename,$fid)
	 {
	 	include_once(ROOT_PATH . 'lib/class/curl.class.php');
	 	$curl = new curl($host,$dir);
	 	$curl->setSubmitType('post');
	 	$curl->setReturnFormat('json');
	 	$curl->initPostData();
	 	$curl->addRequestData('a', 'news_refer_sort');
	 	$curl->addRequestData('fid', $fid);
	 	$ret = $curl->request($filename . '.php');
	 	$info = array();
	 	if(!empty($ret))
	 	{
	 		foreach ($ret as $k => $v)
	 		{
	 			$v['host'] = $host;
	 			$v['dir'] = $dir;
	 			$v['filename'] = $filename;
	 			$info[] = $v;
	 		}
	 	}
	 	return $info;
	 }


	 //using
	 /**
	 * 查看某应用、模块下的素材
	 *
	 * @name get_all_material
	 * @author wangleyuan
	 * @param $host		string   	接口服务器
	 * @param $dir 		string		接口路径
	 * @param $filename string  	模块接口文件
	 * @param $offset	int			数据起始量
	 * @param $count	int 		偏移量
	 * @param $user		string		当前用户
	 *
	 */
	 //必选参数 $this->input['host']、$this->input['dir']、$this->input['filename']、$this->input['sort_id']
	 //可选参数 $this->input['offset']、$this->input['count']
	 //当需要取出我发布的素材时 需传入$this->input['my_publish']
	 private function get_all_mateiral($host,$dir,$filename,$sort_id)
	 {
	 	//我的发布
	 	/* 可选参数 分页   offset 起始值   count 偏移量*/
	 	$user = $this->input['my_publisth'] ? $this->user['user_name'] : '';
	 	$key = $this->input['key'] ? $this->input['key'] : '';
	 	$offset = intval($this->input['offset']) ? intval($this->input['offset']) : 0;
	 	$count = intval($this->input['counts']) ? intval($this->input['counts']) : 20;

	 	include_once(ROOT_PATH . 'lib/class/curl.class.php');
	 	$curl = new curl($host,$dir);
	 	$curl->setSubmitType('post');
	 	$curl->setReturnFormat('json');
	 	$curl->initPostData();
	 	$curl->addRequestData('a', 'news_refer_material');
	 	$curl->addRequestData('offset', $offset);
	 	$curl->addRequestData('count', $count);
	 	$curl->addRequestData('user', $user);
	 	$curl->addRequestData('key', $key);
	 	$curl->addRequestData('sort_id', $sort_id);
	 	$ret = $curl->request($filename . '.php');
	 	$info = array();
	 	if(!empty($ret))
	 	{
	 		foreach ($ret as $k => $v)
	 		{
	 			$v['host'] = $host;
	 			$v['dir'] = $dir;
	 			$v['filename'] = $filename;
	 			$info[] = $v;
	 		}
	 	}
	 	return $info;
	 }


	 //using
	 //获取某应用、模块下一共有多少素材
	 //必选参数 $this->input['host']、$this->input['dir']、$this->input['filename']
	 //当需要取出我发布的素材时 需传入$this->input['my_publish']
	 public function get_material_count()
	 {
	 	if(empty($this->input['host']))
	 	{
	 		$this->errorOutput('host不能为空');
	 	}
	 	if(empty($this->input['dir']))
	 	{
	 		$this->errorOutput('dir不能为空');
	 	}
	 	if(empty($this->input['filename']))
	 	{
	 		$this->errorOutput('filename不能为空');
	 	}

	 	//我的发布
	 	$user = '';
	 	if(!empty($this->input['my_publish']))
	 	{
	 		$user = urldecode($this->user['user_name']);
	 	}

	 	if(!empty($this->input['sort_id']))
	 	{
	 		$sort_id = intval($this->input['sort_id']);
	 	}

	 	$host = urldecode($this->input['host']);
	 	$dir = urldecode($this->input['dir']);
	 	$filename = urldecode($this->input['filename']);
	 	include_once(ROOT_PATH . 'lib/class/curl.class.php');
	 	$curl = new curl($host,$dir);
	 	$curl->setSubmitType('post');
	 	$curl->setReturnFormat('json');
	 	$curl->initPostData();
	 	$curl->addRequestData('a', 'news_refer_count');
	 	$curl->addRequestData('user', $user);
	 	$curl->addRequestData('sort_id', $sort_id);
	 	$q = $curl->request($filename . '.php');
	 	$this->addItem($q[0]);
	 	$this->output();
	 }


	 /**
	  * 引用素材搜索接口 
	  *
	  * @name		get_search
	  * @access		public
	  * @author		wangleyuan
	  * @category	hogesoft
	  * @copyright	hogesoft
	  */
	 public function get_search()
	 {
	 	if(empty($this->input['key']))
	 	{
	 		$this->errorOutput('请传入关键字');
	 	}
	 	$key = urldecode($this->input['key']);
	 	if(is_array($this->settings['refer_module']) && count($this->settings['refer_module']) > 0)
	 	{
	 		foreach($this->settings['refer_module'] as $k => $v)
	 		{
	 			if($this->settings['App_' . $k])
	 			{
	 				$curl = new curl($this->settings['App_' . $k]['host'], $this->settings['App_' . $k]['dir'] . 'admin/');
	 				$curl->setSubmitType('post');
	 				$curl->setReturnFormat('json');
	 				$curl->initPostData();
	 				$curl->addRequestData('a', 'news_refer_material');
	 				$curl->addRequestData('key', $key);
	 				$curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
	 				switch($k){
	 					case 'vote':
	 						$filename = 'vote_question';
	 						break;
	 					case 'livmedia':
	 						$filename = 'vod';
	 						break;
	 					default:
	 						$filename = $k;
	 						break;
	 				}
	 				$q = $curl->request($filename . '.php');
	 				if(is_array($q))
	 				{
	 					foreach ($q as $kk => $vv)
	 					{
	 						$vv['host'] = $this->settings['App_' . $k]['host'];
	 						$vv['dir'] = $this->settings['App_' . $k]['dir'] . 'admin/';
	 						$vv['filename'] = $filename;
	 						$this->addItem($vv);
	 					}
	 				}
	 			}
	 		}
	 	}
	 	$this->output();
	 }

	 //获取示意图
	 public function get_sketch_map()
	 {
	 	if(empty($this->input['host']))
	 	{
	 		$this->errorOutput('host不能为空');
	 	}
	 	if(empty($this->input['dir']))
	 	{
	 		$this->errorOutput('dir不能为空');
	 	}
	 	if(empty($this->input['filename']))
	 	{
	 		$this->errorOutput('filename不能为空');
	 	}
	 	if(empty($this->input['id']))
	 	{
	 		return false;
	 	}
	 	$host = urldecode($this->input['host']);
	 	$dir = urldecode($this->input['dir']);
	 	$filename = urldecode($this->input['filename']);
	 	$id = intval($this->input['id']);

	 	include_once(ROOT_PATH . 'lib/class/curl.class.php');
	 	$curl = new curl($host,$dir);
	 	$curl->setSubmitType('post');
	 	$curl->setReturnFormat('json');
	 	$curl->initPostData();
	 	$curl->addRequestData('a', 'get_sketch_map');
	 	$curl->addRequestData('id', $id);
	 	$curl->addRequestData('filename', $filename);
	 	$curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
	 	$q = $curl->request($filename . '.php');
	 	$q = is_array($q) ? $q[0] : '';
	 	$this->addItem($q);
	 	$this->output();
	 }

	 //获取引用素材详细信息
	 function get_material_info()
	 {
	 	if(empty($this->input['url']))
	 	{
	 		return false;
	 	}
	 	$url = urldecode($this->input['url']);
	 	$url = explode("/", $url);
	 	$name = array_pop($url);
	 	$name = explode(".", $name);
	 	$name = $name[0];
	 	$name = explode("_",$name);
	 	$id = array_pop($name);
	 	$filename = implode('_', $name);
	 	$module_bundle = array_pop($url);
	 	$app_bundle = array_pop($url);
	 	include_once(ROOT_PATH . 'lib/class/curl.class.php');
	 	$curl = new curl($this->settings['App_' . $app_bundle]['host'],$this->settings['App_' . $app_bundle]['dir'] . 'admin/');
	 	$curl->setSubmitType('post');
	 	$curl->setReturnFormat('json');
	 	$curl->initPostData();
	 	$curl->addRequestData('a', 'refer_detail');
	 	$curl->addRequestData('id', $id);
	 	$curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
	 	$ret = $curl->request($filename . '.php');
	 	if(!empty($ret) && is_array($ret))
	 	{
	 		foreach ($ret as $k => $v)
	 		{
	 			$this->addItem($v);
	 		}
	 	}
	 	$this->output();
	 }

	 public function publish()
	 {
	 	$id = urldecode($this->input['id']);
	 	if(!$id)
	 	{
	 		$this->errorOutput('No Id');
	 	}
	 	$pub_time = $this->input['pub_time'] ? strtotime($this->input['pub_time']) : TIMENOW;
	 	$column_id = urldecode($this->input['column_id']);
	 	$isbatch = strpos($id, ',');
	 	if($isbatch !== false && !$column_id)
	 	{
	 		$this->addItem(true);
	 		$this->output();
	 	}
	 	include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
	 	$this->publish_column = new publishconfig();
	 	$column_id = $this->publish_column->get_columnname_by_ids('id,name,parents',$column_id);
	 	$sql = "SELECT * FROM " . DB_PREFIX ."article WHERE id IN( " . $id . ")";
	 	$q = $this->db->query($sql);
	 	while($row = $this->db->fetch_array($q))
	 	{
	 		$row['column_id'] = unserialize($row['column_id']);

	 		$ori_column_id = array();
	 		if(is_array($row['column_id']))
	 		{
	 			$ori_column_id = array_keys($row['column_id']);
	 		}
	 		$ori_column_id_str = $ori_column_id ? implode(',', $ori_column_id) : '';
	 		if($isbatch !== false)     //批量发布只能新增，so需要合并已经发布的栏目
	 		{
	 			$row['column_id'] = is_array($row['column_id']) ? ($row['column_id'] + $column_id) : $column_id;
	 		}
	 		else
	 		{
	 			$row['column_id'] = $column_id;
	 		}
	 		$new_column_id = array_keys($row['column_id']);
	 		/***************************权限控制***************************************/
	 		//批量签发时只能新增 所以对比已经发布的栏目，会导致没有权限发布
	 		$published_column_id = ($isbatch !== false) ? $this->input['column_id'] : $ori_column_id_str;
	 		$this->verify_content_prms(array('column_id' =>$this->input['column_id'], 'published_column_id'=>$published_column_id));
	 		/***************************权限控制***************************************/
	 		$sql = "UPDATE " . DB_PREFIX ."article SET column_id = '". addslashes(serialize($row['column_id'] )) ."',pub_time = ".$pub_time." WHERE id = " . $row['id'];
	 		$this->db->query($sql);
          
            //记录文稿发布库栏目分发表
            $this->obj->update_pub_column($row['id'], implode(',', $new_column_id));
            //记录文稿发布库栏目分发表
                        
	 		if(intval($row['state']) ==1)
	 		{
	 			if(!empty($row['expand_id']))   //已经发布过，对比修改先后栏目
	 			{
	 				$del_column = array_diff($ori_column_id,$new_column_id);
	 				if(!empty($del_column))
	 				{
	 					publish_insert_query($row, 'delete',$del_column);
	 				}
	 				$add_column = array_diff($new_column_id,$ori_column_id);
	 				if(!empty($add_column))
	 				{
	 					publish_insert_query($row, 'insert',$add_column);
	 				}
	 				$same_column = array_intersect($ori_column_id,$new_column_id);
	 				if(!empty($same_column))
	 				{
	 					publish_insert_query($row, 'update',$same_column);
	 				}
	 			}
	 			else 							//未发布，直接插入
	 			{
	 				if ($new_column_id) {
	 					$op = "insert";
	 					publish_insert_query($row,$op);
	 				}
	 			}
	 		}
	 		else    //打回
	 		{
	 			if(!empty($row['expand_id']))
	 			{
	 				$op = "delete";
	 				publish_insert_query($row,$op);
	 			}
	 		}
	 	}
	 	$this->addItem('true');
	 	$this->output();
	 }

	 public function push_block()
	 {
	 	$id = intval($this->input['id']);
	 	$block_id = $this->input['block_id'];
	 	$block_name = $this->input['block_name'];
	 	$block_id_arr = explode(',',$block_id);
	 	$block_name_arr = explode(',',$block_name);
	 	if(!$id)
	 	{
	 		$this->errorOutput('NO_ID');
	 	}
	 	$block_arr = array();
	 	if($block_id)
	 	{
	 		foreach($block_id_arr as $k=>$v)
	 		{
	 			$block_arr[$v]['id'] = $v;
	 			$block_arr[$v]['name'] = $block_name_arr[$k];
	 		}
	 	}

	 	$sql = "UPDATE " . DB_PREFIX ."article SET block = '". serialize($block_arr) ."' WHERE id = " . $id;
	 	$this->db->query($sql);
	 	$sql = "SELECT * FROM " . DB_PREFIX ."article WHERE id = " . $id;
	 	$q = $this->db->query_first($sql);
	 	if(!$q['expand_id'])
	 	{
	 		$this->addItem('true');
	 		$this->output();
	 	}
	 	//插发布队列
	 	$q['column_id'] = unserialize($q['column_id']);
	 	$ori_column_id = array();
	 	if(is_array($q['column_id']))
	 	{
	 		$ori_column_id = array_keys($q['column_id']);
	 	}
	 	publish_insert_query($q, 'update',$ori_column_id);
	 	$this->addItem('true');
	 	$this->output();
	 }


	 public function push_special()
	 {
	 	$id_arr = explode(',',$this->input['id']);
	 	$spe_idarr = explode(',',$this->input['special_id']);
	 	$col_namearr = explode(',',$this->input['column_name']);
	 	$col_idarr = explode(',',$this->input['col_id']);
	 	$sname_idarr = explode(',',$this->input['show_name']);
	 	if(!$spe_idarr)
	 	{
	 		$this->errorOutput('NO_ID');
	 	}
	 	$spe_arr = array();
	 	if($col_idarr)
	 	{
	 		foreach($col_idarr as $k=>$v)
	 		{
	 			if($v)
	 			{
	 				$spe_arr[$v]['id'] = $v;
		 			$spe_arr[$v]['name'] = $col_namearr[$k];
		 			$spe_arr[$v]['special_id'] = $spe_idarr[$k];
		 			$spe_arr[$v]['show_name'] = $sname_idarr[$k];
	 			}
	 		}
	 	}
	 	if($id_arr)
	 	{
	 		foreach($id_arr as $k=>$v)
	 		{
	 			$sql = "UPDATE " . DB_PREFIX ."article SET special = '". serialize($spe_arr) ."' WHERE id = " . $v;
	 			$this->db->query($sql);

	 			$sql = "SELECT * FROM " . DB_PREFIX ."article WHERE id = " . $v;
	 			$q = $this->db->query_first($sql);
	 			if($q['expand_id'])
	 			{
	 				//插发布队列
	 				$q['column_id'] = unserialize($q['column_id']);
	 				$ori_column_id = array();
	 				if(is_array($q['column_id']))
	 				{
	 					$ori_column_id = array_keys($q['column_id']);
	 				}
	 				publish_insert_query($q, 'update',$ori_column_id);
	 			}
	 		}
	 	}

	 	$this->addItem('true');
	 	$this->output();
	 }

	 /**
	  * 更新文稿评论计数
	  * @name 		update_comment_count
	  */
	 function update_comment_count()
	 {
	 	if(empty($this->input['id']))
	 	{
	 		$this->errorOutput(NO_ID);
	 	}
	 	$id = intval($this->input['id']);
	 	//评论数
	 	if($this->input['comment_count'])
	 	{
	 		$comment_count = $this->input['comment_count'];
	 	}
	 	else
	 	{
	 		$comment_count = 1;
	 	}
	 	//审核增加评论数、打回减少评论数
	 	if($this->input['type'] == 'audit')
	 	{
	 		$type = '+';
	 	}
	 	else if($this->input['type'] == 'back')
	 	{
	 		$type = '-';
	 	}
	 	
	 	$info = array();
	 	if($type)
	 	{
	 		$sql = "UPDATE " . DB_PREFIX . "article SET comm_num=comm_num" . $type . $comment_count . " WHERE id =" . $id ;
	 		$this->db->query($sql);
	 		$sql = "SELECT id, state, expand_id, title, column_id, pub_time,user_name FROM " . DB_PREFIX ."article WHERE id =" . $id ;
	 		$info = $this->db->query_first($sql);
	 	}
	 	
	 	if(empty($info))
	 	{
	 		return FALSE;
	 	}
	 	
	 	if(intval($info['state']) == 1)
	 	{
	 		if(!empty($info['expand_id']))
	 		{
	 			$op = "update";
	 		}
	 		else
	 		{
	 			$op = "insert";
	 		}
	 	}
	 	else
	 	{
	 		if(!empty($info['expand_id']))
	 		{
	 			$op = 'delete';
	 		}
	 		else
	 		{
	 			$op = '';
	 		}
	 	}
	 	publish_insert_query($info, $op);
	 	$return = array('status' => 1,'id'=> $id,'pubstatus'=> 1);
	 	$this->addItem($return);
	 	$this->output();
	 }
	 /**
	  * 移动
	  */
	 public function move()
	 {
	 	$id = urldecode($this->input['content_id']);
	 	$node_id = intval($this->input['node_id']);
	 	if(!$id)
	 	{
	 		$this->errorOutput('文章ID不能为空');
	 	}
	 	if($node_id)
	 	{
	 		$this->db->update_data(array('sort_id'=>$node_id), 'article', ' id IN('.$id.')');
	 	}
	 	$ret = array('success' => true, 'id' => $id);
	 	$this->addItem($ret);
	 	$this->output();
	 }

	 /*
	  * 增加历史版本
	  * $id 文章ID
	  * $material_id 上一版本的素材ID
	  */
	 private function add_history($id,$material_id)
	 {
	 	if(empty($id))
	 	{
	 		return false;
	 	}
	 	$info = array();
	 	$sql = "SELECT a.*,ac.* FROM " . DB_PREFIX . "article a
				LEFT JOIN " . DB_PREFIX . "article_contentbody ac 
						ON a.id=ac.articleid  
				WHERE a.id=" . $id;
	 	$f = $this->db->query_first($sql);
	 	if(empty($f))
	 	{
	 		return false;
	 	}
	 	$info = $f;
	 	$info['material_id'] = $material_id;
	 	$data = array(
			'aid' => $id,	
			'content' => serialize($info),	
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,	
	 	);
	 	$this->obj->insert_data($data,'article_history');
	 	return true;
	 }

	 //计划任务审核
	 public function planAudit()
	 {
	 	$start_time = intval($this->input['start_time']);
	 	$end_time = intval($this->input['end_time']);
	 	$status = intval($this->input['status']);
	 	$state = '';
	 	if ($start_time && $end_time && $status) {
	 		switch ($status) {
	 			case 1:$state = 0;break;
	 			case 2:$state = 1;break;
	 			case 3:$state = 2;break;
	 		}
	 		$sql = 'UPDATE '.DB_PREFIX.'article SET state = '.$state.'
                    WHERE state = 0 AND create_time>'.$start_time.' AND create_time<'.$end_time;
	 		$this->db->query($sql);
	 		//发布队列
	 		$sql = 'SELECT id,column_id,title,pub_time,user_name,expand_id,state
                    FROM '.DB_PREFIX.'article 
                    WHERE create_time > '.$start_time.' AND create_time < ' . $end_time;
	 		$q = $this->db->query($sql);
	 		while ($row = $this->db->fetch_array($q))
	 		{
	 			$row['column_id'] = @unserialize($row['column_id']);
	 			if ($row['state'] == 1) {
	 				if ($row['expand_id']) {
	 					publish_insert_query($row, 'update');
	 				}
	 				else {
	 					if ($row['column_id']) {
	 						publish_insert_query($row, 'insert');
	 					}
	 				}
	 			}
	 			else {
	 				if ($row['expand_id'] || $row['column_id']) {
	 					publish_insert_query($row, 'delete');
	 				}
	 			}
	 		}
	 	}
	 	$this->addItem(true);
	 	$this->output();
	 }

     //创建草稿方法
     public function create_draft()
     {
     	if(! $this->settings['autoSaveDraft'])
     	{
     		$this->errorOutput(AUTOSAVEOFF);
     	}
			$content = array(
             'title' => $this->input['title'],
             'page_title' => $this->input['pagetitles'],
             'tcolor' => ($this->input['tcolor']),
             'isbold' => intval($this->input['isbold']),
             'isitalic' => intval($this->input['isitalic']),
             'subtitle' => ($this->input['subtitle']),
             'keywords' => str_replace(' ',',',trim($this->input['keywords'])),
             'brief' => ($this->input['brief']),
             'author' => ($this->input['author']),
             'source' => ($this->input['source']),
             'indexpic' => intval($this->input['indexpic']),
             'outlink' => ($this->input['outlink']),
             'sort_id' => intval($this->input['sort_id']),
             'column_id' => $this->input['column_id'],
//             'pub_time' =>strtotime(($this->input['publish_time'])),
             'weight' => intval($this->input['weight']),
             'water_id' => $this->input['water_config_id'],
             'water_name' => ($this->input['water_config_name']),
             'state'    => $this->get_status_setting('create'),
             'pub_time' => strtotime($this->input['pub_time']),
             'para'     => $this->input['para'],
             'other_settings' => $this->input['other_settings'] ? serialize($this->input['other_settings']) : '',
             'ori_url'  => $this->input['ori_url'],
             'content'  => ($this->input['content']),
             'material_id' => $this->input['material_id'],
         );
         $spe_idarr = explode(',',$this->input['special_id']);
         $col_namearr = explode(',',$this->input['column_name']);
         $col_idarr = explode(',',$this->input['col_id']);
         $sname_idarr = explode(',',$this->input['show_name']);
         $spe_arr = array();
         if($col_idarr)
         {
             foreach($col_idarr as $k=>$v)
             {
                 $spe_arr[$v]['id'] = $v;
                 $spe_arr[$v]['name'] = $col_namearr[$k];
                 $spe_arr[$v]['special_id'] = $spe_idarr[$k];
                 $spe_arr[$v]['show_name'] = $sname_idarr[$k];
             }
         }
         $content['special'] = serialize($spe_arr);
         $draft = array(
             'title'        => hg_daddslashes($content['title']),
             'content'      => hg_daddslashes(serialize($content)),
             'user_id'      => $this->user['user_id'],
             'user_name'    => $this->user['user_name'],
             'isauto'       => $this->input['auto_draft'],
             'create_time'  => TIMENOW,
         );
         if ($draft['isauto'])
         {
            $auto_draft = $this->obj->get_auto_draft($this->user['user_id']);
            if ($auto_draft['id'])
            {
                $this->obj->update($draft, 'draft', ' id = ' . $auto_draft['id']);
            }
            else
            {
                $this->obj->insert_data($draft, 'draft');
            }
         }
         else
         {
            $this->obj->insert_data($draft, 'draft');
         }
         $this->addItem(true);
         $this->output();
     }

     public function draft_del()
     {
         $id = $this->input['draft_id'];
         if (is_array($id))
         {
             $id = implode(',', $id);
         }
         if (!$id)
         {
             $this->errorOutput('NO_ID');
         }

         $sql = "DELETE FROM ".DB_PREFIX."draft WHERE id IN('".$id."')";
         $this->db->query($sql);

         $this->addItem($id);
         $this->output();


     }

	 public function unknow()
	 {
	 	$this->errorOutput("此方法不存在！");
	 }
	 public function sort(){}
	 
	 
	 /**
	  * 修改news内容的所属栏目
	  */
	 public function editColumnById()
	 {
	 	$article_id = intval($this->input['id']);	
	 	$column_id = intval($this->input['column_id']);
	 	$sql = "SELECT * FROM " . DB_PREFIX ."article WHERE id = " . $article_id;
	 	$q = $this->db->query_first($sql);
	 	$updateArray = array();	
	 	include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
	 	$publish_column = new publishconfig();
	 	$result = $publish_column->get_columnname_by_ids('id,name',$column_id);
	 	$info['column_id'] = $result ? serialize($result) : '';
	 	$updateArray['column_id'] = serialize($result);
	 	$article_update_ret = $this->obj->update($updateArray,"article","id={$article_id}");
	 	$ori_column_id = array();
	 	$q['column_id'] = unserialize($q['column_id']);
	 	if(is_array($q['column_id']))
	 	{
	 		$ori_column_id = array_keys($q['column_id']);
	 	}
	 	//记录文稿发布库栏目分发表
	 	$this->obj->update_pub_column($article_id, $this->input['column_id']);	 	
	 	//发布系统
	 	$ret = $this->obj->get_article(" id = {$article_id}", 'column_id,state,expand_id,catalog');
	 	//更改文章后发布的栏目
	 	$ret['column_id'] = unserialize($ret['column_id']);
	 	$new_column_id = array();
	 	if(is_array($ret['column_id']))
	 	{
	 		$new_column_id = array_keys($ret['column_id']);
	 	}
	 	$info['id'] = $article_id;
	 	if(intval($ret['state']) == 1)
	 	{
	 		if(!empty($ret['expand_id']))   //已经发布过，对比修改先后栏目
	 		{
	 			$del_column = array_diff($ori_column_id,$new_column_id);
	 			if(!empty($del_column))
	 			{
	 				publish_insert_query($info, 'delete',$del_column);
	 			}
	 			$add_column = array_diff($new_column_id,$ori_column_id);
	 			if(!empty($add_column))
	 			{
	 				publish_insert_query($info, 'insert',$add_column);
	 			}
	 			$same_column = array_intersect($ori_column_id,$new_column_id);
	 			if(!empty($same_column))
	 			{
	 				publish_insert_query($info, 'update',$same_column);
	 				//有新插入素材时需插入子队列
	 				publish_insert_query($info, 'insert',$same_column,1);
	 			}
	 		}
	 		else 							//未发布，直接插入
	 		{
	 			//根据$article_id拿取news表/article中的信息
	 			
	 			
	 			
	 			
	 			$op = "insert";
	 			publish_insert_query($info, $op);
	 		}
	 	}
	 	else    //打回
	 	{
	 		if(!empty($ret['expand_id']))
	 		{ 				 	
	 			$new_info = $this->obj->getNewInfoById($article_id);
	 			$new_info['column_id'] = $info['column_id'];
	 			$info = $new_info;
	 			$op = "delete";
	 			publish_insert_query($info,$op);
	 		}
	 	}
	 	
	 	if($article_update_ret)
	 	{
	 		$this->addItem($updateArray);
	 	}
	 	$this->output();
// 	 	$result = $this->obj->update(array('column_id'=>$column_id),'article','id = '.$id);
	 }
	 
	 
	 /**
	  * 更新内容的赞的次数
	  */
	 public function update_praise_count()
	 {
	 	$article_id = intval($this->input['content_id']);
	 	$operate = trim($this->input['operate']);
	 	$num = intval($this->input['num']);
	 	if(!$num)
	 	{
	 		$num = 1;
	 	}
	 	$info = array();
		if($operate == 'add')
		{
			$type = "+";
		}
		elseif($operate == 'cancel')
		{
			$type = '-';
		}
	 	
 		$sql = "UPDATE " . DB_PREFIX . "article SET praise_count = praise_count" . $type . $num . " WHERE id =" . $article_id ;
 		$this->db->query($sql);
 		$sql = "SELECT id, state, expand_id, title, column_id, pub_time,user_name FROM " . DB_PREFIX ."article WHERE id =" . $article_id ;
 		$info = $this->db->query_first($sql);
	 	if(empty($info))
	 	{
	 		return FALSE;
	 	} 	 
	 	if(intval($info['state']) == 1)
	 	{
	 		if(!empty($info['expand_id']))
	 		{
	 			$op = "update";
	 		}
	 		else
	 		{
	 			$op = "insert";
	 		}
	 	}
	 	else
	 	{
	 		if(!empty($info['expand_id']))
	 		{
	 			$op = 'delete';
	 		}
	 		else
	 		{
	 			$op = '';
	 		}
	 	}
	 	publish_insert_query($info, $op);
	 	$return = array('status' => 1,'id'=> $article_id,'pubstatus'=> 1);
	 	$this->addItem($return);
	 	$this->output();
	 }
	 
	 /**
	  * 文稿移到垃圾箱
	  */
	 public function moveToTrash()
	 {
	 	$id = intval($this->input['id']);
	 	$news_id = intval($this->input['news_id']); 	
	 	$info = $this->obj->get_article(' id ='.$news_id);
	 	$info['column_id'] = @unserialize($info['column_id']);
	 	//取消文稿库中column的关系
	 	//取消article
	 	$this->obj->update(array(
	 		'column_id'		=> '',
	 		'column_url'	=> '',	
	 	),'article',' id ='.$news_id);
	 	//delete  pub_column
	 	$this->obj->delete('pub_column', ' aid = '.$news_id);	
	 	//删除发布库
	 	$op = "delete";
	 	publish_insert_query($info, $op);	
	 	$this->addItem(array('return'=>true));
	 	$this->output();
	 }
	 
}
$out = new newsUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
