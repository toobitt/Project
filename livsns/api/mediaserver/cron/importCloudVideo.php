<?php
require('global.php');
define('MOD_UNIQUEID','importCloudVideo');//模块标识
require_once(ROOT_PATH . 'lib/class/material.class.php');

class importCloudVideo extends cronBase
{
	protected  $material_server;
	public function __construct()
	{
		parent::__construct();
		$this->material_server = new material();
	}
	public function initcron()
	{
		
	}
	public function show()
	{
		if (!$this->settings['video_cloud'])
		{
			return;
		}
		include(CUR_CONF_PATH . 'lib/cloud/' . $this->settings['video_cloud'] . '.php');
		$cloud = new $this->settings['video_cloud']();
		$cloud->setInput($this->input);
		$cloud->setSettings($this->settings);
		$cloud->setDB($this->db);
		$index = intval($this->input['index']) ? intval($this->input['index']) : 1;
		$size = intval($this->input['size']) ? intval($this->input['size']) : 10;
		$status = intval($this->input['status']) ? intval($this->input['status']) : 0;
		
		$video = $cloud->videoList($index, $size, $status);
		$video = json_decode($video,1);
		
		if($video['code'])
		{
			echo 'error_code : '.$video['code'];
			return;
		}
		$total = $video['total'];
		$persent = ($index*$size*100/$total);
		if($index*$size>=$total)
		{
			$persent = 100;
		}
		$html = '<div style="height:30px;width:100%;border:1px solid black;line-height:30px"><div style="border:0px;height:100%;width:'.$persent.'%;background-color:black;text-align:center;float:left"></div>'.sprintf("%.2f", $persent).'%</div>';
		
		$data = array();
		$extend_data = array();
		if(!$video['code'] && $video['data'])
		{
			foreach($video['data'] as $val)
			{
				$swf = $cloud->videoGetPlayinterface($this->settings['cloud_user'],$val['video_unique'],'flash');
				$data = array(
					'title'=>$val['video_name'],
					'comment'=>$val['video_desc'],
					'keywords'=>$val['tag'],
					'duration' => $val['video_duration'] * 1000,
					'totalsize' => $val['initial_size'],
					'update_time' => time(),
					'create_time'	=> strtotime($val['add_time']),
					'is_link' => 1,
					'vod_leixing'=>5,
'vod_sort_id'=>5,					
'user_id'=>$this->user['user_id'],
					'addperson'=>$this->user['user_name'],
					'org_id'=>$this->user['org_id'],
					'video_order_id'=>0,
					'swf' => $swf
				);
				switch ($val['status']) {
					case 10:
					$data['status'] = 1;
					break;
					case 20:
					$data['status'] = -1;
					default:
						;
					break;
				}
				if($val['img'])
				{
					$imgurl = pathinfo($val['img']);
					$imgurl = $imgurl['dirname'] . '/' . $imgurl['filename'] . '_640_360.' . $imgurl['extension'];
					$img_info = $this->material_server->localMaterial($imgurl);
					$img_info = $img_info[0];
					$image_info = array(
							'host' 		=> $img_info['host'],
							'dir' 		=> $img_info['dir'],
							'filepath' 	=> $img_info['filepath'],
							'filename' 	=> $img_info['filename'],
							'imgwidth' 	=> $img_info['imgwidth'],
							'imgheight' => $img_info['imgheight'],
						);
					$data['img_info'] = serialize($image_info);
				}
				//主表
				$sql = " INSERT INTO ".DB_PREFIX."vodinfo SET ";
				foreach ($data AS $k => $v)
				{
					$sql .= " {$k} = '".addslashes($v)."',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
				$vid = $this->db->insert_id();
				
				//更新video_order_id
				$this->db->query('UPDATE ' . DB_PREFIX . 'vodinfo set video_order_id='.$vid.' WHERE id='.$vid);
				//扩展表
				$extend_data = array(
				'vodinfo_id' => $vid,
				'content_id' => $val['video_id'],
				'extend_data' => $val['video_unique'],
				);
				$sql = " INSERT INTO ".DB_PREFIX."vod_extend SET ";
				foreach ($extend_data AS $k => $v)
				{
					$sql .= " {$k} = '".addslashes($v)."',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
			}
		}
		if($persent == 100)
		{
			exit("已完成所有视频的倒入，总计".$total);
		}
		file_put_contents(CACHE_DIR . 'page.txt', intval($index+1));
		$redirect = 'importCloudVideo.php?index='.intval($index+1).'&size='.$size.'&status='.$status.'&access_token='.$_GET['access_token'];
		echo $html;
		echo '<meta http-equiv="refresh" content="2; url='.$redirect.'" />';
	}
		public function update_img()
	{
		if (!$this->settings['video_cloud'])
		{
			return;
		}
		include(CUR_CONF_PATH . 'lib/cloud/' . $this->settings['video_cloud'] . '.php');
		$cloud = new $this->settings['video_cloud']();
		$cloud->setInput($this->input);
		$cloud->setSettings($this->settings);
		$cloud->setDB($this->db);
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? $this->input['count'] : 10;
		$access_token = $this->input['access_token'] ? $this->input['access_token'] : '';
		$limit = ' LIMIT ' . $offset . ', '.$count;
		$sql = 'SELECT ve.*,v.img_info FROM ' . DB_PREFIX .'vod_extend ve LEFT JOIN ' .DB_PREFIX.'vodinfo v ON v.id=ve.vodinfo_id' . ' ORDER BY ve.content_id ASC' . $limit;
		
		$sql_count = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'vod_extend';
		$count_query=$this->db->query_first($sql_count);
		if($offset >$count_query['total'])
		{
			exit('over');
		}
		else
		{
			echo($offset/$count_query['total']) . '%';
		}
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$imgurl = $cloud->imageGet($row['content_id'], '640_360');
			$imgurl = json_decode($imgurl,1);
			if($imgurl['code'])
			{
				continue;
			}
			$imgurl = $imgurl['data']['img1'];
			if($imgarray = unserialize($row['img_info']))
			{
				$imgarray = $imgarray['host'] . $imgarray['dir'] . $imgarray['filepath'] . $imgarray['filename'];
				//echo $imgarray;exit;
				$img_info = $this->material_server->replaceImg($imgarray, $imgurl);
			}
			else
			{
				$img_info = $this->material_server->localMaterial($imgurl);
			}
			$img_info = $img_info[0];
			//print_r($img_info);exit;
			$image_info = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
					'imgwidth' 	=> $img_info['imgwidth'],
					'imgheight' => $img_info['imgheight'],
				);
			$img_info = serialize($image_info);
			$sql = 'UPDATE ' . DB_PREFIX . 'vodinfo SET img_info="'.addslashes($img_info).'" WHERE id='.$row['vodinfo_id'];
			$this->db->query($sql);
		}
		$redirect = 'importCloudVideo.php?a=update_img&offset='.intval($offset+$count).'&access_token='.$access_token;
		echo '<meta http-equiv="refresh" content="2; url='.$redirect.'" />';
	}
	public function update_order()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? $this->input['count'] : 50;
		$access_token = $this->input['access_token'] ? $this->input['access_token'] : '';
		$limit = ' LIMIT ' . $offset . ', '.$count;
		
		$sql_count = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'vod_extend';
		$count_query=$this->db->query_first($sql_count);
		//print_r($count_query);exit;
		if($offset >$count_query['total'])
		{
			exit('over');
		}
		else
		{
			echo($offset/$count_query['total']) . '%';
		}
		
		$sql = 'SELECT v.* FROM ' . DB_PREFIX .'vod_extend ve LEFT JOIN ' .DB_PREFIX.'vodinfo v ON v.id=ve.vodinfo_id WHERE v.vod_leixing=5' . ' ORDER BY v.id DESC' . $limit;

		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$sql = 'INSERT INTO ' . DB_PREFIX .'vodinfo VALUES ';
			$ids_array[] = $row['id'];
			$_id = $row['id'];
			unset($row['id']);
			foreach($row as $k=>$v)
			{
				$_row[$k] = addslashes($v);
			}
			$sql .= '(null,"'.implode('","', $_row).'")';
			$this->db->query($sql);
			$this->db->query('UPDATE ' . DB_PREFIX . 'vod_extend SET vodinfo_id='.$this->db->insert_id().' WHERE vodinfo_id = '.$_id);
			
		}
		if($ids_array)
		{
			//$this->db->query($sql);
			$sql = 'DELETE FROM ' . DB_PREFIX . 'vodinfo WHERE id IN(' . implode(',', $ids_array).')';
			$this->db->query($sql);
		}
		$redirect = 'importCloudVideo.php?a=update_order&offset='.intval($offset+$count).'&access_token='.$access_token;
		echo '<meta http-equiv="refresh" content="2; url='.$redirect.'" />';
	}
}



$out = new importCloudVideo();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>
