<?php
require_once('global.php');
define('MOD_UNIQUEID','tuji');
class  tuji_request_opration  extends appCommonFrm
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public  function show_opration()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT t.*,ts.name as sort_name FROM ".DB_PREFIX."tuji as t LEFT JOIN ".DB_PREFIX."tuji_node as ts ON t.tuji_sort_id = ts.id WHERE t.id = '".intval($this->input['id'])."'";
		$ret = $this->db->query_first($sql);
		if($ret['cover_url'])
		{
			$img_arr = unserialize($ret['cover_url']);
			$ret['cover_url'] = $img_arr['host'].$img_arr['dir'].$img_arr['filepath'].$img_arr['filename'];
			//$ret['cover_url'] = UPLOAD_ABSOLUTE_URL.$ret['cover_url'];
		}
		else
		{
			$ret['cover_url'] = 0;
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_tuji_image()
	{
		if(!$this->input['id'])//图集的id
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT COUNT(*) AS total_num FROM ".DB_PREFIX."pics WHERE tuji_id = '".intval($this->input['id'])."'";
		$arr = $this->db->query_first($sql);
		
		//如果该图集里面没有图片
		$return = array();
		if(!$arr['total_num'])
		{
			$return['pic_url'] = 0;
			$return['total_num'] = 0;
			$return['nochild'] = 1;
			$return['prev_page']  = 0;
			$return['next_page']  = 0;
			$return['current_page'] = 0;
			$return['over'] = 1;
		}
		else 
		{
			$start = intval($this->input['start']);
			if($start > (intval($arr['total_num']) - 1))
			{
				$start = (intval($arr['total_num']) - 1);
			}
			
			$order = " ORDER BY p.order_id ASC ";
			$sql = "SELECT p.*,t.default_comment,t.is_namecomment FROM ".DB_PREFIX."pics p LEFT JOIN ".DB_PREFIX."tuji t ON t.id = p.tuji_id WHERE p.tuji_id = '".intval($this->input['id'])."' {$order} LIMIT {$start},1";
			/*
			$order = " ORDER BY order_id ASC ";
			$sql = "SELECT * FROM ".DB_PREFIX."pics WHERE tuji_id = '".intval($this->input['id'])."' {$order} LIMIT {$start},1";
			*/
			$return = $this->db->query_first($sql);
			$return['img_info'] = unserialize($return['img_info']);
			$return['pic_url'] = hg_fetchimgurl($return['img_info']);
			$return['total_num'] = $arr['total_num'];
			
			$current_page = $start;
			$last_page = intval($arr['total_num']) - 1;
			/*下一页*/
			$over = 0;
			if($current_page == $last_page)
			{
				$over = 1;
				$return['next_page']  = $current_page;
			}
			else
			{
				$return['next_page']  = $current_page + 1;
			}
	
			/*前一页*/
			if($current_page == 0)
			{
				$return['prev_page']  = 0;
			}
			else 
			{
				$return['prev_page']  = $current_page - 1;
			}
			$return['current_page']  = intval($current_page) + 1;
			$return['over'] = $over;
		}
		
		$this->addItem($return);
		$this->output();
	}
}

$out = new tuji_request_opration();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show_opration';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>