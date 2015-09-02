<?php

/* * *************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: base_frm.php 46728 2015-07-22 02:09:40Z songzhi $
 * ************************************************************************* */

/**
 * 初始化类 接管全局数据
 *
 * @author develop_tong
 */
abstract class InitFrm {

    protected $input;
    protected $db;
    protected $settings;

    function __construct()
    {
        global $_INPUT, $gDB, $gGlobalConfig;
        $this->input    = &$_INPUT;
        $this->db       = &$gDB;
        $this->settings = &$gGlobalConfig;
    }

    function __destruct()
    {
        //NULL
    }

    public function __methods()
    {
        $methods = get_class_methods($this);
        foreach ($methods AS $f) {
            $this->addItem($f);
        }
        $this->output();
    }

    //日志纪录
    public function addLogs($operation, $pre_data, $up_data, $title = "", $content_id = "", $sort_id = "", $action = "")
    {
        @include_once(ROOT_PATH . 'lib/class/logs.class.php');
        if (class_exists('logs')) {
            $logs   = new logs();
            $action = $action ? $action : $this->input['a'];
            $logs->addLogs($operation, $pre_data, $up_data, $title, $content_id, $sort_id, $action);
        }
    }

}

/**
 * 核心基类 包含框架核心方法
 *
 * @author develop_tong
 */
abstract class coreFrm extends InitFrm {

    protected $queue;
    //protected $memcache;
    private   $addItemValueType = 0;//强调添加是 addItem值是否为数组
    protected $mData            = array();
    protected $mType            = 'xml';
    protected $mCharset         = 'UTF-8';
    protected $mRootNode        = 'root';
    protected $mItemNode        = 'item';
    protected $lang             = array();
    protected $mPrmsMethods     = array();

    function __construct()
    {
        parent::__construct();
        /*
          if($this->mModPrmsMethods && is_array($this->mModPrmsMethods))
          {
          foreach ($this->mModPrmsMethods AS $k => $v)
          {
          $this->mPrmsMethods[$k] = $v;
          }
          }
         */
        $this->setOutputType($this->input['format']);
    }

    function __destruct()
    {
        //NULL
        parent::__destruct();
    }

    function resetPrmsMethods()
    {
        $this->mPrmsMethods = array();
    }

    /**
     * 获取模块须权限控制方法

     */
    protected function get_prms_methods()
    {
        return array_keys($this->mPrmsMethods);
    }

    public function show_prms_methods()
    {
        if ($this->mPrmsMethods) {
            foreach ($this->mPrmsMethods AS $k => $m) {
                $this->addItem_withkey($k, $m);
            }
        }
        $this->output();
    }

    public function show_prms_nodes()
    {
        if (! empty($this->mNodes)) {
            $this->addItem($this->mNodes);
            $this->output();
        }
    }

    /**
     * 设置接口输出格式
     *
     * @param $type 输出格式，目前支持json和xml
     */
    private function setOutputType($type = 'xml')
    {
        if (! in_array($type, array('json', 'xml'))) {
            $type = 'json';
        }
        $this->mType = $type;
    }

    /**
     * 增加xml格式条目数据
     *
     * @param Array $data 数据条目
     */
    private function addItemxml($data)
    {
        $this->mData .= '<' . $this->mItemNode . '>';
        $this->mData .= $this->arrayToXml($data);
        $this->mData .= '</' . $this->mItemNode . '>';
    }

    /**
     * 增加xml格式条目数据
     *
     * @param Array $data 数据条目
     */
    private function addItemxml_withkey($key, $data)
    {
        $this->mData .= '<' . $this->mItemNode . '>';
        $this->mData .= $this->arrayToXml($data);
        $this->mData .= '</' . $this->mItemNode . '>';
    }

    /**
     * 数组转化为xml
     *
     * @param Array $data 数据条目
     */
    private function arrayToXml($data)
    {
        if (is_array($data)) {
            $out = '';
            foreach ($data AS $k => $v) {
                if (is_numeric($k)) {
                    $k = 'items';
                }
                if (is_array($v)) {
                    $out .= '<' . $k . '>' . $this->arrayToXml($v) . '</' . $k . '>';
                } else {
                    $out .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
                }
            }
        } else {
            $out = $data;
        }

        return $out;
    }

    /**
     * 增加json格式条目数据
     *
     * @param Array $data 数据条目
     */
    private function addItemjson($data)
    {
        ! $this->addItemValueType && $this->mData[] = $data;
        $this->addItemValueType && is_array($data) && $this->mData = $data;
    }

    /**
     * 增加json格式条目数据
     *
     * @param Array $data 数据条目
     */
    private function addItemjson_withkey($key, $data)
    {
        if ($key) {
            $this->mData[$key] = $data;
        } else {
            $this->mData = $data;
        }
    }

    /**
     * 设置接口返回状态头
     *
     * @param $status 状态值
     */
    protected function header($status)
    {
        if (! $status) {
            return;
        }
        $conf = array(
            100 => "HTTP/1.1 100 Continue",
            101 => "HTTP/1.1 101 Switching Protocols",
            200 => "HTTP/1.1 200 OK",
            201 => "HTTP/1.1 201 Created",
            202 => "HTTP/1.1 202 Accepted",
            203 => "HTTP/1.1 203 Non-Authoritative Information",
            204 => "HTTP/1.1 204 No Content",
            205 => "HTTP/1.1 205 Reset Content",
            206 => "HTTP/1.1 206 Partial Content",
            300 => "HTTP/1.1 300 Multiple Choices",
            301 => "HTTP/1.1 301 Moved Permanently",
            302 => "HTTP/1.1 302 Found",
            303 => "HTTP/1.1 303 See Other",
            304 => "HTTP/1.1 304 Not Modified",
            305 => "HTTP/1.1 305 Use Proxy",
            307 => "HTTP/1.1 307 Temporary Redirect",
            400 => "HTTP/1.1 400 Bad Request",
            401 => "HTTP/1.1 401 Unauthorized",
            402 => "HTTP/1.1 402 Payment Required",
            403 => "HTTP/1.1 403 Forbidden",
            404 => "HTTP/1.1 404 Not Found",
            405 => "HTTP/1.1 405 Method Not Allowed",
            406 => "HTTP/1.1 406 Not Acceptable",
            407 => "HTTP/1.1 407 Proxy Authentication Required",
            408 => "HTTP/1.1 408 Request Time-out",
            409 => "HTTP/1.1 409 Conflict",
            410 => "HTTP/1.1 410 Gone",
            411 => "HTTP/1.1 411 Length Required",
            412 => "HTTP/1.1 412 Precondition Failed",
            413 => "HTTP/1.1 413 Request Entity Too Large",
            414 => "HTTP/1.1 414 Request-URI Too Large",
            415 => "HTTP/1.1 415 Unsupported Media Type",
            416 => "HTTP/1.1 416 Requested range not satisfiable",
            417 => "HTTP/1.1 417 Expectation Failed",
            500 => "HTTP/1.1 500 Internal Server Error",
            501 => "HTTP/1.1 501 Not Implemented",
            502 => "HTTP/1.1 502 Bad Gateway",
            503 => "HTTP/1.1 503 Service Unavailable",
            504 => "HTTP/1.1 504 Gateway Time-out",
            505 => "HTTP/1.1 505 - HTTP Version Not Supported"
        );
        header($conf[$status], TRUE, $status);
    }

    /**
     * 清理xml字符串
     *
     * @param $str 待清理的字符串
     */
    protected function xmlClean($str)
    {
        $str = preg_replace('/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/', '', $str);

        return $str;
    }

    /**
     * 设置xml的根节点和条目节点
     *
     * @param $root_node 根节点
     * @param $item_node 条目节点
     */
    protected function setXmlNode($root_node = 'root', $item_node = 'item')
    {
        $this->mRootNode = $root_node;
        $this->mItemNode = $item_node;
    }

    /**
     * 增加条目数据
     *
     * @param Array $data 数据条目
     */
    protected function addItem_withkey($key, $data)
    {
        $func = 'addItem' . $this->mType . '_withkey';
        $this->$func($key, $data);
    }

    /**
     * 增加条目数据
     *
     * @param Array $data 数据条目
     */
    protected function addItem($data)
    {
        if ($this->settings['field_map'] && is_array($this->settings['field_map'])) {
            foreach ($this->settings['field_map'] as $from => $to) {
                if ($data[$from]) {
                    continue;
                }
                $data[$from] = $data[$to];
                //unset($data[$from]);
            }
        }
        $func = 'addItem' . $this->mType;
        $this->$func($data);
    }

    /**
     * 用于设置输出值强调为数据类型，解决addItemJson方法多加一层数组输出问题,1为全部数据数组，0为某一维数据 ...
     */
    protected function setAddItemValueType($type = 1)
    {
        $this->addItemValueType = $type;
    }

    protected function errorOutput($errno = 'Unknow', $status = '200')
    {
        $this->header($status);
        include(CUR_CONF_PATH . 'conf/error.conf.php');
        ! $errorConf[$errno] && @include(ROOT_DIR . 'conf/error.conf.php');
        if ('xml' == $this->mType) {
            $content_type = 'Content-Type:text/' . $this->mType . '; charset=' . $this->mCharset;
            $output       = '<?xml version="1.0" encoding="' . $this->mCharset . '"?>';
            $output .= '<Error>';
            $output .= '<ErrorCode>' . $errno . '</ErrorCode>';
            $output .= '<ErrorText>' . $errorConf[$errno] . '</ErrorText>';

            if (DEBUG_MODE) {
                $output .= '<Debug>' . hg_page_debug() . '</Debug>';
            }
            $output .= '</Error>';
        } else {
            $content_type = 'Content-Type: text/plain';
            $output       = array(
                "ErrorCode" => $errno,
                "ErrorText" => $errorConf[$errno],
            );
            if ($this->input['ikey']) {
                $output_k[$this->input['ikey']] = $output;
            } else {
                $output_k = $output;
            }
            $output = json_encode($output_k);
        }
        if (! $this->input['callback']) {
            header($content_type);
            echo $output;
        } else {
            header('Content-Type: text/javascript');
            echo $this->input['callback'] . '(' . $output . ');';
        }
        exit;
    }

    /**
     * 输出结果

     */
    protected function output()
    {
        //$this->header(200);
        if ('xml' == $this->mType) {
            $content_type = 'Content-Type:text/' . $this->mType . '; charset=' . $this->mCharset;
            $this->mData  = $this->xmlClean($this->mData);
            $output       = '<?xml version="1.0" encoding="' . $this->mCharset . '"?>';
            $output .= '<' . $this->mRootNode . '>';
            $output .= $this->mData;

            if (DEBUG_MODE) {
                $output .= '<Debug>' . hg_page_debug() . '</Debug>';
            }
            $output .= '</' . $this->mRootNode . '>';
        } else {
            $content_type = 'Content-Type:text/plain';
            if (count($this->mData) == 1) {
                //$this->mData = $this->mData[0];
            }
            if ($this->input['ikey']) {
                $data[$this->input['ikey']] = $this->mData;
            } else {
                $data = $this->mData;
            }
            $output = json_encode($data);
        }
        if (! $this->input['callback']) {
            header($content_type);
            echo $output;
        } else {
            header('Content-Type: text/javascript');
            echo $this->input['callback'] . '(' . $output . ');';
        }
        exit;
    }

    /**
     * 输出配置信息

     */
    public function __getConfig()
    {
        if (! $this->settings) {
            $this->settings = array();
        }
        $this->setXmlNode('configs', 'config');
        $this->addItem($this->settings);
        $this->output();
    }

    /**
     *  输出调试结果，debug用

     */
    protected function ConnectQueue()
    {
        if (! $this->queue) {
            if (class_exists('Memcache')) {
                global $gQueueConfig;
                $queue       = @array_pop($gQueueConfig);
                $this->queue = new Memcache();
                $connect     = @$this->queue->connect($queue['host'], $queue['port']);
                if (! $connect) {
                    include_once(ROOT_PATH . 'lib/class/queue.class.php');
                    $this->queue = new queue();
                } else {
                    if ($gQueueConfig) {
                        foreach ($gQueueConfig AS $queue) {
                            $this->queue->addServer($queue['host'], $queue['port']);
                        }
                    }
                }
            } else {
                include_once(ROOT_PATH . 'lib/class/queue.class.php');
                $this->queue = new queue();
            }
        }
    }

    protected function debug($data)
    {
        if (DEBUG_MODE) {
            if (1 == DEBUG_MODE) {
                if (! is_string($data)) {
                    print_r($data);
                } else {
                    echo $data;
                }
                echo '<br />#----------------------------------------------------------------------------------------------------------------------------#<br />';
            } else {
                hg_mkdir(LOG_DIR);
                hg_debug_tofile($data, 1, LOG_DIR . 'debug.txt');
            }
        }
    }

    private function get_login_cache_from_redis($key)
    {
        if (isset($this->settings['use_redis']) and $this->settings['use_redis']) {
            $redis       = new Redis();
            $conn_status = $redis->pconnect($this->settings['redis_read']['host'], $this->settings['redis_read']['port'], $this->settings['redis_read']['timeout']);

            if ($conn_status) {
                return $redis->get($key);
            }
        }

        return FALSE;
    }

    private function set_login_cache_to_redis($key, $value, $expire = 600)
    {
        if (isset($this->settings['use_redis']) and $this->settings['use_redis']) {
            $redis       = new Redis();
            $conn_status = $redis->pconnect($this->settings['redis_write']['host'], $this->settings['redis_write']['port'], $this->settings['redis_write']['timeout']);
            if ($conn_status) {
                return $redis->set($key, $value, $expire);
            }
        }

        return FALSE;
    }

    protected function verifyToken()
    {
        if (! defined('APP_UNIQUEID') || ! defined('MOD_UNIQUEID')) {
            $this->errorOutput(UNKNOWN_APP_UNIQUEID);
        }
        $gAuthServerConfig = $this->settings['App_auth'];
        if (! $gAuthServerConfig) //未配置授权
        {
            $this->user = array(
                'user_id'      => $this->input['user_id'],
                'user_name'    => $this->input['user_name'],
                'group_type'   => 1, //超级用户
                'appid'        => $this->input['appid'],
                'display_name' => $this->input['user_name'],
                'visit_client' => 0,
            );

            return;
        }

        // key of login cache
        $key = md5($this->input['access_token'] . $this->input['_outercall'] . $this->input['appkey'] . $this->input['m2o_ckey']);
        // expire time of login cache
        $token_expire = $this->settings['token_expire'] ? $this->settings['token_expire'] : 600;

        if ($this->settings['openlogincache']) {
            // 先从 redis 获取 cache 数据
            if ($ret = $this->get_login_cache_from_redis($key)) {
                $this->user = json_decode($ret, 1);

                return;
            } else {
                if (! defined('TOKEN_DIR')) {
                    $tokenfile = '/tmp/m2otoken/';
                } else {
                    $tokenfile = CACHE_DIR . 'm2otoken/';
                }
                if (! is_dir($tokenfile)) {
                    @mkdir($tokenfile);
                }

                $tokenfile .= $key;

                if (is_file($tokenfile) && (TIMENOW - filemtime($tokenfile)) <= $token_expire) {
                    $this->user = json_decode(file_get_contents($tokenfile), 1);

                    return;
                }
            }
        }

        if (! class_exists('curl')) {
            include_once(ROOT_PATH . 'lib/class/curl.class.php');
        }
        $curl = new curl($gAuthServerConfig['host'], $gAuthServerConfig['dir']);
        $curl->initPostData();
        $postdata = array(
            'appid'        => $this->input['appid'],
            'appkey'       => $this->input['appkey'],
            'access_token' => $this->input['access_token'],
            'mod_uniqueid' => MOD_UNIQUEID,
            'app_uniqueid' => APP_UNIQUEID,
            'a'            => 'get_user_info',
        );

        foreach ($postdata as $k => $v) {
            $curl->addRequestData($k, $v);
        }
        if ($this->getUserExtendInfo) {
            $curl->addRequestData('isextend', 1);
        }
        $ret = $curl->request('get_access_token.php');
        //判定终端是否需要登录授权
        if ($ret['ErrorCode'] && $this->input['m2o_ckey'] != CUSTOM_APPKEY) {
            $this->errorOutput($ret['ErrorCode']);
        } else {
            $this->user = $ret[0];
        }
        if (! $this->input['_outercall'] && $this->input['m2o_ckey'] == CUSTOM_APPKEY) {
            $this->user['group_type'] = 1;
        }
        if ($this->settings['openlogincache']) {
            // 先把缓存先到 redis，不成功则写到本地文件
            if (! $this->set_login_cache_to_redis($key, json_encode($this->user), $token_expire)) {
                @file_put_contents($tokenfile, json_encode($this->user));
            }
        }
    }

}

/**
 * 核心基类扩展 包含跨应用共享方法
 *
 * @author develop_tong
 */
abstract class appCommonFrm extends coreFrm {

    protected $user              = array();
    protected $getUserExtendInfo = FALSE;

    function __construct()
    {
        parent::__construct();

        if (defined('INITED_APP') && ! INITED_APP) {
            $this->errorOutput('NOT_INITED');
        } elseif (! defined('INITED_APP')) {
            $this->errorOutput('NOT_INITED');
        }
        if (! defined('WITH_LOGIN') || WITH_LOGIN) {
            $this->initUserInfo();
        }
    }

    protected function initUserInfo()
    {
        $this->verifyToken();
    }

    function __destruct()
    {
        //NULL
        parent::__destruct();
    }

    protected function verify_setting_prms()
    {
        if ($this->user['group_type'] <= MAX_ADMIN_TYPE) {
            return;
        }
        if (! $this->user['prms']['app_prms'][APP_UNIQUEID]['setting']) {
            $this->errorOutput(NO_PRIVILEGE);
        }
    }

    /**
     * 获取广告
     */
    protected function getAds($group = '', $arcinfo = array(), $colid = '')
    {
        $ads = array();
        if (! $group) {
            return $ads;
        }
        include_once(ROOT_PATH . 'lib/class/curl.class.php');
        $curl = new curl($this->settings['App_adv']['host'], $this->settings['App_adv']['dir']);
        $curl->initPostData();
        $curl->addRequestData('group', $group);
        $curl->addRequestData('html', 1);
        $curl->addRequestData('colid', $colid);
        if ($arcinfo) {
            $arcinfo['app_uniqueid'] = APP_UNIQUEID;
            $curl->addRequestData('vinfo', json_encode($arcinfo));
        }
        $ret = $curl->request('ad.php');
        if (is_array($ret)) {
            foreach ($ret as $ad) {
                $ads[$ad['name']][] = array(
                    'title'    => $ad['title'],
                    'brief'    => $ad['brief'],
                    'link'     => $ad['link'],
                    'material' => $ad['material'],
                    'type'     => $ad['mtype'],
                    //'flag'=>$ad['name'],
                    'param'    => $ad['param']['pos'],
                );
            }
        }

        return $ads;
    }

    /**
     * 验证会员访问权限
     *
     * @param unknown $data
     * @param string  $skip
     */
    public function verify_member_purview($data = array(), $skip = FALSE)
    {
        $action       = $data['_action'] ? $data['_action'] : $this->input['a'];
        $access_token = $data['access_token'];
        if ($this->user['user_id'] < 1) {
            $this->errorOutput(USER_NOT_LOGIN);
        }
        if ($this->settings['App_members']) {
            $this->curl = new curl($this->settings['App_members']['host'], $this->settings['App_members']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('a', 'purview');
            $this->curl->addRequestData('access_token', $access_token);
            $this->curl->addRequestData('operation', 'members_login_login');
            $ret = $this->curl->request('member_purview.php');
        }
        if (! $ret['allow']) {
            $this->errorOutput(NO_PRIVILEGE);
        }
    }

    /**
     * 验证内容权限公共方法 主模块使用的方法
     */
    protected function verify_content_prms($data = array(), $skip = FALSE)
    {
        //$data['nodes'] = array('nodevar'=>array('id'=>'parents'));
        if ($this->user['group_type'] <= MAX_ADMIN_TYPE) {
            return;
        }

        $action = $data['_action'] ? $data['_action'] : $this->input['a'];
        if (! $action) {
            $action = 'show';
        }
        if ($this->user['user_id'] < 1) {
            $this->errorOutput(USER_NOT_LOGIN);
        }
        if (! in_array($action, (array)$this->user['prms']['app_prms'][APP_UNIQUEID]['action'])) {
            $this->errorOutput(NO_PRIVILEGE);
        }
        if ($data['id']) {
            $manage_other_data = $this->user['prms']['default_setting']['manage_other_data'];
            //$this->errorOutput($manage_other_data);
            if (! $manage_other_data) {
                if ($this->user['user_id'] != $data['user_id']) {
                    $this->errorOutput(NO_PRIVILEGE);
                }
            }
            //1 代表组织机构以内
            if ($manage_other_data == 1 && $this->user['slave_org']) {
                if (! in_array($data['org_id'], explode(',', $this->user['slave_org']))) {
                    $this->errorOutput(NO_PRIVILEGE);
                }
            }
        }
        if ($skip) {
            return;
        }
        //验证节点权限
        //$this->errorOutput(var_export($this->mPrmsMethods[$action]['node'],1));
        if ($data['nodes']) {
            if (! $auth_nodes = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']) {
                $this->errorOutput(NO_PRIVILEGE);
            }
            if (is_array($auth_nodes) && $auth_nodes[0] != -1) {
                //节点权限判定 支持多节点
                foreach ($data['nodes'] as $node_id => $node_par) {
                    if (array_search($node_id, $auth_nodes)) {
                        continue;
                    }
                    $node_array = explode(',', $node_par);
                    if (! array_intersect($node_array, $auth_nodes)) {
                        $this->errorOutput(NO_PRIVILEGE);
                    }
                }
            }
        }
        if ($data['column_id'] || $data['published_column_id']) //验证发布权限
        {
            if (! class_exists('publishconfig')) {
                include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            }
            $this->publish_column = new publishconfig();
            /*
              if(!$data['column_id'] && !$data['published_column_id'])
              {
              return;
              }
             */
            $result = insert_update_delete($data['column_id'], $data['published_column_id'], 'string');
            //$this->errorOutput(var_export($result,1));
            $_column_ids = '';
            if ($result['insert'] || $result['delete']) {
                $_column_ids = ($_t = array_merge((array)$result['insert'], (array)$result['delete'])) ? $_t : '';
            }
            //$this->errorOutput(var_export($this->user['prms']['site_prms'],1));
            //$this->errorOutput(var_export($_column_ids,1));
            if ($_column_ids) {
                //全站权限 则排除需要检测的栏目
                if (in_array('-1', $this->user['prms']['site_prms'])) {
                    return; //全部站点的全部授权
                }
                $column_site = $this->publish_column->get_column_site(array('column_id' => implode(',', $_column_ids)));
                if (is_array($column_site) && $column_site) {
                    foreach ($column_site as $site_id => $col) {
                        if (in_array($site_id, $this->user['prms']['site_prms'])) {
                            $_column_ids = array_diff($_column_ids, $col);
                        }
                    }
                }
            }
            if (! $_column_ids) {
                return;
            }
            $_column_ids    = implode(',', $_column_ids);
            $column_parents = $this->publish_column->get_column_by_ids('id,parents', $_column_ids);
            $auth_columns   = $this->user['prms']['publish_prms'];
            if (! $auth_columns) {
                //return;
                $this->errorOutput(NO_PRIVILEGE);
            }
            foreach ($column_parents as $column_id => $column_array) {
                $column = explode(',', $column_array['parents']);
                if (! array_intersect($column, $auth_columns)) {
                    $this->errorOutput(NO_PRIVILEGE);
                }
            }
        }
    }

    //获取模块字典信息
    public function __getModelDict($model_name = '', $return = FALSE)
    {
        @include(CUR_CONF_PATH . 'conf/dict.conf.php');
        $model = $this->input['model_name'] ? $this->input['model_name'] : $model_name;
        if ($dict[$model] && is_array($dict[$model]) && ! $return) {
            $this->addItem($dict[$model]);
        }
        if ($return) {
            return $dict[$model];
        }
        $this->setXmlNode('dicts', 'item');
        $this->output();
    }

    //工作量统计
    public function addStatistics($statistics_data)
    {
        @include_once(ROOT_PATH . 'lib/class/statistic.class.php');
        if (class_exists('statistic')) {
            $statistic = new statistic();
            $statistic->insert_record($statistics_data);
        }
    }

    //还原回车站
    public function recover()
    {
        $content = $this->input['content'];
        if (! $content) {
            $this->errorOutput(EMPTY_CONTENT);
        }
        if (is_string($content)) {
            $content = json_decode($content, 1);
        }
        foreach ($content as $key => $value) {
            if (! $value) {
                continue;
            }
            if (is_array($value) && count($value) > 0) {
                foreach ($value as $table => $con) {
                    if (! $con) {
                        continue;
                    }
                    $sql   = "INSERT INTO " . DB_PREFIX . $table . " SET ";
                    $space = '';
                    $flag  = TRUE;
                    foreach ($con as $k => $v) {
                        if (in_array($k, array('expand_id', 'column_id', 'column_url'))) {
                            continue;
                        }

                        if (is_array($v)) {
                            $sql2  = "INSERT INTO " . DB_PREFIX . $table . " SET ";
                            $space = '';
                            foreach ($v as $kk => $vv) {
                                $sql2 .= $space . $kk . "='" . $vv . "'";
                                $space = ',';
                            }
                            $this->db->query($sql2);
                            $flag = FALSE;
                        } else {
                            $sql .= $space . $k . "='" . $v . "'";
                            $space = ',';
                            $flag  = TRUE;
                        }
                    }
                    if ($flag) {
                        $this->db->query($sql);
                    }
                }
            }
        }
        $this->addItem(TRUE);
        $this->output();
    }

    //获取权限设置数据状态 $s原状态
    protected function get_status_setting($action = '', $s = 0)
    {
        if (! $action) {
            return $this->settings['default_state'];
        }
        switch ($action) {
            case 'create': {
                switch ($this->user['prms']['default_setting']['create_content_status']) {
                    case 0: {
                        $status = $this->settings['default_state'];
                        break;
                    }
                    case 1: {
                        $status = 0;
                        break;
                    }
                    case 2: {
                        $status = 1;
                        break;
                    }
                }
                break;
            }
            case 'update_audit': {
                switch ($this->user['prms']['default_setting']['update_audit_content']) {
                    case 0: {
                        $status = $s;
                        break;
                    }
                    case 1: {
                        $status = 0;
                        break;
                    }
                    case 2: {
                        $status = 1;
                        break;
                    }
                    case 3: {
                        $status = 2;
                    }
                }
                break;
            }
            case 'update_publish': {
                switch ($this->user['prms']['default_setting']['update_publish_content']) {
                    case 0: {
                        $status = $s;
                        break;
                    }
                    case 1: {
                        $status = 0;
                        break;
                    }
                    case 2: {
                        $status = 1;
                        break;
                    }
                    case 3: {
                        $status = 2;
                    }
                }
                break;
            }
        }

        return $status;
    }

    //检测接口状态
    public function check_api_state()
    {
        $type   = $this->input['type'];
        $type   = $type ? $type : 'http';
        $host   = $this->input['host'];
        $dir    = $this->input['dir'];
        $file   = $this->input['file'];
        $return = $this->input['return'];
        if ($host) {
            $url = $type . '://' . $host . '/' . $dir . $file;
            $ch  = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            if ($type == 'https') {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $ret       = curl_exec($ch);
            $head_info = curl_getinfo($ch);
            curl_close($ch);
            if ($return) {
                $this->addItem($head_info['http_code']);
                $this->output();
            }

            return $head_info['http_code'];
        } else {
            if ($return) {
                $this->errorOutput(PARA_ERROR);
            }

            return FALSE;
        }
    }

    //上传外链数据缩引图
    protected function upload_indexpic()
    {
        include_once(ROOT_PATH . 'lib/class/material.class.php');
        $mat_server = new material();
        if (! $_FILES['indexpic']['error']) {
            $file['Filedata'] = $_FILES['indexpic'];
            $material         = $mat_server->addMaterial($file); //插入各类服务器
            return $material;
        }
    }

    /**
     * 排序公共方法 ...
     *
     * @param string $table_name 需要排序的表名
     * @param string $order_name 需要排序的字段名
     * @param string $key        需要索引列名
     * @param int    $reType     数据返回方式:1为返回排序的id，0为返回success(兼容)
     */
    public function drag_order($table_name, $order_name, $key = 'id', $reType = 0)
    {
        $content_ids = explode(',', $this->input['content_id']);
        $order_ids   = explode(',', $this->input['order_id']);
        foreach ($content_ids as $k => $v) {
            $sql = "UPDATE " . DB_PREFIX . $table_name . "  SET " . $order_name . " = '" . $order_ids[$k] . "'  WHERE {$key} = '" . $v . "'";
            $this->db->query($sql);
        }

        return $reType ? $content_ids : 'success';
    }

    /**
     * 查看平台及平台下可用的用户
     */
    public function get_plat_and_users()
    {
        include_once(ROOT_PATH . 'lib/class/share.class.php');
        $this->share = new share();
        $plat_type   = $this->share->get_type($this->user['appid']);
        $user        = $this->share->get_user_list();
        $plat_user   = array();
        if (is_array($user) && count($user) > 0) {
            foreach ($user as $k => $v) {
                $plat_user[$v['platId']][] = $v;
            }
        }

        return array('plat_type' => $plat_type, 'plat_user' => $plat_user);
    }

    /**
     * 查看平台下可用的用户
     *
     * @param intval $platid
     */
    public function show_plat_user()
    {
        $platid = intval($this->input['platid']);
        if (! $platid) {
            $this->errorOutput('平台ID不能为空');
        }
        include_once(ROOT_PATH . 'lib/class/share.class.php');
        $this->share = new share();
        $user        = $this->share->get_user_list();
        if (is_array($user) && count($user) > 0) {
            foreach ($user as $k => $v) {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    /**
     * 请求平台授权地址
     *
     * @param int $platid 平台id
     * @return array $plat 系统token及平台授权地址信息
     */
    public function request_auth()
    {
        $platid            = intval($this->input['platid']);
        $access_plat_token = $this->input['token'];
        $uid               = $this->input['uid'];
        include_once(ROOT_PATH . 'lib/class/share.class.php');
        $this->share = new share();
        $plat        = $this->share->oauthlogin($platid, $access_plat_token, $this->user['appid']);
        $plat        = $plat[0];
        $plat['url'] = $plat['sync_third_auth'] . '?oauth_url=' . $plat['oauth_url'] . '&access_plat_token=' . $plat['access_plat_token'] . "&other=1&access_token=" . $this->user['token'] . "&uid=" . $uid;
        $this->addItem($plat);
        $this->output();
    }

    public function share_form()
    {
        $content_id = intval($this->input['id']);
        include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
        $this->publish = new publishcontent();
        $info          = $this->publish->get_content(array('content_id' => $content_id));
        $info          = $info[0];
        if (empty($info)) {
            $this->errorOutput('此内容未发布');
        }
        $title    = $info['title'];
        $content  = $info['title'] . ' ' . $info['content_url'];
        $indexpic = $info['indexpic'];
        $plat     = $this->get_plat_and_users();

        $con = $this->publish->get_content_by_rid($content_id);
        $ret = array('title' => $title, 'content' => $content, 'con' => $con['content'], 'pic' => $indexpic, 'plat_type' => $plat['plat_type'], 'plat_user' => $plat['plat_user']);

        $this->addItem($ret);
        $this->output();
    }

    /**
     * 分享

     */
    public function share()
    {
        if (is_array($this->input['users']) && count($this->input['users']) > 0) {
            $platid = $plat_type = $access_plat_token = $section = array();
            foreach ($this->input['users'] as $v) {
                $v                   = json_decode(htmlspecialchars_decode($v), 1);
                $platid[]            = $v['platId'];
                $plat_type[]         = $v['plat_type'];
                $access_plat_token[] = $v['token'];
                $section[]           = $v['section_id'];
            }
        } else {
            $this->errorOutput('请选择用户');
        }
        $appid   = intval($this->user['appid']);
        $content = $this->input['content'];
        $pic     = $this->input['pic'];
        $title   = $this->input['title'];
        //		if(strlen($content) > 140)
        //		{
        //			$this->errorOutput('分享内容不得大于140个字');
        //		}
        include_once(ROOT_PATH . 'lib/class/share.class.php');
        $this->share = new share();
        if (is_array($platid) && count($platid) > 0) {
            foreach ($platid as $k => $v) {
                $ret = $this->share->toshare($appid, $platid[$k], $plat_type[$k], $access_plat_token[$k], $content, $pic, $title, $section[$k]);
                $this->addItem($ret);
            }
        }
        $this->output();
    }

    public function move_form()
    {
        $content_id = $this->input['id'];
        $nodevar    = $this->input['nodevar'];
        if (! $content_id || ! $nodevar) {
            $this->errorOutput('NO NODEVAR');
        }
        $return = array('content_id' => $content_id, 'nodevar' => $nodevar);
        $this->addItem($return);
        $this->output();
    }

    /**
     * @param        如果间隔使用参数        ,不使用位置''代替,以防止传参出错.
     * @param string $catalog_action 操作方法
     * @param string $id             内容id,例如:1,2,3
     * @param Array  $input          表单内容
     * @param Array  $files          文件上传表单内容
     * @param String $catalog_field  编目标识
     * @param String $catalog_sort   分类标识
     * @param String $table          表名,更新应用冗余数据使用.
     * @param String $bak_data       使用编目的应用主表查出的catalog字段冗余数据.
     * @param string $sql            SQL语句,删除已添加到数据库的内容.防止用户未填写必填项
     */
    public function catalog($catalog_action, $id = '', $table = '', $bak_data = '', $catalog_field = '', $catalog_sort = '', $sql = '')
    {
        $input = $this->input;
        $files = $_FILES;
        if (! $this->settings['App_catalog']) {
            return FALSE;
        }
        @include_once(ROOT_PATH . 'lib/class/catalog.class.php');
        if (class_exists('catalog')) {
            $ts = new catalog();
            switch ($catalog_action) {
                case 'show' :
                    return $ts->$catalog_action($id);
                    break;
                case 'stofield' :
                    return $ts->$catalog_action($id, $catalog_sort);
                    break;
                case 'ftofield' :
                    return $ts->$catalog_action($id, $catalog_field);
                    break;
                case 'field' :
                    return $ts->$catalog_action($id);
                    break;
                case 'sort' :
                    return $ts->$catalog_action($id);
                    break;
                case 'detail' :
                    return $ts->$catalog_action($id);
                    break;
                case 'create' :
                    $re = $ts->$catalog_action($id, $input, $files);
                    if (! empty($re[0]['required'])) {
                        if ($sql)
                            $this->db->query($sql);//如果为必填项则删除已经在应用里添加的内容
                        if (is_array($re[0]['required'])) {
                            $this->errorOutput('编目选项:' . implode(',', $re[0]['required']) . '均为必填项');
                        } else {
                            $this->errorOutput('编目选项:' . $re[0]['required'] . '均为必填项');
                        }
                    }
                    if (! empty($re['result']) && is_array($re['result']) && ! empty($table)) {
                        foreach ($re['result'] as $k => $v) {
                            if ($v['bak'] == 1) {
                                $k               = trim(str_ireplace($re['catalog_prefix'], '', $k));//去掉前缀
                                $catalog_bak[$k] = array(
                                    'zh_name' => $v['zh_name'],
                                    'value'   => $v['value']);
                                //$catalog_bak[$k] = $v['value'];
                            }
                        }
                        $catalog_bak  = addslashes(serialize($catalog_bak));
                        $data_catalog = array('catalog' => $catalog_bak);
                        $where        = "id=" . $id;
                        $this->db->update_data($data_catalog, $table, $where);
                    }

                    return $re;
                    break;
                case 'update' :
                    $catalogdel = $this->input['catalogdel'];
                    $re         = $ts->$catalog_action($id, $input, $files);
                    if (! empty($re[0]['required'])) {
                        if ($sql)
                            $this->db->query($sql);//如果为必填项则删除已经在应用里添加的内容
                        if (is_array($re[0]['required'])) {
                            $this->errorOutput('编目选项:' . implode(',', $re[0]['required']) . '均为必填项');
                        } else {
                            $this->errorOutput('编目选项:' . $re[0]['required'] . '均为必填项');
                        }
                    }
                    $bak_data = unserialize($bak_data);
                    if ($catalogdel)// 如果冗余数据存在,unset掉被删除的冗余数据.
                    {
                        $catalogdel = explode(',', $catalogdel);
                        if ($bak_data) {
                            foreach ($catalogdel as $key) {
                                $key = trim(str_ireplace($re['catalog_prefix'], '', $key));//去掉前缀
                                if ($bak_data[$key]) {
                                    unset($bak_data[$key]);
                                }
                            }

                        }
                    }
                    $catalog_bak = array();
                    if (! empty($re['result']) && is_array($re['result']))//如果允许冗余数据有返回并且已冗余数据存在,则合并
                    {
                        foreach ($re['result'] as $k => $v) {
                            if ($v['bak'] == 1) {
                                $k               = trim(str_ireplace($re['catalog_prefix'], '', $k));//去掉前缀
                                $catalog_bak[$k] = array(
                                    'zh_name' => $v['zh_name'],
                                    'value'   => $v['value']);
                            }
                        }
                    }
                    $where = "id=" . $id;
                    if (empty($catalog_bak)) {
                        $catalog_null = array('catalog' => NULL);
                        $this->db->update_data($catalog_null, $table, $where);
                    } else {
                        $catalog_bak  = addslashes(serialize($catalog_bak));
                        $data_catalog = array('catalog' => $catalog_bak);
                        $this->db->update_data($data_catalog, $table, $where);
                    }

                    return $re;
                    break;
                case 'delete' :
                    return $ts->$catalog_action($id, $catalog_field);
                    break;
                default:
                    return '此方法不存在';
                    break;
            }
        }
    }

    function advanced_search_form()
    {
        $data = array();
        if ($this->settings['App_settings']) {
            $this->curl = new curl($this->settings['App_settings']['host'], $this->settings['App_settings']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('a', 'show');
            $ret            = $this->curl->request('weight.php');
            $data['weight'] = $ret;
        }
        $this->addItem($data);
        $this->output();
    }

    /**
     * 取用户保存的搜索标签
     **/
    function get_searchtag()
    {
        if ($this->settings['App_searchtag']) {
            $this->curl = new curl($this->settings['App_searchtag']['host'], $this->settings['App_searchtag']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('a', 'show');
            $this->curl->addRequestData('app_uniqueid', APP_UNIQUEID);
            $this->curl->addRequestData('mod_uniqueid', MOD_UNIQUEID);
            $ret = $this->curl->request('searchtag.php');
            $this->addItem($ret[0]);
            $this->output();
        }
    }

    /**
     * 保存用户的搜索标签
     * title  标签名称  同用户同模块不允许重复
     * tag_val  搜索条件  json字符串
     */
    function save_searchtag()
    {
        if ($this->settings['App_searchtag']) {
            $this->curl = new curl($this->settings['App_searchtag']['host'], $this->settings['App_searchtag']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('a', 'create');
            $this->curl->addRequestData('title', $this->input['title']);
            $this->curl->addRequestData('tag_val', $this->input['tag_val']);
            $this->curl->addRequestData('app_uniqueid', APP_UNIQUEID);
            $this->curl->addRequestData('mod_uniqueid', MOD_UNIQUEID);
            $ret = $this->curl->request('searchtag.php');
            $this->addItem($ret[0]);
            $this->output();
        } else {
            $this->errorOutput('NO_SEARCHTAG');
        }
    }

    /*
     * 删除搜索标签
     *
     * id  标签id
     */
    function delete_searchtag()
    {
        if ($this->settings['App_searchtag']) {
            $this->curl = new curl($this->settings['App_searchtag']['host'], $this->settings['App_searchtag']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('a', 'delete');
            $this->curl->addRequestData('id', $this->input['id']);
            $this->curl->addRequestData('app_uniqueid', APP_UNIQUEID);
            $this->curl->addRequestData('mod_uniqueid', MOD_UNIQUEID);
            $ret = $this->curl->request('searchtag.php');
            $this->addItem($ret[0]);
            $this->output();
        }
    }

    /**
     *  标签详情
     *
     * @param $id int  标签id
     * @return array() 标签详细信息
     */
    function searchtag_detail($id)
    {
        if ($this->settings['App_searchtag']) {
            $this->curl = new curl($this->settings['App_searchtag']['host'], $this->settings['App_searchtag']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('a', 'detail');
            $this->curl->addRequestData('id', $id);
            $this->curl->addRequestData('app_uniqueid', APP_UNIQUEID);
            $this->curl->addRequestData('mod_uniqueid', MOD_UNIQUEID);
            $ret = $this->curl->request('searchtag.php');

            return $ret[0];
        }
    }


}

abstract class xsFrm extends appCommonFrm {

    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        //NULL
        parent::__destruct();
    }

    /**
     * type为增改删的接收参数
     * add:     $data    =   array(
     * 'pid'     =>   234, // 此字段为主键，必须指定
     * 'subject' =>   '测试文档的标题',
     * 'message' =>   '测试文档的内容部分',
     * 'chrono'  =>   time()
     * );
     * update:  $data    =   array(
     * 'pid'     =>   234, // 此字段为主键，必须指定
     * 'subject' =>   '测试文档的标题',
     * 'message' =>   '测试文档的内容部分',
     * 'chrono'  =>   time()
     * );               (根据主键没有查到有此内容会自动增加此内容)
     * del:  $data = '123';   一个主键id
     * $data = array('123', '789', '456');多个主键id
     * clean:  清空索引，后需重新加入文档数据
     * rebuild: 平滑重建索引
     */
    protected function xs_index($data, $filename, $type = 'add')
    {
        if (! $this->settings['App_textsearch']) {
            return FALSE;
        }
        @include_once(ROOT_PATH . 'lib/class/textsearch.class.php');
        if (class_exists('textsearch')) {
            $ts = new textsearch();

            return $ts->index($data, $type);
        }
    }

    /**
     *  $array_field      数组的字段
     *  $highlight_field  需要高亮的字段
     *  搜索语句最大支持长度为 80 字节（每一个汉字占 3 字节）
     *    addDB($name) - 用于多库搜索，添加数据库名称
     * addRange($field, $from, $to) - 添加搜索过滤区间或范围
     * addWeight($field, $term) - 添加权重索引词
     * setCharset($charset) - 设置字符集
     * setCollapse($field, $num = 1) - 设置搜索结果按字段值折叠
     * setDb($name) - 设置搜索库名称，默认则为 db
     * setFuzzy() - 设置开启模糊搜索, 传入参数 false 可关闭模糊搜索
     * setLimit($limit, $offset = 0) - 设置搜索结果返回的数量和偏移
     * setQuery() - 设置搜索语句
     * setSort($field, $asc = false) - 设置搜索结果按字段值排序
     * setFacets 第一参数为要分面的字段名称（多个字段请用数组作参数）， 第二参数是可选的布尔类型，true 表示需要准确统计，默认 false 则为估算
     * getFacets 返回数组，以 fid 为键，匹配数量为值
     * $searchdata = array(
     * 'charset'      =>      'utf-8',  //设置返回的字符编码
     * 'query'        =>      'bundle_id:(tuji) AND client_type:(2) OR column_id:(4) NOT site_id:(1) XOR 西湖', //查询语句
     * 'fuzzy'        =>       1为true 0为false,//模糊查询为true否则false
     * 'limit'        =>       'count,offset',
     * 'range'           =>       array('publish_time'=>'1343432345,1463784752','create_time'=>'1343432345,1463784752'),//值的范围
     * 'sort'         =>        array('id'=>true,'weight'=>false),  //k为字段，false表示降序，true表示升序
     * 'weight'       =>       array('title'=>'你好','brief'=>'好'),// 增加附加条件：提升标题中包含 'xunsearch' 的记录的权重
     * 'autosynonyms' =>        1为true 0为false,  //设为 true 表示开启同义词功能, 设为 false 关闭同义词功能
     * 'setfacets_fields' =>   array('field'=>array('fid','year'),'count_type'=>0或1), 该方法接受两个参数，第一参数为要分面的字段名称（多个字段请用数组作参数）， 第二参数是可选的布尔类型，1 表示需要准确统计，默认 0 则为估算
     * 'hotquery'     =>       array('limit'=>10,'type'=>'total'or'lastnum'or'currnum'),$limit 整数值，设置要返回的词数量上限，默认为 6，最大值为 50;$type 指定排序类型，默认为 total(总量)，可选值还有：lastnum(上周) 和 currnum(本周)
     * );
     * */
    public function xs_search($searchdata, $filename, $array_field = array(), $highlight_field = array())
    {
        if (! $this->settings['App_textsearch']) {
            return FALSE;
        }
        @include_once(ROOT_PATH . 'lib/class/textsearch.class.php');
        if (class_exists('textsearch')) {
            $ts = new textsearch();

            return $ts->search($searchdata, $array_field, $highlight_field);
        }
    }

    /**
     * 取文本的关键词
     * limit 默认取10个
     * xattr   条件：在返回结果的词性过滤, 多个词性之间用逗号分隔, 以~开头取反
     *            如: 设为 n,v 表示只返回名词和动词; 设为 ~n,v 则表示返回名词和动词以外的其它词
     * return 返回词汇数组, 每个词汇是包含 [times:次数,attr:词性,word:词]
     * */
    public function xs_get_keyword($text, $limit = 10, $xattr = '')
    {
        if (! $this->settings['App_textsearch']) {
            return array(
                'errmsg' => '迅搜未安装',
            );
        }
        @include_once(ROOT_PATH . 'lib/class/textsearch.class.php');
        if (class_exists('textsearch')) {
            $ts  = new textsearch();
            $ret = $ts->get_keyword($text, $limit, $xattr);
            if (empty($ret)) {
                return array(
                    'errormsg' => '未取到关键字',
                );
            }

            return $ret;
        }
    }

    /**
     * 取文本的分词
     * $text 待分词的文本
     * return 返回词汇数组, 每个词汇是包含 [off:词在文本中的位置,attr:词性,word:词]
     * */
    public function xs_getResult($text)
    {
        if (! $this->settings['App_textsearch']) {
            return FALSE;
        }
        @include_once(ROOT_PATH . 'lib/class/textsearch.class.php');
        if (class_exists('textsearch')) {
            $ts = new textsearch();

            return $ts->get_Result($text);
        }
    }

    /**
     * 给需要搜索的标题进行分词后格式转换
     * 如标题：纠结“单独二胎” 折射理性生育观
     * 转换后：7ea07ed3 535572ec 4e8c 4e8c80ce 62985c04 74066027 751f80b2 89c2
     *      ：纠结      单独    二
     */
    public function get_titleResult($title)
    {
        $title_result = $this->xs_getResult($title);
        if ($title_result && is_array($title_result)) {
            foreach ($title_result as $tr_v) {
                $title_resultstr .= $tr_tag . $tr_v['word'];
                $tr_tag = ',';
            }
            $title = str_utf8_unicode($title_resultstr);
        } else {
            $title = str_utf8_unicode($title);
        }

        return $title;
    }

    public function memcache_set($key, $data, $group = '')
    {
        if (! $this->settings['App_memcached'] || ! $this->settings['is_open_memcache']) {
            return FALSE;
        }
        @include_once(ROOT_PATH . 'lib/class/memcached.class.php');
        if (class_exists('mcached')) {
            $ts = new mcached();

            return $ts->set($key, $data, $group);
        }
    }

    public function memcache_get($key, $group = '')
    {
        if (! $this->settings['App_memcached'] || ! $this->settings['is_open_memcache']) {
            return FALSE;
        }
        @include_once(ROOT_PATH . 'lib/class/memcached.class.php');
        if (class_exists('mcached')) {
            $ts = new mcached();

            return $ts->get($key, $group);
        }
    }

    public function memcache_delete($key, $group = '')
    {
        if (! $this->settings['App_memcached'] || ! $this->settings['is_open_memcache']) {
            return FALSE;
        }
        @include_once(ROOT_PATH . 'lib/class/memcached.class.php');
        if (class_exists('mcached')) {
            $ts = new mcached();

            return $ts->get($key, $group);
        }
    }

    public function memcache_flush($group = '')
    {
        if (! $this->settings['App_memcached'] || ! $this->settings['is_open_memcache']) {
            return FALSE;
        }
        @include_once(ROOT_PATH . 'lib/class/memcached.class.php');
        if (class_exists('mcached')) {
            $ts = new mcached();

            return $ts->flush($group);
        }
    }

}

/**
 * 兼容旧版本
 *
 * @author develop_tong
 */
abstract class BaseFrm extends appCommonFrm {

    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        //NULL
        parent::__destruct();
    }

}

/**
 * 计划任务基类
 *
 * @author develop_tong
 */
abstract class cronBase extends appCommonFrm {

    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        //NULL
        parent::__destruct();
    }

    abstract public function initcron();
}

/**
 * 管理程序读基类
 *
 * @author develop_tong
 */
abstract class adminBase extends xsFrm {

    function __construct()
    {
        parent::__construct();
    }

    function __destruct()
    {
        //NULL
        parent::__destruct();
    }

}

abstract class adminReadBase extends adminBase {

    //append方法也定义在这个类
    abstract public function show();

    abstract public function detail();

    abstract public function count();

    abstract public function index();
}

/**
 * 管理程序写基类
 *
 * @author develop_tong
 */
abstract class adminUpdateBase extends adminBase {

    abstract public function create();

    abstract public function update();

    abstract public function delete();

    abstract public function audit();

    abstract public function sort();

    abstract public function publish();
}

/**
 * 外部程序读基类
 *
 * @author develop_tong
 */
abstract class outerReadBase extends xsFrm {

    abstract public function show();

    abstract public function detail();

    abstract public function count();
}

/**
 * 外部程序写基类
 *
 * @author develop_tong
 */
abstract class outerUpdateBase extends appCommonFrm {

    abstract public function create();

    abstract public function update();

    abstract public function delete();
}

// //实例化基类，用于未继承到appCommonFrm类的类
// class instantB extends appCommonFrm
// {
// 	function __construct()
// 	{
// 		parent::__construct();
// 	}
// 	function __destruct()
// 	{
// 		parent::__destruct();
// 	}
// }