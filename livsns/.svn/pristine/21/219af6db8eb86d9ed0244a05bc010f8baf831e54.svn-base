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
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => 'vote',
			'struct_id'     => "vote",
			'struct_ast_id' => "",
			'field'         => "id,content_fromid,pictures,vote_id,title,describes,keywords,start_time,end_time,ip_limit_time,userid_limit_time,is_ip,is_userid,is_user_login,is_verify_code,option_num,option_type,min_option,max_option,is_other,total,order_id,create_time,update_time,ip,status,is_open,pictures_info,more_info,source_type,node_id,org_id,user_id,user_name,update_user_id,update_user_name,update_appid,update_appname,update_ip,column_id,column_url,expand_id,vod_id,ini_total,audit_user_id,audit_user_name,audit_time,publishcontent_id,is_praise,praise_count",
			'array_field'   => "pictures_info,more_info",
			'array_child_field'   => "",
			'table_title'   => "投票",
			'field_sql'   => "  `id` int(10) NOT NULL AUTO_INCREMENT,
			                    `content_fromid` int(11) NOT NULl,
			                    `pictures` varchar(60) NOT NULL COMMENT '图片',
			                    `vote_id` int(10) NOT NULL COMMENT '在liv_vote里面对应的id',
			                    `title` varchar(255) NOT NULL COMMENT '标题',
			                    `describes` varchar(1024) NOT NULL COMMENT '描述',
			                    `keywords` varchar(1024) NOT NULL COMMENT '关键词',
			                    `start_time` int(10) NOT NULL COMMENT '开始时间',
			                    `end_time` int(10) NOT NULL COMMENT '结束时间',
			                    `ip_limit_time` tinyint(2) NOT NULL COMMENT 'ip时间限制',
			                    `userid_limit_time` tinyint(2) NOT NULL COMMENT '用户id时间限制',
			                    `is_ip` tinyint(1) NOT NULL COMMENT 'ip限制',
			                    `is_userid` tinyint(1) NOT NULL COMMENT '用户id',
			                    `is_user_login` tinyint(1) NOT NULL COMMENT '是否需要用户登陆 （1-需要 0-不需要）',
			                    `is_verify_code` tinyint(1) NOT NULL COMMENT '验证码开启',
			                    `verify_type` int(11) NOT NULl,
			                    `option_num` int(10) NOT NULL COMMENT '选项数',
			                    `option_type` tinyint(1) NOT NULL COMMENT '选项类型(1-单选 2-多选)',
			                    `min_option` tinyint(2) NOT NULL COMMENT '最小选项数',
			                    `max_option` tinyint(2) NOT NULL COMMENT '此投票最多可选，0则为无限制',
			                    `is_other` tinyint(1) NOT NULL COMMENT '是否有其他',
			                    `total` int(20) NOT NULL COMMENT '投票总数',
			                    `order_id` int(10) NOT NULL COMMENT '排序id',
			                    `create_time` int(10) NOT NULL COMMENT '创建时间',
			                    `update_time` int(10) NOT NULL COMMENT '更新时间',
			                    `ip` varchar(64) NOT NULL COMMENT '创建者IP',
			                    `status` tinyint(1) NOT NULL COMMENT '审核 （0-未审核 1-已审核 2-已打回 ）',
			                    `is_open` tinyint(4) NOT NULL COMMENT '是否开启 （0-关闭 1-开启）',
			                    `pictures_info` varchar(512) NOT NULL COMMENT '图片信息',
			                    `more_info` text NOT NULL COMMENT '更多信息',
			                    `source_type` tinyint(1) NOT NULL COMMENT '来源类型 (1-网友 0-管理员)',
			                    `node_id` int(10) NOT NULL COMMENT '分类id',
			                    `org_id` int(10) NOT NULL COMMENT '组织id',
			                    `user_id` int(10) NOT NULL,
			                    `user_name` char(64) NOT NULL,
			                    `appid` int(10) NOT NULL,
			                    `appname` char(64) NOT NULL,
			                    `weight` int(10) NOT NULL,
			                    `update_org_id` int(10) NOT NULL COMMENT '更新者组织id',
			                    `update_user_id` int(10) NOT NULL COMMENT '更新者用户id',
			                    `update_user_name` char(32) NOT NULL COMMENT '更新者用户名',
			                    `update_appid` int(10) NOT NULL COMMENT '更新者应用id',
			                    `update_appname` char(32) NOT NULL COMMENT '更新者应用名',
			                    `update_ip` char(32) NOT NULL COMMENT '更新者ip',
			                    `column_id` varchar(500) NOT NULL COMMENT '发布到栏目',
			                    `column_url` varchar(1000) NOT NULL COMMENT '发布到栏目url',
			                    `expand_id` int(10) NOT NULL COMMENT '发布系统id',
			                    `vod_id` varchar(1000) NOT NULL COMMENT '视频id',
			                    `ini_total` int(10) NOT NULL COMMENT '初始化总数',
			                    `audit_user_id` int(11) NOT NULL COMMENT '审核id',
			                    `audit_user_name` varchar(60) NOT NULL COMMENT '审核人姓名',
			                    `audit_time` int(10) NOT NULL COMMENT '审核时间',
			                    `publishcontent_id` varchar(255) NOT NULL,
								`is_praise` tinyint(1) NOT NULL COMMENT '是否开启赞',
 			 				    `praise_count` int(11) NOT NULL COMMENT '赞的次数',	
							  PRIMARY KEY (`id`),
							  KEY `content_fromid` (`content_fromid`),
							  KEY expand_id (expand_id)
							  ",
			'show_field' => array(
					0 => array('field'=>'title'      ,'title'=>'标题'	  ,'type'=>'text'),
					1 => array('field'=>'describes'  ,'title'=>'描述'   	  ,'type'=>'text'),
					2 => array('field'=>'pictures'   ,'title'=>'图片路径'   ,'type'=>'img'),
					3 => array('field'=>'user_name'  ,'title'=>'添加人'     ,'type'=>'text'),
					4 => array('field'=>'create_time','title'=>'创建时间'   ,'type'=>'text'),
					5 => array('field'=>'ip'         ,'title'=>'创建者ip'   ,'type'=>'text'),
			),
			'child_table'=>'',
		);
		$ret = $table->create_table($data);
		/******
		$data = array(
			'bundle_id' => APP_UNIQUEID,
			'module_id' => APP_UNIQUEID,
			'struct_id' => APP_UNIQUEID,
			'struct_ast_id' => "special_material",
			'content_type' => "",
			'field' => "id,name,pic,host,dir,filepath,filename,type,imgwidth,imgheight,filesize,mid,expand_id",
			'array_field' => "",
			'array_child_field' => "",
			'field_sql' => "  `id` int(10) NOT NULL AUTO_INCREMENT,
			                  `name` varchar(40) NOT NULL COMMENT '图片名称',
			                  `pic` varchar(500) NOT NULL COMMENT '图片信息',
			                  `host` varchar(200) NOT NULL COMMENT '主机地址',
			                  `dir` varchar(100) NOT NULL COMMENT '路径地址',
			                  `filepath` varchar(100) NOT NULL COMMENT '原图的存储路径',
			                  `filename` varchar(40) NOT NULL COMMENT '文件名称',
			                  `type` varchar(10) NOT NULL COMMENT '附件扩展名',
			                  `imgwidth` smallint(4) NOT NULL COMMENT '图片宽度',
			                  `imgheight` smallint(4) NOT NULL COMMENT '图片高度',
			                  `filesize` int(10) NOT NULL COMMENT '图片大小',
			                  `mid` int(11) NOT NULL,
			                  `expand_id` int(10) NOT NULL COMMENT '发布系统id',
			                   PRIMARY KEY (`id`)
							  KEY `expand_id` (`expand_id`)",
		
			'table_title' => "投票素材",
			'child_table' => "material",
			'show_field' => array(
					array('field'=>'name','title'=>'素材名','type'=>'text'),
				),			
		);
		
		$ret = $obj->create_table($data);
		*******/
		$data = array(
			1 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => 'vote',
				'struct_id' => 'vote',
				'struct_ast_id' => "",
				'name'      => "投票",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => 'vote_publish.php',
				'action_get_content' => 'get_content',
				'action_insert_contentid' => 'update_content',
				'fid'		=> 0,
			),
			/*********
			2 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => APP_UNIQUEID,
				'struct_ast_id' => "material",
				'name'      => "投票素材",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => APP_UNIQUEID.'_material_publish.php',
				'action_get_content' => 'get_content',
				'action_insert_contentid' => 'update_content',
				'fid'		=> 1,
			),	
			**********/
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