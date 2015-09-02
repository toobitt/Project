<?php
define('MOD_UNIQUEID', 'appstore');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH . 'lib/appstore_frm.php');
class index extends admin_appstore_frm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$offset = intval($this->input['offset']);
		$count = intval($this->input['count']);
		$count = $count ? $count : 100;
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'apps ORDER BY order_id DESC LIMIT ' . $offset . ',' . $count;
		$q = $this->db->query($sql);
		$all_apps = array();
		$app_uniqueids = array();
		while($r = $this->db->fetch_array($q))
		{
			$all_apps[$r['id']] = $r;
			$app_uniqueids[] = $r['app_uniqueid'];
		}
		if ($all_apps)
		{
			$apps = array();
			foreach ($all_apps AS $appid => $v)
			{
				$apps[$v['class_id']][$v['id']] = $v;
			}
		}
		$this->addItem_withkey('apps', $apps);
		$this->output();
	}

	
	public function detail()
	{
		$app = $this->input['app'];
		$sql = 'SELECT * FROM ' . DB_PREFIX . "apps WHERE app_uniqueid='$app'";
		$appinfo = $this->db->query_first($sql);
		$sql = 'SELECT * FROM ' . DB_PREFIX . "version_features WHERE app_uniqueid='$app' AND pre_release=1 ORDER BY version DESC";
		$version_features = $this->db->query_first($sql);
		$appinfo['version_features']['preversion_content'] = $version_features['content'];
		$this->addItem($appinfo);
		$this->output();
	}

	public function update_version()
	{
		$app = trim($this->input['app']);
		$version = trim($this->input['version']);		
		$pre_release = intval($this->input['pre_release']);		
		$content = trim($this->input['content']);		
		$match = preg_match('/^[0-9]+\.[0-9]+\.[0-9]+$/is', $version);
		if (!$match)
		{
			$this->errorOutput('VERSION_FORMAT_ERROR');
		}
		if ($pre_release)
		{
			$field = 'pre_version';
		}
		else
		{
			$field = 'version';
			$sql = 'SELECT * FROM ' . DB_PREFIX . "version_features WHERE app_uniqueid='$app' AND pre_release=1 ORDER BY version DESC";
			$version_features = $this->db->query_first($sql);
			$content = $version_features['content'];
		}

		$sql = 'UPDATE ' . DB_PREFIX . "apps SET $field='$version' WHERE app_uniqueid='$app'";
		$this->db->query($sql);

		$sql = 'REPLACE INTO ' . DB_PREFIX . "version_features (app_uniqueid, version, pre_release, content) VALUES ('$app', '$version', '$pre_release', '$content')";
		$this->db->query($sql);
		$sql = 'SELECT * FROM ' . DB_PREFIX . "apps WHERE app_uniqueid='$app'";
		$appinfo = $this->db->query_first($sql);
		$this->addItem($appinfo);
		$this->output();
	}
	public function get_sort()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'app_class WHERE fid=' . intval($this->input['fid']);
		$q = $this->db->query($sql);
		$sort = array();
		while($r = $this->db->fetch_array($q))
		{
			$sort[$r['id']] = $r;
		}

		$this->addItem_withkey('sort', $sort);
		$this->output();
	}

    /**
     * 修改依赖应用方法
     */
    public function update_related_app()
    {
        $app = trim($this->input['app']);
        $related_app = trim($this->input['related_app']);

        if (!$app)
        {
            $this->errorOutput('NO_APP');
        }
        if (is_array($related_app))
        {
            $related_app = implode(', ', $related_app);
        }
        $sql = "UPDATE " . DB_PREFIX . "apps SET relyonapps='" . $related_app . "' WHERE app_uniqueid='" . $app . "'";
        $this->db->query($sql);
        $sql = 'SELECT * FROM ' . DB_PREFIX . "apps WHERE app_uniqueid='$app'";
        $appinfo = $this->db->query_first($sql);
        $this->addItem($appinfo);
        $this->output();
    }

}
$module = 'index';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>