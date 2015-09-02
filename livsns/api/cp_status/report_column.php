<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: report_column.php 4078 2011-06-15 07:49:14Z zhuld $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR.'global.php');
define('MOD_UNIQUEID','mblog_report_m');
class reportColumn extends outerReadBase
{
	private $reportType;
	function __construct()
	{
		parent::__construct();
		global $gReportType;
		$this->reportType = $gReportType;
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		if($this->reportType)
		{
			$addItem = array();
			$this->setXmlNode('columns' , 'column');
			$this->input['_id'] = urldecode($this->input['_id']);
			if($this->input['_id'])
			{
				$temp = explode(',' , $this->input['_id']);
				foreach($this->reportType as $k=>$v)
				{
					if(in_array($k, $temp))
					{
						$addItem['id'] = $k;
						$addItem['name'] = $v;
						$addItem['fid'] = 0;
						$addItem['bref'] = '';
						$addItem['depath'] = 0;
						$addItem['parents'] = '';
						$addItem['childs'] = '';
						$addItem['is_last'] = 1;
						$this->addItem($addItem);
					}
				}
			}
			else
			{
				foreach($this->reportType as $k=>$v)
				{
					$addItem['id'] = $k;
					$addItem['name'] = $v;
					$addItem['fid'] = 0;
					$addItem['bref'] = '';
					$addItem['depath'] = 0;
					$addItem['parents'] = '';
					$addItem['childs'] = '';
					$addItem['is_last'] = 1;
					$this->addItem($addItem);
				}
			}
		}
		else
		{
			$this->addItem('error');
		}
		//hg_pre($this->reportType);
		$this->output();
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
		if(!empty($this->reportType) && is_array($this->reportType))
		{
			$r = array();
			$r['total'] = count($this->reportType);
			echo json_encode($r);
		}
	}
}
$reportColumn = new reportColumn();
if(!method_exists($reportColumn, $_INPUT['a']))
{
	$_INPUT['a'] = 'show';
}
$reportColumn->$_INPUT['a']();
?>