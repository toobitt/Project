<?php

class material extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

//nousing
	public function delMaterialByCid($id,$app_bundle)
	{
		if(!$id || !$app_bundle)
		{
			return false;
		}
		$sql = "UPDATE ".DB_PREFIX."material SET nums = nums - 1 WHERE cid IN(" . $id . ")";
		$this->db->query($sql);
		$sql = "SELECT isdel,filepath,filename, bs FROM " . DB_PREFIX . "material
				WHERE cid IN(" . $id . ") AND nums <= 0 "; 
		$q = $this->db->query($sql);
		while(false != ($row = $this->db->fetch_array($q)))
		{
			if(empty($row['isdel']))
			{
				$row['filename'] = 'del_' . $row['filename'];
			}
			hg_delete_material(hg_getimg_dir($row['bs']) . app_to_dir($app_bundle) . $row['filepath'],$row['filename']);//删除附件
		}
		$sql="DELETE FROM " . DB_PREFIX . "material WHERE cid IN(" . $id . ") AND nums <=0 ";
		$this->db->query($sql);
		return $id;
	}

//using
	public function delMaterialByMid($id,$app_bundle)
	{
		if(!$id)
		{
			return false;
		}
		$sql = "UPDATE ".DB_PREFIX."material SET nums = nums-1 WHERE id IN(".$id.")";
		$this->db->query($sql);
		$sql = "SELECT isdel,filepath,filename, bs FROM " . DB_PREFIX . "material
				WHERE id IN(" . $id . ") AND nums <=0 "; //
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			hg_delete_material(hg_getimg_dir($row['bs']) . app_to_dir($app_bundle) . $row['filepath'],$row['filename']);//删除附件
		}
		$sql = "DELETE FROM " . DB_PREFIX . "material WHERE id IN(" . $id . ") AND nums <=0 ";
		$this->db->query($sql);
		return $id;
	}

//using
	public function delMaterialByUrl($url)
	{
		if(!$url)
		{
			return false;
		}
		preg_match_all('/^(.*?)(material\/.*?\/img\/)(\d{0,4}\/\d{0,2}\/)(.*?)(\.[a-zA-Z]*)/i',$url,$out);
		$info = array(
			'host' => $out[1][0],// 
			'dir' =>  $out[2][0],// 
			'filepath' => $out[3][0] ,// 2012/05/
			'filename' => $out[4][0] ,// 20120529143609jSG7
			'type' => $out[5][0],//.jpg
		);
		hg_editTrue_material(hg_getimg_dir($info["host"], "host") . $info['dir'] . $info['filepath'],$info['filename'].$info['type']);
		hg_delete_material(hg_getimg_dir($info["host"], "host") . $info['dir'] . $info['filepath'],$info['filename'].$info['type']);
		return true;
	}

//using
	public function deleteMaterialState($id,$app_bundle)
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "SELECT bundle_id, filepath, filename, bs FROM " . DB_PREFIX . "material WHERE id IN(" . $id . ")";
		$q = $this->db->query($sql);
		while(false != ($row = $this->db->fetch_array($q)))
		{
			//改文件名，源文件的，然后根据缩略图的那个文件也改掉地址！！！
			hg_editFalse_material(hg_getimg_dir($row["bs"]) . app_to_dir($row['bundle_id']) . $row['filepath'],$row['filename']);
		}
		$sql = "UPDATE " . DB_PREFIX . "material SET isdel=0 WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		return true;
	}

//using
	public function recoverMaterialState($id,$app_bundle)
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "SELECT bundle_id, filepath, filename, bs FROM " . DB_PREFIX . "material WHERE id IN(" . $id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			//改文件名，源文件的，然后根据缩略图的那个文件也改掉地址！！！
			hg_editTrue_material(hg_getimg_dir($row["bs"]) . app_to_dir($row['bundle_id']) . $row['filepath'],$row['filename']);
		}
		$sql = "UPDATE " . DB_PREFIX . "material SET isdel=1 WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		return true;
	}

//using
	public function localMaterial($url, $cid, $catid, $app_bundle, $material_bundle,$abs_path='',$special_type = '')
	{
		if(empty($url))
		{
			return false;
		}
		$url_arr = explode(',',$url);
		if(empty($url_arr))
		{
			return false;
		}		
		$ret = array();
		$time = TIMENOW;
		foreach($url_arr as $key => $value)
		{
			if($abs_path)
			{
				ob_start(); 
				@readfile($value); 
				$img = ob_get_contents(); 
				ob_end_clean();
			}
			else
			{
				$curl = curl_init(); 
				curl_setopt($curl, CURLOPT_URL, $value);
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 60);
				$img = curl_exec($curl);
                $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				curl_close($curl);				
			}

			if (empty($img) || (!$abs_path && $http_status_code != 200))
			{
				continue;
			}
			$info = array();
			//扩展名
			$str = strstr($value,'?');
			$new_value = $value;
			if($str)
			{
				$new_value = str_replace($str,'',$value);
			}
			$filename = explode('/',$new_value);
			$filename = $filename[count($filename)-1];
			if(strpos($filename,'.'))
			{
				$exttmp = explode('.',$filename);
				$info['type'] = strtolower($exttmp[count($exttmp)-1]);				
			}
			else
			{
				$info['type'] = 'jpg';
			}
			include_once(CUR_CONF_PATH . 'lib/cache.class.php');
			$this->cache = new cache;			
			$gMaterialTpye = $this->cache->check_cache('material_type.cache.php');
			$type = '';
			foreach($gMaterialTpye as $k => $v)
			{
				if(in_array($info['type'],array_keys($v)))
				{
					$type = $k;
				}
			}
			//增加特殊图片后缀的判断
			if($special_type && $type == '')
			{
				$type = 'img';
			}
			$info['mark'] = $type;				
			if(empty($info['mark']))
			{
				continue;
			}
			$info['cid'] = $cid;
			$info['catid'] = $catid;
			$info['bundle_id'] = $app_bundle;
			$info['mid'] = $material_bundle;
			$info['user_id'] = intval($this->input['user_id']);
			$info['user_name'] = urldecode($this->input['user_name']);
			$info['client_id'] = intval($this->input['client_id']);
			$info['client_name'] = urldecode($this->input['client_name']);
			$info['remote_url'] = $value;
									
			//采集后存放的路径
			$info['filepath'] = date('Y') . '/' . date('m') . '/';
			//$info['name'] = $info['filename'] = date('YmdHis') . hg_generate_user_salt(4) . '.' . $info['type'];

            $info['name'] = changeU($filename);
            $info['filename'] = md5($value . $time) . '.' . $info['type'];
			$desdir = hg_getimg_dir() . app_to_dir($app_bundle,$info['mark']) . $info['filepath'];
			$local_filename = $desdir . $info['filename'] ; //新路径
			$local_url = hg_getimg_host() . app_to_dir($app_bundle,$info['mark']) . $info['filepath'] . $info['filename'] ; //本地的访问路径
			
			//本地化
			hg_mkdir($desdir); //判断目录是否存在 并生成
			$fp2 = @fopen($local_filename, "a"); 
			fwrite($fp2,$img); 
			fclose($fp2);
		    //如果附件为img图片类型的则 获取水印设置
		    if($info['mark'] == 'img')
		    {
		   		$tmp_img = getimagesize($local_filename);
				if(!empty($tmp_img))
				{
					$info['imgwidth'] = $tmp_img[0];
					$info['imgheight'] = $tmp_img[1];
				}
				
				$water_id = intval($this->input['water_id']);
                
				if(!intval($water_id))
				{
					//water_id为0时表示继承父级水印设置，get_water_id获取父级的水印设置
					$water_id = $this->get_water_id(urldecode($this->input['app_bundle']),urldecode($this->input['module_bundle']),urldecode($this->input['catid']));
				}
                if($water_id == -1)   //不使用水印
                {
                    $water_id = 0 ;
                }
				$info['water_id'] = $water_id;
                //如果有水印则创建json文件记录水印信息,并记录水印关系表
                if(!empty($info['water_id']))
                {
                    $this->createFile($info['water_id'],$info['bundle_id'],$info['filepath'],$info['filename'], hg_getimg_bs());
                }                
			 }

			 $info['filesize'] = filesize($local_filename);
			 $info['create_time'] = TIMENOW;
			 $info['ip'] = hg_getip();
			 $info['nums'] = 1;
             $info['bs'] = hg_getimg_bs();
			 $info['id'] = $this->insert_data($info);
			 $info['host'] = hg_getimg_host();

			 $info['dir'] = app_to_dir($info['bundle_id'],$info['mark']);
             $info['code'] = str_replace(array('{filename}', '{name}'), array($local_url, $info['name']), $gMaterialTpye[$info['mark']][$info['type']]['code']);
			 unset($info["bs"], $info['user_id'],$info['user_name'],$info['client_id'],$info['client_name'],$info['water_id'],$info['catid'],$info['nums']);
			 $ret[] = $info;
		}

		if(empty($ret))
		{
			foreach ( $url_arr as $key => $value ) 
			{
				$ret[] =  array('error' => true,'remote_url' => $value);      
			}
			return $ret;
		}
		else
		{
			return $ret;
		}

	}

//using
	public function addMaterial()
	{
		if($_FILES['Filedata'])
		{
			if($_FILES['Filedata']['error'])
			{
				return false;
			}
			else 
			{
				/*验证格式*/
				include_once(CUR_CONF_PATH . 'lib/cache.class.php');
				$this->cache = new cache;
				$gMaterialTpye = $this->cache->check_cache('material_type.cache.php');
				$info = array();
				$typetmp = explode('.',$_FILES['Filedata']['name']);
				$filetype = strtolower($typetmp[count($typetmp)-1]);
				$info['type'] = $filetype;
				$type = '';
				foreach($gMaterialTpye as $k => $v)
				{
					if(in_array($filetype,array_keys($v)))
					{
						$type = $k;
                        break;
					}
				}
				if(empty($type))
				{
					return false;
				}
				/*验证大小*/
				$max_size = UPLOAD_FILE_LIMIT*1024*1024; 
				if($_FILES['Filedata']['size'] > $max_size)
				{
					return  false;
				} 
				$info['cid'] = intval($this->input['cid']);
				$info['catid'] = intval($this->input['catid']);
				$info['bundle_id'] = urldecode($this->input['app_bundle']);
				$info['mid'] = urldecode($this->input['module_bundle']); 
				$info['user_id'] = intval($this->input['user_id']);
				$info['user_name'] = urldecode($this->input['user_name']);
				$info['client_id'] = intval($this->input['client_id']);
				$info['client_name'] = urldecode($this->input['client_name']);

				//存放的路径
				$info['filepath'] = date('Y') . '/' . date('m') . '/';
				$info['name'] = urldecode($_FILES['Filedata']['name']);
				$tmp_filename = date('YmdHis') . hg_generate_user_salt(4);
				$info['filename'] = $tmp_filename . '.' . $info['type'];
				$path = hg_getimg_dir() . app_to_dir($info['bundle_id'],$type) . $info['filepath'];
				$info['mark'] = $type;
				//如果附件为img图片类型的则 获取水印设置
				if($info['mark'] == 'img')
				{
					$water_id = intval($this->input['water_id']);
					if(!intval($water_id))    //water_id为-1是不用水印 默认继承水印(0)
					{
						$water_id = $this->get_water_id(urldecode($this->input['app_bundle']),urldecode($this->input['module_bundle']),urldecode($this->input['catid']));
					}
					if($water_id == -1)   //不使用水印
					{
						$water_id = 0 ;
					}
					$info['water_id'] = $water_id;
				}
				
				
				if(!hg_mkdir($path))
				{
					return false;
				}
				else
				{
					if(!move_uploaded_file($_FILES["Filedata"]["tmp_name"], $path . $info['filename']))
					{
						return false;
					}
					else
					{
						$imginfo = getimagesize($path.$info['filename']); 
                        if ($this->input['trans_format']  && ($this->input['trans_format'] != $info['type'])) {
                            $tmp_img = '';
                            switch ($imginfo[2]) {
                                case 1: //gif 
                                $tmp_img = imagecreatefromgif($path.$info['filename']); 
                                    break; 
                                case 2: //jpg 
                                $tmp_img = imagecreatefromjpeg($path.$info['filename']); 
                                    break; 
                                case 3: //png 
                                $tmp_img = imagecreatefrompng($path.$info['filename']); 
                                    break;                             
                                default:
                                    break;
                            }
                            if ($tmp_img) {
                                switch ($this->input['trans_format']) {
                                    case 'png':
                                        $info['type'] = 'png';
                                        imagepng($tmp_img, $path . $tmp_filename . '.' . $info['type']);
                                        break;
                                    case 'jpg':
                                        $info['type'] = 'jpg';
                                        imagejpeg($tmp_img, $path . $tmp_filename . '.' . $info['type']);
                                        break;
                                    case 'gif': 
                                        $info['type'] = 'gif';
                                        imagegif($tmp_img, $path . $tmp_filename . '.' . $info['type']);
                                        break;
                                    default:  
                                        $info['type'] = 'png';
                                        imagepng($tmp_img, $path . $tmp_filename . '.' . $info['type']);
                                        break;
                                }
                                @unlink($path.$info['filename']);
                                $info['filename'] = $tmp_filename . '.' . $info['type'];
                                imagedestroy($tmp_img);
                            }
                        }
                        
						$info['imgwidth'] = $imginfo[0];
						$info['imgheight'] = $imginfo[1];	
						$info['filesize'] = $_FILES["Filedata"]["size"];
						$info['create_time'] = TIMENOW;
						$info['ip'] = hg_getip();
						$info['nums'] = 1;
                        $info['bs'] = hg_getimg_bs();
                        
						$info['id'] = $this->insert_data($info);
						//如果有水印则创建json文件记录水印信息,并记录水印关系表
						if(!empty($info['water_id']))
						{
							$this->createFile($info['water_id'],$info['bundle_id'],$info['filepath'],$info['filename'], hg_getimg_bs());
						}
                        $imgurl = hg_getimg_host();
						$info['url'] = hg_material_link($imgurl, app_to_dir($info['bundle_id'],$type), $info['filepath'], $info['filename']);
						//unset 防止返回错误
						$info['host'] = $imgurl;
						$info['dir'] = app_to_dir($info['bundle_id'],$info['mark']);
                        $info['code'] = str_replace(array('{filename}', '{name}'), array($info['url'], $info['name']), $gMaterialTpye[$info['mark']][$info['type']]['code']);
						unset($info["bs"], $info['user_id'],$info['user_name'],$info['client_id'],$info['client_name'],$info['water_id'],$info['catid'],$info['nums']);
						return $info;
					}
				}
			}
		}
	}

//using
	public function updateMaterial($material_id, $cid, $bundle_id, $module_id)
	{
		if(empty($material_id) || empty($cid) || empty($module_id) || empty($bundle_id))
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "material SET cid=" . $cid . ",bundle_id='" . $bundle_id . "',mid='" . $module_id . "' WHERE id IN (" . $material_id . ")";
		$this->db->query($sql);
		return true;
	}

	//using 
	//入水印关系表
	public function insertMaterialWater($app_bundle,$module_bundle,$catid,$cid,$water_id)
	{
		//当水印为继承或不使用水印时 将水印关系表中的记录删除
		$father_water_id = $this->get_water_id($app_bundle,$module_bundle,$catid);
		switch($water_id)
		{
			case -1:   //water_id 为 -1 时不使用水印，
                $sql = "SELECT * FROM " . DB_PREFIX ."water_material WHERE bundle_id='".$app_bundle."' AND mid='".$module_bundle."' AND  catid='".$catid."' AND cid=".$cid;
                $info = $this->db->query_first($sql);
                if(empty($info))
                {
                    $sql="INSERT INTO " . DB_PREFIX."water_material SET bundle_id='".$app_bundle."',mid='".$module_bundle."',catid='".$catid."',cid=".$cid.",water_id=".$water_id;
                    $this->db->query($sql);
                }
                else
                {
                    $sql = "UPDATE " . DB_PREFIX."water_material SET water_id=".$water_id." WHERE id=".$info['id'];
                    $this->db->query($sql);
                }
                //写入缓存
                $this->water_material_cache($app_bundle,$module_bundle,$catid,$cid,$water_id);
				$this->updateMaterialWaterID($app_bundle,$module_bundle,$catid,$cid,0);
				break;
			case 0:   //默认不选择水印(0)则继承如果此时关系库中有该记录需删除
                $sql = "SELECT * FROM " . DB_PREFIX ."water_material WHERE bundle_id='".$app_bundle."' AND mid='".$module_bundle."' AND  catid='".$catid."' AND cid=".$cid;
                $info = $this->db->query_first($sql);
                if(!empty($info))
                {
                    $sql = "DELETE FROM ".DB_PREFIX."water_material WHERE bundle_id='".$app_bundle."' AND mid='".$module_bundle."' AND catid='".$catid."' AND cid=".$cid;
                    $this->db->query($sql);
                }
                //写入缓存
                $this->water_material_cache($app_bundle,$module_bundle,$catid,$cid,$water_id);
				$water_id = $father_water_id;
				$this->updateMaterialWaterID($app_bundle,$module_bundle,$catid,$cid,$water_id);
				break;
			default:
//				if($water_id == $father_water_id)    //继承,不入水印关系表,不修改缓存文件
//				{
//					$this->updateMaterialWaterID($app_bundle,$module_bundle,$catid,$cid,$water_id);
//					return;
//				}
				$sql = "SELECT * FROM " . DB_PREFIX ."water_material WHERE bundle_id='".$app_bundle."' AND mid='".$module_bundle."' AND  catid='".$catid."' AND cid=".$cid;
				$info = $this->db->query_first($sql);
				if(empty($info))
				{
					$sql="INSERT INTO " . DB_PREFIX."water_material SET bundle_id='".$app_bundle."',mid='".$module_bundle."',catid='".$catid."',cid=".$cid.",water_id=".$water_id;
					$this->db->query($sql);
                    $insert_id = $this->db->insert_id();
				}
				else
				{
					$sql = "UPDATE " . DB_PREFIX."water_material SET water_id=".$water_id." WHERE id=".$info['id'];
					$this->db->query($sql);
                    $insert_id = $info['id'];
				}
                //写入缓存
                $this->water_material_cache($app_bundle,$module_bundle,$catid,$cid,$water_id);
                //重新设置material表中water_id字段，并修改json文件中水印信息，删除所有的缩略图
                $this->updateMaterialWaterID($app_bundle, $module_bundle, $catid, $cid, $water_id);
                return $insert_id;
		}
	}
	
	//using
	public function createFile($water_id,$app_bundle,$filepath,$filename, $bs = "")
	{
        $imgdir = hg_getimg_dir($bs);
		$filepath = $imgdir . app_to_dir($app_bundle) . $filepath;
		$exttmp = explode('.', $filename);
		$json_file = $filepath . $exttmp[0] .'.json';
		$json = array();
		if(file_exists($json_file))
		{
			$json = json_decode(file_get_contents($json_file),true);
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."water_config WHERE id=".$water_id;
		$ret = $this->db->query_first($sql);
		$json['water'] = array(
				'type' => $ret['type'],
				'filename' => $ret['filename'],
				'opacity' => $ret['opacity'],
				'position' => $ret['position'],
				'water_text' => $ret['water_text'],
				'water_font' => $ret['water_font'],
				'font_size' => $ret['font_size'],
				'water_color' => $ret['water_color'],
				'water_angle' => $ret['water_angle'],
				'margin_x' => $ret['margin_x'],
				'margin_y' => $ret['margin_y'],
				'condition_x' => $ret['condition_x'],
				'condition_y' => $ret['condition_y'],
		);
		hg_file_write($json_file, json_encode($json));
        copy($filepath . $filename, $filepath . 'nowater_' . $filename);
        include_once(ROOT_PATH . 'lib/class/gdimage.php');
        $img = new GDImage();
        if($json['water']['type'] == 1)
        {
            $json['water']['water_file_path'] = $json['water']['filename'] ? hg_getimg_default_dir() . WATER_PATH . $json['water']['filename'] : '';

            //根据图片大小和图片水印比例调整水印图片大小
            if ($this->settings['image_water_ratio'] && $json['water']['water_file_path'] )
            {
                $img_info = getimagesize($filepath . $filename);
                $waterimg_info = getimagesize($json['water']['water_file_path']);

                if ( ($img_info[0]/$waterimg_info[0]) < $this->settings['image_water_ratio'] )
                {
                    $new_width = abs(intval($img_info[0]/$this->settings['image_water_ratio']));
                    hg_mk_images($json['water']['water_file_path'], $json['water']['filename'], hg_getimg_default_dir() . WATER_PATH . $new_width . '/' , array('width' => $new_width, 'height'=>'' ), array());
                    $json['water']['water_file_path'] = hg_getimg_default_dir() . WATER_PATH . $new_width . '/' . $json['water']['filename'];
                }
            }

            $img->waterimg($filepath . $filename,$json['water']);

        }
        else
        {
            $json['water']['water_font'] = $json['water']['water_font'] ? CUR_CONF_PATH . 'font/' . $json['water']['water_font'] : CUR_CONF_PATH . 'font/arial.ttf';
            $json['water']['font_size'] = $json['water']['font_size'] ? $json['water']['font_size'] : 14;
            $img->waterstr($filepath . $filename, $json['water']);
        }           
		return true;
	}
	//using
	public function revolveImg($app_bundle,$material_id,$direction)
	{
		$sql = "SELECT * FROM ". DB_PREFIX ."material WHERE id=". $material_id;
		$r = $this->db->query_first($sql);
        $imgdir = hg_getimg_dir($r['bs']);
		$file = $save_file = $imgdir . app_to_dir($r['bundle_id']) . $r['filepath'] . $r['filename'];
		$path = $imgdir . app_to_dir($r['bundle_id']) . $r['filepath'];
		//旋转
		hg_turn_img($file,$save_file,$direction);
		//删除原有所有缩略图
		$filename = preg_replace('/(.*?\.).*?/siU',"\\1json",$r['filename']);
		if(file_exists($path . $filename))
		{
			$file_handle = fopen($path . $filename, "r");
			$content = "";
			while(!feof($file_handle)){
				$content .= fgets($file_handle);
			}
			fclose($file_handle);
			$info = json_decode($content,true);
			if(!empty($info['thumb']))
			{
				foreach($info['thumb'] as $k => $v)
				{
					if(is_file($v) && file_exists($v))
					{
						@unlink($v);
					}				
				}
			}
		}
        $imgurl = hg_getimg_host($r['bs']);
		$ret = array(
			'id' => $material_id,	
			'filename' => $r['filename'],	
			'mark' => 'img',
			'path' => $imgurl.app_to_dir($r['bundle_id']),
			'dir' => $r['filepath'],
			'url' => hg_material_link($imgurl,app_to_dir($r['bundle_id']), $r['filepath'], $r['filename'], $this->settings['default_size']['label'] . "/"),
			'indexurl' => hg_material_link($imgurl,app_to_dir($r['bundle_id']), $r['filepath'], $r['filename'], $this->settings['default_index']['label'] . "/"),
			'ori_url' => hg_material_link($imgurl,app_to_dir($r['bundle_id']), $r['filepath'], $r['filename']),
		);
		return $ret;
	}
	//using
	//获取父级水印id
	private function get_water_id($app_bundle,$module_bundle,$catid)
	{	
		$cache_dir = CACHE_DIR . $app_bundle .".water.cache.php";
		if(!file_exists($cache_dir))  //当缓存文件不存在时，调用water_material_cache建立缓存文件
		{
			$this->water_material_cache($app_bundle,$module_bundle,$catid);
		}
		$cache_data = include($cache_dir);
		$water_id = 0;
		if($catid)
		{
			$catid = explode(',', $catid);
			foreach($catid as $k => $v)
			{
				if(isset($cache_data[$app_bundle][$module_bundle][$v]['']))
				{
					$water_id = $cache_data[$app_bundle][$module_bundle][$v][''];
					break;
				}
			}
			if(!$water_id)
			{
				if(isset($cache_data[$app_bundle][$module_bundle]['']['']))
				{
					$water_id = $cache_data[$app_bundle][$module_bundle][''][''];
				}
				else if(isset($cache_data[$app_bundle]['']['']['']))
				{
					$water_id = $cache_data[$app_bundle][''][''][''];
				}
				else
				{
					$water_id = $cache_data['global_default'];
				}				
			}
		}
		else
		{
			if(isset($cache_data[$app_bundle][$module_bundle]['']['']))
			{
				$water_id = $cache_data[$app_bundle][$module_bundle][''][''];
			}
			else if(isset($cache_data[$app_bundle]['']['']['']))
			{
				$water_id = $cache_data[$app_bundle][''][''][''];
			}
			else
			{
				$water_id = $cache_data['global_default'];
			}
		}
		return $water_id;
	}
	
	//设置水印关系后需修改material表中的water_id字段,因为编辑文章时有可能修改了文章的水印设置,然后修改json文件中的水印设置并
	//删除所有的缩络图
	//using
	private function updateMaterialWaterID($app_bundle,$module_bundle,$catid,$cid,$water_id)
	{
		$sql = "UPDATE ".DB_PREFIX."material SET water_id='".$water_id."' WHERE bundle_id='".$app_bundle."' AND mid='".$module_bundle."' AND catid='".$catid."' AND cid=".$cid." and mark='img'";
		$this->db->query($sql);
		return true;
	}
	//using
	public function deleteMaterialThumb($app_bundle,$filepath,$filename, $bs = "")
	{
		$path = hg_getimg_dir($bs) . app_to_dir($app_bundle) . $filepath;
        if (file_exists($path . 'nowater_' . $filename)) {
            @unlink($path . $filename);
            copy($path . 'nowater_' . $filename, $path . $filename);
        }
		$filename = preg_replace('/(.*?\.).*?/siU',"\\1json",$filename);
		if(file_exists($path . $filename))
		{
			$file_handle = fopen($path . $filename, "r");
			$content = "";
			while(!feof($file_handle)){
				$content .= fgets($file_handle);
			}
			fclose($file_handle);
			$info = json_decode($content,true);
			if(!empty($info['thumb']))
			{
				foreach($info['thumb'] as $k => $v)
				{
					if(is_file($v) && file_exists($v))
					{
						@unlink($v);
					}				
				}
			}
		}
		return true;
	}
	//using 
	public function addMaterialNodb()
	{
		if(intval($this->input['type']) == 1)
		{
			$return = $this->addMaterialNodb_url();
		}
		else if(intval($this->input['type']) == 2)
		{
			$return = $this->addMaterialNodb_file();
		}
		return $return;
	}
	
	//using
	private function addMaterialNodb_url()
	{
		$url = urldecode($this->input['url']);
		$dir = urldecode($this->input['dir']);
		$name = $this->input['name'];
		($dir[count($dir)-1] == '/') ? ($dir = $dir) : ($dir = $dir . '/');		
		// ob_start(); 
		// readfile($url); 
		// $img = ob_get_contents(); 
		// ob_end_clean();
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $img = curl_exec($curl);
        curl_close($curl);                  
		if(!$name)
		{
			$filenametmp = explode('/',$url);
			$filename= $filenametmp[count($filenametmp)-1];
		}
		else 
		{
			$filename = $name;
		}
		//采集后存放的路径

		$desdir = hg_getimg_default_dir() . $dir;
		$local_filename = $desdir . $filename ; //新路径
        $imgurl = hg_getimg_default_host();
		$local_url = $imgurl . $dir . $filename ; //本地的访问路径
		//本地化
		hg_mkdir($desdir); //判断目录是否存在 并生成
		if(file_exists($local_filename)) 	//如果文件存在则删除所有的缩略图
		{
			$json = preg_replace('/(.*?\.).*?/siU',"\\1json",$filename);
			if(file_exists($desdir . $json))
			{
				$info = json_decode(file_get_contents($desdir . $json),true);
				if(!empty($info['thumb']))
				{
					foreach($info['thumb'] as $k => $v)
					{
						if(is_file($v) && file_exists($v))
						{
							@unlink($v);
						}				
					}
				}
			}
		}
		$fp2 = @fopen($local_filename, "w"); 
		@fwrite($fp2,$img); 
		@fclose($fp2);
		$info = array(); 
		$info['host'] = $imgurl;
		$info['dir'] = $dir;
		$info['filename'] = $filename;
		return $info;		
	}
	
	//using
	private function addMaterialNodb_file()
	{
	    $dir = urldecode($this->input['dir']);
	    $name = $this->input['name'];
	    ($dir[strlen($dir)-1] == '/') ? ($dir = $dir) : ($dir = $dir . '/');		
		if($_FILES['Filedata'])
		{
			if($_FILES['Filedata']['error'])
			{
				return false;
			}
			else 
			{
				if(!$name)
				{
					$filename = $_FILES['Filedata']['name'];
				}
				else 
				{
					$filename = $name;
				}
				
				$path = hg_getimg_default_dir() . $dir;
						
				if(!hg_mkdir($path))
				{
					return false;
				}
				else
				{
					if(file_exists($path . $filename)) 	//如果文件存在则删除所有的缩略图
					{
						$json = preg_replace('/(.*?\.).*?/siU',"\\1json",$filename);
						if(file_exists($path . $json))
						{
							$info = json_decode(file_get_contents($path . $json),true);
							if(!empty($info['thumb']))
							{
								foreach($info['thumb'] as $k => $v)
								{
									if(is_file($v) && file_exists($v))
									{
										@unlink($v);
									}				
								}
							}
						}
					}
					
					if(!move_uploaded_file($_FILES["Filedata"]["tmp_name"], $path . $filename))
					{
						return false;
					}
					else
					{	
						$info = array();
						$info['host'] = hg_getimg_default_host();
						$info['dir'] = $dir;
						$info['filename'] = $filename;
						return $info;		
					}
				}
			}
		}
	}
	
	//using
	/**
	 * 替换原图
	 * @param base64 $imgdata 替换数据
	 * @param string $oriurl 原图url地址
     * @param string $url    图片url
	 * @return 
	 */
	public function replaceImg($imgdata,$oriurl, $url)
	{
		if(empty($imgdata) && !$url)
		{
			return false;
		}
		if(empty($oriurl))
		{
			return false;
		}
		preg_match_all('/^(.*?)(material\/.*?\/img\/)(\d{0,4}\/\d{0,2}\/)(.*?)(\.[a-zA-Z]*)/i',$oriurl,$out);
		$info = array(
			'host' => $out[1][0],// 
			'dir' =>  $out[2][0],// 
			'filepath' => $out[3][0] ,// 2012/05/
			'filename' => $out[4][0] ,// 20120529143609jSG7
			'type' => $out[5][0],//.jpg
		);
		$oridir = hg_getimg_dir($info["host"], "host") . $info['dir'] . $info['filepath'] . $info['filename'] . $info['type'];
		$neworidir = $oridir . '.ori';
		$jsondir = hg_getimg_dir($info["host"], "host") . $info['dir'] . $info['filepath'] . $info['filename'] . '.json';
		if(file_exists($jsondir))
		{
			$json = json_decode(file_get_contents($jsondir),true);
		}
        if ($url) {
            $data = hg_file_get_contents($url);
            if (!$data['data'] || $data['statusCode'] != 200)
            {
                return false;
            }
            $data = $data['data'];
        } else {
            $data = base64_decode($imgdata);
        }
        if(!file_exists($neworidir))
        {
            @rename($oridir,$neworidir); 	//保留原图
            $json['isedit'] = 1;
            hg_file_write($jsondir, json_encode($json));
        }
		hg_file_write($oridir, $data);
		if(!empty($json['thumb']))
		{
			foreach($json['thumb'] as $k => $v)
			{
				if(is_file($v) && file_exists($v))
				{
					@unlink($v);
				}				
			}
		}
		$info['mark'] = 'img';
		$info['filename'] = $info['filename'] . $info['type'] . '?' . hg_generate_salt(5);
		return $info;
	}
	
	function editedImg($url)
	{
		preg_match_all('/^(.*?)(material\/.*?\/img\/)(\d{0,4}\/\d{0,2}\/)(.*?)(\.[a-zA-Z]*)/i',$url,$out);
		$info = array(
			'host' => $out[1][0],// 
			'dir' =>  $out[2][0],// 
			'filepath' => $out[3][0] ,// 2012/05/
			'filename' => $out[4][0] ,// 20120529143609jSG7
			'type' => $out[5][0],//.jpg
		);		
		$oridir = hg_getimg_dir($info["host"], "host") . $info['dir'] . $info['filepath'] . $info['filename'] . $info['type'];
		$neworidir = $oridir . '.ori';
		$jsondir = hg_getimg_dir($info["host"], "host") . $info['dir'] . $info['filepath'] . $info['filename'] . '.json';
		$return['isedit'] = 0;
		if(file_exists($neworidir))
		{
			$return['isedit'] = 1;
		}
		return $return;
	}
	
	function recoverImg($url)
	{
		preg_match_all('/^(.*?)(material\/.*?\/img\/)(\d{0,4}\/\d{0,2}\/)(.*?)(\.[a-zA-Z]*)/i',$url,$out);
		$info = array(
			'host'  => $out[1][0],
			'dir' => $out[2][0],
			'filepath' => $out[3][0],
			'filename' => $out[4][0],
			'type'  => $out[5][0],
		);
		$oridir = hg_getimg_dir($info["host"], "host") . $info['dir'] . $info['filepath'] . $info['filename'] . $info['type'];
		$neworidir = $oridir . '.ori';
		@unlink($oridir);
		@rename($neworidir,$oridir);
		$jsondir = hg_getimg_dir($info["host"], "host") . $info['dir'] . $info['filepath'] . $info['filename'] . '.json';
		if(file_exists($jsondir))
		{
			$json = json_decode(file_get_contents($jsondir),true);
		}
		if(!empty($json['thumb']))
		{
			foreach($json['thumb'] as $k => $v)
			{
				if(is_file($v) && file_exists($v))
				{
					@unlink($v);
				}				
			}
		}
		$json['isedit'] = 0;
		hg_file_write($jsondir, json_encode($json));
		$info['filename'] = $info['filename'] . $info['type'] . '?' . hg_generate_salt(5);
		return $info;		
	}
	
	public function imgdata2pic($imgdata,$app_bundle,$type = 'png')
	{
		if(empty($imgdata))
		{
			return false;
		}	
		$info = array(
			'host'  => hg_getimg_host(),
			'dir'	=> app_to_dir($app_bundle),
			'filepath' => date('Y',TIMENOW) . '/' . date('m',TIMENOW)  . '/',
			'filename' => md5(hg_generate_salt(4) . TIMENOW) . '.' . $type,
		);
		$img_dir = hg_getimg_dir() . $info['dir'] . $info['filepath'];
		if (!hg_mkdir($img_dir) || !is_writeable($img_dir))
		{
			$this->errorOutput($img_dir . '目录不可写');
		}
        $imgdata = str_replace('data:image/png;base64,','', $imgdata);
        $imgdata = (strpos($imgdata, 'data:') !== false) ? $imgdata : base64_decode($imgdata);
		hg_file_write($img_dir . $info['filename'], ($imgdata));
		return $info;
	}

	//using
	//建立水印关系缓存文件，默认只包含全局水印id
	private function water_material_cache($app_bundle,$module_bundle='',$catid='',$cid='',$water_id='')
	{
		$cache_dir = CACHE_DIR . $app_bundle . '.water.cache.php';
		$cache_data = array();
		if(file_exists($cache_dir))
		{
            $cache_data = include($cache_dir);
		}
		if($water_id)   //水印id为空时不入水印关系关系表和缓存文件
		{
//            $cache_data[$app_bundle] = array();
//            $cache_data[$app_bundle][$module_bundle] = array();
//            $cache_data[$app_bundle][$module_bundle][$catid] = array();
			$cache_data[$app_bundle][$module_bundle][$catid][''] = $water_id;		
		}
        else //等于0时继承
        {
            unset($cache_data[$app_bundle][$module_bundle][$catid]['']);
            if (!$cache_data[$app_bundle][$module_bundle][$catid])
            {
                unset($cache_data[$app_bundle][$module_bundle][$catid]);
            }
            if (!$cache_data[$app_bundle][$module_bundle])
            {
                unset($cache_data[$app_bundle][$module_bundle]);
            }
            if (!$cache_data[$app_bundle])
            {
                unset($cache_data[$app_bundle]);
            }
        }
		//查询出全局配置文件
		$sql = "SELECT id FROM " . DB_PREFIX ."water_config WHERE global_default = 1 LIMIT 1";
		$q = $this->db->query_first($sql);
		$cache_data['global_default'] = $q['id'];
		$data =  "<?php\nreturn ";
		$data .= var_export($cache_data,1);
		$data .= "\n?>";
		hg_file_write($cache_dir,$data);
		return true;
	}

	function insert_data($data, $table = 'material',$replace= false)
	{
		if(!$data || !$table)
		{
			return false;
		}
		if(is_string($data))
		{
			$fields = $data;
		}
		elseif(is_array($data) && count($data) > 0 )
		{
			$fields = array();
			foreach($data as $k => $v)
			{
				$fields[] = $k . "='" . $v . "'";
			}
			$fields = implode(',', $fields);
		}
		else 
		{
			return false;
		}
		$sql = $replace ? "REPLACE INTO " : "INSERT INTO ";
		$sql .= DB_PREFIX . $table ." SET " . $fields;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	function update_data($data,$where,$table='material')
	{
		if(!$where || !$table)
		{
			return false;
		}
		if(is_string($data) && $data != '')
		{
			$fields = $data;
		}
		elseif(is_array($data) && count($data) > 0)
		{
			$fields = array();
			foreach($data as $k => $v)
			{
				$fields[] = $k . "='" . $v . "'";	
			}
			$fields = implode(',',$fields);
		}
		else
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . $table . " SET " . $fields . " WHERE " . $where;
		return $this->db->query($sql);
	}
	
	protected function verifyToken()
	{

	}
}
?>