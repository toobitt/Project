<?php
class attach extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function get_attach_by_aid($aid = 0, $index_id = false)
	{
		$material = array();
		if($aid)
		{
			$sql  = 'SELECT * FROM ' . DB_PREFIX . 'attach WHERE id IN('.$aid.')';
			
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$material[$row['id']] = ($tmp = unserialize($row['uri'])) ? $tmp : array();
				if($material[$row['id']])
				{
					$material[$row['id']]['aid'] = $row['id'];
					$material[$row['id']]['type'] = $row['type'];
					if($row['type'] == 'link')
					{
						$material[$row['id']]['shorturl'] = SHORT_URL . $material[$row['id']]['shorturl'];
						$material[$row['id']]['realadd'] = urldecode($material[$row['id']]['realadd']);
					}
					$material[$row['id']]['extend'] = array();
					if($extend = unserialize($row['extend']))
					{
						$material[$row['id']]['extend'] = $extend;
					}
				}
			}
			if($material && !$index_id)
			{
				$material = array_values($material);
			}
		}
		return $material;
	}
	function delete_attach($aid, $table='attach_tmp')
	{
		$aid = (is_array($aid) && !empty($aid)) ? implode(',', $aid) : $aid;
		if($aid)
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE id IN('.$aid.')';
			$this->db->query($sql);
		}
	}
	function attach($attach, $type, $table='attach', $extend=array())
	{
		$uri = serialize($attach);
		$create_time = TIMENOW;
		$sql = 'INSERT INTO ' . DB_PREFIX . $table .' SET type="' .$type.'", uri="'.addslashes($uri).'",create_time='.$create_time;
		if($extend)
		{
			$sql .= ',extend="'.addslashes(serialize($extend)).'"';
		}
		$this->db->query_first($sql);
		return $this->db->insert_id();
	}
	function map($location)
	{
		foreach($location as $val)
		{
			if(!$val)
			{
				return '';
			}
		}
		$aid = $this->attach($location, 'location');
		return $aid;
	}
	function outlink($link)
	{
		if(!preg_match('/^http(s)?:\/\/(.*?)/i', $link))
		{
			return '';
		}
		$short_link = shorturl($link);
		$linkinfo = array(
		'shorturl'=>$short_link[0],
		'realadd'=>urlencode($link),
		);
		$aid = $this->attach($linkinfo, 'link');
		return $aid;
	}
	function tmp2att($aid)
	{
		$aid = (is_array($aid) && !empty($aid)) ? implode(',', $aid) : $aid;
		$sql = 'SELECT type, uri, extend, create_time FROM ' . DB_PREFIX . 'attach_tmp WHERE id IN('.$aid.')';
		
		$attach_aid =  '';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . 'attach(type, uri, extend, create_time) VALUE("'.$row['type'].'","'.addslashes($row['uri']).'","'.addslashes($row['extend']).'",'.TIMENOW.')';
			$this->db->query($sql);
			$attach_aid .= $this->db->insert_id() . ',';
		}
		
		return $aid = trim($attach_aid,',');
	}
	function get_avatar($id)
	{
		include_once(ROOT_PATH  . 'lib/class/auth.class.php');
	 	$auth = new auth();
	 	$userinfo = $auth->getMemberById($id);
	 	return $userinfo[0]['avatar'];
	}
}
?>