<?php
class livmedia
{
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_livmedia']['host'], $gGlobalConfig['App_livmedia']['dir']);
	}

	public function __destruct()
	{
	}

	public function resetCurl()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_livmedia']['host'], $gGlobalConfig['App_livmedia']['dir']);
	}
	
	public function getAutoItem()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$ret = $this->curl->request('vod_sort.php');
		return $ret;
	}
	
	/**
	 * 获取视频库已审核的信息 (status=2)
	 * $k 检索 title
	 * $offset 检索条件
	 * $count 检索条件
	 * $pp
	 * $k 
	 * $date_search
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getPageData($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_page_data');
		
		if (!empty($data))
		{
			foreach ($data AS $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		
		$ret = $this->curl->request('vod.php');
		return $ret;
	}
	/**
	 * 获取视频库已审核的信息 (status=2)
	 * $k 检索 title
	 * $offset 检索条件
	 * $count 检索条件
	 * $id 视频id
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getVodInfo($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_vod_info');
		
		if (!empty($data))
		{
			foreach ($data AS $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		
		$ret = $this->curl->request('vod.php');
		return $ret;
	}
	/**
	 * 获取视频库已审核的总数 (status=2)
	 * $k 检索 title
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getVodCount($data = array())
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','count');
		
		if (!empty($data))
		{
			foreach ($data AS $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		
		$ret = $this->curl->request('vod.php');
		return $ret[0];
	}
	/**
	 * 获取视频库已审核的信息 支持多个 (status=2)
	 * $id 视频id
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getVodInfoById($id,$count = 20)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_vod_info');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('vod.php');
		return $ret;
	}
	
	public function getVodNode($fid = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$this->curl->addRequestData('fid', $fid);
		$ret = $this->curl->request('vod_media_node.php');
		return $ret;
	}

	//获取多个视频信息
	public function get_videos($id = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->resetCurl();
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_videos');
		$this->curl->addRequestData('id',$id);
		$ret = $this->curl->request('vod.php');
		return $ret;
	}

    //获取多个id视频信息
    public function get_videos_info($id)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->resetCurl();
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','detail');
        $this->curl->addRequestData('id',$id);
        $ret = $this->curl->request('vod.php');
        return $ret;
    }
	
	/**
	 * 创建视频
	 * @param Array $data 相关属性
	 * @param Array $file 视频文件
	 */
	public function create($data, $file)
	{
		global $gGlobalConfig;
		$curl = new curl($gGlobalConfig['App_mediaserver']['host'], $gGlobalConfig['App_mediaserver']['dir'] . 'admin/');
		if (!$curl) return array();
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$curl->addRequestData($k, $v);
			}
		}
		$curl->addFile($file);
		$ret = $curl->request('create.php');
		return $ret[0];
	}
	
	/**
	 * 编辑视频
	 * @param Array $data
	 * @param Int $id
	 */
	public function update($data, $id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if ($data && is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'update');
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	
	/**
	 * 审核与打回
	 * @param Int $id
	 * @param Int $op op = 1 审核   op = 0 打回
	 */
	public function audit($id, $op)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('audit', $op);
		$this->curl->addRequestData('a', 'audit');
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	
	/**
	 * 删除视频
	 * @param String|Int $ids
	 */
	public function delete($ids)
	{
		if (!$this->curl)
		{
			return array();
		}
		if(!$ids) return false;
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $ids);
		$this->curl->addRequestData('a', 'delete');
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	
	/**
	 * 获取视频转码进度
	 * @param String|Int $ids
	 */
	public function get_video_status($ids = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		if(!$ids) return false;
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_video_status');
		$this->curl->addRequestData('id',$ids);
		$ret = $this->curl->request('vod.php');
		return $ret[0];
	}
	
	public function publish($ids,$status,$column_id,$app_uniqueid = '',$publish_time = TIMENOW)
	{
		if (!$this->curl)
		{
			return array();
		}
		if(!$ids) return false;
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $ids);
		$this->curl->addRequestData('status', $status);
		$this->curl->addRequestData('column_id', $column_id);
		$this->curl->addRequestData('app_uniqueid', $app_uniqueid);
		$this->curl->addRequestData('pub_time', $publish_time);
		$this->curl->addRequestData('a', 'publish');
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	
	public function insertQueueToLivmedia($ids,$op,$column_id,$now_column = '',$pub_time = TIMENOW)
	{
		if (!$this->curl)
		{
			return array();
		}
		if(!$ids) return false;
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $ids);
		$this->curl->addRequestData('op', $op);
		$this->curl->addRequestData('column_id', $column_id);
		$this->curl->addRequestData('now_colum', $now_column);
		$this->curl->addRequestData('pub_time', $pub_time);
		$this->curl->addRequestData('a', 'insertQueueToLivmedia');
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	
	//更新权重
	public function updateWeight($data = array(), $id)
	{
		if (!$this->curl)
		{
			return array();
		}
		if (empty($data) || empty($id))
		{
			 return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_weight');
		$this->curl->addRequestData('app', APP_UNIQUEID);
		$this->curl->addRequestData('module', MOD_UNIQUEID);
		$this->curl->addRequestData('html', true);
		if (is_array($data) && count($data) > 0)
		{
			foreach ($data as $k => $v)
			{
				if (is_array($v))
				{
					$this->array_to_add($k,$v);
				}
				else
				{
					$this->curl->addRequestData($k,$v);
				}
			}
		}
		$this->curl->addRequestData('id',$id);
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	/**
	 * 
	 * @Description: 视频外链上传
	 * @author Kin   
	 * @date 2014-5-26 下午03:22:11
	 */
	public function chain($data)
	{
		if (!$this->curl) return array();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		if ($data && is_array($data) && !empty($data))
		{
			foreach ($data as $k => $v)
			{
				if (is_array($v))
				{
					$this->array_to_add($k,$v);
				}
				else
				{
					$this->curl->addRequestData($k,$v);
				}
			}
		}
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	
	private function array_to_add($str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
	}
	/**
	 * 
	 * @Description: 更新单个内容的栏目
	 * @author Kin   
	 * @date 2014-6-12 下午05:24:06
	 */
	public function update_column($id, $column_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'publish');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('column_id', $column_id);
		$this->curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	
	/**
	 * 内容管理下更换栏目
	 * @author jitao
	 */
	public function editColumnsById($id = 0 , $column_id = 0 , $column_path = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'editColumnsById');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('column_id', $column_id);
		$this->curl->addRequestData('column_path', $column_path);
		$this->curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	
	/**
	 *
	 * @Description  获取视频信息
	 * @author Kin
	 * @date 2013-6-18 上午09:12:55
	 */
	public function get_video($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_videos');
		$this->curl->addRequestData('id',$ids);
		$ret = $this->curl->request('vod.php');
		$ret = $ret[0];
		$vodInfor = array();
		if (is_array($ret) && !empty($ret))
		{
			$arr_id = explode(',', $ids);
			foreach ($arr_id as $val)
			{
				$vodInfor[$val]['url'] = $ret[$val]['video_url'];
				$arr = explode('.', $ret[$val]['video_filename']);
				$type = $arr[1];
				$m3u8 = $ret[$val]['hostwork'].'/'.$ret[$val]['video_path'].str_replace($type, 'm3u8', $ret[$val]['video_filename']);
				$img = $ret[$val]['img_info'] ? unserialize($ret[$val]['img_info']) : '';
				$vodInfor[$val]['img'] = $img;
				$vodInfor[$val]['m3u8'] = $m3u8;
				$vodInfor[$val]['vodid'] = $val;
				$vodInfor[$val]['duration'] = $ret[$val]['duration'];
				$vodInfor[$val]['totalsize'] = $ret[$val]['totalsize'];
				$vodInfor[$val]['is_audio'] = $ret[$val]['is_audio'];
			}
		}
		return $vodInfor;
	}
	
	/**
	 * 赞或者取消赞成功后更新发布库对应内容的赞的count
	 * @param string $operate add或cancel
	 * @param number $content_id 内容ID
	 * @param number $num 次数
	 * @return multitype:|Ambigous <>
	 */
	public function update_praise_count($operate = '' , $content_id = 0 , $num = 0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_praise_count');
		$this->curl->addRequestData('content_id',$content_id);
		$this->curl->addRequestData('operate',$operate);
		$this->curl->addRequestData('num',$num);
		$this->curl->addRequestData('token','8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
	
	/**
	 * 移动到垃圾箱
	 */
	public function moveToTrash($id,$vod_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'moveToTrash');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('vod_id', $vod_id);
		$this->curl->addRequestData('token','8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('admin/vod_update.php');
		return $ret[0];
	}
}
?>