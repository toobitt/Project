<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: news.class.php 46085 2015-06-10 05:25:37Z develop_tong $
***************************************************************************/
class curd extends InitFrm
{
	private $table = '';
	public function __construct($table = '')
	{
		parent::__construct();
		if($table)
		{
			$this->set_table($table);
		}
	}
	public function set_table($table = '')
	{
		$this->table = DB_PREFIX . $table;
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function create($data){
		if(!empty($data))
		{
			$sql = 'INSERT INTO ' . $this->table . ' SET ';
			foreach($data as $field=>$value)
			{
				$sql .= "`{$field}`=\"".addslashes($value)."\",";
			}
			$sql = trim($sql, ',');
			$this->db->query($sql);
			file_put_contents(CACHE_DIR . 'debug.txt', $sql);
			$id = $this->db->insert_id();
			return $id;
		}
		return 0;
	}
	public function update($data){
		
		if(!empty($data) && $data['id'])
		{
			$id = $data['id'];
			unset($data['id']);
			$sql = 'UPDATE ' . $this->table . ' SET ';
			foreach($data as $field=>$value)
			{
				$sql .= "`{$field}`=\"".addslashes($value)."\",";
			}
			$sql = trim($sql, ',') . ' WHERE id = ' . $id;
			$this->db->query($sql);
			return $this->db->affected_rows();
		}
		return 0;
		
	}
	public function delete($id=0, $con=''){
		
		if($id)
		{
			$sql = 'DELETE FROM ' . $this->table . ' WHERE id IN(' . $id . ')';
		}
		else
		{
			$sql = 'DELETE FROM ' . $this->table . ' WHERE 1 ' . $con;
		}
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	public function show($field=' * ' ,$cond = '', $orderby = ' order by id  DESC '){
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count	= $this->input['count'] ? $this->input['count'] : 50;
		$limit  = "limit {$offset}, {$count}";
		$sql = 'SELECT '.$field.' FROM ' . $this->table . ' where 1 ' . $cond . $orderby . $limit;
		$data = array();
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$data[] = $row;
		}
		return $data;
	}
	public function count($cond = ''){
		
		$sql = 'SELECT COUNT(*) AS  total FROM ' . $this->table . ' where 1 ' . $cond;
		return $this->db->query_first($sql);
	}
	public function detail($id=0, $con='', $field='*'){
		if($id)
		{
			$sql = 'SELECT '.$field.' FROM ' . $this->table . ' WHERE id = ' . $id;
		}
		else
		{
			$sql = 'SELECT '.$field.' FROM ' . $this->table . ' WHERE 1 ' . $con;
		}
		return $this->db->query_first($sql);
	}
	
}
?>
