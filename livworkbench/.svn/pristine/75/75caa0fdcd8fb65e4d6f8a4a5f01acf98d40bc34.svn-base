<?php 
/**
 * 创建目录函数
 *
 * @param $dir 需要创建的目录
 */
function hg_mkdir($dir)
{
	if (!is_dir($dir))
	{
		if(!@mkdir($dir, 0777, 1))
		{
			return false;//创建目录失败
		}
	}
	return true;
}
/**
 * 写文件
 *
 * @return intager 写入数据的字节数
 */
function hg_file_write($filename, $content, $mode = 'rb+')
{
	$length = strlen($content);
	@touch($filename);
	if (!is_writeable($filename))
	{
		@chmod($filename, 0666);
	}

	if (($fp = fopen($filename, $mode)) === false)
	{
		trigger_error('hg_file_write() failed to open stream: Permission denied', E_USER_WARNING);
		
		return false;
	}

	flock($fp, LOCK_EX | LOCK_NB);

	$bytes = 0;
	if (($bytes = @fwrite($fp, $content)) === false)
	{
		$errormsg = sprintf('file_write() Failed to write %d bytes to %s', $length, $filename);
		trigger_error($errormsg, E_USER_WARNING);
		return false;
	}

	if ($mode == 'rb+')
	{
		@ftruncate($fp, $length);
	}

	@fclose($fp);

	// 检查是否写入了所有的数据
	if ($bytes != $length)
	{
		$errormsg = sprintf('file_write() Only %d of %d bytes written, possibly out of free disk space.', $bytes, $length);
		trigger_error($errormsg, E_USER_WARNING);
		return false;
	}

	// 返回长度
	return $bytes;
}
function hg_pre($v) 
{
	echo '<pre>';
	print_r($v);
	echo '</pre>';
	exit;
}
function curl_post($url, $data)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$ret = curl_exec($ch);
	if ($ret == null)
	{
		$ret = '模板文件不存在或网络错误,连不上ui界面服务器';
	}
	return $ret;
}
function fetch_css($name)
{
	$url = TEMPLATE_API;
	$ret = curl_post($url, array(
		'template' => $name, 
		'softvar' => defined('SOFTVAR') ? SOFTVAR : 'm2o',
		'group' => defined('GROUP') ? GROUP : 'default',
		'a' => 'getcss'
	));
	$ret = json_decode($ret, true);
	if (substr(RESOURCE_URL, 0, 7) == 'http://')
	{
		$RESOURCE_URL = RESOURCE_URL;
	}
	else
	{
		//有点小问题，遇到再改
		$RESOURCE_URL = '../' . RESOURCE_URL;
	}
	
	if (is_array($ret))
	{
		foreach ($ret AS $file => $content)
		{
			if ($file)
			{
				if(strpos($file, '/'))
				{
					$filename = strrchr($file, '/');
					$dir = str_replace($filename, '', $file);
					if (!hg_mkdir(CSS_FILE_DIR . $dir . '/'))
					{
						exit(CSS_FILE_DIR . $dir . '/目录创建失败，请检查目录权限.');
					}
				}
				$varpreg = "/{\\$[a-zA-Z0-9_\[\]\-\'\>]+}/";
				$content = preg_replace($varpreg,  $RESOURCE_URL, $content);
				hg_file_write(CSS_FILE_DIR . $file, $content);
			}
		}
	}
}
function fetch_template($name)
{
	$url = TEMPLATE_API;
	$template_file = 'cache/' . $name . '.php';
	$ret = curl_post($url, array(
		'template' => $name, 
		'softvar' => defined('SOFTVAR') ? SOFTVAR : 'm2o',
		'group' => defined('GROUP') ? GROUP : 'default'
	));
	hg_file_write($template_file, $ret);
}
function output_tpl($name, $data = array())
{
	fetch_css($name);
	fetch_template($name);
	$RESOURCE_URL = RESOURCE_URL;
	require 'cache/' . $name . '.php';
}

// 递归初始化用户输入
function hg_init_input($data = array())
{
	$ret = array();
	$data = or_get($data, array_merge($_GET, $_POST));
	foreach ($data as $k => $v)
	{
		$ret[hg_clean_key($k)] = is_array($v) ? hg_init_input($v) : hg_clean_value($v);
	}
	return $ret;
}

/**
 * 过滤数组索引
 */
function hg_clean_key($key)
{
	if (is_numeric($key))
	{
		return $key;
	}
	else if (empty($key))
	{
		return '';
	}

	if (strpos($key, '..') !== false)
	{
		$key = str_replace('..', '', $key);
	}

	if (strpos($key, '__') !== false)
	{
		$key = preg_replace('/__(?:.+?)__/', '', $key);
	}

	return preg_replace('/^([\w\.\-_]+)$/', '\\1', $key);
}

/**
 * 过滤输入的数据
 *
 * @param unknown_type $val
 * @return unknown
 */
function hg_clean_value($val)
{
	if ($_REQUEST['html'])
	{
		$val = preg_replace("/<script/i", "&#60;script", $val);
		return $val;
	}
	if (is_numeric($val))
	{
		return $val;
	}
	else if (empty($val))
	{
		return is_array($val) ? array() : '';
	}
	$val = preg_replace("/<script/i", "&#60;script", $val);

	$pregfind = array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
	$pregreplace = array(' ', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
	$val = str_replace($pregfind, $pregreplace, $val);

	return preg_replace('/\\\(&amp;#|\?#)/', '&#092;', $val);
}

//模仿js的||和&&
function or_get($a, $b)
{
	return $a ? $a : $b;
}
function and_get($a, $b)
{
	return $a ? $b : $a;
}
?>
