<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel_publish.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
require_once('global.php');
require(ROOT_PATH . 'frm/publish_interface.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
define('MOD_UNIQUEID','channel_publish');
class  channel_publish  extends adminBase implements publish
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
	
	/**
	 * 获取直播频道信息
	 * Enter description here ...
	 */
	public function get_content()
	{
		$id = intval($this->input['from_id']);
		if (!$id)
		{
			$this->errorOutput('未传入from_id');
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id = " . $id;
		$channel_info = $this->db->query_first($sql);
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道信息不存在');
		}
		$info = array();
		if ($channel_info['logo_rectangle'])
		{
			$channel_info['logo_rectangle'] = @unserialize($channel_info['logo_rectangle']);
			
			$info['indexpic']['host'] 	  = $channel_info['logo_rectangle']['host'];
			$info['indexpic']['dir'] 	  = $channel_info['logo_rectangle']['dir'];
			$info['indexpic']['filepath'] = $channel_info['logo_rectangle']['filepath'];
			$info['indexpic']['filename'] = $channel_info['logo_rectangle']['filename'];
		}
		else 
		{
			$info['indexpic'] = '';
		}
		
		$info['content_fromid'] = $channel_info['id'];
		$info['ip'] = hg_getip();
		$info['user_id'] = $this->user['user_id'];
		$info['user_name'] = $this->user['user_name'];
		$info['title'] = $channel_info['name'];
		$info['brief'] = '';
		$info['outlink'] = MOD_UNIQUEID;
		$info['code'] = $channel_info['code'];
		$info['is_mobile_phone'] = $channel_info['is_mobile_phone'];
		$info['is_audio'] = $channel_info['is_audio'];
		$info['stream_name'] = $channel_info['stream_name'];
		
		$ret[] = $info;
		$this->addItem($ret);
		$this->output();
	}
	
	
	/**
	 * 更新直播频道栏目id
	 */
	public function update_content()
	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id = " . $data['from_id'];
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
		$sql = "UPDATE " . DB_PREFIX . "channel 
				SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' 
				WHERE id = " . $data['from_id'];
		
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	/**
	 * 在发布系统里面创建表
	 */
	public function create_table()
	{
		$table = new publishcontent();
		$data = array(
			'bundle_id'     => APP_UNIQUEID, 
			'module_id'     => MOD_UNIQUEID,
			'struct_id'     => "channel",
			'struct_ast_id' => "",
			'field'         => "id,expand_id,content_fromid,title,brief,outlink,code,logo_rectangle,is_mobile_phone,is_audio,stream_name",
			'array_field'   => "",
			'array_child_field'   => "",
			'table_title'   => "直播频道",
			'field_sql'   	=> "  `id` int(10) NOT NULL AUTO_INCREMENT,
								  `expand_id` int(10) NOT NULL,
								  `content_fromid` int(10) NOT NULL,
								  `title` varchar(128) NOT NULL,
								  `brief` varchar(512) NOT NULL,
								  `outlink` varchar(256) NOT NULL,
								  `code` char(20) NOT NULL,
								  `logo_rectangle` varchar(512) NOT NULL,
								  `is_mobile_phone` tinyint(1) NOT NULL,
								  `is_audio` tinyint(1) NOT NULL,
								  `stream_name` varchar(512) NOT NULL,
								  PRIMARY KEY (`id`) ",
			'show_field' => array(
					0 => array('field'=>'title', 'title'=>'频道名称', 'type'=>'text'),
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
			$sql = "SELECT column_id, column_url FROM " . DB_PREFIX ."channel WHERE id = " . $data['from_id'];
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
			$sql = "UPDATE " . DB_PREFIX ."channel SET column_id = '".addslashes(serialize($column_id))."', column_url = '".addslashes(serialize($column_url))."' WHERE id = " . $data['from_id'];
			$this->db->query($sql);						
		}	
		else		//全部删除
		{
			$sql = "UPDATE " . DB_PREFIX . "channel SET expand_id = '' AND column_id = '' AND column_url = '' WHERE id = " . $data['from_id'];
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

        $sql = "UPDATE " . DB_PREFIX . "channel SET";

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

$out = new channel_publish();
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