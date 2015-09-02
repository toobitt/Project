<?php
require('global.php');
define('MOD_UNIQUEID','mkpublish');//模块标识
require_once(ROOT_PATH.'lib/class/publishconfig.class.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
class mkpublishApi extends adminBase
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH."lib/common.php");
		include(CUR_CONF_PATH . 'lib/mkpublish.class.php');
		$this->obj = new mkpublish();
		$this->pub_config= new publishconfig();
		$this->pub_content= new publishcontent();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$site_id = $this->input['site_id']?intval($this->input['site_id']):1;
		if(!$site_id)
		{
			$this->errorOutput('NO_SITE_ID');
		}
		$site_detail = $this->pub_config->get_site_first('id,site_name',$site_id);
		include_once(CUR_CONF_PATH.'lib/page_manage.class.php');
		$page_manage = new pageManage();
		$page_list = $page_manage->get_page(' AND site_id='.$site_id.' ORDER BY id');
		$ret['site_id'] = $site_id;
		$ret['site_name'] = $site_detail['site_name'];
		$ret['page_list'] = $page_list;
		$this->addItem($ret);
		$this->output();
	}
	
	public function count()
	{
//		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."block WHERE 1 ".$this->get_condition();
//		echo json_encode($this->db->query_first($sql));
	}
	
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
	
	public function mkpublish_form()
	{
		$site_id	  = intval($this->input['site_id']);
		$page_id 	  = intval($this->input['page_id']);
		$page_data_id = intval($this->input['page_data_id']);
		$deploy_name = $this->input['deploy_name'];
		$set_type = $this->settings['site_col_template'];
		if($site_id && !$page_id && !$page_data_id)
		{
			//表示对站点进行部署
			//有内容，查出内容类型
			$content_type = $this->pub_content->get_all_content_type();
			foreach($content_type as $k=>$v)
			{
				$set_type[$v['id']] = $v['content_type'];
			}
		}
		else if($page_id)
		{
			//表示对页面类型进行部署 OR 表示对页面数据进行部署
			$page_info = common::get_page_by_id($page_id);
			$site_id   = $page_info['site_id'];
			if($page_info['has_content'])
			{
				//有内容，查出内容类型
				$content_type = $this->pub_content->get_all_content_type();
				foreach($content_type as $k=>$v)
				{
					$set_type[$v['id']] = $v['content_type'];
				}
			}
			
			//取对应数据源参数信息
			$content_list_ds = common::get_datasource_by_sign(0,$page_info['sign']);
			$content_list_ds['argument'] = $content_list_ds['argument']?unserialize($content_list_ds['argument']):array();
		}
		$result['set_type']	      	= $set_type;
		$result['site_id'] 	  	  	= $site_id;
		$result['page_id']  	  	= $page_id;
		$result['page_data_id']   	= $page_data_id;
		$result['argument']   		= $content_list_ds['argument'];
		$this->addItem($result);
		$this->output();
	}
	
	public function open_url()
	{
		$site_id	  = intval($this->input['site_id']);
		$page_id 	  = intval($this->input['page_id']);
		$page_data_id = intval($this->input['page_data_id']);
		$content_type = intval($this->input['content_type']);
		$site = $this->pub_config->get_site_first('*',$site_id);
		$sub_weburl = $site['sub_weburl'];
		$web_url = $site['weburl'];
		$web_dir = '';
		$indexname = $site['indexname'].($site['produce_format']==1?'.html':'.php');
		
		if($page_id)
		{
			$page_data = common::get_page_by_id($page_id);
			if($page_data['next_domain']||$page_data['domain'])
			{
				$sub_weburl = $page_data['next_domain'];
				$web_url = $page_data['domain'];
				$web_dir = '';
			}
			else
			{
				$web_dir .= $page_data['sign'];
			}
		}
		if($page_data_id)
		{
			$page_datas = common::get_page_data($page_id, '', '', 0, $page_data,$page_data_id);
			$page_data_detail= $page_datas['page_data'][0];
			if($page_data_detail['father_domain'])
			{
				$sub_weburl = $page_data_detail['father_domain'];
			}
			$web_dir .= $page_data_detail['relate_dir'];
			$indexname = $page_data_detail['colindex'].$page_data_detail['suffix'];
		}
		$url = $sub_weburl;
		if($web_url)
		{
			$url .= rtrim($web_url,'/');
		}
		if($web_dir)
		{
			$url .= '/'.rtrim($web_dir,'/');
		}
		$url .= '/'.$indexname;
		echo $url;exit;
		$result['url'] = $url;
		$this->addItem($result);
		$this->output();
	}
	
}

$out = new mkpublishApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
