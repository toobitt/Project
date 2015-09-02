<?php
/*******************************************************************
 * filename     :Core.class.php
 * Created      :2014年01月22日, by Scala
 * Description  :
 ******************************************************************/
define('MOD_UNIQUEID', 'acp_site');
require ('global.php');
include (CUR_CONF_PATH . 'lib/Dao.class.php');
class SiteUpdateAPI extends  adminUpdateBase {
    private $Dao = null;
    private $tbname = 'site';
    public function __construct() {
        parent::__construct();
        $this -> Dao = new Dao();
    }

    private function setTitle() {
        if (!$this -> input['title']) {
            $this -> errorOutput("NO_CUSTOM_TITLE");
        }
        $this -> title = htmlentities($this -> input['title']);
    }

    private function setBrief() {
        $this -> brief = htmlentities($this -> input['brief']);
    }

    private function setUrl() {
        if (!isUrl("http://" . $this -> input['url']) && !isUrl("https://" . $this -> input['url'])) {
            $this -> errorOutput(strtoupper('illegal_url'));
        }
        $this -> url = trim($this -> input['url']);
        $this -> ip = gethostbyname($this -> input['url']);
    }

    private function setIp() {
        $ip = trim($this -> input['ip']);
        if (isIpv4($ip)) {
            if ($this -> ip == $ip)
                $this -> ip = $ip;
        }

    }

    private function setPort() {
        $this -> port = 80;
        if ($this -> input['port']) {
            $this -> port = intval($this -> input['port']);
        }
    }

    private function setCustomId() {
        $this -> custom_id = intval($this -> input['custom_id']);
        if (!$this -> custom_id) {
            $this -> errorOutput(strtoupper('no_custom_id'));
        }
    }

    private function setId() {
        $this -> id = intval($this -> input['id']);
        if (!$this -> id) {
            $this -> errorOutput(strtoupper('no_id'));
        }
    }

    public function create() {
        $this -> setBrief();
        $this -> setTitle();
        $this -> setUrl();
        $this -> setIp();
        $this -> setPort();
        $this -> setCustomId();

        $params['title'] = $this -> title;
        $params['custom_id'] = $this -> custom_id;
        $params['brief'] = $this -> brief;
        $params['url'] = $this -> url;
        $params['ip'] = $this -> ip;
        $params['port'] = $this -> port;

        $params['user_id'] = $this -> user['user_id'];
        $params['org_id'] = $this -> user['org_id'];
        $params['user_name'] = $this -> user['user_name'];
        $params['appid'] = $this -> user['appid'];
        $params['appname'] = $this -> user['display_name'];
        $params['create_time'] = TIMENOW;
        $params['id'] = $this -> Dao -> insert($this -> tbname, $params);
        $this -> addItem($params);
        $this -> output();
    }

    public function __get($name) {
        if (!$this -> $name) {
            $this -> errorOutput(strtoupper('no_attribute' . '-' . $name));
        }
        return $this -> $name;
    }

    public function update() {
        $this -> setId();
        $this -> setUrl();
        $this -> setIp();
        $this -> setPort();
        $this -> setTitle();
        $this -> setBrief();

        $cond = " WHERE `id`=" . $this -> id;
        $params['title'] = $this -> title;
        $params['brief'] = $this -> brief;
        $params['url'] = $this -> url;
        $params['ip'] = $this -> ip;
        $params['port'] = $this -> port;

        $datas = $this -> Dao -> update($this -> tbname, $params, $cond);

        $this -> addItem($datas);
        $this -> output();
    }

    public function audit() {

    }

    public function delete() {
        $this -> setId();
        $cond = " WHERE `id`=" . $this -> id;
        $re = $this -> Dao -> delete($this -> tbname, $cond);
        $this -> addItem($re);
        $this -> output();
    }

    public function index() {

    }

    private function get_condition() {
        $cond = " where 1 ";

        return $cond;
    }

    public function sort() {

    }

    public function publish() {

    }

    public function unknow() {
        $this -> errorOutput(NO_ACTION);
    }

    public function __desctruct() {

    }

}

$out = new SiteUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
