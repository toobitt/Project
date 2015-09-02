<?php 
function hg_mkdir($dir)
{
	if (!is_dir($dir))
	{
		if(!mkdir($dir, 0777, 1))
		{
			return false;//创建目录失败
		}
	}
	return true;
}
class LoadConfig
{
	/*private $mConfigAddr = array(
		'protocol' => 'http://', 
		'host' => 'mcp.hoolo.tv',
		'port' => 80,
		'dir' => '',
		'filename' => 'config.php',
		'token' => 'afafadfwqwqwee',
		);
	private $mConfigAddr = array(
		'protocol' => 'http://', 
		'host' => 'mcp.thmz.com',
		'port' => 80,
		'dir' => '',
		'filename' => 'config.php',
		'token' => 'afafadfwqwqwee',
		);
	*/	
	private $mConfigAddr = array(
		'protocol' => 'http://', 
		'host' => 'localhost',
		'port' => 80,
		'dir' => 'livworkbench/',
		'filename' => 'config.php',
		'token' => 'afafadfwqwqwee',
		);
	private $mRequestData = array();
	private $mVarName = '';
	private $mConfigDirName = 'conf';
	private $mConfigDirs = array();
	private $mRootDir = '/';
	private $mConfigs = array();
	function __construct()
	{
		$this->initRequestData();
		$this->mRootDir = dirname(__FILE__) . '/';
	}

	function __destruct()
	{
	}
	
	public function replace()
	{
		$this->findConfDir();
		echo '<a href="?a=viewConfig">查看配置</a><br />';
		 ?>
		  <form action="?a=doreplace" method="post" id="cform" name="cform" onsubmit="return confirm('确认全部替换吗？');">

		  <input type="text" name="source" value="" size="50" />
		  <input type="text" name="target" value="" size="50" />
		  <div>
			<input type="submit" name="s" value="全部替换" />
		   </div>
		  </form>
		 <?php
		exit;
	}

	
	public function doreplace()
	{
		$this->findConfDir();
		$source = $_REQUEST['source'];
		$target = $_REQUEST['target'];
		echo '<a href="?a=viewConfig">查看配置</a><br />';
		foreach ($this->mConfigDirs AS $i => $dir)
		{
			if (!is_file($dir . 'config.php') && !is_file($dir . 'global.conf.php'))
			{
				continue;
			}
			$filename = $dir . 'config.php';
			$content = @file_get_contents($filename);
			if (!$content)
			{
				$filename = $dir . 'global.conf.php';
				$content = @file_get_contents($dir . 'global.conf.php');
			}
			$content = str_replace($source, $target, $content);
			file_put_contents($filename, $content);
			echo $filename . ' replaced<br />';
		}
		exit;
	}
	
	public function configfile()
	{
		$this->findConfDir();
		$target = '../publish/getfiles/config/';
		//@mkdir($target, 0777, 1);
		echo '<a href="?a=viewConfig">查看配置</a><br />';
		foreach ($this->mConfigDirs AS $i => $dir)
		{
			if (!is_file($dir . 'config.php') && !is_file($dir . 'global.conf.php'))
			{
				continue;
			}
			$filename = $dir . 'config.php';
			$content = @file_get_contents($filename);
			if (!$content)
			{
				$filename = $dir . 'global.conf.php';
				$content = @file_get_contents($dir . 'global.conf.php');
			}
			$filename = str_replace('publish/livsns','publish/getfiles/config', $filename);
			$filedir = explode('/', $filename);
			unset($filedir[count($filedir)-1]);
			@mkdir(implode('/', $filedir), 0777, 1);
			file_put_contents($filename, $content);
			echo $filename . ' replaced<br />';
		}
		exit;
	}

	public function viewConfig()
	{
		$this->findConfDir();
		echo '<a href="?a=viewConfig">查看配置</a>&nbsp;&nbsp;&nbsp;<a href="?a=replace">替换配置</a><br />';
		foreach ($this->mConfigDirs AS $i => $dir)
		{
			if (!is_file($dir . 'config.php') && !is_file($dir . 'global.conf.php'))
			{
				continue;
			}
			echo ($i + 1) . '.&nbsp;&nbsp;<a href=?a=viewDetail&dir=' . $dir . '>' . $dir . '</a><br />';
		}
		exit;
	}

	public function writeconfig()
	{
		$dir = $_REQUEST['dir'];
		if (!is_file($dir . 'config.php') && !is_file($dir . 'global.conf.php'))
		{
			header('Location:?a=viewConfig');
		}
		$content = $_REQUEST['content'];
		if ($content)
		{
			if (is_file($dir . 'config.php'))
			{
				file_put_contents($dir . 'config.php', $content);
			}
			else
			{
				file_put_contents($dir . 'global.conf.php', $content);
			}
		}
		header('Location:?a=viewDetail&dir=' . $dir);
	}
	public function viewDetail()
	{
		$dir = $_REQUEST['dir'];
		echo '<a href="?a=viewConfig">查看配置</a><br />';
		$config = @file_get_contents($dir . 'config.php');
		if (!$config)
		{
			$config = @file_get_contents($dir . 'global.conf.php');
		}
		 ?>
		  <form action="?a=writeconfig" method="post" id="cform" name="cform" onsubmit="return confirm('确认无误保存吗？');">

		  <input type="hidden" name="dir" value="<?php echo $dir;?>" />
		  <input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
		  <textarea rows="30" cols="120" name="content"><?php echo $config;?></textarea>
		  <div>
			<input type="submit" name="s" value="保存" />
		   </div>
		  </form>
		 <?php
		exit;
	}

	private function findConfDir($dir = '')
	{
		if (!$dir)
		{
			$dir = $this->mRootDir;
		}
		$handle = dir($dir);
		while ($file = $handle->read())
		{
			if($file == '.' || $file == '..' || $file == '.svn')
			{
				continue;
			}
			if (is_dir($dir . $file))
			{
				if ($file == $this->mConfigDirName)
				{
					$this->mConfigDirs[] = $dir . $file . '/';
				}
				else
				{
					$this->findConfDir($dir . $file . '/');
				}
			}
		}
	}

	private function parseConfig($content)
	{
		$eregtag = '/\{config:(.*?(?=[\/\}]))\}/ise';
		$content = preg_replace($eregtag, "\$this->getConfig('\\1')", $content);
		return $content;
	}

	private function getConfig($name)
	{
		$index = explode('.', $name);
		$config = $this->mConfigs[$index[0]];
		unset($index[0]);
		if ($index)
		{
			$tmpconfig = $config;
			foreach ($index AS $key)
			{
				$config = $tmpconfig[$key];
				$tmpconfig = $config;
			}
		}
		$str = $this->configToStr($config);
		return $str;
	}

	private function configToStr($config)
	{
		if (is_array($config))
		{
			$str = 'array(';
			foreach ($config AS $k => $v)
			{
				if (is_array($v))
				{
					$s = $this->configToStr($v);
				}
				else
				{
					$s = "'$v'";
				}
				$str .= "'{$k}' => " . $s . ",\n";
			}
			$str .= ')';
			return $str;
		}
		else
		{
			return $config;
		}
	}

	/**
	* 获取配置
	*
	*/
	private function fetchConfig()
	{
		$config_uri = $this->mConfigAddr['protocol'] . $this->mConfigAddr['host'] . ':' . $this->mConfigAddr['port'] . '/'. $this->mConfigAddr['dir'] . $this->mConfigAddr['filename'];
		$config = $this->post($config_uri, $this->mRequestData);
		$this->mConfigs = json_decode($config, true);
	}

	/**
	* 初始化提交数据
	*
	*/
	private function initRequestData()
	{
		$this->mRequestData = array();
	}

	private function addRequestData($name, $value)
	{
		$this->mRequestData[$name] = urlencode($value);
	}

	/**
	* 获取模板信息
	*
	*/
    private function post($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml; charset=utf-8"));
		//print_r( $post_data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if ($ret == null)
        {
        	$ret = '未获取到任何配置';
        }

        return $ret;
    }
}
header('Content-Type:text/html; charset=utf-8');
$load = new LoadConfig();
$func = $_REQUEST['a'];
if (!method_exists($load, $func))
{
	$func = 'viewConfig';	
}
$load->$func();
?>