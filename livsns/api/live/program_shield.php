<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_shield.php 37890 2014-06-25 01:39:03Z develop_tong $
***************************************************************************/
define('WITHOUT_DB', true);
define('MOD_UNIQUEID','program_shield');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('IS_READ', true);
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class programShieldApi extends outerReadBase
{
	private $mProgramShield;
	function __construct()
	{
		parent::__construct();

		include_once CUR_CONF_PATH . 'lib/program_shield.class.php';
		$this->mProgramShield = new programShield();
		
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function show_bak()
	{
		$channel_id = intval($this->input['channel_id']);
		$start_time = intval($this->input['start_time']);
		
		$ret = array();
		if ($channel_id && $start_time)
		{
			$ret = $this->mProgramShield->get_shield_by_time($channel_id, $start_time);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_shield_zone()
	{
		$channel_id = $this->input['channel_code'];
		$dates = $this->input['dates'];
		if (!$dates)
		{
			$dates = date('Y-m-d');
		}
		if (!@include(CACHE_DIR . 'channel/' . $channel_id . '.php'))
		{
			$this->db = hg_ConnectDB();
			include_once CUR_CONF_PATH . 'lib/channel.class.php';
			$mChannel = new channel();
			$mChannel->cache_channel($channel_id);
			@include(CACHE_DIR . 'channel/' . $channel_id . '.php');
		}
		if (!$channel_info['channel'])
		{
			$this->errorOutput('NO_CHANNEL_NAME');
		}
		if (!$channel_info['channel']['status'])
		{
			$this->errorOutput('CHANNEL_STOP');
		}
		$program_shield_dir = $this->settings['program_shield_dir'] ? $this->settings['program_shield_dir'] : 'program_shield';
		$dir  = $dates;
		$cache_file 	  = CACHE_DIR . $program_shield_dir . '/' . $dir . '/' . $channel_info['channel']['code'] . '.php';
		if (!@include($cache_file))
		{
			$this->db = hg_ConnectDB();
			include_once (CUR_CONF_PATH . 'lib/program_shield.class.php');
			$mProgramShield = new programShield();
			$mProgramShield->cache_program_shield($channel_info['channel']['id'], $dates, $channel_info['channel']['code']);
			include($cache_file);
		}
		if (!$program_shield_zone)
		{
			$program_shield_zone = array();
		}
		foreach ($program_shield_zone AS $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}

	public function show()
	{
		//$this->settings['bantype'] = 'player';
		if ($this->settings['bantype'] != 'player') //�ǲ����������������
		{
			$this->addItem(array());
			$this->output();
		}
		$channel_id = intval($this->input['channel_id']);
		$start_time = intval($this->input['start_time']);
		$start_time = $start_time ? $start_time : time();
		
		$ret = array();
		if ($channel_id && $start_time)
		{
			$dates = date('Y-m-d', $start_time);
			$program_shield_dir = $this->settings['program_shield_dir'] ? $this->settings['program_shield_dir'] : 'program_shield';
			
			$program_shield = $this->get_shield_info($channel_id, $dates, $program_shield_dir);
	
			if (!empty($program_shield))
			{
				foreach ($program_shield AS $v)
				{
					if ($v['start_time'] <= $start_time && ($v['start_time'] + $v['toff']) >= $start_time)
					{
						$ret = $v;
						break;
					}
				}
			}
			$this->addItem($ret);
		}
		$this->output();
	}
	
	private function get_shield_info($channel_id, $dates, $program_shield_dir = 'program_shield', $field = ' * ')
	{
		if (!$channel_id || !$program_shield_dir || !$dates)
		{
			return false;
		}
		$times = strtotime($dates);
		$dir  = $dates;
		
		$dir 	  = CACHE_DIR . $program_shield_dir . '/' . $dir;
		$filename = $channel_id . '.php';
		
		$return = array();
		if (is_file($dir . '/' . $filename))
		{
			include $dir . '/' . $filename;
			$return = $program_shield;
		}
		else 
		{
			$this->db = hg_ConnectDB();
			$sql = "SELECT {$field} FROM " . DB_PREFIX . "program_shield ";
			$sql.= " WHERE channel_id = " . $channel_id;
			$sql.= " AND dates = '" . $dates . "'";
			$q = $this->db->query($sql);
			
			$return = array();
			while ($row = $this->db->fetch_array($q))
			{
				$return[] = $row;
			}
			
			if (!is_dir($dir))
			{
				hg_mkdir($dir);
			}
			
			$content = '<?php
				$program_shield = ' . var_export($return, 1) . ';
			?>';
			hg_file_write($dir . '/' . $filename, $content);
		}
		
		return $return;
	}

	public function count()
	{
		
	}
	public function detail()
	{
		
	}
	public function verifyToken()
	{		
	}
}

$out = new programShieldApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>