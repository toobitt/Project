<?php
class gatheraccess extends InitFrm {
	
	public function update($data,$id)
	
	{
	    /* if(!$data||$id||!is_array($data)){
			echo 'id or data null';
	    }  */
		$sql='update '.DB_PREFIX.'gather set ';
		foreach ($data as $key => $value) {
			$sql .= $key.'='.$value;
		}
		$sql .= ' where id in' .'('. "$id".')';
		$this->db->query($sql);
		return TRUE;   
	} 
}
?>