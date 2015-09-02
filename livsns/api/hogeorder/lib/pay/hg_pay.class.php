<?php
/**
 * 支付工厂类
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/4
 * Time: 下午11:17
 *
 * 调用方法
 * include_once(CUR_CONF_PATH . 'lib/PAY/hgpay.class.php');
 *
 * $config = array(
 *      'alipay' => array(
 *          'type' => 'alipay',
 *      ),
 *      'unionpay' => array(
 *          'type' => 'unionpay',
 *      ),
 *      'weixin' => array(
 *          'type' => 'weixin',
 *      ),
 * );
 * $hgPayFactory = hgPayFactory::get_instance($config);   //$config可为空 使用默认配置
 * $pay_driver = $hgPayFactory->get_driver('unionpay');   //类型支持  unionpay  alipay  weixin
 *
 * //生成与支付订单获取支付参数
 * $data = $pay_driver->getPayParam(HogePay $order);
 *
 * //查询订单
 * $data = $pay_driver->query(HogePay $order);
 *
 *
 *
 */


final class HgPayFactory {

    /**
     * 当前缓存工厂静态实例
     */
    private static $pay_factory;


    /**
     * 缓存配置列表
     *
     */
    protected  $config = array(
        'alipay' => array(
            'type' => 'alipay',
        ),
        'unionpay' => array(
            'type' => 'unionpay',
        ),
        'weixin' => array(
            'type' => 'weixin',
        ),
    );

    /**
     * 缓存驱动类实例列表
     */
    protected $driver_list = array();


    public function __construct()
    {

    }

    /**
     * 返回当前缓存工厂类实例
     * @param array $cache_config
     */
    public static function get_instance($config = array())
    {
        //当前工厂类实例为空时初始化该对象
        if (hgPayFactory::$pay_factory == '' || !empty($config))
        {
            hgPayFactory::$pay_factory = new hgPayFactory();
            if (!empty($config))
            {
                hgPayFactory::$pay_factory->config = $config;
            }
        }
        return hgPayFactory::$pay_factory;
    }

    /**
     * 返回支付驱动类实例
     * @param $pay_type
     */
    public function get_driver($pay_type)
    {
        if (!isset($this->driver_list[$pay_type]) || !is_object($this->driver_list[$pay_type]))
        {
            $this->driver_list[$pay_type] = $this->load($pay_type);
        }
        return $this->driver_list[$pay_type];
    }

    /**
     * 加载支付驱动类
     * @param $pay_name 支付配置名称
     */
    public function load($pay_name)
    {
        $object = null;

        if (!$this->config[$pay_name]['type'])
        {
            exit('缺少支付类型参数');
        }

        include_once (CUR_CONF_PATH . 'lib/pay/pay_driver/hg_pay_base.php');
        include_once (CUR_CONF_PATH . 'lib/pay/pay_driver/hg_pay_'.strtolower($this->config[$pay_name]['type']).'.class.php');
        $class = 'Hg' . ucwords($this->config[$pay_name]['type']);
        $object = new $class($this->config[$pay_name]);

        return $object;
    }
}