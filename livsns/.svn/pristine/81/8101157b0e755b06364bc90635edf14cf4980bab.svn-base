<?php
require('global.php');
class movie_update extends BaseFrm
{
	private $mMaterial;
	public function __construct()
	{
		parent::__construct();
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if( empty($this->input['name']))
		{
			$this->errorOutput("没有电影名");
		}
		
		$sql = "select id , name from " . DB_PREFIX . "movie_person " ;
		$query = $this->db->query($sql);
		while ( $person = $this->db->fetch_array($query) )
		{
			$persons[$person['id']] = $person['name'];
		}
		
		$this->input['actor'] = explode(',', trim(urldecode($this->input['actor'])));
		$actor = '';
		foreach ($this->input['actor'] as $b )
		{
			foreach ( $persons as $k => $v )
			{
				if( $b == $v )
				{
					if(empty($actor))
					{
						$actor = $k;
					}
					else 
					{
						$actor .= ',' . $k;
					}
				}
			}
		}
		
		$this->input['director'] = explode(',', trim(urldecode($this->input['director'])));
		$director = '';
		foreach ($this->input['director'] as $b )
		{
			foreach ( $persons as $k => $v )
			{
				if( $b == $v )
				{
					if(empty($director))
					{
						$director = $k;
					}
					else 
					{
						$director .= ',' . $k;
					}
				}
			}
		}
		$this->input['director'] = $director;
		
		//=-----------------------------------------------------
		$sql = "select id , name from " . DB_PREFIX . "movie_lang " ;
		$query = $this->db->query($sql);
		while ( $lang = $this->db->fetch_array($query) )
		{
			$langs[$lang['id']] = $lang['name'];
		}
		
		$this->input['lang_id'] = explode(',', trim(urldecode($this->input['lang_id'])));
		$lan = '';
		foreach ($this->input['lang_id'] as $b )
		{
			foreach ( $langs as $k => $v )
			{
				if( $b == $v )
				{
					if(empty($lan))
					{
						$lan = $k;
					}
					else 
					{
						$lan .= ',' . $k;
					}
				}
			}
		}
		
		$this->input['lang_id'] = trim($lan) ;
		
		//=----------------------------------------------------------
		$sql = "select id , name from " . DB_PREFIX . "movie_node " ;
		$query = $this->db->query($sql);
		while ( $node = $this->db->fetch_array($query) )
		{
			$nodes[$node['id']] = $node['name'];
		}
		
		$this->input['movie_sort_id'] = explode(',', trim(urldecode($this->input['movie_sort_id'])));
		$lan = '';
		foreach ($this->input['movie_sort_id'] as $b )
		{
			foreach ( $nodes as $k => $v )
			{
				if( $b == $v )
				{
					if(empty($lan))
					{
						$lan = $k;
					}
					else 
					{
						$lan .= ',' . $k;
					}
				}
			}
		}
		
		$this->input['movie_sort_id'] = trim($lan) ;
		
		//file_put_contents("222.txt", $this->input['movie_sort_id']);
		
		$this->input['release_time'] = strtotime($this->input['release_time']);
		$this->input['create_time'] = strtotime($this->input['create_time']);
		$this->input['update_time'] = time();
		
		//默认状态
		$this->input['status'] = $this->input['status'] ? $this->input['status'] : 2;
		
		$sql = "INSERT INTO ".DB_PREFIX."movie_info SET ";
		$sql.= "id= NULL , " .
		" name = '" . urldecode(trim($this->input['name'])) . "' , " .
		" other_name = '" . urldecode(trim($this->input['other_name'])) . "' , " .
		" appid = '" . intval($this->user['appid']) . "' , " .
		" app_name = '" . trim($this->user['display_name']) . "' , " .
		" user_id = '" . intval($this->user['user_id']) . "' , " .
		" user_name = '" . urldecode(trim($this->user['user_name'])) . "' , " .
		" media_id = '" . intval($this->input['media_id']) . "' , " .
		" movie_sort_id = '" . trim($this->input['movie_sort_id']) . "' , " .
		" area_id = '" . intval($this->input['area_id']) . "' , " .
		" lang_id = '" . trim($this->input['lang_id']) . "' , " .
		" director = '" . trim($this->input['director']) . "' , " .
		" actor = '" . $actor . "' , " .
		" film_awords = '" . urldecode(trim($this->input['film_awords'])) . "' , " .
		" brief = '" . urldecode(trim($this->input['brief'])) . "' , " .
		" ticket_office = '" . intval($this->input['ticket_office']) . "' , " .
		" release_time = '" . intval($this->input['release_time']) . "' , " .
		" film_range = '" . trim($this->input['film_range']) . "' , " .
		" click_num = '0' , " .
		" share_num = '0' , " .
		" download_num = '0' , " .
		" status = '" . intval($this->input['status']) . "' , " .
		" create_time = '" . time() . "' , " .
		" update_time = '" . intval($this->input['update_time']) . "' , " .
		" ip = '" . intval($this->input['ip']) . "' , " .
		" order_id = '" . intval($this->input['order_id']) . "'" ;
		$this->db->query($sql);
		echo "新增影片成功";
	}
	
	public function update()
	{
		if( empty($this->input['id']))
		{
			$this->errorOutput("没有电影id");
		}
		
		
		
		
		$sql = "select id , name from " . DB_PREFIX . "movie_person " ;
		$query = $this->db->query($sql);
		while ( $person = $this->db->fetch_array($query) )
		{
			$persons[$person['id']] = $person['name'];
		}
		//-------
		$this->input['actor'] = explode(',', trim(urldecode($this->input['actor'])));
		$actor = '';
		foreach ($this->input['actor'] as $b )
		{
			foreach ( $persons as $k => $v )
			{
				if( $b == $v )
				{
					if(empty($actor))
					{
						$actor = $k;
					}
					else 
					{
						$actor .= ',' . $k;
					}
				}
			}
		}
		$this->input['actor'] = $actor ;
		
		//-------------------------------------------------------------------------
		$this->input['director'] = explode(',' , trim(urldecode($this->input['director'])));
		$director = '';
		foreach ( $this->input['director'] as $b )
		{
			foreach ( $persons as $k => $v )
			{
				if( $b == $v )
				{
					if(empty($director))
					{
						$director .= $k;
					}
					else 
					{
						$director .= ',' . $k;
					}
				}
			}
		}
		$this->input['director'] = $director ;
		
		//=--------------------------------------------------------------------
		$sql = "select id , name from " . DB_PREFIX . "movie_lang " ;
		$query = $this->db->query($sql);
		while ( $lang = $this->db->fetch_array($query) )
		{
			$langs[$lang['id']] = $lang['name'];
		}
		
		$this->input['lang_id'] = explode(',', trim(urldecode($this->input['lang_id'])));
		$lan = '';
		foreach ($this->input['lang_id'] as $b )
		{
			foreach ( $langs as $k => $v )
			{
				if( $b == $v )
				{
					if(empty($lan))
					{
						$lan = $k;
					}
					else 
					{
						$lan .= ',' . $k;
					}
				}
			}
		}
		
		$this->input['lang_id'] = trim($lan) ;
		
		
		//=----------------------------------------------------------------------------------
		$sql = "select id , name from " . DB_PREFIX . "movie_node " ;
		$query = $this->db->query($sql);
		while ( $node = $this->db->fetch_array($query) )
		{
			$nodes[$node['id']] = $node['name'];
		}
		
		$this->input['movie_sort_id'] = explode(',', trim(urldecode($this->input['movie_sort_id'])));
		$lan = '';
		foreach ($this->input['movie_sort_id'] as $b )
		{
			foreach ( $nodes as $k => $v )
			{
				if( $b == $v )
				{
					if(empty($lan))
					{
						$lan = $k;
					}
					else 
					{
						$lan .= ',' . $k;
					}
				}
			}
		}
		
		$this->input['movie_sort_id'] = trim($lan) ;
		
		
		//--------------------------------------------------------------------------------
		$this->input['release_time'] = strtotime($this->input['release_time']);
		$this->input['create_time'] = strtotime($this->input['create_time']);
		$this->input['update_time'] = time();
		
		$info = array(
			'name',
			'other_name',
			'icon' ,
			'media_id',
			'movie_sort_id',
			'area_id',
			'lang_id',
			'director',
			'actor',
			'film_awords',
			'brief',
			'ticket_office',
			'release_time',
			'film_range',
			'click_num',
			'share_num',
			'download_num',
			'status',
			'create_time',
			'update_time',
			'ip',
			'order_id',
		);
		
		//上传图片
		if ($_FILES['icon']['tmp_name'])
		{
			$file['Filedata'] = $_FILES['icon'];
			$material = $this->mMaterial->addMaterial($file, $this->input['id']);
			$logo_info['id'] = $material['id'];
			$logo_info['type'] = $material['type'];
			$logo_info['filepath'] = $material['filepath'];
			$logo_info['name'] = $material['name'];
			$logo_info['filename'] = $material['filename'];
			$logo_info['url'] = $material['url'];
			$this->input['icon'] = serialize($logo_info);
		}
		

		$fields = ' SET  ';
		foreach ($info as $k => $v)
		{
			if (!isset($this->input[$v]))
			{
				continue;
			}

			$fields .=  $v . ' = \''.urldecode($this->input[$v]).'\',';
		}
		$fields = rtrim($fields , ',');
		$updatesql = "UPDATE ".DB_PREFIX.'movie_info ' . $fields .'  WHERE id=' . $this->input['id'];
		$info = $this->db->query($updatesql);
		$this->index_search($video_id, 'update');
	
		$this->db->query($sql);
		
		
		
		
		echo 1;
	}
	
	public function delete()
	{
		if( empty($this->input['id']))
		{
			$this->errorOutput("没有电影id");
		}
		$this->db->query("delete from  ".DB_PREFIX."movie_info where id in (" . $this->input['id'] . ")");
		echo 1;
	}
	
	public function unknow()
	{
		$this->errorOutput("没有该方法");
	}
}

$out = new movie_update();
$action = $_INPUT['a'];
if (!method_exists( $out , $action))
{
	$action = 'unknow';
}
$out->$action();

?>