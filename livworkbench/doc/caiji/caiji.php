<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
class main{
    static public $input;
    static public $globalConfigName = 'global.json';
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
        echo '_main';
    }

    static public function config(){
        $config = @file_get_contents(self::$globalConfigName);
        return $config ? json_decode($config) : array();
    }

    static public function saveConfig(){
        $data = self::$input['data'];
        $data && @file_put_contents(self::$globalConfigName, stripslashes($data));
    }

    static public function domain(){
        $domain = self::$input['domain'];
        $config = @file_get_contents(self::file($domain));
        return $config ? json_decode($config) : array();
    }

    static public function saveDomain(){
        $domain = self::$input['domain'];
        $data = self::$input['data'];
        @file_put_contents(self::file($domain), stripslashes($data));
    }

    static public function deleteDomain(){
        $domain = self::$input['domain'];
        if(!is_array($domain)){
            $domain = array($domain);
        }
        foreach($domain as $d){
            @unlink(self::file($d));
        }
    }

    static public function file($domain){
        return self::$cacheDir . $domain . '.json';
    }
}
$result = main::init();

?>

<?php
if(in_array($_REQUEST['a'], array('config', 'domain'))){
?>
<!doctype html>
<html>
    <head>
        <title>规则配置</title>
        <link rel="stylesheet" href="static/reset.css"/>
        <script src="static/jquery.min.js"></script>
        <script src="static/jquery-ui.js"></script>
        <script src="static/jquery.tmpl.min.js"></script>
        <script src="static/tip.js"></script>
    </head>
    <body>
        <script>
        var configs = <?php echo $result ? json_encode($result) : '[]';?>;
        </script>

        <?php
        if($_REQUEST['a'] == 'config'){
        ?>
        <ul class="box">
            <li class="add">
                <div><input class="_name" placeholder="网站名称"/></div>
                <ul>
                    <li><input class="_domain" placeholder="域名/二级域名"/></li>
                    <li class="add-domain"><span>+</span></li>
                </ul>
                <span class="save">确定</span>
            </li>
        </ul>
        <link rel="stylesheet" href="static/config.css"/>
        <script src="static/config.js"></script>
        <script type="text/x-jquery-tmpl" id="tpl">
            <li>
                <span class="del-site">删除该网站</span>
                <div data-val="{{= name}}"><a href="http://{{= domains[0]}}" target="_blank">{{= name}}</a></div>
                <ul>
                {{each domains}}
                    <li data-val="{{= $value}}"><a target="_blank" href="?a=domain&domain={{= $value}}&name={{= name}}">{{= $value}}</a><span class="del">X</span></li>
                {{/each}}
                <li class="add-domain"><span>+</span></li>
                </ul>
            </li>
        </script>

        <?php
        }else{
        ?>
        <script>
        var domain = '<?php echo $_REQUEST["domain"]; ?>';
        var globalName = '<?php echo $_REQUEST["name"]; ?>';
        </script>
        <ul class="box">
            <li class="option"><span class="add">＋</span><span class="save">提交</span></li>
        </ul>
        <link rel="stylesheet" href="static/domain.css"/>
        <script src="static/domain.js"></script>
        <script type="text/x-jquery-tmpl" id="tpl">
            <li>
                <div><label>URL匹配规则：</label><input name="url" value="{{= url}}"/></div>
                <div><label>标题：</label><input name="title" value="{{= title}}"/></div>
                <div><label>作者：</label><input name="author" value="{{= author}}"/></div>
                <div><label>来源：</label><input name="source" value="{{= source}}"/></div>
                <div><label>时间：</label><input name="date" value="{{= date}}"/></div>
                <div><label>内容：</label><input name="content" value="{{= content}}"/></div>
                <div>
                    <label>分页：</label>
                    <input name="page" value="{{= page}}"/>
                    <input class="small" name="page_normal" placeholder="分页项" value="{{= page_normal}}"/>
                    <input class="small" name="page_current" placeholder="当前分页项" value="{{= page_current}}"/>
                    <select name="page_type">
                        <option value="href" {{if $data.page_type=='href'}}selected="selected"{{/if}}>href</option>
                        <option value="click" {{if $data.page_type=='click'}}selected="selected"{{/if}}>click</option>
                    </select>
                </div>
            </li>
        </script>
        <?php
        }
        ?>
    </body>
</html>
<?php
}
?>