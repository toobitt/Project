<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: card_css.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/cardcss.class.php';
define('MOD_UNIQUEID', 'card_css'); //模块标识

class card_cssApi extends adminReadBase
{
	private $cardcss;
	
	public function __construct()
	{
		parent::__construct();
		$this->cardcss = new cardcssClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->cardcss);
	}
	
	public function index()
	{
		
	}
	
	/**
	 * 信息列表
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$card_css_info = array();
		$card_css_info = $this->cardcss->show($offset, $count);
		$this->setXmlNode('card_css_info', 'card_css');
		if ($card_css_info)
		{
			foreach ($card_css_info as $value)
			{
				$this->addItem($value);
			}
		}
		$this->output();
	}
	
	/**
	 * 信息数据总数
	 */
	public function count()
	{
	}

	/**
	**	信息编辑
	**/
	public function detail()
	{
		$id = trim($this->input['id']);
		if(!$id){
			$this->errorOutput(OBJECT_NULL);
		}
		
		$info = array();
		$info = $this->cardcss->detail($id);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 查询条件
	 * @param Array $data
	 */
	private function get_condition()
	{
	}
}

$out = new card_cssApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();

?>