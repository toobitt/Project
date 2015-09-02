<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 6930 2012-05-31 07:16:07Z repheal $
***************************************************************************/
require('global.php');
require_once(ROOT_PATH.'lib/class/statistic.class.php');
define('MOD_UNIQUEID','share');//模块标识
class shareApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'=>'管理',
		'_node'=>array(
			'name'=>'栏目',
			'node_uniqueid'=>'cloumn_node',
			),
		);
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		include(CUR_CONF_PATH . 'lib/share.class.php');
		include(CUR_CONF_PATH . 'lib/oauth.php');
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$plat = $this->obj->get_account($offset,$count,$this->get_condition());
		foreach($plat as $k=>$v)
		{
			if($plat[$k]['picurl'])
			{
				$pic = unserialize($plat[$k]['picurl']);
				$plat[$k]['picurl'] = $pic['host'].$pic['dir'].$pic['filepath'].$pic['filename'];
			}
		}
		$platdata['account'] = $plat;
		$this->addItem($platdata);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."plat ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		if($share_plat = intval($this->input['_id']))
		{
			$condition = ' WHERE type='.$share_plat;
		}
		return $condition;	
	}

	public function create()
	{	
		if(!$this->input['plat_type'])
		{
			$this->errorOutput('未选择平台类型');
		}
		//提交图片
		$picdata = $this->insert_pic();
		$data=array(
			'type' => urldecode($this->input['plat_type']),
			'name' => urldecode($this->input['name']),
			'offiaccount' => urldecode($this->input['offiaccount']),
			'akey' => urldecode($this->input['apikey']),
			'skey' => urldecode($this->input['secretkey']),
			'callback' => urldecode($this->input['callback']),
			'status' => urldecode($this->input['status']),
			'addtime' => time(),
			'picurl' => empty($picdata['picurl'])?'':serialize($picdata['picurl']),
			'pic_login' => empty($picdata['pic_login'])?'':serialize($picdata['pic_login']),
			'pic_share' => empty($picdata['pic_share'])?'':serialize($picdata['pic_share']),
		);
		
		if($data['type'] == 127)
		{
			foreach($this->settings['share_plat'][127]['para'] as $v)
			{
				$platpara[$v['param']] = urldecode($this->input[$v['param']]);
			}
		}
		$data['platdata'] = empty($platpara)?'':serialize($platpara);
		
		$sql="INSERT INTO " . DB_PREFIX . "plat SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
	}
	
	public function insert_pic()
	{
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		$result = array();
		if ($_FILES['pic_files'])
		{
			$default = array();
			$file['Filedata'] = $_FILES['pic_files'];
			$default = $this->mMaterial->addMaterial($file, '');
			$result['picurl']['id'] = $default['id'];
			$result['picurl']['host'] = $default['host'];
			$result['picurl']['dir'] = $default['dir'];
			$result['picurl']['filepath'] = $default['filepath'];
			$result['picurl']['filename'] = $default['filename'];
		}
		if ($_FILES['pic_login'])
		{
			$default = array();
			$file['Filedata'] = $_FILES['pic_login'];
			$default = $this->mMaterial->addMaterial($file, '');
			$result['pic_login']['id'] = $default['id'];
			$result['pic_login']['host'] = $default['host'];
			$result['pic_login']['dir'] = $default['dir'];
			$result['pic_login']['filepath'] = $default['filepath'];
			$result['pic_login']['filename'] = $default['filename'];
		}
		if ($_FILES['pic_share'])
		{
			$default = array();
			$file['Filedata'] = $_FILES['pic_share'];
			$default = $this->mMaterial->addMaterial($file, '');
			$result['pic_share']['id'] = $default['id'];
			$result['pic_share']['host'] = $default['host'];
			$result['pic_share']['dir'] = $default['dir'];
			$result['pic_share']['filepath'] = $default['filepath'];
			$result['pic_share']['filename'] = $default['filename'];
		}
		return $result;
	}

	public function detail()
	{
		$ret = array();
		if($id = $this->input['id'])
		{
			$ret = $this->obj->get_account_by_id($id);
			if($ret['picurl'])
			{
				$pic = unserialize($ret['picurl']);
				$ret['picurl'] = $pic['host'].$pic['dir'].$pic['filepath'].$pic['filename'];
			}
			if($ret['pic_login'])
			{
				$pic = array();
				$pic = unserialize($ret['pic_login']);
				$ret['pic_login'] = $pic['host'].$pic['dir'].$pic['filepath'].$pic['filename'];
			}
			if($ret['pic_share'])
			{
				$pic = array();
				$pic = unserialize($ret['pic_share']);
				$ret['pic_share'] = $pic['host'].$pic['dir'].$pic['filepath'].$pic['filename'];
			}
		}
		$this->addItem($ret);
		$this->output();
	}

	/**
	 * 根据系统 查询出分享的平台 所需参数：id(系统id,liv_app.systemId)
	 * @name share
	 * @access public
	 * @author 
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return data or false
	 */
	public function get_plat()
	{
		$id = intval($this->input['id']);
		$app = $this->obj->get_app_by_systemId($id);
		if($app)
		{
			$platdatas = $this->obj->get_all_plat();
			foreach($platdatas as $k=>$v)
			{
				$pd[$v['id']]['name'] = $v['name'];
				$pd[$v['id']]['picurl'] = $v['picurl'];
			}
			foreach(explode(',',$app['platIds']) as $k=>$v)
			{
				if(!empty($pd[$v]))
				{
					$ret['id'] = $v;
					$ret['name'] = $pd[$v]['name'];
					$ret['picurl'] = UPLOAD_THUMB_URL.$pd[$v]['picurl'];
					$this->addItem($ret);
				}
			}
			$this->output();
		}
		else
		{
			return false;
		}
	}
	
	public function insert_record($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "record SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
	}
	
	public function check_access_token()
	{
		$access_plat_token = $this->input['access_plat_token'];
		if(!$access_plat_token)
		{
			$this->errorOutput('NO_ACCESS_PLAT_TOKEN');
		}
		$sql = "SELECT * FROM ".DB_PREFIX."token WHERE token='".$access_plat_token."'";
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			$this->errorOutput('NO_INFO');
		}
		if(!$info['access_token'])
		{
			$this->errorOutput('NO_ACCESS_TOKEN');
		}
		$info['access_token'] = unserialize($info['access_token']);
		if(check_token_time($info['addtime'],$info['access_token']['expires_in']))
		{
			$this->addItem('success');
		}
		else
		{
			$this->addItem('faild');
		}
		$this->output();
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new shareApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			