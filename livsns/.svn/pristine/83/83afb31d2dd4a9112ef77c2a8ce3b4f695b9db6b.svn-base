<?php
define('MOD_UNIQUEID', 'mediaserver');
require('./global.php');

class vod extends outerReadBase
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
    }

    public function updateclick()
    {
    }

    public function detail()
    {
    }

    public function count()
    {
    }

    //获取分页的一些参数
    public function get_page_data()
    {
    }
    
    public function get_player()
    {
    	$id = intval($this->input['id']);
        if (!$this->input['id'])
        {
           $this->errorOutput(NOID);
        }
        
		if ($this->settings['video_cloud'] && !$this->input['extend'])
		{
			include(CUR_CONF_PATH . 'lib/cloud/' . $this->settings['video_cloud'] . '.php');
			$cloud = new $this->settings['video_cloud']();
			$cloud->setInput($this->input);
			$cloud->setSettings($this->settings);
			$cloud->setDB($this->db);
			$videoinfo = $cloud->getCloudVideo($id);
			$videoinfo = array('code' => $videoinfo);
		}
		else
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'vod_extend WHERE vodinfo_id=' . $id;
			$videoinfo = $this->db->query_first($sql);
		}
		$this->addItem($videoinfo);
		$this->output();
    }
}

$out    = new vod();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>