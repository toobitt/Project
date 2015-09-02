<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');
class configuare extends configuareFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create_publish_table()
	{
		$planret = array();
		if($this->settings['App_livmedia'])
		{
			include_once(ROOT_PATH.'lib/class/curl.class.php');
			$curl =  new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir']);
			
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('a', 'settings');
			$ret = $curl->request('configuare.php');
			$planret[1] = $ret['define']['PUBLISH_SET_ID'];
		}
		$this->addItem_withkey('message', 'success');
		$this->addItem_withkey('ret', $planret);
		$this->output();		
	}

	public function settings()
	{
		$dbconfig = $this->dbconfig;
		unset($dbconfig['pass']);
		$this->addItem_withkey('db', $dbconfig);
		$settings = $this->settings;
		$const = $this->get_const();
		if ($const)
		{
			foreach ($const AS $k => $c)
			{
				$define[$k] = $c;
			}
		}
		
		$freespace = array();
		if (function_exists('disk_free_space'))
		{
			$rdiskspace = @disk_free_space('/');
			$cdiskspace = @disk_free_space(CUR_CONF_PATH);
			$freespace = array(
				'rootfree' => array(
						'size' => $rdiskspace,
						'text' => hg_fetch_number_format($rdiskspace, true),
			),
				'curfree' => array(
						'size' => $cdiskspace,
						'text' => hg_fetch_number_format($cdiskspace, true),
			)
			);
		}	
		if($this->input['is_writes'])//0为获取不到接口默认为可写权限，-1为获取到接口的情况下不可写。1为获取到接口的情况下可写。
		{
			$data_file_purview = 0;
			$cache_file_purview = 0;
			$config_file_purview = 0;
			if(is_writable(DATA_DIR))
			{
				$data_file_purview = 1;
			}
			else {
				$data_file_purview = -1;
			}	
			if(is_writable(CACHE_DIR))
			{
				$cache_file_purview = 1;
			}
			else {
				$cache_file_purview = -1;
			}
			if(is_writable(CONF_FILE))
			{
				$config_file_purview = 1;
			}
			else {
				$config_file_purview = -1;
			}
			$this->addItem_withkey('data_file_purview', $data_file_purview);
			$this->addItem_withkey('cache_file_purview',$cache_file_purview);
			$this->addItem_withkey('config_file_purview',$config_file_purview);
		}
		//此处为了版本兼容
		if(!isset($define['TARGET_VIDEO_DOMAIN']))
		{
			$define['TARGET_VIDEO_DOMAIN'] = $settings['videouploads']['host'];
		}
		
		$this->addItem_withkey('api_dir', realpath(CUR_CONF_PATH));
		$this->addItem_withkey('define', $define);
		$this->addItem_withkey('base', $settings);
		$this->addItem_withkey('dir_index', $this->setCurDirIndex($define['TARGET_DIR']));		
		$this->addItem_withkey('debuged', DEBUG_MODE);
		$start_time = microtime();
		$this->db = hg_ConnectDB();
		//$this->db->mErrorExit = true;
		$totaltime = $this->cal_runtime($start_time);
		if ($this->db)
		{
			$connected = 1;
		}
		else
		{
			$connected = 0;
		}
		$this->addItem_withkey('dbconnected', $connected);
		$this->addItem_withkey('connect_time', $totaltime);
		$this->output();
	}
	
	protected function settings_process()
	{
		//检测提交过来的存储目录与域名是否对应
		$source_dir 	= $this->input['define']['UPLOAD_DIR'];
		$target_dir 	= $this->input['define']['TARGET_DIR'];
		$source_domain 	= $this->input['define']['SOURCE_VIDEO_DOMIAN'];
		$target_domain 	= $this->input['define']['TARGET_VIDEO_DOMAIN']; 
		
		if ($source_dir)
		{
			if(!file_exists($source_dir) || !is_writeable($source_dir))
			{
				$this->errorOutput('视频源目录不存在或者不可写，请设置可写权限');
			}
			//分别写文件到源目录和目标目录用于检测域名连通性
			$this->check_connect($source_dir,$source_domain,'source.txt');
		}
		
		if ($target_dir)
		{
			if(!file_exists($target_dir) || !is_writeable($target_dir))
			{
				$this->errorOutput('视频目标目录不存在或者不可写，请设置可写权限');
			}
			$this->check_connect($target_dir,$target_domain,'target.txt');
		}
		//此处为了兼容老版本
		if(!defined("TARGET_VIDEO_DOMAIN"))
		{
			$this->input['base']['videouploads'] = array(
				 'protocol' =>'http://',
			     'host' 	=>$target_domain,
			     'dir' 		=>'',
			);
			unset($this->input['define']['TARGET_VIDEO_DOMAIN']);
		}
		@copy(ROOT_PATH . 'crossdomain.xml', $target_dir . 'crossdomain.xml');
		//设置当前视频播放的目录索引值(拆条那边涉及到视频存储目录改变，视频播放问题)
		$this->setCurDirIndex($target_dir, 1);
	}
	
	//获取当前视频播放的目录索引值(拆条那边涉及到视频存储目录改变，视频播放问题)
	public function setCurDirIndex($target_dir = '', $alter = 0)
	{
		if(!$target_dir)
		{
			return 0;
		}
		
		$sql = "SELECT index_id FROM " .DB_PREFIX. "dir_index WHERE taget_dir = '" .$target_dir. "'";
		$arr = $this->db->query_first($sql);
		if($arr)
		{
			$num = $arr['index_id'];
		}
		else 
		{
			//查询出总数
			$sql = "SELECT COUNT(*) AS total FROM " .DB_PREFIX. "dir_index";
			$total = $this->db->query_first($sql);
			if($total && $total['total'])
			{
				$num = intval($total['total']);
			}
			else 
			{
				$num = 0;
			}

			$sql = "INSERT INTO " .DB_PREFIX. "dir_index SET taget_dir = '" .$target_dir. "',index_id = '" .$num. "' ";
			$this->db->query($sql);
		}
		
		if ($alter)
		{
			//修改dir_index的默认值
			$sql = "ALTER TABLE  " . DB_PREFIX . "vodinfo CHANGE  `dir_index`  `dir_index` TINYINT( 1 ) NOT NULL DEFAULT  '" .$num. "'";
			$this->db->query($sql);
		}
		return $num;
	}

	//检测连通性
	private function check_connect($dir,$domain,$filename)
	{
		$back = "ok";
		if(!file_exists($dir . $filename))
		{
			file_put_contents(rtrim($dir,'/') . '/' . $filename,$back);
		}
		$ret = @file_get_contents('http://' . ltrim(rtrim($domain,'/'),'http://') . '/' . $filename);
		@unlink(rtrim($dir,'/') . '/' . $filename);
		if($ret != 'ok')
		{
			$this->errorOutput($domain . '域名指向' . $dir . '目录有误');
		}
	}
}
$module = 'configuare';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>