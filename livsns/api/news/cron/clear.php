<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','news');
class recycleClear extends cronBase
{
    
    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,  
            'name' => '清除文稿数据',    
            'brief' => '清除文稿数据',
            'space' => '86400', //运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function clear()
    {
        if($this->settings['clear_config']['clear_date'])  //未定义此配置不进行清理
        {
            $time= TIMENOW - $this->settings['clear_config']['clear_date'] * 86400;
            $sort = $this->settings['clear_config']['clear_sort'];
            $sort = trim($sort, ',，');            
            if (!$sort) {
                exit('未选择需要清理的分类');
            }
            $sql = "SELECT childs FROM " .DB_PREFIX. "sort WHERE id IN(".$sort.")";
            $q = $this->db->query($sql);
            $idArr = array();
            while ($row = $this->db->fetch_array($q)) {
                $idArr[] = $row['childs'];
            }
            $sort = implode(",",$idArr);   
            if (!$sort) {
                exit('分类不存在');
            }
         
            $sql = "DELETE a, ac, ah, m, pc FROM ".DB_PREFIX."article a 
                    LEFT JOIN ".DB_PREFIX."article_contentbody ac 
                        ON a.id=ac.articleid
                    LEFT JOIN ".DB_PREFIX."article_history ah
                        ON a.id=ah.aid
                    LEFT JOIN ".DB_PREFIX."material m
                        ON a.id=m.cid
                    LEFT JOIN ".DB_PREFIX."pub_column pc
                        ON a.id=pc.aid    
                     WHERE a.create_time <= " . $time . " AND a.state != 1 AND a.sort_id IN(" . $sort . ")";
            $this->db->query($sql);
                     
            // $sql = "SELECT id FROM " . DB_PREFIX . "article   
                    // WHERE create_time <= " . $time . " AND state != 1 AND sort_id IN(" . $sort . ") LIMIT 0, 1000";
            // $q = $this->db->query($sql); 
            // while ($row = $this->db->fetch_array($q)) 
            // {
                // $sql = "DELETE FROM " . DB_PREFIX . "article WHERE id = " . $row['id'];
                // $this->db->query($sql);
                // $sql = "DELETE FROM " . DB_PREFIX . "article_contentbody WHERE articleid = " . $row['id'];
                // $this->db->query($sql); 
                // $sql = "DELETE FROM " . DB_PREFIX . "article_history WHERE aid = " . $row['id'];
                // $this->db->query($sql);    
//                          
                // //删除附件缓存表
                // $sql = "DELETE FROM " . DB_PREFIX . "material WHERE cid =" . $row['id'];
                // $this->db->query($sql);   
//                                      
                // echo $row['id'] . '-----';            
            // }     
        }
        else {
            exit('未设置清理');
        } 
    }


    public function array_to_add($str , $data)
    {
        global $curl;
        $str = $str ? $str : 'data';
        if (is_array($data))
        {
            foreach ($data AS $kk => $vv)
            {
                if(is_array($vv))
                {
                    $this->array_to_add($str . "[$kk]" , $vv);
                }
                else
                {
                    $this->curl->addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }
}

$out = new recycleClear();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'clear';
}
$out->$action();

?>
