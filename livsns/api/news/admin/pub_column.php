<?php
/**
 * 内容发布是  记录分发关系
 * 此文件用于更新老数据的分发关系
 * 
 */
require_once('global.php');
define('MOD_UNIQUEID', 'news');
class pubColumn extends adminBase
{
    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/news.class.php');
        $this->obj = new news();        
    }
    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function pubColumn() {
        
        set_time_limit(0);
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 100000;
        
        $sql = "SELECT id, column_id FROM ".DB_PREFIX."article 
                WHERE column_id != '' AND column_id !='a:0:{}' AND column_id !='0'
                ORDER BY id ASC LIMIT " . $offset . ", " . $count;                
        $q = $this->db->query($sql);
        
        while ($row = $this->db->fetch_array($q)) {
            $row['column_id'] = unserialize($row['column_id']);
            $column_id = implode(',', array_keys($row['column_id']));
            $this->obj->update_pub_column($row['id'], $column_id);
            echo $row['id'] . str_repeat(' ', 4096). '<br />';
            ob_flush();
        } 
        
        exit;     
    }
}

$out = new pubColumn();
if (!method_exists($out, $_INPUT['a'])) {
    $action = 'pubColumn';
}
else {
    $action = $_INPUT['a'];
}
$out->$action();
?>