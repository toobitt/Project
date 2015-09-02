<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: base_frm.php 6823 2012-05-28 06:08:48Z zhuld $
 ***************************************************************************/

/**
 * 程序基类
 * @author develop_tong
 *
 */
abstract class configuareFrm extends coreFrm
{
	protected $dbconfig;
	function __construct()
	{
		parent::__construct();
		global $gDBconfig;
		$this->dbconfig = $gDBconfig;
		if (defined('DB_PREFIX'))
		{
			$this->dbconfig['dbprefix'] = DB_PREFIX;
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function __install()
	{
	}

	public function __upgrade()
	{
	}

	public function ping()
	{
		ob_end_clean();
		echo 'ok';
	}
	public function show()
	{
	}

	public function getapp_path()
	{
		$path = realpath('./');
		echo $path;
	}

	public function getDbName()
	{
		$this->db = hg_ConnectDB();
		echo $this->db->dbname;
	}

	public function getInitData()
	{
		$file = 'conf/init.data';
		if (is_file($file))
		{
			echo file_get_contents($file);
		}
	}


	/**
	 * 无需验证授权
	 */
	protected function verifyToken()
	{
	}

	protected  function get_const()
	{
		$content = @file_get_contents('conf/config.php');
		if ($content)
		{
			preg_match_all("/define\('(.*?)'\s*,\s*\'{0,1}(.*?)\'{0,1}\);/is",$content, $const);
			if ($const[1])
			{
				$ret = array_combine($const[1], $const[2]);
				return $ret;
			}
		}
		return array();
	}

	public function setting_group()
	{
		$group = array(
			'base' => '基础设置',	
			'db' => '数据库设置',
		);
		$this->addItem($group);
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
		$this->addItem_withkey('freespace', $freespace);
		$this->addItem_withkey('api_dir', realpath(CUR_CONF_PATH));
		$this->addItem_withkey('define', $define);
		$this->addItem_withkey('base', $settings);
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
	
	protected function cal_runtime($start_time)
	{
		$mtime = explode(' ', microtime());
		$starttime = explode(' ', $start_time);
		$totaltime = sprintf('%.6f', ($mtime[1] + $mtime[0] - $starttime[1] - $starttime[0]));
		return $totaltime;
	}

	/**
	 * 配置更新前处理
	 *
	 */
	protected function settings_process()
	{
	}

	public function doset()
	{
		$this->settings_process();
		//checkbox为空值时表单不会提交过来  手动赋值为空数组
		$db = $this->input['db'];
		$define = $this->input['define'];
		$basesetting = $this->input['base'];
		$content = @file_get_contents('conf/config.php');
		if (!$content)
		{
			$this->errorOutput('CONFIG_FILE_GONE');
		}
		if (!is_writeable('conf/config.php'))
		{
			$this->errorOutput('CONFIG_FILE_NOT_ALLOW_WRITE');
		}
		$db['host'] = $db['host'] ? $db['host'] : $this->dbconfig['host'];
		$db['user'] = $db['user'] ? $db['user'] : $this->dbconfig['user'];
		$db['pass'] = $db['pass'] ? $db['pass'] : $this->dbconfig['pass'];
		$db['dbprefix'] = $db['dbprefix'] ? $db['dbprefix'] : DB_PREFIX;
		$db['database'] = $db['database'] ? $db['database'] : $this->dbconfig['database'];
		$db['pconncet'] = intval($db['pconncet']) ?  intval($db['pconncet']) : $this->dbconfig['pconnect'];
		$string = "\$gDBconfig = array(
'host'     => '{$db['host']}',
'user'     => '{$db['user']}',
'pass'     => '{$db['pass']}',
'database' => '{$db['database']}',
'charset'  => 'utf8',
'pconnect' => '{$db['pconncet']}',
);";
		if ($this->dbconfig)
		{
			$content = preg_replace("/\\\$gDBconfig\s*=\s*array\s*(.*?);/is", $string, $content);
			$content = preg_replace("/define\('DB_PREFIX',\s*'.*?'\);/is","define('DB_PREFIX','{$db['dbprefix']}');", $content);
		}
		else
		{
			if ($this->input['db']['host'])
			{
				$content = preg_replace("/<\?php/is", "<?php\n" . $string, $content);
			}
		}
		if ($define)
		{
			foreach($define AS $k => $v)
			{
				if (is_numeric($v))
				{
					$string = "define('{$k}', $v);";
				}
				else
				{
					$string = "define('{$k}', '{$v}');";
				}
				if (defined($k))
				{
					$content = preg_replace("/define\('$k',\s*.*?\);/is",$string, $content);
				}
				else
				{
					$content = preg_replace("/\?>\n*\s*$/is", "\n" . $string . "\n?>", $content);
				}
			}
		}
		if ($basesetting)
		{
			foreach($basesetting AS $k => $v)
			{
				if (is_array($v))
				{
					$vs = var_export($v, 1);
				}
				else
				{
					$vs = "'$v'";
				}
				if (isset($this->settings[$k]))
				{
					$content = preg_replace("/\\\$gGlobalConfig\['{$k}'\]\s*=\s*(.*?;)/is", "\$gGlobalConfig['{$k}'] =  " . $vs . ';', $content);
				}
				else
				{
					$content = preg_replace("/\?>\n*\s*$/is", "\n\$gGlobalConfig['{$k}'] =  " . $vs . ";\n?>", $content);
				}
				//$this->errorOutput(json_encode($content));
			}
		}

		$configcontent = $content;
		if (defined('INITED_APP') && !INITED_APP || !defined('INITED_APP')) //需要初始化应用而未初始化，进入初始化流程
		{
			$file = CUR_CONF_PATH . 'conf/init.data';
			if (is_file($file))
			{
				$content = file_get_contents($file);
				if ($content)
				{
					preg_match_all('/INSERT\s+INTO\s+(.*?)\(.*?\)\s*;;/is', $content, $match);
					$insertsql = $match[0];
					if ($insertsql)
					{
						$link = @mysql_connect($db['host'], $db['user'], $db['pass']);
						if ($link)
						{
							mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary", $link);
							mysql_select_db($db['database'], $link);
							foreach ($insertsql AS $sql)
							{
								$sql = preg_replace('/INSERT\s+INTO\s+([`]{0,1})liv_/is', 'INSERT INTO \\1' . $db['dbprefix'], $sql);
								mysql_query($sql, $link);
							}
						}
					}
				}
			}
			$content = $configcontent;
			$match = preg_match("/define\('INITED_APP',\s*.*?\s*\);/is", $content);
			if($match)
			{
				$content = preg_replace("/define\('INITED_APP',\s*.*?\s*\);/is","define('INITED_APP', true);", $content);
			}
			else
			{
				$content = preg_replace("/\?>/is", "\ndefine('INITED_APP', true);\n?>", $content);
			}
		}
		$write = @file_put_contents('conf/config.php', $content);


        /***************修改水印设置*****************/

        $watermark = $this->input['watermark'];
        if (!empty($watermark))
        {
            $watermark_id = $watermark['watermark_id'];
            include_once(ROOT_PATH . 'lib/class/material.class.php');
            $this->material = new material();
            $ret = $this->material->setMaterialWater($watermark_id);
    //        file_put_contents('./cache/111.txt', var_export($ret,1));
        }

        /***************修改水印设置*****************/

		$this->addItem_withkey('success', $write);
		$this->output();
	}

	public function get_cron_file()
	{
		$crondir = './cron/';
		if (!is_dir($crondir))
		{
			$this->output();
		}
		$handle = dir($crondir);
		while ($file = $handle->read())
		{
			if(!is_file($crondir . $file) || in_array($file, array('index.php', 'global.php')))
			{
				continue;
			}
			$file_type = strrchr($file, '.');
			if($file_type != '.php')
			{
				continue;
			}
			$this->addItem($file);

		}
		$this->output();
	}

	/**
	 * 获取应用依赖的应用
	 */
	public function related_app()
	{
		$dir = realpath('./');
		$related_app = array();
		$this->search_related_app($dir, $related_app);
		$related_app = array_unique($related_app);
		$this->addItem($related_app);
		$this->output();
	}

	private function search_related_app ($path, &$apps = array())
	{
		$files = array();
		$handle = opendir($path);
		while ($file = readdir($handle)) {
			if ($file == '.' || $file == '..')
			{
				continue;
			}
			if (is_dir($file) && in_array($file, array('admin', 'cron', 'lib'))) {
				$this->search_related_app($path . '/' . $file . '/', $apps);
			}

			$file_type = strrchr($file, '.');
			if($file_type != '.php' || in_array($file, array('index.php', 'global.php')))
			{
				continue;
			}

			$content = file_get_contents($path . '/' . $file);
			$matches = array();
			//匹配 类似include_once(ROOT_PATH . 'lib/class/mateiral.class.php') 字符
			//调用其他模块的接口封装在 ROOT_PATH . 'lib/class/ 类中
			preg_match_all('/[include|include_once|require|require_once]\(ROOT_PATH \. \'(lib\/class\/.*?\.class\.php)\'\)/is', $content, $matches);
			$files = array_merge($files, $matches[1]);
		}
		$files = array_unique($files);

		foreach ((array)$files as $k => $v)
		{
			$refer_content = file_get_contents(ROOT_PATH . $v);
			//匹配inclue文件中是否使用$gGlobalConfig['App_material']应用地址实例话curl
			//有说明使用了改应用的接口, 当前应用依赖于改应用
			preg_match_all('/\$gGlobalConfig\[\'App_(\w*)\'\]/', $refer_content, $app);
			if (!empty($app))
			{
				$app = array_unique($app[1]);
				$apps = array_merge($apps, $app);
			}
		}

	}
	public function get_upload_files()
	{
		$upload_dir = $this->input['upload_dir'];
		$setinterval = $this->input['setinterval'];
		$file_type = urldecode($this->input['file_type']);
		$search_suffix = '';
		$mmin = '';
		if($setinterval)
		{
			$mmin = ' -mmin -' . $setinterval;
		}
		$search_suffix = '';
		if($file_type)
		{
			$file_type_array = explode(',', $file_type);
			foreach($file_type_array as $i=>$type)
			{
				//$search_suffix .= $i==0 ? ' -name "*.'.$type.'" ' : '-o -name "*.'.$type.'" ';
				if($i == 0)
				{
					$search_suffix .= $mmin . ' -name "*.'.$type.'" ';
				}
				else
				{
					$search_suffix .= ' -o ' . $mmin . ' -name "*.'.$type.'" ';
				}
			}
		}
		else
		{
			$search_suffix = $mmin . ' -name "*.*"';
		}
		$file_list = array();
		if($upload_dir)
		{
			$cmd = trim('find '.$upload_dir . $search_suffix);
			@exec($cmd, $file_list);
			
			//file_put_contents(CACHE_DIR . 'debug.txt', $cmd);
		}
		
		$this->addItem($file_list);
		$this->output();
	}
	public function upload2ftp()
	{
		$config = json_decode($this->input['config'], 1);
		$files = json_decode($this->input['files'], 1);
		include_once(ROOT_PATH . 'lib/class/ftp.class.php');
		
		$ftp = new Ftp();
		$server_dir = trim($config['server_dir'], '/');
		
		$app_dir = $config['app_dir'];

		$message = array('error'=>0);
		if(!$ftp->connect($config))
		{
			$message['error'] = 1; 
			$message['message'] = '连接服务器失败['.$config['hostname'].']';
		}
		if($server_dir && !$message['error'])
		{
			if(!$ftp->mkdir($server_dir))
			{
				$message['error'] = 2; 
				$message['message'] = '目标目录不存在且创建失败['.$server_dir.']';
			}
		}
		if(!$files && !$message['error'])
		{
			$message['error'] = 3;
			$message['message'] = '文件列表不存在['.$files.']';
		}
		if(!$message['error'])
		{
			foreach($files as $file)
			{
				if(!file_exists($file))
				{
					//continue;
				}
				//返回上传错误的文件
				$dfile = str_replace($app_dir, '', $file);
				//如果设定了ftp目标目录
				$dfile = $server_dir ? $server_dir . $dfile : $dfile;
				
				$upload_dir = trim(str_replace('/'. basename($file), '', $dfile), '/');
				
				if($upload_dir)
				{
					$ftp->mkdir($upload_dir);
				}
				if(!$ftp->upload($file, $dfile))
				{
					$message['error'] = 4;
					$message['message'][$file] = $dfile;
				}
			}
		}
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($config,1));
		$ftp->close();
		$this->addItem($message);
		$this->output();
	}
}
?>