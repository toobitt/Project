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
define('MOD_UNIQUEID','report');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/report_center_mode.php');
include_once ROOT_PATH . 'lib/class/members.class.php';
require_once(CUR_CONF_PATH . 'lib/app.class.php');


class report extends outerUpdateBase
{
	private $report;
	private $member;
	private $app;
    public function __construct()
    {
        parent::__construct();
        $this->report = new report_center_mode();
        $this->member = new members();
        $this->app = new app();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create()
    {
        $app_id = intval($this->input['app_id']);
      	$content = trim($this->input['content']);
      	$device_token = trim($this->input['device_token']);
      	$model = trim($this->input['model']);
      	$client_type = trim($this->input['client_type']);
      	$system = trim($this->input['system']);
      	$tele_phone = trim($this->input['tele_phone']);
      	$app_version = trim($this->input['app_version']);	
      	$is_debug = intval($this->input['is_debug']);
        $type = trim($this->input['type']) ? trim($this->input['type']) : 'app';
        $reason = trim($this->input['reason']);
        $reported_uid = intval($this->input['reported_uid']);
        $reported_uname = trim($this->input['reported_uname']);
        $relation_id = intval($this->input['relation_id']);
        $relation_content = trim($this->input['relation_content']);
        $relation_comment = trim($this->input['relation_comment']);
        $table_name = trim($this->input['table_name']);
      	if(!$app_id)
      	{
      		$this->errorOutput(APP_ID_NULL);
      	}
      	//检验appInfo是否存在
      	$dataArray = array(
      			'id' => $app_id,
      	);
      	$app_info = $this->app->detail('app_info', $dataArray);
      	if(!$app_info)
      	{
      		$this->errorOutput(APP_ID_WRONG);
      	}
        if(!$type)
        {
            $this->errorOutput(NO_TYPE);
        }
      	if(!$content)
      	{
      		$this->errorOutput(NO_REPORT_CONTENT);
      	}
      	if(!$device_token)
      	{
      		$this->errorOutput(NO_DEVICE_TOKEN);
      	}
      	if(!$client_type)
      	{
      		$this->errorOutput(CLIENT_TYPE_WRONG);
      	}
      	if(!$system)
      	{
      		$this->errorOutput(NO_SYSTEM_INFO);
      	}
      	if(!$app_version)
      	{
      		$this->errorOutput(NO_VERSION_INFO);
      	}
      	if(!$model)
      	{
      		$this->errorOutput(NO_MODEL_INFO);
      	}
 		$data = array(
 			'app_id'  	    => $app_id,
 			'app_name'		=> $app_info['name'],
 			'content' 		=> $content,
 			'device_token'  => $device_token,
 			'create_time' 	=> TIMENOW,
 			'model'   		=> $model,
 			'client_type'   => $client_type,
 			'system'        => $system,
 			'tele_phone'    => $tele_phone,
 			'app_version'   => $app_version,
 			'is_debug'		=> $is_debug,
 			'type'          => $type,
            'reported_uid'  => $reported_uid,
            'reported_uname'=> $reported_uname,
            'relation_id'   => $relation_id,
            'relation_content' => $relation_content,
            'relation_comment' => $relation_comment,
            'table_name'    => $table_name,
            'reason'        => $reason,
 		);
 		$member_id = intval($this->input['member_id']);
 		//如果会员存在 获取会员信息
 		if($member_id)
 		{
 			$member_info = $this->get_member($member_id);
 			if(!$member_info || $app_id != $member_info['identifier'])
 			{
 				$this->errorOutput(REPORT_MEMBERID_ERROR);
 			}
 		}	
 		if($member_info && is_array($member_info))
 		{
 			$data['member_name'] = $member_info['member_name'];
 		}
      	//插入
      	$vid = $this->report->create($data);
        if($vid)
        {
            $data['id'] = $vid;
            $this->addItem($data);
        }
        $this->output();
    }
    
    public function detail()
    {
    	
    }

    /**
     * 更新内容状态
     */
    public function update()
    {
        if(!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $status = intval($this->input['status']);
        $update_data = array(
            'status' => $status,
         );
        $ret = $this->report->update($this->input['id'],$update_data);
        if($ret)
        {
            //$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
            $this->addItem('success');
            $this->output();
        }
    }
    
    public function delete(){}
    
    public function get_member($member_id = 0){
    	$member_info = $this->member->get_members($member_id);
    	return $member_info[0];
    }
    
    
    public function unkow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new report();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unkow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();