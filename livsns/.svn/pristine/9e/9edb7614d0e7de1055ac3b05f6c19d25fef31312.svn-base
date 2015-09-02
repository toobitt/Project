<?php
/**
 * 附件配置
 */
require('./global.php');
define('MOD_UNIQUEID','settings');
class affixSettingUpdateApi extends adminUpdateBase
{
	/**
	* 构造函数
	* author wangleyuan
	* category hogesoft
	* copyright hogesoft
	*/
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH.'lib/affix_setting.class.php');
		$this->obj=new affixSetting();
		include_once(CUR_CONF_PATH . 'lib/cache.class.php');
		$this->cache = new cache;		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function audit(){}
	public function sort(){}	
	public function publish(){}
	
    /**
	*  创建新附件配置
	* @name create
	* @access public 
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	*/
	public function create()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(!$this->input['aname'])
		{
			$this->errorOutput('类型名称不能为空');
		}
		if(!$this->input['expand'])
		{
			$this->errorOutput('扩展名不能为空');
		}
		if(!$this->input['code'])
		{
			$this->errorOutput('解析代码不能为空');
		}
		if(!$this->input['material_style'])
		{
			$this->errorOutput('请选择附件类型');
		}
		$data=array(
			'aname' 	=> $this->input['aname'],
			'expand' 	=> $this->input['expand'],
			'code'		=> htmlspecialchars_decode(urldecode($this->input['code'])),
			'is_open'   => intval($this->input['is_open']),
			'mark'      => $this->input['material_style'],
		);				
		$ret=$this->obj->create($data);
		if($ret)
		{
			$this->cache->recache('material_type.cache.php');
			$this->addLogs('添加附件类型','',$data,$data['aname']);	
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('创建失败');
		}
	}
    
	/**
	* 根据ID更新附件配置
	* @name update
	* @access public 
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	*/
	public function update()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(!$this->input['id'])
		{
			$this->errorOutput('ID不能为空');
		}
		if(!$this->input['aname'])
		{
			$this->errorOutput('类型名称不能为空');
		}
		if(!$this->input['expand'])
		{
			$this->errorOutput('扩展名不能为空');
		}
		if(!$this->input['code'])
		{
			$this->errorOutput('解析代码不能为空');
		}
		if(!$this->input['material_style'])
		{
			$this->errorOutput('请选择附件类型');
		}
		$data=array(
			'aname' => ($this->input['aname']),
			'expand' => ($this->input['expand']),
			'code' => htmlspecialchars_decode(urldecode($this->input['code'])),
			'is_open' => intval($this->input['is_open']),
			'mark' => $this->input['material_style'],
		);		
		$ret = $this->obj->update($data, $this->input['id']);
        $this->cache->recache('material_type.cache.php');
		if($ret)
		{
			$this->addLogs('修改附件附件类型','',$data,$data['aname']);
		}
		$this->addItem('success');
		$this->output();
	}

    /**
	* 根据ID删除附件配置
	* @name delete
	* @access public 
	* @author wangleyuan 
	* @category hogesoft
	* @copyright hogesoft
	*/
	public function delete()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(!$this->input['id'])
		{
			$this->errorOutput('ID不能为空');
		}
		$ids = urldecode($this->input['id']);
		$this->obj->delete($ids);
		$this->cache->recache('material_type.cache.php');
		$this->addLogs('删除附件类型','','','删除附件类型+' . $this->input['id']);
		$this->addItem($ids);
		$this->output();
	}
	/**
	* 未知函数
	* @name unknow
	* @name public 
	* @category hogesoft
	* @copyright hogesoft
	*/
    public function unknow()
	{
		$this->errorOutput("方法不存在！");
	}
}

$out=new affixSettingUpdateApi();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='unknow';
}
$out->$action();
?>