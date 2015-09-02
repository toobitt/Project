<?php
require_once ROOT_PATH . 'lib/class/curl.class.php';
require_once ROOT_PATH . 'lib/class/material.class.php';
class xcpw extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function getProduct($page)
	{
		//获取接口数据
		$data = $this->getXcpw($page);
		//hg_pre($data);exit();
		if ($data['dtsource'])
		{
			$count = $data['recordcount'] ? $data['recordcount'] : 0;
			$totalpage = $data['totalpages'] ? $data['totalpages'] : 0;
			$perpage = $data['totalperpage'] ? $data['totalperpage'] : 0;		
			
			//获取抓取纪录
			$sql = 'SELECT * FROM '.DB_PREFIX.'xcpw_show';
			$query = $this->db->query($sql);
			$record = array();
			while ($row = $this->db->fetch_array($query))
			{
				$record[$row['xc_id']] = $row['show_id'];
			}
			//获取索引图纪录
			$sql = 'SELECT s.id as sid,s.index_id,s.status,m.* FROM '.DB_PREFIX.'show s 
					LEFT JOIN '.DB_PREFIX.'material m ON s.index_id = m.id';
			$query = $this->db->query($sql);
			$m_record = array();
			while ($row = $this->db->fetch_array($query))
			{
				$m_record[$row['sid']] = $row['id'];
				$st[$row['sid']] = $row['status']; 
			}
			//hg_pre($m_record);exit();
			$num = 0;
			$number= $count-($page-1)*$perpage;
			if ($number>=$perpage)
			{
				$num = $perpage;
			}else 
			{
				$num = $number;
			}
			if ($num>0)
			{				
				for ($i=0;$i<$num;$i++)
				{
					$insert_id = '';
					$material_id = '';
					$price_ids = '';
					$low_price = 0;
					$show_id = '';
					$temp = $data['dtsource'][($num-($i+1))];
					if ($temp && is_array($temp) && !empty($temp))
					{
						if (in_array($temp['id'], array_keys($record)))
						{
						 	$show_id = $record[$temp['id']];
						}
						if (!$show_id || ($show_id && $st[$show_id]==0))
						{
							$state = '';	
							switch ($temp['state'])
							{
								case 0:$state = 0;break;
								case 1:$state = 1;break;
								case 2:$state = 2;break;
								case 3:$state = 3;break;
								case 10:$state =10;break;
							}
							//商品信息处理				
							$arr = array(
								'id'=>$show_id,
								'title'=>$temp['name'],
								'brief'=>$temp['brief'],
								'venue'=>$temp['host'],
								'address'=>$temp['address'],
								'show_time'=>$temp['time_notes'],
								'start_time'=>strtotime($temp['start_time_str']),
								'end_time'=>strtotime($temp['end_time_str']),
								'price_notes'=>addslashes($temp['price_notes']),
								'goods_total'=>intval($temp['goods_total']),
								'goods_total_left'=>intval($temp['goods_total_left']),
								'sale_state'=>$state,
								'appid'=>$this->user['appid'],
								'client'=>$this->user['display_name'],
								'create_time'=>TIMENOW,
								'update_time'=>TIMENOW,
								'user_id'=>$temp['create_userid'],
								'user_name'=>$temp['create_username'],	
							);
							if (!$arr['brief'] && $temp['notes'])
							{
								$arr['brief'] = hg_cutchars(strip_tags($temp['notes']),100);						
							}					
							$sql = 'REPLACE INTO '.DB_PREFIX.'show SET ';
							foreach ($arr as $kk=>$vv)
							{
								$sql .= $kk.'="'.addslashes($vv).'",';
							}
							$sql = rtrim($sql,',');
							$this->db->query($sql);
							$insert_id = $this->db->insert_id();
							//索引图处理
							if ($temp['logo']['original'])
							{
								$url = 'http://'.$this->settings['App_xcpw']['host'].'/'.$temp['logo']['original'];
								$res = $this->material->localMaterial($url, $insert_id);
								//入素材库
								if (!empty($res))
								{
									$res = $res[0];
									$materials = array(
										'show_id'=>$res['cid'],
										'index_url'=>serialize(array('host'=>$res['host'],'dir'=>$res['dir'],'filepath'=>$res['filepath'],'filename'=>$res['filename'])),
										'type'=>$res['type'],
										'original_id'=>$res['id'],
										'create_time'=>TIMENOW,
										'user_id'=>$this->user['user_id'],
										'user_name'=>$this->user['user_name'],
									);
									if (!in_array($show_id, array_keys($m_record)))
									{
										$sql = 'INSERT INTO '.DB_PREFIX.'material SET ';
										foreach ($materials as $key=>$val)
										{
											$sql .= $key.'="'.addslashes($val).'",'; 
										}
										$sql = rtrim($sql,',');
										$this->db->query($sql);
										$material_id = $this->db->insert_id();	
									}else {
										$sql = 'UPDATE '.DB_PREFIX.'material SET ';
										foreach ($materials as $key=>$val)
										{
											$sql .= $key.'="'.addslashes($val).'",'; 
										}
										$sql = rtrim($sql,',');
										$sql .= ' WHERE show_id = '.$show_id;
										$this->db->query($sql);
										$material_id = $m_record[$show_id];	
									}
								}					
							}	
							//内容处理
							if ($temp['notes'])
							{
								$content = $this->notesManage($temp['notes'],$insert_id);
								$sql = 'REPLACE INTO '.DB_PREFIX.'content (id,content) VALUES('.$insert_id.',"'.addslashes($content).'")';
								$this->db->query($sql);
							}
							if ($material_id)
							{
								// 更新主表信息
								$sql = 'UPDATE '.DB_PREFIX.'show SET index_id ='. $material_id .',low_price = '.$low_price.',order_id='.$insert_id.' WHERE id = '.$insert_id;
								$this->db->query($sql);
							}	
							//纪录数据关系
							if (!$show_id)
							{
								$sql = 'INSERT INTO '.DB_PREFIX.'xcpw_show (xc_id,show_id) VALUES('.$temp['id'].','.$insert_id.')';
								$this->db->query($sql);
							}
						}						
					}						
				}	
			}else {
				return false;
			}
		}else {
			return false;
		}
		return true;
		
	}
	private function getXcpw($page=0)
	{
		$this->curl = new curl($this->settings['App_xcpw']['host'],$this->settings['App_xcpw']['dir']);
		$this->curl->initPostData();
		$this->curl->setSubmitType('get');
		if ($page)
		{
			$this->curl->addRequestData('pageindex',$page);
		}	
		$ret = $this->curl->request('');
		return $ret;
	}
	/**
	 * 
	 * 星辰票务内容处理
	 * @param unknown_type $content
	 */
	private function notesManage($content,$cid)
	{
		preg_match_all('/<img.*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/is', $content, $match_mat);
		
		$pics = array();
		
		if($match_mat[1])
		{
			$i = 0;
			foreach($match_mat[1] as $k=>$v)
			{
				$res = '';
				$new_url = '';
				$xc_pic = 'http://'.$this->settings['App_xcpw']['host'].$v; 
				if (@file_get_contents($v))
				{
					$res = $this->material->localMaterial($v, $cid);
					if ($res)
					{
						$new_url = $res[0]['host'].$res[0]['dir'].$res[0]['filepath'].$res[0]['filename'];	
						$content = str_replace($v, $new_url, $content);
					}else 
					{
						$this->errorOutput('上传图片出错');
					}	
				}else if (@file_get_contents($xc_pic)){
					
					$res = $this->material->localMaterial($xc_pic, $cid);
					if ($res)
					{
						$new_url = $res[0]['host'].$res[0]['dir'].$res[0]['filepath'].$res[0]['filename'];
						$content = str_replace($v, $new_url, $content);
					}else 
					{
						$this->errorOutput('上传图片出错');
					}	
				}
			}
		}
		return $content;
	}
}