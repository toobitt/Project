<?php
/*
 * @ Creaeted by Sign 2015.07.07.15:10
 */
if (! defined('BASE_URL')) {
    define('BASE_URL', 'http://sign.com');//BASE_URL，加载public以及控制器跳转的基本路径。
    define('BASE_URI', 'http://sign.com/index.php');//BASE_URI作为页面跳转
    define('BASE_DIR', dirname(__FILE__) . '/');
    define('CONTRO_DIR', realpath('controller') . '/');
    define('VIEW_DIR', realpath('views') . '/');
    define('PUBLIC_DIR', realpath('public') . '/');
    define('COMMON_DIR', realpath('common') . '/');
    define('CONFIG_DIR', realpath('config') . '/');
}

date_default_timezone_set('PRC');
/*
 * @框架基类
 * @ $load为common加载类LoadFile实例化
 */

class BaseCore {

    public         $load;
    public         $pdo;
    private static $instance;

    protected function __construct()
    {
        include_once(COMMON_DIR . 'LoadFile.php');
        include_once(COMMON_DIR . 'function.php');
        include_once(COMMON_DIR . 'pdoMysql.php');
        include_once(CONFIG_DIR . 'global.php');
        $this->load = new LoadFile();
        $this->pdo  = (new pdoMysql())->connect();
    }

    protected function __clone()
    {
    }

    public static function get_instance()
    {
        if (! self::$instance instanceof BaseCore) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /*
     * 域名后面加载控制器时暂时需要携带index.php;
     */
    public function index()
    {
        $uris = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        if (count($uris) == 1) {
            include_once(CONTRO_DIR . 'Homepage.php');//@控制器文件名和类名必须统一，且不可为index
            $obj = new Homepage();
            $obj->index();
        } else {
            if (file_exists(CONTRO_DIR . $uris[1] . '.php')) {
                include_once(CONTRO_DIR . $uris[1] . '.php');
                if (empty($uris[2])) {//若无第二个参数则默认调index
                    (new $uris[1]())->index();
                } else {
                    (new $uris[1]())->$uris[2]();
                }
            } else {
                ShowError('ERROR:' . CONTRO_DIR . $uris[1] . '.php was not found!');
            }
        }
    }
}

BaseCore::get_instance()->index();