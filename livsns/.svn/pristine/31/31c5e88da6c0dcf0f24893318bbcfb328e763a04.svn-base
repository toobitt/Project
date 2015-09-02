<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: getblog.php 3873 2011-05-05 08:38:24Z repheal $
***************************************************************************/

class mblog extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//get user media info
	public function getMedia($id)
	{
		if(is_array($id))
		{
			$ids = implode(",", $id);
		}
		else 
		{
			$ids = $id;			
		}	
		$sql = "SELECT * FROM ".DB_PREFIX."media WHERE status_id IN (".$ids.")" ;
		$query = $this->db->query($sql);
		$i = 0;
		while ($array = $this->db->fetch_array($query))
		{
			$info[$array['status_id']][$i] = $array;
			str_replace($this->settings['video_api'],"",$array['link'],$cnt);
			if($cnt)
			{
				$info[$array['status_id']][$i]['self'] = 1;
			}
			else 
			{
				$info[$array['status_id']][$i]['self'] = 0;
			}
			$i++;
		}
		return $info;
	}
}
?>