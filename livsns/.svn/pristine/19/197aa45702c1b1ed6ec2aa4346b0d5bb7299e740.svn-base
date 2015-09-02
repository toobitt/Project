<?php
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
define('SCRIPT_NAME', 'export2members');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
require(ROOT_PATH.'lib/class/curl.class.php');
define('LENGTH',1000);
ini_set('max_execution_time', 3600);
ini_set('memory_limit', '1024M');

class export2members extends adminBase
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
		if(file_exists($progressPath))
		{
			$progress = file_get_contents($progressPath);
		}
		else
		{
			file_put_contents($progressPath,0);
			$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'member';
			$total = $this->db->query_first($sql);
			$total = $total['total'];
			file_put_contents($totalPath, $total);
			$progress = 0;
		}
		$newlegth = $progress+LENGTH;

		$sql = 'SELECT * FROM '.DB_PREFIX.'member where id >'.$progress.' AND id <= '.$newlegth.' order by id asc ';
		$query = $this->db->query($sql);
		$arr = array();
		$ids = array();
		while ($row = $this->db->fetch_array($query))
		{
				
			$ids[] = $row['id'];
			$arr[$row['id']] = array(
				'id'			=> $row['id'],
				'uc_id'			=> $row['uc_id'],
				'member_name'	=> $row['member_name'],
				'nick_name'		=> $row['nick_name'],
				'password'		=> $row['password'],
				'email'			=> $row['email'],
				'appid'			=> $row['appid'],
				'appname'		=> $row['appname'],
				'salt'			=> $row['salt'],
				'mobile'        => $row['mobile'],	
				'avatar'		=> $row['filename'] ? serialize(array(
										'host'=>$row['host'],
										'dir' =>$row['dir'],
										'filepath'=>$row['filepath'],
										'filename'=>$row['filename'],
			)) : '',
				'create_time'=>$row['create_time'],
				'update_time'=>$row['update_time'],
				'ip'=>$row['ip'],									
			);			
		}
		if (!empty($ids))
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'member_bound WHERE member_id IN ('.implode(',', $ids).')';
			$query = $this->db->query($sql);
			$bound = array();
			while ($row = $this->db->fetch_array($query))
			{
				$arr[$row['member_id']]['avatar_url'] 	=  $row['avatar_url'];
				$arr[$row['member_id']]['platform_id']	=  $row['platform_id'];
				$arr[$row['member_id']]['nick_name'] 	= $row['plat_member_name'];
				$platform_id[] = $row['platform_id'];

			}
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
					$arr[$key]['type_name']='M2O';
				}
			}
			$source_db = array(
				'host'     => 'localhost',
				'user' 	   => 'root',
				'pass' 	   => 'hogesoft',
				'database' => 'jn_members',
				'charset'  => 'utf8',	
				'pconncet' => '0',
				'dbprefix'=>'m2o_',
			);
			$sourceDB = new db();
			$sourceDB->connect($source_db['host'], $source_db['user'], $source_db['pass'], $source_db['database'], $source_db['charset'], $source_db['pconnect'], $source_db['dbprefix']);
			foreach ($arr as $key=>$val)
			{
				$sql = 'INSERT INTO '.DB_PREFIX.'member (
						guid,
						member_name,
						password, 
						salt,
						type, 
						type_name,
						avatar,
						email,
						status,
						appid,
						appname,
						mobile,
						create_time,
						update_time,
						ip)
						VALUES(
						"'.guid().'",
						"'.$val['member_name'].'",
						"'.$val['password'].'",
						"'.$val['salt'].'",
						"'.$val['type'].'",
						"'.$val['type_name'].'",
						"'.addslashes($val['avatar']).'",
						"'.$val['email'].'",
						"1",
						"'.$val['appid'].'",
						"'.$val['appname'].'",
						"'.$val['mobile'].'",
						"'.$val['create_time'].'",
						"'.$val['update_time'].'",
						"'.$val['ip'].'"
						)';
				$sourceDB->query_first($sql);
				$iid = $sourceDB->insert_id();
				if (!$iid)
				{
					break;
				}

				$val['platform_id'] = !empty($val['platform_id'])?$val['platform_id']:(empty($val['uc_id'])?$iid:$val['uc_id']);
				$sql = 'INSERT INTO '.DB_PREFIX.'member_bind (
						member_id, 
						platform_id,
						nick_name,
						type,
						type_name,
						avatar_url,
						bind_time,
						inuc,
						is_primary
						) 
						VALUES(
						'.$iid.',
						"'.$val['platform_id'].'",
						"'.$val['nick_name'].'",
						"'.$val['type'].'",
						"'.$val['type_name'].'",
						"'.$val['avatar_url'].'",
						"'.TIMENOW.'",
						"'.$val['uc_id'].'",
						1
						)';				
				$sourceDB->query($sql);
				$sql = 'INSERT INTO '.DB_PREFIX.'member_count (
						u_id
						) 
						VALUES(
						'.$iid.'
						)';
				$sourceDB->query($sql);
			}
			file_put_contents($progressPath,$progress + LENGTH);
			$total = file_get_contents($totalPath);
			if(intval($progress + LENGTH) < intval($total))
			{
				$percent = round(intval($progress + LENGTH)/intval($total) * 100,2) . "%";
				echo $message = '正在更新数据...' . $percent;
				$this->redirect('export2members.php');
			}
			echo "数据更新完成";exit();
		}
		else echo "已经导入完成,请勿导入重复数据";exit();
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