<?php 
class Templates
{
	private $mTemplateFrame;							//模板框架
	private $mSoftVar;							//软件产品标识
	private $mBodyCode;							//<body $mBodyCode>中代码
	private $mTemplate;							// 当前调用模板
	private $mTemplatesTitle = 'default';		//模板标题
	private $mTemplateGroup = 'default';   //模板风格分组
	private $mHeaderCode = array();							//<head>$mHeaderCode</head>中代码
	private $mFooterCode = array();							// $mFootCode</body>中代码
	private $mTemplates = array();			//模板单元
	private $mTemplateDatas = array();		//模板数据
	private $mRequestData = array();		//提交数据
	function __construct()
	{
		$this->mSoftVar = SOFTVAR;
		$this->mTemplatesTitle = SOFTVAR;
	}

	function __destruct()
	{
		$this->clearTemplateCell();
		$this->clearVar();
	}
	
	/**
	* 设置系统名
	*
	*/
	public function setSoftVar($var = '')
	{
		$this->mSoftVar= $var;
	}

	/**
	* 增加模板头部代码
	*
	*/
	public function addHeaderCode($code = '')
	{
		$this->mHeaderCode[] = $code;
	}

	/**
	* 设置<Body>代码
	*
	*/
	public function setBodyCode($code = '')
	{
		$this->mBodyCode = $code;
	}

	/**
	* 增加$mFootCode</body>中代码
	*
	*/
	public function addFooterCode($code = '')
	{
		$this->mFooterCode[] = $code;
	}

	/**
	* 设置模板组
	*
	*/
	public function setTemplateGroup($group_name = 'default')
	{
		$this->mTemplateGroup = $group_name;
	}

	/**
	* 设置模板标题
	*
	*/
	public function setTemplateTitle($title)
	{
		$this->mTemplatesTitle = $title;
	}

	/**
	* 设置模板框架
	*
	*/
	public function setTemplate($template_name = 'default')
	{
		$this->mTemplateFrame = $template_name;
	}
	
	/**
	* 清除模板设置
	*
	*/
	public function clearTemplateCell()
	{
		$this->mTemplates = array();
	}

	/**
	* 添加模板引用
	*
	*/
	public function addTemplateCell($template_name)
	{
		$this->mTemplates[] = $template_name;
	}
	
	/**
	* 添加模板变量
	*
	*/
	public function addVar($var, $value)
	{
		$this->mTemplateDatas[$var] = $value;
	}

	/**
	* 清除模板变量数据
	*
	*/
	public function clearVar()
	{
		$this->mTemplateDatas = array();
		$this->mHeaderCode = array();
		$this->mFooterCode = array();
	}

	/**
	* 初始化提交数据
	*
	*/
	public function initRequestData()
	{
		$this->mRequestData = array();
	}

	/**
	* 输出模板
	*
	*/
	public function outTemplate($hg_template_name, $callback = '')
	{
		if (!$this->mSoftVar)
		{
			echo 'Please specify SOFTVAR in configuare file.';
			return;
		}
		$fetch_template = false;
		if (!CACHE_TEMPLATE)
		{
			$fetch_template = true;
		}
		else
		{
			if (!file_exists(TEMPLATE_DIR . $this->mSoftVar . '/' . $hg_template_name . '.php'))
			{
				$fetch_template = true;
			}
		}
		if ($fetch_template)
		{
			//fetch template and cahed
			$this->localCss($hg_template_name);
			$this->fetchTemplates($hg_template_name);
		}
		foreach ($this->mTemplateDatas AS $k => $v)
		{
			$$k = $v;
		}
		$this->mHeaderCode = implode("\n", $this->mHeaderCode);
		$this->mFooterCode = implode("\n", $this->mFooterCode);
		$RESOURCE_URL = RESOURCE_URL;
		if (!$_REQUEST['ajax'])
		{
			global  $gGlobalConfig;
			if ($gGlobalConfig['rewrite'])
			{
				ob_end_clean();
				ob_start();
				@include (TEMPLATE_DIR . $this->mSoftVar . '/' . $hg_template_name . '.php');
				$html = ob_get_contents();
				ob_end_clean();
				include(ROOT_PATH . 'lib/func/functions_rewrite.php');
				$html = hg_rewrite($html);
				echo $html;
			}
			else
			{
				@include (TEMPLATE_DIR . $this->mSoftVar . '/' . $hg_template_name . '.php');
			}
			$this->mTemplate = $hg_template_name;
			exit;
		}
		else
		{
			ob_end_clean();
			ob_start();
			@include (TEMPLATE_DIR .  $this->mSoftVar . '/' . $hg_template_name . '.php');
			$html = ob_get_contents();
			global  $gGlobalConfig;
			if ($gGlobalConfig['rewrite'])
			{
				include(ROOT_PATH . 'lib/func/functions_rewrite.php');
				$html = hg_rewrite($html);
			}
			ob_end_clean();
			$html = str_replace(array("\r", "\n", "\t"), '', $html);
			$this->mTemplate = $hg_template_name;
			if ($callback)
			{
				$html = addslashes($html);
				$callback = explode(',', $callback);
				$cfunc = $callback[0];
				unset($callback[0]);
				if ($callback)
				{
					$jsstr = '';
					foreach ($callback AS $v)
					{
						$jsstr .= ",'{$v}'";
					}
					
					$callback = $cfunc . "('$html'$jsstr)";
					$html = '';
					$data = array(
						'html' => $html,
						'msg' => '',
						'callback' => $callback,
					);
					echo json_encode($data);
					exit;
				}
				else
				{
					$callback = $cfunc . "('$html'$jsstr)";
					$data = array(
						'html' => $html,
						'msg' => '',
						'callback' => $callback,
					);
					return json_encode($data);
				}
			}
			else
			{
				return $html;
			}
		}
	}

	public function showTemplateVars()
	{
		$template_name = $this->mTemplate;
		$this->initRequestData();
		$this->addRequestData('softvar', $this->mSoftVar);
		$this->addRequestData('group', $this->mTemplateGroup);
		$this->addRequestData('template', $template_name);
		$this->addRequestData('a', 'getvars');
		$ret = $this->post(TEMPLATE_API, $this->mRequestData);
		$ret = json_decode($ret, true);
		return $ret;
	}
	
	/**
	* 本地化css
	*
	*/
	private function localCss($template_name)
	{
		$this->initRequestData();
		$this->addRequestData('softvar', $this->mSoftVar);
		$this->addRequestData('group', $this->mTemplateGroup);
		$this->addRequestData('template', $template_name);
		$this->addRequestData('a', 'getcss');
	
		$ret = $this->post(TEMPLATE_API, $this->mRequestData);
		$ret = json_decode($ret, true);

		if (!hg_mkdir(CSS_FILE_DIR . '/'))
		{
			exit(CSS_FILE_DIR .  '/目录创建失败，请检查目录权限.');
		}
		if (substr(RESOURCE_URL, 0, 7) == 'http://')
		{
			$RESOURCE_URL = RESOURCE_URL;
		}
		else
		{
			$RESOURCE_URL = '../../' . RESOURCE_URL;
		}
		if (is_array($ret))
		{
			foreach ($ret AS $file => $content)
			{
				if ($file)
				{
					if(strpos($file, '/'))
					{
						$filename = strrchr($file, '/');
						$dir = str_replace($filename, '', $file);
						if (!hg_mkdir(CSS_FILE_DIR . $dir . '/'))
						{
							exit(CSS_FILE_DIR . $dir . '/目录创建失败，请检查目录权限.');
						}
					}
					$varpreg = "/{\\$[a-zA-Z0-9_\[\]\-\'\>]+}/";
					$content = preg_replace($varpreg,  $RESOURCE_URL, $content);
					hg_file_write(CSS_FILE_DIR . $file, $content);
				}
			}
		}
	}
	
	/**
	* 获取模板信息
	*
	*/
	private function fetchTemplates($template_name)
	{
		$this->initRequestData();
		$this->addRequestData('softvar', $this->mSoftVar);
		$this->addRequestData('group', $this->mTemplateGroup);
		$this->addRequestData('template', $template_name);
	
		$ret = $this->post(TEMPLATE_API, $this->mRequestData);
		if (hg_mkdir(TEMPLATE_DIR .  $this->mSoftVar . '/'))
		{
			hg_file_write(TEMPLATE_DIR .  $this->mSoftVar . '/' . $template_name . '.php', $ret);
		}
		else
		{
			exit(TEMPLATE_DIR .  $this->mSoftVar . '/目录创建失败，请检查目录权限.');
		}
	}

	private function addRequestData($name, $value)
	{
		$this->mRequestData[$name] = urlencode($value);
	}

	/**
	* 获取模板信息
	*
	*/
    private function post($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml; charset=utf-8"));
		//print_r( $post_data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if ($ret == null)
        {
        	$ret = '模板文件不存在或网络错误,连不上ui界面服务器';
        }

        return $ret;
    }
}
?>