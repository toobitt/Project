<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: group_node.php 4039 2011-06-07 02:05:19Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class albumsNode extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 获取相册节点
	 */
	public function show()
	{
		//分页参数设置
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 50;

		if ($this->input['fields'] == 'all')
		{
			$fields = '*';
		}
		else
		{
			$fields = 'albums_id AS id, albums_name AS name , albums_description AS brief';
		}
		
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$sql = "SELECT {$fields} 
				FROM ".DB_PREFIX."albums WHERE 1 ";

		
		//获取查询条件
		$condition .= ' ORDER BY albums_id ASC';
		$sql = $sql . $condition . $data_limit;		
		$q = $this->db->query($sql);

		$this->setXmlNode('columns' , 'column');
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$this->addItem($row);
		}
		
		$this->output();		
	}
	
	/**
	 * 
	 * 获取相册总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "albums WHERE 1 ";

		//获取查询条件
		$r = $this->db->query_first($sql);
		echo json_encode($r);
	}
	
	/**
	 * 
	 * 获取一条相册记录
	 */
	public function detail()
	{
		$id = $this->input['albums_id'] ? intval($this->input['albums_id']) : -1;
		if($id > 0)
		{			
			$sql = "SELECT * FROM ".DB_PREFIX."albums WHERE albums_id = " . $id;		
			$r = $this->db->query_first($sql);
			$this->setXmlNode('columns' , 'column');
			
			if(is_array($r) && $r)
			{
				$this->addItem($r);
				$this->output();
			}
			else
			{
				$this->errorOutput('地盘不存在');	
			} 					
		}
		else
		{
			$this->errorOutput('未传入查询ID');		
		} 		
	}	
}

/**
 * 程序入口
 */

$out = new albumsNode();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';	
}
$out->$action();

?>