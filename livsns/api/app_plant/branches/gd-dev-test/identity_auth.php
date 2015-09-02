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
define('MOD_UNIQUEID','identity_auth');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/identity_auth_mode.php');
require_once(CUR_CONF_PATH.'lib/company.class.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');

class identity_auth extends outerUpdateBase
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

    public function delete() {}

    /**
     * 获取某个人认证详情
     *
     * @access public
     * @param  uid | access_token
     * @return array
     */
    public function detail()
    {
        $user_id = 0;
        if ($this->user['user_id'])
        {
            $user_id = intval($this->user['user_id']);
        }
        elseif ($this->input['uid'])
        {
            $user_id = intval($this->input['uid']);
        }
        $queryData = array('dingdone_user_id' => $user_id);
        $info = $this->mode->detail($queryData);
        $this->addItem($info);
        $this->output();
    }

    /**
     * 接收提交过来的申请
     *
     * @access   public
     * @param    name:姓名
     *           type:申请类型
     *           identity_type:证件类型
     *           identity_num:证件号
     *           address:地址
     *           province:省份地区码
     *           city:城市地区码
     *           telephone:电话
     *           link_man:联系人
     *           product_brief:产品描述
     * @return   array
     */
    public function create()
    {
        $dingdone_user_id = $this->user['user_id'];
        if(!$dingdone_user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }

        //首先判断用户是否已经提交过申请，已经提交过就不能再提交
        $identity_auth = $this->mode->detail(array(
				'dingdone_user_id' => $dingdone_user_id,
        ));

        if($identity_auth)
        {
            $this->errorOutput(IDENTITY_AUTH_HAS_EXISTS);
        }

        $name 				= $this->input['name'];//姓名
        $dingdone_name 		= $this->user['user_name'];//叮当账号名
        $type 				= $this->input['type'];//申请类型
        $identity_type 		= $this->input['identity_type'];//证件类型
        $identity_num 		= $this->input['identity_num'];//证件号
        $address 			= $this->input['address'];//地址
        $province 			= $this->input['province'];//省份地区码
        $city 				= $this->input['city'];//城市地区码
        $district           = $this->input['district'];//区/县地区码
        $telephone 			= $this->input['telephone'];//电话
        $email				= $this->input['email'];//邮箱
        $link_man			= $this->input['link_man'];//联系人
        $product_brief		= $this->input['product_brief'];//产品描述

        if(!$name)
        {
            $this->errorOutput(NO_NAME);
        }

        if(!$type)
        {
            $this->errorOutput(NO_TYPE);
        }

        if(!$identity_type)
        {
            $this->errorOutput(NO_IDENTITY_TYPE);
        }

        if(!$identity_num)
        {
            $this->errorOutput(NO_IDENTITY_NUM);
        }

        if(!$address)
        {
            $this->errorOutput(NO_ADDRESS);
        }

        if(!email)
        {
            $this->errorOutput(NO_EMAIL);
        }

        $data = array(
			'name' 				=> $name,
			'dingdone_name' 	=> $dingdone_name,
			'dingdone_user_id' 	=> $dingdone_user_id,
			'type' 				=> $type,
			'identity_type' 	=> $identity_type,
			'identity_num' 		=> $identity_num,
			'address' 			=> $address,
			'province' 			=> $province,
			'city' 				=> $city,
		    'district'          => $district,
			'telephone' 		=> $telephone,
			'email' 			=> $email,
			'link_man' 			=> $link_man,
			'product_brief' 	=> $product_brief,
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
            'is_push'			=> 1,//标识这是推送
        );

        //如果传递了证件图
        if(isset($_FILES['credentials']) && !$_FILES['credentials']['error'])
        {
            /*
            $_FILES['Filedata'] = $_FILES['credentials'];
            unset($_FILES['credentials']);
            $material_pic = new material();
            $img = $material_pic->addMaterial($_FILES);
            */
            
            $img = $this->_upYunOp->uploadToBucket($_FILES['credentials'],'',$this->user['user_id']);
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

        $ret = $this->mode->create($data);
        if($ret)
        {
            //申请创建成功后更改该用户的推送状态
            $company = new CompanyApi();
            $company->modifyUserPushStatus($dingdone_user_id,2);
            $this->addItem(array('return' => 1));
        }
        else
        {
            $this->addItem(array('return' => 0));
        }
        $this->output();
    }

    /**
     * 更新申请，重新提交
     *
     * @access    public
     * @param     name:姓名
     *            type:申请类型
     *            identity_type:证件类型
     *            identity_num:证件号
     *            address:地址
     *            province:省份地区码
     *            city:城市地区码
     *            telephone:电话
     *            link_man:联系人
     *            product_brief:产品描述
     * @return    array
     */
    public function update()
    {
        $id = $this->input['id'];
        if(!$id)
        {
            $this->errorOutput(NOID);
        }

        $name 				= $this->input['name'];//姓名
        $type 				= $this->input['type'];//申请类型
        $identity_type 		= $this->input['identity_type'];//证件类型
        $identity_num 		= $this->input['identity_num'];//证件号
        $address 			= $this->input['address'];//地址
        $province 			= $this->input['province'];//省份地区码
        $city 				= $this->input['city'];//城市地区码
        $district           = $this->input['district'];//区/县地区码
        $telephone 			= $this->input['telephone'];//电话
        $email				= $this->input['email'];//邮箱
        $link_man			= $this->input['link_man'];//联系人
        $product_brief		= $this->input['product_brief'];//产品描述

        if(!$name)
        {
            $this->errorOutput(NO_NAME);
        }

        if(!$type)
        {
            $this->errorOutput(NO_TYPE);
        }

        if(!$identity_type)
        {
            $this->errorOutput(NO_IDENTITY_TYPE);
        }

        if(!$identity_num)
        {
            $this->errorOutput(NO_IDENTITY_NUM);
        }

        if(!$address)
        {
            $this->errorOutput(NO_ADDRESS);
        }

        if(!email)
        {
            $this->errorOutput(NO_EMAIL);
        }

        $data = array(
			'name' 				=> $name,
			'type' 				=> $type,
			'identity_type' 	=> $identity_type,
			'identity_num' 		=> $identity_num,
			'address' 			=> $address,
			'province' 			=> $province,
			'city' 				=> $city,
		    'district'          => $district,
			'telephone' 		=> $telephone,
			'email' 			=> $email,
			'link_man' 			=> $link_man,
			'product_brief' 	=> $product_brief,
			'update_time'		=> TIMENOW,
			'status'			=> 1,//由于是重新提交，状态要变成待审核
            'is_push'			=> 1,//标识这是推送
        );

        //如果传递了证件图
        if(isset($_FILES['credentials']) && !$_FILES['credentials']['error'])
        {
            /*
            $_FILES['Filedata'] = $_FILES['credentials'];
            unset($_FILES['credentials']);
            $material_pic = new material();
            $img = $material_pic->addMaterial($_FILES);
            */
            $img = $this->_upYunOp->uploadToBucket($_FILES['credentials'],'',$this->user['user_id']);
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
         
        $ret = $this->mode->update($id,$data);
        if($ret)
        {
            //申请创建成功后更改该用户的推送状态
            $company = new CompanyApi();
            $company->modifyUserPushStatus($ret['dingdone_user_id'],2);
            $this->addItem(array('return' => 1));
        }
        else
        {
            $this->addItem(array('return' => 0));
        }
        $this->output();
    }

    public function unkow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new identity_auth();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unkow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();