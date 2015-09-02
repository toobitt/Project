<?php
require_once ROOT_PATH . 'lib/class/material.class.php';
require_once CUR_CONF_PATH.'core/watermark.class.php';
require_once CUR_CONF_PATH.'core/phpqrcode/phpqrcode.php';
class staff extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$offset,$count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT s.*,s.name AS sname ,s.update_time AS supdate_time,s.user_name AS suser_name,d.name AS dname FROM '.DB_PREFIX.'staff s
				LEFT JOIN '.DB_PREFIX.'department d ON s.department_id = d.id 
				WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		$temp = array();
		while ($row = $this->db->fetch_array($query))
		{
			$temp['id'] = $row['id'];
			$temp['name'] = $row['surname'].$row['sname'];
			$avatar = unserialize($row['avatar']);
			$temp['avatar'] = $avatar ? $avatar : '';
			$temp['sex'] = $row['sex'];
			$temp['department'] = $row['dname'] ? $row['dname'] : '保密';
			$temp['position'] = $row['position'];
			$temp['status'] = $row['status'];
			$temp['update_time'] = date('Y-m-d H:i:s',$row['supdate_time']);
			$temp['user_name'] = $row['suser_name'];
			$k[$row['id']] = $temp;
		}
		return $k;	
	}
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'staff  WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function detail($id)
	{
		$sql = 'SELECT s.*,s.name AS sname,d.name AS dname,ed.education,sk.skills,exp.experience,ext.extra FROM '.DB_PREFIX.'staff s
		       LEFT JOIN '.DB_PREFIX.'department d ON s.department_id = d.id
		       LEFT JOIN '.DB_PREFIX.'staff_education ed ON s.id = ed.id
		       LEFT JOIN '.DB_PREFIX.'staff_skills sk ON s.id = sk.id
		       LEFT JOIN '.DB_PREFIX.'staff_work_experience exp ON s.id = exp.id
		       LEFT JOIN '.DB_PREFIX.'staff_extra ext ON s.id = ext.id
		       WHERE s.id = '.$id;
		$ret = $this->db->query_first($sql);
		$ret['name'] = $ret['sname'];
		$ret['department'] = $ret['dname'] ? $ret['dname'] : '保密';
		$ret['avatar'] = unserialize($ret['avatar']);
		$ret['education'] = $ret['education'];
		$ret['experience'] = $ret['experience'];
		$ret['extra'] = unserialize($ret['extra']);
		return $ret;
	}
	
	public function audit($ids,$status)
	{
		$sql = 'UPDATE '.DB_PREFIX.'staff SET status = '.$status.' WHERE id IN ('. $ids .')';
		$ret = $this->db->query($sql);
		$arr = explode(',', $ids);
		$data = array(
			'id'=>$arr,
			'status'=>$status,
		);
		return $data;
	}
	
	public function create($data,$table)
	{
		$sql = 'INSERT INTO '.DB_PREFIX.$table.' SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');		
		$ret = $this->db->query($sql);		
		$id = $this->db->insert_id();
		
		$sql = 'UPDATE '.DB_PREFIX.'staff SET order_id= '.$id . ' WHERE id = '.$id;
		$this->db->query($sql);
		
		$data['id'] = $id;
		return $data;
	}
	
	public function delete($ids)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'staff WHERE id IN ('.$ids.')';
		$ret = $this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'staff_education WHERE id IN ('.$ids.')';
		$ret = $this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'staff_skills WHERE id IN ('.$ids.')';
		$ret = $this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'staff_extra WHERE id IN ('.$ids.')';
		$ret = $this->db->query($sql);
		$sql = 'DELETE FROM '.DB_PREFIX.'staff_work_experience WHERE id IN ('.$ids.')';
		$ret = $this->db->query($sql);
		return $ids;
	}
	
	public function update($data,$table,$id)
	{
		$sql = 'UPDATE '.DB_PREFIX.$table.' SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id; 
		$this->db->query($sql);
		return true;
	
	}
	public function departments()
	{
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'department';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['id']] = $row['name'];
		}
		return $k;
		
	}
	
	/**
	 * 
	 * 公共入库方法 ...
	 * @param array $data 数据
	 * @param string $dbName  数据库名
	 * @param int   $flag  有主键的置1返回主键值
	 */
	public function storedIntoDB($data,$dbName,$flag=0)
	{		
		if (!$data || !is_array($data) || !$dbName)
		{
			return false;
		}
		$sql = 'REPLACE INTO '.DB_PREFIX.$dbName.' SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		if ($flag)
		{
			return $this->db->insert_id();
		}
		return true;
	}
	
	public function avatar($file,$id)
	{
		$material = $this->material->addMaterial($file,$id); //插入图片服务器
		return $material;
	}
	
	public function userinfo($ids)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'staff WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$temp['id'] = $row['id'];
			$temp['card_id'] = $row['card_id'];
			$temp['surname'] = $row['surname'];
			$temp['name'] = $row['name'];
			$temp['english_name'] = $row['english_name'];
			$temp['position'] = $row['position'];
			$temp['en_position'] = $row['en_position'];
			$temp['company'] = $row['company'];
			$temp['mobile'] = $row['mobile'];
			$temp['tel'] = $row['tel'];
			$temp['ext_num'] = $row['ext_num'];
			$temp['fax'] = $row['fax'];
			$temp['qq'] = $row['qq'];
			$temp['email'] = $row['email'];
			$temp['web'] = $row['web'];
			$temp['company_addr'] = $row['company_addr'];
			$temp['en_company_addr'] = $row['en_company_addr'];
			$k[$row['id']] = $temp;
		}
		return $k; 
	}
	
	public function card_configs($ids)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'staff_card WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[$row['id']] =$row['image'];	
		}
		return $k;
		
	}
	//生成二维码
	public function qrcode($data)
	{
		if (!$data || !$this->settings['staff_phpqrcode'])
		{
			return false;
		}
		$open = $this->settings['staff_phpqrcode']['open'];
		$PNG_WEB_DIR =$this->settings['staff_phpqrcode']['path'];
		$errorCorrectionLevel =$this->settings['staff_phpqrcode']['errorCorrectionLevel'];
		$matrixPointSize = $this->settings['staff_phpqrcode']['matrixPointSize'];
		$margin = $this->settings['staff_phpqrcode']['margin'];
		
		$errorCorrectionLevel = $errorCorrectionLevel ? $errorCorrectionLevel :'L';
		$matrixPointSize = $matrixPointSize ? $matrixPointSize : 4;
		$margin = $margin ? $margin : 2;
		if (!$PNG_WEB_DIR || !$open)
		{
			return false;
		}
		if (!is_dir($PNG_WEB_DIR))
		{
			hg_mkdir($PNG_WEB_DIR);
		}
		$filename = $PNG_WEB_DIR.'qrcode'.md5($data.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize,$margin);
		return $filename;
	}
	//计算字符串长度
	public function abslength($str)
	{
		if (empty($str))
		{
			return 0;
		}
		if (function_exists('mb_strlen'))
		{
			return mb_strlen($str,'utf-8');
		}else {
			preg_match_all("/./u", $str, $arr);
       		return count($arr[0]);
		}
	}	
}