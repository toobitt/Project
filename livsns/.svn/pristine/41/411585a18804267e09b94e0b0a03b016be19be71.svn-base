<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','interview_user_group');//模块标识
class interview_user_group extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
    public function index()
    {
    	
    }
	public function show()
	{

		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$orderby = ' ORDER BY order_id  ASC';
		$sql = 'SELECT * FROM '.DB_PREFIX.'user_group WHERE 1 '.$orderby.$limit;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$this->addItem($r);
		}
		$this->output();
	}


	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'user_group ';
		echo json_encode($this->db->query_first($sql));
	}

	
	function detail()
	{
		if (!$this->input['id'])
		{
			return ;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'user_group WHERE	id = '.urldecode($this->input['id']);
		$r = $this->db->query_first($sql);
		$this->addItem($r);
		$this->output();
	}

}

$ouput= new interview_user_group();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();

