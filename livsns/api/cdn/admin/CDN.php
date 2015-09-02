<?php

/* * *****************************************************************
 * filename :CDN.php
 * Created  :2013年8月9日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 * **************************************************************** */
define('MOD_UNIQUEID', 'cdn'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class CDNAPi extends adminBase
{

    private $obj = null;

    public function __construct()
    {
        parent::__construct();
        $this->mPrmsMethods = array(
            'show' => '查看',
        );
        $this->obj          = new Core();
    }

    public function detail()
    {
        $id     = intval($this->input['id']);
        $tbname = 'cdn_log';

        if ($id)
            $data_limit = 'where id=' . $id;
// 		else
// 			$data_limit = ' LIMIT 1';

        $info = $this->obj->detail($tbname, $data_limit);

        if (!$info)
            $this->errorOutput(NO_DATA_EXIST);

        $this->addItem($info);
        $this->output();
    }

    public function show()
    {
        $tbname                = 'cdn_log';
        $this->input['tbname'] = 'cdn_log';
        $condition             = $this->get_condition();
        $offset                = $this->input['offset'] ? $this->input['offset'] : 0;
        $count                 = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit            = $condition . ' order by id desc LIMIT ' . $offset . ' , ' . $count;

        $datas  = $this->obj->show($tbname, $data_limit, $fields = '*');
        //$datas = array('age'=>11,'gender'=>'female');
        foreach ($datas as $k => $v)
        {
            $$k = $v;
            $this->addItem($$k);
        }
        //$this->addItem($datas);
        $this->output();
    }

    public function count()
    {
        $condition = $this->get_condition();
        $tbname    = 'cdn_log';
        $info      = $this->obj->count($tbname, $condition);
        echo json_encode($info);
    }

    public function index()
    {
        
    }

    private function get_condition()
    {

        //$cond = " where 1 and state=0";
        $cond = " where 1 ";
        return $cond;
    }

    public function register_upyun_user()
    {
        include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');

        $data  = array(
            'username' => UpYun_Username,
            'password' => UpYun_Password,
            'email' => UpYun_Email,
            'account_type' => UpYun_AccountType,
            'real_name' => UpYun_RealName,
            'company_name' => UpYun_CompanyName,
            'mobile' => UpYun_Mobile,
            'client_id' => OAUTH_CLIENT_ID,
            'client_secret' => OAUTH_CLIENT_SECRET,
        );
        $oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET, OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
        $info  = $oauth->request('/accounts/', 'PUT', $data);

        if ($info['error'])
        {
            $this->errorOutput($info['message']);
        }

        $this->addItem('ture');
        $this->output();
    }

    public function get_upyun_access_token()
    {
        include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');

        $data  = array(
            'username' => UpYun_Username,
            'password' => UpYun_Password,
            'grant_type' => 'password',
            'client_id' => OAUTH_CLIENT_ID,
            'client_secret' => OAUTH_CLIENT_SECRET,
        );
        $oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET, OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
        $info  = $oauth->request('/oauth/access_token/', 'POST', $data);
        if ($info['access_token'])
        {
            $upyun_str = serialize($info);

            $returnstr = "<?php\r\n";
            $returnstr .= "\$upyun_info = array(";
            $returnstr .= "'upyun'  => " . "'" . $upyun_str . "',";
            $returnstr .= ");\r\n?>";

            $filename = 'upyun.php';
            $name     = '../cache/' . $filename;
            file_put_contents($name, $returnstr);
        }
        else
        {
            $this->errorOutput($info['message']);
        }
    }

}

$out    = new CDNAPi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>