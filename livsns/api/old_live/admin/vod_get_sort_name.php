<?php
/***************************************************************************
* $Id: vod_get_sort_name.php 17966 2013-02-26 06:11:46Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','livmedia_node');
require_once('global.php');
class  vod_get_sort_name extends adminBase
{
	private $mLivmedia;
    public function __construct()
	{
		parent::__construct();
		include_once ROOT_PATH.'lib/class/curl.class.php';
		$this->mLivmedia = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*根据传过来的类型返回该类型下的分类*/
	public function get_leixing_sort()
	{
		if (!$this->mLivmedia)
		{
			$this->addItem(array());
			$this->output();
		}
		
		$this->mLivmedia->setSubmitType('post');
		$this->mLivmedia->initPostData();
		$this->mLivmedia->setReturnFormat('json');
		$this->mLivmedia->addRequestData('a', 'get_leixing_sort');
		$this->mLivmedia->addRequestData('vod_leixing', intval($this->input['vod_leixing']));
		$return = $this->mLivmedia->request('admin/vod_get_sort_name.php');
		$this->addItem($return[0]);
		$this->output();
	}
	
	/*获取编辑上传的类别*/
	public function get_sort_name()
	{
		if (!$this->mLivmedia)
		{
			$this->addItem(array());
			$this->output();
		}
		
		$this->mLivmedia->setSubmitType('post');
		$this->mLivmedia->initPostData();
		$this->mLivmedia->setReturnFormat('json');
		$this->mLivmedia->addRequestData('a', 'get_sort_name');
		$return = $this->mLivmedia->request('admin/vod_get_sort_name.php');
		$this->addItem($return[0]);
		$this->output();
	}
	
	/*获取标注归档的类别*/
	public function get_mark_sort_name()
	{
		if (!$this->mLivmedia)
		{
			$this->addItem(array());
			$this->output();
		}
		
		$this->mLivmedia->setSubmitType('post');
		$this->mLivmedia->initPostData();
		$this->mLivmedia->setReturnFormat('json');
		$this->mLivmedia->addRequestData('a', 'get_mark_sort_name');
		$return = $this->mLivmedia->request('admin/vod_get_sort_name.php');
		$this->addItem($return[0]);
		$this->output();
	}
	
	/*获取 直播归档的类别*/
	public function get_live_sort_name()
	{
		if (!$this->mLivmedia)
		{
			$this->addItem(array());
			$this->output();
		}
		
		$this->mLivmedia->setSubmitType('post');
		$this->mLivmedia->initPostData();
		$this->mLivmedia->setReturnFormat('json');
		$this->mLivmedia->addRequestData('a', 'get_live_sort_name');
		$return = $this->mLivmedia->request('admin/vod_get_sort_name.php');
		$this->addItem($return[0]);
		$this->output();
	}
	
	/*获取所有类别*/
	public function get_all_sort_name()
	{
		if (!$this->mLivmedia)
		{
			$this->addItem(array());
			$this->output();
		}
		
		$this->mLivmedia->setSubmitType('post');
		$this->mLivmedia->initPostData();
		$this->mLivmedia->setReturnFormat('json');
		$this->mLivmedia->addRequestData('a', 'get_all_sort_name');
		$return = $this->mLivmedia->request('admin/vod_get_sort_name.php');
		$this->addItem($return[0]);
		$this->output();
	}
	
	public function get_all_leixing()
	{
		if (!$this->mLivmedia)
		{
			$this->addItem(array());
			$this->output();
		}
		
		$this->mLivmedia->setSubmitType('post');
		$this->mLivmedia->initPostData();
		$this->mLivmedia->setReturnFormat('json');
		$this->mLivmedia->addRequestData('a', 'get_all_leixing');
		$return = $this->mLivmedia->request('admin/vod_get_sort_name.php');
		$this->addItem($return[0]);
		$this->output();
	}
}

$out = new vod_get_sort_name();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_sort_name';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>