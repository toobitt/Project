<?php
/*
 * @ Creaeted by Sign 2015.07.07.15:10
 */
if(!defined('BASE_DIR'))
{
    define('BASE_DIR',dirname(__FILE__).'/');
    define('CONTRO_DIR',realpath('controller').'/');
    define('VIEW_DIR',realpath('views').'/');
    define('PUBLIC_DIR',realpath('public').'/');
    define('COMMON_DIR',realpath('common').'/');
    define('CONFIG_DIR',realpath('config').'/');
    define('BASE_URL','project.com');
}
/*
 * @框架基类
 * @ $load为common加载类
 *
 */
class BaseCore
{
        public $load;

        private static $instance;

        private function __construct()
        {
            include_once(COMMON_DIR.'function.php');
            include_once(COMMON_DIR.'LoadFile.php');
            $this->load = new LoadFile();
        }

        public function __clone(){}

        public static function get_instance()
        {
            if(!self::$instance instanceof BaseCore)
            {
                self::$instance = new self();
            }
            return self::$instance;
        }
/*
 * 暂时apache中必须配置默认index.php访问，且域名后面暂时需要携带index.php
 */
    	public function index()
        {
            $uris = explode('/',ltrim($_SERVER['REQUEST_URI'],'/'));
            if(count($uris) <= 1 )
            {
                include_once(CONTRO_DIR.'Homepage.php');//@控制器文件名和类名必须统一，且不可为index
                $obj = new Homepage();
                $obj->index();
            }else
            {
                if(file_exists(CONTRO_DIR.$uris[1].'.php'))
                {
                    include_once(CONTRO_DIR . $uris[1] . '.php');
                    if(empty($uris[2]))//若无第二个参数则默认调index
                    {
                        (new $uris[1]())->index();
                    }else
                    {
                        (new $uris[1]())->$uris[2]();
                    }
                }else
                {
                    echo '404 not found ';
                    //header('Location:'.BASE_URL);
                }
            }
        }
}
BaseCore::get_instance()->index();