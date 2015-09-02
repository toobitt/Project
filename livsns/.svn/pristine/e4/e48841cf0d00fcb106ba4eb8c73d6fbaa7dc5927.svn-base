<?php
require('global.php');
require_once(ROOT_PATH.'lib/class/publishcms.class.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
define('MOD_UNIQUEID','site');//模块标识
class siteApi extends adminBase
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
		include(CUR_CONF_PATH . 'lib/site.class.php');
		$this->obj = new site();
		$this->pub_cms = new publishcms();
		$this->pub_content = new publishcontent();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$site_id = '';
		$sitedata = array();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$sitedata = $this->obj->get_site(' * ',$this->get_condition(),$offset,$count);
		
		$this->addItem($sitedata);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."site ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		$condition = '';
		
		if($keyword = urldecode($this->input['keyword']))
		{
			$condition = " AND site_name like '%".$keyword."%' ";
		}
		return $condition;	
	}
	
	public function site_form()
	{
		$sitedata = array();
		if($id = intval($this->input['id']))
		{
			$sitedata = $this->obj->get_site_by_id($id);
		}
		//获取所有客户端
		$allclient = $this->obj->get_client();
		
		//获取所有模块
		//$data['module'] = common::get_module();
		
		$data['site'] = $sitedata;
		$data['client'] = $allclient;
		
		//获取站点可以支持的内容类型
		//$data['content_type'] = $this->pub_content->get_content_type_by_colid($id,'','1');

//		print_r($data);exit;
		$this->addItem($data);
		$this->output();
	}
	
	public function operate()
	{
		if($this->mNeedCheckIn && !$this->prms['create_update'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$site_id = intval($this->input['site_id']);
		$data = array(
			'support_client' => $this->input['client'],
			'site_name' => $this->input['site_name'],
			'site_keywords' => urldecode($this->input['site_keywords']),
			'content' => urldecode($this->input['content']),
			'sub_weburl' => trim(urldecode($this->input['sub_weburl']),'/'),
			'weburl' => trim(urldecode($this->input['weburl']),'/'),
			'site_dir' => urldecode($this->input['site_dir']),
			'produce_format' => urldecode($this->input['produce_format']),
			'indexname' => urldecode($this->input['indexname']),
			'suffix' => urldecode($this->input['suffix']),
//			'material_fmt' => urldecode($this->input['material_fmt']),
//			'material_url' => urldecode($this->input['material_url']),
			'tem_material_url' => urldecode($this->input['tem_material_url']),
			'tem_material_dir' => urldecode($this->input['tem_material_dir']),
			'program_dir' => urldecode($this->input['program_dir']),
			'program_url' => urldecode($this->input['program_url']),
			'jsphpdir' => urldecode($this->input['jsphpdir']),
			'support_module' => empty($this->input['support_module'])?'':implode(',',$this->input['support_module']),
			'support_content_type' => empty($this->input['support_content_type'])?'':implode(',',$this->input['support_content_type']),
			'is_video_record' => intval($this->input['is_video_record']),
			'user_email' => $this->input['user_email'],
		);
		if(empty($data['site_name']) || empty($data['weburl'])  || empty($data['sub_weburl']))
		{
			$this->errorOutput("填写信息不全,请检测站点名称,站点域名,站点子域名是否填写");
		}
		
		//先查询这个站点跟目录是否被应用
		$domain_data = array(
			'type' => $this->settings['domain_type']['site'],
			'from_id' => $site_id,
			'sub_domain' => $data['sub_weburl'],
			'domain' => $data['weburl'],
			'path' => $data['site_dir'],
		);
		if(!common::check_domain($domain_data))
		{
			$this->errorOutput("域名子域名已存在，请重新输入！");
		}
		
		if(empty($data['support_client']))
		{
			$allclient = $this->obj->get_client();
			foreach($allclient as $k=>$v)
			{
				$client_ids .= $v['id'].',';
			}
			$client_ids = trim($client_ids,',');
			$data['support_client'] = $client_ids;
		}
		else
		{
			$data['support_client'] = implode(',',$data['support_client']);
		}
		
		//站点百度视频收录处理
		if($data['is_video_record'])
		{
			$data['is_video_record'] = 1;
			$data['video_record_count'] = empty($this->input['video_record_count'])?500:intval($this->input['video_record_count']);
			//建立视频收录目录
//			if($data['video_record_url'])
//			{
//				hg_mkdir($data['video_record_url']);
//			}
			$data['video_record_url'] = $this->input['video_record_url'];
			$data['video_update_peri'] = intval($this->input['video_update_peri']);
			$data['video_record_filename'] = $this->input['video_record_filename'];
		}
		
		if($site_id)
		{
			//更新
			if(!$site = $this->obj->update_site($site_id,$data))
			{
				$this->errorOutput("更新失败！");
			}
			
			//更新domain
			common::update_domain($domain_data);
			
			//查询出站点详细信息
			//$site_detail = $this->obj->get_site_by_id($site_id);
			
			//站点插入到cms并保存cms站点id
			$cms_site_data = array(
				'cms_siteid' => $site_id,
				'site_name' => $data['site_name'],
				'content' => $data['content'],
				'sitedir' => $data['site_dir'],
				'weburl' => 'http://'.($data['sub_weburl']?$data['sub_weburl'].'.':'').rtrim($data['weburl'],'/').'/',
				'site_keywords' => $data['site_keywords'],
				'produce_format' => $data['produce_format'],
				'indexname' => $data['indexname'],
				'suffix' => $data['suffix'],
				'material_fmt' => $data['material_fmt'],
				'material_url' => $data['material_url'],
				'tem_material_url' => $data['tem_material_url'],
				'tem_material_dir' => $data['tem_material_dir'],
				'program_dir' => $data['program_dir'],
				'program_url' => $data['program_url'],
				'jsphpdir' => $data['jsphpdir'],
			);
			$cms_site_id = $this->pub_cms->update_cms_site($cms_site_data);
			
			$allclient = $this->obj->get_client();
			
			//获取所有模块
			//$data['module'] = common::get_module();
			
			//获取站点可以支持的内容类型
			//$data['content_type'] = $this->pub_content->get_content_type_by_colid($site_id,'','1');
			
			$data['site'] = $site;
			$data['client'] = $allclient;
			$this->addItem($data);
			$this->output();
		}
		else
		{
			$data['create_time'] = TIMENOW;
			//插入
			if($site_id = $this->obj->insert_site($data))
			{
				common::insert_domain($data+array('type'=>$this->settings['domain_type']['site'],'from_id'=>$site_id,'path'=>$data['site_dir']));
				
				//站点插入到cms并保存cms站点id
				$cms_site_data = array(
					'site_id' => $site_id,
					'site_name' => $data['site_name'],
					'content' => $data['content'],
					'sitedir' => $data['site_dir'],
					'weburl' => 'http://'.($data['sub_weburl']?$data['sub_weburl'].'.':'').rtrim($data['weburl'],'/').'/',
					'site_keywords' => $data['site_keywords'],
					'material_fmt' => $data['material_fmt'],
					'material_url' => $data['material_url'],
					'tem_material_url' => $data['tem_material_url'],
					'program_dir' => $data['program_dir'],
					'program_url' => $data['program_url'],
					'jsphpdir' => $data['jsphpdir'],
				);
				$cms_site_id = $this->pub_cms->insert_cms_site($cms_site_data);
				//$this->obj->update_site($site_id,array('cms_site_id'=>$cms_site_id));
			}
			else
			{
				$this->errorOutput("添加失败！");
			}
			$allclient = $this->obj->get_client();
			$data['client'] = $allclient;
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function delete()
	{
		if($this->mNeedCheckIn && !$this->prms['delete'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$site_id = intval($this->input['site_id']);
		if($site_id)
		{
			$this->obj->delete($site_id);
			
			//删除CMS站点
			$this->pub_cms->delete_cms_site($site_id);
			
			$this->addItem('success');
			$this->output();
		}
		else
		{
			$this->errorOutput("删除失败！");
		}
	}
	
	public function check_domain()
	{
		$domain_data = array(
			'type' => $this->settings['domain_type']['site'],
			'from_id' => $this->input['site_id'],
			'sub_domain' => trim($this->input['sub_weburl'],'/'),
			'domain' => trim($this->input['weburl'],'/'),
			'path' => trim($this->input['site_dir'],'/'),
		);
		$result = 1;
		if(!common::check_domain($domain_data))
		{
			$result = 0;
		}
		$this->addItem($result);
		$this->output();
	}
	
	public function get_pub_site()
	{
		$field = urldecode($this->input['field'])?urldecode($this->input['field']):'*';
		$site = $this->obj->get_all_site($field);
		$this->addItem($site);
		$this->output();
	}
	
	public function get_pub_site_first()
	{
		$id = intval($this->input['site_id']);
		$field = urldecode($this->input['field'])?urldecode($this->input['field']):'*';
		$site = $this->obj->get_site_by_id($id,$field);
		$this->addItem($site);
		$this->output();
	}
	
	//取多个栏目信息用此方法
	public function get_site_by_ids()
	{
		$result = array();
		$field = urldecode($this->input['field'])?urldecode($this->input['field']):' * ';
		$site_ids = urldecode($this->input['site_ids']);
		$con = '';
		if($site_ids)
		{
			$con = ' AND id in('.$site_ids.')';
		}
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."site WHERE 1".$con;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$result[$row['id']] = $row;
		}
		
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new siteApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>