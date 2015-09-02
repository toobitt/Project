<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: keywords.php 17941 2013-02-26 02:20:49Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class keywordApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}	
	
	/**
	* 添加关键词
	* @param $keyword
	* @param $result_count
	* @return 当前关键词的信息
	*/	
	public function create()
	{
		$info = array();
		$keyword = urldecode(trim($this->input['keywords']));
		$result_count = trim($this->input['result_count']);
		$sql = "SELECT * FROM ".DB_PREFIX."keywords WHERE keyword='".$keyword."'";
		$first = $this->db->query_first($sql);
		if(!$first)
		{
			$sql = "INSERT INTO ".DB_PREFIX."keywords(
				keyword,
				count,
				result_count
			) 
			VALUES(
				'".$keyword."',
				1,
				".$result_count."
			)";
			$this->db->query($sql);
			$id = $this->db->insert_id();
			if($id)
			{
				$info = array(
					'id' => $id,
					'keyword' => $keyword,
					'count' => 1,
					'result_count' => $result_count,
				);
			}
		}
		else
		{
			$count = $first['count'] + 1;
			$sql = "UPDATE ".DB_PREFIX."keywords 
			SET count = ".$count." , result_count = ".$result_count." 
			WHERE id = ".$first['id'];
			$this->db->query($sql);
			$num = $this->db->affected_rows();
			if($num)
			{
				$info = array(
					'id' => $first['id'],
					'keyword' => $keyword,
					'count' => $count,
					'result_count' => $result_count,
				);
			}
		}		
		$this->setXmlNode('keywords','info');		
		$this->addItem($info);
		$this->output();		
	}
	
	/**
	* 获取关键词信息
	* @return 当前关键词的信息
	*/	
	public function show()
	{  
		$info = array();
		$keyword = urldecode(trim($this->input['keywords']));
		$sql = "SELECT * FROM ".DB_PREFIX."keywords WHERE keyword='".$keyword."'";
		$info = $this->db->query_first($sql);
		$this->setXmlNode('keywords','info');		
		$this->addItem($info);
		$this->output();
		 
	}
}
$out = new keywordApi(); 
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	 $action = 'show';
}
$out->$action();
?>