<?php
require('./global.php');
define('SCRIPT_NAME', 'constellationAdmin');
define('MOD_UNIQUEID', 'constellation');
require_once CUR_CONF_PATH.'lib/constellation.class.php';
require_once CUR_CONF_PATH.'lib/astro.class.php';
class constellationAdmin extends adminReadBase
{

	function __construct()
	{
		parent::__construct();

		$this->constellation = new constellation();
		$this->astro = new astro();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{

		$data = $this->astro->astroinfoadminselect();
		foreach($data as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}

	function date2astro($data = '')
	{
		if(!$data)
		{
			$data = time();
		}
		$month 	= date('n');
		$day 	= date('j');
		// 检查参数有效性
		if ($month < 1 || $month > 12 || $day < 1 || $day > 31)
		{
			return (false);
		}
		// 星座名称以及开始日期
		$signs = array(
		array( "20" => "aquarius"),
		array( "19" => "pisces"),
		array( "21" => "aries"),
		array( "20" => "taurus"),
		array( "21" => "gemini"),
		array( "22" => "cancer"),
		array( "23" => "leo"),
		array( "23" => "virgo"),
		array( "23" => "libra"),
		array( "24" => "scorpio"),
		array( "22" => "sagittariu"),
		array( "22" => "capricorn")
		);
		list($sign_start, $sign_name) = each($signs[(int)$month-1]);
		if ($day < $sign_start)
		list($sign_start, $sign_name) = each($signs[($month -2 < 0) ? $month = 11: $month -= 2]);
		if(!$sign_name)
		{
			return -1;
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'astro_app_info where astroen = "'.$sign_name.'"';
		$result = $this->db->query_first($sql);
		return $result['astroid'];

	}
	function detail()
	{
		$astroid = intval($this->input['id']-1);
		$funs=array('day','tomorrow','week','month','year','love');
		$field = $this->settings['astro'];
		$astroen=$field[$astroid];
		$i=0;
		foreach ($funs as $fun)
		{
			$condition = $this->get_condition($fun,$astroen);
			$data[$fun]=$this->constellation->showadmin($condition,$fun,$astroen,$orderby);
			$astrofun=$this->constellation->fortuneinfo($fun);
			if (!empty($data[$fun]))
			{
				$data[$fun]['astrojson'] = json_decode($data[$fun]['astrojson'],true);
				$data[$fun]['astrofuncn']=$astrofun['astrofuncn'];
				$data[$fun]['astrofunimg']=$astrofun['astrofunimg'];
				$data[$fun]['fortuneinfostart']=$astrofun['fortuneinfostart'];
				$data[$fun]['fortuneinfoend']=$astrofun['fortuneinfoend'];
				$data[$fun]['fun'] = $fun;
			}
			else
			{
				$data[$fun]=$this->astro->show($fun,$astroid);//如果说数据库无数据，则curl请求查询。
				$starttime=$data[$fun]['starttime'];
				$endtime=$data[$fun]['endtime'];
				unset($data[$fun]['starttime']);
				unset($data[$fun]['endtime']);
				$insertid=$this->constellation->insertastro($fun,$field[$astroid],$data[$fun][$field[$astroid]],$starttime,$endtime);
				$this->constellation->updatetime_orerid($insertid,$fun);
				$sql ='UPDATE '.DB_PREFIX.'astro_app_fortuneinfo SET fortuneinfostart = '.strtotime(trim($starttime)).',fortuneinfoend ='.strtotime(trim($endtime)).' WHERE 1 AND astrofun = '."'$fun'";
				$this->db->query($sql);
				$data[$fun]['id']="$insertid";//数组格式转换
				$data[$fun]['astrojson']=$data[$fun][$field[$astroid]];
				unset($data[$fun][$field[$astroid]]);
				$data[$fun]['astrofuncn']=$astrofun['astrofuncn'];
				$data[$fun]['astrofunimg']=$astrofun['astrofunimg'];
				$data[$fun]['fortuneinfostart']=$astrofun['fortuneinfostart'];
				$data[$fun]['fortuneinfoend']=$astrofun['fortuneinfoend'];
				$data[$fun]['fun'] = $fun;

			}

		}
		$data['astroid'] = intval($astroid+1);
		//查询星座名称
		$sql	=	'SELECT astrocn FROM '.DB_PREFIX.'astro_app_info WHERE id = '.($data['astroid']) ;

		$astrocn	=	$this->db->query_first($sql);
		$data['name'] = $astrocn['astrocn'];

		$this->addItem($data);

		$this->output();


	}
	function count()
	{

	}
	function  index()
	{

	}
	public function get_condition($fun,$astroen)
	{

		$condition = ' AND astroen='.'\''.$astroen.'\'';

		if($fun)
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch($fun)
			{
				case day://今天的数据
					$condition .= " AND  starttime = '".$today."' AND endtime = '".$today."'";
					break;
				case tomorrow://明日运势

					$condition .= " AND  starttime = '".$tomorrow."' AND endtime = '".$tomorrow."'";
					break;
				case week://一周运势

					$condition .= " AND  starttime < '".$today."' AND endtime > '".$today."'";
					break;
				case month://本月运势

					$condition .= " AND  starttime < '".$today."' AND endtime > '".$today."'";
					break;
				case year://年度运势

					$condition .= " AND  starttime < '".$today."' AND endtime > '".$today."'";
					break;
				case love://爱情运势

					$condition .= " AND  starttime < '".$today."' AND endtime > '".$today."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
}
include ROOT_PATH . 'excute.php';
?>