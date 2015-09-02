<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: banword.php 39453 2014-08-16 02:24:18Z hanwenbin $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/banword.class.php';
define('MOD_UNIQUEID', 'banword'); //模块标识

class banwordApi extends xsFrm
{
	private $banword;
	
	public function __construct()
	{
		parent::__construct();
		$this->banword = new banwordClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->banword);
	}
	
	/**
	 * 检测是否有屏蔽字操作
	 */
	public function exists()
	{
		$content = trim(urldecode($this->input['banword']));
		if (empty($content)) $this->errorOutput(OBJECT_NULL);
		$data = $this->banword($content);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 替换屏蔽字
	 */
	public function replace()
	{
		$content = trim(urldecode($this->input['banword']));
		$symbol = trim(urldecode($this->input['symbol']));
		if (empty($content))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$data = $this->banword($content);
		if ($data)
		{
			$replace = array();
			$find = array();
			foreach ($data as $v)
			{
				if (!empty($symbol) && $symbol != '*')
				{
					$replace[] = $symbol;
				}else {
					if (!empty($v['banwd']))
					{
						$replace[] = $v['banwd'];
					}else {
						$replace[] = str_repeat('*', mb_strlen($v['banname'], 'utf-8'));
					}
				}
				$find[] = $v['banname'];
			}
			$content = str_replace($find, $replace, $content);
		}
		$this->addItem($content);
		$this->output();
	}
	
	/**
	 * 获取处理数据中存在的屏蔽字
	 * @param String $content
	 */
	private function banword($content)
	{
		$conResult = $this->xs_getResult($content);
		
		if (is_array($conResult) && !empty($conResult))
		{
			$where = array();
			foreach ($conResult as $v)
			{
				$where[] = 'banname = "' . $v['word'] . '"';
			}
			$where = implode(' OR ', $where);
			$where = ' AND (' . $where . ')';
			return $this->banword->show(0, -1, $where);
		}
	}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
}
$out = new banwordApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();
?>