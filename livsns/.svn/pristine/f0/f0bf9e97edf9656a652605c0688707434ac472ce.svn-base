<?php

require('global.php');
define('MOD_UNIQUEID','program_library');
class programLibraryApi extends adminBase
{
    public function show()
    {
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = ' LIMIT ' . $offset . ', ' . $count;
        $condition = $this->get_condition();
        if (!class_exists('programLibrary')) {
            include (CUR_CONF_PATH  . 'lib/program_library.class.php');
        }
        $objProLibrary = new programLibrary();
        $ret = $objProLibrary->getList($condition . $limit);
        if (is_array($ret) && count($ret) > 0) {
            foreach ($ret as $k => $v) {
                $this->addItem($v);
            }
        } 
        $this->output();
    }
    
    public function detail() {
        $id = intval($this->input['id']);
        if (!$id) {
            $this->errorOutput('NO ID');
        }
        if (!class_exists('programLibrary')) {
            include (CUR_CONF_PATH  . 'lib/program_library.class.php');
        }
        $objProLibrary = new programLibrary();
        $ret = $objProLibrary->getOneById($id);        
        if ($ret) {
            $this->addItem($ret);
        }
        $this->output();        
    }

    public function channelList() {
        if (!class_exists('live')) {
            include (CUR_CONF_PATH . 'lib/class/live.class.php');
        }
        $objNewLive = new live();
        $channel = $objNewLive->getChannel();
        $this->addItem($channel);
        $this->output();
    }
    
    public function count() {
        $condition = $this->get_condition();
        $sql = 'SELECT COUNT(*) AS total FROM ' .DB_PREFIX. 'program_library WHERE 1 ' . $condition;
        $ret = $this->db->query_first($sql);
        echo json_encode($ret);
    }

    public function get_condition() {
        $condition = '';
        
        return $condition;
    }
    
    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }
}

$out = new programLibraryApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();

?>