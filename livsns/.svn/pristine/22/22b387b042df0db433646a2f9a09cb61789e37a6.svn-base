<?php
define('MOD_UNIQUEID','material_publish');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
class materialPublish extends appCommonFrm implements publish
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	function create_table()
	{
		$table = new publishcontent();
		$data = array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => MOD_UNIQUEID,
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
			$this->addItem($ret);
			$this->output();		
	}
	function get_content()
	{
		$id = intval($this->input['from_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		
		$sql = "select * from " . DB_PREFIX . "content where 1 and id=".$id;
		$content = $this->db->query_first($sql);
		
		
		$sql = "SELECT * FROM " . DB_PREFIX ."materials where 1 and  expend_id =0  and content_id=" . $id . $data_limit;
		
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info)) 
		{
			$row['bundle_id'] = APP_UNIQUEID;
			$row['module_id'] = MOD_UNIQUEID;
			$row['struct_id'] = 'contribute';
			$row['struct_ast_id'] = 'materials';
			$row['expand_id'] = $content['expand_id'];
			$row['content_fromid'] = $row['materialid'];
			$row['indexpic'] = 'index.php';
			$row['ip'] = hg_getip();
			$row['user_id'] = $this->user['user_id'];
			$row['user_name'] = $this->user['user_name'];
			$row['pic'] = array(
							'host'=>$row['host'],
							'dir'=>$row['dir'],
							'filepath'=>$row['material_path'],
							'filename'=>$row['pic_name'],	
							);
			$video = array();
            if(!empty($row['vodid']))
			{
				$video = array(
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'id'=>$row['vodid']
				);
			}
			$row['video'] = $video;
			unset($row['id']);
			$ret[] = $row;
		}
		$this->addItem($ret);
		$this->output();
	}
	
 	function update_content()
 	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		$sql = "update " . DB_PREFIX. "materials set expand_id = " . $data['expand_id'] . " where materialid =" . $data['from_id'];
		$this->db->query($sql);
		$this->addItem('true');
		$this->output();
 	}

 	/**
 	 * 删除这条内容的发布
 	 *
 	 */
 	function delete_publish()
 	{
 				
 	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
	
}
$out = new materialPublish();
$action = $_INPUT['a'];
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
$out->$action(); 