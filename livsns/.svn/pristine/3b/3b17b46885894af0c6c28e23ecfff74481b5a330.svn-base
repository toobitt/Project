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
		$table = new publishcontent();
		$data = array(
			'bundle_id' => APP_UNIQUEID,
			'module_id' => APP_UNIQUEID,
			'struct_id' => "article",
			'struct_ast_id' => "",
			'content_type' => "文稿",
			'expand_id' => "",
			'content_fromid' => "",
			'field' => "id,content_fromid,title,page_title,tcolor,isbold,isitalic,subtitle,keywords,catalog,brief,author,source,indexpic,outlink,weight,state,sort_id,is_img,is_affix,is_video,video_id,column_id,user_id,user_name,order_id,istop,istpl,tpl_file,pub_time,create_time,update_time,ip,iscomm,comm_num,click_num,is_del,water_id,water_name,content,expand_id,appid,appname,other_settings,ori_url,is_praise,praise_count",
			'array_field' => "other_settings",
			'array_child_field' => "pic",
			'field_sql' => "`id` int(10) NOT NULL AUTO_INCREMENT,
							  `content_fromid` int(10) NOT NULL ,
							  `title` varchar(200) NOT NULL COMMENT '标题',
							  `page_title` varchar(150) NOT NULL COMMENT '分页标题',
							  `tcolor` varchar(20) NOT NULL COMMENT '标题颜色',
							  `isbold` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标题是否为粗体。1为加粗，0为不加粗',
							  `isitalic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '标题是否为斜体。1为斜体，0不是斜体',
							  `subtitle` varchar(200) NOT NULL COMMENT '副标题',
							  `keywords` varchar(200) NOT NULL COMMENT '关键词用,隔开',
							  `catalog` varchar(1000) DEFAULT NULL COMMENT '编目',
							  `brief` varchar(900) NOT NULL COMMENT '简介',
							  `author` varchar(30) NOT NULL COMMENT '作者',
							  `source` varchar(30) NOT NULL COMMENT '文章来源',
							  `indexpic` int(10) NOT NULL COMMENT '索引图片id',
							  `outlink` varchar(255) NOT NULL COMMENT '外链',
							  `weight` smallint(150) NOT NULL COMMENT '权重',
							  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0-待发布1--发布成功',
							  `sort_id` int(10) NOT NULL COMMENT '所属分类',
							  `is_img` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否图片',
							  `is_affix` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否附件',
							  `is_video` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否视频',
							  `video_id` int(10) NOT NULL COMMENT '视频ID，用,',
							  `column_id` varchar(100) NOT NULL COMMENT '发布的栏目',
							  `user_id` int(10) NOT NULL COMMENT '发布者id',
							  `user_name` varchar(30) NOT NULL COMMENT '发布者',
							  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '排序ID',
							  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
							  `istpl` tinyint(1) NOT NULL COMMENT '是否是独立模板',
							  `tpl_file` varchar(100) NOT NULL COMMENT '//指定模板文件名',
							  `pub_time` int(10) NOT NULL COMMENT '发布时间',
							  `create_time` int(10) NOT NULL COMMENT '创建时间',
							  `update_time` int(10) NOT NULL COMMENT '更新时间',
							  `ip` varchar(60) NOT NULL,
							  `iscomm` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否准许评论，1准许，0不准许',
							  `comm_num` int(10) NOT NULL COMMENT '//评论数',
							  `click_num` int(10) NOT NULL COMMENT '//点击数',
							  `is_del` tinyint(1) NOT NULL COMMENT '0--删除 1--不删除',
							  `water_id` int(10) NOT NULL COMMENT '水印ID',
							  `water_name` varchar(50) NOT NULL COMMENT '水印标识',
							  `content` longtext NOT NULL COMMENT '内容',
							  `expand_id` int(10) NOT NULL COMMENT '发布系统',
							  `appid` int(10) NOT NULL COMMENT '客户端id',
							  `appname` varchar(50) NOT NULL COMMENT '客户端名称',
							  `other_settings` varchar(500) NOT NULL COMMENT '其他设置',
							  `ori_url` varchar(300) NOT NULL COMMENT '原始链接',
							  `is_praise` tinyint(1) NOT NULL COMMENT '是否开启赞',
 			 				  `praise_count` int(11) NOT NULL COMMENT '赞的次数',	
							  PRIMARY KEY (`id`),
							  KEY `content_fromid` (`content_fromid`),
							  KEY expand_id (expand_id)
							  ",
			'table_title' => "文稿",
			'child_table' => "material",
			'show_field' => array(
					array('field'=>'title','title'=>'标题','type'=>'text'),
					array('field'=>'brief','title'=>'简介','type'=>'text'),
					array('field'=>'keywords','title'=>'关键词','type'=>'text'),
					array('field'=>'author','title'=>'作者','type'=>'text'),
			),			
		);
		$ret = $table->create_table($data);
		//子表
		$data = array(
				
			'bundle_id' => APP_UNIQUEID,
				
			'module_id' => APP_UNIQUEID,
				
			'struct_id' => "article",
				
			'struct_ast_id' => "material",
				
			'content_type' => "素材",
				
			'expand_id' => "",
				
			'content_fromid' => "",
				
			'field' => "id,cid,expand_id,content_fromid,material_id,name,pic_host,pic_dir,pic_filepath,pic_filename,type,imgwidth,imgheight,filesize,isdel,create_time,ip,remote_url,title,brief,pic",
				
			'array_field' => "pic",
				
			'array_child_field' => "",
				
			'field_sql' => "`id` int(10) NOT NULL AUTO_INCREMENT,
						  
			`cid` int(10) NOT NULL COMMENT '//对应内容id',
						  
			`expand_id` int(10) NOT NULL COMMENT '//发布系统',
						  
			`content_fromid` int(10) NOT NULL COMMENT '//原内容id',
						  
			`material_id` int(10) NOT NULL COMMENT '图片服务器的素材ID',
						  
			`name` varchar(40) NOT NULL COMMENT '图片名称',
						  
			`pic_host` varchar(200) NOT NULL COMMENT 'host',
						  
			`pic_dir` varchar(100) NOT NULL COMMENT 'dir',
						  
			`pic_filepath` varchar(100) NOT NULL COMMENT '原图的存储路径',
						  
			`pic_filename` varchar(40) NOT NULL COMMENT '文件名称',
						  
			`type` varchar(10) NOT NULL COMMENT '图片类型',
						  
			`imgwidth` smallint(4) NOT NULL COMMENT '图片宽度',
						  
			`imgheight` smallint(4) NOT NULL COMMENT '图片高度',
						  
			`filesize` int(10) NOT NULL COMMENT '图片大小',
						 
			`isdel` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否删除0－是 1－ 否',
						  
			`create_time` int(10) NOT NULL,
						  
			`ip` varchar(60) NOT NULL,
						  
			`remote_url` varchar(200) NOT NULL COMMENT '图片原始远程地址',
						  
			`title` varchar(200) NOT NULL COMMENT '标题',
						  
			`brief` varchar(400) NOT NULL COMMENT '标题',
						  
			`pic` varchar(500) NOT NULL COMMENT '图片穿行话',
						  
			PRIMARY KEY (`id`)",
				
				'table_title' => "素材",
				
				'child_table' => "",
				
				'show_field' => array(
						
				array('field'=>'title','title'=>'标题','type'=>'text'),
						
				array('field'=>'brief','title'=>'简介','type'=>'text'),
						
				array('field'=>'name','title'=>'图片名称','type'=>'text'),
						
				array('field'=>'pic','title'=>'图片','type' => 'img'),
						
				array('field'=>'remote_url','title'=>'图片原始远程地址','type'=>'text'),

				),

			);
		
		$ret = $table->create_table($data);	
		$data = array(
				
			1 => array(
						
			'bundle_id' => APP_UNIQUEID,
						
			'module_id' => APP_UNIQUEID,
						
			'struct_id' => "article",
						
			'struct_ast_id' => "",
						
			'name'      => '文稿',
						
			'host'		=> $this->input['apihost'],
						
			'path'		=> $this->input['apidir'] . 'admin/',
						
			'filename'  => 'news_publish.php',
						
			'action_get_content' => 'get_content',
						
			'action_insert_contentid' => 'update_content',
						
			'fid'		=> 0,
				
			),
	
			2 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => "article",
				'struct_ast_id' => "material",
				'name'      => "素材",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => 'material_publish.php',
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