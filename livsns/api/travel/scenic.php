<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require_once(ROOT_PATH."global.php");
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(CUR_CONF_PATH."lib/functions.php");
class scenicApi extends BaseFrm
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/scenic_sort.class.php');
		$this->sort = new scenicSort();
		include(CUR_CONF_PATH . 'lib/scenic.class.php');
		$this->scenic = new scenic();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function get_scenic_sort()
	{
		
		$condition = '';
		if(intval($this->input['fid']))
		{
			$condition .=" AND fid =". intval($this->input['fid']);
		}
		else
		{
			$condition .=" AND fid = 0";
		}
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$sorts = $this->sort->show($condition,$limit);
		foreach($sorts as $k=>$v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function get_scenic()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$sorts = $this->scenic->show($condition,$limit);
		foreach($sorts as $k=>$v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	
	public function get_scenic_survey()
	{
		global $gGlobalConfig;
		$curl = new curl($gGlobalConfig['App_news']['host'],$gGlobalConfig['App_news']['dir'].'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
	    $curl->addRequestData('a','show');
		$curl->addRequestData('para', $this->input['scenic_id']);
		$ret['data'] = $curl->request('news.php');
		foreach($ret as $k=>$v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	private function get_condition()
	{	
		$condition = '';
	
		if(intval($this->input['fid']))
		{
			$condition .=" AND fid =". intval($this->input['fid']);
		}
		else
		{
			$condition .=" AND fid = 0";
		}
		return $condition;
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright 	ho	gesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new scenicApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
