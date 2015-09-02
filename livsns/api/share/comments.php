<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','share');
class commentsApi extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/public.class.php');
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->pub = new publicapi();
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 根据系统id,分享平台id  
	 * @name share
	 * @access public
	 * @author 
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return 
	 */
	public function comments()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$plat_type = intval($this->input['plat_type']);
		$weibo_id = urldecode($this->input['weibo_id']);
		$comment_id = urldecode($this->input['comment_id']);
		$comment = urldecode($this->input['comment']);
		$comment_ori = intval($this->input['comment_ori']);//当评论转发微博时，是否评论给原微博，0：否、1：是，默认为0。 
		$token = urldecode($this->input['access_plat_token']);
		//先判断token有没有过期
		if($token)
		{
			$checktoken = $this->pub->share_check_token($token);
		}
		else
		{
			$checktoken['msg'] = false;
		}
		if($checktoken['msg'] !== 'new' && $checktoken['msg'])
		{
			include_once(CUR_CONF_PATH . 'lib/'.$this->settings['share_plat'][$checktoken['data']['type']]['name'].'_oauth.php');
			$action = $this->settings['share_plat'][$checktoken['data']['type']]['name'].'_comments';
			$ret = $this->$action($checktoken,$weibo_id,$comment,$comment_ori,$comment_id);
			if(!empty($ret['error']))
			{
				$this->errorOutput($ret['error']);
			}
			else
			{
				$this->addItem($ret);
				$this->output();
			}
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			include(CUR_CONF_PATH.'lib/oauthlogin.class.php');
			$oauthlogin = new oauthlogin();
			if($appid && $platid)
			{
				$ret = $oauthlogin->oauthlogin($appid,$platid,$token);
			}
			else
			{
				if($platid || !$plat_type)
				{
					$this->errorOutput('NO_APPID_PLATID');
				}
				else
				{
					$plat = $this->obj->get_plat_by_type($appid,$plat_type);
					if(empty($plat))
					{
						$this->errorOutput('NO_ANY_PLAT');
					}
					$ret = $oauthlogin->oauthlogin($appid,$plat['id'],$token);
				}
			}
			$ret['error'] = 1;
			$this->addItem($ret);
			$this->output();			
		}
	}
	
	public function sinaweibo_comments($checktoken,$weibo_id,$comment,$comment_ori,$comment_id)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		if($comment_id)
		{
			$result = $c->comments($this->settings['share_plat'][$checktoken['data']['type']]['comments_commenturl'],$weibo_id,$comment,$comment_ori,$comment_id,'',true);
		}
		else
		{
			$result = $c->comments($this->settings['share_plat'][$checktoken['data']['type']]['commentsurl'],$weibo_id,$comment,$comment_ori,$comment_id,'',true);
		}
		
		if(empty($result['created_at']))
		{
			$ret['error'] = empty($result['error'])?'empty':$result['error'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
		return $ret;
	}
	
	public function txweibo_comments($checktoken,$weibo_id,$comment,$comment_ori,$comment_id)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$result = $c->comments($this->settings['share_plat'][$checktoken['data']['type']]['commentsurl'],$weibo_id,$comment,$comment_ori,$comment_id,$checktoken['data']['openid'],'tx');
		if(!empty($result['errcode']))
		{
			$ret['error'] = empty($result['msg'])?'empty':$result['msg'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
		return $ret;
	}
	
	public function other_comments($checktoken,$weibo_id,$comment,$comment_ori,$comment_id)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		if($comment_id)
		{
			$result = $c->comments($checktoken['data']['platdata']['comments_commenturl'],$weibo_id,$comment,$comment_ori,$comment_id,'',true);
		}
		else
		{
			$result = $c->comments($checktoken['data']['platdata']['commentsurl'],$weibo_id,$comment,$comment_ori,$comment_id,'',true);
		}
		
		if(empty($result['created_at']))
		{
			$ret['error'] = empty($result['error'])?'empty':$result['error'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
		return $ret;
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright 	ho	gesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new commentsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'comments';
}
$out->$action();
?>
