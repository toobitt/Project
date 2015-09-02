


───────
 代码文件结构
───────

hogeorder
  │
  ├admin┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈后台管理接口文件夹
  │  │
  │  ├app_access.php ┈┈┈┈┈┈应用接入读取类接口文件
  │  │
  │  ├app_access_update.php┈┈┈┈┈┈┈应用接入修改类接口文件
  │  │
  │  ├global.php┈┈┈┈┈┈┈配置文件
  │  │
  │  ├node.php┈┈┈┈┈┈┈订单类别左侧节点文件
  │  │
  │  ├order.php┈┈┈┈┈┈┈订单管理读取读接口文件
  │  │
  │  ├order_update.php┈┈┈┈┈┈┈订单管理写接口文件
  │  │
  │  ├pay_config.php┈┈┈┈┈┈┈支付配置接口文件
  │  │
  │  ├receive_address.php┈┈┈┈┈┈┈收货地址读接口文件
  │  │
  │  ├receive_address_update.php┈┈┈┈┈┈┈收货地址写接口文件
  │
  ├cache┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈缓存文件夹
  │
  ├conf┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈基础文件夹
  │
  ├cron┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈计划任务文件夹
  │  │
  │  ├auto_timeout.php ┈┈┈┈┈┈处理过期订单计划任务
  │  │
  │  ├out_trade_notify.php ┈┈┈┈┈┈支付后通知第三方订单计划任务
  │
  │
  ├data┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈data文件夹
  │
  ├Middleware┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈中间件接口文件夹  临时存放此处
  │  │
  │  ├yc_bus_order.php ┈┈┈┈┈┈盐城汽车票中转接口文件
  │
  │
  ├notify┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈通知文件夹
  │  │
  │  ├notify_url_union.php ┈┈┈┈┈┈银联支付异步通知接口文件
  │  │
  │  ├notify_url_weixin.php ┈┈┈┈┈┈微信支付异步通知接口文件
  │
  │
  ├lib┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈类文件夹
  │  │
  │  ├pay ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈支付处理类文件夹
  │  │  │
  │  │  ├hg_http.php ┈┈┈┈┈┈http请求类
  │  │  │
  │  │  ├hg_pay.class.php ┈┈┈┈┈┈支付工厂类
  │  │  │
  │  │  ├pay_driver ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈银联、微信、支付宝支付驱动类文件夹
  │  │  │  │
  │  │  │  ├hg_pay_base.php ┈┈┈┈┈┈支付驱动基类文件
  │  │  │  │
  │  │  │  ├hg_pay_unionpay.class.php ┈┈┈┈┈┈银联支付处理文件
  │  │  │  │
  │  │  │  ├hg_pay_weixin.class.php ┈┈┈┈┈┈微信支付处理文件
  │  │  │  │
  │  │  │  ├hg_pay_alipay.class.php ┈┈┈┈┈┈支付宝支付处理文件
  │  │  │
  │  │  ├encrypt ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈支付加密类文件夹
  │  │  │  │
  │  │  │  ├hg_md5.class.php ┈┈┈┈┈┈md5加密文件
  │  │  │  │
  │  │  │  ├hg_rsa.class.php ┈┈┈┈┈┈rsa加密文件
  │  │  │  │
  │  │  │  ├hg_sha1.class.php ┈┈┈┈┈┈sha1加密文件
  │  │
  │  └function.php ┈┈┈┈┈┈订单接口公用函数文件
  │
  ├get_pay_type.php┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈获取应用支持支付类型
  │
  ├global.php┈┈┈┈┈┈┈┈┈┈┈┈基础配置文件
  │
  ├order.php┈┈┈┈┈┈┈┈┈┈┈┈┈┈订单读取接口文件  列表  详情
  │
  ├order_pay.php ┈┈┈┈┈┈┈┈┈┈┈┈┈订单支付文件
  │
  ├order_update.php ┈┈┈┈┈┈┈┈┈┈┈┈┈订单提交文件
  │
  ├receive_address.php ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈收货地址相关接口文件
  │
  ├region.php ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈省市区接口文件
  │
  ├hogepay.png ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈hoge支付流程图
  │
  └README.TXT ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈使用说明文本
