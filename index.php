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
}
class BaseCore
{
        public $a = 1234;
        private static $instance;

        private function __construct(){}

        public function __clone(){}

        public static function get_instance()
        {
            if(!self::$instance instanceof BaseCore)
            {
                self::$instance = new self();
            }
            return self::$instance;
        }

    	public function index()
        {
            $uris = explode('/',ltrim($_SERVER['REQUEST_URI'],'/'));
            if(empty($uris[1]))
            {
                include_once(CONTRO_DIR.'Homepage.php');//@控制器文件名和类名必须统一，且不可为index
                (new Homepage())->index();
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
                    echo '404 fund';
                }
            }
        }
}
BaseCore::get_instance()->index();