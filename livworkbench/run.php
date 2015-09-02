<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
 ***************************************************************************/
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'run');
require_once('./global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class run extends uiBaseFrm
{
	private $curl;
	function __construct()
	{
		parent::__construct();
		if (!$this->input['a'])
		{
			$this->input['a'] = 'show';
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function app()
	{
		$appid = intval($this->input['appid']);
		if ($appid)
		{
			$applications = $this->cache->cache['applications'];
			$app_unid = $applications[$appid]['softvar'];
			$this->cache->check_cache('modules');
			$modules = $this->cache->cache['modules'];
			foreach ($modules AS $m)
			{
				if ($m['mod_uniqueid'] == $app_unid && $m['application_id'] == $appid)
				{
					$module_id = $m['id'];
					header('Location:./run.php?a=frame&mid=' . $module_id);
					exit;
					break;
				}
			}
		}
		$this->ReportError('该模块不存在');
	}

	public function frame($message = '')
	{
		$this->db = hg_checkDB();
		$nid = intval($this->input['nid']);
		$module_id = intval($this->input['mid']);
		$sql = "SELECT mn.*,n.node_uniqueid FROM " . DB_PREFIX . "module_node mn LEFT JOIN ".DB_PREFIX."node n ON mn.node_id=n.id  WHERE mn.module_id=" . $module_id . ' AND mn.module_op = \'\'';
		//多节点切换
		if($nid)
		{
			$sql .= " AND mn.node_id = ".$nid;
		}
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			include hg_load_node($row['node_id'], $row['mod_uniqueid']);
			$node_en = $row['node_uniqueid'];
			$nid = $row['node_id'];
			$node_set[] = $row;
		}
		$selfurl = 'run.php?mid=' . $module_id . '&amp;infrm=1' . (isset($nid) ? '&nid=' . $nid : '');
		if(isset($this->input['fid']))
		{
			$selfurl.='&fid='.($this->input['fid']);
		}
		if($node_en)
		{
			$selfurl .= '&amp;node_en='.$node_en;
		}
		$node_iframe_attr = array(
			'src' => $selfurl . '&amp;_firstload=1',
			'attr' => ' class="winframe"',
		);

		/*增加用户自定义传参*/
		foreach ($this->input AS $k => $v)
		{
			if(in_array($k,array('mid','a')))
			{
				continue;
			}
			$node_iframe_attr['src'] = $node_iframe_attr['src'] .'&amp;' .$k . '='. urlencode($v);
		}
		/*增加用户自定义传参*/

		$this->tpl->addVar('show_conf_menu', 1);
		if($module_id)
		{
			$this->cache->check_cache('modules');
			$modules = $this->cache->cache['modules'];
			$appid = $modules[$module_id]['application_id'];
			$this->cache->check_cache('applications');
			$applications = $this->cache->cache['applications'];
			$app_unid = $applications[$appid]['softvar'];
			$app_version = $applications[$appid]['version'];
			$mod_unid = $modules[$module_id]['mod_uniqueid'];

			$this->cache->check_cache('menu_module_' . $app_unid, 'menu_recache');
			$append_menu = $this->cache->cache['menu_module_' . $app_unid];
			if($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				$prms_menus = $this->user['prms_menus'][$app_unid];
				$prms_menus = is_array($prms_menus) ? $prms_menus : explode(',', $prms_menus);
				foreach((array)$append_menu as $k=>$v)
				{
					if(!in_array($v['app_uniqueid'] . '#' . $v['mod_uniqueid'], $prms_menus))
					{
						unset($append_menu[$k]);
					}
				}
				if (!$append_menu)
				{
					$this->tpl->addVar('show_conf_menu', 0);
				}
				if ($hg_data)
				{
					$append_menu = array();
				}
			}
			if (!DEVELOP_MODE)
			{

				$this->tpl->setScriptDir('app_' . $app_unid . '/');
				$this->tpl->setTemplateVersion($app_unid . '/' . $app_version);
			}
			else
			{
				$this->tpl->setScriptDir();
				$this->tpl->setTemplateVersion();
			}
			$this->tpl->setSoftVar($app_unid);
		}

		$this->tpl->addVar('_INPUT', $this->input);
		$this->tpl->setBodyCode(' style="overflow-y:scroll;"');
		$this->tpl->addVar('node_iframe_attr', $node_iframe_attr);
		$this->tpl->addVar('append_menu', $append_menu);
		$this->tpl->addVar('node_set', $node_set);
		$this->tpl->addVar('hg_node_template', $hg_node_template);
		$this->tpl->addVar('application_id', $application_id);
		$this->tpl->addVar('_selfurl', $selfurl);
		$this->tpl->addVar('_node_en', $node_en);
		$this->tpl->outTemplate('iframe_list');
	}

	public function relate_module_show()
	{
		$app_uniq = $this->input['app_uniq'];
		$mod_uniq = $this->input['mod_uniq'];
		$mod_main_uniq = $this->input['mod_main_uniq'];
		if(!$app_uniq)
		{
			$this->ReportError(NO_APPUNIQ);
		}
		if(!$mod_uniq)
		{
			$mod_uniq = $this->input['app_uniq'];
		}
		$this->cache->check_cache('modules');
		$modules = $this->cache->cache['modules'];
		$main_mid = $mid = 0;
		if($modules)
		{
			foreach ($modules as $mod_id => $mod_info)
			{
				if($mod_info['app_uniqueid'] == $app_uniq)
				{
					if($mod_info['mod_uniqueid'] == $mod_uniq)
					{
						$mid = $mod_id;
						if($main_mid)
						{
							break;
						}
					}
					if($mod_info['mod_uniqueid'] == $mod_main_uniq)
					{
						$main_mid = $mod_id;
						if($mid)
						{
							break;
						}
					}
				}
			}
		}
		if(!$mid)
		{
			$this->ReportError(NO_MID);
		}
		$this->input['mid'] = $mid;
		$this->input['main_mid'] = $main_mid;
		$this->input['a'] = $this->input['mod_a'] ? $this->input['mod_a'] : 'show';
		$this->show();
	}

	public function configuare()
	{
		$module_id = intval($this->input['mid']);
		if (!$module_id)
		{
			$this->ReportError('未指定任何模块访问');
		}
		$this->cache->check_cache('modules');
		$modules = $this->cache->cache['modules'];
		$appid = $modules[$module_id]['application_id'];
		$this->cache->check_cache('applications');
		$applications = $this->cache->cache['applications'];
		$app_unid = $applications[$appid]['softvar'];
		$mod_unid = $modules[$module_id]['mod_uniqueid'];

		$this->cache->check_cache('menu_module_' . $app_unid, 'menu_recache');
		$append_menu = $this->cache->cache['menu_module_' . $app_unid];
		//菜单权限过滤开始################
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms_menus'][$app_unid])
			{
				$this->ReportError('没有权限配置');
			}
			$prms_menus = $this->user['prms_menus'][$app_unid];
			$prms_menus = is_array($prms_menus) ? $prms_menus : explode(',', $prms_menus);
			foreach((array)$append_menu as $k=>$v)
			{
				if(!in_array($v['app_uniqueid'] . '#' . $v['mod_uniqueid'], $prms_menus))
				{
					unset($append_menu[$k]);
				}
			}
		}
		else
		{
			$append_menu[] = array(
				'name'	=> '应用设置',	
				'url'	=> 'settings.php?app_uniqueid=' . $app_unid,	
			);
		}
		$append_menu = @array_values($append_menu);
		if (!$append_menu)
		{
			$this->ReportError('没有任何配置');
		}
		//菜单权限过滤结束#################
		$selfurl = $append_menu[0]['url'] . '&amp;infrm=1';
		$node_iframe_attr = array(
			'src' => $selfurl . '&amp;_firstload=1',
			'attr' => ' class="winframe"',
		);

		$this->tpl->addVar('_INPUT', $this->input);
		$this->tpl->setBodyCode(' style="overflow-y:scroll;"');
		$this->tpl->addVar('node_iframe_attr', $node_iframe_attr);
		$this->tpl->addVar('append_menu', $append_menu);
		$this->tpl->addVar('application_id', $application_id);
		$this->tpl->addVar('_selfurl', $selfurl);
		$this->tpl->outTemplate('iframe_list');
	}
	public function show($message = '')
	{
		$this->tpl->addVar('message', $message);
		$this->record_search();
		$this->tpl->addVar('_INPUT', $this->input);
		$this->tpl->addVar('_selfurl', 'run.php?mid=' . $this->input['mid']);
		hg_set_cookie('lastVMod', $this->input['mid']);
		$this->run_program($this->input['a']);
	}
	public function recommend()
	{
		$module_id = $this->input['mid'];
		ob_end_clean();
		ob_start();
		if (DEVELOP_MODE || !@include(CACHE_DIR . 'program/recommend/' . $module_id . '.php'))
		{
			include(ROOT_PATH . 'lib/class/program.class.php');
			$program = new program();
			$module_id = $program->compile($module_id, 'recommend');
			include(CACHE_DIR . 'program/recommend/' . $module_id . '.php');
		}
		ob_end_clean();
		$hg_data_return = is_array($hg_data_return) ? $hg_data_return : json_decode($hg_data_return,1);
		$item = $hg_data_return[0];
		if(!class_exists('column'))
		{
			include_once(ROOT_DIR . 'lib/class/column.class.php');
		}
		$column = new column();
		$publish = array();
		$publish['sites'] = $column->getallsites();
		list($default_site, $default_name) = each($publish['sites']);
		reset($publish['sites']);
		$publish['items'] = $column->getAuthoredColumns($default_site);
		$publish['default_site'] = each($publish['sites']);
		$publish['pub_time'] = $item['pub_time'];
		$publish['custom_filename'] = $item['custom_filename'];
		$hg_print_selected = array();
		if(strpos($this->input['id'], ',') === false)
		{
			$publish['selected_ids'] = $item['column_id'] ? $item['column_id'] : '';
			$publish['selected_items'] = $column->get_selected_column_path($publish['selected_ids']);
		}
		if(is_array($publish['selected_items']))
		{
			foreach ($publish['selected_items'] as $index => $item)
			{
				$hg_print_selected[$index] = array();
				$current = &$hg_print_selected[$index];
				$current['showName'] = '';
				foreach ($item as $sub_item)
				{
					if($sub_item['is_auth'])
					{
						$current['is_auth'] = 1;
					}
					$current['id'] = $sub_item['id'];
					$current['name'] = $sub_item['name'];
					if($sub_item['fid'] == 0)
					{
						$current['showName'] .= $publish['sites'][$sub_item['site_id']] . '>' . $sub_item['name'] . ' > ';
					}
					else {
						$current['showName'] .= $sub_item['name'] . ' > ';
					}
				}
				if(!$current['is_auth'])
				{
					$current['is_auth'] = 0;
				}
				$current['showName'] = substr($current['showName'], 0, -3);
				$selected_names[] = $current['name'];
			}
		}
		$publish['selected_items'] = $hg_print_selected;
		$publish['selected_names'] = isset($selected_names) ? implode(',', $selected_names) : '';
		echo json_encode($publish);exit();
	}

	private function run_program($type)
	{
		$id = $this->input['mid'];
		$this->db = hg_checkDB();
		if (DEVELOP_MODE || !@include(CACHE_DIR . 'program/' . $type . '/' . $id . '.php'))
		{
			include(ROOT_PATH . 'lib/class/program.class.php');
			$program = new program();
			$id = $program->compile($id, $type);
			include(CACHE_DIR . 'program/' . $type . '/' . $id . '.php');
		}
	}


	/**
	 *编目函数
	 *
	 *
	 */
	public function catalog()
	{
		if($this->settings['App_catalog'])
		{
			$module_id = intval($this->input['mid']);
			if($module_id)
			{

				$this->cache->check_cache('modules');
				$modules = $this->cache->cache['modules'];
				$appid = $modules[$module_id]['application_id'];
				$this->cache->check_cache('applications');
				$applications = $this->cache->cache['applications'];
				$app_unid = $applications[$appid]['softvar'];
				$mod_unid = $modules[$module_id]['mod_uniqueid'];
			}
			$Method=$this->input['method']?$this->input['method']:'show';//默认show方法
			$file_name='catalog.php';
			if (stripos($this->input['operate'],'update')!== false)//form表单创建更新识别
			{
				$Method='detail';
			}
			elseif (stripos($Method,'del')!== false)//删除单条编目
			{
				$file_name='catalog_update.php';
			}
			$curl = new curl($this->settings['App_catalog']['host'], $this->settings['App_catalog']['dir']);
			$curl->initPostData();
			$curl->setmAutoInput(FALSE);
			if(!empty($this->input['id']))
			{
				$curl->addRequestData('content_id', $this->input['id']);
			}
			if(!empty($app_unid)&&!empty($mod_unid))
			{
				$curl->addRequestData('app_uniqueid',$app_unid);
				$curl->addRequestData('mod_uniqueid',$mod_unid);
			}
			if($this->input['catalog_sort'])//按分类取编目
			{
				$curl->addRequestData('catalog_sort',$this->input['catalog_sort']);
			}
			if($this->input['catalog_field'])//按删除单条编目
			{
					
				$curl->addRequestData('catalog_field',$this->input['catalog_field']);
			}
			if($this->input['nosortname'])//当nosortname=1的时间去掉分类名
			{
				$curl->addRequestData('nosortname',$this->input['nosortname']);
			}
			if($this->input['yes'])$curl->addRequestData('yes',1);//仅支持编目catalog.php的detail使用.
			$curl->addRequestData('a',$Method);
			$ret = $curl->request($file_name);
			$content_type = 'Content-Type:text/plain';
			header($content_type);
			echo json_encode($ret);
			exit();
		}
	}

	/**
	 * 验证码
	 *
	 */
	public function get_verify_code()
	{
		$type = $this->input['type'];
		if(!$this->settings['App_verifycode'])
		{
			$this->ReportError('验证码应用尚未安装');
		}
		$this->curl = new curl($this->settings['App_verifycode']['host'],$this->settings['App_verifycode']['dir']);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','set_verify_code');
		$this->curl->addRequestData('type',$type);
		$img = $this->curl->request('verifycode.php');
		header('Content-type:image/png');
		echo $img;
		exit();
	}

	/**
	 *
	 * 会员信息列表接口（目前取会员信息公共组件在用） ...
	 */
	public function getMemberInfo()
	{
		if(!$this->settings['App_members'])
		{
			$this->ReportError('对不起，会员应用未安装!');
		}
		$params = array(
			'admin' => array(
				'member'=> array(
					'show'=> array(
						'params'=>array(
							'member_name'=> array('type'=>'string','name'=>'会员名','map'=>'k'),
							'group_id'=> array('type'=>'int','map'=>'gid','name'=>'用户组ID'),
							'page'=> array('type'=>'int','min'=>'0','map'=>'offset','math'=>array(array('type'=>'sub','arenum'=>'1'),array('type'=>'multiply','arenum'=>'mcount')),'name'=>'开启偏移量'),
							'mcount'=> array('type'=>'int','min'=>'1','map'=>'count','name'=>'结束偏移量','value' => '20'),
		),
						'dataMap'=> array(),
		),
				'count'=> 'show',//如果为数组，而为字符串值为另一个方法则参数共享
		),
				'member_group'=> array(
					'show'=> array(
						'params'=>array(
							'mcount'=> array('type'=>'default','map'=>'count','value'=>'1000'),
		),
						'dataMap'=> array(),
		),
		),
		),
			'api'	=> array(
		),
		);
		$data['info'] = $this->CCCurl($params, $this->settings['App_members'], 'show', 'member.php');
		$count = $this->CCCurl($params, $this->settings['App_members'] , 'count', 'member.php');
		$total_num = (int)$count['total'];
		$page = (int)$this->input['page'];
		$mcount = (int)$this->input['mcount'];
		$data['page_info'] = array(
			'current_page' => $page?$page:1,
			'page_num' => $mcount,
			'total_num'	=> $total_num,
			'total_page' => $mcount?ceil($total_num / $mcount):0,
		);
		$data['group_info'] = $this->CCCurl($params,  $this->settings['App_members'], 'show', 'member_group.php');
		header('Content-Type:text/plain');
		echo json_encode($data);
		exit();
	}
	public function getAuthInfo()
	{
		if(!$this->settings['App_auth'])
		{
			$this->ReportError('对不起，权限应用未安装!');
		}
		$params = array(
			'admin' => array(
				'admin'=> array(
					'show'=> array(
						'params' =>array(
						'member_name'=> array('type'=>'string','name'=>'会员名','map'=>'k'),
						'page'=> array('type'=>'int','min'=>'0','math'=>array(array('type'=>'sub','arenum'=>'1'),array('type'=>'multiply','arenum'=>'mcount')),'map'=>'offset','name'=>'开启偏移量'),
						'mcount'=> array('type'=>'int','min'=>'1','map'=>'count','name'=>'结束偏移量','value' => '20'),
						'group_id'=> array('type'=>'int','map'=>'admin_role','name'=>'用户组ID'),
		),
			'dataMap' => array('id'=>'member_id','user_name'=>'member_name','name'=>'groupname'),
		),
				'count'=> 'show',//如果为数组，而为字符串值为另一个方法则参数共享
		),
				'admin_role'=> array(
					'show'=> array(
					'params' =>array(
						 'mcount'=> array('type'=>'default','map'=>'count','value'=>'2000'),
		),
						'dataMap' => array(),
		),
		),
		),
			'api'	=> array(
		),
		);

		$data['info'] = $this->CCCurl($params, $this->settings['App_auth'], 'show', 'admin.php');
		$count = $this->CCCurl($params, $this->settings['App_auth'] , 'count', 'admin.php');
		$total_num = (int)$count['total'];
		$page = (int)$this->input['page'];
		$mcount = (int)$this->input['mcount'];
		$data['page_info'] = array(
			'current_page' => $page?$page:1,
			'page_num' => $mcount,
			'total_num'	=> $total_num,
			'total_page' => ceil($total_num / $mcount),
		);
		$data['group_info'] = $this->CCCurl($params,  $this->settings['App_auth'], 'show', 'admin_role.php');
		header('Content-Type:text/plain');
		echo json_encode($data);
		exit();
	}
	/**
	 *
	 * 公共组件参数控制请求接口 ...
	 * Common Components Curl
	 * @param $params Array 控制参数，格式参考方法内变量注释
	 * @param $setting Array 应用路径数组，array('host'=>xxx,'dir'=>xxx)
	 * @param $Method String 操作方法，必选
	 * @param $fileName String 操作文件名（含扩展名） 必选
	 *
	 */
	private function CCCurl($params,$setting,$Method,$fileName)
	{
		if(!$params || !$setting || !$Method || !$fileName)
		{
			$this->ReportError('对不起,缺少必要参数');
		}
		$nFileName = substr($fileName,0,strrpos($fileName,'.'));
		$adminDir = '';//调用管理目录
		if(!$this->input['noAdmin'])
		{
			$adminDir = 'admin/';
			$apiType = 'admin';
		}
		else{
			$apiType = 'api';
		}
		/**
		 * @var $params = array();
		 *
			是否必填 required = Int 支持类型 String Int Array File
			支持类型 type ＝ ‘int’ ‘string’ ‘array’ ‘file’ ‘default’（整形和字符型会强制转换，array类型传非array会直接报错,文件类型直接提交，default 类型则系统定义值）
			是否禁止纯数字 默认允许 ，bannum  = Int(1,0) 支持类型 String
			是否禁止汉字 默认允许 banchs  = Int(1,0) 支持类型 String
			是否允许特殊字符，默认禁止 specialchar ＝ （1，0）支持类型 String
			限制值内容 legal ＝ array()（例如设置 array(0,1),那么此参数仅允许0和1，那么其它值均非法）支持类型 String Int
			最大最小值 max ＝ Int min ＝ Int （int 型为数字大小，string型为字符串长度）支持类型 String Int
			映射值 map = String （例如 某个参数设置 a，那么他传到其它接口为a） 支持类型 String Int Array File Default
			值名称 name = String （例如 member_id 参数名称为 会员id ） 支持类型 String Int Array File Default
			数学规则 math = Array （例如 'math'=>array(array('type'=>'sub','arenum'=>'1'),array('type'=>'multiply','arenum'=>'mcount'))）type = String 目前仅支持减法和乘法 arenum ＝ Mixed 被计算值，为数字则直接用，为字符串，则为其它参数的值 ；支持参数类型  Int
			默认值 value ＝ Mixed （系统定义值); 支持类型 String Int Array Default 注：当类型!=Default，并设置必填时，此项无效
		 */
		!isset($params[$apiType][$nFileName])&&$this->ReportError('对不起,不支持'.$fileName.'接口文件');
		!isset($params[$apiType][$nFileName][$Method])&&$this->ReportError('对不起,不支持'.$Method.'操作');
		$curl = new curl($setting['host'], $setting['dir']);
		$curl->initPostData();
		$curl->setmAutoInput(false);
		$_Params = array();//某个方法配置
		$_Params = is_array($params[$apiType][$nFileName][$Method])?$params[$apiType][$nFileName][$Method]:$params[$apiType][$nFileName][$params[$apiType][$nFileName][$Method]];
		$tmpParams = is_array($_Params['params'])?$_Params['params']:array();//某个方法参数配置
		if(is_array($tmpParams))
		{
			foreach ($tmpParams as $key => $config)
			{
				if($config['type'] == 'default')
				{
					$newValue = $this->input[$key] = $config['value'];
				}
				else if (!isset($this->input[$key]))
				{
					if($config['required'])
					{
						$this->ReportError('对不起,'.$key.($config['name']?'('.$config['name'].')':'').'为必选参数');
					}
					if(isset($config['value']))
					{
						$this->input[$key] = $config['value'];
					}
					else continue;//不存在跳出
				}
				if(isset($config['map'])&&$config['map'])
				{
					$paramkey = $config['map'];
				}
				else
				{
					$paramkey = $key;
				}
				if($config['type'] == 'string')
				{
					$newValue = (string)$this->input[$key];
				}
				elseif($config['type'] == 'int')
				{
					if(isset($config['math'])&&is_array($config['math']))//数学计算
					{
						foreach ($config['math'] as $Ckey => $Cmath)
						{							
							if($Cmath['type']=='sub')
							{
								if(!isset($tmp_mathvalue))
								{
									$tmp_mathvalue = (int)$this->input[$key];
								}
								if(is_numeric($Cmath['arenum']))
								{
									$tmp_mathvalue -= $Cmath['arenum'];
								}
								elseif (isset($this->input[$Cmath['arenum']]))
								{
									$tmp_mathvalue -= (int)$this->input[$Cmath['arenum']];
								}
								else
								{
									$tmp_mathvalue -= (int)$tmpParams[$Cmath['arenum']]['value'];
								}								
							}
							else if($Cmath['type']=='multiply')
							{
								if(!isset($tmp_mathvalue))
								{
									$tmp_mathvalue = (int)$this->input[$key];
								}
								if(is_numeric($Cmath['arenum']))
								{
									$tmp_mathvalue *= $Cmath['arenum'];
								}
								elseif (isset($this->input[$Cmath['arenum']]))
								{									
									$tmp_mathvalue *=  (int)$this->input[$Cmath['arenum']];
								}
								else
								{
									$tmp_mathvalue *=  (int)$tmpParams[$Cmath['arenum']]['value'];
								}
							}
						}
					}
					if(isset($tmp_mathvalue))
					{
						$newValue = $tmp_mathvalue;
						unset($tmp_mathvalue);
					}
					else
					{
						$newValue = (int)$this->input[$key];
					}
				}
				elseif ($config['type'] == 'array' && !is_array($this->input[$key]))
				{
					$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'类型错误,应该为 Array 类型');
				}
				elseif ($config['type'] == 'file' && $_FILES[$key])
				{
					$files = array($paramkey => $_FILES[$key]);
					$curl->addFile($files);
				}
				if($config['type'] != 'default')
				{
					if(is_string($newValue))
					{
						$strlen = mb_strlen($newValue,'UTF8');
						if(isset($config['min'])&&!($strlen>=$config['min']))
						{
							$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'值小于最小限制');
						}
						if(isset($config['max'])&&!($strlen<=$config['max']))
						{
							$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'值大于最大限制');
						}
						if(isset($config['legal'])&&!in_array($newValue, $config['legal']))
						{
							$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'值不合法，不在可设置范围');
						}
						if($config['bannum']&&is_numeric($newValue))
						{
							$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'禁止全数字');
						}
						elseif($config['banchs']&&preg_match("/([\x81-\xfe][\x40-\xfe])/", $newValue))
						{
							$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'禁止使用汉字');
						}
						elseif(!$config['specialchar']&&preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$newValue))
						{
							$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
						}
					}
					elseif(is_int($newValue))
					{
						if(isset($config['min'])&&!($newValue>=$config['min']))
						{
							$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'值小于最小限制');
						}
						if(isset($config['max'])&&!($newValue<=$config['max']))
						{
							$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'值大于最大限制');
						}
						if(isset($config['legal'])&&!in_array($newValue, $config['legal']))
						{
							$this->ReportError('对不起,参数'.($config['name']?'('.$config['name'].')':'').'值不合法，不在可设置范围');
						}
					}
				}
				$curl->array_to_add($paramkey, $newValue);
			}
		}
		else {
			$this->ReportError('对不起,系统参数配置'.($config['name']?'('.$config['name'].')':'').'出错');
		}

		$curl->addRequestData('a', $Method);
		$reData = $curl->request($adminDir.$fileName);
		$dataMap = $_Params['dataMap']?$_Params['dataMap']:array();
		$newReData = array();
		if(is_array($reData)&&$dataMap)
		{
			foreach ($reData as $k => $v)
			{
				if(is_array($v))
				{
					foreach ($v as $kk => $vv)
					{
						if(array_key_exists($kk, $dataMap))
						{
							$newReData[$k][$dataMap[$kk]] = $vv;
						}
						else
						{
							$newReData[$k][$kk] = $vv;
						}
					}
				}
				elseif(array_key_exists($kk, $dataMap))
				{
					$newReData[$dataMap[$k]] = $v;
				}
				else
				{
					$newReData[$k] = $v;
				}
			}
		}
		else
		{
			$newReData = $reData;
		}
		return $newReData;
	}

}
include (ROOT_PATH . 'lib/exec.php');
?>