<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: vod_update.php 5959 2012-02-20 06:46:51Z zhoujiafei $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."conf/config.php");
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(CUR_CONF_PATH . 'core/mailbox.dat.php');
define('MOD_UNIQUEID','public_mail_box');
class  Comment extends outerReadBase
{
	private $curl;
    public function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_message']['host'],$this->settings['App_message']['dir']);
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function detail()
	{
		
	}
	function show()
	{
		$set = $this->mod_set();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		
		$set = $set[0]['info'];
		$order = $set['display_order'];
		if ($order)
		{
			$descasc = 'DESC';
		}
		else
		{
			$descasc = 'ASC';
		}
	
		$orderby = ' ORDER BY mx.id ' . $descasc;
	
		$condition = $this->get_condition();
		$mes = new MailboxDat();
		$r = $mes->show($condition,$orderby,$limit);
		$res['mes'] = $r;
		$res['set'] = $set;
		$this->addItem($res);
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND mx.title LIKE "%' . trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND mx.id = ' . intval($this->input['id']);
		}
		if($this->input['type_s'])
		{
			$condition .= ' AND mx.type = ' . intval($this->input['type_s']);
		}
		if($this->input['tid_s'])
		{
			$condition .= ' AND mx.tid = ' . intval($this->input['tid_s']);
		}
		$condition .= ' AND m.state=1';
		return $condition;
	}
	function count()
	{
		$condition = $this->get_condition();
		$mes = new MailboxDat();
		$count = $mes->count($condition);
		//$this->addItem($count);
		//$this->output();
	}
	//类型配置
	function mailbox_type()
	{
		$mailbox_type = $this->settings['message_mailbox_type'];
		$this->addItem($mailbox_type);
		$this->output();
	
	}
	//查询类型及分类
	function mailbox_types()
	{
		//$mailbox_type = $this->settings['message_mailbox_type'];
		$sql = "SELECT * from ".DB_PREFIX."mailbox_type WHERE fid=". intval($this->input['fid']);
		$g = $this->db->query($sql);
		while($j = $this->db->fetch_array($g))
		{
			$return[] = $j;
			
		}
		$this->addItem($return);
		$this->output();
	}
	//对回复满意
	function mailbox_useful()
	{
		$ip = hg_getip();
		$sql = 'SELECT * from '.DB_PREFIX.'feedback WHERE ip="'.$ip.'" AND mid='. intval($this->input['id']);
		$q = $this->db->query($sql);
		
		while($r = $this->db->fetch_array($q))
		{
			$res[] = $r;
		}
		//var_dump($res);
		if(!$res)
		{
			$up_sql = 'UPDATE '.DB_PREFIX.'mailbox SET useful=useful+1 WHERE id='.intval($this->input['id']);
			if($this->db->query($up_sql))
			{
				$in_sql = 'INSERT INTO '.DB_PREFIX.'feedback SET ip="'.$ip.'" , type=1 , mid='.intval($this->input['id']);
				if($this->db->query($in_sql))
				{
					//return 1;
					echo 1;
				}
			}
			else
			{
				echo -2;
				//return 2;
			}
		}
		else
		{
			echo -3;
			//return 3;
		}	
	}
	//对回复不满意
	
	function mailbox_yawp()
	{
		$ip = hg_getip();
		$sql = 'SELECT * from '.DB_PREFIX.'feedback WHERE ip="'.$ip.'" AND mid='. intval($this->input['id']);
		$q = $this->db->query($sql);
		
		while($r = $this->db->fetch_array($q))
		{
			$res[] = $r;
		}
		if(!$res)
		{
			$up_sql = 'UPDATE '.DB_PREFIX.'mailbox SET yawp=yawp+1 WHERE id='.intval($this->input['id']);
			if($this->db->query($up_sql))
			{
				$in_sql = 'INSERT INTO '.DB_PREFIX.'feedback SET ip="'.$ip.'" , type=-1 , mid='.intval($this->input['id']);
				if($this->db->query($in_sql))
				{
					echo 1;
				}
			}
			else
			{
				echo -2;
			}
		}
		else
		{
			echo -3;
		}
	}
	//添加留言
	function add_message()
	{
		$set = $this->mod_set();
		$set = $set[0]['info'];
		$sum = $set['max_word'];
		$login = $set['is_login'];
		if($set['display']){
			$state = 0;
		}
		else
		{
			$state = 1;
		}
		if($login && !$this->input['username'])
		{
			$this->errorOutput("请先登录!");
		}

		//if(!$this->input['title'])
		//{
			//$this->errorOutput(NOTITLE);
		//}
		if(!$this->input['content'])
		{
			$this->errorOutput(NOCONTENT);
		}
		$len = strlen(urldecode($this->input['content']));
		if($len>$sum)
		{
			//$this->errorOutput("最多只能输入" . $sum . "字哦!");
			echo "最多只能输入" . $sum . "个字哦!";exit;
		}
		
		$data = array(
		'title'=>urldecode($this->input['title']),
		'author'=>urldecode($this->input['username']),
		'issue'=>urldecode($this->input['content']),
		'time'=>TIMENOW,
		'ip'=>hg_getip(),
		'fid'=>$this->input['type'],
		'tid'=>$this->input['tid'],
		'state'=>$state,
		);
		$mes = new MailboxDat();
		$res = $mes->add_message($data);
		//$res['set'] = $set;
		if($res)
		{
			$this->addItem($res);
		}
		else
		{
			$this->addItem('error');
		}
		$this->output();
	}
	 public function  mod_set()
    {
		 $_mid = $this->input['_mid'];
		 $_type = $this->input['_type'];
    	 $this->curl->setSubmitType('get');
		 $this->curl->initPostData();
		 $this->curl->addRequestData('a','show');
		 $this->curl->addRequestData('_mid',$_mid);
		 $this->curl->addRequestData('_type',$_type);
		 $ret = $this->curl->request('comment.ini.php');
		 return $ret;
		 //$this->addItem($ret);
		 //$this->output();
    }
}

$out = new Comment();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'mailbox_types';
}
else 
{
	$action = $_INPUT['a'];
}
$res = $out->$action();

?>
