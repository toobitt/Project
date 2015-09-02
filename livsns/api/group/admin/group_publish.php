<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: group_publish.php 12533 2012-10-15 05:34:39Z repheal $
***************************************************************************/
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
define('MOD_UNIQUEID','group');
class  group_publish  extends BaseFrm
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
	public function get_group_content()
	{
		$id = intval($this->input['from_id']);
		$sort_id = intval($this->input['sort_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		if($id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."group WHERE 1 AND group_id = {$id}";
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."group WHERE 1 AND group_type = {$sort_id}";
		}
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['content_fromid'] = $row['group_id'];
			$pic = array();
			$pic = unserialize($row['logo']);
			if(!empty($pic))
			{
				$row['indexpic'] = $pic;
			}
			else 
			{
				$row['indexpic'] = '';
			}
			$row['ip'] = hg_getip();
			$row['cuser_id'] = $row['user_id'];
			$row['cuser_name'] = $row['user_name'];
			$row['user_id'] = $this->user['user_id'];
			$row['user_name'] = $this->user['user_name'];
			$row['title'] = $row['name'];
			$row['brief'] = $row['description'];
			$row['outlink'] = MOD_UNIQUEID;
			unset($row['id']);
			$ret[] = $row;
		}
		$this->addItem($ret);
		$this->output();
	}
	
	
	//更新会员栏目id
	public function update_group_column_id()
	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."group WHERE group_id = " . $data['from_id'];
		$ret = $this->db->query_first($sql);
		if(intval($ret['state']) != 1)
		{
			$sql = "UPDATE " . DB_PREFIX ."group SET expand_id = 0 ,column_url = '' 
					WHERE group_id = " . $data['from_id'];
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
			$sql = "UPDATE " . DB_PREFIX . "group 
					SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' 
					WHERE group_id = " . $data['from_id'];
		}
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	

	//在发布系统里面创建表
	public function create_group_publish_table()
	{
		$table = new publishcontent();
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => MOD_UNIQUEID,
			'struct_id'     => "group",
			'struct_ast_id' => "",
			'field'         => "id,cuser_id,cuser_name,content_fromid,title,brief,outlink,lat,lng,b_lat,g_lng,create_time,update_time",
			'array_field'   => "",
			'table_title'   => "圈子",
			'field_sql'   	=> "  `id` int(10) NOT NULL AUTO_INCREMENT,
  								  `cuser_id` int(10) NOT NULL DEFAULT '0' COMMENT '空间创建人id',
  								  `cuser_name` varchar(60) NOT NULL DEFAULT '' COMMENT '创建人昵称',
		  						  `content_fromid` int(10) DEFAULT NULL,
		  						  `title` varchar(150) DEFAULT NULL COMMENT '圈子名',
		  						  `brief` varchar(500) DEFAULT NULL COMMENT '圈子简介',								  
								  `outlink` varchar(200) DEFAULT NULL COMMENT '外链',
  								  `lat` float(17,14) NOT NULL COMMENT '圈子所在的纬度',
  								  `lng` float(17,14) NOT NULL COMMENT '圈子所在的精度',
  								  `b_lat` float(17,14) NOT NULL COMMENT '百度地图的纬度',
  								  `g_lng` float(17,14) NOT NULL COMMENT '百度地图的精度',
   								  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  								  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
								  PRIMARY KEY (`id`)",
			'show_field' => array(
					0 => array('field'=>'title'      ,'title'=>'用户名'	  ,'type'=>'text'),
					1 => array('field'=>'brief'      ,'title'=>'表述'	  ,'type'=>'text'),
				),
			'child_table'=>'',
		);
		$ret = $table->create_table($data);
		$this->addItem($ret);
		$this->output();
	}
	

}

$out = new group_publish();
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