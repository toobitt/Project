<?php
/*$Id: district.php 17947 2013-02-26 02:57:46Z repheal $*/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
/*
 * 获取地区列表
 * @return 如果返回json，数组的格式为：array（'disc_地区编码' => array('name' => 一级地区码-一级地区的名称,array('name'=> 二级地区码-二级地区名称,'disc_地区编码'=>三级地区码-三级地区名) ）
 */

class districtApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function getDistrictList()
	{
		$sql = 'SELECT * FROM ' .DB_PREFIX . 'location WHERE 1'; 
		$queryid = $this->db->query($sql); 
		$this->setXmlNode('DistrictLists','DistrictList');
		$len2 = $len4 = array();
		while($rows = $this->db->fetch_array($queryid))
		{ 
 			$len = strlen($rows['code']);  
 			if($len == 2)
 			{
 				$len2['disc_' . $rows['code']] = array('name'=>$rows['code'] . '-' . $rows['name']); 
 			}
 			else if( $len == 4)
 			{
 				$str = substr($rows['code'],0,2);
 				if($len2['disc_' . $str]) 
 				{
 					$len2['disc_' . $str]['disc_' . $rows['code']] =  $rows['code'] . '-' . $rows['name'];
 				} 
 				
 				$len4['disc_' . $rows['code']] = array('name'=>$rows['code'] . '-' . $rows['name']);
 			}
 			else
 			{
 				$str1 = substr($rows['code'],0,4);
 				if($len4['disc_' . $str1])
 				{ 
 					$len4['disc_' . $str1]['disc_' . $rows['code']] = $rows['code'] . '-' . $rows['name'];
 				}
 				 
 			}
		}
	 	$district = array();
	 	$district = $len2+$len4;
		$this->addItem($district); 
		$this->output();
	}
	 
}

$out = new districtApi();
$out->getDistrictList();