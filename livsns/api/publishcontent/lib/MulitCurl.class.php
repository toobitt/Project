<?php
class MulitCurl {
    private $delay = 500;
    private $timeout = 30;
    private $params = array();
    private $query_post = "";
    public function __construct() {

    }

    public function setParams($params = array()) {
        $this -> params += $params;
    }

    public function setFile($file, $handle) {
        if ($file) {
            foreach ($file as $var => $val) {
                if (is_array($val['tmp_name'])) {

                    foreach ($val['tmp_name'] as $k => $fname) {
                        if ($fname) {
                            // $Params[$var . "[$k]"] = "@" . $fname . ';type=' . $val['type'][$k] . ';filename=' . $val['name'][$k];
                            //$Params[$var . "[$k]"] = '@'.$fname;
                            //print_r($var);exit();
                            $Params[$var . "[$k]"] = new CurlFile($val['tmp_name'][$k], "@" . $val['type'][$k], $val['name'][$k]);
                        }
                    }

                } else {
                    if ($val['tmp_name']) {
                        //$Params[$var] = "@" . $val['tmp_name'] . ';type=' . $val['type'] . ';filename=' . $val['name'];
                        $Params[$var] = new CurlFile($val['tmp_name'], "@" . $val['type'], $val['name']);
                        //$Params[$var] = "@" . $val['tmp_name'];
                    }
                }
            }
            //$args['file'] = new CurlFile('filename.png', 'image/png');
            $params[$handle]['params'] = $Params;
            $this -> setParams($params);
            $this -> contain_file = true;
        }
    }

    public function setTimeout($timeout = 1) {
        $this -> timeout = $timeout;
    }

    public function setDelay($delay) {
        $this -> delay = $delay ? $delay : 50;
    }

    public function rolling_curl() {
        //echo json_encode($this->params);exit();

        $queue = curl_multi_init();
        $map = array();
        
        foreach ($this->params as $k => $param) {
            if (!$this -> verify_url($param['url'])) {
                continue;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $param['url']);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this -> timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);
            $post_params = $this -> verify_params($param['params']);

            $this -> build_query($post_params);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $this -> query_post);
            curl_multi_add_handle($queue, $ch);
            $map[(string)$ch] = $k;
        }

        $responses = array();
        do {
            while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM);

            if ($code != CURLM_OK) {
                break;
            }

            // a request was just completed -- find out which one
            while ($done = curl_multi_info_read($queue)) {
                //return $map;
                //return $done;
                // get the info and content returned on the request
                //$info = curl_getinfo($done['handle']);
                //$error = curl_error($done['handle']);
                //$results = $this -> callback(curl_multi_getcontent($done['handle']), $this -> delay);
                //$responses[$map[(string)$done['handle']]] = compact('info', 'error', 'results');
                $results = json_decode(curl_multi_getcontent($done['handle']),1);
                $responses[$map[(string)$done['handle']]] = $results;
                // remove the curl handle that just completed
                curl_multi_remove_handle($queue, $done['handle']);
                curl_close($done['handle']);
            }

            // Block for data in / output; error handling is done by curl_multi_exec
            if ($active > 0) {
                curl_multi_select($queue, 0.5);
            }

        } while ($active);

        curl_multi_close($queue);
        return $responses;
    }

    private function verify_url($url) {
        if (!is_string($url) || !$url) {
            return false;
        }
        return true;
    }

    private function verify_params($params) {
        if (empty($params)) {
            return false;
        }
        return $params;
    }

    private function callback($data, $delay) {
        $matches = array();
        preg_match_all('/<h3>(.+)<\/h3>/iU', $data, $matches);
        usleep($delay);
        return compact('data', 'matches');
    }

    private function build_query($postdatas, $fk = '') {

        foreach ($postdatas as $key => $postdata) {
            if (is_a($postdata, "CURLFile")) {
                $this -> query_post[$key] = $postdata;
                continue;
            }
            if (is_array($postdata)) {
                $this -> build_query($postdata, $key);
            }
            if ($fk) {
                $this -> query_post[$fk . "[$key]"] = $postdata;
            }else{
                $this -> query_post[$key] = $postdata;
            }
        }


    }

    public function __destruct() {

    }

}
?>