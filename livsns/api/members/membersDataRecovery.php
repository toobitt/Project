<?php
/**
 *
 * 此类为了修复老会员导入数据到新会员造成的数据导入错误问题。
 * @var unknown_type
 */
define('MOD_UNIQUEID','members');//模块标识
define('SCRIPT_NAME', 'membersDataRecovery');
require('./global.php');
require(ROOT_PATH.'lib/class/curl.class.php');
define('LENGTH',10);
ini_set('max_execution_time', 3600);
ini_set('memory_limit', '1024M');
class membersDataRecovery extends adminBase
{
	protected $curl;
	protected $_plat_maps = array(
	'1'=>array('type'=>'sina', 'type_name'=>'新浪微博'),
	'2'=>array('type'=>'renren', 'type_name'=>'人人网'),
	'3'=>array('type'=>'tencent', 'type_name'=>'腾讯微博'),
	'4'=>array('type'=>'douban', 'type_name'=>'豆瓣网'),
	'5'=>array('type'=>'netease', 'type_name'=>'网易微博'),
	'6'=>array('type'=>'qq', 'type_name'=>'腾讯QQ'),
	'7'=>array('type'=>'discuz', 'type_name'=>'Discuz论坛'),
	);
	protected $plat_maps = array();
	public function __construct()
	{
		parent::__construct();
		if($this->settings['App_share'])
		{
			$this->curl = new curl($this->settings['App_share']['host'], $this->settings['App_share']['dir']);
		}
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_all_plat');
		$this->curl->addRequestData('id',$platform_id_str);
		$ret = $this->curl->request('get_plat_type.php');
		if(is_array($ret))
		{
			foreach ($ret as $k => $v)
			{
				$this->plat_maps[$v['id']]=array('type'=>$this->_plat_maps[$v['type']]['type'], 'type_name'=>$this->_plat_maps[$v['type']]['type_name']);
			}
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
			$sql = 'SELECT max(member_id) as max,min(member_id) as min FROM '.DB_PREFIX.'member_bind WHERE member_id != platform_id AND type =  \'m2o\' AND inuc = 0';
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
		$sql = 'SELECT member_id FROM '.DB_PREFIX.'member_bind where ( member_id >'.$progress.' AND member_id != platform_id AND type =  \'m2o\' AND inuc = 0) AND ( member_id <= '.$newlegth.' AND member_id != platform_id AND type =  \'m2o\' AND inuc = 0) order by member_id asc ';
		$query = $this->db->query($sql);
		$arr = array();
		$member_id = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[$row['member_id']]	=  array('member_id'=>$row['member_id']);
			$member_id[] = $row['member_id'];
		}
		$source_db = array(
				'host'     => 'localhost',
				'user' 	   => 'root',
				'pass' 	   => 'hogesoft',
				'database' => 'dev_member',
				'charset'  => 'utf8',	
				'pconncet' => '0',
				'dbprefix'=>'m2o_',
		);
		$sourceDB = new db();
		$sourceDB->connect($source_db['host'], $source_db['user'], $source_db['pass'], $source_db['database'], $source_db['charset'], $source_db['pconnect'], $source_db['dbprefix']);
		if (!empty($member_id))
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'member_bound WHERE member_id IN ('.implode(',', $member_id).')';
			$query = $sourceDB->query($sql);
			$platform_id = array();
			while ($row = $sourceDB->fetch_array($query))
			{
				$arr[$row['member_id']]['platform_id']	=  $row['platform_id'];
				$platform_id[] = $row['platform_id'];

			}
			$sourceDB->close();
		}
		if($platform_id && $this->settings['App_share'])
		{
			$platform_id_str = implode(',', $platform_id);
			//$access_plat_token_str .= ',d4cf3e1c11842fc401dac4785fc7cb74,4161492c9e237fc3a6a33b6420fdbb73';
			$this->curl->setSubmitType('post');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', 'get_user_by_id');
			$this->curl->addRequestData('id',$platform_id_str);
			$ret = $this->curl->request('get_user.php');
			$ret = $ret[0];
		}
		if (!empty($arr))
		{

			foreach ($arr as $key=>$val)
			{
				if(!empty($ret[$val['platform_id']]))
				{
					$arr[$key]['type'] = $this->plat_maps[$ret[$val['platform_id']]['plat_type']]['type'];
					$arr[$key]['type_name'] = $this->plat_maps[$ret[$val['platform_id']]['plat_type']]['type_name'];
					$arr[$key]['platform_id'] = $ret[$val['platform_id']]['uid'];
				}
				else
				{
					$arr[$key]['type']='m2o';
					$arr[$key]['type_name']='m2o';
				}
			}
			$source_db = array(
				'host'     => 'localhost',
				'user' 	   => 'root',
				'pass' 	   => 'hogesoft',
				'database' => 'hoolo_members',
				'charset'  => 'utf8',	
				'pconncet' => '0',
				'dbprefix'=>'m2o_',
			);
			$_sourceDB = new db();
			$_sourceDB->connect($source_db['host'], $source_db['user'], $source_db['pass'], $source_db['database'], $source_db['charset'], $source_db['pconnect'], $source_db['dbprefix']);
			foreach ($arr as $key=>$val)
			{
				if($val['platform_id'])
				{
					$sql = 'UPDATE '.DB_PREFIX.'member SET type=\''.$val['type']
					.'\',type_name = \''.$val['type_name'] .'\' WHERE member_id = '.$val['member_id'];
					$_sourceDB->query($sql);
					$sql = 'UPDATE '.DB_PREFIX.'member_bind SET
					platform_id = \''.$val['platform_id']
					.'\',type=\''.$val['type']
					.'\',type_name = \''.$val['type_name'] .'\' WHERE member_id = '.$val['member_id'];
					$_sourceDB->query($sql);
				}
				else {
					echo "数据修复出错";exit();
				}
			}
			$_sourceDB->close();
			file_put_contents($progressPath,$newlegth);
			if($is_next)
			{
				$percent = round(intval($newlegth)/intval($total) * 100,2) . "%";
				echo $message = '系统正在修复数据，别打扰唉...' . $percent;
				$this->redirect('membersDataRecovery.php');
			}
			echo "数据修复完成";exit();
		}
		else echo "已经修复完成,请勿重复修复数据";exit();
	}
	
	public  function  mobiletypem2otoshouji()
	{
		$updatetotalPath 		= CACHE_DIR . 'mtosupdatetotal.txt';
		$progressPath 	= CACHE_DIR . 'mtosprogress.txt';
		$totalPath 		= CACHE_DIR . 'mtostotal.txt';
		$is_next = true;
		if(file_exists($progressPath)&&file_exists($totalPath))
		{
			$progress = file_get_contents($progressPath);
			$total = file_get_contents($totalPath);
			$newlegth = intval($progress + LENGTH);
		    if($newlegth > intval($total))
		    {
			 $newlegth = $total;
			 $is_next = false;
		    }
		}
		else
		{
			file_put_contents($progressPath,0);
			$sql = 'SELECT count(member_id) as total FROM '.DB_PREFIX.'member WHERE 1 AND member_name >= 10000000000 AND member_name <= 99999999999 AND type =  \'m2o\'';
			$count = $this->db->query_first($sql);
			file_put_contents($totalPath, $count['total']);
			$newlegth = 0;
			$total = $count['total'];
		}
		$sql = 'SELECT member_id,member_name FROM '.DB_PREFIX.'member WHERE 1 AND member_name >= 10000000000 AND member_name <= 99999999999 AND type =  \'m2o\' LIMIT '.$newlegth.','.LENGTH;
		$query = $this->db->query($sql);
		$member_id = array();
		while ($row = $this->db->fetch_array($query))
		{
			if(hg_verify_mobile($row['member_name']))
			{
				$member_id[$row['member_id']]	=  array('member_name'=>$row['member_name']);
			}			
		}
		if ($member_id)
		{
			
			foreach ($member_id as $key => $val)
			{
				if($key)
				{
					$sql = 'UPDATE '.DB_PREFIX.'member SET mobile = '.$val['member_name'].',type=\'shouji\',type_name = \'手机快速注册\' WHERE member_id = '.$key;
					$this->db->query($sql);
					$membercount = array();
					$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'member_bind WHERE member_id = '.$key.' AND type = \'shouji\' AND is_primary = 0';
					$membercount = $this->db->query_first($sql);
					
					if($membercount['total'])
					{
					  $sql = 'DELETE FROM ' . DB_PREFIX . 'member_bind WHERE member_id = '.$key.' AND type = \'shouji\' AND is_primary = 0';
					  $this->db->query($sql);
					}
					$membercount = array();
					$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.'member_bind WHERE is_primary = 1 AND member_id = '.$key;
					$membercount = $this->db->query_first($sql);
					if($membercount['total'])
					{
					 $sql = 'UPDATE '.DB_PREFIX.'member_bind SET
					 platform_id = \''.$val['member_name']
					 .'\',type=\'shouji\',type_name = \'手机快速注册\' WHERE is_primary = 1 AND member_id = '.$key;
					 $this->db->query($sql);
					}
					if(!$membercount['total']) 
					{
						  $sql = 'UPDATE '.DB_PREFIX.'member_bind SET
					      platform_id = \''.$val['member_name']
					      .'\',type=\'shouji\',type_name = \'手机快速注册\',is_primary = 1  WHERE type = \'m2o\' AND member_id = '.$key;
					     $this->db->query($sql);
					}
					$sql = 'UPDATE '.DB_PREFIX.'member_bind SET inuc = 0 WHERE member_id = '.$key;
					$this->db->query($sql);
					$updatetotal = 0;
					file_exists($updatetotalPath)  && $updatetotal = file_get_contents($updatetotalPath);
					file_put_contents($updatetotalPath,$updatetotal + 1);
				}
				else {
					echo "数据修复出错";exit();
				}
			}
			file_put_contents($progressPath,$newlegth);
			if($is_next)
			{
				$percent = round(intval($newlegth)/intval($total) * 100,2) . "%";
				echo $message = '系统正在修复数据，别打扰唉...' . $percent;
				$this->redirect('membersDataRecovery.php?a=mobiletypem2otoshouji');
			}
			echo "数据修复完成";exit();
		}
		else if( $newlegth < intval($total))
		{
			    file_put_contents($progressPath,$newlegth);
				$percent = round(intval($newlegth)/intval($total) * 100,2) . "%";
				echo $message = '系统正在修复数据，别打扰唉...' . $percent;
				$this->redirect('membersDataRecovery.php?a=mobiletypem2otoshouji');
	    }
		else  echo "已经修复完成,请勿重复修复数据";exit();
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