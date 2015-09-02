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
define('MOD_UNIQUEID','app_store');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/app_store_mode.php');
require_once(CUR_CONF_PATH . 'lib/UpYunOp.class.php');

class app_store extends outerUpdateBase
{
    private $mode;
    private $_upYunOp;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new app_store_mode();
        $this->_upYunOp = new UpYunOp();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //生成一条申请
    public function create()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //首先查看有没有已经申请了
        $app_store_info = $this->mode->detail(''," AND status!=3 AND user_id = '" .$user_id. "' ");
        if($app_store_info)
        {
            $this->errorOutput(APP_IS_SUBMITED);
        }

        
	    $data = array(
	        'app_name'           	=> $this->input['app_name'],
    	    'version'      			=> $this->input['version'],
    	    'copy_right'           	=> $this->input['copy_right'],
    	    'brief'        			=> $this->input['brief'],
    	    'keywords'       		=> $this->input['keywords'],
	        'tech_surpport_site'   	=> $this->input['tech_surpport_site'],
	        'privacy_policy'       	=> $this->input['privacy_policy'],
	        'attach_id'        		=> $this->input['attach_id'],
	    	'app_icon'				=> intval($this->input['app_icon']),
	    	'apple_id'        		=> $this->input['apple_id'],
	    	'qq'					=> $this->input['qq'],
	    	'itunes_connect'		=> $this->input['itunes_connect'],
	    	'case_url'				=> addslashes(urldecode($this->input['case_url'])),
	    	'android_market1'		=> addslashes(urldecode($this->input['android_market1'])),
	    	'android_market2'		=> addslashes(urldecode($this->input['android_market2'])),
	    	'share_snap'			=> intval($this->input['share_snap']),
	    	'baidu_koubei_snap'		=> intval($this->input['baidu_koubei_snap']),
	    	'create_time'			=> TIMENOW,
	    	'update_time'			=> TIMENOW,
	    	'status'				=> 0,
	    	'user_id'				=> $this->user['user_id'],
	    	'user_name'				=> $this->user['user_name'],
	    	'zip'					=> addslashes(urldecode($this->input['zip'])),
    		'admin_user_name'		=> $this->input['admin_user_name'],
    		'admin_user_pwd'		=> $this->input['admin_user_pwd'],
    		'version_info'			=> $this->input['version_info'],
	    );
    	
	    if(!$this->check_attach_owner($data['share_snap'], $data['baidu_koubei_snap'], $data['attach_id']))
	    {
	    	$this->errorOutput(DATA_ERROR);
	    }
	    
    	if(is_array($data['attach_id']))
	    {
	    	$data['attach_id'] = serialize($data['attach_id']);
	    }
	    
	    $ret = $this->mode->create($data);
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
    
    //更新
    public function update()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $id = intval($this->input['id']);
        $apply = $this->mode->detail(''," AND id=".$id." AND user_id = '" .$user_id. "' ");
        if(!$apply)
        {
            $this->errorOutput(DATA_ERROR);
        }
        
        //只有被打回的申请才能重新提交
        if(intval($apply['status']) != 2)
        {
            $this->errorOutput(YOU_CAN_NOT_RE_SUBMIT_APPLY);
        }
		
        
	   $data = array(
	        'app_name'           	=> $this->input['app_name'],
    	    'version'      			=> $this->input['version'],
    	    'copy_right'           	=> $this->input['copy_right'],
    	    'brief'        			=> $this->input['brief'],
    	    'keywords'       		=> $this->input['keywords'],
	        'tech_surpport_site'   	=> $this->input['tech_surpport_site'],
	        'privacy_policy'       	=> $this->input['privacy_policy'],
	        'attach_id'        		=> $this->input['attach_id'],
	   		'apple_id'        		=> $this->input['apple_id'],
	    	'app_icon'				=> intval($this->input['app_icon']),
	    	'qq'					=> $this->input['qq'],
	    	'itunes_connect'		=> $this->input['itunes_connect'],
	    	'case_url'				=> addslashes(urldecode($this->input['case_url'])),
	    	'android_market1'		=> addslashes(urldecode($this->input['android_market1'])),
	    	'android_market2'		=> addslashes(urldecode($this->input['android_market2'])),
	    	'share_snap'			=> intval($this->input['share_snap']),
	    	'baidu_koubei_snap'		=> intval($this->input['baidu_koubei_snap']),
	   		'update_time'			=> TIMENOW,
	   		'user_id'				=> $this->user['user_id'],
	   		'status'				=> 0,
	   		//'message'				=> '',
	   		//'zip'					=> addslashes(urldecode($this->input['zip'])),
	   		'admin_user_name'		=> $this->input['admin_user_name'],
	   		'admin_user_pwd'		=> $this->input['admin_user_pwd'],
	   		'version_info'			=> $this->input['version_info'],
	    );
	    if(urldecode($this->input['zip']))
	    {
	    	$data['zip'] = addslashes(urldecode($this->input['zip']));
	    }
    	if(is_array($data['attach_id']))
	    {
	    	$data['attach_id'] = serialize($data['attach_id']);
	    }
    	if(!$this->check_attach_owner($data['share_snap'], $data['baidu_koubei_snap'], $data['attach_id']))
	    {
	    	$this->errorOutput(DATA_ERROR);
	    }
	    $ret = $this->mode->update($apply['id'],$data);
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
        $apply = $this->mode->detail(''," AND status  in(0,1,2) AND user_id = '" .$this->user['user_id']. "' ");
        if(!$apply)
        {
        	$apply = $this->mode->detail(''," AND status = 3 AND user_id = '" .$this->user['user_id']. "' ORDER BY ID DESC");
        }
        if($apply)
        {
            $this->addItem($apply);
        }
        else 
        {
            $this->addItem(array('nodata' => 1));
        }
        $this->output();
    }
    
    public function delete(){}
    public function check_attach_owner($share_snap=0,$baidu_koubei_snap=0, $preview_snap=array())
    {
    	$attach_id = array(
    	'baidu_koubei_snap'=>$baidu_koubei_snap,
    	'share_snap'	   =>$share_snap,
    	'attach_id'		   =>$preview_snap,
    	);
    	$attach = $this->mode->get_attach($attach_id);
    	if(!$attach)
    	{
    		return false;
    	}
    	foreach ($attach as $val)
    	{
    		if($val['user_id'] != $this->user['user_id'])
    		{
    			return false;
    		}
    	}
	    return true;
    }
    public function unkow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new app_store();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unkow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();