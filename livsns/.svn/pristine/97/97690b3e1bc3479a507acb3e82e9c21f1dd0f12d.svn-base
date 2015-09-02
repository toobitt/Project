<?php
require('global.php');
define('MOD_UNIQUEID','news');
class news_update extends outerUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/news.class.php');
		$this->obj = new news();	
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();			
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{
		if(!$this->input['title'])
		{
			$this->errorOutput('标题不能为空');
		}	
		if(!$this->input['content'] && !$this->input['outlink'])
		{
			$this->errorOutput('内容不能为空');
		}
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

        //是否开启评论  优先使用iscomment设置
        if ( isset($this->input['iscomment']) )
        {
            $this->input['other_settings']['closecomm'] = !intval($this->input['iscomment']);
        }

		$info = array(
			'title' => $this->input['title'],
			'page_title' => ($this->input['pagetitles']),
			'tcolor' => ($this->input['tcolor']),
			'isbold' => intval($this->input['isbold']),
			'isitalic' => intval($this->input['isitalic']),
			'istop' => $this->input['istop']==1 ? 1 : 0, 
			'iscomment' => !$this->input['other_settings']['closecomm'],
			'istpl' => $this->input['istpl']==1 ? 1 : 0,
			'subtitle' => ($this->input['subtitle']),
			'keywords' => str_replace(' ',',',trim($this->input['keywords'])),
			'brief' => ($this->input['brief']),
			'author' => ($this->input['author']),
			'source' => ($this->input['source']),
			'indexpic' => intval($this->input['indexpic']),
			'outlink' => ($this->input['outlink']),
			'sort_id' => intval($this->input['sort_id']),
			'column_id' => $this->input['column_id'],
			'org_id'   => $this->input['org_id'] ? intval($this->input['org_id']): intval($this->user['org_id']),
			'user_id'   => $this->input['user_id'] ? intval($this->input['user_id']): intval($this->user['user_id']),
			'user_name' => $this->input['user_name'] ? $this->input['user_name'] : $this->user['user_name'],
			'create_time' => $this->input['create_time'] ? $this->input['create_time'] : TIMENOW,
			'update_time' => $this->input['update_time'] ? $this->input['update_time'] : TIMENOW,
			'pub_time' =>strtotime(($this->input['pub_time'])),
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
			'state'    => isset($this->input['state']) ? intval($this->input['state']) : $this->settings['default_state'],
			'pub_time' => strtotime($this->input['publish_time']),
			'app'      => $this->input['app_uniqueid'] ? $this->input['app_uniqueid'] : APP_UNIQUEID,
			'module'   => $this->input['module_uniqueid'] ? $this->input['module_uniqueid'] : MOD_UNIQUEID,
			'para'     => $this->input['para'],
			'ori_url'  => $this->input['ori_url'],
		);
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
		$column_id = $info['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$info['column_id']);
		$info['column_id'] = serialize($info['column_id']);
		$info['id'] = $article_id = $this->obj->insert_data($info,"article");
		$this->obj->update(array('order_id' => $article_id),"article","id={$article_id}");
		//内容表
		$infoCon = array(
				'articleid'  => $article_id,
				'content'    => $content,
		);
		$this->obj->insert_data($infoCon,"article_contentbody");
		###更新索引图ID
		$this->input['indexpic'] && $this->obj->update(array('cid'=>$article_id), 'material', ' material_id=' . $this->input['indexpic']);
		//处理图片
		if($this->input['needlocalimg'])
		{
			$url = is_array($this->input['needlocalimg']) ? implode(',',$this->input['needlocalimg']) : $this->input['needlocalimg'];
			$material = $this->mater->localMaterial($url);
			if(is_array($material) && count($material)>0)
			{
				foreach($material as $k => $v)
				{
					if($v && !$v['error'])
					{
						$tem_data = array(
								'cid'   			=> $info['id'],
								'material_id'       => $v['id'],
								'name'              => $v['name'],
								'type'              => $v['type'],
								'mark'              => $v['mark'],
								'filesize'          => $v['filesize'],
								'create_time'       => $v['create_time'],
								'ip'                => $v['ip'],
								'remote_url'        => $v['remote_url'],
								'host'              => $v['host'],
								'dir'               => $v['dir'],
								'filepath'          => $v['filepath'],
								'filename'          => $v['filename'],								
						);
						$tem_data['pic'] = array(
								'host' 		=> $v['host'],
								'dir'		=> $v['dir'],
								'filepath'  => $v['filepath'],
								'filename'  => $v['filename'],
						);
						$tem_data['pic'] = serialize($tem_data['pic']);
						$this->obj->insert_data($tem_data,'material');
					}
				}
			}
		}	
		if($this->input['img'])
		{
			if(is_array($this->input['img']) && count($this->input['img']) > 0 )
			{
				$material_id = array();
				foreach($this->input['img'] as $k => $v)
				{
					if($v)
					{	
						$tmp_data = array(
								'cid' 				=> $info['id'],
								'material_id'       => $v['id'],
								'name'              => $v['name'],
								'type'              => $v['type'],
								'mark'              => $v['mark'] ? $v['mark'] : 'img',
								'filesize'          => $v['filesize'],
								'create_time'       => $v['create_time'],
								'host'              => $v['host'],
								'dir'               => $v['dir'],
								'filepath'          => $v['filepath'],
								'filename'          => $v['filename'],
						);
						$tmp_data['pic'] = array(
								'host' 		=> $v['host'],
								'dir'		=> $v['dir'],
								'filepath'  => $v['filepath'],
								'filename'  => $v['filename'],
						);
						$tmp_data['pic'] = serialize($tmp_data['pic']);
						$material_id[] = $v['id'];
						$this->obj->insert_data($tmp_data,'material');
					}
				}
				$material_id = implode(',', $material_id);
				if($material_id)
				{
					$this->mater->updateMaterialNum($material_id);
				}	
			}
		}

        if ($this->input['material_ids']) {
            $material = $this->mater->get_material_by_ids($this->input['material_ids']);
            foreach ((array)$material as $k => $v) {
                if($v) {   
                    $tmp_data = array(
                            'cid'               => $info['id'],
                            'material_id'       => $v['id'],
                            'name'              => $v['name'],
                            'type'              => $v['type'],
                            'mark'              => $v['mark'] ? $v['mark'] : 'img',
                            'filesize'          => $v['filesize'],
                            'create_time'       => $v['create_time'],
                            'host'              => $v['host'],
                            'dir'               => $v['dir'],
                            'filepath'          => $v['filepath'],
                            'filename'          => $v['filename'],
                    );
                    $tmp_data['pic'] = array(
                            'host'      => $v['host'],
                            'dir'       => $v['dir'],
                            'filepath'  => $v['filepath'],
                            'filename'  => $v['filename'],
                    );
                    $tmp_data['pic'] = serialize($tmp_data['pic']);
                    $material_id[] = $v['id'];
                    $this->obj->insert_data($tmp_data,'material');
                }                
            }
        }

		//放入发布队列
		if(intval($info['state']) == 1 && !empty($column_id))
		{
			$op = 'insert';
			publish_insert_query($info,$op);
		}
		//叮当平台需要记录索引图
        if ($this->input['need_indexpic'] && $info['indexpic']) {
            $info['indexpic_url'] = $this->obj->getIndexpic($info['indexpic']);
        }					
		$this->addItem($info);
		$this->output();
	}
	
	function update()
	{		
	}
	function delete()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput("文章ID不能为空");
		}
		$id = urldecode($this->input['id']); //支持批量
		//删除文章表记录
		$sql = "DELETE FROM " . DB_PREFIX . "article WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		//删除内容表记录
		$sql = "DELETE FROM " . DB_PREFIX . "article_contentbody WHERE articleid IN(" . $id . ")";
		$this->db->query($sql);				
		//删除历史记录表
		$sql = "DELETE FROM " . DB_PREFIX . "article_history WHERE aid IN(" . $id . ")";
		$this->db->query($sql);		
		$sql = "DELETE FROM " . DB_PREFIX . "material WHERE cid IN(" . $id . ")";
		$this->db->query($sql);
		$this->addItem($id);
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
	
		
	function unknow()
	{
		$this->errorOutput('此方法不存在');
	}
}
$out = new news_update();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>