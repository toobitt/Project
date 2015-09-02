<?php 

require 'global.php';

function get_page_name($cond = '') 
{
	return $cond ? 'news_list' : 'news';	
}
$tpl_vars = array(
	'page_name' => get_page_name($_INPUT['sort'])
);
output_tpl($tpl_vars['page_name'], $tpl_vars);

?>