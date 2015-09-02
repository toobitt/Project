<?php
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
define('MOD_UNIQUEID','issue');
class  IssuePublish  extends BaseFrm implements publish
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
			'struct_ast_id' => "issue",
			'content_type' => "期刊",
			'expand_id' => "",
			'content_fromid' => "",
			'array_field'=>"indexpic",
			'array_child_field'=>"indexpic",
			'field' => "id,title,magazine_id,pub_date,state,top,recommend,ip,user_id,user_name,create_time,update_time,host,dir,file_name,file_path,file_type,
			file_size,original_id,issue_size,appid,appname,outlink,weight,cover_article,column_id,expand_id,indexpic,content_fromid,brief",
			'field_sql' => 	"`id` int(10) NOT NULL AUTO_INCREMENT,
							  `title` varchar(100) NOT NULL COMMENT '刊号',
							  `magazine_id` int(10) NOT NULL COMMENT '关联杂志表',
							  `pub_date` int(10) NOT NULL COMMENT '发行时间',
							  `state` tinyint(4) NOT NULL COMMENT '状态',
							  `top` tinyint(2) NOT NULL COMMENT '置顶',
							  `recommend` tinyint(2) NOT NULL COMMENT '推荐',
							  `ip` varchar(25) NOT NULL,
							  `user_id` int(10) NOT NULL COMMENT '添加人id',
							  `user_name` varchar(20) NOT NULL COMMENT '添加人姓名',
							  `create_time` int(10) NOT NULL COMMENT '创建时间',
							  `update_time` int(10) NOT NULL,
							  `host` varchar(100) NOT NULL,
							  `dir` varchar(100) NOT NULL,
							  `file_name` varchar(120) NOT NULL,
							  `file_path` varchar(200) NOT NULL,
							  `file_type` varchar(10) NOT NULL,
							  `file_size` int(10) NOT NULL,
							  `original_id` int(10) NOT NULL COMMENT '原始素材ID',
							  `issue_size` int(10) NOT NULL COMMENT '杂志大小',
							  `appid` int(10) NOT NULL,
							  `appname` varchar(50) NOT NULL,
							  `outlink` varchar(50) NOT NULL COMMENT '外链',
							  `weight` int(4) NOT NULL COMMENT '权重',
							  `cover_article` text NOT NULL COMMENT '封面文章信息',
							  `column_id` varchar(100) NOT NULL COMMENT '发布的栏目',
							  `expand_id` int(10) NOT NULL COMMENT '发布系统',
							  `indexpic` varchar(500) NOT NULL COMMENT '图片串行话', 
							  `content_fromid` int(10) NOT NULL COMMENT 'content_fromid',
							  `brief` varchar(200) NOT NULL COMMENT '期刊简介',
							  PRIMARY KEY (`id`),
							  UNIQUE KEY `id` (`id`)",
			'table_title' => "期刊",
			'child_table' => "article",
			'show_field' => array(
					array('field'=>'title','title'=>'刊号','type'=>'text'),
					array('field'=>'issue_size','title'=>'杂志大小','type'=>'text'),
					array('field'=>'pub_date','title'=>'发行时间','type'=>'text'),
					array('field'=>'indexpic','title'=>'图片','type'=>'img'),
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
			return false;
		}
		
		//查询期刊状态
		$sql = "SELECT * FROM ".DB_PREFIX."issue WHERE id = ".$data['from_id'];
		$ret = $this->db->query_first($sql);
		if ($ret['state'] !=1)
		{
			$sql = "UPDATE ".DB_PREFIX."issue SET expand_id = 0 ,column_url = '' WHERE id = " . $data['from_id'];
			
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
			$sql = "UPDATE " . DB_PREFIX . "issue SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' where id = " . $data['from_id'];
		}
		$this->db->query($sql);
	
		//打回期刊，更新期刊下所有子内容
		if(empty($data['expand_id']))
		{
			//更新期刊
			$sql = "update " . DB_PREFIX. "issue set expand_id = " . $data['expand_id'] . " where id =" . $data['from_id'];
			$this->db->query($sql);
			
			//查询期刊下文章id
			$sql = "SELECT id FROM ".DB_PREFIX."article WHERE issue_id = ".$data['from_id'];
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$art_ids[] = $r['id'];
			}
			$ids = implode(',', $art_ids);
			//更新期刊下文章
			$sql = "UPDATE ".DB_PREFIX."article SET expand_id = ".$data['expand_id']." WHERE issue_id = ".$data['from_id'];
			$this->db->query($sql);
			
			
			//查询文章下素材id
			$sql = "SELECT id FROM ".DB_PREFIX."material WHERE cid IN (".$ids.")";
			$q = $this->db->query($sql); 
			while ($r = $this->db->fetch_array($q))
			{
				$mat_ids = $r['id'];
			}
			$ids = implode(',', $mat_ids);
			//更新文章下素材
			$sql = "UPDATE ".DB_PREFIX."material SET expand_id = ".$data['expand_id']." WHERE id IN (".$ids.")";
			$this->db->query($sql);
		}
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
		$sort_id = intval($this->input['sort_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		
		$sql = "select expand_id from " . DB_PREFIX . "magazine where 1 and id=".$id;
		$expand = $this->db->query_first($sql);
		
		if(empty($sort_id))
		{
			if($this->input['is_update'])
			{
				$sql = 'SELECT m.id,i.id as issueId,i.* FROM '.DB_PREFIX.'magazine m
					LEFT JOIN '.DB_PREFIX.'issue i ON m.id = i.magazine_id
					WHERE 1 AND i.state = 1 AND i.id = '.$id.$data_limit;
			}
			else 
			{
				$sql = 'SELECT m.id,i.id as issueId,i.* FROM '.DB_PREFIX.'magazine m
					LEFT JOIN '.DB_PREFIX.'issue i ON m.id = i.magazine_id
					WHERE 1 AND i.state = 1 AND i.expand_id = "" AND m.id = '.$id.$data_limit;
			}
		}
		else 
		{
			$sql = 'SELECT m.id ,i.* FROM '.DB_PREFIX.'magazine m 
					LEFT JOIN '.DB_PREFIX.'issue i ON m.id = i.magazine_id
					WHERE 1 AND i.state =1 AND i.sort_id = '.$sort_id.$data_limit;
		}
		//file_put_contents('1.txt', $sql);
		$query = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['bundle_id'] = APP_UNIQUEID;
			$row['module_id'] = MOD_UNIQUEID;
			$row['struct_id'] = 'magazine';
			$row['struct_ast_id'] = 'issue';
			$row['expand_id'] = $expand['expand_id'];
			$row['content_fromid'] = $row['issueId'];
			$row['title'] =date('Y',$row['pub_date']).'第'.$row['issue'].'期' ;
			$pic = array(
				'host'=>$row['host'],
				'dir'=>$row['dir'],
				'filepath'=>$row['file_path'],
				'filename'=>$row['file_naem'],
			);			
			$row['indexpic'] = $pic;
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

$out = new IssuePublish();
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