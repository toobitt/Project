<?php
require_once(ROOT_PATH.'lib/class/material.class.php');
class lbs_field extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *
	 * @Desc 通过分类id进行获取附加信息,sort_id为可选,如选则输出该分类下已开启的附加信息,如不选则输出所有已开启的附加信息
	 * @author Ayou
	 * @date 2013-11-20 下午05:15:34
	 */
	public function handle($sort_id='',$content_id='')
	{
		$sql='SELECT field.id,field.field,field.remark,field.field_default,field.selected,field.batch,field.zh_name,style.datatype AS type 
				FROM '.DB_PREFIX.'field AS field 
				LEFT JOIN '.DB_PREFIX.'style AS style 
					ON field.form_style=style.id 
				WHERE 1 AND field.switch = 1';
		if($sort_id)
		{
			$sql .=' AND field.id  IN (SELECT field_id from '.DB_PREFIX.'fieldbind where sort_id ='.$sort_id.')';
		}

		$q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
			$datas[$data['field']]=$data;
		}

		if($content_id&&$sort_id)
		{
			$sql = "SELECT content.id,field.field,content.value 
					FROM " . DB_PREFIX . "field AS field 
					LEFT JOIN " . DB_PREFIX . "fieldcontent AS content 
						ON field.field=content.field 
					WHERE 1 AND content_id = ".$content_id." AND sort_id = ".$sort_id;
			$q = $this->db->query($sql);
			$field_content_id=array();
			while($data = $this->db->fetch_array($q))
			{
				if($datas[$data['field']]['type']=='img')
				{
					$field_content_id[]=$data['id'];
					$field_content[$data['field']]=$data['id'];
				}
				else
				{
					$datas[$data['field']]['selected']=$data['value'];
				}
			}
			if($field_content_id&&is_array($field_content_id))
			{
				$query=$this->db->query('SELECT id,cid,host,dir,filepath,filename FROM '.DB_PREFIX.'materials where module=1 AND cid IN ('.implode(',', $field_content_id).')');

				while($row=$this->db->fetch_array($query))
				{
					$materials[$row['cid']][]=array(
					'id'=>$row['id'],
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'filepath'=>$row['filepath'],
					'filename'=>$row['filename'],
					);
				}
			}
			if($field_content&&is_array($field_content))
			{
				foreach ($field_content as $k => $v)
				{
					if($materials[$v])
					{
						$datas[$k]['selected']=$materials[$v];
					}
				}
			}
		}


		if (!empty($datas) && is_array($datas))
		{
			foreach ($datas as $key=>$value)
			{
				if($value['selected'])
				{
					$unserializes=@unserialize($value['selected']);
					if($unserializes)
					{
						$value['selected']=$unserializes;
					}
				}
				if($value['field_default']=$value['field_default']?$value['field_default']:array())
				{
					$_explode=explode(',', $value['field_default']);
					$value['field_default']=$_explode;
				}
				if(stripos($value['type'], 'checkbox')!== false)
				{
					$field='catalog['.$value['field'].'][]';
				}
				elseif(stripos($value['type'], 'img')!== false)
				{
					$field='catalog['.$value['field'].'][]';
				}
				else $field='catalog['.$value['field'].']';
				$data_key[]=array('id'=>$value['id'],
									'field' => $field,
									'remark' => $value['remark'],
									'type' => $value['type'],
									'batch' => $value['batch'],
								  'field_default' => $value['field_default'],
								 'selected' => $value['selected'],
       							  'zh_name' => $value['zh_name'],
				);
			}
		}
		return $data_key;
	}
	/**
	 *   附加信息创建方法
	 * @param 如果间隔使用参数,不使用位置''代替,以防止传参出错.
	 * @param int $id 内容id
	 * @param int $sort_id 分类id
	 * @param array $input 表单数组
	 * @param array $file  文件数组
	 * @param 返回值说明:0,内容id未传入,-1,图片服务器未安装,-2,分类id未传入,-3内容创建重复,-4,无内容传入
	 */
	public function field_contentcreate($id,$sort_id,$input,$file='',$return=false)
	{
		$input_create=$input;
		if($_FILES['catalog']&&is_array($_FILES['catalog']))
		{
			$files=	$_FILES['catalog'];
			foreach ($files as $key => $value)
			{
				foreach ($value as $keys=>$values)
				{
					if($values&&is_array($values))
					{
						foreach ($values as $values_k => $values_v)
						{
							$tmp[$keys][$values_k][$key]=$values_v;
						}
					}
					else {
						$tmp[$keys][$key]=$values;
					}
				}
			}
			$files=$tmp;
			unset($tmp);
		}
		else $files = $_FILES;
		$files=	$file?$file:$files;
		unset($input);
		$all_field=$this->all_field($sort_id);//获取已开启附加信息标识
		$insert_data=array(
		'sort_id'=>$sort_id,
		'content_id'=>$id,
		);
		$where=$this->get_condition($id,$sort_id);
		$sql = "SELECT field FROM " . DB_PREFIX . "fieldcontent WHERE 1 " . $where;
		$q = $this->db->query($sql);//当前内容id是否已存在与内容表,并取出编目标识
		while($data = $this->db->fetch_array($q))
		{
			$contentfield[$data['field']] =  $data['field']; //标识
		}
		foreach ($all_field AS $key=>$val)//遍历所需的编目内容.重新组装数组,同时为了过滤传入非编目应用的表单.
		{
			if($contentfield[$key])
			{
				$input_null=TRUE;//如果有重复.
				continue;
			}
			$this->check_format($val['type'], $input_create[$key]);//格式检测
			if($val['type']=='img')
			{
				if(isset($files[$key])&&!empty($files[$key]))
				{
					if($val['batch'])//是否允许多图上传
					{
						foreach ($files[$key] as $_file)
						{
							$img=$this->upload_img($_file);
							if($img == -1)
							{
								$this->errorOutput('图片服务器未安装');
							}
							$images[$key][]=$img;
						}
					}
					else {
						foreach ($files[$key] as $_file)
						{
						}
						$img=$this->upload_img($_file);
						if($img == -1)
						{
							$this->errorOutput('图片服务器未安装');
						}
						$images[$key][]=$img;
					}
					foreach ($images[$key] AS  $val)
					{
						$images_id['value'] .= $val['id'].',';
					}
					$_insert_data=$insert_data;
					$_insert_data['field']=$key;
					$_insert_data['value']=trim($images_id['value'],',');
					$re_content=$this->create('fieldcontent', $_insert_data);
					$re_mater=array('cid'=>$re_content['id'],'flag'=>0);
					$idsArr=array('id'=>explode(',', $_insert_data['value']));
					$this->update('materials', $re_mater, $idsArr);
					$create_out[$key]=array(
					'zh_name'=>$all_field[$key]['zh_name'],
					'type'=>$all_field[$key]['type'],
					'value'=>$images[$key]);
					unset($images_id);
				}
			}
			else
			{
				if(is_array($input_create[$key]))
				{
					$tmp=implode(',', $input_create[$key]);
					$input_create_value[$key]=$tmp;
					unset($tmp);
				}
				else  $input_create_value[$key]=$input_create[$key];
			}
		}
		if($input_null&&!$input_create_value&&empty($return))
		{
			return -3;
		}
		elseif((!$input_create_value || !is_array($input_create_value))&&empty($return))
		{
			return -4;
		}
		$tableName = DB_PREFIX."fieldcontent";
		$sql = 'INSERT INTO ' . $tableName . ' (sort_id,content_id,field,value) VALUES ';
		foreach($input_create_value as $k=>$v)
		{
			$sql .='('.intval($sort_id).','.intval($id).','."'$k'".','."'$v'".'),';

			if($all_field[$k]['type']=='img')
			{
				$v=$images[$k];
			}
			if($all_field[$k]['type']=='checkbox')
			{
				$v=explode(',', $v);
			}
			$create_out[$k]=array('zh_name'=>$all_field[$k]['zh_name'],'type'=>$all_field[$k]['type'],'value'=>$v);
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		return $create_out;
	}

	/**
	 *   附加信息更新方法
	 * @param 如果间隔使用参数,不使用位置''代替,以防止传参出错.
	 * @param int $id 内容id
	 * @param int $sort_id 分类id
	 * @param array $input_update 更新内容
	 * @param 返回值说明:0,内容id未传入,-1,图片服务器未安装,-2,分类id未传入,-3内容创建重复,-4,无内容传入
	 */
	public function field_contentupdate($id,$sort_id,$input_update)
	{

		$input=$input_update;
		if($_FILES['catalog']&&is_array($_FILES['catalog']))
		{
			$files=	$_FILES['catalog'];
			foreach ($files as $key => $value)
			{
				foreach ($value as $keys=>$values)
				{
					if($values&&is_array($values))
					{
						foreach ($values as $values_k => $values_v)
						{
							$tmp[$keys][$values_k][$key]=$values_v;
						}
					}
					else {
						$tmp[$keys][$key]=$values;
					}
				}
			}
			$files=$tmp;
			unset($tmp);
		}
		else $files = $_FILES;
		unset($input_update);
		$all_field=$this->all_field($sort_id);//获取已开启附加信息标识
		$ids_str=null;
		$is_batch_del=array();
		$no_batch_del=array();
		if($input['materialdel'])
		{
			if(is_string($input['materialdel']))
			{
				$ids_arr = explode(',', trim($input['materialdel'],','));
			}
			elseif (is_array($input['materialdel']))
			{
				$ids_arr = $input['materialdel'];
			}
			$ids_arr = array_filter($ids_arr);
			if ($ids_arr&&is_array($ids_arr))
			{
				$ids_str=implode(',', $ids_arr);
				$query=$this->db->query('SELECT field,value FROM '.DB_PREFIX.'fieldcontent WHERE id IN (SELECT cid FROM '.DB_PREFIX.'materials where id IN ('.$ids_str.'))');
				$id_arr=array();
				while($row=$this->db->fetch_array($query))
				{
					if($row['value'])
					{
						$id_arr[$row['field']] = explode(',', $row['value']);
					}
				}				
				foreach ($id_arr as $field_v=>$id_v)
				{
					foreach ($id_v as $id_vv)
					{
						if(in_array($id_vv,$ids_arr))
						{
							if($all_field[$field_v]['batch'])
							{
								$is_batch_del[]=$id_vv;
							}
							else {
								$no_batch_del[]=$id_vv;
							}
						}
					}
				}				
			}
			if($ids_str)
			{
				$this->delete('materials', array('id'=>$ids_str));//删除无用素材.前端页面会记录用户操作无用的素材
			}
		}
		$where=$this->get_condition($id,$sort_id);
		$sql = "SELECT id,field,value FROM " . DB_PREFIX . "fieldcontent WHERE 1 " . $where;
		$q = $this->db->query($sql);//当前内容用到的编目标识,用于提取curl数组
		$contentfield=array();
		while($data = $this->db->fetch_array($q))
		{
			$contentfield[$data['field']] = array('id'=>$data['id'],'value'=>$data['value']); //当前内容id已经使用的编目标识
		}
		if(!empty($contentfield) && is_array($contentfield))
		{
			foreach ($contentfield as $key=>$value)//获取input中,content表中存在的标识,存入新数组
			{
				$this->check_format($all_field[$key]['type'], $input[$key]);//格式检测
				if($all_field[$key]['type']=='img')
				{
					$f_cid=$value['id'];//编目内容id
					$images_id=array();
					if(isset($files[$key])&&!empty($files[$key]))
					{
						if($all_field[$key]['batch'])//是否允许多图上传
						{
							foreach ($files[$key] as $_file)
							{
								$img=$this->upload_img($_file,$f_cid);
								if($img == -1)
								{
									$this->errorOutput('图片服务器未安装');
								}
								$images[$key][]=$img;
							}
						}
						else {
							foreach ($files[$key] as $_file)
							{
							}
							$img=$this->upload_img($_file,$f_cid);
							if($img == -1)
							{
								$this->errorOutput('图片服务器未安装');
							}
							$images[$key][]=$img;
						}

						foreach ($images[$key] AS $val)
						{
							$images_id[$key][] = $val['id'];
						}

						if($contentfield[$key]['value']&&!$all_field[$key]['batch']) {

							$this->delete('materials', array('id'=>explode(',', $contentfield[$key]['value'])));//删除旧数据,单张图片上传模式
						}
						unset($files[$key]);
					}
					if($all_field[$key]['batch'])//多图img型,对内容表中删除掉的素材id进行处理
					{
						if(stripos($contentfield[$key]['value'], ',')!==false)
						{	
							$contentfield[$key]['value']=explode(',', $contentfield[$key]['value']);
						}
						elseif($contentfield[$key]['value'])
						{
							$contentfield[$key]['value']=array(intval($contentfield[$key]['value']));
						}					
							
						if($is_batch_del)//如果有删除
						{	
							if(is_string($is_batch_del)&&stripos($is_batch_del,',')!==false)
							{
								$is_batch_del=explode(',',$is_batch_del);
							}
							elseif(is_string($is_batch_del)||is_numeric($is_batch_del)||is_integer($is_batch_del))
							{
								$is_batch_del=array(intval($is_batch_del));	
							}
							if($contentfield[$key]['value'])
							{
								$contentfield[$key]['value']=array_diff($contentfield[$key]['value'],$is_batch_del);
							}
						}
						if($images_id[$key])//如果上传了图片
						{

							if($contentfield[$key]['value'])
							{
								$content_array['value'][$key]=implode(',',array_merge($contentfield[$key]['value'],$images_id[$key]));
							}
							else {
								$content_array['value'][$key]=implode(',',$images_id[$key]);
							}
						}
						elseif($is_batch_del)//如果有删除,未上传图片
						{
							if($contentfield[$key]['value'])
							{
								$content_array['value'][$key]=implode(',',$contentfield[$key]['value']);
							}
							else {
								$content_array['value'][$key]='';
							}
						}
					}
					elseif (!$app_field[$key]['batch'])//单图img型,处理!
					{
						if($images_id[$key])
						{
							$content_array['value'][$key]=implode(',',$images_id[$key]);
						}
						elseif(!empty($no_batch_del))
						{
							$content_array['value'][$key]='';
						}
					}
				}
				else
				{
					if(is_array($input[$key]))
					{
						$tmp=implode(',', $input[$key]);
						$content_array['value'][$key]=$tmp;
						unset($input[$key],$tmp);
					}
					else
					{
						$content_array['value'][$key]=$input[$key];
						unset($input[$key]);
					}

				}
			}
			$is_create_flag=FALSE;
			foreach ($all_field as $key=>$value)//重新遍历input数组查询是否有新添加的编目内容,如果有则调用创建编目函数.
			{
				if(isset($files[$key])&&!empty($files[$key]))
				{
					$is_create_flag=TRUE;
				}
				elseif(isset($input[$key])&&!empty($input[$key]))
				{
					$is_create_flag=TRUE;
				}
			}
		}
		else $is_create_flag=TRUE;
		$create_field=array();
		if($is_create_flag)
		{
			$create_field=$this->field_contentcreate($id,$sort_id,$input,$files,true);
		}
		if(!empty($content_array))
		{
			$field_string = "'".trim(implode("','", array_keys($content_array['value'])))."'";//去前缀
			$sql = "UPDATE ".DB_PREFIX."fieldcontent SET ";
			$count_content=count($content_array);
			$i=0;//用于判断是否为最后一个字段
			foreach($content_array as $key=>$value)
			{
				$i++;
				$sql .="$key = CASE field ";
				if (!empty($value) && is_array($value))
				{
					foreach ($value as $key => $content_value)
					{
						$sql .= sprintf("WHEN %s THEN %s ", "'".$key."'", "'".$content_value."'");  // 拼接SQL语句
						if($all_field[$key]['type']=='img')
						{
							$content_value=$images[$key];
						}
						elseif($all_field[$key]['type']=='checkbox')
						{
							$content_value=explode(',', $content_value);
						}
						$update_out[$key]=array('zh_name'=>$all_field[$key]['zh_name'],'type'=>$all_field[$key]['type'],'value'=>$content_value);
					}
					if(!($i==$count_content)) $sql.="END,";
				}
			}
			$sql .= "END WHERE 1 ".$where." AND field IN ($field_string)";
			$this->db->query($sql);
			//以下sql为更新相应的更新时间,更新用户.
		}
		if(is_array($create_field)&&$create_field)
		{
			foreach ($create_field as $key=>$val)
			{
				foreach ($val as $keys=>$vals)
				{
					$update_out[$key][$keys]=$vals;
				}
			}
		}

		return $update_out;
	}
	/**
	 *   附加信息删除方法
	 * @param int $id 内容id
	 * @param 返回值说明:0,删除失败.1,删除成功
	 */
	public function field_contentdelete($ids)
	{
		if(!$ids)
		{
			return 0;
		}
		$where="";
		if(!empty($ids))
		{
			if(is_string($ids))$ids_arr = explode(',', $ids);
			$ids_arr = array_filter($ids_arr);
			if (!$ids_arr)
			{
				return 0;
			}
			$ids = implode(',', $ids_arr);

			$where.=" AND content_id IN(".$ids.")";
		}

		$sql = 'DELETE FROM ' . DB_PREFIX . 'fieldcontent WHERE 1'.$where;
		$this->db->query($sql);
		$this->db->query('DELETE FROM ' . DB_PREFIX . 'materials WHERE module = 1 AND cid IN ('.$ids.')');
		return 1;

	}
	public function get_condition($id,$sort_id)
	{
		if(!$id)
		{
			return 0;
		}
		if(!$sort_id)
		{
			return -2;
		}
		$where="";
		if(!empty($id))//如果传内容id则查询该内容id.
		{
			$where.=" AND content_id = ".$id;
		}

		if(!empty($sort_id))//如果传分类id则查询该内容id.
		{
			$where.=" AND sort_id = ".$sort_id;
		}
		return $where;
	}

	//获取已开启附加信息标识
	public function all_field($sort_id=0)
	{
		$leftjoin='';
		$where='';
		if($sort_id)
		{
			$where=' AND fb.sort_id = '.$sort_id;
			$leftjoin=' LEFT JOIN '.DB_PREFIX.'fieldbind fb ON fb.field_id=f.id';
		}
		$sql='SELECT f.zh_name,f.field,s.datatype as type,f.batch FROM '.DB_PREFIX.'field as f '.$leftjoin.' left join '.DB_PREFIX.'style as s ON f.form_style=s.id WHERE 1 AND switch = 1'.$where;

		$q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
			$datas[$data['field']]=array('zh_name'=>$data['zh_name'],'field'=>$data['field'],'type'=>$data['type'],'batch'=>$data['batch']);
		}
		return $datas;
	}

	public function province()
	{
		$sql='SELECT id,name FROM '.DB_PREFIX.'province WHERE 1';
		$q=$this->db->query($sql);
		while ($re = $this->db->fetch_array($q))
		{
			$data[]=$re;
		}
		return $data;
	}

	public function city($province_id)
	{
		$sql='SELECT id,city FROM '.DB_PREFIX.'city WHERE 1';
		if($province_id)
		{
			$sql .=' AND province_id = '.intval($province_id);
		}
		$q=$this->db->query($sql);
		while ($re = $this->db->fetch_array($q))
		{
			$data[]=$re;
		}
		return $data;
	}

	public function area($city_id)
	{
		$sql='SELECT id,area FROM '.DB_PREFIX.'area WHERE 1';
		if($city_id)
		{
			$sql .=' AND city_id = '.intval($city_id);
		}
		$q=$this->db->query($sql);
		while ($re = $this->db->fetch_array($q))
		{
			$data[]=$re;
		}
		return $data;
	}

	/**
	 *
	 * @Description 视频上传
	 */
	public function uploadToVideoServer($file,$title='',$brief = '',$vod_lexing = 1)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($file);
		$curl->addRequestData('title', $title);
		$curl->addRequestData('comment',$brief);
		$curl->addRequestData('vod_leixing',$vod_lexing);//网页传的视频类型是1，手机传的视频是2
		$curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		$ret = $curl->request('create.php');
		return $ret[0];
	}

	/**
	 *
	 * 图片上传,支持传参 ...
	 * @param array $files $_FILES数组
	 * @param int $cid 编目内容id
	 */
	private function upload_img($files=array(),$cid=0)
	{
		$id = $cid?$cid:($this->input['id']?intval($this->input['id']):0);
		$flag = $id?0:1;//判断是否临时数据
		$file['Filedata']=$files?$files:$_FILES['Filedata'];
		if($file['Filedata'])
		{
			if (!$this->settings['App_material']&&empty($files))
			{
				$this->errorOutput('图片服务器未安装！');
			}
			elseif(!$this->settings['App_material']) {

				return -1;//图片服务器未安装
			}
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($file);
			$img_data = array(
				'host' 			=> $img_info['host'],
				'dir' 			=> $img_info['dir'],
				'filepath' 		=> $img_info['filepath'],
				'filename' 		=> $img_info['filename'],
			);

			$data = $img_data;
			$data['cid'] 			= $id;
			$data['module'] 		= 1;
			$data['original_id'] 	= $img_info['id'];
			$data['type'] 			= $img_info['type'];
			$data['mark'] 			= 'img';
			$data['imgwidth'] 		= $img_info['imgwidth'];
			$data['imgheight'] 		= $img_info['imgheight'];
			$data['flag']			= $flag;
			$vid = $this->db->insert_data($data, 'materials');
			if($vid&&empty($files))
			{
				$data['id'] = $vid;
				$this->addItem($data);
				$this->output();
			}
			elseif($vid)
			{
				$data['id'] = $vid;
				return $data;
			}
		}
	}

	/**
	 * 删除
	 * @paramString $table
	 * @param Array $data
	 */
	private function delete($table, $data)
	{
		if (empty($table) || !is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		if($data)
		{
			foreach ($data as $key => $val)
			{
				if (is_int($val) || is_float($val)||is_numeric($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val)&&(stripos($val, ',')===false))
				{
					$sql .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
				elseif(is_string($val))
				{
					$sql .= ' AND ' . $key . ' in (' . $val . ')';
				}
				elseif (is_array($val))
				{
					$sql .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
				}
			}
		}
		return $this->db->query($sql);
	}

	/**
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	private function create($table, $data, $order=false,$pk = 'id')
	{
		if (!$table || !is_array($data)) return false;
		$fields = '';
		foreach ($data as $k => $v)
		{
			if (is_string($v))
			{
				$fields .= $k . "='" . $v . "',";
			}
			elseif (is_int($v) || is_float($v))
			{
				$fields .= $k . '=' . $v . ',';
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' SET ' . $fields;
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if($table&&$order)//更新附加信息表排序
		{
			$sql = 'UPDATE '.DB_PREFIX. $table . ' set order_id = '.$id.' WHERE id = '.$id;
			$this->db->query($sql);
		}
		$data[$pk] = $id;
		return $data;
	}

	/**
	 * 更新数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param Array $idsArr 条件
	 * @param Boolean $flag
	 */
	private function update($table, $data, $idsArr, $flag = false)
	{
			
		if (!$table || !is_array($data) || !is_array($idsArr)) return false;
		$fields = '';

		foreach ($data as $k => $v)
		{
			if ($flag)
			{
				$v = $v > 0 ? '+' . $v : $v;
				$fields .= $k . '=' . $k . $v . ',';
			}
			else
			{
				if (is_string($v))
				{
					$fields .= $k . "='" . $v . "',";
				}
				elseif (is_int($v) || is_float($v))
				{
					$fields .= $k . '=' . $v . ',';
				}
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $fields . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val) || is_float($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val))
				{
					$sql .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
				elseif (is_array($val))
				{
					$sql .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
				}
			}
		}
		$res=$this->db->query($sql);
		if ($idsArr&&$res)
		{
			return $idsArr;
		}
		return false;

	}
	/**
	 *
	 * 检测数据输入是否正确
	 */
	private function check_format($type,$val)
	{
		if($type=='phone')
		{
			$is_error=true;
			if(is_string($val)||is_numeric($val))
			{
				if(IsTel($val)||IsMobile($val))
				{
					$is_error=false;
				}
				elseif(empty($val))
				{
					$is_error=false;
				}
			}

			if($is_error)
			{
				$this->errorOutput('您输入的电话号码或者手机号码格式错误');
			}
		}
	}

}