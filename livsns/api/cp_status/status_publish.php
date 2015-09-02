<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: status_publish.php 12407 2012-10-11 07:55:41Z wangleyuan $
***************************************************************************/
define('ROOT_DIR', '../../');
require_once ROOT_DIR . 'global.php';
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
define('MOD_UNIQUEID','mblog_status');
class  status_publish  extends outerReadBase
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
		$this->errorOutput(NOMETHOD);
	}

	
	//获取会员信息
	public function get_status_content()
	{
		$id = intval($this->input['from_id']);
		$sort_id = intval($this->input['sort_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		if($id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."status WHERE 1 AND id = {$id}";
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."status WHERE 1 AND type = {$sort_id}";
		}
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$info = array();
			$info['content_fromid'] = $row['id'];
			$info['indexpic'] = '';
			$info['ip'] = hg_getip();
			$info['user_id'] = $this->user['user_id'];
			$info['user_name'] = $this->user['user_name'];
			$info['title'] = $row['text'];
			$info['brief'] = '';
			$info['outlink'] = MOD_UNIQUEID;
			$ret[] = $info;
		}
		$this->addItem($ret);
		$this->output();
	}
	
	
	//更新会员栏目id
	public function update_status_column_id()
	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."status WHERE id = " . $data['from_id'];
		$ret = $this->db->query_first($sql);
		if(intval($ret['state']) != 1)
		{
			$sql = "UPDATE " . DB_PREFIX ."status SET expand_id = 0 ,column_url = '' 
					WHERE id = " . $data['from_id'];
		}	
		else 
		{
			$column_id = unserialize($ret['column_id']);	   //发布栏目	
			$column_url = unserialize($ret['column_url']);	   //栏目url，发布对比，有删除栏目则删除对于栏目url
			$url = array();
			if(!empty($column_url) && is_array($column_url))
			{
				foreach($column_url as $k => $v)
				{
					if($column_id[$k])
					{
						$url[$k] = $v;
					}
				}
			}
			if(!empty($data['content_url']) && is_array($data['content_url']))
			{
				foreach($data['content_url'] as $k => $v)
				{
					$url[$k] = $v;
				}
			}
			$sql = "UPDATE " . DB_PREFIX . "status 
					SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' 
					WHERE id = " . $data['from_id'];
		}
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	

	//在发布系统里面创建表
	public function create_status_publish_table()
	{
		$table = new publishcontent();
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => MOD_UNIQUEID,
			'struct_id'     => "mblog_status",
			'struct_ast_id' => "",
			'field'         => "id,expand_id,content_fromid,title,brief,outlink",
			'array_field'   => "",
			'table_title'   => "帖子",
			'field_sql'   	=> "  `id` int(10) NOT NULL AUTO_INCREMENT,
		  						  `expand_id` int(10) DEFAULT NULL,
		  						  `content_fromid` int(10) DEFAULT NULL,
		  						  `title` varchar(150) DEFAULT NULL COMMENT '帖子名',
		  						  `brief` varchar(500) DEFAULT NULL COMMENT '帖子简介',								  
								  `outlink` varchar(200) DEFAULT NULL COMMENT '外链',
								  PRIMARY KEY (`id`)",
			'show_field' => array(
					0 => array('field'=>'title'      ,'title'=>'帖子名'	  ,'type'=>'text'),
				),
			'child_table'=>'',
		);
		$ret = $table->create_table($data);
		$this->addItem($ret);
		$this->output();
	}
	
	public function show()
	{
		
	}
	
	public function count()
	{
		
	}
	
	public function detail(){
		
	}

}

$out = new status_publish();
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