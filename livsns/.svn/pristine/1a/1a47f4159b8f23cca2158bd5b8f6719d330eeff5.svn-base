<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: water.class.php 6434 2012-04-17 06:18:14Z wangleyuan $
***************************************************************************/
class water extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($cond = '')
    {
		$sql="SELECT * FROM " . DB_PREFIX . "water_config where 1 " . $cond;
		$r=$this->db->query($sql);
		$info = array();
		$this->settings['default_size']['other_dir'] = 1;
		while($row = $this->db->fetch_array($r))
		{
			if($row['type'] == 1)
			{
				$row['img_url'] = $row['filename'] ? hg_getimg_default_host() . WATER_PATH . $row['filename'] . "?" . hg_generate_user_salt(5) :'';
			}
			$row['create_time'] = date('Y-m-d H:i',$row['create_time']);
			$row['update_time'] = date('Y-m-d H:i',$row['update_time']);
			$info[$row['id']] = $row;
		}
		return $info;
	}

	public function show_water_config()
	{
		$sql = "select id,config_name from " . DB_PREFIX ."water_config";
		$ret = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($ret))
		{
			$info[$row['id']] = $row;
		}
		return $info;
	}

	public function count($cond)
	{
		$sql="select count(*) as total from " . DB_PREFIX . "water_config where 1 " . $cond;
		$f = $this->db->query_first($sql);
		return $f;
	}

//using
	public function create($data)
	{
		if(!$data)
		{
			return false;	
		}
		$sql = "INSERT " . DB_PREFIX ."water_config SET ";
		$space ='';
		foreach($data as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space=',';
		}
		return $this->db->query($sql);
	}

	public function update($water, $id)
	{
		$sql = "UPDATE " . DB_PREFIX ."water_config SET ";
		$space ='';
		foreach($water as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .= " WHERE 1 AND id = " . $id;
		$this->db->query($sql);	
		return $this->db->affected_rows();	
	}

	public function delete($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "water_config WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			if(file_exists(hg_getimg_default_dir() . WATER_PATH . $row['filename']))
			{
				hg_unlink_file(hg_getimg_default_dir() . WATER_PATH,$row['filename']);//删除水印
			}
		}
		$sql = "DELETE FROM " . DB_PREFIX . "water_config WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		hg_unlink_file(CACHE_DIR,'.water.cache.php');  //删除水印缓存文件
		return $id;
	}

	public function detail($cond)
	{
		if(empty($cond))
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "water_config WHERE 1 " . $cond;
		$info = $this->db->query_first($sql);
		$this->settings['default_size']['other_dir'] = 1;
		if(!empty($info))
		{
			$info['url'] = $info['filename'] ? hg_getimg_default_host() . WATER_PATH . $info['filename'] . "?" . hg_generate_user_salt(5) : ''; //
			$info['small_url'] = hg_material_link(hg_getimg_default_host(),WATER_PATH,'',$info['filename'],$this->settings['default_size']['label']);
			$info['path'] = hg_getimg_default_host() . WATER_PATH ;
			return $info;
		}
		else
		{
			return false;
		}
	}

	public function audit()
	{
		$id = urldecode($this->input['id']); 
		$sql = "UPDATE " . DB_PREFIX . "water_config SET status=1 where id IN(" . $id . ")";
		$this->db->query($sql);
		return array('status' => 1,'id'=> $id,'pubstatus'=> 1);
	}

	public function waterSystem()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "water_config where type=1";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['url'] = hg_getimg_default_host() . WATER_PATH . $row['filename'] . "?" . hg_generate_user_salt(5); //
			$row['small_url'] = hg_material_link(hg_getimg_default_host(),WATER_PATH,'',$row['filename'],$this->settings['default_size']['label']);
			$row['path'] = hg_getimg_default_host() . WATER_PATH;
			$row['dir'] = '';
			$info[]=$row;
		}
		return $info;	
	}

	public function water_upload()
	{
		$typetmp = explode('.',$_FILES['Filedata']['name']);
		$filetype = strtolower($typetmp[count($typetmp)-1]);
		//验证文印图片格式
		include_once(CUR_CONF_PATH . 'lib/cache.class.php');
		$this->cache = new cache;
		$material_type = $this->cache->check_cache('material_type.cache.php');
		$type = '';
		if(!empty($material_type))
		{
			foreach($material_type as $k => $v)
			{
				if(in_array($filetype,array_keys($v)))
				{
					$type = $k;
				}
			}
		}						
		if($type != 'img')
		{
			return false;
		}		
		$filename = date('YmdHis').hg_generate_user_salt(4).'.'.$filetype;
		//上传到临时目录
		$path = hg_getimg_default_dir() . MATERIAL_TMP_PATH;
		if(!hg_mkdir($path))
		{
			return false;
		}
		else
		{
			if(!move_uploaded_file($_FILES["Filedata"]["tmp_name"], $path . $filename))
			{
				return false;
			}
			else
			{
				$info['filename'] = $filename;
				$info['path'] = MATERIAL_TMP_PATH;
				$info['url'] = hg_material_link(hg_getimg_default_host(), MATERIAL_TMP_PATH, '', $filename);
				return $info;
			}
		}
	}
	
	public function update_water_nodefault($default_id)
	{
		$sql = "UPDATE ".DB_PREFIX."water_config SET global_default = 0 WHERE id != " . $default_id;
		$this->db->query($sql);		
		return true;		
	}

}

?>