<?php
/**
 * 获取站点的配置信息
 */
define('MOD_UNIQUEID', 'SiteConfig'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class SiteConfigApi extends  adminReadBase
{
    private $obj=null;
    private $tbname = 'site_config';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    /**
     * 显示站点配置信息
     * @return array
     */
    public function show()
    {
        $query = "SELECT * FROM ".DB_PREFIX.$this->tbname;
        $where = " WHERE 1 ";
        
        $datas = $this->obj->query($query);
        $config = array();
        if($datas)
        {
            foreach($datas as $data)
            {
                $config[$data['key_field']] = $data['key_value'];
            }
        }
        $this->addItem($config);
        $this->output();
    }
    
    public function count()
    {
        
    }
    
    public function detail()
    {
        
    }
    
    public function index()
    {
        
    }
}
$out = new SiteConfigApi();
$action = 'show';
$out-> $action();
?>