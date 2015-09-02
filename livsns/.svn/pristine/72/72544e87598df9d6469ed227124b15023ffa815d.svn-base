<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
define('MOD_UNIQUEID','activity');
class  activity_publish  extends adminBase
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
	public function get_activity_content()
	{
		$id = intval($this->input['from_id']);
		$sort_id = intval($this->input['sort_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		if($id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."activity WHERE 1 AND id = {$id}";
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."activity WHERE 1 AND type_id = {$sort_id}";
		}
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info))
		{
			$row['content_fromid'] = $row['id'];
			$pic = array();
			$pic = unserialize($row['action_img']);
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
			$row['title'] = $row['action_name'];
			$row['brief'] = $row['introduce'];
			$row['outlink'] = MOD_UNIQUEID;
			unset($row['id']);
			$ret[] = $row;
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	
	//更新会员栏目id
	public function update_activity_column_id()
	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."activity WHERE id = " . $data['from_id'];
		$ret = $this->db->query_first($sql);
		if(intval($ret['state']) != 1)
		{
			$sql = "UPDATE " . DB_PREFIX ."activity SET expand_id = 0 ,column_url = '' 
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
			$sql = "UPDATE " . DB_PREFIX . "activity 
					SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' 
					WHERE id = " . $data['from_id'];
		}
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	

	//在发布系统里面创建表
	public function create_activity_publish_table()
	{
		$table = new publishcontent();
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => MOD_UNIQUEID,
			'struct_id'     => "activity",
			'struct_ast_id' => "",
			'field'         => "id,content_fromid,cuser_id,cuser_name,action_sort,type_id,start_time,end_time,place,need_pay,need_num,introduce,slogan,yet_join,apply_num,collect_num,concern,contact,rights,link,swfurl,media_id,edit_count,create_time,is_open,bus,connection_user,connection_group,state,lat,lng",
			'array_field'   => "",
			'table_title'   => "活动",
			'field_sql'   	=> "  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '发起活动的主键',
								  `content_fromid` int(10) DEFAULT NULL,
								  `cuser_id` int(10) NOT NULL COMMENT '用户关联标识',
								  `cuser_name` varchar(100) NOT NULL COMMENT '发起活动的名字',
								  `action_name` varchar(100) NOT NULL COMMENT '活动的主题',
								  `action_sort` tinyint(1) DEFAULT '0' COMMENT '活动种类（0：线上，1：线下）',
								  `type_id` int(10) DEFAULT NULL COMMENT '活动类型',
								  `start_time` int(10) NOT NULL COMMENT '活动的开始时间',
								  `end_time` int(10) NOT NULL COMMENT '活动的截止时间',
								  `place` varchar(200) NOT NULL COMMENT '活动的具体地点',
								  `need_pay` int(5) NOT NULL COMMENT '判断是否需要花销0：不要 有内容：要',
								  `need_num` int(5) NOT NULL COMMENT '活动人数的上限',
								  `introduce` mediumtext NOT NULL COMMENT '对活动的描述',
								  `slogan` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '宣言',
								  `yet_join` int(5) NOT NULL DEFAULT '0' COMMENT '已经通过审核的人数',
								  `apply_num` int(5) NOT NULL DEFAULT '0' COMMENT '已经申请的人数',
								  `collect_num` int(11) DEFAULT '0' COMMENT '收藏次数',
								  `concern` tinyint(1) DEFAULT '0' COMMENT '对于是否关注该地盘（活动发起者） 0：不要，1：要',
								  `contact` tinyint(1) DEFAULT '0' COMMENT '对联系方式的要求 0：不要，1：要',
								  `rights` tinyint(1) DEFAULT '0' COMMENT '对权限审核的要求 0：不要，1：要',
								  `link` varchar(250) NOT NULL COMMENT '活动视频链接',
								  `swfurl` varchar(255) NOT NULL COMMENT '视频地址',
								  `media_id` int(11) NOT NULL COMMENT '关联微博的媒体ID，用于发布微博时封面为分享图片',
								  `edit_count` tinyint(1) DEFAULT '0' COMMENT '编辑次数',
								  `create_time` int(10) NOT NULL COMMENT '活动的创建时间',
								  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启圈子',
								  `bus` text NOT NULL COMMENT '路线',
								  `connection_user` char(100) NOT NULL DEFAULT '' COMMENT '相关管理人员id用'',''隔开',
								  `connection_group` char(100) NOT NULL COMMENT '相关管理圈子id用'',''隔开',
								  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未审核1已审核2关闭',
								  `lat` float(17,14) NOT NULL DEFAULT '32.03966522216797',
								  `lng` float(17,14) NOT NULL DEFAULT '118.80860137939453',
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

$out = new activity_publish();
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