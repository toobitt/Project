<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', './');
define('WITH_DB',true);
define('SCRIPT_NAME', 'cloud');
require('./global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class cloud extends uiBaseFrm
{
	private $curl;
	private $initdata;
	private $cloud;
	private $default;
	private $cloud_user;
	private $request_times = 0;
	private $record_data;
	private $app_uniqueid;
	private $publish_maps = array();
	//入库映射处理
	private $maps = array(
		'news'=>array(
			'title'=>'title',
			'content'=>'content',
			'brief'  => 'brief',
			'indexpic_url' => 'indexpic',
			'tcolor'   => 'tcolor',
			'isbold'   => 'isbold',
			'author'   => 'author',
			//'create_time' => 'create_time',
			//'update_time' => 'update_time',
			'weight'    => 'weight',
			'keywords' => 'keywords',
			'outlink'=>'outlink',
		),
		'tuji'=>array(
			'title'=>'title',
			'keywords'=>'keywords',
			'comment'=>'comment',
			//'cover_url'=>'cover_url',
			'pics'=>'pics',
			'index_id'=>'index_id',
		),
		'livmedia'=>array(
			'title'=>'title',
			'isbold'=>'isbold',
			'tcolor'=>'tcolor',
			'isitalic'=>'isitalic',
			'weight'=>'weight',
			'comment'=>'comment',
			'subtitle'=>'subtitle',
			'source'=>'source',
			'author'=>'author',
			'keywords'=>'keywords',
			'hostwork'=>'hostwork',
			'video_path'=>'video_path',
			'video_filename'=>'video_filename',
			'duration'=>'duration',
			'totalsize'=>'totalsize',
			'video'=>'video',
			'frame_rate'=>'frame_rate',
			'aspect'=>'aspect',
			'width'=>'width',
			'height'=>'height',
			'audio'=>'audio',
			'sampling_rate'=>'sampling_rate',
			'audio_channels'=>'audio_channels',
			'img'=>'index_pic',
			'bitrate'=>'bitrate',
			'is_forcecode'=>'is_forcecode',
			'starttime'=>'starttime',
			'status'=>'status',
			'audit_time'=>'audit_time',
			'is_audio'=>'is_audio',
			'isfile'=>'isfile'
		),
	);
	private $search_maps = array(
		//文稿
		'news'=>array(
			//'status_search'=>'article_status',
		),
		//图集
		'tuji'=>array(
			'key'=>'k',
			'status_search'=>'tuji_status',
		),
		'livmedia'=>array(
			'status_search'=>'trans_status',
			'key'=>'k',
		)
	);
	private $show_maps = array(
		//文稿
		'news'=>array(
			//'status_search'=>'article_status',
		),
		//图集
		'tuji'=>array(
			'cover_array'=>'pic',
			'create_time'=>'create_time_show',
			'status'=>'audit',
		),
		'livmedia'=>array(
			'img_info'=>'pic',
			'status'=>'audit',
			'addperson'=>'user_name',
			'create_time'=>'create_time_show',
		)
	);
	function __construct()
	{

		parent::__construct();
		include('./publish_config.php');
		$this->publish_maps = $_publish_maps;
		if($_GET['debug'])
		{
			print_r($this->publish_maps);
		}
		$this->curl = new curl();
		$this->curl->setmAutoInput(0);
		if(!intval($this->input['site_id']))
		{
			if(isset($this->input['info']))
			{
				foreach($this->input['info'] as $k => $v)
				{
					if($v['name'] == 'site_id')
					{
						$this->input['site_id'] = $v['value'];
						break;
					}
				}
			}
			else
			{
				
				if(!$this->input['cloud_id'])
				{
					$site = $this->_getSite();
					if($site && is_array($site))
					{
						foreach($site as $k => $v)
						{
							$this->input['site_id'] = $site[1]['id'];
							break;
						}				
					}
				}
				else
				{
					$tmp_first = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'cloud WHERE cloud_id=' . intval($this->input['cloud_id']));
					$this->input['site_id'] = $tmp_first['site_id'];
				}				
			}
//			file_put_contents('./cache/sss2',var_export($this->input,1));
//			file_put_contents('./cache/sss2',var_export($site,1),FILE_APPEND);
		}
		else
		{
			if(intval($this->input['mid']))
			{
				$tmp_first = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'cloud WHERE site_id=' . intval($this->input['site_id']) . ' AND module_id=' . intval($this->input['mid']));
				$this->input['cloud_id'] = $tmp_first['cloud_id'];
			}
		}
		$this->initdata = array(
			'mid'=> intval($this->input['mid']),
			'cloud_id'=>intval($this->input['cloud_id']),
			'site_id'=>intval($this->input['site_id']),
		);
	//	hg_pre($this->initdata);exit;
		$sql = 'SELECT app_uniqueid FROM ' . DB_PREFIX . 'modules WHERE id='.$this->initdata['mid'];
		$this->app_uniqueid = $this->db->query_first($sql);
		$this->app_uniqueid = $this->app_uniqueid['app_uniqueid'];
		//exit($this->app_uniqueid);
		$this->cloud = $this->cloud_data();

		if(!$this->cloud)
		{
			$this->cloud_error('Cloud Error');
		}
		if($this->initdata['cloud_id'])
		{
			$this->default = $this->cloud[$this->initdata['cloud_id']];
		}
		else
		{
			list($_default,$this->default) = each($this->cloud);
			unset($_default);
		}
		if(!$this->default)
		{
			$this->cloud_error('指定数据源不存在！');
		}
		if(!$this->default['access_token'])
		{
			$this->cloud_user = $this->access($this->default);
		}
		else
		{
			$this->cloud_user = $this->access($this->default, true);
		}
		//$this->record_data = (array)$this->record();
		$this->curl->setSubmitType('post');
		$this->curl->setClient($this->default['appid'], $this->default['appkey']);
		$this->curl->setToken($this->cloud_user['token']);

	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
	//file_put_contents('./cache/sss2',var_export($this->input,1));
		$default = $this->default;
	//	hg_pre($default);exit;
		$data = array(
			'list'		=> array(),
			'page'		=> array(
				'total_page'=>0,
				'total_num'=>0,
				'page_num'=>$this->input['count'] ? intval($this->input['count']) : 10,
				'current_page'=>$this->input['page'] ? intval($this->input['page']) : 1,
				'page_num_list' => array(10,20,40,60,80,100),
			),
			'node'		=> array(),
			'search'	=> $this->search(),
			'cloud'		=> $this->cloud,
			'site_info'		=> $default['site_info'],
		);
		$offset = ($data['page']['current_page']-1)*$data['page']['page_num'];
		$count = $data['page']['page_num'];
		$this->curl->setUrlHost($default['remote_host'], $default['remote_dir']);
		//初始化
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		//$this->curl->addRequestData('outlink',1);
		$this->add_search_data($data['search']);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('offset', $offset);
		//列表默认数据
		$list = $this->curl->request($default['remote_file']);
		if($list)
		{
			$id_strings = '';
			foreach ($list as $key=>$val)
			{
				$list[$key]['is_local'] = 0;
				/*
				if(in_array($val['id'], $this->record_data))
				{
					$list[$key]['is_local'] = 1;
				}
				*/
				$id_strings .= $val['id'] . ',';
				if($this->show_maps[$this->app_uniqueid])
				{
					foreach($this->show_maps[$this->app_uniqueid] as $from=>$to)
					{
						$list[$key][$to] = $list[$key][$from];
						unset($list[$key][$from]);
					}
				}
			}
			$exists = $this->record($id_strings);
			foreach($list as $key=>$val)
			{
				if(in_array($val['id'], $exists))
				{
					$list[$key]['is_local'] = 1;
				}
			}
			$data['list'] = $list;
			//file_put_contents('./cache/debug.txt', var_export($list,1));
			//总数
			$this->curl->initPostData();
			$this->curl->addRequestData('a', 'count');
			$this->add_search_data($data['search']);
			$count = $this->curl->request($default['remote_file']);
			$data['page']['total_num'] = intval($count['total']);
			$data['page']['total_page'] = ceil($data['page']['total_num']/$data['page']['page_num']);
		}
		//节点默认数据
		$this->curl->initPostData();
		$node = $this->curl->request($default['remote_node_file']);
		
		if($node)
		{
			$data['node'] = $node;
		}
		$this->json_output($data);
	}
	public function add_search_data($data = array())
	{
		if($data)
		{
			foreach($data as $field=>$value)
			{
				$this->curl->addRequestData($field, $value);
			}
		}
	}
	public function json_output($data)
	{
		if($data && is_array($data) && !empty($data))
		{
			echo json_encode($data);
			exit;
		}
		exit('[]');
	}
	public function childs()
	{
		$cloud_id = $this->initdata['mid'];
		$cloud = $this->default;
		$this->curl->setUrlHost($cloud['remote_host'], $cloud['remote_dir']);
		//初始化
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('fid', intval($this->input['fid']));
		$childs = $this->curl->request($cloud['remote_node_file']);
		$this->json_output($childs);
	}
	protected function cloud_data()
	{
		$where = '';
		if($this->initdata['cloud_id'])
		{
			$where .=  ' AND cloud_id = ' . $this->initdata['cloud_id'];
		}
		if($this->initdata['site_id'])
		{
			$where .=  ' AND site_id = ' . $this->initdata['site_id'];
		}
		
		$q = $this->db->query('SELECT id,custom_appkey,name FROM ' . DB_PREFIX . 'cloud_site WHERE 1');
		$site_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$site_info[$row['id']] = $row;
		}
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud WHERE module_id = ' . $this->initdata['mid'] . ' AND is_close = 0' . $where;
		$cloud_data = array();
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['custom_appkey'] = $site_info[$row['site_id']]['custom_appkey'];
			unset($site_info[$row['site_id']]['custom_appkey']);
			$cloud_data[$row['cloud_id']] = $row;
		}
		return $cloud_data;
	}
	
	public function getSite()
	{
		$q = $this->db->query('SELECT id,name FROM ' . DB_PREFIX . 'cloud_site WHERE 1');
		$site_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$site_info[$row['id']] = $row;
		}
		$this->json_output($site_info);
	}
	
	public function _getSite()
	{
		$q = $this->db->query('SELECT id,name FROM ' . DB_PREFIX . 'cloud_site WHERE 1');
		$site_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$site_info[$row['id']] = $row;
		}
		return $site_info;
	}
	public function search()
	{
		$search = array('cloud_id' => $this->default['cloud_id']);
		if(!empty($this->input['info']))
		{
			foreach ($this->input['info'] as $val)
			{
				if($val['name'] != 'cloud_id')
				{
					$search[$val['name']] = $val['value'];
				}
			}
		}
		if($this->search_maps[$this->app_uniqueid])
		{
			foreach($this->search_maps[$this->app_uniqueid] as $from=>$to)
			{
				$search[$to] = $search[$from];
				unset($search[$from]);
				switch($this->app_uniqueid)
				{
					//图集坐兼容处理
					case 'tuji':
						{
							//状态值切换匹配相应应用
							if($from == 'status_search')
							{
								if($search['tuji_status'] == 1)
								{
									$search['tuji_status'] = 0;
								}
								if($search['tuji_status'] == 2)
								{
									$search['tuji_status'] = -1;
								}
								if($search['tuji_status'] == 3)
								{
									$search['tuji_status'] = 1;
								}
								if($search['tuji_status'] == 4)
								{
									$search['tuji_status'] = 2;
								}
							}
							break;
						}
					case 'livmedia':
						{
							//状态值切换匹配相应应用
							if($from == 'status_search')
							{
								if($search['trans_status'] == 1)
								{
									$search['trans_status'] = -2;
								}
								if($search['trans_status'] == 2)
								{
									$search['trans_status'] = 1;
								}
								if($search['trans_status'] == 3)
								{
									$search['trans_status'] = 2;
								}
								if($search['trans_status'] == 4)
								{
									$search['trans_status'] = 3;
								}
							}
							break;
						}
				}
			}
		}
		if($this->input['sort_id'])
		{
			$search['_id'] = intval($this->input['sort_id']);
		}
		return $search;
	}
	//获取访问令牌
	public function access($cloud = array(), $get_user=false)
	{
		$url = ltrim($cloud['authapi'], 'http://');
		$url = rtrim($cloud['authapi'], '/');
		
		$url = 'http://' .  $url  . '/' . 'get_access_token.php';
		if(!$get_user)
		{
			$data = array(
				'appid'=>$cloud['appid'],
				'appkey'=>$cloud['appkey'],
				'username'=>$cloud['username'],
				'password'=>urlencode(hg_encript_str($cloud['pwd'],false,$cloud['custom_appkey'])),
				'a'=>'show',
			);
		}
		else
		{
			$data = array(
				'appid'=>$cloud['appid'],
				'appkey'=>$cloud['appkey'],
				'access_token'=>$cloud['access_token'],
				'a'=>'get_user_info',
			);
		}
		$data_str = '';
		foreach ($data AS $k => $v)
		{
			if(!$v)continue;
			$data_str .= $k . '=' .  $v . '&';
		}
		$data_str = rtrim($data_str,'&');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$response = json_decode($response,1);
		if($response['ErrorCode'])
		{
			if($response['ErrorCode'] == 'NO_ACCESS_TOKEN')
			{
				//防止循环
				$this->request_times++;
				if($this->request_times > 3)
				{
					$this->cloud_error('请求超出最大次数');
				}
				$response[0] = $this->access($cloud);
			}
			else
			{
				$this->cloud_error($response['ErrorCode'].$response['ErrorText']);
			}
		}
		$response = $response[0];
		if(!$get_user)
		{
			$this->db->query('UPDATE ' . DB_PREFIX . 'cloud SET access_token = "' . $response['token'] . '" WHERE cloud_id='.intval($cloud['cloud_id']));
		}
		return $response;
	}
	
	public function local()
	{
		$default = $this->default;
	//	hg_pre($default);exit;
		$detail = array();
		$content_id = $this->input['cid'];
		if(!$content_id)
		{
			$this->cloud_error("无效内容！");
		}
		$content_id = array_filter(explode(',', urldecode($content_id)));
		foreach($content_id as $cid)
		{
			//file_put_contents('./cache/debug.txt', var_export($cid,1), FILE_APPEND);
			$this->curl->setUrlHost($default['remote_host'], $default['remote_dir']);
			//初始化
			$this->curl->initPostData();
			$this->curl->addRequestData('a', 'detail');
			$this->curl->setToken($default['access_token']);
			$this->curl->addRequestData('cloud_id', $this->default['cloud_id']);
			$this->curl->addRequestData('id', $cid);
			//列表默认数据
			$detail = $this->curl->request($default['remote_file']);
			$detail = $this->check_return_data($detail);
			
			if(!$detail)
			{
				$this->cloud_error('获取详细信息失败，可能数据已经被删除！');
			}
			if($_GET['debug'])
			{
				print_r($detail);
			}
			$formdata = array();
			if(is_array($this->maps[$this->app_uniqueid]))
			{
				foreach($this->maps[$this->app_uniqueid] as $from=>$to)
				{
					if($to == 1)
					{
						$formdata[$from] = $to;
					}
					else
					{
						$formdata[$to] = $detail[$from];
					}					
				}
				$formdata['column_id'] = urldecode($this->input['column_id']);
				if(!$formdata['column_id'])
				{
					$formdata['column_id'] = '';
					$detail['column_id'] = is_array($detail['column_id']) ? $detail['column_id'] : explode(',', $detail['column_id']);
					if($detail['column_id'])
					{
						foreach($detail['column_id'] as $c)
						{
							if($this->publish_maps[$this->app_uniqueid][$c])
							{
								$formdata['column_id'] .= $this->publish_maps[$this->app_uniqueid][$c] . ',';
							}
						}
					}
					$formdata['column_id'] = trim($formdata['column_id'], ',');
				}
				if($_GET['debug'])
				{
					echo $formdata['column_id'];exit('debug');
				}
				switch($this->app_uniqueid)
				{
					case 'news':
						{
							$formdata['indexpic'] = hg_bulid_img($formdata['indexpic']);
							$formdata['sort_id'] = intval($this->input['sort_id']);
							break;
						}
					case 'tuji':
						{
							if($formdata['pics'])
							{
								$formdata['pic_links'] = '';
								$briefs = '';
								foreach ($formdata['pics'] as $pid=>$pic)
								{
									if($formdata['index_id'] == $pid)
									{
										$formdata['pic_links'] = hg_bulid_img($pic) . "\n" . $formdata['pic_links'];
										$briefs = $pic['description'] . '|||' . $briefs;
									}
									else
									{
										$formdata['pic_links'] .= hg_bulid_img($pic) . "\n";
										$briefs .= $pic['description'] . '|||';
									}
								}
							}
							$formdata['tuji_sort_id'] = intval($this->input['sort_id']);
							$formdata['briefs'] = $briefs;
							unset($formdata['index_id']);
							unset($formdata['pics']);
							break;
						}
					case 'livmedia':
						{
							$formdata['vod_sort_id'] = intval($this->input['sort_id']);
							break;
						}
				}
			}

			if(!$formdata)
			{
				$this->cloud_error('无效表单数据');
			}
			
		//	hg_pre($this->settings['App_' . $this->app_uniqueid]);
		//	hg_pre($formdata);
		//	exit;
			$this->curl->setUrlHost($this->settings['App_' . $this->app_uniqueid]['host'], $this->settings['App_' . $this->app_uniqueid]['dir'] . 'admin/');
			$this->curl->initPostData();
			//file_put_contents('./cache/adebug.txt', var_export($formdata,1), FILE_APPEND);
			foreach ($formdata as $name=>$val)
			{
				$this->curl->addRequestData($name, $val);
			}
			$this->curl->addRequestData('html', 'true');
			$this->curl->setToken($this->user['token']);
			$this->curl->addRequestData('a', 'create');
			$return = $this->curl->request($default['remote_update_file']);

			//file_put_contents('./cache/debug.txt', var_export($return,1), FILE_APPEND);
			$return = $this->check_return_data($return);
		
			if($return)
			{
				$this->db->query('REPLACE INTO ' . DB_PREFIX . 'cloud_record SET mid='.$this->initdata['mid'].',cloud_id='.$this->default['cloud_id'].',content_id='.$cid);
			}
			else
			{
				$this->cloud_error('本地化数据失败');
			}
		}
		exit(json_encode($content_id));
	}
	private function check_return_data($data)
	{
		if(!is_array($data))
		{
			$data = json_decode($data,1);
		}
		if($data['ErrorCode'])
		{
			$this->cloud_error($data['ErrorCode'].$data['ErrorText']);
		}
		return $data[0];
	}
	protected function record($id_strings = '')
	{
		$record = array();
		$id_strings = trim($id_strings, ',');
		if(!$id_strings)
		{
			return $record;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud_record WHERE mid='.$this->initdata['mid'] . ' AND cloud_id='.$this->default['cloud_id'] . ' AND content_id IN('.$id_strings.')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$record[] = $row['content_id'];
		}
		return $record;
	}
	function cloud_error($msg='')
	{
		exit(json_encode(array('error'=>1, 'msg'=>$msg)));
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>