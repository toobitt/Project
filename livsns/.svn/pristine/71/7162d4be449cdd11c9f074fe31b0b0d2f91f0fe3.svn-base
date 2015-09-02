<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 6048 2012-03-08 06:27:09Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','channel_weibo');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
require(ROOT_PATH . 'lib/class/curl.class.php');
class channelWeibo extends outerReadBase
{
	private $curl;
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['weibo_api']['host'], $this->settings['weibo_api']['dir'], $this->settings['weibo_api']['token']);	
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput('未传入频道ID');
		}
		//$cond = ' AND status=1';
		$sql = "select * from " . DB_PREFIX . "channel where id=" . $channel_id . $cond;
		$channel_info = $this->db->query_first($sql);
		if(!$channel_info)
		{
			$this->errorOutput('指定频道不存在或被关闭');
		}
		$source = array(
			'0' => '网站',	
			'1' => 'Iphone客户端',	
			'2' => 'android客户端',	
		);

		$sql = "SELECT * FROM " . DB_PREFIX . "topic WHERE " . TIMENOW . " > start_time AND " . TIMENOW . " < end_time AND channel_id=" . $channel_id;
		$f = $this->db->query_first($sql);
		if(!empty($f))
		{
			$channel_topic = $f['name'];
		}
		else
		{
			$sql = "SELECT id,theme,start_time FROM ".DB_PREFIX.'program  WHERE channel_id ='.$channel_id.' ';
			$sql .= ' AND start_time + toff >= ' . TIMENOW . ' ORDER BY start_time ASC LIMIT 1';
			$program = $this->db->query_first($sql);
			$channel_topic = $program['theme'] ? $program['theme'] : $channel_info['name'];
		}

		$comments = array();
		$return = array();
		$return['show_topic'] = '关于#' . $channel_topic . '#的讨论';
		$return['channel_topic'] = $channel_topic;
		$count = intval($this->input['count']);
		$count = $count ? $count : 20;
		$page = intval($this->input['offset']) / $count;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('q', $channel_topic);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('order_type', 0);
		$this->curl->addRequestData('newest_id', 0);
		$this->curl->addRequestData('count', $count);
		$comments = $this->curl->request('search.php');	
		$deal_comments = array();
		unset($comments[0]);	
		$len = strlen('#' . $channel_topic . '#');
		if ($comments)
		{
			foreach ($comments AS $value)
			{
				if (substr(trim($value['text']), ($len - 1), 1) == '#')
				{
					$value['text'] = substr(trim($value['text']), $len);
				}
				$text = hg_verify($value['text']);
				$text_show = '：'.($value['text'] ? $value['text'] : $this->lang['forward_null']);
				if($value['reply_status_id'])
				{
					$forward_show = '//@'.$value['user']['username'].' '.$text_show;
					$title = $this->lang['forward_one'].$value['retweeted_status']['text'];
				}
				else
				{
					$forward_show = '';
					$title = $this->lang['forward_one'].$value['text'];
				}
				if ($value['source'])
				{
					$value['source_text'] = '通过' . $source[$value['source']];
				}
				else
				{
					$value['source_text'] = '';
				}
				$comment = array(
					'id' => $value['id'],	
					'content' => $title,	
					'source' => $value['source'],	
					'author' => $value['user']['username'],	
					'pubdate' =>  date('m月d日 H:i', $value['create_at']),	
					'source_text' => '通过' . $value['source'],	
				);
				$deal_comments[] = $comment;
			}
		}
		$return['comments'] = $deal_comments;
		$this->addItem($return);
		$this->output();
	}
	public function count()
	{
		
	}
	public function detail()
	{
		
	}
}

$out = new channelWeibo();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>