<?php
define('MOD_UNIQUEID','getContribute');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once ROOT_PATH.'lib/class/share.class.php';
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class getContribute extends coreFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->share = new share();
		$this->material = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '爆料获取微博',	 
			'brief' => '获取微博',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function  get_contribute()
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."user_queue ORDER BY weight DESC,since_time ASC LIMIT 0,1";
		$info = $this->db->query_first($sql);
		$since_id = '';	
		if (!empty($info))
		{
			//获取客户端
			$sql = 'SELECT * FROM '.DB_PREFIX.'user_token WHERE id ='.$info['id'];
			$c = $this->db->query_first($sql);
			$client = $c['type_name'];
			$data = $this->share->get_mention($info['appid'], $info['plat_id'], $info['plat_token'], $info['since_id'], '', 10);
			if(!empty($data))
			{
				//print_r($data);exit();
				/*
				if ($data['error'] && $data['error']!='empty')
				{
					$this->clear_queue($info['id'],$info['since_id']);
					exit("<h3>发生错误</h3>");
				}
				*/
				if($data['error'] && $data['error']=='empty')
				{
					//无新数据
					$this->clear_queue($info['id'],$info['since_id']);
					exit("<h3>无新数据</h3>");					
				}
				if (is_array($data) && !$data['error'])
				{
					//此时存在新数据
					
					foreach ($data as $key=>$val)
					{
						if (!$since_id)
						{
							$since_id = $val['id'];
						}	
						$this->create($val['text'], $val['original_pic'], 0, $val['screen_name'], $val['created_at'], $info['plat_id'], $client, $info['con_sort'],$info['name'],$c['type']);						
					}
					
					$this->clear_queue($info['id'],$since_id);
					exit("<h3>数据获取成功</h3>");
					
				}
				if ($data['error'] && $data['sync_third_auth'])
				{
					//此时授权到期
					$sql = 'UPDATE '.DB_PREFIX.'user_token SET can_access=0 WHERE id = '.$info['id'];
					$this->db->query($sql);
					$this->clear_queue($info['id'],$info['since_id']);
					exit("<h3>授权到期</h3>");
				}
			}else {
				$this->clear_queue($info['id'],$since_id);
				exit("<h3>信息获取失败</h3>");
			}	
		}else {
			exit("<h3>没有用户</h3>");
		}
	}
	private function create($content,$pic,$user_id,$user_name,$create_time,$plat_id,$client,$sort,$name,$type)
	{
		//添加爆料主表
		$data = array(
					'title'=>'',
					'brief'=>'',
					'appid'=>$plat_id,
					'client'=>$client,
		 			'longitude'=>'',
		 			'latitude'=>'',
					'create_time'=>$create_time,
					'user_id'=>$user_id,
					'user_name'=>$user_name,
					'audit'=>1,
		 			'sort_id'=>$sort, 			 	
		);
		//去掉@用户名
		$content = ltrim($content,'@'.$name);	
		if (!$content)
		{
			return false;
		}
		if (!$data['sort_id'])
		{
			$data['sort_id'] = 0; 
		}
		if (!$data['title'])
		{
			$data['title'] = addslashes(hg_cutchars($content,20));						
		}
		if (!$data['brief'])
		{
			$data['brief'] = addslashes(hg_cutchars($content,100));
		}
		
		$contribute_id = $this->add_content($data);
		
		//添加内容表	
		
		$body = array(
			'id'=>$contribute_id,
			'text'=>addslashes($content),
		);	
		$this->add_contentbody($body);
		//图片上传
		if ($pic)
		{		
			if (is_array($pic))
			{
				foreach ($pic as $key=>$val)
				{	
					if ($type==3)
					{
						$val=$val.'/2000.jpg';
					}
					$ret = $this->material->localMaterial($val, $contribute_id);
					$ret = $ret[0]; 	
					//准备入库数据
					$arr = array(
						'content_id'=>$contribute_id,
						'mtype'=>$ret['type'],						
						'original_id'=>$ret['id'],
						'host'=>$ret['host'],
						'dir'=>$ret['dir'],
						'material_path'=>$ret['filepath'],
						'pic_name'=>$ret['filename'],
					);
					$id = $this->upload($arr);
					
					//默认第一张图片为索引图
					if (!$indexpic)
					{
						$indexpic = $this->update_indexpic($id, $contribute_id);
					}
				}
			}else {
				
				$ret = $this->material->localMaterial($pic, $contribute_id);	
				$ret = $ret[0]; 	
					//准备入库数据
				$arr = array(
						'content_id'=>$contribute_id,
						'mtype'=>$ret['type'],						
						'original_id'=>$ret['id'],
						'host'=>$ret['host'],
						'dir'=>$ret['dir'],
						'material_path'=>$ret['filepath'],
						'pic_name'=>$ret['filename'],
				);
				$id = $this->upload($arr);
					
				//默认第一张图片为索引图
				if (!$indexpic)
				{
					$indexpic = $this->update_indexpic($id, $contribute_id);
				}
			}	
		}	
		return $contribute_id;
	}
	private function add_content($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'content SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$update_sql = 'UPDATE '.DB_PREFIX.'content set order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($update_sql);
		return $id;
	}
	//添加爆料内容
	private function add_contentbody($data)	
	{
		$sql  = 'REPLACE INTO '.DB_PREFIX.'contentbody SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	//单图片上传
	private function upload($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	//更新索引图
	private function update_indexpic($mid,$cid)
	{
		$sql = 'UPDATE '.DB_PREFIX.'content SET material_id = '.$mid.' WHERE id = '.$cid;
		$this->db->query($sql);
		$pic_sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE materialid = '.$mid;
		$pic = $this->db->query_first($pic_sql);
		$url = array(
					'host'=>$pic['host'],
					'dir'=>$pic['dir'],
					'file_path'=>$pic['material_path'],
					'file_name'=>$pic['pic_name'],
					'cid'=>$cid
				);
		return $url;
	}
	private function clear_queue($id,$since_id=0)
	{
		if (!isset($id) || !isset($since_id))
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'user_token SET since_id = '.$since_id.',since_time='.TIMENOW.' WHERE id = '.$id;
		$this->db->query($sql);
		//删除队列
		$sql = 'DELETE FROM '.DB_PREFIX.'user_queue WHERE id = '.$id;
		$this->db->query($sql);
		return $id;
		
	}	
}
$out = new getContribute();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_contribute';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>
