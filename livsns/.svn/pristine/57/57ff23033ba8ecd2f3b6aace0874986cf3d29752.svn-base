<?php
define('MOD_UNIQUEID','promote_info');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/promote_info_mode.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');
require_once(CUR_CONF_PATH."lib/company.class.php");
require_once(CUR_CONF_PATH."lib/avos/LeanCloud.php");
require_once(CUR_CONF_PATH.'lib/app.class.php');
require_once(CUR_CONF_PATH.'lib/leancloud_user.class.php');
require_once(CUR_CONF_PATH.'lib/leancloud_app_mode.php');
class promote_info extends outerReadBase
{
	private $mode;
    private $_upYunOp;
    private $company;
    private $lean;
    private $api;
    private $leancloud_user;
    private $leancloud_app;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new promote_info_mode();
        $this->_upYunOp = new UpYunOp();
        $this->company = new CompanyApi();
        $this->api = new app();
        $this->leancloud_user = new leancloud_user();
        $this->leancloud_app = new leancloud_app_mode();
    }
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	/**
	 * Display the count resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * input your param.
	 *
	 * @param  param
	 * @return Response
	 */
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		return $condition;
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function detail()
	{
		if($this->input['app_id'])
		{
			$ret = $this->mode->getInfoByAppId($this->input['app_id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}

	/**
	 * Create the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function create()
	{
		$app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$this->user['user_id'])
        {
            $this->errorOutput(NO_USER_INFO);
        }
        $data = array(
            'app_id'    => $app_id,
            'user_id'   => $this->user['user_id'],
            'user_name' => $this->user['user_name'],
            'created_at'=> date('Y-m-d H:i:s',TIMENOW),
            'status'    => 1,
        );

        //如果传递了3张推广截图
        for($i=1;$i<=3;$i++)
        {
            if(isset($_FILES['picture_'.$i.'']) && !$_FILES['picture_'.$i.'']['error'])
            {
                $img = $this->_upYunOp->uploadToBucket($_FILES['picture_'.$i.''],'',$this->user['user_id']);
                if($img)
                {
                    $img_info = array(
                        'host' 		=> $img['host'],
                        'dir' 		=> $img['dir'],
                        'filepath' 	=> $img['filepath'],
                        'filename' 	=> $img['filename'],
                        'imgwidth'	=> $img['imgwidth'],
                        'imgheight'	=> $img['imgheight'],
                    );
                    $data['picture_'.$i.''] = addslashes(serialize($img_info));
                }
            }
        }

        $info = $this->mode->getInfoByAppId($app_id);
        if($info)
        {
            $vid = $this->mode->update($app_id,$data);
        }
        else
        {
        	$data['create_time'] = TIMENOW;
            $vid = $this->mode->create($data);
        }

		if($vid)
		{
			//获取leancloud key
            if($data['status'] == 1)
            {
                $this->apply_leancloud_key($data['user_id'],$data['user_name']);
            }

            $data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	

	/**
	 * Update the resource.
	 *
	 * @param  condition
	 * @return Response
	 */
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$update_data = array(
			/*
				code here;
				key => value
			*/
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}


	/**
	 * Delete the resource.
	 *
	 * @param  condition
	 * @return Response
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
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}

    /**
     * 申请leancloud的appkey master_key
     * @param $user_id
     * @param $user_name
     */
    public function apply_leancloud_key($user_id,$user_name)
    {
        //申请创建成功后更改该用户的推送状态
        $company = new CompanyApi();
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



}

$out = new promote_info();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>