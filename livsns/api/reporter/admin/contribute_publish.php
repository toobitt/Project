<?php
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
define('MOD_UNIQUEID','reporter');
require(ROOT_PATH . 'frm/publish_interface.php');
class  contributePublish  extends appCommonFrm implements publish
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
			'struct_id' => "content",
			'struct_ast_id' => "",
			'content_type' => "爆料",
			'expand_id' => "",
			'content_fromid' => "",
			'field' => "id,expand_id,content_fromid, title,brief,sort_id,keywords,material_id,appid,client,longitude,
						latitude,create_time,update_time,user_id,user_name,audit,is_pub,order_id,expand_id,column_id,
						column_url,publish_time,content,indexpic",
			'array_field'=>"indexpic",	
			'array_child_field'=>"pic,video",	
			'field_sql' =>   "`id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
							  `sort_id` int(10) NOT NULL COMMENT '分类',
							  `title` varchar(100) NOT NULL COMMENT '标题',
							  `keywords` varchar(30) DEFAULT NULL COMMENT '关键字',
							  `brief` varchar(1000) NOT NULL COMMENT '简要',
							  `material_id` int(10) NOT NULL COMMENT '素材库的ID',
							  `appid` int(10) NOT NULL COMMENT 'appid',
							  `client` char(20) NOT NULL COMMENT '客户端',
							  `longitude` float(17,14) NOT NULL COMMENT '所在经度',
							  `latitude` float(17,14) NOT NULL COMMENT '所在的纬度',
							  `create_time` int(10) NOT NULL COMMENT '发布时间',
							  `update_time` int(10) NOT NULL COMMENT '更新时间',
							  `user_id` int(10) NOT NULL COMMENT '用户id',
							  `user_name` char(30) NOT NULL COMMENT '用户名',
							  `audit` tinyint(1) NOT NULL COMMENT '审核',
							  `is_pub` tinyint(1) NOT NULL COMMENT '发布状态位',
							  `order_id` int(10) NOT NULL COMMENT '排序ID',
							  `expand_id` int(10) NOT NULL COMMENT '在发布系统里面的id',
							  `column_id` varchar(500) NOT NULL COMMENT '发布到的栏目id',
							  `column_url` varchar(1000) NOT NULL COMMENT '栏目url',
							  `publish_time` int(10) NOT NULL COMMENT '发布时间',
							  `content_fromid` int(10) NOT NULL COMMENT 'content_fromid',
							  `content` text NOT NULL COMMENT '爆料内容',
							  `indexpic` varchar(500) NOT NULL COMMENT '图片串行话', 
							  PRIMARY KEY (`id`)",
			'table_title' => "爆料",
			'child_table' => "materials",
			'show_field' => array(
					array('field'=>'title','title'=>'爆料标题','type'=>'text'),
					array('field'=>'brief','title'=>'简介','type'=>'text'),
					array('field'=>'content','title'=>'爆料内容','type'=>'text'),				
			),			
		);
		$ret = $obj->create_table($data);
		$this->addItem($ret);
		$this->output();
	}
	function get_content()
	{
		$id = intval($this->input['from_id']);
		$sort_id = intval($this->input['sort_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		if(empty($sort_id))
		{
			$sql = "SELECT c.*,cb.*,m.*  FROM " . DB_PREFIX . "content c LEFT JOIN " . DB_PREFIX . "contentbody cb ON c.id = cb.id LEFT JOIN ".DB_PREFIX."materials m on c.material_id = m.materialid WHERE 1 and c.id=".$id . $data_limit;
		}
		else 
		{
			$sql = "SELECT c.*,cb.*,m.*  FROM " . DB_PREFIX . "content c LEFT JOIN " . DB_PREFIX . "contentbody cb ON c.id = cb.id LEFT JOIN ".DB_PREFIX."materials m on c.material_id = m.materialid WHERE 1 and c.sort_id=".$sort_id . $data_limit;
		}
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info))
		{
			$row['bundle_id'] = APP_UNIQUEID;
			$row['module_id'] = MOD_UNIQUEID;
			$row['struct_id'] = 'contribute';
			$row['struct_ast_id'] = '';
			$row['expand_id'] = '';
			$row['content_fromid'] = $row['id'];
			$pic = array();
			if(!empty($row['material_id']) && !empty($row['materialid']))
			{
				$pic = array(
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'filepath'=>$row['material_path'],
					'filename'=>$row['pic_name'],
				);
			}		
			$row['indexpic'] = $pic;
			$video = array();
			if(!empty($row['material_id']) && !empty($row['vodid']))
			{
				$video = array(
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'id'=>$row['vodid']
				);
			}
			$row['video'] = $video;
			$row['content'] = $row['text'];
			$row['ip'] = hg_getip();
			$row['user_id'] = $this->user['user_id'];
			$row['user_name'] = $this->user['user_name'];
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
		//查询爆料状态，
		$sql = "SELECT * FROM ".DB_PREFIX."content WHERE id = ".$data['from_id'];
		$ret = $this->db->query_first($sql);
		if ($ret['audit'] !=2)
		{
			$sql = "UPDATE ".DB_PREFIX."content SET expand_id = 0 ,column_url = '' WHERE id = " . $data['from_id'];
			
		}else {
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
			$sql = "UPDATE " . DB_PREFIX . "content SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' where id = " . $data['from_id'];
		}
		$this->db->query($sql);
		
		if(empty($data['expand_id']))
		{
			$sql = "UPDATE " . DB_PREFIX. "materials SET expand_id = " . $data['expand_id'] . " WHERE content_id =" . $data['from_id'];
			$this->db->query($sql);
		}
		$this->addItem('true');
		$this->output();
 	}
 	/**
 	 * 删除这条内容的发布
 	 *
 	 */
 	function delete_publish()
 	{
 		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		if($data['is_delete_column'])   //只删除某一栏目中内容
		{
			$sql = "SELECT column_id,column_url FROM " . DB_PREFIX ."content WHERE id = " . $data['from_id'];
			$ret = $this->db->query_first($sql);
			$column_id = unserialize($ret['column_id']);
			$column_url = unserialize($ret['column_url']);
			$del_columnid = explode(',',$data['column_id']);
			if(is_array($del_columnid))
			{
				foreach($del_columnid as $k => $v)
				{
					unset($column_id[$v],$column_url[$v]);	
				}
			}
			$sql = "UPDATE " . DB_PREFIX ."content SET column_id = '".addslashes(serialize($column_id))."',column_url = '".addslashes(serialize($column_url))."' WHERE id = " . $data['from_id'];
			$this->db->query($sql);						
		}	
		else		//全部删除
		{
			$sql = "UPDATE " . DB_PREFIX . "content SET expand_id = '' AND column_id = '' AND column_url = '' WHERE id = " . $data['from_id'];
			$this->db->query($sql);	
			$sql = "UPDATE " . DB_PREFIX . "materials SET expand_id = '' WHERE content_id = " . $data['from_id'];
			$this->db->query($sql);
		}
		$this->addItem('true');
		$this->output(); 		
 	}
 	
	/**
	 * 
	 * 插入发布计划配置
	 * 
	 * @name		insert_plan_set
	 * @access		public 
	 * @author		hanwenbin
	 * @category	hogesoft
	 * @copyright	hogesoft
	 * 
	 */
	public function  insert_plan_set()
	{
		$data = array(
			1 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => MOD_UNIQUEID,
				'struct_id' => "content",
				'struct_ast_id' => "",
				'name'      => '爆料',
				'host'		=> 'localhost',
				'path'		=> 'livsns/api/contribute/admin/',
				'filename'  => 'contribute_publish.php',
				'action_get_content' => 'get_contribute_content',
				'action_insert_contentid' => 'update_contribute_column_id',
				'fid'		=> 0,
			),
			2 => array(
				'bundle_id' => APP_UNIQUEID,
				'module_id' => MOD_UNIQUEID,
				'struct_id' => "content",
				'struct_ast_id' => "materials",
				'name'      => '素材',
				'host'		=> 'localhost',
				'path'		=> 'livsns/api/contribute/admin/',
				'filename'  => 'contribute_publish.php',
				'action_get_content' => 'get_materials_content',
				'action_insert_contentid' => 'update_materials_column_id',
				'fid'		=> 1,
			),
		);
		
		require_once ROOT_PATH . 'lib/class/publishplan.class.php';
		$plan = new publishplan();
		$ret  = $plan->insert_plan_set($data);
		//返回配置ID,某种方式更改
		
		$sql = "insert into " . DB_PREFIX ."settings (type,var_name,value,description,is_edit,is_open) values(2,'CONTRIBUTE_PLAN_SET_ID',$ret[1],'',1,1)";
		$this->db->query($sql);
		
		$sql = "insert into " . DB_PREFIX ."settings (type,var_name,value,description,is_edit,is_open) values(2,'MATERIALS_PLAN_SET_ID',$ret[2],'',1,1)";
		$this->db->query($sql);
		$this->addItem($ret);
		$this->output();
		
	}

	public function publish_insert_contribute()
	{
		$id = intval($this->input['id']);//爆料id
		$op = urldecode($this->input['op']);//操作
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		$sql = "select * from " . DB_PREFIX ."content where id = " . $id;
		$info = $this->db->query_first($sql);
 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=>	CONTRIBUTE_PLAN_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $info['column_id'],
			'title'     =>  $info['title'],
			'action_type' => $op,
			'publish_time'  => TIMENOW,
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}
}

$out = new contributePublish();
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