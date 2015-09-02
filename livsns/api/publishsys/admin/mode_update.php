<?php
require('global.php');
define('MOD_UNIQUEID','mode');//模块标识
class modeUpdateApi extends adminUpdateBase
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
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/mode.class.php');
		$this->obj = new mode();
		include(CUR_CONF_PATH . 'lib/template_analyse.php');
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{	
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('mode',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		if( '-1' == $sort_id = $this->input['sort_id'] )
		{
			$this->errorOutput("请选择样式分类");
		}
		$data = $argument = $out_argument = $css_argument = $js_argument = $mode_out_variable = array();
		
		if($this->input['mode_html'])
		{
			if(is_array($this->input['name']))
			{
				foreach($this->input['name'] as $k=>$v)
				{
					$argument['name'][$k] = $this->input['name'][$k];
				}
			}
			if($this->input['sign'])
			{
				$argument['sign'] = $this->input['sign'];
			}
			
			if($this->input['flag'])
			{
				$argument['flag'] = $this->input['flag'];
			}
			if(is_array($this->input['default_value']))
			{
				foreach($this->input['default_value'] as $k=>$v)
				{
					$argument['default_value'][$k] = $this->input['default_value'][$k];
				}
			}
			if($this->input['type'])
			{
				$argument['type'] = $this->input['type'];
				foreach($this->input['type'] as $k=>$v)
				{
					$stva = '';
					if($v =='select')
					{
						$select_key = $this->input['sign'][$k]."_select_key";
						$select_va = $this->input['sign'][$k]."_select_value";
						$str='';
						foreach($this->input[$select_key] as $ke=>$va)
						{
							if(isset($va)&&isset($this->input[$select_va][$ke])&&$va!='')
							{
								$str .= $va.'=>'.$this->input[$select_va][$ke].'#&33';
								$para[$k][$va] = $this->input[$select_va][$ke];
							}
						}
						$stva = str_replace("\r\n",' ',trim($str));
					}
					$argument['other_value'][$k] = $stva;
				}
			}
		}
		
		if($argument['sign'])
		{
			foreach($argument['sign'] as $k=>$v)
			{
				$mode_out_variable['data'][0][$argument['sign'][$k]] = $argument['name'][$k];
				if($argument['name'][$k])
				{
					$p_data = array(
						'sign'				=> 		$v,
						'name'				=> 		$argument['name'][$k],
						'default_value'		=> 		$argument['default_value'][$k],
						'para_type'			=> 		$argument['type'][$k],
						'flag'				=> 		$argument['flag'][$k],
						'type'				=> 		'html',
					);
					if($para[$k])
					{
						$p_data['other_value'] = addslashes(serialize($para[$k]));
					}
					$this->obj->insert_para_name($p_data,'cell_code_para_name');
				}
				unset($p_data);
			}
		}
		
		$content = addslashes(htmlspecialchars_decode($this->input['mode_html']));
		$content = str_replace("&#60;", '<', $content);
		
		$data = array(
			'title'					=>	addslashes($this->input['title']),
			'description'			=>	$this->input['description'],
			'data_cate'				=>	$this->input['data_cate'],
			'data_cate_num'			=>	$this->input['data_cate_num'],
			//'site_id'				=>	$this->input['site_id'],
			'need_pages'			=>	$this->input['need_pages'],		
			'sort_id'				=>	$sort_id,
			'content'				=>	$content,
			'argument'				=>	serialize($argument),
			'mode_out_variable'		=>	addslashes(serialize($mode_out_variable)),
			'mode_type'				=>	$this->input['mode_type'],
			'user_id'       		=>  $this->user['user_id'],
			'user_name'    		 	=>  $this->user['user_name'],
			'ip'       				=>  $this->user['ip'],
			'org_id'				=> 	$this->user['org_id'],
			'sign'					=>uniqid(),
			'update_time'			=>  TIMENOW,
			'create_time'			=>  TIMENOW,
		);
		if($_FILES['indexpic'])
		{
			$file = array();
			$file['Filedata'] = $_FILES['indexpic'];
			$arr = $this->upload($file);
			$data['indexpic'] =	html_entity_decode(serialize($arr));
		}
		
		if($_FILES['effectpic'])
		{
			$file_ = array();
			$file_['Filedata'] = $_FILES['effectpic'];
			$arr_ = $this->upload($file_);
			$data['effectpic'] =	html_entity_decode(serialize($arr_));
		}
		/*if($_FILES['Filepic'])
		{
			require_once(ROOT_PATH . 'lib/class/material.class.php');
			$this->material = new material();
			$pic_name= $_FILES['Filepic']['name'];
			$pic_type = strtolower(strrchr($pic_name,"."));
			$ptypes = $this->settings['pic_types'];
			if(!in_array($pic_type,$ptypes))
			{
				$this->errorOutput("缩略图类型错误，请重新上传");
			}
			$pic['Filedata'] = $_FILES['Filepic'];
			$pic_info = $this->material->addMaterial($pic); //插入图片服务器
			
			if($pic_info)
			{
				$arr = array(
					'host'			=>$pic_info['host'],
					'dir'			=>$pic_info['dir'],
					'filepath'		=>$pic_info['filepath'],
					'filename'		=>$pic_info['filename'],
				);
				$data['indexpic'] =	serialize($arr);
			}	
		}	
		if(!$data['indexpic']&&$this->input['indexpic'])
		{
			$indexpic  = html_entity_decode($this->input['indexpic']);
			$data['indexpic'] = $indexpic;
		}*/
		
		$default_param = html_entity_decode($this->input['default_param'],ENT_QUOTES);
		if(!$default_param)
		{
			$default_param_arr=array();
		}
		else
		{
			eval('$default_param_arr='.trim($default_param,';').';');
		}
		$data['default_param'] = serialize($default_param_arr);
		
		$ret = $this->obj->create($data);
		$r = $this->obj->update_cell_var($ret,$argument);
		
		$datafid = $this->obj->create_out_para('data','0',$ret);
		$fid = $this->obj->create_out_para('0',$datafid,$ret);
		$code_para = $this->input['out_arname'];
		if(is_array($code_para))
		{
			foreach($code_para as $k=>$v)
			{
				if($v!='data')
				{
					$this->obj->create_out_para($v,$fid,$ret,$this->input['out_arment_flag'][$k]);
				}
			}
		}
		
		$default_css = str_replace("css", '', $this->input['default_css']);
		$default_css = str_replace("_", '', $default_css);	
		
		if($this->input['css_arrs'])
		{	
			$css_arrs = explode(',',$this->input['css_arrs']);
			foreach($css_arrs as $key=>$va)
			{
				$css_argument = array();
				$css_name = $va.'name';
				$css_sign = $va.'sign';
				$css_flag = $va.'flag';
				$css_default_value = $va.'default_value';
				$css_other_value = $va.'other_value';
				$css_type = $va.'type';
				$css_title = $va.'title';
				$css_pic = $va.'pic';
				$css_hidpic = $va.'hidpic';
				if($this->input[$va])
				{
					if(is_array($this->input[$css_name]))
					{
						foreach($this->input[$css_name] as $k=>$v)
						{
							$css_argument['css_name'][$k] = $this->input[$css_name][$k];
						}
					}
					$css_argument['css_sign'] = $this->input[$css_sign];
					$css_argument['css_flag'] = $this->input[$css_flag];
					if(is_array($this->input[$css_default_value]))
					{
						foreach($this->input[$css_default_value] as $k=>$v)
						{
							$css_argument['css_default_value'][$k] = $this->input[$css_default_value][$k];
						}
					}
					if($this->input[$css_type])
					{
						$css_argument['css_type'] = $this->input[$css_type];
						foreach($this->input[$css_type] as $k=>$v)
						{
							$stva = '';
							if($v =='select')
							{
								$select_key = $this->input[$css_sign][$k].'_'.$va."select_key";
								$select_va = $this->input[$css_sign][$k].'_'.$va."select_value";
								$str='';
								foreach($this->input[$select_key] as $ke=>$value)
								{
									if(isset($value)&&isset($this->input[$select_va][$ke])&&$value!='')
									{
										$str .= $value.'=>'.$this->input[$select_va][$ke].'#&33';
										$css_para[$k][$value] = $this->input[$select_va][$ke];
									}
								}
								$stva = str_replace("\r\n",' ',trim($str));
							}
							$css_argument['css_other_value'][$k] = $stva;
						}
					}
					$csskey = array('css_name','css_sign','css_flag','css_default_value','css_other_value','css_type');
					$css_argument = hg_format_array_reverse($css_argument,$csskey);
				}
				if($_FILES[$css_pic])
				{
					$pic_['Filedata'] = $_FILES[$css_pic];
					$indexpic_ = $this->upload($pic_);//插入图片服务器
				}
				else
				{
					$pic_ =  html_entity_decode($this->input[$css_hidpic]);
					$indexpic_ = unserialize($pic_);
				}
				$dva = str_replace("css", '', $va);
				$dva = str_replace("_", '', $dva);	
				if($dva == $default_css)
				{
					$default = '1';
				}
				else
				{
					$default = '0';
				}
  				
				$css_data[] = array(
					'mode_id'		=>	$ret,
					'title'			=>	$this->input[$css_title],
					'code'			=>	addslashes($this->input[$va]),
					'para'			=>	serialize($css_argument),
					'indexpic'		=>	serialize($indexpic_),
					'type'			=>	'css',
					'default_css'	=>	$default,
				);
				
				if(is_array($css_argument))
				{
					foreach($css_argument as $key=>$val)
					{
						if($val['css_name'])
						{
							$csdata = array(
								'sign'				=> 		$val['css_sign'],
								'name'				=> 		$val['css_name'],
								'default_value'		=> 		$val['css_default_value'],
								'para_type'			=> 		$val['css_type'],
								'flag'				=> 		$val['css_flag'],
								'type'				=> 		'css',
							);
							if($css_para[$key])
							{
								$csdata['other_value'] = addslashes(serialize($css_para[$key]));
							}
							$this->obj->insert_para_name($csdata,'cell_code_para_name');
						}
						unset($csdata);
					}
				}
			}
			$cda[] = $css_data;
		}
		if($this->input['js'])
		{
			if(is_array($this->input['js_name']))
			{
				foreach($this->input['js_name'] as $k=>$v)
				{
					$js_argument['js_name'][$k] = $this->input['js_name'][$k];
				}
			}
			$js_argument['js_sign'] = $this->input['js_sign'];
			$js_argument['js_flag'] = $this->input['js_flag'];
			if(is_array($this->input['js_default_value']))
			{
				foreach($this->input['js_default_value'] as $k=>$v)
				{
					$js_argument['js_default_value'][$k] = $this->input['js_default_value'][$k];
				}
			}
			if($this->input['js_type'])
			{
				$js_argument['js_type'] = $this->input['js_type'];
				foreach($this->input['js_type'] as $k=>$v)
				{
					$stva = '';
					if($v =='select')
					{
						$select_key = $this->input['js_sign'][$k]."_js_select_key";
						$select_va = $this->input['js_sign'][$k]."_js_select_value";
						$str='';
						foreach($this->input[$select_key] as $ke=>$va)
						{
							if(isset($va)&&isset($this->input[$select_va][$ke])&&$va!='')
							{
								$str .= $va.'=>'.$this->input[$select_va][$ke].'#&33';
								$js_para[$k][$va] = $this->input[$select_va][$ke];
							}
						}
						$stva = str_replace("\r\n",' ',trim($str));
					}
					$js_argument['js_other_value'][$k] = $stva;
				}
			}
		}
		$jskey = array('js_name','js_sign','js_flag','js_default_value','js_other_value','js_type');
		$js_argument = hg_format_array_reverse($js_argument,$jskey);
		
		$js_data =  array();
		if($this->input['js'])
		{
			$js_data = array(
				'title'			=>	$this->input['js_title'],
				'code'			=>	addslashes($this->input['js']),
				'para'			=>	serialize($js_argument),
				'type'			=>	'js',
				'mode_id'		=>	$ret,
			);
		}
		
		if(is_array($js_argument))
		{
			foreach($js_argument as $ky=>$vl)
			{
				if($vl['js_name'])
				{
					$jsdata = array(
						'sign'				=> 		$vl['js_sign'],
						'name'				=> 		$vl['js_name'],
						'default_value'		=> 		$vl['js_default_value'],
						'para_type'			=> 		$vl['js_type'],
						'flag'				=> 		$vl['js_flag'],
						'type'				=> 		'js',
					);
					if($js_para[$ky])
					{
						$jsdata['other_value'] = addslashes(serialize($js_para[$ky]));
					}
					$this->obj->insert_para_name($jsdata,'cell_code_para_name');
				}
				unset($jsdata);
			}
		}
		if($js_data)
		{
			$js_data['sign'] = uniqid();
			$this->obj->update_js_code($js_data);
		}
		
		if(is_array($cda))
		{
			foreach($cda[0] as $k=>$v)
			{
				$v['sign'] = uniqid();
				$this->obj->create_code($v);
			}
		}
		
		$data['id'] = $ret;
		
		$this->addLogs('新增样式' , '' , $data , $data['title']);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{	
		
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('mode',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		if( '-1' == $sort_id = $this->input['sort_id'] )
		{
			$this->errorOutput("请选择样式分类");
		}
		
		$default_css = str_replace("css", '', $this->input['default_css']);
		$default_css = str_replace("_", '', $default_css);	
		$data = $argument = $out_argument = $css_argument = $js_argument = $mode_out_variable = array();
		$out_variable_ids = $this->input['out_variable_ids'];
		if($this->input['mode_html'])
		{
			if(is_array($this->input['name']))
			{
				foreach($this->input['name'] as $k=>$v)
				{
					$argument['name'][$k] = $this->input['name'][$k];
				}
			}
			if($this->input['sign'])
			{
				$argument['sign'] = $this->input['sign'];
			}
			
			if($this->input['flag'])
			{
				$argument['flag'] = $this->input['flag'];
			}
			if(is_array($this->input['default_value']))
			{
				foreach($this->input['default_value'] as $k=>$v)
				{
					$argument['default_value'][$k] = $this->input['default_value'][$k];
				}
			}
			if($this->input['type'])
			{
				$argument['type'] = $this->input['type'];
				foreach($this->input['type'] as $k=>$v)
				{
					$stva = '';
					if($v =='select')
					{
						$select_key = $this->input['sign'][$k]."_select_key";
						$select_va = $this->input['sign'][$k]."_select_value";
						$str='';
						foreach($this->input[$select_key] as $ke=>$va)
						{
							if(isset($va)&&isset($this->input[$select_va][$ke])&&$va!='')
							{
								$str .= $va.'=>'.$this->input[$select_va][$ke].'#&33';
								$para[$k][$va] = $this->input[$select_va][$ke];
							}
						}
						$stva = str_replace("\r\n",' ',trim($str));
					}
					$argument['other_value'][$k] = $stva;
				}
			}
		}
		if($argument['sign'])
		{
			foreach($argument['sign'] as $k=>$v)
			{
				if($argument['name'][$k])
				{
					$p_data = array(
						'sign'				=> 		$v,
						'name'				=> 		$argument['name'][$k],
						'default_value'		=> 		$argument['default_value'][$k],
						'para_type'			=> 		$argument['type'][$k],
						'flag'				=> 		$argument['flag'][$k],
						'type'				=> 		'html',
					);
					if($para[$k])
					{
						$p_data['other_value'] = addslashes(serialize($para[$k]));
					}
					$this->obj->insert_para_name($p_data,'cell_code_para_name');
				}
				unset($p_data);
			}
		}
		$code_para = $this->input['out_arname'];
		if(is_array($code_para))
		{
			$sql="DELETE FROM " . DB_PREFIX . "out_variable  WHERE mod_id =2 AND depath =3  AND expand_id =  " .$this->input['id'];
			$this->db->query($sql);
			$fid  = $this->input['fid']; 
			foreach($code_para as $k=>$v)
			{
				if($v!='data')
				{
					$this->obj->create_out_para($v,$fid,$this->input['id'],$this->input['out_arment_flag'][$k]);
				}
			}
		}
		
		if($argument['sign'])
		{
			foreach($argument['sign'] as $k=>$v)
			{
				$mode_out_variable['data'][0][$argument['sign'][$k]] = $argument['name'][$k];
			}
		}
		$content = addslashes(htmlspecialchars_decode($this->input['mode_html']));
		$content = str_replace("&#60;", '<', $content);
		
		$data = array(
			'id'					=>	$this->input['id'],
			'title'					=>	addslashes($this->input['title']),
			//'sign'					=>	uniqid(),
			'sort_id'				=>	$sort_id,
			//'site_id'				=>	$this->input['site_id'],
			'need_pages'			=>	$this->input['need_pages'],
			'description'			=>	$this->input['description'],
			'data_cate'				=>	$this->input['data_cate'],
			'data_cate_num'			=>	$this->input['data_cate_num'],	
			'argument'				=>	serialize($argument),
			'mode_out_variable'		=>	addslashes(serialize($mode_out_variable)),
			'content'				=>	$content,
			'mode_type'				=>	$this->input['mode_type'],
			'update_time'			=>  TIMENOW,
		);
		if($_FILES['indexpic'])
		{
			$file = array();
			$file['Filedata'] = $_FILES['indexpic'];
			$arr = $this->upload($file);
			$data['indexpic'] =	serialize($arr);
		}
		
		if($_FILES['effectpic'])
		{
			$file_ = array();
			$file_['Filedata'] = $_FILES['effectpic'];
			$arr_ = $this->upload($file_);
			$data['effectpic'] =	serialize($arr_);
		}
		/*if($_FILES['Filedata'])
		{
			$file_name= $_FILES['Filedata']['name'];
			$file_type = strtolower(strrchr($file_name,"."));
			$ftypes = $this->settings['file_types'];
			if(!in_array($file_type,$ftypes))
			{
				$this->errorOutput("样式文件类型错误");
			}
			
			$content = file_get_contents($_FILES['Filedata']['tmp_name']);
			$data['content'] = addslashes($content);
		}
		else
		{
			$data['content'] = addslashes($content);
		}*/
		
		/*if($_FILES['Filepic'])
		{
			$pic_name= $_FILES['Filepic']['name'];
			$pic_type = strtolower(strrchr($pic_name,"."));
			$ptypes = $this->settings['pic_types'];
			if(!in_array($pic_type,$ptypes))
			{
				$this->errorOutput("缩略图类型错误，请重新上传");
			}
			$pic['Filedata'] = $_FILES['Filepic'];
			$pic_info = $this->material->addMaterial($pic);//插入图片服务器
			if($pic_info)
			{
				$arr = array(
					'host'			=>$pic_info['host'],
					'dir'			=>$pic_info['dir'],
					'filepath'		=>$pic_info['filepath'],
					'filename'		=>$pic_info['filename'],
				);
				$data['indexpic'] =	serialize($arr);
			}	
		}	*/
		$default_param = html_entity_decode($this->input['default_param'],ENT_QUOTES);
        if(!$default_param)
		{
			$default_param_arr=array();
		}
		else
		{
			eval('$default_param_arr='.trim($default_param,';').';');
		}
		$data['default_param'] = serialize($default_param_arr);
		
		$s =  "SELECT * FROM " . DB_PREFIX . "cell_mode WHERE id = " . $this->input['id'];
		$pre_data = $this->db->query_first($s);
		
		$data_te = $data;
		unset($data_te['ip']);
		unset($data_te['user_name']);
		unset($data_te['user_id']);
		unset($data_te['update_time']);
		$re = $this->obj->update($data_te,'cell_mode');
		if($re)
		{
			$ret = $this->obj->update($data,'cell_mode');
		
			$sq =  "SELECT * FROM " . DB_PREFIX . "cell_mode WHERE id = " . $this->input['id'];
			$up_data = $this->db->query_first($sq);
			
			$this->addLogs('更新样式' , $pre_data , $up_data , $pre_data['title']);
		}
		else
		{
				$ret = $this->obj->update($data,'cell_mode');
		}
	
		$r = $this->obj->update_cell_var($this->input['id'],$argument);
		
		if($out_variable_ids)
		{
			$this->obj->update_out_variable($this->input['id'],$out_variable_ids);
		}
		/*if($_FILES['Filedata1'])
		{
			$file_name= $_FILES['Filedata1']['name'];
			$file_type = strtolower(strrchr($file_name,"."));
			if($file_type == '.zip')
			{
				$this->obj->unzip_info($_FILES['Filedata1'],$this->input['site_id'],$this->input['id']);
			}
			else
			{	
				$file = $_FILES['Filedata1'];
				$tmp_dir = CUR_CONF_PATH."data/mode/".$this->input['site_id']."/".$this->input['id']."/";
				if (!hg_mkdir($tmp_dir) || !is_writeable($tmp_dir))
				{
					$this->errorOutput($tmp_dir . '目录不可写');
				}
				
				if(!move_uploaded_file($file['tmp_name'], $tmp_dir . $file['name']))
				{
					$this->errorOutput('示意图移动失败');
				}
			}
		}*/
		if($this->input['css_arrs'])
		{	
			$css_arrs = explode(',',$this->input['css_arrs']);
			foreach($css_arrs as $key=>$va)
			{
				$css_argument = array();
				$css_name = $va.'name';
				$css_sign = $va.'sign';
				$css_flag = $va.'flag';
				$css_default_value = $va.'default_value';
				$css_other_value = $va.'other_value';
				$css_type = $va.'type';
				$css_title = $va.'title';
				$css_pic = $va.'pic';
				$css_hidpic = $va.'hidpic';
				if($this->input[$va])
				{
					if(is_array($this->input[$css_name]))
					{
						foreach($this->input[$css_name] as $k=>$v)
						{
							$css_argument['css_name'][$k] = $this->input[$css_name][$k];
						}
					}
					$css_argument['css_sign'] = $this->input[$css_sign];
					$css_argument['css_flag'] = $this->input[$css_flag];
					if(is_array($this->input[$css_default_value]))
					{
						foreach($this->input[$css_default_value] as $k=>$v)
						{
							$css_argument['css_default_value'][$k] = $this->input[$css_default_value][$k];
						}
					}
					if($this->input[$css_type])
					{
						$css_argument['css_type'] = $this->input[$css_type];
						foreach($this->input[$css_type] as $k=>$v)
						{
							$stva = '';
							if($v =='select')
							{
								$select_key = $this->input[$css_sign][$k].'_'.$va."select_key";
								$select_va = $this->input[$css_sign][$k].'_'.$va."select_value";
								$str='';
								foreach($this->input[$select_key] as $ke=>$value)
								{
									if(isset($value)&&isset($this->input[$select_va][$ke])&&$value!='')
									{
										$str .= $value.'=>'.$this->input[$select_va][$ke].'#&33';
										$css_para[$k][$value] = $this->input[$select_va][$ke];
									}
								}
								$stva = str_replace("\r\n",' ',trim($str));
							}
							$css_argument['css_other_value'][$k] = $stva;
						}
					}
					$csskey = array('css_name','css_sign','css_flag','css_default_value','css_other_value','css_type');
					$css_argument = hg_format_array_reverse($css_argument,$csskey);
				}
				if($_FILES[$css_pic])
				{
					$pic__['Filedata'] = $_FILES[$css_pic];
					$indexpic_ = $this->upload($pic__);//插入图片服务器
				}
				else
				{
					$hipic =  html_entity_decode($this->input[$css_hidpic]);
					$indexpic_ = unserialize($hipic);
				}
				
				$dva = str_replace("css", '', $va);
				$dva = str_replace("_", '', $dva);	
				if($dva == $default_css)
				{
					$default = '1';
				}
				else
				{
					$default = '0';
				}
				$css_data[] = array(
					'mode_id'		=>	$this->input['id'],
					'title'			=>	$this->input[$css_title],
					'code'			=>	addslashes($this->input[$va]),
					'para'			=>	serialize($css_argument),
					'indexpic'		=>	serialize($indexpic_),
					'type'			=>	'css',
					'default_css'	=>	$default,
				);
				
				if(is_array($css_argument))
				{
					foreach($css_argument as $key=>$val)
					{
						if($val['css_name'])
						{
							$csdata = array(
								'sign'				=> 		$val['css_sign'],
								'name'				=> 		$val['css_name'],
								'default_value'		=> 		$val['css_default_value'],
								'para_type'			=> 		$val['css_type'],
								'flag'				=> 		$val['css_flag'],
								'type'				=> 		'css',
							);
							if($css_para[$key])
							{
								$csdata['other_value'] = addslashes(serialize($css_para[$key]));
							}
							$this->obj->insert_para_name($csdata,'cell_code_para_name');
						}
						unset($csdata);
					}
				}
				
			}
			$cda[] = $css_data;
		}
		if($this->input['js'])
		{
			if(is_array($this->input['js_name']))
			{
				foreach($this->input['js_name'] as $k=>$v)
				{
					$js_argument['js_name'][$k] = $this->input['js_name'][$k];
				}
			}
			$js_argument['js_sign'] = $this->input['js_sign'];
			$js_argument['js_flag'] = $this->input['js_flag'];
			if(is_array($this->input['js_default_value']))
			{
				foreach($this->input['js_default_value'] as $k=>$v)
				{
					$js_argument['js_default_value'][$k] = $this->input['js_default_value'][$k];
				}
			}
			if(is_array($this->input['js_other_value']))
			{
				foreach($this->input['js_other_value'] as $k=>$v)
				{
					//$v = str_replace("\r\n",' ',trim(html_entity_decode($v)));
					$js_argument['js_other_value'][$k] = $v;
				}
			}
			if($this->input['js_type'])
			{
				$js_argument['js_type'] = $this->input['js_type'];
				foreach($this->input['js_type'] as $k=>$v)
				{
					$stva = '';
					if($v =='select')
					{
						$select_key = $this->input['js_sign'][$k]."_js_select_key";
						$select_va = $this->input['js_sign'][$k]."_js_select_value";
						$str='';
						foreach($this->input[$select_key] as $ke=>$va)
						{
							if(isset($va)&&isset($this->input[$select_va][$ke])&&$va!='')
							{
								$str .= $va.'=>'.$this->input[$select_va][$ke].'#&33';
								$js_para[$k][$va] = $this->input[$select_va][$ke];
							}
						}
						$stva = str_replace("\r\n",' ',trim($str));
					}
					$js_argument['js_other_value'][$k] = $stva;
				}
			}
		}
		
		$jskey = array('js_name','js_sign','js_flag','js_default_value','js_other_value','js_type');
		$js_argument = hg_format_array_reverse($js_argument,$jskey);
		
		$js_data =  array();
		$js_data = array(
			'title'			=>	$this->input['js_title'],
			'code'			=>	addslashes($this->input['js']),
			'para'			=>	serialize($js_argument),
			'type'			=>	'js',
			'mode_id'		=>	$this->input['id'],
		);
		
		//$this->obj->update($css_data,'cell_mode_code');
		if($js_data)
		{
			$js_data['sign'] = uniqid();
			$this->obj->update_js_code($js_data);
		}
		
		if(is_array($js_argument))
		{
			foreach($js_argument as $ke=>$va)
			{
				if($va['js_name'])
				{
					$jdata = array(
						'sign'				=> 		$va['js_sign'],
						'name'				=> 		$va['js_name'],
						'default_value'		=> 		$va['js_default_value'],
						'para_type'			=> 		$va['js_type'],
						'flag'				=> 		$va['js_flag'],
						'type'				=> 		'js',
					);
					if($js_para[$ke])
					{
						$jdata['other_value'] = addslashes(serialize($js_para[$ke]));
					}
					$this->obj->insert_para_name($jdata,'cell_code_para_name');
				}
				unset($jdata);
			}
		}
		$old_css_ids = $css_ids = array();
		if($this->input['css_ids'])
		{	
			$csids =  explode(',',$this->input['css_ids']);
			$css_ids = array_values($csids);
			if($csids)
			{
				foreach($csids as $key=>$va)
				{
					$css = 'css'.$va.'_';
					$css_argument = array();
					$css_name =$css.'name';
					$css_sign = $css.'sign';
					$css_flag = $css.'flag';
					$css_default_value = $css.'default_value';
					$css_other_value = $css.'other_value';
					$css_type = $css.'type';
					$css_title = $css.'title';
					$css_pic = $css.'pic';
					$css_hidpic = $css.'hidpic';
					if($this->input[$css])
					{
						if(is_array($this->input[$css_name]))
						{
							foreach($this->input[$css_name] as $k=>$v)
							{
								$css_argument['css_name'][$k] = $this->input[$css_name][$k];
							}
						}
						$css_argument['css_sign'] = $this->input[$css_sign];
						$css_argument['css_flag'] = $this->input[$css_flag];
						if(is_array($this->input[$css_default_value]))
						{
							foreach($this->input[$css_default_value] as $k=>$v)
							{
								$css_argument['css_default_value'][$k] = $this->input[$css_default_value][$k];
							}
						}
						if($this->input[$css_type])
						{
							$css_argument['css_type'] = $this->input[$css_type];
							foreach($this->input[$css_type] as $k=>$v)
							{
								$stva = '';
								if($v =='select')
								{
									$select_key = $this->input[$css_sign][$k].'_'.$css."select_key";
									$select_va = $this->input[$css_sign][$k].'_'.$css."select_value";
									$str='';
									foreach($this->input[$select_key] as $ke=>$value)
									{
										if(isset($value)&&isset($this->input[$select_va][$ke])&&$value!='')
										{
											$str .= $value.'=>'.$this->input[$select_va][$ke].'#&33';
											$css_para[$k][$value] = $this->input[$select_va][$ke];
										}
									}
									$stva = str_replace("\r\n",' ',trim($str));
								}
								$css_argument['css_other_value'][$k] = $stva;
							}
						}
						$csskey = array('css_name','css_sign','css_flag','css_default_value','css_other_value','css_type');
						$css_argument = hg_format_array_reverse($css_argument,$csskey);
					}
					
					$cs_indexpic = array();
					if($_FILES[$css_pic])
					{
						$pic_['Filedata'] = $_FILES[$css_pic];
						$cs_indexpic = $this->upload($pic_);//插入图片服务器
					}
					else
					{
						$pic =  html_entity_decode($this->input[$css_hidpic]);
						$cs_indexpic = unserialize($pic);
					}
					
					if($va == $default_css)
					{
						$default = '1';
					}
					else
					{
						$default = '0';
					}
					
					$cssda[$va] = array(
						'id'			=>	$va,
						'title'			=>	$this->input[$css_title],
						'code'			=>	addslashes($this->input[$css]),
						'para'			=>	serialize($css_argument),
						'type'			=>	'css',
						'default_css'	=>	$default,
						
					);
	
					if($cs_indexpic)
					{
						$cssda[$va]['indexpic']		= 	serialize($cs_indexpic);
					}
					if(is_array($css_argument))
					{
						foreach($css_argument as $ky=>$vl)
						{	
							if($vl['css_name'])
							{
								$cdata = array(
									'sign'				=> 		$vl['css_sign'],
									'name'				=> 		$vl['css_name'],
									'default_value'		=> 		$vl['css_default_value'],
									'para_type'			=> 		$vl['css_type'],
									'flag'				=> 		$vl['css_flag'],
									'type'				=> 		'css',
								);
								if($css_para[$ky])
								{
									$cdata['other_value'] = addslashes(serialize($css_para[$ky]));
								}
								$this->obj->insert_para_name($cdata,'cell_code_para_name');
							}
							unset($cdata);
						}
					}
				}
			}
			//$this->obj->update($data,'cell_mode');
		}
		if($cssda)
		{
			foreach($cssda as $k=>$v)
			{
				$this->obj->update($v,'cell_mode_code');
			}
		}
		if($this->input['old_css_ids'])
		{
			$old_css_ids = array_values($this->input['old_css_ids']);
		}
		
		if($del_css_ids = array_diff($old_css_ids,$css_ids))
		{
			$delcids = implode(",",$del_css_ids);
			$this->obj->update_css_code(array('del' => '1'), 'cell_mode_code ', " id IN({$delcids})");
		}
		
		if(is_array($cda[0]))
		{
			//$sql="DELETE FROM " . DB_PREFIX . "cell_mode_code WHERE mode_id =".$this->input['id']." AND type = 'css'";;
			//$this->db->query($sql);
			
			foreach($cda[0] as $k=>$v)
			{
				$v['sign'] = uniqid();
				$this->obj->create_code($v);
			}
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	
	function edit_update()
	{	
		$data['content'] = htmlspecialchars_decode(urldecode($this->input['content']));
		$data['id'] = $this->input['id'];
		$data['type'] = $this->input['type'];
		
		$ret = $this->obj->edit_update($data);
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function upload($pic)
	{	
		$material = array();
		$material = $this->material->addMaterial($pic,'','','-1'); //插入图片服务器
			
		//file_put_contents('00',var_export($spe_mater,1));
		if($material)
		{
			$indexpic = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
		}
		else
		{
			$indexpic = '';
		}
		
		return $indexpic;
	}
	
	//样式上传
	function upload_update()
	{		
		$file_name= $_FILES['Filedata']['name'];
		$file_type = strtolower(strrchr($file_name,"."));
		$ftypes = $this->settings['file_types'];
		if($file_type == '.zip')
		{
			$this->obj->unzip_info($_FILES['Filedata']);
			
			$this->addItem('ture');
			$this->output();
		}
		else
		{
			$file = $_FILES['Filedata'];
			$tmp_dir = CUR_CONF_PATH."data/mode/default/";
			if (!hg_mkdir($tmp_dir) || !is_writeable($tmp_dir))
			{
				$this->errorOutput($tmp_dir . '目录不可写');
			}
			
			if(!move_uploaded_file($file['tmp_name'], $tmp_dir . $file['name']))
			{
				$this->errorOutput('示意图移动失败');
			}
			$this->addItem('ture');
			$this->output();
		}	
	}
	
	function delete()
	{		
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('mode',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$ids = $this->input['id'];
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的样式");
		}
		
		$sqll =  "SELECT * FROM " . DB_PREFIX . "cell_mode WHERE id IN (" . $ids . ")";
		$sll = $this->db->query($sqll);
		$ret = array();
		while($rowl = $this->db->fetch_array($sll))
		{
			$pre_data[] = $rowl;
		}
		
		$ret = $this->obj->delete($ids);
		if($ret)
		{
			$this->addLogs('删除样式' , $pre_data , '', '删除样式'.$ids);
		}
		
		$this->addItem($ret);
		$this->output();
		
	}
	function analyse_result($original , $target , $type)
	{
		$return = analyse($original , $target);
		$table1 = draw_table($return[0] , 1 , 'org_');
		$table2 = draw_table($return[1] , 0 , 'tar_');
	
		$table1_fix = ($table2[2] > $table1[2] ? $table2[2]- $table1[2]:0);
		$table2_fix = ($table1[2] > $table2[2] ? $table1[2]- $table2[2]:0);
		$str =  '<style>
		.compare{
			width:48%;
			float:left;
			table-layout:fixed;
			overflow:auto;
			height:400px;
		}
	
		.line
		{
			width:40px;
			background-color:#d0d0e7;
		}
	
		.code{
			width:40%;
			word-break:keep-all;
			white-space:nowrap;
			overflow:hidden;
			text-overflow:ellipsis;
		}
	
		.fix
		{
			background-color:#EADAEB;
		}
	
		.same
		{
			background-color:#e8f2fe;
		}
	
		.notsame
		{
			background-color:#DD5C67;
		}
	
		.span_diff
		{
			background-color:#DD5C67;
		}
	
		.span_same
		{
			background:transparent;
		}
	
		.blank
		{
			background-color:#FFFFFF;
		}
	
		.hover
		{
			background-color:#ffffce;
		}
		</style>
		<script>
			$(document).ready(
			function(){
				jQuery("tr").each(
					function(){
						$(this).mouseover(
							function(){
								var id = this.id.substr(4);
								$("#org_" + id).addClass("hover");
								$("#tar_" + id).addClass("hover");
							}
						);
						$(this).mouseout(
							function(){
								var id = this.id.substr(4);
								$("#org_" + id).removeClass("hover");
								$("#tar_" + id).removeClass("hover");
							}
						);
					}
				);
				jQuery(".compare").each(
					function(){
						$(this).scroll(
							function(){
								var id = this.id.substr(4);
								$("#org_" + id).scrollTop($(this).scrollTop());
								$("#tar_" + id).scrollTop($(this).scrollTop());
								$("#org_" + id).scrollLeft($(this).scrollLeft());
								$("#tar_" + id).scrollLeft($(this).scrollLeft());
							}
						);
					}
				);
			}
			);
		</script>';
		$str_1 =  '<div style="float:left;width:60%"><div class="tbhead"  style="margin-top:1px;padding-top:5px;text-align:center;width:100%;height:27px;" ><span  style="float:left;border-right:1px solid #eaeaea;width:45%;">原文件内容</span></div>';
			$str1 =$str. $str_1. '<div class="compare" id="org_container" ><table border="0" cellspacing="0" cellpadding="0" class="">';
			$str1 .=  $table1[0];
			$str3 = '';
			for($i = 1 ; $i<= $table1_fix ; $i++)
			{
				$str1 . '<tr class="fix" id="org_'.$table1[2].'"><td colspan="2">'.($i + $table1[1]) .'</td></tr>';
				$table1[2]++;
			}
			$str1 .=  '</table></div>';
			
			$str2 =$str. '<div class="compare" id="tar_container"><table border="0" cellspacing="0" cellpadding="0" class="">';
			$str2 .=  $table2[0];
			$str3 .= $table2[0];
			for($i = 1 ; $i<= $table2_fix ; $i++)
			{
				$str2 .= '<tr class="fix" id="tar_'.$table2[2].'"><td colspan="2">&nbsp;</td></tr>';
				$table2[2]++;
			}
			$str2 .= '</table></div></div>';
			$table = array($str1,$str2,$target,$type);
			return $table;
	}
	
	/*public function upload()
	{	
		$material = array();
		$material = $this->material->addMaterial($_FILES,'','','-1');
		//file_put_contents('00',var_export($spe_mater,1));
		if($material)
		{
			$pic = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
			$indexpic = serialize($pic);
		}
		return $indexpic;	
	}*/
	
	public function audit()
	{
	}
	public function sort()
	{
	}
	public function publish()
	{
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

$out = new modeUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>