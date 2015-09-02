<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 4234 2011-07-28 05:14:16Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID', 'vod');
class vodConfig extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function index()
	{
		
	}
	
	function count()
	{
		
	}

	/**
	 * 显示
	 */
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();

		$sql = "SELECT * FROM ".DB_PREFIX."vod_config  WHERE 1 " . $condition . " ORDER BY config_order_id  DESC  ".$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['is_use'] = $r['is_use']?'是':'否';
			$r['is_open_water'] = $r['is_open_water']?'<font color="blue">是</font>':'<font color="red">否</font>';
			$this->addItem($r);
		}
			
		$this->output();	
	}
	
	public function get_condition()
	{
		$condition = "";
		if($this->input['id'])
		{
			$condition .= " AND id = '".intval($this->input['id'])."'";
		}
		
		if($this->input['k'] || urldecode($this->input['k'])== '0')
		{
			$condition .= ' AND  name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		/*输出格式*/
		if($this->input['output_format'])
		{
			$condition .= " AND output_format = '".urldecode($this->input['output_format'])."'";
		}
		
		/*编码格式*/
		if($this->input['codec_format'])
		{
			$condition .= " AND codec_format = '".urldecode($this->input['codec_format'])."'";
		}
		
		/*编码质量*/
		if($this->input['codec_profile'])
		{
			$condition .= " AND codec_profile = '".urldecode($this->input['codec_profile'])."'";
		}
		
		/*名称匹配*/
		if($this->input['name'])
		{
			$condition .= ' AND name LIKE "%'.urldecode($this->input['name']).'%"';
		}
		
		if($this->input['is_use'])
		{
			$condition .= " AND is_use = '".urldecode($this->input['is_use'])."'";
		}

		return $condition;
	}
	
	public function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."vod_config  WHERE id = '".intval($this->input['id'])."'"; 
		$return = $this->db->query_first($sql);
		$this->addItem($return);
		$this->output();
	} 

}

$out = new vodConfig();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>