<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
define('ROOT_DIR', './');
define('WORKBENCH_URL', 'localhost/livworkbench');//设置wokrbenchRUL
define('WITH_DB',true);
define('SCRIPT_NAME', 'auto_cloud');
define('WITHOUT_LOGIN', true);//无需登陆配置
require('./global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class auto_cloud extends uiBaseFrm
{
	private $cloud;
	private $cloud_config;
	private $request_times = 0;
	private $update_config=false;
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl();
		$this->curl->setmAutoInput(0);
		$this->curl->setSubmitType('get');
		$this->cloud_config=$this->cloud_config();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	/**
	 *
	 * 入口方法 ...
	 */
	public function show()
	{
		if($this->cloud_config&&is_array($this->cloud_config))
		foreach ($this->cloud_config as $val)
		{
			$this->curl->initPostData();
			if(!$val['access_token'])
			{
				$cloud_user = $this->access($val);
			}
			else
			{
				$cloud_user = $this->access($val, true);
			}
			foreach ($val['auto_config']['cond'] as $keys => $vals)
			{
				$new_auto_config= !$keys ? $val['auto_config'] : $new_auto_config;
				$search=$this->search($vals['c_cond']);

				$this->curl->setToken($cloud_user['token']);
				$this->curl->setUrlHost($val['remote_host'], $val['remote_dir']);
				//初始化
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'show');
				$this->add_search_data($search);
				//$this->curl->addRequestData('orderby_id', 1);//按id ASC排序
				$this->curl->addRequestData('max_id', $vals['max_id']);//每次查出开始条数
				$this->curl->addRequestData('count', $val['auto_config']['config']['count']);//每次查出结束条数
				//列表默认数据
				$list = $this->curl->request($val['remote_file']);

				if($list && is_array($list))
				{
					$id_strings = '';
					foreach($list as $l)
					{
						$id_strings .= $l['id'] . ',';
					}
					$record=$this->record($val['module_id'],$val['cloud_id'], $id_strings);
				}
				$c_id='';//需要插入的id;
				$c_id_array=array();
				$insert_id_error=array();
				if($list&&is_array($list))
				{
					foreach ($list as $k=>$v)
					{
						if($v['id']>$vals['max_id']){//判断最大id是否变化
							$vals['max_id']=$v['id'];
							$new_auto_config['cond'][$keys]['max_id'] = $vals['max_id'];
							$this->update_config = true;
						}
						if(array_key_exists($v['id'],$record))//过滤已经插入过的id.
						{
							//	$new_auto_config['cond'][$keys]['count']++;
							continue;
						}
						if(in_array($v[$vals['field']['status']], $vals['status']))
						{
							$c_id .= $v['id'].',';//提取需要插入的内容id.
							$c_id_array[]=$v['id'];
						}
						else {
							$insert_id_error[]=array('cloud_id'=>$val['cloud_id'],'mid'=>$val['module_id'],'content_id'=>$v['id'],'status'=>$v[$vals['field']['status']]);
						}
					}
				}
				//获取符合插入条件的数据结束.
				if($c_id)//调用云平台手动插入接口.
				{
					$authapi = array(
						'authapi'=>$this->settings['App_auth']['host'] .'/'. $this->settings['App_auth']['dir'],
						'appid'=>APPID,
						'appkey'=>APPKEY,
						'access_token'=>$val['localaccesstoken'],
						'username'=>$val['localusername'],
						'pwd'=>$val['localuserpwd'],
						'cloud_id'=>$val['cloud_id'],
					);
					if(!$val['localaccesstoken'])
					{
						$localuser = $this->access($authapi, false, 'localaccesstoken');
					}
					else
					{
						$localuser = $this->access($authapi, true,'localaccesstoken');
					}
					$ret=array();
					$c_id=trim($c_id,',');
					//hg_pre($c_id.',1');
					$this->curl->initPostData();
					$this->curl->setUrlHost(WORKBENCH_URL,'');
					$this->curl->setToken($localuser['token']);
					$this->curl->addRequestData('mid', $val['module_id']);
					$this->curl->addRequestData('cloud_id', $val['cloud_id']);
					$this->curl->addRequestData('a', 'local');
					$this->curl->addRequestData('sort_id', $vals['to_sort_id']);
					$this->curl->addRequestData('column_id', $vals['column_id']);
					$this->curl->addRequestData('cid', $c_id);
					$ret[$keys]=$this->curl->request('cloud.php');
					$id_error=array();
					$insert_id = array();
					$insert_id=$this->record($val['module_id'],$val['cloud_id'] , $c_id);//查询提交的id是否成功,用于获取最大id和未成功的id存入插入错误表.
					if($insert_id&&is_array($insert_id))
					{
						$id_error=array_diff($c_id_array, $insert_id);//对比出插入出错的id;
						$this->update_config=true;
					}
					else {
						$id_error=$c_id_array;
					}
					if($id_error&&is_array($id_error))
					{
						foreach ($id_error as $v)
						{
							$insert_id_error[]=array('cloud_id'=>$val['cloud_id'],'mid'=>$val['module_id'],'content_id'=>$v,'status'=>$vals['status']);//符合插入规则,但是插入失败
						}
					}
				}
			}
			$this->insert_id_error($insert_id_error);
			$this->update_cloud_config($val['cloud_id'], $new_auto_config);//插入数据记录
			$this->update_config=false;
		}
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

	public function search($search)
	{
		if(!empty($search['sort_id']))
		{
			$search['_id'] = intval($search['sort_id']);
			unset($search['sort_id']);
		}
		return $search;
	}

	private  function cloud_config()
	{
		$where = '';
		if($cloud_id)
		{
			//$where =  ' AND cloud_id = ' . $cloud_id;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud WHERE 1 AND is_close = 0 AND is_auto=1' . $where;
		$cloud_data = array();
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			if ($row['auto_config'])
			{
				$row['auto_config']=unserialize($row['auto_config']);
			}
			else
			{
				continue;
			}
			$cloud_data[$row['cloud_id']] = $row;
		}
		return $cloud_data;
	}
	/**
	 *
	 *暂时用于手动生成配置,生成成功后手动复制到数据库
	 */
	public function make_auto_config()
	{
		$cloud_config=array (
		'config' =>array(//全局配置
						'count' => 2,//每次取出条数
						'expired_del'=>72,//删除间隔时间.0为永不删除错误记录
						'error_count'=>2//每次处理粗错条数
					),
		'cond' =>array(//条件配置,例如:实现不同分类插入不同分类数据
			'0' => array ( //配置1
				'c_cond' => array (//查找条件.
							 'sort_id' => 2,//需要取出数据的分类id,可以添加其它配置.但需要接口支持
							),
				'status' => array(2,1),//审核状态.
				'field'=>array(//字段兼容,为了兼容不同的应用有不同的字段名
							'status'=>'status_display',//例如视频里的status叫status_display,而其它应用叫其它名字
							'sort_id'=>'vod_leixing'//例如视频里的sort_id叫vod_leixing,而其它应用叫其它名字
							) ,
				'to_sort_id' => 168,//需要插入的分类id
				'max_id' => 0,//当前已经执行的id,如果非第一次配置此条规则.则此处一定要填写老配置已经记录的id
				)
			)
		);
		$this->update_cloud_config(intval($this->input['cloud_id']), $cloud_config);
	}
	//获取访问令牌
	public function access($cloud = array(), $get_user=false, $field = 'access_token')
	{
		$url = ltrim($cloud['authapi'], 'http://');
		$url = rtrim($cloud['authapi'], '/');
		$url = 'http://' .  $url  . '/' . 'get_access_token.php';
		//hg_pre(hg_encript_str($cloud['pwd'],false));exit;
		if(!$get_user)
		{
			$data = array(
				'appid'=>$cloud['appid'],
				'appkey'=>$cloud['appkey'],
				'username'=>$cloud['username'],
				'password'=>hg_encript_str($cloud['pwd'],false),
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
			if(!$v)
			{
				continue;
			}
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
				$response[0] = $this->access($cloud, false, $field);
			}
			else
			{
				$this->cloud_error($response['ErrorCode'].$response['ErrorText']);
			}
		}
		$response = $response[0];
		if(!$get_user || $this->request_times)
		{
			$this->db->query('UPDATE ' . DB_PREFIX . 'cloud SET '.$field. '="' . $response['token'] . '" WHERE cloud_id='.intval($cloud['cloud_id']));
		}
		return $response;
	}

	protected function record($mid,$cloud_id, $id_strings = '')
	{
		$record = array();
		$id_strings = trim($id_strings, ',');
		if(!$id_strings)
		{
			return $record;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud_record WHERE cloud_id='.$cloud_id . ' AND mid='.$mid . ' AND content_id IN('.$id_strings.')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$record[$row['content_id']] = $row['content_id'];
		}
		return $record;
	}
	protected function update_cloud_config($cloud_id,$cloud_config)
	{
		$this->update('cloud', array('auto_config'=>serialize($cloud_config)), array('cloud_id'=>intval($cloud_id)));
		return true;
	}
	/**
	 *
	 * 记录出错id和模块 ...
	 * @param unknown_type $insert_id_error
	 */
	private function insert_id_error($insert_id_error)
	{
		if($insert_id_error&&is_array($insert_id_error))
		{
			foreach ($insert_id_error as $v)
			{
				$v['create_time']=$v['update_time']=TIMENOW;
				$this->create('cloud_error', $v);
			}
			return true;
		}
		return false;
	}
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	private function count($cloud_id,$mid,$content_id)
	{
		$condition=' AND cr.cloud_id = '.$cloud_id.' AND cr.mid='.$mid.' AND cr.content_id IN('.$content_id.')';
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'cloud_record cr WHERE 1 '.$condition;
		$total = $this->db->query_first($sql);
		return $total;
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
	function cloud_error($msg='')
	{
		exit(json_encode(array('error'=>1, 'msg'=>$msg)));
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>