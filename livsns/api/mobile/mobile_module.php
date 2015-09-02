<?php
/***************************************************************************
* $Id: mobile_module.php 11749 2012-09-24 01:11:58Z lijiaying $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
define('MOD_UNIQUEID','mobile_module');
class mobileModuleApi extends outerReadBase
{
	private $mMobileModule;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/mobile_module.class.php';
		$this->mMobileModule = new mobileModule();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function count()
	{
		
	}
	function detail()
	{
		
	}
	public function show()
	{
		//查询手机模块对此应用最低版本限制
		$sql_confine = "SELECT * FROM " . DB_PREFIX . "mobile_module_confine WHERE app_id=".intval($this->input['appid']);
		$q = $this->db->query($sql_confine);
		$version_limit = array();
		while ($g = $this->db->fetch_array($q))
		{
			$version_limit[$g['module_id']]['version'] = $g['version'];
			$version_limit[$g['module_id']]['version_max'] = $g['version_max'];
			
			//应用设置了单独logo，取应用logo
			if($g['icon1'])
			{
				$version_limit[$g['module_id']]['app_icon'] = unserialize($g['icon1']);
			}
			elseif($g['host'])
			{
				$app_icon = array(
					'host' => $g['host'],	
					'dir' => $g['dir'],	
					'filepath' => $g['filepath'],	
					'filename' => $g['filename'],	
				);
				$version_limit[$g['module_id']]['app_icon'] = $app_icon;
			}
			//应用中包含多张图片
			if($g['icon2'])
			{
				$version_limit[$g['module_id']]['app_icon2'] = unserialize($g['icon2']);
			}
			if($g['icon3'])
			{
				$version_limit[$g['module_id']]['app_icon3'] = unserialize($g['icon3']);
			}
			if($g['icon4'])
			{
				$version_limit[$g['module_id']]['app_icon4'] = unserialize($g['icon4']);
			}
		}
		//hg_pre($version_limit);
		$offset = intval($this->input['offset']);
		$count = intval($this->input['count']);
		$count = $count ? $count : 100;
		
		if (!$this->input['debug'])
		{
			$condition = " AND m.status=1 ";
		}
		if($this->input['sort_id'])
		{
			$condition .= " AND m.sort_id IN (" . $this->input['sort_id'] . ")";
		}
		
		if($this->input['exclude_sort_id'])
		{
			$condition .= " AND m.sort_id NOT IN (" . $this->input['exclude_sort_id'] . ")";
		}
		
		$mobileModuleList = $this->mMobileModule->show($condition, $offset, $count, 'ASC');
		//hg_pre($mobileModuleList);
		$filter_modules = explode(',', $this->input['filter_modules']);
		$modules = array();
		if ($mobileModuleList)
		{
			$version = $this->input['version'];
			foreach ($mobileModuleList AS $mobileModule)
			{
				if (in_array($mobileModule['module_id'], $filter_modules))
				{
					continue;
				}
				
				//如果此应用下有限制应用版本的模块，并且应用版本小于或者大于此模块限制版本，跳过
				$vl 	= $version_limit[$mobileModule['id']]['version'];
				$vl_max = $version_limit[$mobileModule['id']]['version_max'];
				
				//if (!$this->input['debug'])
				{
					if($vl && $version < $vl)
					{
						continue;
					}
					if($vl_max && $version > $vl_max)
					{
						continue;
					}
				}
				//根据版本返回url,没有默认返回main_url
				if($mobileModule['version_url'])
				{
					if($mobileModule['version_url'][$version])
					{
						$mobileModule['url'] = $mobileModule['version_url'][$version];
					}
					unset($mobileModule['version_url']);
				}
				//如果手机模块对该应用设置了单独的logo，调用单独设置，否则用公共的
				if($version_limit[$mobileModule['id']]['app_icon'])
				{
					$icon = $version_limit[$mobileModule['id']]['app_icon'];
				}
				else 
				{
					if($mobileModule['icon1'])
					{
						$icon = $mobileModule['icon1'];
					}
					else if($mobileModule['host'])
					{
						$icon = array(
							'host' => $mobileModule['host'],	
							'dir' => $mobileModule['dir'],	
							'filepath' => $mobileModule['filepath'],	
							'filename' => $mobileModule['filename'],	
						);
						unset($mobileModule['host'],$mobileModule['dir'],$mobileModule['filepath'],$mobileModule['filename']);
					}
				}
				$icon['force'] = $this->settings['force_update_icon'];
				$mobileModule['icon'] = $icon;
				//多张图片
				if($version_limit[$mobileModule['id']]['app_icon2'])
				{
					$mobileModule['icon2'] = $version_limit[$mobileModule['id']]['app_icon2'];
				}
				if($version_limit[$mobileModule['id']]['app_icon3'])
				{
					$mobileModule['icon3'] = $version_limit[$mobileModule['id']]['app_icon3'];
				}
				if($version_limit[$mobileModule['id']]['app_icon4'])
				{
					$mobileModule['icon4'] = $version_limit[$mobileModule['id']]['app_icon4'];
				}
				
				if ($mobileModule['type'] == 2 && $this->settings['outlink_version_ctrl'] && $version < 1.73)
				{
					$mobileModule['module_id'] = 'outlink';
				}
				
				$modules[] = $mobileModule;
			}
		}
		//hg_pre($modules);
		if($this->input['need_sort'])
		{
			$sql = "SELECT id,name FROM  " .DB_PREFIX. "module_sort WHERE 1 ";
			if($this->input['exclude_sort_id'])
			{
				$sql .= " AND id NOT IN (" . $this->input['exclude_sort_id'] . ")";
			}
			$sql .= " ORDER BY order_id ASC";
			
			$q = $this->db->query($sql);
			
			$sort = array();
			while ($r = $this->db->fetch_array($q))
			{
				$sort[$r['id']] = $r;
			}
			
			if(!empty($modules))
			{
				foreach ($modules as $k => $v)
				{
					if($sort[$v['sort_id']])
					{
						$sort[$v['sort_id']]['modules'][] = $v;
					}
				}
			}
			
			if(!empty($sort))
			{
				$modules = array();
				foreach ($sort as $v)
				{
					$modules[] = $v;
				}
			}
		}
		$url = '';
		$material = '';
		$force = 1;
		$oldurl = ($this->input['adimg']);
		$ads = array('before' => array(
			'image' => '',
			'force' => $force,
		));

		if ($this->settings['App_adv'] && !$this->input['exclude_ad'])
		{
            $this->input['ad_group_sign'] = $this->input['ad_group_sign']?$this->input['ad_group_sign']:'mobile';
			$curl = new curl($this->settings['App_adv']['host'], $this->settings['App_adv']['dir']);
			$curl->initPostData();
			$curl->addRequestData('group', $this->input['ad_group_sign']);
			$filter_conditions = array(
				'appid'=>$this->input['appid'],
			);
			$curl->addRequestData('vinfo', urlencode(json_encode($filter_conditions)));
			//$curl->addRequestData('flag', 'launch');
			$ad = $curl->request('ad.php');
			if (is_array($ad) && count($ad))
			{
				foreach ($ad AS $k => $v)
				{
					$image = $v['material'];
					$m3u8 = $v['m3u8'];
					$link = $v['link'];
					$url = hg_fetchimgurl($image);
					$params = $v['param']['pos'];
					if ($oldurl != $url)
					{
						$force = 1;
					}
					else
					{
						$force = 0;
					}
					$tmp = explode('-', $v['name']);
					if ($m3u8)
					{
						$kkk = 'm3u8';
					}
					else
					{
						$kkk = 'image';
					}
					if ($tmp[1])
					{
						$ads[$tmp[0]][$tmp[1]][] = array(
							$kkk => $$kkk,
							'force' => $force,
							'link' => $link,
							'params' => $params,
							'time' => $params['time'] / 1000,
						);
					}
					else
					{
						$ads[$tmp[0]] = array(
							$kkk => $$kkk,
							'force' => $force,
							'link' => $link,
							'params' => $params,
							'time' => $params['time'] / 1000,
						);
					}
				}
				$ads['playstrat'] = $ads['before'];

				$ads['before'] = $ads['launch'];
				unset($ads['launch']);

			}
		}
		$weburl = $this->settings['weburl'][$this->input['appid']];
		if (!$weburl)
		{
			$weburl = $this->settings['weburl'][0];
		}
		$config = array(
			'youmeng' => array(
				'appkey' => defined('YOUMENG_KEY') ? YOUMENG_KEY : ''
			),
			'weburl' => $weburl,
		);
		
		if ($this->settings['App_tv_interact'])
		{
			$path = $this->settings['App_tv_interact'];
			
			$host = $path['host'];
			$dir = $path['dir'];
			
			if($host && $dir)
			{
				include_once(ROOT_PATH.'lib/class/curl.class.php');
				$curl = new curl($host,$dir);
				$curl->setSubmitType('post');
				$curl->initPostData();
				
				$link = array();
				$curl->addRequestData('link_jump',1);
				$link = $curl->request('tv_interact.php');
			}
		}
		
		$link_address = $link['link_address'] ? $link['link_address'] : '';
		$link_tip = $link['tip'] ? $link['tip'] : '';
		$config['event'] = array(
	        'yao' => array(
	            'outlink' => $link_address,
	            'tip' => $link_tip,
	        )
		);
		//hg_pre($modules,0);
		
		if($this->input['appid'])
		{
			$sql = "SELECT version,up_url,force_up FROM " . DB_PREFIX . "certificate WHERE appid = " . intval($this->input['appid']);
			$up_info = $this->db->query_first($sql);
		}
		
		if($this->input['version'] == $up_info['version'])
		{
			$up_info['force_up'] = 0;
		}
		$this->addItem_withkey('up_info', $up_info);
		$this->addItem_withkey('module', $modules);
		$this->addItem_withkey('ad', $ads);
		$this->addItem_withkey('config', $config);
		$this->output();
	}

}

$out = new mobileModuleApi();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>