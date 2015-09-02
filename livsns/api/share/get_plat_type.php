<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'share_plat_type');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");

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
    public function get_plat_type()
    {
        $plat_type = array();
        $id        = intval($this->input['appid']);
        if (!$id)
        {
            $this->errorOutput('NO_APP_INFO');
        }
        $app = $this->obj->get_app_by_systemId($id);
        if (empty($app))
        {
            $this->errorOutput('NO_PLAT_DATA');
        }
        $platdatas = $this->obj->get_plat_supportid('id,name,picurl,type', $app['platIds'], 'id');
        foreach ($platdatas as $v)
        {
            $plat_type[$v['type']] = $this->settings['share_plat'][$v['type']]['name_ch'];
        }
        $this->addItem($plat_type);
        $this->output();
    }

    /**
     * 同上，输出内容不同
     * @name share
     * @access public
     * @author 
     * @category hogesoft
     * @copyright hogesoft
     * @return data or false
     */
    public function get_type()
    {
        $plat_type = array();
        $id        = intval($this->input['appid']);
        if (!$id)
        {
            $this->errorOutput('NO_APP_INFO');
        }
        $app = $this->obj->get_app_by_systemId($id);
        if (empty($app))
        {
            $this->errorOutput('NO_PLAT_DATA');
        }
        $platdatas = $this->obj->get_plat_supportid('id,name,picurl,type', $app['platIds'], 'id');
        if (is_array($platdatas) && count($platdatas) > 0)
        {
            include_once(CUR_CONF_PATH . 'lib/oauthlogin.class.php');
            $oauthlogin = new oauthlogin();
            foreach ($platdatas as $k => $v)
            {
                $plat     = $oauthlogin->oauthlogin($id, $v['id']);
                $v['url'] = $plat['sync_third_auth'] . '?oauth_url=' . $plat['oauth_url'] . '&access_plat_token=' . $plat['access_plat_token'] . "&other=1&access_token=" . $this->user['token'] . "&uid=" . $uid;
                $this->addItem($v);
            }
        }
        $this->output();
//		foreach($platdatas as $v)
//		{
//			$plat_type[$v['type']] = $this->settings['share_plat'][$v['type']]['name_ch'];
//		}
//		$this->addItem($plat_type);	
//		$this->output();
    }

    public function get_plat_info()
    {
        $platid = intval($this->input['platid']);
        if (!$platid)
        {
            $this->errorOutput('平台id不能为空');
        }
        $info = $this->obj->get_plat_info($platid);
        if (!$info)
        {
            $this->errorOutput('平台不存在');
        }
        $this->addItem($info);
        $this->output();
    }
    /**
     * 
     * 获取所有平台信息:目前只有新会员和老会员导数据会用
     */
    public function get_all_plat()
    {
    	$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$plat = $this->obj->get_account($offset,$count);
		foreach($plat as $k=>$v)
		{
			if($v['picurl'])
			{
				$pic = unserialize($v['picurl']);
				$v['picurl'] = $pic['host'].$pic['dir'].$pic['filepath'].$pic['filename'];
			}
			$this->addItem($v);
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

$out    = new get_access_platApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'get_plat_type';
}
$out->$action();
?>
