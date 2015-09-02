<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
require(CUR_CONF_PATH.'lib/gather.class.php');
define('MOD_UNIQUEID','gatherapi');
class recycleClear extends cronBase
{
    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,  
            'name' => '接入URL',    
            'brief' => '第三方URL地址接入',
            'space' => '60', //运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    public function gatherurl()
    {
  	    $time = time();
    	$sql = 'SELECT * FROM '.DB_PREFIX.'plan WHERE is_open=1 AND createtime < '.$time;
    	$query = $this->db->query($sql);
    	
    	$arr=array();
    	while($row=$this->db->fetch_array($query)){
    		$arr[]=$row;
    		$setstatussql="update ".DB_PREFIX."plan set next_time=".(time()+$row['mk_time'])." where id=".$row['id'];
    		$this->db->query($setstatussql);
			$otherinfo=array(
				'auto_publish' => $row['auto_publish'],
				'sort_id'  => $row['sort_id'],
			    'create_user'=>$this->user['user_name']
			);
    	}
    	$ar=new gatherapi();
    	
    	$getuser=$ar->getotherinfo($otherinfo);

    	//获取其它相关信息传到接口
    	/* $otherinfo=array(
    			$user=$this->user['user_name'],
    			$auto_publish = $this->input['auto_publish'],
    			$sort_id = $this->input['sort_id'],
    	); */
         
    	foreach ($arr as $k => $v) {
    		$url=explode("\n",$v[url]);
    		foreach ($url as $key=>$value) {
    			$ar->gather(trim($value));
    		}
    	}
    	
    }
}

$out = new recycleClear();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'gatherurl';
}
$out->$action();

?>
