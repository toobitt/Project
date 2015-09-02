<?php
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
define('MOD_UNIQUEID','magazine');
class  MagezinePublish  extends BaseFrm implements publish
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
			'struct_ast_id' => "",
			'content_type' => "杂志",
			'expand_id' => "",
			'content_fromid' => "",
			'array_field'=>"indexpic",
			'array_child_field'=>"indexpic",
			'field' => "id,title,issue_id,sort_id,brief,state,user_name,create_time,update_time,contract_way,release_cycle,volume,current_nper,mana_nper,
						cssn,issn,price,page_num,sponsor,editor,language,appid,appname,order_id,column_id,expand_id,is_publish_child,indexpic,content_fromid,user_id",
			'field_sql' => 	" `id` int(10) NOT NULL AUTO_INCREMENT,
							  `title` char(20) NOT NULL COMMENT '杂志名称',
							  `issue_id` int(10) NOT NULL COMMENT '当期刊的id',
							  `sort_id` int(4) NOT NULL COMMENT '杂志分类',
							  `brief` varchar(200) NOT NULL COMMENT '杂志简介',
							  `state` tinyint(4) NOT NULL COMMENT '0未审核1审核2打回',
							  `user_id` int(10) NOT NULL COMMENT '用户id',
							  `user_name` varchar(20) NOT NULL COMMENT '添加者',
							  `create_time` int(10) NOT NULL COMMENT '创建时间',
							  `update_time` int(10) NOT NULL COMMENT '更新时间',
							  `contract_way` text NOT NULL COMMENT '联系方式',
							  `release_cycle` int(4) NOT NULL COMMENT '发行周期',
							  `volume` int(4) NOT NULL COMMENT '累计期数',
							  `current_nper` int(4) NOT NULL COMMENT '当前其数',
							  `mana_nper` int(4) NOT NULL COMMENT '管理期数',
							  `cssn` varchar(20) NOT NULL COMMENT '国内统一刊号',
							  `issn` varchar(20) NOT NULL COMMENT '国际统一刊号',
							  `price` float(5,2) unsigned NOT NULL COMMENT '定价',
							  `page_num` int(4) NOT NULL COMMENT '页数',
							  `sponsor` varchar(50) NOT NULL COMMENT '主办单位',
							  `editor` varchar(60) NOT NULL COMMENT '责任主编',
							  `language` varchar(20) NOT NULL COMMENT '语言',
							  `appid` int(10) NOT NULL,
							  `appname` varchar(50) NOT NULL,
							  `order_id` int(10) NOT NULL COMMENT '排序',
							  `column_id` varchar(100) NOT NULL COMMENT '发布的栏目id',
							  `expand_id` int(10) NOT NULL COMMENT '在发布系统中的id',
							  `is_publish_child` tinyint(1) NOT NULL COMMENT '是否发布子内容',
							  `indexpic` varchar(500) NOT NULL COMMENT '图片串行化', 
							  `content_fromid` int(10) NOT NULL COMMENT 'content_fromid',
							  PRIMARY KEY (`id`)",
			'table_title' => "杂志",
			'child_table' => "issue",
			'show_field' => array(
					array('field'=>'title','title'=>'杂志名称','type'=>'text'),
					array('field'=>'brief','title'=>'简介','type'=>'text'),
					array('field'=>'cssn','title'=>'国内统一刊号','type'=>'text'),
					array('field'=>'price','title'=>'价格','type'=>'text'),
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
		$sql = "SELECT * FROM ".DB_PREFIX."magazine WHERE id = ".$data['from_id'];
		$ret = $this->db->query_first($sql);

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
		$sql = "UPDATE " . DB_PREFIX . "magazine SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' where id = " . $data['from_id'];
		$this->db->query($sql);
		
		/*if(empty($data['expand_id']))
		{
			$sql = "update " . DB_PREFIX. "issue set expand_id = " . $data['expand_id'] . " where magazine_id =" . $data['from_id'];
			$this->db->query($sql);
		}*/
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
		if (empty($sort_id))
		{
			$sql = 'SELECT m.id as mid,m.*,i.* FROM '.DB_PREFIX.'magazine m  
					LEFT JOIN '.DB_PREFIX.'issue i ON m.issue_id = i.id 
					WHERE 1 AND m.id='.$id.$data_limit;
		}else {
			$sql = 'SELECT m.*,i.* FROM '.DB_PREFIX.'magazine m  
					LEFT JOIN '.DB_PREFIX.'issue i 
					ON m.issue_id = i.id WHERE 1 AND m.sort_id='.$sort_id.$data_limit;
		}
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info)) 
		{
			$row['bundle_id'] = APP_UNIQUEID;
			$row['module_id'] = MOD_UNIQUEID;
			$row['struct_id'] = 'magazine';
			$row['struct_ast_id'] = '';
			$row['expand_id'] = '';
			$row['content_fromid'] = $row['mid'];
			$row['title'] = $row['name'];
			$pic = array(
				'host'=>$row['host'],
				'dir'=>$row['dir'],
				'filepath'=>$row['file_path'],
				'filename'=>$row['file_name'],
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
				'bundle_id' => 'magazine',
				'module_id' => 'magazine',
				'struct_id' => 'magazine',
				'struct_ast_id' => "",
				'name'      => '杂志',
				'host'		=> 'localhost',
				'path'		=> 'livsns/api/magazine/admin/',
				'filename'  => 'magazine_publish.php',
				'action_get_content' => 'get_magazine_content',
				'action_insert_contentid' => 'update_magazine_column_id',
				'fid'		=> 0,
			),
			2 => array(
				'bundle_id' => 'magazine',
				'module_id' => 'magazine',
				'struct_id' => 'magazine',
				'struct_ast_id' => "issue",
				'name'      => '期刊',
				'host'		=> 'localhost',
				'path'		=> 'livsns/api/magazine/admin/',
				'filename'  => 'magazine_publish.php',
				'action_get_content' => 'get_issue_content',
				'action_insert_contentid' => 'update_issue_column_id',
				'fid'		=> 1,
			),
			3 => array(
				'bundle_id' => 'magazine',
				'module_id' => 'magazine',
				'struct_id' => 'magazine',
				'struct_ast_id' => "article",
				'name'      => '文章',
				'host'		=> 'localhost',
				'path'		=> 'livsns/api/magazine/admin/',
				'filename'  => 'magazine_publish.php',
				'action_get_content' => 'get_article_content',
				'action_insert_contentid' => 'update_article_column_id',
				'fid'		=> 2,
			),
			4 => array(
				'bundle_id' => 'magazine',
				'module_id' => 'magazine',
				'struct_id' => 'magazine',
				'struct_ast_id' => "material",
				'name'      => '图片',
				'host'		=> 'localhost',
				'path'		=> 'livsns/api/magazine/admin/',
				'filename'  => 'magazine_publish.php',
				'action_get_content' => 'get_article_material',
				'action_insert_contentid' => 'update_material_column_id',
				'fid'		=> 3,
			),
		);
		
		require_once ROOT_PATH . 'lib/class/publishplan.class.php';
		$plan = new publishplan();
		$ret  = $plan->insert_plan_set($data);
		//返回配置ID,某种方式更改
		$sql = "insert into " . DB_PREFIX ."settings (type , var_name, value, description, is_edit, is_open) values(2,'MAGAZINE_PLAN_SET_ID',$ret[1],'',1,1)";
		$this->db->query($sql);
		
		$sql = "insert into " . DB_PREFIX ."settings (type , var_name, value, description, is_edit, is_open) values(2,'ISSUE_PLAN_SET_ID',$ret[2],'',1,1)";
		$this->db->query($sql);
		
		$sql = "insert into " . DB_PREFIX ."settings (type , var_name, value, description, is_edit, is_open) values(2,'ARTICLE_PLAN_SET_ID',$ret[3],'',1,1)";
		$this->db->query($sql);
		
		$sql = "insert into " . DB_PREFIX ."settings (type , var_name, value, description, is_edit, is_open) values(2,'MATERIAL_PLAN_SET_ID',$ret[4],'',1,1)";
		$this->db->query($sql);
		
		
		$this->addItem($ret);
		$this->output();
	}
	
	
	/**
	*  发布系统，将杂志内容插入发布队列
	*  
	*  @name 		publist_insert_magazine()
	*  @access 		public
	*  @author 		hanwenbin
	*  @category	hogesoft
	*  @copyright	hogesoft
	*  @param		int $id   杂志id
	*  @param		int $op 		  操作类型  insert update delete
	*/	
	public function publish_insert_magazine()
	{
		$id = intval($this->input['id']);//杂志id
		$op = urldecode($this->input['op']);//操作
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		$sql = "select * from " . DB_PREFIX ."magazine where id = " . $id;
		$info = $this->db->query_first($sql);
 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=>	MAGAZINE_PLAN_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $info['column_id'],
			'title'     =>  $info['name'],
			'action_type' => $op,
			'publish_time'  => $info['pub_time'],
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
			'is_publish_child' => 1,
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	
	/**
	*  发布系统，将期刊内容插入发布队列
	*  
	*  @name 		publist_insert_issue()
	*  @access 		public
	*  @author 		hanwenbin
	*  @category	hogesoft
	*  @copyright	hogesoft
	*  @param		int $id   期刊id
	*  @param		int $op 		  操作类型  insert update delete
	*/	
	public function publish_insert_issue()
	{
		$id = intval($this->input['id']);//期刊id
		$op = urldecode($this->input['op']);//操作
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		
		$sql = "select * from " . DB_PREFIX ."issue where id = " . $id;
		$info = $this->db->query_first($sql);
		
		$maga_id = $info['magazine_id'];
		$sql = "select expand_id from " . DB_PREFIX ."magazine where id = " . $maga_id;
		$res = $this->db->query_first($sql);
		//期刊所属杂志如果没有发布，期刊不能发布
		if(!$res['expand_id'])
		{
			return false;
		}
 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=>	ISSUE_PLAN_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $info['column_id'],
			'title'     =>  $info['issue'],
			'action_type' => $op,
			'publish_time'  => $info['pub_date'],
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
			'is_publish_child' => 1,
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	/**
	*  发布系统，将文章内容插入发布队列
	*  
	*  @name 		publist_insert_article()
	*  @access 		public
	*  @author 		hanwenbin
	*  @category	hogesoft
	*  @copyright	hogesoft
	*  @param		int $id   文章id
	*  @param		int $op 		  操作类型  insert update delete
	*/	
	public function publish_insert_article()
	{
		$id = intval($this->input['id']);//文章id
		$op = urldecode($this->input['op']);//操作
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		
		$sql = "select * from " . DB_PREFIX ."article where id = " . $id;
		$info = $this->db->query_first($sql);
		
		$issue_id = $info['issue_id'];
		$sql = "select expand_id from " . DB_PREFIX ."issue where id = " . $issue_id;
		$res = $this->db->query_first($sql);
		//期刊所属杂志如果没有发布，期刊不能发布
		if(!$res['expand_id'])
		{
			return false;
		}
 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=>	ARTICLE_PLAN_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $info['column_id'],
			'title'     =>  $info['title'],
			'action_type' => $op,
			'publish_time'  => $info['pub_date'],
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
			'is_publish_child' => 1,
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	
	public function delete_publish()
	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		if($data['is_delete_column'])   //只删除某一栏目中内容
		{
			$sql = "SELECT column_id,column_url FROM " . DB_PREFIX ."magazine WHERE id = " . $data['from_id'];
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
			$sql = "UPDATE " . DB_PREFIX ."magazine SET column_id = '".addslashes(serialize($column_id))."',column_url = '".addslashes(serialize($column_url))."' WHERE id = " . $data['from_id'];
			$this->db->query($sql);						
		}	
		else		//全部删除
		{
			$sql = "UPDATE " . DB_PREFIX . "magazine SET expand_id = '' AND column_id = '' AND column_url = '' WHERE id = " . $data['from_id'];
			$this->db->query($sql);	
			$sql = "UPDATE " . DB_PREFIX . "materials SET expand_id = '' WHERE content_id = " . $data['from_id'];
			$this->db->query($sql);
		}
		$this->addItem('true');
		$this->output(); 
		
	}
	
	function up_content()
    {
        $content_fromid = intval($this->input['data']['content_fromid']);
        if(!$content_fromid)
        {
            $this->errorOutput('NO_ID');
        }
        unset($this->input['data']['content_fromid']);

        $sql = "UPDATE " . DB_PREFIX . "magazine SET";

        $sql_extra = $space     = ' ';
        foreach ($this->input['data'] as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE id=".$content_fromid ;
        $this->db->query($sql);
    }
	
	
}

$out = new MagezinePublish();
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