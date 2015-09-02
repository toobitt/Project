<?php
require_once 'global.php';
require_once '../lib/opinion.class.php';
class opinionApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->opinion = new opinion();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		
	}
	public function get_condition()
	{
		$condition ='';
		if (!$this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		return $condition;
	}
	public function detail()
	{
		
	}
}

$ouput = new opinionApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();

?>


			