<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
require(ROOT_PATH . 'frm/publish_interface.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
define('MOD_UNIQUEID','member_publish');
class  member_publish  extends adminBase implements publish
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
	public function get_content()
	{
		$id = intval($this->input['from_id']);
		$sort_id = intval($this->input['sort_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		if($id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."member WHERE 1 AND id = {$id}";
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."member WHERE 1 AND group_id = {$sort_id}";
		}
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info))
		{
			$info = array();
			$info['content_fromid'] = $row['id'];
			if($row['host'])
			{
				$info['indexpic']['host'] =$row['host'];
				$info['indexpic']['dir'] =$row['dir'];
				$info['indexpic']['filepath'] =$row['filepath'];
				$info['indexpic']['filename'] =$row['filename'];
			}
			else
			{
				$info['indexpic'] = '';
			}
			$info['ip'] = hg_getip();
			$info['user_id'] = $this->user['user_id'];
			$info['user_name'] = $this->user['user_name'];
			$info['title'] = $row['member_name'];
			$info['brief'] = '';
			$info['outlink'] = MOD_UNIQUEID;
			//unset($row['id']);
			$ret[] = $info;
		}
		$this->addItem($ret);
		$this->output();
	}
	
	
	//更新会员栏目id
	public function update_content()
	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."member WHERE id = " . $data['from_id'];
		$ret = $this->db->query_first($sql);
		if(intval($ret['status']) != 1)
		{
			$sql = "UPDATE " . DB_PREFIX ."member SET expand_id = 0 ,column_url = '' 
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
			$sql = "UPDATE " . DB_PREFIX . "member 
					SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' 
					WHERE id = " . $data['from_id'];
		}
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	

	//在发布系统里面创建表
	public function create_table()
	{
		$table = new publishcontent();
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => MOD_UNIQUEID,
			'struct_id'     => "member",
			'struct_ast_id' => "",
			'field'         => "id,expand_id,content_fromid,title,brief,outlink",
			'array_field'   => "",
			'array_child_field'   => "",
			'table_title'   => "用户",
			'field_sql'   	=> "  `id` int(10) NOT NULL AUTO_INCREMENT,
		  						  `expand_id` int(10) DEFAULT NULL,
		  						  `content_fromid` int(10) DEFAULT NULL,
		  						  `title` varchar(150) DEFAULT NULL COMMENT '用户名',
		  						  `brief` varchar(500) DEFAULT NULL COMMENT '用户简介',								  
								  `outlink` varchar(200) DEFAULT NULL COMMENT '外链',
								  PRIMARY KEY (`id`)",
			'show_field' => array(
					0 => array('field'=>'title'      ,'title'=>'用户名'	  ,'type'=>'text'),
				),
			'child_table'=>'',
		);
		$ret = $table->create_table($data);
		$this->addItem($ret);
		$this->output();
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
			$sql = "SELECT column_id, column_url FROM " . DB_PREFIX ."member WHERE id = " . $data['from_id'];
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
			$sql = "UPDATE " . DB_PREFIX ."member SET column_id = '".addslashes(serialize($column_id))."', column_url = '".addslashes(serialize($column_url))."' WHERE id = " . $data['from_id'];
			$this->db->query($sql);						
		}	
		else		//全部删除
		{
			$sql = "UPDATE " . DB_PREFIX . "member SET expand_id = '' AND column_id = '' AND column_url = '' WHERE id = " . $data['from_id'];
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

        $sql = "UPDATE " . DB_PREFIX . "member SET";

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

$out = new member_publish();
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