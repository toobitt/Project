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
			'module_id'     => 'vod',
			'struct_id'     => "vod",
			'struct_ast_id' => "",
			'field'         => "id,expand_id,content_fromid,title,brief,video_order_id,channel_id,bitrate,vod_sort_id,status,author,user_id,addperson,source,starttime,delay_time,height,start,duration,width,keywords,catalog,type,transize,totalsize,audit,create_time,update_time,ip,vod_leixing,aspect,vtype,from_appid,from_appname,server,hostwork,video_path,video_filename,weight,outlink,ori_url,is_praise,praise_count",
			'array_field'   => "img_info,collects",
			'array_child_field'   => "",
			'table_title'   => "视频",
			'content_type' => "视频",
			'field_sql'   => "id int(11) NOT NULL AUTO_INCREMENT,
	  						  expand_id int(10) DEFAULT NULL,
	  						  content_fromid int(10) DEFAULT NULL,
	  						  title varchar(150) DEFAULT NULL COMMENT '内容标题',
	  						  brief varchar(500) DEFAULT NULL COMMENT '内容简要',
							  video_order_id int(10) DEFAULT '0' COMMENT '视频排序',
							  channel_id int(10) NOT NULL COMMENT '频道ID',
							  bitrate int(10) DEFAULT '0' COMMENT '码流',
							  vod_sort_id int(10) DEFAULT '0' COMMENT '视频类别',
							  status tinyint(1) DEFAULT '0' COMMENT '转码状态',
							  author varchar(20) DEFAULT NULL COMMENT '作者',
							  user_id int(10) DEFAULT NULL COMMENT '添加人user_id',
							  addperson varchar(20) DEFAULT NULL COMMENT '添加人',
							  source varchar(60) DEFAULT NULL' COMMENT '频道来源',
							  starttime int(10) NOT NULL COMMENT '录播节目开始时间',
							  delay_time int(10) NOT NULL COMMENT '录播频道回看时间',
							  height smallint(10) DEFAULT '0' COMMENT '视频的高度',
							  start int(11) DEFAULT '0' COMMENT '视频开始时间',
							  duration int(10) DEFAULT '0' COMMENT '视频总的时长',
							  width smallint(10) DEFAULT '0' COMMENT '视频的宽度',
							  keywords varchar(250) DEFAULT NULL COMMENT '视频id',
							  catalog  varchar(1000) DEFAULT NULL COMMENT '编目',
							  type varchar(10) DEFAULT NULL COMMENT '视频类型（avi，还是3gp等等）',
							  transize int(10) DEFAULT NULL COMMENT '转码中的视频已转码大小',
							  totalsize int(10) DEFAULT NULL,
							  audit tinyint(4) DEFAULT '0',
							  create_time int(10) DEFAULT NULL COMMENT '创建时间',
							  update_time int(10) DEFAULT NULL COMMENT '更新时间',
							  ip char(20) DEFAULT NULL COMMENT 'ip',
							  vod_leixing int(10) DEFAULT '0' COMMENT '所属类型',
							  aspect char(6) NOT NULL COMMENT '比率',
							  vtype tinyint(1) NOT NULL DEFAULT '1' COMMENT '视频类型1－音视频，2－音频',
							  from_appid int(10) NOT NULL COMMENT '来自客户端id',
							  from_appname varchar(30) NOT NULL COMMENT '来自客户端名称',
							  server varchar(20) NOT NULL COMMENT '来自服务器',
							  hostwork varchar(50) NOT NULL COMMENT '域名',
							  video_path varchar(255) NOT NULL COMMENT '视频路径',
							  video_filename varchar(50) NOT NULL COMMENT '视频文件名',
							  weight int(10) NOT NULL COMMENT '权重',
							  outlink varchar(100) NOT NULL COMMENT '外联',
							  ori_url varchar(300) NOT NULL COMMENT '视频原始url',
							  `is_praise` tinyint(1) NOT NULL COMMENT '是否开启赞',
 			 				  `praise_count` int(11) NOT NULL COMMENT '赞的次数',	
							  PRIMARY KEY (`id`),
							  KEY `content_fromid` (`content_fromid`),
							  KEY expand_id (expand_id)
							  ",
			'show_field' => array(
					0 => array('field'=>'title'      ,'title'=>'标题'	  ,'type'=>'text'),
					1 => array('field'=>'brief'      ,'title'=>'内容简要'   ,'type'=>'text'),
					2 => array('field'=>'video'      ,'title'=>'视频路径'   ,'type'=>'video'),
					3 => array('field'=>'addperson'  ,'title'=>'添加人'     ,'type'=>'text'),
					4 => array('field'=>'create_time','title'=>'创建时间'   ,'type'=>'text'),
					5 => array('field'=>'ip'         ,'title'=>'标题'      ,'type'=>'text'),
				),
			'child_table'=>'',
		);
		$ret = $table->create_table($data);
		

		
		$data = array(
			1 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => 'vod',
				'struct_id' => 'vod',
				'struct_ast_id' => "",
				'name'      => "视频库",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => 'vod_publish.php',
				'action_get_content' => 'get_content',
				'action_insert_contentid' => 'update_content',
				'fid'		=> 0,
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