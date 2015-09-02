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
		//主表
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => APP_UNIQUEID,
			'struct_id'     => APP_UNIQUEID,
			'struct_ast_id' => "",
			'field'         => "id,expand_id,content_fromid,title,brief,img,update_status,update_speed,playcount,duration,status,type,play_sort_id,district,year,lang,director,main_performer,awards,publisher,copyright_limit,user_id,user_name,org_id,create_time,update_time,ip,order_id,url,weight",
			'array_field'   => "img",
			'array_child_field'   => "img",
			'table_title'   => "电视剧",
                        'content_type'  => "电视剧",
			'field_sql'   	=> "  `id` int(11) NOT NULL AUTO_INCREMENT,
		  						  `expand_id` int(10) DEFAULT NULL,
		  						  `content_fromid` int(10) DEFAULT NULL,
								  `title` varchar(60) NOT NULL COMMENT '电视剧名称',
								  `brief` text NOT NULL COMMENT '简介',
								  `img` varchar(256) NOT NULL,
								  `update_status` int(10) NOT NULL COMMENT '更新状态',
								  `update_speed` int(10) NOT NULL COMMENT '更新速度',
								  `playcount` int(11) NOT NULL COMMENT '剧集总数',
								  `duration` int(10) NOT NULL COMMENT '每集时长',
								  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
								  `type` varchar(60) NOT NULL COMMENT '电视剧类型相当于关键字',
								  `play_sort_id` int(10) NOT NULL COMMENT '电视剧分类',
								  `district` int(10) NOT NULL COMMENT '所属地区',
								  `year` varchar(20) NOT NULL,
								  `lang` int(10) NOT NULL COMMENT '语言',
								  `director` varchar(60) NOT NULL COMMENT '导演',
								  `main_performer` varchar(60) NOT NULL COMMENT '主演',
								  `awards` text NOT NULL COMMENT '所获奖项',
								  `publisher` varchar(60) NOT NULL COMMENT '版权商',
								  `copyright_limit` int(10) NOT NULL COMMENT '版权期限',
								  `user_id` int(10) NOT NULL,
								  `user_name` varchar(60) NOT NULL,
								  `org_id` int(10) NOT NULL,
								  `create_time` int(10) NOT NULL,
								  `update_time` int(10) NOT NULL,
								  `ip` varchar(20) NOT NULL,
								  `order_id` int(10) NOT NULL,
								  `url` varchar(200) NOT NULL COMMENT '外链',
								  `weight` int(10) NOT NULL COMMENT '权重',
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
					5 => array('field'=>'img'        ,'title'=>'电视剧封面' ,'type'=>'img'),
				),
			'child_table'=>'tv_episode',
		);
		$ret = $table->create_table($data);
		
		//子表
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => APP_UNIQUEID,
			'struct_id'     => APP_UNIQUEID,
			'struct_ast_id' => 'tv_episode',
			'field'         => "id,expand_id,content_fromid,title,brief,tv_play_id,video_id,img,index_num,user_name,user_id,org_id,create_time,update_time,ip,url",
			'array_field'   => "img",
			'array_child_field'   => "",
			'table_title'   => "剧集",
			'field_sql'   	=> "  `id` int(11) NOT NULL AUTO_INCREMENT,
		  						  `expand_id` int(10) DEFAULT NULL,
		  						  `content_fromid` int(10) DEFAULT NULL,
		  						  `title` varchar(150) DEFAULT NULL COMMENT '内容标题',
		  						  `brief` varchar(500) DEFAULT NULL COMMENT '内容简要',
								  `tv_play_id` int(10) NOT NULL COMMENT '所属的电视剧id',
								  `video_id` int(10) NOT NULL COMMENT '对应视频库里面的视频的id',
								  `img` varchar(255) NOT NULL COMMENT '剧集的索引图',
								  `index_num` int(10) NOT NULL COMMENT '剧集索引',
								  `user_name` varchar(20) NOT NULL,
								  `user_id` int(11) NOT NULL,
								  `org_id` int(11) NOT NULL,
								  `create_time` int(10) NOT NULL,
								  `update_time` int(10) NOT NULL,
								  `ip` varchar(20) NOT NULL,
								  `url` varchar(200) NOT NULL COMMENT '外链',
								  PRIMARY KEY (`id`)",
			'show_field' => array(
					0 => array('field'=>'title'      ,'title'=>'剧集标题'	  ,'type'=>'text'),
					1 => array('field'=>'brief'      ,'title'=>'内容简要'   ,'type'=>'text'),
					2 => array('field'=>'img'        ,'title'=>'剧集图片'   ,'type'=>'img'),
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
				'name'      => "电视剧",
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
				'struct_ast_id' => "tv_episode",
				'name'      => "剧集",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => 'tv_episode_publish.php',
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