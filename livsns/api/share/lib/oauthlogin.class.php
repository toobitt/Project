<?php

class oauthlogin extends BaseFrm
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
            $dataarr['sync_third_auth'] = $platdata['type']==7?$this->settings['share_plat'][$platdata['type']]['oauthurl']:$this->settings['sync_third_url'];
            return $dataarr;
        }
        else
        {
            return 'NO_PLAT_DATA';
        }
    }

    public function sinaweibo_oauthlogin($platdata, $wap = '')
    {
        $loginurl = trim($this->public_oauthlogin($platdata), '/') . ($wap ? '&display=mobile' : '');
        $loginurl .= "&forcelogin=true";
        return $loginurl;
    }

    public function public_oauthlogin($platdata, $wap = '', $url = '')
    {
        $o        = new Oauth($platdata['akey'], $platdata['skey'], $platdata['response_type']);
        $loginurl = $o->getAuthorizeURL($url ? $url : $this->settings['share_plat'][$platdata['type']]['oauthurl'], $platdata['callback']);
        return $loginurl;
    }

    //腾讯qq
    public function txqq_oauthlogin($platdata, $wap = '')
    {
        return trim($this->public_oauthlogin($platdata), '/') . ($wap ? '&wap=2' : '');
    }

    //腾讯微博
    public function txweibo_oauthlogin($platdata, $wap = '')
    {
        return trim($this->public_oauthlogin($platdata), '/') . ($wap ? '&wap=2' : '');
    }

    //人人
    public function renren_oauthlogin($platdata, $wap = '')
    {
        return trim($this->public_oauthlogin($platdata), '/') . '&scope=publish_blog';
    }

    //豆瓣
    public function douban_oauthlogin($platdata, $wap = '')
    {
        return $this->public_oauthlogin($platdata);
    }

    //网易微博
    public function wangyi_oauthlogin($platdata, $wap = '')
    {
        return $this->public_oauthlogin($platdata);
    }

    //discuz
    public function dz_oauthlogin($platdata, $wap = '')
    {
        return  $this->settings['share_plat'][$platdata['type']]['accessurl'];
    }

    public function other_oauthlogin($platdata, $wap = '')
    {
        $loginurl = trim($this->public_oauthlogin($platdata, '', $platdata['platdata']['oauthurl']), '/') . ($wap ? '&display=mobile' : '');
        $loginurl .= "&forcelogin=true";
        return $loginurl;
    }

}

?>
