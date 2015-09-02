<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
require(CUR_CONF_PATH.'lib/insertgather.class.php');
require(CUR_CONF_PATH.'lib/content_update_plan.class.php');
define('MOD_UNIQUEID','auto_pub');
class auto_pub extends cronBase
{
	
    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,  
            'name' => '自动签发到采集',    
            'brief' => '自动签发到采集',
            'space' => '60', //运行时间间隔，单位秒
            'is_use' => 1,   //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    //自动签发到采集
    public function auto_gather()
    {
    	
    	set_time_limit(0);
    	$sql = 'SELECT * FROM '.DB_PREFIX.'plan WHERE is_open=1 AND auto_publish=1 AND next_time <= '.TIMENOW;
    	$result=$this->db->query($sql);
    	$ar=new insertgather();
    	while($row=$this->db->fetch_array($result)){
    		$arr[]=$row;
    	}

	    	if(!$arr){
	    		$this->errorOutput('计划任务为空');
	    	    exit;
	    	}else{
	    		foreach ($arr as $key => $value) {
	    			$ar->gather(trim($value['url']),$value['sort_id'],$value['id']);
	    			/*  $url=explode(',',$value['url']);
	    			 foreach ($url as $k => $v) {
	    			$sort_id=$v['sort_id'];
	    			}  */
	    			
	    			$next_sql='select mk_time,next_time from '.DB_PREFIX.'plan where id = '.$value['id'];
	    			$ndata=$this->db->query_first($next_sql);
	    			
	    			$upnext_time=array(
	    					'next_time' => $ndata['mk_time'] + $ndata['next_time']
	    			);
	    			
	    			$timeupdata=new contentUpdatePlan();
	    			$timeupdata->update($upnext_time,$value['id']);
	    			
	    	}
    	}
    }
    	
}

$out = new auto_pub();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'auto_gather';
}
$out->$action();

?>
