<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','videoop');
class builtXML extends cronBase
{
    function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/videoop.class.php');
        $this->obj = new videoop();        
    }
    
    function __destruct()
    {
        parent::__destruct();
    }
    
    public function initcron()
    {
        $array = array(
                'mod_uniqueid' => MOD_UNIQUEID,
                'name' => '创建XML文件',
                'brief' => '创建XML文件',
                'space' => '600', //运行时间间隔，单位秒
                'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function show()
    {
        $condition  = " AND state = 1";
        $condition .= " ORDER BY last_time DESC ";
        $condition .= " LIMIT 0, 10";
        $field = 'id, title, site_id, column_ids, email, update_peri,number_include,videoop_xml_dir,videoop_xml_filename';
        $list = $this->obj->getContentList($condition, $field);
        if (is_array($list) && count($list) > 0) {
            include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
            $this->pubContent = new publishcontent(); 
            include_once(ROOT_PATH . 'lib/class/mkpublish.class.php');
            $this->mkPublish = new mkpublish();
            include_once(CUR_CONF_PATH . 'lib/videoop_xml.class.php');
            foreach ($list as $key => $value) {
                $ret = $this->pubContent->videoop_content_data($value['site_id'], $value['number_include']);
                $ret = $ret[0];
                if ($ret) {
                     $webSite = rtrim($ret['site_info']['sub_weburl'], '.')  . '.' . $ret['site_info']['weburl'];
                     $email = $value['email'];
                     $updatePeri = $value['update_peri'];
                     // print_r($ret['content_data']);
                     $this->videoopXML = new videoopXML($webSite, $updatePeri, $email, $ret['content_data']);
                     $xml = $this->videoopXML->getRecordXML();
                     $filepath = rtrim($ret['site_info']['site_dir'], '/') . rtrim($value['videoop_xml_dir'], '/') . '/';
                     $filename = $value['videoop_xml_filename'];
                     $r = $this->mkPublish->file_in($filepath, $filename, $xml);
                     if ($r['msg'] = 'sucess') {
                         echo $value['id'] . '、' . $value['title'] . "----生成XML文件成功!";
                     } else {
                         echo $value['id'] . '、' . $value['title'] . "----生成XML文件失败!";
                     }
                }
            }
        }
    }
    
}
$out = new builtXML();
$action = $_INPUT['a'];
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'show';
}
$out->$action(); 
?>
