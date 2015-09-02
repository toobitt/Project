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
		$obj = new publishcontent();
		$data = array(
			'bundle_id' => APP_UNIQUEID,
			'module_id' => APP_UNIQUEID,
			'struct_id' => APP_UNIQUEID,
			'struct_ast_id' => "",
			'content_type' => "cutv视频",
			'field' => "id,expand_id,content_fromid,orderid,array_child_field,keywords,indexpic,title,brief,category_id,cpid,cpname,maid,video_source,video_id,program_form,media_type,program_type,duration,status,bitrate,create_date,create_time,ip",
			'array_field' => "indexpic",
			'array_child_field' => "",
			'field_sql' => "  `id` int(10) NOT NULL AUTO_INCREMENT,
							  `content_fromid` int(10) NOT NULL ,
							  `title` varchar(200) NOT NULL COMMENT '标题',
							  `brief` varchar(500) NOT NULL COMMENT '描述',
							  `category_id` varchar(50) NOT NULL,
							  `orderid` int(10) NOT NULL,
							  `keywords` varchar(50) NOT NULL,
							  `cpid` varchar(50) NOT NULL,
							  `cpname` varchar(10) NOT NULL,
							  `maid` varchar(50) NOT NULL,
							  `video_source` varchar(120) NOT NULL,
							  `video_id` int(10) NOT NULL,
							  `indexpic` varchar(500) NOT NULL,
							  `program_form` tinyint(1) NOT NULL,
							  `media_type` tinyint(1) NOT NULL,
							  `program_type` tinyint(1) NOT NULL,
							  `duration` int(5) NOT NULL,
							  `status` tinyint(1) NOT NULL,
							  `bitrate` int(6) NOT NULL,
							  `create_date` int(10) NOT NULL,
							  `create_time` int(10) NOT NULL,
							  `ip` varchar(60) NOT NULL,
							  `expand_id` int(10) NOT NULL COMMENT '发布系统',
							   PRIMARY KEY (`id`),
							  KEY `content_fromid` (`content_fromid`),
							  KEY expand_id (expand_id)
							  ",
		
			'table_title' => "WEB视频",
			'child_table' => "",
			'show_field' => array(
					array('field'=>'cpname','title'=>'电视台','type'=>'text'),
					array('field'=>'title','title'=>'标题','type'=>'text'),
					array('field'=>'brief','title'=>'简介','type'=>'text'),
				),			
		);
		
		$ret = $obj->create_table($data);
		

		
		$data = array(
			1 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => APP_UNIQUEID,
				'struct_id' => APP_UNIQUEID,
				'struct_ast_id' => "",
				'name'      => "CUTV",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => APP_UNIQUEID . '_publish.php',
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