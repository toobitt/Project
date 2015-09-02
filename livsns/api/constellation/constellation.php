<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/constellation.class.php';
require_once CUR_CONF_PATH.'lib/astro.class.php';
define('SCRIPT_NAME', 'constellationApi');
define('MOD_UNIQUEID','constellation');//模块标识
class constellationApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();

		$this->constellation = new constellation();
		$this->astro = new astro();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function count()
	{

	}
	public function show()
	{
		//$id = !isset($this->input['id']) ? intval($this->constellation->date2astro()) : intval($this->input['id']);
		$mobileastroid_to_phpastroid = $this->settings['astroid'];
		$id	=	$this->input['id'];
		$astroid	=	!isset($this->input['id']) ? intval($this->constellation->date2astro()) : intval($mobileastroid_to_phpastroid[$id]);
		$this->astro->astroflag($astroid);
		if($astroid < 0 || $astroid > 11)
		{
			$this->errorOutput(INTVALID_PARAMETER);
		}
		$fun = trim($this->input['fun']) ? trim($this->input['fun']) : 'day';
		$funs=array('day','tomorrow','week','month','year','love');
		//$table = DB_PREFIX . 'app' . $fun;
		if(!in_array($fun, $funs))
		{
			$this->errorOutput(INTVALID_PARAMETER);
		}
		$field = $this->settings['astro'];
		$orderby = ' ORDER BY order_id DESC,id  DESC';

		$condition = ' AND astroen='.'\''.$field[$astroid].'\'';
		$condition .= $this->get_condition($fun);

		$data = $this->constellation->show($condition,$fun,$field[$astroid],$orderby);
		if (!empty($data))
		{
			$this->addItem($data);
		}
		else
		{
			$data=$this->astro->show($fun,$astroid);
			$starttime=$data['starttime'];
			$endtime=$data['endtime'];
			unset($data['starttime']);
			unset($data['endtime']);
			$insertid=$this->constellation->insertastro($fun,$field[$astroid],$data[$field[$astroid]],$starttime,$endtime);
			$this->constellation->updatetime_orerid($insertid,$fun);
			$sql ='UPDATE '.DB_PREFIX.'astro_app_fortuneinfo SET fortuneinfostart = '.strtotime(trim($starttime)).',fortuneinfoend ='.strtotime(trim($endtime)).' WHERE 1 AND astrofun = '."'$fun'";
			$this->db->query($sql);

			$this->addItem($data[$field[$astroid]]);
		}
		$this->output();
	}

	function detail()
	{

	}
	public function get_condition($fun)
	{
		if($fun)
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch($fun)
			{
				case day://今天的数据
					$condition = " AND  starttime = '".$today."' AND endtime = '".$today."'";
					break;
				case tomorrow://明日运势

					$condition = " AND  starttime = '".$tomorrow."' AND endtime = '".$tomorrow."'";
					break;
				case week://一周运势

					$condition = " AND  starttime < '".$today."' AND endtime > '".$today."'";
					break;
				case month://本月运势

					$condition = " AND  starttime < '".$today."' AND endtime > '".$today."'";
					break;
				case year://年度运势

					$condition = " AND  starttime < '".$today."' AND endtime > '".$today."'";
					break;
				case love://爱情运势

					$condition = " AND  starttime < '".$today."' AND endtime > '".$today."'";
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