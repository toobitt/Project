<?php
require './global.php';
define ('MOD_UNIQUEID', 'sourceSetting');
class sourceSettingUpdateApi extends adminBase
{
    public function __construct() 
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/source_setting.class.php');
        $this->obj = new sourceSetting();
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
        if (!$this->input['host'])
    	{
            $this->errorOutput('NO HOST');
        }
        if(!$this->input['filename'])
        {
	        $this->errorOutput('NO FILENAME');
        }
        //$xml = $this->input['content'];
        $data = array(
        	'title' 	=> trim($this->input['title']),
        	'host'		=> trim($this->input['host']),
        	'dir'		=> trim($this->input['dir']),
        	'filename'	=> trim($this->input['filename']),
        	'parameter'	=> trim($this->input['parameter']),
        	'port'		=> (intval($this->input['port']) == 0 || intval($this->input['port']) == 80) ? '' : intval($this->input['port']),
        	'state'		=> intval($this->input['state']),
        	'islocal'	=> intval($this->input['islocal']),
        	'data_type' => trim($this->input['data_type']),
        	'cid' => trim($this->input['cid']),
        	'create_user' => $this->user['user_name'],
        	'create_time' => TIMENOW,
        );
        if($info['state'])//审核通过直接请求数据缓存
        {
	      //  
        }        
        $content = $this->format_data($data);
	    $data['content'] = json_encode($content);
        $info = $this->obj->create($data);
         
	      
        $this->addItem($info);
        $this->output();
    }
    
    private function format_data($data)
    {
    	/*
    	$filecontnet = '';
    	$filename = $id . '.php';
    	$filedir = DATA_DIR;
    	*/
		$info = array();
		if($data['data_type'])
		{
			switch($data['data_type'])
			{
				case 'news':
					$info = array(
						'id' => 'id',
						'content_id' => 'content_id',
						'column_id' => 'column_id',
						'column_name' => 'column_name',
						'bundle_id' => 'bundle_id',
						'module_id' => 'module_id',
						'struct_id' => 'struct_id',
						'title' => 'title',
						'subtitle' => 'subtitle',
						'keywords' => 'keywords',
						'brief' => 'brief',
						'publish_time' => 'publish_time',
						'publish_user' => 'publish_user',
						'publish_date' => 'publish_date',
						'create_time' => 'create_time',
						'create_user' => 'create_user',
						'outlink' => 'outlink',
						'source' => 'source',
						'author' => 'author',
						'click_num'	=> 'click_num',
						'ip' => 'ip',
						'indexpic' => 'indexpic',
						'content_url' => 'content_url',
						'date' => 'date',
					);
					break;
				case 'livmedia':
					$info = array(
						'id' => 'id',
						'content_id' => 'content_id',
						'column_id' => 'column_id',
						'column_name' => 'column_name',
						'bundle_id' => 'bundle_id',
						'module_id' => 'module_id',
						'struct_id' => 'struct_id',
						'title' => 'title',
						'subtitle' => 'subtitle',
						'keywords' => 'keywords',
						'brief' => 'brief',
						'publish_time' => 'publish_time',
						'publish_user' => 'publish_user',
						'publish_date' => 'publish_date',
						'create_time' => 'create_time',
						'create_user' => 'create_user',
						'outlink' => 'outlink',
						'source' => 'source',
						'author' => 'author',
						'click_num'	=> 'click_num',
						'ip' => 'ip',
						'indexpic' => 'indexpic',
						'duration' => 'duration',
						'duration_format' => 'duration_format', //和data/api.php保持一致
						'bitrate' => 'bitrate',
						'content_url' => 'content_url',
						'date' => 'date',
					);
					break;
				case 'variety':
					$info = array(
						'id' => 'id',
						'content_id' => 'content_id',
						'column_id' => 'column_id',
						'column_name' => 'column_name',
						'bundle_id' => 'bundle_id',
						'module_id' => 'module_id',
						'struct_id' => 'struct_id',
						'title' => 'title',
						'subtitle' => 'subtitle',
						'keywords' => 'keywords',
						'brief' => 'brief',
						'publish_time' => 'publish_time',
						'publish_user' => 'publish_user',
						'publish_date' => 'publish_date',
						'create_time' => 'create_time',
						'create_user' => 'create_user',
						'outlink' => 'outlink',
						'source' => 'source',
						'author' => 'author',
						'click_num'	=> 'click_num',
						'ip' => 'ip',
						'indexpic' => 'indexpic',
						'duration' => 'duration',
						'duration_format' => 'duration_format', //和data/api.php保持一致
						'bitrate' => 'bitrate',
						'content_url' => 'content_url',
						'date' => 'date',
						'index' => array(
							'id',
		    				'rid',
		    				'title',
		    				'subtitle',
		    				'brief',
		    				'keywords',
		    				'source',
		    				'create_user',
		    				'publish_user',
		    				'author',
		    				'create_time',
		    				'publish_time',
		    				'verify_time',
		    				'content',
		    				'indexpic',
		    				'content_url',
						),
					);
					break;
				default:
					break;
			}
		}
		else
		{
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
			$curl_connect = new curl($data['host'] . ($data['port'] ? ":" . $data['port'] : ''), $data['dir']);
			$curl_connect->setSubmitType('post');
			$curl_connect->setReturnFormat('json');
			$curl_connect->initPostData();
			$tmp_data = array();
			if(!empty($data['parameter']))
			{
				$data['parameter'] = explode('&',trim($data['parameter']));
			}
			foreach($data['parameter'] as $k => $v)
			{
				$tmp = explode('=',$v);
				$curl_connect->addRequestData($tmp[0], $tmp[1]);
			}
			$ret = $curl_connect->request($data['filename']);
			$info = array_keys($ret[0]);
		}
		return $info;
    }
    
    public function update()
    {	
    	if (!$this->input['id'])
    	{
            $this->errorOutput('NO ID');
        }    
    	if (!$this->input['title'])
    	{
            $this->errorOutput('NO TITLE');
        }
        if (!$this->input['host'])
    	{
            $this->errorOutput('NO HOST');
        }
        if(!$this->input['filename'])
        {
	        $this->errorOutput('NO FILENAME');
        }
        $id = intval($this->input['id']);
        //$xml = $this->input['content'];
        $data = array(
        	'title' 	=> trim($this->input['title']),
        	'host'		=> trim($this->input['host']),
        	'dir'		=> trim($this->input['dir']),
        	'filename'	=> trim($this->input['filename']),
        	'parameter'	=> trim($this->input['parameter']),
        	'port'		=> (intval($this->input['port']) == 0 || intval($this->input['port']) == 80) ? '' : intval($this->input['port']),
        	'state'		=> intval($this->input['state']),
        	'islocal'	=> intval($this->input['islocal']),
        	'data_type' => trim($this->input['data_type']),
        	'cid' => trim($this->input['cid']),
        );
        
        if($data['state'])//审核通过直接请求数据缓存
        {
	       // 
        }
        $content = $this->format_data($data);
	    $data['content'] = json_encode($content);
	    
        $info = $this->obj->update($data,$id);
        $this->addItem($info);
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
}

$out = new sourceSettingUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
