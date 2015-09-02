<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', './');
define('WITH_DB',true);
define('SCRIPT_NAME', 'route2node');
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
require(ROOT_PATH . 'lib/class/nodePrms.class.php');
class route2node extends uiBaseFrm
{
	private $node;
	function __construct()
	{
		parent::__construct();
		$this->node = new NodePrms();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$node_data = $this->node->getNodeDataByMidN($this->input['mid'], $this->input['nodevar'],true);
		if($node_data == -1)
		{
			$this->ReportError(UNKNOWN_NODEVAR);
		}
		$this->jsondata($node_data);
	}
	public function get_node_data()
	{
		$node_data = $this->node->getNodeDataByAppN($this->input['app'], $this->input['nodevar']);
		$this->jsondata($node_data);
	}
	protected function jsondata($node_data = array())
	{
		$format = $this->input['format'];
		switch ($format)
		{
			case 'array':
				{
					print_r($node_data);break;
				}
			default:
				{
					echo json_encode($node_data);
				}
		}
		exit;
	}
	public function column_node()
	{
		$curl = new curl($this->settings['App_publishcontent']['host'],$this->settings['App_publishcontent']['dir'].'admin/');
		$curl->initPostData();
		$curl->addRequestData('a', 'get_all_columns');
		$return = $curl->request('column.php');
		if(is_array($return) && !$return['ErrorCode'])
		{
			exit(json_encode($return));
		}
		exit($return);
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>