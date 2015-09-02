<?php
require('global.php');
define('MOD_UNIQUEID','fast_special');
class fastSpecial extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
		$this->publishsys = new publishsys();
		include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
		$this->publishcontent = new publishcontent();	
		include_once(ROOT_PATH.'lib/class/logs.class.php');
		$this->logs = new logs();		
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	public function show(){}
	public function detail(){}
	public function count(){}
	private function get_condition()
	{	
	}
	
	//布局列表
	public function layout_list()
	{
		$list = $this->publishsys->layout_list();
		$this->addItem($list);
		$this->output();
	}
	
	//修改布局标题
	public function update_layout_title()
	{
		$layout_id = intval($this->input['layout_id']);
		if (!$layout_id) {
			$this->errorOutput('NO ID');
		}
		$ret = $this->publishsys->update_layout_title($layout_id, $this->input['is_header'], $this->input['header_text'], $this->input['is_more'], $this->input['more_href']);
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_special_templates()
	{
		$return  = array();
		$offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count      = $this->input['count'] ? intval($this->input['count']) : 400;
        
		if($this->input['tag'])
		{
			$tag = $this->input['tag'];
		}
		else
		{
			$tag = '';
		}
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			$list = $this->publishsys->get_special_templates($offset,$count,$tag);
		}
		else
		{
			$actions  = $this->user['prms']['app_prms'][APP_UNIQUEID]['action'];
			if($actions && is_array($actions))
			{
				if(!in_array('get_special_templates',$actions))
				{
					$list['error'] = 'NO_PRIVILEGE';
				}
				else
				{
					$list = $this->publishsys->get_special_templates($offset,$count,$tag);
				}
			}
		}
		
		$return['template'] = $list;
		$this->addItem($return);
		$this->output();
	}
	
	public function get_special_info()
    {
    	$offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count      = $this->input['count'] ? intval($this->input['count']) : 20;
        
    	$tag = $this->publishsys->get_template_tag($offset,$count);
		
		$special_id = $this->input['special_id'];
		if($special_id)
		{
			$sql = 'SELECT *
					FROM '.DB_PREFIX.'special WHERE id = '.$special_id;
			$spe_info  = $this->db->query_first($sql);
			$return['special'] = $spe_info;
		}
		
		if($spe_info['template_sign'])
		{
			$spe_tem  = $this->publishsys->get_template_info($spe_info['template_sign']);
			if($spe_tem && is_array($spe_tem))
			{
				$return['spetemp'] = array(
					'id'	=>	$spe_tem['id'],
					'title'	=>	$spe_tem['title'],
					'pic'	=>	$spe_tem['pic'],
					'sign'	=>	$spe_tem['sign'],
				);
			}
		}
    	$return['tag'] = $tag;
    	
    	$data = array();
    	$data = array(
    		'bundle_id' =>	APP_UNIQUEID,
    		'moudle_id' =>	MOD_UNIQUEID,
    		'title'		=>	$spe_info['name'],
    	);
    	$logs = $this->logs->queryLogs($data);
    	if($logs && is_array($logs))
    	{
    		foreach($logs as $k=>$v)
    		{
    			$info = array();
    			$info = array(
    				'title'			=>	$v['up_data']['tem_title'],
    				'user_name'		=>	$v['user_name'],
    				'create_time'	=>	date("Y-m-d", $v['create_time']),
    			);
    			$spe_logs[] = $info;
    		}
    	}
    	$return['logs']  = $spe_logs;
    	$this->addItem($return);
		$this->output();
    }
	
	public function select_template()
	{
		if (!$this->input['special_id']) {
			$this->errorOutput('SPECIAL ID CAN NOT EMPTY');
		}
		$special_id = intval($this->input['special_id']);
		$template_sign = $this->input['id'];
		$sql = "UPDATE " .DB_PREFIX. "special SET template_sign = '" . $template_sign . "'
				WHERE id = " . $special_id;
		$this->db->query($sql);
		
		$up_data = array('tem_title' => $this->input['tem_name']);
		$this->addLogs('选择专题模板','',$up_data,$this->input['spe_name']);
		$this->addItem(true);
		$this->output();
	}
	
	/**
	 * 获取布局预设信息
	 * @param int   	$id 布局id
	 * @return array    模板、布局、单元信息
	 * 
	 */
	 public function get_layout_preview()
	 {
	 	if (!$this->input['layout_id']) {
	 		$this->errorOutput('LAYOUTID IS EMPTY');
	 	}
		$layout_id = intval($this->input['layout_id']);
		$ret = $this->publishsys->get_layout_preview($layout_id);
		$this->addItem($ret);
		$this->output();
	 }
	
	public function update_special_layout()
	{
		$special_id = $this->input['special_id'] ? intval($this->input['special_id']) : intval($this->input['page_data_id']);
		if (!$special_id) {
	 		$this->errorOutput('SPECIAL_ID IS EMPTY');
	 	}	
		
		$layout_ids = $this->input['layout_ids'];
		$ret = $this->publishsys->update_special_layout($special_id, $layout_ids);		
		$this->addItem($ret);
		$this->output();
	}	
	
	
}
$out = new fastSpecial();
$action = $_INPUT['a'];
if (!method_exists($out,$action)){
	$action = 'show';
}
$out->$action();
?>
