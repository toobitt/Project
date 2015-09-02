<?php
require('global.php');
define('MOD_UNIQUEID','mkpublish');//模块标识
class mkpublish_updateApi extends adminBase
{
	private $sqlarr = array(); 
	
	private $publish_user = ''; 
	
	private $publish_time = ''; 
	
	private $content_type_true = array(0,-1); 
	
	private $content_mk_num;
	
	private $content_param;
	
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/mkpublish.class.php');
		include(CUR_CONF_PATH . 'lib/common.php');
		$this->obj = new mkpublish();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function set_sqlarr($site_id,$page_id,$page_data_id,$content_type)
	{
		$this->sqlarr[] = array(
			'site_id' 		=> $site_id,
			'page_id' 		=> $page_id,
			'page_data_id' 	=> $page_data_id,
			'content_type' 	=> $content_type,
			'publish_time' 	=> $this->publish_time,
			'publish_user' 	=> $this->publish_user,
			'content_param' => $this->content_param,
			'count' 		=> $this->content_mk_num,
		);
	}
	
	public function set_publish_user($publish_user)
	{
		$this->publish_user = $publish_user;
	}
	
	public function set_publish_time($publish_time)
	{
		$publish_time = strtotime($publish_time);
		$this->publish_time = $publish_time;
	}
	
	public function set_content_mk_num($content_mk_num)
	{
		$this->content_mk_num = $content_mk_num;
	}
	
	public function set_content_param($content_param)
	{
		$this->content_param = $content_param;
	}
	
	
	//进入到生成发布队列
	public function create()
	{
		$site_id = intval($this->input['site_id']);
		$page_id = intval($this->input['page_id']);
		$page_data_id = intval($this->input['page_data_id']);
		$content_type = ($this->input['content_type']);
		$content_mk_num = intval($this->input['content_mk_num']);
		$page_data_idarr = $page_data_id?explode(',',$page_data_id):array();
		$is_contain_child = intval($this->input['is_contain_child']);//0不支持 1支持
		
		$this->set_content_mk_num($content_mk_num);
		$this->set_publish_user(trim($this->input['publish_user']));
		$this->set_publish_time(($this->input['publish_time']));
		
		//内容参数
		$content_param = array();
		foreach($this->input as $k=>$v)
		{
			if(strpos($k,'mkcontent_')!==0)
			{
				continue;
			}
			$content_param[str_replace('mkcontent_','',$k)] = $v;
		}
		if($content_param)
		{
			$this->set_content_param(serialize($content_param));
		}
		
		if(!$content_type)
		{
			$this->errorOutput('请选择需要生成发布的内容类型');
		}
		
		if($site_id && !$page_id)
		{
			if($is_contain_child)
			{
				/**发布子级*/
				//查出站点下的页面类型
				$page_type = common::get_page_manage($site_id);
				if(is_array($page_type)&&$page_type)
				{
					$this->mk_plan_page_type($content_type,$page_type,$is_contain_child);
				}
			}
			else
			{
				/**不发布子级*/
				foreach($content_type as $k=>$v)
				{
					$this->set_sqlarr($site_id,0,0,$v);
				}
			}
		}
		if($page_id && !$page_data_idarr)
		{
			//判断站点有没有，如有，是不是站点包含子级生成发布过了
			if($site_id && $is_contain_child)
			{
				break;
			}
			$page_type = common::get_page_by_id($page_id,true);
			if(is_array($page_type)&&$page_type)
			{
				$this->mk_plan_page_type($content_type,$page_type,$is_contain_child);
			}
		}
		if($page_data_idarr)
		{
			//判断站点有没有，如有，是不是站点包含子级生成发布过了
			if($site_id && $is_contain_child)
			{
				break;
			}
			$page_type = common::get_page_by_id($page_id,true,'id');
			if($page_type&&is_array($page_type))
			{
				foreach($page_data_idarr as $k=>$v)
				{
//					$page_id;#!!!!!
					$page_data_id = $v;;#!!!!!
					foreach($content_type as $kk=>$vv)
					{
						$this->set_sqlarr($page_type[$page_id]['site_id'],$page_id,$page_data_id,$vv);
					}
					if($is_contain_child)
					{
						$this->mk_plan_page_data($content_type,$page_type[$page_id],$page_data_id,$is_contain_child);
					}
				}
			}
		}
		
		//入库
		if($this->sqlarr)
		{
			$this->obj->insert_plan($this->sqlarr);
		}
	}
	
	public function mk_plan_page_type($content_type,$page_type,$is_contain_child)
	{
		foreach($page_type as $kk=>$vv)
		{
			foreach($content_type as $k=>$v)
			{
				if(!$vv['has_content'])
				{
					if(!in_array($v,$this->content_type_true))
						continue;
				}
				$this->set_sqlarr($vv['site_id'],$vv['id'],0,$v);
			}
			if($is_contain_child)
			{
				//查出页面数据
				$this->mk_plan_page_data($content_type,$vv,0,$is_contain_child);
			}
		}
	}
	
	public function mk_plan_page_data($content_type,$page_info,$fid,$is_contain_child)
	{
		$page_data = common::get_page_data($page_info['id'],0,1000,$fid,$page_info);
		if(is_array($page_data['page_data'])&&$page_data['page_data'])
		{
			foreach($page_data['page_data'] as $kkk=>$vvv)
			{
				foreach($content_type as $k=>$v)
				{
					$this->set_sqlarr($page_info['site_id'],$page_info['id'],$vvv['id'],$v);
					if(!$vvv['is_last'] && $is_contain_child)
					{
						$this->mk_plan_page_data($content_type,$page_info,$vvv['id'],$is_contain_child);
					}
				}
			}
		}
	}
	
	//生成素材
	public function mk_material()
	{
		$site_id = intval($this->input['site_id']);
		if(!$site_id)
		{
			$this->errorOutput('没有站点ID');
		}
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub_config = new publishconfig();
		$site = $this->pub_config->get_site_first('*',$site_id);
		if(!$site['tem_material_dir'])
		{
			$site['tem_material_dir'] = $site['site_dir'];
		}
		
		//将模板素材拷贝
		if(is_dir(CUR_CONF_PATH.'data/template'))
		{
			file_copy(CUR_CONF_PATH.'data/template'.'/'.$site_id,rtrim($site['tem_material_dir'],'/').'/'.$this->settings['template_name'].'/'.$site_id);
		}
		echo '成功拷贝模板素材'.'<br>';

		$this->mk_mode($site_id,$site);
		
	}
	
	//生成样式素材
	public function mk_mode($site_id = '',$site = array())
	{
		if(!$site_id)
		{
			$this->errorOutput('没有选择站点');
		}
		if(!$site)
		{
			include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
			$this->pub_config = new publishconfig();
			$site = $this->pub_config->get_site_first('*',$site_id);
		}
		if(is_dir(CUR_CONF_PATH.'data/mode/'.$site_id))
		{
			file_copy(CUR_CONF_PATH.'data/mode/'.$site_id,rtrim($site['tem_material_dir'],'/').'/'.$this->settings['mode_name'].'/'.$site_id,array());
		}
		echo '成功拷贝样式素材';
	}
	
	//生成内容页
	public function mk_content()
	{
		$m2o['data'] = $this->input['data'];
		$page_sign = $this->input['page_sign'];
		$page_data_id = intval($this->input['column_id']);
		$content_type = intval($this->input['content_type']);
		
		if(!$page_sign)
		{
			$this->errorOutput('没有页面类型标识');
		}
		
		//根据页面类型标识取查询出页面
		$sql = "SELECT * FROM ".DB_PREFIX."page_manage WHERE sign='".$page_sign."'";
		$page = $this->db->query_first($sql);
		if(!$page)
		{
			$this->errorOutput('没有页面类型信息');
		}
		$page_id = $page['id'];
		
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub_config = new publishconfig();
		$column_detail = $this->pub_config->get_column_first('*',$page_data_id);
		$site_id = $column_detail['site_id'];
		$plan['site_id'] = $site_id;
		$plan['page_id'] = $page_id;
		$plan['page_data_id'] = $page_data_id;
		$plan['content_type'] = $content_type;
		$plan['count'] = 1;
		include_once(CUR_CONF_PATH . 'lib/mkhtml.class.php');
		$this->mkhtml = new mkhtml();
		
		$this->mkhtml->show($plan,$m2o['data'],true);
	}
	
	//生成框架
	public function mk_frame()
	{
		$site_id = intval($this->input['site_id']);
		if(!$site_id)
		{
			$this->errorOutput('没有站点ID');
		}
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub_config = new publishconfig();
		$site = $this->pub_config->get_site_first('*',$site_id);
		if(!$site['tem_material_dir'])
		{
			$site['tem_material_dir'] = $site['site_dir'];
		}
		$m2o_dir = rtrim($site['tem_material_dir'],'/').'/'.$this->settings['frame_filename'];
		hg_mkdir($m2o_dir);
		//将php框架拷贝
		if(file_exists(CUR_CONF_PATH.'data/m2o/conf/config.php'))
		{
			$config_str = @file_get_contents(CUR_CONF_PATH.'data/m2o/conf/config.php');
			$config_str = preg_replace('/define\(\'APPID\',\'\d+\'\);/','define(\'APPID\',\''.APPID.'\');',$config_str);
			$config_str = preg_replace('/define\(\'APPKEY\',\'.+\'\);/','define(\'APPKEY\',\''.APPKEY.'\');',$config_str);
			@file_put_contents(CUR_CONF_PATH.'data/m2o/conf/config.php',$config_str);
		}
		file_copy(CUR_CONF_PATH.'data/m2o',$m2o_dir,array());
		echo '成功拷贝';exit;
	}
	
	public function test()
	{
		include('../cache/datasource/23.php');
		$a = new ds_23();
		$a->show(array());
	}
	
}

$out = new mkpublish_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>
