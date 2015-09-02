<?php
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
define('MOD_UNIQUEID','material');
class  MaterialPublish  extends BaseFrm implements publish
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function unknow()
	{
		$this->errorOutput('方法不存在!');
	}
	

	function create_table()
	{
		$obj = new publishcontent();
		$data = array(
			'bundle_id' => APP_UNIQUEID,
			'module_id' => MOD_UNIQUEID,
			'struct_id' => "magazine",
			'struct_ast_id' => "material",
			'content_type' => "素材",
			'expand_id' => "",
			'content_fromid' => "",
			'array_field'=>"pic",
			'array_child_field'=>"",
			'field' => "id,cid,expand_id,content_fromid,material_id,name,pic_host,pic_dir,pic_filepath,pic_filename,type,imgwidth,imgheight,filesize,isdel,create_time,ip,remote_url,title,brief,pic",
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
		$ret = $obj->create_table($data);
		$this->addItem($ret);
		$this->output();
	}
	/**
	 * 
	 * 修改杂志表发布栏目 ...
	 * 
	 */
	public function update_content()
	{	
		$data = $this->input['data'];		
		if(empty($data))
		{
			$this->errorOutput('data is empty!');
		}
		$sql = "UPDATE " . DB_PREFIX. "material SET expand_id = " . $data['expand_id'] . " WHERE id =" . $data['from_id'];
		$this->db->query($sql);
		$this->addItem('true');
		$this->output();
	}
	/**
	 * 获取杂志内容
	 * 
	 */
	public function get_content()
	{
		$id = intval($this->input['from_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;	
		
		$sql = "SELECT expand_id FROM ".DB_PREFIX."article WHERE 1 and id=".$id;
		$extend = $this->db->query_first($sql);
		
		$sql = "SELECT * FROM " . DB_PREFIX ."material WHERE 1 AND cid=" . $id ." AND expand_id = '' AND isdel = 1 ". $data_limit;
		
		$query = $this->db->query($sql);
        $ret = array();
		while ($row = $this->db->fetch_array($query)) 
		{
			$row['bundle_id'] = APP_UNIQUEID;
			$row['module_id'] = MOD_UNIQUEID;
			$row['struct_id'] = 'article';
			$row['struct_ast_id'] = 'material';
			$row['expand_id'] = $extend['expand_id'];
			$row['content_fromid'] = $row['id'];
			$pic = array(
				'host'=>$row['host'],
				'dir'=>$row['dir'],
				'filepath'=>$row['filepath'],
				'filename'=>$row['filename'],
			);
			$row['pic'] = $pic;
			$row['ip'] = hg_getip();
			$row['user_id'] = $this->user['user_id'];
			$row['user_name'] = $this->user['user_name'];
			unset($row['id']);
			$ret[] = $row;
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete_publish()
	{
		
	}
	
}

$out = new MaterialPublish();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>