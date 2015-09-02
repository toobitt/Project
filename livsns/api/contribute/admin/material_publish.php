<?php
define('MOD_UNIQUEID','material_publish');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
class materialPublish extends appCommonFrm implements publish
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	function get_content()
	{
		$id = intval($this->input['from_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		
		$sql = "select * from " . DB_PREFIX . "content where 1 and id=".$id;
		$content = $this->db->query_first($sql);
		
		
		$sql = "SELECT * FROM " . DB_PREFIX ."materials where 1 and  expand_id =0  and content_id=" . $id . $data_limit;
		
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info)) 
		{
			$row['bundle_id'] = APP_UNIQUEID;
			$row['module_id'] = MOD_UNIQUEID;
			$row['struct_id'] = 'contribute';
			$row['struct_ast_id'] = 'materials';
			$row['expand_id'] = $content['expand_id'];
			$row['content_fromid'] = $row['materialid'];
			$row['indexpic'] = 'index.php';
			$row['ip'] = hg_getip();
			$row['user_id'] = $this->user['user_id'];
			$row['user_name'] = $this->user['user_name'];
			$row['pic'] = array(
							'host'=>$row['host'],
							'dir'=>$row['dir'],
							'filepath'=>$row['material_path'],
							'filename'=>$row['pic_name'],	
							);
			$video = array();
            if(!empty($row['vodid']))
			{
				$video = array(
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'id'=>$row['vodid']
				);
			}
			$row['video'] = $video;
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
		$sql = "update " . DB_PREFIX. "materials set expand_id = " . $data['expand_id'] . " where materialid =" . $data['from_id'];
		$this->db->query($sql);
		$this->addItem('true');
		$this->output();
 	}

 	/**
 	 * 删除这条内容的发布
 	 *
 	 */
 	function delete_publish()
 	{
 				
 	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
	
}
$out = new materialPublish();
$action = $_INPUT['a'];
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
$out->$action(); 