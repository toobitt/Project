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
	
	public function create_publish_table()
	{
		include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
		$table = new publishcontent();
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => APP_UNIQUEID,
			'struct_id'     => APP_UNIQUEID,
			'struct_ast_id' => "",
			'field'         => "id,expand_id,content_fromid,title,brief,tuji_sort_id,default_comment,user_name,create_time,ip,update_time,status,total_pic,keywords,catalog,total_visit,total_comment,cover_url,path,order_id,auto_cover,is_namecomment,is_orderby_name,is_add_water,water_id,tuji_source,url,weight,is_praise,praise_count",
			'array_field'   => "cover_url,latest",
			'array_child_field'   => "pic,img_info",
			'table_title'   => "图集",
                        'content_type'  => "图集",
			'field_sql'   	=> "  `id` int(11) NOT NULL AUTO_INCREMENT,
		  						  `expand_id` int(10) DEFAULT NULL,
		  						  `content_fromid` int(10) DEFAULT NULL,
		  						  `title` varchar(150) DEFAULT NULL COMMENT '内容标题',
		  						  `brief` varchar(500) DEFAULT NULL COMMENT '内容简要',
								  `tuji_sort_id` int(10) NOT NULL COMMENT '关联图集分类ID',
								  `default_comment` varchar(1000) DEFAULT NULL COMMENT '图集默认描述（用于其底下的图片继承用的）',
								  `user_name` char(64) NOT NULL,
								  `create_time` int(10) NOT NULL COMMENT '创建时间',
								  `ip` char(20) NOT NULL COMMENT '创建IP',
								  `update_time` int(10) NOT NULL COMMENT '修改时间',
								  `status` tinyint(2) NOT NULL COMMENT '状态 -1=>未审核 1=>已审核 2=>打回 ',
								  `total_pic` int(10) NOT NULL COMMENT '图片总数',
								  `keywords` varchar(250) DEFAULT NULL COMMENT '关键字',
								  `catalog` varchar(1000) DEFAULT NULL COMMENT '编目',
								  `total_visit` int(10) NOT NULL COMMENT '图集访问量',
								  `total_comment` int(10) NOT NULL COMMENT '图集评论数',
								  `cover_url` varchar(250) NOT NULL COMMENT '图集封面',
								  `path` varchar(250) NOT NULL COMMENT '图集目录',
								  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序id',
								  `auto_cover` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否自动设置封面',
								  `is_namecomment` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否以图片名作为描述',
								  `is_orderby_name` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否以图片名排序',
								  `is_add_water` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否增加水印',
								  `water_id` int(10) NOT NULL DEFAULT '0' COMMENT '所用水印id',
								  `tuji_source` int(10) NOT NULL DEFAULT '0' COMMENT '图集来源',
								  `url` varchar(200) NOT NULL COMMENT '外联',	
								  `weight` int(10) NOT NULL COMMENT '权重',	
								  `is_praise` tinyint(1) NOT NULL COMMENT '是否开启赞',
 			 				  	  `praise_count` int(11) NOT NULL COMMENT '赞的次数',
								  PRIMARY KEY (`id`),
								  KEY `content_fromid` (`content_fromid`),
								  KEY expand_id (expand_id)
								  ",
			'show_field' => array(
					0 => array('field'=>'title'      ,'title'=>'标题'	  ,'type'=>'text'),
					1 => array('field'=>'brief'      ,'title'=>'内容简要'   ,'type'=>'text'),
					2 => array('field'=>'user_name'  ,'title'=>'添加人'     ,'type'=>'text'),
					3 => array('field'=>'create_time','title'=>'创建时间'   ,'type'=>'text'),
					4 => array('field'=>'ip'         ,'title'=>'ip'       ,'type'=>'text'),
				),
			'child_table'=>'tuji_pics',
		);
		$ret = $table->create_table($data);
		
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => APP_UNIQUEID,
			'struct_id'     => APP_UNIQUEID,
			'struct_ast_id' => "tuji_pics",
			'field'         => "id,expand_id,content_fromid,title,brief,tuji_id,material_id,old_name,new_name,thumb_name,path,total_visit,status,is_cover,create_time,update_time,ip,order_id,pic",
			'array_field'   => "pic,img_info",
			'array_child_field'   => "",
			'table_title'   => "图片",
			'field_sql'   	=> "  `id` int(11) NOT NULL AUTO_INCREMENT,
		  						  `expand_id` int(10) DEFAULT NULL,
		  						  `content_fromid` int(10) DEFAULT NULL,
		  						  `title` varchar(150) DEFAULT NULL COMMENT '内容标题',
		  						  `brief` varchar(500) DEFAULT NULL COMMENT '内容简要',
		  						  `tuji_id` int(10) NOT NULL COMMENT '关联图集ID',
								  `material_id` int(10) NOT NULL DEFAULT '0' COMMENT '素材id',
								  `old_name` varchar(250) NOT NULL COMMENT '原始文件名称',
								  `new_name` varchar(250) NOT NULL COMMENT '上传之后的文件名',
								  `thumb_name` varchar(250) NOT NULL COMMENT '缩略图名称',
								  `path` varchar(250) NOT NULL,
								  `total_visit` int(10) NOT NULL,
								  `status` tinyint(2) NOT NULL COMMENT '0=>新增 1=>审核通过 2=>软删除 3=>彻底删除',
								  `is_cover` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是封面',
								  `create_time` int(10) NOT NULL COMMENT '上传时间',
								  `update_time` int(10) NOT NULL COMMENT '修改时间',
								  `ip` char(20) NOT NULL COMMENT '上传IP',
								  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序id',
								  `pic` varchar(500) NOT NULL COMMENT '图片串行话',
								  PRIMARY KEY (`id`)",
			'show_field' => array(
					0 => array('field'=>'title'      ,'title'=>'标题'	  ,'type'=>'text'),
					1 => array('field'=>'brief'      ,'title'=>'内容简要'   ,'type'=>'text'),
					2 => array('field'=>'pic'        ,'title'=>'图片路径'   ,'type'=>'img'),
					3 => array('field'=>'user_name'  ,'title'=>'添加人'     ,'type'=>'text'),
					3 => array('field'=>'create_time','title'=>'创建时间'   ,'type'=>'text'),
					5 => array('field'=>'ip'         ,'title'=>'ip'       ,'type'=>'text'),
				),
			'child_table'=>'',
		);
		$ret = $table->create_table($data);

		
		$data = array(
			1 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => APP_UNIQUEID,
				'struct_ast_id' => "",
				'name'      => "图集库",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => APP_UNIQUEID . '_publish.php',
				'action_get_content' => 'get_content',
				'action_insert_contentid' => 'update_content',
				'fid'		=> 0,
			),
			2 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => APP_UNIQUEID,
				'struct_ast_id' => "tuji_pics",
				'name'      => "图片",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => 'pic_publish.php',
				'action_get_content' => 'get_content',
				'action_insert_contentid' => 'update_content',
				'fid'		=> 1,
			),
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
	$func = 'show';	
}
$$module->$func();
?>