<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: ui.base.php 8049 2014-10-22 04:03:42Z develop_tong $
***************************************************************************/
/**
 * 初始化抽象类，完成程序的初始化工作
 * @author develop_tong
 *
 */
abstract class InitFrm
{
	protected $db;
	protected $input;
	protected $settings;
	protected $user;
	protected $cache;
	protected $channel;
	protected $lang; //语言词条

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
		global $_INPUT, $gGlobalConfig, $gUser,$gCache;
		$this->input = &$_INPUT;
		$this->settings = &$gGlobalConfig;
		$this->user = &$gUser;
		$this->cache = &$gCache;
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
	}

	protected function output($data)
	{
		if ($this->input['debug'])
		{
			echo '<pre>';
			print_r($data);
			echo '</pre>';
		}
		else
		{
			echo json_encode($data);
		}
		exit;
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
				hg_debug_tofile($data, 1, LOG_DIR, 'debug.txt');
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
	protected $tpl;
	protected $_ext_link;
	protected $_pp;
	protected $nav = array();
	function __construct()
	{
		global $gTpl;
		parent::__construct();
		$this->tpl = $gTpl;

		if ($this->user['group_type'] > MAX_ADMIN_TYPE && $this->settings['upgrading']['open'] && SCRIPT_NAME != 'login')
		{
			$this->tpl->setTemplateTitle('系统升级中...');
			$this->tpl->addVar('message', $this->settings['upgrading']['message']);
			$endtime = strtotime($this->settings['upgrading']['endtime']);
			if ($endtime < TIMENOW)
			{
				$endtime = TIMENOW + 21600;
			}
			$format_endtime = date('Y-m-d H:i', $endtime);
			$this->tpl->addVar('format_endtime', $format_endtime);
			$this->tpl->addVar('endtime', $endtime);
			$this->tpl->outTemplate('upgrading');
		}
		if (defined('INITED_APP') && !INITED_APP) //需要初始化应用而未初始化，进入初始化流程
		{
			$file = 'conf/init.data';
			if (is_file($file))
			{
				$content = file_get_contents($file);
				if ($content)
				{
					$this->db = hg_checkDB();
					preg_match_all('/INSERT\s+INTO\s+(.*?)\(.*?\)\s*;;/is', $content, $match);
					$insertsql = $match[0];
					if ($insertsql)
					{
						$this->db->mErrorExit = false;
						foreach ($insertsql AS $sql)
						{
							$sql = preg_replace('/INSERT\s+INTO\s+([`]{0,1})liv_/is', 'INSERT INTO \\1' . DB_PREFIX, $sql);
							$this->db->query($sql);
						}
						$this->db->mErrorExit = true;
					}
				}
			}
			if (!is_writeable('conf/config.php'))
			{
				if ($this->settings['mcphost'])
				{
					$m2oserver = array(
						'ip' => $this->settings['mcphost'],	
						'port' => 6233,
					);
					hg_run_cmd( $m2oserver, 'runcmd', 'chmod -Rf 777 ' .  realpath('conf/config.php'));
				}
			}
			$content = @file_get_contents('conf/config.php');
			$content = preg_replace("/define\('INITED_APP',\s*.*?\);/is","define('INITED_APP', true);", $content);
			@file_put_contents('conf/config.php', $content);
		}
		elseif(!defined('INITED_APP')) //不需要初始化应用或之前版本不包含此设置，加入此设置
		{
			if (is_writeable('conf/config.php'))
			{
				$content = @file_get_contents('conf/config.php');
				$content = preg_replace("/\?>/is", "\ndefine('INITED_APP', true);\n?>", $content);
				@file_put_contents('conf/config.php', $content);
			}
		}
		$this->check_lang();
		$this->load_lang('global');
		if(!$this->input['referto'])
		{
			$this->input['referto'] = str_replace('&a=form', '',$_SERVER['REQUEST_URI']);
			//$this->input['referto'] = REFERRER;
		}
		$dialog = array(
			'id' => 'livwindialog',
		);
		//$_SERVER['HTTP_CHANNEL'] = 'hnws';
		if ($_SERVER['CHANNEL_CODE'])
		{
			$this->input['infrm'] = 1;
			if ($this->settings['App_live'])	//调用直播互动登陆模板
			{
				//获取频道信息
				///*
				include_once(ROOT_PATH . 'lib/class/curl.class.php');
				$mLive = new curl($this->settings['App_live']['host'], $this->settings['App_live']['dir']);
				$mLive->initPostData();
				$mLive->addRequestData('a', 'show');
				$mLive->addRequestData('channel_code', $_SERVER['CHANNEL_CODE']);
				$ret_channel = $mLive->request('channel.php');
				$this->channel = $ret_channel[0];
				$this->input['channel_id'] = $this->channel['id'];
				$reffer = 'run.php?mid=' . $this->settings['App_interactive']['mid'][$this->user['group_type']] . '&channel_id=' . $this->input['channel_id'];
				$this->tpl->setTemplateTitle($this->channel['channel']['name'] . '直播互动平台');
				//*/
			}
		}
		else
		{
			$this->check_nav();
			if (defined('CUSTOM_NAME'))
			{
				$meta_title = CUSTOM_NAME . $this->settings['name'];
			}
			else
			{
				$meta_title =  $this->settings['name'];
			}
			$this->tpl->setTemplateTitle($meta_title);
		}
		$this->tpl->addVar('dialog', $dialog); //弹出框
		$this->tpl->addVar('_INPUT', $this->input);
		$this->tpl->addVar('_user', $this->user);
		$this->tpl->addVar('_settings', $this->settings);
	}

	function __destruct()
	{
		parent::__destruct();
	}

	protected function check_api()
	{
		$this->cache->check_cache('applications');
		$applications = $this->cache->cache['applications'];
		if (!$applications) //无应用服务器设置，进入服务器设置
		{
			header('Location:./api_install.php');
		}
	}
	protected function check_nav()
	{
		//导航的第一级 系统英文名称
		$this->nav[] = array(
			'name'  =>  $this->settings['enname'],
			'class' => '',
			'link'  => '',
			'target' => 'mainwin'
		);
		if ($this->input['infrm'])
		{
			$this->_ext_link = '&infrm=' . $this->input['infrm'];
		}
		if ($this->input['pp'])
		{
			$this->_pp = '&pp=' . $this->input['pp'];
			$this->_ext_link .= $this->_pp;
			$this->tpl->addVar('_pp', $this->_pp);
		}
		$this->tpl->addVar('_ext_link', $this->_ext_link );
		$module_id = intval($this->input['mid']);
		$applcation_id = intval($this->input['appid']);
		$this->cache->check_cache('applications');
		$applications = $this->cache->cache['applications'];
		$target = 'mainwin';
		if ($module_id)
		{
			$this->cache->check_cache('modules');
			$modules = $this->cache->cache['modules'];
			$applcation_id = $modules[$module_id]['application_id'];
			$father_id = $modules[$module_id]['fatherid'];
			//导航的第二级 应用名称（主模块）
			$this->nav[] = array(
				'name'  => $applications[$applcation_id]['name'],
				'class' => '',
				'has_setting' => !$applications[$applcation_id]['nosetting'],
				'link'  => 'run.php?a=app&appid=' . $applcation_id,
				'mid'  => $module_id,
				'appid'  => $applcation_id,
				'target' => 'mainwin'
			);
			/*
			 * 取消父级模块的显示
			if ($father_id)
			{
				$this->nav[] = array(
					'name'  => $modules[$father_id]['name'],
					'class' => '',
					'link'  => '',
					'mid'  => $father_id,
					'appid'  => $applcation_id,
					'target' => 'mainwin'
				);
			}
			*/
			//导航的第三级 模块自身名称（主模块）
			$this->nav[] = array(
				'name'  => $modules[$module_id]['name'],
				'class' => '',
				'link'  => '',
				'appid'  => $applcation_id,
				'target' => 'mainwin'
			);
			$node_id = $modules[$module_id]['node_id'];
			$target = $node_id ? 'nodeFrame' : $target;
		}
		$this->tpl->addVar('_nav', $this->nav);
	}

	protected function append_nav($nav)
	{
		$this->nav[] = $nav;
	}

	protected function check_login()
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
	public function Redirect($message = '', $url = '', $delay = 1, $header = 0,  $callback = '')
	{
		if (!$url)
		{
			$url = $this->GetReferUrl();
		}
		else
		{
			if (!$this->input['goon'])
			{
				$url = $this->GetReferUrl();
			}
		}
		if ($this->input['infrm'])
		{
			if (!strstr($url, 'infrm='))
			{
				if (!strstr($url, '?'))
				{
					$sp = '?';
				}
				else
				{
					$sp = '&amp;';
				}
				$url = $url . $sp . 'infrm=' . $this->input['infrm'];
			}
		}
		$url = str_replace(array('&a=frame', '&amp;a=frame'), '', $url);
		if (!$this->input['ajax'])
		{
			if ($header)
			{
				header('Location:' . $url);
			}
			if ($this->mGuide && $delay < 2)
			{
				$delay = 3;
			}
			$callback = html_entity_decode($callback);
			$new_callback = substr($callback, 0,strpos($callback, '(')+1) . "''," . substr($callback,strpos($callback,'(')+1,strlen($callback));
			$extra_header = '';
			if(substr($new_callback,0,6) != 'top.$.')
			{
				$extra_header .= '<meta http-equiv="refresh" content="' . $delay . '; url=' . $url . '" />';
			}
			
			/*当submit_type=1时关闭当前iframe*/
/*
			if($this->input['submit_type'] == 1)
			{
*/
				$extra_header .= '<script>';
				$extra_header .= 'if (top !== self) {' . $new_callback . ";}else{setTimeout(function(){try{window.opener=null;window.open('', '_self');window.close();}catch(e){}}, 1500);}";
				$extra_header .= '</script>';
/* 			} */
			
			unset($this->nav);
			$this->tpl->addVar('_nav', $this->nav);
			$this->tpl->setTemplateTitle('正在转向...');
			$this->tpl->addVar('message', $message);
			$this->tpl->addVar('url', $url);
			$this->tpl->addHeaderCode($extra_header);
			$this->tpl->outTemplate('redirect');
		}
		else
		{
			$data = array(
			'msg' => $message,
			'referto' => $url,
			'callback' => $callback,
			);
			echo json_encode($data);
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
	public function ReportError($message = '', $need_login = 1, $templates = "error", $tpldir = './tpl/')
	{
		$url = $_SERVER['HTTP_REFERER'];
		$this->input['referto'] = REFERRER;
		unset($this->nav);
		$this->tpl->addVar('_nav', $this->nav);
		if(!$this->user['id'] && $need_login)
		{
				$this->tpl->setTemplateTitle('登录');
				$this->tpl->addVar('message', $message);
				$this->tpl->addVar('referto', $this->input['referto']);
				$this->tpl->outTemplate('login', 'hg_show_error');
		}
		else
		{
			$this->tpl->setTemplateTitle('出错了');
			$this->tpl->addVar('message', $message);
			$this->tpl->addVar('url', $url);
			$this->tpl->outTemplate('error' . $this->input['ajax'], 'hg_show_error');
		}
		exit();
	}

	protected function record_search()
	{
		$hash = $this->input['search_hash'];
		if ($this->input['hg_search'])
		{
			$this->db = hg_checkDB();
			unset($this->input['search_hash']);
			$search = serialize($this->input);
			$hash = md5($search . SCRIPT_NAME);
			$data = array(
				'hash' => $hash,
				'search' => $search,
				'update_time' => TIMENOW,
			);
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'search WHERE hash=\'' . $hash . "'";
			$search = $this->db->query_first($sql);
			if ($search)
			{
				$searchs = unserialize($search['search']);
				$conditicon = "hash='$hash'";
			}
			else
			{
				$searchs = $this->input;
			}
			hg_fetch_query_sql($data, 'search', $conditicon);
			$sql = 'DELETE FROM ' . DB_PREFIX . 'search WHERE update_time < ' . (TIMENOW - 3600); //1小时前的搜索清理
			$this->db->query($sql);
		}

		if ($hash && !$searchs)
		{
			$this->db = hg_checkDB();
			$sql = 'SELECT hash,search FROM ' . DB_PREFIX . 'search WHERE hash=\'' . $hash . "'";
			$search = $this->db->query_first($sql);
			$searchs = unserialize($search['search']);
		}
		if ($searchs)
		{
			$this->input['search_hash'] = $hash;
			$this->input = $searchs + $this->input;
		}
	}
	/**
	* 检测接口是否正常
	* 
	*/
	public function check_api_state()
	{
		$type = $this->input['type'];
		$type = $type ? $type : 'http';
		$host = $this->input['host'];
		$dir = $this->input['dir'];
		$file = $this->input['file'];
		$return = $this->input['return'];
		if ($host && $dir && $file)
		{
			$url = $type . '://' . $host . '/' . $dir . $file;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			
			curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			if($type == 'https')
			{
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$ret = curl_exec($ch);
			$head_info = curl_getinfo($ch);
			curl_close($ch);
			return $head_info['http_code'];
		}
		else
		{
			return false;
		}
	}
}

class uiview extends uiBaseFrm
{
	protected function check_api()
	{
	}
}