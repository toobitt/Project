<?php 
/***************************************************************************

* $Id: competiton.class.php 7272 2012-06-20 01:37:09Z lijiaying $

***************************************************************************/
class competiton extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		if (urldecode($this->input['uid']))
		{
			$condition = " o.uid = " . urldecode($this->input['uid']) ;
		}
		
		if (urldecode($this->input['id']))
		{
			$condition = " o.id = " . urldecode($this->input['id']) ;
		}
		
		$sql = "SELECT o.*, u.*, o.id AS id, u.id AS u_id FROM " . DB_PREFIX . "opus o LEFT JOIN " . DB_PREFIX . "undertaking u ON o.id=u.opus_id ";
		$sql .= " WHERE  " . $condition . " ORDER BY o.id DESC";
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[$row['id']] = $row;
		}
		if (!empty($info))
		{
			$materialInfo = $this->getOpusMaterialInfo(@array_keys($info));
		}
		
		$return = array();
		if ($info && $materialInfo)
		{
			foreach ($info AS $k => $v)
			{
				if ($materialInfo[$k])
				{
					$return[$k] = @array_merge($info[$k],$materialInfo[$k]);
				}
			}
		}
		return $return;
	}
	
	public function getOpusMaterialInfo($opus_ids)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "opus_material WHERE opus_id IN (" . implode(',', $opus_ids) . ") ORDER BY id DESC";
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['file_path'] = OPUS_IMG_DIR . $row['entry_number'] . '/' . $row['newname'];
			$info[$row['opus_id']]['materials'][$row['id']] = $row;
		}
		return $info;
	}

	public function getOpusInfos()
	{
		$aid = urldecode($this->input['aid']);
		$cid = urldecode($this->input['cid']);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "opus WHERE aid = '" . $aid . "' AND cid = '" . $cid . "' ORDER BY opus_order DESC";
		$info = $this->db->query_first($sql);
		
		return $info;
	}
	
	public function str2int2str($opus_order)
	{
		$new_opus_order = intval($opus_order) + 1;
		$length = strlen($new_opus_order);
	 	$str_opus_order = $new_opus_order;
		switch ($length)
		{
			case 1:
				$new_opus_order = '000'.$str_opus_order;
				break;
			case 2:
				$new_opus_order = '00'.$str_opus_order;
				break;
			case 3:
				$new_opus_order = '0'.$str_opus_order;
				break;
			default:
				$new_opus_order = $str_opus_order;
				break;
		}
		return $new_opus_order;
	}
	
	public function create()
	{
		$info = $this->getOpusInfos();
		
		$opus_order = $this->str2int2str($info['opus_order']);
	
		//选择赛区院校选题
		$data = array(
			'uid' => urldecode($this->input['uid']),
			'tid' => urldecode($this->input['tid']),
			'aid' => urldecode($this->input['aid']),
			'cid' => urldecode($this->input['cid']),
			'opus_order' => $opus_order,
			'opus_name' => urldecode($this->input['opus_name']),
			'describes' => urldecode($this->input['describes']),
			'username' => urldecode($this->input['username']),
			'author' => urldecode($this->input['author']),
			'teacher' => urldecode($this->input['teacher']),
			'source' => urldecode($this->input['source']),
			'idcard' => urldecode($this->input['idcard']),
			'tel' => urldecode($this->input['tel']),
			'addr' => urldecode($this->input['addr']),
			'email' => urldecode($this->input['email']),
			'postcode' => intval($this->input['postcode']),
			'inventory' => urldecode($this->input['inventory']),
			'create_time' => TIMENOW,
			'ip' => hg_getip(),
			'other_college_name' => urldecode($this->input['other_college_name']),
			'teacher_tel' => urldecode($this->input['teacher_tel']),
		);
		
		$sql = "INSERT INTO " . DB_PREFIX . "opus SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();
		
		//承诺书
		if ($data['id'])
		{	
			//参赛编号
			$entry_number = urldecode($this->input['tid']) . '-' . urldecode($this->input['aid']) . '-' . urldecode($this->input['cid']) . '-' . $opus_order;
			
			$un_tak_data = array(
				'opus_id' => $data['id'],
				'entry_number' => $entry_number,
				'entry_name' => urldecode($this->input['entry_name']),
				'papers_type' => intval($this->input['papers_type']),
				'papers_num' => urldecode($this->input['papers_num']),
				'sign_time' => urldecode($this->input['sign_time']) ? strtotime(urldecode($this->input['sign_time'])) : TIMENOW,
			);
			
			$sql = "INSERT INTO " . DB_PREFIX . "undertaking SET ";
			$space = "";
			foreach ($un_tak_data AS $key => $value)
			{
				$sql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
			$this->db->query($sql);
		
			$entry_id = $this->db->insert_id();
		}
		
		//作品素材
		if ($entry_id)
		{
			for ($i=0; $i < intval($this->input['counts']); $i++)
			{
				$files[$i] = array(
					'name' => urldecode($this->input['name_'.$i]),
					'type' => urldecode($this->input['type_'.$i]),
					'tmp_name' => urldecode($this->input['tmp_name_'.$i]),
					'error' => $this->input['error_'.$i],
					'size' => $this->input['size_'.$i]
				);
			}
			
			if($files)
			{
				$material_ids = $flag = array();
				foreach ($files AS $i => $file)
				{
					$type = $file['type'];
					
					if ($entry_number)
					{
						$tc = substr($entry_number, 0,1);
					}
					$flag[$i] = '';
					switch ($tc)
					{
						case 'D': //flv 5M
							if ($type == 'video/x-flv' && $file['size'] < $this->settings['opus_material_size']['D']*1024*1024)
							{
								$flag[$i] = 'D';
							}
							else 
							{
								$flag[$i] = 0;
							}
							break;
						case 'E': //flv 20M
							if ($type == 'video/x-flv' && $file['size'] < $this->settings['opus_material_size']['E']*1024*1024)
							{
								$flag[$i] = 'E';
							}
							else 
							{
								$flag[$i] = 0;
							}
							break;
						case 'F': //word或者ppt 或者 rar 40M
							if (($type == 'application/octet-stream' || $type == 'application/msword' || $type == 'application/vnd.openxmlformats-officedocument.presentationml.presentation') && $file['size'] < $this->settings['opus_material_size']['F']*1024*1024)
							{
								$flag[$i] = 'F';
							}
							else 
							{
								$flag[$i] = 0;
							}
							break;
						default: //jpg 2M
							if (($type == 'image/jpeg' || $type == 'image/pjpeg') && $file['size'] < $this->settings['opus_material_size']['other']*1024*1024)
							{
								$flag[$i] = 'ABCGH';
							}
							else 
							{
								$flag[$i] = 0;
							}
					}
				
					if ($flag[$i])
					{
						$path = OPUS_IMG_DIR . $entry_number;
				
						$this->create_dir($path); //创建参赛作品文件夹
						
						$pt = strrpos($file['name'], '.');
						
						if($pt)
						{
							$suffix_name = substr($file['name'], $pt+1);
						}
						// 参赛编号 作品顺序 时间戳
						$material['newname'] = $entry_number . '-' . ($i+1) . '-' . TIMENOW . '.' . $suffix_name;
						
						$ret = copy($file['tmp_name'],$path . "/" . $material['newname']);
						
						if ($ret)
						{
							$material_data = array(
								'opus_id' => $data['id'],
								'entry_number' => $entry_number,
								'oldname' => urldecode($file['name']),
								'newname' => urldecode($material['newname']),
								'type' => urldecode($suffix_name),
								'size' => urldecode($file['size'])
							);
		
							$sql = "INSERT INTO " . DB_PREFIX . "opus_material SET ";
							$space = "";
							foreach ($material_data AS $key => $value)
							{
								$sql .= $space . $key . "=" . "'" . $value . "'";
								$space = ",";
							}
							$this->db->query($sql);
							$material_ids[$i] = $this->db->insert_id();
							
						}
						
					}
					else 
					{
						$material_ids[$i] = 0;
					}
					
				}
				if (is_array($material_ids))
				{
					$flag_id = '';
					foreach ($material_ids AS $k => $material_id)
					{
						if (!$material_id)
						{
							$flag_id = 1;
						}
					}
					
					//如果有素材入库不成功 则 删除 相应的 参赛编号表 承诺书表 的记录
					if ($flag_id)
					{
						$sql = "DELETE FROM " . DB_PREFIX . "opus WHERE id = " . $data['id'];
						$this->db->query($sql); 
						
						$sql = "DELETE FROM " . DB_PREFIX . "undertaking WHERE id = " . $entry_id;
						$this->db->query($sql); 
						
						$sql = "DELETE FROM " . DB_PREFIX . "opus_material WHERE id IN (" . implode(',', $material_ids) . ")";
						$this->db->query($sql);
						
						$this->del_all_dir($path);
					}
				}
			}
			
		}
		else 
		{
			$sql = "DELETE FROM " . DB_PREFIX . "opus WHERE id = " . $data['id'];
			$this->db->query($sql); 
		}
		
		if (!$flag_id)
		{
			return $data;
		}
		return false;
	}
	
	/**
	 * 创建参赛作品目录 
	 * Enter description here ...
	 * @param unknown_type $path
	 */
	function create_dir($path)
	{
		if(is_dir($path))
		{
			return;
		}
		else
		{
			hg_mkdir($path);
		}
	
	}
	
	/**
	 * 添加参赛作品失败后删除该参赛作品目录 
	 * Enter description here ...
	 * @param unknown_type $path
	 */
	function del_all_dir($path)
	{
		if(!is_dir($path))
		{
			exit();
		}
		$handle = opendir($path);
		while($dir = readdir($handle))
		{
			if($dir == '..' || $dir == '.')
			{
				continue;
			}
			$file = $path . '/' . $dir;
			echo '<br />';
			if(is_dir($file))
			{
				// 递归删除
				del_all_dir($file);
			}
			// 所有文件删除成功 出现零界点 即空目录
			if(is_file($file))
			{
				@unlink($file);
			}
		}
		closedir($handle);
		echo '<br />';
		// 零界点
		@rmdir($path);
	}
	
}

?>