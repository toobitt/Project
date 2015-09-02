<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: dispose.php 4602 2011-09-27 03:26:59Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
require(ROOT_PATH . 'lib/class/status.class.php');
require_once(ROOT_PATH . 'lib/user/user.class.php');

class dispose extends uiBaseFrm
{	
	private $info;
	private $status;
	function __construct()
	{
		parent::__construct();
//		$this->load_lang('global');
		$this->status = new status();
		$this->info = new user();		
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	* 发布，转发点滴的处理方法
	* 
	*/	
	public function update()
	{
		if($this->user['id'])
		{
			$text = $this->input['status']?$this->input['status']:"";
			$media_id = $this->input['media_id'];
			$group_id = $this->input['group_id'];
			$ret = $this->status->verifystatus();
			if(!$media_id)
			{
				if($ret['total']&&$ret['text'] === $text&&!$ret['reply_status_id'])
				{
					echo json_encode($ret);
					exit;
				}
			}
			$source = $this->input['source']?$this->input['source']:"";
			$id = $this->input['status_id']? $this->input['status_id']:0;
			$type = $this->input['type']?$this->input['type']:"";
			$pic = $this->input['pic_id'];
			$ret = $this->status->update($text,$source,$id,0,$type , $pic);		
			if($text)//同步发布点滴(注：转发不同步)
			{
				$this->status_bind($ret['id'],$ret['text'],$pic);
			}
			if(!$ret['total'])
			{
				if($media_id)
				{
					$info = $this->status->updateMedia($ret['id'], $media_id);
				}
				if($group_id)
				{
					//点滴同步发送到讨论区
					$infos = $this->pub_to_group($ret['id'], $group_id);
				}
			}
			echo json_encode($ret);
		}
		else
		{
			echo json_encode('false');
		}		
	}
	
	
	private function status_bind($id,$text,$pic_id)
	{
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();	
		$bind_info = $this->mUser->get_bind_info(); //获取绑定信息
		include_once (ROOT_PATH . 'lib/class/status.class.php');
		$status = new status();			
		if(!$bind_info)
		{
			//do nothing!	
		}
		else//已绑定点滴
		{
			//file_put_contents('d:/show.txt', '用户绑定了' , FILE_APPEND);
				
			$bind_info = $bind_info[0];
					
			if($bind_info['state'] == 1 && $bind_info['last_key']) //同步开启
			{
				//file_put_contents('d:/show.txt', '用户开启同步了' , FILE_APPEND);
							
				include_once (ROOT_PATH . 'lib/class/weibooauth.class.php');
			    $last_key = unserialize($bind_info['last_key']);
			    			
			    $oauth = new WeiboClient( WB_AKEY , WB_SKEY , $last_key['oauth_token'] , $last_key['oauth_token_secret'] );
			
				//$oauth = new WeiboOAuth( WB_AKEY , WB_SKEY , 'e9b1d743a687550cec725e65fd204b6c' , '119934aabf1632d426533505c0f02e70' );								
				
			    //判断是否发送了图片
			    if($pic_id)
			    {
			    	//file_put_contents('d:/show.txt', '用户发送了图片' , FILE_APPEND);
			    	
			    	//取出该图片的路径
			    	
			    	//获取媒体信息
			    	$pic_url = $status->getPicById($pic_id);
			    	
			    	$url = $pic_url[0]; 
			    	//$url = 'http://127.0.0.1/3.jpg';
			    	
			    	$content = $oauth->upload($text , $url);
			    }
			    else
			    {				    	
			    	$content = $text;				    					    	
					$pattern = "/#([\x{4e00}-\x{9fa5}0-9A-Za-z_-\s‘’“”'\"!\?$%&:;！？￥×\*\<\>》《]+)[\s#]/iu";
			    	
				    if(preg_match_all($pattern , $content , $topic))
					{
						include_once (ROOT_PATH . 'lib/class/shorturl.class.php');
						$shorturl = new shorturl();
						$link = '';
						$tmp_url = '';
						$topic_name = array();

						foreach ($topic[1] as $key => $value)
						{
							$tmp_url = SNS_MBLOG.'k.php?q='.urlencode($value);
							$short_url = $shorturl->shorturl($tmp_url);

							$link .= $short_url;										
						}					
						$content = $content . $link;							
					}
			    					    					    	
//			    	$content = $oauth->post( $oauth->updateURL() , array( 'status' =>  $content) );	
			    	$content = $oauth->update($content);//同步发送
			    } 
			    				    				
				$syn_id = $content['id'];   //返回点滴的ID	
				$type = $bind_info['type']; //绑定类型

				/**
				 * 记录同步发送的点滴id
				 */
				$status->syn_relation($id , $syn_id , $type);
			}
		}	
	}
	
	
	
	//同步发布点滴信息到讨论区
	public function pub_to_group($status_id="",$group_ids="")
	{
		$group_ids = $this->input['group_ids']?$this->input['group_ids']:$group_ids;
		$status_id = $this->input['status_id']?$this->input['status_id']:$status_id;
		$status_info = $this->status->show(intval($status_id));
		$status_info = $status_info[0];  
		$ip = $status_info['ip'];
		$content = hg_verify($status_info['text']);
		if(!empty($status_info['medias']))
		{
			$mediaInfo = $status_info['medias'];
			foreach($status_info['medias'] as $key => $mediaInfo)
			{
				$type = $mediaInfo['type']; 
				$content .= ($media_str = ($type > 0) ? (strstr($mediaInfo['link'],'.swf') ? '  [flash]'. $mediaInfo['link'] . '[/flash]  <br />' : '  [real]'. $mediaInfo['link'] . '[/real]  <br />') : '  [img]'. $mediaInfo['larger'] . '[/img]  <br />');
			}
				
		}
		
		$spe_char = array('#', '@');
		$spe_replace   = array('', '');
		$text  = str_replace($spe_char, $spe_replace, $status_info['text']);
		
		$title = mb_substr(hg_move_face($text),0,30,'utf-8');
		$groupids = explode(',',$group_ids);
		$tmp = array();
		foreach($groupids as $key => $idd)
		{
			if($idd)
			{
				$tmp[$idd] = $idd;
			}
		} 
		include_once (ROOT_PATH . 'lib/class/groups.class.php');
		$groups = new Group();
		 
		foreach($tmp as $gid)
		{  
			$rr = $groups->add_new_thread($gid,$title,$content,$ip);
		}
		 
		if(empty($rr))
		{
			//echo json_encode('false');
		}
		else
		{
			//echo json_encode('true');
		}
	}
	

	//删除一条点滴记录
	public function destroy_blog()
	{
		if($this->user['id'])
		{
			//测试数据
			//$this->input['statu_id'] = 23048;
			//传递参数
			$status_id = $this->input['status_id'];
			
			//删除博客信息
			$ret = $this->status->destroy($status_id);
			//用户博客信息数减一
			if($ret['0']['id'])
			{
				$user = new user();	
				$ret = $user->destroy_attention_count();
				echo json_encode($ret);
			}
			else
			{
				echo json_encode('false');
			}
			
		}
		else
		{
			echo json_encode('false');
		}
		
	}
	
	/**
	* 增加关注话题
	* 
	*/	
	public function addTopicFollow()
	{
		if(!$this->input['topic'])
		{
			echo json_encode('null');
		}
		else
		{
			$topic = trim($this->input['topic']);
			$topic_follow = $this->status->getTopicFollow();
			if(!count($topic_follow))
			{
				$info = $this->status->addTopicFollow($topic);
				echo json_encode($info);
			}
			else 
			{
				foreach($topic_follow as $value)
				{
					$topicTitle[] = $value['title']; 
				}
				if(in_array($topic,$topicTitle))
				{
					echo json_encode('false');
				}
				else
				{
					$info = $this->status->addTopicFollow($topic);
					echo json_encode($info);
				}
			}
		}
	}

	/**
	* 删除关注话题
	* 
	*/	
	public function delTopicFollow()
	{
		$topic = trim($this->input['topic']);
		$info = $this->status->delTopicFollow($topic);
		echo json_encode($info);
	}
	
	/**
	* 添加举报
	* 举报内容类型：1：帖子，2：视频，3：微博评论，4：相册，5：视频评论，6：相册评论，7：帖子回复
	*/
	
	public function add_report()
	{
		if(!$this->user['id'])
		{
			echo json_encode('login');
			exit;
		}
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->status = new status();
		$cid = $this->input['cid'];
		$uid = $this->input['uid'];
		$url = $this->input['url'];
		$type = $this->input['type'];
		
		/*include_once(ROOT_PATH . 'lib/class/shorturl.class.php');
		$shorturl = new shorturl($url);
		$url = $shorturl->shorturl($url);*/
		$content = trim($this->input['content'])?trim($this->input['content']):'我对这条记录有异议，特向你报告';
		if($cid&&$uid)
		{
			$ret = $this->status->create_report($cid,$uid,$type,$url,$content);
			echo json_encode($ret);
		}
		else 
		{
			echo json_encode('');
		}
	}
	
}
$out = new dispose();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>