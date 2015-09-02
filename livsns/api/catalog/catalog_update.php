<?php
require('./global.php');
define('MOD_UNIQUEID', 'catalog'); //模块标识
require_once (CUR_CONF_PATH . 'core/catalog.core.php');
include_once ROOT_PATH  . 'lib/class/material.class.php';
include_once (CUR_CONF_PATH . 'lib/manage.class.php');
require_once(CUR_CONF_PATH . 'lib/catalog.class.php');
require_once(CUR_CONF_PATH . 'lib/catalog_sort.class.php');

class catalogUpdateApi extends outerUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$cache_file = CUR_CONF_PATH . CACHE_SORT;
		$this->catalog = new catalog();
		$this->manage = new manage();
		$this->catalogsort = new catalogsort();
		$this->catalogcore = new catalogcore();
		if (!file_exists($cache_file)) //检测缓存文件是否存在,防止require错误
		{

			$this->catalogcore->cache();//更新缓存文件
			if (!file_exists($cache_file)) {//检测缓存文件更新是否成功

				$this->errorOutPut(CACHE_ERROR);
					

			}

		}
		$cache = array();
		if(file_exists($cache_file))
		{
			require_once $cache_file;//引入缓存文件
		}
		$this->rows = $cache;
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 添加编目内容
	 * 本函数参数作为接口时无需使用,只在本类进行函数调用时使用.
	 */
	public function create($content_insert_array = array() ,$files= array(),$return= false)
	{
	    $app_uniqueid=$this->input['app_uniqueid']?trim($this->input['app_uniqueid']):'';
		$mod_uniqueid=$this->input['mod_uniqueid']?trim($this->input['mod_uniqueid']):'';
		$content_id=$this->input['content_id']?intval($this->input['content_id']):'';
		$identifier=$this->input['identifier']?intval($this->input['identifier']):'';
		$catalog_sort=$this->input['catalog_sort']?trim($this->input['catalog_sort']):'';  //分类标识
		$user_id=$this->user['user_id'];
		$error=$this->catalog->error($app_uniqueid,$mod_uniqueid,$content_id);
		if($error) $this->errorOutput($error);
		$_FILES	= empty($files)?$_FILES:$files;		
		$input_create = empty($content_insert_array) ? $this->input :$content_insert_array;
		
		if($catalog_sort =='dingdone')
		{
		    $app_field=$this->catalog->getUser_field($identifier, $user_id);
		}
		else
		{
    		$app_field=$this->catalog->app_field($app_uniqueid);//获取当前应用已启用的编目标识
		}
		if(empty($app_field))//此应用未绑定编目
		{
			$this->errorOutput(NO_BIND_APP);
		}
		$insert_data = array(
			'app_uniqueid'		=> $app_uniqueid,
			'mod_uniqueid'		=> $mod_uniqueid,
			'user_name'			=> $this->user['user_name'],
			'user_id' 			=> $this->user['user_id'],
			'content_id'			=> $content_id,
			'catalog_field' 		=> $this->input['catalog_field'],
			'update_user_id'		=> $this->user['user_id'],
			'update_user_name'	=> $this->user['user_name'],
		    'identifier'       => $identifier,
			'create_time'		=> TIMENOW,
			'update_time' 		=> TIMENOW		
		);
		$where=$this->catalog->get_condition($app_uniqueid,$mod_uniqueid,$content_id);
		$sql = "SELECT catalog_field,value FROM " . DB_PREFIX . "content WHERE 1 " . $where;
		$q = $this->db->query($sql);//当前内容id是否已存在与内容表,并取出编目标识
		while($data = $this->db->fetch_array($q))
		{
			$data['catalog_field']=catalog_prefix($data['catalog_field']);
			$all_field[$data['catalog_field']] =  $data['catalog_field']; //编目标识
			$field_value[$data['catalog_field']]= $data['value']?$data['value']:NULL;
		}
		//必填项判断开始
		$required=array();
		if(!$return)
		{
			$required=$this->required($input_create, $_FILES, $app_field, $field_value);
		}
		if($required)
		{
			$required_ret=array();
			$required_ret['required']=$required;
			$this->addItem($required_ret);
			$this->output();
		}
		$create_out = array();	
		//必填项判断结束
		foreach ($app_field AS $key=>$val)//遍历所需的编目内容.重新组装数组,同时为了过滤传入非编目应用的表单.
		{
		    if($all_field[$key])    
			{
			    $input_null=TRUE;//如果有重复.
				continue;
			}
			if($val['type']=='img')
			{
			    if(isset($_FILES[$key])&&!empty($_FILES[$key]))
				{
					if($val['batch'])//是否允许多图上传
					{
						$_files = array();
						foreach ($_FILES[$key] AS $name=>$value)
						{
							foreach ($value as $keys=>$values)
							{
								$_files[$keys][$name]=$values;
							}
						}
						foreach ($_files as $_file)
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
						$img=$this->upload_img($_FILES[$key]);
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
					$_insert_data['catalog_field']=catalog_prefix($key,'del');
					$_insert_data['value']=trim($images_id['value'],',');
					$re_content=$this->catalogcore->create('content', $_insert_data);
					$re_mater=array('cid'=>$re_content['id'],'flag'=>0);
					$idsArr=array('id'=>explode(',', $_insert_data['value']));
					$this->catalogcore->update('materials', $re_mater, $idsArr);
					$create_out[$key]=array(
					'zh_name'=>$app_field[$key]['zh_name'],
					'bak'=>$app_field[$key]['bak'],
					'type'=>$app_field[$key]['type'],
					'value'=>$images[$key]);
					unset($images_id);
				}
			}
			elseif($val['type']=='video')
			{

			}
			else
			{
			    if(is_array($input_create[$key]))
				{
				    $tmp=implode(',', $input_create[$key]);
					$input_create_value[$key]=$tmp;
					unset($tmp);
				}
				else  {
					$input_create_value[$key]=$input_create[$key];
				}
			}
		}
		if($input_null&&empty($input_create_value)&&empty($return))//内容已存在,无需重复创建
		{
			$this->errorOutput(CONTENT_EXIST);
		}
		elseif((empty($input_create_value) || !is_array($input_create_value))&&empty($return))//未传任何内容
		{
			$this->errorOutput(NO_CONTENT);
		}
		if($input_create_value&&is_array($input_create_value))
		{
		    $tableName = DB_PREFIX."content";
			$sql = 'INSERT INTO ' . $tableName . ' (app_uniqueid,mod_uniqueid,content_id,catalog_field,value,identifier,user_id,user_name,update_user_id,update_user_name,create_time,update_time) VALUES ';
			foreach($input_create_value as $k=>$v)
			{
				$key=trim(str_ireplace(CATALOG_PREFIX,'',$k));//去掉前缀

				$sql .='(\''.$insert_data['app_uniqueid'].'\',\''.$insert_data['mod_uniqueid'].'\','
				.$insert_data['content_id'].','."'$key'".','."'$v'".','.$insert_data['identifier'].','
				.$insert_data['user_id'].",'".$insert_data['user_name']."',".$insert_data['update_user_id'].',\''
				.$insert_data['update_user_name'].'\','.$insert_data['create_time'].','.$insert_data['update_time'].'),';

				$create_out[$k]=array('zh_name'=>$app_field[$k]['zh_name'],'bak'=>$app_field[$k]['bak'],'type'=>$app_field[$k]['type'],'value'=>$v);
			}
			$sql = rtrim($sql,',');		
			$res=$this->db->query($sql);
		}
		if($return)
		{			
			return $create_out;
		}
		else
		{
			$this->addItem_withkey('result', $create_out);
			$this->addItem_withkey('catalog_prefix', CATALOG_PREFIX);
			$this->output();
		}
	}

	//修改编目内容
	public function update()
	{
		$app_uniqueid=$this->input['app_uniqueid']?trim($this->input['app_uniqueid']):'';
		$mod_uniqueid=$this->input['mod_uniqueid']?trim($this->input['mod_uniqueid']):'';
		$content_id=$this->input['content_id']?intval($this->input['content_id']):'';
		$error=$this->catalog->error($app_uniqueid,$mod_uniqueid,$content_id);
		if($error) $this->errorOutput($error);
		$input=$this->input;//获取curl的post数组
		$update_user = array(
			'update_user_id'	=> intval($this->user['user_id']),
			'update_user_name'	=> $this->user['user_name'],
			'update_time' 		=> TIMENOW		
		);
		$app_field=$this->catalog->app_field($app_uniqueid);//获取当前应用已启用的编目标识
		$where=$this->catalog->get_condition($app_uniqueid,$mod_uniqueid,$content_id);
		$sql = "SELECT id,catalog_field,value FROM " . DB_PREFIX . "content WHERE 1 " . $where;
		$q = $this->db->query($sql);//当前内容用到的编目标识,用于提取curl数组
		while($data = $this->db->fetch_array($q))
		{
			$data['catalog_field']	=	catalog_prefix($data['catalog_field']);
			$arr_catalog_field[] = $data['catalog_field']; //当前内容id已经使用的编目标识
			$catalog_content[$data['catalog_field']]=array('id'=>$data['id'],'value'=>$data['value']);
			$field_value[$data['catalog_field']]= $data['value']?$data['value']:NULL;

		}
		$required=array();
		$required=$this->required($input, $_FILES, $app_field, $field_value);
		if(!empty($required))
		{
			$required_ret=array();
			$required_ret['required']=$required;
			$this->addItem($required_ret);
			$this->output();
		}
		if($input['catalogdel'])
		{
			$this->delete(TRUE);
			if(stripos($input['catalogdel'], ',')!==false)
			{
				$catalogdel=explode(',', $input['catalogdel']);
			}
			else
			{
				$catalogdel=array(trim($input['catalogdel']));
			}
			$arr_catalog_field=array_diff($arr_catalog_field, $catalogdel);//清除掉已删除的编目标识
		}
		if($input['materialdel'])
		{		
			if(!is_array($input['materialdel']))
			{
			$materialdel = array();
			$materialdel=explode(',', $input['materialdel']);
			$materialdel=array_filter($materialdel,"clean_array_null");
			$materialdel=array_filter($materialdel,"clean_array_num");
			if($materialdel&&is_array($materialdel))
			{
				$input['materialdel']=trim(implode(',', $materialdel));
				$this->catalog->deleteMaterial_id($input['materialdel']);	//删除无用素材.前端页面会记录用户操作无用的素材	
			}
			}
		}

		if(!empty($arr_catalog_field) && is_array($arr_catalog_field))
		{
			$images = array();
			foreach ($arr_catalog_field as $value)//获取input中,content表中存在的标识,存入新数组
			{
				$images[$value] = array();
				if($app_field[$value]['type']=='img')
				{
					$f_cid=$catalog_content[$value]['id'];//编目内容id
					$images_id=array();
					if(isset($_FILES[$value])&&!empty($_FILES[$value]))
					{
						if($app_field[$value]['batch'])//是否允许多图上传
						{
							$_files = array();
							foreach ($_FILES[$value] AS $name=>$f_value)
							{
								foreach ($f_value as $keys=>$values)
								{
									$_files[$keys][$name]=$values;
								}
							}
							foreach ($_files as $_file)
							{
								$img=$this->upload_img($_file,$f_cid);
								if($img == -1)
								{
									$this->errorOutput('图片服务器未安装');
								}
								$images[$value][]=$img;
							}
						}
						else {
							$img=$this->upload_img($_FILES[$value],$f_cid);
							if($img == -1)
							{
								$this->errorOutput('图片服务器未安装');
							}
							$images[$value][]=$img;
						}

						foreach ($images[$value] AS $val)
						{
							$images_id[$value][] = $val['id'];
						}

						if($catalog_content[$value]['value']&&!$app_field[$value]['batch']) {

							$this->catalogcore->delete('materials', array('id'=>explode(',', $catalog_content[$value]['value'])));//删除旧数据,单张图片上传模式
						}
						/*
						 $update_out[$value]=array(
						 'zh_name'=>$app_field[$value]['zh_name'],
						 'type'=>$app_field[$value]['type'],
						 'value'=>$images[$value]);
						 //unset($images_id);
						 *
						 */
						unset($_FILES[$value]);
					}

					if($app_field[$value]['batch'])//多图img型,对内容表中删除掉的素材id进行处理
					{

						if(stripos($catalog_content[$value]['value'], ',')!==false)
						{
							$catalog_content[$value]['value']=explode(',', $catalog_content[$value]['value']);
						}
						elseif($catalog_content[$value]['value'])
						{
							$catalog_content[$value]['value']=array(intval($catalog_content[$value]['value']));
						}

						if($input['materialdel'])//如果有删除
						{
							if(stripos($input['materialdel'], ',')!==false)
							{
								$input['materialdel']=explode(',', $input['materialdel']);
							}
							else
							{
								$input['materialdel']=array(intval($input['materialdel']));
							}

							if($catalog_content[$value]['value'])
							{
								$catalog_content[$value]['value']=array_diff($catalog_content[$value]['value'], $input['materialdel']);
							}
								
						}
						if($images_id[$value])//如果上传了图片
						{

							if($catalog_content[$value]['value'])
							{
								$content_array['value'][$value]=implode(',',array_merge($catalog_content[$value]['value'],$images_id[$value]));
							}
							else {
								$content_array['value'][$value]=implode(',',$images_id[$value]);
							}
						}
						else//if($input['materialdel'])//如果有删除,未上传图片
						{
							if($catalog_content[$value]['value'])
							{
								$content_array['value'][$value]=implode(',',$catalog_content[$value]['value']);
							}
							else {
								//$content_array['value'][$value]='';
								$this->input['catalogdel'] = $value;
								$this->delete(true,false);
							}
						}
						$get_material_id = $catalog_content[$value]['value']?implode(',',$catalog_content[$value]['value']):'';
					}
					elseif (!$app_field[$value]['batch'])//单图img型,处理!
					{
						$is_update_Material = 1;
						if($images_id[$value])
						{
							$content_array['value'][$value]=implode(',',$images_id[$value]);
							$is_update_Material = 0;
						}
						elseif ($catalog_content[$value]['value'])
						{
							$content_array['value'][$value] = $catalog_content[$value]['value'];
						}
						$get_material_id = $is_update_Material?($catalog_content[$value]['value']):'';//如果为新上传的,则不用查找素材.如果为旧数据,则需要重新查出
					}
					$images[$value] = array_merge($images[$value], $this->catalog->get_Material($get_material_id));//取旧图片数据.
					
				}
				elseif($val['type']=='video')
				{

				}
				else
				{
					if(is_array($input[$value]))
					{
						$tmp=implode(',', $input[$value]);
						$content_array['value'][$value]=$tmp;
						unset($tmp);
					}
					else  $content_array['value'][$value]=$input[$value];
					unset($input[$value]);
				}
			}
			$is_create_flag=FALSE;

			if(is_array($app_field))
			{
				foreach ($app_field as $key=>$value)//重新遍历input数组查询是否有新添加的编目内容,如果有则调用创建编目函数.
				{
					if(isset($_FILES[$key])&&!empty($_FILES[$key]))
					{
						$is_create_flag=TRUE;
					}
					elseif(isset($input[$key])&&!empty($input[$key]))
					{
						$is_create_flag=TRUE;
					}
				}	
			}
		}
		else
		{ 
			if(is_array($app_field))
			{
				foreach ($app_field as $key=>$value)//重新遍历input数组查询是否有新添加的编目内容,如果有则调用创建编目函数.
				{
					if(!empty($_FILES[$key]))
					{
						$is_create_flag=TRUE;
					}
					elseif(isset($input[$key]))
					{
						$is_create_flag=TRUE;
					}
				}	
			}
		}
		if($is_create_flag)
		{			
			$return_create=$this->create($input,$_FILES,TRUE);
		}
		$update_out = array();
		if(!empty($content_array))
		{
			$catalog_field_string = "'".trim(str_ireplace(CATALOG_PREFIX,'',implode("','", array_keys($content_array['value']))))."'";//去前缀
			$sql = "UPDATE ".DB_PREFIX."content SET ";
			$count_content=count($content_array);
			$i=0;//用于判断是否为最后一个字段
			foreach($content_array as $key=>$value)
			{
				$i++;
				$sql .="$key = CASE catalog_field ";
				if (!empty($value) && is_array($value))
				{
					foreach ($value as $catalog_field => $content_value)
					{
						$catalog_field_replace=trim(str_ireplace(CATALOG_PREFIX,'',$catalog_field));//去掉前缀
						$sql .= sprintf("WHEN %s THEN %s ", "'".$catalog_field_replace."'", "'".$content_value."'");  // 拼接SQL语句

						if($app_field[$catalog_field]['type']=='img')
						{
							if($content_value)
							{
								$content_value=$images[$catalog_field];
							}
							else
							{
								$content_value =array();
							}
						}
						elseif ($app_field[$catalog_field]['type']=='checkbox'&&$content_value)
						{
							$content_value=explode(',', $content_value);
						}
						$update_out[$catalog_field]=array('zh_name'=>$app_field[$catalog_field]['zh_name'],'bak'=>$app_field[$catalog_field]['bak'],'type'=>$app_field[$catalog_field]['type'],'value'=>$content_value);
					}
					if(!($i==$count_content)) $sql.="END,";
				}
			}
			$sql .= "END WHERE 1 ".$where." AND catalog_field IN ($catalog_field_string)";
			$this->db->query($sql);
			$fields = '';
			foreach($update_user as $k=>$v)
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
			$fields = rtrim($fields,',');
			$sql = 'UPDATE ' . DB_PREFIX.'content SET '.$fields.' WHERE 1 '.$where.' AND catalog_field IN ('.$catalog_field_string.')';
			$this->db->query($sql);
		}

		if($return_create)//判断是否有创建新数据,如果有则添加到返回数组中.
		{
			foreach ($return_create as $key=>$val)
			{
				$update_out[$key]=$val;
			}
		}
			$this->addItem_withkey('result', $update_out);
			$this->addItem_withkey('catalog_prefix', CATALOG_PREFIX);
			$this->output();
	}

	public function delete($return='',$is_deleteMaterial = true)
	{
		$app_uniqueid=$this->input['app_uniqueid']?trim($this->input['app_uniqueid']):'';
		$mod_uniqueid=$this->input['mod_uniqueid']?trim($this->input['mod_uniqueid']):'';
		$ids=$this->input['content_id']?trim($this->input['content_id']):'';
		$error=$this->catalog->error($app_uniqueid,$mod_uniqueid,$ids);
		if($error) $this->errorOutput($error);
		$where="";

		if(!empty($app_uniqueid))
		{
			$where.=" AND app_uniqueid ='".$app_uniqueid.'\'';

		}
		if(!empty($mod_uniqueid))
		{
			$where.=" AND mod_uniqueid ='".$mod_uniqueid.'\'';
		}
		if(!empty($ids))
		{
			if(is_string($ids))$ids_arr = explode(',', $ids);
			$ids_arr = array_filter($ids_arr);
			if (!$ids_arr)
			{
				$this->errorOutput(PARAM_WRONG);
			}
			$ids = implode(',', $ids_arr);

			$where.=" AND content_id IN(".$ids.")";
		}

		if(isset($this->input['catalogdel'])&&!empty($this->input['catalogdel']))
		{
			if(is_string($this->input['catalogdel']))
			{
				$catalogdels = $this->input['catalogdel'];
				$catalogdelarr=explode(',', $catalogdels);
			}
			elseif(is_array($this->input['catalogdel']))
			{
				$catalogdelarr=$this->input['catalogdel'];
			}
			$catalogdelarr = array_filter($catalogdelarr);

			if (!$catalogdelarr)
			{
				$this->errorOutput(PARAM_WRONG);
			}
			$catalogdel=array();
			foreach ($catalogdelarr as $val)
			{
				$catalogdel[]=trim(str_ireplace(CATALOG_PREFIX,'',$val));//去掉前缀
			}
			if($is_deleteMaterial)
			$this->catalog->deleteMaterial_field($app_uniqueid,$mod_uniqueid,$ids,$catalogdel);//删除素材

			$catalogdels = "'".implode("','", $catalogdel )."'";
			$where.=" AND catalog_field IN(".$catalogdels.")";
		}

		if(!$this->input['catalogdel'])//如果删除某个内容的全部编目,删除素材处理
		{
			$field=$this->catalog->get_catalog_field($where,false);
			
			if($is_deleteMaterial)
			$this->catalog->deleteMaterial_field($app_uniqueid,$mod_uniqueid,$ids,$field);
		}
		$sql = 'DELETE FROM ' . DB_PREFIX . 'content WHERE 1'.$where;
		$this->db->query($sql);

		if($return)
		{
			return $catalogdel;
		}
		else
		{
			$this->addItem($ids_arr);
			$this->output();
		}
	}
	
    /**
     * 删除叮当扩展字段的内容
     */
	public function DelContent()
	{
		$id = $this->input['id'] ? intval($this->input['id']) : '';
		$catalog_field = $this->input['catalog_field'] ? trim($this->input['catalog_field']) : '';
	    $identifier = $this->input['identifier'] ? intval($this->input['identifier']) : '';
	    $sql = "DELETE FROM " . DB_PREFIX . "content WHERE catalog_field='".$catalog_field."' 
	    												AND identifier=".$identifier."";
	    
	    $q = $this->db->query($sql);
	    $this->manage->delete('app_map', array('field_id' => $id));
		$this->addItem('success');
		$this->output();
	}
	
	
	public function sort(){}
	public function audit(){}
	public function publish(){}
	//空方法
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
	/**
	 *
	 * 判断是否为必填项 ...
	 * @param array $input input表单数组
	 * @param array $files file表单数组
	 * @param array $field 此应用可用的编目标识相关
	 * @param array $field_value 此应用可用的编目内容相关
	 */
	public function required($input,$files,$field,$field_value)
	{
		$required=array();
		if(is_array($field))
		{
			foreach ($field as $key => $val)
			{
				if($val['required']==1)
				{
					if($val['type']=='img'){
						if((empty($files[$key])&&empty($field_value[$key]))||((stripos($this->input['catalogdel'], $key)!== false)&&empty($files[$key])))
						{
							$required[]=$val['zh_name'];
						}
					}
					else
					{
						if (empty($input[$key]))
						{
							$required[]=$val['zh_name'];
						}
					}
				}
			}
		}
		return $required;
	}

	/**
	 *
	 * 图片上传,支持传参 ...
	 * @param array $files $_FILES数组
	 * @param int $cid 编目内容id
	 */
	public function upload_img($files=array(),$cid=0)
	{
		$id = $cid?$cid:($this->input['id']?intval($this->input['id']):0);
		$flag = $id?0:1;//判断是否临时数据
		$_FILES['Filedata']=$files?$files:$_FILES['Filedata'];

		if($_FILES['Filedata'])
		{
			if (!$this->settings['App_material']&&empty($files))
			{
				$this->errorOutput('图片服务器未安装！');
			}
			elseif(!$this->settings['App_material']) {

				return -1;//图片服务器未安装
			}
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			$img_data = array(
				'id'			=> "0",
				'host' 			=> $img_info['host'],
				'dir' 			=> $img_info['dir'],
				'filepath' 		=> $img_info['filepath'],
				'filename' 		=> $img_info['filename'],
			);

			$data = $img_data;
			$data['cid'] 			= $id;
			$data['original_id'] 	= $img_info['id'];
			$data['type'] 			= $img_info['type'];
			$data['mark'] 			= 'img';
			$data['imgwidth'] 		= $img_info['imgwidth'];
			$data['imgheight'] 		= $img_info['imgheight'];
			$data['flag']			= $flag;
			$vid = $this->catalogcore->insert_materials($data);
			if($vid&&empty($files))
			{
				$img_data['id'] = $vid;
				$this->addItem($img_data);
				$this->output();
			}
			elseif($vid)
			{
				$img_data['id'] = "$vid";
				return $img_data;
			}
		}
	}

}

$out = new catalogUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>