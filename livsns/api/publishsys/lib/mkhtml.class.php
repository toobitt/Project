<?php
class mkhtml extends appCommonFrm
{
	/**
	 * 生成的html
	 * */
	private $html = '';
	
	/**
	 * 生成的路径
	 * */
	private $pagedir;
	
	/**
	 * 页面生成方式 1静态  2动态
	 * */
	private $mktype;
	
	/**
	 * 生成文件名称
	 * */
	private $filename;
	
	/**
	 * 自身，列表模板类型id
	 * */
	private $content_type_true = array(0,-1); 
	
	/**
	 * 一次取正文数
	 * */
	private $timecount = 10;
	
	/**
	 * 站点详细信息
	 * */
	private $site = array();
	
	/**
	 * 栏目详细信息
	 * */
	private $column = array();
	
	/**
	 * 当前计划
	 * */
	private $plan=array();
	
	/**
	 * 当前链接
	 * */
	private $weburl;
	
	/**
	 * 是否需要分页
	 * */
	private $is_need_page=false;
	
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/functions.php');
		include_once(CUR_CONF_PATH . 'lib/mkpublish.class.php');
		include_once(CUR_CONF_PATH . 'lib/common.php');
		$this->obj = new mkpublish();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub_config = new publishconfig();
		include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->pub_content = new publishcontent();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	private function setHtml($html)
	{
		$this->html = $this->html.' '.$html;
	}
	
	private function setPagedir($pagedir)
	{
		$this->pagedir .= ($this->pagedir?'/':'').rtrim($pagedir,'/');
		$this->pagedir 	= rtrim($this->pagedir,'/');
	}
	
	private function setMktype($mktype)
	{
		$this->mktype = $mktype;
	}
	
	private function setFilename($filename)
	{
		$this->filename = $filename;
	}
	
	private function setTimecount($timecount)
	{
		$this->timecount = $timecount;
	}
	
	private function setPlan($plan)
	{
		$this->plan = $plan;
	}
	
	private function setSite($site)
	{
		$this->site = $site;
	}
	
	private function setColumn($column)
	{
		$this->column = $column;
	}
	
	private function setWeburl()
	{
		if($this->column)
		{
			$this->weburl = $this->column['column_url'];
		}
		else
		{
			$this->weburl = $this->site['sub_weburl'].'.'.$this->site['weburl'];
		}
	}
	
	public function show($plan,$content_data=array(),$is_from_sys=false)
	{
		$html = '';
		$this->setPlan($plan);
		if(!in_array($plan['content_type'],$this->content_type_true) && !$is_from_sys)
		{
			if($plan['count']===0)
			{
				echo "所取内容条数为0条，不生成";exit;
			}
			else if($plan['count'])
			{
				$count = $plan['count']<=$this->settings['content_num_time']?$plan['count']:$this->settings['content_num_time'];
				$this->setTimecount($count);
				$nextcount = $plan['count']-$count;
				$is_copy_plan = $nextcount>0?true:false;
				$offset = $plan['offset']+$count;
			}
			else
			{
				$offset = $plan['offset']+$this->settings['content_num_time'];
				//表示全部生成
				$this->setTimecount($this->settings['content_num_time']);
				$is_copy_plan = true;
			}
		}
		
		//获取站点信息
		$site = $this->pub_config->get_site_first('*',$plan['site_id']);
		$this->setSite($site);
		if(!$site['tem_material_url'])
		{
			$site['tem_material_url'] = rtrim($site['sub_weburl'],'/').'.'.$site['weburl'];
		}
		
		$this->setPagedir($site['site_dir']);
		$this->setMktype($site['produce_format']);
		$this->setFilename($site['indexname']);
		
		/**页面生成方式  1为静态生成 2为动态生成   
			$page_mk_type页面生成方式  $page_content_mk_type内容生成方式
			$dir 该页面生成的目录
		*/
		if($plan['page_id'])
		{
			$page_type 				= common::get_page_manage($plan['site_id'], $plan['page_id'], 'id');
			$page_type_detail 		= $page_type[$plan['page_id']];
			if($page_type_detail['sign']!='column')
			{
				$this->setPagedir($page_type_detail['sign']);
			}
		}
		
		if($plan['page_data_id'])
		{
			$page_data 				= common::get_page_data($plan['page_id'], '', '', '', $page_type_detail,$plan['page_data_id']);
			$page_data_detail 		= $page_data['page_data'][0];
			$this->setFilename($page_data_detail['colindex']);
			$this->setPagedir($page_data_detail['column_dir']);
			$this->setColumn($page_data_detail);
			if(in_array($plan['content_type'],$this->content_type_true))
			{
				$this->setMktype($page_data_detail['maketype']);
			}
			else
			{
				$this->setMktype($page_data_detail['col_con_maketype']);
			}
		}
		
		$this->setWeburl();
		
		//获取对应模板，单元
		$cell_ret 		= common::merge_cell($plan['site_id'],$plan['page_id'],$plan['page_data_id'],$plan['content_type'],$site);
		if(MK_DEBUG)
		{
			file_in('../cache/log/','处理前所有单元.txt',var_export($cell_ret,1),true,true);
		}
		$template_style = $cell_ret['curr_style']; 					//当前使用中的套系标识
		$template_sign 	= $cell_ret['template_sign']; 				//当前模板标识
		
		//获取模板
//		$site['tem_material_url'] = $site['tem_material_url']?$site['tem_material_url']:(rtrim($site['sub_weburl'],'/').'/'.rtrim($site['weburl'],'/').'/'.'templates');
		$template = common::get_template_cache($template_sign,$template_style,$plan['site_id'],$site['tem_material_url'].'/t');
		if(MK_DEBUG)
		{
			file_in('../cache/log/','处理前的模板.txt',$template,true,true);
		}
		//页面标题关键字描述的插入到模板中
		$template = template_process($template,$this->site,$this->column);
		if(MK_DEBUG)
		{
			file_in('../cache/log/','处理head后模板.txt',$template,true,true);
		}
		if(empty($template))
		{
			echo "没有部署模板";exit;
		}
		
		if(is_array($cell_ret['default_cell']))
		{
//			print_r($cell_ret['default_cell']);exit;
			//获取所有样式
			foreach($cell_ret['default_cell'] as $k=>$v)
			{
				if($v['cell_mode'])
				{
					$mode_idarr[$v['cell_mode']] = $v['cell_mode'];
				}
				if($v['data_source'])
				{
					$datasourceid[$v['data_source']] = $v['data_source'];
				}
			}
			
			//取样式的详细信息
			if($mode_idarr)
			{
				$mode_id_str = implode(',',$mode_idarr);
				$mode_data = common::get_mode_infos($mode_id_str,'','id');
				//获取样式参数
				$mode_variables = common::get_mode_variable($mode_id_str);
				//加载css
				$cssstr = '<style type="text/css">';
				foreach($mode_data as $k=>$v)
				{
					$cssstr .= $v['css'].' ';
				}
				$cssstr .= '</style>';
				$template = str_ireplace('</head>',$cssstr.'</head>',$template);
			}
			if(MK_DEBUG)
			{
				file_in('../cache/log/','处理过样式后模板.txt',$template,true,true);
			}
			/**
			 * 首页，列表的单元处理
			 * */
			if(in_array($plan['content_type'],$this->content_type_true))
			{
				$index_cell = $this->index_cell($cell_ret,$mode_data,$datasourceid,$mode_variables);
				$tem_cell = $index_cell['tem_cell'];
			}
			else
			{
				//取正文
				if($is_from_sys)
				{
					$content_list[0] = $content_data;
				}
				else
				{
					//找到能取列表的数据源
					$content_list_ds = common::get_datasource_by_sign(0,$page_type_detail['sign']);
					$datasource_param = array();
					if($content_list_ds)
					{
						$content_list_ds['argument'] = unserialize($content_list_ds['argument']);
						$datasource_param = datasource_param($content_list_ds['argument']);
					}
					$plan['content_param']['site_id'] 	= $plan['site_id'];
					$plan['content_param']['column_id'] = $plan['page_data_id'];
					$plan['content_param']['count'] = $this->timecount;
					$plan['content_param']['offset'] = $plan['offset'];
					if($content_list_ds)
					{
						$content_list    = common::get_content_by_datasource($content_list_ds['id'],$plan['content_param']+$datasource_param);
//						print_r($content_list);
					}
					
					$new_plan = $plan;
					unset($new_plan['id']);
					$new_plan['offset'] = $offset;
					$new_plan['count'] = $nextcount;
					$new_plan['content_param'] = serialize($plan['content_param']);
					if(empty($content_list))
					{
						$content_list = array(0=>array());
						echo "没有可生成的内容";exit;
					}
					else if($is_copy_plan)
					{
						if(count($content_list)>=$this->settings['content_num_time'])
						{
							$this->obj->insert('mkpublish_plan',$new_plan);
						}
					}
				}
				
				$index_cell = $this->content_cell($cell_ret,$mode_data,$content_list,$datasourceid,$page_type_detail);
				$tem_cell = $index_cell['tem_cell'];
				$tem_cell_file = $index_cell['tem_cell_file'];
//				print_r($tem_cell);exit;
			}
		}
//		$this->pagedir = '../cache/www/aaa/bbb/';
		foreach($tem_cell as $kk=>$vv)
		{
			$this->html = '';
			if($this->mktype==2)
			{
				//动态生成页面
				if(in_array($plan['content_type'],$this->content_type_true))
				{
					$this->include_conf($datasourceid,$site['site_dir'],$this->pagedir);
				}
				else
				{
					$this->include_conf($datasourceid,$site['site_dir'],$tem_cell_file[$kk]['file_dir']);
				}
			}
			else
			{
				//静态生成页面
				$this->include_head($datasourceid,$site['site_dir']);
			}
			$parse_tem_cell_find = $parse_tem_cell_replace = array();
			if($vv)
			{
				foreach($vv as $k=>$v)
				{
					$parse_tem_cell_find[] 		= '/<span[\s]+(?:id|class)="livcms_cell".+?>liv_'.$v['name'].'<\/span>/';
					$parse_tem_cell_replace[] 	= $v['program'];
				}
				$new_template = preg_replace($parse_tem_cell_find,$parse_tem_cell_replace,$template);
			}
			$this->setHtml($new_template);
			//表示首页或者列表页
			if($this->mktype==2)
			{
				//动态生成页面
				if(in_array($plan['content_type'],$this->content_type_true))
				{
					file_in($this->pagedir,$this->filename.".php",$this->html,true,true,$this->filename.".html");
				}
				else
				{
					file_in($tem_cell_file[$kk]['file_dir'],rtrim($tem_cell_file[$kk]['file_name'],'.html').'.php',$this->html,true,true,rtrim($tem_cell_file[$kk]['file_name'],'.html').'.html');
				}
			}
			else
			{
				//静态生成页面
				$cache_filename = md5(uniqid()).'.php';
				file_put_contents(CUR_CONF_PATH."cache/{$cache_filename}",$this->html);
				$result = $this->include_file(CUR_CONF_PATH."cache/{$cache_filename}");
				chmod(CUR_CONF_PATH."cache/{$cache_filename}", 0777);
				unlink(CUR_CONF_PATH."cache/{$cache_filename}");
				if(in_array($plan['content_type'],$this->content_type_true))
				{
					if($this->is_need_page)
					{
						if($this->plan['page_num'])
						{
							$this->filename .= '_'.$this->plan['page_num'];
						}
					}
					file_in($this->pagedir,$this->filename.".html",$result,true,true,$this->filename.".php");
				}
				else
				{
					
					file_in($tem_cell_file[$kk]['file_dir'],$tem_cell_file[$kk]['file_name'],$result,true,true,rtrim($tem_cell_file[$kk]['file_name'],'.html').'.php');
				}
			}
			echo "已生成".$this->pagedir.$this->filename.".html";
		}
		
	}
	
	//首页列表页模板内单元处理
	public function index_cell($cell_ret,$mode_data,$datasourceid,$mode_variables)
	{
		$i = 0;
		//取数据源详细信息
		$datasource_data = $this->datasource_param($datasourceid);
//		print_r($cell_ret['default_cell']);exit;
		foreach($cell_ret['default_cell'] as $k=>$v)
		{
			/**
			 * 单元生成方式是静态的还是动态的  0是静态  1是动态
			 * 动态的判断缓存里是否有包含文件  有则删除
			 * 静态的则把数据源跟缓存运行的结果插入页面
			 * */
			if(!$v['cell_mode'])
			{
				$tem_cell[$i][$k]['name'] 		= $v['cell_name'];
				$tem_cell[$i][$k]['program'] 	= '';
				continue;
			}
			$ds_param = array();
			$tem_cell[$i][$k]['name'] = $v['cell_name'];
			
			//数据源参数跟单元设的参数合并
			$data_input_variable = array();
			if($v['data_source'])
			{
				if(!empty($v['param_asso']['input_param']))
				{
					$data_input_variable = $v['param_asso']['input_param'];
					$data_input_variable = is_array($data_input_variable)?$data_input_variable:array();
				}
			}
			$datasource_data[$v['data_source']] = is_array($datasource_data[$v['data_source']])?$datasource_data[$v['data_source']]:array();
			$ds_param = $data_input_variable+$datasource_data[$v['data_source']];
			
			if($v['cell_type']==1)
			{
				//获取单元缓存
				$cell_cache 				= common::get_cell_cache($v['id']);
				if($v['data_source'])
				{
					$tem_cell[$i][$k]['program'] 	= '$m2o[\'data\'] = $ds_'.$v['data_source'].'->show(array(\'offset\'=>$_GET[\'cur_page\'])+'.var_export($ds_param,true).');';
				}
				
				//先处理单元缓存，再插入到单元中去
				$cell_cache 					= preg_replace('/include_once.+?;/','',$cell_cache);
				$tem_cell[$i][$k]['program'] 	= '<?php '.$tem_cell[$i][$k]['program'].' ?>'.$cell_cache;
				$tem_cell[$i][$k]['program']    = $tem_cell[$i][$k]['program'];
			}
			else
			{
				//静态单元
				$cache_file 				= MODE_CACHE_DIR .$v['id'] . '.php';
				if($v['data_source'])
				{
					$ds_param['offset'] = $this->plan['offset'];
					$m2o['data'] 			= common::get_content_by_datasource($v['data_source'],$ds_param);
				}
				if(!$m2o['data'])
				{
					$m2o['data'] 			= $mode_data[$v['cell_mode']]['default_param'];
				}
				//判断是否是列表，是否需要分页，生成静态分页列表
				if(!empty($mode_variables[$v['cell_mode']]['need_page']))
				{
					$this->is_need_page = true;
					if(!empty($m2o['data']['list']) && is_array($m2o['data']['list']))
					{
						$page_plan = $this->plan;
						$page_plan['offset'] = count($m2o['data']['list'])+$this->plan['offset'];
						$page_plan['content_param'] = serialize($page_plan['content_param']);
						$page_plan['page_num'] = $page_plan['page_num']=='0'?2:++$page_plan['page_num'];
						$this->obj->insert('mkpublish_plan',$page_plan);
					}
					$need_page_info['total_rows'] = $m2o['data']['totalcount'];
					$need_page_info['cur_page'] = intval($this->plan['offset']);
				}
				if(MK_DEBUG)
				{
					file_in('../cache/log/','首页列表页每个单元ID.txt',$cache_file,false,true);
				}
				$tem_cell[$i][$k]['program'] = $this->include_file($cache_file,$m2o['data'],$need_page_info);
				if(MK_DEBUG)
				{
					file_in('../cache/log/','首页列表页每个单元生成结果.txt',$tem_cell[$i][$k]['program'],false,true);
				}
			}
		}
		return array('tem_cell'=>$tem_cell);
	}
	
	//内容页模板内单元处理
	public function content_cell($cell_ret,$mode_data,$content_list,$datasourceid,$page_type)
	{
		$i = 0;
		$tem_cell = $tem_cell_file = array();
		//取数据源详细信息
		$datasource_data = $this->datasource_param($datasourceid);
		foreach($content_list as $kkk=>$vvv)
		{
			if($page_type['sign']=='column')
			{
				if($vvv['file_name'])
				{
					$content_file_name = explode('/',$vvv['file_name']);
				}
				$tem_cell_file[$i]['file_dir'] = $this->pagedir.'/'.trim($content_file_name[0]);
				$tem_cell_file[$i]['file_name'] = trim($content_file_name[1]);
			}
			
			foreach($cell_ret['default_cell'] as $k=>$v)
			{
				/**
				 * 单元生成方式是静态的还是动态的  0是静态  1是动态
				 * 动态的判断缓存里是否有包含文件  有则删除
				 * 静态的则把数据源跟缓存运行的结果插入页面
				 * */
				if(!$v['cell_mode'])
				{
					$tem_cell[$i][$k]['name'] 		= $v['cell_name'];
					$tem_cell[$i][$k]['program'] 	= '';
					continue;
				}
				$ds_param = array();
				$tem_cell[$i][$k]['name'] = $v['cell_name'];
				
				//数据源参数跟单元设的参数合并
				$data_input_variable = array();
				if($v['data_source'])
				{
					if(!empty($v['param_asso']['input_param']))
					{
						$data_input_variable 		= $v['param_asso']['input_param'];
						$data_input_variable 		= is_array($data_input_variable)?$data_input_variable:array();
					}
				}
				$datasource_data['ds_params'][$v['data_source']] = is_array($datasource_data['ds_params'][$v['data_source']])?$datasource_data['ds_params'][$v['data_source']]:array();
				$ds_param = $data_input_variable+$datasource_data['ds_params'][$v['data_source']];
				$ds_param[$datasource_data['ds_datas'][$v['data_source']]['request_field']] = $vvv[$datasource_data['ds_datas'][$v['data_source']]['key_field']];
				if($v['cell_type']==1)
				{
					//获取单元缓存
					$cell_cache 					= common::get_cell_cache($v['id']);
					if($v['data_source'])
					{
						$tem_cell[$i][$k]['program'] 	= '$m2o[\'data\'] = $ds_'.$v['data_source'].'->show('.var_export($ds_param,true).');';
					}
					
					//先处理单元缓存，再插入到单元中去
					$cell_cache 					= preg_replace('/include_once.+?;/','',$cell_cache);
					$tem_cell[$i][$k]['program'] 	= '<?php '.$tem_cell[$i][$k]['program'].' ?>'.$cell_cache;
					$tem_cell[$i][$k]['program']    = $tem_cell[$i][$k]['program'];
				}
				else
				{
//					include_once(CUR_CONF_PATH.'data/m2o/lib/web_functions.php');
					//静态单元
					$cache_file 					= MODE_CACHE_DIR .$v['id'] . '.php';
					if($v['data_source'])
					{
						$m2o['data'] 			= common::get_content_by_datasource($v['data_source'],$ds_param);
					}
					if(!$m2o['data'])
					{
						$m2o['data'] 				= $mode_data[$v['cell_mode']]['default_param'];
					}
					$tem_cell[$i][$k]['program'] 	= $this->include_file($cache_file,$m2o['data']);
				}
			}
			$i++;
		}
		
		return array('tem_cell'=>$tem_cell,'tem_cell_file'=>$tem_cell_file);
	
	}
	
	public function include_conf($datasourceid,$site_dir,$dir)
	{
		//计算出相对框架的配置目录
		$realpath_site_dir 	= @realpath($site_dir);
		$realpath_dir 		= @realpath($dir);
		$realpath_site_dir .= '/'.$this->settings['frame_filename'].'/';
		$realpath_dir 	   .= '/';
		if($realpath_site_dir && $realpath_dir)
		{
			$r = compara_path($realpath_dir,$realpath_site_dir);
			$r = $r;
		}
		$html = '<?php define(\'ROOT_PATH\', \''.$r.'\');' .
				' define(\'CUR_CONF_PATH\', \''.$r.'\'); ';
		$html .= 'require(ROOT_PATH.\''.'global.php\');';
		$html .= 'global $gGlobalConfig;$_configs = $gGlobalConfig;';
		$html .= '$_site = '.var_export($this->site,1).';$_column = '.var_export($this->column,1).';';
		//数据源引入
		if($datasourceid && is_array($datasourceid))
		{
			if(!is_dir($site_dir.'/'.$this->settings['m2o_include']))
			{
				hg_mkdir($site_dir.'/'.$this->settings['m2o_include']);
			}
			foreach($datasourceid as $k=>$v)
			{
				if(!file_exists($site_dir.'/'.$this->settings['m2o_include'].$v.'.php'))
				{
					@copy($this->settings['data_source_dir'].$v.'.php',$site_dir.'/'.$this->settings['m2o_include'].$v.'.php');
				}
				$html .= 'include(ROOT_PATH.\'include/'.$v.'.php\');';
				$class = 'ds_'.$v;
				$html .= '$ds_'.$v.' = new '.$class.'();';
			}
		}
		$html .= ' ?>';
		$this->setHtml($html);
	}
	
	public function include_head($datasourceid,$site_dir)
	{
		$site_dir = rtrim($site_dir,'/');
		$html = '<?php ';
		$html .= 'include_once(CUR_CONF_PATH.\'data/m2o/conf/config.php\');';
		$html .= 'global $gGlobalConfig;$_configs = $gGlobalConfig;';
		//数据源引入
		if($datasourceid && is_array($datasourceid))
		{
			if(!is_dir($site_dir.'/'.$this->settings['m2o_include']))
			{
				hg_mkdir($site_dir.'/'.$this->settings['m2o_include']);
			}
			foreach($datasourceid as $k=>$v)
			{
				if(!file_exists($site_dir.'/'.$this->settings['m2o_include'].$v.'.php'))
				{
					@copy($this->settings['data_source_dir'].$v.'.php',$site_dir.'/'.$this->settings['m2o_include'].$v.'.php');
				}
				$html .= 'include_once($this->settings[\'data_source_dir\'].\'/'.$v.'.php\');';
				$class = 'ds_'.$v;
				$html .= '$ds_'.$v.' = new '.$class.'();';
			}
		}
		$html .= ' ?>';
		$this->setHtml($html);
	}
	
	public function include_file($cache_file,$data = array(),$need_page_info=array())
	{
		global $gGlobalConfig;
		$_configs = $gGlobalConfig;
		$_site = $this->site;
		$_column = $this->column;
		if($data)
		{
			$m2o['data'] = $data;
		}
		if($need_page_info)
		{
			$need_page_info['page_url'] = rtrim($this->weburl,'/');
			$need_page_info['page_filename'] = $this->filename;
		}
		$GLOBALS['need_page_info'] = $need_page_info;
		include_once(CUR_CONF_PATH.'data/m2o/conf/config.php');
		include_once(CUR_CONF_PATH.'data/m2o/lib/web_functions.php');
		
		if(php_check_syntax($cache_file,$error))
		{
			ob_start();
			include $cache_file;
			$result = ob_get_contents();
			ob_clean();
		}
		else
		{	
			$result = $error;
		}
		return $result;
	}
	
	public function datasource_param($datasourceid)
	{
		if(!$datasourceid)
		{
			return array();
		}
		$ret = $result = $ds_datas = array();
		$sql = "SELECT * FROM ".DB_PREFIX."data_source  WHERE id in(".implode(',',$datasourceid).")";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$row['argument'] = $row['argument']?unserialize($row['argument']):array();
			$ret[$row['id']] = $row['argument'];
			$ds_datas[$row['id']] = $row;
		}
		if($ret)
		{
			foreach($ret as $k=>$v)
			{
				if(!$v['ident'])
				{
					continue;
				}
				foreach($v['ident'] as $kk=>$vv)
				{
					$result[$k][$vv] = $v['value'][$kk];
				}
			}
		}
		$r['ds_params'] = $result;
		$r['ds_datas']  = $ds_datas;
		return $r;
	}
	
}
?>