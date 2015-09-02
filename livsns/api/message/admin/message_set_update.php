<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
require_once('../lib/functions.php');
define('MOD_UNIQUEID','message_set');//模块标识
class MessageModuleUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{
		if(!$this->input['app_id'])
		{
			$this->errorOutput(NO_APP_INFO);
		}
		//评论设置
		if(!$this->input['st'])
		{
			$this->errorOutput(NO_SET);
		}
		$bundle_id = $this->input['app_id'];
		$module_id = $this->input['module_id'];
		//应用信息
		$app_info = explode('@', $this->input['app_id']);
		
		if(!$this->input['module_id'])
		{
			$type = 0;
			$name = $app_info[1];
			$var_name = $app_info[0];
			$module_id = '';
		}
		else 
		{
			//模块信息
			$mod_info = explode('@', $this->input['module_id']);
			$name = $mod_info[1];
			$var_name = $app_info[0].'_'.$mod_info[0];
			$type = 1;
		}
		$type = 0;
		$arr_val = $this->input['st'];//设置内容
	
		//开启敏感词过滤，colation_type敏感词处理方式
		if($this->input['colation_type'] && $arr_val['colation'])
		{
			$arr_val['colation'] = $this->input['colation_type'];
		}
		//字数限制不填默认300
		if(!intval($arr_val['max_word']))
		{
			$arr_val['max_word'] = '300';
		}
		$arr_val['max_word'] 		= abs($arr_val['max_word']);
		$arr_val['rate'] 			= abs(intval($arr_val['rate']));
		
		//验证码开启，才记录验证码设置
		if($arr_val['verify_mode'])
		{
			$arr_val['verify_type']	= intval($this->input['verify_type']);
		}
		
	
		$data = array(
			'name' => $name,
			'bundle_id' => $bundle_id,
			'module_id' => $module_id,
			'type' => $type,
			'var_name' => $var_name,
			'value' => serialize($arr_val),
			'is_open' => $arr_val['state'],
			
			'user_name'=>$this->user['user_name'],
			'user_id' => $this->user['user_id'],
			'org_id' => $this->user['org_id'],
			'create_time'=>TIMENOW,
			'update_time'=>TIMENOW,
		);
		//插入系统配置表settings
		$sql ="INSERT INTO " . DB_PREFIX ."app_settings SET ";
		foreach($data as $k=>$v)
		{
			$sql .= "`".$k . "`='" . $v . "',";
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		
		//创建缓存
		$this->build_comment_set_cache();
		
		$this->addItem('success');
		$this->output();
	}
	
	//更新配置
	public function update()
	{	
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$data['_action'] = 'manage';
		$this->verify_content_prms($data);
		
		$arr_val = $this->input['st'];//设置内容
		$var_name = $this->input['var_name'];
		
		//开启敏感词过滤，colation_type敏感词处理方式
		if($this->input['colation_type'] && $arr_val['colation'])
		{
			$arr_val['colation'] = $this->input['colation_type'];
		}
		//字数限制不填默认300
		if(!intval($arr_val['max_word']))
		{
			$arr_val['max_word'] = '300';
		}
		$arr_val['max_word'] 	= abs($arr_val['max_word']);
		$arr_val['rate'] 		= abs(intval($arr_val['rate']));
		
		//验证码开启，才记录验证码设置
		if($arr_val['verify_mode'])
		{
			$arr_val['verify_type']	= intval($this->input['verify_type']);
		}
		//插入系统配置表settings
		$sql ="UPDATE " . DB_PREFIX ."app_settings SET ";
		$sql .= "var_name = '" . $var_name ."',";

		$sql .= "value = '" . serialize($arr_val) . "',";
		$sql .=" is_open = ".$arr_val['state'];
		$sql .=" WHERE id = ".$id;
		$this->db->query($sql);
		
		//重建缓存
		$this->build_comment_set_cache();
		
		$this->addItem('success');
		$this->output();
	}
	//创建评论设置缓存
	function build_comment_set_cache()
	{
		//查询各应用系统配置
		$sql = "SELECT * FROM ".DB_PREFIX."app_settings WHERE content_id=0";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$arr[$r['var_name']] = unserialize($r['value']);
		}
		if($arr && count($arr))
		{
			if(!is_dir(MESSAGE_SET_CACHE_DIR))
			{
				hg_mkdir(MESSAGE_SET_CACHE_DIR);
			}
			if(file_exists('../cache/comment_set_cache.php'))
			{
				unlink('../cache/comment_set_cache.php');
			}
			@file_put_contents(MESSAGE_SET_CACHE_DIR .'comment_set_cache.php', "<?php\r\n");
			foreach ($arr as $k => $v)
			{
				@file_put_contents(MESSAGE_SET_CACHE_DIR .'comment_set_cache.php', "\r\n\$gGlobalConfig['" . $k . "'] = ".var_export($v,true).";\n",FILE_APPEND);
			}
			@file_put_contents(MESSAGE_SET_CACHE_DIR .'comment_set_cache.php', "\n?>",FILE_APPEND);
		}
	}
	function delete()
	{
		$ids = urldecode($this->input['id']);
		$sql  = 'DELETE FROM '.DB_PREFIX.'app_settings WHERE id in('.$ids.')';
		$this->db->query($sql);
		
		//重建缓存
		$this->build_comment_set_cache();
		
		$this->addItem('success');
		$this->output();
	}
	function sort()
	{
		
	}
	function publish()
	{
		
	}
	//应用创建自己的评论配置
	function create_set()
	{
		$data['_action'] = 'manage';
		$this->verify_content_prms($data);
		
		if(!$this->input['app_uniqueid'])
		{
			$this->errorOutput('没有应用标识');
		}
		if($this->input['app_uniqueid'])
		{
			$con = ' AND bundle_id="'.$this->input['app_uniqueid'].'"';
		}
		if($this->input['mod_uniqueid'])
		{
			$con .= ' AND module_id="'.$this->input['mod_uniqueid'].'"';
		}
		if($this->input['content_id'])
		{
			$con .= ' AND content_id = '.$this->input['content_id'];
		}
		//删除之前配置
		$sql = 'delete from ' . DB_PREFIX . 'app_settings where 1 '.$con ;
		$this->db->query($sql);
		
		//插入应用配置表settings
		$var_name = 'message_form_set';
		$type = 0;
		$set = $this->input['set'];
		$sql ="insert into " . DB_PREFIX ."app_settings set ";
		$sql .= "bundle_id = '" . $this->input['app_uniqueid'] ."',";
		$sql .= "module_id = '" . $this->input['mod_uniqueid'] ."',";
		
		if($this->input['content_id'])
		{
			$sql .= "content_id = " . $this->input['content_id'] .",";
		}
		
		$sql .= "var_name = '" . $var_name ."',";
		$sql .= "type = " . $type .",";

		$sql .= "value = '" . serialize($set) . "',";
		$sql .=" is_edit = 1 ,";
		$sql .=" is_open = 1 ";
		$this->db->query($sql);
		
		$this->build_comment_set_cache();
	}
	function audit()
	{
	}
}
$ouput= new MessageModuleUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>