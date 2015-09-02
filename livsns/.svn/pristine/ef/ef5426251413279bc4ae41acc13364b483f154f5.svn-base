<?php
/***************************************************************************
* $Id: verify_code_auto.php 36459 2014-04-17 02:13:51Z jiyuting $
***************************************************************************/
require('../admin/global.php');
define('MOD_UNIQUEID','updateVoteNum');
require_once(CUR_CONF_PATH . 'lib/survey_mode.php');
class updateVoteNum extends cronBase
{
	public function __construct()
	{
		$this->mode = new survey_mode();
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '更新投票数据',	 
			'brief' => '更新投票数据',
			'space' => '600',//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function run()
	{
		if(!($this->settings['redis']))
		{
			exit();
		}
		$this->redis = new Redis();
		$this->redis->connect($this->settings['redis']['redis1']['host'], $this->settings['redis']['redis1']['port']);
		$this->redis->auth(REDIS_KEY);
		$allcount = $this->redis->keys('g_*');
		$allproblem = $this->redis->keys('s_*');
		
		if($allcount)
		{
			foreach ($allcount as $value)
			{
				$id = substr($value,2);
				if($id && is_numeric($id))
				{
					$id = intval($id);
					$data[$id] = $this->redis->get($value);
				}
			}
			if($data)
			{
				foreach ($data as $id => $count)
				{
					$this->mode->update($id,'survey', array( 'submit_num'	=> $count,'used_survey_id'=>$count));
				}
			}
		}
		if($allproblem)
		{
			foreach ($allproblem as $value)
			{
				$id = substr($value,2);
				if($id && is_numeric($id))
				{
					$sv = $this->redis->hgetall($value);
					if($sv)
					{
						foreach ($sv as $k=>$v)
						{
							if(substr($k,2))
							{
								$kk = explode('_',substr($k,2));
								if($kk[1] && $kk[0] && is_numeric($kk[1]) && is_numeric($kk[0]))
								{
									if($kk[1] != -1)
									{
										$oData[$kk[1]] = $v;
									}
									$pData[$kk[0]] += intval($v);
								}
							}
						}
					}
				}
			}
			if($oData)
			{
				foreach ($oData as $k=>$v)
				{
					if($v)
					{
						$this->mode->update($k, 'options',array('total'=>$v));
					}
				}
			}
			if($pData)
			{
				foreach ($pData as $k=>$v)
				{
					if($v)
					{
						$this->mode->update($k, 'problem',array('counts'=>$v));
					}
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
}

$out = new updateVoteNum();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>