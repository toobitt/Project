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
	
	function settings_process()
	{
		$dir = CUR_CONF_PATH . 'data/';
		if(!is_writable($dir))
		{
			$this->errorOutput("目录不可写");
		}
		file_put_contents($dir.'ping.txt', 'ok');
		$addomain = $this->input['define']['FB_DOMAIN'];
		$file = trim($addomain,'/').'/ping.txt';
		$ping = @file_get_contents($file);
		unlink($dir.'ping.txt');
		if($ping != 'ok')
		{
			$this->errorOutput($ping);
		}
	}
	
	public function create_publish_table()
	{
		include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
		$table = new publishcontent();
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => 'feedback',
			'struct_id'     => "feedback",
			'struct_ast_id' => "",
			'field'         => "id,content_fromid,title,brief,node_id,column_id,status,counts,is_login,order_id,org_id,user_id,user_name,create_time,update_user_id,update_user_name,update_time,audit_user_id,audit_user_name,audit_time,ip,appid,appname,expand_id,column_url,pub_time",
			'array_field'   => "column_id",
			'array_child_field'   => "",
			'table_title'   => "表单反馈",
			'field_sql'     => "  `id` int(11) NOT NULL AUTO_INCREMENT,
			                      `content_fromid` int(11) NOT NULl,
			                      `title` varchar(255) NOT NULL COMMENT '表单标题',
			                      `brief` text NOT NULL COMMENT '反馈表单描述',
			                      `node_id` int(11) NOT NULL COMMENT '分类',
			                      `column_id` varchar(1000) NOT NULL COMMENT '栏目',
			                      `status` tinyint(1) NOT NULL COMMENT '审核状态（0-未审核，1-已审核，2-已打回）',
			                      `counts` int(11) NOT NULL COMMENT '提交表单数量',
			                      `is_login` tinyint(1) NOT NULL COMMENT '是否需要登录',
			                      `order_id` int(11) NOT NULL,
			                      `org_id` int(11) NOT NULL,
			                      `user_id` int(11) NOT NULL,
			                      `user_name` varchar(32) NOT NULL,
			                      `create_time` int(10) NOT NULL,
			                      `update_user_id` int(11) NOT NULL,
			                      `update_user_name` varchar(32) NOT NULL,
			                      `update_time` int(10) NOT NULL,
			                      `audit_user_id` int(11) NOT NULL,
			                      `audit_user_name` varchar(32) NOT NULL,
			                      `audit_time` int(10) NOT NULL,
			                      `ip` int(15) NOT NULL,
			                      `appid` int(11) NOT NULL,
			                      `appname` varchar(60) NOT NULL,
			                      `expand_id` int(11) NOT NULL,
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
				'module_id' => 'feedback',
				'struct_id' => 'feedback',
				'struct_ast_id' => "",
				'name'      => "反馈表单",
				'host'		=> $this->input['apihost'],
				'path'		=> $this->input['apidir'] . 'admin/',
				'filename'  => 'feedback_publish.php',
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