<?php
/*******************************************************************
 * filename     :Core.class.php
 * Created      :2014年01月22日, by Scala
 * Description  :
 ******************************************************************/
define('MOD_UNIQUEID', 'acp_cumstom');
require ('global.php');
include (CUR_CONF_PATH . 'lib/Dao.class.php');
include (CUR_CONF_PATH . 'lib/MyCurl.class.php');
class ConfigUpdateAPI extends  adminUpdateBase {
    private $Dao = null;
    private $tbname = 'site_config';
    public function __construct() {
        parent::__construct();
        $this -> Dao = new Dao();

    }

    private function setSiteId() {
        $this -> site_id = intval($this -> input['site_id']);
        if (!$this -> site_id) {
            $this -> errorOutput(strtoupper('no_site_id'));
        }
    }

    private function setRootPath() {
        $this -> root_path = $this -> input['root_path'];
    }

    private function setParentPath() {
        $this -> parent_path = $this -> input['parent_path'];
    }

    private function setCurrentPath() {
        $this -> current_path = $this -> input['current_path'];
    }

    private function setConfigName() {
        $this -> config_name = $this -> input['config_name'];
    }

    private function setConfigBrief() {
        $this -> config_brief = $this -> input['config_brief'];
    }

    private function setConfigContent() {

        $this -> config_content = $this -> _stripSlashes(htmlspecialchars_decode($this -> input['config_content'], ENT_QUOTES));

        eval("\$this -> config_content=" . $this -> config_content . ";");
        if (!is_array($this -> config_content)) {
            $this -> errorOutput(strtoupper('illegal_param'));
        }
    }

    /**
     * 采用stripslashes反转义特殊字符
     *
     * @param array|string $data 待反转义的数据
     * @return array|string 反转义之后的数据
     */
    private function _stripSlashes(&$data) {
        return is_array($data) ? array_map(array($this, '_stripSlashes'), $data) : stripslashes($data);
    }

    private function setId() {
        $this -> id = intval($this -> input['id']);
        if (!$this -> id) {
            $this -> errorOutput(strtoupper('no_id'));
        }
    }

    public function create() {
        $this -> setSiteId();
        $this -> setRootPath();
        $this -> setParentPath();
        $this -> setCurrentPath();
        $this -> setConfigName();
        $this -> setConfigBrief();
        $this -> setConfigContent();

        $params['site_id'] = $this -> site_id;
        $params['root_path'] = $this -> root_path;
        $params['parent_path'] = $this -> parent_path;
        $params['current_path'] = $this -> current_path;
        $params['config_name'] = $this -> config_name;
        $params['config_brief'] = $this -> config_brief;
        $params['config_content'] = urlencode(json_encode($this -> config_content));

        $params['user_id'] = $this -> user['user_id'];
        $params['user_name'] = $this -> user['user_name'];
        $params['appid'] = $this -> user['appid'];
        $params['appname'] = $this -> user['display_name'];
        $params['create_time'] = TIMENOW;
        $params['ip'] = hg_getip();

        $query = "SELECT * 
                 FROM " . DB_PREFIX . "site_config 
                 WHERE config_name='" . $params['config_name'] . "' 
                 AND current_path='" . $params['current_path'] . "' 
                 ";

        $is_exist = $this -> Dao -> query($query);
        if ($is_exist) {
            $this -> errorOutput('has_exist');
        }

        $this -> getSite();
        $re = $this -> sync();
        if (!$re) {
            $this -> errorOutput('illegal_path');
        }

        $params['id'] = $this -> Dao -> insert($this -> tbname, $params);
        $this -> addItem($params);
        $this -> output();
    }

    public function update() {
        $this -> setId();

        $cond = " WHERE `id`=" . $this -> id;
        $query = "  SELECT sc.*,s.url as site_url
                    FROM " . DB_PREFIX . "site_config sc
                    LEFT JOIN " . DB_PREFIX . "site s
                    ON sc.site_id = s.id
                    WHERE sc.id = " . $this -> id;

        $re = $this -> Dao -> query($query);

        $this -> siteinfo['url'] = $re[$this -> id]['site_url'];

        $this -> setRootPath();

        $this -> setParentPath();
        $this -> setCurrentPath();
        $this -> setConfigName();
        $this -> setConfigBrief();

        $this -> setConfigContent();

        $params['root_path'] = $this -> root_path;
        $params['parent_path'] = $this -> parent_path;
        $params['current_path'] = $this -> current_path;
        $params['config_name'] = $this -> config_name;
        $params['config_brief'] = $this -> config_brief;

        $params['config_content'] = urlencode(json_encode($this -> config_content));

        $re = $this -> sync();
        if (!$re) {
            $this -> errorOutput('illegal_path');
        }
        $datas = $this -> Dao -> update($this -> tbname, $params, $cond);
        $this -> addItem($datas);
        $this -> output();
    }

    //获取站点信息，并校验
    private function getSite() {
        $query = "SELECT * 
                  FROM " . DB_PREFIX . "site 
                  WHERE id=" . $this -> site_id;

        $result = $this -> Dao -> query($query);

        if (!$result[$this -> site_id]['port']) {
            $file_url = $result[$this -> site_id]['url'];
        } else {
            $port = $result['port'];
            $file_url = $result[$this -> site_id]['url'] . ":" . $port;
        }
        $file_url .= "/cache/config/verison";
        $re = json_decode(file_get_contents($file_url), 1);
        if ($re['version']) {
            $this -> siteinfo = $result[$this -> site_id];
            $this -> siteinfo['url'] = $file_url;
        } else {
            $this -> siteinfo = array();
        }
        $this -> siteinfo = $result[$this -> site_id];

    }

    //同步配置
    private function sync() {
        $params['dir'] = $this -> current_path;
        $params['name'] = $this -> config_name;
        $params['config'] = $this -> config_content;
        $params['is_scala'] = 'helloscala';
        $curl = new MyCurl();
        $curl -> setUrl($this -> siteinfo['url'] . "/config_do.php");
        foreach ($params as $k => $v) {
            $curl -> addParam($k, $v);
        }
        $re = $curl -> exec();
        return $re;
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

$out = new ConfigUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
