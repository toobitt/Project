<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel_update.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','live');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class channelApi extends outerUpdateBase
{
	private $mChannel;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channel();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		
	}
	public function update()
	{
	
	}
	public function delete()
	{
		
	}
	
	public function update_beibo()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$data = array(
			'id'	=> $id,
			'beibo'	=> trim($this->input['beibo']),
		);
		$return = $this->mChannel->update($data);
		$this->addItem($return);
		$this->output();
	}
	
	public function update_change()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		$data = array(
			'id'			=> $id,
			'change_id'		=> intval($this->input['change_id']),
			'change_name'	=> trim($this->input['change_name']),
			'change_type'	=> trim($this->input['change_type']),
			'stream_id'		=> intval($this->input['stream_id']),
			'input_id'		=> intval($this->input['input_id']),
		);
		
		$return = $this->mChannel->update($data);
		$this->addItem($return);
		$this->output();
	}
}

$out = new channelApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>