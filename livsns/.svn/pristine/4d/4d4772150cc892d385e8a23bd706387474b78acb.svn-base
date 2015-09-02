<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 后台申请认证操作接口（增、删、改）
 **************************************************************************/
define('MOD_UNIQUEID','identity_auth');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/identity_auth_mode.php');
require_once(CUR_CONF_PATH."lib/company.class.php");
require_once(CUR_CONF_PATH."lib/avos/LeanCloud.php");
require_once(CUR_CONF_PATH.'lib/app.class.php');
require_once(CUR_CONF_PATH.'lib/leancloud_user.class.php');

class identity_auth_update extends adminUpdateBase
{
    private $mode;
    private $company;
    private $lean;
    private $api;
    private $leancloud_user;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new identity_auth_mode();
        $this->company = new CompanyApi();
        $this->api = new app();
        $this->leancloud_user = new leancloud_user(); 
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create(){}
    public function update(){}
    public function sort(){}
    public function publish(){}

    /**
     * 删除申请
     *
     * @access public
     * @param  id:申请id，多个用逗号分隔
     *
     * @return array
     */
    public function delete()
    {
        if(!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        $ret = $this->mode->delete($this->input['id']);
        if($ret)
        {
            $this->addItem('success');
            $this->output();
        }
    }

    /**
     * 后台对提交过来的申请进行审核
     *
     * @access public
     * @param  id:申请id，多个用逗号分隔
     *
     * @return array
     */
    public function audit()
    {
        $id = $this->input['id'];
        if(!$id)
        {
            $this->errorOutput(NOID);
        }

        $status = intval($this->input['status']);
        if(!$status)
        {
            $this->errorOutput(NO_STATUS);
        }

        $data = array(
			'suggestion' 	=> $this->input['suggestion'],
			'status' 		=> $status,
        );
        $ret = $this->mode->update($id,$data);
        if($ret)
        {
            //申请创建成功后更改该用户的推送状态
            $company = new CompanyApi();
            //已审核，用户的推送状态是待审核
            if($status == 1)
            {
            	$company->modifyUserPushStatus($ret['dingdone_user_id'],2);
            }
            //已审核，用户的推送状态是待开通
            elseif($status == 2)
            {
            	$company->modifyUserPushStatus($ret['dingdone_user_id'],3);
            	
            	/*
            	$user_id = $ret['dingdone_user_id'];
                $company->modifyUserPushStatus($ret['dingdone_user_id'],5);
                 
                //先查看此用户是已经有app_key与app_id信息了 如果有，不需要一下操作
                $push_info = $company->getPushApiConfig($user_id);
                //已存在则不需要继续获取
                if($push_info && $push_info['app_id'] && $push_info['app_key'])
                {
                	
                }
                else
                {
                	//需要自动开通安卓推送功能
                	//先要通过leancloud开放平台创建用户，并且创建应用，拿到app_id app_key
                	$this->lean = new leanCloud();
                	//根据用户id拿到用户信息
                	$user_info = $this->company->getUserInfoByUserId($user_id);
                	$app_info = $this->api->getAppInfoByUserId($user_id);
                	$user_name = $ret['dingdone_name'];
                	$email = $ret['email'];        	
                	$result = $this->lean->createUser($email , $user_name);
                	$accsss_token = $result['access_token'];
                	$uid = $result['uid'];
                	//将信息存入liv_leancloud_user中
                	$data = $result;
                	$data['user_id'] = $user_id;
                	$data['user_name'] = $user_name;
                	$data['email'] = $email;
                	$data['create_time'] = TIMENOW;                	
                	$this->leancloud_user->create($data);                	
                	//创建应用
                	$name = $app_info['name'];
                	$description = $app_info['brief'];
                	$ret = $this->lean->createApp($accsss_token,$uid,$name,$description);
                	//将应用信息存入liv_leancloud_app中
                	$ret['user_id'] = $user_id;
                	$ret['user_name'] = $user_name;
                	if($ret['code'] == 1)
                	{
                		//已经存在 则取出key和id
                		$key_info = $this->lean->getAppInfoBy($uid,$accsss_token,$name);
                		$app_key = $key_info['app_key'];
                		$app_id  = $key_info['app_id'];               		
                		$isRet = array(
                			'user_id'   => $user_id,
                			'user_name' => $user_name,
                			'app_name'  => $name,
                			'app_key'   => $app_key,
                			'app_id'    => $app_id,
                			'client_id' => $key_info['client_id'],
                			'created'   => $key_info['created'],
                				
                		);
                		$this->api->create('leancloud_app', $isRet); 		
                	}
                	else
                	{
                		//将应用信息存入liv_leancloud_app中
                		$ret['user_id'] = $user_id;
                		$ret['user_name'] = $user_name;
                		
                		$this->api->create('leancloud_app', $ret); 	
                			
                		$app_key = $ret['app_key'];
                		$app_id  = $ret['app_id'];
                	}
                	$this->company->saveLeancloudParam($name,$user_id,$app_id,$app_key);
                }
                */
            }
            else if($status == 3)//被打回，用户的推送状态变成审核未通过
            {
                $company->modifyUserPushStatus($ret['dingdone_user_id'],4);
            }
            $this->addItem($ret);
            $this->output();
        }
    }

    public function unknow()
    {
        $this->errorOutput(UNKNOW);
    }
}
$out = new identity_auth_update();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unknow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();