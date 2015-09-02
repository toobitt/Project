<?php
define('MOD_UNIQUEID','promote_info');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/promote_info_mode.php');
require_once(CUR_CONF_PATH."lib/company.class.php");
require_once(CUR_CONF_PATH."lib/avos/LeanCloud.php");
require_once(CUR_CONF_PATH.'lib/app.class.php');
require_once(CUR_CONF_PATH.'lib/leancloud_user.class.php');
require_once(CUR_CONF_PATH.'lib/leancloud_app_mode.php');
class promote_info_update extends adminUpdateBase
{
	private $mode;
    private $company;
    private $lean;
    private $api;
    private $leancloud_user;
    private $leancloud_app;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new promote_info_mode();
        $this->company = new CompanyApi();
        $this->api = new app();
        $this->leancloud_user = new leancloud_user();
        $this->leancloud_app = new leancloud_app_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			/*
				code here;
				key => value
			*/
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
        if(!isset($this->input['status']))
        {
            $this->errorOutput(NO_STATUS);
        }

		$update_data = array(
			'status' => $this->input['status'],
		);
		$ret = $this->mode->updateStatus($this->input['id'],$update_data);
		if($ret)
		{
			//更新用户推送状态
            $this->update_push_status($update_data['status'],$ret['user_id'],$ret['user_name']);

			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}

    /**
     * 更新用户推送状态
     */
    private function update_push_status($status, $user_id, $user_name)
    {
        //申请创建成功后更改该用户的推送状态
        $company = new CompanyApi();
        //已审核，用户的推送状态是待审核
        if($status == 0)
        {
            $company->modifyUserPushStatus($user_id,2);
        }
        //已审核，用户的推送状态是待开通
        elseif($status == 1)
        {
            //$company->modifyUserPushStatus($user_id,3);

            //先查看此用户是已经有app_key与app_id信息了 如果有，不需要一下操作
            $push_info = $company->getPushApiConfig($user_id);
            //已存在则不需要继续获取
            if(!$push_info || (!$push_info['app_id'] && !$push_info['app_key']))
            {
                //先要通过leancloud开放平台创建用户，并且创建应用，拿到app_id app_key
                $this->lean = new leanCloud();
                //根据用户id拿到用户信息
                $user_info = $this->company->getUserInfoByUserId($user_id);
                $app_info = $this->api->getAppInfoByUserId($user_id);
                $email = $user_info['email'];
                $result = $this->lean->createUser($email , $user_name);
                if($result['code'] == 1)
                {
                    //用户在leancloud第一次创建应用失败
                    $result = $this->leancloud_user->detail(array('user_id' => $user_id));
                    if(!$result)
                    {
                        $this->errorOutput(CREATE_LEANCLOUD_USER_FAIL);
                    }
                }
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
                        'created_at'   => $key_info['created_at'],

                    );
                    $this->leancloud_app->create($isRet);
                }
                else
                {
                    //将应用信息存入liv_leancloud_app中
                    $ret['user_id'] = $user_id;
                    $ret['user_name'] = $user_name;
                    $ret['created_at'] = $ret['created'];
                    unset($ret['created']);
                    $this->leancloud_app->create($ret);

                    $app_key = $ret['app_key'];
                    $app_id  = $ret['app_id'];
                }

                //保存配置到 push_api_config
                $res = $this->company->saveLeancloudParam($name,$user_id,$app_id,$app_key);

                $company->modifyUserPushStatus($user_id,5);
            }

        }
        else if($status == 2)//被打回，用户的推送状态变成审核未通过
        {
            $company->modifyUserPushStatus($user_id,4);
        }
    }
}

$out = new promote_info_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>