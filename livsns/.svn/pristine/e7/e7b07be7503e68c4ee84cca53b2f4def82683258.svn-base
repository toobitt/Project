<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
class block_set extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function get_block_line($id)
	{
		$result = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "block_line WHERE block_id=".$id;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$result[$row['line']] = $row;
		}
		return $result;
	}
	
	public function get_group_block_line($ids)
	{
		$result = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "block_line WHERE block_id in(".$ids.")";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$result[$row['block_id']][$row['line']] = $row;
		}
		return $result;
	}
	
	public function get_block_line_first($id,$line)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "block_line WHERE block_id=".$id." AND line=".$line;
		$result = $this->db->query_first($sql);
		return $result;
	}
	
	public function get_block_content($id , $num = '')
	{
		$result = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "block_content WHERE block_id=".$id;
		if($num)
		{
			$sql .= " AND line<=".$num;
		}
		$sql .= " ORDER BY line";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['indexpic'] = $row['indexpic']?unserialize($row['indexpic']):'';
			$result[$row['line']][] = $row;
		}
		return $result;
	}
	
	public function get_group_block_content($block)
	{
		$result = array();
		foreach($block as $v)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "block_content WHERE block_id=".$v['id'];
			if($v['line_num'])
			{
				$sql .= " AND line<=".$v['line_num'];
			}
			$sql .= " ORDER BY line,child_line";
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$row['indexpic'] = $row['indexpic']?unserialize($row['indexpic']):'';
				$result[$v['id']][$row['line']][] = $row;
			}
		}
		return $result;
	}
	
	public function get_all_contentid($block_id)
	{
		$result = array();
		$sql = "SELECT content_id FROM " . DB_PREFIX . "block_content WHERE block_id=".$block_id;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$result []= $row['content_id'];
		}
		return $result;
	}
	
	public function get_block_content_info($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "block_content WHERE id=".$id;
		return $this->db->query_first($sql);
	}
	
	public function get_child_line($id , $line)
	{
		$result = array();
		$sql = "SELECT id,line,child_line FROM " . DB_PREFIX . "block_content WHERE block_id=".$id." AND line=".$line;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$result[$row['id']] = $row['child_line'];
		}
		if(empty($result))
		{
			return 1;
		}
		else
		{
			//返回数组中最大的数+1
			rsort($result);
			return intval($result[0]['line']+1);
		}
	}
		
	public function insert_line($line_num , $data)
	{
		for($i=1 ; $i<=$line_num ; $i++)
		{
			$data['line'] = $i;
			$sql="INSERT INTO " . DB_PREFIX . "block_line SET ";
			
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
			$this->db->query($sql);
		}
	}
	
	public function insert_content($line_num , $block_id , $content_data)
	{
		
		foreach($content_data as $k=>$v)
		{
			$data['block_id'] = $block_id;
			$data['content_id'] = $v['id'];
			$data['line'] = $k+1;
			$data['title'] = $v['title'];
			$data['brief'] = $v['brief'];
			$data['outlink'] = $v['outlink'];
			$data['indexpic'] = serialize($v['indexpic']);
			$data['child_line'] = 1;
			$sql="INSERT INTO " . DB_PREFIX . "block_content SET ";
			
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
			$this->db->query($sql);
		}
		
	}
	
	public function insert_child_content($data , $tag)
	{
		if($tag)
		{
			//更新其他内容line+1
			$sql = "UPDATE " . DB_PREFIX . "block_content SET line=line+1 WHERE block_id=".$data['block_id'];
			$this->db->query($sql);
		}
		
		$sql="INSERT INTO " . DB_PREFIX . "block_content SET ";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .= $sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function update_content($content_id,$data)
	{
		$sql="UPDATE " . DB_PREFIX . "block_content SET ";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .= $sql_extra;
		$sql .= " WHERE id=".$content_id;
		$this->db->query($sql);
	}
	
	public function update_block_line($id,$line,$data)
	{
		$sql="UPDATE " . DB_PREFIX . "block_line SET ";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .= $sql_extra;
		$sql .= " WHERE block_id=".$id." AND line in (".$line.")";
		$this->db->query($sql);
	}
	
	public function update_line_by_id($id,$line)
	{
		$sql = "UPDATE " . DB_PREFIX . "block_line SET line=".$line;
		$sql .= " WHERE id=".$id;
		$this->db->query($sql);
	}
	
	public function update_content_by_id($id,$l)
	{
		if(isset($l['line']))
		{
			$data['line'] = $l['line'];
		}
		if(isset($l['child_line']))
		{
			$data['child_line'] = $l['child_line'];
		}
		if(!empty($data))
		{
			$sql="UPDATE " . DB_PREFIX . "block_content SET ";
			
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .= $sql_extra;
			$sql .= " WHERE id=".$id;
			$this->db->query($sql);
		}
	}
	
	public function delete_content_by_id($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "block_content WHERE id=".$id;
		$this->db->query($sql);
	}
	
	public function delete_content($content_id)
	{
		$content_info = $this->get_block_content_info($content_id);
		if(empty($content_info))
		{
			return false;
		}
		$sql = "SELECT id,line,child_line FROM " . DB_PREFIX . "block_content WHERE block_id=".$content_info['block_id']." AND line=".$content_info['line'];
		$line_content = $this->db->fetch_all($sql);
		$l_c_num = count($line_content);
		if($l_c_num == 1)
		{
			//表示这一行只有一条数据
			$sql = $sql = "UPDATE " . DB_PREFIX . "block_content SET line=line-1 WHERE line>".$content_info['line']." AND block_id=".$content_info['block_id'];
			$this->db->query($sql);
		}
		else
		{
			//更新同行的子行 child_line
			$sql = $sql = "UPDATE " . DB_PREFIX . "block_content SET child_line=child_line-1 WHERE child_line>".$content_info['child_line']." AND block_id=".$content_info['block_id'];
			$this->db->query($sql);
		}
		$this->delete_content_by_id($content_id);
		return true;
	}
	
}

?>