<?php
/**
 * 附件管理
 */
require('./global.php');
define('MOD_UNIQUEID','material');
class affixUpdateApi extends adminUpdateBase
{
	/**
	* 构造函数
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	*/
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH.'lib/affix.class.php');
	    $this->obj=new affix();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function audit(){}
	public function sort(){}	
	public function publish(){}	

	/**
	*	创建新的附件
	* @name create
	* @access public
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesof
	*/
	public function create()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$ret = $this->obj->create();
		$this->addItem($ret);
		$this->output();
	}

	/**
	* 根据id更新附件
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
		$ret = $this->obj->update();
		$this->addItem($ret);
		$this->output();
	}

	/**
	* 删除附件(支持批量)
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
		if(empty($this->input['id']))
		{
			$this->errorOutput('未传入附件ID');
		}
		$ret=$this->obj->delete();
		if($ret)
		{
			$this->addLogs('删除附件','','','删除附件+'.$this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('删除失败');
		}
	}
	

	/**
	* 删除一定尺寸的图片缩略图
	* @name delete_thumb_size
	* @access public 
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	*/
	public function delete_thumb_size()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(empty($this->input['path']))
		{
			$this->errorOutput('未传入附件路径');
		}
		if(empty($this->input['size_label']))
		{
			$this->errorOutput('未传入缩略图尺寸');
		}
		$ret=$this->obj->delete_thumb_size();
		if($ret)
		{
			$this->addLogs('删除图片缩略图','','','删除图片缩略图+'.$this->input['path'] . $this->input['size_label']);	
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('删除失败');
		}
	}

	/**
	* 上传附件
	* @name upload_affix
	* @access public
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	*/
	public function upload_affix()
	{	
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if($_FILES['Filedata'])
		{
			$return = $this->obj->upload_affix();
			$this->addLogs('上传附件','','',$_FILES['Filedata']['name']);	
			$this->addItem($return);
			$this->output();
		}
	}


   /**
   *  获取节点
   *
   *  @name get_node
   *  @access public
   *  @author wangleyuan
   *  @category hogesoft
   *  @copyright hogesoft
   */
	public function get_node()
	{
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		if($this->input['fid'])
		{
			$modules = $this->auth->get_module('id,app_uniqueid,mod_uniqueid,name',$this->input['fid']);
			if(is_array($modules))
			{
				foreach($modules as $k=>$v)
				{
					 $m = array('id'=>$v['id'],'name'=>$v['name'],'fid'=>$this->input['fid'],'depth'=>0,'is_last'=>1,'input_k' => '_id','_appid' => $v['app_uniqueid'],'_modid' => $v['mod_uniqueid']);
				 	 $this->addItem($m);
				}
			}
			$this->output();
		}
		else
		{
			$app_info = $this->auth->get_app();
			if(is_array($app_info))
			{
				foreach($app_info as $k=>$v)
				{
					$app = array('id' => $v['id'],'name' => $v['name'],'fid' =>0, 'depth' => 0,'is_last' =>0,'input_k' => '_id','_appid' => $v['bundle'],'_modid' => '');
					$this->addItem($app);
				}
			}
			$this->output();
		}
	}


	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function unknow()
	{
		
		$this->errorOutput("此方法不存在！");
	}

}

$out = new affixUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>