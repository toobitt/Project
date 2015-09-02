<?php
/***************************************************************************

* $Id: sms_server.class.php 44118 2015-02-06 08:32:21Z youzhenghuan $

***************************************************************************/
class smsServer extends InitFrm
{
	public function __construct()
	{
		parent::__construct();

	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition, $offset = 0, $count = 20, $orderby = '')
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = $orderby ? $orderby : " ORDER BY order_id DESC ";

		$sql = "SELECT * FROM " . DB_PREFIX . "sms_server ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;

		$q = $this->db->query($sql);

		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['logo']		= unserialize($row['logo']);

			$return[] = $row;
		}

		return $return;
	}

	public function detail($id)
	{
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1 ";
		}
		else
		{
			$condition = " WHERE id IN (" . $id .")";
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "sms_server " . $condition;
		$row = $this->db->query_first($sql);

		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['logo']		= unserialize($row['logo']);

			return $row;
		}
		return false;
	}

	public function count($condition = '')
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "sms_server WHERE 1 " . $condition;
		$return = $this->db->query_first($sql);
		return $return;
	}

	public function create($data)
	{
		$sql = "INSERT INTO " . DB_PREFIX . "sms_server SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);

		$data['id'] = $this->db->insert_id();

		if ($data['id'])
		{
			return $data;
		}
		return false;
	}

	public function update($data)
	{
		$sql = "UPDATE " . DB_PREFIX . "sms_server SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$sql .= " WHERE id = " . $data['id'];

		$this->db->query($sql);

		if ($data['id'])
		{
			return $data;
		}
		return false;
	}

	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "sms_server WHERE id IN (" . $id . ")";

		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}

	public function mobile_verifycode_create($data)
	{
		$sql = "REPLACE INTO " . DB_PREFIX . "mobile_verifycode SET ";
		$space = '';
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		if ($this->db->query($sql))
		{
			return $data;
		}
		return false;
	}

	public function mobile_verifycode_delete($mobile, $verifycode)
	{
		$binary = '';//不区分大小写
		if(defined('IS_VERIFYCODE_BINARY') && IS_VERIFYCODE_BINARY)//区分大小写
		{
			$binary = 'binary ';
		}

		$sql = "DELETE FROM " . DB_PREFIX . "mobile_verifycode WHERE mobile ='" . $mobile . "' AND " . $binary . " verifycode = '" . $verifycode . "'";

		if ($this->db->query($sql))
		{
			return true;
		}
		return false;
	}

	public function get_sms_server_info($condition, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "sms_server WHERE 1 " . $condition;
		$q = $this->db->query($sql);

		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['create_time'])
			{
				$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			}

			if ($row['update_time'])
			{
				$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			}

			if ($row['logo'])
			{
				$row['logo']		= unserialize($row['logo']);
			}

			$return[] = $row;
		}
		return $return;
	}

	public function get_verifycode_info($mobile, $verifycode)
	{
		$binary = '';//不区分大小写
		if(defined('IS_VERIFYCODE_BINARY') && IS_VERIFYCODE_BINARY)//区分大小写
		{
			$binary = 'binary ';
		}
		
		$sql = "SELECT verifycode,create_time FROM " . DB_PREFIX . "mobile_verifycode WHERE mobile ='" . $mobile . "' AND " . $binary . " verifycode = '" . $verifycode . "'";
		$return = $this->db->query_first($sql);
		return $return;
	}

	/**
	 * 入素材库
	 * Enter description here ...
	 * @param unknown_type $file
	 * @param unknown_type $id
	 */
	public function add_material($file, $id)
	{
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$mMaterial = new material();
		if (!$mMaterial)
		{
			return false;
		}

		$files['Filedata'] = $file;
		$material = $mMaterial->addMaterial($files, $id);
		$return = array();
		if (!empty($material))
		{
			$return['host'] 	= $material['host'];
			$return['dir'] 		= $material['dir'];
			$return['filepath'] = $material['filepath'];
			$return['filename'] = $material['filename'];
		}

		return $return;
	}

	/**
	 * 短信接口
	 *
	 * $protocol
	 * $host
	 * $apidir
	 *
	 * $accesskey
	 * $secretkey
	 * $mobile
	 * $content
	 */
	public function sms_api($protocol, $host, $apidir, $data)
	{
		require_once ROOT_PATH . 'lib/class/curl.class.php';
		$this->curl = new curl();

		if (!$this->curl)
		{
			return array();
		}

		$this->curl->setUrlHost($host, $apidir);
		$this->curl->setRequestType($protocol);
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');

		if (empty($data))
		{
			return array();
		}

		foreach ($data AS $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}

		$ret = $this->curl->request('');

		return $ret;
	}

	/**
	 * 用于将数组直接用json的方式提交到某一个地址
	 */
	public function curl_json($url, $data = array())
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$response  = curl_exec($ch);
		$head_info = curl_getinfo($ch);
		if($head_info['http_code']!= 200)
		{
			$error = array('result' =>'fail');
			return json_encode($error);
		}
		curl_close($ch);//关闭
		return json_decode($response, true);
	}

	/**
	 * 用于已get方式提交到某一个地址
	 * 返回json
	 */
	public function curl_get($url, $type = 'json')
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$response  = curl_exec($ch);
		$head_info = curl_getinfo($ch);
		if($head_info['http_code']!= 200)
		{
			$error = array('result' =>'fail');
			return json_encode($error);
		}
		curl_close($ch);//关闭
		if ($type == 'json')
		{
			return json_decode($response, true);
		}
		elseif ($type == 'px')
		{
			$response = explode(',', $response);
			if ($response[0] == 0)
			{
				$sucess = '01';
			}
			else
			{
				$sucess = $response[0];
			}
			$result = array(
				'result' => $sucess,	
				'msgid' => $response[1],	
			);
			return $result;
		}
		elseif ($type == 'xml')
		{
			$response = xml2Array($response);
			//
			
			$result = array(
					'result' => $response['result'],
					'msgid' => $response['taskid'],	
				);

			if(!intval($response['result']))
			{
				$result['result'] = '01';
			}
			else
			{
				file_put_contents(CACHE_DIR . $response['taskid'] . '.txt', var_export($response,1));
			}
			return $result;
		}
		elseif ($type == 'ps')
		{
			$result = array();
			parse_str($response,$result);
			$result['taskid'] && $result['msgid'] = $result['taskid'];
			if(!intval($result['result']))
			{
				$result['result'] = '01';
			}
			else
			{
				file_put_contents(CACHE_DIR . $result['taskid'] . '.txt', var_export($result,1));
			}
			return $result;
		}
	}
}

?>