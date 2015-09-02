<?php
require './global.php';
define ('MOD_UNIQUEID', 'xmlSetting');
class xmlSettingUpdateApi extends adminBase
{
    public function __construct() 
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/xml_setting.class.php');
        include_once(CUR_CONF_PATH . 'lib/source_setting.class.php');
        $this->obj = new xmlSetting();
        $this->source_obj = new sourceSetting();
    }
    
    public function __destruct() 
    {
        parent::__destruct();
    }
    
    public function create()
    {
    	if (!$this->input['title'])
    	{
            $this->errorOutput('NO TITLE');
        }
        if (!$this->input['content'])
    	{
            $this->errorOutput('NO CONTENT');
        }
        if (!$this->input['file_name'])
    	{
            $this->errorOutput('NO FILE_NAME');
        }
        if (!$this->input['source_id'])
    	{
            $this->errorOutput('NO SOURCE_ID');
        }
        //file_put_contents('../cache/sss1',var_export($this->input,1));
        $content = trim($this->input['content']);
		$data = array(
				'title' => trim($this->input['title']),
				'content' => $content,
				'file_name' => trim($this->input['file_name']),
				'file_size' => intval($this->input['file_size']),
				'space_time' => intval($this->input['space_time']),
				'valid_time' => intval($this->input['valid_time']),
				'source_id'	=> intval($this->input['source_id']),
				'count_num' => trim($this->input['count_num']),
				'offset_num' => trim($this->input['offset_num']),
				'is_split'	=> 	intval($this->input['is_split']),
				'state'	=> 	intval($this->input['state']),
				'create_user' => $this->user['user_name'],
				'create_time' => TIMENOW,
				'is_index' => intval($this->input['is_index']),
				'index_file' => trim($this->input['index_file']),
				'index_content' => trim($this->input['index_content'])
		);
		$verify_name = $this->obj->verify_name($data['file_name'],0);
		if($verify_name)
		{
		   $this->errorOutput('FILE_NAME IS EXISTS');
		}
		$source = $this->source_obj->detail($data['source_id']);
		if(!empty($source))
		{
				$data['source_content'] = $source['content'];
		}
		else
		{
		    $this->errorOutput('SOURCE IS ERROR');
		}
		preg_match_all("/{while}(.*){\/while}/is",$content,$tmp_whiles);
		$whiles = $tmp_whiles[1][0];
		preg_match_all("/\#(.*)\#/i",$whiles,$tmp_words);
		$words = $tmp_words[1];
		$data['relation'] = json_encode($words);
		$info = $this->obj->create($data);
		$this->addItem($info);
		$this->output();
    }
    
    public function update()
    {       
    	if (!intval($this->input['id']))
    	{
            $this->errorOutput('NO ID');
        }
    	if (!$this->input['title'])
    	{
            $this->errorOutput('NO TITLE');
        }
        if (!$this->input['content'])
    	{
            $this->errorOutput('NO CONTENT');
        }
        if (!$this->input['file_name'])
    	{
            $this->errorOutput('NO FILE_NAME');
        }
        if (!$this->input['source_id'])
    	{
            $this->errorOutput('NO SOURCE_ID');
        }
        //file_put_contents('../cache/sss1',var_export($this->input,1));
        $content = trim($this->input['content']);
        if($content)
        {
	     //  file_put_contents('../cache/sss',var_export($content,1));
	       $id = intval($this->input['id']);
	       $data = array(
	       		'title' => trim($this->input['title']),
	       		'content' => $content,
	       		'file_name' => trim($this->input['file_name']),
	       		'file_size' => intval($this->input['file_size']),
	       		'space_time' => intval($this->input['space_time']),
	       		'valid_time' => intval($this->input['valid_time']),
	       		'source_id'	=> intval($this->input['source_id']),
				'count_num' => trim($this->input['count_num']),
				'offset_num' => trim($this->input['offset_num']),
				'is_split'	=> 	intval($this->input['is_split']),
				'is_index' => intval($this->input['is_index']),
				'index_file' => trim($this->input['index_file']),
				'index_content' => trim($this->input['index_content'])
	       );
	       if(!intval($this->input['is_index']))
	       {
		       $data['index_file'] = '';
		       $data['index_content'] = '';
	       }
	       $verify_name = $this->obj->verify_name($data['file_name'],$id);
	       if($verify_name)
	       {
		       $this->errorOutput('FILE_NAME IS EXISTS');
	       }
	       $source = $this->source_obj->detail($data['source_id']);
	       if(!empty($source))
	       {
	       		$data['source_content'] = $source['content'];
	       }
	       else
	       {
		        $this->errorOutput('SOURCE IS ERROR');
	       }
	       preg_match_all("/{while}(.*){\/while}/is",$content,$tmp_whiles);
	       $whiles = $tmp_whiles[1][0];
	       preg_match_all("/\#(.*)\#/i",$whiles,$tmp_words);
	       $words = $tmp_words[1];
	       $data['relation'] = json_encode($words);
	       $this->obj->update($data,$id);
        }
        $this->addItem($data);
        $this->output();
    }
    
    public function audit()
    {
       	$ids = $this->input['id'];
	    if (!$ids) {
	        $this->errorOutput('NO ID');
	    }
	    $state = intval($this->input['audit']) ? 1 : 2;
	    $info = $this->obj->audit($ids,$state);
	    $this->addItem($info);
	    $this->output();
    }
    
    public function delete()
    {
    	$ids = $this->input['id'];
        if (!$ids) {
            $this->errorOutput('NO ID');
        }
        $info = $this->obj->delete($ids);
        $this->addItem($info);
        $this->output();
    }
    
    public function rebulid_index()
    {
    	$info = array(123,'return' => 'success');
	     $this->addItem('1,2,3,4');
        $this->output();
    }
    
    public function rebulid_xml()
    {
    	$info = array(123,'return' => 'success');
	    $this->addItem('1,2,3,4');
        $this->output();
    }
}

$out = new xmlSettingUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
