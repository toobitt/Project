<?php
require './global.php';
define ('MOD_UNIQUEID', 'tables');
class tablesUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/tables.class.php');
        $this->obj = new tables();
        $prms_data = array(
        	'_action' => 'manger',
        );
    	$this->verify_content_prms($prms_data);
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {
    	if(empty($this->input['name']))
    	{
	    	$this->errorOutput('NO NAME');
    	}
    	if(empty($this->input['table_name']))
    	{
	    	$this->errorOutput('NO TABLE_NAME');
    	}
    	$is_column = intval($this->input['is_column']) ? 1 : 0;
    	$tmp_bool = $this->obj->checkTableName($this->input['table_name']);
    	if($tmp_bool)
    	{
	    	$this->errorOutput('TABLE_NAME IS EXISTS');
    	}
    	$field_info = array(
    		'field_name' => $this->input['field_name'],
    		'field_type' => $this->input['field_type'],
    		'field_length' => $this->input['field_length'],
    		'field_auto' => $this->input['field_auto'],    		
    		'field_index' => $this->input['field_index'],
    		'field_mark' => $this->input['field_mark'],
    		'field_key' => $this->input['field_key'],    	
    	);
    	if($is_column)
    	{
    		foreach($field_info['field_name'] as $k => $v)
    		{
	    		if(in_array($v,array('column_id','column_name')))
	    		{
		    		$this->errorOutput(CAN_NOT_FORAMT_COLUMN);
	    		}
    		}
    		$key = count($field_info['field_name']);
	    	$field_info['field_name'][$key] = 'column_id';
	    	$field_info['field_type'][$key] = 'varchar';
	    	$field_info['field_length'][$key] = 200;
	    	$field_info['field_index'][$key] = 'default';
	    	$field_info['field_mark'][$key] = '栏目ID';
	    	$field_info['field_key'][$key] = hg_generate_user_salt(5);
	    	
	    	$field_info['field_name'][$key+1] = 'column_name';
	    	$field_info['field_type'][$key+1] = 'varchar';
	    	$field_info['field_length'][$key+1] = 500;
	    	$field_info['field_index'][$key+1] = 'default';
	    	$field_info['field_mark'][$key+1] = '栏目名称';
	    	$field_info['field_key'][$key+1] = hg_generate_user_salt(5);
    	}
    	$data = array(
    		'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
    		'table_name' => trim($this->input['table_name']) ? trim($this->input['table_name']) : '',
    		'sort_id' => intval($this->input['sort_id']) ? intval($this->input['sort_id']) : '',
    		'table_format' => $field_info,
    		'is_column' => $is_column,
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	);
    	
    	$ret = $this->obj->create($data);
    	if($ret['error'])
    	{
    		$this->errorOutput($ret['error']);
	    	
    	}
    	$this->addItem($ret);
        $this->output();
    }
    
    public function update()
    {
    	$id = intval($this->input['id']) ? intval($this->input['id']) : 0;
    	if(empty($id))
    	{
	    	$this->errorOutput('NO ID');
    	}
    	if(empty($this->input['name']))
    	{
	    	$this->errorOutput('NO NAME');
    	}
    	if(empty($this->input['table_name']))
    	{
	    	$this->errorOutput('NO TABLE_NAME');
    	}
    	$is_column = intval($this->input['is_column']) ? 1 : 0;
    	
    	$tmp_bool = $this->obj->checkTableName($this->input['table_name'],$id);
    	if($tmp_bool)
    	{
	    	$this->errorOutput(TABLES_IS_EXISTS);
    	}
    	$field_info = array(
    		'field_name' => $this->input['field_name'],
    		'field_type' => $this->input['field_type'],
    		'field_length' => $this->input['field_length'],
    		'field_auto' => $this->input['field_auto'],    		
    		'field_index' => $this->input['field_index'],
    		'field_mark' => $this->input['field_mark'],  
    		'field_key' => $this->input['field_key'],    	  	
    	);
    	if($is_column)
    	{
    		foreach($field_info['field_name'] as $k => $v)
    		{
	    		if(in_array($v,array('column_id','column_name')))
	    		{
		    		$this->errorOutput(CAN_NOT_FORAMT_COLUMN);
	    		}
    		}
    		$single = $this->obj->detail($id);
    		$single_key = array();
    		if(isset($single['table_format']))
    		{
	    		foreach($single['table_format'] as $k => $v)
	    		{
		    		if(in_array($v['field_name'],array('column_id','column_name')))
		    		{
			    		$single_key[$v['field_name']] = $k;
		    		}
	    		}
    		}
    		$key = count($field_info['field_name']);
	    	$field_info['field_name'][$key] = 'column_id';
	    	$field_info['field_type'][$key] = 'varchar';
	    	$field_info['field_length'][$key] = 200;
	    	$field_info['field_index'][$key] = 'default';
	    	$field_info['field_mark'][$key] = '栏目ID';
	    	$field_info['field_key'][$key] = $single_key['column_id'] ? $single_key['column_id'] : hg_generate_user_salt(5);
	    	
	    	$field_info['field_name'][$key+1] = 'column_name';
	    	$field_info['field_type'][$key+1] = 'varchar';
	    	$field_info['field_length'][$key+1] = 500;
	    	$field_info['field_index'][$key+1] = 'default';
	    	$field_info['field_mark'][$key+1] = '栏目名称';
	    	$field_info['field_key'][$key+1] = $single_key['column_name'] ? $single_key['column_name'] : hg_generate_user_salt(5);
    	}
    	$data = array(
    		'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
    		'table_name' => trim($this->input['table_name']) ? trim($this->input['table_name']) : '',
    		'sort_id' => intval($this->input['sort_id']) ? intval($this->input['sort_id']) : '',
    		'table_format' => $field_info,
    		'is_column' => $is_column,
    		'update_time' => TIMENOW,
    	);
    	
    	
    	
    	$ret = $this->obj->update($data,$id); 	
    	if($ret['error'])
    	{
    		$this->errorOutput($ret['error']);	    	
    	}
        $this->addItem($ret);
        $this->output();
    }
    
    public function audit()
    {
       
    }
    
    public function delete()
    {
        $id = $this->input['id'] ? trim($this->input['id']) : '';
        if (!$id) 
        {
            $this->errorOutput('NO ID');
        }
        $ret = $this->obj->delete($id);
        $this->addItem($ret);
        $this->output();
    }
    public function unknow(){}
}

$out = new tablesUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
