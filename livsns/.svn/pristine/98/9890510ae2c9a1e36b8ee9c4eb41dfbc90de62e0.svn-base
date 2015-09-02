<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_auto.php 6444 2012-04-18 05:18:47Z repheal $
***************************************************************************/
set_time_limit(0);
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','ftp_import_auto');
define('SOURCE_FILE','/public/vod2/');
define('SOURCE_URL','http://mediaserver_api.app.m2o/migrate/data/ftp_auto/');
define('APPID',11);
define('APPKEY','Jme3RUKsryf2OpPnUsvaYLVeyJedIyDh');
define('M2OCKEY','QseaVBIaFFwVuFu1nZhvsKMFsOhC2ufJ');
require_once(CUR_CONF_PATH . 'lib/functions.php');
require_once(ROOT_DIR.'global.php');
class ftpImportAutoApi extends cronBase
{
	private $media;
	private $play;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$this->media = new livmedia();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => 'ftp数据',	 
			'brief' => 'ftp的数据抓取',
			'space' => '30',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	function show() 
	{
		$ftp_server = "172.25.69.69";
		$ftp_port = 21;
		$ftp_user = "gsftp";
		$ftp_pass = "happy2015";
		$file_format = array("xml");
		//连接FTP服务器
		$conn_id = ftp_connect($ftp_server,$ftp_port);
		$f_dir = '/public/vod2/xml/';//xml
		$g_dir = CUR_CONF_PATH . 'data/ftp_auto/';
		//$gf_dir = CUR_CONF_PATH . 'data/ftp_auto/'; //ftp and api is in the same
		$gf_dir = '/data2/video.app.m2o/uploads'; //ftp and api is in the same
		$count = 10;
		$cache_file = CACHE_DIR . 'queue_auto';
		if(file_exists($cache_file) && !$this->input['debug'])
		{
			$last_time = file_get_contents($cache_file);
			if($this->input['queue'])
			{
				unlink($cache_file);
			}
			elseif((time()-$last_time) > 600)
			{
				unlink($cache_file);
			}
			else
			{
				$this->errorOutput('当前有任务在执行。。。');
			}			
		}
		else
		{
			if(hg_mkdir($g_dir))
			{
				// 登陆FTP
				$file_name = array();
				if(ftp_login($conn_id, $ftp_user, $ftp_pass))
				{
					$tmp_dir = opendir($g_dir);
					$i = 0;
					while($file = readdir($tmp_dir))
					{
						if($file == '.' || $file == '..' || !in_array(substr(strrchr($file, '.'), 1),$file_format)) 
						{
							continue;
						}
						if($i <= $count)
						{
							$file_name[] = $file;
						}
						else
						{
							break;
						}
						$i++;
					}
					if(empty($file_name))
					{
						$filelist = ftp_rawlist($conn_id,$f_dir);
						foreach($filelist as $file)
						{ 
							$tmp_filename = preg_replace("/.+[:]*\\d+\\s/", "", $file);
							$tmp_name = mb_convert_encoding($tmp_filename, "GBK", "UTF-8");
							if($tmp_filename == '.' || $tmp_filename == '..' || !in_array(substr(strrchr($tmp_name, '.'), 1),$file_format))
							{
								continue;
							}
							ftp_get($conn_id, $g_dir . $tmp_filename, $f_dir . $tmp_filename, FTP_BINARY);
							chmod($g_dir . $tmp_filename,0777);	
						}
						$tmp_dir = opendir($g_dir);
						$i = 0;
						while($file = readdir($tmp_dir))
						{
							if($file == '.' || $file == '..'  || !in_array(substr(strrchr($file, '.'), 1),$file_format)) 
							{
								continue;
							}
							if($i <= $count)
							{
								$file_name[] = $file;
							}
							else
							{
								break;
							}
							$i++;
						}
					}				 
				}
			//	hg_pre($file_name);exit;
				if(!empty($file_name))
				{
					file_put_contents($cache_file,time());
					chmod($cache_file,0777);	
					$sql_ftp = "INSERT INTO " . DB_PREFIX . "ftp_source(vid,asset_id,parent_asset_id,name,tv_name,tv_id,index_num,create_time,update_time) VALUE ";
					$insert_ftp = $space = '';
					foreach($file_name as $key => $value)
					{
						echo $value . '<br/>';
						$info = $data = array();
						$filepath = $indexpic = '';
						if(!file_exists($g_dir . $value))
						{
							continue;
						}
						$xml_data = file_get_contents($g_dir . $value);
						if(!$this->xml_parser($xml_data))
						{
							unlink($g_dir . $value);
							continue;
						}
						$array_data = xml2Array($xml_data);
						$adi_data = $this->xml_content($array_data);//处理xml内容
					//	hg_pre($adi_data);exit;
						$asset_id = $adi_data['asset_id'];						
						$sql = "SELECT * FROM " . DB_PREFIX . "ftp_source WHERE asset_id='" . $asset_id . "'";
						$f = $this->db->query_first($sql);
						$column_id = $sort_id = 0;
						$column_id = $adi_data['columnid'];
					//	hg_pre($f);exit;
						if(empty($f))
						{
							$info = array(
								'title' 		=> $adi_data['title'],
								'subtitle' 		=> '',
								'keywords' 		=> $adi_data['keywords'],
								'comment' 		=> $adi_data['comment'],
								'author' 		=> $adi_data['author'],
								'vod_sort_id' 	=> $adi_data['vod_sort_id'],
							//	'index_pic'	=> $adi_data['index_pic'],
							//	'column_id' 	=> $adi_data['column_id'],
								'start'			=> 0,
								'duration'		=> '',
								'audit_auto'	=> 2,
								'create_time'	=> $adi_data['playTime'],
								//'user_id'		=> $user_info[$row['userid']]['id'],
								//'user_name' 	=> $user_info[$row['userid']]['name'],
								//'org_id' 		=> 2,
								'appid' 		=> APPID,
								'appkey'		=> APPKEY,
								'm2o_ckey'		=> M2OCKEY,
							);
							$info['a'] = 'create';
							$tmp_array = explode('/',$adi_data['video_filename']);
							$filename = $tmp_array[count($tmp_array)-1];
							$filepath =  $adi_data['video_filename'];//mb_convert_encoding(, "GBK", "UTF-8");
							$tmp_image = explode('/',$adi_data['imagePath']);
							$imagename = $tmp_image[count($tmp_image)-1];
							$imagepath = $adi_data['imagePath'];	
					//		echo $gf_dir . $filename;exit;
					//		echo $g_dir . $imagename;exit;
							//构建目标视频目录
                            $video_dir = $gf_dir .'/sobey/' . date('Y',TIMENOW) . '/' . date('m',TIMENOW) . '/' . date('d',TIMENOW).'/';
                            if (!hg_mkdir($video_dir))
                            {
                                    $this->errorOutput(NOWRITE);
                            }
							chmod($video_dir,0777);
							if(!file_exists($video_dir . $filename))
							{
echo $filepath;
								@ftp_get($conn_id, $video_dir . $filename, $filepath , FTP_BINARY);
								@ftp_get($conn_id, $g_dir . $imagename,  $imagepath , FTP_BINARY);
echo $imagepath;
	//							exit;
								if(!file_exists($video_dir . $filename) || filesize($video_dir . $filename) <= 0)
								{
								//	unlink($video_dir . $filename);//删除本地视频文件
									unlink($g_dir . $value);//删除本地adi
								//	ftp_delete($conn_id, $filepath);//删除ftp视频文件
									ftp_delete($conn_id,$imagepath);//删除ftp视频文件
									ftp_delete($conn_id, $f_dir . $value);//删除ftp上adi
									continue;
								}
								else
								{
									chmod($video_dir . $filename,0777);
									chmod($g_dir . $imagename,0777);
								}
							}
						//	$info['videofile'] = '@' . $video_dir . $filename;
							$info['index_pic'] = SOURCE_URL . $imagename;
							$info['filepath'] = str_replace($gf_dir,'',$video_dir) . $filename;
						//	hg_pre($info);exit;
							$url = 'http://' . $this->settings['App_mediaserver']['host'] . '/' . $this->settings['App_mediaserver']['dir'] . 'admin/create.php?format=json';
							$ch = curl_init();
							curl_setopt($ch,CURLOPT_URL,$url);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $info);
							$rt = curl_exec($ch);
							curl_close($ch);
							$rt = json_decode($rt,1);
						//	hg_pre($rt);
							$rt = $rt[0];
							if($rt['id'])
							{
								$insert_ftp = "(" . $rt['id'] . ",'" . $asset_id . "','','" . $title  . "','',0,0," . TIMENOW . "," . TIMENOW . ")";
								//unlink($video_dir . $filename);//删除本地视频文件
								unlink($g_dir . $imagename);//删除本地视频文件
								unlink($g_dir . $value);//删除本地adi
						//		ftp_delete($conn_id,'./' . $filepath);//删除ftp视频文件
						//		ftp_delete($conn_id,'./' . $imagepath);//删除ftp视频文件
								ftp_delete($conn_id, $f_dir . $value);//删除ftp上adi
								$this->db->query($sql_ftp . $insert_ftp);
							}
							else//提交转码服务器失败
							{
								unlink($g_dir . $imagename);//删除本地视频文件
								unlink($g_dir . $value);//删除本地adi
								ftp_delete($conn_id, $f_dir . $value);//删除ftp上adi
							}
							hg_pre($rt);
							//exit;
						}
						else
						{
							$tmp_array = explode('/',$adi_data['video_filename']);
							$filename = $tmp_array[count($tmp_array)-1];
							$filepath =  $adi_data['video_filename'];//mb_convert_encoding(, "GBK", "UTF-8");
							unlink($g_dir . $value);//删除本地adi
							@ftp_delete($conn_id, $f_dir . $value);//删除ftp上adi
						//no update operate
						}
					}//foreach
					unlink($cache_file);
				}
				//echo $file_name;exit;
				ftp_close($conn_id);                   //断开ftp服务器连接
			}
		}
	}
	
	private function xml_content($data)//内容格式化
	{
		$vod_sort = array(
	//		'吉林卫视' => 8,
	//		'都市频道' => 9,
		);
		$column_sort = array(
			/*'安徽新闻联播' => 2775,
			'超级新闻场' => 2777,*/
		);
		$adi_data = array();
	//	hg_pre($data);exit;
		if(!empty($data))
		{
			$adi_data['title'] = $data['Objects']['Object'][0]['Property'][0];
			$adi_data['comment'] = $data['Objects']['Object'][0]['Property'][4];
		//	$adi_data['source'] = $v['Value'];
		//	$adi_data['author'] = $data['author'];
		//	$adi_data['sub_title'] = $v['Value'];
		//	$adi_data['keywords'] = $data['content'];
		//	$adi_data['column_id'] = $v['Value'];
			$adi_data['vod_sort_id'] = 6;
			$adi_data['imagePath'] = str_replace('ftp://ftpuser:ftpuser@172.25.69.69:21/',SOURCE_FILE, $data['Objects']['Object'][2]['Property'][0]);
			$adi_data['video_filename'] = str_replace('ftp://ftpuser:ftpuser@172.25.69.69:21/',SOURCE_FILE, $data['Objects']['Object'][1]['Property'][1]);
			$adi_data['asset_id'] = $data['Objects']['Object'][0]['ID'];
		//	$adi_data['playTime'] = $data['playTime'];
		}
		return $adi_data;
	}
	
	private function xml_parser($str){ 
        $xml_parser = xml_parser_create(); 
        if(!xml_parse($xml_parser,$str,true)){ 
            xml_parser_free($xml_parser); 
            return false; 
        }else { 
            return (json_decode(json_encode(simplexml_load_string($str)),true)); 
        } 
    } 
	
	private function cal_toff($toff)
	{
		if(empty($toff))
		{
			return 0;
		}
		else
		{
			$toff = explode(':',$toff);
			return intval($toff[0])*3600+intval($toff[1])*60+intval($toff[2]);
		}
	}
}

$out = new ftpImportAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
