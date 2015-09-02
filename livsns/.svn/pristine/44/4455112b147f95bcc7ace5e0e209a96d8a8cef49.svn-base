<?php
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
define('MOD_UNIQUEID','article');
class  ArticlePublish  extends BaseFrm implements publish
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
			'struct_ast_id' => "article",
			'content_type' => "文章",
			'expand_id' => "",
			'content_fromid' => "",
			'array_field'=>"indexpic",
			'array_child_field'=>"pic",
			'field' => "id,title,subhead,brief,tcolor,isbold,isitalic,article_author,redactor,issue_id,group_id,
						keywords,state,order_id,create_time,update_time,ip,user_id,user_name,indexpic,water_id,
						water_name,article_size,outlink,weight,column_id,expand_id,content_fromid",
			'field_sql' => "`id` int(10) NOT NULL AUTO_INCREMENT,
						  `title` varchar(100) NOT NULL,
						  `subhead` varchar(200) NOT NULL COMMENT '副标题',
						  `brief` varchar(200) NOT NULL COMMENT '描述',
						  `tcolor` varchar(20) NOT NULL COMMENT '标题颜色',
						  `isbold` tinyint(1) NOT NULL COMMENT '加粗',
						  `isitalic` tinyint(1) NOT NULL COMMENT '斜体',
						  `article_author` varchar(20) NOT NULL COMMENT '作者',
						  `redactor` varchar(50) NOT NULL COMMENT '责任编辑',
						  `issue_id` int(10) NOT NULL COMMENT '期刊id',
						  `group_id` int(10) NOT NULL COMMENT '分类id',
						  `keywords` varchar(200) NOT NULL COMMENT '关键字',
						  `state` tinyint(1) NOT NULL COMMENT '0未审核1审核2打回',
						  `order_id` int(10) NOT NULL COMMENT '文章排序id',
						  `create_time` int(10) NOT NULL,
						  `update_time` int(10) NOT NULL,
						  `ip` varchar(30) NOT NULL,
						  `user_id` int(10) NOT NULL COMMENT '用户id',
						  `user_name` varchar(20) NOT NULL COMMENT '添加人姓名',
						  `indexpic` int(10) NOT NULL COMMENT '索引图id',
						  `water_id` int(10) NOT NULL COMMENT '水印id',
						  `water_name` varchar(50) NOT NULL COMMENT '水印名称',
						  `article_size` int(10) NOT NULL COMMENT '文章大小',
						  `outlink` varchar(50) NOT NULL COMMENT '外链',
						  `weight` int(4) NOT NULL COMMENT '权重',
						  `column_id` varchar(100) NOT NULL COMMENT '发布的栏目id',
						  `expand_id` int(10) NOT NULL COMMENT '在发布系统中的id',
						  `content_fromid` int(10) NOT NULL COMMENT 'content_fromid',
						  PRIMARY KEY (`id`)",
				'table_title' => "文章",
				'child_table' => "material",
				'show_field' => array(
						array('field'=>'title','title'=>'标题','type'=>'text'),
						array('field'=>'brief','title'=>'简介','type'=>'text'),
						array('field'=>'keywords','title'=>'关键词','type'=>'text'),
						array('field'=>'article_author','title'=>'作者','type'=>'text'),
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
			//查询文章状态
			$sql = "SELECT * FROM ".DB_PREFIX."article WHERE id = ".$data['from_id'];
			$ret = $this->db->query_first($sql);
			if ($ret['state'] !=1)
			{
				$sql = "UPDATE ".DB_PREFIX."article SET expand_id = 0 ,column_url = '' WHERE id = " . $data['from_id'];
				
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
				$sql = "UPDATE " . DB_PREFIX . "article SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' where id = " . $data['from_id'];
			}
			$this->db->query($sql);
		
			if(empty($data['expand_id']))
			{
				$sql = "update " . DB_PREFIX. "article set expand_id = " . $data['expand_id'] . " where id =" . $data['from_id'];
				$this->db->query($sql);
				
				//查询文章下素材id
				$sql = "SELECT id FROM ".DB_PREFIX."material WHERE cid ＝ ".$data['from_id'];
				$q = $this->db->query($sql); 
				while ($r = $this->db->fetch_array($q))
				{
					$mat_ids = $r['id'];
				}
				$ids = implode(',', $mat_ids);
				//更新期刊下文章
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
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		
		$sql = "SELECT  expand_id  FROM  " . DB_PREFIX . "issue WHERE 1 AND id=".$id;
		$expand = $this->db->query_first($sql);
		if($this->input['is_update'])
		{
			$sql = 'SELECT i.id,a.id as aid,a.*,c.* FROM '.DB_PREFIX.'issue i 
				LEFT JOIN '.DB_PREFIX.'article a ON i.id = a.issue_id
				LEFT JOIN '.DB_PREFIX.'content c ON a.id = c.article_id
				LEFT JOIN '.DB_PREFIX.'material m ON a.indexpic = m.id
				WHERE 1 AND a.state =1  AND a.id = '.$id.$data_limit;
		}
		else 
		{
			$sql = 'SELECT i.id,a.id as aid,a.*,c.* FROM '.DB_PREFIX.'issue i 
				LEFT JOIN '.DB_PREFIX.'article a ON i.id = a.issue_id
				LEFT JOIN '.DB_PREFIX.'content c ON a.id = c.article_id
				LEFT JOIN '.DB_PREFIX.'material m ON a.indexpic = m.id
				WHERE 1 AND a.state =1  AND a.expand_id = "" AND i.id = '.$id.$data_limit;
		}
		
		$query = $this->db->query($sql);
		$ret = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['bundle_id'] = APP_UNIQUEID;
			$row['module_id'] = MOD_UNIQUEID;
			$row['struct_id'] = 'issue';
			$row['struct_ast_id'] = 'article';
			$row['expand_id'] = $expand['expand_id'];
			$row['content_fromid'] = $row['aid'];
			$pic = array(
				'host'=>$row['host'],
				'dir'=>$row['dir'],
				'filepath'=>$row['filepath'],
				'filename'=>$row['filename'],
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

$out = new ArticlePublish();
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