<?php
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/content.class.php';
include_once CUR_CONF_PATH . 'lib/user.class.php';
include_once ROOT_PATH . 'lib/class/news.class.php';
include_once ROOT_PATH . 'lib/class/applant.class.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
define('MOD_UNIQUEID', 'news');  //模块标识
define('APP_UNIQUEID','company');
class contributeApi extends appCommonFrm{
	
	private $api;
	private $app;
	private $use;
	private $news;
	private $material;
	public function __construct()
	{
		parent::__construct();
		$this->api = new content();
		$this->app = new applant();
		$this->use = new user();
		$this->material = new material();
		$this->news = new news();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	public function show(){}
		
	/**
	 * 手动采集数据保存
	 * @author jitao
	 */
	public function create(){
		
		if(!$this->user['user_id'])
		{
			$this->errorOutput(TOKEN_VALIDATE_FAIL);
		}
		//根据百姓网数据ID得到数据htmlspecialchars_decode  stripslashes
		$data = $this->input['data'];
		if(!$data)
		{
			$this->errorOutput(BAIXING_DATA_NULL);
		}
		$dataArray = json_decode(urldecode(html_entity_decode(stripslashes($data),ENT_QUOTES)),1);
		$app_id = intval($this->input['appId']);
		$module_id = intval($this->input['moduleId']);
		if($app_id == '')
		{
			$this->errorOutput(BAIXING_APP_ID_NULL);
		}	
		if($module_id == '')
		{
			$this->errorOutput(BAIXING_MODULE_ID_NULL);
		}
		//先得到模块信息
		$moduleInfo = $this->app->getModuleInfoByAppIdAndModuleId($module_id);
		//得到应用信息
		$appInfo = $this->app->getUserInfoByAppId($app_id);
		if($moduleInfo['app_id']!=$app_id||!$moduleInfo||!$appInfo)
		{
			$this->errorOutput(BAIXING_INFO_WRONG);
		}	
		$user_id = $moduleInfo['user_id'];
		$user_name = $moduleInfo['user_name'];
		$column_id = $moduleInfo['column_id'];//栏目id
		$column_name = $moduleInfo['name'];//栏目名字
		$app_name = $appInfo['name'];
		$userData = array(
			'id' => $user_id,	
		);	
		$userInfo = $this->use->detail('user',$userData);
		$site_id = $userInfo['s_id'];
		for($i=0;$i<count($dataArray);$i++)
		{		
			$saveArray=array();
			$price = '';
			$saveArray = array(
				'title'        => $dataArray[$i]['title'],
				'site_id'      => $site_id,	
				'outlink' 	   => $dataArray[$i]['contentUrl'],
				'column_id'    => $column_id,
				'column_path'  => $column_name,		
				'state'        => 1,	
				'user_id'	   => $user_id,
				'user_name'    => $user_name,
				'appname'      => $app_name,
				'identifier'   => $app_id,
				'brief'		   => $dataArray[$i]['location'],
			);		
			if($dataArray[$i]['price'])
			{
				if(strpos($dataArray[$i]['price'],'万'))
				{
					$price = intval($dataArray[$i]['price'])*10000;
				}
				else
				{
					$price = intval($dataArray[$i]['price']);
				}
				$nowPrice = $price.",".$price;
			}
			else
			{
				$nowPrice = "";
			}
			$saveArray['catalog_price1'] = $nowPrice;
			$saveArray['catalog_sort'] = 'dingdone';
// 			$saveArray['catalog_userdefined1'] = $dataArray[$i]['location'];
			if($dataArray[$i]['title']==''||$dataArray[$i]['contentUrl']=='')
			{
				$this->addItem_withkey($dataArray[$i]['id'],'fail');
				continue;
			}
			$material_id = array();
			$material_history = "";
			$materialInfo = $this->material->localMaterial($dataArray[$i]['indexPic'],'','','','jpg_bi');
			if($materialInfo && is_array($materialInfo) && $materialInfo[0])
			{
				$saveArray['indexpic'] = $materialInfo[0]['id'];
				$material_id[] = $materialInfo[0]['id'];
				$tmpData = array(
						'material_id' => $materialInfo[0]['id'],
						'name'        => $materialInfo[0]['name'],
						'host'        => $materialInfo[0]['host'],
						'dir'         => $materialInfo[0]['dir'],
						'filepath'    => $materialInfo[0]['filepath'],
						'filename'    => $materialInfo[0]['filename'],
						'type'        => $materialInfo[0]['type'],
						'mark'        => $materialInfo[0]['mark'],
						'imgwidth'    => $materialInfo[0]['imgwidth'],
						'imgheight'   => $materialInfo[0]['imgheight'],
						'filesize'    => $materialInfo[0]['filesize'],
						'create_time' => $materialInfo[0]['create_time'],
						'ip'          => $materialInfo[0]['ip'],
						'remote_url'  => $materialInfo[0]['remote_url'],
				);
				//调用news模块里的接口 将$tmpData插入dev_news库中的liv_material 得到的为material中的id字段
				$newsMaterial_Id = $this->news->uploadNewsMaterial($tmpData);
			}
			$material_history = implode(',', $material_id);
			$saveArray['material_history'] = $material_history; //取索引图标识
			$saveArray['material_id'] = $material_id; //取索引图标识
			$saveArray['need_indexpic'] = 1; //取索引图标识
			$info = array();
			$info = $this->news->create($saveArray);
			if($info['id']){	
				$indexpic = '';
				if ($info['indexpic_url']['host'])
				{
					$indexpic = array(
							'id'        => $info['indexpic_url']['id'],
							'host'      => $info['indexpic_url']['host'],
							'dir'       => $info['indexpic_url']['dir'],
							'filepath'  => $info['indexpic_url']['filepath'],
							'filename'  => $info['indexpic_url']['filename'],
							'imgheight' => $info['indexpic_url']['imgheight'],
							'imgwidth'  => $info['indexpic_url']['imgwidth'],
					);
				}				
				$localData = array(
						'site_id' => $site_id,
						'source_id' => $info['id'],
						'source' => MOD_UNIQUEID,
						'title' => $info['title'],
						'keywords' => $info['keywords'],
						'brief' => $info['brief'],
						'weight' => $info['weight'],
						'column_id' => $info['column_id'],
						'column_path' => serialize(array($column_id=>$column_name)),
						'state' => 1,
						'app_uniqueid' => APP_UNIQUEID,
						'mod_uniqueid' => MOD_UNIQUEID,
						'user_id' => $user_id,
						'user_name' => $user_name,
						'org_id' => $info['org_id'],
						'appid' => $info['appid'],
						'appname' => $info['appname'],
						'create_time' => $info['create_time'],
						'ip' => $info['ip'],
						'indexpic'=>$indexpic ? addslashes(serialize($indexpic)) : '',
						'template_sign' => $info['template_sign'],
						'outlink'   => $info['outlink'],
						'iscomment' => $info['iscomment'] ? 1 : 0,
						'catalog'   => $info['catalog'],
				);
				$result = $this->api->create('content', $localData);
				if($result){
					$relation = $this->api->column_cid($column_id, $result['id']);		
					$this->addItem_withkey($dataArray[$i]['id'],'success');		
				}
			}
		}
		$this->output();
	}
	public function t(){
		print_r($this->material->localMaterial("http://img4.baixing.net/399915d9aac9be2d4c83e1dae50f8058.jpg_bi"));
	}
}
$out = new contributeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();