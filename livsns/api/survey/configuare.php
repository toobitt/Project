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
			'module_id'     => 'survey',
			'struct_id'     => "survey",
			'struct_ast_id' => "",
			'field'         => "id,content_fromid,title,brief,problem_num,submit_num,column_id,indexpic,status,node_id,used_survey_id,start_time,end_time,question_time,picture_ids,video_ids,publicontent_ids,is_ip,ip_limit_time,is_login,is_auto_submit,is_result_public,is_verifycode,order_id,user_id,update_user_id,audit_user_id,user_name,update_user_name,audit_user_name,org_id,appid,appname,create_time,update_time,audit_time,ip,expand_id,column_url,pub_time",
			'array_field'   => "indexpic,column_id",
			'array_child_field'   => "",
			'table_title'   => "调查问卷",
			'field_sql'   => "  `id` int(10) NOT NULL AUTO_INCREMENT,
			                    `content_fromid` int(11) NOT NULl,
			                    `title` varchar(45) DEFAULT NULL COMMENT '问卷标题	',
			                    `brief` text COMMENT ' 调查问卷的描述',
			                    `problem_num` int(10) NOT NULL COMMENT '题目数量',
			                    `submit_num` int(10) NOT NULL COMMENT '提交的问卷数量',
			                    `column_id` varchar(255) DEFAULT NULL COMMENT '栏目id',
			                    `indexpic` text COMMENT '索引图',
			                    `status` tinyint(1) NOT NULL COMMENT '审核 （0-未审核 1-已审核 2-已打回 ）',
			                    `node_id` int(10) DEFAULT NULL COMMENT '分类ID',
			                    `used_survey_id` int(10) NOT NULL COMMENT '引用的问卷id',
			                    `start_time` int(10) NOT NULL COMMENT '开始时间',
			                    `end_time` int(10) NOT NULL COMMENT '结束时间',
			                    `question_time` int(11) NOT NULL COMMENT '答题时间',
			                    `picture_ids` varchar(100) NOT NULL COMMENT '图片id',
			                    `video_ids` varchar(100) NOT NULL COMMENT '视频,音频id',
			                    `publicontent_ids` varchar(100) NOT NULL COMMENT '引用id',
			                    `is_ip` tinyint(4) NOT NULL COMMENT '是否限制ip(1是0否)',
			                    `ip_limit_time` int(10) NOT NULL COMMENT 'ip限制时长(单位:小时)',
			                    `is_login` tinyint(4) NOT NULL COMMENT '是否登录(1是,0否)',
			                    `is_auto_submit` tinyint(4) NOT NULL COMMENT '是否自动提交(1是,0否)',
			                    `is_result_public` tinyint(4) NOT NULL COMMENT '结果是否公开(1是0否)',
			                    `is_verifycode` tinyint(4) NOT NULL COMMENT '是否开启验证码(1是0否)',
			                    `order_id` int(10) DEFAULT NULL,
			                    `user_id` int(10) DEFAULT NULL,
			                    `update_user_id` int(10) NOT NULL,
			                    `audit_user_id` int(11) NOT NULL,
			                    `user_name` varchar(60) DEFAULT NULL,
			                    `update_user_name` varchar(30) NOT NULL,
			                    `audit_user_name` varchar(32) NOT NULL,
			                    `org_id` int(10) DEFAULT NULL,
			                    `appid` int(10) DEFAULT NULL,
			                    `appname` varchar(60) DEFAULT NULL,
			                    `create_time` int(10) DEFAULT NULL,
			                    `update_time` int(10) DEFAULT NULL,
			                    `audit_time` int(10) NOT NULL,
			                    `ip` char(64) DEFAULT NULL,
			                    `expand_id` int(10) NOT NULL,
			                    `column_url` varchar(1000) NOT NULL,
			                    `pub_time` int(10) NOT NULL,
			                    PRIMARY KEY (`id`),
			                    KEY `content_fromid` (`content_fromid`),
							    KEY expand_id (expand_id)
							  ",
			'show_field' => array(
					0 => array('field'=>'title'      ,'title'=>'标题'	  ,'type'=>'text'),
					1 => array('field'=>'brief'      ,'title'=>'描述'   	  ,'type'=>'text'),
					2 => array('field'=>'indexpic'   ,'title'=>'图片路径'   ,'type'=>'img'),
					3 => array('field'=>'user_name'  ,'title'=>'添加人'     ,'type'=>'text'),
					4 => array('field'=>'create_time','title'=>'创建时间'   ,'type'=>'text'),
					5 => array('field'=>'ip'         ,'title'=>'创建者ip'   ,'type'=>'text'),
			),
			'child_table'=>'',
		);
		$ret = $table->create_table($data);
		
		$data = array(
			1 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => 'survey',
				'struct_id' => 'survey',
				'struct_ast_id' => "",
				'name'      => "调查问卷",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => 'survey_publish.php',
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