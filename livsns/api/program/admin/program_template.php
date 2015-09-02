<?php

require('global.php');
define('MOD_UNIQUEID','program_template');
class programTemplateApi extends adminBase
{
    public function show()
    {
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = ' LIMIT ' . $offset . ', ' . $count;
        $condition = $this->get_condition();
        if (!class_exists('programTemplate')) {
            include (CUR_CONF_PATH  . 'lib/program_template.class.php');
        }
        $objProTemplate = new programTemplate();
        $fields = 'id, title,indexpic, create_time, update_time, create_userid, create_username, edit_userid, edit_username';
        $ret = $objProTemplate->getList($condition . $limit, $fields);
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
            $this->output();
        }
        if (!class_exists('programTemplate')) {
            include (CUR_CONF_PATH  . 'lib/program_template.class.php');
        }
        $objProTemplate = new programTemplate();
        $ret = $objProTemplate->getOneById($id);
        if (!empty($ret['data'])) {
            $date = date('Y-m-d');
            $noon = strtotime($date . ' 12:00');
            $program = array();
            if (is_array($ret['data']) && count($ret['data']) > 0) {
                foreach ($ret['data'] as $k => $v) {
                    $v['start_time'] = strtotime($data . ' ' . $v['start']);
                    if ($v['start_time'] < $noon) {
                        $v['pos'] = hg_get_pos($v['start_time'] - strtotime($date));
                        $v['slider'] = hg_get_slider($v['start_time'] - strtotime($date));
                        $key = 'am';                       
                    }
                    else {
                        $v['pos'] = hg_get_pos($v['start_time'] - strtotime($date." 12:00"));
                        $v['slider'] = hg_get_slider($v['start_time'] - strtotime($date." 12:00"));
                        $key = 'pm';                           
                    }
                    $v['id'] = $v['key'] = hg_rand_num(4);
                    $program[$key][] = $v;                    
                }
                $ret['data'] = $program;
            }
        }
        
        if ($ret) {
            $this->addItem($ret);
        }
        $this->output();        
    }

    
    public function count() {
        $condition = $this->get_condition();
        $sql = 'SELECT COUNT(*) AS total FROM ' .DB_PREFIX. 'program_template WHERE 1 ' . $condition;
        $ret = $this->db->query_first($sql);
        echo json_encode($ret);
    }

//节目库列表
    public function programLibraryList() {
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 400;
        $limit = ' LIMIT ' . $offset . ', ' . $count;
        $condition = $this->get_condition();
        if (!class_exists('programLibrary')) {
            include (CUR_CONF_PATH  . 'lib/program_library.class.php');
        }
        $objProLibrary = new programLibrary();
        $ret = $objProLibrary->getList($condition . $limit);
        if (is_array($ret) && count($ret) > 0) {
            $date = date('Y-m-d');
            $noon = strtotime($date . ' 12:00');
            foreach ($ret as $k => $v) {
                $ret[$k]['noon'] = (strtotime($data . ' ' . $v['start_time']) >= $noon) ? 1 : 0 ; 
            }
        }
        if (!empty($ret)) {
            $this->addItem($ret);
        }
        $this->output();        
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

$out = new programTemplateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();

?>