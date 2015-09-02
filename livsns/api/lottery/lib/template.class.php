<?php
class template_mode extends InitFrm
{
	private $updateCache;
	private $comtemp;
	public function __construct()
	{
		parent::__construct();
		if(!defined('CORE_DIR'))
		{
			define('CORE_DIR', CUR_CONF_PATH.'core/');//定义模板目录
		}
		if(!defined('DATA_DIR'))
		{
			define('DATA_DIR', CUR_CONF_PATH.'data/');//定义模板目录
		}
		$this->updateCache = intval($this->input['update_cache']);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "template  WHERE 1 " . $condition . $orderby ;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['indexpic'] = $r['indexpic'] ? unserialize($r['indexpic']) : array();
			if(1 || is_dir(CORE_DIR.$r['sign'].'_'.$r['theme']))
			{
				$info[] = $r;
			}
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "template SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."template SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "template WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "template SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		$info = $this->get_style($id);
		$ret['info'] = $info;
		return $ret;
	}
	
	public function get_component($id)
	{
		$data = $this->get_style($id);
		$fname = $data['sign'].'_'.$data['theme'];
		$cache_theme_dir = DATA_DIR.'component/';		//缓存全局组件路径
		$cache_tem_dir = $cache_theme_dir.$fname.'/';	//缓存主题组件路径
		$theme_dir = CORE_DIR.'global/';				//全局组件源路径
		$tem_dir = CORE_DIR.$fname.'/component/';		//主题组件源路径
		$com = $this->settings['mode_type'];
		if($this->settings['other_mode'])
       	{
       		foreach ($this->settings['other_mode'] as $k=>$v)
       		{
       			$com[] = $k;
       		}
       	}
       	if(file_exists($cache_tem_dir.'/component.php') && !$this->updateCache)		//如果有主题组件缓存，优先读取缓存
       	{
       		$tcomponent = file_get_contents($cache_tem_dir.'component.php');
       		$t_com = json_decode($tcomponent,1);
       	}
       	elseif($com && is_array($com)) 	//如果没有主题组件缓存，则生成主题组件缓存
       	{
	       	if(file_exists($cache_theme_dir.'component.php') && !$this->updateCache) //获取全局组价缓存
	       	{
	       		$gcomponent = file_get_contents($cache_theme_dir.'component.php');
	       		$g_com = json_decode($gcomponent,1);
	       	}
	       	else	//如果没有全局组件缓存，则生成全局组件缓存
	       	{
	       		foreach ($com as $k=>$v)
	       		{
	       			if($v)
	       			{
	       				$g_com[$v] = file_get_contents($theme_dir.$v.'.html');
	       			}
	       		}
	       		if(!is_dir($cache_theme_dir))
	       		{
	       			hg_mkdir($cache_theme_dir);
	       		}
	       		file_put_contents($cache_theme_dir.'component.php', json_encode($g_com));
	       	}
	       	foreach ($com as $k=>$v)
       		{
       			if($v && file_exists($tem_dir.$v.'.html'))	//如果主题自定义了组件，则使用主题组件，否则使用全局组件，生成主题组件缓存
       			{
       				$t_com[$v] = file_get_contents($tem_dir.$v.'.html');
       			}
       			else 
       			{
       				$t_com[$v] = $g_com[$v];
       			}
       		}
       		if(!is_dir($cache_tem_dir))
	       	{
	       		hg_mkdir($cache_tem_dir);
	       	}
       		file_put_contents($cache_tem_dir.'component.php', json_encode($t_com));
       	}
       	return $t_com;
	}
	
	public function get_style($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "template  WHERE 1 AND is_display = 1 AND id = ". $id ;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "template WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "template WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "template WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "template WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "template SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	public function get_template($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'template WHERE id='.$id;
		$template = $this->db->query_first($sql);
		$template['filename'] = $template['filename'] ? $template['filename'] : 'index.html';
		if(!$template['theme'] || !$template['filename'])
		{
			return false;
		}
		$style_dir = CORE_DIR.$template['sign'].'_'.$template['theme'].'/';
		$template_file = $style_dir.$template['filename'];
		if(!is_dir($style_dir) || !is_readable($style_dir))
		{
			return false;
		}
		if(!file_exists($template_file) || !is_readable($template_file))
		{
			return false;
		}
		if(!defined('DATA_DIR') || !DATA_DIR)
    	{
    		define('DATA_DIR', CUR_CONF_PATH.'data/');//定义模板目录
    	}
    	$template['style_dir'] = $style_dir;
    	$template['template_file'] = $template_file;
    	return $template;
	}

		
	public function generation($data,$template_file,$child_data = array())
	{
		$this->comtemp = $this->get_component($data['template_id']);
		$content = file_get_contents($template_file);
		if($child_data)
		{
			$forms = $this->generate_forms($child_data,$data['template_id']);
		}
 		if($data['header_info'])
		{
			$header_info = $this->generation_info($data['header_info'],$data['template_id'],'header_info');
		}
		if($data['footer_info'])
		{
			$footer_info= $this->generation_info($data['footer_info'],$data['template_id'],'footer_info');
		}
		if($data['videos'] && $data['videos'][0])
		{
			$data['video_info'] = $this->generation_info($data['videos'],$data['template_id'],'video');
		}
		if($data['audios'] && $data['audios'][0])
		{
			$data['audio_info'] = $this->generation_info($data['audios'],$data['template_id'],'audio');
		}
		if($data['pictures'])
		{
			$option['options'] = $data['pictures'];
			$data['pictures'] = $this->create_options($this->comtemp['picture'],$option,0,0);//创建简单型
		}
		
		$eregtag = '/<span[\s]+(?:id|class)="[\s]*liv_(\w+)[\s]?(.*?)".*?>liv_(.*?)<\/span>/i';
		preg_match_all( $eregtag, $content, $match );
		if($match)
		{
			foreach ($match[1] as $k=>$v)
			{
				$find[] = $match[0][$k];
				$rep = '';
				if($data[$v] && ($v == 'indexpic' || strpos($match[2][$k],'image')!== false))//索引图或图片
				{
					$rep = $this->replace_picture($match[2][$k],$data[$v]);
				}
				else if($v == 'header_info') //题头部分
				{
					$rep = $header_info;
				}
				else if($v == 'forms') //表单部分
				{
					$rep = $forms;
				}
				else if($v == 'footer_info') //题头部分
				{
					$rep = $footer_info;
				}else
				{
					$rep = $data[$v] || is_string($data[$v]) ? $data[$v] : '';
				}
				$replace[] = $rep;
			}
		}
		
		$content = str_replace($find, $replace, $content);
		$preg = '/{liv_(.*?)}/';
		preg_match_all( $preg, $content, $match_preg );
		if($match_preg[1])
		{
			foreach ($match_preg[1] as $k=>$v)
			{
				$find2[] = $match_preg[0][$k];
				if($v == 'indexpic')
				{
					$repl = hg_fetchimgurl($data['indexpic']);
				}
				else 
				{
					$repl = $data[$v] ? $data[$v] : '';
				}
				$replace2[] = $repl;
			}
		}
		$content = str_replace($find2, $replace2, $content);
		return $content;
	}
	
	public function replace_picture($class,$picture)
	{
		if(strpos($class,'image')!== false)
		{
			$wh = explode('_',$class);
			if($wh)
			{
				foreach ($wh as $v)
				{
					if(strpos($v,'w') !==false)
					{
						$width = ltrim($v,'w') ? ltrim($v,'w') : '';
					}
					if(strpos($v,'h') !==false)
					{
						$height = ltrim($v,'h') ? ltrim($v,'h') : '';
					}
				}
			}
		}
		$rep = '<img src="'.hg_fetchimgurl($picture,$width,$height).'"/>';
		return $rep;
	}
	
	public function generate_forms($form,$template_id)
	{
		if(!$template_id)
		{
			return false;
		}
		if($form)
		{
			foreach ($form as $v)
			{
				$v['brief'] = $v['tips'];
				$find = $replace = array();
				if(!$this->comtemp[$v['mode_type']]) //未设置该组件的套系
				{
					continue;
				}
				switch ($v['mode_type'])
				{
					case 'radio':case 'checkbox':case 'choose':case 'select':
						$forms .= $this->create_options($this->comtemp[$v['mode_type']],$v); //创建带选项型
						break;
					case 'address':case 'time':
						$forms .= $this->create_combine($this->comtemp[$v['mode_type']], $v);//创建组合型
						break;
					default:
						$forms .= $this->create_simple($this->comtemp[$v['mode_type']],$v);//创建简单型
				}
			}
			return $forms;
		}
		
	}
	
	public function generation_info($info,$template_id,$sign)
	{
		if(!$template_id)
		{
			return false;
		}
		if($info)
		{
			if(!$this->comtemp[$sign])
			{
				return false;
			}
			foreach ($info as $v)
			{
				$v['noplay'] = $v['noplay'] ? 'noplay' : '';
				$info_html .= $this->create_simple($this->comtemp[$sign], $v);
			}
		}
		return $info_html;
	}
		
	public function create_options($temp,$info,$need_other = 0,$need_class = 1)
	{
		$info['cor'] = $info['cor'] == '2' && $info['cor'] ? 'checkbox' : 'radio';
		if($info['mode_type'] == 'select')
		{
			$preg = '/<(?:select|span)[\s].*?class="cell_'.$info['mode_type'].'.*?".*?>(.*?)<\/(?:select|span)>/is';
			preg_match_all($preg,$temp,$match_a);
		}else
		{
			$preg = '/(<(?:select|span)[\s]class="cell_element.*?".*?>(.*?)<\/(?:select|span)>)/is';
			preg_match_all($preg,$temp,$match_a);
			$preg_input = '/<span\sclass="cell_input.*?".*?>(.*?)<\/(?:select|span)>/is';
			preg_match_all($preg_input,$temp,$match_input);
		}
		if($match_a)
		{
			preg_match_all('/{cell_(.*?)}/',$match_a[1][0],$match_b);
			if($match_b && $info['options'])
			{
				if(!$info['is_other'] && $need_other)
				{
					$info['options'][] = array(
						'id'			=> '-1',
						'name'			=> '其他',
						'is_other'		=> 1,
						'noplay'		=> 1,
					);
				}
				foreach ($info['options'] as $key=>$op)
				{
					if($op['img_arr'])
					{
						$op['img_info'] = hg_fetchimgurl($op['img_arr'],'400');
					}
					$op['other_sign'] = $op['is_other'] ? 'other' : '';
					if($op['is_other'])
					{
						$op['mode_type'] = 'input';
						$op['brief'] = $op['brief'] ? $op['brief'] : '请注明...';
						$op['unique_name'] = 'other_answer['.$info['id'].']';
						$op['other'] = $this->create_simple($match_input[0][0], $op);
						$op['noplay'] = $op['noplay'] ? 'noplay' : '';
					}
					$find = $replace = array();
					foreach ($match_b[1] as $k=>$v)
					{
						$find[] = $match_b[0][$k];
						if($v == 'options_element')
						{
							$replace[] = $op;
						}
						else if($v == 'key')
						{
							$replace[] = $key;
						}
						else if(strpos($v,'options_')!==false && $op[str_replace('options_','',$v)])
						{
							$replace[] = $op[str_replace('options_','',$v)];
						}
						else{
							$replace[] = $info[$v] ? $info[$v] : '';
						}
					}
					$class = $need_class ? 1 : 2;
					$forms_choose .= str_replace($find,$replace,$match_a[$class][0]).PHP_EOL;
				}
			}
			$temp = str_replace($match_input[0][0],'',$temp);
			$temp = str_replace($match_a[1][0],$forms_choose,$temp);
			$forms = $this->replace_cell($temp,$info);
			return $forms;
		}
	}
	
	public function create_simple($temp,$info)
	{
		$forms = $this->replace_cell($temp,$info);
		return $forms;
	}
	
	public function create_combine($temp,$info)
	{
		$form_b = $form_c = '';
		if($info['element'])
		{
			foreach ($info['element'] as $v)
			{
				$form_a = $form_d = '';
				$preg = '/<span[\s]class="cell_element\s+'.$v['other_sign'].'.*?".*?>.*?<(?:select|span).*?class="cell_.*?">(.*?)<\/(?:select|span)>.*?<\/span>/is';
				preg_match_all($preg,$temp,$match_a);
				$v['noplay'] = !$v['selected'] ? 'noplay' : '';
				if(!$v['selected']) $v['unique_name'] = '';
				if( $v['value'] && $v['mode_type'] == 'select')
				{
					$v['options'] = $v['value'];
					$form_a = $this->create_options($match_a[0][0], $v);
					$temp = str_replace($match_a[0][0],$form_a,$temp);
				}
				else if($v['mode_type'] == 'input')
				{
					$v['brief'] = '请填写详细地址';
					$form_d = $this->create_simple($match_a[1][0], $v);
					$temp= str_replace($match_a[1][0],$form_d,$temp);
				}else 
				{
					$temp= str_replace($match_a[0][0],'',$temp);
				}
			}
		}
		$forms = $this->replace_cell($temp,$info);
		return $forms;
	}
	
	public function replace_cell($temp,$info)
	{
		preg_match_all('/{cell_(.*?)}/',$temp,$match_c);
		if($match_c)
		{
			$find = $replace = array();
			foreach ($match_c[1] as $k=>$v)
			{
				$find[] = $match_c[0][$k];
				$replace[] = $info[$v] ? $info[$v] : '';
			}
			$forms = str_replace($find,$replace,$temp);
		}
		return $forms;
	}
	
	public function create_file($filename_arr = array())
	{
		if($filename_arr)
		{
			foreach ($filename_arr as $v)
			{
				if(!file_exists(DATA_DIR.$v) || $this->updateCache )
				{
					copy(CORE_DIR.$v,DATA_DIR.$v);
				}
			}
		}
    	return true;
	}
	
	//生成css/js/等辅助文件
    public function generate_assist($dir,$sign,$theme,$id)
    {
    	if(!is_dir(DATA_DIR.$sign))
		{
			hg_mkdir(DATA_DIR.$sign);
		}
		$sign_url = DATA_DIR.$sign;
		if(!is_dir($sign_url.'/'.$theme))
		{
			hg_mkdir($sign_url.'/'.$theme);
		}
		$theme_url = $sign_url.'/'.$theme;//模板路径
    	if(!is_dir($theme_url.'/'.$id))
		{
			hg_mkdir($theme_url.'/'.$id);
		}
		$assist_url = $theme_url.'/'.$id;//模板路径
		if (is_dir($dir.'/css'))
        {
        	if(!is_dir($assist_url.'/css') || $this->updateCache)
        	{
	        	hg_mkdir($assist_url.'/css');
	        	file_copy($dir.'css', $assist_url.'/css', array());
        	}
        }
    	if (is_dir($dir.'js'))
        {
        	if(!is_dir($assist_url.'/js') || $this->updateCache)
        	{
	        	hg_mkdir($assist_url.'/js');
	        	file_copy($dir.'js', $assist_url.'/js', array());
        	}
        }
   		if (is_dir($dir.'images'))
        {
        	if(!is_dir($assist_url.'/images') || $this->updateCache)
        	{
	        	hg_mkdir($assist_url.'/images');
        	}
        	file_copy($dir.'images', $assist_url.'/images', array());
        }
    	return true;
    }
 	
}
?>