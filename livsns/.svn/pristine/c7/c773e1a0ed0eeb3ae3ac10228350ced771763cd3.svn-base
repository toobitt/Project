<?php
require_once('global.php');
define('MOD_UNIQUEID','vod_media_mark_node');
require_once(CUR_CONF_PATH . 'lib/vod_sort.class.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class vod_mark_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('vod_node');
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 获取视频节点
	 */
	
	public function show_1()
	{
		$this->setXmlNode('vodsorts' , 'vodsort');
		$vodsort = new vodSort();
		if(!$this->input['fid'])
		{
			foreach($this->settings['video_upload_type'] as $k => $v)
			{
				if (!in_array($k, array(3, 4)))
				{
					continue;
				}
				$r = array('id' => $k,"name" => $v,"fid" => 0,"depth" => 0, 'input_k' => '_type' ,'attr' => $this->settings['video_upload_type_attr'][$k]);
				$this->addItem($r);
			}
		}
		else 
		{
			$vodsort->set('father=' . intval($this->input['fid']));
			$sort = $vodsort->fetch();
			foreach ($sort AS $k => $r)
			{
				$this->addItem($r);
			}
		}
		
		$this->output();
		
	}
	
	public function show()
	{
		$this->setXmlNode('vodsorts' , 'vodsort');
		$vodsort = new vodSort();
		$vodsort->set('father=3');
		$sort = $vodsort->fetch();
		foreach ($sort AS $k => $r)
		{
			$this->addItem($r);
		}
		$this->output();
	}
	//获取节点的父节点树
	public function getParentsTree()
	{	
		$id = $this->input['ids'];		
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$ret = $this->getParentsTreeById($id);
		$this->addItem($ret);
		$this->output();
	}
	//获取节点的父节点树,合并父节点
	public function getMergeParentsTree()
	{
		$id = $this->input['ids'];
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$ret = $this->getMergeParentsTreeById($id);
		$this->addItem($ret);
		$this->output();
	}
	
}

/**
 * 程序入口
 */

$out = new vod_mark_node();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';	
}
$out->$action();

?>