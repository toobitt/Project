<?php
require 'global.php';
define('MOD_UNIQUEID', 'searchtag');
class searchtagApi extends outerUpdateBase {

    function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/searchtag.class.php');
        $this->mode = new searchtag();
    }
   
    function __destruct() {
        parent::__destruct();
    }
    
    
    public function show(){
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = " ORDER BY id DESC LIMIT $offset, $count";
        $condition = $this->get_condition();
        $fields = 'id, title, tag_val';
        $tag = $this->mode->tag_list($condition . $limit, $fields);  
        
        foreach ((array)$tag as $k => $v) {
        	$tag[$k]['tag_val'] = json_encode($v['tag_val']);
        }
        
        $this->addItem($tag);
        $this->output();            
    }
    
    private function get_condition() {
        $condition = '';
        
        if($this->input['app_uniqueid']) {
            $condition .= ' AND app_uniqueid = \''.$this->input['app_uniqueid'].'\'';
        }

        if ($this->input['mod_uniqueid']) {
            $condition .= ' AND mod_uniqueid = \''.$this->input['mod_uniqueid'].'\'';
        }
        
        if($this->user['user_id']) {
            $condition .= ' AND user_id = ' . $this->user['user_id'];
        }        
        return $condition;
    }
    
    public function detail() {
        if(!$this->input['id']) {
            $this->errorOutput(NO_ID);
        }
        
        $id = intval($this->input['id']);
        $sql = 'SELECT * FROM '.DB_PREFIX.'searchtag WHERE id = ' . $id;
        $info = $this->db->query_first($sql);
        if ($info) {
            $info['tag_val'] = $info['tag_val'] ? unserialize($info['tag_val']) : array();
            
            if($info['tag_val'])
            {
            	if($info['tag_val']['end_time'] && stripos($info['tag_val']['end_time'], '00:00:00') === false)
            	{
            		if(($end_time = strtotime($info['tag_val']['end_time'])) == strtotime(date('Y-m-d',$end_time)))
            		{
            		  $info['tag_val']['end_time'] = date('Y-m-d',$end_time);
            		  $info['tag_val']['end_time'] .= " 23:59:59";
            		}
            	}
            }
        }
        $this->addItem($info);
        $this->output();
    }
    
    public function create(){
        if(!$this->input['title']) {
            $this->errorOutput(NO_TITLE);
        }
        //验证相同用户相同模块的搜索标签是否有重复的
        $sql = 'SELECT id FROM '.DB_PREFIX.'searchtag 
                WHERE title = \''.$this->input['title'].'\' 
                AND user_id = ' . $this->user['user_id'] .' 
                AND app_uniqueid = \'' . $this->input['app_uniqueid'] .'\'
                AND mod_uniqueid = \'' . $this->input['mod_uniqueid'] . '\'';
        if($this->db->query_first($sql)) {
            $ret = array('errno' => 1, 'errmsg' => '标签名不能重复');
            $this->addItem($ret);
            $this->output();
        }        
        $data = array(
            'title'         => $this->input['title'],
            'app_uniqueid'  => $this->input['app_uniqueid'],
            'mod_uniqueid'  => $this->input['mod_uniqueid'],
            'user_id'       => $this->user['user_id'],
            'user_name'     => $this->user['user_name'],
            'create_time'   => TIMENOW,
        );
        $tag_val= json_decode(html_entity_decode($this->input['tag_val']),1);
        $val = array();
        foreach ((array)$tag_val as $k => $v) {
        	$val[$v['name']]  = $v['value'];
        }	
        
        $data['tag_val'] = $val ? addslashes(serialize($val)) : '';
        $data['tag_id'] = $this->mode->create($data);
        
        $data['tag_val'] = $data['tag_val'] ? unserialize(stripslashes($data['tag_val'])) : array();
        $data['tag_val'] = json_encode($data['tag_val']);
        $this->addItem($data);
        $this->output();
    }

    public function update(){}

    public function delete() {
        $id = $this->input['id'];
        if(!$id) {
            $this->errorOutput(NO_ID);
        }
        if (!is_array($id)) {
            $id = explode(',', $id);
        }
        $id = implode("','", $id);
        $sql = 'DELETE FROM '.DB_PREFIX.'searchtag WHERE id IN(\''. $id .'\')';
        $this->db->query($sql);
        
        $this->addItem($this->input['id']);
        $this->output();
    } 
}

$out = new searchtagApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();
