<?php
/*******************************************************************
 * filename :member_myData_update.php
 * 我的数据储存接口
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','memberMyData');//模块标识
require('./global.php');
class memberMyDataUpdateApi extends outerUpdateBase
{
	private $memberMyData = null;
	public function __construct()
	{
		parent::__construct();
		$this->memberMyData = new memberMyData();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		try{
		$this->get_condition();//获取必要参数处理
		$TParams = $this->memberMyData->setMark(trim($this->input['mark']));
		if($TParams==-3)
		{
			$this->errorOutput(PROHIBIT_CN);
		}
		elseif ($TParams == -4)
		{
			$this->errorOutput(MARK_CHARACTER_ILLEGAL);
		}
		elseif($TParams == -5)
		{
			$this->errorOutput(MARK_ERROR);
		}
		elseif($TParams == -6)
		{
			$this->errorOutput(NO_MARK_ERROR);
		}
		$TParams = $this->memberMyData->setMyData($this->input['mydata']);
		if($TParams==-7)
		{
			$this->errorOutput(ERROR_MYDATA);
		}
		if(!$this->memberMyData->getMyData())
		{
			$this->errorOutput(MYDATA_NOT_NULL);
		}

		$TParams = $this->memberMyData->setSerach($this->input['serach']);
		if($TParams == -8)
		{
			$this->errorOutput(ERROR_SERACH);
		}
		
		$ret = $this->memberMyData->dataProcess('create')->create();//数据处理
		$data = array(array_merge(array('mdid'=>(string)$ret['id']),maybe_unserialize($ret['mydata'])));
		$data = $this->memberMyData->outputProcess($data);
		$redata = array(
			'status'=> $ret['id']>0?1:0,
			'data' => $data,
			'copywriting' => $data['title'].($ret['id']>0?'增加成功':'增加失败'),
			) ;
		}
		catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}
		$this->addItem($redata);
		$this->output();
	}
	
	public function update()
	{
		try {
		$this->get_condition();//获取必要参数处理
		if(($TParams = $this->memberMyData->setMdid((int)$this->input['mdid'])))
		{
			if($TParams == -9)
			{
				$this->errorOutput(NO_DATA_ID);
			}
			elseif($TParams == -10)
			{
				$this->errorOutput(NO_INPUT_MYDATA_ID);
			}
		}
		$TParams = $this->memberMyData->setMyData($this->input['mydata']);
		if($TParams==-7)
		{
			$this->errorOutput(ERROR_MYDATA);
		}
		if(!$this->memberMyData->getMyData())
		{
			$this->errorOutput(MYDATA_NOT_NULL);
		}
		
		$TParams = $this->memberMyData->setSerach($this->input['serach']);
		if($TParams == -8)
		{
			$this->errorOutput(ERROR_SERACH);
		}
		
		if(!$this->memberMyData->checkMeData())
		{
			$this->errorOutput(NOT_UPDATE_OTHERS_DATA);
		}
		
		$ret = $this->memberMyData->dataProcess('update')->paramProcess('update')->update();//数据处理
		$data = array(array_merge(array('mdid'=>(string)$ret['mdid']),maybe_unserialize($ret['mydata'])));
		$data = $this->memberMyData->outputProcess($data);
		$redata = array(
			'status'=> $ret['mdid']>0?1:0,
			'data' => $data,
			'copywriting' => $data['title'].($ret['mdid']>0?'更新成功':'更新失败'),
			) ;
	    }	
		catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}
		$this->addItem($redata);
		$this->output();
	}
	
	public function delete()
	{
		try {
		$this->get_condition();//获取必要参数处理
		if(($TParams = $this->memberMyData->setMdid((int)$this->input['mdid'])))
		{
			if($TParams == -9)
			{
				$this->errorOutput(NO_DATA_ID);
			}
			elseif($TParams == -10)
			{
				$this->errorOutput(NO_INPUT_MYDATA_ID);
			}
		}
		if(!$this->memberMyData->checkMeData())
		{
			$this->errorOutput(NOT_DELETE_OTHERS_DATA);
		}
		$ret = $this->memberMyData->paramProcess('delete')->delete();//数据处理
		$data = $this->memberMyData->getMark();
		$redata = array(
			'status'=> $ret?1:0,
			'copywriting' => $data['title'].($ret?'删除成功':'删除失败'),
			) ;
      	}
		catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}	
		$this->addItem($redata);
		$this->output();
	}
	
	/**
	 *
	 * 图片上传,支持传参 ...
	 * @param array $files $_FILES数组
	 * @param int $cid 编目内容id
	 */
	public function upload_img()
	{
		$this->get_condition();
		 $_FILES['photos'] && $file['Filedata'] = $_FILES['photos'];
		if($file)
		{
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装！');
			}
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($file);
			$img_data = array();
			$img_info['id'] && $img_data = array(
											'host' 			=> $img_info['host'],
											'dir' 			=> $img_info['dir'],
											'filepath' 		=> $img_info['filepath'],
											'filename' 		=> $img_info['filename'],
											);

			if($img_data)
			{
				$ret_datas['status'] = 1;
				$ret_datas['imgUrl'] = $img_info['url'];
				$ret_datas['imgData'] = $img_data;
				$this->setAddItemValueType();
				$this->addItem($ret_datas);
				$this->output();
			}
			else 
			{
				$this->errorOutput('图片上传错误,请重新上传');
			}
		}
		else if (empty($_FILES['Filedata']))
		{
			$this->errorOutput('你未提交需要上传文件');
		}
	}
	
	
	/**
	 *
	 * 获取需要的条件
	 */
	private function get_condition()
	{	
		$TParams = $this->memberMyData->setMemberId($this->user['user_id']);
		if ($TParams == -1)
		{
			$this->errorOutput(NO_MEMBER);
		}
		elseif ($TParams ==-2)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
	}

	/**
	 * 空方法,如果用户调取的方法不存在.则执行
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在");
	}


}

$out = new memberMyDataUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>