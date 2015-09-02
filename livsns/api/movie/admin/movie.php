<?php
require('global.php');
class movie extends BaseFrm
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
		$offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
		$count = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;
		$limit = " limit {$offset}, {$count} ";
		
		//条件查询
		$sql = "select * from " . DB_PREFIX . "movie_info where 1 " ;
		$sql = $sql . $this->get_condition() . ' order by order_id desc , id desc ' .$limit;
		
		$key_value = $this->get_key_value( '1' );
		$query = $this->db->query( $sql );
		while( $row = $this->db->fetch_array($query) )
		{
			$row['area_name'] = $key_value['areas'][$row['area_id']];
			$row['lang_name'] = $key_value['langs'][$row['lang_id']];
			
			$row['actor'] = explode(',', $row['actor']);
			foreach ( $row['actor'] as $t )
				$actors[$t] = $key_value['persons'][$t];
			$row['actor'] = implode(',', $actors) ;
			
			$row['director'] = explode(',', $row['director']);
			foreach ( $row['director'] as $t )
				$directors[$t] = $key_value['persons'][$t];
			$row['director'] = implode(',', $directors) ;
			
			$row['lang'] = explode(',', $row['lang_id']);
			foreach ( $row['lang'] as $t )
				$langs[$t] = $key_value['langs'][$t];
			$row['lang'] = implode(',', $langs) ;
			
			$row['movie_sort_id'] = explode(',', $row['movie_sort_id'] );
			foreach ( $row['movie_sort_id'] as $t )
				$node_ids[$t] = $key_value['nodes'][$t];
			$row['movie_sort_id'] = implode('/', $node_ids) ;

			$row['create_time'] = date( 'Y-m-d H:i:s' , $row['create_time'] );
			$row['update_time'] = date( 'Y-m-d H:i:s' , $row['update_time'] );
			$row['release_time'] = date( 'Y-m-d H:i:s' , $row['release_time'] );
			
			$rows[] = $row;
			
			unset($langs);
			unset($actors);
			unset($directors);
			unset($node_ids);
		}
		$this->addItem_withkey("kv", $key_value);
		$this->addItem_withkey("list", $rows);
		$this->output();
	}
	
	public function count()
	{
		$sql = "select count(*) as count  from " . DB_PREFIX . "movie_info where 1 " . $this->get_condition() ;
		$row = $this->db->fetch_first( $sql );
		echo json_encode( $row['count'] );
	}
	
	public function get_condition()
	{
		$condition = '';
		
		if ( $this->input['keyword'] )
		{
			$keyword = urldecode( $this->input['keyword'] );
			$condition .= " and name like '%$keyword%' ";
		}
		if ( $this->input['movie_sort_id'] )
		{
			$movie_sort_id = urldecode( $this->input['movie_sort_id'] );
			$condition .= " and find_in_set( '$movie_sort_id' , movie_sort_id ) ";
		}
	    if ( $this->input['release_time'] )
		{
			$release_time = ceil(urldecode($this->input['release_time']));
			$start_time = strtotime( $release_time . '-0-0 0:0:0' );
			$end_time = strtotime( ( $release_time + 1 ) . '-0-0 0:0:0' );
			$condition .= " and release_time > $start_time and release_time < $end_time ";
		}
		if ( $this->input['area_id'] )
		{
			$area_id = urldecode( $this->input['area_id'] );
			$condition .= " and area_id = $area_id ";
		}
		if ( $this->input['actor'] )
		{
			$actor = urldecode( $this->input['actor'] );
			$condition .= " and find_in_set('$actor', actor ) ";
		}
		if( $this->input['status'] )
		{
			$status = urldecode( $this->input['status'] );
			$condition .= " and status = $status ";
		}
		if( $this->input['movie'] )
		{
			$movie = urldecode( $this->input['movie'] );
			$condition .= " and id = $movie ";
		}
		return $condition;
	}
	
	public function detail()
	{	
		if( $this->input['movie'] )
		{
			//查询
			$sql = "select * from " . DB_PREFIX . "movie_info where 1 " ;
			$sql = $sql . $this->get_condition() . ' order by order_id desc , id desc limit 1';
			$query = $this->db->query( $sql );
			$rows = array();
			$row = $this->db->fetch_array($query);
			$row['release_time'] = date( 'Y-m-d H:i:s' , $row['release_time'] );
			$row['create_time'] = date( 'Y-m-d H:i:s' , $row['create_time'] );
			$row['update_time'] = date( 'Y-m-d H:i:s' , $row['update_time'] );
		}
		$this->addItem($row);
		$this->output();
	}
	
	public function get_key_value( $return = '0' )
	{
		$sql = "select id,name from " . DB_PREFIX . "movie_area " ;
		$query = $this->db->query($sql);
		while ( $area = $this->db->fetch_array($query) )
		{
			$areas[$area['id']] = $area['name'];
		}
		$sql = "select id,name from " . DB_PREFIX . "movie_lang " ;
		$query = $this->db->query($sql);
		while ( $lang = $this->db->fetch_array($query) )
		{
			$langs[$lang['id']] = $lang['name'];
		}
		$sql = "select id , name from " . DB_PREFIX . "movie_person " ;
		$query = $this->db->query($sql);
		while ( $person = $this->db->fetch_array($query) )
		{
			$persons[$person['id']] = $person['name'];
		}
		$sql = "select id , name from " . DB_PREFIX . "movie_node " ;
		$query = $this->db->query($sql);
		while ( $node = $this->db->fetch_array($query) )
		{
			$nodes[$node['id']] = $node['name'];
		}
		$row['areas'] = $areas ;
		$row['langs'] = $langs ;
		$row['persons'] = $persons ;
		$row['nodes'] = $nodes ;
		
		if( $return == '1' )
		{
			return $row;
		}
		
		$this->addItem($row);
		$this->output();
	}
}

$out = new movie();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();