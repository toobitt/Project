<?php
define('MOD_UNIQUEID','news_publish');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
class MaterialPublish extends adminUpdateBase implements publish
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
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}		
	public function get_content()
	{
		$id = intval($this->input['from_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		
		$sql = "select * from " . DB_PREFIX . "article where 1 and id=".$id;
		$article_info = $this->db->query_first($sql);
		
		
		$sql = "SELECT *,host pic_host,dir pic_dir,filepath pic_filepath,filename pic_filename FROM " . DB_PREFIX ."material where 1 and cid=" . $id ." and expand_id = '' and isdel = 1 ". $data_limit;
		
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info)) 
		{
			$row['bundle_id'] = APP_UNIQUEID;
			$row['module_id'] = MOD_UNIQUEID;
			$row['struct_id'] = 'article';
			$row['struct_ast_id'] = 'material';
			$row['expand_id'] = $article_info['expand_id'];
			$row['content_fromid'] = $row['id'];
			$row['indexpic'] = 'index.php';
			$row['ip'] = hg_getip();
			$row['user_id'] = $this->user['user_id'];
			$row['user_name'] = $this->user['user_name'];
			$row['pic'] = unserialize($row['pic']);
			unset($row['id']);
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
			$this->errorOutput('data is empty!');
		}
		$sql = "UPDATE " . DB_PREFIX. "material 
				SET expand_id = " . $data['expand_id'] . " 
				WHERE id =" . $data['from_id'];
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

$out = new MaterialPublish();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'unknow';
}
$out->$action(); 
?>
