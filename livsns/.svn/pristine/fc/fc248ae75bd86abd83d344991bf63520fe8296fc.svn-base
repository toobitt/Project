<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: app.php 776 2010-12-17 05:47:15Z yuna $
*/

!defined('IN_UC') && exit('Access Denied');

class ugroupmodel {

	var $db;
	var $base;
	var $query;

	function __construct(&$base) {
		$this->ugmodel($base);
	}

	function ugmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function get_ugroups() {
		$arr = $this->db->fetch_all("SELECT * FROM ".UC_DBTABLEPRE."member_group WHERE 1 ORDER BY order_id ASC");
		return $arr;
	}

	function add_ug($ug_name,$ug_desc,$ug_order,$ug_cout=0,$ug_prms='无'){
		
		$this->query = $this->db->query("SELECT * FROM ".UC_DBTABLEPRE."member_group WHERE groupname='$ug_name'");
		if(!$this->db->num_rows($this->query))
		{
			$sql = "INSERT INTO " .UC_DBTABLEPRE."member_group(groupname,groupdesc,member_count,prms,order_id) 
					values('$ug_name','$ug_desc','$ug_cout','$ug_prms','$ug_order')";
			return $this->db->query($sql);
		}
	}
	
	function delete_ug($ug_id) {
		$this->if_eff_id($ug_id);
		return $this->db->query("DELETE FROM ".UC_DBTABLEPRE."member_group WHERE id IN ($ug_id)");
	}

	function edit_ug($group_id) {
		$this->if_eff_id($group_id);
		return $this->db->fetch_array($this->query);
	}
	
	function update_ug($ug_id,$ug_name,$ug_desc,$ug_order)
	{
			$this->if_eff_id($ug_id);
			$sql = "UPDATE ".UC_DBTABLEPRE."member_group SET groupname='$ug_name',groupdesc='$ug_desc',order_id='$ug_order' WHERE id=$ug_id";
			return $this->db->query($sql);
	}
	
	/**
	 * 
	 * 检测用户组id是否有效
	 * @param $ug_id 用户组id
	 */
	function if_eff_id($ug_id)
	{
		$this->query =  $this->db->query("SELECT * FROM ".UC_DBTABLEPRE."member_group WHERE id IN ($ug_id)");
		if( !$this->db->num_rows($this->query) ){return 'invalid';}
	}
}
?>