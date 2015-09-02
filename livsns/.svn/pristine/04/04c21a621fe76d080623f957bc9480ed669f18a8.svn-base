<?php 
function unserialize_template_record($data)
{
	foreach(array('video_preview', 'index_pic', 'material') as $key)
	{
		$data[$key] = ($tmp=unserialize($data[$key])) ? $tmp : array();
	}
	return $data;
}
?>