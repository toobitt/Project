<?php
class credittype extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "credit_type ";
		$sql.= " WHERE 1 " . $condition;
		$sql .= " ORDER BY order_id DESC";
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if(!empty($row['img']))
			{
				$row['img']=maybe_unserialize($row['img']);
			}
			$return[] = $row;
		}

		return $return;
	}

	public function detail($id)
	{

		$condition = " WHERE id = ".intval($id);
		$sql = "SELECT * FROM " . DB_PREFIX . "credit_type " . $condition;
		$row = $this->db->query_first($sql);
		if($row['img'])
		{
			$row['img']=maybe_unserialize($row['img']);
		}
		if(is_array($row) && $row)
		{
			return $row;
		}
		return false;
	}

	public function display($ids, $opened,$field)
	{
		if($field)
		{			
			$no_unique=array('is_on');
			$credit_type=array();
			if(!in_array($field, $no_unique))
			{
				$sql='SELECT id FROM '.DB_PREFIX.'credit_type WHERE '.$field.'=1';
				$credit_type=$this->db->query_first($sql);
			}
			if(empty($credit_type)||in_array($field, $no_unique))
			{
				$sql = 'UPDATE '.DB_PREFIX.'credit_type SET '.$field.' = '.$opened.' WHERE id = '.$ids;
				$this->db->query($sql);
			}
			else
			{
				$no_unique=array('is_trans');
				if($credit_type['id']==$ids&&in_array($field, $no_unique))//控制交易积分可以全部关闭
				{
					$sql = 'UPDATE '.DB_PREFIX.'credit_type SET '.$field.' = '.$opened.' WHERE id = '.$ids;
					$this->db->query($sql);
				}
				elseif($credit_type['id']==$ids) 
				{
					return false;
				}
				$openeds=array($ids=>1,$credit_type['id']=>0);
				$_ids = implode(',', array_keys($openeds));
				$sql = "UPDATE ".DB_PREFIX."credit_type SET ".$field." = CASE id ";
				foreach ($openeds as $id => $ordinal) {
					$sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);  // 拼接SQL语句
				}
				$sql .= "END WHERE id IN ($_ids)";
				$this->db->query($sql);
			}
		}
		//	$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'opened'=>$opened?0:1,
		);
		return $arr;
	}

}

?>