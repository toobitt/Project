<?php
include_once (ROOT_PATH.'lib/class/curl.class.php');

class gatherapi extends coreFrm {
	
	 public function gather($url) {
		$url = $this->VerifyUrl ( $url );
		$data = $this->GetData ( $url );
		$this->InsertData ( $data );
	 }
	
	/**
	 * Url verify
	 */
	public function VerifyUrl($url) {
		if (! filter_var ( $url, FILTER_VALIDATE_URL )) {
			$this->errorOutput ( "URL error" );
		}
		return $url;
	}
	public function GetData($url) {
		$set = array (
				CURLOPT_URL => "$url",
				CURLOPT_RETURNTRANSFER => true 
				);
		$ch = curl_init ();
		curl_setopt_array ( $ch, $set );
		$data1 = curl_exec ( $ch );
		$pattern = '/<li class="(.*?)">(.*?)<\/li>/is';
		preg_match_all ( $pattern, $data1, $str );
		$strall = $str [2];
		foreach ( $strall as $k => $v ) {
			preg_match_all ( '/<span class="(.*?)">(.*?)<\/span>/i', $v, $strdata );
			$resultdata [$k] [field] = $strdata [1];
			$resultdata [$k] [value] = $strdata [2];
		}
		curl_close ( $ch );
		return $resultdata;
	}
	private function VerifyData($data) {
		if (! is_array ( $data )) {
			$this->errorOutput ( "is no array" );
		} else {
			return $data;
		}
	}
    
	//其它状态信息接收
	public function getotherinfo($info){
	    $this->info=$info;
	}

	//数据直接插入采集库
	public function insertgathre($data)
	{
		print_r($data);
	}
	//数据插入内容接入库
	private function InsertData($data) {
		
		foreach ( $data as $allkey => $allvalue ) {
			foreach ( $allvalue as $key => $value ) {
				$data [$allkey] [$key] [gather] = array_slice ( $value, 0, 5 );
				$data [$allkey] [$key] [content] = array_slice ( $value, 5, 1 );
				$data [$allkey] [$key] [other] = array_slice ( $value, 6 );
			}
		}
		
		$checksql='select cid from '.DB_PREFIX.'gather';
		$checkdata=$this->db->fetch_all($checksql);
		foreach ($data as $key => $value) {
			if($checkdata[$key][cid] == $value[value][gather][0]){
				unset($key);
			}else{
				unset ( $fie );
				unset ( $val );
				unset ( $vcontent );
				$query = "replace into " . DB_PREFIX . "gather";
				foreach ( $value [field] [gather] as $k => $v ) {
					$fie .= $v . ',';
					$val .= "'" . $value [value] [gather] [$k] . "'" . ",";
				}
				
				$fie_str = substr ( $fie, 0, - 1 );
				$val_str = substr ( $val, 0, - 1 );
				$create_user = $this->info['create_user'];
				$auto_publish = $this->info['auto_publish'];
				$sort_id = $this->info['sort_id'];
				$sql = $query . "(" . id . "," . $fie_str . ",". create_time . ",". create_user . ",". auto_publish . ",". sort_id  .")" . values . "(" . "null," . $val_str . ",'" . TIMENOW . "','" .$create_user . "','" .$auto_publish . "','" .$sort_id ."')";
				$this->db->query ( $sql );
				$gic = $this->db->insert_id ( $sql );
				//gather表中插入返回的id,用作content表的cid.
				$vcontent .= "'" . $value [value] [content] [0] . "'";
				$othercontent = "'" . serialize ( $value [value] [other] ) . "'";
				$vsql = "replace into " . DB_PREFIX . "content (cid,content,othercontent) values ($gic,$vcontent,$othercontent)";
				$this->db->query ( $vsql );
				//$cid=$this->db->insert_id($vsql);
			}
		}
		
	}
	
	public function detail() {
		$id = $this->input ['id'];
		$sql = "select g.title,c.* from " . DB_PREFIX . "gather g left join " . DB_PREFIX . "content c on g.id=c.cid where g.id='{$id}'";
		$query = $this->db->query ( $sql );
		while ( $row = $this->db->fetch_array ( $query ) ) {
			$arr [] = $row;
		}
		
		$uns = unserialize ( $arr [0] ['othercontent'] );
		$arr [0] ['othercontent'] = $uns;
		$arr = $arr [0];
		$this->addItem ( $arr );
		
		$this->output ();
	}
	public function count() {
		$sql = "select count(*) as total from " . DB_PREFIX . "gather";
		$result = $this->db->query_first ( $sql );
		echo json_encode ( $result );
	}
}
?>
