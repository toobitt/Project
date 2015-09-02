<?php
define ( 'MOD_UNIQUEID', 'gather' ); // 模块标识
require_once ('./global.php');
class gatherApi extends adminReadBase {
	public function __construct() {
		parent::__construct ();
	}
	public function __destruct() {
		parent::__destruct ();
	}
	public function show() {
		$offset=$this->input['offset'] ? intval($this->input['offset']) : 0;
		$count=$this->input['count'] ? intval($this->input['count']) : 20;
	
		$sql="select * from ".DB_PREFIX."gather limit $offset ".','." $count";

		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[] = $row;
		}
		if (!empty($arr))
		{
			foreach ($arr as $val)
			{
				$val['create_time']=date('Y-m-d H:i:s',$val['create_time']);
				if($val['is_publish']=='1'){
					$val['is_publish']='已签发';
				}elseif($val['is_publish']=='0'){
					$val['is_publish']='未签发';
				} 
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	/**
	 * 采集数据
	 */
	public function gather($url) {		
		$url=$this->VerifyUrl($url);
		$data=$this->GetData($url);
		$this->InsertData($data);
	}
	
	/**
	 * Url verify
	 */
	public function VerifyUrl($url) {
		if(!filter_var("$url", FILTER_VALIDATE_URL)){
 			$this->errorOutput("URL error");
 		}
 		return $url;
	}
	
	public function GetData($url) {
		$set=array (
				CURLOPT_URL => "$url",
				CURLOPT_RETURNTRANSFER => true,
				//CURLOPT_FOLLOWLOCATION => true
		);
		$ch = curl_init ();
		curl_setopt_array ($ch,$set);
		$data1 = curl_exec ($ch); 
        $pattern = '/<li class="(.*?)">(.*?)<\/li>/is';
 		preg_match_all($pattern,$data1,$str);
 		$strall=$str[2];
        foreach ($strall as $k => $v ) {
        	preg_match_all('/<span class="(.*?)">(.*?)<\/span>/i',$v,$strdata);
        	$resultdata[$k][field]=$strdata[1];
        	$resultdata[$k][value]=$strdata[2];
        }
		curl_close ( $ch );
		return $resultdata;
	}

	private function VerifyData($data) {
		if(!is_array($data)){
			$this->errorOutput("is no array");
		}else {
			return $data;
		}
	}
	
	private function InsertData($data) {
 		foreach ($data as $allkey => $allvalue) {
			foreach ($allvalue as $key => $value) {
				     $data[$allkey][$key][gather]=array_slice($value,0,5);
				     $data[$allkey][$key][content]=array_slice($value,5,1);
				     $data[$allkey][$key][other]=array_slice($value,6);
				}
		}		
		foreach ($data as $key => $value){
			unset($fie);
			unset($val);
			unset($vcontent);
			$query="insert into ".DB_PREFIX."gather";
			foreach ($value[field][gather] as $k => $v ) {
				$fie .= $v . ',';
				$val .= "'".$value[value][gather][$k]."'".",";
			}
			$fie_str=substr($fie,0,-1);
			$val_str=substr($val,0,-1);
			$sql=$query."(".id.",".$fie_str.")".values."("."null,".$val_str.")";
			$this->db->query($sql);
			$gic=$this->db->insert_id($sql);
			
			$vcontent .= "'".$value[value][content][0]."'";
			$othercontent="'".serialize($value[value][other])."'";
			$vsql="insert into ".DB_PREFIX."content (cid,content,othercontent) values ($gic,$vcontent,$othercontent)";
			$this->db->query($vsql);
			
		}
	}
	
	public function detail() {
		    $id=$this->input['id'];
		    $sql="select g.title,c.* from ".DB_PREFIX."gather g left join ".DB_PREFIX."content c on g.id=c.cid where g.id='{$id}'";
		    $query=$this->db->query($sql);
	        while($row=$this->db->fetch_array($query)){
	        	$arr[]=$row;
	        }
	        
 	        $uns=unserialize($arr[0]['othercontent']);
	        $arr[0]['othercontent'] = $uns;
 	        $arr=$arr[0];
	        $this->addItem($arr);
				
	        $this->output();
	}
	
	public function count() 
	{
		$sql="select count(*) as total from ".DB_PREFIX."gather";
		$result=$this->db->query_first($sql);
		echo json_encode($result);
	}
	
	public function index() {
	}
	private function get_condition() {
	}
	
}

$out = new gatherApi ();
$action = $_INPUT ['a'];
if (! method_exists ( $out, $action )) {
	$action = 'show';
}
$out->$action ();
?>
