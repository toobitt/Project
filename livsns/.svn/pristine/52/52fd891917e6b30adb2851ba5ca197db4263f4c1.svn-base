<?php
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
define('MOD_UNIQUEID','contribute');
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
	
	function get_content()
	{
		$id = intval($this->input['from_id']);
		$sort_id = intval($this->input['sort_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		if(empty($sort_id))
		{
			$sql = "SELECT c.*,cb.* FROM " . DB_PREFIX . "content c LEFT JOIN " . DB_PREFIX . "contentbody cb ON c.id = cb.id WHERE 1 and c.id=".$id . $data_limit;
		}
		else 
		{
			$sql = "SELECT c.*,cb.* FROM " . DB_PREFIX . "content c LEFT JOIN " . DB_PREFIX . "contentbody cb ON c.id = cb.id WHERE 1 and c.sort_id=".$sort_id . $data_limit;
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
			$row['content'] = $row['text'];
			$ret[$row['id']] = $row;
		}
		if ($ret)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "materials WHERE content_id IN (" . implode(',', array_keys($ret)) . ") ORDER BY materialid DESC";
			$info = $this->db->query($sql);
			$material = array();
			while($row = $this->db->fetch_array($info))
			{
				if(empty($row['vodid']))
				{
					$pic = array(
						'host'=>$row['host'],
						'dir'=>$row['dir'],
						'filepath'=>$row['material_path'],
						'filename'=>$row['pic_name'],
						'imgwidth'=>$row['imgwidth'],
						'imgheight'=>$row['imgheight'],
					);
					$material[$row['content_id']]['indexpic'] = $pic;
				}
				else
				{
					$video = array(
						'host'=>$row['host'],
						'dir'=>$row['dir'],
						'filepath' => $row['video_path'],
						'filename' => $row['filename'] . '.mp4',
					);
					$material[$row['content_id']]['video'] = $video;
				}
			}
		}
		$return = array();
		foreach ($ret AS $k => $v)
		{
			if ($material[$v['id']]['indexpic'])
			{
				$v['indexpic'] = $material[$v['id']]['indexpic'];
			}
			else
			{
				$v['indexpic'] = array();
			}
			if ($material[$v['id']]['video'])
			{
				$v['video'] = $material[$v['id']]['video'];
			}
			else
			{
				$v['video'] = array();
			}
			unset($v['id']);
			$return[] = $v;
		}
		$this->addItem($return);
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
			$sql = "UPDATE " . DB_PREFIX . "content SET expand_id = '' , column_id = '' , column_url = '' WHERE id = " . $data['from_id'];
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

        $sql = "UPDATE " . DB_PREFIX . "content SET";

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