<?php

class dz_oauth extends BaseFrm
{

    public function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/share.class.php');
        $this->obj = new share();
        include_once(CUR_CONF_PATH . 'lib/public.class.php');
        $this->pub = new publicapi();
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
    public function oauthlogin($appid, $platid, $access_plat_token = '')
    {
        $dataarr = array();
        $type    = $this->user['visit_client'];
        if ($access_plat_token)
        {
            $check_result = $this->pub->share_check_token($access_plat_token, $appid, $platid);
        }
        if (empty($appid) || empty($platid))
        {
            return 'NO_APP';
        }
        if ($platdata = $this->obj->get_by_app_plat($appid, $platid))
        {
            include_once(CUR_CONF_PATH . 'lib/' . $this->settings['share_plat'][$platdata['type']]['name'] . '_oauth.php');
            $action               = $this->settings['share_plat'][$platdata['type']]['name'] . '_oauthlogin';
            $ret                  = $this->$action($platdata, $type);
            $dataarr['oauth_url'] = urlencode($ret);
            //生成新token
            if (!$access_plat_token || $check_result['msg'] === 'new')
            {
                $dataarr['access_plat_token'] = mk_token();
                $this->obj->inserttoken($appid, $platdata['id'], $dataarr['access_plat_token'], '', '');
            }
            else
            {
                $dataarr['access_plat_token'] = $access_plat_token;
            }
            $dataarr['sync_third_auth'] = $this->settings['share_plat'][$platdata['type']]['oauthurl'];
            return $dataarr;
        }
        else
        {
            return 'NO_PLAT_DATA';
        }
    }
    
    //discuz
    public function dz_oauthlogin($platdata, $wap = '')
    {
        return  $this->settings['share_plat'][$platdata['type']]['access_url'];
    }

}

?>
