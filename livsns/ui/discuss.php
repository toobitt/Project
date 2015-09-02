<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: k.php 2398 2011-03-01 06:37:14Z wang $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');
class discuss extends uiBaseFrm
{	
	private $status;
	function __construct()
	{		
		parent::__construct();
		$this->load_lang('k');

		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->status = new status();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$keywords = urldecode(trim($this->input['q']));
		if ($keywords)
		{
			$count = 20;
			$page = intval($this->input['pp']) / $count;

			$order_type = $this->input['order'] ? 1 : 0;
			$statusline = $this->status->search($keywords,$page,$count , $order_type );

			if(is_array($statusline))
			{
				$data['totalpages'] = $statusline[0]['total'];
				$result_count = $statusline[0]['total'];
				unset($statusline[0]);
				
				$newest_id = $statusline[1]['id'];	
				krsort($statusline);		
			}
			else
			{
				$statusline = array();
				$newest_id = 0;
			}
		}
		
		$width = $this->input['width']? "width:".$this->input['width']."px;":"";
		$height = $this->input['height']? "height:".$this->input['height']."px;":"";
		$fwidth = $this->input['fwidth']? $this->input['fwidth']."px":"350px";

		$this->page_title = $this->lang['pageTitle'];
		$order = $this->input['order']?$this->input['order']:0;
		hg_add_head_element('js-c',"
			var ORDER = '" . $order . "';");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'discuss.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'rotate.js');
		//hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'timing_update.js');  //定时更新页面信息数据

		
		
		if($this->input['type'])
		{
			$this->tpl->addVar('keywords', $keywords);
			$this->tpl->addVar('statusline', $statusline);
			$this->tpl->addVar('newest_id', $newest_id);
			$this->tpl->addVar('width', $width);
			$this->tpl->addVar('height', $height);
			$this->tpl->addVar('fwidth', $fwidth);
			//include hg_load_template('comment');
			$this->tpl->addHeaderCode(hg_add_head_element('echo'));
			$this->tpl->setTemplateTitle($this->page_title);
			$this->tpl->outTemplate('comment');

		}
		else 
		{
			//include hg_load_template('discuss');
			hg_add_head_element('css', MAIN_URL . 'res/zhibo/images/zhibo.css');
			hg_add_head_element('css', MAIN_URL . 'res/zhibo/images/tab.css');

			$this->tpl->addVar('keywords', $keywords);
			$this->tpl->addVar('statusline', $statusline);
			$this->tpl->addVar('newest_id', $newest_id);
			$this->tpl->addVar('width', $width);
			$this->tpl->addVar('height', $height);
			$this->tpl->addVar('fwidth', $fwidth);
			$this->tpl->addHeaderCode(hg_add_head_element('echo'));
			$this->tpl->setTemplateTitle($this->page_title);
			$this->tpl->outTemplate('discuss');
		}		
	}
	
	
	/**
	 * 更新聊天信息
	 */
	public function update()
	{
		$keywords = urldecode(trim($this->input['q']));
		
		$newest_id = intval($this->input['newest_id']);
		
		if ($keywords)
		{
			$order_type = $this->input['order'] ? 1 : 0;		
			$statusline = $this->status->search($keywords,$page,$count , $order_type , $newest_id);
			
			unset($statusline[0]);
			
			if($statusline)
			{							
				$newest_id = $statusline[1]['id'];
				krsort($statusline);

				$this->tpl->addVar('keywords', $keywords);
				$this->tpl->addVar('statusline', $statusline);
				$this->tpl->outTemplate('speak');
				
				$date = array('chat_content' => $speak_message , 'last_id' => $newest_id);
				$r = json_encode($date);
				
				echo $r;
			}
			else
			{
				echo 1;	
			} 			
		}		
	}	
}
$out = new discuss();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();



?>