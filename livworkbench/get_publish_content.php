<?php

define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'publishcontent');
require('./global.php');
require('./lib/class/curl.class.php');
class publishcontent extends uiBaseFrm
{	
	private $site;
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_publishcontent']['host'],$this->settings['App_publishcontent']['dir']);
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function seturl()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir'] . 'admin/');
	}
	
	//获取发布内容
	public function get_content()
	{
		$data = array();
		$data = $this->get_conditions();
		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('a', 'get_content');
		if (is_array($data))
		{
			foreach($data as $k=>$v)
			{
				$this->curl->addRequestData($k,$v);
			}
		}
		$re = $this->curl->request('content.php');
		
		$bundles = $this->get_bundles('1');
		
		$columns = $this->get_columns('1');
		
		if(is_array($re['data']))
		{
			foreach($re['data'] as $k=>$v)
			{
				$co_names = array();
				if($v['column_id'])
				{
					$co_arr = explode(" ",$v['column_id']);
					foreach($co_arr as $ke=>$va)
					{
						$co_names[] = $columns[$va];
					}
				}
				$v['column_name'] = implode(" ",$co_names);
				//$v['app_name']	=	$apps[$v['bundle_id']];
				$v['module_name']	=	$bundles[$v['bundle_id']];
				$v['pic'] = json_encode($v['indexpic']);
				$ret[] = $v;
			}
		}
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 10;
		
		$total_num =$re['total'];//总的记录数
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$return['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$count) + 1;
		}
		$return['total_num'] = $total_num;//总的记录数
		$return['page_num'] = $count;//每页显示的个数
		$return['current_page']  = $pp;//当前页码
		
		$retu['info'] = $ret;
		$retu['page_info'] = $return;
		exit(json_encode($retu));
	}
	
	private function get_conditions()
	{
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 10;
		$offset = intval(($pp - 1)*$count);			
		$data = array(
			'offset'	  		=> $offset,
			'count'		  		=> $count,
			'client_type'		=>	'2',
			'need_count'		=> '1',
		);
		if ($this->input['column_id'])
		{
			$data['column_id'] = intval($this->input['column_id']);
		}
		
		if($this->input['info'])
		{
			foreach($this->input['info'] as $k=>$v)
			{
				$info[$v['name']] = $v['value'];
			}
		}
		//查询
		if($info['special_modules'])
		{
			$data['bundle_id'] = $info['special_modules'];
		}
		
		//查询站点
		if($info['site_id'])
		{
			$data['site_id'] = $info['site_id'];
		}

		//查询时间
		if($info['special_date_search'])
		{
			$data['date_search'] = $info['special_date_search'];
		}
		
		//查询标题
		if($info['k'])
		{
			$data['k'] = $info['k'];
		}
		
		//查询创建的起始时间
		if($info['start_time'])
		{
			$data['starttime'] = $info['start_time'];
		}
		
		//查询创建的结束时间
		if($info['end_time'])
		{
			$data['endtime'] = $info['end_time'];
		}
		
		//发布人
		if($info['user_name'])
		{
			$data['publish_user'] = $info['user_name'];
		}
		//站点
		if($info['site_id'])
		{
			$data['site_id'] = $info['site_id'];
		}
		
		//是否有示意图
		if($info['is_have_indexpic'])
		{
			$data['is_have_indexpic'] = $info['is_have_indexpic'];
		}
		//是否有视频
		if($info['is_have_video'])
		{
			$data['is_have_video'] = $info['is_have_video'];
		}
		//查询权重
		if(isset($info['start_weight']) && intval($info['start_weight'])>=0)
		{
			$data['start_weight'] = $info['start_weight'];
		}
		if(isset($info['end_weight']) && intval($info['end_weight'])>=0)
		{
			$data['end_weight'] = $info['end_weight'];
		}
		
		return $data;
	}
	
	//获取应用模块
	public function get_bundles($flag='')
	{
		$this->seturl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_pub_content_type');
		$type = $this->curl->request('content.php');
		$return = $type[0];
		
		if(is_array($return))
		{
			foreach($return as $k => $v)
			{
				$bundles[$v['bundle']] = $v['name'];
			}
		}
		if($flag)
		{
			return  $bundles;
		}
		else
		{
			exit(json_encode($bundles));
		}
	}
	
	//获取栏目
	public function get_columns($flag='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_column');
		$this->curl->addRequestData('field',' * ');
		$this->curl->addRequestData('condition','');
		$this->curl->addRequestData('html',true); 
		$publish_columns = $this->curl->request('column.php');
		
		if($publish_columns && is_array($publish_columns))
		{
			foreach($publish_columns as $k=>$v)
			{
				$columns[$v['id']]	= $v['name'];
			}
		}
		if($flag)
		{
			return  $columns;
		}
		else
		{
			exit(json_encode($columns));
		}
	}
	
	//获取站点
	public function get_site()
	{	
		$this->seturl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_sites');
		$ret = $this->curl->request('site.php');
		exit(json_encode($ret[0]));
	}	
}
include (ROOT_PATH . 'lib/exec.php');
?>