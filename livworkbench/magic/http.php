<?php
define('ROOT_DIR', '../');
require_once('../global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class Http extends curl
{
    public function __construct($host = '', $apiDir = '') {
        parent::__construct($host, $apiDir); 
        $this->setCurlTimeOut(300);  
    }
    
    public function http($postFields, $method = 'post', $format = 'json' ) {
        $request = $response = '';
        $this->setSubmitType($method);
        $this->setReturnFormat($format);
        $this->initPostData();  
        if (is_array($postFields) && count($postFields) > 0 ) {
            foreach ( $postFields as $k => $v) {
                if (is_array($v)) {
                    $k == 'files' ?  $this->addFile($v) : $this->postArray($k, $v);
                } 
                else {
                     $k == 'request' ? ($request = $v) : $this->addRequestData($k, $v);
                }
            }
        }
        if ($request) {
            $response = $this->request($request) ;
        }
        return $response;         
   }

   private function postArray($str , $data) {
        $str = $str ? $str : 'data';
        if (is_array($data)) {
            foreach ($data AS $kk => $vv) {
                if(is_array($vv)) {
                    $this->postArray($str . "[$kk]" , $vv);
                }
                else {
                    $this->addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }
        
    public function __destruct() {
        parent::__destruct();
    }
}

?>
