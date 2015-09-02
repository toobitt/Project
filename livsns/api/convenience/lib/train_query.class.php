<?php
//火车票查询类
class train
{
	public function query($data = array())
	{
		if(!$data)
		{
			return false;
		}
		$ret = postCurl(TRAIN_API,$data);
		if(!$ret)
		{
			return false;
		}
		
		//如果需要缓存数据，就缓存数据
		if(IS_CACHE_TRAIN)
		{
			$filePath = TRAIN_DATA_CACHE;
			if (hg_mkdir(TRAIN_DATA_CACHE) && is_writeable(TRAIN_DATA_CACHE))
			{
				file_put_contents(TRAIN_DATA_CACHE . $data['DepartCity'] . '#' . $data['ArriveCity'] . '#' . $data['DepartDate'] . '.txt' , $ret);
			}
		}
		return $ret;
	}
}
