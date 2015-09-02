<?php
define('MOD_UNIQUEID','tv_play_publish');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
class TvEpisodePublish extends appCommonFrm implements publish
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
		
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		
		
		//查询剧集信息
		$sql = "SELECT * FROM " . DB_PREFIX . "tv_episode WHERE id = {$id}";
		
		//$sql = "SELECT * FROM " . DB_PREFIX . "tv_play WHERE 1 AND id=".$id;
		//$tv_play_info = $this->db->query_first($sql);
		
		if($this->input['is_update'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."tv_episode WHERE id = {$id} " . $data_limit;
		}
		else 
		{
			//$sql = "SELECT * FROM " . DB_PREFIX ."tv_episode WHERE tv_play_id = {$id} AND expand_id = ''".$data_limit;
		}
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info))
		{
			$tv_play_id = $row['tv_play_id'];
			//$row['expand_id'] = $tv_play_info['expand_id'];
			$row['content_fromid'] = $row['id'];
			$row['indexpic'] = '';
			$row['ip'] = hg_getip();
			$row['user_id'] = $this->user['user_id'];
			$row['user_name'] = $this->user['user_name'];
			$row['img'] = $row['img'];
			unset($row['id'],$row['img']);
			$ret[] = $row;
		}
		
		if($tv_play_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "tv_play WHERE 1 AND id=".$tv_play_id;
			$tv_play_info = $this->db->query_first($sql);
			
			$ret[0]['expand_id'] = $tv_play_info['expand_id'];
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
		$sql = "UPDATE " . DB_PREFIX. "tv_episode 
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
$out = new TvEpisodePublish();
$action = $_INPUT['a'];
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
$out->$action(); 
?>
