<?php
//数据直接插入采集库
include_once (ROOT_PATH.'lib/class/curl.class.php');
include_once(CUR_CONF_PATH.'lib/common.class.php');
class insertgather extends InitFrm {

	 public function __construct(){
	 	parent::__construct();
	 	$this->updata=new contentUpdatePlan();
	 }
	 
	 public function gather($url,$sid,$id) {
	 	$this->sort_id=$sid;
	 	$this->id=$id;
		$url = $this->VerifyUrl ( $url );
		$data = $this->insertdata ( $url );
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
	
	private function VerifyData($data) {
		if (! is_array ( $data )) {
			$this->errorOutput ( "is no array" );
		} else {
			return $data;
		}
	}
	
	public function insertdata($url) {
		$common_obj = new Common();
		$set = array (
				CURLOPT_URL => "$url",
				CURLOPT_RETURNTRANSFER => true 
				);
		$ch = curl_init ();
		curl_setopt_array ( $ch, $set );
		$data1 = curl_exec ( $ch );
		
		$pattern = '/<li>(.*?)<\/li>/is';
		preg_match_all ( $pattern, $data1, $str );
		$strall = $str [0];
		$listparse=parse_url($url);
		
		foreach ( $strall as $k => $v ) {
			preg_match('/<a href="(.*?)">(.*?)<\/a><\/div>/is',$v,$title);
			$conurl=parse_url($title[1]);
				if($courl['scheme']==null){
				$scheme='http://';
			}
			if($courl['host']==null){
				$host=$listparse['host'];
			}
			if($courl['path']==null){
				$path=$listparse['path'];
			}
			$conquery='?'.$conurl['query'];	
			$contenturl=trim($scheme.$host.$path.$conquery);
			$md5url=md5("$contenturl");
			$csql="select urlmd5 from ".DB_PREFIX."gather where urlmd5='".$md5url."'";
			$query=$this->db->query($csql);
			$checkurl=$this->db->fetch_array($query);
			if($checkurl){
				continue;
			}
			//内容页preg
			curl_setopt($ch,CURLOPT_URL,$contenturl);
			$href_content=curl_exec($ch);
			preg_match('/<\!\-\- m2o content start \-\->(.*?)<\!\-\- m2o content end \-\->/is',$href_content,$maincontent);
			preg_match('/<div class="brief">(.*?)<\/div>/is',$v,$brief);
			preg_match('/<div class="pubdate">(.*?)<\/div>/is',$v,$pubdate);
			preg_match('/<div class="subtitle">(.*?)<\/div>/is',$v,$subtitle);
			preg_match('/<div class="keywords">(.*?)<\/div>/is',$v,$keywords);
			preg_match('/<div class="author">(.*?)<\/div>/is',$v,$author);
			preg_match('/<img src="(.*?)" class="indexpic"\/>/is',$v,$indexpic);
			$arr=array(
				'title' => $title[2],
			    'brief' => $brief[1],
				'pubdate' => $pubdate[1],
				'subtitle' => $subtitle[1],
				'keywords' => $keywords[1],
				'author' => $author[1],
				'indexpic' => $indexpic[1],
				'content' => $maincontent[1],
			    'source_url' => $contenturl
			);
			$resultdata[]=array_reverse($arr);
		}
       
		foreach ($resultdata as $key => $value) {
			$common_obj->post_datagather($value,$this->sort_id);
				
			//更新接入状态
			$urlstatus=array(
					'urlmd5' => md5($contenturl),
					'url' => $contenturl,
					'is_publish' => 1,
					'title' =>$title[2],
					'create_time' => TIMENOW,
			);
			$this->updata->creategather($urlstatus);
		}

		curl_close ( $ch );
		return TRUE;
	}
	
	//更新状态
	public function getotherinfo(){
	    $this->info=$info;
	}	
}
?>
