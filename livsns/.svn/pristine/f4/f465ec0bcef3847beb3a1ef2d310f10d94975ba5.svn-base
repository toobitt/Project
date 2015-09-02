<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','share_get_access_plat');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
class get_access_platApi extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	/**
	 * 根据系统 查询出分享的平台 所需参数：id(系统id,liv_app.systemId)
	 * @name share
	 * @access public
	 * @author 
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return data or false
	 */
	public function get_plat()
	{
		$ret = array();
		$id = intval($this->input['appid']);
		if(!$id)
		{
			$this->errorOutput('NO_APP_INFO');
		}
		$app = $this->obj->get_app_by_systemId($id);
		if(empty($app))
		{
			$this->errorOutput('NO_PLAT_DATA');
		}
		$platdatas = $this->obj->get_plat_supportid('id,name,picurl,type,pic_login,pic_share',$app['platIds'],'id');
		if($tokens = $this->input['access_plat_token'])
		{
			$tokendata = $this->obj->get_token_by_tokens($tokens);
		}
		if($platdatas)
		{
			foreach($platdatas as $k=>$v)
			{
				$v['can_access'] = 0;
				if($tokendata[$v['id']])
				{
					$access_token_arr = $tokendata[$v['id']]['access_token'];
					if(check_token_time($tokendata[$v['id']]['token_addtime'],$access_token_arr['expires_in']))
					{
						$v['expired_time'] = $tokendata[$v['id']]['token_addtime']+$access_token_arr['expires_in'];
						$v['can_access'] = 1;
					}
				}
				$v['type_name'] = $this->settings['share_plat'][$v['type']]['name_ch'];
				$v['picurl'] = empty($v['picurl'])?'':$v['picurl'];
				$v['pic_login'] = empty($v['pic_login'])?'':$v['pic_login'];
				$v['pic_share'] = empty($v['pic_share'])?'':$v['pic_share'];
				$this->addItem($v);
			}
		}
		
		$this->output();
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

$out = new get_access_platApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_plat';
}
$out->$action();
?>
