<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: shorturl.php 4557 2011-09-22 08:41:38Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR.'global.php');
class shorturlShowApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
		$this->db->close();
	}
	/*
	 * 查看具体的短URL信息
	 */
	function detail()
	{
		$this->input['id'] = urldecode($this->input['id']);

		if(!$this->input['id'])
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id in(' . $this->input['id'] .')';
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "urls"  . $condition;	
		$r = $this->db->query_first($sql);
		$this->setXmlNode('urls' , 'url');
		
		if(is_array($r) && $r)
		{
			$r['shorturl'] = SITE_URL.'/'.$r['code'];
			$this->addItem($r);
			$this->output();
		}
	}
	/**
	 * 获取短url记录
	 */
	function show()
	{
		$this->input['count'] = (isset($this->input['count']) && (int)$this->input['count']>0)
		?(int)$this->input['count'] 
		:10;
		$this->input['offset'] = (isset($this->input['offset']) && (int)$this->input['offset']>0) 
		?(int)$this->input['offset'] 
		:0;
		$orders = array('date_added', 'click_count');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $orders))
		{
			$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		else
		{
			$orderby = ' ORDER BY id DESC';
		}
		
		$sql = 'select * from '.DB_PREFIX.'urls';
		$sql .= ' where 1 '.$this->get_condition() . $orderby;
		$sql .= ' limit '.$this->input['offset'].','.$this->input['count'];
		//exit($sql);
		$url_all_data = $this->db->query($sql);
		$topic_ids = array();
		while($result = $this->db->fetch_array($url_all_data))
		{
			$result['shorturl'] = SITE_URL.'/'.$result['code'];
			$this->addItem($result);
		}
		//$this->get_topic_member($topic_ids);
		$this->output();
	}
	function count()
	{
		$sql = 'select count(*) as total from '.DB_PREFIX.'urls where 1 '.$this->get_condition();
		$topic_total_item = $this->db->query_first($sql);
		echo json_encode($topic_total_item);		
	}
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$this->input['start_time'] = strtotime(urldecode($this->input['start_time']));
		    $a = $this->input['start_time'];
			if(isset($this->input['end_time']) && !empty($this->input['end_time']))
			{
				$this->input['end_time'] = strtotime(urldecode($this->input['end_time']));
				$condition .= 'and unix_timestamp(date_added) between '.$this->input['start_time'].' and '.$this->input['end_time'];
			}
			else
			{
				$condition .= 'and unix_timestamp(date_added) >= '.$this->input['start_time'];
			}
		}
		if(!$this->input['start_time'] && isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$this->input['end_time'] = strtotime(urldecode($this->input['end_time']));
			$condition .= 'and unix_timestamp(date_added) <= '.$this->input['end_time'];
		}
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' and url like \'%'.urldecode($this->input['k']).'%\'';
		}
		
		/*if(isset($this->input['state']))
		{
			if(-1!=$this->input['state'])
			{
				$condition .= ' and status = '.(int)urldecode($this->input['state']);
			}
		}*/
		return $condition;	
	}
}
	$shorturlShowApi = new shorturlShowApi();
	if(!method_exists($shorturlShowApi, $_INPUT['a']))
	{
		$_INPUT['a'] = 'show';
	}
	$shorturlShowApi->$_INPUT['a']();
?>