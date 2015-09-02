<?php
class memberMySet extends classCore
{
	private $membersql;
	public function __construct()
	{
		parent::__construct();
		$this->membersql = new membersql();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create($data)
	{
		return $this->membersql->create('member_myset',$data,true);
	}
	
	public function update($data,$param)
	{
		return $this->membersql->update('member_myset',$data,$param);
	}
	public function delete($data)
	{
		return $this->membersql->delete('member_myset', $data);
	}
	
	public function count($idsArr)
	{
		$membersql = new membersql();
		return $membersql->count($idsArr, 'member_myset');
	}
	
	public function show($condition,$offset,$count,$field = '*',$key = '',$orderby = 'ORDER BY order_id DESC',$type = 1,$otherkey = '')
	{
		$membersql = new membersql();
		return $membersql->show($condition, 'member_myset', $offset, $count,$orderby,$field,$key,array('icon'=>array('type'=>'array','format'=>'unserialize')),$type,$otherkey);
	}
	
	public function detail($id)
	{
		$membersql = new membersql();
		return $membersql->detail($id, 'member_myset',array('icon'=>array('type'=>'array','format'=>'unserialize')));
	}
	
	public function getMemberMySetByKey($condition='')
	{
		return $this->show($condition, 0, 0,'title,mark,url,display','mark');
	}
	
	public function getMySetMarkToIdBatch($mark)
	{
		
	}
	
	public function getMySetIdToMarkBatch($id)
	{
		if(!is_array($id))
		{
			throw new Exception(PARAM_WRONG, 200);
		}
		$mySetInfo = $this->show(array('id'=>$id), 0, 0,'mark','mark','',3,'mark');
		return $mySetInfo;
	}
	
	public function getMySetMarkToId($mark)
	{
		$mySetInfo = $this->getMySetMarkToIdBatch(array($mark));
		return $mySetInfo[$mark];
	}
	
	public function getMySetIdToMark($id)
	{
		$mySetInfo = $this->getMySetIdToMarkBatch(array($id));
		return $mySetInfo[$id];
	}
	
	/**
	 * 
	 * 获取定义字段数量，直接输出数量 ...
	 * @param unknown_type $id 可 直接为id 例如：1；也可以用 array('id'=>1);形式自定义参数
	 */
	public function getMFcount($id)
	{
		$count = array();
		$count = $this->getMFcountAssoc($id);
		return (int)$count['mfcount'];
	}
	
	/**
	 * 
	 * 获取定义字段数量,带键值输出 ...
	 * @param unknown_type $id 可 直接为id 例如：1；也可以用 array('id'=>1);形式自定义参数
	 */
	public function getMFcountAssoc($id)
	{
		$count = array();
		$membersql = new membersql();
		$membersql -> setSelectField('mfcount');
		$count = $membersql->detail($id, 'member_myset');
		return $count;
	}
	
	/**
	 * 
	 * 设置定义字段数量 ...
	 * @param unknown_type $id
	 * @param unknown_type $count
	 * @param unknown_type $type
	 */
	public function setMFcount($id,$count,$type)
	{
		$mfcountArr = array();
		$count = (int)$count;
		if($mfcountArr = $this->getMFcountAssoc($id))
		{
			$mfcount = (int)$mfcountArr['mfcount'];
			if($type == 'del')
			{
				if($count>0&&$mfcount>=$count)
				{
					$mfcount -=$count;
				}
				elseif ($count<0&&$mfcount>=abs_num($count))
				{
					$mfcount += $count;
				}
				else if ($count && $mfcount)
				{
					$mfcount = 0;
				}
			}
			else if ($type == 'add')
			{
				if($count<0)
				{
					throw new Exception(NOT_NEGATIVE_NUMBER, 200);
				}
				$count>0 && $mfcount += $count;
			}
			else 
			{
				throw new Exception(SETMFCOUNT_NOT_OPERATE, 200);
			}
			if($count) return $this->update(array('mfcount'=>$mfcount), array('id' => $id));
		}
		else
		{
			throw new Exception(MYSET_ERROR, 200);
		}
		return false;
	}
}

?>