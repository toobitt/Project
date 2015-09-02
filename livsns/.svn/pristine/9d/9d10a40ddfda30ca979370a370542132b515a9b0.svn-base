<?php
define('ROOT_DIR', '../../');
define('WITHOUT_DB', true);
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');
class configuare extends configuareFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function __install()
	{
	}

	public function __upgrade()
	{
	}
	
	public function create_publish_table()
	{
		include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
		$obj = new publishcontent();
		$data = array(
			'bundle_id' => APP_UNIQUEID,
			'module_id' => APP_UNIQUEID,
			'struct_id' => APP_UNIQUEID,
			'struct_ast_id' => "",
			'content_type' => "专题",
			'field' => "id,content_fromid,name,tcolor,sort_id,isbold,isbold,link,order_id,keywords,pic,brief,weight,status,other_settings,use_id,user_name,create_time,update_time,ip,pub_time,expand_id,column_id,colunmn_url",
			'array_field' => "pic",
			'array_child_field' => "pic",
			'field_sql' => "`id` int(10) NOT NULL AUTO_INCREMENT,
							  `content_fromid` int(10) NOT NULL ,
							  `name` varchar(200) NOT NULL COMMENT '标题',
							  `tcolor` varchar(20) NOT NULL COMMENT '标题颜色',
							  `isbold` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标题是否为粗体。1为加粗，0为不加粗',
							  `isitalic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标题是否为斜体。1为斜体，0不是斜体',
							  `sort_id` int(10) NOT NULL COMMENT '所属分类',
							  `link` varchar(100) NOT NULL COMMENT '专题链接',
							  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序ID',
							  `keywords` varchar(200) NOT NULL COMMENT '关键词用,隔开',
							  `pic` text NOT NULL COMMENT '专题索引图',
							  `brief` varchar(300) NOT NULL COMMENT '简介',
							  `weight` smallint(150) NOT NULL COMMENT '权重',
							  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0-待发布1--发布成功',
							  `other_settings` varchar(500) NOT NULL COMMENT '其他设置',
							  `user_id` int(10) NOT NULL COMMENT '发布者id',
							  `user_name` varchar(30) NOT NULL COMMENT '发布者',
							  `create_time` int(10) NOT NULL COMMENT '创建时间',
							  `update_time` int(10) NOT NULL COMMENT '更新时间',
							  `ip` varchar(60) NOT NULL,
							  `pub_time` int(10) NOT NULL COMMENT '发布时间',
							  `expand_id` int(10) NOT NULL COMMENT '发布系统',
							  `column_id` varchar(100) NOT NULL COMMENT '发布的栏目',
							  `column_url` varchar(1000) NOT NULL,
							  PRIMARY KEY (`id`),
							  KEY `sort_id` (`sort_id`),
							  KEY `state` (`state`),
							  KEY `order_id` (`order_id`),
							  KEY `create_time` (`create_time`)",
		
			'table_title' => "专题",
			'child_table' => "",
			'show_field' => array(
					array('field'=>'title','title'=>'标题','type'=>'text'),
					array('field'=>'brief','title'=>'简介','type'=>'text'),
					array('field'=>'keywords','title'=>'关键词','type'=>'text'),
				),			
		);
		
		$ret = $obj->create_table($data);
		
                /**
		$data = array(
			'bundle_id' => APP_UNIQUEID,
			'module_id' => APP_UNIQUEID,
			'struct_id' => APP_UNIQUEID,
			'struct_ast_id' => "special_material",
			'content_type' => "",
			'field' => "id,content_fromid,name,material_id,special_id,filename,host,dir,filepath,type,mark,filesize,del,create_time,ip,expand_id,column_id",
			'array_field' => "",
			'array_child_field' => "",
			'field_sql' => "`id` int(10) NOT NULL AUTO_INCREMENT,
							 `material_id` int(10) NOT NULL COMMENT '素材id',
							  `special_id` int(10) NOT NULL COMMENT '专题id',
							  `name` varchar(40) NOT NULL COMMENT '图片名称',
							  `material` text NOT NULL,
							  `type` varchar(10) NOT NULL COMMENT '附件扩展名',
							  `mark` varchar(30) NOT NULL COMMENT '附件类型',
							  `filesize` int(10) NOT NULL COMMENT '图片大小',
							  `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1已删除0未删除',
							  `create_time` int(10) NOT NULL,
							  `ip` varchar(60) NOT NULL,
							  `column_id` varchar(100) NOT NULL COMMENT '发布的栏目id',
							  `expand_id` int(10) NOT NULL COMMENT '在发布系统中的id',
							  PRIMARY KEY (`id`),
							  KEY `special_id` (`special_id`)",
		
			'table_title' => "专题",
			'child_table' => "special_material",
			'show_field' => array(
					array('field'=>'name','title'=>'素材名','type'=>'text'),
					array('field'=>'brief','title'=>'简介','type'=>'text'),
				),			
		);
		
		//$ret = $obj->create_table($data);
		
		$data = array(
			'bundle_id' => APP_UNIQUEID,
			'module_id' => APP_UNIQUEID,
			'struct_id' => APP_UNIQUEID,
			'struct_ast_id' => "special_summary",
			'content_type' => "",
			'field' => "id,content_fromid,title,content,special_id,del,create_time,ip,expand_id,column_id",
			'array_field' => "",
			'array_child_field' => "",
			'field_sql' => "`id` int(11) NOT NULL AUTO_INCREMENT,
							  `special_id` int(10) NOT NULL COMMENT '专题id',
							  `title` varchar(100) NOT NULL COMMENT '标题',
							  `content` text NOT NULL COMMENT '概要内容',
							  `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1已删除0未删除',
							   PRIMARY KEY (`id`)",
		
			'table_title' => "专题",
			'child_table' => "special_summary",
			'show_field' => array(
					array('field'=>'title','title'=>'概要名','type'=>'text'),
					array('field'=>'content','title'=>'概要内容','type'=>'text'),
				),			
		);
		
		//$ret = $obj->create_table($data);
		*/
		$data = array(
			1 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => APP_UNIQUEID,
				'struct_ast_id' => "",
				'name'      => "专题",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => APP_UNIQUEID . '_publish.php',
				'action_get_content' => 'get_content',
				'action_insert_contentid' => 'update_content',
				'fid'		=> 0,
			),
			/*2 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => APP_UNIQUEID,
				'struct_ast_id' => "special_material",
				'name'      => "专题素材",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => APP_UNIQUEID.'_material_publish.php',
				'action_get_content' => 'get_content',
				'action_insert_contentid' => 'update_content',
				'fid'		=> 1,
			),	
			3 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => APP_UNIQUEID,
				'struct_ast_id' => "special_summary",
				'name'      => "专题概要",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => APP_UNIQUEID.'_summary_publish.php',
				'action_get_content' => 'get_content',
				'action_insert_contentid' => 'update_content',
				'fid'		=> 1,
			),	*/
		);
		
		include_once ROOT_PATH . 'lib/class/publishplan.class.php';
		$plan = new publishplan();
		$ret  = $plan->insert_plan_set($data);
		$this->addItem_withkey('message', 'success');
		$this->addItem_withkey('ret', $ret);
		$this->output();		
	}
	
}
$module = 'configuare';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'create_publish_table';	
}
$$module->$func();
?>