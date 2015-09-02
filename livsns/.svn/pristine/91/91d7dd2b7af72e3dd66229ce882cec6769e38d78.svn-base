<?php
define('MOD_UNIQUEID','thread');//模块标识
define('SCRIPT_NAME', 'thread');
require('./global.php');
require_once (CUR_CONF_PATH . 'lib/multifunctional.class.php');
require_once (CUR_CONF_PATH . 'lib/content.class.php');
require_once (CUR_CONF_PATH . 'lib/attach.class.php');
class thread extends adminBase
{
	function __construct()
	{
		parent::__construct();
		$this->attachlib = new attach();
		$this->attach = new multifunc();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		print_r($this->user);exit;
	}
	function publishlib()
	{
		$c = new content();
		$selected_id = intval($this->input['id']);
		print_r($c->get_published_content_byid($selected_id));
	}
	function create()
	{
		$avatar = $this->attachlib->get_avatar($this->user['user_id']);
		$content = trim(rawurldecode($this->input['thread']));
		$topic_id = $this->input['tid'];
		$pcd = intval($this->input['pcd']);
		$aid = urldecode($this->input['aid']);
		
		if($aid)
		{
			$aid_array = array_filter(explode(',',$aid));
			$aid = $this->attachlib->tmp2att($aid_array);
		}
		if($pcd)
		{
			$c = new content();
			$article = $c->get_published_content_byid($pcd);
			if($article)
			{
				$extend_arc = array(
				'title'=>$article['title'],
				'brief'=>$article['brief'],
				'href'=>$article['content_url'],
				'bundle_id'=>$article['bundle_id'],
				'module_id'=>$article['module_id'],
				);
				
				$article = is_array($article['indexpic']) ? array(
				'host'=>$article['indexpic']['host'],
				'dir'=>$article['indexpic']['dir'],
				'filepath'=>$article['indexpic']['filepath'],
				'filename'=>$article['indexpic']['filename']
				) : array();
				
				$aid .= ',' . $this->attachlib->attach($article, 'publish', 'attach', $extend_arc);
			}
		}
		if(!$topic_id)
		{
			$this->errorOutput("请选择一个话题");
		}
		
		
		$short_link = $this->attachlib->outlink(urldecode($this->input['outlink']));
		if($short_link)
		{
			$aid .= ',' . $short_link;
		}
		
					
		$location = array(
		'lat'  => $this->input['lat'],
		'lon' => $this->input['lon'],
		'address' => urldecode($this->input['address']),
		'gpsx'=>$this->input['gpsx'],
		'gpsy'=>$this->input['gpsy'],
		);
		
		if($location['lat'] && $location['lon'])
		{
			$loc = FromBaiduToGpsXY($location['lon'], $location['lat']);
			$location['gpsx'] = $loc['x'];
			$location['gpsy'] = $loc['y'];
		}
		if($map = $this->attachlib->map($location))
		{
			$aid .=  ',' . $map;
		}
		
		if(!$content && !$aid)
		{
			$this->errorOutput("内容不能为空");
		}
		$aid = $aid ? trim($aid,',') : '';
		$data = array(
		'tid'=>$topic_id,
		'content'=>$content,
		'aid'=>$aid,
		'client'=>$this->user['display_name'],
		'status'=>0,
		'create_time'=>TIMENOW,
		'user_id'=>$this->user['user_id'],
		'avatar'=>$avatar ? addslashes(serialize($avatar)) : '',
		'user_name'=>$this->user['user_name'],
		'pcd'=>$pcd,
		'ip'=>hg_getip(),
		);
		$sql = 'INSERT INTO ' . DB_PREFIX . 'thread SET ';
		foreach($data as $key=>$val)
		{
			$sql .= "`{$key}` = \"{$val}\",";
		}
		$sql = trim($sql, ',');
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		$data['format_create_time'] = hg_tran_time($data['create_time']);
		$data['materail'] = $this->attachlib->get_attach_by_aid($aid);		
		$this->attachlib->delete_attach(urldecode($this->input['aid']));
		$this->addItem($data);
		$this->output();
	}
	
	function media()
	{
		$attach_info = $this->attach->upload($_FILES['media'], 'media');
		$attach_info = !empty($attach_info[0]) ? $attach_info[0] : array();
		if(!$attach_info)
		{
			$this->errorOutput("上传媒体数据出错");
		}
		$vid = $attach_info['id'];
		$tran_server = $attach_info['tran_server'];
		
		$attach_info = $attach_info['img'];
		$attach = $attach_info ? $attach = array(
			'host'=>$attach_info['host'],
			'dir'=>$attach_info['dir'],
			'filepath'=>$attach_info['filepath'],
			'filename'=>$attach_info['filename']
			) : array('type'=>$this->input['media_type']);
		$extend = array(
		'title'=>trim(rawurldecode($this->input['title'])),
		'comment'=>trim(rawurldecode($this->input['comment'])),
		'keywords'=>trim(rawurldecode($this->input['keywords'])),
		'vid'=>$vid,
		'tran_server'=>$tran_server,
		);
		$meida_type = $this->input['media_type'] ? $this->input['media_type'] : 'media';
		$aid = $this->attachlib->attach($attach, $meida_type, 'attach_tmp', $extend);
		$this->addItem(array('aid'=>$aid,'url'=>$attach));
		$this->output();
	}
	function image()
	{
		$attach_info = $this->attach->upload($_FILES['image'], 'img');
		$attach_info = !empty($attach_info[0]) ? $attach_info[0] : array();
		if(!$attach_info)
		{
			$this->errorOutput("上传图片出错");
		}
		$attach = array(
		'host'=>$attach_info['host'],
		'dir'=>$attach_info['dir'],
		'filepath'=>$attach_info['filepath'],
		'filename'=>$attach_info['filename']
		);
		$extend = array(
		//'keywords'=>urldecode($this->input['keywords']),
		);
		$aid = $this->attachlib->attach($attach, 'image', 'attach_tmp', $extend);
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($attach,1));
		$this->addItem(array('aid'=>$aid,'url'=>$attach));
		$this->output();
	}
	function audit()
	{
		$status = intval($this->input['status']);
		$thread_id = intval($this->input['id']);
		$thread_data = $this->db->query_first('SELECT id,tid FROM ' . DB_PREFIX . 'thread WHERE id='.$thread_id);
		
		if(!$thread_data)
		{
			$this->errorOutput("帖子不存在或已经被删除");
		}
		$tid = $thread_data['tid'];
		$sql = 'UPDATE ' .DB_PREFIX  . 'thread set status = '.$status . ' where id ='.$thread_id;
		$this->db->query($sql);
		//冗余数据
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'thread WHERE tid = '.$tid.' AND status = 1 ORDER BY create_time DESC limit 0,' . REDUN_LATEST_NUM;
		$query = $this->db->query($sql);
		$latest = $material = array();
		while($row = $this->db->fetch_array($query))
		{
			$latest[] = array(
			'content'=>$row['content'],
			'create_time'=>hg_get_format_date($row['create_time'], 8),
			'user_name'=>$row['user_name'],
			'user_id'=>$row['user_id'],
			'material'=>$row['aid'] ? explode(',', $row['aid']) : array(),
			);
			$material[]=$row['aid'];
		}
		if($material)
		{
			$material = @array_filter(array_unique((explode(',', implode(',', $material)))));
			if($material)
			{
				$material = $this->attachlib->get_attach_by_aid(implode(',', $material), true);
			}
		}
		if($latest && $material && $tid)
		{
			foreach($latest as $key=>$val)
			{
				$latest[$key]['material'] = !empty($val['material']) ? array_intersect($material, $val['material']): array();
 			}
 			$sql = 'UPDATE ' . DB_PREFIX .'topic SET latest="'.addslashes(json_encode($latest)).'" WHERE id = '.$tid;
 			$this->db->query($sql);
		}
		//重新计算更新总数
		$total = $this->db->query_first('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'thread WHERE status=1 and tid='.$tid);
		$this->db->query('UPDATE ' . DB_PREFIX . 'topic set total = '.$total['total'] . ' where id='.$tid);
		$this->addItem(array($thread_id));
		$this->output();
	}
	function delete_attach()
	{
		$aid = $this->input['aid'];
		$table = intval($this->input['is_use']) ? 'attach' : 'attach_tmp';
		$this->attachlib->delete_attach($aid, $table);
		$tid = intval($this->input['tid']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'topic WHERE id = '.$tid;
		$tdata = $this->db->query_first($sql);
		if($tdata && intval($this->input['is_use']))
		{
			$aids = $tdata['aid'] ? explode(',', $tdata['aid']) : array();
			if($aids)
			{
				//file_put_contents(CACHE_DIR . 'debu.txt', var_export($aids,1) . '--'. $aid);
				unset($aids[array_search($aid, $aids)]);
				$aids = $aids ? implode(',', $aids) : '';
				$this->db->query('UPDATE ' . DB_PREFIX . 'topic set aid = "'.$aids.'" WHERE id='.$tid);
			}
			
		}
		$this->addItem($aid);
		$this->output();
	}
	function get_transcode_progress()
	{
		$aid = urldecode($this->input['aid']);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'attach WHERE id IN('.$aid.')';
		$query = $this->db->query($sql);
		$data = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['extend'] = $extend = unserialize($row['extend']);
			if($extend)
			{
				$data[] = array('aid'=>$row['id'],'id'=>$extend['vid'], 'transcode_server'=>$extend['tran_server']);
			}
			$row['uri'] = unserialize($row['uri']);
			$attach[$row['id']] = $row;
		}
		if($data)
		{
			$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
			$return = array();
			foreach ($data as $v)
			{
				 $curl->setSubmitType('get');
				 $curl->initPostData();
				 $curl->addRequestData('id',$v['id']);
				 $curl->addRequestData('a' ,'get_transcode_status');
				 $curl->addRequestData('host',$v['transcode_server']['host']);
				 $curl->addRequestData('port',$v['transcode_server']['port']);
				 $ret = $curl->request('video_transcode.php');
				 if($ret['return'] && $ret['return'] == 'fail')
				 {
				 	 $info = array('transcode_percent' => 100,'id' => $v['id']);
				 }
				 else 
				 {
				 	 $info = array('transcode_percent'=> $ret['transcode_percent'], 'id'=>$ret['id']);
				 }
				 $info['aid']=$v['aid'];
				 $info['status'] = 0;
				 if($info['transcode_percent'] == 100)
				 {
				 	$livmedia = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . 'admin/');
				 	$livmedia->initPostData();
				 	$livmedia->addRequestData('id',$info['id']);
				 	$livmedia->addRequestData('a','detail');
				 	$vodinfo = $livmedia->request('vod.php');
				 	$vodinfo =$vodinfo[0];
				 	$info['m3u8'] = '';
					$info['duration'] = '';
					$info['is_audio'] = -1;
				 	if(is_array($vodinfo) && !empty($vodinfo))
				 	{
				 		$info['status'] = $vodinfo['status'];
					 	$info['m3u8'] = $vodinfo['is_audio'] ? str_replace('m3u8', 'mp4', $vodinfo['video_m3u8']) : $vodinfo['video_m3u8'];
					 	$info['duration'] = $vodinfo['duration'];
					 	$info['is_audio'] = $vodinfo['is_audio'];
				 	}
				 }
				 $return[] = $info;
			}
			if($return)
			{
				foreach($return as $val)
				{
					if(isset($val['status']) && ($val['status']==1))
					{
						$uri = $attach[$val['aid']]['uri'];
						$uri['m3u8'] = $val['m3u8'];
						$uri['duration'] = $val['duration'];
						$type = '';
						if($val['is_audio']>=0)
						{
							$type = $val['is_audio'] ? 'audio' : 'video';
						}
						if($type)
						{
							$type = ',type="'.$type.'"';
						}
						$sql = 'UPDATE ' . DB_PREFIX . 'attach set uri="'.addslashes(serialize($uri)).'"'.$type.' where id='.$val['aid'];
						$this->db->query($sql);
					}
					$this->addItem($val);
				}
			}
			$this->output();
		}
	}
}
include ROOT_PATH . 'excute.php';
?>