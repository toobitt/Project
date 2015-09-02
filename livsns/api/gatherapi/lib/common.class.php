<?php
class common extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	function post_datagather($arrall,$sort_id=''){
		
			$curl = new curl($this->settings['App_gather']['host'],$this->settings['App_gather']['dir']);
			$curl->setCurlTimeOut('3600');
			$curl->setSubmitType('post');
			$curl->addRequestData('a', create);
			$curl->addRequestData(content,$arrall[content]);
			$curl->addRequestData(title,$arrall[title]);
			$curl->addRequestData(brief,$arrall[brief]);
			$curl->addRequestData(subtitle,$arrall[title]);
			$curl->addRequestData(indexpic,$arrall[indexpic]);
			$curl->addRequestData(subtitle,$arrall[subtitle]);
			$curl->addRequestData(keywords,$arrall[keywords]);
			$curl->addRequestData(pic,$arrall[img]);
			$curl->addRequestData(sort_id,$sort_id);
			$curl->addRequestData(source_url,$arrall[source_url]);
			$ret = $curl->request('gather_update.php');
	}
	
}
