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
 * @description 身份认证接口
 **************************************************************************/
define('MOD_UNIQUEID','developer_auth');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/identity_auth_mode.php');
require_once(CUR_CONF_PATH.'lib/company.class.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');

class developer_auth extends outerUpdateBase
{
    private $mode;
    private $_upYunOp;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new identity_auth_mode();
        $this->_upYunOp = new UpYunOp();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //检测该用户有没有已经提交了，如果提交了就更新数据
        $applyInfo = $this->mode->detail(array('dingdone_user_id' => $user_id));

        $name 				= $this->input['name'];//姓名
        $dingdone_name 		= $this->user['user_name'];//叮当账号名
        $dev_type 		    = $this->input['dev_type'];//证件类型
        $identity_num 		= $this->input['identity_num'];//证件号
        $address 			= $this->input['address'];//地址
        $province 			= $this->input['province'];//省份地区码
        $city 				= $this->input['city'];//城市地区码
        $district           = $this->input['district'];//区/县地区码
        $telephone 			= $this->input['telephone'];//电话
        $email				= $this->input['email'];//邮箱
        $link_man			= $this->input['link_man'];//联系人
        $link_phone    		= $this->input['link_phone'];//联系人手机号
        $link_email         = $this->input['link_email'];//联系人邮箱
        $company_brief      = $this->input['company_brief'];//
        $company_fax        = $this->input['company_fax'];//
        $company_site       = $this->input['company_site'];//
        $postalcode         = $this->input['postalcode'];//
        $tech               = $this->input['tech'];//
        $is_has_market      = $this->input['is_has_market'];
        $reason             = $this->input['reason'];
        $fixed_line         = $this->input['fixed_line'];
        $app_name 	 		= $this->input['app_name'];
    
        if(!$name)
        {
            $this->errorOutput(NO_NAME);
        }

        if(!$dev_type)
        {
            $this->errorOutput(NO_TYPE);
        }

        if(!$identity_num)
        {
            $this->errorOutput(NO_IDENTITY_NUM);
        }

        if(!email)
        {
            $this->errorOutput(NO_EMAIL);
        }

        $data = array(
            'name'               => $name,
            'dingdone_name'	     => $dingdone_name,
            'dingdone_user_id'   => $user_id,
            'dev_type'           => $dev_type,
            'identity_num'       => $identity_num,
            'address'            => $address,
        	'province'           => $province,
    	    'city'               => $city,
    	    'district'           => $district,
    	    'telephone'          => $telephone,
    	    'email'              => $email,
            'link_man'           => $link_man,
            'link_phone'         => $link_phone,
    	    'link_email'         => $link_email,
    	    'company_brief'      => $company_brief,
    	    'company_fax'        => $company_fax,
            'company_site'       => $company_site,
            'fixed_line'		 => $fixed_line,
            'postalcode'         => $postalcode,
    	    'tech'               => $tech,
    	    'is_has_market'      => $is_has_market,
    	    'reason'             => $reason,
            'create_time'	     => TIMENOW,
            'update_time'	     => TIMENOW,
            'is_developer'		 => 1,
        	'app_name'			 => $app_name,
        );
       
        if(isset($_FILES['identity_photo']) && !$_FILES['identity_photo']['error'])
        {
            $img = $this->_upYunOp->uploadToBucket($_FILES['identity_photo'],'',$this->user['user_id']);
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
                $data['identity_photo'] = addslashes(serialize($img_info));
            }
        }

        if($applyInfo)
        {
            $data['dev_status'] = 1;
            $ret = $this->mode->update($applyInfo['id'],$data);
        }
        else 
        {
            //申请创建成功后更改该用户的推送状态
            $company = new CompanyApi();
            $company->modifyUserPushStatus($user_id,4);
            $ret = $this->mode->create($data);
        }
        
        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
        else
        {
            $this->errorOutput(FAILED);   
        }
    }
    
    public function detail()
    {
        if(!$this->user['user_id'])
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $ret = $this->mode->detail(array('dingdone_user_id' => $this->user['user_id']));
        if($ret)
        {
            $this->addItem($ret);
            $this->output();
        }
        else
        {
            $this->errorOutput(NO_DATA);
        }
    }

    public function update(){}
    public function delete(){}
    
    public function unkow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new developer_auth();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unkow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();