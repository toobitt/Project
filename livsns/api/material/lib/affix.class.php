<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: affix.class.php 6202 2012-03-27 07:59:49Z wangleyuan $
***************************************************************************/
class affix extends InitFrm
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

	public function show($condition)
	{
		$sql="SELECT * FROM " . DB_PREFIX . "material WHERE isdel=1 " . $condition;
		$ret=$this->db->query($sql);
		$info=array();
		while($row=$this->db->fetch_array($ret))
		{
			$row['create_time']=date('Y-m-d H:i',$row['create_time']);
			$row['filesize'] = hg_bytes_to_size($row['filesize']);
			
			switch($row['mark'])
			{
				case 'img':
					$setting_code = $this->get_setting_code($row['type']);
					$filename = preg_replace('/(.*?\.).*?/siU',"\\1json",$row['filename']);
					$filepath = hg_getimg_dir($row['bs']) . app_to_dir($row['bundle_id']) . $row['filepath'];
					if(file_exists($filepath . $filename))
					{
						$file_handle = fopen($filepath . $filename, "r");
						$content = "";
						while (!feof($file_handle)){
							  $content .= fgets($file_handle);
						}
						fclose($file_handle);
						$thumb = json_decode($content,true);
						
						if(!empty($thumb['thumb']))
						{
							foreach($thumb['thumb'] as $k => $v)
							{
								if(is_file($v) && file_exists($v))
								{
									preg_match_all('/(.*?)(.*?\/img\/)([0-9]*)[x|-]([0-9]*)\/(\d{0,4}\/\d{0,2}\/)(.*?)(\.[a-zA-Z]*)(\?\w*)?/i',$v,$out);
									$row['thumb'][$k] = hg_material_link(hg_getimg_host($row['bs']),app_to_dir($row['bundle_id']),$row['filepath'],$row['filename'],$this->settings['default_size']['label'] . '/');
									$row['thumb_size'][$k] = $out[3][0] . 'x' . $out[4][0];// 100x75/
									$row['thumb_url'][$k] = hg_material_link(hg_getimg_host($row['bs']),app_to_dir($row['bundle_id']),$row['filepath'],$row['filename'],$row['thumb_size'][$k] . '/');
									$row['thumb_code'][$k] = str_replace('{filename}',$row['thumb'][$k],$setting_code);
								}
							}
						}
					}
					$row['url'] = hg_material_link(hg_getimg_host($row['bs']),app_to_dir($row['bundle_id']),$row['filepath'],$row['filename'],$this->settings['default_size']['label'] . '/');
					$row['code'] = str_replace("{filename}",$row['url'],$setting_code);
					$row['ori_path'] = app_to_dir($row['bundle_id']) . $row['filepath'] . $row['filename'];
					$row['mark']= 'img';
					$info[$row['id']] = $row;
					break;
				case 'doc':
					$row['url'] = hg_material_link(hg_getimg_host($row['bs']),app_to_dir($row['bundle_id'],'doc'),$row['filepath'],$row['filename']);
					$setting_code = $this->get_setting_code($row['type']);
					$search = array("{filename}","{name}");
					$replace = array($row['url'],$row['name']);
					$row['code'] = str_replace($search,$replace,$setting_code);
					$info[$row['id']] = $row;
					break;
				default:
					break;
			}
		}
		return $info;
	}

	public function count($condition)
	{
		$sql="SELECT COUNT(*) as total FROM " .DB_PREFIX . "material WHERE isdel=1 " . $condition;
		$ret=$this->db->query_first($sql);
		return $ret;
	}

	public function detail($condition)
	{
		$sql="SELECT * FROM " . DB_PREFIX . "material WHERE 1 " . $condition;
		$info=$this->db->query_first($sql);
		if(!empty($info))
		{
			$info['create_time']=date('Y-m-d H:i:s',$info['create_time']);
		    if($info['filesize'] < 1024)
			{
				$info['filesize']=$info['filesize'] . 'B';
			}
			elseif($info['filesize']<1024*1024)
			{
				$info['filesize']=sprintf("%.2f",$info['filesize']/1024) . 'KB';
			}
			else
			{
				$info['filesize']=sprintf("%.2f",$info['filesize']/(1024*1024)) . 'M';
			}
			//缩略图路径
			$info['url'] = hg_material_link(hg_getimg_host($info['bs']),app_to_dir($info['bundle_id']),$info['filepath'],$info['filename'],$this->settings['default_size']['label'] . '/');
		}
		return $info;
	}

	public function update()
	{
		if(!empty($this->input['tmpurl']))
		{
			$filepath=urldecode($this->input['filepath']);
			$filename=urldecode($this->input['filename']);
			$app_bundle=urldecode($this->input['app_bundle']);
			$tmpurl=urldecode($this->input['tmpurl']);
            $sql="SELECT * FROM " . DB_PREFIX . "material WHERE filepath = '".$filepath."' and bundle_id = '".$app_bundle."' and filename = '".$filename."'";
            $res=$this->db->query_first($sql);
            if(!$res){
                return true;
            }
			//先删除原来的图片和缩略图
			hg_delete_material(hg_getimg_dir($res["bs"]) . app_to_dir($app_bundle) . $filepath,$filename);
			//重命名新上传的图片然后移动图片
			$desdir=hg_getimg_dir($res["bs"]) . app_to_dir($app_bundle) . $filepath;
			hg_mkdir($desdir);

			ob_start();
			readfile($tmpurl);
			$img=ob_get_contents();
			ob_end_clean();
			$res=@fopen($desdir . $filename,'a');
			fwrite($res,$img);
			fclose();
		}
		return true;
	}

   public function delete()
   {
	   $id=urldecode($this->input['id']);
	   $sql="SELECT * FROM " . DB_PREFIX . "material WHERE id IN(" . $id . ")";
	   $r=$this->db->query($sql);
	   while($row = $this->db->fetch_array($r))
	   {
	   		hg_editTrue_material(hg_getimg_dir($row["bs"]) . app_to_dir($row['bundle_id']) . $row['filepath'], $row['filename']);
		 	hg_delete_material(hg_getimg_dir($row["bs"]) . app_to_dir($row['bundle_id']) . $row['filepath'],$row['filename']);
	   }
	   $sql = "DELETE FROM " . DB_PREFIX ."material WHERE id IN(" . $id .")";
	   $this->db->query($sql);	  
	   return true; 
   }   

   //删除一定尺寸的缩略图
   public function delete_thumb_size()
   {
	   $url=urldecode($this->input['path']);
	   $size_label=urldecode($this->input['size_label']);
	   $label_tmp=explode('-',$size_label);
	   $size_label2=implode('x',$label_tmp);
	   $affix_id=intval(urldecode($this->input['affix_id']));
	   $delete_tmp='';
	   //$host = urldecode($this->input['host']) == "221.226.87.26" ? "img.dev.hogesoft.com:83" : urldecode($this->input['host']);
	   if(empty($url) || empty($size_label))
	   {
		   return false;
	   }
	   else
	   {
		   //$url = 'material/article/img/2012/05/20120529143609jSG7.jpg?21jj';
		   preg_match_all('/(.*?)(.*?\/img\/)(\d{0,4}\/\d{0,2}\/)(.*?)(\.[a-zA-Z]*)(\?\w*)?/i',$url,$out);
		   $info = array(
				'dir' =>  $out[2][0],// 
				'filepath' => $out[3][0] ,// 2012/05/
				'filename' => $out[4][0] ,// 20120529143609jSG7
				'type' => $out[5][0],//.jpg
			);
           preg_match_all('/.*?\/(.*?)\/img\/.*/i',$url,$app);
           $app_bundle = $app[1][0];
           $sql="SELECT * FROM " . DB_PREFIX . "material WHERE filepath = '".$info['filepath']."' and bundle_id = '".$app_bundle."' and filename = '".$info['filename'].$info['type']."'";
           $res=$this->db->query_first($sql);
           if(!$res){
               return true;
           }
			$filepath = hg_getimg_dir($res['bs']) . $info['dir'] . $info['filepath'] . $info['filename'] . ".json";
			if(file_exists($filepath))
			{
				$file_handle = fopen($filepath, "r");
				$content = "";
				while (!feof($file_handle)){
					$content .= fgets($file_handle);
				}
				fclose($file_handle);
				$thumb = json_decode($content,true);
					
				if(!empty($thumb['thumb']))
				{
					foreach($thumb['thumb'] as $k => $v)
					{

							if(strpos($v,$size_label) !== false)
							{
								@unlink($v);//删除文件
								$delete_tmp=$k;
								unset($thumb['thumb'][$k]);
							}
							else if(strpos($v,$size_label2) !==false)
							{
								@unlink($v);//删除文件
								$delete_tmp=$k;
								unset($thumb['thumb'][$k]);
							}
					}
					//重新写入
                    hg_file_write($filepath,json_encode($thumb));
				}
			}
	   }
	   return array('affix_id' => $affix_id,'thumb_id' => $delete_tmp);
   }

	public function upload_affix()
	{
		
		if($_FILES['Filedata'])
		{
			if($_FILES['Filedata']['error'])
			{
				return false;
			}
			else 
			{
				$typetmp = explode('.',$_FILES['Filedata']['name']);
				$filetype = strtolower($typetmp[count($typetmp)-1]);
				$filepath = date('Y') . '/' . date('m') . '/';
				$tmp_filename = date('YmdHis') . hg_generate_user_salt(4);
				$filename = $tmp_filename . '.' . $filetype;
				include_once(CUR_CONF_PATH . 'lib/cache.class.php');
				$this->material_type = new cache();
				$gMaterialType = $this->material_type->check_cache('material_type.cache.php');
				$type = '';
				foreach($gMaterialType as $k => $v)
				{
					if($k == 'img')
					{
						if(in_array($filetype,array_keys($v)))
						{
							$path = hg_getimg_dir() . MATERIAL_TMP_PATH . $filepath;
							hg_mkdir($path);
							if(!move_uploaded_file($_FILES["Filedata"]["tmp_name"],$path . $filename))
							{
								return false;
							}
							else
							{
								$ret = array(
									'tmpurl' =>hg_material_link(hg_getimg_host(),MATERIAL_TMP_PATH,$filepath, $filename),
									'url' => hg_material_link(hg_getimg_host(),MATERIAL_TMP_PATH,$filepath, $filename,$this->settings['default_size']['label'] . '/'),
								);
							}
						}
					}
				}
				return $ret ;
			}
		}
	}

	public function get_app($data_limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "app WHERE father = 0" . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	public function get_module($data_limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."app WHERE  father != 0" . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	public function get_some_module($father, $data_limit = '')
	{
		$sql = "SELECT * FROM " .DB_PREFIX ."app WHERE  father =" . $father . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}

	private function get_setting_code($type)
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."affix_setting WHERE expand = '".$type."'";
		$ret = $this->db->query_first($sql);
		if($ret)
		{
			return $ret['code'];
		}
		else
		{
			return false;
		}
	}
}
?>