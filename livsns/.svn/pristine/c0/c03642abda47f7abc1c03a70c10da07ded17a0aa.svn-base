<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");
define('MOD_UNIQUEID', 'share_accesstoken');

class accesstokenApi extends adminBase
{

    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/share.class.php');
        $this->obj      = new share();
        include(CUR_CONF_PATH . 'lib/get_user.class.php');
        $this->get_user = new get_user();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * 根据code，系统id，平台id 获取accesstoken 
     * @name share
     * @access public
     * @author 
     * @category hogesoft
     * @copyright hogesoft
     * @return 
     */
    public function accesstoken()
    {
        $access_plat_token = urldecode($this->input['access_plat_token']);
        if (!$access_plat_token)
        {
            $this->errorOutput('NO_ACCESS_PLAT_TOKEN');
        }

        if ($platdata = $this->obj->get_token_by_token($access_plat_token))
        {
            include_once(CUR_CONF_PATH . 'lib/' . $this->settings['share_plat'][$platdata['type']]['name'] . '_oauth.php');
            $action = $this->settings['share_plat'][$platdata['type']]['name'] . '_accesstoken';
            $ret    = $this->$action($platdata);
            $this->addItem($ret);
            $this->output();
        }
        else
        {
            $this->errorOutput('NO_PLAT_DATA');
        }
    }

    public function sinaweibo_accesstoken($platdata)
    {
        $data = $this->public_accesstoken($platdata);
        //插入到第三方用户表中
        if (!$data)
        {
            return false;
        }
        $insert_data['plat_type']   = $platdata['type'];
        $insert_data['platId']      = $platdata['platId'];
        $insert_data['uid']         = $data['access_token']['uid'];
        $insert_data['name']        = $data['userdata']['name'];
        $insert_data['avatar']      = $data['userdata']['avatar_large'];
        $insert_data['token']       = $data['access_plat_token'];
        $insert_data['user_id']     = $this->user['user_id'];
        $insert_data['user_name']   = $this->user['user_name'];
        $insert_data['create_time'] = $insert_data['update_time'] = TIMENOW;
        $auth_user                  = $this->input['other'] ? $this->obj->get_plat_user($insert_data) : $this->obj->get_auth_user($insert_data);
        if (!$auth_user)
        {
            $insert_id = $this->input['other'] ? $this->obj->insert_plat_user($insert_data) : $this->obj->insert_auth_user($insert_data);
        }
        else
        {
            $this->input['other'] ? $this->obj->update_plat_user($insert_data, $auth_user['id']) : $this->obj->update_auth_user($insert_data, $auth_user['id']);
        }

        $result['access_plat_token'] = $data['access_plat_token'];
        $result['oauth_suc']         = 1;
        $result['name']              = empty($data['userdata']['name']) ? '' : $data['userdata']['name'];
        $result['pic']               = empty($data['userdata']['avatar_large']) ? '' : $data['userdata']['avatar_large'];
        $result['auth_user_id']      = empty($auth_user['id']) ? $insert_id : $auth_user['id'];
        return $result;
    }

    //所需值：code,openid
    public function public_accesstoken($platdata, $get_user_data = true, $url = '')
    {
        $code   = $this->input[$platdata['response_type']];
        $openid = $this->input['openid'];
        if ($code)
        {
            $keys                             = array();
            $keys[$platdata['response_type']] = $code;
            $keys['redirect_uri']             = $platdata['callback'];
            $o                                = new Oauth($platdata['akey'], $platdata['skey'], $platdata['response_type']);
            $access_token                     = $o->getAccessToken($url ? $url : $this->settings['share_plat'][$platdata['type']]['accessurl'], $keys);
            //根据uid，name，access_token获取用户头像，名称
            $uid                              = empty($access_token['uid']) ? '' : $access_token['uid'];
            $name                             = empty($access_token['name']) ? '' : $access_token['name'];
            if ($get_user_data)
            {
                $data['userdata'] = $this->get_user->show_user($platdata, $access_token, $uid, $name, $openid, $url);
                if ($this->input['uid'] && ($data['userdata']['id'] != $this->input['uid']))
                {
                    return false;
                }
                if ($data['userdata']['error'])
                {
                    return false;
                }
            }

            if (!empty($access_token['access_token']))
            {
                //更新到数据库中
                if ($this->obj->updatetoken($platdata['token'], array('access_token' => json_encode($access_token), 'openid' => $openid, 'addTime' => TIMENOW)))
                {
                    $data['openid']            = $openid;
                    $data['access_token']      = $access_token;
                    $data['access_plat_token'] = $platdata['token'];
                    return $data;
                }
                else
                {
                    return 'GET_FAILD';
                }
            }
            else
            {
                return 'NO_ACCESS_TOKEN';
            }
        }
        else
        {
            return 'NO_RESPONSE_TYPE';
        }
    }

    public function txweibo_accesstoken($platdata)
    {
        $data = $this->public_accesstoken($platdata);
        //插入到第三方用户表中
        if (!$data)
        {
            return false;
        }
        $insert_data['plat_type']   = $platdata['type'];
        $insert_data['uid']         = $data['openid'];
        $insert_data['platId']      = $platdata['platId'];
        $insert_data['name']        = empty($data['userdata']['data']['name']) ? '' : $data['userdata']['data']['name'];
        $insert_data['avatar']      = $data['userdata']['data']['head'] . '/180';
        $insert_data['token']       = $data['access_plat_token'];
        $insert_data['user_id']     = $this->user['user_id'];
        $insert_data['user_name']   = $this->user['user_name'];
        $insert_data['create_time'] = $insert_data['update_time'] = TIMENOW;
        $auth_user                  = $this->input['other'] ? $this->obj->get_plat_user($insert_data) : $this->obj->get_auth_user($insert_data);
        if (!$auth_user)
        {
            $insert_id = $this->input['other'] ? $this->obj->insert_plat_user($insert_data) : $this->obj->insert_auth_user($insert_data);
        }
        else
        {
            $this->input['other'] ? $this->obj->update_plat_user($insert_data, $auth_user['id']) : $this->obj->update_auth_user($insert_data, $auth_user['id']);
        }

        $result['oauth_suc']         = 1;
        $result['access_plat_token'] = $data['access_plat_token'];
        $result['name']              = $data['userdata']['data']['nick'];
        $result['pic']               = $data['userdata']['data']['head'] . '/180';
        $result['auth_user_id']      = empty($auth_user['id']) ? $insert_id : $auth_user['id'];
        return $result;
    }

    public function txqq_accesstoken($platdata)
    {
        $data = $this->public_accesstoken($platdata, false);
        if (!$data)
        {
            return false;
        }
        //获取openid
        $data['access_token']['access_token'];
        $txuserdata       = $this->get_user->get_tx_open_id($platdata, $data['access_token']);
        $data['userdata'] = $this->get_user->show_user($platdata, $data['access_token'], '', '', $txuserdata['openid']);
        if (!empty($txuserdata['openid']))
        {
            $data['access_token']['uid']  = $txuserdata['openid'];
            $data['access_token']['name'] = empty($data['userdata']['nickname']) ? '' : $data['userdata']['nickname'];
            $this->obj->updatetoken($platdata['token'], array('access_token' => json_encode($data['access_token']), 'openid' => $txuserdata['openid']));
        }
        //插入到第三方用户表中
        $insert_data['plat_type']   = $platdata['type'];
        $insert_data['uid']         = $txuserdata['openid'];
        $insert_data['platId']      = $platdata['platId'];
        $insert_data['name']        = empty($data['userdata']['nickname']) ? '' : $data['userdata']['nickname'];
        $insert_data['avatar']      = $data['userdata']['figureurl_2'];
        $insert_data['token']       = $data['access_plat_token'];
        $insert_data['user_id']     = $this->user['user_id'];
        $insert_data['user_name']   = $this->user['user_name'];
        $insert_data['create_time'] = $insert_data['update_time'] = TIMENOW;
        $auth_user                  = $this->input['other'] ? $this->obj->get_plat_user($insert_data) : $this->obj->get_auth_user($insert_data);
        if (!$auth_user)
        {
            $insert_id = $this->input['other'] ? $this->obj->insert_plat_user($insert_data) : $this->obj->insert_auth_user($insert_data);
        }
        else
        {
            $this->input['other'] ? $this->obj->update_plat_user($insert_data, $auth_user['id']) : $this->obj->update_auth_user($insert_data, $auth_user['id']);
        }
        $result['oauth_suc']         = 1;
        $result['access_plat_token'] = $data['access_plat_token'];
        $result['name']              = empty($data['userdata']['nickname']) ? '' : $data['userdata']['nickname'];
        ;
        $result['pic']               = $data['userdata']['figureurl_2'];
        $result['auth_user_id']      = empty($auth_user['id']) ? $insert_id : $auth_user['id'];
        return $result;
    }

    public function renren_accesstoken($platdata)
    {
        $data = $this->public_accesstoken($platdata, false);
        if (!$data)
        {
            return false;
        }
        if (!empty($data['access_token']['access_token']))
        {
            $new_access_token['access_token']  = $data['access_token']['access_token'];
            $new_access_token['uid']           = $data['access_token']['user']['id'];
            $new_access_token['name']          = $data['access_token']['user']['name'];
            $new_access_token['expires_in']    = $data['access_token']['expires_in'];
            $new_access_token['access_token']  = $data['access_token']['access_token'];
            $new_access_token['refresh_token'] = $data['access_token']['refresh_token'];
            $new_access_token['scope']         = $data['access_token']['scope'];
            $this->obj->updatetoken($platdata['token'], array('access_token' => json_encode($new_access_token)));
        }
        //插入到第三方用户表中
        $insert_data['plat_type']   = $platdata['type'];
        $insert_data['uid']         = $data['access_token']['user']['id'];
        $insert_data['platId']      = $platdata['platId'];
        $insert_data['name']        = empty($data['access_token']['user']['name']) ? '' : $data['access_token']['user']['name'];
        $insert_data['avatar']      = $data['access_token']['user']['avatar'][0]['url'];
        $insert_data['token']       = $data['access_plat_token'];
        $insert_data['user_id']     = $this->user['user_id'];
        $insert_data['user_name']   = $this->user['user_name'];
        $insert_data['create_time'] = $insert_data['update_time'] = TIMENOW;
        $auth_user                  = $this->input['other'] ? $this->obj->get_plat_user($insert_data) : $this->obj->get_auth_user($insert_data);
        if (!$auth_user)
        {
            $insert_id = $this->input['other'] ? $this->obj->insert_plat_user($insert_data) : $this->obj->insert_auth_user($insert_data);
        }
        else
        {
            $this->input['other'] ? $this->obj->update_plat_user($insert_data, $auth_user['id']) : $this->obj->update_auth_user($insert_data, $auth_user['id']);
        }
        $result['oauth_suc']         = 1;
        $result['access_plat_token'] = $data['access_plat_token'];
        $result['name']              = $data['access_token']['user']['name'];
        $result['pic']               = $data['access_token']['user']['avatar'][0]['url'];
        $result['auth_user_id']      = empty($auth_user['id']) ? $insert_id : $auth_user['id'];
        return $result;
    }

    public function douban_accesstoken($platdata)
    {
        return $this->public_accesstoken($platdata);
    }

    public function wangyi_accesstoken($platdata)
    {
        return $this->public_accesstoken($platdata);
    }

    public function dz_accesstoken($platdata)
    {
        if(!$this->input['cookie_data'] || !$this->input['filename'])
        {
            return 'NO_COOKIE';
        }
        $md5_filename = md5($this->input['filename']);
        $dir = substr($md5_filename,0,2).'/'.substr($md5_filename,2,2).'/';
        file_in($this->settings['cookie_dir'].$dir, $this->input['filename'], $this->input['cookie_data']);
        $access_token['expires_in'] = 2592000;
        $access_token['cookie_dir'] = realpath($this->settings['cookie_dir'].$dir . $this->input['filename']);
        $access_token['uid']        = $this->input['data']['user_id'];
        //更新到数据库中
        if ($this->obj->updatetoken($platdata['token'], array('access_token' => json_encode($access_token), 'addTime' => TIMENOW)))
        {
            $insert_data['plat_type']    = $platdata['type'];
            $insert_data['uid']          = $this->input['data']['user_id'];
            $insert_data['platId']       = $platdata['platId'];
            $insert_data['name']         = $this->input['data']['user_name'];
            $insert_data['avatar']       = '';
            $insert_data['token']        = $platdata['token'];
            $insert_data['user_id']      = $this->user['user_id'];
            $insert_data['user_name']    = $this->user['user_name'];
            $insert_data['create_time']  = $insert_data['update_time']  = TIMENOW;
            $insert_data['mode_type']  = $this->input['data']['forum']?serialize($this->input['data']['forum']):'';
            $auth_user = $this->input['other'] ? $this->obj->get_plat_user($insert_data) : $this->obj->get_auth_user($insert_data);
            
            if (!$auth_user)
            {
                $insert_id = $this->input['other'] ? $this->obj->insert_plat_user($insert_data) : $this->obj->insert_auth_user($insert_data);
            }
            else
            {
                $this->input['other'] ? $this->obj->update_plat_user($insert_data, $auth_user['id']) : $this->obj->update_auth_user($insert_data, $auth_user['id']);
            }
            return $data;
        }
        else
        {
            return 'GET_FAILD';
        }
        //插入到第三方用户表中
        if (!$data)
        {
            return false;
        }
        /**
        $insert_data['plat_type']   = $platdata['type'];
        $insert_data['platId']      = $platdata['platId'];
        $insert_data['uid']         = $data['access_token']['uid'];
        $insert_data['name']        = $data['userdata']['name'];
        $insert_data['avatar']      = $data['userdata']['avatar_large'];
        $insert_data['token']       = $data['access_plat_token'];
        $insert_data['user_id']     = $this->user['user_id'];
        $insert_data['user_name']   = $this->user['user_name'];
        $insert_data['create_time'] = $insert_data['update_time'] = TIMENOW;
        $auth_user                  = $this->input['other'] ? $this->obj->get_plat_user($insert_data) : $this->obj->get_auth_user($insert_data);
        if (!$auth_user)
        {
            $insert_id = $this->input['other'] ? $this->obj->insert_plat_user($insert_data) : $this->obj->insert_auth_user($insert_data);
        }
        else
        {
            $this->input['other'] ? $this->obj->update_plat_user($insert_data, $auth_user['id']) : $this->obj->update_auth_user($insert_data, $auth_user['id']);
        }

        $result['access_plat_token'] = $data['access_plat_token'];
        $result['oauth_suc']         = 1;
        $result['name']              = empty($data['userdata']['name']) ? '' : $data['userdata']['name'];
        $result['pic']               = empty($data['userdata']['avatar_large']) ? '' : $data['userdata']['avatar_large'];
        $result['auth_user_id']      = empty($auth_user['id']) ? $insert_id : $auth_user['id'];
        
         */
    }

    public function other_accesstoken($platdata)
    {
        $data = $this->public_accesstoken($platdata, '', $platdata['platdata']['accessurl']);
        if (!$data)
        {
            return false;
        }
        //插入到第三方用户表中
        $insert_data['plat_type']   = $platdata['type'];
        $insert_data['platId']      = $platdata['platId'];
        $insert_data['uid']         = $data['access_token']['uid'];
        $insert_data['name']        = $data['userdata']['name'];
        $insert_data['avatar']      = $data['userdata']['avatar_large'];
        $insert_data['token']       = $data['access_plat_token'];
        $insert_data['user_id']     = $this->user['user_id'];
        $insert_data['user_name']   = $this->user['user_name'];
        $insert_data['create_time'] = $insert_data['update_time'] = TIMENOW;
        $auth_user                  = $this->input['other'] ? $this->obj->get_plat_user($insert_data) : $this->obj->get_auth_user($insert_data);
        if (!$auth_user)
        {
            $insert_id = $this->input['other'] ? $this->obj->insert_plat_user($insert_data) : $this->obj->insert_auth_user($insert_data);
        }
        else
        {
            $this->input['other'] ? $this->obj->update_plat_user($insert_data, $auth_user['id']) : $this->obj->update_auth_user($insert_data, $auth_user['id']);
        }

        $result['access_plat_token'] = $data['access_plat_token'];
        $result['oauth_suc']         = 1;
        $result['name']              = empty($data['userdata']['name']) ? '' : $data['userdata']['name'];
        $result['pic']               = empty($data['userdata']['avatar_large']) ? '' : $data['userdata']['avatar_large'];
        $result['auth_user_id']      = empty($auth_user['id']) ? $insert_id : $auth_user['id'];
        return $result;
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

$out    = new accesstokenApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'accesstoken';
}
$out->$action();
?>
