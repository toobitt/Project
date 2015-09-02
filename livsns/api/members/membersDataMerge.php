<?php
/**
 *
 * 两套会员数据合并函数
 * @var unknown_type
 */
define('MOD_UNIQUEID','members');//模块标识
define('SCRIPT_NAME', 'membersDataMerge');
require('./global.php');
require(ROOT_PATH.'lib/class/curl.class.php');
define('LENGTH',500);
ini_set('max_execution_time', 3600);
ini_set('memory_limit', '1024M');
class membersDataMerge extends adminBase
{
	protected $curl;
	public function __construct()
	{
		parent::__construct();
		if(!$this->curl)
		{
			return array();
		}
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$progressPath 	= CACHE_DIR . 'progress.txt';
		$totalPath 		= CACHE_DIR . 'total.txt';
		$is_next = true;
		if(file_exists($progressPath)&&file_exists($totalPath))
		{
			$progress = file_get_contents($progressPath);
			$total = file_get_contents($totalPath);
		}
		else
		{
			file_put_contents($progressPath,0);
			$sql = 'SELECT max(member_id) as max,min(member_id) as min FROM '.DB_PREFIX.'member';
			$max_min = $this->db->query_first($sql);
			file_put_contents($totalPath, $max_min['max']);
			file_put_contents($progressPath, $max_min['min']);
			$progress = $max_min['min']-1;
			$total = $max_min['max'];
		}
		$newlegth = intval($progress + LENGTH);
		if($newlegth > intval($total))
		{
			$newlegth = $total;
			$is_next = false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'member where ( member_id >'.$progress.') AND ( member_id <= '.$newlegth.') order by member_id asc ';
		$query = $this->db->query($sql);
		$member = array();
		$member_id = array();
		while ($row = $this->db->fetch_array($query))
		{
			$member[$row['member_id']]	=  $row;
			$member_id[] = $row['member_id'];
		}
		if($member_id)
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'member_bind where member_id IN ('.implode(',', $member_id).') order by member_id asc ';
			$query = $this->db->query($sql);
			$member_bind = array();
			while ($row = $this->db->fetch_array($query))
			{
				$member_bind[$row['member_id']][]	=  $row;
			}
		}

		if (!empty($member))
		{
			$source_db = array(
				'host'     => 'localhost',
				'user' 	   => 'root',
				'pass' 	   => 'hogesoft',
				'database' => 'jn_members2',
				'charset'  => 'utf8',	
				'pconncet' => '0',
				'dbprefix'=>'m2o_',
			);
			$_sourceDB = new db();
			$_sourceDB->connect($source_db['host'], $source_db['user'], $source_db['pass'], $source_db['database'], $source_db['charset'], $source_db['pconnect'], $source_db['dbprefix']);
			foreach ($member as $key=>$val)
			{
				$insertData = $val;
				if(!$val['guid'])
				{
					$insertData['guid'] = guid();
				}
				$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'member where member_name = \''.$insertData['member_name'].'\' AND type = \''.$insertData['type'].'\'';
				$isInsert = $_sourceDB->query_first($sql);
				if($isInsert['total']>0)
				{
					file_put_contents(CACHE_DIR.'error_member.txt', var_export($insertData,1),FILE_APPEND);
					continue;
				}
				unset($insertData['member_id']);
				$cmd = $replace ? 'REPLACE INTO ' : 'INSERT INTO ';
				$sql = $cmd.DB_PREFIX . 'member SET ' . $this->setField($insertData);
				$_sourceDB->query($sql);
				$iid = $_sourceDB->insert_id();
				if (!$iid)
				{
					break;
				}
				$sql = 'INSERT INTO '.DB_PREFIX.'member_count (
						u_id
						) 
						VALUES(
						'.$iid.'
						)';
				$_sourceDB->query($sql);
								
				if($_member_bind = $member_bind[$val['member_id']])
				{
					foreach ($_member_bind as $k => $v)
					{
						$inuc = 0;
						$v['member_id'] = $iid;
						if($v['type']=='m2o'&&$v['inuc']==0)
						{
							$v['platform_id'] = $iid;
						}
						if($v['type']=='uc')
						{
							$inuc = $v['platform_id'];
						}
						else if ($v['inuc']!=0)
						{
							$inuc = $v['inuc'];
						}
						$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'member_bind where member_id = '.$v['member_id'].' AND platform_id = \''.$v['platform_id'].'\' AND type = \''.$v['type'].'\'';
						$isInsertBind = $_sourceDB->query_first($sql);
						if($isInsertBind['total']>0)
						{
							file_put_contents(CACHE_DIR.'error_memberbind.txt', var_export($v,1),FILE_APPEND);
							continue;
						}
						$cmd = $replace ? 'REPLACE INTO ' : 'INSERT INTO ';
						$sql = $cmd.DB_PREFIX . 'member_bind SET ' . $this->setField($v);
						$_sourceDB->query($sql);
						if($inuc)
						{
							$sql = 'UPDATE '.DB_PREFIX.'member_bind SET inuc =\''.$inuc .'\' WHERE member_id = '.$iid;
							$_sourceDB->query($sql);
						}
					}
				}
			}
			$_sourceDB->close();
			file_put_contents($progressPath,$newlegth);
			if($is_next)
			{
				$percent = round(intval($newlegth)/intval($total) * 100,2) . "%";
				echo $message = '系统正在合并数据，别打扰唉...' . $percent;
				$this->redirect('membersDataMerge.php');
			}
			echo "数据合并完成";exit();
		}
		else echo "已经合并完成,请勿重复合并数据";exit();
	}

	private function setField ($insertData)
	{
		$fields = '';
		if(is_array($insertData))
		foreach ($insertData as $k => $v)
		{
			if (is_string($v))
			{
				$fields .= $k . "='" . $v . "',";
			}
			elseif (is_int($v) || is_float($v))
			{
				$fields .= $k . '=' . $v . ',';
			}
		}
		$fields = rtrim($fields, ',');
		return $fields;
	}

	private function redirect($url)
	{
		$jsStr  = "<SCRIPT LANGUAGE='JavaScript'>";
		$jsStr .= "window.location.href='" .$url. "'";
		$jsStr .= "</SCRIPT>";
		echo $jsStr;
	}
}
include ROOT_PATH  . 'excute.php';