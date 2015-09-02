<?php
define('MOD_UNIQUEID','HotQuery');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('SCRIPT_NAME', 'HotQuery');
class HotQuery extends cronBase
{

    private $bundle_id;
    private $module_id;
    private $filename;

    public function __construct()
    {
        parent::__construct();
        if (!$this->settings['is_open_xs'])
        {
            $this->errorOutput('NOT_OPEN_XS');
        }
    }

    public function __destruct()
    {
        parent::__destruct();
    }

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '获取搜索热词',	 
			'brief' => '获取搜索热词',
			'space' => '120',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
    public function show()
    {            
    		try
            {
                include_once (CUR_CONF_PATH . 'lib/xunsearch/XS.php');
        		$conf = realpath(CUR_CONF_PATH . 'data/publishcontent_publishcontent.ini');
                $xs     = new XS($conf); // 建立 XS 对象
                $search = $xs->search; // 获取 搜索对象
            }
            catch (XSException $e)
            {
                $this->errorOutput('ERROR');
            }
			$hotwords = $search->getHotQuery(200, 'curnum');
			$words = array();
			if ($hotwords)
			{
				foreach ($hotwords AS $w => $r)
				{
					preg_match_all("/[\x{4e00}-\x{9fa5}]+/u",$w, $t);
					if($t && $t[0][0] && strlen($t[0][0]) > 4)
					{
						$words[$t[0][0]] = $r;
					}
				}
			}
			
			if($words)
			{
				$sql = "select id,name from " . DB_PREFIX . "hotwords where name IN ( '" . implode("','", array_keys($words)) . "')";
				$q   = $this->db->query($sql);
				$exists = array();
				while ($r = $this->db->fetch_array($q))
				{
					$exists[$r['name']] = $r['id'];
				}
				include(ROOT_PATH . 'lib/class/pinyin.class.php');
				include(CUR_CONF_PATH . 'lib/hotwords.class.php');
				$obj = new Hotwords();
				foreach($words AS $w => $r)
				{
					if ($exists[$w])
					{
						$sql = 'UPDATE ' . DB_PREFIX . 'hotwords SET rate=' . intval($r) . ' WHERE id=' . $exists[$w];
						$this->db->query($sql);
					}
					else
					{
						$info = array(
							'name' => $w,
							'rate' => $r,
							'user_id' => $this->user['user_id'],
							'user_name' => $this->user['user_name'],
							'ip' => $this->user['ip'],
							'update_time' => TIMENOW,
							'create_time' => TIMENOW,
						);
						$title_pinyin_result = hanzi_to_pinyin($w, false, 0);
						
						if($title_pinyin_result['word'])
						{
							$info['pinyin'] = implode('',$title_pinyin_result['word']);
						}
						$obj->create($info);
					}
				}
			}
    }

}

$out    = new HotQuery();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>