<?php
/**
 **编目分类*
 */
require('./global.php');
define('MOD_UNIQUEID','catalog');
require_once (CUR_CONF_PATH . 'core/catalog.core.php');
require_once(CUR_CONF_PATH . 'lib/catalog.class.php');
include_once (CUR_CONF_PATH . 'lib/manage.class.php');

class catalogApi extends outerReadBase
{

	public function __construct()
	{
		parent::__construct();
		$cache_file = CUR_CONF_PATH . CACHE_SORT;
		$this->catalogcore = new catalogcore();
		$this->catalog = new catalog();
		$this->manage = new manage();
		if (!file_exists($cache_file)) //检测缓存文件是否存在,防止require错误
		{
			$this->catalogcore->cache();//更新缓存文件
			if (!file_exists($cache_file)) //检测缓存文件更新是否成功
			{
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
	 *
	 *	取出该应用已有编目分类. ...
	 */
	public function sort()
	{
		$rows=$this->rows;
		if(empty($this->input['app_uniqueid'])&&empty($this->input['mod_uniqueid']))
		{
			$re=FALSE;
		}
		else $arr_filter=$this->manage->get_catalog_filter($this->input['app_uniqueid'],'',TRUE);
		if(!empty($arr_filter)&&is_array($arr_filter))
		{
			if($this->input['app_uniqueid']&&$this->input['mod_uniqueid']&&$this->input['content_id'])
			{
				$where=$this->catalog->get_condition();
				$arr_field=$this->manage->get_catalog_field($where);//取出已使用编目.
				if(!empty($arr_field)&&is_array($arr_field))
				{
					$arr_filter=array_diff($arr_filter,$arr_field);//属于此应用编目与此应用已使用编目取差集
				}
				if(!empty($arr_filter)&&is_array($arr_filter))
				{
					foreach ($rows as $key=>$val)
					{
						foreach ($arr_filter as $val_filter)
						{
							if(!empty($rows[$key]['html'][$val_filter]))
							{
								unset($rows[$key]['html']);
								$re[$key]=$rows[$key];//取分类标识和分类名
							}
						}
							
					}
				}
				else $re=FALSE;
			}
			elseif($this->input['app_uniqueid']&&$this->input['mod_uniqueid'])
			{
				if(!empty($arr_filter)&&is_array($arr_filter))
				{
					if(!empty($rows)&&is_array($rows))
					{
						foreach ($rows as $key=>$val)
						{
							foreach ($arr_filter as $val_filter)
							{
								if(!empty($rows[$key]['html'][$val_filter]))
								{
									unset($rows[$key]['html']);
									$re[$key]=$rows[$key];
								}
							}

						}
					}
				}
				else
				{
					$re = FALSE;
				}
					
			}
		}
		else $re=FALSE;
		$this->addItem($re);//如果编目被使用完或者此应用没有编目会输出flase
		$this->output();

	}

	/**
	 * 根据分类标识取编目
	 */
	public function stofield()
	{
		$error=$this->catalog->error(FALSE);//传参为FALSE,则content_id为可选.
		if(empty($this->input['catalog_sort']))
		{
			$this->errorOutput('未传分类标识');
		}
		if($error) $this->errorOutput($error);
		$rows=$this->rows;
		$tmp=$rows[$this->input['catalog_sort']];
		if(!empty($tmp))
		{
			$rows=array();
			$rows[$this->input['catalog_sort']]=$tmp;
			unset($tmp);
		}
		else
		{
			$this->errorOutput('分类标识不存在');
		}
		if(isset($this->input['content_id'])&&isset($this->input['app_uniqueid'])&&isset($this->input['mod_uniqueid']))
		{
			$arr_filter=$this->manage->get_catalog_filter($this->input['app_uniqueid']);
			$where=$this->catalog->get_condition();
			$arr_field=$this->manage->get_catalog_field($where);

			if(!empty($arr_filter) && is_array($arr_filter)&&!empty($arr_field)&&is_array($arr_field))
			{
				$arr_field=array_merge($arr_filter,$arr_field);
			}
			elseif(!empty($arr_filter) && is_array($arr_filter)) $arr_field=$arr_filter;

			if(!empty($arr_field) && is_array($arr_field))
			{
				$rows=$this->catalog->catalog_unset($rows, $arr_field);
			}
		}
		elseif(isset($this->input['app_uniqueid'])&&isset($this->input['mod_uniqueid']))//不带内容id处理
		{
			$arr_filter=$this->manage->get_catalog_filter($this->input['app_uniqueid']);
			if(!empty($arr_filter) && is_array($arr_filter))
			{
				$rows=$this->catalog->catalog_unset($rows, $arr_filter);	//unset掉编目
			}
		}

		if(!empty($rows) && is_array($rows))
		{
			foreach ($rows as $sortk=>$sortv)//替换data为空.
			{

				if(!empty($rows[$sortk]['html']) && is_array($rows[$sortk]['html']))
				{
					foreach ($rows[$sortk]['html'] as $sortvk=>$sortvv)
					{
						//需要单独处理的表单,如附加默认选中项等开始
						if(stripos($sortvv['style'], 'radio')!== false)//单选处理.
						{
							$selected=!empty($rows[$sortk]['html'][$sortvk]['selected'])?explode(',',$rows[$sortk]['html'][$sortvk]['selected']):explode(',', $sortvv['catalog_default']);
							$rows[$sortk]['html'][$sortvk]['style']=str_replace('value="'.$selected[0].'"','value="'.$selected[0].'"  checked="checked"',$rows[$sortk]['html'][$sortvk]['style']);
						}
						//需要单独处理的表单,如附加默认选中项等结束
						else $rows[$sortk]['html'][$sortvk]['style'] = str_replace(REPLACE_DATA,'',$rows[$sortk]['html'][$sortvk]['style']);
					}
				}
			}
			if(!empty($this->input['nosortname'])&&isset($this->input['nosortname']))
			{
				$tmp=$rows;
				$rows=$rows[$input_sort];
			}
			$this->addItem($rows);

			$this->output();
		}
		else
		{
			$this->addItem(FALSE);//如果编目被使用完或者此应用没有编目会输出flase
			$this->output();
		}
	}

	/**
	 *
	 *	取出该应用已有编目标识. ...
	 */
	public function field()
	{
		$rows=$this->rows;
		if(empty($this->input['app_uniqueid'])&&empty($this->input['mod_uniqueid']))
		{
			$re=FALSE;
		}
		else $arr_filter=$this->manage->get_catalog_filter($this->input['app_uniqueid'],'',TRUE);
		if(!empty($arr_filter)&&is_array($arr_filter))
		{
			if($this->input['app_uniqueid']&&$this->input['mod_uniqueid']&&$this->input['content_id'])
			{
				$where=$this->catalog->get_condition();
				$arr_field=$this->manage->get_catalog_field($where);//取出已使用编目.
				if(!empty($arr_field)&&is_array($arr_field))
				{
					$arr_filter=array_diff($arr_filter,$arr_field);//属于此应用编目与此应用已使用编目取差集
				}
				if(!empty($arr_filter)&&is_array($arr_filter))
				{
					foreach ($rows as $key=>$val)
					{
						foreach ($arr_filter as $val_filter)
						{
							if(!empty($rows[$key]['html'][$val_filter]))
							{
								unset($rows[$key]['html']);
								$re[$key]=$rows[$key];//取分类标识和分类名
							}
						}
							
					}
				}
				else $re=FALSE;
			}
			elseif($this->input['app_uniqueid']&&$this->input['mod_uniqueid'])
			{
				if(!empty($arr_filter)&&is_array($arr_filter))
				{
					if(!empty($rows)&&is_array($rows))
					{
						foreach ($rows as $key=>$val)
						{
							foreach ($arr_filter as $val_filter)
							{
								if(!empty($rows[$key]['html'][$val_filter]))
								{
									unset($rows[$key]['html']);
									$re[$key]=$rows[$key];
								}
							}

						}
					}
				}
				else
				{
					$re = FALSE;
				}
					
			}
		}
		else $re=FALSE;
		$this->addItem($re);//如果编目被使用完或者此应用没有编目会输出flase
		$this->output();

	}
	/**
	 *
	 * 根据应用标识,模块标识,内容id(使用编目的应用内容id)取编目存储的内容 ...
	 */
	public function show_content()
	{
		$app_uniqueid=$this->input['app_uniqueid']?trim($this->input['app_uniqueid']):'';
		$mod_uniqueid=$this->input['mod_uniqueid']?trim($this->input['mod_uniqueid']):'';
		$content_id=$this->input['content_id']?trim($this->input['content_id']):'';
		$error=$this->catalog->error($app_uniqueid,$mod_uniqueid,$content_id,FALSE);
		if($error)
		{
			$this->errorOutput($error);
		}
		$where=$this->catalog->get_condition($app_uniqueid,$mod_uniqueid);
		$id_bol=false !== stripos($content_id, ',');//判断是否是多个ID
		if($id_bol)
		{
			$where.=" AND content_id IN (".$content_id.")";
		}
		else
		{
			$where.=" AND content_id =".intval($content_id);
		}
		$field='f.zh_name,c.content_id,c.catalog_field,c.value,m.id AS mid, m.host, m.dir, m.filepath, m.filename, m.imgheight, m.imgwidth,s.type';
		$data=$this->catalogcore->show_content($where,$field);
		if($id_bol)//多个id,就带id输出处理
		{
			if(is_array($data))
			{
				foreach ($data as $key => $val)
				{
					$this->addItem_withkey($key, $val);
				}
			}
		}
		else {//单id,不带id输出处理
				
			if(is_array($data))
			{
				foreach ($data as $key => $val)
				{
					$this->addItem($val);
				}
			}
		}
		$this->output();
	}
	
	/**
	 * 批量 根据应用标识,模块标识,内容id(使用编目的应用内容id)取编目存储的内容 ...
	 */
	public function getAllcontent()
	{
		$data = $this->input['data'];
	    foreach ($data as $k=>$v)
		{
		    $app_uniqueid=$v['app_uniqueid'];
		    $mod_uniqueid=$v['mod_uniqueid'];
		    $content_id=$v['content_id'];
		    $error=$this->catalog->error($app_uniqueid,$mod_uniqueid,$content_id,FALSE);
    		if($error)
    		{
    			$this->errorOutput($error);
    		}
    		$where=$this->catalog->get_condition($app_uniqueid,$mod_uniqueid);
    		$id_bol=false !== stripos($content_id, ',');//判断是否是多个ID
    		if($id_bol)
    		{
    			$where.=" AND content_id IN (".$content_id.")";
    		}
    		else
    		{
    			$where.=" AND content_id =".intval($content_id)."";
    		}
    		$field='f.zh_name,f.catalog_default,f.status,f.form_style,f.selected,c.content_id,c.catalog_field,c.value,m.id AS mid, m.host, m.dir, m.filepath, m.filename, m.imgheight, m.imgwidth,s.type';
    		$res[$k]=$this->catalogcore->showAllcontent($where,$field,true,$id_bol);
    		if($res)
    		{
    		    $result=$res;
    		}
    		else
    		{
    		    $result=array();
    		}
			if(is_array($result))
			{
				foreach ($result as $key => $val)
				{
					$this->addItem_withkey($key, $val);
				}
			}
		}
		$this->output();
	}

	/**
	 * 根据编目标识取编目信息
	 */
	public function ftofield()
	{
		$error=$this->catalog->error(FALSE);//传参为FALSE,则content_id为可选.
		if(empty($this->input['catalog_sort']))
		{
			$this->errorOutput('未传分类标识');
		}
		if($error) $this->errorOutput($error);
		$rows=$this->rows;
		$tmp=$rows[$this->input['catalog_sort']];
		if(!empty($tmp))
		{	$rows=array();
		$rows[$this->input['catalog_sort']]=$tmp;
		unset($tmp);
		}
		else
		{
			$this->errorOutput('分类标识不存在');
		}
		if(isset($this->input['content_id'])&&isset($this->input['app_uniqueid'])&&isset($this->input['mod_uniqueid']))
		{
			$arr_filter=$this->manage->get_catalog_filter($this->input['app_uniqueid']);
			$where=$this->catalog->get_condition();
			$arr_field=$this->manage->get_catalog_field($where);

			if(!empty($arr_filter) && is_array($arr_filter)&&!empty($arr_field)&&is_array($arr_field))
			{
				$arr_field=array_merge($arr_filter,$arr_field);
			}
			elseif(!empty($arr_filter) && is_array($arr_filter)) $arr_field=$arr_filter;

			if(!empty($arr_field) && is_array($arr_field))
			{
				$rows=$this->catalog->catalog_unset($rows, $arr_field);
			}
		}
		elseif(isset($this->input['app_uniqueid'])&&isset($this->input['mod_uniqueid']))//不带内容id处理
		{
			$arr_filter=$this->manage->get_catalog_filter($this->input['app_uniqueid']);
			if(!empty($arr_filter) && is_array($arr_filter))
			{
				$rows=$this->catalog->catalog_unset($rows, $arr_filter);	//unset掉编目
			}
		}

		if(!empty($rows) && is_array($rows))
		{
			foreach ($rows as $sortk=>$sortv)//替换data为空.
			{

				if(!empty($rows[$sortk]['html']) && is_array($rows[$sortk]['html']))
				{
					foreach ($rows[$sortk]['html'] as $sortvk=>$sortvv)
					{
						//需要单独处理的表单,如附加默认选中项等开始
						if(stripos($sortvv['style'], 'radio')!== false)//单选处理.
						{
							$selected=!empty($rows[$sortk]['html'][$sortvk]['selected'])?explode(',',$rows[$sortk]['html'][$sortvk]['selected']):explode(',', $sortvv['catalog_default']);
							$rows[$sortk]['html'][$sortvk]['style']=str_replace('value="'.$selected[0].'"','value="'.$selected[0].'"  checked="checked"',$rows[$sortk]['html'][$sortvk]['style']);
						}
						//需要单独处理的表单,如附加默认选中项等结束
						else $rows[$sortk]['html'][$sortvk]['style'] = str_replace(REPLACE_DATA,'',$rows[$sortk]['html'][$sortvk]['style']);
					}
				}
			}
			if(!empty($this->input['nosortname'])&&isset($this->input['nosortname']))
			{
				$tmp=$rows;
				$rows=$rows[$input_sort];
			}
			$this->addItem($rows);

			$this->output();
		}
		else
		{
			$this->addItem(FALSE);//如果编目被使用完或者此应用没有编目会输出flase
			$this->output();
		}
	}
	/**
	 *
	 * 有数据返回数据,无数据直接输出所有属于该应用的编目
	 */
	public function show()
	{
		$arr_filter = array();
		$app_uniqueid=$this->input['app_uniqueid']?trim($this->input['app_uniqueid']):'';
		$mod_uniqueid=$this->input['mod_uniqueid']?trim($this->input['mod_uniqueid']):'';
		$content_id=$this->input['content_id']?intval($this->input['content_id']):'';
		$error=$this->catalog->error($app_uniqueid,$mod_uniqueid,$content_id,FALSE);
		if($error) $this->errorOutput($error);
		$rows=$this->rows;
		$arr_filter=$this->manage->get_catalog_filter($app_uniqueid);//不属于此应用的编目;
		$rows=$this->catalog->catalog_unset($rows, $arr_filter);	//unset掉编目
		if($content_id)
		{
			$where=$this->catalog->get_condition($app_uniqueid,$mod_uniqueid,$content_id);
			$where .=' AND field.switch = 1';
			$sql = "SELECT sort.catalog_sort,field.catalog_field,field.form_style,content.id as c_id,content.value,
			m.id AS mid, m.host, m.dir, m.filepath, m.filename, m.imgheight, m.imgwidth FROM " . DB_PREFIX . "field AS field 
			LEFT JOIN " . DB_PREFIX . "content AS content ON field.catalog_field=content.catalog_field  AND field.identifier=content.identifier
			LEFT JOIN ". DB_PREFIX . "field_sort AS sort ON sort.id=field.catalog_sort_id 
			LEFT JOIN ".DB_PREFIX ."materials AS m ON m.cid=content.id WHERE 1 ".$where;
			$q = $this->db->query($sql);
			while($data = $this->db->fetch_array($q))
			{
				$data['catalog_field']=catalog_prefix($data['catalog_field']);
				$data['value']=maybe_unserialize($data['value']);
				if($rows[$data['catalog_sort']]['html'][$data['catalog_field']]['type']=='img')
				{
					$img_info = array();
					if ($data['host'] && $data['dir'] && $data['filepath'] && $data['filename'])
					{
					$img_info = array(
					'id'		=> $data['mid'],
					'host'		=> $data['host'],
					'dir'		=> $data['dir'],
					'filepath'	=> $data['filepath'],
					'filename'	=> $data['filename'],
					'imgheight'	=> $data['imgheight'],
					'imgwidth'	=> $data['imgwidth'],
						);
					}
					$rows[$data['catalog_sort']]['html'][$data['catalog_field']]['catalog_cid']=$data['c_id'];
					if($img_info)
					{
						$rows[$data['catalog_sort']]['html'][$data['catalog_field']]['data'][]=$img_info;
					}
				}
				elseif(!$rows[$data['catalog_sort']]['html'][$data['catalog_field']]['style']||!$rows[$data['catalog_sort']]['html'][$data['catalog_field']]['zh_name'])//如果出现缓存与数据库记录不同步
				{
					$this->catalogcore->cache();//更新缓存文件
					$this->show();
				}
			}
            if(is_array($rows) & $rows['field']!='dingdone')
            {
    			foreach ($rows as $sort=>$value)
    			{
    			    if(is_array($v['html']))
    			    {
    			       foreach ($v['html'] as $field=>$values) {
    			           $filedval = $values['catalog_cid']?$values['data']:$values['selected'];
    					   $rows[$sort]['html'][$field]['style'] = $this->catalogcore->replace($values['style'], $values['type'], $filedval);
    			       }
    			    }
    			}
            }
		}
		else
		{
			while (list($sort,$value) = each($rows))
			{
				while (list($field,$values) = each($value['html']))
				{
					$rows[$sort]['html'][$field]['style'] = $this->catalogcore->replace($values['style'], $values['type'], $values['selected']);
				}
			}
		}
		$this->addItem_withkey('field', $rows);
		$this->addItem_withkey('materialdel',MATERIALDEL);//删除某个图片或者视频素材记录删除掉的id
		$this->addItem_withkey('catalogdel',CATALOGDEL);//删除某个编目记录删除掉的编目
		$this->addItem_withkey('catalog_prefix',CATALOG_PREFIX);//编目前缀
		$this->output();
	}
	/**
	 * 获取某个应用某个模块某个内容
	 * @param  content_id
	 * @param  app_uniqueid
	 * @param  mod_uniqueid
	 */
	public function detail()
	{
		$rows=$this->rows;
		if(empty($this->input['content_id'])&&$this->input['yes'])//无id,FALSE返回值.如果需要ERROR返回方式,则留yes为空.
		{
			$catalogs=FALSE;
		}
		else
		{
			$error=$this->catalog->error();
			if($error) $this->errorOutput($error);
			$where=$this->catalog->get_condition();
			$sql = "SELECT sort.catalog_sort,field.catalog_field,content.id,content.value FROM " . DB_PREFIX . "field AS field LEFT JOIN catalog_content AS content ON field.catalog_field=content.catalog_field LEFT JOIN ". DB_PREFIX . "field_sort AS sort ON sort.id=field.catalog_sort_id WHERE 1 ".$where;
			$q = $this->db->query($sql);
			while($data = $this->db->fetch_array($q))
			{
				$data['catalog_field']=catalog_prefix($data['catalog_field']);
				$datas[$data['catalog_field']]=array($data['catalog_field']=>$data['value'],'data_id'=>$data['id'],'catalog_sort'=>$data['catalog_sort']);
				$field[$data['catalog_field']] =  $data['catalog_field']; //编目标识
			}
			if (!empty($datas) && is_array($datas))//替换value值和添加data内容
			{
				foreach ($rows as $sortk=>$sortv)
				{
					if(!empty($sortv['html']) && is_array($sortv['html']))
					{
						foreach ($sortv['html'] as $sortkk=>$sortvv)
						{
							if($sortkk!=$field[$sortkk])//如果变量比对不相等,则该内容必然不存在
							{
								unset($rows[$sortk]['html'][$sortkk]);//unset掉不存在数据.
								unset($field[$sortkk]);//unset掉无用变量.
							}
						}
					}
					if(empty($rows[$sortk]['html']) && is_array($rows[$sortk]['html']))
					{
						unset($rows[$sortk]);//unset掉空分类
					}
				}
				foreach ($datas as $datas_key=>$datas_value)
				{
					if(stripos($rows[$datas_value['catalog_sort']]['html'][$datas_key]['style'], 'radio')!== false)//单选处理
					{
						$rows[$datas_value['catalog_sort']]['html'][$datas_key]['data']=array('data_id'=>$datas_value['data_id'],'value'=>$datas_value[$datas_key]);
						$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']=str_replace('value="'.$datas_value[$datas_key].'"','value="'.$datas_value[$datas_key].'"  checked="checked"',$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']);
						$catalogs[$datas_value['catalog_sort']]=$rows[$datas_value['catalog_sort']];
					}
					elseif(stripos($rows[$datas_value['catalog_sort']]['html'][$datas_key]['style'], 'option')!== false)//下拉处理
					{
						$rows[$datas_value['catalog_sort']]['html'][$datas_key]['data']=array('data_id'=>$datas_value['data_id'],'value'=>$datas_value[$datas_key]);
						$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']=str_replace('value="'.$datas_value[$datas_key].'"','value="'.$datas_value[$datas_key].'"  selected="selected"',$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']);
						$catalogs[$datas_value['catalog_sort']]=$rows[$datas_value['catalog_sort']];
					}

					elseif(stripos($rows[$datas_value['catalog_sort']]['html'][$datas_key]['style'], 'checkbox')!== false)//多选处理
					{
						$checkbox_value_array=explode(',', $datas_value[$datas_key]);
						$rows[$datas_value['catalog_sort']]['html'][$datas_key]['data']=array('data_id'=>$datas_value['data_id'],'value'=>$datas_value[$datas_key]);
						if (isset($datas_value[$datas_key])&&!empty($datas_value[$datas_key]))//判断value是否为空,如果为空则不替换选中状态.
						{
							foreach ($checkbox_value_array as $checkbox_value)
							{
								$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']=str_replace('value="'.$checkbox_value.'"','value="'.$checkbox_value.'"  checked="checked"',$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']);
							}
						}
						$catalogs[$datas_value['catalog_sort']]=$rows[$datas_value['catalog_sort']];
					}
					elseif(stripos($rows[$datas_value['catalog_sort']]['html'][$datas_key]['style'], 'file')!== false)//文件上传处理
					{

						$datas_value[$datas_key] = unserialize($datas_value[$datas_key]);
						$rows[$datas_value['catalog_sort']]['html'][$datas_key]['data']=array('data_id'=>$datas_value['data_id'],'value'=>$datas_value[$datas_key]);
						$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']=str_replace(REPLACE_DATA,$datas_value[$datas_key],$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']);
						$catalogs[$datas_value['catalog_sort']]=$rows[$datas_value['catalog_sort']];
					}
					else
					{
						$rows[$datas_value['catalog_sort']]['html'][$datas_key]['data']=array('data_id'=>$datas_value['data_id'],'value'=>$datas_value[$datas_key]);
						$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']=str_replace(REPLACE_DATA,$datas_value[$datas_key],$rows[$datas_value['catalog_sort']]['html'][$datas_key]['style']);
						$catalogs[$datas_value['catalog_sort']]=$rows[$datas_value['catalog_sort']];
					}
				}


				foreach ($catalogs AS $catalogs_key=>$catalogs_value)//重新遍历数组,检查未被使用的编目替换value为空值;
				{
					if(!empty($catalogs[$catalogs_key]['html']) && is_array($catalogs[$catalogs_key]['html'])){
						foreach ($catalogs[$catalogs_key]['html'] as $catalogs_key_key=>$catalogs_value_value)
						{

							$catalogs[$catalogs_key]['html'][$catalogs_key_key]['style'] = str_replace(REPLACE_DATA,'',$catalogs[$catalogs_key]['html'][$catalogs_key_key]['style']);

						}
					}
				}
			}
			else
			{//此内容id不存在则执行处理
				$catalogs=FALSE;
			}
		}
		$this->addItem($catalogs);
		$this->output();
	}
	
	/**
	 * 获取已设置的编目和内容
	 * @Description 
	 * @author Kin
	 * @date 2014-10-20上午10:28:21
	 */
	public function select()
	{
	    $user_id = isset($this->input['user_id']) ? intval($this->input['user_id']) : 0;
		$identifier = isset($this->input['identifier']) ? intval($this->input['identifier']) : 0;
		$user_type = isset($this->input['user_type']) ? intval($this->input['user_type']) : 0;
		$sql = "SELECT * FROM " . DB_PREFIX . "field LEFT JOIN " . DB_PREFIX . "content 
												ON " . DB_PREFIX . "content.catalog_field=" . DB_PREFIX . "field.catalog_field 
												WHERE " . DB_PREFIX . "user_id=".$user_id." AND " . DB_PREFIX . "identifier=".$identifier."";
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
		    //$this->catalogcore->cache();//更新缓存
			$this->addItem($row);
		}
		
		$this->output();
	}
	
	public function no()//测试编目是否被某一应用使用完毕!
	{
		$error=$this->catalog->error(FALSE);
		if($error) $this->errorOutput($error);
		$no= TRUE;
		$filter=$this->manage->get_catalog_filter($this->input['app_uniqueid'],'',TRUE);
		$where=$this->catalog->get_condition();
		if($this->input['content_id'])
		{
			$arr_field=$this->manage->get_catalog_field($where);//已使用编目
		}
		else $arr_field=array();
		if(empty($filter))
		{
			$no=FALSE;
		}
		elseif(!empty($filter)&&!empty($arr_field))
		{
			if(!array_diff($filter,$arr_field))
			{
				$no=FALSE;
			}
		}
		$this->addItem($no);//如果编目被使用完或者此应用没有编目会输出false
		$this->output();
	}
	public function yes()//测试编目是否被某一应用使用!
	{
		$error=$this->catalog->error(FALSE);
		if($error) $this->errorOutput($error);
		$yes= FALSE;
		$where=$this->catalog->get_condition();
		if($this->input['content_id'])
		{
			$arr_field=$this->manage->get_catalog_field($where);//已使用编目
		}
		else $arr_field=array();
		if(!empty($arr_field))
		{
			$yes=TRUE;
		}
		$this->addItem($yes);//如果编目未被使用过则输出false
		$this->output();
	}

	public function count()
	{
	}


}
$out=new catalogApi();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>