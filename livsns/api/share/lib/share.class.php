<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: news.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
require_once(ROOT_PATH.'lib/class/gdimage.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
define('MOD_UNIQUEID','share_m');//模块标识
class share extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_account($offset,$count,$condition = '')
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plat ".$condition." ORDER BY id DESC LIMIT {$offset},{$count}";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_plat_by_type($appid,$plat_type)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."auth_app WHERE appid=".$appid;
		$app = $this->db->query_first($sql);
		if(!empty($app['platIds']))
		{
			$sql = "SELECT * FROM ".DB_PREFIX."plat WHERE type=".$plat_type." AND id in(".$app['platIds'].") ORDER BY id";
			return $this->db->query_first($sql);
		}
		return false;
	}
	
	public function insert_plataccount($picfiles)
	{
		$data=array(
			'type' => urldecode($this->input['plat_type']),
			'name' => urldecode($this->input['name']),
			'offiaccount' => urldecode($this->input['offiaccount']),
			'akey' => urldecode($this->input['apikey']),
			'skey' => urldecode($this->input['secretkey']),
			'oauthurl' => urldecode($this->input['oauthurl']),
			'shareurl' => urldecode($this->input['shareurl']),
			'userurl' => urldecode($this->input['userurl']),
			'callback' => urldecode($this->input['callback']),
			'accessurl' => urldecode($this->input['accessurl']),
			'followurl' => urldecode($this->input['followurl']),
			'status' => urldecode($this->input['status']),
			'addtime' => time(),
		);
		$sql="INSERT INTO " . DB_PREFIX . "plat SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		$id=$this->db->insert_id();
		
		/*将图片提交到图片服务器*/
		$files['Filedata'] = $picfiles;
		if($files['Filedata'])
		{
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($files,$id,$this->input['module_id'],'img10');
			$img_thumb_info = $this->get_thumb_pic($img_info['filepath'],$img_info['filename']);//去请求一张缩略图
			$data = array(
				'platId' => $id,
				'oldname'=> $files['Filedata']['name'],
				'newname'=> $img_info['filename'],
				'addtime'=> time(),
				'path'=>$img_info['filepath'],
			);
			//判断是否存在封面 如果不存在则以第一幅图片做为封面
			$sql = 'UPDATE '.DB_PREFIX.'plat SET picurl = "'.$data['path'].$data['newname'].'" WHERE id = '.$data['platId'];
			$this->db->query($sql);
			$sql="INSERT INTO " . DB_PREFIX . "pics SET";
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
			$this->db->query($sql);
		}
		return true;
	}
	
	/*获取指定大小的图片*/
	function get_thumb_pic($filepath,$filename,$sizelabel = "100x100")
	{
		return UPLOAD_ABSOLUTE_URL. $sizelabel .'/'. $filepath . $filename . "?" . hg_generate_user_salt(5);
	}
	
	public function delete_by_id()
	{
		if(!$this->input['id'])
		{
			return false;
		}
		$ids = trim(urldecode($this->input['id']));
		$sql = "SELECT * FROM " . DB_PREFIX ."plat WHERE id IN(" .$ids .")";
		$r = $this->db->query($sql);
		while($row = $this->db->fetch_array($r))
		{
			$data[$row['id']] = array(
				'title' => $row['name'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
			);
			$data[$row['id']]['content']['plat'] = $row;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."pics WHERE id IN(" . $ids .")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$data[$row['id']]['content']['pics'] = $row;
		}
		if(!empty($data))
		{
			foreach($data as $key => $value)
			{
				$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		$sql="DELETE FROM " . DB_PREFIX . "plat WHERE id in ($ids)";
		$this->db->query($sql);
		$sql="DELETE FROM " . DB_PREFIX . "pics WHERE platId in ($ids)";
		$this->db->query($sql);
		return  true;
	}

	public function delete_comp()
	{
		return true;
	}
	
	public function delete_pics($tuji_id)
	{
		if(!$tuji_id)
		{
			return;
		}
		$tuji_id = explode(',', $tuji_id);
		foreach ($tuji_id as $id)
		{
			$dir = UPLOAD_ROOT_DIR.hg_num2dir($id);
			$files = scandir($dir);
			if(!is_array($files))
			{
				continue;
			}
			foreach($files AS $v)
			{
				if($v != '.' && $v!='..')
				{
					if(is_dir($dir.$v)) continue;
					@unlink($dir.$v);
				}
			}
		}
	}
	
	public function get_account_by_id($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plat WHERE id=$id ";
		$ret = $this->db->query_first($sql);
		if(!empty($ret))
		{
			$ret['status'] = $this->settings['status'][$ret['status']];
			$ret['platdata'] = empty($ret['platdata'])?array():unserialize($ret['platdata']);
//			$ret['type'] = empty($this->settings['share_plat'][$ret['type']]['name_ch'])?'':$this->settings['share_plat'][$ret['type']]['name_ch'];
			return $ret;
		}
		else
		{
			return false;
		}
	}
	
	public function get_account_by_last()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plat ORDER BY id DESC limit 1 ";
		$info = $this->db->query_first($sql);
		if(!empty($info))
		{
			return $info;
		}
		else
		{
			return false;
		}
	}
	
	public function update_by_id($id,$filepic)
	{
		$condition = '';
		$sql = "UPDATE ". DB_PREFIX ."plat set ";
		$condition .= "name='".urldecode($this->input['name'])."' , ";
		if($this->input['offiaccount']!='')
		{
			$condition .= "offiaccount='".urldecode($this->input['offiaccount'])."' , ";
		}
		if($this->input['apikey']!='')
		{
			$condition .= "akey='".urldecode($this->input['apikey'])."' , ";
		}
		if($this->input['secretkey']!='')
		{
			$condition .= "skey='".urldecode($this->input['secretkey'])."' , ";
		}
		if($this->input['callback']!='')
		{
			$condition .= "callback='".urldecode($this->input['callback'])."' , ";
		}
		if($this->input['status']!='')
		{
			$condition .= "status='".urldecode($this->input['status'])."' , ";
		}
		if(!empty($filepic['picurl']))
		{
			$condition .= "picurl='".serialize($filepic['picurl'])."' , ";
		}
		if(!empty($filepic['pic_login']))
		{
			$condition .= "pic_login='".serialize($filepic['pic_login'])."' , ";
		}
		if(!empty($filepic['pic_share']))
		{
			$condition .= "pic_share='".serialize($filepic['pic_share'])."' , ";
		}
		
		if(intval($this->input['type']) == 127)
		{
			foreach($this->settings['share_plat'][127]['para'] as $v)
			{
				$platpara[$v['param']] = urldecode($this->input[$v['param']]);
			}
		}
		$platdata = empty($platpara)?'':serialize($platpara);
		if(!empty($data['platdata']))
		{
			$condition .= "platdata='".$data['platdata']."' , ";
		}
		
		$condition = trim($condition,', ');
		if(!$condition)
		{
			return false;
		}
		$sql .= $condition." where id=$id";
		if($this->db->query($sql))
		{
			$sql = "SELECT * FROM ".DB_PREFIX."plat WHERE id=$id ";
			$ret = $this->db->query_first($sql);
			$ret['picurl'] = empty($ret['picurl'])?array():unserialize($ret['picurl']);
			$ret['status'] = $this->settings['status'][$ret['status']];
			$ret['type'] = $this->settings['share_plat'][$ret['type']]['name_ch'];
			unset($this->input['callback']);
			return $ret;
		}
		else
		{
			return false;
		}
	}
	
	public function get_app_datas($offset,$count,$condition = '')
	{
		$sql = "SELECT * FROM ".DB_PREFIX."app ".$condition." LIMIT {$offset},{$count}";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function insert_app($systemId,$platIds)
	{
		$data=array(
			'name' => '',
			'bundle' => $systemId,
			'platIds' => $platIds,
			'updatetime' => time(),
		);
		$sql="INSERT INTO " . DB_PREFIX . "app SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		
		$id=$this->db->insert_id();
		if($id)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function get_app_by_Id($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."auth_app WHERE appid=$id";
		$info = $this->db->query_first($sql);
		if(!empty($info))
		{
			return $info;
		}
		else
		{
			return false;
		}
	}
	
	public function get_app_by_systemId($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."auth_app WHERE appid='" .$id. "' AND status='1'";
		$info = $this->db->query_first($sql);
		if(!empty($info))
		{
			return $info;
		}
		else
		{
			return false;
		}
	}
	
	public function get_all_plat($field = '*' , $key_id = false)
	{
		$ret = array();
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."plat ";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$row['picurl'] = $row['picurl']?unserialize($row['picurl']):array();
			if($key_id)
			{
				$ret[$row[$key_id]] = $row;
			}
			else
			{
				$ret[] = $row;
			}
		}
		return $ret;
	}
	
	public function get_plat_supportid($field = '*' , $suppids , $key_id = false)
	{
		$ret = array();
		$sql = "SELECT ".$field." FROM ".DB_PREFIX."plat WHERE id in (".$suppids.")";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$row['picurl'] = $row['picurl']?unserialize($row['picurl']):array();
			$row['pic_login'] = $row['pic_login']?unserialize($row['pic_login']):array();
			$row['pic_share'] = $row['pic_share']?unserialize($row['pic_share']):array();
			if($key_id)
			{
				$ret[$row[$key_id]] = $row;
			}
			else
			{
				$ret[] = $row;
			}
		}
		return $ret;
	}
	
	/**
	 * 当分享平台为空时，插入一条platId为空对应的系统的数据，如果有则删除这条记录，分别插入分享平台
	 * @author 
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include share.class.php
	 */
	public function update_app_by_Id($id)
	{
		$str = '';
		$pls = $this->input['platlist'];
		$status = $this->input['status'];
		$platIds = !$pls?'':trim(implode(',',$pls),',');
		//先查询有没有此内容
		$sql = "SELECT appid FROM ".DB_PREFIX."auth_app WHERE appid=$id ";
		$ret = $this->db->query_first($sql);
		if(empty($ret))
		{
			$sql = "INSERT ".DB_PREFIX."auth_app SET appid='".$id."' , status='".$status."' , platIds='".$platIds."',updatetime=".TIMENOW."";
		}
		else
		{
			$sql = "UPDATE ".DB_PREFIX."auth_app SET status='".$status."' , platIds='".$platIds."',updatetime=".TIMENOW."";
			$sql .= " WHERE appid=$id";
		}
		
		if($this->db->query($sql))
		{
			$sql = "SELECT * FROM ".DB_PREFIX."auth_app WHERE appid=$id ";
			$ret = $this->db->query_first($sql);
			$all['status'] = $this->settings['status'][$ret['status']];
			$platdatas = self::get_all_plat();
			
			foreach($platdatas as $k=>$v)
			{
				$pd[$v['id']] = $v['name'];
			}
			if($ret['platIds'])
			{
				foreach(explode(',',$ret['platIds']) as $k1=>$v1)
				{
					$str .= $pd[$v1].""; 
				}
			}
			
			$all['plats'] = $str;
			$all['updatetimef'] = date('Y-m-d',$ret['updatetime']);
			$all['updatetimea'] = date('H-i-s',$ret['updatetime']);
			return $all;
		}
		else
		{
			return false;
		}
	}
	
	public function get_by_app_plat($appid,$platid)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."auth_app WHERE appid='".$appid."' AND status='1'";
		$ret = $this->db->query_first($sql);
		if($ret)
		{
			$platIds = explode(',',$ret['platIds']);
			if(in_array(intval($platid),$platIds))
			{
				$sql = "SELECT * FROM ".DB_PREFIX."plat WHERE id=$platid AND status='1'";
				$result = $this->db->query_first($sql);
				$result['platdata'] = empty($result['platdata'])?array():unserialize($result['platdata']);
				return $result;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	public function getAuthoshareURL($data)
	{
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $data['callback'];
		$params['response_type'] = 'token';
		$params['state'] = NULL;
		$params['display'] = NULL;
		return trim($data['oauthurl'],'/'). "?" . http_build_query($params);
	}
	
	public function getnewtoken($length)
	{
		$num = 0;
		$str = '';
		//验证是否存在
		while($num > 0 || $str == '')
		{
			$str = self::random_string($length);
			$sql = "select * from ".DB_PREFIX."token where token='$str'";
			$num = count($this->db->fetch_all($sql));
		}
		return $str;
	}
	
	public function inserttoken($appid,$platid,$tokenstr,$access_token,$openid = '')
	{
		$data=array(
			'appid' => $appid,
			'platId' => $platid,
			'token' => $tokenstr,
			'access_token' => $access_token,
			'openid' => $openid,
			'addtime' => TIMENOW,
		);
		$sql="INSERT INTO " . DB_PREFIX . "token SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return true;
	}
	
	public function get_token_by_token($token)
	{
		$sql = "select *,p.name as name,t.addtime as token_addtime from ".DB_PREFIX."token t left join ".DB_PREFIX."plat p on t.platId=p.id LEFT JOIN ".DB_PREFIX."auth_app a ON t.appid=a.appid where t.token='$token' AND p.status='1' AND a.status='1' ";
		$ret = $this->db->query_first($sql);
		$ret['platdata'] = empty($ret['platdata'])?array():unserialize($ret['platdata']);
		$ret['access_token'] = empty($ret['access_token'])?array():json_decode($ret['access_token'],true);
		return $ret;
	}
	
	public function get_token_by_tokens($tokens)
	{
		$sql = "select *,t.addtime as token_addtime from ".DB_PREFIX."token t left join ".DB_PREFIX."plat p on t.platId=p.id LEFT JOIN ".DB_PREFIX."auth_app a ON t.appid=a.appid where t.token in ('".implode("','",explode(',',$tokens))."') AND p.status='1' AND a.status='1' ";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$row['platdata'] = empty($row['platdata']) ? array() : unserialize($row['platdata']);
			$row['access_token'] = empty($row['access_token'])?'':json_decode($row['access_token'],true);
			$ret[$row['platId']] = $row;
		}
		return $ret;
	}
	
	public function get_record($condition,$offset,$count)
	{
		$sql = "SELECT r.*,p.name as platname FROM ".DB_PREFIX."record r LEFT JOIN ".DB_PREFIX."plat p ON r.platid=p.id ".$condition." ORDER BY r.addtime DESC LIMIT {$offset},{$count}";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_record_by_id($id)
	{
		$sql = "SELECT r.*,p.name as platname FROM ".DB_PREFIX."record r LEFT JOIN ".DB_PREFIX."plat p ON r.platid=p.id  WHERE r.id=".$id;
		return $this->db->query_first($sql);
	}
	
	public function delete_record($ids)
	{
		$sql = "DELETE FROM ".DB_PREFIX."record WHERE id in(".$ids.")";
		$this->db->query($sql);
		return true;
	}
	
	function random_string($length, $max=FALSE)
	{
		if (is_int($max) && $max > $length)
		{
			$length = mt_rand($length, $max);
		}
		$output = '';
		
		for ($i=0; $i<$length; $i++)
		{
			$which = mt_rand(0,2);
			
			if ($which === 0)
			{
				$output .= mt_rand(0,9);
			}
			elseif ($which === 1)
			{
				$output .= chr(mt_rand(65,90));
			}
			else
			{
				$output .= chr(mt_rand(97,122));
			}
		}
		return $output;
	}
	
	public function get_auth_app($appids)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."auth_app WHERE appid in (".$appids.")";
		$info = $this->db->fetch_all($sql,'appid');
		return $info;
	}
	
	public function updatetoken($token,$data)
	{
		$sql="UPDATE " . DB_PREFIX . "token SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE token='".$token."'";
		$this->db->query($sql);
		return true;
	}
	
	public function insert_record($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "record SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function get_auth_user($insert_data)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "auth_user WHERE plat_type=".$insert_data['plat_type'];
//		if($insert_data['uid'])
//		{
//			$sql .= " AND uid='".$insert_data['uid']."'";
//		}
		if($insert_data['name'])
		{
			$sql .= " AND name='".$insert_data['name']."'";
		}
		return $this->db->query_first($sql);
	}

	public function get_plat_user($insert_data)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "plat_user WHERE plat_type=".$insert_data['plat_type'] . " AND platId=" . $insert_data['platId'];
//		if($insert_data['uid'])
//		{
//			$sql .= " AND uid='".$insert_data['uid']."'";
//		}
		if($insert_data['name'])
		{
			$sql .= " AND name='".$insert_data['name']."'";
		}
		return $this->db->query_first($sql);
	}	
	
	public function insert_auth_user($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "auth_user SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function insert_plat_user($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "plat_user SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}	
	
	public function update_auth_user($data,$id)
	{
		$sql="UPDATE " . DB_PREFIX . "auth_user SET";
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id=".$id;
		$this->db->query($sql);
		return true;
	}
		
	public function update_plat_user($data,$id)
	{
		$sql="UPDATE " . DB_PREFIX . "plat_user SET";
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id=".$id;
		$this->db->query($sql);
		return true;
	}	
	
	public function delete_access_plat_token($token)
	{
		$sql = "DELETE FROM ".DB_PREFIX."token WHERE token='".$token."'";
		$this->db->query($sql);
	}
	
	public function delete_auth_user($platid,$uid,$name)
	{
		$sql = "DELETE FROM ".DB_PREFIX."auth_user WHERE platId=".$platid;
		if($uid)
		{
			$sql .= " AND uid='".$uid."'";
		}
		if($name)
		{
			$sql .= " AND name='".$name."'";
		}
		$this->db->query($sql);
	}
	
	public function get_plat_info($platid)
	{
		if(!$platid)
		{
			return false;
		}
		$sql = "SELECT * FROM ".DB_PREFIX."plat WHERE id = " . $platid;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
}

?>