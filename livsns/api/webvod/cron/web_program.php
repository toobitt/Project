<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','webvod');
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
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '更新配置',	 
			'brief' => '更新配置',
			'space' => '600',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	/**
	 * 
	 * 更新配置
	 */
	public function GetProgram()
	{
		$sql = "select * from " . DB_PREFIX . "webvod_conf where func='GetProgram'";
		$f = $this->db->query_first($sql);
		if(!$f)
		{
			echo "请输入配置文件";
			exit;
		}
		
		$SourceModuleID = $f['source_modid'];
		$url = $f['url'];
		
		$default_begin = $_REQUEST['starttime']?$_REQUEST['starttime']:1312128000;
		
		$interval = 60*60; //间隔1小时
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
					<Cmd>GetProgram</Cmd>
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
			if(is_object($info->Programs->Program))
			{
				foreach($info->Programs->Program as $k => $v)
				{
					$keyw = (string)$v->Keyword;
					$pregreplace= array(',', ',');
					$pregfind= array(' ', '，');
					$keywords = str_replace($pregfind, $pregreplace, $keyw);
					$program[] = array(
						"program_id" => (string)$v->ProgramID,	
						"orderid" => (string)$v->ProgramID,	
						"maid" => (string)$v->MAID,
						"create_date" => strtotime((string)$v->CreateDate),
						"category_id" => (string)$v->CategoryID,
						"cp_ip" => (string)$v->CPID,
						"cp_name" => (string)$v->CPName,
						"title" => (string)$v->Title,
						"program_form" => (string)$v->ProgramForm,
						"media_type" => (string)$v->MediaType,
						"program_type" => (string)$v->ProgramType,
						"duration" => (string)$v->Duration,
						"brief" => (string)$v->Description,
						"keywords" => $keywords,
						"status" => (string)$v->Status
					);
				}			
			}
			if(is_object($info->ImgResources->ImgResource))
			{
				//514横图、130方图、258竖图
				$imgsource = array();
				/*require_once(ROOT_PATH . 'lib/class/material.class.php');
				$this->ma = new material();*/
				foreach($info->ImgResources->ImgResource as $k => $v)
				{
					/*$materials = $this->ma->localMaterial((string)$v->Url,(string)$v->ProgramID);
					if($materials)
					{
						$arr = array(
							'host'			=>	$materials[0]['host'],
							'dir'			=>	$materials[0]['dir'],
							'filepath'		=>	$materials[0]['filepath'],
							'filename'		=>	$materials[0]['filename'],
						);
						$indexpic =	serialize($arr);
					}*/
					$imgsource[] = array(
						"program_id" => (string)$v->ProgramID,	
						"url" => (string)$v->Url,
						"type" => (string)$v->FileFormat,
						"status" => (string)$v->Status,
						//"indexpic" => $indexpic,
					);
				}
			}
		
			$sqlimg = "REPLACE INTO " . DB_PREFIX . "webvodpic(program_id,url,type,status) VALUES";
			$space_ = $extra_ = "";
			if(is_array($imgsource))
			{
				foreach($imgsource as $key => $val)
				{
					$extra_ .= $space_ ."(";
					$space_s_ = "";
					foreach($val as $k => $v)
					{
						$extra_ .= $space_s_ . "'" . $v . "'";
						$space_s_ = ",";
					}
					$extra_ .= ")";
					$space_ = ",";
				}
			}

			if(is_object($info->VideoResources->VideoResource))
			{
				$videsource = array();
				foreach($info->VideoResources->VideoResource as $k => $v)
				{
					if((int)$v->Status == 1)
					{
						$videsource[(string)$v->ProgramID] = array(
							'url' => (string)$v->Url,
							'bitrate' => (string)$v->Bitrate,
							'video_id' => (string)$v->VideoID,
						); 
					}
				}	
			}
			$sql = "REPLACE INTO " . DB_PREFIX . "webvod(program_id,orderid,maid,create_date,category_id,cpid,cpname,title,program_form,media_type,program_type,duration,brief,keywords,status,video_source,bitrate,video_id,create_time) VALUES";
			$space = $extra = "";
			if(is_array($program))
			{
				foreach($program as $key => $value)
				{
					//$value['imgsource'] = $imgsource[$value['ProgramID']]['url']; //图片地址 可以给空地址
					$value['video_source'] = $videsource[$value['program_id']]['url']; // 视频流地址
					$value['bitrate'] = $videsource[$value['program_id']]['bitrate'];// 码率
					$value['video_id'] = $videsource[$value['program_id']]['video_id'];// 视频ID
					$value['create_time'] = TIMENOW;
					$extra .= $space ."(";
					$space_s = "";
					foreach($value as $k => $v)
					{
						$extra .= $space_s . "'" . $v . "'";
						$space_s = ",";
					}
					$extra .= ")";
					$space = ",";
				}
			}

			$sqls = "update " . DB_PREFIX . "webvod_conf set import_time=" . $import_time . " where func='GetProgram'";
			$this->db->query($sqls);
			if($extra)
			{
				$sql .= $extra;
				$this->db->query($sql);
				if($f['gap'])
				{
					$program_ids = array();
					$gap = TIMENOW - $f['gap']*24*60*60;
					$sq = "select distinct program_id from " . DB_PREFIX . "webvod where expand_id=0 AND  create_time < " . $gap;
					$qs = $this->db->query($sq);
					while($rs = $this->db->fetch_array($qs))
					{
						$program_ids[] = $rs['program_id'];
					}
					$pids = implode(',',$program_ids);
					
					$sqls = "delete from " . DB_PREFIX . "webvod where expand_id=0 AND  create_time < " . $gap;
					$this->db->query($sqls);
					if($pids)
					{
						$wsq = "delete from " . DB_PREFIX . "webvodpic where program_id in(" . $pids . ")";
						$this->db->query($wsq);
					}
					
				}
				if($extra_)
				{
					$sqlimg .= $extra_;
					$this->db->query($sqlimg);	
				}
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
					$this->GetProgram();
				}
			}


		}
	}
}

$out = new webvodApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'GetProgram';
}
$out->$action();

?>
