<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-7-31
 * Time: 上午11:15
 */
require('global.php');
define('MOD_UNIQUEID','topic');
class nodeApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){}

    public function __destruct() {
        parent::__destruct();
    }

    public function show()
    {
        if ($this->settings['App_live'])
        {
            include_once(ROOT_PATH . 'lib/class/live.class.php');
            $this->live = new live();
            $channels = $this->live->getChannel();
            $authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
            $authnode = is_array($authnode) ? $authnode : explode(',', $authnode) ;
            foreach ((array)$channels as $key => $val)
            {
                if ($val)
                {
                    ####增加权限控制 用于显示####
                    if($this->user['group_type'] > MAX_ADMIN_TYPE)
                    {
                        if (!in_array($val['id'], $authnode))
                        {
                            continue;
                        }
                    }
                    $channel = array(
                        'id'     => $val['id'],
                        'name'   => $val['name'],
                        'fid'    => 0,
                        'depath' => 1,
                        'is_last'=> 1,
                    );
                    $this->addItem($channel);
                }
            }
            $this->output();
        }
        else
        {
            $this->errorOutput('NOT INSTALLED LIVE');
        }
    }

    public function detail()
    {
    }


    public function count()
    {

    }
}

$out = new nodeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action)) {
    $action = 'show';
}
$out->$action();

/* End of file topic.php */
