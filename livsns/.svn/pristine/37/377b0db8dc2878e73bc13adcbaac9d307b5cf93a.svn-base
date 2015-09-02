<?php
require('global.php');
define('MOD_UNIQUEID','cell');
class cellApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function index(){}
	public function show(){}
	function detail(){}
	public function count(){}
	//data_source表
	public function update1()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."data_source WHERE 1 ORDER BY id ASC";
		$q = $this->db->query($sql);
		$ret = array();
		
		while ($row = $this->db->fetch_array($q)) {
			$row['argument'] = unserialize($row['argument']);
			if (is_array($row['argument']['type']) && count($row['argument']['type']) > 0) {
				foreach ($row['argument']['type'] as $k => $v)
				{
					if ($v == 'select')
					{
						echo $row['argument']['other_value'][$k] . '<br/>';
						$row['argument']['other_value'][$k] = html_entity_decode($row['argument']['other_value'][$k]);
						echo $row['argument']['other_value'][$k] . '<br/>';
						$row['argument']['other_value'][$k] = str_replace(' ', '#&33',$row['argument']['other_value'][$k]);
						echo $row['argument']['other_value'][$k] . '<br/>';
					}
				}
			}
			$sql = "UPDATE ".DB_PREFIX."data_source SET argument = '" .serialize($row['argument']). "' WHERE id = " . $row['id'];
			$this->db->query($sql);
			$ret[] = $row;	
		}
		echo "<pre>";
		print_r($ret);
		exit;
	}
	
	//cell_mode_variable
	function update2()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."cell_mode_variable WHERE 1 ORDER BY id DESC";
		$q = $this->db->query($sql);
		$ret = array();		
		
		while ($row = $this->db->fetch_array($q)) {
			if ($row['other_value']) {
				echo $row['other_value'];
				$row['other_value']  = html_entity_decode($row['other_value']);
				$row['other_value'] = str_replace(' ', '#&33', $row['other_value']);
			}
			$ret[] = $row;
			$sql = "UPDATE ".DB_PREFIX."cell_mode_variable SET other_value = '".$row['other_value']."' WHERE id = " . $row['id'];
			$this->db->query($sql);
		}
		echo "<pre>";
		print_r($ret);
		exit;
	}
	
	//cell_mode表
	function update3()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."cell_mode WHERE 1 ORDER BY id ASC";
		$q = $this->db->query($sql);
		$ret = array();
		
		while ($row = $this->db->fetch_array($q)) {
			$row['argument'] = unserialize($row['argument']);
			if (is_array($row['argument']['type']) && count($row['argument']['type']) > 0) {
				foreach ($row['argument']['type'] as $k => $v)
				{
					if ($v == 'select')
					{
						echo $row['argument']['other_value'][$k] . '<br/>';
						$row['argument']['other_value'][$k] = html_entity_decode($row['argument']['other_value'][$k]);
						echo $row['argument']['other_value'][$k] . '<br/>';
						$row['argument']['other_value'][$k] = str_replace(' ', '#&33',$row['argument']['other_value'][$k]);
						echo $row['argument']['other_value'][$k] . '<br/>';
					}
				}
			}
			$sql = "UPDATE ".DB_PREFIX."cell_mode SET argument = '" .serialize($row['argument']). "' WHERE id = " . $row['id'];
			$this->db->query($sql);
			$ret[] = $row;	
		}
		echo "<pre>";
		print_r($ret);
		exit;		
	}
	
	//cell_mode_code表
	function update4()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."cell_mode_code WHERE 1 ORDER BY id ASC";
		$q = $this->db->query($sql);
		$ret = array();
		
		while ($row = $this->db->fetch_array($q)) {
			$row['para'] = unserialize($row['para']);
			$prefix = $row['type'];
			if (is_array($row['para']) && count($row['para']) > 0) {
				foreach ($row['para'] as $k => $v)
				{
					if ($v[$prefix . '_type'] == 'select')
					{
						echo $v[$prefix . '_other_value'] . '<br/>';
						$v[$prefix . '_other_value'] = html_entity_decode($v[$prefix . '_other_value']);
						echo $v[$prefix . '_other_value'] . '<br/>';
						$row['para'][$k][$prefix . '_other_value'] = str_replace(' ', '#&33', $v[$prefix . '_other_value']);
						echo $row['para'][$k][$prefix . '_other_value'] . '<br/>';
					}
				}
			}
			$sql = "UPDATE ".DB_PREFIX."cell_mode_code SET para = '" .serialize($row['para']). "' WHERE id = " . $row['id'];
			$this->db->query($sql);
			$ret[] = $row;	
		}
		echo "<pre>";
		print_r($ret);
		exit;		
	}	
}
$out = new cellApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
