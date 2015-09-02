<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: web_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/

require_once './global.php';
require_once CUR_CONF_PATH . 'lib/web.class.php';
define('MOD_UNIQUEID', 'webapp'); //模块标识


class webUpdateApi extends outerUpdateBase
{
	private $web;
	
	public function __construct()
	{
		parent::__construct();
		$this->web = new webClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->web);
	}
	
	public function  GetIP()
	{
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		  $cip = $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		  $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		elseif(!empty($_SERVER["REMOTE_ADDR"])){
		  $cip = $_SERVER["REMOTE_ADDR"];
		}
		else{
		  $cip = "";
		}
		return $cip;
	}
	
	/**
	 * 顶踩评分心情入库处理
	 */
	public function create()
	{
		$id = intval($this->input['id']) ? intval($this->input['id']) : 0;
		$type = strtolower($this->input['type']);
		$sql = 'SELECT pic_icon,step_value,pic_value,type FROM '.DB_PREFIX.'webapp_material WHERE pic_icon = "'.$type.'"';
		if(!$picinfo = $this->db->query_first($sql))
		{
			$this->errorOutput(TYPE_ERROR);
		}
		$type_value = $picinfo['type'] == 1 ? $this->input['type_value'] : $picinfo['step_value'];
		if(!$id || !$type || !$type_value)
		{
			$this->errorOutput("参数缺损");
		}
		else
		{
			$ip = $this->GetIP();
			include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
			$this->puscont = new publishcontent();
			$data['id'] = $id;
			$data['client_type'] = "2";
			$result = $this->puscont->get_content($data);
			$title = $result[0]['title'];
			$bundle = $result[0]['bundle_id'];
			//查询是否已经存在，如不存在，写入库，存在就要判断是否重复评分
			$result = $this->web->extis_webapp($id);
			if(is_array($result) &&!empty($result) && count($result)>0)
			{
				
				$listid = $result['id'];
				//查询该条评分是否存在这个ip的纪录
				$result_one_list = $this->web->extis_one_webapp($listid,$ip,$type);
				if(is_array($result_one_list) &&!empty($result_one_list) && count($result_one_list)>0)
				{
					if(IS_REPEAT==1)
					{
						$time_1 = TIMENOW;
						$time_2 = $result_one_list['create_time'];
						$time_3 = $time_1-$time_2; //时间差
						$time_4 = TIMEOUT*3600; //允许的重复评分的时间间隔
						if($time_3<$time_4)
						{
							$create_one_list_data = array();
							$create_one_list_data['listid'] = $listid;
							$create_one_list_data['content_type'] = $bundle;
							$create_one_list_data['mark_name'] = $type;
							$create_one_list_data['mark_value'] = $type_value;
							$create_one_list_data['ip'] = $ip;
							$create_one_list_data['create_time'] = TIMENOW;
							$create_one_list_data['active'] = 1;
							$result_one_list_create = $this->web->create_one_list($create_one_list_data);
							
						}
						else
						{
							$this->errorOutput("规定时间内不允许重复评分！");
						}
					}
					else{
						$this->errorOutput("同一ip不允许重复评分！");
					}
				}
				else {
					$create_one_list_data = array();
					$create_one_list_data['listid'] = $listid;
					$create_one_list_data['content_type'] = $bundle;
					$create_one_list_data['mark_name'] = $type;
					$create_one_list_data['mark_value'] = $type_value;
					$create_one_list_data['ip'] = $ip;
					$create_one_list_data['create_time'] = TIMENOW;
					$create_one_list_data['active'] = 1;
					$result_one_list_create = $this->web->create_one_list($create_one_list_data);
				}
			}
			else
			{
				//入评分列表
				$create_list_data = array();
				$create_list_data['title'] = $title;
				$create_list_data['content_id'] = $id;
				$create_list_data['create_time'] = TIMENOW;
				$create_list_data['update_time'] = TIMENOW;
				$create_list_data['user_name'] = $this->user['user_name'];
				$create_list_data['user_id'] = $this->user['id'];
				$create_list_data['appid'] = $this->user['appid'];
				$create_list_data['appname'] = $this->user['appname'];
				$create_list_data['active'] = 1;
                $create_list_data['app_uniqueid'] = $bundle;
				$result_list_create = $this->web->create_list($create_list_data);
				$listid = $result_list_create['id'];
				//评分列表
				if(is_array($result_list_create) &&!empty($result_list_create) && count($result_list_create)>0)
				{
					$create_one_list_data = array();
					$create_one_list_data['listid'] = $listid;
					$create_one_list_data['content_type'] = $bundle;
					$create_one_list_data['mark_name'] = $type;
					$create_one_list_data['mark_value'] = $type_value;
					$create_one_list_data['ip'] = $ip;
					$create_one_list_data['create_time'] = TIMENOW;
					$create_one_list_data['active'] = 1;
					$result_one_list_create = $this->web->create_one_list($create_one_list_data);
				}
			}
			
			//更新主表webapp updown score mood 冗余
			$sql_content = 'SELECT * FROM ' . DB_PREFIX . 'webapp_list WHERE active = 1 and listid = '.$listid;
			$query_content = $this->db->query($sql_content);
			$info_content = array();
			while ($rows_content = $this->db->fetch_array($query_content))
			{
				if($rows_content['mark_name']=="pingfen")
				{
					$score += $rows_content['mark_value'];
				}
				elseif($rows_content['mark_name']=="ding")
				{
					$ding += $rows_content['mark_value'];
				}
				elseif($rows_content['mark_name']=="cai")
				{
					$cai += $rows_content['mark_value'];
				}
				else
				{
					$info_content[$rows_content['mark_name']] += $rows_content['mark_value'];
				}
			}
			$pingfen_count = $this->web->count_score($listid);
			if($pingfen_count==0)
			{
				$score = 0;
			}
			else {
				$score = $score/$pingfen_count;
			}
			
			//冗余数据
			$mood = serialize($info_content);
			/*
			$updown['ding'] = $ding;
			$updown['cai'] = $cai;
			$updown = serialize($updown);
			*/
			$update_time = TIMENOW;
			$sql = 'update '.DB_PREFIX.'webapp set score = '.$score.', up = \''.$ding.'\', down = \''.$cai.'\' , mood = \''.$mood.'\', update_time = '.$update_time.' where id = ' . $listid;
			$this->db->query($sql);
			
			unset($info_content);
			unset($rows_content);
				
			$this->addItem($create_one_list_data);
			$this->output();
		}
	}
	
	public function update()
	{		
	}

	public function delete()
	{
			
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
	
}

$out = new webUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>