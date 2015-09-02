<?php
class rebuilddeploy extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH."lib/common.php");
		include_once(CUR_CONF_PATH . 'lib/deploy.class.php');
		include_once(ROOT_PATH.'lib/class/publishconfig.class.php');
		include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
		$this->obj = new deploy();
		$this->pub_config= new publishconfig();
		$this->pub_content= new publishcontent();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//根据站点页面类型页面数据获取部署信息
	public function get_deploy_templates($site_id,$page_id='',$page_data_id='')
	{
		if(!$site_id)
		{
			return false;
		}
		include_once(CUR_CONF_PATH.'lib/cache.class.php');
		$this->cache = new cache();
		$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
		$str = $site_id;
		if($page_id)
		{
			$str .= '_'.$page_id;
			if($page_data_id)
			{
				$str .= '_'.$page_data_id;
			}
		}
		$data = $this->cache->get($str,'no_file_dir');
		
		if($data=='no_file_dir')
		{
			$this->rebuild_deploy($site_id,0,0);
			$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
			$data = $this->cache->get($str);
		}
		
		return $data;
	}
	
	public function rebuild_deploy($site_id,$page_id,$page_data_id,$site_detail=array())
	{
		if(!$site_id && !$page_id)
		{
			return false;
		}
		
		if(!$site_detail)
		{
			//查出这个站点使用的套系
			$site_detail = $this->pub_config->get_site_first('id,site_name,tem_style,support_client',$site_id);
			$support_client = explode(',',$site_detail['support_client']);
			if(!$support_client)
			{
				return false;
			}
		}
		else
		{
			$support_client = explode(',',$site_detail['support_client']);
		}
		
		$set_type_content = array();
		
		//默认的模板类型
		$set_type_default = $this->settings['site_col_template'];
		
		//有内容，查出内容类型
		$content_type = $this->get_content_type();
		
		include_once(CUR_CONF_PATH.'lib/cache.class.php');
		$this->cache = new cache();
		
		if($site_id && !$page_id)
		{
			//先查出这个站点的部署信息
			$site_deploy_tem  = $this->obj->get_deploy_template($site_id,$this->settings['tem_style_default'],0,0);
			$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
			$this->cache->set($site_id,$site_deploy_tem[$site_id][0][0]);
			
			//取出站点下面的页面
			$page_manage 	  = common::get_page_manage($site_id);
			
			//取出所有页面类型的模板部署
			$page_deploy_tems = $this->obj->get_deploy_template_all($site_id,"'".$site_detail['tem_style']."'");
			
			foreach($page_manage as $k=>$v)
			{
				$set_type_use = array();
				
				//给页面类型部署（如果没有，则继承站点部署）
				$set_type_use = $v['has_content']?($set_type_default+$content_type):$set_type_default;
				foreach($support_client as   $k1=>$v1)
				{
					foreach($set_type_use as $k2=>$v2)
					{
						//页面类型部署
						if(empty($page_deploy_tems[$site_id][$v['id']][0][$v1][$k2]))
						{
							if(!empty($site_deploy_tem[$site_id][0][0][$v1][$k2]))
							{
								$page_deploy_tems[$site_id][$v['id']][0][$v1][$k2] = $site_deploy_tem[$site_id][0][0][$v1][$k2];
							}
							else
							{
								$page_deploy_tems[$site_id][$v['id']][0][$v1][$k2] = array();
							}
						}
					}
				}
				
				$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
				$this->cache->set($site_id.'_'.$v['id'],$page_deploy_tems[$site_id][$v['id']][0]);
				
				//给页面数据部署
				$this->rebuild_page_data($support_client,$set_type_use,$site_id,$v,$page_deploy_tems,$page_deploy_tems[$site_id][$v['id']][0],$site_detail,0,0,1000);
			}
		}
		else if($page_id && !$page_data_id)
		{
			$father_deploy_tem = array();
			//查询出页面类型详情
			$page_info 		= common::get_page_by_id($page_id);
			$set_type_use   = $page_info['has_content']?($set_type_default+$content_type):$set_type_default;
			
			//取出所有页面类型的模板部署
			$page_deploy_tems = $this->obj->get_deploy_template_all($site_id,"'".$site_detail['tem_style']."'",$page_id);
			
			//当前页面类型的模板部署
			$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
			$page_type_deploy_tem_cache = $this->cache->get($site_id.'_'.$page_id);
			$page_deploy_tems[$site_id][$page_id][0] = $page_deploy_tems[$site_id][$page_id][0]?$page_deploy_tems[$site_id][$page_id][0]:array();
			if(empty($page_type_deploy_tem_cache)&&empty($page_deploy_tems[$site_id][$page_id][0]))
			{
				//取站点缓存部署
				$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
				$site_deploy_tem_cache = $this->cache->get($site_id);
				if(!empty($site_deploy_tem_cache))
				{
					$page_deploy_tems[$site_id][$page_id][0] = $site_deploy_tem_cache;
				}
			}
			$father_deploy_tem 			= multi_array_merge($page_type_deploy_tem_cache,$page_deploy_tems[$site_id][$page_id][0]);
			$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
			$this->cache->set($site_id.'_'.$page_id,$father_deploy_tem);
			
			//给页面数据部署
			$this->rebuild_page_data($support_client,$set_type_use,$site_id,$page_info,$page_deploy_tems,$father_deploy_tem,$site_detail,0,0,1000);
		}
		else if($page_data_id)
		{
			$father_deploy_tem = array();
			//查询出页面类型详情
			$page_info 		   = common::get_page_by_id($page_id);
			$set_type_use      = $page_info['has_content']?($set_type_default+$content_type):$set_type_default;
			
			//取出所有页面类型的模板部署
                        if(!is_numeric($page_data_id))
                        {
                            return ;
                        }
			$page_deploy_tems  = $this->obj->get_deploy_template_all($site_id,"'".$site_detail['tem_style']."'",$page_id,$page_data_id);
			
			//当前页面类型的模板部署
			$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
			$page_data_deploy_tem_cache = $this->cache->get($site_id.'_'.$page_id.'_'.$page_data_id);
			$page_data_deploy_tem_cache = empty($page_data_deploy_tem_cache)?array():$page_data_deploy_tem_cache;
			$page_deploy_tems[$site_id][$page_id][$page_data_id] = $page_deploy_tems[$site_id][$page_id][$page_data_id]?$page_deploy_tems[$site_id][$page_id][$page_data_id]:array();
			if(empty($page_data_deploy_tem_cache)&&empty($page_deploy_tems[$site_id][$page_id][$page_data_id]))
			{
				$this->rebuild_deploy($site_id,$page_id,0,$site_detail);
				$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
				$page_data_deploy_tem_cache = $this->cache->get($site_id.'_'.$page_id.'_'.$page_data_id);
				$page_data_deploy_tem_cache = empty($page_data_deploy_tem_cache)?array():$page_data_deploy_tem_cache;
				$page_deploy_tems[$site_id][$page_id][$page_data_id] = $page_deploy_tems[$site_id][$page_id][$page_data_id]?$page_deploy_tems[$site_id][$page_id][$page_data_id]:array();
			}
			$father_deploy_tem 			= multi_array_merge($page_data_deploy_tem_cache,$page_deploy_tems[$site_id][$page_id][$page_data_id]);
			$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
			$this->cache->set($site_id.'_'.$page_id.'_'.$page_data_id,$father_deploy_tem);
			
			//给页面数据部署
			$this->rebuild_page_data($support_client,$set_type_use,$site_id,$page_info,$page_deploy_tems,$father_deploy_tem,$site_detail,$page_data_id,0,1000);
		}
	}
	
	public function get_content_type()
	{
		$set_type_content = array();
		//有内容，查出内容类型
		$content_type = $this->pub_content->get_all_content_type();
		if(is_array($content_type))
		{
			foreach($content_type as $k=>$v)
			{
				$set_type_content[$v['id']] = $v['content_type'];
			}
		}
		return $set_type_content;
	}
	
	public function rebuild_page_data($support_client,$set_type_use,$site_id,$page_info,$page_deploy_tems,$father_deploy_tem,$site_detail,$fid=0,$offset=0,$count=1000)
	{
		$page_data = common::get_page_data($page_info['id'],$offset,$count,$fid);
		
		if(!is_array($page_data['page_data']))
		{
			return false;
		}
		foreach($page_data['page_data'] as $kk=>$vv)
		{
			if(!isset($vv[$page_info['field']]) || !is_numeric($vv[$page_info['field']]))
			{
				break;
			}
			//取出所有页面类型的模板部署
			$page_deploy_tems  = $this->obj->get_deploy_template_all($site_id,"'".$site_detail['tem_style']."'",$page_info['id'],$vv[$page_info['field']]);
			foreach($support_client   as $k1=>$v1)
			{
				foreach($set_type_use as $k2=>$v2)
				{
					//页面类型部署
					if(empty($page_deploy_tems[$site_id][$page_info['id']][$vv[$page_info['field']]][$v1][$k2]))
					{
						if(!empty($father_deploy_tem[$v1][$k2]))
						{
							$page_deploy_tems[$site_id][$page_info['id']][$vv[$page_info['field']]][$v1][$k2] = $father_deploy_tem[$v1][$k2];
						}
						else
						{
							$page_deploy_tems[$site_id][$page_info['id']][$vv[$page_info['field']]][$v1][$k2] = array();
						}
					}
				}
			}
			//判断有无子级，有则递归
			if($page_info['has_child'])
			{
				if(!$vv[$page_info['last_field']])
				{
					$this->rebuild_page_data($support_client,$set_type_use,$site_id,$page_info,$page_deploy_tems,$page_deploy_tems[$site_id][$page_info['id']][$vv[$page_info['field']]],$site_detail,$vv[$page_info['field']]);
				}
			}
			$this->cache->initialize(CUR_CONF_PATH.'cache/deploy');
			$this->cache->set($site_id.'_'.$page_info['id'].'_'.$vv[$page_info['field']],$page_deploy_tems[$site_id][$page_info['id']][$vv[$page_info['field']]]);
		}
	}
}
?>
