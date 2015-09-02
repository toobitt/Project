<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

class main{
    public function __construct(){
        $this->init();
    }

    public function init(){
        $action = trim($_REQUEST['a']);
        !$action && $action = '_main';
        if(!method_exists($this, $action)){
            $action = '_main';
        }
        $this->$action();
    }

    public function _main(){

    }

    public function getMatch(){
        $url = $_REQUEST['url'];
        $parseUrlInfos = $this->parseUrl($url);
        $host = $parseUrlInfos['host'];
        if('news.qq.com' == $host){
            $info = array(
                'match' => '\/a\/\d+\/',
                'title' => '#C-Main-Article-QQ .hd h1',
                'content' => '#Cnt-Main-Article-QQ'
            );
        }
        $this->ej($info);
    }

    public function save(){
        $url = $_REQUEST['url'];
        $title = $_REQUEST['title'];
        $content = $_REQUEST['content'];
        $md5 = md5($url);
        @file_put_contents('./cache/' . $md5 . '.bak', $title . "\n" . $content);
        @file_put_contents('./cache/index.bak', "\n" . $md5, FILE_APPEND);
        $this->ej('ok');
    }

    public function lists(){
        $pp = intval($_REQUEST['pp']);
        $page = 20;
        $offset = $page * $pp;
        $list = @file_get_contents('./cache/index.bak');
        if($list){
            $list = explode("\n", $list);
            $eachs = array();
            $n = 0;
            foreach($list as $each){
                if(!$each) continue;
                if($n > $offset + $page){
                    break;
                }
                if($n >= $offset){
                    $eachs[$each] = @file_get_contents('./cache/' . $each . '.bak');
                    echo $eachs[$each];
                    echo '<br/><br/>';
                }
                $n++;
            }
        }

    }

    public function login(){
        $name = $_REQUEST['name'];
        $password = $_REQUEST['password'];
        if($name == 'test' && $password == 'test'){
            $this->ej(array('token' => 'xyz'));
        }else{
            $this->ej('登陆错误', true);
        }
    }

    public function ej($echo, $error = false){
        if(!is_array($echo)){
            if($error){
                $echo = array('error' => $echo);
            }else{
                $echo = array('ok' => $echo);
            }
        }
        echo json_encode($echo);
    }

    private function parseUrl($url){
        return parse_url($url);
    }
}
new main();
?>