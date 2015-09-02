<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/opinion.class.php');
include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/member.class.php');
require_once(ROOT_PATH . 'lib/class/ftp.class.php');
class contribute extends InitFrm
{
	static private $sortId = array();
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->opinion = new opinion();
		$this->publish_column = new publishconfig();
		$this->member = new member();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$offset,$count, $body = 0)
	{
		$limit = " limit {$offset}, {$count}";
		if ($body)
		{
			$field = ',cb.text';
			$leftjoin = ' LEFT JOIN '.DB_PREFIX.'contentbody cb ON c.id=cb.id ';
		}
		$sql = 'SELECT c.id as cid,c.*' . $field . ',s.name,m.host,m.dir,m.material_path,m.pic_name,cu.tel FROM '.DB_PREFIX.'content c  
				LEFT  JOIN  '.DB_PREFIX.'sort s ON c.sort_id = s.id 
				' . $leftjoin . '
				LEFT JOIN '.DB_PREFIX.'materials m ON m.materialid = c.material_id 
				LEFT JOIN '.DB_PREFIX.'content_user cu ON cu.con_id = c.id 
				WHERE 1 '.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$k = array();
		$ids = array();
		$new_member = array();
		$old_member = array();
		$avatar = array();
		$indexpic_id = array();
		while(!false ==($r = $this->db->fetch_array($q)))
		{
			if($r['material_id'])
			{
				$indexpic_id[$r['material_id']] = 1;
			}
			$r['pass_time'] 	= TIMENOW-$r['create_time'];
			$r['create_time'] 	= date('Y-m-d H:i:s',$r['create_time']);
			$r['zt'] 			= $r['audit'];
		/*
	switch ($r['audit'])
			{
				case  1: $r['audit'] = '未审核';$r['color'] = '#FF7F00';break;
				case  2: $r['audit'] = '已审核';$r['color'] = '#0000FF';break;
				case  3: $r['audit'] = '被打回';$r['color'] = '#FF0000';break;
				case  4: $r['audit'] = '敏感词';$r['color'] = '#FF1CAE';break;
				default: $r['audit'] = '未审核';$r['color'] = '#FF7F00';
			}
*/
			$r['audit']			= $this->settings['contribute_audit'][$r['zt']];
			$r['color']			= $this->settings['contribute_audit_color'][$r['zt']];
			if (!$r['user_name'])
			{
				$r['user_name'] = '匿名用户';
			}
			$r['name'] = $r['name'] ? $r['name'] : '未分类';
			$r['indexpic'] = array();
			if ($r['host'] && $r['dir'] && $r['material_path'] && $r['pic_name'])
			{
				$r['indexpic'] = array(
					'host'		=> $r['host'],
					'dir'		=> $r['dir'],
					'file_path'	=> $r['material_path'],
					'file_name'	=> $r['pic_name'],
				);
			}
         	//输出发布栏目
         	if ($r['column_id'])
         	{
         		$column_id = unserialize($r['column_id']);
         		if ($column_id)
         		{
         			$r['column_id'] = $column_id;
         		}else {
         			$r['column_id'] = array();
         		}
         	}
         	$ids[] = $r['cid'];
         	
         	if ($r['new_member']==1)
         	{
         		$new_member[$r['cid']] = $r['user_id'];
         	}
         	if ($r['new_member']==0)
         	{
         		$old_member[$r['cid']] = $r['user_id'];
         	}
         	
         /*
	if($r['is_follow'] == 1)
	        {
		        $r['is_follow_text'] = '已跟踪';
	        }
	        elseif($r['is_follow'] == 0)
	        {
		        $r['is_follow_text'] = '未跟踪';
	        }
*/         $r['is_follow_text']	= $this->settings['contribute_follow_return'][$r['is_follow']];
	        
	        if($r['satisfy_score'] > $r['unsatisfy_score'])
	        {
		        $r['satisfy'] = '满意';
	        }
	        elseif($r['satisfy_score'] < $r['unsatisfy_score'])
	        {
		         $r['satisfy'] = '不满意';
	        }
	        else
	        {
		         $r['satisfy'] = '';
	        }    	
        
         	//对手机帐户名就行处理
         	if (is_numeric($r['user_name']) && strlen($r['user_name'])==11)
         	{
         		//$r['user_name'] = str_replace(substr($r['user_name'], 3,4), '****', $r['user_name']);
         	}
         	$r['id'] = $r['cid'];
         	unset($r['cid']);
			$k[] = $r;
			
		}
		$content_ids = '';
		$memberinfos = array();
		//取所有图片
		if (!empty($ids))
		{
			$content_ids = implode(',', $ids) ;
			if (!empty($new_member))
			{
				$new_member_ids = implode(',', $new_member);
				//echo $new_member_ids;exit();
				if ($this->settings['App_members'])
				{
					$new_ret = $this->get_newUserInfo_by_ids($new_member_ids);
				}
				if ($new_ret && is_array($new_ret) && !empty($new_ret))
				{
					foreach ($new_member as $key=>$val)
					{
						
						foreach ($new_ret as $new_member_infor)
						{
							if ($val == $new_member_infor['member_id'])
							{
								$avatar[$key] = $new_member_infor['avatar'];
							}
							$memberinfos[$new_member_infor['member_id']] = $new_member_infor;
						}
					}
				}				
			}
			if (!empty($old_member))
			{
				$old_member_ids = implode(',', $old_member);
				if ($this->settings['App_member'])
				{
					$old_ret = $this->get_newUserInfo_by_ids($old_member_ids);
				}
				
				if ($old_ret && is_array($old_ret) && !empty($old_ret))
				foreach ($old_member as $key=>$val)
				{
					foreach ($old_ret as $old_user_infor)
					{
						if ($val == $old_user_infor['member_id'])
						{
							$avatar[$key] = $old_user_infor['avatar'];
						}
						$memberinfos[$old_user_infor['member_id']] = $old_user_infor;
					}
				}
			}
		}
		if ($content_ids)
		{
			//$pic = $this->all_pic($content_ids);
			$pic = array();
			//输出视频标识
			$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$content_ids.')' ;
	        $res = $this->db->query($sql);
	        $videolog = array();
	        $videoType = $this->getVideoConfig();
	        $vodids = array();
	        //hg_pre($indexpic_id,0);
	        while ($row = $this->db->fetch_array($res))
	        {
	        	//排除索引图
	        	if($indexpic_id[$row['materialid']])
	        	{
//	        		continue;
	        	}
	       		if (in_array($row['mtype'], explode(',', $videoType['hit']))){
	       			$videolog[$row['content_id']] = 1;
	       			//$vodids[$row['content_id']] = $row['vodid'];
	       		}
	       		///*
	       		if (!$row['vodid'] && $row['pic_name'])
	       		{
	       			$pic[$row['content_id']][] = array(
						'content_id'=>$row['content_id'],
						'material_id'=>$row['materialid'],
						'host'=>$row['host'],
						'dir'=>$row['dir'],
						'filepath'=>$row['material_path'],
						'filename'=>$row['pic_name'],
					);
	       		}
	       		//*/
	        }

	        /*
	        if (!empty($vodids))
	        {
	        	foreach ($vodids as $cid=>$vodid)
	        	{
	        		$vod_ret = $this->get_mediaserver_pic($vodid);
	        		if ($vod_ret)
	        		{
	        			$vodPicInfor[$cid] = $vod_ret;
	        		}
	        	}
	        }
	        */
			if (!empty($k))
			{
				foreach ($k as $key=>$val)
				{	
					//$k[$key]['pic'] = $pic[$val['id']];
					$k[$key]['videolog'] = $videolog[$val['id']];
					$k[$key]['avatar'] = $avatar[$val['id']] ? $avatar[$val['id']] :array();
					if ($val['user_id'])
					{
						$k[$key]['member'] = array(
							'member_id' => $memberinfos[$val['user_id']]['member_id'],	
							'member_name' => $memberinfos[$val['user_id']]['member_name'],	
							'mobile' => $memberinfos[$val['user_id']]['mobile'],	
						);
					}
					$k[$key]['pics'] = $pic[$val['id']];
					//$k[$key]['vodid'] = $vodids[$val['id']];
				}
				
			}
			
		}		
		return $k;
	}
	
	/**
	 * 
	 * @Description  快速设置索引图
	 * @author Kin
	 * @date 2013-6-14 下午02:41:17
	 */
	public function show_pic($id)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id = '. $id;
		$query = $this->db->query($sql);
		$pic = '';
		$vodid = '';
		$ret = '';
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['vodid'])
			{
				$vodid = $row['vodid'];
			}
			else 
			{
				$pic[] = array(
						'content_id'=>$row['content_id'],
						'material_id'=>$row['materialid'],
						'host'=>$row['host'],
						'dir'=>$row['dir'],
						'filepath'=>$row['material_path'],
						'filename'=>$row['pic_name'],
					);
			}
		}
		if ($vodid) 
		{
			$vod_ret = $this->get_mediaserver_pic($vodid);
        	if ($vod_ret)
        	{
        		$vodPicInfor = $vod_ret;
        	}
		}
		if (!empty($pic) || !empty($vodPicInfor))
		{
			$ret = array(
				'pic'=>$pic,
				'vod_pic'=>$vodPicInfor,
				'vodid'=>$vodid,
			);
		}
		return $ret;
	} 
	
	public function get_mediaserver_pic($id)
	{
		if (!$id || !$this->settings['App_mediaserver'])
		{
			return false;
		}
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'].'admin/');
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('id',$id);
		$curl->addRequestData('count', VOD_PIC_NUM);
		$curl->addRequestData('stime',1);
		$ret = $curl->request('snap.php');
		$ret = $ret[0];
		if (!$ret)
		{
			return false;
		}
		if ($ret && is_array($ret))
		{
			foreach ($ret as $val)
			{
				if (!strpos($val, '_fail.jpg'))
				{
					$pics[] =  $val;
				}
			}
		}		
		return $pics;
	}
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'content c WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	
	public function detail($id)
	{
		$sql = 'SELECT c.*,cb.text,s.name,u.*  FROM  '.DB_PREFIX.'content c 
				LEFT JOIN '.DB_PREFIX.'contentbody cb ON c.id = cb.id  
				LEFT JOIN '.DB_PREFIX.'sort s ON c.sort_id = s.id
				LEFT JOIN '.DB_PREFIX.'content_user u ON c.id = u.con_id
				WHERE c.id = '.$id;
		$ret = $this->db->query_first($sql);
		$ret['brief'] = htmlspecialchars_decode($ret['brief']);
		$ret['text'] = htmlspecialchars_decode($ret['text']);
		$ret['pub_time'] = $ret['publish_time'] ? date("Y-m-d H:i",$ret['publish_time']) : '';
		//输出所有分类
			
		//根据索引图ID搜索图片
		$ret['indexpic'] = $this->get_indexpic($ret['material_id']);

		//视频地址
		$ret['video_url'] = $this->get_video($id);
		
		//多个视频地址
		$ret['video_urls'] = $this->get_videos($id);
		
		//图片信息
		$ret['pic'] = $this->get_pic($id,$ret['material_id']);
		
		$ret['create_time'] = date('Y-m-d H:i:s',$ret['create_time']);
		
		
		$ret['event_time'] = $ret['event_time'] ? date('Y-m-d H:i:s',$ret['event_time']) : '';
	    //发布栏目
        $column_id = unserialize($ret['column_id']) ? unserialize($ret['column_id']) : array();
        if (is_array($column_id))
        {
        	$ret['column_id'] = implode(',', array_keys($column_id));
        }
        $ret['open_bounty'] = BOUNTY;
        if ($ret['user_id'])
        {
	        if ($ret['new_member'] == 0)
	        {
	        	$userinfor = $this->get_userinfo_by_ids($ret['user_id']);
	        	$ret['avatar'] = array(
	        		'host'		=> $userinfor[$ret['user_id']]['host'],
	        		'dir'		=> $userinfor[$ret['user_id']]['dir'],
	        		'filepath'	=> $userinfor[$ret['user_id']]['filepath'],
	        		'filename'	=> $userinfor[$ret['user_id']]['filename'],
	        	);
	        }
	        if ($ret['new_member'] == 1)
	        {
	        	$userinfor = $this->get_newUserInfo_by_id($ret['user_id']);
	        	$ret['avatar'] = $userinfor['avatar'];
	        }
        } 
       /*
 
        if($ret['is_follow'] == 1)
        {
	        $ret['is_follow_text'] = '已跟踪';
        }
        elseif($ret['is_follow'] == 0)
        {
	        $ret['is_follow_text'] = '未跟踪';
        }
        

			 $ret['color'] = $this->settings['contribute_audit_color'][$ret['audit']];
			 $ret['audit'] = $this->settings['contribute_audit'][$ret['audit']];
*/      
        $ret['is_follow_text']	= $this->settings['contribute_follow_return'][$ret['is_follow']];
		//$ret['audit']	    = $ret['audit'] ? $ret['audit'] : 1;
		$ret['audit_text']	= $this->settings['contribute_audit'][$ret['audit']];
		$ret['color']		= $this->settings['contribute_audit_color'][$ret['audit']];
        //满意度
        if($ret['satisfy_score'] > $ret['unsatisfy_score'])
        {
	        $ret['satisfy'] = '满意';
        }
        elseif($ret['satisfy_score'] < $ret['unsatisfy_score'])
        {
	         $ret['satisfy'] = '不满意';
        }
        else
        {
	         $ret['satisfy'] = '';
        }    	
		return $ret;
	}
	public function show_opration($id)
	{
		$sql = 'SELECT c.*,cb.text,s.name,u.* FROM '.DB_PREFIX.'content c
				LEFT JOIN '.DB_PREFIX.'contentbody cb ON c.id = cb.id
				LEFT JOIN '.DB_PREFIX.'sort s ON c.sort_id =s.id
				LEFT JOIN '.DB_PREFIX.'content_user u ON c.id =u.con_id
				WHERE c.id='.$id;
		$ret =  $this->db->query_first($sql);
		$ret['create_time'] = date('Y-m-d H:i:s',$ret['create_time']);
		$ret['text'] = html_entity_decode($ret['text']);
	
	
		if ($ret['audit']==5)
		{
			$ret['audit']=3;
		}
		$ret['zt'] = $this->settings['contribute_audit'][$ret['audit']];
		$ret['color'] = $this->settings['contribute_audit_color'][$ret['audit']];
		$ret['pic'] = $this->get_pic($id);
		$ret['video_url'] = $this->get_video($id);
		return $ret;
	}
	//根据图片id获得索引图
	public function get_indexpic($id)
	{
		$sql = 'SELECT host,dir,material_path,pic_name FROM '.DB_PREFIX.'materials WHERE materialid = '.$id;
		$ret = $this->db->query_first($sql);
		$k =array();
		if ($ret)
		{
			$k = array(
				'host'=>$ret['host'],
				'dir'=>$ret['dir'],
				'file_path'=>$ret['material_path'],
				'file_name'=>$ret['pic_name'],
			);
		}
		return $k;		
	}
	
	//根据内容id获取所有图片
	public function get_pic($id,$indexpic_id='')
	{
		$sql = "SELECT * FROM ".DB_PREFIX.'materials WHERE content_id = '.$id;
		$query = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			
			if($row['is_vod_pic'])
			{
				continue;
			}
			
			//if($indexpic_id && $indexpic_id == $row['materialid'])
			{
				//continue;
			}
			if ($row['pic_name'])
			{
				$k[] = array(
					'material_id'=>$row['materialid'],
					'host' =>$row['host'],
					'dir'=>$row['dir'],		
					'file_path'=>$row['material_path'], 
					'file_name'=>$row['pic_name'],
				);
			}
		}
		return $k;
	}
		
	//根据内容id获取所有视频信息
	public function get_video($id)
	{
		$k = array();
		$sql = "SELECT * FROM ".DB_PREFIX.'materials WHERE content_id = '.$id.' AND vodid!=""';
		$res = $this->db->query_first($sql);
		$url = $res['host'].'/'.$res['dir'].MANIFEST;
		$vodid = $res['vodid'];
		
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'].'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','get_video');
		$curl->addRequestData('id',$vodid);
		$ret = $curl->request('vod.php');
		$ret = $ret[0];
		if (is_array($ret) &&  !empty($ret))
		{
			$arr = explode('.', $ret['video_filename']);
			$type = $arr[1];
			//$m3u8 = $ret['hostwork'].'/'.$ret['video_path'].str_replace($type, 'm3u8', $ret['video_filename']);
			$m3u8 = $res['host'].'/'.$res['dir'].$res['filename'].'.m3u8';
			$mp4Url = $res['host'].'/'.$res['dir'].$res['filename'].'.'.$res['mtype'];
			$vImgInfo = $ret['img_info'];
			$duration = $ret['duration'];
			$totalsize = $ret['totalsize'];
			$is_audio = $ret['is_audio'];
		}
		if ($vodid)
		{
			$k[] = array(
			'm3u8'=>$m3u8,
			'url'=>$url,
			'mp4Url'=>$mp4Url,
			'imgInfo'=>$vImgInfo,
			'vodid'=>$vodid,
			'duration'=>$duration,
			'totalsize'=>$totalsize,
			'is_audio'=>$is_audio,
			);
		}
		/*
		$query = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			if ($row['vodid'])
			{
				$v['m3u8'] = $row['host'].'/'.$row['dir'].$row['filename'].'.m3u8';
				$v['url'] = $row['host'].'/'.$row['dir'].MANIFEST;
				$v['vodid'] = $row['vodid'];
				$k[] = $v;
			}
		}
		*/
		return $k;
	}
	
	//根据内容id获取所有视频信息
	public function get_videos($id)
	{
		$k = array();
		$sql = "SELECT vodid,host,dir,filename,mtype FROM ".DB_PREFIX.'materials WHERE content_id = '.$id.' AND vodid!=""';
		$q = $this->db->query($sql);
		
		
		$vod_info = array();
		$vodids = array();
		while ($res = $this->db->fetch_array($q))
		{
			$m3u8 = $mp4Url = $url = '';
			$m3u8 = $res['host'].'/'.$res['dir'].$res['filename'].'.m3u8';
			$mp4Url = $res['host'].'/'.$res['dir'].$res['filename'].'.'.$res['mtype'];
			$url = $res['host'].'/'.$res['dir'].MANIFEST;
			
			$vod_info[$res['vodid']]['url'] = $url;
			$vod_info[$res['vodid']]['m3u8'] = $m3u8;
			$vod_info[$res['vodid']]['mp4Url'] = $mp4Url;
			$vodids[] = $res['vodid'];
		}
		
		
		if(!empty($vodids))
		{
			
			$vids = implode(',', $vodids);
			
			$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'].'admin/');
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			
			$curl->initPostData();
			$curl->addRequestData('a','get_videos');
			$curl->addRequestData('id',$vids);
			
			$res = array();
			$res = $curl->request('vod.php');
			
			
			if (is_array($res) &&  !empty($res))
			{
				
				foreach ($res as $vid => $ret)
				{
					if (!$vod_info[$vid])
					{
						continue;
					}
					
					$k[] = array(
						'm3u8'=>$vod_info[$vid]['m3u8'],
						'url'=>$vod_info[$vid]['url'],
						'mp4Url'=>$vod_info[$vid]['mp4Url'],
						'imgInfo'=>$ret['img_info'],
						'vodid'=>$vid,
						'duration'=>$ret['duration'],
						'totalsize'=>$ret['totalsize'],
						'is_audio'=>$ret['is_audio'],
					);
				}
			}
		}
		return $k;
	}
	
    //根据内容id获取相关信息
    public function get_contentinfo($id)
    {
    	$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$id.')';
    	$query = $this->db->query($sql);
    	$k = array();
    	while (!false == ($row = $this->db->fetch_array($query)))
    	{
    		$k[$row['id']] = $row;
    	}
    	return $k;
    }
	
    /**
     * 
     * @Description: 单图片上传入库
     * @author Kin
     * @date 2013-4-13 下午04:09:39
     */
	public function upload($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 * 
	 * @Description 删除图片
	 * @author Kin
	 * @date 2013-4-16 上午10:08:20
	 */
	public function del_pic($ids)
	{
		
		$sql = 'DELETE FROM '.DB_PREFIX.'materials  WHERE  materialid IN ('.$ids.')';
		$this->db->query($sql);
		//搜索原始素材id，准备删除图片服务器上文件,此处注释，因为删除后回收站将无法恢复图片
		/*
		$sql = 'SELECT original_id FROM '.DB_PREFIX.'materials WHERE materialid IN('.$ids.')';
		$query  = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			$k[] = $row['original_id'];
		}
		$id = implode(',', $k);
		$this->material->delMaterialById($id,2);
		*/
		return true;		
	}
	
	/**
	 * 
	 * @Description 删除视频
	 * @author Kin
	 * @date 2013-4-16 上午10:13:18
	 */
	public function del_video($ids)
	{
		
		$sql = 'DELETE FROM '.DB_PREFIX.'materials  WHERE  materialid IN ('.$ids.')';
		$this->db->query($sql);
		//搜索原始素材id，准备删除视频服务器上文件,此处注释，因为删除后回收站将无法恢复视频
		/*
		$sql = 'SELECT original_id FROM '.DB_PREFIX.'materials WHERE materialid IN('.$ids.')';
		$query  = $this->db->query($sql);
		$k = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			$k[] = $row['original_id'];
		}
		$id = implode(',', $k);	
		$curl = new curl($this->settings['video_api']['host'],$this->settings['video_api']['dir'],$this->settings['video_api']['token']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','delete');
		$curl->addRequestData('id',$id);
		$curl->request('vod_update.php');
		*/
		return true;
	}
	
	/**
	 * 
	 * @Description 更新索引图
	 * @author Kin
	 * @date 2013-4-16 上午09:32:46
	 */
	public function update_indexpic($mid,$cid)
	{
		$sql = 'UPDATE '.DB_PREFIX.'content SET material_id = '.$mid.' WHERE id = '.$cid;
		$this->db->query($sql);
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE materialid = '.$mid;
		$pic = $this->db->query_first($sql);
		if ($pic['host'] && $pic['dir'] && $pic['material_path'] && $pic['pic_name'])
		{
			$url = array(
					'host'		=> $pic['host'],
					'dir'		=> $pic['dir'],
					'file_path' => $pic['material_path'],
					'file_name' => $pic['pic_name'],
					'content_id'=> $cid,
					'material_id'=>$mid,
				);	
		}
		
		return $url;
	}
	
	public function update_indexpic_by_vod_pic($cid,$src,$vodid)
	{
		if (!$cid || !$src)
		{
			return false;
		}
		$material = $this->localMaterial($src, $cid);
		if (!$material)
		{
			return false;
		}
		$temp = array(
					'content_id'	=> $cid,
					'mtype'			=> $material['type'],						
					'original_id'	=> $material['id'],
					'host'			=> $material['host'],
					'dir'			=> $material['dir'],
					'material_path' => $material['filepath'],
					'pic_name'		=> $material['filename'],
					'imgwidth'		=> $material['imgwidth'],
					'imgheight'		=> $material['imgheight'],
					'is_vod_pic'	=> 1,
					);
		//是否存在视频截图
		$sql = 'SELECT materialid FROM '.DB_PREFIX.'materials WHERE is_vod_pic = 1 AND content_id = '.$cid;
		$ret = $this->db->query_first($sql);
		if ($ret)
		{
			$sql = 'UPDATE '.DB_PREFIX.'materials SET ';
			foreach ($temp as $key=>$val)
			{
				$sql .= $key.'="'.$val.'",';
			}
			$sql = rtrim($sql,',');
			$sql .= ' WHERE materialid = '.$ret['materialid']; 
			$this->db->query($sql);
			$mid = $ret['materialid'];
		}
		else 
		{
			//插入图片纪录
			$mid = $this->upload($temp);
		}
		
		//更新索引图
		$sql = 'UPDATE '.DB_PREFIX.'content SET material_id ='.$mid.' WHERE id = '.$cid;
		$this->db->query($sql);
		//更新视频索引图
		if ($this->settings['App_livmedia'])
		{
			$arr = array(
				'host'=>$material['host'],
				'dir'=> $material['dir'],
				'filepath'=>$material['filepath'],
				'filename'=>$material['filename'],
			);
			$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('a', 'update_img');
			$curl->addRequestData('id',$vodid);
			$curl->addRequestData('img_info',serialize($arr));
			$curl->addRequestData('html',true);
			$ret = $curl->request('vod.php');
		}
		if ($material['host'] && $material['dir'] && $material['filepath'] && $material['filename'])
		{
			$url = array(
					'host'		=> $material['host'],
					'dir'		=> $material['dir'],
					'filepath' => $material['filepath'],
					'filename' => $material['filename'],
					'content_id'=> $cid,
					'material_id'=>$mid,
				);	
		}
		return $url;
	}
	
	//更新爆料主表
	public function update_content($data,$id)
	{
		if (!$id || !is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'content SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id; 
		$this->db->query($sql);
		return true;
	}
	//更新内容表
	public function update_contentbody($content,$id)
	{
		if (!$content || !$id)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'contentbody SET text = "'.$content.'" WHERE id = '.$id;
		$this->db->query($sql);
		return true;
	}
	
	//删除信息表
	public function del_content($ids)
	{
		if (!$ids)
		{
			return false;
		}
		
		$sql = 'DELETE FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return true;
	}
	//删除内容表
	public function del_contentbody($ids)
	{
		if (!$ids)
		{
			return false;
		}
		
		$sql = 'DELETE FROM '.DB_PREFIX.'contentbody WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return true;
	}
	//删除素材表
	public function del_materials($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$p = array();
		$v = array();
		while (!false == ($row = $this->db->fetch_array($query)))
		{
			if ($row['pic_name'])
			{
				$p[] = $row['materialid'];
			}
			if ($row['vodid'])
			{
				$v[] = $row['materialid'];
			}
		}
		if (!empty($p))
		{
			$pids = implode(',', $p);
			$this->del_pic($pids);
		}
		if (!empty($v))
		{
			$vids = implode(',', $v);
			$this->del_video($vids);
		}
		return true;		
	}
	
	/**
	 * 
	 * @Description	删除报料用户信息表
	 * @author Kin
	 * @date 2013-4-25 上午09:09:13
	 */
	public function del_con_user($ids)
	{
		if (!$ids)
		{
			return false;
		}
		
		$sql = 'DELETE FROM '.DB_PREFIX.'content_user WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return true;
	}
	/**
	 * 
	 * @Description  改变审核状态  1－未审，2－已审，3－打回
	 * @author Kin
	 * @date 2013-4-24 下午03:31:01
	 */
	public function changeAudit($state,$id)
	{
		if (!$state)
		{
			return false;
		}

		$sql = 'UPDATE '.DB_PREFIX.'content SET audit = '.$state.' WHERE id = '.$id;
		$this->db->query($sql);
		/**
		 * 未看到有返回暂时注释
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id='.$id;
		$ret = $this->db->query_first($sql);
		*/
		return $state;
	}
	
	/**
	 * 
	 * @Description  多条报料审核
	 * @author Kin
	 * @date 2013-4-20 上午10:24:36
	 */
	public function audit($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'content SET audit = 2 WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		
		return $ids;
	}
	//多个打回	
	public function back($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'content SET audit = 3 WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		//循环插队列，存在效率问题
		while ($row = $this->db->fetch_array($query))
		{
			if (!empty($row['expand_id']))
			{
				$op = 'delete';
			}
			$this->publish_insert_query($row['id'], $op);
		}
		return $ids;
	}
	//添加爆料内容
	public function add_contentbody($data)	
	{
		$sql  = 'REPLACE INTO '.DB_PREFIX.'contentbody SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	//添加爆料信息
	public function add_content($data)
	{
		if (!$data || !is_array($data))
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'content SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$update_sql = 'UPDATE '.DB_PREFIX.'content set order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($update_sql);
		return $id;
	}
	
	/**
	 * 
	 * @Description: 上传图片服务器 
	 * @author Kin
	 * @date 2013-4-13 下午04:04:52
	 */
	public function uploadToPicServer($file,$content_id)
	{
		$material = $this->material->addMaterial($file,$content_id); //插入图片服务器
		return $material;
	}
	//根据url上传图片
	public function localMaterial($url,$cid)
	{
		$material = $this->material->localMaterial($url,$cid);
		return $material[0];
	}
	
	/**
	 * 
	 * @Description 视频上传
	 * @author Kin
	 * @date 2013-4-13 下午04:34:29
	 */
	public function uploadToVideoServer($file,$title,$brief)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($file);
		$curl->addRequestData('title', $title);
		$curl->addRequestData('comment',$brief);
		$curl->addRequestData('vod_leixing',2);
		$ret = $curl->request('create.php');
		return $ret[0];
	}
	
	//每一级分类
	public function sort($id, $exclude_id = 0, $flag = 0, $userInfor = '', $limitids = '',$fids ='',$orderby = '')
	{
		$cond = '';
		if ($exclude_id)
		{
			$cond .= ' AND id NOT IN (' . $exclude_id . ')';
		}
		if ($limitids)
		{
			$cond .= ' AND id IN (' . $limitids . ')';
		}
		if ($id > -1)
		{
			$cond .= ' AND fid=' . intval($id);
		}
		if($fids)
		{
			$cond .= ' AND id IN (' . $fids .')';
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'sort WHERE 1' . $cond .$orderby;
		$query = $this->db->query($sql);
		$k = array();
		while(!false == ($row = $this->db->fetch_array($query)))
		{
			if($fids)  //去除掉子分类中包含的当前分类id
			{
				$child = explode(',',$row['childs']);
				$fid_key = array_search($fids,$child);
				unset($child[$fid_key]);
				$row['childs'] = implode(',',$child);
			}
			if ($flag && $id == 0)
			{
				if (in_array($row['id'], $userInfor['prms']['app_prms'][MOD_UNIQUEID]['nodes']))
				{
					if ($row['userinfo'])
					{
						$row['userinfo'] = unserialize($row['userinfo']);
					}
					if ($row['image'])
					{
						$row['image'] = unserialize($row['image']);
					}
					$k[$row['id']] = $row;
				}
			}
			else 
			{
				if ($row['userinfo'])
				{
					$row['userinfo'] = unserialize($row['userinfo']);
				}
				if ($row['image'])
				{
					$row['image'] = unserialize($row['image']);
				}
				$k[$row['id']] = $row;
			}
		}
		return $k;
	}
	//获取所有分类
	public function allsort()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'sort ORDER BY order_id ASC';
		$query = $this->db->query($sql);
		$k = array();
		while(!false == ($row = $this->db->fetch_array($query)))
		{
			$k[$row['id']] = $row;
		}
		return $k;
	}
	//根据分类id获取所有的快捷输入
	public function fastInput($id)
	{
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$k = array();
		$sql = 'SELECT childs FROM '.DB_PREFIX.'sort WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		if ($ret)
		{
			$sql = 'SELECT input_sort FROM '.DB_PREFIX.'sort WHERE id IN ('.$ret['childs'].')';
			$query = $this->db->query($sql);
			while (!false == ($row = $this->db->fetch_array($query)))
			{
				$row['input_sort'] = explode(',', $row['input_sort']);
				foreach ($row['input_sort'] as $key=>$val)
				{
					if ($val)
					{
						$k[] = $val;
					}		
				}
			}
		}
		$k = array_unique($k);
		$sortIds = implode(',', $k);
		$res = $this->fastInput_by_sort($sortIds);
		return $res;
	}
	//根据快捷输分类获取快捷输入
	public function fastInput_by_sort($ids)
	{
		$k = array();
		if ($ids)
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'fastInput WHERE sort_id IN ('.$ids.') ORDER BY order_id ASC';
			$query = $this->db->query($sql);	
			while (!false == ($row = $this->db->fetch_array($query)))
			{
				if (!in_array($row['content'], $k))
				{
					$k[$row['id']] = $row['content'];
				}
			}
			//$k = array_unique($k);
		}	
		return $k;
	}
	/**
	 * 
	 * @Description  发布队列
	 * @author Kin
	 * @date 2013-4-24 上午11:50:06
	 */
	public function publish_insert_query($contributeId, $op, $column_id = array(), $publishTime = TIMENOW, $userInfo = array())
	{
		$id = intval($contributeId);
		if (empty($id) || empty($op))
		{
			return false;
		}
		$sql = "SELECT  *  FROM ".DB_PREFIX."content WHERE id = " . $id;
		$info = $this->db->query_first($sql);
		
		if (empty($column_id))
		{		
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}			
		}
		else 
		{
			$column_id = implode(',',$column_id);
		}
 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 			=> PUBLISH_SET_ID,
			'from_id'   		=> $info['id'],
			'class_id'			=> 0,
			'column_id' 		=> $column_id,
			'title'     		=> $info['title'],
			'action_type' 		=> $op,
			'publish_time'  	=> $publishTime,
			'publish_people'	=> $userInfo['user_name'],
			'ip'   				=> hg_getip(),
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	//生成xml文件
	public function forward_suobei($id)
	{
		//获取视频id
		$sql = 'SELECT content_id,vodid FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$id.') AND vodid !=""';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['content_id']] = $row['vodid'];
		}
		$ids = '';
		$ret = array();
		if (!empty($k))
		{
			//获取视频信息
			$ids = implode(',', $k);
			$keys = array_keys($k);
			$vodpath = array();
			
			//获取报料标题
			$sql = 'SELECT id,title FROM '.DB_PREFIX.'content WHERE id IN ('.implode(',', $keys).')';
			$query = $this->db->query($sql);
			$title = array();
			while ($row=$this->db->fetch_array($query))
			{
				$title[$row['id']] = $row['title'];
			}
			$title = array_combine($k, $title);
			$ftp = $this->settings['App_suobei']['ftp'];
			$ids = implode(',', $k);
			$ret = $this->get_vodinfo($ids,$ftp['host'],$ftp['username'],$ftp['password']);
			$vodpath = array();
			if (!empty($ret) && is_array($ret))
			{
				foreach ($ret as $key=>$val)
				{
					$vodpath[$val['id']] = $ret[$key];
				}
			}else {
				$this->errorOutput('ftp上传失败');
			}	
		}
		if (!empty($vodpath) && !empty($title))
		{
			//获取报料标题
			
			//写xml文件
			$this->vod_xml($vodpath,$title);
			//ftp上传
			//实例化ftp,并连接
			$ftp_config = array(
				'hostname' => $ftp['host'],
				'username' => $ftp['username'],
				'password' => $ftp['password'],
			);
			$ftp_up =new Ftp();
			if(!$ftp_up->connect($ftp_config))
			{
				$this->errorOutput('CAN NOT CONNECT FTP SERVER');
			}
			
			foreach($vodpath AS $k => $v)
			{
				$target_dir = $v['dir'] . '/' ;
				$target_path = $target_dir . $v['filename'].'.xml';
				$xml_filepath = $this->settings['App_suobei']['xmldir'].$v['filename'].'.xml';
				if(!file_exists($xml_filepath))
				{
					$this->errorOutput('CAN NOT FIND XML');
				}
				
				if(!$ftp_up->mkdir($target_dir))
				{
					$this->errorOutput('CAN NOT MAKE DIR');
				}
				
				if(!$ftp_up->upload($xml_filepath,$target_path))
				{
					$this->errorOutput('CAN NOT UPLOAD FILE');
				}
				
			}
			$ftp_up->close();	
			//更新状态位
			$sql = 'UPDATE '.DB_PREFIX.'content SET suobei=1 WHERE id IN ('.implode(',', $keys).')';
			$this->db->query($sql);	
		}
		return $id;	
	}
	//获取视频物理路径
	private  function get_vodinfo($id,$host,$username,$pass)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','upload');
		$curl->addRequestData('video_id',$id);
		$curl->addRequestData('hostname',$host);
		$curl->addRequestData('username',$username);
		$curl->addRequestData('password',$pass);
		$ret = $curl->request('ftp_upload.php');
		return $ret;		
	}
	private function vod_xml($data,$title)
	{  
		foreach ($data as $key=>$val)
		{
			$dom = new DOMDocument('1.0', 'utf-8');   
			$ClipItem = $dom->createElement('ClipItem');
			$ClipItem = $dom->appendChild($ClipItem);     
	 		$ClipName = $dom->createElement('ClipName');
			$ClipName=$ClipItem->appendChild($ClipName); 
			$ClipIn = $dom->createElement('ClipIn');
			$ClipIn=$ClipItem->appendChild($ClipIn); 
			$ClipOut = $dom->createElement('ClipOut');
			$ClipOut=$ClipItem->appendChild($ClipOut); 
			$ClipLength = $dom->createElement('ClipLength');
			$ClipLength=$ClipItem->appendChild($ClipLength); 
			$LockFlag = $dom->createElement('LockFlag');
			$LockFlag=$ClipItem->appendChild($LockFlag); 
			$KeepDays = $dom->createElement('KeepDays');
			$KeepDays=$ClipItem->appendChild($KeepDays); 
			$ClipNote = $dom->createElement('ClipNote');
			$ClipNote=$ClipItem->appendChild($ClipNote); 
			$Catalog = $dom->createElement('Catalog');
			$Catalog=$ClipItem->appendChild($Catalog);  
			$FileItem = $dom->createElement('FileItem');	
			$FileItem=$ClipItem->appendChild($FileItem);
			$FileName = $dom->createElement('FileName');	
			$FileName=$FileItem->appendChild($FileName); 
				
			$ClipName->appendChild($dom->createTextNode($title[$key]));			
			$ClipIn->appendChild($dom->createTextNode(0));
			$ClipOut->appendChild($dom->createTextNode(-1));			
			$ClipLength->appendChild($dom->createTextNode(-1));
			$LockFlag->appendChild($dom->createTextNode(0));
			$KeepDays->appendChild($dom->createTextNode(7));
			$ClipNote->appendChild($dom->createTextNode(''));
			$Catalog->appendChild($dom->createTextNode('\\\公共资源库\CUTV总台\济南台\电视电台类\都市\都市新女报\\'));
			$FileName->appendChild($dom->createTextNode($this->settings['App_suobei']['xmlpath'].$val['path']));
			if (!hg_mkdir($this->settings['App_suobei']['xmldir']) || !is_writeable($this->settings['App_suobei']['xmldir']))
			{
				$this->errorOutput(NOWRITE);
			}
			$dom->save($this->settings['App_suobei']['xmldir'].$val['filename'].".xml");				
		}
		
		
		 return true;
	}
	//更新用户信息
	public function user_info($data)
	{
		$sql = 'REPLACE INTO '.DB_PREFIX.'content_user SET ';
		foreach ($data as $key=>$val)
		{
			$sql.= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		return true;
	}
	//根据用户id获取用户信息,老会员
	public function get_userinfo_by_id($uid)
	{
		$ret = $this->member->getUserinfoById($uid);
		return $ret[0];
	}
	//根据用户id获取用户信息,老会员 ,批量
	public function get_userinfo_by_ids($uid)
	{
		$ret = $this->member->getMemberByIds($uid);
		return $ret[0];
	}
	//根据用户id获取用户信息，新会员,批量
	public function get_newUserInfo_by_ids($uid)
	{
		if (!$uid || !$this->settings['App_members'])
		{
			return false;
		}
		$ret = array();
		$curl = new curl($this->settings['App_members']['host'],$this->settings['App_members']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'show');
		$curl->addRequestData('member_id',$uid);
		$ret = $curl->request('member.php');
		return $ret;
			
	}
	
	//根据用户id获取用户信息，新会员
	public function get_newUserInfo_by_id($uid)
	{
		if (!$uid || !$this->settings['App_members'])
		{
			return false;
		}
		$ret = array();
		$curl = new curl($this->settings['App_members']['host'],$this->settings['App_members']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a', 'detail');
		$curl->addRequestData('member_id',$uid);
		$ret = $curl->request('member.php');
		$ret = $ret[0];
		if ($ret && is_array($ret))
		{
			if ($ret['extension'] && is_array($ret['extension']))
			{
				foreach ($ret['extension'] as $val)
				{
					if ($val['field'] == 'email')
					{
						$ret['email'] = $val['value'];
					}
					if ($val['field'] == 'add')
					{
						$ret['address'] = $val['value'];
					}
				}
			}
		}
		return $ret;
			
	}
	
	public function access_sync($data,$id)
	{
		if(!empty($data) && is_array($data))
		{
			$sql = "UPDATE ".DB_PREFIX."content SET ";
			$space = '';
			foreach($data as $k => $v)
			{
				$sql.= $space . $k ."='".$v."'";
				$space = ',';
			}
			$sql .= " WHERE id = " . $id;
			$this->db->query($sql);
			$sql = "SELECT audit,expand_id FROM ".DB_PREFIX."content WHERE id = " . $id;
			$info = $this->db->query_first($sql);
			if($info['audit'] == 2)
			{
				if(!empty($info['expand_id']))
				{
					$op = 'update';
				}
				else
				{
					$op = 'insert';
				}
				$this->publish_insert_query($id, $op);
			}
			else
			{
				if(!empty($info['expand_id']))
				{
					$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
				}		
				else 
				{
					$op = "";
				}
				$this->publish_insert_query($id, $op);				
			}
		}
		return $data;
	}
	public function all_pic($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE  1 AND vodid="" AND content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k =array();
		while ($row = $this->db->fetch_array($query))
		{		
			$k[] = array(
				'content_id'=>$row['content_id'],
				'material_id'=>$row['materialid'],
				'host'=>$row['host'],
				'dir'=>$row['dir'],
				'filepath'=>$row['material_path'],
				'filename'=>$row['pic_name'],
			);
		}
		return $k;
	}
	
	/**
	 * 
	 * @Description 报料的转发
	 * @author Kin
	 * @date 2013-4-13 下午05:47:48
	 */
	public function send_contribute($id)
	{
		//获取已转发的数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'content_forward WHERE cid IN ('.$id.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['cid']][] = $row['fid'];
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$id.')';
		$query = $this->db->query($sql);
		$sort = array();
		$sorts = array();
		$forward_id = array();
		$forward = array();
		$info = array();
		$relation = array(); 
		$return = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[$row['id']] = $row['sort_id'];
		}
		if (!empty($sorts))
		{
			$sort = array_unique($sorts);
			$sql = 'SELECT * FROM '.DB_PREFIX.'forward WHERE 1 AND is_open=1 AND sort_id IN ('.implode(',', $sort).')';
			$query = $this->db->query($sql);
			while ($row = $this->db->fetch_array($query))
			{
				$protocol = '';
				$request_type = '';
				$protocol = $this->settings['con_api_protocol'][$row['protocol']];
				$row['protocol'] = $protocol ? $protocol : 'HTTP';
				$request_type = $this->settings['con_request_type'][$row['request_type']];
				$row['request_type'] = $request_type ? strtolower($request_type) : 'post';
				$row['match_rule'] = unserialize($row['match_rule']);
				$forward[$row['sort_id']][$row['id']] = $row;
			}
			if (!empty($forward))
			{
				foreach ($sorts as $key=>$val)
				{
					if (in_array($val, array_keys($forward)))
					{
						$forward_id[$key] = $val;
					}
				}
				if (!empty($forward_id))
				{
					//获取满足条件的爆料
					$info = $this->get_contribute(implode(',', array_keys($forward_id)));
					foreach ($forward_id as $key=>$val)
					{
						$data = '';
						$data = $info[$key];
						
						if (!empty($data))
						{	
							foreach ($forward[$val] as $kk=>$vv)
							{						
								if ((is_array($k[$key]) && !in_array($kk, $k[$key])) || !$k[$key] || empty($k[$key]))
								{
									$ret = $this->forward_curl($data, $vv);
									if ($ret)
									{
										$return[] = array(
											'cid'=>$key,
											'fid'=>$vv['id'],
											'rid'=>addslashes($ret['id']),
										);
									}
								}
							
								
							}						
						}
					}
				}
			}
		}
		//转发关系入库
		if (!empty($return) && is_array($return))
		{
			foreach ($return as $key=>$val)
			{
				$this->content_forward_create($val);
			}
		}
		return true;
	}
	private function get_contribute($ids)
	{
		$sql = 'SELECT c.id AS cid,c.*,s.name,m.original_id,m.host,m.dir,m.material_path,m.pic_name,m.mtype,cb.* FROM '.DB_PREFIX.'content c  
				LEFT  JOIN  '.DB_PREFIX.'sort s ON c.sort_id = s.id 
				LEFT JOIN '.DB_PREFIX.'materials m ON m.materialid = c.material_id
				LEFT JOIN '.DB_PREFIX.'contentbody cb ON cb.id = c.id
				WHERE 1 AND c.id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$temp =  array();
			$temp['title'] = $row['title'];
			$temp['brief'] = $row['brief'];
			$temp['index_pic'] = array(); 
			if ($row['host'] && $row['dir'] && $row['material_path'] && $row['pic_name'])
			{
				$temp['index_pic'] = array(
					'id'=>$row['original_id'],
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'filepath'=>$row['material_path'],
					'filename'=>$row['pic_name'],
					'type'=>$row['mtype'],
				);
			}
			$temp['longitude'] = $row['longitude'];
			$temp['latitude'] = $row['latitude'];
			$temp['content'] = $row['text'];
			$temp['user_name'] = $row['user_name'];
			$k[$row['cid']] = $temp;
		}
		//获取图片和视频
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['vodid'])
			{
				$k[$row['content_id']]['video'] = array(
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'id'=>$row['vodid'],
					'type'=>$row['mtype'],
				);
			}else 
			{
				if ($row['host'] && $row['dir'] && $row['material_path'] && $row['pic_name'])
				{
					$k[$row['content_id']]['picture'][] = array(
						'id'=>$row['original_id'],
						'host'=>$row['host'],
						'dir'=>$row['dir'],
						'filepath'=>$row['material_path'],
						'filename'=>$row['pic_name'],
						'type'=>$row['mtype'],
					);
				}
			}
		}
		return $k;	
	}
	//转发
	private function forward_curl($data,$config)
	{
		$curl = new curl($config['host'],$config['dir']);
		$curl->setSubmitType($config['request_type']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		if ($config['match_rule']['way'] &&is_array($config['match_rule']['way']) && !empty($config['match_rule']['way']))
		{
		   foreach ($config['match_rule']['way'] as $key=>$val)
		   {
		   		if ($val ==1)
		   		{
		   			if ($config['match_rule']['dict'][$key] && $config['match_rule']['mark'][$key])
		   			{
				   		
		   				if ($config['match_rule']['dict'][$key]=='index_pic')
						{
							foreach ($data['index_pic'] as $kk=>$vv)
							{
								$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']',$vv);
							}
						}elseif ($config['match_rule']['dict'][$key]=='video')
						{
							foreach ($data['video'] as $kk=>$vv)
							{
								$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']',$vv);
							}
						}elseif ($config['match_rule']['dict'][$key]=='picture')
						{
							foreach ($data['picture'] as $kk=>$vv)
							{
								foreach ($vv as $kkk=>$vvv)
								{
									$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']'.'['.$kkk.']',$vvv);
								}		
							}
						}else {					
							$curl->addRequestData($config['match_rule']['mark'][$key],$data[$config['match_rule']['dict'][$key]]);
						}
		   			}
		   		}elseif ($val==2)
		   		{
		   			if ($config['match_rule']['value'][$key] && $config['match_rule']['mark'][$key])
		   			{
		   				$curl->addRequestData($config['match_rule']['mark'][$key],$config['match_rule']['value'][$key]);
		   			}		
		   		}
		   }
		}
		$ret = $curl->request($config['filename']);
		if (is_array($ret) && !empty($ret))
		{
			return $ret[0];
		}else {
			return false;
		}		
	}
	//内容和转发关系表
	private function content_forward_create($data)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.'content_forward SET ';
		foreach ($data as $key=>$val)
		{
			$sql.= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		return true;	
	}
	//删除转发的数据
	public function del_send_contribute($ids)
	{
		//获取内容和转发的关系表
		$sql = 'SELECT * FROM '.DB_PREFIX.'content_forward WHERE cid IN ('.$ids.')';
		$query=$this->db->query($sql);
		$relation = array();
		$fid = array();
		$rid = array();
		$del = array();
		$forward = array();
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$fid[] = $row['fid'];
			$rid[$row['fid']][] = $row['rid']; 
			$k[$row['id']] = $row;
			
		}
		if (!empty($fid))
		{
			$fid = array_unique($fid);
			//获取所有的配置
			$forward = $this->get_forward(implode(',', $fid));
			/*
			$sql = 'SELECT * FROM '.DB_PREFIX.'forward WHERE id IN ('.implode(',', $fid).')';
			$query = $this->db->query($sql);
			while ($row = $this->db->fetch_array($query))
			{
				$protocol = '';
				$request_type = '';
				$protocol = $this->settings['con_api_protocol'][$row['protocol']];
				$row['protocol'] = $protocol ? $protocol : 'HTTP';
				$request_type = $this->settings['con_request_type'][$row['request_type']];
				$row['request_type'] = $request_type ? strtolower($request_type) : 'post';
				$row['match_rule'] = unserialize($row['match_rule']);
				$forward[$row['id']] = $row;
			}
			*/
			if (!empty($forward))
			{
				foreach ($rid as $key=>$val)
				{
					$return = $this->del_forward_curl($forward[$key], implode(',', $val));
				}
			}
			$this->content_forward_delete(implode(',', array_keys($k)));
		}
		return true;
	}
	
	//根据配置id获取配置
	private function get_forward($ids)
	{
		//获取所有的配置
		$sql = 'SELECT * FROM '.DB_PREFIX.'forward WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$forward = array();
		while ($row = $this->db->fetch_array($query))
		{
			$protocol = '';
			$request_type = '';
			$protocol = $this->settings['con_api_protocol'][$row['protocol']];
			$row['protocol'] = $protocol ? $protocol : 'HTTP';
			$request_type = $this->settings['con_request_type'][$row['request_type']];
			$row['request_type'] = $request_type ? strtolower($request_type) : 'post';
			$row['match_rule'] = unserialize($row['match_rule']);
			$forward[$row['id']] = $row;
		}
		
		return $forward;
	}
	private function del_forward_curl($config,$ids)
	{
		$curl = new curl($config['host'],$config['dir']);
		$curl->setSubmitType($config['request_type']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','delete');
		$curl->addRequestData('id',$ids);
		$ret = $curl->request($config['filename']);
		return $ret;
	}
	//内容和转发关系表
	private function content_forward_delete($ids)
	{
		if ($ids)
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'content_forward WHERE id IN ('.$ids.')';
			$this->db->query($sql);
			return true;	
			
		}		
	}	
	
	/**
	 * 
	 * @Description 更新转发的数据
	 * @author Kin
	 * @date 2013-4-15 上午10:25:39
	 */
	public function update_send_contribute($ids)
	{
		if ($ids)
		{
			//检索配置
			$sql = 'SELECT * FROM '.DB_PREFIX.'content_forward WHERE cid IN ('.$ids.')';
			$query = $this->db->query($sql);
			$config = array();
			$cid = array();
			$fid = array();
			$data = array();
			$forward = array();
			$arr = array();
			while ($row = $this->db->fetch_array($query))
			{
				$config[$row['id']] = $row['cid'];
				$cid[$row['cid']][] = $row['fid'];
				$fid[$row['fid']][] = $row['rid'];
			}
			if (!empty($config))
			{
				$data = $this->get_contribute(implode(',', $config));
				foreach ($cid as $kkk=>$vvv)
				{
					foreach ($vvv as $vvvv)
					{
						$arr[] = $vvvv; 
					}
				}
				$arr = array_unique($arr);
				$forward = $this->get_forward(implode(',', $arr));
				$config = array_unique($config);
				foreach ($config as $key=>$val)
				{
					foreach ($cid[$val] as $kk=>$vv)
					{
						$this->_update_forward_curl($data[$val], $forward[$vv], implode(',', $fid[$vv]));
					}
				}
			}
		}
		return true;
	}
	
	
	private function _update_forward_curl($data,$config,$ids)
	{
		$curl = new curl($config['host'],$config['dir']);
		$curl->setSubmitType($config['request_type']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','update');
		$curl->addRequestData('id',$ids);
		if ($config['match_rule']['way'] &&is_array($config['match_rule']['way']) && !empty($config['match_rule']['way']))
		{
		   foreach ($config['match_rule']['way'] as $key=>$val)
		   {
		   		if ($val ==1)
		   		{
		   			if ($config['match_rule']['dict'][$key] && $config['match_rule']['mark'][$key])
		   			{
				   		
		   				if ($config['match_rule']['dict'][$key]=='index_pic')
						{
							foreach ($data['index_pic'] as $kk=>$vv)
							{
								$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']',$vv);
							}
						}elseif ($config['match_rule']['dict'][$key]=='video')
						{
							foreach ($data['video'] as $kk=>$vv)
							{
								$curl->addRequestData($config['match_rule']['mark'][$key].'['.$kk.']',$vv);
							}
						}elseif ($config['match_rule']['dict'][$key]=='picture')
						{
							foreach ($data['picture'] as $kk=>$vv)
							{
								foreach ($vv as $kkk=>$vvv)
								{
									$curl->addRequestData($config['match_rule']['mark'][$key][$kk].'['.$kkk.']',$vvv);
								}		
							}
						}else {					
							$curl->addRequestData($config['match_rule']['mark'][$key],$data[$config['match_rule']['dict'][$key]]);
						}
		   			}
		   		}elseif ($val==2)
		   		{
		   			if ($config['match_rule']['value'][$key] && $config['match_rule']['mark'][$key])
		   			{
		   				$curl->addRequestData($config['match_rule']['mark'][$key],$config['match_rule']['value'][$key]);
		   			}		
		   		}
		   }
		}
		$ret = $curl->request($config['filename']);
		if (is_array($ret) && !empty($ret))
		{
			return $ret[0];
		}else {
			return false;
		}		
	}
	
	/**
	 * 
	 * @Description 报料和视频的审核状态同步
	 * @author Kin
	 * @date 2013-4-15 上午09:17:50
	 */
	public function video_audit($ids,$audit)
	{
		if ($ids)
		{
			$sql = 'SELECT vodid FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.') AND vodid !=""';
			$query = $this->db->query($sql);
			$k = array();
			while ($row = $this->db->fetch_array($query))
			{
				$k[] = $row['vodid'];
			}
			if (!empty($k))
			{
				$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'].'admin/');
				$curl->setSubmitType('post');
				$curl->setReturnFormat('json');
				$curl->initPostData();
				$curl->addRequestData('a', 'audit');
				$curl->addRequestData('id', implode(',', $k));
				$curl->addRequestData('audit',$audit);
				$ret = $curl->request('vod_update.php');
				if ($ret && is_array($ret))
				{
					return true;
				}
			}
		}
	}
	//通过分类id 查询转发信息
	public function check_sort($id)
	{
		if (isset($id))
		{
			$sql = 'SELECT sort_id FROM '.DB_PREFIX.'content WHERE id = '.$id;
			$ret = $this->db->query_first($sql);
			if ($ret['sort_id'])
			{
				$sql = 'SELECT title FROM '.DB_PREFIX.'forward WHERE sort_id = '.$ret['sort_id'].' AND is_open =1';
				$query = $this->db->query($sql);
				$k = array();
				$return = '';
				while ($row = $this->db->fetch_array($query))
				{
					$k[] = $row['title'];
				}
				if (!empty($k))
				{
					$return = implode(',', $k);
				}
				if ($return)
				{
					return $return;
				}else {
					return false;
				}
				
			}else {
				return false;
			} 
			
		}else 
		{
			return false;
		}	
	}
	
	/**
	 * 
	 * @Description: 获取上传图片的类型
	 * @author Kin
	 * @date 2013-4-13 下午03:50:44
	 */
	public function getPhotoConfig()
	{
		$ret = $this->material->get_allow_type();
		if (!$ret) 
		{
			return false;
		}
		$photoConfig = array();
		if(!$type)
		{
			if (is_array($ret['img']) && !empty($ret['img']))
			{
				$img_arr = array_keys($ret['img']);
				foreach ($img_arr as $type)
				{
					$photoConfig['type'][] =  'image/'.$type;
				}
				$photoConfig['hint'] = implode(',', $img_arr);
			}
		}
		else if($type == 'doc')
		{
			if (is_array($ret['doc']) && !empty($ret['doc']))
			{
				$pdf_arr = array_keys($ret['doc']);
				foreach ($pdf_arr as $type)
				{
					$photoConfig['type'][] =  'application/'.$type;
				}
				$photoConfig['hint'] = implode(',', $pdf_arr);
			}
		}
		return $photoConfig;		
	}
	
	/**
	 * 
	 * @Description  获取视频的配置
	 * @author Kin
	 * @date 2013-4-13 下午04:48:54
	 */
	public function getVideoConfig()
	{
		$videoConfig = array();
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','__getConfig');
		$ret = $curl->request('index.php');
		if (empty($ret) || !is_array($ret))
		{
			return false;
		}
		$temp = explode(',', $ret[0]['video_type']['allow_type']);
		$videoConfig['type'] = $temp;
		if (is_array($temp) && !empty($temp))
		{
			foreach ($temp as $val)
			{
				$videoType[] = ltrim($val,'.');
				//$videoConfig['type'][] = 'video/'.ltrim($val,'.');
			}
			$videoConfig['hit'] = implode(',', $videoType);
			
		}
		return $videoConfig;
	}
	
	/**
	 * 
	 * @Description 部门认领
	 * @author Kin
	 * @date 2013-5-23 上午10:41:01
	 */
	public function claim($ids,$user)
	{
		//查询未认领的报料，防止重复认领
		$sql = 'SELECT id FROM '.DB_PREFIX.'content WHERE claim_org_id = 0 AND id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$claim_ids = array();
		while ($row = $this->db->fetch_array($query))
		{
			$claim_ids[] = $row['id'];
		}
		if (!empty($claim_ids))
		{
			$claim_ids = implode(',', $claim_ids);
			$sql = 'UPDATE '.DB_PREFIX.'content SET claim_org_id = '.intval($user['org_id']).'
				,claim_org="'.addslashes($user['org_name']).'" WHERE id IN ('.$claim_ids.') AND claim_org_id = 0' ;
			$this->db->query($sql);
			$ret = array(
				'id'=>$claim_ids,
				'org_name'=>$user['org_name'],
			);
		}
		return $ret;
	}
	
	public function off_claim($ids)
	{
		$sql = 'UPDATE '.DB_PREFIX.'content SET claim_org_id = 0, claim_org="" WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$ret = array(
				'id'=>$ids,
				'msg'=>'认领',
		);
		return $ret;
	}
	//百度坐标转换为GPS坐标
	public function FromBaiduToGpsXY($x,$y)
	{
	    $Baidu_Server = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
	    $result = @file_get_contents($Baidu_Server);
	    $json = json_decode($result);  
	    if($json->error == 0)
	    {
	        $bx = base64_decode($json->x);     
	        $by = base64_decode($json->y);  
	        $GPS_x = 2 * $x - $bx;  
	        $GPS_y = 2 * $y - $by;
	        return array('GPS_x' => $GPS_x,'GPS_y' => $GPS_y);//经度,纬度
	    }
	    else
	    {
	    	return false;//转换失败
	    }
	}
	
	//GPS坐标转换为百度坐标
	public function FromGpsToBaiduXY($x,$y)
	{
		$url = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$info = json_decode($response,1);
		if($info && !$info['error'])
		{
			unset($info['error']);
			$info['x'] = base64_decode($info['x']);
			$info['y'] = base64_decode($info['y']);
			return $info;
		}
	}
	//分类异常数据处理
	public function sortException($sort = 0)
	{
		$sort = intval($sort);
		if (!intval($sort))
		{
			return 0;
		}
		$sql = 'SELECT id FROM '.DB_PREFIX.'sort WHERE id ='.intval($sort);
		$ret = $this->db->query_first($sql);
		if (!$ret)
		{
			return 0;
		}
		else
		{
			return $sort;	
		}
	}
}