<?php
/**
 * memcache缓存类
 *
 * User: wangleyuan
 * Date: 14/10/23
 * Time: 下午1:18
 */

class cache_memcache {
    /**
     * 配置
     */
    protected $settings = array(
        'host'    => 'localhost',   //服务器地址
        'port'    => '11211',  //连接端口
        'timeout' => '1',     //连接超时时间
        'expire'  => 3600,   //缓存过期时间
        'servers'  => array(

        ),
    );

    /**
     * memcache对象实例
     * @var null
     */
    private $memcache = null;

    /**
     * 构造函数
     */
    public function __construct($settings = '')
    {
        if($settings)
        {
            $this->settings = array_merge($this->settings, $settings);
        }
        $this->memcache = new Memcache();
        $this->memcache->connect($this->settings['host'], $this->settings['port'], $this->settings['timeout']);
    }

    public function get($id)
    {
        return $this->memcache->get($id);
    }

    public function set($id, $data, $expire = '')
    {
        $expire = $expire ? intval($expire) : intval($this->settings['expire']);
        return $this->memcache->set($id, $data, false, $expire);
    }

    public function delete($id)
    {
        return $this->memcache->delete($id);
    }

    public function clean()
    {
        return $this->memcache->flush();
    }

    public function is_supported()
    {
        if ( !extension_loaded('memcache') )
        {
            return false;
        }
        return true;
    }

}
