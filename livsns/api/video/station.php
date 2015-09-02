<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: station.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class stationApi extends adminBase
{
	private $mVideo;
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	
	/**
	 * 根据条件来查询多个网台信息
	 * @param $count 返回记录数
	 * @return $info 网台信息
	 */
	function show(){
		$mInfo = $this->mUser->verify_credentials();

		$count = intval($this->input['count']?$this->input['count']:0);
		$page = intval($this->input['page']?$this->input['page']:0);
		$end = "";
		$offset = $page * $count;
		if($count)
		{
			$end = " LIMIT ".$offset.",".$count;
		}
		$size = array(
			"small" => array('t'=>"s_",'size'=>LOGO_SIZE_SMALL),
		);
		
		
		$cond =" WHERE 1 AND program_count > 0";
		$sta_id = urldecode($this->input['sta_id']?$this->input['sta_id']:0);
		if($sta_id)
		{
			$cond .=" AND id IN (".$sta_id.")";
		}
		$user_id =  urldecode($this->input['user_id']?$this->input['user_id']:0);

		if($user_id)
		{
			$cond .=" AND user_id IN (".$user_id.")";
		}
		
		$cond .=" AND state = 1 ORDER BY update_time DESC,collect_count DESC ";
		$sql = "SELECT * FROM ". DB_PREFIX ."network_station".$cond.$end;
		
		$query = $this->db->query($sql);
		
		$u_id = "";
		$sta_id = "";
		$space = " ";
		while($array = $this->db->fetch_array($query))
		{		
			$array['programe'] = unserialize($array['programe']);
			$u_id .= $space.$array['user_id'];
			$sta_id .= $space.$array['id'];
			$space = ",";
			foreach($size as $key=>$value)
			{
				if($array['logo'])
				{
					$new_name = $value['t'].$array['logo'];
					$array[$key] = UPLOAD_URL.LOGO_DIR.ceil($array['user_id']/NUM_IMG)."/".$new_name;
				}
				else 
				{
					if(file_exists(hg_avatar($array['user_id'],"larger",$array['user_id'].".jpg")))
					{
						$array[$key] = hg_avatar($array['user_id'],"larger",$array['user_id'].".jpg");
					}
					else 
					{
						$array[$key] = AVATAR_URL."larger_0.jpg";
					}
				}
			}
			$info[] = $array;
		}
		$user = $this->getUserById($u_id);
		$re = $this->mVideo->get_concern_relevance($mInfo['id'],$sta_id);	
		
		$this->setXmlNode('user','info');
		foreach($info as $k => $v)
		{
			$v['user'] = $user[$v['user_id']];
			$v['relation'] = $re[$v['id']]['relation'];
			$v['concern_id'] = $re[$v['id']]['id'];
			$this->addItem($v);
		}
		if($count)
		{
			$sql = "SELECT count(*) as total FROM ". DB_PREFIX ."network_station".$cond;
			$first = $this->db->query_first($sql);
			$this->addItem($first['total']);
		}
		$this->output();	
	}
	
	
	public function all_station()
	{
		$count = intval($this->input['count']?$this->input['count']:0);
		$page = intval($this->input['page']?$this->input['page']:0);
		$end = "";
		$offset = $page * $count;
		if($count)
		{
			$end = " LIMIT ".$offset.",".$count;
		}
		$size = array(
			"small" => array('t'=>"s_",'size'=>LOGO_SIZE_SMALL),
		);
		
		
		$cond =" WHERE 1";

		$cond .=" ORDER BY update_time DESC,collect_count DESC";
		$sql = "SELECT * FROM ". DB_PREFIX ."network_station".$cond.$end;
		
		$query = $this->db->query($sql);
		
		$u_id = "";
		$sta_id = "";
		$space = " ";
		while($array = $this->db->fetch_array($query))
		{		
			$array['programe'] = unserialize($array['programe']);
			$u_id .= $space.$array['user_id'];
			$sta_id .= $space.$array['id'];
			$space = ",";
			foreach($size as $key=>$value)
			{
				if($array['logo'])
				{
					$new_name = $value['t'].$array['logo'];
					$array[$key] = UPLOAD_URL.LOGO_DIR.ceil($array['user_id']/NUM_IMG)."/".$new_name;
				}
				else 
				{
					if(file_exists(hg_avatar($array['user_id'],"larger",$array['user_id'].".jpg")))
					{
						$array[$key] = hg_avatar($array['user_id'],"larger",$array['user_id'].".jpg");
					}
					else 
					{
						$array[$key] = AVATAR_URL."larger_0.jpg";
					}
				}
			}
			$info[] = $array;
		}
		$user = $this->getUserById($u_id);
		$re = $this->mVideo->get_concern_relevance($mInfo['id'],$sta_id);	
		
		$this->setXmlNode('user','info');
		foreach($info as $k => $v)
		{
			$v['user'] = $user[$v['user_id']];
			$v['relation'] = $re[$v['id']]['relation'];
			$v['concern_id'] = $re[$v['id']]['id'];
			$this->addItem($v);
		}
		if($count)
		{
			$sql = "SELECT count(*) as total FROM ". DB_PREFIX ."network_station".$cond;
			$first = $this->db->query_first($sql);
			$this->addItem($first['total']);
		}
		$this->output();		
	}
		
	private function getUserById($user_id)
	{
		$user = $this->mUser->getUserById($user_id);
		
		foreach($user as $key => $value)
		{
			$info[$value['id']] = $value;
		}
		
		return $info;
	}
	
	/**
	* 根据当前用户来查询网台信息
	* @return $info 网台信息
	*/
	function showOne(){
		$mInfo = $this->mUser->verify_credentials();
		$id = $this->input['user_id']?$this->input['user_id']:($mInfo['id']?$mInfo['id']:0);
		$sql_id = $id?" WHERE user_id=".$id:"";
		$sql = "SELECT * FROM ". DB_PREFIX ."network_station".$sql_id;
		$info = $this->db->query_first($sql);
		$size = array(
			"small" => array('t'=>"s_",'size'=>LOGO_SIZE_SMALL),
			"ori" => array('t'=>"",'size'=>LOGO_SIZE_SMALL),
		);
		
			foreach($size as $key=>$value)
			{
				$new_name = $value['t'].$info['logo'];
				if($info['logo'])
				{
					$info[$key] = UPLOAD_URL.LOGO_DIR.ceil($id/NUM_IMG)."/".$new_name;
				}
				else 
				{
					if(file_exists(hg_avatar($id,"larger",$id.".jpg")))
					{
						$info[$key] = hg_avatar($id,"larger",$id.".jpg");
					}
					else 
					{
						$info[$key] = AVATAR_URL."larger_0.jpg";
					}
				}
			}
		$user = $this->getUserById($id);
		$info['user'] = $user[$info['user_id']];
		
		$re = $this->mVideo->get_concern_relevance($mInfo['id'],$info['id']);
		$info['relation'] = $re[$info['id']]['relation'];
		$info['concern_id'] = $re[$info['id']]['id'];
		$info['programe'] = unserialize($info['programe']);
		if($info['user_id'] == $mInfo['id'])
		{
			$info['myself'] = 1;
		}
		else 
		{
			$info['myself'] = 0;
		}
		unset($info['user_id']);
		$this->setXmlNode('user','info');
		$this->addItem($info);
		$this->output();
	}
	
	/**
	* 创建网台
	* @param $web_station_name 名称
	* @param $brief 简介
	* @param $logo logo名称
	* @return $info 网台信息
	*/
	function create(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'network_station WHERE user_id = '.$mInfo['id'];
		$q = $this->db->query_first($sql);
		if($q)
		{
			$this->input['sta_id'] = $q['id'];
		}
		else 
		{
			$this->input['sta_id'] = 0;
		}
		if($this->input['sta_id'])
		{
			$this->update();
		}
		
		include_once(ROOT_DIR . 'lib/class/banword.class.php');
		$banword = new banword();
		$status = 1;
		$banwords = $banword->banword(($this->input['web_station_name'] . $this->input['brief'] . $this->input['tags']));
		
		$info = array(
			'user_id' => $mInfo['id'],
			'web_station_name' => urldecode($this->input['web_station_name'])?urldecode($this->input['web_station_name']):'',
			'tags' => urldecode($this->input['tags'])?urldecode($this->input['tags']):'',
			'brief' => urldecode($this->input['brief'])?urldecode($this->input['brief']):'',
			'logo' => urldecode($this->input['logo'])?urldecode($this->input['logo']):'',
			'create_time' => time(),
			'update_time' => time(),
		);
		if ($banwords && $banwords != 'null')
		{
			$info['web_station_name'] = str_replace($banwords, '***', $info['web_station_name']);
			$info['tags'] = str_replace($banwords, '***', $info['tags']);
			$info['brief'] = str_replace($banwords, '***', $info['brief']);
		}
		$sql = "INSERT INTO ".DB_PREFIX."network_station(
			user_id,
			web_station_name,
			tags,
			brief,
			logo,
			create_time,
			update_time
		)
		VALUES(
			".$info['user_id'].",
			'".$info['web_station_name']."',
			'".$info['tags']."',
			'".$info['brief']."',
			'".$info['logo']."',
			".$info['create_time'].",
			".$info['update_time']."
		)";
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		$this->tags($info['tags'],$info['id']);
		
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
		$this->mUser->update_type($mInfo['id']);
		
		$this->setXmlNode('user','info');
		$this->addItem($info);
		$this->output();
	}
	
	/**
	* 更新网台数据
	* @param $web_station_name 名称
	* @param $brief 简介
	* @param $logo logo名称
	* @param $sta_id 网台ID
	* @return $info 网台信息
	*/
	function update(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$logo_o = urldecode($this->input['logo_o']);
		$logo = urldecode($this->input['logo'])?urldecode($this->input['logo']):urldecode($this->input['logo_o']);

		$info = array(
			'id' => intval($this->input['sta_id']),
			'web_station_name' => urldecode($this->input['web_station_name'])?urldecode($this->input['web_station_name']):'',
			'tags' => urldecode($this->input['tags'])?urldecode($this->input['tags']):'',
			'brief' => urldecode($this->input['brief'])?urldecode($this->input['brief']):'',
			'logo' => $logo,
			'update_time' => time(),
		);
		$sql = "UPDATE ".DB_PREFIX."network_station SET
			web_station_name='".$info['web_station_name']."',
			tags='".$info['tags']."',
			brief='".$info['brief']."',
			logo='".$info['logo']."',
			update_time=".$info['update_time']." 
			WHERE id = ".$info['id']." AND user_id = ".$mInfo['id'];
		$this->db->query($sql);
		
		$this->tags($info['tags'],$info['id']);
		
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
		$this->mUser->update_type($mInfo['id']);
		
		$this->setXmlNode('user','info');
		$this->addItem($info);
		$this->output();
	}

	private function tags($video_tags = "",$id=0){
		//标签
//		$video_tags = "动漫,日报,天气,南京,下雪,下雨";

		if(!$video_tags&&!$id)
		{
			return false;
		}
		$video_tags = str_replace("，",",",$video_tags);
		$tags = explode(',' , $video_tags );

		$space ="";
		foreach($tags as $key => $value)
		{
			$tag .= $space."'".$value."'";
			$space = ",";
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE tagname IN(" . $tag . ")";
		
		$q = $this->db->query($sql);
		$tagr = array();
		$space = "";
		$c ="";
		while($row = $this->db->fetch_array($q))
		{
			$tagr[] = $row;
			$arr[] = $row['tagname'];
			$tago .= $space."'".$row['tagname']."'";
			$c .= $space."(".$id.",".$row['id'].",1)";
			$space = ",";
		}

		if($arr&&is_array($arr))
		{
			foreach($arr as $k => $v)
			{
				$sql = "UPDATE " . DB_PREFIX . "tags SET tag_count = tag_count + 1 WHERE tagname='".$v."'";
				$this->db->query($sql);
			}
		}

		$dif = array_diff($tags, $arr);
		if(!$dif&&!$arr)
		{
			$dif = $tags;
		}
		if($dif)
		{
			$sql = "INSERT INTO " . DB_PREFIX . "tags(tagname,tag_count) VALUES";
			$space ="";
			$con ="";
			foreach($dif as $k => $v)
			{
				$con .=$space."('".$v."',1)";
				$space = ",";
			}
			$sql .=$con;
			$this->db->query($sql);	
			$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE tagname IN(" . $tag . ")";
			$q = $this->db->query($sql);
			$tagr = array();
			$c ="";
			while($row = $this->db->fetch_array($q))
			{
				$tagr[] = $row;
				$c .= $space."(".$id.",".$row['id'].",1)";
			}
			$sql = "INSERT IGNORE INTO " . DB_PREFIX . "video_tags(video_id,tag_id,type) VALUES";
			$sql .=$c; 
			$this->db->query($sql);
		}
		return true;
	}
	
	
	function logo(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$logo = urldecode($this->input['logo'])?urldecode($this->input['logo']):'';
		$files = $_FILES['files'];
		$uploadedfile = $files['tmp_name'];	
		$image = getimagesize($uploadedfile);
		$width = $image[0];
		$height = $image[1];
		if($width<LOGO_SIZE_SMALL && $height<LOGO_SIZE_SMALL)
		{
			$this->setXmlNode('station','info');
			$this->addItem("");
			$this->output();
		}
		if($logo)
		{
			$size = array(
			"logo_o" => array('t'=>"",'size'=>LOGO_SIZE_SMALL),
			"small" => array('t'=>"s_",'size'=>LOGO_SIZE_SMALL),
			);

			$infos = array();
			foreach($size as $key=>$value)
			{
				$new_name = $value['t'].$logo;
				$infos[$key] = UPLOAD_DIR.LOGO_DIR.ceil($mInfo['id']/NUM_IMG)."/".$new_name; 
				if(is_file($infos[$key]))
				{
					unlink($infos[$key]);
				}
			}
		}
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'network_station WHERE user_id = '.$mInfo['id'];
		$q = $this->db->query_first($sql);
		if($q)
		{
			$sta_id = $q['id'];
		}
		else 
		{
			$sta_id = 0;
		}
		
		include_once(ROOT_DIR . 'lib/class/gdimage.php');
		//源文件
		
		
		
		//文件名
		$file_name = hg_generate_user_salt(5).".jpg";
		$size = array(
			"small" => array('t'=>"s_",'size'=>LOGO_SIZE_SMALL),
		);
		
		//目录
		$file_dir = UPLOAD_DIR.LOGO_DIR . ceil($mInfo['id']/NUM_IMG)."/";	
	
		//文件路径
		$file_path = $file_dir . $file_name;

		if(!hg_mkdir($file_dir))
		{
			$this->errorOutput(UPLOAD_ERR_NO_FILE);
		}
		if(!copy($uploadedfile, $file_path))
		{					
			$this->errorOutput(UPLOAD_ERR_NO_FILE);						
		}
		$img = new GDImage($file_path , $file_path , '');
		$info =array();
		foreach($size as $key=>$value)
		{
			$new_name = $value['t'].$file_name;
			$save_file_path = $file_dir . $new_name;
			$img->init_setting($file_path , $save_file_path , '');
			$img->maxWidth = $value['size'];
			$img->maxHeight = $value['size'];
			$img->makeThumb(3,false);
			$info[$key] = UPLOAD_URL.LOGO_DIR.ceil($mInfo['id']/NUM_IMG)."/".$new_name."?".hg_rand_num(7); 
		}
		if($sta_id)
		{
			$sql = "UPDATE ".DB_PREFIX."network_station SET 
			logo='".$file_name."' 
			WHERE id = ".$sta_id." AND user_id = ".$mInfo['id'];
			$this->db->query($sql);
			$info['id'] = 0;
		}
		else 
		{
			$sql = "INSERT INTO ".DB_PREFIX."network_station(user_id,logo,create_time) VALUES(".$mInfo['id'].",'".$file_name."',".time().")";
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
			include_once(ROOT_PATH . 'lib/user/user.class.php');
			$this->mUser = new user();
			$this->mUser->update_type($mInfo['id']);
		}
		$info['logo'] = $file_name;		
		$this->setXmlNode('station','info');
		$this->addItem($info);
		$this->output();
		
	}
	
	/**
	 * 修改网台的收藏数目
	 * @param $sta_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function favorite_count()
	{
		$mInfo = $this->mUser->verify_credentials();
		$sta_id = $this->input['sta_id']? $this->input['sta_id']:1;
		$type = $this->input['type']? $this->input['type']:0;//默认增加
		
		if(!$mInfo&&!$sta_id)
		{
			$this->errorOutput(OBJECT_NULL);	
		}
		
		$sql = "UPDATE " . DB_PREFIX . "network_station SET collect_count=";
		if($type)
		{
			$sql .="collect_count+1";
		}
		else 
		{
			$sql .="collect_count-1";
		}
		
		$sql .= " WHERE id = ".$sta_id;
		$this->db->query($sql);
		$this->setXmlNode('station' , 'info');
		$this->addItem($sta_id);
		$this->output();
	}
	
	/**
	 * 更新网台的访问次数
	 */
	public function update_click_count()
	{
		$sta_id = intval(trim($this->input['sta_id']));
		$sql = "UPDATE " . DB_PREFIX . "network_station SET click_count = click_count + 1 WHERE id = " .$sta_id ;
		$this->db->query($sql);	
	}
	

	public function get_station_num()
	{
		$sql = "SELECT COUNT(*) as nums FROM " . DB_PREFIX . "network_station";
		$r = $this->db->query_first($sql);
		echo $r['nums'];	
	}	

	/**
	 * 检索网台根据网台名称条件
	 * @param $key
	 * @param $count
	 * @param $page
	 * @return $info 网台信息 
	 */
	public function search()
	{
		$mInfo = $this->mUser->verify_credentials();

		$key = urldecode($this->input['key']?$this->input['key']:"");
		$count = intval($this->input['count']?$this->input['count']:0);
		$page = intval($this->input['page']?$this->input['page']:0);
		$end = "";
		$offset = $page * $count;
		if($count)
		{
			$end = " LIMIT ".$offset.",".$count;
		}
		$size = array(
			"small" => array('t'=>"s_",'size'=>LOGO_SIZE_SMALL),
		);
		$cond =" WHERE 1";
				
		if(!$key)
		{
			
		}
		else 
		{
			$cond .= " AND state = 1 AND concat(web_station_name,brief,tags) LIKE '%".$key."%'";
		}
						
		$cond .=" ORDER BY update_time DESC";
		$sql = "SELECT * FROM ". DB_PREFIX ."network_station".$cond.$end;
		
		$query = $this->db->query($sql);
		
		if($this->db->num_rows($query) == 0)
		{
			$this->errorOutput(OBJECT_NULL);	
		}
		
		$u_id = "";
		$sta_id = "";
		$space = " ";
		while($array = $this->db->fetch_array($query))
		{		
			$array['programe'] = unserialize($array['programe']);
			$u_id .= $space.$array['user_id'];
			$sta_id .= $space.$array['id'];
			$space = ",";
			foreach($size as $key=>$value)
			{
				if($array['logo'])
				{
					$new_name = $value['t'].$array['logo'];
					$array[$key] = UPLOAD_URL.LOGO_DIR.ceil($array['user_id']/NUM_IMG)."/".$new_name;
				}
				else 
				{
					if(file_exists(hg_avatar($array['user_id'],"larger",$array['user_id'].".jpg")))
					{
						$array[$key] = hg_avatar($array['user_id'],"larger",$array['user_id'].".jpg");
					}
					else 
					{
						$array[$key] = AVATAR_URL."larger_0.jpg";
					}
				}
			}
			$info[] = $array;
		}
		$user = $this->getUserById($u_id);
		$re = $this->mVideo->get_concern_relevance($mInfo['id'],$sta_id);	
		
		$this->setXmlNode('user','info');
		foreach($info as $key => $value)
		{
			$value['user'] = $user[$value['user_id']];
			$value['relation'] = $re[$value['id']]['relation'];
			$value['concern_id'] = $re[$value['id']]['id'];
			$this->addItem($value);
		}
		if($count)
		{
			$sql = "SELECT count(*) as total FROM ". DB_PREFIX ."network_station".$cond;
			$first = $this->db->query_first($sql);
			$this->addItem($first['total']);
		}
		$this->output();	
	}
	
	public function verify_station()
	{
		$state = intval($this->input['state']);
		$id = intval($this->input['station_id']);
		
		if(!$state && !$id)
		{
			$this->errorOutput(OBJECT_NULL);	
		}
				
		$sql = "UPDATE " . DB_PREFIX . "network_station SET state = " . $state . " WHERE id = " . $id;
		$this->db->query($sql);
	}	
	
	/**
	 * 重建所有网台中program中的program信息
	 *
	 */
	public function reset_program_history()
	{
		$sql = "SELECT id FROM ".DB_PREFIX."network_station WHERE program_count > 0";
		$query = $this->db->query($sql);	
		while($r = $this->db->fetch_array($query))
		{
			$info = array(
				"sta_id" => $r['id'],
				"programe" => "",
				"update_time" => time(),
				"ip" => hg_getip(),		
			);
			
			if($info['sta_id'])
			{
				$sql = "SELECT id,user_id,sta_id,video_id,programe_name,start_time,end_time,create_time,update_time FROM ". DB_PREFIX ."network_programme WHERE sta_id =".$info['sta_id']." ORDER BY update_time DESC LIMIT 0,4";
				$q = $this->db->query($sql);
				$video_id = "";
				$user_id = "";
				$space = "";
				while($row = $this->db->fetch_array($q))
				{
					$program[$row['id']]= $row;
					$video_id .= $space.$row['video_id'];
					$user_id = $row['user_id'];
					$space = ",";
				}
				if($user_id)
				{
					$user = $this->mVideo->getUserById($user_id);
				}
				
				if($video_id)
				{
					$sql = "SELECT id,user_id,sort_id,title,tags,schematic,bschematic,filename,streaming_media,toff,copyright,collect_count,comment_count,play_count,click_count,is_top,state,bans,ip,create_time,update_time,is_show,serve_id,is_recommend,is_thread FROM ". DB_PREFIX ."video WHERE state=1 AND is_show=2 AND id IN(".$video_id.")";
					$q = $this->db->query($sql);
					while($row = $this->db->fetch_array($q))
					{
						if($row['id'])
						{
							$row = hg_video_image($row['id'], $row);
							$video[$row['id']] = $row;
						}
					}
				}
				
				
				if(is_array($program) && $video)
				{
					foreach($program as $key => $value)
					{
						$program[$key]['user'] = $user[$value['user_id']];
						if($video[$value['video_id']])
						{
							$program[$key]['video'] = $video[$value['video_id']];
						}
						else 
						{
							unset($program[$key]);
						}
					}
					$info['programe'] = hg_filter_chars(serialize($program));
					$sql = "UPDATE ".DB_PREFIX."network_station SET programe='".$info['programe']."',update_time =".$info['update_time']." WHERE id=".$info['sta_id'];
					$this->db->query($sql);
				}
				unset($info,$program,$video);
			}
		}
	}
	
	/**
	 * 重建所有网台中网台名称没有的情况下默认为用户名
	 *
	 */
	public function reset_station_name()
	{
		$sql = "SELECT user_id,web_station_name FROM ".DB_PREFIX."network_station WHERE web_station_name = ''";
		$q = $this->db->query($sql);
		$user_id=$space="";
		while($r = $this->db->fetch_array($q))
		{
			$user_id .= $space.$r['user_id'];
			$space = ',';
		}
		$user = $this->mVideo->getUserById($user_id);
		foreach($user as $key => $value)
		{
			$sqls = "update ".DB_PREFIX."network_station set web_station_name='" . $value['username'] . "' where user_id=" . $value['id'];
			$this->db->query($sqls);
		}
		$sql = "SELECT count(*) as num FROM ".DB_PREFIX."network_station WHERE web_station_name = ''";
		$f = $this->db->query_first($sql);
		if($f['num'])
		{
			$this->reset_station_name();
		}
		else
		{
			return true;
		}
	}
	
}

$out = new stationApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'tags';
}
$out->$action();
?>