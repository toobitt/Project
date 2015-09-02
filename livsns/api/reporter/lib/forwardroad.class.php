<?php
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class forwardroad extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$limit)
	{
		//查询上次纪录
		//$ret = $this->get_record($id);
		$ret = $this->get_record($condition,$orderby,$limit);
		if ($ret && $ret!=-1)
		{
			$sql = 'SELECT c.id as cid,c.*,cb.* FROM '.DB_PREFIX.'content c 
			LEFT JOIN '.DB_PREFIX.'contentbody cb ON c.id = cb.id 
			WHERE c.id >'.$ret['since_id'].' AND c.sort_id = '.$ret['sort_id'].' ORDER BY c.id ASC limit 0,10';
			$query = $this->db->query($sql);
			$k = array();
			$ids = array();
			while ( $row = $this->db->fetch_array($query))
			{
				$k[$row['cid']] = $row;
				$ids[] = $row['cid'];
			}
			if (!empty($k))
			{
				//取所有图片
				$ids = implode(',', $ids);
				$materials = $this->get_materials($ids);
				$num = 0;
				//纪录状态
				$state = array();
				foreach ($k as $key=>$val)
				{
					$num = ($key>$num) ? $key : $num;
					$val['pic'] = $materials[$key] ? $materials[$key][$val['material_id']] : '';				
					$return = $this->forward_road($val,$ret);	
					$state[$key] = $return;
				}
				
				//检查是否有失败的，失败的再次重新发送
				foreach ($state as $key=>$val)
				{					
					if (!$val)
					{
						$k[$key]['pic'] = $k[$key]['pic'] ? $k[$key]['pic'][0] : '';
						$kk = $this->forward_road($k[$key]);
						$state[$key] = $kk;
					}
				}
				//更新纪录
				$this->update_record($num,$ret['id']);
				//更新爆料状态
				$kkk = array();
				foreach ($state as $key=>$val)
				{
					if ($val)
					{
						$kkk[$key] = $val;
					}
				}
				if (!empty($kkk))
				{
					$this->update_contribute(implode(',', array_keys($kkk)));
				}
				return true;	 
			}else {
				return true;
			}
		}
		if (!$ret)
		{
			return false;
		}
		if ($ret==-1)
		{
			return -1;//未开启
		}
		
	}
	private function get_record($condition,$orderby,$limit)
	{
		/*
		$sql = 'SELECT * FROM '.DB_PREFIX.'transmit WHERE id='.$id.' AND is_open = 1';
		$res = $this->db->query_first($sql);
		if ($res)
		{
			return $res;
		}else 
		{
			return false;
		}
		*/
		$sql = 'SELECT * FROM '.DB_PREFIX.'transmit WHERE 1 '.$condition.$orderby.$limit;
		$ret = $this->db->query_first($sql);
		if ($ret)
		{
			if ($ret['is_open'])
			{
				return $ret;
			}else {
				return -1;//未打开
			}
		}else{
			return false;
		}
		
		
	}
	private function get_materials($ids)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			//目前只处理图片
			if (!$row['vodid'])
			{
				$k[$row['content_id']][$row['materialid']] = array(
					'id'=>$row['original_id'],
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'filepath'=>$row['material_path'],
					'filename'=>$row['pic_name'],
				);
			}
			
		}
		return $k;
	}
	private function forward_road($data,$ret)
	{
		//hg_pre($ret);exit();
		$curl = new curl($ret['host'],$ret['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		$curl->addRequestData('content',$data['text']);
		$curl->addRequestData('longitude',$data['longitude']);
		$curl->addRequestData('latitude',$data['latitude']);
		$curl->addRequestData('pic', $data['pic']);
		if ($data['pic'])
		{
			foreach ($data['pic'] as $key=>$val)
			{
				$curl->addRequestData("pic[$key]", $val);
			}
		}
		$ret = $curl->request($ret['filename']);
		return $ret;
	}
	private function update_record($since_id,$id)
	{
		$data = array(
			'since_id'=>intval($since_id),
			'since_time'=>TIMENOW,
		);
		$sql = ' UPDATE '.DB_PREFIX.'transmit SET since_id = '.$data['since_id'].',since_time='.$data['since_time'].' WHERE id='.$id;
		$this->db->query($sql);
		return true;
	}
	private function update_contribute($ids)
	{
		$sql = 'UPDATE '.DB_PREFIX.'content SET is_road=1 WHERE id IN ('.$ids.')';
		$query= $this->db->query($sql);
		return true;
	}
}