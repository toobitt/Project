<?php
include 'global.php';
define('MOD_UNIQUEID', 'seo');
include CUR_CONF_PATH . 'lib/MulitCurl.class.php';

class seoApi extends adminUpdateBase {
    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function show() {

        $params = array();
        $sites = array();
        $this->get_colums();
        //echo json_encode($this->siteinfos);exit();
        foreach($this->siteinfos as $site_id=>$sites){
            // if($site_id==1||$site_id==16)
                // continue;

            if(is_array($sites)&&$sites){
                foreach($sites as $k=>$v){
                    $params[$k]['url'] = "http://kernel.api.8684.com/v2/seo/title/get";
                    $params[$k]['params']['appkey'] = '0accae3fef143f432a24';
                    //$params[$k]['params']['url'] = "http://www.8684.com/z_97";
                    $params[$k]['params']['url'] = $v;
                    // $params[$k]['url'] = 'http://10.0.1.40/livsns/api/members/login.php';
                    // $params[$k]['params']['member_name'] = 'scala';
                    // $params[$k]['params']['password'] = '123';
                    // $params[$k]['params']['type'] = 'm2o';
                    // $params[$k]['params']['appid'] = '55';
                    // $params[$k]['params']['appkey'] = 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7';
                    // $params[$k]['params']['a'] = 'login';
                    
                }
            }
            //echo json_encode($params);exit();
            //unset($params['1118']);
            
            $re = $this->get_seo($params);
            $datas[$site_id] = $re;
            //var_export($re);exit();
            
        }
        
        //echo json_encode($datas);exit();
        $result = $this->update_seos($datas);

        
        //$result = $this->db->query($query);
        //var_dump($result);
        //echo json_encode($datas);
        //exit();
        //mk_column_url($row);
        $output['count'] = $result;
        $output['message'] = '';
        $this -> addItem($output);
        $this -> output();
    
    }

    public function update_seos($variables){
       global $gDBconfig;
       //echo json_encode($gDBconfig);exit();
       $link = mysql_connect($gDBconfig['host'],$gDBconfig['user'],$gDBconfig['pass']);
       mysql_select_db($gDBconfig['database'],$link);
       mysql_set_charset("utf8", $link);
        $query = "";
        $i = 0;
        foreach ($variables as $site_id => $datas) {
            //$i = 0;
            foreach($datas as $id=>$value){
                $i++;
                $query ="update  ".DB_PREFIX."column set  
                 `shortname`='".$value['title']."',  
                 `content`='".$value['description']."'   
                  where `id`=$id;
                ";
                //$this->db->query($query);
                mysql_query($query,$link);
//                if($i==20)
//                {
//                    //mysql_query($query,$link);
//                    $i=0;
//                    $query = '';
//                }
                //$this->db->query($query);
            }
        }
        //mysql_close($link);
        //echo $query;
        return $i++;;
    }


    private function get_seo($params){
        
        $MulitCurl = new MulitCurl();
        // $params['user']['url'] = "http://kernel.api.8684.com/v2/seo/title/get";
        // $params['user']['params']['appkey'] = '0accae3fef143f432a24';
        // $params['user']['params']['url'] = "http://www.8684.com/z_97,http://www.8684.com/z_97";
        $MulitCurl -> setTimeout(600);
        $MulitCurl -> setParams($params);
        $re = $MulitCurl -> rolling_curl();
        return $re;
    }

    private function get_colums(){
        $query = "select c.*,
                  s.sub_weburl as sub_web_url,
                  s.weburl as weburl,
                  s.site_dir as site_dir
                  from " . DB_PREFIX . "column c 
                  left join " . DB_PREFIX . "site  s 
                  on c.site_id=s.id " . $this -> get_condition();
        $result = $this -> db -> query($query);
        $column = array();
        while ($row = $this -> db -> fetch_array($result)) {
            $url = $this -> mk_column_url($row);
            if (!$this->isUrl($url))
                continue;
            $siteinfo[$row['site_id']][$row['id']] = $url;
        }
        $this->siteinfos = $siteinfo;
    }

    public function count() {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "column WHERE 1 " . $this -> get_condition();
        echo json_encode($this -> db -> query_first($sql));
    }

    private function get_condition() {
        $condition = ' where 1 ';

        if (isset($this -> input['site_id'])) {
            $site_id = intval($this -> input['site_id']);

            if ($site_id && $site_id != '-1') {
                $condition .= " AND c.site_id=" . $site_id;
            }
        }

        return $condition;
    }

    //删除栏目，只会一个一个删
    public function delete() {

        $this -> addItem($tag);
        $this -> output();
    }

    //权限中使用栏目节点公共方法
    function get_all_columns() {
        $sql = 'SELECT c.site_id,c.id,c.name,s.site_name FROM ' . DB_PREFIX . 'column c LEFT JOIN ' . DB_PREFIX . 'site s ON c.site_id = s.id WHERE c.fid = 0 ';
        $query = $this -> db -> query($sql);
        $column = array();
        while ($row = $this -> db -> fetch_array($query)) {
            $column[$row['site_id']][] = $row;
        }
        $this -> addItem($column);
        $this -> output();
    }

    function unknow() {
        $this -> errorOutput("此方法不存在！");
    }

    private function mk_column_url($row, $take_suffix = true, $need_filename = false) {
        $result = '';
        if ($row['linkurl'] && $row['is_outlink']) {
            return $row['linkurl'];
        }
        if ($row['father_domain']) {
            $result .= $row['father_domain'];
        } else {
            $result .= $row['sub_weburl'];
        }
        $result .= '.' . $row['weburl'];
        if ($row['relate_dir']) {
            $result .= '/' . trim($row['relate_dir'], '/');
        }
        $row['colindex'] = trim($row['colindex'], '.');
        $suffix = $row['maketype'] == 1 ? '.html' : '.php';
        if ($need_filename) {
            $result .= '/' . $row['colindex'] . $suffix;
        } else if ($take_suffix) {
            if ($row['colindex'] != 'index' && $row['colindex']) {
                $result .= '/' . $row['colindex'] . $suffix;
            }
        }

        return 'http://' . trim($result, '.');
    }

    public function create() {

    }

    public function update() {

    }

    public function audit() {

    }

    public function sort() {

    }

    public function publish() {

    }
    
    function isUrl($string) {
        return 0 < preg_match('/^(?:http(?:s)?:\/\/(?:[\w-]+\.)+[\w-]+(?:\:\d+)*+(?:\/[\w- .\/?%&=]*)?)$/', $string);
    }
    
    function cachedaas($key,$value,$timeout=86400){
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache = new Cache();
        $seodatas = CUR_CONF_PATH.'cache/seodatas';
        if ($value)
        {
            $cache->initialize($seodatas);
            $cache->set($key, $value,$timeout);
            return true;
            //return $this->memcache_set($key, $content_data, APP_UNIQUEID);
        }
        $cache->initialize($seodatas);
        $ret = $cache->get($key,true);
        if ($ret == 'no_file_dir')
        {
            return false;
        }
        return $ret;
    }

}

$out = new seoApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>