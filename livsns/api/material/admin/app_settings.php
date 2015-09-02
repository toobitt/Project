<?php
require('global.php');
define('MOD_UNIQUEID','configuare');
class AppSettings extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function detail(){}
	public function count(){}	

	public function show()
	{
        $father = $this->get_father();
		$sql = "SELECT * FROM " . DB_PREFIX ."app WHERE id = " . $father;
		$info = $this->db->query_first($sql);
		$sql = "SELECT * FROM " . DB_PREFIX . "app WHERE father !=0 AND father =" . $father;
		$q = $this->db->query($sql);
		if($this->db->num_rows($q))
		{
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				$row['father_bundle'] = $info['bundle'];
				$ret[] = $row;
			}
			$this->addItem($ret);
			$this->output();
		}
	}

	public function get_father()
	{
		$father = 0;
		if($this->input['_id'])
		{
			$father = intval($this->input['_id']);
		}
		return $father;
	}


    //初始化应用settings
	public function init_app_settings()
	{
		if(empty($this->input['data']))
		{
			$this->errorOutput('请传入数据');
		}
	    $data = unserialize(urldecode($this->input['data']));
		$app_name = $data['app_name'];
		$module_name = $data['module_name'];
		$bundle_id = $data['bundle_id'];
		$module_id = $data['module_id'];
		$sql = "SELECT * FROM " . DB_PREFIX ."app where bundle ='" . $bundle_id ."'"; 
		$q = $this->db->query_first($sql);
		if(empty($q))
		{
			$sql ="INSERT INTO " . DB_PREFIX ."app(name,bundle,father) values('" . $app_name . "','" . $bundle_id. "',0)";
			$this->db->query($sql);
			$father = $this->db->insert_id();
			$sql = "SELECT * FROM " . DB_PREFIX ."app where bundle ='" . $module_id ."'";
			$r = $this->db->query_first($sql);
			if(empty($r))
			{
				$sql ="INSERT INTO " . DB_PREFIX ."app(name,bundle,father) values('" . $module_name . "','" . $module_id . "'," . $father . ")";
				$this->db->query($sql);
			}
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."app where bundle ='" . $module_id ."'";
			$r = $this->db->query_first($sql);
			if(empty($r))
			{
				$sql ="INSERT INTO " . DB_PREFIX ."app(name,bundle,father) values('" . $module_name . "','" . $module_id . "'," . $q['id'] . ")";
				$this->db->query($sql);
			}
		}

		$this->addItem('sucess');
		$this->output();
	}
     
	 /**
	 *	取所有的应用
	 */
	public function app_bundle()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "app WHERE father = 0";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$ret[] = $row;
		}
		foreach($ret as $k => $v)
		{
			  $m = array('id'=>$v['id'],'name'=>$v['name'],'fid'=>0,'depth'=>0,'is_last'=>1);
			  $this->addItem($m);
		}
		$this->output();
	}

	public function get_settings()
	{
		if(empty($this->input['bundle_id']))
		{
			$this->errorOutput('请传入应用标识');
		}

	    if(empty($this->input['module_id']))
		{
			$this->errorOutput('请传入模块标识');
		}
		$app_bundle = urldecode($this->input['bundle_id']);
		$module_bundle = urldecode($this->input['module_id']);
		
		$sql = "select * from " . DB_PREFIX . "settings where type !=1";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($row['type'] !=2)
			{
				$row['value'] = var_export(unserialize($row['value']),1);
			}
			$row['bundle_id'] = $app_bundle;
			$row['module_id'] = $module_bundle;
			$ret[$row['var_name']] = $row;
			$var_name[] = "'" . $row['var_name'] ."'";
		}

		$var_name = implode(',',$var_name);
		
		$sql = "SELECT * FROM " .	 DB_PREFIX . "app_settings WHERE bundle_id = '" . $app_bundle . "' AND module_id ='" . $module_bundle ."'";
		$q = $this->db->query($sql);
		
		if(!$this->db->num_rows($q))
		{
			$sql ="SELECT * FROM " . DB_PREFIX . "water_material WHERE  mid ='" . $module_bundle . "' AND catid = 0 AND cid = 0";
			$r = $this->db->query_first($sql);
			$gMaterialWater = $this->settings['material_water'];
			if($r['filename'])
			{
				if($r['father_dir'])
				{
					$r['filename'] = $gMaterialWater['host'] . $gMaterialWater['dir'] . $r['father_dir'] . '/' . $r['filename'];
				}
				$r['filename'] = $gMaterialWater['host'] . $gMaterialWater['dir'] . $r['filename']; 
			}
			$ret['water'] = $r;

			$this->addItem($ret);
			$this->output();
		}
		else
		{		
			while($row = $this->db->fetch_array($q))
			{
				if($row['type'] != 2)
				{
					$row['value'] = var_export(unserialize($row['value']),1);
				}
				if($ret[$row['var_name']])
				{
					$ret[$row['var_name']]= $row;
				}
			}

			$sql ="SELECT * FROM " . DB_PREFIX . "water_material WHERE  mid ='" . $module_bundle . "' AND catid = 0 AND cid = 0";
			$r = $this->db->query_first($sql);
			$gMaterialWater = $this->settings['material_water'];
			if($r['filename'])
			{
				if($r['father_dir'])
				{
					$r['filename'] = $gMaterialWater['host'] . $gMaterialWater['dir'] . $r['father_dir'] . '/' . $r['filename'];
				}
				$r['filename'] = $gMaterialWater['host'] . $gMaterialWater['dir'] . $r['filename']; 
			}
			$ret['water'] = $r;
			$this->addItem($ret);
			$this->output();
		}
	}

	/**
	*  当应用下没有安装模块时直接取该应用的配置
	*  
	*  @param app_bundle string 应用标识
	*  @return $array array 配置信息
	*/
	public function get_app_settings($app_bundle)
	{
		$sql = "SELECT * FROM " .	 DB_PREFIX . "app_settings WHERE bundle_id = '" . $app_bundle . "' AND module_id = ' '";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($row['type'] != 2)
			{
				$row['value'] = var_export(unserialize($row['value']),1);
			}
			$ret[$row['var_name']]= $row;
		}
		$sql ="SELECT * FROM " . DB_PREFIX . "water_material WHERE bundle_id ='" . $app_bundle . "' AND mid = '' AND catid = 0 AND cid = 0";
		$r = $this->db->query_first($sql);
		$gMaterialWater = $this->settings['material_water'];
		if($r['filename'])
		{
			if($r['father_dir'])
			{
				$r['filename'] = $gMaterialWater['host'] . $gMaterialWater['dir'] . $r['father_dir'] . '/' . $r['filename'];
			}
			$r['filename'] = $gMaterialWater['host'] . $gMaterialWater['dir'] . $r['filename']; 
		}
		$ret['water'] = $r;
		$this->addItem($ret);
		$this->output();
	}

	public function update()
	{
		$arr_key = $this->input['st_key'];
		$arr_bundle_id = $this->input['st_bundle_id'];
		$arr_module_id = $this->input['st_module_id'];
		$arr_val = $this->input['st_val'];
		$arr_type = $this->input['st_type'];
		if(!empty($arr_key))
		{
			foreach($arr_key as $k => $v)
			{
				 //删除
				 $sql = "delete from " . DB_PREFIX . "app_settings where bundle_id = '" . $arr_bundle_id[$k] ."' and module_id = '" . $arr_module_id[$k] . "' and var_name ='" . $arr_key[$k] ."'";
				 $this->db->query($sql);
				 //插入
				 $sql = '';
				 $sql ="insert into " . DB_PREFIX ."app_settings set ";
				 $sql .= "bundle_id = '" . urldecode($arr_bundle_id[$k]) ."',";
				 $sql .= "module_id = '" . urldecode($arr_module_id[$k]) . "',";
				 $sql .= "var_name = '" . urldecode($arr_key[$k]) ."',";
				 $sql .= "type = " . $arr_type[$k] .",";
				 if($arr_type[$k] == 2)
				 {
					 $sql .= "value = '" . $arr_val[$k] . "',";
				 }
				 else
				 {   
					 $val = htmlspecialchars_decode(urldecode($arr_val[$k]),ENT_QUOTES);
					 eval('$val = ' . $val . ';');
					 $sql .= "value = '" . serialize($val) . "',";
				 }
				 $sql .=" is_edit = 1 ,";
				 $sql .=" is_open = 1 ";
				 $this->db->query($sql);
			}
		}
		$this->addItem('success');
		$this->output();
	}

    
	public function water_settings_update()
	{
		if(empty($this->input['module_id']))
		{
			$this->errorOutput("传入模块标识");
		}
		$module_id = urldecode($this->input['module_id']);

        $sql = "SELECT * FROM " . DB_PREFIX . "water_material  WHERE  mid='" . $module_id ."' and catid = 0 and cid = 0 ";
		$q = $this -> db->query($sql);
		if(!$this->db->num_rows($q))
		{
			$sql ="INSERT INTO " . DB_PREFIX . "water_material SET ";
		}
		else
		{
			$sql = "UPDATE " . DB_PREFIX . "water_material SET ";
		}
		if($this->input['image_type']) //图片水印
		{
			$sql .= " width ='" . intval($this->input['width']) ."'," . 
					" height ='" . intval($this->input['height']) ."'," .
					" position ='" . intval($this->input['get_photo_waterpos']) ."'," .
					" water_type =1," . 
					" father_dir = 'module'," . 
					" filename = '" . urldecode($this->input['water_filename']) ."'," . 
					" opacity ='" . urldecode($this->input['opacity']) ."'";
		}
		else //文字水印
		{
			$sql .= " water_text = '" . urldecode($this->input['water_text']) . "',".
			 		" water_font = '" . intval($this->input['water_font']) . "'," .
			 		" water_angle = '" . intval($this->input['water_angle']) . "'," .
					" position = '" . intval($this->input['get_photo_waterpos']) . "'," .
					" water_type = 0,".
			 		" water_color = '" . urldecode($this->input['water_color']) . "'";
		}
		if($this->db->num_rows($q))
		{
			$sql .= " WHERE mid ='" . $module_id . "' and catid = 0 and cid =0 ";
		}
		else
		{
			$sql .= " ,mid = '" . $module_id . "'," .
				       " catid =0," .
					   " cid =0" ;
		}

		//file_put_contents('11.php',$sql);
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}

	public function water_upload()
	{
		$typetmp = explode('.',$_FILES['Filedata']['name']);
		$filetype = $typetmp[count($typetmp)-1];
		$gMaterialWater = $this->settings['material_water'];
		$return = array(
			'filename' => date('YmdHis') . hg_generate_user_salt(4) . '.' . $filetype,		
		);
		$path = hg_getimg_default_dir() . $gMaterialWater['dir'] . 'module/' ;
		if(hg_mkdir($path))
		{
			if(move_uploaded_file($_FILES["Filedata"]["tmp_name"],$path . $return['filename']))
			{
				$this->settings['default_size']['other_dir'] = 1;
				$imgsize = getimagesize($path . $return['filename']);
				hg_mk_images($path . $return['filename'], $return['filename'] , $path, $this->settings['default_size']);
				$return['img_path'] =  hg_material_link($gMaterialWater['host'],$gMaterialWater['dir'],'module/',$return['filename']);
				return $return;
			}
		}
	}
}

//execute
$out = new AppSettings();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'get_father';
}
$out->$action();

?>