<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: message_received.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/messagereceived.class.php';
define('MOD_UNIQUEID', 'message_received'); //模块标识

class messagereceviedApi extends adminReadBase
{
	private $messagereceived;
	
	public function __construct()
	{
		parent::__construct();
		$this->messagereceived = new messagereceivedClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->messagereceived);
	}
	
	public function index()
	{
		
	}
	
	/**
	 * 信息列表
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$cateid = isset($this->input['cateid']) ? intval($this->input['cateid']) : "";
		$condition = $this->get_condition();
		$messagereceived_info = array();
		$messagereceived_info['date'] = array();
		$messagereceived_info['date'] = $this->messagereceived->show($offset, $count, $condition ,$cateid);		
		$this->setXmlNode('messagereceived_info', 'messagereceived');
		
		if ($messagereceived_info['date'])
		{	
			foreach($messagereceived_info as $k=>$v){
				$this->addItem_withkey($k,$v);
			}
		}
		
		$this->output();
	}
	
	/**
	 * 信息数据总数
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->messagereceived->count($condition);
		echo json_encode($info);
	}

	/**
	**	信息编辑
	**/
	public function detail()
	{
		$id = trim($this->input['id']);
		if(!$id){
			$this->errorOutput(OBJECT_NULL);
		}
		
		$info = array();		
		$info['files'] = array();
		$info = $this->messagereceived->detail($id);		
		$info['files'] = $this->messagereceived->detailfiles($id);
		$info['picture'] = array();
		$info['video'] = array();
		$info['annex'] = array();
		foreach($info['files'] as $key=>$value)
		{
			if($value['typeid']==1)
			{
				$info['picture'][] = $value['host'].$value['dir'].$value['filepath'].$value['filename'];
			}
			
			if($value['typeid']==2)
			{
				$video_array = array();
				$video_vid = array();
				$video_vid = unserialize($value['backup']);
				$video_array['vid'] = $video_vid['vid'];
				$video_array['pic'] = $video_vid['pic'];
				$video_array['url'] = $value['host'].$value['dir']."/".$value['filepath'];
				$info['video'][] = $video_array;
			}
			
			if($value['typeid']==3)
			{
				$info['annex'][] = $value;
			}			
			
			
		}
		
		
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 查询条件
	 * @param Array $data
	 */
	private function get_condition()
	{	
		return array(
			'key' => trim(urldecode($this->input['key'])),
		);
	}
}
$out = new messagereceviedApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();

?>