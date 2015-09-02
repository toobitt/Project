<?php
class template_mode extends InitFrm
{
	private $updateCache;
	public function __construct()
	{
		parent::__construct();
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
			$r['indexpic'] = unserialize($r['indexpic']);
			if(is_dir(CORE_DIR.$r['sign'].'_'.$r['theme']))
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'default_template WHERE template_id = '.$id;
		$template = $this->db->query_first($sql);
		$template['indexpic'] = $template['indexpic'] ? unserialize($template['indexpic']) : array();
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'default_comp WHERE template_id = '.$id;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$cop[] = $r;
		}
		$this->feed = new feedback_mode();
		$form = $this->feed->get_complete_component($id,$cop);
		$ret['attr'] = $template;
		$ret['form'] = $form ? $form : array();
		$ret['info'] = $info;
		return $ret;
	}
	
	public function get_component($id)
	{
		$data = $this->get_style($id);
		$cache_theme_dir = DATA_DIR.'component/';
		$cache_tem_dir = $cache_theme_dir.$data['sign'].'_'.$data['theme'].'/';
		$theme_dir = CORE_DIR.'global/';
		$tem_dir = CORE_DIR.$data['sign'].'_'.$data['theme'].'/component/';
		$com = array_merge($this->settings['standard'],$this->settings['fixed']);
		$com[] = 'header_info';
		$com[] = 'footer_info';
        $com[] = 'captcha';
       	if(file_exists($cache_tem_dir.'/component.php') && !$this->updateCache)
       	{
       		$tcomponent = file_get_contents($cache_tem_dir.'component.php');
       		$t_com = json_decode($tcomponent,1);
       	}
       	elseif($com && is_array($com)) 
       	{
	       	if(file_exists($cache_theme_dir.'component.php') && !$this->updateCache)
	       	{
	       		$gcomponent = file_get_contents($cache_theme_dir.'component.php');
	       		$g_com = json_decode($gcomponent,1);
	       	}
	       	else
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
       			if($v && file_exists($tem_dir.$v.'.html'))
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
	
	public function get_style($id="")
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "template  WHERE is_display = 1 AND id = " .$id ;
		$info = $this->db->query_first($sql);
		$info['indexpic'] = $info['indexpic'] ? unserialize($info['indexpic']) : array();
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
	
	protected function get_template_component($content)
	{
		$standard = $this->settings['standard'];
		$fixed = $this->settings['fixed'];
		$content=preg_replace("/[\t\n\r]+/","",$content);
		$ereg = '/<div[\s]+(?:id|class)="liv_(.*?)".*?>[\s]?(.*?)[\s]?<\/div>/is';
		preg_match_all($ereg,$content,$match);//匹配组件及组件类型
		if($match[1])
		{
			foreach ($match[1] as $k=>$v)
			{
				if($v) //匹配组件里的具体标题
				{
					if(in_array($v,$standard)) //标准组件
					{
						$type = 'standard';
						$form_type_standard = array_flip($standard);
					}
					elseif (in_array($v,$fixed))//固定组件
					{
						$type = 'fixed';
						$form_type_standard = array_flip($fixed);
					}
					else 
					{
						continue;
					}
					$eregspan = '/<span[\s]+(?:id|class)="cell_(.*?)".*?>[\s]?(.*?)[\s]?<\/span>/is';
					preg_match_all($eregspan,$match[2][$k],$matchspan);//匹配组件及组件类型
					if($matchspan)
					{
						$tform = array();
						foreach ($matchspan[1] as $ks=>$vs)
						{
							$tform[$vs] = $matchspan[2][$ks];
						}
					}
					$form[] = array(
						'name'	=> $tform['name'],
						'brief' => $tform['brief'],
						'form_type'	=> $form_type_standard[$v],
						'mode_type'	=> $v,
						'options'	=> $v == 'select' || $v == 'choose' ? array("选项一","选项二","选项三") : '',
						'type'	=> $type,
					);
				}
				
			}
		}
		return $form;
	}
}
?>