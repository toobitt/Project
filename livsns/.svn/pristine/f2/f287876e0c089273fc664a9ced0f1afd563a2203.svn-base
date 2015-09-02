<?php
/***************************************************************************

* $Id: group 26794 2013-08-01 04:34:02Z purview $

***************************************************************************/
class medalmanage extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members=new members();
		$this->membersql = new membersql();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition,$offset,$count,$field='*')
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "medal WHERE 1 " . $condition ." ORDER BY order_id DESC".$limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['image_url']='';
			if($row['image'])
			{
				$row['image']=maybe_unserialize($row['image']);
				$row['image_url']=hg_fetchimgurl($row['image']);
			}
			if ($row['start_date'])
			{
				$row['start_date']=date('Y.m.d',$row['start_date']);
			}
			if ($row['end_date'])
			{
				$row['end_date']=date('Y.m.d',$row['end_date']);
			}
			if($row['start_date']&&empty($row['end_date']))
			{
				$row['award_date']=$row['start_date'].' - 世界末日';
			}
			if($row['end_date']&&empty($row['start_date']))
			{
				$row['award_date']='现在 - '.$row['end_date'];
			}
			if(empty($row['end_date'])&&empty($row['start_date']))
			{
				$row['award_date']='无限制';
			}
			if(empty($row['limit_num']))
			{
				$row['limit_num']='不限量';
			}
			else
			{
				$row['limit_num']=$row['limit_num'].'枚';
			}
			if(empty($row['used_num']))
			{
				$row['used_num']='未发放';
			}
			else
			{
				$row['used_num']=$row['used_num'].'枚';
			}
			if($row['end_date']&&$row['start_date'])
			{
				$row['award_date']=$row['start_date'].' - '.$row['end_date'];
			}
			if($row['brief']&&$row['brief'])
			{
				$row['brief']=html_entity_decode($row['brief']);
			}
			$return[] = $row;
		}

		return $return;
	}

	public function detail($id)
	{

		$sql = "SELECT * FROM " . DB_PREFIX . "medal WHERE id = " . $id;
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			$row['image_url']='';
			if($row['image'])
			{
				$row['image']=maybe_unserialize($row['image']);
				$row['image_url']=hg_fetchimgurl($row['image']);
			}
			if($row['start_date'])
			{
				$row['start_date']=date('Y-m-d',$row['start_date']);
			}
			if($row['end_date'])
			{
				$row['end_date']=date('Y-m-d',$row['end_date']);
			}
			if($row['start_date']||$row['end_date'])
			{
				$row['is_award_time']=1;
			}
			return $row;
		}
		return false;
	}

	/**
	 *
	 * 开关 ...
	 */
	public function display($ids, $opened)
	{
		$sql = 'UPDATE '.DB_PREFIX.'medal SET available = '.$opened.' WHERE id = '.$ids;
		$this->db->query($sql);
		$arr = array(
			'id'=>$ids,
			'opened'=>$opened,
		);
		return $arr;
	}
}

?>