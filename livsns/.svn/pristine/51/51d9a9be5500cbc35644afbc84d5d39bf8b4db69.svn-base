<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'share');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");

class updateAPI extends adminBase
{

    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/share.class.php');
        $this->obj      = new share();
        include_once(CUR_CONF_PATH . 'lib/public.class.php');
        $this->pub      = new publicapi();
        include(CUR_CONF_PATH . 'lib/get_user.class.php');
        $this->get_user = new get_user();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function toshare()
    {
        $appid     = intval($this->input['appid']);
        $platid    = intval($this->input['id']);
        $plat_type = intval($this->input['plat_type']);
        $token     = urldecode($this->input['access_plat_token']);
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
            $platdata = $this->obj->get_by_app_plat($checktoken['data']['appid'], $checktoken['data']['platId']);
            include_once(CUR_CONF_PATH . 'lib/' . $this->settings['share_plat'][$checktoken['data']['type']]['name'] . '_oauth.php');
            $action   = $this->settings['share_plat'][$platdata['type']]['name'] . '_toshare';
            $ret      = $this->$action($checktoken['data'], $checktoken['data']);
            if ($ret['error'])
            {
                $this->errorOutput($ret['error']);
            }
            else
            {
                $this->addItem($ret);
                $this->output();
            }
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
                if ($platid || !$plat_type)
                {
                    $this->errorOutput('NO_APPID_PLATID');
                }
                else
                {
                    $plat = $this->obj->get_plat_by_type($appid, $plat_type);
                    if (empty($plat))
                    {
                        $this->errorOutput('NO_ANY_PLAT');
                    }
                    $ret = $oauthlogin->oauthlogin($appid, $plat['id'], $token);
                }
            }
            $ret['error'] = 1;
            $this->addItem($ret);
            $this->output();
        }
    }

    public function sinaweibo_toshare($tokendata, $platdata)
    {
        if ($tokendata)
        {
            $text    = ($this->input['text']);
            $picpath = urldecode($this->input['picpath']);
            $lat     = $this->input['lat'];
            $long    = $this->input['long'];
            if ($text)
            {
//				if($picpath)
//				{
//					$picpath = url_format($picpath);
//					$file = create_image_dir($picpath,CUR_CONF_PATH.$this->settings['image_cache']);
//					$new_picpath = $file['filepath'].$file['filename'];
//					if(!file_exists($new_picpath))
//					{
//						$new_picpath = upload_image($picpath,$file);
//					}
//				}

                $access_token = $tokendata['access_token'];
                $c            = new ClientV2($tokendata['akey'], $tokendata['skey'], $tokendata['response_type'], $access_token['access_token']);
                if ($picpath)
                {
                    if (substr($picpath, 0, 7) == 'http://')
                    {
                        if (true)
                        {
                            $result = $c->upload($this->settings['share_plat'][$tokendata['type']]['sharepicurl'], $text, $picpath, true, $lat, $long);
                        }
                        else
                        {
                            $result = $c->upload($this->settings['share_plat'][$tokendata['type']]['sharepicurl'], $text, $new_picpath, true, $lat, $long);
                        }
                    }
                }
                else
                {
                    $result = $c->update($this->settings['share_plat'][$tokendata['type']]['shareurl'], $text, true, $lat, $long);
                }
                $userdata = $this->get_user->show_user($platdata, $access_token, $access_token['uid']);
                if (!empty($result['created_at']))
                {
                    $data              = array(
                        'type' => 1,
                        'uid' => $access_token['uid'],
                        'name' => $userdata['name'],
                        'platid' => $platdata['id'],
                        'url' => $tokendata['shareurl'],
                        'content' => $text,
                        'picpath' => $picpath,
                        'jing' => $lat,
                        'wei' => $long,
                        'addtime' => TIMENOW,
                    );
                    $insert_id         = $this->obj->insert_record($data);
                    $return['id']      = $insert_id;
                    $return['name']    = $userdata['name'];
                    $return['picpath'] = $picpath;
                    $return['addtime'] = $data['addtime'];
                }
                else
                {
                    $return['error'] = $result['error'];
                }
//				print_r($userdata);exit;
                return $return;
            }
            else
            {
                return "没有分享的内容";
            }
        }
        else
        {
            return "无可用分享信息，请重新登录";
        }
    }

    public function txweibo_toshare($tokendata, $platdata)
    {
        if ($tokendata)
        {
            $text         = urldecode($this->input['text']);
            $picpath      = urldecode($this->input['picpath']);
            $lat          = urldecode($this->input['lat']);
            $long         = urldecode($this->input['long']);
            $access_token = $tokendata['access_token'];
            $c            = new ClientV2($tokendata['akey'], $tokendata['skey'], $tokendata['response_type'], $access_token['access_token']);
            if (!$picpath)
            {
                $result = ($c->txupdate($tokendata['openid'], $this->settings['share_plat'][$tokendata['type']]['shareurl'], $text, $picpath, false, $lat, $long));
            }
            else
            {
                $picpath = url_format($picpath);
                $result  = ($c->txupload($tokendata['openid'], $this->settings['share_plat'][$tokendata['type']]['sharepicurl'], $text, $picpath, false, $lat, $long));
            }
            if ($result['msg'] == 'ok')
            {
                //根据uid，name，access_token获取用户头像，名称
                $userdata          = $this->get_user->show_user($platdata, $access_token, '', $access_token['name'], $tokendata['openid']);
                $data              = array(
                    'type' => 1,
                    'uid' => $tokendata['openid'],
                    'name' => $userdata['data']['nick'],
                    'platid' => $platdata['id'],
                    'url' => $tokendata['shareurl'],
                    'content' => $text,
                    'picpath' => $picpath,
                    'jing' => $lat,
                    'wei' => $long,
                    'addtime' => TIMENOW,
                );
                $insert_id         = $this->obj->insert_record($data);
                $return['id']      = $insert_id;
                $return['name']    = $userdata['data']['nick'];
                $return['picpath'] = $picpath;
                $return['addtime'] = $data['addtime'];
            }
            else
            {
                $return['error'] = $result['error'];
            }
            return $return;
        }
        else
        {
            return "无可用分享信息，请重新登录";
        }
    }

    public function renren_toshare($tokendata, $platdata)
    {
        if ($tokendata)
        {
            //				$title = $this->input['title'];
            //				$text = $this->input['text'];
            //				$picpath = $this->input['picpath'];
            $lat          = "";
            $long         = "";
            $title        = "人人api";
            $text         = "人人api";
            $picpath      = '123';
            $access_token = $tokendata['access_token'];
            //				print_r($access_token->access_token);exit;
            $c            = new ClientV2($tokendata['akey'], $tokendata['skey'], $tokendata['response_type'], $access_token['access_token']);
            $result       = $c->rrupload($title, $tokendata['shareurl'], $text, $platdata['skey'], $picpath, false);
            $data         = array(
                'type' => 1,
                'uid' => '12',
                'name' => 'renren',
                'platid' => $platdata['id'],
                'url' => $tokendata['shareurl'],
                'content' => $text,
                'picpath' => $picpath,
                'jing' => $lat,
                'wei' => $long,
                'addtime' => TIMENOW,
            );
            $this->obj->insert_record($data);
        }
        else
        {
            return "无可用分享信息，请重新登录";
        }
    }

    public function douban_toshare($tokendata, $platdata)
    {
        return $this->sinaweibo_toshare($tokendata, $platdata);
    }

    public function wangyi_toshare($tokendata, $platdata)
    {
        return $this->sinaweibo_toshare($tokendata, $platdata);
    }

    public function dz_toshare($tokendata, $platdata)
    {
        $login_url = $this->settings['share_plat'][$platdata['type']]['shareurl'];
        if(!$tokendata['access_token']['cookie_dir'])
        {
            return '没有缓存';
        }
        $post_fields['message'] = urldecode($this->input['text']);
        $post_fields['subject'] = urldecode($this->input['title']);
        $post_fields['fid'] = $this->input['section_id'];
        $ch  = curl_init($login_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $tokendata['access_token']['cookie_dir']);
        $ret = curl_exec($ch);
        $head_info = curl_getinfo($ch);
        curl_close($ch);
        $return['id']           = '111';
        $return['name']         = $userdata['data']['nick'];
        $return['picpath']      = $picpath;
        $return['addtime']      = $data['addtime'];
        return $return;
    }

    public function other_toshare($tokendata, $platdata)
    {
        if ($tokendata)
        {
            $text    = ($this->input['text']);
            $picpath = urldecode($this->input['picpath']);
            $lat     = $this->input['lat'];
            $long    = $this->input['long'];
            if ($text)
            {
                if ($picpath)
                {
                    $file        = create_image_dir($picpath, CUR_CONF_PATH . $this->settings['image_cache']);
                    $new_picpath = $file['filepath'] . $file['filename'];
                    //if(!file_exists($new_picpath))
                    {
                        $new_picpath = upload_image($picpath, $file);
                    }
                }

                $access_token = $tokendata['access_token'];
                $c            = new ClientV2($tokendata['akey'], $tokendata['skey'], $tokendata['response_type'], $access_token['access_token']);
                if ($picpath)
                {
                    if (substr($picpath, 0, 7) == 'http://')
                    {
                        if (false)
                        {
                            $result = $c->upload($platdata['platdata']['sharepicurl'], $text, $picpath, true, $lat, $long);
                        }
                        else
                        {
                            $result = $c->upload($platdata['platdata']['sharepicurl'], $text, $new_picpath, true, $lat, $long);
                        }
                    }
                }
                else
                {
                    $result = $c->update($platdata['platdata']['shareurl'], $text, true, $lat, $long);
                }
                $userdata = $this->get_user->show_user($platdata, $access_token, $access_token['uid']);
                if (!empty($result['created_at']))
                {
                    $data              = array(
                        'type' => 1,
                        'uid' => $access_token['uid'],
                        'name' => $userdata['name'],
                        'platid' => $platdata['id'],
                        'url' => $tokendata['shareurl'],
                        'content' => $text,
                        'picpath' => $picpath,
                        'jing' => $lat,
                        'wei' => $long,
                        'addtime' => TIMENOW,
                    );
                    $insert_id         = $this->obj->insert_record($data);
                    $return['id']      = $insert_id;
                    $return['name']    = $userdata['name'];
                    $return['picpath'] = $picpath;
                    $return['addtime'] = $data['addtime'];
                }
                else
                {
                    $return['error'] = $result['error'];
                }
//				print_r($userdata);exit;
                return $return;
            }
            else
            {
                return "没有分享的内容";
            }
        }
        else
        {
            return "无可用分享信息，请重新登录";
        }
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

$out    = new updateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'toshare';
}
$out->$action();
?>
