<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: mms_call.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class mmsCall extends appCommonFrm
{	
	private $status;
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/status.class.php');
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		global $gUser;

		$apikeys = $_REQUEST['apikey'];//判断验证
		$mobile = $_REQUEST['mobile']?$_REQUEST['mobile']:0;
		
		if($apikeys != PRIVATE_KEY)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		if(!$mobile)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
		$user = $this->mUser->getUserByCellphone($mobile);
		if(!is_array($user))
		{
			$this->errorOutput(OBJECT_NULL);
		}

		$gUser = $user;
		$this->input['pass'] = $user['password'];
		$this->status = new status();

		$post = $_REQUEST['post'];
		$type = $space = $filenames = $ret ="";
		$text = $_REQUEST['subject']." ";
		$pic = 0;
		$arr = unserialize(stripslashes($_REQUEST['attaches']));
		if($post)
		{
			$twipai_upload_url = $_REQUEST['twipai_upload_url'];
			if(is_array($arr))
			{
				foreach($arr as $k => $v)
				{
					$file = file_get_contents($twipai_upload_url.$v['file_path'].$v['file_name']);
					$snap = ROOT_PATH."cache/".$v['file_name'];
					file_put_contents($snap,$file);//生成图片
					$text .= $v['description'] ." ";
					$filenames .= $space.$snap;
					$space = ',';
				}
			}
			
			$ret = $this->status->uploadeImageMore($filenames);
			$type = $ret['type']?"1,":"0,";
			if(is_array($ret[0]))
			{
				 $pic = $ret[0]['id'];
			}
		}
		else 
		{
			$text .= $_REQUEST['text'];
		}

		$text = trim($text)?trim($text):'通过彩信分享图片';
		$source = '<a href="'.SNS_UCENTER.'cellphone.php'.'" blank="_target">彩信</a>';
		$id = 0;
		$status_info = $this->status->update($text,$source,$id,0,$type ,$pic,"","",1);
		
		if(is_array($ret))
		{
			foreach($ret as $key => $value)
			{
				$info = $this->status->updateMedia($status_info['id'], $value['id']);
			}
		}
	
		if($text)//同步发布点滴(注：转发不同步)
		{
			$this->status_bind($status_info['id'],$status_info['text'],$pic);
		}

		if($status_info['id'])
		{
			echo SNS_MBLOG."show.php?id=".$status_info['id'];
		}
	}
	
	private function status_bind($id,$text,$pic_id)
	{
		$status = new status();
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();	
		$bind_info = $this->mUser->get_bind_info(); //获取绑定信息
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
    			    $content = $oauth->update($content);//同步发送 					    	
			    	//$content = $oauth->post( $oauth->updateURL() , array( 'status' =>  $content) );//同步发送	
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
	
}

$out = new mmsCall();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();


//$exif = exif_read_data($file,'EXIF');



?>