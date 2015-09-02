<?php
require('global.php');
class webvodApi extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/webvod.class.php');
		$this->obj = new webvod();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 插入分类
	 */
	public function GetCategory()
	{
		$sql = "select * from " . DB_PREFIX . "webvod_conf where func='GetCategory'";
		$f = $this->db->query_first($sql);
		if(!$f)
		{
			echo "请输入配置文件";
			exit;
		}
		
		$SourceModuleID = $f['source_modid'];
		$url = $f['url'];
		
		$default_begin = $_REQUEST['starttime']?$_REQUEST['starttime']:1312128000;
		
		$interval = 60*60*24*90; //间隔3个月
		
		$start_time = $f['import_time']?$f['import_time']:$default_begin;
		
		if(!$start_time)
		{
			echo "there is no start_time,please input !";
			exit;
		}
		$import_time = ($start_time+$interval) > time()?time():($start_time+$interval);
		
		$lastSyncTime = date("Y-m-d",$start_time) . "T" . date("H:i:s",$start_time);
		$endTime = date("Y-m-d",$import_time) . "T" . date("H:i:s",$import_time);
		
		if($lastSyncTime && $endTime)
		{
			$send_xml = '<?xml version="1.0" encoding="utf-8" ?>
				<ProgramExportRequest>
				  <CmdRequest>
					<SourceModuleID>' . $SourceModuleID . '</SourceModuleID>
					<AssociationID></AssociationID>
					<Cmd>GetCategory</Cmd>
					<UserToken></UserToken>
					<LastSyncTime>' . $lastSyncTime . '</LastSyncTime>
					<EndTime>' . $endTime . '</EndTime>
				  </CmdRequest>
				</ProgramExportRequest>';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $send_xml);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$xml = curl_exec($ch);
			curl_close($ch);
			
			$xml = str_replace("'", "’", $xml);
			$info = simplexml_load_string($xml,null,LIBXML_NOCDATA); 
			if(intval($info->CmdResultNotify->ErrorCode))
			{
				echo $info->CmdResultNotify->ErrorMessage;
				exit;
			}
			
			if(is_object($info->Categories->Category))
			{
				$arr = array();
				$sql = "REPLACE INTO " . DB_PREFIX . "categorys(
					category_id,
					cpid,
					cp_name,
					title,
					category_type,
					sequence,
					status) VALUES";
				
				$tv_sql = "REPLACE INTO " . DB_PREFIX . "categorys_tv(
					cpid,
					cp_name) VALUES";
				$space = $extra = $tv_extra = "";
				foreach($info->Categories->Category as $k => $v)
				{
					$r = array();
					$extra .= $space . "('";
					$extra .= (string)$v->CategoryID . "','";
					$extra .= (string)$v->CPID . "','";
					$extra .= (string)$v->CPName . "','";
					$extra .= (string)$v->Title . "','";
					$extra .= (string)$v->CategoryType . "','";
					$extra .= (string)$v->Sequence . "','";
					$extra .= (string)$v->Status . "'";
					$extra .= ')';

					$tv_extra .= $space . "('";
					$tv_extra .= (string)$v->CPID . "','";
					$tv_extra .= (string)$v->CPName . "'";
					$tv_extra .= ')';
					$space = ',';
				}
				
				$sqls = "update " . DB_PREFIX . "webvod_conf set import_time=" . $import_time . " where func='GetCategory'";
				$this->db->query($sqls);
				if($extra)
				{
					$sql .= $extra;
					$this->db->query($sql);
					$tv_sql .= $tv_extra;
					$this->db->query($tv_sql);
					
					echo date("Y-m-d H:i:s",$import_time)." success！<br/>";
				}
				else 
				{
					if(($import_time + 10*60) > time())
					{
						echo "import completed！";
					}
					else 
					{
						$this->GetCategory();
					}
				}
			}
		}
	}
}

$out = new webvodApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'GetCategory';
}
$out->$action();

?>
