<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: alive.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
define('WITHOUT_DB', true);
define('MOD_UNIQUEID','alive');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
define('IS_READ', true);
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class aliveApi extends outerReadBase
{
	private $mLivemms;
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		$server_info   = $this->get_server_config();
		$server_config = $server_info[@array_rand($server_info, 1)];
		
		$host 		= $server_config['host'];
		$output_dir = $server_config['output_dir'];
		$type 		= $server_config['type'];
		
		$ntpTime = 0;
		if ($host && $output_dir && $type == 'wowza')
		{
			$ret_ntpTime = $this->mLivemms->outputNtpTime($host, $output_dir);
			
			if ($ret_ntpTime['result'])
			{
				$ntpTime = $ret_ntpTime['ntp']['utc'];
			}
		}
		
		$return = array(
			'current' => $ntpTime ? $ntpTime : TIMENOW . '000',
		);
		
		$this->addItem($return);
		$this->output();
	}

	/**
	 * 生成缓存文件
	 * Enter description here ...
	 */
	private function get_server_config()
	{
		$alive_filename = $this->settings['alive_filename'] ? $this->settings['alive_filename'] : 'alive';
		$filename 		= $alive_filename . '.php';
		
		if (is_file(CACHE_DIR . $filename))
		{
			include CACHE_DIR . $filename;
		}
		else
		{
			$this->db = hg_ConnectDB();
			$sql = "SELECT id, host, output_dir, input_port, type FROM " . DB_PREFIX . "server_config ";
			$sql.= " WHERE status = 1 ORDER BY id DESC ";
			$q = $this->db->query($sql);
			
			$return = array();
			while ($row = $this->db->fetch_array($q))
			{
				$row['host'] = $row['host'] . ':' . $row['input_port'];
				$return[] = $row;
			}
			
			$content = '<?php
				if (!IS_READ)
				{		
					exit();
				}
				$return = ' . var_export($return, 1) . ';
			?>';
			hg_file_write(CACHE_DIR . $filename, $content);
		}
	
		return $return;
	}
	
	public function count()
	{
		
	}
	public function detail()
	{
		
	}
}

$out = new aliveApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>