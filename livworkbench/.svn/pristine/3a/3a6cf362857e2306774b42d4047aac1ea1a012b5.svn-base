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
define('SCRIPT_NAME', 'auto_cloud_error');
define('WITHOUT_LOGIN', true);//无需登陆配置
require('./global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class auto_cloud_error extends uiBaseFrm
{
	private $cloud;
	private $cloud_config;
	private $c_config;
	private $cloud_user=array();
	private $request_times = 0;
	private $expired_del=0;
	private $error_count=0;
	private $cloud_id=0;
	private $mid=0;
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
			$this->cloud_id=$val['cloud_id']?intval($val['cloud_id']):0;//平台id
			$this->mid=$val['module_id']?intval($val['module_id']):0;//模块id
			$this->expired_del=$val['expired_del']?intval($val['expired_del']):0;//过期删除时间
			$this->error_count=$val['error_count']?intval($val['error_count']):0;//每次处理错误条数
			$this->delete_id_error();//删除掉已经过期的错误.
			$this->curl->initPostData();
			if(!$val['access_token'])
			{
				$this->cloud_user = $this->access($val);
			}
			else
			{
				$this->cloud_user = $this->access($val, true);
			}
			$select_id_error=array();
			$select_id_error=$this->select_id_error();
			$list = $this->select_id_curl($val,$select_id_error);//查询数据列表.
			foreach ($val['auto_config']['cond'] as $keys => $vals)
			{
				$id_strings='';
				$id_strings_array=array();
				$update_id_error=array();
				//获取符合插入条件的数据
				if($list && is_array($list))
				{
					$id_strings = '';
					foreach($list as $l)
					{
						if($vals['c_cond']['sort_id']==$l[$vals['field']['sort_id']])
						{

							if(in_array($l[$vals['field']['status']], $vals['status']))//如果查出的状态符合则记录提交.
							{
								$id_strings .= $l['id'] . ',';
								$id_strings_array[]=$l['id'];
							}
							else {
									
								$update_id_error[]=array('content_id'=>$l['id'],'status'=>$l[$vals['field']['status']]);
							}
						}
					}
				}
				//获取符合插入条件的数据结束.
				if($id_strings)//调用云平台手动插入接口.
				{
					$authapi = array(
						'authapi'=>$this->settings['App_auth']['host'] .'/'. $this->settings['App_auth']['dir'],
						'appid'=>APPID,
						'appkey'=>APPKEY,
						'access_token'=>$val['localaccesstoken'],
						'username'=>$val['localusername'],
						'pwd'=>$val['localuserpwd'],
						'cloud_id'=>$this->cloud_id,
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
					$id_strings=trim($id_strings,',');
					//hg_pre($id_strings.',1');
					$this->curl->initPostData();
					$this->curl->setUrlHost(WORKBENCH_URL,'');
					$this->curl->setToken($localuser['token']);
					$this->curl->addRequestData('mid', $this->mid);
					$this->curl->addRequestData('cloud_id', $this->cloud_id);
					$this->curl->addRequestData('a', 'local');
					$this->curl->addRequestData('sort_id', $vals['to_sort_id']);
					$this->curl->addRequestData('column_id', $vals['column_id']);
					$this->curl->addRequestData('cid', $id_strings);
					$ret[$keys]=$this->curl->request('cloud.php');
					$id_error=array();
					$insert_id = array();
					$insert_id=$this->record($id_strings);//查询提交的id是否成功,用于获取最大id和未成功的id存入插入错误表.
					if($insert_id&&is_array($insert_id))
					{
						$id_error=array_diff($id_strings_array, $insert_id);//对比出插入出错的id;
					}
					else {
						$id_error=$id_strings_array;
					}
					if($id_error&&is_array($id_error))
					{
						foreach ($id_error as $v)
						{
							$update_id_error[]=array('content_id'=>$v,'status'=>$vals['status']);//符合插入规则,但是插入失败
						}
					}
				}
			}
			$this->update_id_error($update_id_error);//更新此次未成功的记录
			$this->delete_id_error($insert_id);//删除掉此次已成功.
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

	protected function record($id_strings = '')
	{
		$record = array();
		$id_strings = trim($id_strings, ',');
		if(!$id_strings)
		{
			return $record;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'cloud_record WHERE cloud_id='.$this->cloud_id . ' AND mid='.$this->mid . ' AND content_id IN('.$id_strings.')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$record[$row['content_id']] = $row['content_id'];
		}
		return $record;
	}
	/**
	 *
	 * 更新出错id和模块 ...
	 * @param array $update_id_error
	 */
	private function update_id_error($update_id_error)
	{
		if($update_id_error&&is_array($update_id_error))
		{
			$update=array();
			$update['update_time']=TIMENOW;
			$update['read_num']=1;
			$where=array();
			$where['cloud_id']=$this->cloud_id;
			$where['mid']=$this->mid;
			foreach ($update_id_error as $v)
			{
				$update['status']=$v['status'];
				$where['content_id']=$v['content_id'];
				$this->update('cloud_error', $update,$where,array('read_num'=>0));
			}
			return true;
		}
		return false;
	}
	/**
	 *
	 * 删除掉已经过期或者成功的id ...
	 * @param array $insert_id 将要删除掉的id,如果为空,则仅删除过期的.
	 */
	private function delete_id_error($insert_id=array())
	{
		$insert_id_s='';
		if($insert_id&&is_array($insert_id))
		{
			$insert_id=array_keys($insert_id);
			$insert_id_s=implode(',', $insert_id);
				
		}
		if($insert_id_s)
		{
			$this->delete('cloud_error', array('cloud_id'=>$this->cloud_id,'mid'=>$this->mid,'content_id'=>$insert_id_s));
		}
		elseif($this->expired_del>0) {
			$expired_del=TIMENOW-($this->expired_del*60*60);
			$sql='DELETE FORM '.DB_PREFIX.'cloud_error WHERE create_time <='.$expired_del.' AND cloud_id='.$cloud_id.' AND mid='.$mid;
			$this->db->query($sql);
		}
	}
	private function select_id_error()
	{
		$data=array();
		$limit='';
		if($this->error_count>0)
		{
			$limit=' LIMIT 0 , '.$this->error_count;
		}
		$orderby=' ORDER BY update_time ASC';
		$sql='SELECT content_id FROM '.DB_PREFIX.'cloud_error WHERE cloud_id = '.$this->cloud_id.' AND mid='.$this->mid.$orderby.$limit;
		$query=$this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$data[]=$row['content_id'];
		}
		return $data;
	}

	/**
	 * 更新数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param Array $idsArr 条件
	 * @param array $flag 包含的key将启用算术符
	 */
	private function update($table, $data, $idsArr, $flag = array())
	{
			
		if (!$table || !is_array($data) || !is_array($idsArr)) return false;
		$fields = '';
		$math=array(
		'+','-','*','/','%','^','!'
		);
		foreach ($data as $k => $v)
		{
			if (array_key_exists($k, $flag))
			{
				$v = $v > 0 ? $math[$flag[$k]] . $v : $v;
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
		//echo $sql;exit;
		$res=$this->db->query($sql);
		if ($idsArr&&$res)
		{
			return $idsArr;
		}
		return false;

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
	 * 
	 * Enter description here ...
	 * @param unknown_type $select_id_error
	 */
	private function select_id_curl($c_config,$select_id_error=array())
	{
		$list=array();
		if ($select_id_error)
		{
			$record=$this->record(@implode(',', $select_id_error));
			if($record)
			{
				$select_id_error=array_diff($select_id_error, $record);//过滤已经成功的id
			}
			if($select_id_error)
			{
				$this->curl->setToken($this->cloud_user['token']);
				$this->curl->setUrlHost($c_config['remote_host'], $c_config['remote_dir']);
				//初始化
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'show');
				$this->curl->addRequestData('id', implode(',',$select_id_error));//每次查出开始条数
				$this->curl->addRequestData('count', count($select_id_error));//为了发出多少条数据查回多少条
				//列表默认数据
				$list = $this->curl->request($c_config['remote_file']);
			}
		}
		return $list;
	}
	function cloud_error($msg='')
	{
		exit(json_encode(array('error'=>1, 'msg'=>$msg)));
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>