<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: ui.base.php 4099 2011-06-21 07:58:25Z repheal $
***************************************************************************/
/**
 * 初始化抽象类，完成程序的初始化工作
 * @author develop_tong
 *
 */
abstract class InitFrm
{
	var $db;
	var $input;
	var $settings;
	var $user;
	var $lang; //语言词条
	var $tpl;
    
	function __construct()
	{
		global $gDB; 
		$this->db = &$gDB;
		$this->__init();
	}
	
	function __destruct()
	{
	}
	
	function __call($function,$param)
	{
		echo $function."方法没有被实现";
	}

	public function __init()
	{
		global $_INPUT, $gGlobalConfig, $gUser,$gSite,$gNavConfig, $gTpl;
		$this->input = &$_INPUT;
		$this->settings = &$gGlobalConfig;
		$this->user = &$gUser;
		$this->nav = &$gNavConfig;
		$this->tpl = &$gTpl;
		$this->tpl->setTemplateGroup(DEFAULT_TEMPLATE); 
		$this->tpl->addVar('_INPUT', $this->input);
		$this->tpl->addVar('_settings', $this->settings);
		$this->tpl->addVar('_user', $this->user);
		$this->tpl->addVar('_nav', $this->nav);
	}	
	
	public function GetReferUrl()
	{
		if ($this->input['referto'])
		{
			$url = $this->input['referto'];
			if (strstr($url, 'login.php'))
			{
				$url = 'index.php';
			}
		}
		elseif(REFERRER)
		{
			$url = REFERRER;
		}
		else
		{
			$url = 'index.php';
		}
		return $url;
	}
	protected function check_lang()
	{
		$this->cur_lang = 'zh-cn';
	}
	
	/**
	 * 载入语言词条
	 * 原先 include_once 被我修改了 如果后面不同类中反复引用同一文件 会出错 ( unset 了) 
	 * @param string $name 语言文件名
	 */
	
	protected function load_lang($file, $dir = '')
	{
		@include("lang/{$this->cur_lang}/{$dir}{$file}.php");
		if ($lang) 
		{
			$this->lang = array_merge((array)$this->lang, $lang);
			unset($lang);
		}		
		$this->tpl->addVar('_lang', $this->lang);
	}

	/**
	*  输出调试结果，debug用
	*
	*/
	protected function debug($data = '')
	{
		if (DEBUG_MODE)
		{
			if (1 == DEBUG_MODE)
			{
				if (!is_string($data))
				{
					print_r($data);
				}
				else
				{
					echo $data;
				}	
				echo '<br />#----------------------------------------------------------------------------------------------------------------------------#<br />';
				echo hg_page_debug();
			}
			else
			{
				hg_mkdir(LOG_DIR);
				hg_debug_tofile($data, 1, LOG_DIR . 'debug.txt');
			}
		}
	}

}

/**
 * 主程序抽象类
 * @author develop_tong
 *
 */
abstract class uiBaseFrm extends InitFrm
{
    var $mGuide;
	function __construct()
	{
		parent::__construct();

		$this->__callprms();
		$this->check_lang();
		$this->load_lang('global');
		if(!$this->input['referto'])
		{
			$this->input['referto'] = REFERRER;
		}
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 功能权限统一处理，若某模块无需权限或自定义权限只需重定义此函数即可
	 * @return unknown_type
	 */
	public function __callprms()
	{
	}

	public function check_login()
	{
		if (!$this->user['id'])
		{
			$this->ReportError('',1);
			exit;
		}
	}
	
	/**
	 * 转向函数
	 * @param string $message 转向时提示文字
	 * @param string $url 转向到url，无时将自动取上次页面
	 * @param string $delay 延迟转向，单位秒
	 * @param string $header 是否采用header转向，默认html转向
	 * @return 无返回
	 */	
	public function Redirect($message = '', $url = '', $delay = 1, $header = 0, $extra_header = '')
	{		
		if (!$this->input['ajax'])
		{
			if (!$url)
			{
				$url = $this->GetReferUrl();
//				echo $url;
			}
			if ($header)
			{
				header('Location:' . $url);
			}
			if ($this->mGuide && $delay < 2)
			{
				$delay = 3;
			}
			$extra_header .= '<meta http-equiv="refresh" content="' . $delay . '; url=' . $url . '" />';
					
			$this->tpl->setTemplateTitle('正在转向...');
			$this->tpl->addVar('message', $message);
			$this->tpl->addVar('url', $url);
			$this->tpl->addHeaderCode($extra_header);
			$this->tpl->outTemplate('redirect');
		}
		else
		{
			if($url)
			{
				$data = array(
				'isclose' => 1,
				'msg' => $message,
				'referto' => $url
				);
				echo json_encode($data);
			}
			else
			{
				echo $message;
			}
		}
		exit();
	}

	/**
	 * 出错提示函数
	 * @param string $message 出错时提示文字
	 * @param string $templates 出错调用模板
	 * @param string $tpldir 出错调用模板所在目录
	 * @return 无返回
	 */	
	public function ReportError($message = '', $need_login = 0, $templates = "error", $tpldir = './tpl/')
	{
		$url = $_SERVER['HTTP_REFERER'];
		if (!$this->input['ajax'])
		{
			$this->input['referto'] = REFERRER;
			if(!$this->user['id'] && $need_login)
			{
				if(!SNS_UCENTER)
				{
					$message = '请先登录…';
					$this->tpl->setTemplateTitle('登录');
					$this->tpl->addVar('message', $message);
					$this->tpl->addVar('referto', $this->input['referto']);
					$this->tpl->outTemplate('login');
				}
				else
				{
					header('Location:' . SNS_UCENTER . 'login.php?referto=' . urlencode($this->input['referto']));
				}
			}
			else 
			{
				$this->tpl->setTemplateTitle('出错了');
				$this->tpl->addVar('message', $message);
				$this->tpl->addVar('url', $url);
				$this->tpl->outTemplate('error');
			}
		}
		else
		{
			echo $message;
		}
		exit();
	}
}