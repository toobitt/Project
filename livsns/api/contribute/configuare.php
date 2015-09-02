<?php
define('ROOT_DIR', '../../');
define('WITHOUT_DB', true);
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');
require(ROOT_DIR . 'lib/class/curl.class.php');
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
			'bundle_id' => APP_UNIQUEID,
			'module_id' => APP_UNIQUEID,
			'struct_id' => "content",
			'struct_ast_id' => "",
			'content_type' => "爆料",
			'expand_id' => "",
			'content_fromid' => "",
			'field' => "id,expand_id,content_fromid,title,brief,sort_id,keywords,material_id,appid,client,longitude,
						latitude,create_time,update_time,user_id,user_name,audit,is_pub,order_id,expand_id,column_id,
						column_url,publish_time,content,indexpic,opinion,event_time,event_address,event_suggest,event_user_name,event_user_tel",
			'array_field'=>"indexpic",	
			'array_child_field'=>"pic,video",	
			'field_sql' =>   "`id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
							  `sort_id` int(10) NOT NULL COMMENT '分类',
							  `title` varchar(100) NOT NULL COMMENT '标题',
							  `keywords` varchar(30) DEFAULT NULL COMMENT '关键字',
							  `brief` varchar(1000) NOT NULL COMMENT '简要',
							  `event_time` int(10) NOT NULL COMMENT '发生时间',
							  `event_address` varchar(255) NOT NULL COMMENT '事件发生地',
							  `event_suggest` text NOT NULL COMMENT '报料人诉求',
							  `event_user_name` varchar(255) NOT NULL COMMENT '报料人',
							  `event_user_tel` varchar(255) NOT NULL COMMENT '报料人电话',
							  `material_id` int(10) NOT NULL COMMENT '素材库的ID',
							  `appid` int(10) NOT NULL COMMENT 'appid',
							  `client` char(20) NOT NULL COMMENT '客户端',
							  `longitude` float(17,14) NOT NULL COMMENT '所在经度',
							  `latitude` float(17,14) NOT NULL COMMENT '所在的纬度',
							  `create_time` int(10) NOT NULL COMMENT '发布时间',
							  `update_time` int(10) NOT NULL COMMENT '更新时间',
							  `user_id` int(10) NOT NULL COMMENT '用户id',
							  `user_name` char(30) NOT NULL COMMENT '用户名',
							  `audit` tinyint(1) NOT NULL COMMENT '审核',
							  `is_pub` tinyint(1) NOT NULL COMMENT '发布状态位',
							  `order_id` int(10) NOT NULL COMMENT '排序ID',
							  `expand_id` int(10) NOT NULL COMMENT '在发布系统里面的id',
							  `column_id` varchar(500) NOT NULL COMMENT '发布到的栏目id',
							  `column_url` varchar(1000) NOT NULL COMMENT '栏目url',
							  `publish_time` int(10) NOT NULL COMMENT '发布时间',
							  `content_fromid` int(10) NOT NULL COMMENT 'content_fromid',
							  `content` text NOT NULL COMMENT '爆料内容',
							  `indexpic` varchar(500) NOT NULL COMMENT '图片串行话',
							  `opinion` text NOT NULL COMMENT '审核意见', 
							  PRIMARY KEY (`id`),
							  KEY `content_fromid` (`content_fromid`),
							  KEY expand_id (expand_id)
							  ",
			'table_title' => "爆料",
			'child_table' => "materials",
			'show_field' => array(
					array('field'=>'title','title'=>'爆料标题','type'=>'text'),
					array('field'=>'brief','title'=>'简介','type'=>'text'),
					array('field'=>'content','title'=>'爆料内容','type'=>'text'),				
			),			
		);
		$ret = $table->create_table($data);
		
		$data = array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => "content",
				'struct_ast_id' => "materials",
				'content_type' => "素材",
				'expand_id' => "",
				'content_fromid' => "",
				'field' => "id,expand_id,content_fromid, title,brief,content_id,mtype,original_id,host,dir,material_path,vodid,pic_name,pic,video",
				'array_field'=>"pic,video",
				'array_child_field'=>"",
				'field_sql' => 	"`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '素材关联ID',
								`title` varchar(100) NOT NULL COMMENT '标题',
								`brief` varchar(1000) NOT NULL COMMENT '简要',
								  `content_id` int(10) NOT NULL COMMENT '内容id',
								  `mtype` char(20) NOT NULL COMMENT '素材类型',
								  `original_id` char(32) NOT NULL COMMENT '素材原始文件ID',
								  `host` varchar(100) NOT NULL COMMENT 'host',
								  `dir` varchar(100) NOT NULL COMMENT 'dir',
								  `material_path` varchar(1024) NOT NULL,
								  `vodid` varchar(50) NOT NULL,
								  `pic_name` varchar(50) NOT NULL COMMENT '图片名',
								  `expand_id` int(10) NOT NULL COMMENT '在发布系统里面的id',
								  `content_fromid` int(10) NOT NULL COMMENT 'content_fromid',
								  `pic` varchar(500) NOT NULL COMMENT '图片串行话',
								  `video` varchar(500) NOT NULL COMMENT '视频串行话',
								  PRIMARY KEY (`id`)",
				'table_title' => "爆料",
				'child_table' => "",
				'show_field' => array(
						array('field'=>'title','title'=>'标题','type'=>'text'),
						array('field'=>'brief','title'=>'简介','type'=>'text'),
						array('field'=>'pic_name','title'=>'图片名称','type'=>'text'),
						array('field'=>'pic','title'=>'图片','type' => 'img'),
						array('field'=>'video','title'=>'视频',type=>"video"),
				),			
			);
		$ret = $table->create_table($data);

		
		$data = array(
			1 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => 'content',
				'struct_ast_id' => "",
				'name'      => '爆料',
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
				'struct_id' => 'content',
				'struct_ast_id' => "materials",
				'name'      => '素材',
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


    public function setting_group()
    {
        $group = array(
            'base' => '基础设置',
            'db' => '数据库设置',
            'watermark' => '水印设置',
        );
        $this->addItem($group);
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