<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once CUR_CONF_PATH.'lib/constellation.class.php';
require_once CUR_CONF_PATH.'lib/astrojson.php';
class astro extends appCommonFrm
{
	//
	protected $api_uri;
	protected $dataType;
	function __construct()
	{
		$this->api_uri = APIURI;
		$this->dataType = 'json';
		parent::__construct();
		$this->constellation = new constellation();
		$this->astrojson = new astrojson();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($fun,$astroid)//星座访问触发函数
	{
		$field = $this->settings['astro'];

		$data[$field[$astroid]] = $this->request($astroid,$fun);



		$data[$field[$astroid]]=$this->astrojson->arraytojson($data[$field[$astroid]],$fun,$astroid);
		$fun = trim($fun);
		switch ($fun)
		{
			case day:
				$starttime = $data[$field[$astroid]][astrotime];
				$endtime = $data[$field[$astroid]][astrotime];
				unset($data[$field[$astroid]][astrotime]);
				break;
			case tomorrow:
				$starttime = $data[$field[$astroid]][astrotime];
				$endtime = $data[$field[$astroid]][astrotime];
				unset($data[$field[$astroid]][astrotime]);
				break;
			case week:
				$starttime = $data[$field[$astroid]][starttime];
				$endtime = $data[$field[$astroid]][endtime];
				unset($data[$field[$astroid]][starttime]);
				unset($data[$field[$astroid]][endtime]);
				break;
			case month:
				$starttime = $data[$field[$astroid]][starttime];
				$endtime = $data[$field[$astroid]][endtime];
				unset($data[$field[$astroid]][starttime]);
				unset($data[$field[$astroid]][endtime]);
				break;
			case year:
				$starttime = $data[$field[$astroid]][starttime];
				$endtime = $data[$field[$astroid]][endtime];
				unset($data[$field[$astroid]][starttime]);
				unset($data[$field[$astroid]][endtime]);
				break;
			case love:
				$starttime = $data[$field[$astroid]][starttime];
				$endtime = $data[$field[$astroid]][endtime];
				unset($data[$field[$astroid]][starttime]);
				unset($data[$field[$astroid]][endtime]);
				break;
			default:
				$starttime = TIMENOW;
				$endtime = TIMENOW;
				break;
		}
     $data['starttime']=$starttime;
     $data['endtime']=$endtime;
	 return $data;

	}

	public function astroinfoselect()//星座前台输出信息
	{
		$sql = 'SELECT astroid,astrocn,astroen,astrointroduction,astroimg,astrostart,astroend,astroflag FROM '.DB_PREFIX.'astro_app_info WHERE 1 ';
		$q = $this->db->query($sql);

		while($r = $this->db->fetch_array($q))
		{
			$r['astroimg'] = unserialize($r['astroimg']);
			$r['astroimg'] = hg_fetchimgurl($r['astroimg']);
			$r['astrostart']=date('m月d日',$r['astrostart']);
			$r['astroend']=date('m月d日',$r['astroend']);
			$astroinfo[$r['astroid']] = array('astrocn'=>$r['astrocn'],'astrointroduction'=>$r['astrointroduction'],'astroimg'=>$r['astroimg'],'astrostart'=>$r['astrostart'],'astroend'=>$r['astroend'],'astroflag'=>$r['astroflag']);

		}
		return $astroinfo;
			
	}

	public function astroinfoadminselect()//星座后台输出信息
	{
		$sql = 'SELECT id,astrocn,astroen,astrointroduction,astroimg,astrostart,astroend FROM '.DB_PREFIX.'astro_app_info WHERE 1 ';
		$query = $this->db->query($sql);
		$data = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['astroimg'] = unserialize($row['astroimg']);
			$row['logo'] = hg_fetchimgurl($row['astroimg']);
			$row['astrostart'] = date("m月d日", $row['astrostart']);
			$row['astroend'] = date("m月d日", $row['astroend']);
			$data[$row['id']] = $row;
		}

		return $data;
			
	}

	//图标加亮标记重置函数
	public function astroflag($astroid){
		$flag=array('0'=>'0','1'=>'0','2'=>'0','3'=>'0','4'=>'0','5'=>'0','6'=>'0','7'=>'0','8'=>'0','9'=>'0','10'=>'0','11'=>'0');

		foreach ($flag as $key=>$val)
		{
			if($key == $astroid)
			$flag[$key]='1';
		}
		$ids = implode(',', array_keys($flag));
		$sql = "UPDATE ".DB_PREFIX."astro_app_info SET astroflag = CASE astroid ";
		foreach ($flag as $id => $ordinal) {
			$sql .= sprintf("WHEN %d THEN %d ", $id, $ordinal);  // 拼接SQL语句
		}
		$sql .= "END WHERE astroid IN ($ids)";
		$this->db->query($sql);
	}

	/**
	 * URL参数说明
	 * ===============================================
	 * @param   string   fun         函数类型(day,totomorrow,week,month,year,love)
	 * @param   integer  id          星座编号(必填)
	 * @param   string   format      数据格式(json,jsonp,xml)
	 * @param   string   callback    只有当数据格式为jsonp时,callback参数才有效
	 * =========================================
	 */
	protected function getRequestParameter($aid,$fun)
	{
		$data = array();
		$data['fun'] = $fun;
		$data['id']=$aid;
		$data['format'] = $this->input['format'] ? $this->input['format'] : 'json';
		if(!in_array($data['fun'], explode(',', 'day,tomorrow,week,month,year,love')))
		{
			$data['fun'] = 'day';
		}
		if($data['id'] < 0 || $data['id'] > 11)
		{
			$this->errorOutput('Id is error');
		}
		if(!in_array($data['format'], array('xml', 'json')))
		{
			$this->errorOutput('Datatype is error');
		}
		$para = '';
		foreach($data as $pa=>$val)
		{
			$para .= $pa . '=' . $val . '&';
		}
		return trim($para, '&');

	}
	public function request($astroid,$fun)
	{   $aid=$astroid;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
	$this->api_uri . '?' . $this->getRequestParameter($aid,$fun);
	curl_setopt($ch, CURLOPT_URL, $this->api_uri . '?' . $this->getRequestParameter($aid,$fun));
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);
	if($this->dataType == 'json')
	{
		$decoded = json_decode($data, true);
		if(!is_array($decoded) || empty($decoded))
		{
			$this->errorOutPut('Error : ' . $data);
		}
	}
	return $decoded;
	}


}
