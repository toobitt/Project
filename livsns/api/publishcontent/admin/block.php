<?php
require('global.php');
define('MOD_UNIQUEID','publishcontent_block');//模块标识
require_once(ROOT_PATH.'lib/class/publishsys.class.php');
require_once(ROOT_PATH.'lib/class/publishconfig.class.php');
class blockApi extends BaseFrm
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		$this->pub_sys= new publishsys();
		$this->pub_config= new publishconfig();
		include(CUR_CONF_PATH . 'lib/block.class.php');
		$this->obj = new block();
		include(CUR_CONF_PATH . 'lib/block_set.class.php');
		$this->block_set = new block_set();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$columns = array();
		//查询出站点下模块的内容
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$block_data = $this->obj->get_block($this->get_condition(),$offset,$count);
		//查询栏目的名称
		if($block_data['block_record'])
		{
			foreach($block_data['block_record'] as $v)
			{
				foreach($v as $vv)
				{
					$column_ids .= ','.$vv;
				}
			}
			$columns = $this->pub_config->get_columnname_by_ids('id,name',trim($column_ids,','));
		}
		//查出所有app标识
		$apps = $this->obj->get_app();
		
		$result['block'] = $block_data;
		$result['column'] = $columns;
		$result['app'] = $apps;
//		print_r($columns);exit;
		$this->addItem($result);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."block WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	private function get_condition()
	{
		$condition = ' AND group_id=id ';
		$id = urldecode($this->input['_id']);
		if($id!='')
		{
			if(strstr($id,"site")===false)
			{
				$coldetail = $this->pub_config->get_column_first(' site_id ',$id);
				if(empty($coldetail))
				{
					$this->errorOutput('未获取到栏目信息');
				}
				//获取这个栏目下的所有栏目id
				$columns_data = $this->pub_config->get_column_first(' id,name,fid,parents,childs ' , $id);
				$column_id = $columns_data['childs'];
				$condition .= " AND column_id in(".$column_id.")";
			}
			else
			{
				$site_id = str_replace('site','',$id);
				$condition .= " AND site_id in(".$site_id.")";
			}
		}
		if($keyword = urldecode($this->input['keyword']))
		{
			$condition .= " AND name like '%".$keyword."%'";
		}
		return $condition;
	}
	
	public function block_form()
	{
		$data = $datasource_info_data = array();
		$id = intval($this->input['id']);
		if($id)
		{
			$data = $this->obj->get_block_first($id);
			
			if($data['datasource_id'])
			{
				$datasource_info_data = $this->pub_sys->get_datasource_info($data['datasource_id']);
				if($data['datasource_argument'])
				{
					$data['datasource_argument'] = unserialize($data['datasource_argument']);
				}
			}
		}
		
		//取出数据源
		$datasource = $this->pub_sys->showDataSource();
		
		//取出所有栏目
		$all_column = $this->pub_config->get_column(' id,name ');
		
		$data['datasource_data'] = $datasource['datasource_data'];
		$data['datasource_info_data'] = $datasource_info_data;
		$data['app_data'] = $datasource['app_data'];
		$data['all_column'] = $all_column;
//		print_r($data);exit;
		$this->addItem($data);
		$this->output();
	}
	
	public function create()
	{
		$data = array(
			'site_id' => intval($this->input['site_id']),
			'column_id' => intval($this->input['column_id']),
			'name' => urldecode($this->input['name']),
			'update_time' => intval($this->input['update_time']),
			'update_type' => intval($this->input['update_type']),
			'datasource_id' => intval($this->input['datasource_id']),
			'width' => intval($this->input['width']),
			'height' => intval($this->input['height']),
			'line_num' => intval($this->input['line_num']),
			'father_tag' => urldecode($this->input['father_tag']),
			'loop_body' => urldecode($this->input['loop_body']),
			'next_update_time' => TIMENOW+intval($this->input['update_time']),
		);
		if(!$data['name'] || !$data['update_type'])
		{
			$this->errorOutput('信息未填全');
		}
		
		//判断有没有数据源id，如果有则取设定的参数
		if($data['datasource_id'])
		{
			$datasource_info_data = $this->pub_sys->get_datasource_info($data['datasource_id']);
			$datasource_arg = $datasource_info_data['argument'];
			foreach($datasource_arg['ident'] as $k=>$v)
			{
				$datasource_argarr[$v] = urldecode($this->input['argument_'.$v]);
			}
		}
		$data['datasource_argument'] = $datasource_argarr?serialize($datasource_argarr):'';
		//根据栏目id查出站点id
		$column_detail = $this->pub_config->get_column_first(' id,site_id ',$data['column_id']);
		$data['site_id'] = $column_detail['site_id'];
		$insert_id = $this->obj->insert($data);
		$line_data = array(
			'block_id' => $insert_id,
		);
		//根据数据源取对应内容
		$content_data = array();
		if($data['datasource_id'])
		{
			$content_data = $this->pub_sys->queryDataSource($data['datasource_id'],$datasource_argarr);
		}
		$this->block_set->insert_line($data['line_num'] , $line_data);
		$this->block_set->insert_content($data['line_num'] , $insert_id , $content_data);
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('更新失败');
		}
		$data = array(
			'column_id' => intval($this->input['column_id']),
			'name' => urldecode($this->input['name']),
			'update_time' => intval($this->input['update_time']),
			'update_type' => intval($this->input['update_type']),
			'datasource_id' => intval($this->input['datasource_id']),
			'width' => intval($this->input['width']),
			'height' => intval($this->input['height']),
			'line_num' => intval($this->input['line_num']),
			'father_tag' => urldecode($this->input['father_tag']),
			'loop_body' => urldecode($this->input['loop_body']),
		);
		if(!$data['name'] || !$data['update_type'])
		{
			$this->errorOutput('信息未填全');
		}
		
		//判断有没有数据源id，如果有则取设定的参数
		if($data['datasource_id'])
		{
			$datasource_info_data = $this->pub_sys->get_datasource_info($data['datasource_id']);
			$datasource_arg = $datasource_info_data['argument'];
			foreach($datasource_arg['ident'] as $k=>$v)
			{
				$datasource_argarr[$v] = urldecode($this->input['argument_'.$v]);
			}
		}
		$data['datasource_argument'] = $datasource_argarr?serialize($datasource_argarr):'';
		
		$old_block_data = $this->obj->get_block_first($id);
		
		//根据栏目id查出站点id
		$column_detail = $this->pub_config->get_column_first(' id,site_id ',$data['column_id']);
		$data['site_id'] = $column_detail['site_id'];
		$this->obj->update($data,$id);
		
		$data = $this->obj->get_block_first($id);
		$datasource_info_data = array();
		if($data['datasource_id'])
		{
			$datasource_info_data = $this->pub_sys->get_datasource_info($data['datasource_id']);
			if($data['datasource_argument'])
			{
				$data['datasource_argument'] = unserialize($data['datasource_argument']);
			}
		}
		//取出数据源
		$datasource = $this->pub_sys->showDataSource();
		
		//取出所有栏目
		$all_column = $this->pub_config->get_column(' id,name ');
		
		$data['datasource_data'] = $datasource['datasource_data'];
		$data['datasource_info_data'] = $datasource_info_data;
		$data['app_data'] = $datasource['app_data'];
		$data['all_column'] = $all_column;
		$this->addItem($data);
		$this->output();
	}
	
	public function delete()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput('删除失败');
		}
		$this->obj->delete($ids);
		$this->outItem('success');
		$this->output();
	}
	
	public function get_datasource_info()
	{
		$id = intval($this->input['id']);
		$data = $this->pub_sys->get_datasource_info($id);
		$this->addItem($data);
		$this->output();
	}
	
	public function get_block()
	{
		$data = array();
		$data = $this->obj->get_all_block();
		$this->addItem($data);
		$this->output();
	}
	
	public function get_block_content()
	{
		$data = array();
		$block_id = intval($this->input['block_id']);
		$line_num = intval($this->input['line_num']);
		if(!$line_num)
		{
			//取区块信息
			$block_data = $this->obj->get_block_first($block_id);
		}
		//取区块每行内容
		$content_data = $this->block_set->get_block_content($block_id , $line_num?$line_num:$block_data['line_num']);
		$this->addItem($content_data);
		$this->output();
	}
	
	public function get_block_content_html()
	{
		$data = array();
		$str = '';
		$block_id = intval($this->input['block_id']);
//		$line_num = intval($this->input['line_num']);
		$pic_width = urldecode($this->input['pic_width']);
		$pic_height = urldecode($this->input['pic_height']);
		$title_num = urldecode($this->input['title_num']);
		$brief_num = urldecode($this->input['brief_num']);
		if(!$block_id)
		{
			$result = array(
				'error' => '未传区块',
			);
			$this->addItem($result);
			$this->output();
		}
		//取区块信息
		$block_data = $this->obj->get_block_first($block_id);
		//取区块每行内容
		$content_data = $this->block_set->get_block_content($block_id , $block_data['line_num']);
		//取区块每行信息
		$line_data = $this->block_set->get_block_line($block_id);
		
		//父标签
		$str = "<".$block_data['father_tag']." style='";
		if($block_data['width'])
		{
			$str .= "width:".$block_data['width']."px;";
		}
		if($block_data['height'])
		{
			$str .= "height:".$block_data['height']."px;";
		}
		$str .= "'>";
		
		//每行
		foreach($line_data as $k=>$v)
		{
			if(!empty($content_data[$k]))
			{
				foreach($content_data[$k] as $kk=>$vv)
				{
					$title = $title_num?hg_cutchars($vv['title'],$title_num,''):$vv['title'];
					$brief = $brief_num?hg_cutchars($vv['brief'],$brief_num,''):$vv['brief'];
					$outlink = $vv['outlink'];
					if(!empty($vv['indexpic']))
					{
						$pic_data = unserialize($vv['indexpic']);
						$indexpic = $pic_data['host'].$pic_data['dir'];
						if($pic_width && !$pic_height)
						{
							$indexpic .= $pic_width."x"."0/";
						}
						else if($pic_width && $pic_height)
						{
							$indexpic .= $pic_width."x".$pic_height."/";
						}
						$indexpic .= $pic_data['filepath'].$pic_data['filename'];
					}
					$loop_body = $v['loop_body']?$v['loop_body']:$block_data['loop_body'];
//					print_r($loop_body);exit;
					eval("\$li_data = \"$loop_body\";");
					//生成样式
					$style = " style='";
					if($v['width'])
					{
						$style .= "width:".$v['width']."px;";
					}
					if($v['height'])
					{
						$style .= "height:".$v['height']."px;";
					}
					$style .= "'";
					
					if(!$insert_i = stripos($v['loop_body'],'>'))
					{
						$result = array(
							'error' => '未找到区块样式的标识',
						);
						$this->addItem($result);
						$this->output();
					}
					
					//插入样式
					$str .= str_insert($li_data, $insert_i , $style);
				}
			}
			
		}
		$str .= "</".$block_data['father_tag'].">";
		
		$this->addItem($str);
		$this->output();
	}
	
	public function check_update_content()
	{
		//取自动更新的区块(每次只取一个区块)
		$update_block = $this->obj->get_block_by_condition(' AND update_type=2 ORDER BY next_update_time ASC limit 1');
		if(empty($update_block))
		{
			echo "没有可更新的区块";
			exit;
		}
		if($update_block['next_update_time'] > TIMENOW)
		{
			echo "没有可更新的区块";
			exit;
		}
		
		//更改区块下次更新时间
		$update_data = array('next_update_time' => $update_block['update_time']+$update_block['next_update_time']);
		$this->obj->update($update_data,$update_block['id']);
		
		/**更新区块每行内容*/
		//根据数据源设置的参数取内容
		$condition_arr = array();
		if($update_block['datasource_argument'])
		{
			$datasource_argument = unserialize($update_block['datasource_argument']);
			foreach($datasource_argument as $k=>$v)
			{
				if($v !== '')
				{
					$condition_arr[$k] = $v;
				}
			}
		}
		$content_data = $this->pub_sys->queryDataSource($update_block['id'],$condition_arr);
		if(empty($content_data))
		{
			echo "没有要更新的内容";
			exit; 
		}
		
		//查询出这个区块的现有所有内容id
		$contentidarr = $this->block_set->get_all_contentid($update_block('id'));
		
		//满足什么条件进行内容添加
		foreach($content_data as $k=>$v)
		{
			if(!in_array($v['id'],$contentidarr))
			{
				//插入到区块内容表中,插入到最新行
				$newdata = array(
					'block_id' => $update_block('id'),
					'line' => 1,
					'title' => $v['title'],
					'brief' => $v['brief'],
					'outlink' => $v['outlink'],
					'indexpic' => $v['indexpic'],
					'child_line' => 1,
				);
				$this->block_set->insert_child_content($newdata , true);
			}
		}
		
	}
	
	public function use_block()
	{
		$data = $this->input['data'];
		$block_id = intval($this->input['block_id']);
		
		if(!$block_id)
		{
			$result = array(
				'error' => '没有使用的区块',
			);
		}
		$this->obj->update_block_use_num($block_id);
		//记录到记录表
		
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright 	ho	gesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new blockApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'check_update_content';
}
$out->$action();
?>