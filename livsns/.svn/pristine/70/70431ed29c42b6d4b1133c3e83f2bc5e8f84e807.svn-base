<?php
/***************************************************************************

* $Id: member.class.php 36754 2014-04-30 10:02:14Z youzhenghuan $

***************************************************************************/
class memberMy extends classCore
{
	private $membersql;
	private $memberMySet;
	public function __construct()
	{
		parent::__construct();
		$this->membersql = new membersql();
		$this->memberMySet = new memberMySet();

	}

	public function __destruct()
	{
		parent::__destruct();
	}

	private function getMydata($member_id,$mark = '')
	{
		if(!$member_id)
		{
			return array();
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'member_my WHERE 1 AND member_id = '.$member_id;
		if($mark){
			$sql .=' AND mark = \''.$mark.'\'';
		}
		$query = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($query))
		{
			$ret[$row['mark']] = array(
				'total'=>$row['total'],
				'totalsum'=>$row['totalsum'],
			);
		}
		return $ret;
	}

	public function verify($param)
	{
		return $this->membersql->verify('member_my',$param);
	}

	public function update($data,$param,$isMath,$math_key=array())
	{
		return $this->membersql->update('member_my',$data,$param,$isMath,$math_key);
	}

	public function create($data)
	{
		return $this->membersql->create('member_my', $data);
	}
	/**
	 *
	 * 数据输出格式化 ...
	 * @param unknown_type $myData
	 */
	public function outPutDataFormat($myData = array(),$useSource = 0)
	{
		$_myData = array();
		$condition = '';//查询条件
		if($useSource == 1)
		{
			$condition ='AND useSource !=2';
		}
		elseif ($useSource == 2)
		{
			$condition ='AND useSource !=1';
		}
		if($myConfig = $this->memberMySet->getMemberMySetByKey($condition))
		{
			foreach ($myConfig as $k => $v)
			{
				if($v['display'])//控制是否输出显示
				{
					$_myData[] = array(
					'title'=>$v['title'],
					'mark' =>$k,
					'url' =>$v['url'],
					'total'=>$myData[$k]['total']?intval($myData[$k]['total']):0,	
					'totalsum'  =>$myData[$k]['totalsum']?intval($myData[$k]['totalsum']):0					
					);
				}
			}
		}
		return $_myData;
	}

	public function cache($member_id)
	{
		$myData = array();
		$myData = $this->getMydata($member_id);
		$myData = $this->oldcacheProess($myData);//不合法数据删除(例如：数据库中已存在，但是卡片标识数组不存在的值)
		$data = array('myData'=>maybe_serialize($myData));
		$this->membersql->update('member',$data,array('member_id'=>$member_id));
		return $myData;
	}

	/**
	 *
	 * 不合法数据删除 ...
	 * @param unknown_type $myData
	 */
	private function oldCacheProess($myData)
	{
		$delmark = array();
		if($myData&&($myConfig = $this->memberMySet->getMemberMySetByKey()))
		foreach ($myData as $k => $v)
		{
			if(!array_key_exists($k,$myConfig))
			{
				$delmark['mark'] = $k;
				unset($myData[$k]);
			}
		}
		if($delmark)
		{
			$this->membersql->delete('member_my', $delmark);
		}
		return $myData;
	}
}

?>