<?php
/***************************************************************************
* $Id: interactive_channel.php 15387 2012-12-12 06:27:36Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive_channel');
require('global.php');
class interactiveChannelApi extends BaseFrm
{
	private $mInteractive;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$offset 	= $this->input['offset'] ? $this->input['offset'] : 0;			
		$count 		= $this->input['count'] ? intval($this->input['count']) : 20;
		if (isset($this->input['k']))
		{
			$k = urldecode($this->input['k']);
		}
		$channel_info = $this->mInteractive->get_channel_by_id('',$offset, $count, $k);
		
		if (!empty($channel_info))
		{
			foreach ($channel_info AS $channel)
			{
				$this->addItem($channel);
			}
		}
		$this->output();
	}
	
	public function count()
	{
		if (isset($this->input['k']))
		{
			$k = urldecode($this->input['k']);
		}
		$info = $this->mInteractive->get_channel_count($k);
		echo json_encode($info);
	}

}

$out = new interactiveChannelApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>