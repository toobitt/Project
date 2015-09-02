<?php
class recycle extends InitFrm
{
	var $curl;
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($cond)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "app WHERE  father != 0"; //ȡģ��
		$app = $this->db->fetch_all($sql);
		$sql = "SELECT r.*,c.* FROM " . DB_PREFIX . "recycle r " .
				"LEFT JOIN " . DB_PREFIX . "content c " .
						"ON r.id=c.recycleid " .
				"WHERE 1 " . $cond;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{		
			foreach($app as $k => $v)
			{
				if($v['bundle'] == $row['app_mark'])
				{
					$row['app_mark'] = $v['name'];
				}
			}
			$row['time'] = date('Y-m-d H:i',$row['time']);
			$info[$row['id']] = $row;
		}
		return $info;
	}

	public function count($cond)
	{
		$sql="SELECT count(*) AS total FROM " . DB_PREFIX . "recycle r LEFT JOIN " . DB_PREFIX . "content c ON r.id=c.recycleid WHERE 1 " . $cond;
		$q = $this->db->query_first($sql);
		return $q;
	}


   /**
   *	�ӻ���վɾ��
   */
	public function delete()
	{
		if(!$this->input['id'])
		{
			return false;
		}
		$id = urldecode($this->input['id']); //֧����
		$sql = "SELECT r.*,c.* FROM " . DB_PREFIX . "recycle r 
				LEFT JOIN " . DB_PREFIX . "content c 
					ON r.id=c.recycleid 
				WHERE r.id IN(" . $id . ")";		
		$ret = $this->db->query($sql);
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		while($row = $this->db->fetch_array($ret))
		{
			$row['content'] = unserialize($row['content']);
			$cid = $row['cid'];
			if($this->settings['App_' . $row['app_mark']])
			{			
				$this->curl = new curl($this->settings['App_' . $row['app_mark']]['host'],$this->settings['App_' . $row['app_mark']]['dir'] . 'admin/');
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'delete_comp');
				$this->curl->addRequestData('cid', $cid);
				$this->array_to_add('content' , $row['content']);
				$this->curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
				$filename = '';
				switch($row['app_mark'])
				{
					case 'vote':
						$filename = 'vote_question';
						break;
					case 'livmedia':
						$filename = 'vod';
						break;
					default:
						$filename = $row['app_mark'];
						break;
				}				
				$q = $this->curl->request($filename . '_update.php');
			}		
		}
		$sql = "DELETE FROM " . DB_PREFIX . "recycle WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "content WHERE recycleid IN(". $id .")";
		$this->db->query($sql);
		return $id;
	}
    

    public function add_recycle()
	{	
		$data = array(
			'title' => $this->input['title'],
			'app_mark' => $this->input['app_mark'],
			'module_mark' => $this->input['module_mark'],
			'delete_people' => $this->input['delete_people'],
			'time' => $this->input['time'],
			'ip' => $this->input['ip'],
			'cid' => intval($this->input['cid']),
			'catid' => intval($this->input['catid']),
		);
			
		$content = addslashes(serialize($this->input['content']));	//对双引号添加进行转义,content含有json串
					
//		$sql = "SELECT * FROM " . DB_PREFIX ."app_settings where bundle_id ='" . $data['app_mark'] ."' AND module_id ='" . $data['module_mark'] ."' AND var_name='ISOPEN'"; 
//		$r = $this->db->query_first($sql);
//		if(empty($r))
//		{
//			$sql="SELECT * FROM " . DB_PREFIX ."settings where var_name='ISOPEN'";
//			$q = $this->db->query_first($sql);
//			if($q['value'])
//			{
//				$sql = "INSERT INTO " . DB_PREFIX . "recycle SET ";
//				$space = '';
//				foreach($data as $k => $v)
//				{
//					$sql .= $space . $k ."='" . $v . "'";
//					$space = ',';
//				}
//				$this->db->query($sql);
//				$recycleid=$this->db->insert_id();
//				$sql = "INSERT INTO " . DB_PREFIX ."content(recycleid,content) VALUES('". $recycleid ."','". $content ."')";
//				$this->db->query($sql);
//				return array('data' => $data,'is_open' => true,'sucess' =>true ,'msg' => '放入回收站成功');
//			}
//			else
//			{
//				return array('data' => $data,'is_open' => false,'sucess' => true,'msg' => '回收站没有开启');
//			}
//		}
//		else
//		{
			if(ISOPEN)
			{
				$sql = "INSERT INTO " . DB_PREFIX . "recycle SET ";
				$space = '';
				foreach($data as $k => $v)
				{
					$sql .= $space . $k ."='" . $v . "'";
					$space = ',';
				}
				$this->db->query($sql);
				$recycleid=$this->db->insert_id();
				$sql = "INSERT INTO " . DB_PREFIX ."content(recycleid,content) VALUES('". $recycleid ."','". $content ."')";
				$this->db->query($sql);
				return array('data' => $data,'is_open' => true,'sucess' =>true ,'msg' => '放入回收站成功');
			}
			else
			{
				return array('data' => $data,'is_open' => false,'sucess' => true,'msg' => '回收站没有开启');
			}
//		}
	}


	public function recover_recycle()
	{		
		if(empty($this->input['id']))
		{
			return false;
		}
		$id = urldecode($this->input['id']);
		$sql = "SELECT r.*,c.* FROM " . DB_PREFIX . "recycle r " .
				"LEFT JOIN " . DB_PREFIX . "content c " .
						"ON r.id=c.recycleid " .
				"WHERE r.id IN(" . $id . ")";
		$ret = $this->db->query($sql);
		$info=array();
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		while($row = $this->db->fetch_array($ret))
		{
			$row['content'] = unserialize($row['content']);
			if($this->settings['App_' . $row['app_mark']])
			{
				$this->curl = new curl($this->settings['App_' . $row['app_mark']]['host'],$this->settings['App_' . $row['app_mark']]['dir'] . 'admin/');
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'recover');			
				$this->curl->addRequestData('html',true);
				$this->array_to_add('content[0]' , $row['content']);
				$this->curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
				$filename = '';
				switch($row['app_mark'])
				{
					case 'vote':
						$filename = 'vote_question';
						break;
					case 'livmedia':
						$filename = 'vod';
						break;
					case 'cheapbuy':
						$filename = 'product';
						break;
					default:
						$filename = $row['app_mark'];
						break;
				}
				$q = $this->curl->request($filename. '_update.php');
			}
			else
			{
				return false;
			}			
		}
		if($q[0])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "recycle WHERE id IN(" . $id .")";
			$this->db->query($sql);
			$sql = "DELETE FROM " . DB_PREFIX . "content WHERE recycleid IN(" . $id .")";
			$this->db->query($sql);
			return $id;
		}
		else 
		{
			return false;
		}
	}


    public function get_app($data_limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "app WHERE father = 0" . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	public function get_module($data_limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."app WHERE  father != 0" . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	public function get_some_module($father, $data_limit = '')
	{
		$sql = "SELECT * FROM " .DB_PREFIX ."app WHERE  father =" . $father . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	
	public function array_to_add($str , $data)
	{
		global $curl;
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if(is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
	}
}
?>