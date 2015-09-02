<?php 
/***************************************************************************

* $Id: member_avatar.class.php 15421 2012-12-12 09:28:06Z repheal $

***************************************************************************/
define('MOD_UNIQUEID','member_avatar');//模块标识
class memberAvatar extends InitFrm
{
	private $mMaterial;
	public function __construct()
	{
		parent::__construct();
		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 会员头像
	 * Enter description here ...
	 */
	
	public function memberAvatarDetail($member_id, $width, $height)
	{
		$condition = ' WHERE member_id IN (' . urldecode($this->input['member_id']) .')';
		
		$sql = "SELECT * FROM " . DB_PREFIX . "member_avatar " . $condition;		
		$row = $this->db->query_first($sql);
	
		if(is_array($row) && $row)
		{
			//40*40
			$row['avatar_small'] = hg_material_link($row['host'], $row['dir'], $row['filepath'], $row['filename'], $width.'x'.$height.'/');
			
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			return $row;
		}

		return false;
	}
	
	public function memberAvatarCreate($member_id, $files)
	{
		if ($_FILES['avatar']['tmp_name'])
		{
			$avatar['Filedata'] = $files;//$_FILES['avatar'];
			
			$material = $this->mMaterial->addMaterial($avatar, $member_id);
			
			if (!empty($material))
			{
				$data = array(
					'member_id' => $member_id,
					'imgid' => $material['id'],
					'name' => $material['name'],
					'filename' => $material['filename'],
					'type' => $material['type'],
					'filepath' => $material['filepath'],
					'url' => $material['url'],
					'host' => $material['host'],
					'dir' => $material['dir'],
					'create_time' => TIMENOW,
					'update_time' => TIMENOW,
					'ip' => hg_getip()
				);

				$sql = "INSERT INTO " . DB_PREFIX . "member_avatar SET ";
				$space = "";
				foreach ($data AS $key => $value)
				{
					$sql .= $space . $key . "=" . "'" . $value . "'";
					$space = ",";
				}

				$this->db->query($sql);

				$data['avatar_id'] = $this->db->insert_id();

				if ($data['avatar_id'])
				{
					$sql = "UPDATE " . DB_PREFIX . "member SET avatar = 1 WHERE id = " . $member_id;
					$this->db->query($sql);
				}
				
				if ($data['avatar_id'])
				{
					return $data;
				}
				return false;
			}
		}
		
		return false;
	}
	
	public function memberAvatarUpdate($member_id, $files)
	{
	
		if ($files['tmp_name'])
		{
			$avatar['Filedata'] = $files;//$_FILES['avatar'];
			
			$material = $this->mMaterial->addMaterial($avatar, $member_id);
			
			if (!empty($material))
			{
				$data = array(
			//		'member_id' => $member_id,
					'imgid' => $material['id'],
					'name' => $material['name'],
					'filename' => $material['filename'],
					'type' => $material['type'],
					'filepath' => $material['filepath'],
					'url' => $material['url'],
					'host' => $material['host'],
					'dir' => $material['dir'],
					'update_time' => TIMENOW,
				);

				$sql = "UPDATE " . DB_PREFIX . "member_avatar SET ";
				$space = "";
				foreach($data as $key => $value)
				{
					$sql .= $space . $key . "=" . "'" . $value . "'";
					$space = ",";
				}
				
				$sql .= " WHERE member_id = " . $member_id; 
				
				$this->db->query($sql);
				
				return $data;
			}
		}
		
		return false;
	}
}

?>