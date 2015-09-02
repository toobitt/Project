<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: recache.class.php 1524 2011-01-04 09:46:16Z yuna $
***************************************************************************/

class crond
{
	private $mCronDir = 'cron/';
	private $mCronCMD = 'LivMcpCron.py';
	private $mPsCMD = '/usr/bin/pgrep ';
	function __construct()
	{
	}
	
	function __destruct()
	{
	}
	
	public function setCronCmd($cmd)
	{
		$this->mCronCMD = $cmd;
	}
	public function getPid()
	{
		$cmd = $this->mPsCMD . $this->mCronCMD;
		exec($cmd, $out, $t);
		return intval($out[0]);
	}

	public function isRun()
	{
		if ($this->getPid())
		{
			return true;
		}
		return false;
	}

	public function start()
	{
		if ($pid = $this->getPid())
		{
			return $pid;
		}
		$cmd = '/usr/bin/nohup ' . ROOT_PATH . $this->mCronDir . $this->mCronCMD . ' > /dev/null &';
		exec($cmd, $out, $t);
		return $this->getPid();
	}

	public function stop()
	{
		$pid = $this->getPid();
		if ($pid)
		{
			$cmd = '/bin/kill -9 ' . $pid;
			exec($cmd, $out, $t);
		}
		return $this->getPid();
	}
}
?>