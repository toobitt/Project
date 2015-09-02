<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_create_collect extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create_collect()
	{
		$return = array();
		
        $sql = "SELECT * FROM ".DB_PREFIX."vod_sort";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
        	$arr_sort[] = $r;
        }
        
        $return['vod_sort'] = $arr_sort;
        //$return['source'] = $this->settings['video_channel'];
        $return['id'] = intval($this->input['id']);
        $this->addItem($return);
        $this->output();
       
	}
	
	/*参数:collect_name集合的名称
	 *功能:创建一个集合
	 *返回值:sucess/error
	 * */
	public function  insert2collect()
	{
	    if(!$this->input['collect_name'])
		{
			$this->errorOutput();
		}
		
		$sql = "INSERT INTO ".DB_PREFIX."vod_collect  SET  ";
		$sql.= "collect_name='".urldecode($this->input['collect_name'])."',".
		       "vod_sort_id='".urldecode($this->input['sort_name'])."',". 
		       "source='".urldecode($this->input['source'])."',". 
		       "admin_name='".urldecode($this->user['user_name'])."',". 
		       "admin_id='".urldecode($this->user['user_id'])."',". 
			   "create_time='".TIMENOW."',".
           	   "update_time='".TIMENOW."'";
		
		if($this->db->query($sql))
		{
			//添加集合成功之后，要更新该集合所属的类别的表里面的collect_count
			$sql = "UPDATE ".DB_PREFIX."vod_sort SET collect_count = collect_count + 1 WHERE id = ".urldecode($this->input['sort_name']);
			$q = $this->db->query($sql);
			if($q)
			{
				$this->addItem('success');
			}
			else 
			{
				$this->addItem('error');
			}
		
		}
		else
		{
			$this->addItem('error');
		}
		$this->output();
	}
	
}

$out = new vod_create_collect();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'create_collect';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>