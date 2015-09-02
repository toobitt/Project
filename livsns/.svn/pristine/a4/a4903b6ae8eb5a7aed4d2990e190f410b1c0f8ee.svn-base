<?php 
class pic extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 图片格式
	 * 
	 * @param str $fname
	*/
	function type($fname) 
	{ 
		return substr(strrchr($fname,'/'),1); 
	} 
	
	/**
	 * 图片上传
	 * 
	 * @param unknown_type $files
	 * @param int $interview_id
	 * @param int $mid
	 * @param int $flag
	 */
	function interview_uplaod($files,$interview_id)
	{
		$gMaterialServer = $this->settings['material_server'];
		$this->mater = new material();
		$material = $this->mater->addMaterial($files,$interview_id); //插入各类服务器
		return $material;
	}
	/**
	 * 
	 * 图片的删除
	 * @param int $id
	 */
	function del_pic($id){
		$gMaterialServer = $this->settings['material_server'];
		$this->mater = new material();
		$r = $this->mater->delMaterialById($id,2);
		return $r;
	}
	/**
	 * 
	 * 设置封面
	 * @param  $id  文件ID
	 * @param  $vid 访谈的ID
	 * @param  $cid 封面的ID
	 */
	function set_cover_pic($id,$vid,$cid)
	{
		$arr = array();
		//判断是否为初次设置封面
		if($cid !=0)
		{
			$sql = 'UPDATE '.DB_PREFIX.'interview SET cover_pic = '.$id.' WHERE id = '.$vid;
			$this->db->query($sql);
			$arr['id'] = $id;
			$arr['vid'] = $vid;
			$arr['cid'] = $cid;  //$cid 作为改变前封面状态的ID
			return $arr;
		}else {
			$sql = 'UPDATE '.DB_PREFIX.'interview SET cover_pic = '.$id.' WHERE id = '.$vid;
			$this->db->query($sql);
			$arr['id'] = $id;
			$arr['vid'] = $vid;
			$arr['cid'] = $id;  //为初次设置提供判断条件
			return $arr;
		}
	}
	/**
	 * 
	 * 取消封面
	 * @param  $id  文件ID
	 * @param  $vid 访谈的ID
	 * @param  $cid 封面的ID
	 */
	function off_cover_pic($id,$vid,$cid)
	{
		$arr =array();
		$sql = 'UPDATE '.DB_PREFIX.'interview SET cover_pic = 0 WHERE id = '.$vid;
		$this->db->query($sql);
		$arr['id'] = $id;
		$arr['vid'] = $vid;
		$arr['cid'] = 0;
		return $arr;
	}
}





