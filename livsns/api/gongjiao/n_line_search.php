<?php
require ('./global.php');
define('MOD_UNIQUEID','gongjiao');
define('SCRIPT_NAME', 'LineSearch');
class LineSearch extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){}	
	
	public function show()
	{
		$q = $this->input['k'] ? $this->input['k']  : $this->input['routeid'] ;
		if (!$q)
		{
			$return = array(
				'error_message' => '请输入要查询的线路名称',
			);
			echo json_encode($return);
			exit;
		}
		
		if (is_numeric($q))
		{
			//$q1 = $q . '夜';
			//$q .= '路';
		}
		if ($q1)
		{
			$cond = " OR line_name LIKE '$q1'";
		}
		$sql = "SELECT id,line_no,line_name FROM " . DB_PREFIX . "line WHERE line_name LIKE '$q%'$cond";
		
		//echo $sql;
		$q = $this->db->query($sql);
		$line = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['line_no'] = $row['line_no'];
			$line[] = array(
				'routeid' => $row['line_no'],
				'routename' => $row['line_name'],
			);
		}

		if (!$line)
		{
			$return = array(
				'error_message' => '没有该线路',
			);
			echo json_encode($return);
			exit;
		}
		echo json_encode($line);
		
	}
	
	public function get_condition()
	{
		$condition = '';
		return $condition ;
	}
	
	
	public function detail()
	{
	}
}
include(ROOT_PATH . 'excute.php');
?>