<?php
//define('ROOT_DIR', '../../../../');
//require_once('../../../../global.php');
class main{
    static public $input;
    static public $cacheDir = './cache/';

    static public function init(){
        self::$input = &$_REQUEST;
        $action = self::$input['a'];
        if(!$action || !in_array($action, get_class_methods(__CLASS__))){
            $action = '_main';
        }
        return self::$action();
    }

    static public function _main(){
        //global $gUser;
        $filename = main::$cacheDir . date('Y_m_d_H_i_s') . '_' . rand() . '.bak';
        //print_r($gUser);
        @file_put_contents($filename, json_encode(array(
            'text' => self::$input['text'],
            'url' => self::$input['url'],
            'user' => self::$input['user']/*这边外网没法访问进来的，所以用户信息没有*//*$gUser['user_name']*/,
            'chrome_name' => self::$input['chrome_name'],
            'time' => date('Ymd H:i:s')
        )));
    }

    static public function show(){
        $d = dir(self::$cacheDir);
        $path = $d->path;
        $count = self::$input['count'];
        (!$count || $count > 100) && $count = 20;
        $offset = intval(self::$input['offset']);
        $loop = 0;
        $info = array();
        while($file = $d->read()){
            $filePath = $path . '/' . $file;
            if($file == '.' || $file == '..' || !is_file($filePath)){
                continue;
            }
            $info[$file] = @file_get_contents($filePath);
            if(++$loop > $count){
                break;
            }
        }
        return $info;
    }
}
$result = main::init();

if($_REQUEST['a'] == 'show'){
?>
<html>
<body>
<?php
if($result){
    foreach($result as $key => $val){
        ?>
        <div>
            <h1><?php echo $key;?></h1>
            <div><?php echo $val;?></div>
        </div>
        <?php
    }
}
?>
</body>
</html>
<?php
}

?>