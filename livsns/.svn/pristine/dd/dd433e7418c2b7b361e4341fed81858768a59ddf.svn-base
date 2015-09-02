<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'share');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");

class oauthloginApi extends adminBase
{

    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/get_user.class.php');
        include(CUR_CONF_PATH . 'lib/public.class.php');
        include(CUR_CONF_PATH . 'lib/share.class.php');
        $this->pub      = new publicapi();
        $this->obj      = new share();
        $this->get_user = new get_user();
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
    public function get_user()
    {
        $uid    = urldecode($this->input['uid']);
        $name   = urldecode($this->input['name']);
        $appid  = intval($this->input['appid']);
        $platid = intval($this->input['id']);
        $token  = urldecode($this->input['access_plat_token']);
        //先判断token有没有过期
        if ($token)
        {
            $checktoken = $this->pub->share_check_token($token);
        }
        else
        {
            $checktoken['msg'] = false;
        }
        if ($checktoken['msg'] !== 'new' && $checktoken['msg'])
        {
            if (!$name && !$uid)
            {
                if (!empty($checktoken['data']['access_token']['uid']))
                {
                    $uid = $checktoken['data']['access_token']['uid'];
                }
                else if (!empty($checktoken['data']['access_token']['name']))
                {
                    $name = $checktoken['data']['access_token']['name'];
                }
            }

            include_once(CUR_CONF_PATH . 'lib/' . $this->settings['share_plat'][$checktoken['data']['type']]['name'] . '_oauth.php');
            $action = $this->settings['share_plat'][$checktoken['data']['type']]['name'] . '_getuser';
            $result = $this->$action($checktoken, $uid, $name);
            $this->addItem($result);
            $this->output();
        }
        else
        {
            include(CUR_CONF_PATH . 'lib/oauthlogin.class.php');
            $oauthlogin = new oauthlogin();
            if ($appid && $platid)
            {
                $ret = $oauthlogin->oauthlogin($appid, $platid, $token);
            }
            else
            {
                $this->errorOutput('NO_APPID_PLATID');
            }
            $ret['error'] = 1;
            $this->addItem($ret);
            $this->output();
        }
    }

    public function sinaweibo_getuser($checktoken, $uid, $name)
    {
        $ret    = array();
        $c      = new ClientV2($checktoken['data']['akey'], $checktoken['data']['skey'], $checktoken['data']['response_type'], $checktoken['data']['access_token']['access_token']);
        $result = $c->get_other_user($this->settings['share_plat'][$checktoken['data']['type']]['other_userurl'], $uid, $name, '', true);
//		print_r($result);exit;
        if (empty($result['error']))
        {
            $ret['uid']              = empty($result['id']) ? '' : $result['id'];
            $ret['name']             = empty($result['screen_name']) ? '' : $result['screen_name'];
            $ret['location']         = empty($result['location']) ? '' : $result['location'];
            $ret['description']      = empty($result['description']) ? '' : $result['description'];
            $ret['friends_count']    = empty($result['friends_count']) ? 0 : $result['friends_count'];
            $ret['followers_count']  = empty($result['followers_count']) ? 0 : $result['followers_count'];
            $ret['statuses_count']   = empty($result['statuses_count']) ? 0 : $result['statuses_count'];
            $ret['favourites_count'] = empty($result['favourites_count']) ? 0 : $result['favourites_count'];
            $ret['created_at']       = empty($result['created_at']) ? 0 : strtotime($result['created_at']);
            $ret['avatar']           = empty($result['avatar_large']) ? '' : $result['avatar_large'];
            switch ($result['gender'])
            {
                case 'm':
                    $ret['sex'] = '男';
                    break;
                case 'f':
                    $ret['sex'] = '女';
                    break;
                default:
                    $ret['sex'] = '';
                    break;
            }
        }
        else
        {
            $ret['error'] = empty($result['error']) ? 'empty' : $result['error'];
        }
        return $ret;
    }

    public function txweibo_getuser($checktoken, $uid, $name)
    {
        $ret    = array();
        $c      = new ClientV2($checktoken['data']['akey'], $checktoken['data']['skey'], $checktoken['data']['response_type'], $checktoken['data']['access_token']['access_token']);
        $result = $c->get_other_user($this->settings['share_plat'][$checktoken['data']['type']]['other_userurl'], $uid, $name, $checktoken['data']['access_token']['openid'], 'tx');
        if (empty($result['errcode']))
        {
            $ret['uid']              = $result['data']['openid'];
            $ret['avatar']           = $result['data']['head'] . '/180';
            $ret['location']         = $result['data']['location'];
            $ret['description']      = '';
            $ret['name']             = $result['data']['name'];
            $ret['friends_count']    = empty($result['data']['idolnum']) ? 0 : $result['data']['idolnum'];
            $ret['followers_count']  = empty($result['data']['fansnum']) ? 0 : $result['data']['fansnum'];
            $ret['favourites_count'] = empty($result['data']['favnum']) ? 0 : $result['data']['favnum'];
            $ret['created_at']       = empty($result['data']['regtime']) ? 0 : $result['data']['regtime'];
            switch ($result['data']['sex'])
            {
                case 0:
                    $ret['sex'] = '女';
                    break;
                case 1:
                    $ret['sex'] = '男';
                    break;
                default:
                    $ret['sex'] = '';
                    break;
            }
        }
        else
        {
            $ret['error'] = empty($result['msg']) ? 'empty' : $result['msg'];
        }

        return $ret;
    }

    public function txqq_getuser($checktoken, $uid, $name)
    {
        $ret    = array();
        $c      = new ClientV2($checktoken['data']['akey'], $checktoken['data']['skey'], $checktoken['data']['response_type'], $checktoken['data']['access_token']['access_token']);
        $result = $c->get_other_user($this->settings['share_plat'][$checktoken['data']['type']]['userurl'], $uid, $name, $checktoken['data']['openid'], 'tx');
//		print_r($result);exit;
        if (empty($result['errcode']))
        {
            $ret['uid']              = $result['data']['openid'];
            $ret['avatar']           = $result['data']['head'];
            $ret['location']         = $result['data']['location'];
            $ret['name']             = $result['data']['name'];
            $ret['screen_name']      = $result['data']['nick'];
            $ret['sex']              = $result['data']['sex'];
            $ret['friends_count']    = empty($result['data']['idolnum']) ? 0 : $result['data']['idolnum'];
            $ret['followers_count']  = empty($result['data']['fansnum']) ? 0 : $result['data']['fansnum'];
            $ret['favourites_count'] = empty($result['data']['favnum']) ? 0 : $result['data']['favnum'];
            $ret['created_at']       = empty($result['data']['regtime']) ? 0 : $result['data']['regtime'];
        }
        else
        {
            $ret['error'] = empty($result['msg']) ? 'empty' : $result['msg'];
        }

        return $ret;
    }

    public function renren_getuser($checktoken, $uid, $name)
    {
        $ret    = array();
        $c      = new ClientV2($checktoken['data']['akey'], $checktoken['data']['skey'], $checktoken['data']['response_type'], $checktoken['data']['access_token']['access_token']);
        $result = $c->renren_show_user($this->settings['share_plat'][$checktoken['data']['type']]['userurl'], $uid, $name, $checktoken['data']['skey']);
        if (!empty($result[0]['uid']))
        {
            $ret['uid']    = $result[0]['uid'];
            $ret['avatar'] = $result[0]['headurl'];
            $ret['name']   = $result[0]['name'];
            $ret['sex']    = $result[0]['sex'];
        }
        else
        {
            $ret['error'] = empty($result['msg']) ? 'empty' : $result['msg'];
        }
        return $ret;
    }

    public function other_getuser($checktoken, $uid, $name)
    {
        $ret    = array();
        $c      = new ClientV2($checktoken['data']['akey'], $checktoken['data']['skey'], $checktoken['data']['response_type'], $checktoken['data']['access_token']['access_token']);
        $result = $c->get_other_user($checktoken['data']['platdata']['other_userurl'], $uid, $name, '', true);
//		print_r($result);exit;
        if (empty($result['error']))
        {
            $ret['uid']              = empty($result['id']) ? '' : $result['id'];
            $ret['screen_name']      = empty($result['screen_name']) ? '' : $result['screen_name'];
            $ret['name']             = empty($result['name']) ? '' : $result['name'];
            $ret['location']         = empty($result['location']) ? '' : $result['location'];
            $ret['description']      = empty($result['description']) ? '' : $result['description'];
            $ret['friends_count']    = empty($result['friends_count']) ? 0 : $result['friends_count'];
            $ret['followers_count']  = empty($result['followers_count']) ? 0 : $result['followers_count'];
            $ret['statuses_count']   = empty($result['statuses_count']) ? 0 : $result['statuses_count'];
            $ret['favourites_count'] = empty($result['favourites_count']) ? 0 : $result['favourites_count'];
            $ret['created_at']       = empty($result['created_at']) ? 0 : strtotime($result['created_at']);
            $ret['avatar']           = empty($result['avatar_large']) ? '' : $result['avatar_large'];
        }
        else
        {
            $ret['error'] = empty($result['error']) ? 'empty' : $result['error'];
        }
        return $ret;
    }

    public function get_auth_user()
    {
        $token = urldecode($this->input['access_plat_token']);
        if (!$token)
        {
            $this->errorOutput('NO_ACCESS_PLAT_TOKEN');
        }
        $checktoken = $this->pub->share_check_token($token);
        if (empty($checktoken['data']['access_token']))
        {
            $this->errorOutput('NO_PLAT_DATA');
        }
        if (!empty($checktoken['data']['access_token']['uid']))
        {
            $uid = $checktoken['data']['access_token']['uid'];
        }
        else if (!empty($checktoken['data']['access_token']['name']))
        {
            $name = $checktoken['data']['access_token']['name'];
        }
        if (!$uid && !$name)
        {
            $this->errorOutput('NO_USER_DATA');
        }
        if ($checktoken['data']['addtime'] || $checktoken['data']['access_token'])
        {
            $expired      = !check_token_time($checktoken['data']['token_addtime'], $checktoken['data']['access_token']['expires_in']);
            $expired_time = $checktoken['data']['token_addtime'] + $checktoken['data']['access_token']['expires_in'];
        }
        else
        {
            $expired = true;
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "auth_user WHERE plat_type=" . $checktoken['data']['type'];
        if ($uid)
        {
            $sql .= " AND uid='" . $uid . "'";
        }
        if ($name)
        {
            $sql .= " AND name='" . $name . "'";
        }
        $ret = $this->db->query_first($sql);
        if (!empty($ret))
        {
            $ret['plat_name']         = $checktoken['data']['name'];
            $ret['access_plat_token'] = $token;
            $expired_time ? $ret['expired_time']      = $expired_time : '';
            $ret['expired']           = $expired;
        }
        $this->addItem($ret);
        $this->output();
    }

    public function get_user_by_token()
    {
        $token               = urldecode($this->input['access_plat_token']);
        if(!$token)
        {
            $this->errorOutput('NO_TOKEN');
        }
        $tokens = implode("','",explode(',',$token));
        $sql                 = "select *,p.name as name,t.addtime as token_addtime from " . DB_PREFIX . "token t left join " . DB_PREFIX . "plat p on t.platId=p.id LEFT JOIN " . DB_PREFIX . "auth_app a ON t.appid=a.appid where t.token in ('".$tokens."') AND p.status='1' AND a.status='1' ";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $row['platdata']     = empty($row['platdata']) ? array() : unserialize($row['platdata']);
            $row['access_token'] = empty($row['access_token']) ? array() : json_decode($row['access_token'], true);
            if(!$row['access_token'])
            {
                continue;
            }
            if (!empty($row['access_token']['uid']))
            {
                $uid = $row['access_token']['uid'];
            }
            else if (!empty($row['access_token']['name']))
            {
                $name = $row['access_token']['name'];
            }
            if (!$uid && !$name)
            {
                continue;
            }
            $sql = "SELECT * FROM " . DB_PREFIX . "auth_user WHERE plat_type=" . $row['type'];
            if ($uid)
            {
                $sql .= " AND uid='" . $uid . "'";
            }
            if ($name)
            {
                $sql .= " AND name='" . $name . "'";
            }
            $ret = $this->db->query_first($sql);
            if (!empty($ret))
            {
                $ret['plat_name']         = $row['name'];
                $ret['access_plat_token'] = $row['token'];
            }
            $r[$row['token']] = $ret;
        }
        $this->addItem($r);
        $this->output();
    }
    
    public function get_user_by_id()
    {
        $ids = $this->input['id'];
        if(!$ids)
        {
            $this->errorOutput('NO_ID');
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "auth_user WHERE id in (" . $ids.")";
        $query = $this->db->query($sql);
       while ($re = $this->db->fetch_array($query))
			{
			$ret[$re['id']]=$re;
			}
        $this->addItem($ret);
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

$out    = new oauthloginApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'get_user';
}
$out->$action();
?>
