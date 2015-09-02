<?php
/*******************************************************************
 * filename     :Core.class.php
 * Created      :2014年01月22日, by Scala
 * Description  :
 ******************************************************************/
define('MOD_UNIQUEID', 'acp_cumstom');
require ('global.php');
include (CUR_CONF_PATH . 'lib/Dao.class.php');
class CustomUpdateAPI extends  adminUpdateBase {
    private $Dao = null;
    private $tbname = 'custom';
    public function __construct() {
        parent::__construct();
        $this -> Dao = new Dao();
    }

    private function setBrief() {
        $this -> title = htmlentities($this -> input['brief']);
    }

    private function setTitle() {
        if (!$this -> input['title']) {
            $this -> errorOutput("NO_CUSTOM_TITLE");
        }
        $this -> title = htmlentities($this -> input['title']);
    }

    private function setUrl() {
        if (!isUrl($this -> input['url'])) {
            $this -> errorOutput(strtoupper('illegal_url'));
        }
        $this -> url = trim($this -> input['url']);
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

        $params['title'] = $this -> title;
        $params['brief'] = $this -> brief;
        $params['url'] = $this -> url;
        $params['user_id'] = $this -> user['user_id'];
        $params['org_id'] = $this -> user['org_id'];
        $params['user_name'] = $this -> user['user_name'];
        $params['appid'] = $this -> user['appid'];
        $params['appname'] = $this -> user['display_name'];
        $params['create_time'] = TIMENOW;
        $params['id'] = $this -> Dao -> insert($this -> tbname, $params);
        $params['ip'] = hg_getip();
        $this -> addItem($params);
        $this -> output();
    }

    public function update() {
        $this -> setId();
        $params['title'] = $this -> title;
        $params['brief'] = $this -> brief;
        $params['url'] = $this -> url;
        $cond = " WHERE `id`=" . $this -> id;
        $params['title'] = $this -> title;
        $params['brief'] = $this -> brief;
        $params['url'] = $this -> url;
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

$out = new CustomUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
