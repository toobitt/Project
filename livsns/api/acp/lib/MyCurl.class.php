<?php
class MyCurl {
    var $curlopt_connection_timeout = 3;
    var $curlopt_timeout = 3;
    var $curlopt_returntransfer = true;
    var $curlopt_followlocation = true;
    var $curlopt_header = false;
    var $curlopt_post = true;

    public function __construct() {

    }

    public function setUrl($url) {
        $this -> curl = curl_init();
        curl_setopt($this -> curl, CURLOPT_URL, $url);
        curl_setopt($this -> curl, CURLOPT_HEADER, $this -> curlopt_header);
        curl_setopt($this -> curl, CURLOPT_FOLLOWLOCATION, $this -> curlopt_followlocation);
    }

    public function addParam($k, $v) {
        if (is_array($v)) {
            foreach ($v as $kk => $vv) {
                $this -> addParam($k . "[$kk]", $vv);
            }
        } else {
            $this -> params[$k] = $v;
        }
    }

    public function exec() {
        curl_setopt($this -> curl, CURLOPT_CONNECTTIMEOUT, $this -> curlopt_timeout);
        curl_setopt($this -> curl, CURLOPT_TIMEOUT, $this -> curlopt_timeoutp);
        curl_setopt($this -> curl, CURLOPT_POST, $this -> curlopt_post);
        curl_setopt($this -> curl, CURLOPT_POSTFIELDS, $this -> params);
        curl_setopt($this -> curl, CURLOPT_HEADER, $this -> curlopt_header);
        $datas = curl_exec($this -> curl);
        $this -> close();
        return $datas;
    }

    public function close() {
        return curl_close($this -> curl);
    }

    public function __destruct() {

    }

}
?>