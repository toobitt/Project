<?php
require('./global.php');
define('MOD_UNIQUEID', 'wbcircle');
class wbCircleUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function sort(){}
	public function publish(){}
	public function create()
	{
		if(empty($this->input['name']))
		{
			$this->errorOutput('圈子名称不能为空');
		}		
		$info = array(
			'name' 		   => $this->input['name'],
			'description'  => $this->input['description'],
			'create_time'  => TIMENOW,
			'update_time'  => TIMENOW,
			'ip'		   => hg_getip(),
			'user_id'      => intval($this->user['user_id']),
			'user_name'    => trim(urldecode($this->user['user_name'])),
			'log'		   => htmlspecialchars_decode(urldecode($this->input['log'])),
		);
		$sql = "INSERT INTO " . DB_PREFIX . "circle SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v ."'";
			$space = ',';
		}
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = "UPDATE " . DB_PREFIX ."circle SET order_id = " . $id ." WHERE id = " . $id;
		$this->db->query($sql);
		if($id)
		{
			$this->addLogs('添加微博圈','',$info,$info['name']);		
			$this->addItem($id);
			$this->output();
		}
		$this->errorOutput('添加失败');		
	}
	
	
	public function update()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');	
		}
		if(empty($this->input['name']))
		{
			$this->errorOutput('圈子名称不能为空');
		}
		$info = array(
			'name' 			=> $this->input['name'],
			'description'   => $this->input['description'],
			'log'		    => htmlspecialchars_decode(urldecode($this->input['log'])),
		);
		$sql = "UPDATE " . DB_PREFIX . "circle SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v ."'";
			$space = ',';
		}
		$sql .=  " where id=" . intval($this->input['id']);
		$this->db->query($sql);
		if($this->db->affected_rows() > 0)
		{
			$update_info = array(
				'update_time'  => TIMENOW,
			);
			$sql = "UPDATE " . DB_PREFIX . "circle SET ";
			$space = '';
			foreach($info as $k => $v)
			{
				$sql .= $space . $k . "='" . $v ."'";
				$space = ',';
			}
			$sql .=  " WHERE id=" . intval($this->input['id']);
			$this->db->query($sql);				
		}		
		
		$info['id'] = intval($this->input['id']);
		
		//更改溶于数据
		$sql = "SELECT user_id FROM " . DB_PREFIX ."user_circle WHERE circle_id = " . $info['id'];
		$ret = $this->db->query($sql);
		$user_id = array();
		while($row = $this->db->fetch_array($ret))
		{
			$user_id[] = $row['user_id'];
		}
		$user_id = $user_id ? implode(',',$user_id) : $user_id;
		$this->update_user($user_id,$info['id'],$info['name']);
		$this->addLogs('修改微博圈','',$info,$info['name']);	
		$this->addItem($info);
		$this->output();
	}
	
	
	public function delete()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput("ID不能为空");
		}
		$ids = urldecode($this->input['id']);
		
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$material = new material();
		//删除圈子log
		$sql = "SELECT log FROM " . DB_PREFIX ."circle WHERE id IN(" . $ids . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$row['log'] = json_decode($row['log'],1);
			if($row['log'])
			{
				$material->delMaterialById($row['log'][0]['id'],2);
			}
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "circle WHERE id IN(". $ids .")";
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX ."weibo_circle WHERE circle_id IN(" . $ids . ")";
		$this->db->query($sql);
		
		//修改用户表中的溶于数据
		$sql = "SELECT user_id FROM " . DB_PREFIX ."user_circle WHERE circle_id IN(" . $ids . ")";
		$ret = $this->db->query($sql);
		$user_id = array();
		while($row = $this->db->fetch_array($ret))
		{
			$user_id[] = $row['user_id'];
		}
		$user_id = $user_id && is_array($user_id) ? implode(',',$user_id) : $user_id;
		$this->update_user($user_id,$ids);
		
		$sql = "DELETE FROM " . DB_PREFIX ."user_circle WHERE circle_id IN(" . $ids . ")";
		$this->db->query($sql);
		$this->addLogs('删除微博圈','','','删除微博圈+' . $ids);	
		$this->addItem($ids);
		$this->output();
	}
	
	
	public function audit()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空'); 
		}
		$id = urldecode($this->input['id']);
		$audit = intval($this->input['audit']);
		$arr_id = explode(',',$id);
		if($audit == 1) //审核操作
		{		
			$sql = "UPDATE " . DB_PREFIX ."circle SET status = 1 WHERE id IN(" . $id . ")";
			$this->db->query($sql);
			$opration = '审核';
			$arr = array('status' => 1,'id'=> $arr_id);
		}
		else if($audit == 0)  //打回
		{
			$sql = "UPDATE " . DB_PREFIX ."circle SET status = 2 WHERE id IN(" . $id . ")";
			$this->db->query($sql);
			$opration = '打回';
			$arr = array('status' => 2, 'id' => $arr_id);			
		}
		$this->addLogs($opration, '', '', $opration .'+'. $id);	
		$this->addItem($arr);
		$this->output();
	}
	
	
	/*参数:video_id(圈子的id可以多个),order_id(圈子的排序id),table_name(需要排序的表名)
	 *功能:对圈子列表进行排序操作
	 *返回值:将圈子id以逗号隔开，字符串的形式返回
	 * */
	public function drag_order()
	{
	    parent::drag_order('circle','order_id');   
		$this->addLogs('微博圈排序', '', '',  '微博圈排序+'. $ids);	
		$this->addItem($ids);
		$this->output();
	}
	
	public function upload()
	{
		if($_FILES['Filedata'])
		{			
			$typetmp = explode('.',$_FILES['Filedata']['name']);
			$filetype = strtolower($typetmp[count($typetmp)-1]);
			$gMaterialType = $this->mater->check_cache();
			$type = '';
			if(!empty($gMaterialType))
			{
				foreach($gMaterialType as $k => $v)
				{
					if(in_array($filetype,$v))
					{
						$type = $k;
					}
				}
			}
				
			if($type!='img')
			{
				$return = array(
					'success' => false,
					'error' => '上传文件格式不正确',
				);
				return $return;
			}
				
			$material = $this->mater->addMaterial($_FILES); //插入各类服务器
				
			if(!empty($material))
			{
				$material['success'] = true;
			    $return = $material;
			}
			else
			{
				$return = array(
					'success' => false,
					'error' => '文件上传失败',
				);
			}			
		}
		else 
		{
			$return = array(
				'success' => false,
				'error' => '文件上传失败',
			);
		}
		$this->addItem($return);	
		$this->output();	
	}
	
	
	public function update_user($user_id, $circle_id, $circle_name = '')
	{
		$user_id = is_array($user_id) ? implode(',',$user_id) : $user_id;
		$circle_id = !is_array($circle_id) && $circle_id ? explode(',',$circle_id) : $circle_id;
		$circle_name = !is_array($circle_name) && $circle_name ? explode(',',$circle_name) : $circle_name;
		if(!$user_id) return false;
		if(empty($circle_id)) return false;
		$sql = "SELECT id,circle_id FROM " . DB_PREFIX ."user WHERE id IN(" . $user_id . ")";
		$ret = $this->db->query($sql);	
		while($row = $this->db->fetch_array($ret))
		{
			$row['circle_id'] = unserialize($row['circle_id']);
			foreach($circle_id as $k => $v)
			{
				if($row['circle_id'][$v])
				{
					if($circle_name[$k])
					{
						$row['circle_id'][$v] = $circle_name[$k];
					}
					else
					{
						unset($row['circle_id'][$v]);
					}
					$sql = "UPDATE " .DB_PREFIX ."user SET circle_id = '".serialize($row['circle_id'])."' WHERE id = " . $row['id'];
					$this->db->query($sql);
				}
			}
		}
		return true;
	}
	
	public function unknow()
	{
		$this->errorOutput('方法不存在');
	}
}

$out = new wbCircleUpdate();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 

?>