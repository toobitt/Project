<?php
require 'global.php';
define('MOD_UNIQUEID', 'searchtag');
class searchtagUpdateApi extends adminUpdateBase {
    
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/searchtag.class.php');
        $this->mode = new searchtag();        
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create() {} 
    public function update() {}
    
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
        
    public function audit() {}
    public function sort() {}
    public function publish() {}
    
    
    public function unknow() {
        $this->errorOutput('方法不存在');
    }
    
}

$obj = new searchtagUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($obj, $action)) {
    $action = 'unknow';
}
$obj->$action();