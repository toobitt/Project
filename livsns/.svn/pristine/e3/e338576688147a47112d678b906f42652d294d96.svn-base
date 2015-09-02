<?php
require('./global.php');
define ('MOD_UNIQUEID', 'churu');
class churuApi extends adminReadBase
{
	var $condition = '';
	public function __construct()
	{
		parent::__construct();
		include('../lib/churu.class.php');
		$this->obj = new churu();
	}
	public function __destruct() {
        parent::__destruct();
    }
    public function index()
    {
	    
    }
    public function show()
    {
    	//echo trim($this->input['start_time']);die;
	    $this->get_condition();
	    //echo $this->condition;die;
	    $type = trim($this->input['type']) ? trim($this->input['type']) : 'waichu';
		$offset = intval($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = intval($this->input['count']) ? intval($this->input['count']) : 20;
		$dataLimit = 'LIMIT '.$offset.','.$count;
		//echo $dataLimit;die;
		$data = $this->obj->show($type,$this->condition.$dataLimit);
		//print_r($data);die;
		if($data)
		{
			foreach($data as $vo)
			{
				$this->addItem($vo);
			}
			$this->output();
		}; 
    }
    public function detail()
    {
	    
    }
    public function count()
    {
	    
    }
    public function get_condition()
    {	
	    if($this->input['id'])
	    {
		    $this->condition = $this->input['id']; 
	    }elseif(trim($this->input['start_time'])){
		   $t = trim($this->input['start_time']);
		   $t = strtotime($t);
		   $this->condition = "AND start_time between ".$t.' AND '.($t+60*60*24).' ORDER BY start_time DESC ';
	    }
	    return $this->condition;
    }
}
$out = new churuApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
?>