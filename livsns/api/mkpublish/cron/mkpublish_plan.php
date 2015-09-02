<?php

define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
define('CUR_M2O_PATH', '../lib/m2o/');
require_once(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'mkpublish_plan'); //模块标识
require(CUR_CONF_PATH . 'lib/functions.php');

class mkpublish_plan extends cronBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include news.class.php
     */
    public function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/mkpublish.class.php');
        $this->obj     = new mkpublish();
        include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
        $this->pub_sys = new publishsys();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '生成发布页面',
            'brief' => '生成发布页面',
            'space' => '1', //运行时间间隔，单位秒
            'is_use' => 1, //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function show()
    {
        $mkplan = $mk = array();
        $sql = "select * from ".DB_PREFIX."mkpublish_plan where is_open=1 and next_time<=".TIMENOW." order by next_time limit 0,1";
        $info = $this->db->query($sql);
        while($row = $this->db->fetch_array($info))
        {
            $sqlupdate = "update ".DB_PREFIX."mkpublish_plan set next_time=id*60+".(time()+$row['mk_time'])." where id=".$row['id'];
            $this->db->query($sqlupdate);
            $mkplan[] = $row;
        }
        if($mkplan)
        {
            foreach($mkplan as $k=>$v)
            {
                $mk[$k]['title'] = $v['title'];
                $mk[$k]['site_id'] = $v['site_id'];
                $mk[$k]['page_id'] = $v['page_id'];
                $mk[$k]['page_data_id'] = $v['page_data_id'];
                $mk[$k]['content_type'] = $v['content_type'];
                $mk[$k]['client_type'] = $v['client_type'];
                $mk[$k]['max_page'] = 20;
                $mk[$k]['publish_user'] = $this->user['user_name'];
                $mk[$k]['publish_time'] = TIMENOW;
            }
        }
        else
        {
            $this->addItem('NO_PLAN');
            $this->output();
        }
        
        $sql = $tag = '';
        $sql .= 'insert into '.DB_PREFIX."mking(title,site_id,page_id,page_data_id,content_type,client_type,max_page,publish_user,publish_time) values ";
        foreach($mk as $k=>$v)
        {
            $sql .= $tag.'('.'\''.$v['title'].'\','.'\''.$v['site_id'].'\','.'\''.$v['page_id'].'\','.'\''.$v['page_data_id'].'\','.'\''.$v['content_type'].'\','.'\''.$v['client_type'].'\','.'\''.$v['max_page'].'\','.'\''.$v['publish_user'].'\','.'\''.$v['publish_time'].'\''.')';
            $tag = ',';
        }
        $this->db->query($sql);
        echo $sql;exit;
    }

    

}

$out    = new mkpublish_plan();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>