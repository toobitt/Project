<?php
define('ROOT_PATH', './../../');
define('CUR_CONF_PATH', './');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','memcache');//模块标识
class memcacheApi extends outerReadBase
{
	private $bundle_id;
	
	private $module_id;
	
	private $filename;
	
	private $m = null; 
	
	private $group = null; 
	
	private $version = 1; 
	
	private $m_set = array();
	
	public function __construct()
	{
		parent::__construct();
		if(!$this->settings['is_open_memcache'])
		{
			return;
		}
		if(!$this->input['bundle_id'] || !$this->input['module_id'])
		{
			return;
		}
		$this->filename = $this->input['bundle_id'].'_'.$this->input['module_id'];
		$conf = realpath(CUR_CONF_PATH . 'data/' . $this->filename . '.ini.php');
		if (!$conf)
		{
			return;
		}
		$this->m_set = @include($conf);
		if(!$this->m_set)
		{
			return;
		}
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function set_base()
	{
		if(!class_exists('Memcache'))
		{ 
			$this->m = false; 
			return; 
		} 
		$this->m = new Memcache();
		if(count($this->m_set)==1)
		{
			foreach($this->m_set as $k=>$v)
			{
				if($v['timeout']==1)
				{
					if(!@$this->m->connect($v['host'], $v['port']))
					{
						return;
					}
				}
				else
				{
					if(!@$this->m->connect($v['host'], $v['port'], $v['timeout']))
					{
						return;
					}
				}
			}
		}
		else
		{
			foreach($this->m_set as $k=>$v)
			{
				$persistent = $v['persistent']?true:false;
				$weight = $v['weight']?$v['weight']:100;
				$timeout = $v['timeout']?$v['timeout']:1;
				$retry_interval = $v['retry_interval']?$v['retry_interval']:15;
				$status = $v['status']?true:false;
				$failure_callback = $v['failure_callback']?$v['failure_callback']:'';
				if($failure_callback)
				{
					if(!@$this->m->addServer($v['host'], $v['port'],$persistent,$weight,$timeout,$retry_interval,$status,$failure_callback))
					{
						return;
					}
				}
				else
				{
					if(!@$this->m->addServer($v['host'], $v['port'],$persistent,$weight,$timeout,$retry_interval,$status))
					{
						return;
					}
				}
			}
		}
		$this->version = $this->m->get('version_'.$this->group); 
		return true;
	}
	
	public function set()
	{
		$key = $this->input['key'];
		$data = $this->input['data'];
		$this->group = $this->input['group'];
		$expire = $this->input['expire']?$this->input['expire']:3600;;
		if(!$this->set_base())
		{
			echo '';exit;
		}
		$this->m->set($this->group.'_'.$this->version.'_'.$key, $data, $expire); 
	}
	
	public function get()
	{
		file_put_contents('1111.txt',var_export($this->input,1));
		$result = $docs = array();
		$key = $this->input['key'];
		$this->group = $this->input['group'];
		if(!$this->set_base())
		{
			echo '';exit;
		}
		$result = $this->m->get($this->group.'_'.$this->version.'_'.$key); 
		$this->addItem($result);
		$this->output();
	}
	
	public function delete()
	{
		$key = $this->input['key'];
		$this->group = $this->input['group'];
		if(!$this->set_base())
		{
			echo '';exit;
		}
		$this->m->delete($this->group.'_'.$this->version.'_'.$key); 
		$this->addItem();
		$this->output();
	}
	
	public function flush()
	{
		$this->group = $this->input['group'];
		if(!$this->set_base())
		{
			echo '';exit;
		}
		++$this->version; 
		$this->m->set('version_'.$this->group, $this->version); 
		$this->addItem();
		$this->output();
	}
	
	public function show(){}
	public function detail(){}
	public function index(){}
	public function count(){}
}

$out = new memcacheApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			