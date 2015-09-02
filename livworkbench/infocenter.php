<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'infoCenter');
require('./global.php');
require(ROOT_DIR .'lib/class/curl.class.php');
require_once(ROOT_DIR .'lib/class/MibaoCard.class.php');
class infoCenter extends uiBaseFrm
{
	private $mibao;
	public function __construct()
	{
		parent::__construct();
		$this->mibao = new MibaoCard();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','getMemberById');
		$curl->addRequestData('id',$this->user['id']);
		$return = $curl->request('member.php');
		$return= $return[0][0];
		$this->tpl->addVar('a', 'setting');
		$this->tpl->addVar('id', $return['id']);
		$this->tpl->addVar('user_name', $return['user_name']);
		$this->tpl->addVar('avatar', $return['avatar']);
		$this->tpl->addVar('is_bind_card', $return['is_bind_card']);
		$this->tpl->addVar('is_open_card', $return['is_open_card']);
		$this->tpl->outTemplate('infocenter');
	}
	public function setting()
	{	
		$data = array(
			'id'=>intval($this->user['id']),
			'password'=>trim($this->input['password']),
			'password_again'=>trim($this->input['password_again']),
			'old_password'=>trim($this->input['old_password']),
		);
	
		if ($data['password'] && ($data['password'] != $data['password_again']))
		{
			$this->ReportError('两次输入的密码不一样');
		}
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','update_password');
		foreach ( $data as $key=>$val)
		{
			$curl->addRequestData($key,$val);
		}
		if ($_FILES['Filedata'])
		{
			$curl->addFile($_FILES);
		}
		$return = $curl->request('member.php');
		if($return && $return[0])
		{
			if ($return[0]['error'] == -1)
			{
				$this->ReportError('原始密码错误');
			}
		}
		$this->redirect('更新成功');
	}
	
	/***************************************************密保卡系列操作*****************************************************/
	//获取密保卡信息(如果没有绑定就为其绑定)
	public function get_mibao_info($re=false)
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$return = $this->mibao->get_mibao_info($id);
		if(!$return['zuobiao'])
		{
			$this->ReportError('该用户尚未绑定密保');
		}
		if($return['zuobiao'])
		{
			$ret['img'] = 'cache/mibao/mibao_'.$return['cardid'].'.jpg';
			if(!file_exists($ret['img']))//不存在就创建
			{
				$ret['img'] = $this->mibao->create_secret_image($return['cardid'], $return['zuobiao']);
			}
		}
		$ret['user_name'] = $return['user_name'];
		if($re) return $ret;
		echo json_encode($ret);
	}
	public function get_user_mibao()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->ReportError("非管理员无法操作");
		}
		$where = $this->get_mibao_info(true);
		
		$this->download_card($where['img'], $where['user_name']);
	}
	//为用户绑定密保卡(如果原来已经绑定就重新绑定)
	public function bind_card()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		//去绑定密保
		$return = $this->mibao->bind_card($id);
		if(!$return)
		{
			$this->ReportError('密保卡绑定失败');
		}
		//生成一张密保卡图片
		$ret['img'] = $this->mibao->create_secret_image($return['cardid'],$return['zuobiao']);
		echo json_encode($ret);
	}
	
	//取消密保绑定
	public function cancel_bind()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$return = $this->mibao->cancel_bind($id);
		if($return['return'] == 'success')
		{
			$ret['status'] = 1;
		}
		else 
		{
			$ret['status'] = 0;
		}
		echo json_encode($ret);
	}
	
	//下载密保卡
	public function download_card($img = '', $name="")
	{
		$this->mibao->download_card($img ? $img : $this->input['img'], $name);
	}
	
	/***************************************************密保卡系列操作*****************************************************/
}
include (ROOT_PATH . 'lib/exec.php');
?>