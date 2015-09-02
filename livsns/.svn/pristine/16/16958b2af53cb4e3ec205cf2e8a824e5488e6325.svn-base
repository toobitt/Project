<?php
/**
 * 缓存工厂类
 * User: wangleyuan
 * Date: 14/10/23
 * Time: 上午9:28
 *
 *
 * 调用方法
 * include_once(ROOT_PATH . 'lib/class/cache/cache.class.php');
 *
 * $cache_config = array(
 *    'file' => array(
 *      'type'  => 'file',
 *    ),
 *    'memcache' => array(
 *    ),
 * );
 * $cache_factory = cache_factory::get_instance($cache_config);   //$cache_config可为空 使用默认配置
 * $cache_driver = $cache_factory->get_cache_driver('file');   //类型支持  file  memcache  apc
 *
 * //读取文件缓存
 * $data = $cache_driver->get($id);
 *
 * //设置缓存
 * $data = $cache_driver->set($id, $data, $expire);
 *
 * //删除指定缓存
 * $cache_driver->delete($id);
 *
 * //清空所有缓存
 * $cache_driver->clean();
 *
 */


final class cache_factory {

    /**
     * 当前缓存工厂静态实例
     */
    private static $cache_factory;


    /**
     * 缓存配置列表
     *
     */
    protected  $cache_config = array(
        'file' => array(
            'type'  => 'file',
            'expire'  => 3600,
        ),
        'memcache' => array(
            'type'  => 'memcache',
            'host'  => 'localhost',
            'port'  => '11211',
            'timeout' => 1,
            'expire'  => 3600,
        ),
        'apc' => array(
            'type'  => 'apc',
        ),
    );

    /**
     * 缓存驱动类实例列表
     */
    protected $cache_driver_list = array();

    public function __construct()
    {

    }

    /**
     * 返回当前缓存工厂类实例
     * @param array $cache_config
     */
    public static function get_instance($cache_config = array())
    {
        //当前工厂类实例为空时初始化该对象
        if (cache_factory::$cache_factory == '' || !empty($cache_config) )
        {
            cache_factory::$cache_factory = new cache_factory();
            if (!empty($cache_config))
            {
                cache_factory::$cache_factory->cache_config = $cache_config;
            }
        }
        return cache_factory::$cache_factory;
    }

    /**
     * 返回缓存驱动类实例
     * @param $cache_name
     */
    public function get_cache_driver($cache_name)
    {
        if (!isset($this->cache_driver_list[$cache_name]) || !is_object($this->cache_driver_list[$cache_name]))
        {
            $this->cache_driver_list[$cache_name] = $this->load($cache_name);
        }
        return $this->cache_driver_list[$cache_name];
    }

    /**
     * 加载缓存驱动类
     * @param $cache_name 缓存配置名称
     */
    public function load($cache_name)
    {
        $object = null;

        switch($this->cache_config[$cache_name]['type'])
        {
            case 'file' :
                include_once (ROOT_PATH . 'lib/class/cache/driver/cache_file.class.php');
                $object = new cache_file($this->cache_config[$cache_name]);
                break;
            case 'memcache' :
                include_once (ROOT_PATH . 'lib/class/cache/driver/cache_memcache.class.php');
                $object = new cache_memcache($this->cache_config[$cache_name]);
                break;
            case 'apc' :
                include_once (ROOT_PATH . 'lib/class/cache/driver/cache_apc.class.php');
                $object = new cache_apc($this->cache_config[$cache_name]);
                break;
            default:
                include_once (ROOT_PATH . 'lib/class/cache/driver/cache_file.class.php');
                $object = new cache_file($this->cache_config[$cache_name]);
                break;
        }
        return $object;
    }
}