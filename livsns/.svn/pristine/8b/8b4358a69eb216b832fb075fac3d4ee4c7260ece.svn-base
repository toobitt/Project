<?php

define('MOD_UNIQUEID', 'livmedia');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH . "global.php");
require(CUR_CONF_PATH . "lib/functions.php");
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(ROOT_PATH .'lib/class/recycle.class.php');
class vod_update extends outerUpdateBase
{
	private $recycle;
    public function __construct()
    {
        parent::__construct();
        $this->recycle = new recycle();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function create(){}
	public function update(){}
	
	public function delete()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		/*******************************************查询出原来数据***********************************************/
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id in (".$id.")";
		$q = $this->db->query($sql);
		$recycle = array();//记录回收站的数据
		while ($r = $this->db->fetch_array($q))
		{
			$recycle[$r['id']] = array(
				'title' 		=> $r['title'],
				'delete_people' => trim(($this->user['user_name'])),
				'cid' 			=> $r['id'],
			);
			$recycle[$r['id']]['content']['vodinfo'] = $r;
		}
		/*******************************************查询出原来数据***********************************************/
		
		/*******************************************删除*******************************************************/
		$sql = "DELETE FROM " .DB_PREFIX. "vodinfo WHERE id IN (" .$id. ")";
		$this->db->query($sql);
		//删除物理文件
		$this->del_phyfile($id);
		/*******************************************删除*******************************************************/
		
	 	/******************************************加入回收站***************************************************/
	    if(!empty($recycle))
		{
			foreach($recycle as $key => $value)
			{
				$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		/******************************************加入回收站***************************************************/
	    $this->addItem('success');
		$this->output();
	}
	
	private function del_phyfile($id)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$id);
		$curl->request('delete.php');
	}
	
	//更新视频库里面属于电视剧的视频的tv_play_id
	public function updateTvPlayId()
	{
		//先获取所有电视剧与视频的关联信息
		$curl = new curl($this->settings['App_tv_play']['host'], $this->settings['App_tv_play']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$info = $curl->request('updateTvPlayToLivmedia.php');
		if($info && $info[0])
		{
			$info = $info[0];
			foreach ($info AS $k => $v)
			{
				foreach ($v AS $_k => $_v)
				{
					$sql = "UPDATE " .DB_PREFIX. "vodinfo SET tv_play_id = '" . $k . "',app_uniqueid = 'tv_play',mod_uniqueid = 'tv_play' WHERE id = '" .$_v. "'";
					$this->db->query($sql);
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
    public function unknow()
	{
		$this->errorOutput(NOMETHOD); 
	}
}

$out = new vod_update();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>