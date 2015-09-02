<?php
require_once(ROOT_PATH . 'lib/class/auth.class.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
class mood_mode extends InitFrm
{
	private $auth;
	private $publishcontent;
	private $puscont;
	public function __construct()
	{
		parent::__construct();
		$this->auth = new Auth();
		$this->publishcontent = new publishcontent();
		$this->puscont = new publishcontent();	
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "mood  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$mood_style = $this->get_all_styles();
		$apps_arr = $this->get_app();
		$modules_arr = $this->get_moudle();
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time']  = date('Y-m-d H:i:s',$r['create_time']);
			$r['app_uniqueid']  = $apps_arr[$r['app_uniqueid']] ? $apps_arr[$r['app_uniqueid']] : $r['app_uniqueid'];
			$r['module_uniqueid'] = $modules_arr[$r['module_uniqueid']] ? $modules_arr[$r['module_uniqueid']] : $r['module_uniqueid'];
			$r['mood_style_name'] = $mood_style[$r['mood_style']]['name'];
			$r['mood_style_picture'] = $mood_style[$r['mood_style']]['index_picture'];
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "mood SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."mood SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($rids)
	{
		if(!$rids)
		{
			return false;
		}
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "mood SET counts = counts + 1  WHERE rid = '"  .$rids. "'";
		$this->db->query($sql);
		$sql = " SELECT * FROM ".DB_PREFIX."mood WHERE rid = '"  .$rids. "'";
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "mood  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		$mood_style = $this->get_all_styles();
		$apps_arr = $this->get_app();
		$modules_arr = $this->get_moudle();
		$info['create_time'] = date('Y-m-d H:i:s',$info['create_time']);
		$info['app_uniqueid']  = $apps_arr[$info['app_uniqueid']];
		$info['module_uniqueid'] = $modules_arr[$info['module_uniqueid']];
		$info['mood_style_name'] = $mood_style[$info['mood_style']]['name'];
		$info['mood_style_picture'] = $mood_style[$info['mood_style']]['index_picture'];
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "mood WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "mood WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "mood WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $id;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "mood WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "mood SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
		
	public function update_mood($data = array(),$condition)
	{
		if(!$data)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "mood WHERE 1 ".$condition;
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "mood SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE 1 ".$condition;
		$this->db->query($sql);
		
		$data['id'] = $pre_data['id'];
		return $data;
	}
	
	public function get_all_styles()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "mood_style  WHERE 1 ";
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['index_picture'])
			{
				$r['index_picture'] = @unserialize($r['index_picture']);
				$r['index_picture'] = hg_material_link($r['index_picture']['host'],$r['index_picture']['dir'],$r['index_picture']['filepath'],$r['index_picture']['filename']);
			}
			$info[$r['id']] = $r;
		}
		return $info;		
	}
	
	/**
	 * 获取某一样式下的所有心情
	 * Enter description here ...
	 * @param unknown_type $style_id 样式的id
	 */
	public function get_mood($style_id = '')
	{
		if($style_id)
		{
			$condition .= "AND style_id = ".$style_id;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "mood_option  WHERE 1 " . $condition;
		$query = $this->db->query($sql);
		while($r = $this->db->fetch_array($query))
		{
			if($r['picture'])
			{
				$r['picture'] = @unserialize($r['picture']);
			}
			$mood[$r['id']] = array(
			    'id'         => $r['id'],
			    'mood_name'  => $r['mood_name'],
			    'picture' => hg_material_link($r['picture']['host'],$r['picture']['dir'],$r['picture']['filepath'],$r['picture']['filename']),
			);
		}
		return $mood;
	}
	
	public function get_app()
	{
		$apps = $this->auth->get_app('bundle,name');
		if(is_array($apps))
		{
			foreach($apps as $k=>$v)
			{
				$apps_arr[$v['bundle']] = $v['name'];
			}
		}
		return $apps_arr;
	}
	
	public function get_moudle()
	{
		$modules = $this->auth->get_module('mod_uniqueid,name');
		if(is_array($modules))
		{
			foreach($modules as $k=>$v)
			{
				$modules_arr[$v['mod_uniqueid']] = $v['name'];
			}
		}
		return $modules_arr;
	}
	
	/**
	 * 
	 * 更新栏目id
	 * @param $id 内容id
	 * @param $column_id 更新的栏目id
	 */
	public function update_column($id, $column_id)
	{
		if(!$id)
		{
			return false;
		}
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "mood SET column_id = " . $column_id . "  WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $id;
		
	}
	
	/**
	 * 获取心情的投票结果
	 * @param $condition 内容在库中的id
	 * @param $mood_style 心情样式
	 */
	public function get_mood_result($condition,$mood_style)
	{
		$mood = $this->get_mood($mood_style);    //通过样式id获取该样式的所有心情
		//获取该样式下每个心情的点击数据
		$sql = "SELECT * FROM " . DB_PREFIX . "mood_count  WHERE 1 " . $condition . $orderby ;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$info[$r['mood_id']] = $r;
		}
		
		$ret['result'] = array();
		$total_count = 0;
		if(is_array($mood))
		{
			foreach ($mood as $k=>$v)
			{
				$result[] = array(
				    'mood_id'   => $v['id'],
				    'mood_name' => $v['mood_name'],
				    'mood_picture'   => $v['picture'],
				    'counts'    => $info[$k]['counts'] ? $info[$k]['counts'] : 0,
				);
				$total_count = $total_count + $info[$k]['counts'];  //获取总的点击量
			}
		}
		$ret['result'] = $result;
		$ret['total_count'] = $total_count;
		return $ret;
	}
	
	public function get_publishcontent($rids)
	{
		$ret = $this->publishcontent->get_content_by_rids($rids);
		$return = $this->puscont->get_pub_content_type();
		if(!$ret)
		{
			return false;
		}
		if(is_array($return))
		{
			foreach($return as $k => $v)
			{
				$bundles[$v['bundle']] = $v['name'];
			}
		}
		
		if(is_array($ret))
		{
			foreach($ret as $k => $v)
			{
				$content[$v['id']] = array(
					'title'=> $v['title'],
					'app_uniqueid' => $v['bundle_id'],
					'module_uniqueid' => $v['module_id'],
					'site_id'     => $v['site_id'],
					'column_id' => $v['column_id'],
					'column_name' => $v['column_name'],
					'module_name' => $bundles[$v['bundle_id']],
					'cid'         => $v['content_fromid'],
					'create_time' => $v['create_time'],
					'create_user'   => $v['create_user'],
				    'content_url'   => $v['content_url'],
				    'indexpic'    => $v['indexpic'],
				    'is_indexpic' => $v['is_have_indexpic'],
				    'subtitle'    => $v['subtitle'],
				    'brief'       => $v['brief'],
				    'keywords'    => $v['keywords'],
				    'publish_time'=> $v['publish_time'],
				    'create_time' => $v['create_time'],
				    'author'      => $v['author'],
				    'tcolor'      => $v['tcolor'],
				    'isbold'      => $v['isbold'],
				    'isitalic'    => $v['isitalic'],
				);
			}
		}
		if(!$content)
		{
			return false;
		}
		return $content;
	}
	
}
?>