<?php
class configSet extends classCore
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create($data)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->create('setting',$data,true);
	}

	public function update($data,$param)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->update('setting',$data,$param);
	}
	public function delete($data)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->delete('setting', $data);
	}

	public function updatelist($settings,$settingsInfo)
	{
		$re = array();
		$ids = "'".implode("','", $settingsInfo )."'";
		$sql = 'UPDATE '.DB_PREFIX.'setting SET value = CASE id ';
		foreach ($settings as $id => $value) {
			$_value=is_array($value)?implode("\n",$value):$value;
			if(in_array($id, $settingsInfo))
			{
				$sql .= sprintf('WHEN %d THEN %s ', $id, '\''.$_value.'\'');
				$re[$id] = $value;
			}
		}
		$sql .= 'END WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $re;
	}

	public function img_upload($img_file)
	{
		$classfile = ROOT_PATH.'lib/class/material.class.php';
		if(is_file($classfile))
		{			
			class_exists('material') OR include ($classfile);
			$material_pic = new material();
			$img = array('Filedata'=>$img_file);
			if (!$this->settings['App_material'])
			{
				return '';
			}
			$img_info = $material_pic->addMaterial($img);
			$img_data = array(
				'host' 			=> $img_info['host'],
				'dir' 			=> $img_info['dir'],
				'filepath' 		=> $img_info['filepath'],
				'filename' 		=> $img_info['filename'],
			);			
			return maybe_serialize($img_data);
		}
		return '';
	}

	public function setCount($groupmark)
	{
		$sqlCore = new sqlCore();
		$idsArr = array('groupmark'=>$groupmark);
		$total = $sqlCore->count($idsArr, 'setting');
		$configSetSort = new configSetSort();
		return $configSetSort->sortSetTotal($total,$idsArr);
	}

	public function getSort($groupmark = array())
	{
		$configSetSort = new configSetSort();
		return $configSetSort->show($groupmark,0,0,'id,grouptitle,groupmark');
	}
	
	public function getApp()
	{
		$configSetApp = new configApp();
		return $configSetApp->show('',0,0,'id,app_uniqueid,appname');
	}

	public function count($idsArr)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->count($idsArr, 'setting');
	}

	public function show($condition,$offset,$count,$field = '*',$key = '',$orderby = 'ORDER BY order_id DESC',$format = array(),$type = 1,$leftjon = '')
	{
		$sqlCore = new sqlCore();
		return $sqlCore->show($condition, 'setting', $offset, $count,$orderby,$field,$key,$format,$type,'',$leftjon);
	}

	public function makehtml($row)
	{
		switch ($row['type'])
		{
			case 'text':
				$enterstyle = makesingleinput(array(
													"type"=>'text',
													"name"=> "settings[".$row['id']."]",
													"showit"=>true,
													"css"=>"input_normal",
													"value"=>$row['value'],
				));
				break;
			case 'textarea':
				$enterstyle = makesingletextarea(array(
													"name"=> "settings[".$row['id']."]",
													"showit"=>true,
													"css"=>"input_normal",
													"cols"=>"50",
													"rows"=>"5",
													"value"=>$row['value'],
				));
				break;
			case 'radio':
				$op = array();
				if($row['dropextra'])
				{
					$extra = explode("\n",$row['dropextra']);
				}
				if($extra)
				{
					foreach($extra  AS $k=>$v)
					{
						$tmp = explode("==",$v);
						if($v)
						$op[$tmp[0]] = $tmp[1];
					}
				}
				$enterstyle = makesingleyesno(array(
													"name"=> "settings[".$row['id']."]",
													"showit"=>true,
													"option"=>$op,
													"css"=>"input_normal",
													"selected"=>$row['value'],
				));
				break;
			case 'checkbox':
				$op = array();
				if($row['dropextra'])
				{
					$extra = explode("\n",$row['dropextra']);
				}
				if($extra)
				{
					foreach($extra  AS $k=>$v)
					{
						$tmp = explode("==",$v);
						if($v)
						$op[$tmp[0]] = $tmp[1];
					}
				}
				$enterstyle = makesinglecheckbox(array(
													"name"=> "settings[".$row['id']."][]",
													"showit"=>true,
													"option"=>$op,
													"css"=>"input_normal",
													"selected"=>$row['value'],
				));
				break;
			case 'select':
				$op = array();
				$op[0] = "无";
				if($row['dropextra'])
				{
					$extra = explode("\n",$row['dropextra']);
				}
				if($extra)
				{
					foreach($extra  AS $k=>$v)
					{
						$tmp = explode("==",$v);
						if($v)
						$op[$tmp[0]] = $tmp[1];
					}
				}
				$enterstyle = makesingleselect(array(
													"name"=> "settings[".$row['id']."]",
													"showit"=>true,
					                                "option"=>$op,
													"css"=>"input_normal",
													"selected"=>$row['value'],
				));
				break;
			case 'img':
				$enterstyle = makesingleuploadimg(array(
													"name"=> "settings[".$row['id']."]",
													"css"=>"input_normal",
													"value"=>$row['value'],
				));
				break;
		}
		return $enterstyle;
	}
	/**
	 *
	 * 根据应用标识取配置 ...
	 * @param array $app_uniqueid
	 */
	public function getConfig($app_uniqueid)
	{
		$re = array();
		if(is_array($app_uniqueid)){
			$settingRelation = new settingRelation();
			$relationInfo = $settingRelation->show(array('app_uniqueid'=>$app_uniqueid), 0, 0,'app_uniqueid,groupmark','app_uniqueid',3,'groupmark');
			if($relationInfo)
			{
				$_relationInfo = array();
				foreach ($relationInfo as $v)
				{
					$_relationInfo = array_merge($_relationInfo,$v);
				}
				$configInfo = $this->show(array('groupmark'=>array_unique($_relationInfo)), 0, 0,'settitle,setname,description,groupmark,limitapps,type,value','groupmark','ORDER BY order_id DESC',array('limitapps'=>array('type'=>'explode','delimiter'=>"\n")),2);
				foreach ($relationInfo as $key => $val)
				{
					foreach ($configInfo as $k=>$v)
					{
						if(in_array($k, $val))
						{
							foreach ($v as $vv)
							{
								if(empty($vv[limitapps])||$vv[limitapps]&&in_array($key, $vv[limitapps]))
								{
									$vv['value'] = outPutFormat($vv['type'],$vv['value'],array('img'=>1));
									unset($vv['limitapps'],$vv['groupmark'],$vv['type']);
									$re[$key][$k][$vv[setname]] = $vv;
								}
							}
						}
					}
				}

			}
		}
		return $re;
	}

	/**
	 *
	 * 操作配置后更新函数 ...
	 */
	public function updateConfigAfterProcess($id)
	{
		$UpdateInfo = $this->show(array('t.id'=>$id), 0, 0,'sr.app_uniqueid','app_uniqueid','',array(),0,'LEFT JOIN '.DB_PREFIX.'setting_relation AS sr ON t.groupmark = sr.groupmark');
		$pushAppInfo = array();
		$pullAppInfo = array();
		if($UpdateInfo)
		{
			$configApp = new configApp();
			$configAppInfo = $configApp->show(array('app_uniqueid'=>array_unique($UpdateInfo)), 0, 0,'app_uniqueid,updatetype,callurl,argument','app_uniqueid','');
			
			if($configAppInfo)
			{
				class_exists('curl') OR include(ROOT_PATH.'lib/class/curl.class.php');
				global $curl;
				$curl = new curl();
				foreach ($configAppInfo as $k => $v)
				{
					if($v['updatetype'])
					{
						$pullAppInfo[$k] = $v;
					}
					elseif(!$v['updatetype'])
					{
						$pushAppInfo[$k] = $v;
					}
				}
			}
			$pullAppInfo&&$this->pullConfig($pullAppInfo);
			$pushAppInfo&&$this->pushConfig($pushAppInfo);
		}
	}
	/**
	 *
	 * 推送配置至需要更新的应用...
	 * @param unknown_type $pushAppInfo
	 */
	private function pushConfig($pushAppInfo)
	{
		global $curl;
		$config = $this->getConfig(array_keys($pushAppInfo));
		foreach ($pushAppInfo as $k =>$v)
		{
			$curl->setUrlHost($v[callurl], '');
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			$curl->initGetData();
			$curl->initPostData();
			if(is_array($config[$k]))
			{
			$param = array('param'=>$config[$k]);
			$getParam = array();
			if($argument = $v[argument])//额外参数追加
			{
				foreach($argument['ident'] as $k => $v)
				{
					if($v&&($argument['argument_type'][$k]=='get'))
					{
						$getParam[$v] = $argument['value'][$k];
					}
					else if($v&&($argument['argument_type'][$k]=='post'))
					{
						$param[$v] = $argument['value'][$k];
					}
				}
			}
				foreach ($getParam as $k => $v)
				{
					$curl->addGetData($k, $v);
				}
				
				foreach ($param as $k => $v)
				{
					if(is_array($v))
					{
						$this->array_to_add($k, $v);
					}else {
						$curl->addRequestData($k, $v);
					}
				}
			}
			$curl->request('');
		}
	}

	public function array_to_add($str, $data)
	{
		global $curl;
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
		else
		{
			$curl->addRequestData($str, $data);
		}
	}
	/**
	 *
	 *  通知需要更新配置应用拉取接口 ...
	 * @param unknown_type $pullAppInfo
	 */
	private function pullConfig($pullAppInfo)
	{
		global $curl;
		foreach ($pullAppInfo as $k =>$v)
		{
			$curl->setUrlHost($v[callurl], '');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->request('');
		}
	}

	public function detail($id,$field = '*')
	{
		$sqlCore = new sqlCore();
		return $sqlCore->detail($id, 'setting',array('limitapps'=>array('type'=>'explode','delimiter'=>"\n"),'dropextra'=>array('type'=>'explode','delimiter'=>"\n")),$field);
	}

}

?>