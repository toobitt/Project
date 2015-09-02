<?php
require('global.php');
class movie_person extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$sql = $sql = "select * from " . DB_PREFIX . "movie_person where 1 " . $this->get_condition();
		$rows = $this->db->fetch_all($sql);
		foreach( $rows as $k => $v )
		{
			if( $v['type'] == '1' )
				$rows[$k]['type_name'] = '演员';
			if( $v['type'] == '2' )
				$rows[$k]['type_name'] = '导演';
			
			/*
			$film_ids = explode(',', $v['film_works']);
			if( !empty($film_ids) )
			{
				foreach ( $film_ids as $vs )
				{
					$_query = $this->db->query("select name from " . DB_PREFIX . "movie_info where id = " . $vs );
					$_rows = $this->db->fetch_array($_query);
					$rows[$k]['film_work_names'][] = $_rows['name'];
				}
				$rows[$k]['film_work_names'] = implode(',', $rows[$k]['film_work_names']);
			}
			*/
		}
		$this->addItem($rows);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if ( $this->input['keyword'] )
		{
			$keyword = urldecode( $this->input['keyword'] );
			$condition .= " and name like '%$keyword%' ";
		}
		return $condition;
	}
	
	public function person_detail()
	{
		if($this->input['id'])
		{
			$sql = $sql = "select * from " . DB_PREFIX . "movie_person where id = " . $this->input['id'];
			$query = $this->db->query($sql);
			$row = $this->db->fetch_array( $query );
			
			$film_ids = explode(',', $row['film_works']);
			if( !empty($film_ids) )
			{
				foreach ( $film_ids as $vs )
				{
					$_query = $this->db->query("select name from " . DB_PREFIX . "movie_info where id = " . $vs );
					$_rows = $this->db->fetch_array($_query);
					$rows[$k]['film_work_names'][] = $_rows['name'];
				}
				$row['film_work_names'] = implode(',', $rows[$k]['film_work_names']);
			}
			$this->addItem( $row );
		}
		$this->output();
	}
	
	
	
	public function unknow()
	{
		$this->output("没有这个方法");
	}
}

$out = new movie_person();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
