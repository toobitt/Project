<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
define('MOD_UNIQUED','travel');//定义应用
class scenicPicApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function  show()
	{	
		global $gGlobalConfig;
		$curl = new curl($gGlobalConfig['App_tuji']['host'],$gGlobalConfig['App_tuji']['dir'].'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
	    $curl->addRequestData('a','show');
		//$curl->addRequestData('para', $this->input['para']);
		$ret['data'] = $curl->request('tuji.php');
		$ret['app'] = APP_UNIQUED;
		$ret['mod'] = MOD_UNIQUED;
		$this->addItem($ret);
		$this->output();
	}
	
	function delete()
	{			
		$id = urldecode($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput("请选择需要删除的图集");
		}
		global $gGlobalConfig;
		$curl = new curl($gGlobalConfig['App_tuji']['host'],$gGlobalConfig['App_tuji']['dir'].'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
	    $curl->addRequestData('a','delete');
		$curl->addRequestData('id',$id);
		$ret = $curl->request('tuji_update.php');
		$this->addItem('sucess');
		$this->output();
		
	}
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'templates WHERE 1 '.$this->get_condition();
		$templates_total = $this->db->query_first($sql);
		echo json_encode($templates_total);	
	}
	
}

$out = new scenicPicApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
