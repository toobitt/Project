<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','recycle');
class recycleClear extends cronBase
{
    
    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,  
            'name' => '清除回收站数据',    
            'brief' => '清除回收站数据',
            'space' => '86400', //运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function clear()
    {
        if(defined('CLEAR_DATE') && CLEAR_DATE)  //未定义此配置不进行清理
        {
            $time= TIMENOW - CLEAR_DATE * 86400;
            $sql = "SELECT r.*,c.* FROM " . DB_PREFIX . "recycle r 
                    LEFT JOIN " . DB_PREFIX . "content c 
                        ON r.id=c.recycleid 
                    WHERE time <= " . $time;
            $q = $this->db->query($sql); 
            while ($row = $this->db->fetch_array($q)) 
            {
                $row['content'] = unserialize($row['content']);
                $cid = $row['cid'];
                if($this->settings['App_' . $row['app_mark']])
                {           
                    $this->curl = new curl($this->settings['App_' . $row['app_mark']]['host'],$this->settings['App_' . $row['app_mark']]['dir'] . 'admin/');
                    $this->curl->setSubmitType('post');
                    $this->curl->setReturnFormat('json');
                    $this->curl->initPostData();
                    $this->curl->addRequestData('a', 'delete_comp');
                    $this->curl->addRequestData('cid', $cid);
                    $this->array_to_add('content' , $row['content']);
                    $filename = '';
                    switch($row['app_mark'])
                    {
                        case 'vote':
                            $filename = 'vote_question';
                            break;
                        case 'livmedia':
                            $filename = 'vod';
                            break;
                        default:
                            $filename = $row['app_mark'];
                            break;
                    }               
                    $response = $this->curl->request($filename . '_update.php');
                }
                $sql = "DELETE FROM " . DB_PREFIX . "recycle WHERE id IN(" . $row['id'] . ")";
                $this->db->query($sql);
                $sql = "DELETE FROM " . DB_PREFIX . "content WHERE recycleid IN(". $row['id'] .")";
                $this->db->query($sql);     
                $this->addItem($row['id']);            
            }     
        }
        $this->output();
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
