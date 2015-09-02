<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','vote');
class vote extends cronBase
{
    
    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,  
            'name' => '签发到投票',    
            'brief' => '自动签发到投票',
            'space' => '600', //运行时间间隔，单位秒
            'is_use' => 1,  //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }
    
    //自动签发到投票
    public function action()
    {
    	global $gGlobalConfig;
    	$sql = 'select rp.feedback_id,rp.id,rp.stat,r.type,r.form_name,r.value,r.order_id from '.DB_PREFIX.'record_person rp left join '.DB_PREFIX.'record r on rp.id = r.person_id where rp.feedback_id = '.$gGlobalConfig['source']['0'].' and rp.process = 1' . ' and rp.stat != 1';
//     	echo $sql;exit;
    	$data = $this->db->fetch_all($sql);
    	if(empty($data)) {
    		exit ('没有数据');
    	}
     	
     	foreach ($data as $dkey => $dvalue) {
     		   $did=$dvalue['id'];
     		if($did==$dvalue['id']){
     			$ddata[$did][]=$dvalue;
     		}
     	}
    	foreach ($ddata as $key => $value) {
    		$this->send($value);
    	}
    	
    }
    
    public function send($data){
    	global $gGlobalConfig;
    	include_once(ROOT_PATH . 'lib/class/curl.class.php');
    	$curl = new curl($gGlobalConfig['App_vote']['host'],$gGlobalConfig['App_vote']['dir']);
    	$curl -> setSubmitType('post');
    	foreach ($data as $k => $v) {
    		if($v['order_id'] == '0') {
    			$curl -> addRequestData('title', $v['value']);
    			$curl -> addRequestData('vote_question_id',$gGlobalConfig['des']['0']);
    			$curl -> addRequestData('id', $v['id']); //record_person 的id
    			foreach ($data as $key => $value){
    				if($value['type'] == 'file') {
    					$sql='select * from ' .DB_PREFIX. 'materials where id = '.$value['value'];
    					$pic=$this->db->fetch_all($sql);
    					$pic=$pic['0'];
    					$indexpic=array(
    							'host' => $pic['host'],
    							'dir' => $pic['dir'],
    							'filepath' => $pic['material_path'],
    							'filename' => $pic['pic_name'],
    					);
    				}
    			}
    			$curl -> addRequestData('pictures_info', serialize($indexpic));
    			$curl -> addRequestData('a', vote_option);
    			$ret = $curl -> request('vote_option.php');
    			if($ret) {
    				$sql='update '.DB_PREFIX.'record_person set stat = 1 where id='.$v['id'];
    				$this->db->query($sql);
    			}
    			 
    		}
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

$out = new vote();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'action';
}
$out->$action();

?>
