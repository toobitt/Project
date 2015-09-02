<?php
define('MOD_UNIQUEID','tuji_publish');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
class PicPublish extends appCommonFrm implements publish
{
		/**
	 * 构造函数
	 */
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
		$data_limit = ' ORDER BY order_id LIMIT ' . $offset . ' , ' . $num;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "tuji WHERE 1 AND id=".$id;
		$tuji_info = $this->db->query_first($sql);
		
		if($this->input['is_update'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."pics WHERE id = {$id} " . $data_limit;
		}
		else 
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."pics WHERE tuji_id = {$id} AND expand_id = ''".$data_limit;
		}
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info))
		{
			$row['expand_id'] = $tuji_info['expand_id'];
			$row['content_fromid'] = $row['id'];
			$row['indexpic'] = '';
			$row['ip'] = hg_getip();
			$row['user_id'] = $this->user['user_id'];
			$row['user_name'] = $this->user['user_name'];
			$row['pic'] = $row['img_info'];
			$row['title'] = $row['old_name'];
			$row['brief'] = $row['description']?$row['description']:(!DESCRIPTION_TYPE?$tuji_info['comment']:'');
			//$row['brief'] = $row['description'];
			unset($row['id'],$row['img_info']);
			$ret[] = $row;
		}
		$this->addItem($ret);
		$this->output();	
	}
 	
 	/**
 	 * 更新内容expand_id,发布内容id
 	 *
 	 */
 	function update_content()
 	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX. "pics 
				SET expand_id = " . $data['expand_id'] . " 
				WHERE id =" . $data['from_id'];
		$this->db->query($sql);
		$this->addItem('success');
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
$out = new PicPublish();
$action = $_INPUT['a'];
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
$out->$action(); 
?>
