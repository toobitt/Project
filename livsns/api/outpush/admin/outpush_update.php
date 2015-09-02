<?php
define('MOD_UNIQUEID', 'outpush');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/outpush_mode.php');

class outpush_update extends adminUpdateBase {

    private $mode;

    public function __construct()
    {
        parent::__construct();
        include_once(ROOT_DIR . 'lib/class/auth.class.php');
        $this->auth = new auth();
        $this->mode = new outpush_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create()
    {
    }

    public function update()
    {
        if ($_REQUEST['menu'])//判断新增menu
        {
            if (! $_REQUEST['access_token']) {
                $this->errorOutput(NOLOGIN);
            }
            $auth    = $this->auth->getUserExtendInfo($_REQUEST['access_token']);
            $user_id = $auth['user_id'];
            $arr     = explode('*', $_REQUEST['menu']);
            $data    = array(
                'user_id'     => $user_id,
                'appid'       => $arr[0],
                'name'        => $arr[1],
                'create_time' => TIMENOW,
                'update_time' => TIMENOW,
                'ip'          => $_REQUEST['lpip'] ? $_REQUEST['lpip'] : '',
            );
            $ret     = $this->mode->create($data);
            if ($ret) {
                $this->addItem('success');
                $this->output();
            }

        } else {
            if (! $this->input['id']) {
                $this->errorOutput(NOID);
            }

            if (! isset($this->input['status'])) {
                $this->errorOutput(NOSTATUS);
            }
            if ($this->input['status'] == 1) {
                $status = 0;
            } else {
                $status = 1;
            }
            $update_data = array(
                'status'      => $status,
                'update_time' => TIMENOW,
            );
            $ret         = $this->mode->update($this->input['id'], $update_data);
            if ($ret) {
                $this->addItem('success');
                $this->output();
            }
        }
    }

    public function delete()
    {
        if (! $this->input['id']) {
            $this->errorOutput(NOID);
        }

        $ret = $this->mode->delete($this->input['id']);
        if ($ret) {
            $this->addItem('success');
            $this->output();
        }
    }

    public function audit()
    {
        if (! $this->input['id']) {
            $this->errorOutput(NOID);
        }
        $ret = $this->mode->audit($this->input['id']);
        if ($ret) {
            $this->addItem($ret);
            $this->output();
        }
    }

    public function form()
    {
        $this->mode->form();
    }

    public function sort()
    {
    }

    public function publish()
    {
    }

    public function unknow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new outpush_update();
if (! method_exists($out, $_INPUT['a'])) {
    $action = 'unknow';
} else {
    $action = $_INPUT['a'];
}
$out->$action();
?>