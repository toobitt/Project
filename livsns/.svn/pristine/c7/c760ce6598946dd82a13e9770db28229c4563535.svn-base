<?php
require('./global.php');
define('MOD_UNIQUEID','road');
class rdCatUpdate extends adminUpdateBase
{
	public function __construct()
	{
		global $gGlobalConfig;
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
		
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_material']['host'], $gGlobalConfig[App_material]['dir']);
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
			$this->errorOutput('分类名称不能为空');
		}		
		$info = array(
			'title' 		   => $this->input['name'],
			'color'            => $this->input['color'],
			'create_time'  	   => TIMENOW,
			'update_time'  	   => TIMENOW,
			'ip'		       => hg_getip(),
			'user_id'          => intval($this->user['user_id']),
			'user_name'    	   => trim(urldecode($this->user['user_name'])),
			'log'		       => htmlspecialchars_decode(urldecode($this->input['log'])),
			'status'           => $this->input['status'],
 		);
 		$info['log'] = json_decode($info['log'],1);
 		$info['log'] = array(
 			'id' => $info['log'][0]['id'],
 			'host' => $info['log'][0]['host'],
 			'dir'  => $info['log'][0]['dir'],
 			'filepath'   => $info['log'][0]['filepath'],
 			'filename'   => $info['log'][0]['filename'],
 		);
 		//file_put_contents('info.txt',var_export($info,1));
 		$info['log'] = json_encode($info['log']);
		$sql = "INSERT INTO " . DB_PREFIX . "group SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v ."'";
			$space = ',';
		}
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = "UPDATE " . DB_PREFIX ."group SET order_id = " . $id ." WHERE id = " . $id;
		$this->db->query($sql);
		if($id)
		{
			$this->addLogs('添加路况分类','',$info,$info['title']);
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
			$this->errorOutput('分类名称不能为空');
		}
		$info = array(
			'title' 			=> $this->input['name'],
			'color'             => $this->input['color'],
			'log'		    	=> htmlspecialchars_decode(urldecode($this->input['log'])),
			'status'        	=> $this->input['status'],
		);
 		$info['log'] = json_decode($info['log'],1);
 		if($info['log'][0]['id']>0)
 		{
	 		$info['log'] = array(
	 			'id' => $info['log'][0]['id'],
	 			'host' => $info['log'][0]['host'],
	 			'dir'  => $info['log'][0]['dir'],
	 			'filepath'   => $info['log'][0]['filepath'],
	 			'filename'   => $info['log'][0]['filename'],
	 		);
 		}
 		else{
	 		$info['log'] = array(
	 			'id' => $info['log']['id'],
	 			'host' => $info['log']['host'],
	 			'dir'  => $info['log']['dir'],
	 			'filepath'   => $info['log']['filepath'],
	 			'filename'   => $info['log']['filename'],
	 		);
 		}
 		$info['log'] = json_encode($info['log']);
		$sql = "UPDATE " . DB_PREFIX . "group SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v ."'";
			$space = ',';
		}
		$sql .=  " WHERE id=" . intval($this->input['id']);
		$this->db->query($sql);
		$info['id'] = intval($this->input['id']);
		$this->addLogs('修改路况分类',$info,'',$info['title']);
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
		$sql = "SELECT log FROM " . DB_PREFIX ."group WHERE id IN(" . $ids . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$row['log'] = json_decode($row['log'],1);
			if($row['log'])
			{
				$material->delMaterialById($row['log'][0]['id'],2);
			}
		}
		$sql = "DELETE FROM " . DB_PREFIX . "group WHERE id IN(". $ids .")";
		$this->db->query($sql);
		$this->addLogs('删除路况分类','','','删除路况分类+' . $ids);
		$this->addItem($ids);
		$this->output();
	}
	
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('ID不能为空！');
		}
		$ids = urldecode($this->input['id']);
		$audit = intval($this->input['audit']);
		$arr_id = explode(',',$ids);
		if($audit == 1) //审核操作
		{
			$sql = "UPDATE " . DB_PREFIX ."group SET status = 1 WHERE id IN(".$ids.")";
			$this->db->query($sql);
			$opration = '审核';
			$return =  array('id' => $arr_id,'status' => 1);
		}
		else if($audit == 0) //打回操作
 		{
			$sql = "UPDATE " . DB_PREFIX ."group SET status = 2 WHERE id IN(".$ids.")";
			$this->db->query($sql);
			$opration = '打回';
			$return =  array('id' => $arr_id,'status' => 2);
		}
		$this->addLogs($opration,'','',$opration . '+' . $ids);
		$this->addItem($return);
		$this->output();
	}
	
	/*参数:video_id(圈子的id可以多个),order_id(圈子的排序id),table_name(需要排序的表名)
	 *功能:对圈子列表进行排序操作
	 *返回值:将圈子id以逗号隔开，字符串的形式返回
	 * */
	public function drag_order()
	{
		if(!$this->input['content_id'])
		{
			$this->errorOutput(NOID);
		}
		$ids       = explode(',',urldecode($this->input['video_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " .DB_PREFIX. "group  SET order_id = ".$order_ids[$k]."  WHERE id = ".$v;
			$this->db->query($sql);
		}
		$this->addLogs('拖动排序','','','拖动排序+' . $ids);
		$this->addItem($ids);
		$this->output();
	}
	
	public function upload()
	{
		if($_FILES['Filedata'])
		{			
			$typetmp = explode('.',$_FILES['Filedata']['name']);
			
			$filetype = strtolower($typetmp[count($typetmp)-1]);
//			$this->curl->setSubmitType('post');
//			$this->curl->setReturnFormat('json');
//			$this->curl->initPostData();
//    		$this->curl->addRequestData('a','check_cache');
//    		$ret = $this->curl->request("admin/cache.php");
//			//$gMaterialType = $this->cache->check_cache();
//			$gMaterialType = $ret;
//			$type = '';
//			if(!empty($gMaterialType))
//			{
//				
//				foreach($gMaterialType as $k => $v)
//				{
//					if(in_array($filetype,$v))
//					{
//						$type = $k;
//					}
//				}
//			}
//				var_dump($filetype);

//			$filetypes = array('png','jpeg','jpg','gif');
//			var_dump($typetmp);
//			var_dump(in_array($typetmp[1],$filetypes));
			//if(in_array($typetmp,$filetypes))
			$type = 'img';
			if($type!='img')
			{
				$return = array(
					'success' => false,
					'error' => '上传文件格式不正确',
				);
				return $return;
			}
				
			$material = $this->mater->addMaterial($_FILES); //插入各类服务器
			//var_dump($material);	
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
	
	public function unknow()
	{
		$this->errorOutput('方法不存在');
	}
}

$out = new rdCatUpdate();
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