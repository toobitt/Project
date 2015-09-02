<?php
/*******************************************************************
 * filename :CNDUpdate.php
 * Created  :2013年8月9日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'cdn');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  CDNUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->obj = new Core();
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
	}
	public function delete()
	{
		if (empty ($this->input['id']))
		{
			$this->errorOutput("NO_DATA_ID");
		}
		if(empty($this->input['tbname']))
		{
			$this->errorOutput("NO_TBNAME");
		}
		$id = intval($this->input['id']);
		
		$re = $this->obj->delete(trim($this->input['tbname'])," where id=".intval($this->input['id']));
		$this->addItem($re);
		$this->output();
	}
	public function audit()
	{

	}
	public function sort()
	{
		
	}
	public function publish()
	{
	}
	/**
	 * 主要对表单提交的数据处理
	 */
	private function get_condition()
	{

        //cdn类型
		/*if(!isset($this->input['type']))
		{
			return false;
		}*/
		$params['type'] = $this->input['type'];
		/**
		 * url处理
		 * 提交的数据可以数组、或者字符串
		 */
		if(isset($this->input['urls']))
		{
			if(is_string($this->input['urls']))
			{
				
				$urls = explode("\n", $this->input['urls']);

				if($urls)
				{
					foreach ($urls as $key => $url) {
						if(trim($url))
						{
							$params['urls'][] = trim($url);
						}
					}//end foreach
				}//end if
				
			}
			else if(is_array($this->input['urls']))
			{
				$params['urls'] = $this->input['urls'];
			}
			
		}//end if
		/**
		 * dir处理
		 */		
		if(isset($this->input['dirs']))
		{
			if(is_string($this->input['dirs']))
			{
				$dirs = explode("\n", $this->input['dirs']);
				if($dirs)
				{
					foreach ($dirs as $key => $dir) {
						if(trim($dir))
						{
							$params['dirs'][] = $trim($dir);
						}
					}//end foreach
				}//end if
				
			}
			else if(is_array($this->input['dirs']))
			{
				$params['dirs'] = $this->input['dirs'];
			}
		}

		return $params;
	}
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}


    /**
     * 数据推送
     */
	public function push()
	{
		if ($this->settings['close_push'])
		{
			$this->errorOutput(PUSH_IS_CLOSE);
		}
	    /**
         * 获取数据
         */
		$params = $this->get_condition();
		
		if(!$params)
		{
			$this->errorOutput(NO_DATA_NEED_TO_PUSH);
		}
		if(!CDN_STATUS)
		{
			$this->errorOutput(CDN_STATUS_IS_NOT_OPEN);
		}
		
		$type = CDN_TYPE ? CDN_TYPE : 'UpYun';
		if(!$params['type'])
		{
			$params['type'] = $type;
		}
		
		if(!in_array($params['type'],$this->settings['cdn']['type']))
		{
			$this->errorOutput(THE_CDN_TYPE_DONOT_EXIST);
		}
		
		include CUR_CONF_PATH."lib/".$params['type'].".class.php";
		$this->obj = new $params['type'];
		$this->addItem($this->obj->push($params));
		$this->output();
		
	}
	
	//主要供计划任务使用
	public function pushfordb()
	{
		foreach ($this->settings['cdn']['type'] as $type)
		{
			include CUR_CONF_PATH."lib/".$type.".class.php";
			$typeobj = $type."obj";
			if(!$this->$typeobj)
				$this->$typeobj = new $type();
		}
		
		$datas = $this->obj->show('cdn_log',$cond=' limit 0,'.FAIL_DATA_LIMIT,$fields='*');
		
		if(!empty($datas))
			return true;
		
		$ids = "";
		foreach($datas as $data)
		{
			$cdntype = $data['type']."obj";
			$this->$cdntype->pushfordb(unserialize($data['data']));
			$ids .= $data['id'].",";
		}
		$ids = substr($ids,0,-1);	
		$this->$cdntype->delete($ids);
		
		$this->addItem($id);
		$this->output();	
	}
	
	//前端提交需要更新的数据
	//支持一次提交多条数据
	public function pushforfront()
	{
		/*foreach ($this->settings['cdn']['type'] as $type)
		{*/
		$type = CDN_TYPE ? CDN_TYPE : 'UpYun';
		include CUR_CONF_PATH."lib/".$type.".class.php";
		$typeobj = $type."obj";
		if(!$this->$typeobj)
			$this->$typeobj = new $type();
		//}//end foreach
		$id = $this->input['id'];
		if(!$id)
			$this->errorOutput(NO_ID);
		
		$datas = $this->obj->show('cdn_log',$cond=' where id in('.$id.')',$fields='*');
		foreach($datas as $data)
		{
			$cdntype = $data['type']."obj";
			$this->$cdntype->pushfordb(unserialize($data['data']));
		}
		$this->$cdntype->delete($id);
		$this->addItem($id);
		$this->output();
	}


}
$out = new CDNUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>