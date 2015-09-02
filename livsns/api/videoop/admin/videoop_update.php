<?php
require './global.php';
define ('MOD_UNIQUEID', 'videoop');
class videoopUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/videoop.class.php');
        $this->obj = new videoop();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {
        if (!$this->input['title']) {
            $this->errorOutput('NO TITLE');
        }
        $data = array(
            'title'         => $this->input['title'],
            'site_id'       => $this->input['site_id'],
            'email'         => $this->input['email'],
            'update_peri'   => intval($this->input['update_peri']),
            'number_include'  => intval($this->input['number_include']),
            'videoop_xml_dir' => $this->input['videoop_xml_dir'],
            'videoop_xml_filename' => $this->input['videoop_xml_filename'],
            'user_id'              => $this->user['user_id'],
            'user_name'            => $this->user['user_name'],
            'create_time'          => TIMENOW,  
            'update_time'          => TIMENOW,
        );
        $data['id'] = $this->db->insert_data($data,'videoop');
        if ($data['site_id']) {
            if (!class_exists('publishconfig')) {
                include_once (ROOT_PATH . 'lib/class/publishconfig.class.php');
                $this->publishConfig = new publishconfig();
            }
            $site_info = $this->publishConfig->get_site_first('id,site_name,sub_weburl,weburl,site_keywords,site_dir', $data['site_id']);
            $this->db->update_data(array('site_info' => serialize($site_info)), 'videoop', 'id=' . $data['id']);
        }
        $this->addItem($data);
        $this->output();
    }
    
    public function update()
    {
        if (!$this->input['id']) {
            $this->errorOutput('NO ID');
        }
        if (!$this->input['title']) {
            $this->errorOutput('NO TITLE');
        }
        $id = intval($this->input['id']);
        $data = array(
            'title'         => $this->input['title'],
            'site_id'       => $this->input['site_id'],
            'email'         => $this->input['email'],
            'update_peri'   => $this->input['update_peri'],
            'number_include'=> intval($this->input['number_include']),
            'videoop_xml_dir' => $this->input['videoop_xml_dir'],
            'videoop_xml_filename' => $this->input['videoop_xml_filename'],
        );
        $this->db->update_data($data, 'videoop', 'id = ' . $id);
        if ($this->db->affected_rows() > 0) {
            $this->db->update_data('update_time='.TIMENOW, 'videoop', 'id = ' . $id);
        }
        if (!class_exists('publishconfig')) {
            include_once (ROOT_PATH . 'lib/class/publishconfig.class.php');
            $this->publishConfig = new publishconfig();
        }
        $site_info = $this->publishConfig->get_site_first('id,site_name,sub_weburl,weburl,site_keywords,site_dir', $data['site_id']);
        $this->db->update_data(array('site_info' => serialize($site_info)), 'videoop', 'id=' . $id);
        $data['id'] = $id;
        $this->addItem($data);
        $this->output();
    }
    
    public function audit()
    {
        $ids = $this->input['id'];
        if (!$ids) {
            $this->errorOutput('NO ID');
        }
        $idArr = explode(',', $ids);
        
        $audit = intval($this->input['audit']);
        if ($audit == 1) {  //审核
            $this->db->update_data(array('state' => 1), 'videoop', "id IN({$ids})");
            $return = array('status' => 1, 'id' => $idArr);
        } 
        else if ($audit == 0) {    //打回
            $this->db->update_data(array('state' => 2), 'videoop', "id IN({$ids})"); 
            $return = array('status' => 2, 'id' => $idArr);  
        }
        
        $this->addItem($return);
        $this->output();
    }
    
    public function delete()
    {
        $id = $this->input['id'];
        if (!$id) {
            $this->errorOutput('NO ID');
        } 
        $this->obj->delConByIds($id);
        $this->addItem($id);
        $this->output();
    }
}

$out = new videoopUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
