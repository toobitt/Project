<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auto_vod_record.php 481 2012-01-14 01:13:19Z repheal $
***************************************************************************/
define('WITH_DB', false);
define('WITHOUT_LOGIN', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'fetchFile');
require('../global.php');
require('./upgrade.frm.php');
set_time_limit(0);

class fetchFile extends upgradeFrm
{
	private $files = array();
	private $fiter_files = array();
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$app = $this->mApp;
		$upgrade = !($this->input['install']);
		$product_dir = $this->mProductDir;
		if ($app == 'livcmscp' && !$upgrade)
		{
			copy('/web/publish_product/livcms.zip', 'temp/livcmscp.zip');
			
			$ret = array(
				'files' => 	'livcmscp.zip',
				'zip' => 'temp/livcmscp.zip',
			);
			echo json_encode($ret);
			exit;
		}
		if ($app == 'livmcp')
		{
			$sourcedir = $product_dir . 'livworkbench';
		}
		elseif($app == 'base')
		{
			$sourcedir = $product_dir . $app;
			$this->fiter_files = array('api', 'admin_api', 'service', 'ucenter', 'ucs', 'ui', 'vui', 'tools' , 'db');
		}
		else
		{
			$sourcedir = $product_dir . $app;
		}
		$sourcedir .= '/' . $this->mVersion . '/';
		if (!$app || !is_dir($sourcedir))
		{
			exit();
		}
		if (in_array($app, array('mobile', 'livmcp', 'livworkbench')))
		{
			$this->fiter_files = array('api');
		}
		$time = $this->input['time'];
		$destdir = 'temp/' . $app . '/';
		
		$cmd = 'rm -Rf ' . $destdir;
		exec($cmd);
		@unlink('temp/' . $app . $upgrade . $time . '.zip');

		$this->get_files($sourcedir, $destdir, $time, $upgrade);
		if ($this->files)
		{
			if (($app == 'livmcp' || $app == 'livworkbench') && !$upgrade)
			{
				@rename($destdir . 'cron/', $destdir . 'crontmp/');
				mkdir($destdir . 'cron/');
				@copy($destdir . 'crontmp/config.py', $destdir . 'cron/config.py');
				@copy($destdir . 'crontmp/LivMcpCron.py', $destdir . 'cron/LivMcpCron.py');
				$cmd = 'rm -Rf ' . $destdir . 'crontmp/';
				exec($cmd);
			}
			$tdir = realpath('temp');
			chdir('temp/' . $app);
			$dir = getcwd();
			$cmd = 'zip -r ' . $tdir  . '/' . $app . $upgrade . $time . '.zip ' . './';
			exec($cmd);
			$ret = array(
				'files' => 	$this->files,
				'zip' => 'temp/' . $app . $upgrade . $time . '.zip',
			);
		}
		else
		{
			$ret = array();
		}
		echo json_encode($ret);
	}

	public function getData()
	{
		$cmd = 'rm -Rf temp/mobile/api/';
	}

	private function get_files($dir, $destdir, $modtime = 0, $update = true)
	{
		static $depth = 0;
		$handle = dir($dir);
		static $mod = 0;
		while ($file = $handle->read())
		{
			if(in_array($file, array('.', '..', '.svn', 'Thumbs.db', 'livtemplates', 'temp', 'install', 'doc', 'access_plat', 'task', 'mapout', 'help', 'source_code', 'm2o', 'hogesoft', 'db')))
			{
				continue;
			}

			if ($this->fiter_files && in_array($file, $this->fiter_files))
			{
				continue;
			}

			if (in_array($file, array( 'cache', 'data', 'uploads', 'certificate', 'cron', 'font')))
			{
				if(!$update)
				{
					@mkdir($destdir . $file, 0777, true);
					
					if (!in_array($file, array('cron', 'font')))
					{
						continue;
					}
				}
				else
				{
					continue;
				}
			}
			$bdir = $dir . "/" . $file;
			if (is_dir($bdir))
			{
				$depth++;
				$this->get_files($bdir, $destdir . $file . '/', $modtime, $update);	
				$depth--;
			}
			else
			{
				if (in_array($file, array('svn_update.sh')))
				{
					continue;
				}
				if (in_array($file, array('config.php','config.py','global.conf.php','nav.conf.php','search_config_publish_content.ini','template.conf.php', 'xs.ini')))
				{
					if($update)
					{
						continue;
					}
				}
				if (filemtime($bdir) > $modtime)
				{
					@mkdir($destdir, 0777, true);
					$this->files[] = $bdir;
					$file_type = strrchr($bdir, '.');
		
					copy($bdir, $destdir . $file);
					$mod++;
				}
			}
		}
		return $mod;
	}
}

include (ROOT_PATH . 'lib/exec.php');
?>
