<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
class feedback_mode extends BaseFrm
{
	private $updateCache;
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->updateCache = intval($this->input['update_cache']);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "feedback  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		$sort = $this->get_sort();
		$new_reply = $this->is_reply();
		while($r = $this->db->fetch_array($q))
		{
			$r['column'] = $r['column_id'] = $r['column_id'] ? unserialize($r['column_id']) : '';
			if(is_array($r['column_id']) && count($r['column_id'])>0)
			{
				$column_id = array();
				$column_name = array();
				foreach($r['column_id'] AS $k => $v)
				{
					$column_id[] = $k;
					$column_name[] = $v;
				}
				$column_id = @implode(',',$column_id);
				$column_name = @implode(',',$column_name);
				$r['column_id'] = $column_id;
				$r['column_name'] = $column_name;
			}
			$r['indexpic'] = $r['indexpic'] ? unserialize($r['indexpic']) : array();
     		$r['column_url'] = $r['column_url'] ? unserialize($r['column_url']) : '';
			$r['sort_name'] = $sort[$r['node_id']];
			if(file_exists(DATA_DIR.$r['create_time'].$r['id'].'/'.$r['id'].'.html'))
			{
				$r['url'] = FB_DOMAIN.$r['create_time'].$r['id'].'/'.$r['id'].'.html';
			}
			if(strtotime(date('Y-m-d',TIMENOW)) == $r['end_time'])
			{
				$r['end_time_flag'] = $r['end_time'] + 86400 -1 > TIMENOW ? 1 : 0;
			}else 
			{
				$r['end_time_flag'] = $r['end_time'] && $r['end_time'] > TIMENOW ? 1 : 0;
			}
			$r['create_time'] = $r['create_time'] ? date('Y-m-d H:i',$r['create_time']) : 0;
			$r['update_time'] = $r['update_time'] ? date('Y-m-d H:i',$r['update_time']) : 0;
			$r['audit_time'] = $r['audit_time'] ? date('Y-m-d H:i',$r['audit_time']) : 0;
			$r['start_time'] = $r['start_time'] ? date('Y-m-d',$r['start_time']) : 0;
			$r['end_time'] = $r['end_time'] ? date('Y-m-d',$r['end_time']) : 0;
			$r['new_reply'] = $new_reply[$r['id']] ? 1 : 0;
			$info[] = $r;
		}
		return $info;
	}
	
	/**
	 * 
	 * 外部列表接口
	 */
	public function show_all($condition = '',$orderby = '',$limit = '',$need_forms = 0)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "feedback  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$fids = array();
		while($rs = $this->db->fetch_array($q))
		{
			$fids[] = $rs['id'];		
		}
		if(count($fids)>0 && $need_forms)
		{
			$ids = implode(',',$fids);
			$forms = $this->get_forms($ids);
			if($forms)
			{
				foreach ($forms as $k=>$v)
				{
					$all_form[$v['fid']] = $forms;
				}
			}
		}
		$info = array();
		$sort = $this->get_sort();
		$qs = $this->db->query($sql);
		while($r = $this->db->fetch_array($qs))
		{
			unset($r['column_id']);
			unset($r['column_url']);
			$r['indexpic'] = $r['indexpic'] ? unserialize($r['indexpic']) : array();
			$r['sort_name'] = $sort[$r['node_id']];
			if(file_exists(DATA_DIR.$datafile_name.'/index.html') && $this->input['encryption'])
			{
				$r['url'] = FB_DOMAIN.$datafile_name.'/index.html';
			}elseif(file_exists(DATA_DIR.$r['create_time'].$r['id'].'/'.$r['id'].'.html'))
			{
				$r['url'] = FB_DOMAIN.$r['create_time'].$r['id'].'/'.$r['id'].'.html';
			}
			$r['create_time'] = $r['create_time'] ? date('Y-m-d H:i:s',$r['create_time']) : 0;
			$r['update_time'] = $r['update_time'] ? date('Y-m-d H:i:s',$r['update_time']) : 0;
			$r['audit_time'] = $r['audit_time'] ? date('Y-m-d H:i',$r['audit_time']) : 0;
			$r['start_time'] = $r['start_time'] ? date('Y-m-d H:i',$r['start_time']) : 0;
			$r['end_time'] = $r['end_time'] ? date('Y-m-d H:i',$r['end_time']) : 0;
			if($all_form[$r['id']]){
				$r['forms'] = $all_form[$r['id']];
			}
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array(),$table,$order = 1)
	{
		if(!$data || !$table)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . $table . " SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		if($order)
		{
			$sql = " UPDATE ". DB_PREFIX . $table ." SET order_id = {$vid}  WHERE id = {$vid}";
			$this->db->query($sql);
		}
		return $vid;
	}
	
	public function update($id,$table,$data = array(),$keys = 'id')
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX . $table . " WHERE {$keys} = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . $table . " SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE  {$keys} = '"  .$id. "'";
		$this->db->query($sql);
		$data['affected_rows'] = $this->db->affected_rows();
		return $data;
	}
	
	public function detail($id = '',$order = SORT_DESC)
	{
		if(!$id)
		{
			return false;
		}
		$sort = $this->get_sort();
		$sql = "SELECT * FROM " . DB_PREFIX . "feedback  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			return false;
		}
		$info['column'] = @unserialize($info['column_id']);
		if(is_array($info['column']) && count($info['column'])>0)
		{
			$column_id = array();
			$column_name = array();
			foreach($info['column'] AS $k => $v)
			{
				$column_id[] = $k;
				$column_name[] = $v;
			}
			$column_id = @implode(',',$column_id);
			$column_name = @implode(',',$column_name);
			$info['column_id'] = $column_id;
			$info['column_name'] = $column_name;
		}
		if($info['column_url'])
		{
			$info['column_url'] = @unserialize($info['column_url']);
		}
		$info['indexpic'] = $info['indexpic'] ? unserialize($info['indexpic']) : array();
		$info['is_publish'] = $info['expand_id'] ? 1 : 0;
		$info['sort_id'] = $info['node_id'];
		$info['sort_name'] = $sort[$info['node_id']];
		$info['create_time'] = $info['create_time'] ? date('Y-m-d H:i:s',$info['create_time']) : 0;
		$info['update_time'] = $info['update_time'] ? date('Y-m-d H:i:s',$info['update_time']) : 0;
		$info['audit_time'] = $info['audit_time'] ? date('Y-m-d H:i',$info['audit_time']) : 0;
		$info['start_time'] = $info['start_time'] ? date('Y-m-d H:i',$info['start_time']) : 0;
		$info['end_time'] = $info['end_time'] ? date('Y-m-d H:i',$info['end_time']) : 0;
		$info['header_info'] = $info['header_info'] ? unserialize($info['header_info']) : array();
		$info['footer_info'] = $info['footer_info'] ? unserialize($info['footer_info']) : array();
		$forms = $this->get_forms($id,$order);
		$info['forms'] = $forms;
		return $info;
	}
	
	/**外部接口**/
	public function show_detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		$sort = $this->get_sort();
		$sql = "SELECT * FROM " . DB_PREFIX . "feedback  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		unset($info['column_id']);
		unset($info['column_url']);
		$info['indexpic'] = $info['indexpic'] ? unserialize($info['indexpic']) : array();
		$info['sort_name'] = $sort[$info['node_id']];
		$info['create_time'] = $info['create_time'] ? date('Y-m-d H:i:s',$info['create_time']) : 0;
		$info['update_time'] = $info['update_time'] ? date('Y-m-d H:i:s',$info['update_time']) : 0;
		$info['audit_time'] = $info['audit_time'] ? date('Y-m-d H:i',$info['audit_time']) : 0;
		$info['start_time'] = $info['start_time'] ? date('Y-m-d H:i',$info['start_time']) : 0;
		$info['end_time'] = $info['end_time'] ? date('Y-m-d H:i',$info['end_time']) : 0;
		
		$forms = $this->get_forms($id,SORT_ASC);
		$info['forms'] = $forms;
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "feedback WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "feedback WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "feedback WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		//删除标准组建表
		$sql = " DELETE FROM " .DB_PREFIX. "standard WHERE fid IN (" . $id . ")";
		$this->db->query($sql);
		//删除固定组建表
		$sql = " DELETE FROM " .DB_PREFIX. "fixed WHERE fid IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '',$pre_status)
	{
		if(!$id)
		{
			return false;
		}
		switch (intval($pre_status))
		{
			case 0:$status = 2;break;//打回
			case 1:$status = 1;break;//审核
		}
		$data = array(
		    'status'           => $status,
		    'audit_user_id'    => $this->user['user_id'],
		    'audit_user_name'  => $this->user['user_name'],
		    'audit_time'       => TIMENOW,
		);
		$sql = " UPDATE " . DB_PREFIX . "feedback SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id IN ("  .$id. ")";
		$this->db->query($sql);
		return array('id' => $id,'status' => $status);
	}
	
	public function get_forms($id,$order = SORT_DESC)
	{
		if(!$id)
		{
			return false;
		}
		$type = $this->settings['standard'];
		$forms = array();
		$sql = "SELECT * FROM ". DB_PREFIX . "standard WHERE fid IN (".$id.") ORDER BY order_id";
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			if($r['options'])
			{
				$r['options'] = unserialize($r['options']);
			}
			$r['is_common'] = 0;
			$r['form_type'] = intval($r['form_type']);
			$r['type_name'] = $this->settings['form_type'][$r['form_type']]['title'];
			$r['mode_type'] = $type[$r['form_type']];
			$r['type'] = "standard";
			if($r['form_type'] == 5)
			{
				$r['unique_name'] = "file_{$r['id']}[]";
			}
			else 
			{
				$r['unique_name'] = "form[standard_{$r['id']}]";
			}
			$forms[] = $r;
		}
		$sql = "SELECT * FROM ". DB_PREFIX . "fixed WHERE fid IN (".$id.") ORDER BY order_id";
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			$r['is_common'] = 0;
			$r['form_type'] = intval($r['fixed_id']);
			$fixed[$r['id']] = $r;
			$fixed[$r['id']]['mode_type'] = $this->settings['fixed'][$r['fixed_id']];
			switch ($r['fixed_id'])
			{
				case 4 :case 6://固定组件-地址或者时间
					if($r['conf'])
					{
						$member_field = $fixed_conf_id = array();
						$fixed_conf_id = $r['conf'] ? explode(',',$r['conf']) : array();
						if($r['fixed_id'] == 4 && $r['member_field'])
						{
							$member_field = explode(',',$r['member_field']);
							$i = 0;
							$count = count($member_field);
							while($i<$count)
							{
								$field[$member_field[$i]] = $member_field[$i+1];
								$i = $i+2;
							}
							$fixed[$r['id']]['member_field_addr'] = $field;						
						}
						foreach ($fixed_conf_id as $v)
						{
							$fixed_conf[$v] = $this->settings['element'][$v];
							$fixed_conf[$v]['id'] = $v;
							if($v == 1)
							{
								$fixed_conf[$v]['value'] = $this->settings['hour'];
								$fixed_conf[$v]['other_sign'] = 'hour';
							}
							if($v == 2)
							{
								$fixed_conf[$v]['value'] = $this->settings['minit'];
								$fixed_conf[$v]['other_sign'] = 'minit';
							}
							if($v == 3)
							{
								$fixed_conf[$v]['value'] = $this->settings['second'];
								$fixed_conf[$v]['other_sign'] = 'second';
							}
							if($v == 8)
							{
								$fixed_conf[$v]['value'] = $this->show_province();
								$fixed_conf[$v]['other_sign'] = 'province';
							}
							if($v == 9)
							{
								$fixed_conf[$v]['value'] = $this->show_city(PROVINCE_ID);
								$fixed_conf[$v]['other_sign'] = 'city';
							}
							if($v == 10)
							{
								$fixed_conf[$v]['value'] = $this->show_area(CITY_ID);
								$fixed_conf[$v]['other_sign'] = 'area';
							}
							$fixed_conf[$v]['mode_type'] = 'select';
							if($v == 11)
							{
								$fixed_conf[$v]['mode_type'] = 'input';
								$fixed_conf[$v]['other_sign'] = 'detail';
							}
							$fixed_conf[$v]['unique_name'] = "form[fixed_{$r['id']}][{$v}]";
							$fixed[$r['id']]['element'][] = $fixed_conf[$v];
						}
					}
					break;
				case 1 :case 2: case 3:case 5://固定组件-姓名 邮箱 电话
					$fixed[$r['id']]['standard_type'] = 1;
					$fixed[$r['id']]['unique_name'] = "form[fixed_{$r['id']}]";
					if($r['conf'])
					{
						$conf =  unserialize($r['conf']);
						$fixed[$r['id']] = array_merge($fixed[$r['id']],$conf);
						unset($fixed[$r['id']]['conf']);
					}
					break;
				default:
					break;
			}	
			$fixed[$r['id']]['type_name'] = $this->settings['fixed_type'][$r['form_type']]['title'];
			$fixed[$r['id']]['type'] = 'fixed';
		}
		if($fixed)
		{
			$forms = array_merge($forms,$fixed);
		}
		if(!$forms)
		{
			return false;
		}
		foreach ($forms as $key => $value)
		{
			$orderid[$key] = $value['order_id'];
		}
		array_multisort($orderid, $order, $forms);
		return $forms;
	}

	public function formtypes($field)
	{
		//初始化数据
		$id = $field['id'];
		$formtype = $field['form_type'];
		$tp = $field['type'] ? $field['type'] : 'standard';
		$tips = $field['tips'] ?  $field['tips'] :  $field['brief'];
		$member_fileld = $field['member_field'];
		$name = $field['name'];
		if($field['is_required'])
		{
			$name .= '<b>*</b>';
		}
		if($formtype == 3 && $field['cor'] == 2)
		{
			if($field['limit_type'] == 3)
			{
				$limit_type = '只能选';
			}
			elseif($field['limit_type'] == 2)
			{
				$limit_type = '最多选';
			}
			elseif($field['limit_type'] == 1)
			{
				$limit_type = '最少选';
			}
			$name .= '<span style="color:red">(多选：'.$limit_type.$field['op_num'].'项)</span>';
		}
		$formname = $field['unique_name'] ? $field['unique_name'] : 'form['.$tp.'_'.$id.']';
		$width = $field['width'] ? 'width:'.$field['width'].'px;' : '';
		$height = $field['height'] ? 'height:'.$field['height'].'px;' : '';
		$require = $field['is_required'] ? ' required="ture" ': '';
		$cor = $field['cor'] ? $field['cor'] : 1;
		$options = $field['options'];
		$default = $field['default'] ?  $field['default'] : $data[$member_fileld];
		//初始化数据结束
		
		$html = '';
		switch ($formtype)
		{
			case 1:
				$inputtype = $field['fixed_id'] == 2 ? 'email' : $field['fixed_id'] == 5 ? 'date' : 'text';
				switch ($field['fixed_id'] )
				{
					case 2 : $inputtype = 'email';break;
					case 3 : $tips = $tips ? $tips : '参考格式:13812341234,025-88888888';break;
					case 5 : $inputtype = 'date'; $tips = $tips ? $tips : '参照格式 : 2014-01-01'; break;
					default: $inputtype = 'text';break;
				}
				$html = '<input type="'.$inputtype.'" name="'.$formname.'" placeholder="'.$tips.'" value="'.$default.'" '.$require.'/>';
				$class = 'email'; 
				break;
			case 2:
				$html = '<textarea name="'.$formname.'" placeholder="'.$tips.'" '.$require.'>'.$default.'</textarea>';
				$class = 'advice'; 
				break;
			case 3:
				$ftype = ($cor == 2) ? 'checkbox' : 'radio';
				$html = '<ul _tp="'.$tp.'" _type="'.$cor.'" _id="'.$id.'">';
				$default_arr = explode(',', $default);
				if(is_array($options) && count($options)>0)
				{
					foreach ($options as $key=>$option)
        			{
        				$select = in_array($option ,$default_arr) ? 'select' : '';
        				$html .= '<li class="m2o-flex question '.$select.'" _value="'.$option.'" title="'.$option.'">'. "\n";
        				$html .= $option;
        				$html .= '</li>';
        			}	
				}
				$html .= '</ul>';
				$html .= '<input type="hidden" name="'.$formname.'" value="'.$default.'" />';
				$class =  ($cor == 2) ? 'list select-more' : 'list select-one';
				break;
			case 4:
				$default = $default ? $default : '- 请选择 -';
				$html = '';
				$html .= '<div class="button select">'.$default.'</div>';
				$html .= '<select _id="'.$id.'" name="'.$formname.'">';
				$html .= '<option value="">- 请选择 -</option>';
				if($default && $default != '- 请选择 -' && ($field['ele_id'] == 9 || $field['ele_id'] == 10))
				{
					$html .= '<option selected="selected" value="'.$default.'">'.$default.'</option>';
				}
				else 
				{
					if(is_array($options) && count($options)>0)
					{
						foreach ($options as $key=>$option)
	        			{
	        				$select = $option == $default ? 'selected="selected"' : '';
	        				$html .= '<option _id="'.$key.'" '.$select.' value="'.$option.'">'.$option.'</option>';
	        			}	
					}
				}
				$html .= '</select>';
				$class = 'select-box'; 
				$class2 = '';
				if($field['ele_id'])
				{
					switch ($field['ele_id'])
					{
						case 8:
							$class2 = 'prov';break;
						case 9:
							$class2 = 'city';break;
						case 10:
							$class2 = 'area';break;
						default:break;
					}
				}
				break;
			case 5:	
				$default = $default ? 'style="display: block;"' : '';
				$html = '<div class="button upload"><div class="icon-ok" '.$default.'></div>'.$name.'</div>';
				$html .= '<div class="img-box"><span class="touch-del">轻触删除</span></div>';
				$html .= '<input type="file" class="file"  style="opacity:0.00000000000001"  name="'.$formname.'"/>';
				if($field['default'])
				{
					$html .= '<input type="hidden" name="form['.$tp.'_'.$id.']" value="'.$field['default'].'"/>'."\r\n";
				}
				$class = 'file-item'; 
				break;
			case 6:
				$html .= '<span class="line"></span>';
				$class = 'spilter'; 
				break;
			default:break;
			}
		if(!$field['ele_id'] && $formtype !=5 && $formtype !=6) $form .= '<p class="title">'.$name.'</p>';
		if(!$field['ele_id'] && $formtype == 4)
		{
			$form .= '<div class="item file-item">';
		}
		$require_txt = $field['is_required'] ? 1 : 0;
		$form .= '<div class="'.$class.' '.$class2.' item " _require = "'.$require_txt.'">';
		$form .= $html;	
		$form .= '</div>';
		if(!$field['ele_id'] && $formtype == 4)
		{
			$form .= '</div>';
		}
		$form .= "\r\n";
		return $form;
	}
	
	public function forms($id,$order = SORT_ASC)
	{
		if(!$id)
		{
			return false;
		}
		$forms = array();
		$sql = "SELECT * FROM ". DB_PREFIX . "standard WHERE fid = ".$id." ORDER BY order_id";
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			$r['title'] = $r['name'];
			$r['options'] = $r['options'] ? unserialize($r['options']) : array();
			$r['is_common'] = 0;
			$r['mode_type'] = $this->settings['standard'][$r['form_type']];
			$r['type'] = "standard";
			$r['unique_name'] = $r['form_type'] != 5 ? "form[standard_{$r['id']}]" : "file_{$r['id']}";
			if($r['form_type'] == 3 && $r['cor'] == 2)
			{
				$r['unique_name'] .= '[]';
			}
			$forms[] = $r;
		}
		
		$sql = "SELECT * FROM ". DB_PREFIX . "fixed WHERE fid =".$id." ORDER BY order_id";
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			$r['title'] = $r['name'];
			$r['is_common'] = 0;
			$r['form_type'] = $r['fixed_id'];
			$r['mode_type'] = $this->settings['fixed'][$r['fixed_id']];
			$r['type'] = 'fixed';
			if($r['fixed_id'] == 4 || $r['fixed_id'] == 6)
			{
				$element[4] = array(8,9,10,11);
				$element[6] = array(1,2,3);
				$r['element'] = array();
				$fixed_conf_id = $r['conf'] ? explode(',', $r['conf']) : array();
				foreach ($element[$r['fixed_id']] as $v)
				{
					$ele = array(
						'id'	=> $v,
						'mode_type'	=> 'select',
						'unique_name'=>"form[fixed_{$r['id']}][{$v}]",
						'form_type' => 4,
						'selected' => in_array($v,$fixed_conf_id) ? 1 : 0,
					);
					switch ($v)
					{
						case 1:
							$ele['value'] = $this->settings['hour'];
							$ele['other_sign'] = 'hour';
							break;
						case 2:
							$ele['value'] = $this->settings['minit'];
							$ele['other_sign'] = 'minit';
							break;
						case 3:
							$ele['value'] = $this->settings['second'];
							$ele['other_sign'] = 'second';
							break;
						case 8:
							$ele['value'] = $this->show_province();
							$ele['other_sign'] = 'province';
							break;
						case 9:
							$ele['value'] = $this->show_city(PROVINCE_ID);
							$ele['other_sign'] = 'city';
							break;
						case 10:
							$ele['value'] = $this->show_area(CITY_ID);
							$ele['other_sign'] = 'area';
							break;
						case 11:
							$ele['mode_type'] = 'input';
							$ele['form_type'] = '1';
							$ele['other_sign'] = 'detail';
							break;
					}
					$r['element'][] = $ele;
				}
			}else 
			{
				$r['standard_type'] = 1;
				$r['unique_name'] = "form[fixed_{$r['id']}]";
				if($r['conf'])
				{
					$conf =  unserialize($r['conf']);
					$r = array_merge($r,$conf);
					unset($r['conf']);
				}
			}
			$forms[] = $r;
		}
		if(!$forms)
		{
			return false;
		}
		foreach ($forms as $key => $value)
		{
			$orderid[$key] = $value['order_id'];
		}
		array_multisort($orderid, $order, $forms);
		return $forms;
	}
	
	/**
	 * 多行插入数据到数据库
     * Enter description here ...
     * @param  $table 表名
     * @param  $data 插入数据数组
     */
	public function insert_datas($table,$data,$order = 0)
	{
		foreach ($data AS $key => $value)
		{
			foreach ($value as $k=>$v)
			{
				$field[$key][]=$k;
				$val[$key][] = "'".$v."'";
			}
			$alldata[] = '('.implode(',',$val[$key]).')';
		}
		$fields = '('.implode(',',$field[0]).')';
		$val = implode(',',$alldata);
		$sql =" INSERT INTO " . DB_PREFIX .$table .' '.$fields ." VALUES " . $val;
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		foreach ($data as $key=>$value)
		{
			$data[$key]['id'] = $vid;
			if($order)
			{
				$data[$key]['order_id'] = $vid;
				$this->update($vid, $table,$data[$key]);
			}
			$vid++;
		}
		return $data;
	}
	
	public function get_feedback($cond, $field = '*')
	{
		$sql = "SELECT ". $field ." FROM " . DB_PREFIX . "feedback WHERE 1 AND " . $cond;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_feedback_info($id,$con = '',$field = '*')
	{
		$sql = "SELECT ". $field ." FROM " . DB_PREFIX . "feedback WHERE id = ".$id . $cond;
		$info = $this->db->query_first($sql);
		if( $info['start_time'] > TIMENOW)
		{
			$info['flag'] = '未开始';
		}else if($info['end_time'] && $info['end_time'] < TIMENOW)
		{
			$info['flag'] = '已结束';
		}else 
		{
			$info['flag'] = '进行中';
		}
		$info['start_time'] = $info['start_time'] ? date('Y年m月d日',$info['start_time']) : '';
		$info['end_time'] = $info['end_time'] ? date('Y年m月d日',$info['end_time']) : '';
		$info['submit_text'] = $info['submit_text'] ? $info['submit_text'] : '保存';
		$info['page_title'] = $info['page_title'] ? $info['page_title'] : $info['title'];
		$info['indexpic'] = $info['indexpic'] ? unserialize($info['indexpic']) : array();
		$info['header_info'] = $info['header_info'] ? unserialize($info['header_info']) : array();
		$info['footer_info'] = $info['footer_info'] ? unserialize($info['footer_info']) : array();
		return $info;
	}
	
	public function get_feedback_list($cond, $field="*")
	{
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."feedback WHERE 1 AND " . $cond;
		$q = $this->db->fetch_all($sql);
		return $q;
	}
	
	public function get_template($id)
	{
		$sql = 'SELECT id,filename,theme,sign FROM '.DB_PREFIX.'template WHERE id='.$id;
		$template = $this->db->query_first($sql);
		$template['filename'] = $template['filename'] ? $template['filename'] : 'index.html';
		if(!$template['theme'] || !$template['filename'])
		{
			$this->errorOutput('该表单未选择对应套系或者模板');
		}
		//$style_dir = CORE_DIR.$template['style'].'/'.$template['template'].'/';
		$style_dir = CORE_DIR.$template['sign'].'_'.$template['theme'].'/';
		$template_file = $style_dir.$template['filename'];
		if(!is_dir($style_dir) || !is_readable($style_dir))
		{
			$this->errorOutput('对不起，该套系下的模板不可用');
		}
		if(!file_exists($template_file) || !is_readable($template_file))
		{
			$this->errorOutput('对不起，该模板不可用');
		}
		if(!defined('DATA_DIR') || !DATA_DIR)
    	{
    		define('DATA_DIR', CUR_CONF_PATH.'data/');//定义模板目录
    	}
    	$ret = $template;
    	$ret['style_dir'] = $style_dir;
    	$ret['template_file'] = $template_file;
    	return $ret;
	}
	/**
	 * 
	 * @Description: 上传图片服务器 
	 */
	public function uploadToPicServer($file,$content_id)
	{
		if(!$file)
		{
			return false;
		}
		$material = $this->material->addMaterial($file,$content_id); //插入图片服务器
		return $material;
	}
	
	public function get_allow_type()
	{
		$material = $this->material->get_allow_type(); //插入图片服务器
		if($material)
		{
			foreach ($material as $key=>$val)
			{
				foreach ($val as $k=>$v)
				{
					$mat[] = '.'.$k;
				}
			}
		}
		return $mat;
	}
	//根据url上传图片
	public function localMaterial($url,$cid)
	{
		$material = $this->material->localMaterial($url,$cid);
		return $material[0];
	}
	/**
	 * 
	 * @Description 视频上传
	 */
	public function uploadToVideoServer($file,$title = '',$brief = '')
	{
		if(!$file)
		{
			return false;
		}
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($file);
		$curl->addRequestData('title', $title);
		$curl->addRequestData('comment',$brief);
		$curl->addRequestData('vod_leixing',2);
		$ret = $curl->request('create.php');
		if(!$ret[0])
		{
			return false;
		}
		return $ret[0];
	}
	
		/**
	 * 
	 * @Description  获取视频的配置
	 * @author Kin
	 * @date 2013-4-13 下午04:48:54
	 */
	public function getVideoConfig()
	{
		$videoConfig = array();
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','__getConfig');
		$ret = $curl->request('index.php');
		if (empty($ret))
		{
			return false;
		}
		$temp = explode(',', $ret[0]['video_type']['allow_type']);
		$videoConfig['type'] = $temp;
		if (is_array($temp) && !empty($temp))
		{
			foreach ($temp as $val)
			{
				$videoType[] = ltrim($val,'.');
				//$videoConfig['type'][] = 'video/'.ltrim($val,'.');
			}
			$videoConfig['hit'] = implode(',', $videoType);
			
		}
		return $videoConfig;
	}
	
	
    /**
     * 
     * @Description: 单图片上传入库
     */
	public function upload($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	
		
	//显示省
	public function show_province()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'province WHERE 1';
		$province = $this->db->fetch_all($sql);
		foreach ($province as $v)
		{
			$provinces[$v['id']] = $v['name'];
		}
		return $provinces;
	}
	
	//显示市
	public function show_city($province_id)
	{
		if(!$province_id)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'city WHERE 1 AND province_id = '.$province_id;
		$city = $this->db->fetch_all($sql);
		foreach ($city as $v)
		{
			$citys[$v['id']] = $v['city'];
		}
		return $citys;
	}
	
	//显示区
	public function show_area($city)
	{
		if(!$city)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'area WHERE 1 AND city_id = '.$city;
		$area = $this->db->fetch_all($sql);
	    foreach ($area as $v)
		{
			$areas[$v['id']] = $v['area'];
		}
		return $areas;
	}
	
	public function get_sort()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'feedback_node WHERE 1 ';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$sort[$r['id']] = $r['name'];
		}
		return $sort;
	}
	
	
	public function get_sort_by_id($id)
	{
		$sql = 'SELECT name FROM '.DB_PREFIX.'feedback_node WHERE 1 AND id='.$id;
		$q = $this->db->query_first($sql);
		$sort = $q['name'];
		return $sort;
	}
	
	private function is_reply()
	{
		$sql = 'SELECT SUM(new_reply) as sum , feedback_id FROM '.DB_PREFIX.'record_person GROUP BY feedback_id ';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$reply[$r['feedback_id']] = $r['sum'] > 0 ? 1 : 0;
		}
		return $reply;
	}
	
	//知道person_id 获取提交结果
	public function get_result_with_pid($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'record WHERE person_id = '.$id .' ORDER BY order_id DESC';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['unique_name'] = "form[{$r['type']}_{$r['form_id']}]";
			$result[] = $r;
		}
		return $result;
	}
	
	public function generation($feedback,$template_file)
	{
        $this->temp = new template_mode();
        $this->comtemp = $this->temp->get_component($feedback['template_id']);
		$content = file_get_contents($template_file);
		$forms = $this->generate_forms($feedback['forms'],$feedback['template_id'], $content);
		if($feedback['header_info'])
		{
			$header_info = $this->generation_info($feedback['header_info'],$feedback['template_id'],$content,'header_info');
		}
		if($feedback['footer_info'])
		{
			$footer_info= $this->generation_info($feedback['footer_info'],$feedback['template_id'],$content,'footer_info');
		}
        if($feedback['is_verifycode'])
        {
            $replace_info['captcha_url'] = FB_DOMAIN.'feedback.php?a=captcha&type='.$feedback['verifycode_type'];
            $captcha= $this->replace_cell($this->comtemp['captcha'],$replace_info);
        }
		$eregtag = '/<span[\s]+(?:id|class)="[\s]*liv_(\w+)[\s]?(.*?)".*?>liv_(.*?)<\/span>/i';
		preg_match_all( $eregtag, $content, $match );
		if($match)
		{
			foreach ($match[1] as $k=>$v)
			{
				$find[] = $match[0][$k];
				$rep = '';
				if($feedback[$v] && ($v == 'indexpic' || strpos($match[2][$k],'image')!== false))//索引图或图片
				{
					$rep = $this->replace_picture($match[2][$k],$feedback[$v]);
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
				}
                else if($v == 'captcha') //题头部分
                {
                    $rep = $captcha;
                }
                else
				{
					$rep = $feedback[$v] || is_string($feedback[$v]) ? $feedback[$v] : '';
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
					$repl = hg_fetchimgurl($feedback['indexpic']);
				}
				else 
				{
					$repl = $feedback[$v] ? $feedback[$v] : '';
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
	
	public function generate_forms($form,$template_id,$content)
	{
		if(!$template_id)
		{
			return false;
		}
		$this->temp = new template_mode();
		$comtemp = $this->temp->get_component($template_id);
		if($form)
		{
			foreach ($form as $v)
			{
				if($v['is_required'])
				{
					$v['required'] = '*';
				}
				$find = $replace = array();
				if(!$comtemp[$v['mode_type']]) //未设置该组件的套系
				{
					continue;
				}
				switch ($v['mode_type'])
				{
					case 'choose':case 'select':
						$forms .= $this->create_options($comtemp[$v['mode_type']],$v); //创建带选项型
						break;
					case 'address':case 'time':
						$forms .= $this->create_combine($comtemp[$v['mode_type']], $v);//创建组合型
						break;
					default:
						$forms .= $this->create_simple($comtemp[$v['mode_type']],$v);//创建简单型
				}
			}
			return $forms;
		}
		
	}
	
	public function create_options($temp,$info)
	{
		$info['cor'] = $info['cor'] == '2' && $info['cor'] ? 'checkbox' : 'radio';
		if($info['form_type'] == 3)
		{
			$preg = '/(<(?:select|span)[\s]class="cell_element.*?".*?>(.*?)<\/(?:select|span)>)/is';
			preg_match_all($preg,$temp,$match_a);
		}
		elseif($info['form_type'] == 4)
		{
			$preg = '/<(?:select|span)[\s].*?class="cell_'.$info['mode_type'].'.*?".*?>(.*?)<\/(?:select|span)>/is';
			preg_match_all($preg,$temp,$match_a);
		}
		if($match_a)
		{
			preg_match_all('/{cell_(.*?)}/',$match_a[1][0],$match_b);
			if($match_b && $info['options'])
			{
				foreach ($info['options'] as $key=>$op)
				{
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
						else{
							$replace[] = $info[$v] ? $info[$v] : '';
						}
					}
					$forms_choose .= str_replace($find,$replace,$match_a[1][0]).PHP_EOL;
				}
			}
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
	
	public function replace_cell($temp,$info,$prefix = 'cell_')
	{
		preg_match_all('/{'.$prefix.'(.*?)}/',$temp,$match_c);
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
	
	public function create_file()
	{
        $addtemp = @file_get_contents(CORE_DIR.'address.php');
        if(!file_exists(DATA_DIR.'address.php') || $this->updateCache )
        {
            file_put_contents(DATA_DIR.'address.php', $addtemp);
        }
		$greettemp = @file_get_contents(CORE_DIR.'greet_result.php');
		if(!file_exists(DATA_DIR.'greet_result.php') || $this->updateCache )
		{
			file_put_contents(DATA_DIR.'greet_result.php', $greettemp);
		}
		$feedtem = @file_get_contents(CORE_DIR.'feedback.php');
		if(!file_exists(DATA_DIR.'feedback.php') || $this->updateCache )
		{
			file_put_contents(DATA_DIR.'feedback.php', $feedtem);
		}
    	return true;
	}
	
	public function generation_info($info,$template_id,$content,$sign)
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
	        	if(!file_copy($dir.'css', $assist_url.'/css', array()))
	            {
	            	$this->errorOutput(realpath($assist_url.'/css').'目录不可写1');
	            }
        	}
        }
    	if (is_dir($dir.'js'))
        {
        	if(!is_dir($assist_url.'/js') || $this->updateCache)
        	{
	        	hg_mkdir($assist_url.'/js');
	        	if(!file_copy($dir.'js', $assist_url.'/js', array()))
	            {
	                $this->errorOutput(realpath($assist_url.'/js').'目录不可写2');
	            }
        	}
        }
   		if (is_dir($dir.'images'))
        {
        	if(!is_dir($assist_url.'/images') || $this->updateCache)
        	{
	        	hg_mkdir($assist_url.'/images');
        	}
        	if(!file_copy($dir.'images', $assist_url.'/images', array()))
            {
                $this->errorOutput(realpath($assist_url.'/images').'目录不可写3');
            }
        }
    	return true;
    }
    
    public function process_standard($standard,$vid,$is_create = 0)
    {
    	if($standard)
		{
			foreach ($standard as $k=>$v)
			{
				if($v['form_type'] == 3 && $v['options'])
				{
					$op_num = $v['op_num'] ? $v['op_num'] : 1;
					$count_option = count(array_filter($v['options']));
					if($v['op_num'] > $count_option)
					{
						$op_num = $count_option;
					}
				}elseif($v['form_type'] == 4)
				{
					$op_num = 1;
				}
				else 
				{
					$op_num = 0;
				}
				$standard_data = array(
					'id'			=> $v['id'] && $is_create ? $v['id'] : '', 
					'fid'			=> $vid,  //反馈的id
					'name'			=> ($v['mode_type'] == 'split' ) ? '分隔符' : $v['name'] ? $v['name'] : $v['title'],
					'brief'			=> trim($v['brief']),
					'form_type'		=> $v['form_type'],
					'is_required' 	=> $v['is_required'] && $v['mode_type']!= 6 ? 1 : 0,
					'is_member'  	=> $v['is_member'] ? 1 : 0,
					'member_field'  => $v['member_field'] ? trim($v['member_field']) : '',
					'is_name'  		=> $v['is_name'] ? 1 : 0,
					'is_unique'		=> $v['is_unique'] ? 1 : 0,
					'order_id' 		=>intval($v['order_id']),
					'width'			=> $v['form_type'] < 3 ? intval($v['width']) : 0,
					'height'		=> $v['form_type'] < 3 ? intval($v['height']) : 0,
					'char_num'		=> $v['form_type'] < 3 ? intval($v['char_num']) : 0,
					'op_num'		=> $op_num,
					'cor'			=> $v['form_type'] == 3 ? intval($v['cor']) : 0,
					'min'			=> $v['min'] && $v['min'] > 0 ? $v['min'] : 0,
					'max'			=> $v['max'] && $v['max'] > 0 ? $v['max'] : $count_option,
					'options'		=> $v['options'] ? serialize(@array_filter($v['options'])) : '',
					'limit_type'	=>$v['form_type'] == 3 ? intval($v['limit_type']) :0,
					'spilter'	=>$v['form_type'] == 6 ? intval($v['spilter']) :0,
				);
				if($v['id'] || !$is_create)
				{
					$all_standard[] = $standard_data; 
				}
				else 
				{
					$new_standard[] = $standard_data;
				}
				if($v['is_common'])
				{
					$common_conf = array_slice($standard_data,3);
					$common_data[] = array(
						'name'     => trim($v['name']),
						'brief'    => trim($v['brief']),
						'type'     => 'standard',
						'configs'  => $common_conf ? serialize($common_conf) : '',
						'user_id'  => $this->user['user_id'],
					);
				}
			}
		}
		$data['standard'] = $all_standard ? $all_standard : array();
		$data['common']	= $common_data ? $common_data : array();
		$data['new_standard'] = $new_standard ? $new_standard : array();
		return $data;
    }
    
    public function process_fixed($fixed,$vid,$is_create = 0)
    {
    	if($fixed)
		{
			foreach ($fixed as $k=>$v)
			{
				$v['fixed_id'] = $v['fixed_id'] ? $v['fixed_id'] : $v['form_type'];
				switch ($v['fixed_id'])
				{
					case 4:case 6:
						$fixed_conf = $v['conf'];
						break;
					case 5:
						$fixed_conf = serialize(array( 
							'start_time' 	=> $v['start_time'] ? $v['start_time'] : '1900',
							'end_time' 		=> $v['end_time'] ? $v['end_time'] : '2100',
						));
						break;
					default:
						$fixed_conf = serialize(array(
							    'width' => $v['width'], 
							    'height' => $v['height'],
							    'char_num' => $v['char_num'],
							));
						break;
				}
				$fix_data = array(
					'id'			=> $v['id'] && $is_create ? $v['id'] : '', 
					'fid'			=> $vid,  //反馈的id
					'name'			=> $v['name'] ? $v['name'] : $v['title'],
					'brief'			=> trim($v['brief']),
					'fixed_id'		=> $v['fixed_id'],
					'is_required' 	=> intval($v['is_required']) ? 1 : 0,
					'is_member'  	=> intval($v['is_member']) ? 1 : 0,
					'member_field'  => $v['member_field'] ? trim($v['member_field']) : '',
					'is_name'  		=> intval($v['is_name']) ? 1 : 0,
					'is_unique'		=> intval($v['is_unique']) ? 1 : 0,
					'order_id' 		=>intval($v['order_id']),
					'conf' 			=> $fixed_conf,
				);
				if($v['id'] || !$is_create)
				{
					$all_fixed[] = $fix_data; 
				}
				else 
				{
					$new_fixed[] = $fix_data;
				}
				if($v['is_common'])
				{
					$common_conf = array_slice($fix_data,3);
					$common_data[] = array(
						'name'     => trim($v['name']),
						'brief'    => trim($v['brief']),
						'type'     => 'fixed',
						'configs'  => $common_conf ? serialize($common_conf) : '',
						'user_id'  => $this->user['user_id'],
					);
				}
			}
		}
		$data['fixed'] = $all_fixed ? $all_fixed : array();
		$data['common']	= $common_data ? $common_data : array();
		$data['new_fixed'] = $new_fixed ? $new_fixed : array();
		return $data;
    }
    
    public function get_complete_component($id,$form)
    {
    	$this->temp = new template_mode();
    	$component = $this->temp->get_component($id);
    	if(!$form)
    	{
    		return false;
    	}
    	$this->feed = new feedback_mode();
    	foreach ($form as $k=>$v)
    	{
    		$forms = $conf_array = array();
    		$forms = $v['configs'] ? unserialize($v['configs']) : array();
    		if($v['type'] == 'fixed')
    		{
    			if($forms['fixed_id'] !=4 && $forms['fixed_id'] !=6)
    			{
    				$forms['conf'] = $forms['conf'] ? unserialize($forms['conf']) : array();
    				$forms = array_merge($forms,$forms['conf']);
    				unset($forms['conf']);
    			}elseif($forms['fixed_id'] == 4)
    			{
  					$conf_array = explode(',',$forms['conf']);
    				$element = 
					array(
						array( 'id'	=> 8, 'mode_type' => 'select', 'form_type' => 4,'value' => $this->show_province(), 'other_sign'=> 'province','selected' => in_array(8,$conf_array) ? 1 :0 ),
						array( 'id'	=> 9, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->show_city(PROVINCE_ID), 'other_sign'=> 'city','selected' => in_array(9,$conf_array) ? 1 :0 ),
						array( 'id'	=> 10, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->show_area(CITY_ID), 'other_sign'=> 'area','selected' => in_array(10,$conf_array) ? 1 :0 ),
						array( 'id'	=> 11, 'mode_type' => 'input', 'form_type' => 1, 'other_sign' => 'detail','selected' => in_array(11,$conf_array) ? 1 :0 ),
					);
					$forms['element'] = $element;
    			}else
    			{
    				$conf_array = explode(',',$forms['conf']);
    				$element = 
					array(
						array( 'id'	=> 1, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->settings['hour'], 'other_sign'=> 'hour', 'selected' => in_array(1,$conf_array) ? 1 :0),
						array( 'id'	=> 2, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->settings['minit'], 'other_sign'=> 'minit','selected' => in_array(2,$conf_array) ? 1 :0),
						array( 'id'	=> 3, 'mode_type' => 'select', 'form_type' => 4, 'value' => $this->settings['second'], 'other_sign'=> 'second','selected' => in_array(3,$conf_array) ? 1 :0),
					);
					$forms['element'] = $element;
    			}
    			$forms['form_type'] = $forms['fixed_id'];
    		}
    		else
    		{
    			$forms['options'] = $forms['options'] ? unserialize($forms['options']) : array();
    		}
    		$forms['name'] = $v['name'];
    		$forms['brief'] = $v['brief'];
    		$forms['type'] = $v['type'];
    		$forms['mode_type'] = $this->settings[$v['type']][$forms['form_type']];
    		$forms['order_id'] = $v['order_id'];
    		$forms['html'] = $component[$forms['mode_type']];
    		$ret[] = $forms;
    	}
    	return $ret;
    }
}
?>